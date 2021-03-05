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

if (!defined('ADMIN_FILE')) {
	die ("Access Denied");
}

global $admin_file;

$module_name = dirname(__FILE__);
$module_name = explode("/", str_replace("\\","/", $module_name));
array_pop($module_name);
$module_name = end($module_name);

$module_menus[] = adminmenu("".$admin_file.".php?op=credits", ""._CREDITS_ADMIN."", "modules/$module_name/includes/credits.png");
?>