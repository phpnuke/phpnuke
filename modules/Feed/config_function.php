<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* https://www.phpnuke.ir                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

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