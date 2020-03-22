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

// Error reporting, to be set in config.php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);

// Define the INCLUDE PATH
define('INCLUDE_PATH', 'includes');
define('ADMIN_PATH', 'admin');

//Absolute PHP-Nuke directory
define("PHPNUKE_ROOT_PATH", dirname(__FILE__));

$nuke_root = str_replace(array( '\\', '../' ),array( '/',  '' ), PHPNUKE_ROOT_PATH );

define("PHPNUKE_ROOT_MAIN_PATH", trim(str_replace($_SERVER['DOCUMENT_ROOT'], "", str_replace(array( '\\', '../' ),array( '/',  '' ), PHPNUKE_ROOT_PATH )) ,"/"));

// Absolute Phoenix Directory And Includes
define('PHOENIX_INCLUDE_DIR', PHPNUKE_ROOT_PATH."/".INCLUDE_PATH.'/');

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

// override old superglobals if php is higher then 4.1.0
if($phpver >= '4.1.0')
{
	$HTTP_GET_VARS = $_GET;
	$HTTP_POST_VARS = $_POST;
	$HTTP_SERVER_VARS = $_SERVER;
	$HTTP_POST_FILES = $_FILES;
	$HTTP_ENV_VARS = $_ENV;
	$PHP_SELF = $_SERVER['PHP_SELF'];
	if(isset($_SESSION))
	{
		$HTTP_SESSION_VARS = $_SESSION;
	}
	if(isset($_COOKIE))
	{
		$HTTP_COOKIE_VARS= $_COOKIE;
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
	extract($$method);

if ((file_exists('install/') && file_exists('install.php')) && !defined("IN_INSTALL"))
{
	header("location: install.php");
	exit;
}

require_once(INCLUDE_PATH."/functions.php");
require_once("config.php");

if(preg_match("#/thumbs/(.*)\.jpg$#i", $_SERVER['SCRIPT_URL'], $matches))
{
	$timthumbs_data = parse_timthumbs_args($matches[1]);
	$QUERY_STRING = array();
	foreach($timthumbs_data as $timthumbs_key => $timthumbs_val)
	{
		$_REQUEST[$timthumbs_key] = $_GET[$timthumbs_key] = $timthumbs_val;
		$QUERY_STRING[] = "$timthumbs_key=$timthumbs_val";
	}
	
	if(!empty($QUERY_STRING))
		$_SERVER ['QUERY_STRING'] = implode("&", $QUERY_STRING);

	extract($timthumbs_data);
	header('Content-Disposition:inline;filename="'.basename($src).'"');

	$ALLOWED_SITES = array();
	$ALLOW_ALL_EXTERNAL_SITES = false;
	
	if(isset($src) && $src != '')
	{
		$ALLOWED_SITES = (isset($timthumb_allowed) && !empty($timthumb_allowed)) ? $timthumb_allowed:false;
		
		if(!empty($ALLOWED_SITES))
			$ALLOW_ALL_EXTERNAL_SITES = true;
			
		if(isset($data_image))
		{
			$metadata = read_media_metadata($src);
				
			require_once(INCLUDE_PATH."/class.simple_image.php");
			header('Content-Type: '.$metadata['cover']['mime_type'].'');

			$image = new SimpleImage();
			$image->compression = (intval($q) != 0) ? $q:90;
			$image->load(true, $metadata['cover']['data'], $metadata['cover']['mime_type']);
			$h = isset($h) ? $h:false;
			$w = isset($w) ? $w:false;
			if($h && $w)
			{
				$image->resize($w,$h);
			}
			elseif($w && !$h)
			{
				$image->resizeToWidth($w);
			}
			elseif($h && !$w)
			{
				$image->resizeToHeight($h);
			}
			$image->output();
			die();
		}
		else
		{
			if(PHPNUKE_ROOT_MAIN_PATH != '')
				define ('LOCAL_FILE_BASE_DIRECTORY', str_replace("/".PHPNUKE_ROOT_MAIN_PATH, "", $nuke_root));
			else
				define ('LOCAL_FILE_BASE_DIRECTORY', $nuke_root);
			include(INCLUDE_PATH."/class.timthumb.php");
		}
	}
	die();
}
	
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
require_once(INCLUDE_PATH .'/csrfp/libs/csrf/csrfprotector.php');

//require_once("db/db.php");
require_once('db/Database.php');
//use PHPnuke\Database\Database;
$pn_Sessions = new pn_Sessions();
$pn_Cookies = new pn_Cookies();
$pn_Bots = new CrawlerDetect();

// This block of code makes sure $admin and $user are COOKIES
if((isset($admin) && $admin != $pn_Cookies->get('admin')) OR (isset($user) && $user != $pn_Cookies->get('user')))
{
	die("Illegal Operation");
}

// Die message for not allowed HTML tags
$htmltags = "<div class=\"text-center\"><img src=\"images/logo.gif\" title=\"logo\" alt=\"phpnuke\"><br><br><b>";
$htmltags .= "The html tags you attempted to use are not allowed</b><br><br>";
$htmltags .= "[ <a href=\"javascript:history.go(-1)\"><b>Go Back</b></a> ]</div>";


GUMP::add_filter("sanitize_title", 'sanitize');
$PnValidator = new GUMP();
$cache = new Cache();

if (!defined('ADMIN_FILE'))
{
	foreach ($_GET as $sec_key => $secvalue)
	{
		if((@preg_match("#<[^>]*script*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("#<[^>]*object*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("#<[^>]*iframe*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("#<[^>]*applet*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("#<[^>]*meta*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("#<[^>]*style*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("#<[^>]*form*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("#<[^>]*img*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("#<[^>]*onmouseover *\"?[^>]*#i", $secvalue)) ||
		(@preg_match("#<[^>]*body *\"?[^>]*#i", $secvalue)) ||
		(@preg_match("#\([^>]*\"?[^)]*\)#i", $secvalue)) ||
		(@preg_match("#\"#i", $secvalue)) ||
		(@preg_match("#forum_admin#i", $sec_key)) ||
		(@preg_match("#inside_mod#i", $sec_key)))
		{
			die ($htmltags);
		}
	}

	foreach ($_POST as $secvalue)
	{
		if ((@preg_match("<[^>]*iframe*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("<[^>]*object*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("<[^>]*applet*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("<[^>]*meta*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("<[^>]*onmouseover*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("<[^>]script*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("<[^>]*body*\"?[^>]*#i", $secvalue)) ||
		(@preg_match("<[^>]style*\"?[^>]*#i", $secvalue)))
		{
			die ($htmltags);
		}
	}
}

$postString = "";
$postString = http_build_query($_POST);

str_replace("%09", "%20", $postString);
$postString_64 = base64_decode($postString);
if (
	(!isset($admin) OR (isset($admin) AND !is_admin())) AND 
	(
		(stristr($postString,'%20union%20'))		OR 
		(stristr($postString_64,'%20union%20'))		OR 
		(stristr($postString,' union '))			OR 
		(stristr($postString_64,' union '))			OR 
		(stristr($postString,'*/union/*'))			OR 
		(stristr($postString_64,'*/union/*'))		OR 
		(stristr($postString,'+union+'))			OR 
		(stristr($postString_64,'+union+'))			OR 
		(stristr($postString,'http-equiv'))			OR 
		(stristr($postString_64,'http-equiv'))		OR 
		(stristr($postString,'alert('))				OR 
		(stristr($postString_64,'alert('))			OR 
		(stristr($postString,'javascript:'))		OR 
		(stristr($postString_64,'javascript:'))		OR 
		(stristr($postString,'document.cookie'))	OR 
		(stristr($postString_64,'document.cookie'))	OR 
		(stristr($postString,'onmouseover='))		OR 
		(stristr($postString_64,'onmouseover='))	OR 
		(stristr($postString,'document.location'))	OR 
		(stristr($postString_64,'document.location'))
	)
)
{
	header("Location: ".LinkToGT("index.php")."");
	die();
}

if(!$pn_dbname && !defined("IN_INSTALL"))
{
    die("<br><br><div class=\"text-center\"><img src=images/logo.gif title=\"logo\" alt=\"phpnuke\"><br><br><b>There seems that PHP-Nuke isn't installed yet.<br>(The values in config.php file are the default ones)<br><br>You can proceed with the <a href='install.php'>web installation</a> now.</div></b>");
}

$db = Database::connect($pn_dbhost, $pn_dbuname , $pn_dbpass, $pn_dbname, $pn_dbtype, $pn_dbfetch, $pn_dbcharset);

if(empty($admin_file))
	die ("You must set a value for admin_file in config.php");
elseif(!empty($admin_file) && !file_exists($admin_file.".php"))
	die ("The admin_file you defined in config.php does not exist");


$handle=opendir('modules');
while ($mfile = @readdir($handle))
{
	if($mfile != '.' && $mfile != '..' && $mfile != '.htaccess' && $mfile != 'index.html' && $mfile != 'nexttime' && is_dir("modules/$mfile"))
	{
		$moduleslist[] = $mfile;
	}
}
closedir($handle);
sort($moduleslist);

define("CONFIG_FUNCTIONS_FILE", true);
foreach($moduleslist as $modulename)
	if(file_exists("modules/$modulename/config_function.php"))
		@include("modules/$modulename/config_function.php");

//$row = $db->sql_fetchrow($db->sql_query("SET NAMES `utf8`"));

$nuke_points_groups_cacheData = get_cache_file_contents('nuke_points_groups');
$nuke_banners_cacheData = get_cache_file_contents('nuke_banners');
$nuke_mtsn_ipban_cacheData = get_cache_file_contents('nuke_mtsn_ipban');
$nuke_bookmarksite_cacheData = get_cache_file_contents('nuke_bookmarksite');
$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
$nuke_languages_cacheData = get_cache_file_contents('nuke_languages');
$nuke_banned_ip_cacheData = get_cache_file_contents('nuke_mtsn_ipban');

if(is_array($cache_systems) && !empty($cache_systems))
	foreach($cache_systems as $cache_system_name => $cache_system)
		if($cache_system['auto_load'])
			eval('$'.$cache_system_name.'_cacheData = get_cache_file_contents("'.$cache_system_name.'");');


$visitor_ip = get_client_ip();
$nuke_modules_friendly_urls_cacheData = get_cache_file_contents('nuke_modules_friendly_urls');


$nuke_configs = get_cache_file_contents('nuke_configs');
if(empty($nuke_configs))
{
	cache_system('nuke_configs');
	$nuke_configs = get_cache_file_contents('nuke_configs');
}

// Request URL Redirect To Nuke Url
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 1 : 0;

if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
{
	$secure = 1;
	$server_port = 443;
}

$Req_Protocol 	= ($secure == 1) ? 'https' : 'http';
$Req_Host     	= $_SERVER['HTTP_HOST'];
$Req_Uri		= $_SERVER['REQUEST_URI'];
$Req_Path		= $_SERVER['SCRIPT_NAME'];
$Req_URL		= $Req_Protocol . '://' . $Req_Host . $Req_Path;
$Req_URI		= $Req_Protocol . '://' . $Req_Host . $Req_Uri;
$Req_Filename 	= substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
$filenamepos 	= strpos($Req_URL,$Req_Filename);
$Req_URL 		= substr($Req_URL,0,$filenamepos);
$Redirect_Url 	= substr($Req_URI,strlen($Req_URL),1000);

if(!defined("IN_INSTALL") && !defined("ADMIN_FILE"))
{
	if(version_compare($nuke_configs['Version_Num'], "8.4.2", ">="))
	{
		if($Req_URL != $nuke_configs['nukeurl'] && $nuke_configs['lock_siteurl'] == 1)
		{
			header("Location: ".$nuke_configs['nukeurl'] . $Redirect_Url,TRUE,301);
			exit;
		}
		else
			$nuke_configs['nukeurl'] = $Req_URL;
	}
	else
	{
		$db->query("UPDATE ".CONFIG_TABLE." SET config_value = '".$Req_URL."' WHERE config_name = 'nukeurl'='");
		if($Req_Filename != 'install.php')
		{
			header("Location: ".LinkToGT("install.php")."",TRUE,301);
			exit;
		}
	}
}
/******************END****************/
$pn_Sessions->sPrefix = (isset($nuke_configs['sessions_prefix']) && $nuke_configs['sessions_prefix'] != '') ? $nuke_configs['sessions_prefix']:'pnSession_';
csrfProtector::init();

if(!$pn_Sessions->exists('nuke_authors'))
{
	define("IN_FLUSH", true);
	cache_system('nuke_authors');
}

$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);

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

if(isset($nuke_configs['have_forum']) && $nuke_configs['have_forum'] == 1 && is_dir($nuke_configs['forum_path']))
	require_once(INCLUDE_PATH."/forums_classes/class.".$nuke_configs['forum_system'].".php");
else
	require_once(INCLUDE_PATH."/forums_classes/class.no_forum.php");
	
$users_system = new users_system();

$userinfo = (isset($users_system->data) && is_array($users_system->data)) ? $users_system->data:array();
		
cache_system();

define("_PN_CSRF_TOKEN", get_form_token());

// set common configs
$nuke_configs['copyright']			= base64_decode(base64_decode(base64_decode(filter($nuke_configs['copyright']))));
$nuke_configs['phpver']				= $phpver;

$nuke_configs['links_function']		= (isset($nuke_configs_links_function) && is_array($nuke_configs_links_function)) ? $nuke_configs_links_function:array();
$nuke_configs['categories_link']	= (isset($nuke_configs_categories_link) && is_array($nuke_configs_categories_link)) ? $nuke_configs_categories_link:array();
$nuke_configs['categories_delete']	= (isset($nuke_configs_categories_delete) && is_array($nuke_configs_categories_delete)) ? $nuke_configs_categories_delete:array();

$users_system->search_data			= (isset($users_system->search_data) && is_array($users_system->search_data)) ? $users_system->search_data:array();
$nuke_configs_search_data			= (isset($nuke_configs_search_data) && is_array($nuke_configs_search_data)) ? $nuke_configs_search_data:array();
$nuke_configs['search_data']		= array_merge($users_system->search_data, $nuke_configs_search_data);


$nuke_configs['statistics_data']	= (isset($nuke_configs_statistics_data) && is_array($nuke_configs_statistics_data)) ? $nuke_configs_statistics_data:array();

$nuke_configs_links_function = $nuke_configs_categories_link = $nuke_configs_categories_delete = $nuke_configs_search_data = $nuke_configs_statistics_data = $users_system->search_data = null;

unset($nuke_configs_links_function);
unset($nuke_configs_categories_link);
unset($nuke_configs_categories_delete);
unset($nuke_configs_search_data);
unset($users_system->search_data);

$nuke_configs['mtsn_gfx_chk'] = (isset($nuke_configs['mtsn_gfx_chk']) && $nuke_configs['mtsn_gfx_chk'] != '') ? explode(",", $nuke_configs['mtsn_gfx_chk']):array();

// set common configs
	
$nuke_configs['pages_links'] = array(
	"1" => "{YEAR}/{MONTH}/{DAY}/{PAGEURL}/",
	"2" => "{YEAR}/{MONTH}/{PAGEURL}/",
	"3" => "{ID}/{PAGEURL}/",
	"4" => "{PAGEURL}/",
	"5" => "{CATEGORY}/{PAGEURL}/",
);

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$start_time = $mtime;
$pagetitle = "";

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

$nuke_configs['nukecdnurl'] = (isset($nuke_configs['nukecdnurl']) && $nuke_configs['nukecdnurl'] != '') ? $nuke_configs['nukecdnurl']:$nuke_configs['nukeurl'];

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
global $nukelang;
require_once("language/alphabets.php");

if(isset($lang) && $lang == "default" && !isset($captcha))
{
	$pn_Cookies->set("nukelang",false,'');
	header("location: ".LinkToGT("index.php")."");
}

if ((isset($lang)) AND (!stristr($lang,".")) && !isset($captcha))
{
	global $currentpage;
	$lang = filter($lang, "nohtml");
	if (file_exists("language/".$lang.".php"))
	{
		$pn_Cookies->set("nukelang",$lang,(365*24*3600));
		$nuke_configs['currentlang'] = $lang;
	}
	else
	{
		$pn_Cookies->set("nukelang",$nuke_configs['language'],(365*24*3600));
		$nuke_configs['currentlang'] = $nuke_configs['language'];
	}
	$currentpage = (isset($currentpage) && $currentpage != '') ? $currentpage:"index.php";
	header("location: ".LinkToGT($currentpage)."");
}
elseif (isset($nukelang) && $nukelang != '')
{
	$nukelang = filter($nukelang, "nohtml");
	$nuke_configs['currentlang'] = $nukelang;
}
else
{
	if(!isset($captcha) && !$pn_Cookies->exists("nukelang"))
	{
		$pn_Cookies->set("nukelang",$nuke_configs['language'],(365*24*3600));
	}
	$nuke_configs['currentlang'] = $nuke_configs['language'];
}

define("NUKE_LANG_FILE", true);

$nuke_languages = get_languages_data();

foreach($nuke_languages[$nuke_configs['currentlang']] as $nuke_language_key => $nuke_language_val)
{
	if(!defined($nuke_language_key))
		define($nuke_language_key, $nuke_language_val);
}
unset($nuke_languages);

if(_DIRECTION == "rtl")
{
	define("_TEXTALIGN1","right");
	define("_TEXTALIGN2","left");
	define("_RTL_TEXT",true);
}
else
{
	define("_TEXTALIGN1","left");
	define("_TEXTALIGN2","right");
	define("_LTR_TEXT",true);
}

/* define languages */

/* define theme */
if(defined('ADMIN_FILE') && !defined("IN_INSTALL"))
  include_once("admin/template/themes.php");

$custom_theme_setup = array();

if(file_exists("themes/".$nuke_configs['ThemeSel']."/theme_setup.php") && !defined("IN_INSTALL"))
	require_once("themes/".$nuke_configs['ThemeSel']."/theme_setup.php");
	
if(!defined('ADMIN_FILE') && file_exists("themes/".$nuke_configs['ThemeSel']."/theme.php") && !defined("IN_INSTALL"))
  require_once("themes/".$nuke_configs['ThemeSel']."/theme.php");

/* define theme */

$HijriCalendar = new HijriCalendar();
$hijri = $HijriCalendar->GregorianToHijri( _NOWTIME );


///////////////////////////////// MTSN Code Start
if(!defined("IN_INSTALL"))
{
	if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], "Googlebot") == FALSE )
	{
		if (intval($nuke_configs['mtsn_status']) == 1)
		{
			@mtsn();
		}
		$ipbanx = stripslashes($nuke_configs['mtsn_block_ip']);
		if ($ipbanx == "1")
		{
			if ($visitor_ip != "127.0.0.1" AND !is_admin())
			{
				$ip = addslashes($visitor_ip);
				$ip_arr = explode(".", $ip);
				
				$ip_block_where[] = "'$ip_arr[0].$ip_arr[1].$ip_arr[2].$ip_arr[3]'";
				$ip_block_where[] = "'$ip_arr[0].$ip_arr[1].$ip_arr[2].*'";
				$ip_block_where[] = "'$ip_arr[0].$ip_arr[1].*.*'";
				$ip_block_where[] = "'$ip_arr[0].*.*.*'";
				
				$numrows = $db->table(MTSN_IPBAN_TABLE)
							->where('ipaddress', '=', $ip_block_where[0])
							->orWhere('ipaddress', '=', $ip_block_where[1])
							->orWhere('ipaddress', '=', $ip_block_where[2])
							->orWhere('ipaddress', '=', $ip_block_where[3])
							->select(["id"])
							->count();
				if (isset($numrow) && $numrow != 0)
				{
					die("<br><br><div class=\"text-center\"><img src='images/mtsn/mtsn.gif' title=\"mtsn\" alt=\"phpnuke mtsn\"><br><br><b><body bgcolor='#000066' style='font-family:arial; color:#ffffff; cursor:default;'>You have been banned by the MTSN</b></div>");
				}
			}
		}
	}
	///////////////////////////////// MTSN Code End

	require_once(INCLUDE_PATH."/ipban.php");

	if (intval($nuke_configs['httpref']) == 1)
	{
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$referrer = $_SERVER['HTTP_REFERER'];
			$referrer = check_html($referrer, "nohtml");
			if (@stristr($referrer, "nuke_") && @stristr($referrer, "into") && @stristr($referrer, "from")) {
				$referrer = "";
			}
			if (!empty($referrer) && !stripos_clone($referrer, "unknown") && !stripos_clone($referrer, "bookmark") && !stripos_clone($referrer, $_SERVER['HTTP_HOST']))
			{
				$db->table(REFERRER_TABLE)
					->insert(array(
						'url' => addslashes($referrer),
						'path' => $_SERVER['REQUEST_URI'],
						'ip' => $visitor_ip,
						'time' => _NOWTIME,
					));
					
				$numrows = $db->table(REFERRER_TABLE)
							->select()
							->count();
					
				if($numrows >= $nuke_configs['httprefmax']) {
					$db->table(REFERRER_TABLE)
						->order_by(array("rid" => "ASC"))
						->limit(1,0)
						->delete();
				}
				$numrows = null;
				$referrer = null;
			}
		}
	}
}

$main_module = 'Articles';

if(isset($nuke_modules_cacheData) && !empty($nuke_modules_cacheData))
{
	foreach($nuke_modules_cacheData as $nuke_modules__info)
	{
		if($nuke_modules__info['main_module'] == 1)
		{
			$main_module = $nuke_modules__info['title'];
			break;
		}
	}
}

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

// Additional security (Union, CLike, XSS)
//Union Tap
//Copyright Zhen-Xjell 2004 http://nukecops.com
//Beta 3 Code to prevent UNION SQL Injections
unset($matches);
unset($loc);
if(isset($_SERVER['QUERY_STRING'])){
	if (@preg_match("/([OdWo5NIbpuU4V2iJT0n]{5}) /", rawurldecode($loc=$_SERVER['QUERY_STRING']), $matches))
	{
		die('Illegal Operation');
	}
}

if(!isset($admin) OR (isset($admin) AND !is_admin()))
{
	$queryString = $_SERVER['QUERY_STRING'];
	if ((($_SERVER['PHP_SELF'] != "/index.php") OR !isset($url)))
	{
		if (stristr($queryString,'http://')) die('Illegal Operation');
	}
	if (
		(stristr($queryString,'%20union%20'))	OR 
		(stristr($queryString,'%2f%2a'))		OR 
		(stristr($queryString,'%2f*'))			OR 
		(stristr($queryString,'/*'))			OR 
		(stristr($queryString,'*/union/*'))		OR 
		(stristr($queryString,'c2nyaxb0'))		OR 
		(stristr($queryString,'+union+'))		OR 
		(
			(stristr($queryString,'cmd='))	AND 
			(!stristr($queryString,'&cmd'))
		)										OR 
		(
			(stristr($queryString,'exec'))	AND 
			(!stristr($queryString,'execu'))
		)										OR 
		(stristr($queryString,'concat'))
	)
	{
		die('Illegal Operation');
	}
}

/* jrating class by iman64 */
$jrating_Response['error'] = false;
$jrating_Response['message'] = '';
if(isset($_POST['jaction']))
{
	$jaction = adv_filter($_POST['jaction'], array("sanitize_string"), array("alpha_dash"));
	if($jaction[0] == 'success')
	{
		if(in_array(htmlentities($jaction[1], ENT_QUOTES, 'UTF-8'),array('rating','liking')))
		{
			submit_ratings($jrating_Response);
		}
		else
		{
			$jrating_Response['error'] = true;
			$jrating_Response['message'] = '"action" post data not equal to \'rating\'';
						
			
			die(json_encode($jrating_Response));
		}
	}
}
/* jrating class by iman64 */

if(isset($captcha) && isset($id) && $id != '')
{
	require_once INCLUDE_PATH.'/captcha/securimage.php';

	$img = new Securimage();

	$def_id = '_NUKE_CAPTCHA';
	
	if(isset($id) && filter($id, "nohtml") != '')
	{
		$requestId = adv_filter($id, array('sanitize_string'),array('alpha_dash','required'));
		if($requestId[0] != 'error')
			$def_id = $requestId[1];
	}
	
	$language_list = $all_languages = get_dir_list('language', 'files');
	
	$language = (isset($language) && in_array($language, $language_list)) ? filter($language, "nohtml"):'farsi';
	$height = (isset($height) && intval($height) != 0) ? intval($height):50;
	
	if($def_id == "_forum")
	{
		$img->image_height = 30;
		$img->font_ratio = 0.3;
		$img->text_x_start = 15;
		$img->arc_linethrough = false;
	}

	$img->code_length		= 5;
	if(isset($nuke_configs['mtsn_captcha_charset']) && $nuke_configs['mtsn_captcha_charset'] != '')
		$img->charset			= $nuke_configs['mtsn_captcha_charset'];

	$img->noise_level		= 1;
	$img->font_ratio		= 0.5;
	if($language == "farsi")
	{
		//$img->charset			= "1234567890";
		//$img->ttf_file        = INCLUDE_PATH.'/captcha/BKOODB_0.TTF';
		$img->perturbation		= 0;
	}
	//$img->captcha_type    = Securimage::SI_CAPTCHA_MATHEMATIC; // show a simple math problem instead of text
	//$img->case_sensitive	= true;                              // true to use case sensitve codes - not recommended
	$img->image_height    	= $height;    // height in pixels of the image
	$img->image_width     = $img->image_height * M_E;          // a good formula for image size based on the height
	//$img->perturbation    = .75;                               // 1.0 = high distortion, higher numbers = more distortion
	//$img->image_bg_color  = new Securimage_Color("#0099CC");   // image background color
	$img->text_color      = new Securimage_Color(rand(0, 64),rand(64, 128),rand(128, 255));   // captcha text color
	$img->num_lines       = 2;                                 // how many lines to draw over the image
	//$img->line_color      = new Securimage_Color("#0000CC");   // color of lines over the image
	//$img->image_type      = SI_IMAGE_JPEG;                     // render as a jpeg image
	//$img->signature_color = new Securimage_Color(rand(0, 64),
	//                                             rand(64, 128),
	//                                             rand(128, 255));  // random signature color

	// see securimage.php for more options that can be set

	// set namespace if supplied to script via HTTP GET
	if (!empty($def_id)) $img->setNamespace($def_id);

	$img->show(INCLUDE_PATH."/captcha/backgrounds/".rand(1,10).".jpg");

	//$img->show();  // outputs the image and content headers to the browser
	// alternate use:
	// $img->show('/path/to/background_image.jpg');
	die();
}

suspend_site_show();

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
	die();
}

$plugin_files = get_dir_list(INCLUDE_PATH.'/plugins', 'files', false, array(".","..","index.html",".htaccess"));
$plugin_files2 = get_dir_list('themes/'.$nuke_configs['ThemeSel'].'/plugins', 'files', false, array(".","..","index.html",".htaccess"));
if(!empty($plugin_files))
{
	define("PLUGIN_FILE", true);
	foreach($plugin_files as $plugin_file)
		if(file_exists(INCLUDE_PATH."/plugins/".$plugin_file))
			@include(INCLUDE_PATH."/plugins/".$plugin_file);
}
if(!empty($plugin_files2))
{
	if(!defined("PLUGIN_FILE"))
		define("PLUGIN_FILE", true);
	foreach($plugin_files2 as $plugin_file)
		if(file_exists('themes/'.$nuke_configs['ThemeSel'].'/plugins/'.$plugin_file))
			@include('themes/'.$nuke_configs['ThemeSel'].'/plugins/'.$plugin_file);
}
?>