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
* Migration stage 2: Initial data changes to the database
*/
class m2_initial_data extends \phpbb\db\migration\migration
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
		return array('\phpbb\boardannouncements\migrations\v10x\m1_initial_schema');
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
			// Add our config table settings
			array('config.add', array('board_announcements_enable', 0)),
			array('config.add', array('board_announcements_guests', 0)),

			// Add our config_text table settings
			array('config_text.add', array('announcement_text', '')),
			array('config_text.add', array('announcement_uid', '')),
			array('config_text.add', array('announcement_bitfield', '')),
			array('config_text.add', array('announcement_options', OPTION_FLAG_BBCODE + OPTION_FLAG_SMILIES + OPTION_FLAG_LINKS)),
			array('config_text.add', array('announcement_bgcolor', '')),
			array('config_text.add', array('announcement_timestamp', '')),
		);
	}
}
