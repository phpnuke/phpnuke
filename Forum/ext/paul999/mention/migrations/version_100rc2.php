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

class version_100rc2 extends \phpbb\db\migration\migration
{

	static public function depends_on()
	{
		return array(
			'\paul999\mention\migrations\version_001',
			'\paul999\mention\migrations\update_bbcode',
		);
	}
}
