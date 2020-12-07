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

$nuke_modules_boxes_parts[$this_module_name] = array(
	"index" => "_INDEX",
);

?>