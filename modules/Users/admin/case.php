<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('ADMIN_FILE'))
{
	die ("Access Denied");
}

$module_name = dirname(__FILE__);
$module_name = explode("/", str_replace("\\","/", $module_name));
array_pop($module_name);
$module_name = end($module_name);

switch($op)
{
    case "users":
	case "users_admin":
	case "users_groups":
	case "users_groups_admin":
	case "users_fields_admin":
	case "users_resend_code":
	case "users_config":
    include("modules/$module_name/admin/index.php");
    break;
}

?>