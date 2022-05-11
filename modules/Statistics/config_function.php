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

function progressbar_theme($key, $hits, $progressbar_class="info", $progress_bar = false)
{
	$col_sm1 = ($progress_bar) ? 5:12;
	$contents = "
	<div class=\"col-sm-$col_sm1 text-right\" style=\"margin:5px 0;\">$key<span class=\"pull-left badge\">$hits</span></div>";
	if($progress_bar)
	{
	$contents .= "<div class=\"col-sm-7\">
		<div class=\"progress\">
			<div class=\"progress-bar progress-bar-$progressbar_class progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"$hits\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:$hits%\">$hits%</div>
		</div>
	</div>";
	}
	return $contents;
}

function parse_statistics_link($matches)
{
	$output = array();
	if(isset($matches[1]))
		parse_str($matches[1], $output);
	
	if(isset($output['op']) && $output['op'] == "advanced_statistics")
		$output['op'] = "all";
	if(isset($output['year']))
		unset($output['op']);
		
	$output = implode("/", $output);
	return "statistics/".(($output == "") ? "":$output."/");
}

function parse_adv_statistics_link($matches)
{
	$output = "index.php?modname=Statistics";
	if(isset($matches[1]))
	{
		$matches[1] = explode("/", $matches[1]);
		if(isset($matches[1][0]) && $matches[1][0] != '')
			$output .= "&op=advanced_statistics&year=".$matches[1][0];
		if(isset($matches[1][1]))
			$output .= "&month=".$matches[1][1];
		if(isset($matches[1][2]))
			$output .= "&day=".$matches[1][2];
	}
	return $output;
}

function statistics_boxes_parts($nuke_modules_boxes_parts)
{
	$nuke_modules_boxes_parts['Statistics'] = array(
		"index" => _INDEX,
		"advanced" => _ADVANCED,
	);
	
	return $nuke_modules_boxes_parts;
}

$hooks->add_filter("modules_boxes_parts", "statistics_boxes_parts", 10);

function statistics_assets($theme_setup)
{
	global $nuke_configs, $hooks;
	
	$main_chart_data = $hooks->functions_vars['statistics_assets']['main_chart_data'];
	$browsers_chart_data = $hooks->functions_vars['statistics_assets']['browsers_chart_data'];
	$module_name = $hooks->functions_vars['statistics_assets']['module_name'];
	$os_chart_data = $hooks->functions_vars['statistics_assets']['os_chart_data'];
	
	$default_css[] = "<link rel=\"stylesheet\" href=\"".$nuke_configs['nukecdnurl']."modules/$module_name/includes/style.css\">";
		
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/amcharts/amcharts.js\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/amcharts/serial.js\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/amcharts/pie.js\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/amcharts/themes/light.js\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/amcharts/plugins/responsive/responsive.min.js\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."modules/$module_name/includes/charts.js\"></script>";
	$defer_js[] = "<script>
	$(document).ready(function(){
		var main_chart_data = $main_chart_data;
		var browsers_chart_data = $browsers_chart_data;
		var os_chart_data = $os_chart_data;
		main_chart('serial_chart', main_chart_data);
		pie_chart('browsers_chart', browsers_chart_data, 'browser', 'value');
		pie_chart('os_chart', os_chart_data, 'os', 'value');
	});
	</script>";
	$theme_setup = array_merge_recursive($theme_setup, array(
		"default_css" => $default_css,
		"defer_js" => $defer_js
	));
	return $theme_setup;
}

function adv_statistics_assets($theme_setup)
{
	global $nuke_configs, $hooks;
	
	$module_name = $hooks->functions_vars['adv_statistics_assets']['module_name'];
	$script_array = $hooks->functions_vars['adv_statistics_assets']['script_array'];
	
	$default_css[] = "<link rel=\"stylesheet\" href=\"".$nuke_configs['nukecdnurl']."modules/$module_name/includes/style.css\">";
			
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/amcharts/amcharts.js\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/amcharts/serial.js\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/amcharts/pie.js\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/amcharts/themes/light.js\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/amcharts/plugins/responsive/responsive.min.js\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."modules/$module_name/includes/charts.js\"></script>";
	$defer_js[] = $script_array;
		
	$theme_setup = array_merge_recursive($theme_setup, array(
		"default_css" => $default_css,
		"defer_js" => $defer_js
	));
	return $theme_setup;
}

function statistics_breadcrumb($breadcrumbs, $block_global_contents)
{
	$breadcrumbs['statistics'] = array(
		"name" => _VIEWERS_STATISTICS,
		"link" => LinkToGT("index.php?modname=Statistics"),
		"itemtype" => "WebPage"
	);
	return $breadcrumbs;
}

function adv_statistics_breadcrumb($breadcrumbs, $block_global_contents)
{
	global $hooks;
	$breadcrumb_data = $hooks->functions_vars['adv_statistics_breadcrumb']['breadcrumb_data'];
	
	$breadcrumbs['statistics'] = array(
		"name" => _VIEWERS_STATISTICS,
		"link" => LinkToGT("index.php?modname=Statistics"),
		"itemtype" => "WebPage"
	);
	
	foreach($breadcrumb_data as $key => $breadcrumb)
	{
		$breadcrumbs["statistics_$key"] = array(
			"name" => $breadcrumb[0],
			"link" => $breadcrumb[1],
			"itemtype" => "WebPage"
		);
	}
	
	return $breadcrumbs;
}

/*
function statistics_settings($other_admin_configs){
	//$other_admin_configs['statistics'] = array("title" => "_VIEWERS_STATISTICS", "function" => "statistics_config", "God" => false);
	return $other_admin_configs;
}

$hooks->add_filter("other_admin_configs", "statistics_settings", 10);
*/

?>