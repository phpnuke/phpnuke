<?php
/**
*
* Board Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\boardannouncements\migrations\v10x;

/**
* Migration stage 3: Initial module
*/
class m3_initial_module extends \phpbb\db\migration\migration
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
		return array('\phpbb\boardannouncements\migrations\v10x\m2_initial_data');
	}

	/**
	* Add or update data in the database
	*
	* @return array Array of table data
	* @access public
	*/
	public function update_data()
	{
		return array(
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_BOARD_ANNOUNCEMENTS')),
			array('module.add', array(
				'acp', 'ACP_BOARD_ANNOUNCEMENTS', array(
					'module_basename'	=> '\phpbb\boardannouncements\acp\board_announcements_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
