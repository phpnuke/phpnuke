<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \boardtools\quickreply\functions\listener_helper */
	protected $helper;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth                                 $auth
	 * @param \phpbb\config\config                             $config
	 * @param \phpbb\template\template                         $template
	 * @param \phpbb\user                                      $user
	 * @param \phpbb\request\request                           $request
	 * @param \boardtools\quickreply\functions\listener_helper $helper
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request $request, \boardtools\quickreply\functions\listener_helper $helper)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->helper = $helper;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		// We set lower priority for some events for the case if another extension wants to use those events.
		return array(
			'core.user_setup'                    => 'load_language_on_setup',
			'core.viewtopic_modify_post_row'     => 'viewtopic_modify_post_row',
			'core.viewtopic_modify_page_title'   => 'viewtopic_modify_data',
			'core.modify_submit_post_data'       => 'change_subject_when_sending',
			'core.posting_modify_template_vars'  => 'delete_re',
			'core.submit_post_end'               => 'on_submit',
			'rxu.postsmerging.posts_merging_end' => 'on_submit',
			'core.search_get_posts_data'         => 'hide_posts_subjects_in_searchresults_sql',
			'core.search_modify_tpl_ary'         => 'hide_posts_subjects_in_searchresults_tpl',
		);
	}

	/**
	 * Load language file for notifications.
	 *
	 * @param object $event The event object
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'boardtools/quickreply',
			'lang_set' => 'quickreply_notification',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Add decoded message text if full quotes are enabled.
	 *
	 * @param object $event The event object
	 */
	public function viewtopic_modify_post_row($event)
	{
		$topic_data = $event['topic_data'];
		$post_row = $event['post_row'];
		$row = $event['row'];
		if ($this->config['qr_quickquote'])
		{
			$row = $event['row'];
			$post_row = $event['post_row'];
			$post_row = array_merge($post_row, array(
				'QR_POST_TIME' => $row['post_time'],
			));
			$event['post_row'] = $post_row;
		}

		if ($this->config['qr_full_quote'] && $this->auth->acl_get('f_reply', $topic_data['forum_id']))
		{
			$decoded_message = censor_text($row['post_text']);
			decode_message($decoded_message, $row['bbcode_uid']);

			$decoded_message = bbcode_nl2br($decoded_message);
			$post_row = array_merge($post_row, array(
				'DECODED_MESSAGE' => $decoded_message,
			));
		}

		if ($this->helper->plugins_helper->quote_button_disabled($topic_data, $row['post_id']))
		{
			$post_row = array_merge($post_row, array(
				'U_QUOTE' => false
			));
		}
		$event['post_row'] = $post_row;
	}

	/**
	 * Show bbcodes and smilies in the quickreply.
	 *
	 * @param object $event The event object
	 */
	public function viewtopic_modify_data($event)
	{
		$forum_id = $event['forum_id'];
		$topic_data = $event['topic_data'];
		$post_list = $event['post_list'];
		$topic_id = $topic_data['topic_id'];

		$this->user->add_lang_ext('boardtools/quickreply', 'quickreply');

		// Mark post notifications read for this user in this topic
		$this->helper->notifications_helper->mark_qr_notifications_read($post_list);

		if ($this->helper->qr_is_enabled($forum_id, $topic_data))
		{
			if (!$this->user->data['is_registered'])
			{
				$this->helper->enable_qr_for_guests($forum_id, $topic_data);
			}

			$this->helper->form_helper->prepare_qr_form($forum_id, $topic_id);

			$this->helper->assign_template_variables_for_qr($forum_id);

			$add_re = ($this->config['qr_enable_re']) ? 'Re: ' : '';
			$this->template->assign_var('SUBJECT', $this->request->variable('subject', $add_re . censor_text($topic_data['topic_title']), true));
		}

		$this->template->assign_vars(array(
			'QR_HIDE_POSTS_SUBJECT' => !$this->config['qr_show_subjects'],
		));
	}

	/**
	 * Lock post subject if the user cannot change it.
	 *
	 * @param object $event The event object
	 */
	public function change_subject_when_sending($event)
	{
		$data = $event['data'];

		if ($this->helper->plugins_helper->post_id_in_array($data) && $this->helper->plugins_helper->cannot_change_subject($data['forum_id'], $event['mode'], $data['topic_first_post_id'], $data['post_id']))
		{
			$re = ($this->config['qr_enable_re'] == 0) ? '' : 'Re: ';
			$subject = $re . $data['topic_title'];
			$event['subject'] = $subject;
		}
	}

	/**
	 * Delete Re:, lock post subject.
	 * Ctrl+Enter submit - template variables in the full editor.
	 *
	 * @param object $event The event object
	 */
	public function delete_re($event)
	{
		$forum_id = $event['forum_id'];
		$page_data = $event['page_data'];
		$post_data = $event['post_data'];

		// Delete Re:
		if ($this->config['qr_enable_re'] == 0)
		{
			$page_data['SUBJECT'] = preg_replace('/^Re: /', '', $page_data['SUBJECT']);
		}

		// Disable full quote if the user does not have the required permission.
		if ($this->helper->plugins_helper->check_full_quote_permission($event))
		{
			$post_data['post_text'] = $page_data['MESSAGE'] = '';
		}

		// Whether the user can change post subject or not
		if ($this->helper->plugins_helper->cannot_change_subject($forum_id, $event['mode'], $post_data['topic_first_post_id'], $event['post_id']))
		{
			$this->template->assign_vars(array(
				'S_QR_NOT_CHANGE_SUBJECT' => true,
				'QR_HIDE_SUBJECT_BOX'     => $this->config['qr_hide_subject_box'],
			));
		};

		// Whether we need to suppress full quotes in topic review
		$page_data = array_merge($page_data, array(
			'S_QR_FULL_QUOTE_ALLOWED' => $this->auth->acl_get('f_qr_full_quote', $forum_id),
			'S_QR_LAST_QUOTE'         => $this->config['qr_last_quote'],
		));

		$event['post_data'] = $post_data;
		$event['page_data'] = $page_data;

		$this->user->add_lang_ext('boardtools/quickreply', 'quickreply');
	}

	/**
	 * Send notifications.
	 *
	 * @param object $event The event object
	 */
	public function on_submit($event)
	{
		$this->helper->notifications_helper->add_qr_notifications($event);
	}

	/**
	 * Hide posts subjects in search results.
	 *
	 * @param object $event The event object
	 */
	public function hide_posts_subjects_in_searchresults_sql($event)
	{
		if (!$this->config['qr_show_subjects_in_search'])
		{
			$sql_array = $event['sql_array'];
			if (!preg_match('/t\.topic_first_post_id/', $sql_array['SELECT'], $matches_t))
			{
				$sql_array['SELECT'] .= ', t.topic_first_post_id';
			}
			if (!preg_match('/p\.post_id/', $sql_array['SELECT'], $matches_p))
			{
				$sql_array['SELECT'] .= ', p.post_id';
			}
			$event['sql_array'] = $sql_array;

			$this->template->assign_vars(array(
				'QR_HIDE_POSTS_SUBJECT' => true
			));
		}
	}

	/**
	 * Hide posts subjects in search results.
	 *
	 * @param object $event The event object
	 */
	public function hide_posts_subjects_in_searchresults_tpl($event)
	{
		if (!$this->config['qr_show_subjects_in_search'])
		{
			$row = $event['row'];
			$tpl_ary = $event['tpl_ary'];
			$show_results = $event['show_results'];

			if ($show_results == 'posts')
			{
				$tpl_ary = array_merge($tpl_ary, array(
					'QR_NOT_FIRST_POST' => ($row['topic_first_post_id'] == $row['post_id']) ? false : true,
				));

				$event['tpl_ary'] = $tpl_ary;
			}
		}
	}
}
