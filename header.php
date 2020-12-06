<?php

/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* https://www.phpnuke.ir                                                   */
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

if(!isset($meta_tags))
	global $meta_tags;

if(!isset($custom_theme_setup))
	global $custom_theme_setup;

if(!isset($custom_theme_setup_replace))
	global $custom_theme_setup_replace;

if(!isset($pagetitle))
	global $pagetitle;

if(defined("ADMIN_FILE"))
{
	$has_micrometa = (isset($has_micrometa)) ? $has_micrometa:false;
	$html_output .= @adminheader($pagetitle, $meta_tags, $has_micrometa);
}
else
{
	global $nuke_configs, $modname, $error, $REQUESTURL, $pn_Cookies;

	//// in this code we save current page that user is in it.
	if($modname != "Users")
	{
		$pn_Cookies->set("currentpage",$nuke_configs['REQUESTURL'],1800);
	}
	if(!isset($error))
		include("includes/counter.php");
		
	$html_output .= (defined("SPECIAL_HOME_PAGE")) ? website_index($meta_tags):themeheader($meta_tags, $custom_theme_setup, $custom_theme_setup_replace);
}

?>