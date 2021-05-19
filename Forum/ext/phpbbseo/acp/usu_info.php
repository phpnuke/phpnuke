<?php
/**
*
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2017 www.phpBB-SEO.ir
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\usu\acp;

class main_info
{
	function module()
	{
		return [
			'filename'	=> '\phpbbseo\usu\acp\usu',
			'title'		=> 'ACP_CAT_PHPBB_SEO',
			'version'	=> '2.0.0-b2',
			'modes'		=> [
				'settings'	=> [
					'title'	=> 'ACP_PHPBB_SEO_CLASS',
					'auth'	=> 'ext_phpbbseo/usu && acl_a_board',
					'cat'	=> ['ACP_MOD_REWRITE']
				],
				'forum_url'	=> [
					'title'	=> 'ACP_FORUM_URL',
					'auth'	=> 'ext_phpbbseo/usu && acl_a_board',
					'cat'	=> ['ACP_MOD_REWRITE']
				],
				'server'	=> [
					'title'	=> 'ACP_REWRITE_CONF',
					'auth'	=> 'ext_phpbbseo/usu && acl_a_board',
					'cat'	=> ['ACP_MOD_REWRITE']
				],
				'sync_url'	=> [
					'title'	=> 'ACP_SYNC_URL',
					'auth'	=> 'ext_phpbbseo/usu && acl_a_board',
					'cat'	=> ['ACP_MOD_REWRITE']
				],
				'extended'	=> [
					'title'	=> 'ACP_SEO_EXTENDED',
					'auth'	=> 'ext_phpbbseo/usu && acl_a_board',
					'cat'	=> ['ACP_MOD_REWRITE']
				],
			]];
	}
}
