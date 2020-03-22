<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\functions;

class notifications_helper
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var string */
	protected $type_notification;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth            $auth
	 * @param \phpbb\user                 $user
	 * @param \phpbb\notification\manager $notification_manager
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\user $user, \phpbb\notification\manager $notification_manager)
	{
		$this->auth = $auth;
		$this->user = $user;
		$this->notification_manager = $notification_manager;
		$this->type_notification = 'boardtools.quickreply.notification.type.quicknick';
	}

	/**
	 * Marks QuickReply notifications read in the specified posts
	 * for current user.
	 *
	 * @param array $post_list Array of post IDs
	 */
	public function mark_qr_notifications_read($post_list)
	{
		$this->notification_manager->mark_notifications_read($this->type_notification, $post_list, $this->user->data['user_id']);
	}

	/**
	 * Adds or updates QuickReply notifications.
	 *
	 * @param object $event The event object
	 */
	public function add_qr_notifications($event)
	{
		$data = $event['data'];

		if ($this->auth->acl_get('f_noapprove', $data['forum_id']))
		{
			$mode = $event['mode'];
			$subject = $event['subject'];
			$username = $event['username'];

			$notification_data = $this->get_notification_data($data, $subject, $username);
			$this->set_notification_data($mode, $notification_data);
		}
	}

	/**
	 * Returns array of data for notifications
	 *
	 * @param array  $data     Current notifications' data object
	 * @param string $subject  Post's subject
	 * @param string $username Username of the post author
	 * @return array
	 */
	private function get_notification_data($data, $subject, $username)
	{
		return array_merge($data, array(
			'topic_title'   => (isset($data['topic_title'])) ? $data['topic_title'] : $subject,
			'post_username' => $username,
			'post_text'     => $data['message'],
			'post_time'     => time(),
			'post_subject'  => $subject,
		));
	}

	/**
	 * Handles (adds/updates) notifications
	 *
	 * @param string $mode              Current posting mode
	 * @param array  $notification_data Array with notifications' data
	 */
	private function set_notification_data($mode, $notification_data)
	{
		if ($this->case_to_add($mode))
		{
			$this->notification_manager->add_notifications($this->type_notification, $notification_data);
		}
		else if ($this->case_to_update($mode))
		{
			$this->notification_manager->update_notifications($this->type_notification, $notification_data);
		}
	}

	/**
	 * Returns whether we need to add a notification
	 *
	 * @param string $mode Current posting mode
	 * @return bool
	 */
	private function case_to_add($mode)
	{
		return in_array($mode, array('post', 'reply', 'quote'));
	}

	/**
	 * Returns whether we need to update an existing notification
	 *
	 * @param string $mode Current posting mode
	 * @return bool
	 */
	private function case_to_update($mode)
	{
		return in_array($mode, array('edit_topic', 'edit_first_post', 'edit', 'edit_last_post'));
	}
}
