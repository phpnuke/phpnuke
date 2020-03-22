<?php
/**
 *
 * @package SCSS Compiler
 * @author Arty (Vjacheslav Trushkin) cyberalien@gmail.com
 * @copyright (c) 2015 artodia.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace arty\scss\acp;

/**
* @package module_install
*/
class scss_info
{
	function module()
	{
		return array(
			'filename'	=> 'arty\scss\acp\scss_module',
			'title'		=> 'ACP_SASS_MODULE',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'main'	=> array('title' => 'ACP_RECOMPILE_THEMES', 'auth'	=> 'acl_a_styles', 'cat'	=> array('ACP_STYLE_MANAGEMENT')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
