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


require_once("mainfile.php");
global $db, $admin_file, $main_module, $redirect, $error;
$mobile_browser = '0';


$modname = (isset($modname)) ? filter($modname, "nohtml"):"";
$file = (isset($file)) ? filter($file, "nohtml"):"index";
$op = (isset($op)) ? filter($op, "nohtml"):"main";
$_REQUEST['modname'] = (isset($_REQUEST['modname'])) ? filter($_REQUEST['modname'], "nohtml"):"";
$_REQUEST['file'] = (isset($_REQUEST['file'])) ? filter($_REQUEST['file'], "nohtml"):"index";
$_REQUEST['op'] = (isset($_REQUEST['op'])) ? $_REQUEST['op']:"main";
$modname = trim($modname);
$file = trim($file);
$op = trim($op);

///////////////////////////// Nuke mobile Version - Zero-F
if($nuke_configs['mobile_mode'] == 1 && $modname != 'AvantGo')
{
	$user_agent = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT']:"";
	if(@preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i',
	strtolower($user_agent)))
	{
		$mobile_browser++;
	}

	if(isset($_SERVER['HTTP_ACCEPT']) && (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or
	((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']))))
	{
		$mobile_browser++;
	}

	$mobile_ua = strtolower(substr($user_agent,0,4));
	$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda','xda-'
	);

	if(in_array($mobile_ua,$mobile_agents))
	{
		$mobile_browser++;
	}
	if (isset($_SERVER['ALL_HTTP']) && strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0)
	{
		$mobile_browser++;
	}
	if (strpos(strtolower($user_agent),'windows')>0)
	{
		$mobile_browser=0;
	}

	if($mobile_browser > 0 && is_active("AvantGo"))
	{
		header("Location: ".LinkToGT("index.php?modname=AvantGo")."");
		die();
	}
}
///////////////////////////// Nuke mobile Version - Zero-F

if(isset($error) && filter($error,"nohtml") != '')
{
	$error = adv_filter($error, array('sanitize_string'),array('alpha_dash','required'), true);
	
	if($error[0] != 'error')
	{
		$error_function ="die_".$error[1];
		if(function_exists($error_function))
			die($error_function());
		else
			die_error($error[1]);
	}
}

if (isset($session_destroy))
{
	$pn_Sessions->destroy();
	header("location: ".LinkToGT("index.php")."");
}

if (isset($url) AND isset($_GET['url']) AND is_admin($admin))
{
	$url = urldecode($url);
	die("<meta http-equiv=\"refresh\" content=\"0; url=$url\">");
}

if(isset($redirect) && filter($redirect,"nohtml") != '' AND is_admin($admin))
{
	$redirect = adv_filter($redirect, array(''),array('valid_url'));
	
	if($redirect[0] != 'error')
	{
		die("<meta http-equiv=\"refresh\" content=\"0; url=$redirect\">");
	}
}

/*if (isset($op) AND ($op == "ad_click") AND isset($bid))
{
	$bid = intval($bid);
	$clickurl = filter($nuke_banners_cacheData[$bid]['clickurl'], "nohtml");

	$banners = $db->table(BANNER_TABLE)->idName('bid')->find($bid);
	$banners['clicks'] = $banners['clicks']+1;
	$banners->save();
	
	update_points(21,'');
	cache_system('nuke_banners');
	header("Location: ".addslashes($clickurl));
	die();
}*/

$hooks->add_filter("html_output", "pn_admin_bar", 10);

if(($main_module == "" && $modname == '') || $modname != $_REQUEST['modname'])
{
	include("header.php");
	$html_output .= "<p align=\"center\">"._NOMODULE_EXISTS."</p>";
	include("footer.php");
}
elseif($main_module != '' && (trim($REQUESTURL,"/") == '' || in_array($REQUESTURL, array("index.php","index.html","index.htm"))))// is in home
{
	$modname = $main_module;
	define('INDEX_FILE', true);
	define('HOME_FILE', true);
}

$modpath = '';

if (!isset($file) OR $file != $_REQUEST['file'])
	$file = "index";	
if (!isset($op) OR $op != $_REQUEST['op'])
	$op = "main";
	
parse_old_links(false);

if (stripos_clone($modname,"..") || (isset($file) && stripos_clone($file,"..")) || stripos_clone($op,".."))
{
	die("You are so cool...");
}
elseif (defined("HOME_FILE") && isset($nuke_configs['website_index_theme']) && $nuke_configs['website_index_theme'] == 1 && (file_exists("themes/".$nuke_configs['ThemeSel']."/website_index.php") || function_exists("website_index")))
{
	define("SPECIAL_HOME_PAGE", true);
	include("header.php");
	include("footer.php");
}
else
{	
	define('MODULE_FILE', true);
	if (file_exists("themes/".$nuke_configs['ThemeSel']."/modules/$modname/".$file.".php")) {
		$modpath = "themes/".$nuke_configs['ThemeSel']."/";
	}
	
	$modpath .= "modules/$modname/".$file.".php";

	$modname = addslashes(trim($modname));
	$modstring = strtolower($_SERVER['QUERY_STRING']);
	if (stripos_clone($modname, "..") OR ((stripos_clone($modstring,"&file=nickpage") || stripos_clone($modstring,"&user="))))
		header("Location: ".LinkTOGT("index.php")."");
	
	$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
	
	$nuke_modules_cacheData_by_title = phpnuke_array_change_key($nuke_modules_cacheData, "mid", "title");
	unset($nuke_modules_cacheData);
	if(isset($nuke_modules_cacheData_by_title[$modname]))
	{
		$mod_active			= intval($nuke_modules_cacheData_by_title[$modname]['active']);
		$mod_permissions	= ($nuke_modules_cacheData_by_title[$modname]['mod_permissions'] != "") ? explode(",", $nuke_modules_cacheData_by_title[$modname]['mod_permissions']):array(0);
		
		if (($mod_active == 1) OR ($mod_active == 0 AND is_admin()) OR defined("INDEX_FILE"))
		{
			if(($mod_active == 0 AND !is_admin()) && !defined("INDEX_FILE"))
			{
				include("header.php");
				$html_output .= OpenTable();
				$html_output .= "<div class=\"text-center\"><span style=\"color:#FF0000;\">"._NOTE_MODULE_DISABLED."</span></div>";
				$html_output .= CloseTable();
				include("footer.php");
			}
		
			$allow_to_view = false;
			$disallow_message = "";
			
			$permission_result = phpnuke_permissions_check($mod_permissions);
			
			$allow_to_view = $permission_result[0];
			$disallow_message = $permission_result[1];
			unset($permission_result);
			
			if($allow_to_view || $nuke_modules_cacheData_by_title[$modname]['main_module'] == 1 || defined("INDEX_FILE"))
			{
				unset($nuke_modules_cacheData_by_title);
				if (file_exists($modpath))
					include($modpath);
				else
					simple_output("<br><div class=\"text-center\">"._SORRY_PAGE_NOTFOUND."</div><br>");
			}
			else
				simple_output("<div class=\"text-center\">$disallow_message<br><br>"._GOBACK."</div>");
		}
		else
			simple_output("<div class=\"text-center\">"._MODULENOTACTIVE."<br><br>"._GOBACK."</div>");
	}
	else
		simple_output("<br><div class=\"text-center\">"._SORRY_PAGE_NOTFOUND."</div><br>");
}

die_error("404");
?>