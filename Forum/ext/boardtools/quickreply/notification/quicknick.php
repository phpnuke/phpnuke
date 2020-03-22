<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace boardtools\quickreply\notification;

class quicknick extends \phpbb\notification\type\quote
{
	/**
	 * Get notification type name
	 *
	 * @return string
	 */
	public function get_type()
	{
		return 'boardtools.quickreply.notification.type.quicknick';
	}

	/**
	 * Language key used to output the text
	 *
	 * @var string
	 */
	protected $language_key = 'NOTIFICATION_QUICKNICK';

	/**
	 * Notification option data (for outputting to the user)
	 *
	 * @var bool|array False if the service should use it's default data
	 *                    Array of data (including keys 'id', 'lang', and 'group')
	 */
	public static $notification_option = array(
		'lang'  => 'NOTIFICATION_TYPE_QUICKNICK',
		'group' => 'NOTIFICATION_GROUP_POSTING',
	);

	/**
	 * Is available
	 */
	public function is_available()
	{
		return true;
	}

	/**
	 * Get a list of mentioned users
	 *
	 * @param  string $xml Parsed text
	 * @return string[] List of users
	 */
	private function get_mentioned_users($xml)
	{
		$usernames = array();
		if (!preg_match('/<REF[ >]/', $xml))
		{
			return $usernames;
		}

		$dom = new \DOMDocument;
		$dom->loadXML($xml);
		$xpath = new \DOMXPath($dom);
		foreach ($xpath->query('//REF') as $username)
		{
			preg_match('#^\[ref(.*?)\](.+)\[\/ref\]$#ui', $username->textContent, $matches);
			$usernames[] = $matches[2];
		}

		return $usernames;
	}

	/**
	 * Find the users who want to receive notifications
	 *
	 * @param array $post    Data from submit_post
	 * @param array $options Options for finding users for notification
	 *
	 * @return array
	 */
	public function find_users_for_notification($post, $options = array())
	{
		$options = array_merge(array(
			'ignore_users' => array(),
		), $options);

		$usernames = $this->get_mentioned_users($post['post_text']);

		if (empty($usernames))
		{
			return array();
		}

		$usernames = array_unique($usernames);

		$usernames = array_map('utf8_clean_string', $usernames);

		$users = $this->get_ids_of_usernames($usernames, $post['poster_id']);

		return $this->get_authorised_recipients($users, $post['forum_id'], $options, true);
	}

	/**
	 * Get an array of users' IDs from an array of their usernames
	 *
	 * @param array $usernames Array of usernames
	 * @param int   $poster_id User ID of current post author (that should not be notified)
	 * @return int[]
	 */
	protected function get_ids_of_usernames($usernames, $poster_id)
	{
		$users = array();

		$sql = 'SELECT user_id
			FROM ' . USERS_TABLE . '
			WHERE ' . $this->db->sql_in_set('username_clean', $usernames) . '
				AND user_id <> ' . (int) $poster_id;
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$users[] = (int) $row['user_id'];
		}
		$this->db->sql_freeresult($result);

		return $users;
	}

	/**
	 * Get email template
	 *
	 * @return string|bool
	 */
	public function get_email_template()
	{
		return '@boardtools_quickreply/quicknick';
	}
}
