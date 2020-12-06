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

$nuke_modules_boxes_parts[$this_module_name] = array(
	"index" => "_INDEX",
	"advanced" => "_ADVANCED",
);

//$other_admin_configs['statistics'] = array("title" => _VIEWERS_STATISTICS, "function" => "statistics_config", "God" => false);

?>