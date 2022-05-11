<?php

/**
*
* @package phpBB3 SEO Sitemap
* @copyright (c) 2014 www.phpbb-work.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace shredder\sitemap\acp;

class sitemap_info
{
	function module()
	{
		return array(
			'filename'	=> '\shredder\sitemap\acp\sitemap_module',
			'title'		=> 'SEOMAP',
			'modes'		=> array(
				'settings'		=> array('title' => 'SEOMAP_SETTINGS', 'auth' => 'ext_shredder/sitemap && acl_a_board', 'cat' => array('SEOMAP')),
			),
		);
	}
}