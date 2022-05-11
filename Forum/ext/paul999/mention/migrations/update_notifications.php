<?php
/**
 *
 * phpBB mentions. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2016, paul999, https://www.phpbbextensions.io
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace paul999\mention\migrations;

class update_notifications extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array('\paul999\mention\migrations\version_100RC2');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'update_mention_notifications'))),
		);
	}

	public function update_mention_notifications()
	{
		// get the mention notification type id
		$sql = 'SELECT notification_type_id
			FROM ' . NOTIFICATION_TYPES_TABLE . "
			WHERE notification_type_name = 'paul999.mention.notification.type.mention'";
		$this->db->sql_query($sql);
		$result = $this->db->sql_query_limit($sql, 1);
		$notification_type_id = (int) $this->db->sql_fetchfield('notification_type_id');
		$this->db->sql_freeresult($result);

		// get all mention notifications
		$sql = 'SELECT notification_id, notification_data
			FROM ' . NOTIFICATIONS_TABLE . '
			WHERE notification_type_id = ' . (int) $notification_type_id;
		$this->db->sql_query($sql);
		$result = $this->db->sql_query($sql);
		$rowset = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		if (count($rowset))
		{
			$this->db->sql_transaction('begin');

			foreach ($rowset as $row)
			{
				// get notification data
				$notification_data = unserialize($row['notification_data']);

				// skip if notification already has a topic title
				if (isset($notification_data['topic_title']))
				{
					continue;
				}

				// get the topic title
				$sql = 'SELECT topic_title
					FROM ' . TOPICS_TABLE . '
					WHERE topic_id = ' . (int) $notification_data['topic_id'];
				$this->db->sql_query($sql);
				$result = $this->db->sql_query_limit($sql, 1);
				$notification_data['topic_title'] = $this->db->sql_fetchfield('topic_title');
				$this->db->sql_freeresult($result);

				// update the notification data
				$sql = 'UPDATE ' . NOTIFICATIONS_TABLE . "
					SET notification_data = '" . $this->db->sql_escape(serialize($notification_data)) . "'
					WHERE notification_id = " . $row['notification_id'];
				$this->db->sql_query($sql);
			}

			$this->db->sql_transaction('commit');
		}
	}
}
