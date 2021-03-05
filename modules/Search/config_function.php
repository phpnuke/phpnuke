<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('CONFIG_FUNCTIONS_FILE')) {
	die("You can't access this file directly...");
}
$this_module_name = basename(dirname(__FILE__));

function parse_search_link($matches)
{
	if(isset($matches[1]))
		parse_str($matches[1], $output);
	
	$return = 'search/';
	
	if(isset($output['search_query']))
		$return .= urlencode($output['search_query'])."/";
	
	if(isset($output['search_module']))
		$return .= "".$output['search_module']."/";
	
	if(isset($output['search_category']))
		$return .= "cat-".$output['search_category']."/";
	
	if(isset($output['search_author']))
		$return .= "author-".$output['search_author']."/";
	
	if(isset($output['search_time']))
		$return .= "date-".$output['search_time']."/";
	
	if(isset($output['page']))
		$return .= "page/".$output['page']."/";
	
	return $return;
}

function search_boxes_parts($nuke_modules_boxes_parts)
{
	$nuke_modules_boxes_parts['Search'] = array(
		"index" => _INDEX,
	);
	
	return $nuke_modules_boxes_parts;
}

$hooks->add_filter("modules_boxes_parts", "search_boxes_parts", 10);

?>