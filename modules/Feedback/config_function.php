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

?>