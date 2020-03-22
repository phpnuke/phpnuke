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