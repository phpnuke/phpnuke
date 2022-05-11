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

function feedbak_admin_top_menus($admin_top_menus)
{
	global $admin_file, $this_module_name;
	$admin_top_menus['recives']['children'][] = array(
		"id" => 'feedbacks', 
		"parent_id" => 'recives', 
		"title" => _CONTACT_US, 
		"url" => "".$admin_file.".php?op=feedbacks", 
		"icon" => ""
	);
	return $admin_top_menus;
}
$hooks->add_filter("admin_top_menus", 'feedbak_admin_top_menus', 10);

function feedback_alert_messages($alerts_messages)
{
	$alerts_messages = array_merge($alerts_messages, array(
		"new_feedbacks" => array(
			"prefix"	=> "feedbacks",
			"by"		=> "fid",
			"table"		=> FEEDBACKS_TABLE,
			"where"		=> "replys = '' OR replys IS NULL",
			"color"		=> "blue",
			"text"		=> _NEW_FEEDBACKS_NUMBER,
		)
	));
	
	return $alerts_messages;
}
$hooks->add_filter("admin_alert_messages", 'feedback_alert_messages', 10);

function feedback_boxes_parts($nuke_modules_boxes_parts)
{
	$nuke_modules_boxes_parts['Feedback'] = array(
		"index" => _INDEX,
	);
	
	return $nuke_modules_boxes_parts;
}

$hooks->add_filter("modules_boxes_parts", "feedback_boxes_parts", 10);

function feedback_assets($theme_setup)
{
	global $nuke_configs, $hooks;
	
	$json_feedback_data = $hooks->functions_vars['feedback_assets']['json_feedback_data'];
	$module_name = $hooks->functions_vars['feedback_assets']['module_name'];
	$feedback_configs = $hooks->functions_vars['feedback_assets']['feedback_configs'];
	
	$theme_setup = array_merge_recursive($theme_setup, array(
		"defer_js" => array(
			"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>",
			"<script>var feedback_data=JSON.parse('".$json_feedback_data."');</script>",
			"<script src=\"".$nuke_configs['nukecdnurl']."modules/".$module_name."/includes/feedback.js\"></script>",
			"".((isset($feedback_configs['map_active']) && $feedback_configs['map_active'] == 1) ? "<script src=\"https://maps.googleapis.com/maps/api/js?callback=phpnukeMap&key=".$feedback_configs['google_api']."\"></script>":"").""
		)
	));
	return $theme_setup;
}

function feedback_breadcrumb($breadcrumbs, $block_global_contents)
{
	$breadcrumbs['feedback'] = array(
		"name" => _CONTACT_US,
		"link" => LinkToGT("index.php?modname=Feedback"),
		"itemtype" => "WebPage"
	);
	return $breadcrumbs;
}
?>