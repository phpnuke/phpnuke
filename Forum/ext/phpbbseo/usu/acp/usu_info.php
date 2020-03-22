<?php
/**
*
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2017 www.phpbb-seo.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbbseo\usu\acp;

class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\phpbbseo\usu\acp\usu',
			'title'		=> 'ACP_CAT_PHPBB_SEO',
			'version'	=> '2.0.0-b2',
			'modes'		=> array(
				'settings'	=> array(
					'title'	=> 'ACP_PHPBB_SEO_CLASS',
					'auth'	=> 'ext_phpbbseo/usu && acl_a_board',
					'cat'	=> array('ACP_MOD_REWRITE')
				),
				'forum_url'	=> array(
					'title'	=> 'ACP_FORUM_URL',
					'auth'	=> 'ext_phpbbseo/usu && acl_a_board',
					'cat'	=> array('ACP_MOD_REWRITE')
				),
				'server'	=> array(
					'title'	=> 'ACP_REWRITE_CONF',
					'auth'	=> 'ext_phpbbseo/usu && acl_a_board',
					'cat'	=> array('ACP_MOD_REWRITE')
				),
				'sync_url'	=> array(
					'title'	=> 'ACP_SYNC_URL',
					'auth'	=> 'ext_phpbbseo/usu && acl_a_board',
					'cat'	=> array('ACP_MOD_REWRITE')
				),
				'extended'	=> array(
					'title'	=> 'ACP_SEO_EXTENDED',
					'auth'	=> 'ext_phpbbseo/usu && acl_a_board',
					'cat'	=> array('ACP_MOD_REWRITE')
				),
			));
	}
}
