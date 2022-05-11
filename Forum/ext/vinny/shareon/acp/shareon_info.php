<?php
/**
*
* Share On extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Vinny <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\shareon\acp;

class shareon_info
{
	function module()
	{
		return array(
			'filename'	=> '\vinny\shareon\acp\shareon_module',
			'title'		=> 'SO_ACP',
			'version'	=> '2.0.1',
			'modes'		=> array(
				'settings'	=> array('title' => 'SO_CONFIG', 'auth'	=> 'ext_vinny/shareon && acl_a_group', 'cat' => array('SHARE_ON_MOD')),
			),
		);
	}
}
