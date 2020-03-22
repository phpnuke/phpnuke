<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class ajax_helper
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\content_visibility */
	protected $phpbb_content_visibility;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\context */
	protected $template_context;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $php_ext;

	/** @var ajax_preview_helper */
	public $ajax_preview_helper;

	/** @var bool */
	public $qr_insert;

	/** @var bool */
	public $qr_first;

	/** @var bool */
	public $qr_merged = false;

	/** @var array */
	private static $qr_fields = array();

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth                  $auth
	 * @param \phpbb\config\config              $config
	 * @param \phpbb\template\template          $template
	 * @param \phpbb\user                       $user
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param \phpbb\content_visibility         $phpbb_content_visibility
	 * @param \phpbb\request\request            $request
	 * @param \phpbb\template\context           $template_context
	 * @param string                            $phpbb_root_path Root path
	 * @param string                            $php_ext
	 * @param ajax_preview_helper               $ajax_preview_helper
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\content_visibility $phpbb_content_visibility, \phpbb\request\request $request, \phpbb\template\context $template_context, $phpbb_root_path, $php_ext, ajax_preview_helper $ajax_preview_helper)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
		$this->phpbb_content_visibility = $phpbb_content_visibility;
		$this->request = $request;
		$this->template_context = $template_context;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->ajax_preview_helper = $ajax_preview_helper;
		$this->qr_insert = false;
		$this->qr_first = false;
	}

	/**
	 * Checks whether quick reply form was submitted using Ajax
	 *
	 * @return bool
	 */
	public function qr_is_ajax_submit()
	{
		return $this->config['qr_ajax_submit'] && $this->request->is_ajax() && $this->request->is_set_post('qr');
	}

	/**
	 * Checks whether this is a QuickReply Ajax request
	 *
	 * @return bool
	 */
	public function qr_is_ajax()
	{
		return $this->request->is_ajax() && $this->request->is_set('qr_request');
	}

	/**
	 * Prepares and outputs the page for Ajax QuickReply request
	 *
	 * @param string $page_title      The title of the page
	 * @param int    $current_post_id ID of the current last post
	 * @param int    $forum_id        Forum ID
	 */
	public function ajax_response($page_title, $current_post_id, $forum_id)
	{
		// Fix issues if the inserted post is not the first.
		if ($this->qr_insert && !$this->qr_first)
		{
			$this->template->alter_block_array('postrow', array(
				'S_FIRST_ROW' => false,
			), false, 'change');
		}
		$this->template->assign_vars(array(
			'S_QUICKREPLY_REQUEST' => true,
			'S_QR_FULL_QUOTE'      => $this->config['qr_full_quote'],
		));
		$this->template->append_var('QR_HIDDEN_FIELDS', build_hidden_fields(array(
			'qr'             => 1,
			'qr_cur_post_id' => (int) $current_post_id
		)));
		$this->output_ajax_response($page_title, $forum_id);
	}

	/**
	 * Outputs the page for Ajax QuickReply request
	 *
	 * @param string $page_title The title of the page
	 * @param int    $forum_id   Forum ID
	 */
	public function output_ajax_response($page_title, $forum_id)
	{
		page_header($page_title, false, $forum_id);
		page_footer(false, false, false);
		self::send_json(array(
			'status' => 'success',
			'result' => $this->template->assign_display('@boardtools_quickreply/quickreply_template.html', '', true),
			'insert' => $this->qr_insert,
			'merged' => $this->qr_merged,
		));
	}

	/**
	 * Sends Ajax submission status response
	 *
	 * @param object $event The event object
	 */
	public function ajax_submit($event)
	{
		$data = $event['data'];
		if ($this->is_not_approved($data))
		{
			// No approve
			$this->send_approval_notify();
		}

		$qr_cur_post_id = $this->request->variable('qr_cur_post_id', 0);
		$url_hash = strpos($event['url'], '#');
		$result_url = ($url_hash !== false) ? substr($event['url'], 0, $url_hash) : $event['url'];

		self::send_json(array(
			'status' => 'success',
			'url'    => $result_url,
			'merged' => ($qr_cur_post_id === $data['post_id']),
		));
	}

	/**
	 * Check approve
	 *
	 * @param array $data
	 * @return bool
	 */
	public function is_not_approved($data)
	{
		return (
				!$this->auth->acl_get('f_noapprove', $data['forum_id'])
				&& empty($data['force_approved_state'])
			) || (
				isset($data['force_approved_state'])
				&& !$data['force_approved_state']
			);
	}

	/**
	 * Checks whether we need to send new form token
	 *
	 * @param array $error     Array with error strings
	 * @param array $post_data Array with post data
	 * @return bool
	 */
	public function check_form_token(&$error, $post_data)
	{
		if (!sizeof($error))
		{
			return false;
		}

		$form_error_key = array_search($this->user->lang['FORM_INVALID'], $error);
		if ($form_error_key !== false)
		{
			add_form_key('posting');

			$rootref = &$this->template_context->get_root_ref();
			$qr_hidden_fields = array(
				'qr'                => 1,
				'qr_cur_post_id'    => (int) $this->request->variable('qr_cur_post_id', 0),
				'topic_cur_post_id' => (int) $post_data['topic_last_post_id'],
				'lastclick'         => (int) time(),
				'topic_id'          => (int) $post_data['topic_id'],
				'forum_id'          => (int) $post_data['forum_id'],
			);
			self::$qr_fields = array(
				'qr_fields' => $rootref['S_FORM_TOKEN'] . "\n" . build_hidden_fields($qr_hidden_fields)
			);
			unset($error[$form_error_key]);

			return true;
		}

		return false;
	}

	/**
	 * Sends ajax response in case of errors
	 *
	 * @param array $error Array with error strings
	 */
	public function check_errors($error)
	{
		if (sizeof($error))
		{
			$this->output_errors($error);
		}
	}

	/**
	 * Sends new form token as the only response
	 */
	public function send_form_token()
	{
		self::send_json(array(
			'status' => 'outdated_form',
		));
	}

	/**
	 * Sends the occurred errors with additional information (if provided)
	 *
	 * @param array $error  Array with error strings
	 * @param array $params Array with additional information (optional)
	 */
	public function output_errors($error, $params = array())
	{
		self::send_json(array_merge(array(
			'error'         => true,
			'MESSAGE_TITLE' => $this->user->lang['INFORMATION'],
			'MESSAGE_TEXT'  => implode('<br />', $error),
		), $params));
	}

	/**
	 * Checks whether the current post has been updated by merge
	 *
	 * @return bool
	 */
	public function check_post_merge()
	{
		$current_post = $this->request->variable('qr_cur_post_id', 0);

		$sql = 'SELECT post_time
				FROM ' . POSTS_TABLE . '
				WHERE post_id = ' . (int) $current_post;
		$result = $this->db->sql_query_limit($sql, 1);
		$post_time = (int) $this->db->sql_fetchfield('post_time');
		$this->db->sql_freeresult($result);

		$lastclick = $this->request->variable('lastclick', time());

		return $this->qr_merged = ($post_time > $lastclick);
	}

	/**
	 * Sends the URL to the next post
	 *
	 * @param int $forum_id  Forum ID
	 * @param int $topic_id  Topic ID
	 * @param int $lastclick Time of the last click
	 */
	public function send_next_post_url($forum_id, $topic_id, $lastclick)
	{
		$sql = 'SELECT post_id
				FROM ' . POSTS_TABLE . '
				WHERE topic_id = ' . (int) $topic_id . '
					AND ' . $this->phpbb_content_visibility->get_visibility_sql('post', (int) $forum_id, '') . '
					AND post_time > ' . (int) $lastclick . '
				ORDER BY post_time ASC';
		$result = $this->db->sql_query_limit($sql, 1);
		$post_id_next = (int) $this->db->sql_fetchfield('post_id');
		$this->db->sql_freeresult($result);

		$url_next_post = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", "f=$forum_id&amp;t=$topic_id&amp;p=$post_id_next"); // #p$post_id_next
		$current_post = $this->request->variable('qr_cur_post_id', 0);

		self::send_json(array(
			'status' => 'new_posts',
			'error'  => true,
			'merged' => ($post_id_next === $current_post),
			'url'    => $url_next_post,
		));
	}

	/**
	 * Sends post approval notice
	 */
	public function send_approval_notify()
	{
		self::send_json(array(
			'status'        => 'no_approve',
			'MESSAGE_TITLE' => $this->user->lang['INFORMATION'],
			'MESSAGE_TEXT'  => $this->user->lang['POST_STORED_MOD'] . (($this->user->data['user_id'] == ANONYMOUS) ? '' : ' ' . $this->user->lang['POST_APPROVAL_NOTIFY']),
			'REFRESH_DATA'  => array(
				'time' => 10,
			)
		));
	}

	/**
	 * Sends a JSON response
	 *
	 * @param array $data Array with JSON data
	 */
	public static function send_json($data)
	{
		$json_response = new \phpbb\json_response;
		$json_response->send(array_merge(self::$qr_fields, $data));
	}

	/**
	 * Returns Ajax related template variables
	 *
	 * @param array $topic_data Array of topic data
	 * @return array
	 */
	public function template_variables_for_ajax($topic_data)
	{
		$ajax_submit = $this->config['qr_ajax_submit'] && $topic_data['qr_ajax_submit'];
		return array(
			'L_FULL_EDITOR'        => ($ajax_submit) ? $this->user->lang['PREVIEW'] : $this->user->lang['FULL_EDITOR'],
			'S_QR_AJAX_SUBMIT'     => $ajax_submit,
			'S_QR_FORM_TYPE'       => $topic_data['qr_form_type'],
			'S_QR_FIX_EMPTY_FORM'  => $this->user->data['qr_fix_empty_form'],
			'S_QR_ENABLE_WARNING'  => $this->user->data['qr_enable_warning'],
			//
			'S_QR_AJAX_PAGINATION' => $this->config['qr_ajax_pagination'] && $this->user->data['ajax_pagination'],
			//
			'S_QR_ENABLE_SCROLL'   => $this->user->data['qr_enable_scroll'],
			'S_QR_SCROLL_INTERVAL' => $this->config['qr_scroll_time'],
			'S_QR_SOFT_SCROLL'     => $this->config['qr_scroll_time'] && $this->user->data['qr_soft_scroll']
		);
	}

	/**
	 * Checks whether Ajax callback function needs
	 * to insert the post instead of updating the page
	 *
	 * @param int   $current_post Current post ID
	 * @param array $post_list    Array of fetched post IDs
	 * @return bool
	 */
	public function no_refresh($current_post, $post_list)
	{
		return $this->request->is_ajax() && $this->request->variable('qr_no_refresh', 0) && in_array($current_post, $post_list);
	}
}
