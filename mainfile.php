<?php
/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                             */
/* Copyright (c) 2007 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

// After doing those superglobals we can now use one
// and check if this file isnt being accessed directly
if (stristr(htmlentities($_SERVER['PHP_SELF']), "mainfile.php"))
{
    header("Location: index.php");
    exit();
}

define('NUKE_FILE', true);
define('_START_TIME', microtime(true));
// Error reporting, to be set in config.php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);

// Define the INCLUDE PATH
define('INCLUDE_PATH', 'includes');
define('ADMIN_PATH', 'admin');

//Absolute PHP-Nuke directory
define("PHPNUKE_ROOT_PATH", dirname(__FILE__));

$nuke_root = str_replace(array( '\\', '../' ),array( '/',  '' ), PHPNUKE_ROOT_PATH );

define("PHPNUKE_ROOT_MAIN_PATH", trim(str_replace($_SERVER['DOCUMENT_ROOT'], "", str_replace(array( '\\', '../' ),array( '/',  '' ), dirname($_SERVER['SCRIPT_FILENAME']) )) ,"/"));

// Absolute Phoenix Directory And Includes
define('PHOENIX_INCLUDE_DIR', $nuke_root."/".INCLUDE_PATH.'/');

// End the transaction
if(!defined('END_TRANSACTION'))
{
  define('END_TRANSACTION', 2);
}

// Get php version
$phpver = phpversion();

//// Set IRAN Time
if ($phpver >= '5.1.0')
{
	date_default_timezone_set('Asia/Tehran');
}

if(version_compare(PHP_VERSION, '5.4.0', "<"))
{
	die("<p align=\"center\">sorry. PHPNuke 8.4 MT Edition needs php 5.4.0 or above</p>");
}
/////

define("_NOWTIME", time());

// convert superglobals if php is lower then 4.1.0
if ($phpver < '4.1.0')
{
	$_GET = $HTTP_GET_VARS;
	$_POST = $HTTP_POST_VARS;
	$_SERVER = $HTTP_SERVER_VARS;
	$_FILES = $HTTP_POST_FILES;
	$_ENV = $HTTP_ENV_VARS;
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$_REQUEST = $_POST;
	} elseif($_SERVER['REQUEST_METHOD'] == "GET")
	{
		$_REQUEST = $_GET;
	}
	if(isset($HTTP_COOKIE_VARS))
	{
		$_COOKIE = $HTTP_COOKIE_VARS;
	}
	if(isset($HTTP_SESSION_VARS))
	{
		$_SESSION = $HTTP_SESSION_VARS;
	}
}

if ($phpver >= '4.0.4pl1' && isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'],'compatible'))
{
	if (extension_loaded('zlib'))
	{
    	@ob_end_clean();
    	@ob_start('ob_gzhandler');
  	}
}
elseif($phpver > '4.0' && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && !empty($_SERVER['HTTP_ACCEPT_ENCODING']))
{
  	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	{
    	if (extension_loaded('zlib'))
		{
      		$do_gzip_compress = true;
      		@ob_start(array('ob_gzhandler',5));
      		@ob_implicit_flush(0);
			if (@preg_match("MSIE", $_SERVER['HTTP_USER_AGENT']))
			{
				header('Content-Encoding: gzip');
      		}
    	}
  	}
}

$methods = array("_GET","_POST","_REQUEST","_FILES");
foreach($methods as $method)
{
	if(isset($$method))
		extract($$method);
}

if ((file_exists('install/') && file_exists('install.php')) && !defined("IN_INSTALL"))
{
	header("location: install.php");
	exit;
}

require_once(INCLUDE_PATH."/functions.php");
require_once(INCLUDE_PATH."/class.Hooks.php");
require_once("config.php");
$hooks = Hooks::getInstance();

timthumb_check();

// Include the required files
require_once(INCLUDE_PATH."/constants.php");
require_once(INCLUDE_PATH."/class.cache.php");
require_once(INCLUDE_PATH."/class.gump.php");
require_once(INCLUDE_PATH."/class.walker.php");
require_once(INCLUDE_PATH."/class.sessions.php");
require_once(INCLUDE_PATH."/class.cookies.php");
if(!defined("IN_INSTALL"))
	require_once(INCLUDE_PATH."/class.comments.php");
require_once(INCLUDE_PATH."/class.CrawlerDetect.php");
require_once(INCLUDE_PATH."/utf_tools.php");
require_once(INCLUDE_PATH."/class.ping-optimizer.php");
require_once(INCLUDE_PATH."/csrfp/libs/csrf/csrfprotector.php");
require_once(INCLUDE_PATH."/class.Database.php");

$pn_Sessions = new pn_Sessions();
$pn_Cookies = new pn_Cookies();

GUMP::add_filter("sanitize_title", 'sanitize');
$PnValidator = new GUMP();
$cache = new Cache();

check_requests();

if(!$pn_dbname && !defined("IN_INSTALL"))
{
    die("<br><br><div class=\"text-center\"><img src=images/logo.gif title=\"logo\" alt=\"phpnuke\"><br><br><b>There seems that PHP-Nuke isn't installed yet.<br>(The values in config.php file are the default ones)<br><br>You can proceed with the <a href='install.php'>web installation</a> now.</div></b>");
}

$db = Database::connect($pn_dbhost, $pn_dbuname , $pn_dbpass, $pn_dbname, $pn_dbtype, $pn_dbfetch, $pn_dbcharset);

if(empty($admin_file))
	die ("You must set a value for admin_file in config.php");
elseif(!empty($admin_file) && !file_exists($admin_file.".php"))
	die ("The admin_file you defined in config.php does not exist");

$all_post_types = array();

define("CONFIG_FUNCTIONS_FILE", true);
foreach(get_modules_list() as $modulename)
	if(file_exists("modules/$modulename/config_function.php"))
		@include("modules/$modulename/config_function.php");

//$row = $db->sql_fetchrow($db->sql_query("SET NAMES `utf8`"));

$visitor_ip = get_client_ip();

$nuke_configs = get_cache_file_contents('nuke_configs');
if(empty($nuke_configs))
{
	cache_system('nuke_configs');
	$nuke_configs = get_cache_file_contents('nuke_configs');
}

if($pn_Cookies->exists('admin'))
{
	$admin = base64_decode($pn_Cookies->get('admin'));
	$admin = addslashes($admin);
	$admin = explode(":", $admin);
}

if($pn_Cookies->exists('user'))
{
	$user = base64_decode($pn_Cookies->get('user'));
	$user = addslashes($user);
	$user = explode(":", $user);
}

check_baseurl();

$pn_Sessions->sPrefix = (isset($nuke_configs['sessions_prefix']) && $nuke_configs['sessions_prefix'] != '') ? $nuke_configs['sessions_prefix']:'pnSession_';
csrfProtector::init();

if(!$pn_Sessions->exists('nuke_authors'))
{
	define("IN_FLUSH", true);
	cache_system('nuke_authors');
}

$forum_class = "no_forum";

if(isset($nuke_configs['have_forum']) && $nuke_configs['have_forum'] == 1 && is_dir($nuke_configs['forum_path']))
{
	if(!defined("IN_INSTALL") && is_dir($nuke_configs['forum_path']."/install"))
		$nuke_configs['have_forum'] = 0;
	else
		$forum_class = $nuke_configs['forum_system'];
}

require_once(INCLUDE_PATH."/forums_classes/class."."$forum_class.php");
unset($forum_class);
	
$users_system = new users_system();

$userinfo = (isset($users_system->data) && is_array($users_system->data)) ? $users_system->data:array();

define("_PN_CSRF_TOKEN", get_form_token());

// set common configs
$nuke_configs['copyright']			= base64_decode(base64_decode(base64_decode(filter($nuke_configs['copyright']))));
$nuke_configs['phpver']				= $phpver;
$nuke_configs['mtsn_gfx_chk'] = (isset($nuke_configs['mtsn_gfx_chk']) && $nuke_configs['mtsn_gfx_chk'] != '') ? explode(",", $nuke_configs['mtsn_gfx_chk']):array();
$nuke_configs['nukecdnurl'] = (isset($nuke_configs['nukecdnurl']) && $nuke_configs['nukecdnurl'] != '') ? $nuke_configs['nukecdnurl']:$nuke_configs['nukeurl'];
$nuke_configs['pages_links'] = array(
	"1" => "{YEAR}/{MONTH}/{DAY}/{PAGEURL}/",
	"2" => "{YEAR}/{MONTH}/{PAGEURL}/",
	"3" => "{ID}/{PAGEURL}/",
	"4" => "{PAGEURL}/",
	"5" => "{CATEGORY}/{PAGEURL}/",
);

// set common configs

if($nuke_configs['display_errors'])
{
	global $op;
	if($op != "savegeneral")
		error_reporting(E_ALL);
	@ini_set('display_errors', 1);
}
else
{
	@ini_set('display_errors', 0);
}

/* define languages */
if ((isset($datetype)) && !isset($captcha))
{
	global $currentpage;
	$datetype = intval($datetype);
	if($datetype > 0)
	{
		$pn_Cookies->set("cdatetype",$datetype,(365*24*3600));
		$nuke_configs['datetype'] = $datetype;
	}
	else
		$pn_Cookies->set("cdatetype",false,-1);
	$currentpage = (isset($currentpage) && $currentpage != '') ? $currentpage:"index.php";
	header("location: ".LinkToGT($currentpage)."");
}
elseif(isset($cdatetype))
	$nuke_configs['datetype'] = $cdatetype;

