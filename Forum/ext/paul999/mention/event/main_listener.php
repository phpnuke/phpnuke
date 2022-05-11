<?php
/**
 *
 * phpBB mentions. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2016, paul999, https://www.phpbbextensions.io
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\mention\event;

/**
 * @ignore
 */
use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\db\driver\driver;
use phpbb\db\driver\driver_interface;
use phpbb\notification\manager;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * phpBB mentions Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	/**
	 * @var string
	 */
	private $regex = '#\[mention\]<\/s>(.*?)<e>\[\/mention\]#';

	/**
	 * @var helper
	 */
	protected $helper;

	/**
	 * @var template
	 */
	protected $template;

	/**
	 * @var driver
	 */
	private $db;

	/**
	 * @var manager
	 */
	private $notification_manager;

	/**
	 * @var user
	 */
	private $user;

	/**
	 * @var array
	 */
	private $mention_data;

	/**
	 * @var auth
	 */
	private $auth;
	/**
	 * @var config
	 */
	private $config;
	/**
	 * @var string
	 */
	private $php_ext;

	/**
	 * Constructor
	 *
	 * @param helper $helper Controller helper object
	 * @param template $template Template object
	 * @param driver_interface $db
	 * @param manager $notification_manager
	 * @param user $user
	 * @param auth $auth
	 * @param config $config
	 * @param string $php_ext
	 * @internal param viewonline_helper $viewonline_helper
	 */
	public function __construct(helper $helper, template $template, driver_interface $db, manager $notification_manager, user $user, auth $auth, config $config, $php_ext)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->db = $db;
		$this->notification_manager = $notification_manager;
		$this->user = $user;
		$this->auth = $auth;
		$this->config = $config;
		$this->php_ext = $php_ext;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.submit_post_end'                  => 'submit_post',
			'core.modify_submit_post_data'          => 'modify_submit_post',
			'core.approve_posts_after'              => 'handle_post_approval',
			'core.permissions'                      => 'add_permission',
			'core.user_setup'			            => 'load_language_on_setup',
			'core.modify_posting_auth'              => 'posting',
			'core.viewtopic_modify_page_title'      => 'viewtopic',
			'core.text_formatter_s9e_parse_before'  => 'permissions',
			'core.posting_modify_template_vars'     => 'remove_mention_in_quote',
			'core.markread_before'                  => 'mark_read',
			'rxu.postsmerging.posts_merging_end'	=> 'submit_post',
			'core.acp_board_config_edit_add'        => 'acp_board_settings',
			'core.validate_config_variable'         => 'validate_config',
			'core.page_header'                      => 'page_header',
		];
	}

	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_can_mention'] = array('lang' => 'ACL_U_CAN_MENTION', 'cat' => 'misc');
		$event['permissions'] = $permissions;
	}

	/**
	 * Load common language files during user setup
	 *
	 * @param object $event The event object
	 * @access public
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'paul999/mention',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * @param array $event
	 */
	public function viewtopic($event)
	{
		$s_quick_reply = false;
		if ($this->user->data['is_registered'] && $this->config['allow_quick_reply'] && ($event['topic_data']['forum_flags'] & FORUM_FLAG_QUICK_REPLY) && $this->auth->acl_get('f_reply', $event['forum_id']))
		{
			// Quick reply enabled forum
			$s_quick_reply = (($event['topic_data']['forum_status'] == ITEM_UNLOCKED && $event['topic_data']['topic_status'] == ITEM_UNLOCKED) || $this->auth->acl_get('m_edit', $event['forum_id'])) ? true : false;
		}
		if ($s_quick_reply)
		{
			$this->template->assign_vars([
				'UA_AJAX_MENTION_URL'    => $this->helper->route('paul999_mention_controller'),
			]);
		}
	}

	/**
	 * Mark notifications as read when topics are read,
	 * or when user uses the mark as read function.
	 *
	 * @param array $event
	 */
	public function mark_read($event)
	{
		switch ($event['mode'])
		{
			case 'all':
				$this->mark_all_read($event['post_time']);
			break;

			case 'topics':
				$this->mark_forum_read($event['forum_id'], $event['post_time']);
			break;

			case 'topic':
				$this->mark_topic_read($event['topic_id'], $event['post_time']);
			break;
		}
	}

	/**
	 * Add settings to the ACP page.
	 *
	 * @param \phpbb\event\data $event The event object
	 */
	public function acp_board_settings($event)
	{
		if ($event['mode'] === 'post')
		{
			$this->user->add_lang('acp_common', false, false, 'paul999/mention');
			$display_vars = $event['display_vars'];
			$sm_config_vars = [
				'simple_mention_minlength' => [
					'lang'		=> 'MENTION_LENGTH',
					'validate'	=> 'int',
					'type'		=> 'number:1:9999',
					'explain'	=> true,
				],
				'simple_mention_color'  => [
					'lang'      => 'MENTION_COLOR',
					'validate'  => 'mention_hex',
					'type'      => 'text:6:6',
					'explain'   => true,
				],
			];
			$display_vars['vars'] = phpbb_insert_config_array($display_vars['vars'], $sm_config_vars, array('after' => 'allow_quick_reply'));
			$event['display_vars'] = $display_vars;
		}
	}

	/**
	 * Validate the simple mention hex color
	 * @param \phpbb\event\data $event Event data
	 */
	public function validate_config($event)
	{
		if ($event['config_definition']['validate'] === 'mention_hex')
		{
			$value = $event['cfg_array'][$event['config_name']];
			if (!preg_match("/([a-f0-9]{3}){1,2}\b/i", $value))
			{
				$error = $event['error'];
				$error[] = sprintf($this->user->lang('MENTION_COLOR_INVALID'), $value);
				$event['error'] = $error;
			}
		}
	}

	/**
	 * Set the mention color on pages.
	 * @param \phpbb\event\data $event
	 */
	public function page_header($event)
	{
		$this->template->assign_vars([
			'MENTION_COLOR' => $this->config['simple_mention_color'],
		]);
	}

	/**
	 * Mark all notifications as read
	 * @param int $post_time
	 */
	private function mark_all_read($post_time)
	{
		$this->notification_manager->mark_notifications([
			'paul999.mention.notification.type.mention',
		], false, $this->user->data['user_id'], $post_time);
	}

	/**
	 * Mark notifications for a topic_id as read
	 * @param int|array $topic_id
	 * @param int $post_time
	 */
	private function mark_topic_read($topic_id, $post_time)
	{
		$this->notification_manager->mark_notifications_by_parent(array(
			'paul999.mention.notification.type.mention',
		), $topic_id, $this->user->data['user_id'], $post_time);
	}

	/**
	 * Mark notifications for forum_id as read
	 * @param int|array $forum_id
	 * @param int $post_time
	 */
	private function mark_forum_read($forum_id, $post_time)
	{
		// Mark all topics in forums read
		if (!is_array($forum_id))
		{
			$forum_id = [$forum_id];
		}
		else
		{
			$forum_id = array_unique($forum_id);
		}

		// Mark all post/quote notifications read for this user in this forum
		// Pretty bad, as this query is already done in mark_read, but
		// because we have no access to that data in the event we need to run it
		// again :(
		$topic_ids = array();
		$sql = 'SELECT topic_id
			FROM ' . TOPICS_TABLE . '
			WHERE ' . $this->db->sql_in_set('forum_id', $forum_id);
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$topic_ids[] = $row['topic_id'];
		}
		$this->db->sql_freeresult($result);

		$this->mark_topic_read($topic_ids, $post_time);
	}

	/**
	 * @param array $event
	 */
	public function posting($event)
	{
		if ($this->auth->acl_get('u_can_mention'))
		{
			$this->template->assign_vars([
			   'UA_AJAX_MENTION_URL'    => $this->helper->route('paul999_mention_controller'),
			]);
		}
	}

	/**
	 * @param array $event
	 */
	public function permissions($event)
	{
		$disable = false;
		if (!$this->auth->acl_get('u_can_mention'))
		{
			$disable = true;
		}

		if ($this->user->page['page_name'] != 'posting.' . $this->php_ext)
		{
			// Only enable mention BBCode on posting page.
			$disable = true;
		}

		if ($disable)
		{
			$event['parser']->disable_bbcode('mention');
		}
	}

	/**
	 * Remove mention BBCode from quote.
	 * @param array $event
	 */
	public function remove_mention_in_quote($event)
	{
		if ($event['submit'] || $event['preview'] || $event['refresh'] || $event['mode'] != 'quote' || !isset($event['page_data']) || !isset($event['page_data']['MESSAGE']))
		{
			return;
		}
		$page_data = $event['page_data'];
		$page_data['MESSAGE'] = preg_replace('#\[mention\](.*?)\[\/mention\]#uis', '@\\1', $page_data['MESSAGE']);
		$event['page_data'] = $page_data;
	}

	/**
	 * @param array $event
	 */
	public function modify_submit_post($event)
	{
		$handle = ['post', 'reply', 'quote'];

		if (!in_array($event['mode'], $handle) || !$this->auth->acl_get('u_can_mention'))
		{
			return;
		}

		$this->parse_message($event['data']['message'], $event['data']['forum_id']);
	}

	public function handle_post_approval($event)
	{
		if ($event['action'] != 'approve')
		{
			return;
		}

		$posts = [];
		foreach ($event['post_info'] as $post_id => $post_data)
		{
			$posts[] = $post_id;
		}

		$sql = 'SELECT p.poster_id, p.post_text, p.post_id, t.topic_id, t.forum_id, t.topic_title
				  FROM ' . POSTS_TABLE . ' p, ' . TOPICS_TABLE . ' t
				  WHERE t.topic_id = p.topic_id
						AND ' . $this->db->sql_in_set('p.post_id', $posts);
		$result = $this->db->sql_query($sql);

		$data = [];
		$users = [];
		$userdata = [];
		while ($row = $this->db->sql_fetchrow($result))
		{
			$data[] = $row;
			$users[] = (int) $row['poster_id'];
		}
		$this->db->sql_freeresult($result);

		$sql = 'SELECT username, user_id, user_permissions, user_type
				  FROM ' . USERS_TABLE . '
				  WHERE ' . $this->db->sql_in_set('user_id', $users);
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$userdata[$row['user_id']] = $row;
		}
		$this->db->sql_freeresult($result);

		foreach ($data as $row)
		{
			$this->mention_data = [];

			$local_auth = new auth();
			$local_auth->acl($userdata[$row['poster_id']]);

			if (!$local_auth->acl_get('u_can_mention'))
			{
				continue;
			}
			$this->parse_message($row['post_text'], $row['forum_id'], false);

			if (count($this->mention_data))
			{
				$insert = [
					'post_id'       => $row['post_id'],
					'username'      => $userdata[$row['poster_id']]['username'],
					'user_id'       => $row['poster_id'],
					'topic_id'      => $row['topic_id'],
					'topic_title'   => $row['topic_title'],
				];
				$this->send_notification($insert);
			}

		}

	}

	public function submit_post($event)
	{
		if ($event['post_visibility'] == ITEM_APPROVED && isset($this->mention_data))
		{
			$data = $event['data'];
			$data['username'] = $this->user->data['username'];
			$data['user_id'] = $this->user->data['user_id'];
			$this->send_notification($data);
		}
	}

	/**
	 * @param string $message
	 * @param int $forum_id
	 * @param bool $current
	 */
	private function parse_message($message, $forum_id, $current = true)
	{
		$matches = [];
		if (preg_match_all($this->regex, $message, $matches, PREG_OFFSET_CAPTURE) === 0)
		{
			return;
		}
		$this->mention_data = [];
		$mentions = [];
		$data = [];

		for ($i = 0; $i < count($matches[1]); $i++)
		{
			$data[] = utf8_clean_string($matches[1][$i][0]);
		}

		$sql = 'SELECT user_id, username, user_permissions, user_type
				FROM ' . USERS_TABLE . '
				WHERE ' . $this->db->sql_in_set('username_clean', $data);
		$result = $this->db->sql_query($sql);

		$data = [];

		while ($row = $this->db->sql_fetchrow($result))
		{
			if (!in_array($row['user_id'], $mentions))
			{
				$mentions[] = (int) $row['user_id'];
				$data[] = $row;
			}
		}
		$this->db->sql_freeresult($result);

		if (count($data))
		{
			foreach ($data as $index => $row)
			{
				if ($current && $this->user->data['user_id'] == $row['user_id'])
				{
					continue; // Do not send notification to current user.
				}
				$auth = new auth();
				$auth->acl($row);
				if ($auth->acl_get('f_read', $forum_id))
				{
					// Only do the mention when the user is able to read the forum
					$this->mention_data[] = (int) $row['user_id'];
				}
			}
		}
	}

	/**
	 * @param $data
	 */
	private function send_notification($data)
	{
		$this->notification_manager->add_notifications('paul999.mention.notification.type.mention', [
			'user_ids' => $this->mention_data,
			'notification_id' => $data['post_id'],
			'username' => $data['username'],
			'poster_id' => $data['user_id'],
			'post_id' => $data['post_id'],
			'topic_id' => $data['topic_id'],
			'topic_title' => $data['topic_title'],
		],
		[
			'user_ids' => $this->mention_data,
		]);
	}
}
