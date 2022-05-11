<?php
/**
*
* Board Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\boardannouncements\migrations\v10x;

/**
* Migration stage 5: Enable announcements for new users registered by default
*/
class m5_enable_announcements_for_new_users extends \phpbb\db\migration\migration
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
			'\phpbb\boardannouncements\migrations\v10x\m4_announcements_dismiss_config',
		);
	}

	/**
	* Change default value for the board_announcements_status column from 0 to 1.
	*
	* @return array Array of table data
	* @access public
	*/
	public function update_schema()
	{
		return array(
			'change_columns'	=> array(
				$this->table_prefix . 'users'			=> array(
					'board_announcements_status'	=> array('BOOL', 1),
				),
			),
		);
	}
}
