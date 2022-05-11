<?php
/**
*
* Board Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace phpbb\boardannouncements\acp;

class board_announcements_info
{
	public function module()
	{
		return array(
			'filename'	=> '\phpbb\boardannouncements\acp\board_announcements_module',
			'title'		=> 'ACP_BOARD_ANNOUNCEMENTS',
			'modes'		=> array(
				'settings'	=> array(
					'title' => 'ACP_BOARD_ANNOUNCEMENTS_SETTINGS',
					'auth' => 'ext_phpbb/boardannouncements && acl_a_board',
					'cat' => array('ACP_BOARD_ANNOUNCEMENTS')
				),
			),
		);
	}
}
