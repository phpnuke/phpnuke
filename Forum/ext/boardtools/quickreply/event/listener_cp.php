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
class listener_cp implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \boardtools\quickreply\functions\cp_helper */
	protected $cp_helper;

	/**
	 * Constructor
	 *
	 * @param \phpbb\template\template                   $template
	 * @param \phpbb\user                                $user
	 * @param \boardtools\quickreply\functions\cp_helper $cp_helper
	 */
	public function __construct(\phpbb\template\template $template, \phpbb\user $user, \boardtools\quickreply\functions\cp_helper $cp_helper)
	{
		$this->template = $template;
		$this->user = $user;
		$this->cp_helper = $cp_helper;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		// We set lower priority for some events for the case if another extension wants to use those events.
		return array(
			'core.ucp_prefs_view_data'                  => 'ucp_prefs_get_data',
			'core.ucp_prefs_view_update_data'           => 'ucp_prefs_set_data',
			'core.acp_users_prefs_modify_data'          => 'acp_prefs_get_data',
			'core.acp_users_prefs_modify_template_data' => 'acp_prefs_template_data',
			'core.acp_users_prefs_modify_sql'           => 'ucp_prefs_set_data', // For the ACP.
			'core.acp_manage_forums_request_data'       => 'acp_manage_forums_get_data',
			'core.acp_manage_forums_display_form'       => 'acp_manage_forums_template_data',
			'core.acp_manage_forums_initialise_data'    => 'acp_manage_forums_initialise_data',
			'core.permissions'                          => 'add_permission',
		);
	}

	/**
	 * Get user's options and display them in UCP Prefs View page
	 *
	 * @param object $event The event object
	 */
	public function ucp_prefs_get_data($event)
	{
		$data = $event['data'];

		// Request the user option vars and add them to the data array
		$data = array_merge($data, $this->cp_helper->qr_get_user_prefs_data($this->user->data));

		// Output the data vars to the template
		$this->user->add_lang_ext('boardtools/quickreply', 'quickreply_ucp');
		$this->template->assign_vars($this->cp_helper->qr_user_prefs_data($data));

		$event['data'] = $data;
	}

	/**
	 * Add user options' state into the sql_array
	 *
	 * @param object $event The event object
	 */
	public function ucp_prefs_set_data($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], $this->cp_helper->qr_set_user_prefs_data($event['data']));
	}

	/**
	 * Get user's options and display them in ACP Prefs View page
	 *
	 * @param object $event The event object
	 */
	public function acp_prefs_get_data($event)
	{
		$data = $event['data'];
		$user_row = $event['user_row'];
		$data = array_merge($data, $this->cp_helper->qr_get_user_prefs_data($user_row));
		$event['data'] = $data;
	}

	/**
	 * Assign template data in the ACP
	 *
	 * @param object $event The event object
	 */
	public function acp_prefs_template_data($event)
	{
		$this->user->add_lang_ext('boardtools/quickreply', 'quickreply_ucp');
		$data = $event['data'];
		$user_prefs_data = $event['user_prefs_data'];
		$user_prefs_data = array_merge($user_prefs_data, $this->cp_helper->qr_user_prefs_data($data));
		$event['user_prefs_data'] = $user_prefs_data;
	}

	/**
	 * Get forum's options and display them in ACP Forums page
	 *
	 * @param object $event The event object
	 */
	public function acp_manage_forums_get_data($event)
	{
		$forum_data = $event['forum_data'];
		$forum_data = array_merge($forum_data, $this->cp_helper->qr_get_forums_data());
		$event['forum_data'] = $forum_data;
	}

	/**
	 * Assign template data in the ACP
	 *
	 * @param object $event The event object
	 */
	public function acp_manage_forums_template_data($event)
	{
		$this->user->add_lang_ext('boardtools/quickreply', 'info_acp_quickreply');
		$template_data = $event['template_data'];
		$forum_data = $event['forum_data'];
		$template_data = array_merge($template_data, $this->cp_helper->qr_forums_data($forum_data));
		$event['template_data'] = $template_data;
	}

	/**
	 * Initialise forum data
	 *
	 * @param object $event The event object
	 */
	public function acp_manage_forums_initialise_data($event)
	{
		$forum_data = $event['forum_data'];
		$forum_data = array_merge($forum_data, $this->cp_helper->qr_init_forums_data($forum_data));
		$event['forum_data'] = $forum_data;
	}

	/**
	 * Add permissions
	 *
	 * @param object $event The event object
	 */
	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['a_quickreply'] = array('lang' => 'ACL_A_QUICKREPLY', 'cat' => 'misc');
		$permissions['f_qr_change_subject'] = array('lang' => 'ACL_F_QR_CHANGE_SUBJECT', 'cat' => 'post');
		$permissions['f_qr_full_quote'] = array('lang' => 'ACL_F_QR_FULL_QUOTE', 'cat' => 'post');
		$event['permissions'] = $permissions;
	}
}
