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

use paul999\mention\core\bbcodes_installer;
use phpbb\db\migration\container_aware_migration;

class update_bbcode extends container_aware_migration
{
	static public function depends_on()
	{
		return array('\paul999\mention\migrations\add_bbcode');
	}

	public function update_data()
	{
		return array(
			array('custom', array(array($this, 'update_bbcodes'))),
		);
	}
	public function update_bbcodes()
	{
		$install = new bbcodes_installer($this->db, $this->container->get('request'), $this->container->get('user'), $this->phpbb_root_path, $this->php_ext);
		$install->install_bbcodes([
			'mention' => [
				'display_on_posting'	=> false,
				'bbcode_match'		    => '[mention]{TEXT}[/mention]',
				'bbcode_tpl'		    => '<span class="mention">@{TEXT}</span>',
			],
		]);

	}
}