/* define languages */

$nuke_configs['ThemeSel'] = get_theme();

/* define languages */
require_once("language/alphabets.php");

start_nuke_language($nuke_configs);
/* define languages */

/* Ping System */
$PingOptimizer = new PingOptimizer();
/* Ping System */

$all_post_types = array();
$all_post_types = $hooks->apply_filters("set_all_post_types", $all_post_types);

/* define theme */
if(!defined("IN_INSTALL"))
{
	if(file_exists("themes/".$nuke_configs['ThemeSel']."/theme_setup.php"))
		require_once("themes/".$nuke_configs['ThemeSel']."/theme_setup.php");
	
	if(defined('ADMIN_FILE'))
	{
	  include_once("admin/template/themes.php");
	}
	else
	{
		if(file_exists("themes/".$nuke_configs['ThemeSel']."/theme.php"))
		  require_once("themes/".$nuke_configs['ThemeSel']."/theme.php");
	}
}
/* define theme */

$cache_systems = $hooks->apply_filters("cache_systems", []);

if(is_array($cache_systems) && !empty($cache_systems))
	foreach($cache_systems as $cache_system_name => $cache_system)
		if(isset($cache_system['auto_load']) && $cache_system['auto_load'])
			eval('$'.$cache_system_name.'_cacheData = get_cache_file_contents("'.$cache_system_name.'");');

