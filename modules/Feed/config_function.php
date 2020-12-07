<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('CONFIG_FUNCTIONS_FILE')) {
	die("You can't access this file directly...");
}
$this_module_name = basename(dirname(__FILE__));

function parse_rss_link($matches)
{
	if(isset($matches[1]) && $matches[1] == 'back')
		return 'feedback/';
	if(isset($matches[1]))
		parse_str($matches[1], $output);
	$return = '';
	if(isset($output['module_link']))
	{
		$output['module_link'] = rtrim($output['module_link'], "/");
		$return .= $output['module_link']."/";
	}
	
	$return .= 'feed/';
	
	if(isset($output['mode']))
		$return .= "".$output['mode']."/";
	
	return $return;
}

?>