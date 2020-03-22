<?php
/**
 *
 * phpBB mentions. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2016, paul999, https://www.phpbbextensions.io
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\mention\notification\type;

use phpbb\config\config;
use phpbb\notification\type\base;

/**
 * phpBB mentions Notification class.
 */
class mention extends base
{
	/**
	 * @var config
	 */
	private $config;

	/**
	 * @var \phpbb\user_loader
	 */
	protected $user_loader;

	/**
	 * Set the config class
	 *
	 * @param config $config
	 *
	 */
	public function set_config(config $config)
	{
		$this->config = $config;
	}

	public function set_user_loader(\phpbb\user_loader $user_loader)
	{
		$this->user_loader = $user_loader;
	}

	/**
	 * Get notification type name
	 *
	 * @return string
	 */
	public function get_type()
	{
		return 'paul999.mention.notification.type.mention';
	}

	/**
	 * Notification option data (for outputting to the user)
	 *
	 * @var bool|array False if the service should use it's default data
	 * 					Array of data (including keys 'id', 'lang', and 'group')
	 */
	public static $notification_option = array(
		'lang'	=> 'NOTIFICATION_TYPE_MENTION',
	);

	/**
	 * Is this type available to the current user (defines whether or not it will be shown in the UCP Edit notification options)
	 *
	 * @return bool True/False whether or not this is available to the user
	 */
	public function is_available()
	{
		return true;
	}

	/**
	 * Get the id of the notification
	 *
	 * @param array $data The data for the updated rules
	 *
	 * @return int Id of the notification
	 */
	public static function get_item_id($data)
	{
		return $data['notification_id'];
	}

	/**
	 * Get the id of the parent
	 *
	 * @param array $data The data for the updated rules
	 *
	 * @return int Id of the parent
	 */
	public static function get_item_parent_id($data)
	{
		return isset($data['topic_id']) ? $data['topic_id'] : 0;
	}

	/**
	 * Find the users who want to receive notifications
	 *
	 * @param array $data The type specific data
	 * @param array $options Options for finding users for notification
	 * 		ignore_users => array of users and user types that should not receive notifications from this type because they've already been notified
	 * 						e.g.: array(2 => array(''), 3 => array('', 'email'), ...)
	 *
	 * @return array
	 */
	public function find_users_for_notification($data, $options = array())
	{
		$users = [];

		foreach ($options['user_ids'] as $key => $user)
		{
			$users[$user] = $user;
		}
		return $this->check_user_notification_options($users);
	}

	/**
	 * Users needed to query before this notification can be displayed
	 *
	 * @return array Array of user_ids
	 */
	public function users_to_query()
	{
		return array($this->notification_data['poster_id']);
	}

	/**
	 * Get the HTML formatted title of this notification
	 *
	 * @return string
	 */
	public function get_title()
	{
		return $this->language->lang('MENTION_MENTION_NOTIFICATION', $this->user_loader->get_username($this->notification_data['poster_id'], 'no_profile'), $this->notification_data['topic_title']);
	}

	/**
	 * Get the url to this item
	 *
	 * @return string URL
	 */
	public function get_url()
	{
		return append_sid($this->phpbb_root_path . 'viewtopic.' . $this->php_ext, 'p=' . $this->notification_data['post_id'] . '#p' . $this->notification_data['post_id']);
	}

	/**
	 * Get email template
	 *
	 * @return string|bool
	 */
	public function get_email_template()
	{
		return '@paul999_mention/mention_mail';
	}

	/**
	 * Get email template variables
	 *
	 * @return array
	 */
	public function get_email_template_variables()
	{
		return [
			'USERNAME'          => $this->notification_data['username'],
			'TOPIC_TITLE'		=> $this->notification_data['topic_title'],
			'U_LINK_TO_TOPIC'   => generate_board_url() . 'viewtopic.' . $this->php_ext . '?p=' . $this->notification_data['post_id'] . '#p' .$this->notification_data['post_id'],
		];
	}

	/**
	 * Get the user's avatar
	 */
	public function get_avatar()
	{
		return $this->user_loader->get_avatar($this->get_data('poster_id'), false, true);
	}

	/**
	 * Function for preparing the data for insertion in an SQL query
	 * (The service handles insertion)
	 *
	 * @param array $data The data for the updated rules
	 * @param array $pre_create_data Data from pre_create_insert_array()
	 *
	 * @return array Array of data ready to be inserted into the database
	 */
	public function create_insert_array($data, $pre_create_data = array())
	{
		$this->set_data('notification_id', $data['notification_id']);
		$this->set_data('username', $data['username']);
		$this->set_data('user_ids', $data['user_ids']);
		$this->set_data('poster_id', (int) $data['poster_id']);
		$this->set_data('post_id', (int) $data['post_id']);
		$this->set_data('topic_id', (int) $data['topic_id']);
		$this->set_data('topic_title', $data['topic_title']);

		parent::create_insert_array($data, $pre_create_data);
	}
}
