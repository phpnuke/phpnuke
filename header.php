<?php

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (stristr(htmlentities($_SERVER['PHP_SELF']), "header.php"))
{
	Header("Location: index.php");
	die();
}

define('NUKE_HEADER', true);
require_once("mainfile.php");
	
$html_output = '';

if(defined("ADMIN_FILE"))
{
	$has_micrometa = (isset($has_micrometa)) ? $has_micrometa:false;
	$html_output .= @adminheader($has_micrometa);
}
else
{
	global $nuke_configs, $modname, $error, $REQUESTURL, $pn_Cookies;

	//// in this code we save current page that user is in it.
	if($modname != "Users" && !defined("_ERROR_PAGE"))
	{
		$pn_Cookies->set("currentpage",$nuke_configs['REQUESTURL'],1800);
	}
	if(!isset($error))
		include("includes/counter.php");
		
	$html_output .= (defined("SPECIAL_HOME_PAGE") && function_exists("website_index")) ? website_index():themeheader();
}

?>