cache_system();

if($nuke_configs['datetype'] == 2)
{
	$HijriCalendar = new HijriCalendar();
	$hijri = $HijriCalendar->GregorianToHijri( _NOWTIME );
}

///////////////////////////////// MTSN Code Start
mtsn_check();

$main_module = get_main_module();

if(!defined("ADMIN_FILE") && !defined("IN_INSTALL"))
{
	$REQUESTURL = str_replace(array( '\\', '../' ),array( '/',  '' ),$_SERVER['REQUEST_URI']);
	$parsed_GT_link = parse_GT_link($REQUESTURL);

	if(!empty($parsed_GT_link[0]))
	{
		unset($_GET);
		unset($_SERVER_QUERY_STRING);
		$_SERVER_QUERY_STRING = array();
		foreach($parsed_GT_link[0] as $GTkey => $GTval)
		{
			$_REQUEST[$GTkey] = $_GET[$GTkey] = $$GTkey = $GTval;
			$_SERVER_QUERY_STRING[] = "".$$GTkey." = ".$GTval."";
		}
		if(!empty($_SERVER_QUERY_STRING))
			$_SERVER['QUERY_STRING'] = $_SERVER['argv'][0] = implode("&",$_SERVER_QUERY_STRING);
	}
	
	$nuke_configs['REQUESTURL'] = $REQUESTURL = (!empty($parsed_GT_link[1])) ? $parsed_GT_link[1]:"/";
	$nuke_configs['noPermaLink'] = $noPermaLink = (!empty($parsed_GT_link[3])) ? $parsed_GT_link[3]:"index.php";

	if(!defined("IN_INSTALL"))
		update_blocks();
}

jrating_check();

captcha_check();

suspend_site_show();

$main_plugin_files = array();
$theme_plugin_files = array();
$main_plugin_files = get_dir_list(INCLUDE_PATH.'/plugins', 'files', false, array(".","..","index.html",".htaccess"), true);
$theme_plugin_files = get_dir_list('themes/'.$nuke_configs['ThemeSel'].'/plugins', 'files', false, array(".","..","index.html",".htaccess"), true);
$plugin_files = $hooks->apply_filters("plugin_files", array_merge($main_plugin_files, $theme_plugin_files));

if(!empty($plugin_files))
{
	define("PLUGIN_FILE", true);
	foreach($plugin_files as $plugin_file)
	{
		if(file_exists($plugin_file))
		{
			include($plugin_file);
		}
	}
}
unset($plugin_files);
unset($main_plugin_files);
unset($theme_plugin_files);

$sop = (isset($sop)) ? filter($sop, "nohtml"):"";
if($sop != '')
{
	switch($sop)
	{
		case"report":
			$post_link = isset($post_link) ? filter($post_link):"";
			$module_name = isset($module_name) ? filter($module_name, "nohtml"):"";
			$post_title = isset($post_title) ? filter($post_title, "nohtml"):"";
			$post_id = isset($post_id) ? intval($post_id):0;
			report_friend_form(false, $sop, $post_id, $post_title, $module_name, '', '', $post_link, '', '');
		break;
	}
	$hooks->do_action("run_sop_requests", $sop);
	die();
}

$hooks->do_action("run_plugins");

?>