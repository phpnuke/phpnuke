<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System										  */
/* ===========================										  */
/*																	  */
/* Copyright (c) 2006 by Francisco Burzi								*/
/* http://phpnuke.org												   */
/*																	  */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.	   */
/************************************************************************/

if (!defined('ADMIN_FILE')) {
	die ("Access Denied");
}
global $admin_file;

$main_menus['authors']		= adminmenu("".$admin_file.".php?op=mod_authors",		""._EDITADMINS."",		"authors.png");
$main_menus['backup']		= adminmenu("".$admin_file.".php?op=database",			""._DATABASE."",		"backup.png");
$main_menus['blocks']		= adminmenu("".$admin_file.".php?op=BlocksAdmin",		""._BLOCKS."",			"blocks.png");
$main_menus['cache']		= adminmenu("".$admin_file.".php?op=cache",				""._CACHE."",			"cache.png");
$main_menus['bookmarks']	= adminmenu("".$admin_file.".php?op=bookmarks",			""._BOOKMARK."",		"bookmark.png");
$main_menus['language']		= adminmenu("".$admin_file.".php?op=language",			""._LANGUAGE."",		"lang.png");
$main_menus['groups']		= adminmenu("".$admin_file.".php?op=points_groups",		""._POINTS."",			"groups.png");
$main_menus['media']		= adminmenu("".$admin_file.".php?op=media_browser",		""._MULTIMEDIA."",		"multimedia.png");
$main_menus['modules']		= adminmenu("".$admin_file.".php?op=modules",			""._MODULES."",			"modules.png");
$main_menus['meta_tags']	= adminmenu("".$admin_file.".php?op=seo",				""._SEO_ADMIN."",		"meta.png");
$main_menus['mtsn']			= adminmenu("".$admin_file.".php?op=mtsn_admin",		""._MTSNADMIN."",		"mtsn.png");
$main_menus['settings']		= adminmenu("".$admin_file.".php?op=settings",			""._PREFERENCES."",		"preferences.png");
$main_menus['referrers']	= adminmenu("".$admin_file.".php?op=hreferrer",			""._WHOLINKS."",		"referers.png");
$main_menus['nav_menus']	= adminmenu("".$admin_file.".php?op=nav_menus",			""._NAVS_ADMIN."",		"nav_menus.png");
$main_menus['upgrade']		= adminmenu("".$admin_file.".php?op=upgrade",			""._UPGRADE."",			"upgrade.png");

?>