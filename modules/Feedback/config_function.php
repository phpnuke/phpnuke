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

$admin_top_menus['recives']['children'][] = array(
	"id" => 'feedbacks', 
	"parent_id" => 'recives', 
	"title" => "_CONTACT_US", 
	"url" => "".$admin_file.".php?op=feedbacks", 
	"icon" => ""
);

$alerts_messages[$this_module_name] = array(
	"prefix"	=> "feedbacks",
	"by"		=> "fid",
	"table"		=> FEEDBACKS_TABLE,
	"where"		=> "replys = '' OR replys IS NULL",
	"color"		=> "blue",
	"text"		=> "_NEW_FEEDBACKS_NUMBER",
);

$nuke_modules_boxes_parts[$this_module_name] = array(
	"index" => "_INDEX",
);

?>