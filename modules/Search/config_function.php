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
	
	if(isset($output['search_query']) && isset($output['search_module']))
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

function search_form_assets($theme_setup)
{
	global $nuke_configs, $hooks;
	
	$modules_search_data = $hooks->functions_vars['search_form_assets']['modules_search_data'];
	$module = $hooks->functions_vars['search_form_assets']['module'];
	$module_name = $hooks->functions_vars['search_form_assets']['module_name'];
	$category = $hooks->functions_vars['search_form_assets']['category'];
	
	$theme_setup = array_merge_recursive($theme_setup, array(
		"defer_js" => array(
			"<script>
				var first_module = '$module';
				var selected_category = '$category';
				var search_data = ".((!empty($modules_search_data)) ? json_encode($modules_search_data):"[]").";
				var search_language = {
					all_categories : '"._ALL_CATEGORIES."'
				}
			</script>",
			"<script src=\"".$nuke_configs['nukecdnurl']."modules/$module_name/includes/search.js\"></script>"
		)
	));
	return $theme_setup;
}

function search_main_breadcrumb($breadcrumbs, $block_global_contents)
{
	global $hooks;
	$search_module = $hooks->functions_vars['search_main_breadcrumb']['search_module'];
	$search_data = $hooks->functions_vars['search_main_breadcrumb']['search_data'];
	$meta_tags = $hooks->functions_vars['search_main_breadcrumb']['meta_tags'];
	
	$breadcrumbs['search'] = array(
		"name" => _SEARCH,
		"link" => LinkToGT("index.php?modname=Search&search_module=$search_module"),
		"itemtype" => "WebPage"
	);
	if($search_data['search_query'] != '')
	{
		$breadcrumbs['search-query'] = array(
			"name" => $search_data['search_query'],
			"link" => $meta_tags['url'],
			"itemtype" => "WebPage"
		);
	}
	return $breadcrumbs;
}

?>