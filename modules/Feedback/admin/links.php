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

$module_menus[] = adminmenu("".$admin_file.".php?op=feedbacks", ""._CONTACT_US."", "feedback.png");

?>