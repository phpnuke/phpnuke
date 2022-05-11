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

class version_001 extends \phpbb\db\migration\migration
{

	static public function depends_on()
	{
		return array(
			'\paul999\mention\migrations\install_permission',
			'\paul999\mention\migrations\install_role',
			'\paul999\mention\migrations\add_bbcode',
			'\phpbb\db\migration\data\v320\v320',
		);
	}

}
