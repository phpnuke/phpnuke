<?php
/**
*
* Board Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2016 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\boardannouncements\migrations\v10x;

/**
* Migration stage 6: Update config name
*/
class m6_update_config extends \phpbb\db\migration\migration
{
	/**
	* Assign migration file dependencies for this migration
	*
	* @return array Array of migration files
	* @static
	* @access public
	*/
	static public function depends_on()
	{
		return array(
			'\phpbb\boardannouncements\migrations\v10x\m1_initial_schema',
			'\phpbb\boardannouncements\migrations\v10x\m5_enable_announcements_for_new_users',
		);
	}

	/**
	* Add board announcements data to the database.
	*
	* @return array Array of table data
	* @access public
	*/
	public function update_data()
	{
		return array(
			array('config.add', array('board_announcements_users', (int) !$this->config['board_announcements_guests'])),
			array('config.remove', array('board_announcements_guests')),
		);
	}
}
