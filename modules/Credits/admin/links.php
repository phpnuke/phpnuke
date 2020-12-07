<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

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