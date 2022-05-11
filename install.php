<?php

// 8.4.5 installer
// 8.3.7 to 8.4.5 upgrader

if(version_compare(PHP_VERSION, '5.4.0', "<"))
{
	die("<p align=\"center\">sorry. PHPNuke 8.4 MT Edition needs php 5.4.0 or above. your Server PHP Version is : ".PHP_VERSION."</p>");
}

define("IN_INSTALL", true);
define("_NEW_VERSION", '8.4.5');

// Try to override some limits - maybe it helps some...
@set_time_limit(0);
$mem_limit = @ini_get('memory_limit');
if (!empty($mem_limit))
{
	$unit = strtolower(substr($mem_limit, -1, 1));
	$mem_limit = (int) $mem_limit;

	if ($unit == 'k')
	{
		$mem_limit = floor($mem_limit / 1024);
	}
	else if ($unit == 'g')
	{
		$mem_limit *= 1024;
	}
	else if (is_numeric($unit))
	{
		$mem_limit = floor((int) ($mem_limit . $unit) / 1048576);
	}
	$mem_limit = max(128, $mem_limit) . 'M';
}
else
{
	$mem_limit = '128M';
}
@ini_set('memory_limit', $mem_limit);

error_reporting(E_ALL);
@ini_set('display_errors', 1);

if(isset($_GET['op']))
{
	require_once("mainfile.php");
}
else
{
	// Get php version
	$phpver = phpversion();
	//// Set IRAN Time
	if ($phpver >= '5.1.0')
	{
		date_default_timezone_set('Asia/Tehran');
	}
	define("NUKE_FILE", true);
	define("_NOWTIME", time());
	$methods = array("_GET","_POST","_REQUEST","_FILES");
	foreach($methods as $method)
	{
		if(isset($$method))
			extract($$method);
	}
	
	require_once("includes/functions.php");
	require_once("config.php");
	require_once("includes/class.Hooks.php");
	$hooks = Hooks::getInstance();
	require_once("includes/class.sessions.php");
	require_once("includes/class.cache.php");
	require_once("includes/class.cookies.php");
	$pn_Cookies = new pn_Cookies();
	
	$pn_salt = "";
	// setup 'default' cache
	$cache = new Cache();
	require_once("includes/constants.php");
	include_once('includes/class.Database.php');
	// Request URL Redirect To Nuke Url
	$Req_Protocol 	= strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
	$Req_Host     	= $_SERVER['HTTP_HOST'];
	$Req_Uri		= $_SERVER['REQUEST_URI'];
	$Req_Path		= $_SERVER['SCRIPT_NAME'];
	$Req_URL		= $Req_Protocol . '://' . $Req_Host . $Req_Path;
	$Req_URI		= $Req_Protocol . '://' . $Req_Host . $Req_Uri;
	$Req_Filename 	= substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
	$filenamepos 	= strpos($Req_URL,$Req_Filename);
	$Req_URL 		= substr($Req_URL,0,$filenamepos);
	$Redirect_Url 	= substr($Req_URI,strlen($Req_URL),strlen($Req_URL));
}
error_reporting(E_ALL);
@ini_set('display_errors', 1);
if(isset($_REQUEST['install_lang']) && $_REQUEST['install_lang'] != '')
{
	$pn_Cookies->set("install_lang",$_REQUEST['install_lang']);
	$install_lang = $_REQUEST['install_lang'];
}
elseif($pn_Cookies->exists("install_lang"))
{
	$install_lang = $pn_Cookies->get("install_lang");
}
else
	$install_lang = 'farsi';

$old_title = '';
$Old_Title = '';
	
global $db, $nuke_configs;

if($cache->isCached('install_options'))
{
	$install_options = $cache->retrieve('install_options');
	$install_options = phpnuke_unserialize($install_options);

	if(isset($install_options['install_lang']))
		$install_lang = $install_options['install_lang'];
	
	if(isset($install_options['admininfo']) && isset($install_options['db_info']))
	{
		if($install_options['mode'] == 'upgrade')
		{
			define("OLD_DB", $install_options['admininfo']['old_dbname']);
			define("OLD_DB_PREFIX", $install_options['admininfo']['old_dbprefix']);
		}
		define("NEW_DB", $install_options['db_info']['db_name']);
	}
}

include("language/$install_lang.php");
$nuke_languages = $nuke_languages[$install_lang];

define("_ALIGN1", (($nuke_languages['_DIRECTION'] == "rtl") ? "right":"left"));
define("_ALIGN2", (($nuke_languages['_DIRECTION'] == "rtl") ? "left":"right"));

function steps_error($message, $step, $percent)
{
	global $nuke_languages;
	upgrade_header($step, $percent);
	echo"<div class=\"wizard-card-container\" style=\"height: 326px;\">
		<div class=\"wizard-card\" data-cardname=\"group\">
			<h3>".$nuke_languages['_ERROR']."</h3>
			<div class=\"wizard-input-section\">
				$message
			</div>
			<div class=\"text-center\"><a href=\"install.php\"><button class=\"btn btn-default\">".$nuke_languages['_INSTALL_RETURN_TO_FIRSTSTEP']."</button></a></div>
		</div>
	</div>";
	upgrade_footer();
	die();
}

function upgrade_header($step = 1, $progress = 0)
{
	global $db, $nuke_configs, $cache, $install_lang, $nuke_languages;
	
	$langs = get_dir_list("language", "files", true, array('.','..','', '.htaccess','index.html','alphabets.php'));
	$langs = str_replace(".php","",$langs);
	
	if($cache->isCached('install_options'))
	{
		$install_options = $cache->retrieve('install_options');
		$install_options = phpnuke_unserialize($install_options);
	}
	
	$install_options['mode'] = (isset($install_options['mode']) && $install_options['mode'] != '') ? $install_options['mode']:"install";
	
	$active_1 = ($step == 1 || $step == 0) ? "active":"";
	$active_2 = ($step == 2) ? "active":"";
	$active_3 = ($step == 3) ? "active":"";
	$active_4 = ($step == 4) ? "active":"";
	$active_5 = ($step == 5) ? "active":"";
	$active_6 = ($step == 6) ? "active":"";
	$active_7 = ($step == 7) ? "active":"";

	$step_title = (isset($install_options['mode']) && $install_options['mode'] == 'install') ? $nuke_languages['_INSTALL_NEW_ADMIN_DATA']:$nuke_languages['_INSTALL_OLD_VERSION_DATA'];
	
	$install_folder = (defined("_INSTALL_FOLDER")) ? _INSTALL_FOLDER:"install";
	echo"<!DOCTYPE html>
<html lang=\"en\" dir=\"".$nuke_languages['_DIRECTION']."\">
<head>
	<title>".$nuke_languages['_INSTALL_PAGE_TITLE']." "._NEW_VERSION."</title>
	<meta charset=\"UTF-8\">
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
	<link href=\"includes/Ajax/jquery/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">";
	if($nuke_languages['_DIRECTION'] == "rtl")
		echo"<link href=\"includes/Ajax/jquery/bootstrap/css/bootstrap-rtl.css\" rel=\"stylesheet\">";
	echo"<link href=\"includes/fonts/vazir/style.css\" rel=\"stylesheet\">
	<link href=\"includes/fonts/fontawesome/style.css\" rel=\"stylesheet\">
	<link href=\"$install_folder/bootstrap/bootstrap-wizard.css\" rel=\"stylesheet\">
	<script src=\"$install_folder/js/pwdwidget.js\" type=\"text/javascript\"></script>
	<!--[if lt IE 9]> <script src=\"$install_folder/js/html5shiv-3.7.0.js\"></script> <script src=\"$install_folder/js/respond-1.3.0.min.js\"></script> <![endif]-->
	<link rel=\"stylesheet\" href=\"$install_folder/css/style.css\">
	<script src=\"includes/Ajax/jquery/jquery.min.js\" type=\"text/javascript\"></script>
</head>

<body class=\"modal-open ".$nuke_languages['_DIRECTION']."\">
	<div class=\"modal fade wizard in\" style=\"display: block;\" aria-hidden=\"false\">
		<div class=\"modal-dialog wizard-dialog\" style=\"width: 720px; padding-top: 0px;\">
			<div class=\"modal-content wizard-content\" style=\"height: 455px;\">
				<div class=\"modal-header wizard-header\">
					<h3 class=\"modal-title wizard-title\">".$nuke_languages['_INSTALL_PAGE_TITLE']."</h3>";
					if($progress == 0)
					{
					echo"<span class=\"wizard-subtitle\">
						<select class=\"selectlang\" onchange=\"top.location.href=this.options[this.selectedIndex].value\">";
							foreach($langs as $lang)
							{
								$sel = ($install_lang == $lang) ? "selected":"";
								echo"<option value=\"install.php?install_lang=$lang\" $sel>$lang</option>";
							}
						echo"</select>
					</span>";
					}
				echo"</div>
				<div class=\"modal-body wizard-body\" style=\"height: 400px;\">
					<div class=\"pull-"._ALIGN1." wizard-steps\" style=\"height: 400px;\">
						<div class=\"wizard-nav-container\" style=\"height: 310px;\">
							<ul class=\"nav wizard-nav-list\">
								<li class=\"wizard-nav-item $active_1\"><a class=\"wizard-nav-link\"><span class=\"glyphicon glyphicon-chevron-"._ALIGN2."\"></span> ".$nuke_languages['_INSTALL_WELLCOME']."</a>
								</li>
								<li class=\"wizard-nav-item $active_2\"><a class=\"wizard-nav-link\"><span class=\"glyphicon glyphicon-chevron-"._ALIGN2."\"></span> ".$nuke_languages['_INSTALL_DBCONNECT']."</a>
								</li>
								<li class=\"wizard-nav-item $active_3\"><a class=\"wizard-nav-link\"><span class=\"glyphicon glyphicon-chevron-"._ALIGN2."\"></span> ".$nuke_languages['_INSTALL_SERVERCHK']."</a>
								</li>
								<li class=\"wizard-nav-item $active_4\"><a class=\"wizard-nav-link\"><span class=\"glyphicon glyphicon-chevron-"._ALIGN2."\"></span> ".$nuke_languages['_INSTALL_SITEINFO']."</a>
								</li>
								<li class=\"wizard-nav-item $active_5\"><a class=\"wizard-nav-link\"><span class=\"glyphicon glyphicon-chevron-"._ALIGN2."\"></span> $step_title</a>
								</li>
								<li class=\"wizard-nav-item $active_6\"><a class=\"wizard-nav-link\"><span class=\"glyphicon glyphicon-chevron-"._ALIGN2."\"></span> ".$nuke_languages['_INSTALL_SYSTEMINSTALL']."</a>
								</li>";
								if(isset($install_options['mode']) && $install_options['mode'] == 'upgrade')
								{
									echo"<li class=\"wizard-nav-item $active_7\"><a class=\"wizard-nav-link\"><span class=\"glyphicon glyphicon-chevron-"._ALIGN2."\"></span>".$nuke_languages['_INSTALL_UPGRADE']."</a>
									</li>";
								}
							echo"</ul>
						</div>
						<div class=\"wizard-progress-container\">
							<div class=\"progress progress-striped\">
								<div class=\"progress-bar\" style=\"width: $progress%;\"></div>
							</div>
						</div>
					</div>
					<div class=\"wizard-cards\" style=\"height: 400px;\">";
}

function upgrade_footer()
{
	echo"
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class=\"modal-backdrop fade in\"></div>
</body>
<script src=\"install/chosen/chosen.jquery.js\"></script>
<script src=\"includes/Ajax/jquery/bootstrap/js/bootstrap.min.js\" type=\"text/javascript\"></script>
<script src=\"install/js/prettify.js\" type=\"text/javascript\"></script>

</html>";
}

function upgrade_start()
{
	global $cache, $nuke_languages;
	
	$cache->flush_caches();
	
	upgrade_header(0);
	echo"
		<div class=\"wizard-card-container\" style=\"height: 326px;\">
			<div class=\"wizard-card\" data-cardname=\"group\">
				<h3>".$nuke_languages['_INSTALL_WELLCOME_TITLE']."</h3>
				<div class=\"wizard-input-section\">
					".$nuke_languages['_INSTALL_WELLCOME_DETAILS']."
					<span><a href=\"http://www.phpnuke.ir/Forum/viewtopic.php?f=1&t=20\" target=\"_blank\">".$nuke_languages['_INSTALL_WELLCOME_OTHER_RULES']." </a></span>&nbsp;&nbsp;&nbsp;<span style=\"cursor:pointer\" onclick=\"showHelp()\">".$nuke_languages['_INSTALL_WELLCOME_INSTALL_GUIDE']."</span><br /><br />
					<div class=\"text-center\"><div class=\"col-xs-6\"><form action=\"install.php?step=db&mode=install\" method=\"post\"><button type=\"submit\" class=\"btn btn-primary\">".$nuke_languages['_INSTALL_INSTALL']."</button></form></div><div class=\"col-xs-6\"><form action=\"install.php?step=db&mode=upgrade\" method=\"post\"><button type=\"submit\" class=\"btn btn-primary\">".$nuke_languages['_INSTALL_UPGRADE']."</button></form></div></div>
				</div>
			</div>
		</div>
		<div class=\"wizard-footer\">
			<div class=\"wizard-buttons-container\">
				<div class=\"btn-group-single pull-"._ALIGN2."\">
				</div>
			</div>
		</div>";
	upgrade_footer();
}

function step_db()
{
	global $db, $nuke_configs, $mode, $cache, $nuke_languages, $install_lang;
	
	$install_options = array();
	
	$mode = (isset($mode) && in_array($mode, array("install","upgrade"))) ? $mode:"install";
	
	$install_options['mode'] = $mode;
	$install_options['install_lang'] = $install_lang;
	$cache->store("install_options", phpnuke_serialize($install_options));

	upgrade_header(2, 20);
	echo"
	<form role=\"form\" class=\"form-horizontal\" id=\"nukeform\" action=\"install.php?step=server_check\" method=\"post\">
		<div class=\"wizard-card-container\" style=\"height: 326px;\">
			<div class=\"wizard-card\" data-cardname=\"group\" style=\"height: 300px;\">
				<h3>".$nuke_languages['_INSTALL_DBINFO']."</h3>
				<div class=\"wizard-input-section\">
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"pn_server_name\">".$nuke_languages['_INSTALL_SERVER_NAME']." :</label>
						<div class=\"col-sm-8\"> 
							<input type=\"text\" class=\"form-control\" id=\"pn_server_name\" name=\"db_fields[db_server_name]\" value=\"localhost\" placeholder=\"".$nuke_languages['_INSTALL_SERVER_NAME_PH']."\" minlength=\"3\" required data-msg-required=\"".$nuke_languages['_INSTALL_SERVER_NAME_ERR']."\" />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"pn_db_name\">".$nuke_languages['_INSTALL_NEW_DB_NAME'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"pn_db_name\" name=\"db_fields[db_name]\" value=\"\" placeholder=\"".$nuke_languages['_INSTALL_NEW_DB_NAME_PH']."\" required data-msg-required=\"".$nuke_languages['_INSTALL_NEW_DB_NAME_ERR']."\" />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"pn_db_forumname\">".$nuke_languages['_INSTALL_FORUM'].":</label>
						<div class=\"col-sm-8\" id=\"have_forum\">
							<label for=\"db_have_forum1\"><input type=\"radio\" id=\"db_have_forum1\" name=\"db_fields[db_have_forum]\" value=\"1\" /> ".$nuke_languages['_ACTIVE']."<label> &nbsp;&nbsp;
							<label for=\"db_have_forum2\"><input type=\"radio\" id=\"db_have_forum2\" name=\"db_fields[db_have_forum]\" value=\"2\" /> ".$nuke_languages['_INSTALL_USERS_TRANSFER_ONLY']."<label> &nbsp;&nbsp;
							<label for=\"db_have_forum3\"><input type=\"radio\" id=\"db_have_forum3\" name=\"db_fields[db_have_forum]\" value=\"0\" checked /> ".$nuke_languages['_INACTIVE']."<label>
						</div>
					</div>
					<div class=\"form-group have_forum\">
						<label class=\"control-label col-sm-4\" for=\"pn_db_forumname\">".$nuke_languages['_INSTALL_NEW_FORUM_DB_NAME'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"pn_db_forumname\" name=\"db_fields[db_forumname]\" value=\"\" />
						</div>
					</div>
					<div class=\"form-group have_forum\">
						<label class=\"control-label col-sm-4\" for=\"pn_db_forumpath\">".$nuke_languages['_INSTALL_FORUM_PATH'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"pn_db_forumpath\" name=\"db_fields[db_forumpath]\" value=\"Forum\" />
						</div>
					</div>
					<div class=\"form-group have_forum\">
						<label class=\"control-label col-sm-4\" for=\"pn_db_forumcms\">".$nuke_languages['_INSTALL_FORUM_SCRIPT'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"pn_db_forumcms\" name=\"db_fields[db_forumcms]\" value=\"phpbb\" />
						</div>
					</div>
					<div class=\"form-group have_forum\">
						<label class=\"control-label col-sm-4\" for=\"pn_db_forumunicode\">".$nuke_languages['_INSTALL_FORUM_COLLATION'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"pn_db_forumunicode\" name=\"db_fields[db_forumunicode]\" value=\"utf8mb4\" />
						</div>
					</div>
					<div class=\"form-group have_forum\">
						<label class=\"control-label col-sm-4\" for=\"pn_db_forumprefix\">".$nuke_languages['_INSTALL_FORUM_PREFIX']."</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"pn_db_forumprefix\" name=\"db_fields[db_forumprefix]\" value=\"phpbb\" />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"pn_db_username\">".$nuke_languages['_INSTALL_DB_USER'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"pn_db_username\" name=\"db_fields[db_username]\" value=\"\"  />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"pn_server_password\">".$nuke_languages['_INSTALL_DB_PASS'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"password\" class=\"form-control\" id=\"pn_server_password\" name=\"db_fields[db_password]\" />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"pn_server_prefix\">".$nuke_languages['_INSTALL_DB_PREFIX'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"pn_server_prefix\" name=\"db_fields[db_prefix]\" value=\"nuke\" placeholder=\"".$nuke_languages['_INSTALL_DB_PREFIX_PH']."\" />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class=\"wizard-footer\">
			<div class=\"wizard-buttons-container\">
				<div class=\"btn-group-single pull-"._ALIGN2."\">
					<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>
					<input class=\"btn wizard-next btn-primary\" type=\"submit\" value=\"".$nuke_languages['_NEXT']."\" />
				</div>
			</div>
		</div>
	</form>
	<script>
	$(document).ready(function(){
		$(\"#have_forum\").find('input').click(function(){
			if($(this).val() != 0)
				$(\".have_forum\").show();
			else
				$(\".have_forum\").hide();
		});
	});
	</script>
	";
	upgrade_footer();
}

function step_server_check()
{
	global $db, $nuke_configs, $db_fields, $pn_dbtype, $pn_dbfetch, $pn_dbcharset, $cache, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 3, 40);
	}
	
	$import_db = Database::connect($db_fields['db_server_name'], $db_fields['db_username'] , $db_fields['db_password'], $db_fields['db_name'], $pn_dbtype, $pn_dbfetch, $pn_dbcharset, true);

	if(!$import_db)
		$errors[] = $nuke_languages['_INSTALL_DB_CONNECT_ERROR'];
	
	$install_options = $cache->retrieve('install_options');
	
	$install_options = phpnuke_unserialize($install_options);
	
	$install_options['db_info'] = $db_fields;
	$install_options['db_info']['db_prefix'] = ($install_options['db_info']['db_prefix'] == '') ? "nuke":$install_options['db_info']['db_prefix'];
	
	$cache->store("install_options", phpnuke_serialize($install_options));
	
	upgrade_header(3, 40);
	
	$errors = array();
	$showerror = 0;
	$configstatus = '';
	
	if(!file_exists("config.php"))
	{
		if(!@rename("config.default.php", "config.php"))
		{
			$errors[] = $nuke_languages['_INSTALL_CONFIG_FILE_ERROR'];
			$configstatus = "<span class=\"fail\"><strong>".$nuke_languages['_INSTALL_NO_PERM_ERROR']."</strong></span>";
			$showerror = 1;
		}
	}

	// Check PHP Version
	if(version_compare(PHP_VERSION, '5.4.0', "<"))
	{
		$errors[] = "".$nuke_languages['_INSTALL_BAD_VERSION_ERROR']." : ".PHP_VERSION."";
		$phpversion = "<span class=\"fail\"><strong>".PHP_VERSION."</strong></span>";
		$showerror = 1;
	}
	else
	{
		$phpversion = '<span class="pass">'.PHP_VERSION.'</span>';;
	}
	
	if(function_exists('mb_detect_encoding'))
	{
		$mboptions[] = "Multi-Byte";
	}
	
	if(function_exists('iconv'))
	{
		$mboptions[] = 'iconv';
	}
	
	// Check Multibyte extensions
	if(count($mboptions) < 1)
	{
		$mbstatus = "<span class=\"fail\"><strong>None</strong></span>";
	}
	else
	{
		$mbstatus = "<span class=\"pass\">".implode(', ', $mboptions)."</span>";
	}

	// Check database engines
	if(class_exists('PDO'))
		$supported_dbs = PDO::getAvailableDrivers();
		
	if(count($supported_dbs) < 1)
	{
		$errors[] = $nuke_languages['_INSTALL_NO_DB_ERROR'];
		$dbsupportlist = "<span class=\"fail\"><strong>None</strong></span>";
		$showerror = 1;
	}
	else
	{
		$dbsupportlist = "<span class=\"pass\">".implode(', ', $supported_dbs)."</span>";
	}
	
	// Check config file is writable
	if(!is_writable('config.php'))
	{
		$errors[] = $nuke_languages['_INSTALL_CONFIG_PERM_ERROR'];
		$configstatus = "<span class=\"fail\"><strong>".$nuke_languages['_INSTALL_NO_PERM_ERROR']."</strong></span>";
		$showerror = 1;
	}
	else
	{
		$configstatus = "<span class=\"pass\"><strong>".$nuke_languages['_INSTALL_PERM_OK']."</strong></span>";
	}

	// Check cache directory is writable
	if(!is_writable(dirname('cache/')))
	{
		$errors[] = $nuke_languages['_INSTALL_CACHE_PERM_ERROR'];
		$cachestatus = "<span class=\"fail\"><strong>".$nuke_languages['_INSTALL_NO_PERM_ERROR']."</strong></span>";
		$showerror = 1;
	}
	else
	{
		$cachestatus = "<span class=\"pass\"><strong>".$nuke_languages['_INSTALL_PERM_OK']."</strong></span>";
	}

	// Check upload directory is writable
	if(!is_writable(dirname('files/uploads/')))
	{
		$errors[] = $nuke_languages['_INSTALL_UPLOADS_PERM_ERROR'];
		$uploadsstatus = "<span class=\"fail\"><strong>".$nuke_languages['_INSTALL_NO_PERM_ERROR']."</strong></span>";
		$showerror = 1;
	}
	else
	{
		$uploadsstatus = "<span class=\"pass\"><strong>".$nuke_languages['_INSTALL_PERM_OK']."</strong></span>";
	}

	// Check articles directory is writable
	if(!is_writable(dirname('files/Articles/')))
	{
		$errors[] = $nuke_languages['_INSTALL_ARTICLES_PERM_ERROR'];
		$Articlesstatus = "<span class=\"fail\"><strong>".$nuke_languages['_INSTALL_NO_PERM_ERROR']."</strong></span>";
		$showerror = 1;
	}
	else
	{
		$Articlesstatus = "<span class=\"pass\"><strong>".$nuke_languages['_INSTALL_PERM_OK']."</strong></span>";
	}

	if($showerror == 1)
	{
		$error_list = implode("<br />", $errors);
		$message = "<div class=\"error\">
		<h3>".$nuke_languages['_ERROR']."</h3>
		<p>".$nuke_languages['_INSTALL_PRR_ERROR']."</p>
		$error_list
		</div>";
	}
	else
	{
		$message = $nuke_languages['_INSTALL_PRR_OK'];
	}
	
	
	echo"
		<div class=\"wizard-card-container\" style=\"height: 326px;\">
			<div class=\"wizard-card\" data-cardname=\"group\" style=\"height: 305px;\">
				<h3>".$nuke_languages['_INSTALL_CHECK_SERVER_REQS']."</h3>
				<div class=\"wizard-input-section\">
					<div class=\"border_wrapper\">
						<table class=\"general\" cellspacing=\"0\">
							<tbody>
								<tr class=\"first\">
									<td class=\"first\">".$nuke_languages['_INSTALL_PHP_VERSION'].":</td>
									<td class=\"last alt_col\">$phpversion</td>
								</tr>
								<tr class=\"alt_row\">
									<td class=\"first\">".$nuke_languages['_INSTALL_SUPPORTED_DBS'].":</td>
									<td class=\"last alt_col\">$dbsupportlist</td>
								</tr>
								<tr class=\"alt_row\">
									<td class=\"first\">".$nuke_languages['_INSTALL_SUPPORTED_TRP']."</td>
									<td class=\"last alt_col\">$mbstatus</td>
								</tr>
								<tr class=\"alt_row\">
									<td class=\"first\">".$nuke_languages['_INSTALL_CONFIG_FILE_PERM'].":</td>
									<td class=\"last alt_col\">$configstatus</td>
								</tr>
								<tr>
									<td class=\"first\">".$nuke_languages['_INSTALL_CACHE_DIR_PERM'].":</td>
									<td class=\"last alt_col\">$cachestatus</td>
								</tr>
								<tr class=\"alt_row\">
									<td class=\"first\">".$nuke_languages['_INSTALL_UPLOADS_DIR_PERM'].":</td>
									<td class=\"last alt_col\">$uploadsstatus</td>
								</tr>
								<tr class=\"last\">
									<td class=\"first\">".$nuke_languages['_INSTALL_ARTICLES_DIR_PERM'].":</td>
									<td class=\"last alt_col\">$Articlesstatus</td>
								</tr>
							</tbody>
						</table>
						<br />$message
					</div>
				</div>
			</div>
		</div>
		<form action=\"install.php?step=siteinfo\" method=\"post\"><div class=\"wizard-footer\">
			<div class=\"wizard-buttons-container\">
				<div class=\"btn-group-single pull-"._ALIGN2."\">
					<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>";
					if($showerror == 0)
						echo" &nbsp; <button type=\"submit\" class=\"btn wizard-next btn-primary\">".$nuke_languages['_NEXT']."</button>";
				echo"</div>
			</div>
		</div></form>";
	upgrade_footer();
}

function step_siteinfo()
{
	global $db, $nuke_configs, $mode, $Req_URL, $cache, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 4, 60);
	}
	
	$install_options = $cache->retrieve('install_options');
	$install_options = phpnuke_unserialize($install_options);
	
	upgrade_header(4, 60);
	echo"
	<form role=\"form\" class=\"form-horizontal\" id=\"nukeform\" action=\"install.php?step=admin_info\" method=\"post\">
		<div class=\"wizard-card-container\" style=\"height: 326px;\">
			<div class=\"wizard-card\" data-cardname=\"group\">
				<h3>".$nuke_languages['_INSTALL_SITEINFO']."</h3>
				<div class=\"wizard-input-section\">
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"nukeurl\">".$nuke_languages['_SITEURL']." :</label>
						<div class=\"col-sm-8\"> 
							<input type=\"text\" class=\"form-control\" id=\"nukeurl\" name=\"install_fields[nukeurl]\" value=\"$Req_URL\" required />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"sitename\">".$nuke_languages['_SITENAME']." :</label>
						<div class=\"col-sm-8\"> 
							<input type=\"text\" class=\"form-control\" id=\"sitename\" name=\"install_fields[sitename]\" value=\"\" required />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class=\"wizard-footer\">
			<div class=\"wizard-buttons-container\">
				<div class=\"btn-group-single pull-"._ALIGN2."\">
					<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>
					<input class=\"btn wizard-next btn-primary\" type=\"submit\" value=\"".$nuke_languages['_NEXT']."\" />
				</div>
			</div>
		</div>
	</form>";
	upgrade_footer();
}

function step_admin_info()
{
	global $db, $nuke_configs, $install_fields, $cache, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 5, 80);
	}
	
	$install_options = $cache->retrieve('install_options');
	$install_options = phpnuke_unserialize($install_options);
	$install_options['siteinfo'] = $install_fields;
	
	$cache->store("install_options", phpnuke_serialize($install_options));
	$step_title = ($install_options['mode'] == 'install') ? $nuke_languages['_INSTALL_NEW_ADMIN_DATA']:$nuke_languages['_INSTALL_OLD_VERSION_DATA'];
	upgrade_header(5, 80);
	echo"
	<form role=\"form\" class=\"form-horizontal\" id=\"nukeform\" action=\"install.php?step=install\" method=\"post\">
		<div class=\"wizard-card-container\" style=\"height: 326px;\">
			<div class=\"wizard-card\" data-cardname=\"group\" style=\"height: 300px;\">
				<h3>$step_title</h3>
				<div class=\"wizard-input-section\">";
					if($install_options['mode'] == 'install')
					{
					echo"<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"admin_id\">".$nuke_languages['_INSTALL_ADMIN_USERNAME'].":</label>
						<div class=\"col-sm-8\"> 
							<input type=\"text\" class=\"form-control\" id=\"admin_id\" name=\"install_fields[aid]\" value=\"admin\" maxlength=\"25\" required />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"admin_realname\">".$nuke_languages['_INSTALL_ADMIN_DISPNAME']." :</label>
						<div class=\"col-sm-8\"> 
							<input type=\"text\" class=\"form-control\" id=\"admin_realname\" name=\"install_fields[realname]\" />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"admin_password\">".$nuke_languages['_PASSWORD'].":</label>
						<div class=\"col-sm-8\">
							<div class='pwdwidgetdiv' id='thepwddiv'></div>
							<script  type=\"text/javascript\" >
								var pwdwidget = new PasswordWidget('thepwddiv','install_fields[pwd]');
								pwdwidget.MakePWDWidget();
							</script>
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"admin_email\">".$nuke_languages['_EMAIL']." :</label>
						<div class=\"col-sm-8\"> 
							<input type=\"email\" class=\"form-control\" id=\"admin_email\" name=\"install_fields[email]\" />
						</div>
					</div>";
					}
					else
					{
					echo"
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"old_site_link\">".$nuke_languages['_INSTALL_OLD_SITEURL'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"old_site_link\" name=\"install_fields[old_site_link]\" />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"old_site_path\">".$nuke_languages['_INSTALL_OLD_PATH'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"old_site_path\" name=\"install_fields[old_site_path]\" />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"admin_password\">".$nuke_languages['_INSTALL_OLD_ADMIN_PASS'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"password\" class=\"form-control\" id=\"admin_password\" name=\"install_fields[pwd]\" />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"old_dbname\">".$nuke_languages['_INSTALL_OLD_DBNAME'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"old_dbname\" name=\"install_fields[old_dbname]\" value=\"\" required  />
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"old_dbprefix\">".$nuke_languages['_INSTALL_OLD_DBPREFIX'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"old_dbprefix\" name=\"install_fields[old_dbprefix]\" value=\"nuke\" required />
						</div>
					</div>";
					}
				echo"
					<div class=\"form-group\">
						<label class=\"control-label col-sm-4\" for=\"admin_filename\">".$nuke_languages['_INSTALL_ADMINFILE'].":</label>
						<div class=\"col-sm-8\">
							<input type=\"text\" class=\"form-control\" id=\"admin_filename\" name=\"install_fields[admin_filename]\" value=\"admin\" required />
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class=\"wizard-footer\">
			<div class=\"wizard-buttons-container\">
				<div class=\"btn-group-single pull-"._ALIGN2."\">
					<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>
					<input class=\"btn wizard-next btn-primary\" type=\"submit\" value=\"".$nuke_languages['_NEXT']."\" />
				</div>
			</div>
		</div>
	</form>";
	upgrade_footer();
}

function step_install()
{
	global $db, $nuke_configs, $install_fields, $pn_dbtype, $pn_dbfetch, $pn_dbcharset, $Req_URL, $cache, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$install_options = $cache->retrieve('install_options');
	$install_options = phpnuke_unserialize($install_options);
	$install_options['admininfo'] = $install_fields;
	
	$install_options['admininfo']['old_site_link'] = ($install_options['mode'] == 'install') ? "":((isset($install_fields['old_site_link']) && !empty($install_fields['old_site_link'])) ? $install_fields['old_site_link']:"");
	
	if($install_options['admininfo']['admin_filename'] != '' && $install_options['admininfo']['admin_filename'] != 'admin' && file_exists("admin.php") && !file_exists($install_options['admininfo']['admin_filename'].".php"))
	{
		if(!rename("admin.php", $install_options['admininfo']['admin_filename'].".php"))
		{
			upgrade_header(7, 95);
			echo"<div class=\"wizard-card-container\" style=\"height: 326px;\">
				<div class=\"wizard-card\" data-cardname=\"group\">
					<h3>".$nuke_languages['_INSTALL_DBINFO']."</h3>
					<div class=\"wizard-input-section\">
						".$nuke_languages['_INSTALL_ADMINFILE_ERROR']."
					</div>
				</div>
			</div>";
			upgrade_footer();
			die();
		}
	}

	global $pn_salt;
	$pn_salt = '';
	$pn_salt = change_config_content($install_options);
	$cache->flush_caches();

	$new_cache = new Cache();
	$new_cache->store("install_options", phpnuke_serialize($install_options));
	
	$errors = array();
	
	require_once 'admin/modules/modules/mysql_backup.php';

	$DB_obj = new BackupMySQL();
	$DB_obj->database = $install_options['db_info']['db_name'];

	if($install_options['db_info']['db_server_name'] == '' || $install_options['db_info']['db_username'] == '' || $install_options['db_info']['db_name'] == '')
		$errors[] = $nuke_languages['_INSTALL_INCORRECT_DATA'];
	
	$import_db = Database::connect($install_options['db_info']['db_server_name'], $install_options['db_info']['db_username'] , $install_options['db_info']['db_password'], $install_options['db_info']['db_name'], $pn_dbtype, $pn_dbfetch, $pn_dbcharset, true);

	if(!$import_db)
		$errors[] = $nuke_languages['_INSTALL_DB_CONNECT_ERROR'];
	
	$filename = 'install/nuke.sql';
	
	$DB_obj->db = $import_db;
	
	$default_tables = array("`nuke_admins_menu`","`nuke_articles`","`nuke_authors`","`nuke_banned_ip`","`nuke_blocks`","`nuke_blocks_boxes`","`nuke_blocks_themes`","`nuke_bookmarksite`","`nuke_categories`","`nuke_comments`","`nuke_config`","`nuke_feedbacks`","`nuke_groups`","`nuke_headlines`","`nuke_languages`","`nuke_log`","`nuke_modules`","`nuke_mtsn`","`nuke_mtsn_ipban`","`nuke_nav_menus`","`nuke_nav_menus_data`","`nuke_points_groups`","`nuke_posts`","`nuke_postsmeta`","`nuke_referrer`","`nuke_reports`","`nuke_scores`","`nuke_sessions`","`nuke_statistics`","`nuke_statistics_counter`","`nuke_surveys`","`nuke_surveys_check`","`nuke_tags`","`nuke_transactions`","`nuke_users`","`nuke_users_fields`","`nuke_users_fields_values`","`nuke_users_invites`");

	$import_db->query("DROP TABLE IF EXISTS ".implode(", ", $default_tables).";");
	
	if($install_options['db_info']['db_prefix'] != 'nuke')
	{
		foreach($default_tables as $key => $default_table)
		{
			$new_table_name = str_replace("nuke_", $install_options['db_info']['db_prefix']."_", $default_table);
			$default_tables[$key] = $new_table_name;
		}
		$import_db->query("DROP TABLE IF EXISTS ".implode(", ", $default_tables).";");
	}

	$DB_obj->db_connection_charset = $pn_dbcharset;
	
	$result = $DB_obj->read_sql_url($filename, 1, 0, ";", 0, $install_options['db_info']['db_prefix']."_");
	
	upgrade_header(6, (($install_options['mode'] == 'install') ? 100:90));

	echo"
	<div class=\"wizard-card-container\" style=\"height: 326px;\">
		<div class=\"wizard-card\" data-cardname=\"group\">
			<h3>".$nuke_languages['_INSTALL_SYSTEMINSTALL']."</h3>
			<div class=\"wizard-input-section\">
				<div class=\"alert alert-success\">
					<span class=\"create-server-name\"></span>".$nuke_languages['_INSTALL_TABLES_ADDED']."
				</div>";
				echo"<p align=\"center\">";
				if($install_options['mode'] == 'install')
				{
				echo"
				<meta http-equiv=\"refresh\" content=\"5;URL='install.php?op=final'\" />";
				}
				else
				{
				echo"
				<form action=\"install.php?op=first\" method=\"post\"><input class=\"btn btn-default\" type=\"submit\" value=\"".$nuke_languages['_INSTALL_CONTINUE_UPDATING']."\" /></form>";
				}
			echo"</p>
			</div>
		</div>
	</div>";
	upgrade_footer();
}

function change_config_content($install_options)
{

$pn_salt = random_str(15, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$~%^&*()_-+|');

$file_contents = '<?php

######################################################################
# PHP-NUKE: Advanced Content Management System
# ============================================
#
# Copyright (c) 2006 by Francisco Burzi
# http://phpnuke.org
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################

if (stristr(htmlentities($_SERVER["PHP_SELF"]), "config.php")) {
    Header("Location: index.php");
    die();
}

######################################################################
# Database & System Config
#
# dbhost:       SQL Database Hostname
# dbuname:      SQL Username
# dbpass:       SQL Password
# dbname:       SQL Database Name
# $prefix:      Your Database table\'s prefix
# $user_prefix: Your Users\' Database table\'s prefix (To share it)
# $dbtype:      Your Database Server type. Supported servers are:
#               MySQL, mysql4, sqlite, postgres, mssql, oracle,
#               msaccess, db2 and mssql-odbc
#               Be sure to write it exactly as above, case SeNsItIvE!
# $sitekey: Security Key. CHANGE it to whatever you want, as long
#               as you want. Just don\'t use quotes.
# $gfx_chk: Set the graphic security code on every login screen,
#		You need to have GD extension installed:
#		0: No check
#		1: Administrators login only
#		2: Users login only
#		3: New users registration only
#		4: Both, users login and new users registration only
#		5: Administrators and users login only
#		6: Administrators and new users registration only
#		7: Everywhere on all login options (Admins and Users)
#		NOTE: If you aren\'t sure set this value to 0
# $subscription_url : If you manage subscriptions on your site, you
#                     must write here the url of the subscription
#                     information/renewal page. This will send by
#                     email if set.
# $admin_file: Administration panel filename. "admin" by default for
#		   "admin.php". To improve security please rename the file
#              "admin.php" and change the $admin_file value to the
#              new filename (without the extension .php)
# $tipath:      Path to where the topic images are stored.
# $nuke_editorr: Turn On/Off the WYSIWYG text editor
#                   0: Off, will use the default simple text editor
#                   1: On, will use the full featured text editor
# $nuke_editorr: Debug control to see PHP generated errors.
#                   false: Do not show errors
#                   True See all errors ( No notices )t editor
######################################################################

$pn_dbhost = "'.$install_options['db_info']['db_server_name'].'";
$pn_dbuname = "'.$install_options['db_info']['db_username'].'";
$pn_dbpass = \''.$install_options['db_info']['db_password'].'\';
$pn_dbname = "'.$install_options['db_info']['db_name'].'";
$pn_prefix = "'.$install_options['db_info']['db_prefix'].'";
$pn_dbtype = "mysql";
$pn_dbfetch = PDO::FETCH_ASSOC;
$pn_dbcharset = "utf8mb4";

$pn_sitekey = "'.random_str(40).'";
$pn_subscription_url = "";
$pn_tipath = "images/topics/";
$pn_cache_type = "MySQL";
$admin_file = "'.$install_options['admininfo']['admin_filename'].'";
$pn_salt = \''.$pn_salt.'\';
$old_site_link = "'.$install_options['admininfo']['old_site_link'].'";
define("_MAX_CACHE_COUNTER_TIME", 3600);
define("_MAX_CACHE_COUNTER_LINES", 1000);

/*********************************************************************/
/* You finished to configure the Database. Now you can change all    */
/* you want in the Administration Section.   To enter just launch    */
/* you web browser pointing to http://yourdomain.com/admin.php       */
/* (Change xxxxxx.xxx to your domain name, for example: phpnuke.org) */
/*                                                                   */
/* Remeber to go to Settings section where you can configure your    */
/* new site. In that menu you can change all you need to change.     */
/*                                                                   */
/* Congratulations! now you have an automated news portal!           */
/* Thanks for choose PHP-Nuke: The Future of the Web                 */
/*********************************************************************/

// DO NOT TOUCH ANYTHING BELOW THIS LINE UNTIL YOU KNOW WHAT YOU\'RE DOING

$reasons = array("As Is","Offtopic","Flamebait","Troll","Redundant","Insighful","Interesting","Informative","Funny","Overrated","Underrated");
$badreasons = 4;
$AllowableHTML = array("img"=>2,"tr"=>1,"td"=>2,"table"=>2,"div"=>2,"p"=>2,"hr"=>1,"b"=>1,"i"=>1,"strike"=>1,"u"=>1,"font"=>2,"a"=>2,"em"=>1,"br"=>1,"strong"=>1,"blockquote"=>1,"tt"=>1,"li"=>1,"ol"=>1,"ul"=>1,"center"=>1);
$CensorList = array("fuck","cunt","fucker","fucking","pussy","cock","c0ck","cum","twat","clit","bitch","fuk","fuking","motherfucker");

//***************************************************************
// IF YOU WANT TO LEGALY REMOVE ANY COPYRIGHT NOTICES PLAY FAIR AND CHECK: http://phpnuke.org/modules.php?name=Commercial_License
// COPYRIGHT NOTICES ARE GPL SECTION 2(c) COMPLIANT AND CAN\'T BE REMOVED WITHOUT PHP-NUKE\'S AUTHOR WRITTEN AUTHORIZATION
// THE USE OF COMMERCIAL LICENSE MODE FOR PHP-NUKE HAS BEEN APPROVED BY THE FSF (FREE SOFTWARE FOUNDATION)
// YOU CAN REQUEST INFORMATION ABOUT THIS TO GNU.ORG REPRESENTATIVE. THE EMAIL THREAD REFERENCE IS #213080
// YOU\'RE NOT AUTHORIZED TO CHANGE THE FOLLOWING VARIABLE\'S VALUE UNTIL YOU ACQUIRE A COMMERCIAL LICENSE
// (http://phpnuke.org/modules.php?name=Commercial_License)
//***************************************************************
$commercial_license = 0;

$timthumb_allowed = array();
?>';

    $fp = fopen('config.php', 'w');
    fputs($fp, $file_contents);
    fclose($fp);
	return $pn_salt;
}

function change_htacces_RewriteBase()
{
	$SCRIPT_NAME = str_replace("install.php","", $_SERVER['SCRIPT_NAME']);
	
    $handle = fopen('.htaccess', 'r');
	$file_contents = fread($handle,filesize('.htaccess'));
    fclose($handle);
	$file_contents = str_replace("RewriteBase /","RewriteBase $SCRIPT_NAME", $file_contents);
    $handle = fopen('.htaccess', 'w');
    fputs($handle, $file_contents);
    fclose($handle);
}

function upgrade_progress_output($pagetitle = '', $total_rows = 0, $fetched_rows = 0, $start = 0, $finish_page = '', $in_progress_page = '', $total_progress = 0, $total_proccess = 500)
{
	global $nuke_configs, $cache, $install_options, $nuke_languages;

	$percent = ($fetched_rows == 0 || ($fetched_rows > 0 && $fetched_rows < $total_proccess && $start != 0) || ($fetched_rows > 0 && $fetched_rows == $total_rows)) ? 100:(int)((($fetched_rows+$start)/$total_rows)*100);
	
	upgrade_header(7, 95);
	echo"<div class=\"wizard-card\">
		<h3>".$nuke_languages['_CMS_UPGRADE']."</h3>";
		
		if($finish_page != '')
		{
		echo"<div class=\"wizard-input-section\">
			<p>".$nuke_languages['_INSTALL_UPDATING_PATIENT']."<br /><br /></p>";
			
			if($fetched_rows == 0 || ($fetched_rows > 0 && $fetched_rows < $total_proccess && $start != 0) || ($fetched_rows > 0 && $fetched_rows == $total_rows))
			{
				if($finish_page != '')
					echo"<meta http-equiv=\"refresh\" content=\"5;URL='$finish_page'\" />";
			}
			else
			{
				if($in_progress_page != '')
					echo"<meta http-equiv=\"refresh\" content=\"5;URL='$in_progress_page'\" />";
			}
			echo"$pagetitle
			<div class=\"progress\">
				<div class=\"progress-bar progress-bar-info progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"$percent\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:$percent%\"></div>
			</div>
			".$nuke_languages['_INSTALL_TOTAL_UPDATE_PROGRESS']."
			<div class=\"progress\">
				<div class=\"progress-bar progress-bar-primary progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"$total_progress\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:$total_progress%\"></div>
			</div>
		</div>";
		}
		else
		{
			echo"<div class=\"wizard-success\">
				<div class=\"alert alert-success\">
					<span class=\"create-server-name\"></span>".sprintf($nuke_languages['_INSTALL_FINISH_INSTALL'], (($install_options['mode'] == 'install') ? $nuke_languages['_INSTALL_FINISH_INSTALLED']:$nuke_languages['_INSTALL_FINISH_UPDATED']))."
				</div> 
				<a class=\"btn btn-default\" href=\"".$install_options['admininfo']['admin_filename'].".php\">".$nuke_languages['_LOGIN_TO_ADMIN']."</a>  
				<a class=\"btn btn-default\" href=\"".$install_options['siteinfo']['nukeurl']."\">".$nuke_languages['_SHOW_FRONTPAGE']."</a> ";
				if(isset($install_options['db_info']['db_have_forum']) && $install_options['db_info']['db_have_forum'] == 1)
				{
					echo"<a class=\"btn btn-default\" target=\"_blank\" href=\"".$install_options['siteinfo']['nukeurl'].$install_options['db_info']['db_forumpath']."/install/\">".(($install_options['mode'] == 'install') ? $nuke_languages['_INSTALL_INSTALL']:$nuke_languages['_INSTALL_UPGRADE'])." ".$nuke_languages['_INSTALL_FORUM']."</a>";
				}
			echo"
			<div align=\"center\" style=\"margin-top:30px;\"><a href=\"http://www.phpnuke.ir/Donate_us/\"><button type=\"button\" class=\"btn btn-info\"><i class=\"fa fa-usd\"></i> ".$nuke_languages['_INSTALL_DONATEUS']." <i class=\"fa fa-usd\"></i></button></a></div>
			</div>";
		}
	echo"</div>";
	upgrade_footer();

}

function upgrade_first()
{
	global $db, $nuke_configs, $cache, $install_options, $install_fields, $pn_dbcharset, $nuke_languages;

	$languages_list = array();
	$all_languages = get_dir_list('language', "files", true);
	foreach($all_languages as $language)
	{
		if($language == 'index.html' || $language == '.htaccess' || $language == 'alphabets.php') continue;
		$language = str_replace(".php", "", $language);
		$languages_list[] = $language;
	}
	
	$default_admin = "admin";
	$default_admin_pwds = array();
	// update nuke_authors
	$insert_query = array();
	$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_authors`");
	
	if(isset($install_fields) && isset($install_fields['pwd']) && !empty($install_fields['pwd']))
	{
		if(!$cache->isCached('install_options'))
		{
			steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
		}
		
		$install_options = $cache->retrieve('install_options');
		$install_options = phpnuke_unserialize($install_options);
		$install_options['admininfo']['pwd'] = $install_fields['pwd'];
		$cache->store("install_options", phpnuke_serialize($install_options));
	}	
	
	if(intval($result->count()) > 0)
	{
		$rows = $result->results();
		
		foreach($rows as $row)
		{
			if($row['name'] == "God")
			{
				$default_admin_pwds[] = $row['pwd'];
				$default_admin = $row['aid'];
				$cache->store('default_admin', $default_admin);
			}
			$insert_query[] = array($row['aid'],$row['name'],$row['url'],$row['email'],$row['pwd'],$row['counter'],$row['radminsuper'],$row['admlanguage'],$row['aadminsuper']);
		}
		
		if($install_options['mode'] == 'upgrade' && !empty($default_admin_pwds) && !in_array(md5($install_options['admininfo']['pwd']), $default_admin_pwds))
		{
			upgrade_header(7, 95);
			echo"
			<form role=\"form\" class=\"form-horizontal\" id=\"nukeform\" action=\"install.php?op=first\" method=\"post\">
				<div class=\"wizard-card-container\" style=\"height: 326px;\">
					<div class=\"wizard-card\" data-cardname=\"group\">
						".$nuke_languages['_CMS_UPGRADE']."
						<div class=\"wizard-error\">
							<div class=\"alert alert-danger\">	<strong>".$nuke_languages['_ERROR']." :</strong> ".$nuke_languages['_INSTALL_INCORRECT_OLD_ADMINPASS']."</div>
						</div>
						<div class=\"wizard-input-section\">
							<div class=\"form-group\">
								<label class=\"control-label col-sm-4\" for=\"admin_password\">".$nuke_languages['_INSTALL_OLD_ADMIN_PASS'].":</label>
								<div class=\"col-sm-8\">
									<input type=\"password\" class=\"form-control\" id=\"admin_password\" name=\"install_fields[pwd]\" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class=\"wizard-footer\">
					<div class=\"wizard-buttons-container\">
						<div class=\"btn-group-single pull-"._ALIGN2."\">
							<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>
							<input class=\"btn wizard-next btn-primary\" type=\"submit\" value=\"".$nuke_languages['_NEXT']."\" />
						</div>
					</div>
				</div>
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
			</form>";
			upgrade_footer();
			die();
		}
	
		$db->query("set names '$pn_dbcharset'");
		if(isset($insert_query) && !empty($insert_query))
			$db->table(AUTHORS_TABLE)->multiinsert(array("aid","name","url","email","pwd","counter","radminsuper","admlanguage","aadminsuper"),$insert_query);
	}

	// update nuke_blocks AND nuke_messages
	$db->query("set names 'latin1'");
	$insert_query1 = array();
	$box_blocks_data = array();
	$top_center_weight = 1;
	$last_bid = 0;
	$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_blocks` ORDER BY bposition ASC, weight ASC");
	if(intval($result->count()) > 0)
	{
		$rows = $result->results();
		foreach($rows as $row)
		{
			if($row['blockfile'] != '' && !file_exists("blocks/".$row['blockfile'].""))
				continue;
			
			$last_bid = max($last_bid, $row['bid']);
			
			$lang_titles = array();
			$result2 = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_blocks_titles` WHERE bid = '".$row['bid']."'");
			if(intval($result2->count()) > 0)
			{
				$rows2 = $result2->results();
				foreach($rows2 as $row2)
				{
					if(isset($row2['btitle']))
						$lang_titles[$row2['lang']] = $row2['btitle'];
				}
			}
			
			$box_id = "right";
			
			switch($row['bposition'])
			{
				case"r":
					$box_id = "left";
				break;
				case"c":
					$box_id = "topcenter";
				break;
				case"d":
					$box_id = "bottomcenter";
				break;
				default:
					$box_id = "right";
				break;
			}
			
			$top_center_weight = max($top_center_weight, $row['weight']);
			
			$box_blocks_data[$box_id][$row['bid']] = array(
				"title" => $row['title'],
				"lang_titles" => ((!empty($lang_titles)) ? phpnuke_serialize($lang_titles):""),
				"blanguage" => $row['blanguage'],
				"weight" => $row['weight'],
				"active" => $row['active'],
				"time" => $row['time'],
				"permissions" => $row['view'],
				"publish" => 0,
				"expire" => 0,
				"action" => "d",
				"theme_block" => '',
			);
			
			$insert_query1[] = array($row['bid'],$row['title'],$row['content'],$row['url'],$row['refresh'],$row['blockfile']);
		}
		
		$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_message`");
		if(intval($result->count()) > 0)
		{	
			$rows = $result->results();
			foreach($rows as $row)
			{
				$lang_titles = array();
				foreach($languages_list as $languages_name)
				{
					$lang_titles[$languages_name] = $row['title'];
				}
				
				$last_bid++;
				$box_id = "topcenter";
				$top_center_weight++;
				$box_blocks_data[$box_id][$last_bid] = array(
					"title" => $row['title'],
					"lang_titles" => ((!empty($lang_titles)) ? phpnuke_serialize($lang_titles):""),
					"blanguage" => $row['mlanguage'],
					"weight" => $top_center_weight,
					"active" => $row['active'],
					"time" => $row['date'],
					"permissions" => $row['view'],
					"publish" => 0,
					"expire" => $row['expire'],
					"action" => "d",
					"theme_block" => '',
				);
				
				$insert_query1[] = array($last_bid, $row['title'],$row['content'], '', '', '');
			}
		}
		
		$db->query("set names '$pn_dbcharset'");
		if(isset($insert_query1) && !empty($insert_query1))
			$blocks_insert = $db->table(BLOCKS_TABLE)->multiinsert(array("bid","title","content","url","refresh","blockfile"),$insert_query1);

		if($blocks_insert && isset($box_blocks_data) && !empty($box_blocks_data))
		{
			foreach($box_blocks_data as $box_id => $box_block_data)
			{
				$db->table(BLOCKS_BOXES_TABLE)
					->where('box_id', $box_id)
					->update(array(
						'box_blocks' => implode(",", array_keys($box_block_data)),
						'box_blocks_data' => phpnuke_serialize($box_block_data),
					));
			}
		}
	}
	
	// update nuke_config	
	$when_query = array();
	$insert_query = array();
	$params_index = array();
	$query_IN = array();
	$feedback_configs = (isset($nuke_configs['feedbacks']) && !empty($nuke_configs['feedbacks']) && !is_array($nuke_configs['feedbacks'])) ? phpnuke_unserialize($nuke_configs['feedbacks']):$nuke_configs['feedbacks'];
	$feedback_configs['depts'] = array();
	
	$db->query("set names 'latin1'");
	$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_config`");
	if(intval($result->count()) > 0)
	{
		$old_rows = $result->results()[0];
		
		$feedback_depts = array();
		$result2 = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_feedback_depts`");
		if(intval($result2->count()) > 0)
		{
			$rows2 = $result2->results();

			if(!empty($rows2))
			{
				foreach($rows2 as $row2)
					$feedback_configs['depts'][$row2['did']] = array($row2['dname'], $row2['demail'], $row2['dresponsibility']);
			}
			$feedback_configs = phpnuke_serialize($feedback_configs);
			$when_query['feedbacks'] = "WHEN config_name = 'feedbacks' THEN :".$feedback_configs."";
			$when_query_val[":feedbacks"] = $feedback_configs;
			$params_index[] = "?";
			$query_IN[] = 'feedbacks';
		}
		
		if(isset($old_rows) && !empty($old_rows))
		{
			foreach($old_rows as $config_key => $config_val)
			{
				$when_query[$config_key] = "WHEN config_name = '".$config_key."' THEN :".$config_key."";
				$when_query_val[":".$config_key.""] = $config_val;
				$params_index[] = "?";
				$query_IN[] = $config_key;
			}
		
			if(!empty($when_query))
			{
				$when_query = implode("\n", $when_query);
				$params_index = implode(" , ", $params_index);
				$params = array_merge($when_query_val, $query_IN);
				
				$db->query("set names '$pn_dbcharset'");
				$db->query("UPDATE ".CONFIG_TABLE." SET config_value = CASE 
					$when_query
				END
				WHERE config_name IN ($params_index)", $params);
			}
			
			$nuke_configs = array();
			$config_result = $db->table(CONFIG_TABLE)
								->select();
			if(intval($config_result->count()) > 0)
			{
				$new_rows = $config_result->results();
				foreach($new_rows as $row)
				{
					$key = $row['config_name'];
					$val = $row['config_value'];
					$nuke_configs[$key] = $val;
				}
			}
			
			foreach($old_rows as $config_key => $config_val)
			{
				if(!isset($nuke_configs[$config_key]))
				{
					$insert_query[] = array($config_key, $config_val);
				}
			}
			
			$db->query("set names '$pn_dbcharset'");
			if(isset($insert_query) && !empty($insert_query))
				$db->table(CONFIG_TABLE)->multiinsert(array("config_name","config_value"),$insert_query);
		}
	}
	$db->table(CONFIG_TABLE)
		->where('config_name', 'Default_Theme')
		->update([
			"config_value" => "Mashhadteam-Caspian"
		]);
	$db->table(CONFIG_TABLE)
		->where('config_name', 'multilingual')
		->update([
			"config_value" => "1"
		]);

	// update nuke_headlines
	$db->query("set names 'latin1'");
	$insert_query = array();
	$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_headlines`");
	if(intval($result->count()) > 0)
	{
		$rows = $result->results();
		$hid = 0;
		foreach($rows as $row)
		{
			$hid = $row['hid'];
			$insert_query[] = array($row['hid'],$row['sitename'],$row['headlinesurl']);
		}
		$insert_query[] = array(($hid+1), 'phpnuke', 'http://www.phpnuke.ir/feed/');
		
		$db->query("set names '$pn_dbcharset'");
		if(isset($insert_query) && !empty($insert_query))
			$db->table(HEADLINES_TABLE)->multiinsert(array("hid","sitename","headlinesurl"),$insert_query);
	}

	// update nuke_modules
	/*$insert_query = array();
	$main_module_selected = false;
	$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
	$nuke_modules_cacheData_by_title = phpnuke_array_change_key($nuke_modules_cacheData, "mid", "title");
	$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_modules`");
	if(intval($result->count()) > 0)
	{
		$rows = $result->results();
		foreach($rows as $row)
		{
			$row['main_module'] = 0;
			if(!$main_module_selected)
			{
				$result2 = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_main`");
				if(intval($result2->count()) > 0)
				{
					$row2 = $result2->results();
					if(isset($row2[0]) && $row2[0] != '' && $row2[0] == $row['title'])
					{
						$row['main_module'] = 1;
						$main_module_selected = true;
					}
				}
			}
			
			if(!array_key_exists($row['title'], $nuke_modules_cacheData_by_title))
				$insert_query[] = array($row['mid'],$row['title'],$row['active'],$row['view'],$row['admins'],$row['leftblock'],$row['main_module'],$row['inmenu']);
		}
		
		if(isset($insert_query) && !empty($insert_query))
			$db->table(MODULES_TABLE)->multiinsert(array("mid","title","active","mod_permissions","admins","all_blocks","main_module","in_menu"),$insert_query);
	}*/

	// update nuke_groups_info
	$db->query("set names '$pn_dbcharset'");
	$when_query = array();
	$result = $db->query("SELECT g.*, p.points FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_groups_info` as g LEFT JOIN `".OLD_DB."`.`".OLD_DB_PREFIX."_groups_points` as p ON g.id = p.id ORDER BY g.id ASC");
	if(intval($result->count()) > 0)
	{
		$rows = $result->results();
		
		foreach($rows as $row)
			$when_query[$row['id']] = "WHEN id = '".$row['id']."' THEN ".$row['points']."";
			
		$db->query("set names '$pn_dbcharset'");
		if(!empty($when_query))
		{
			$ids = array_keys($when_query);
			$ids = implode(", ", $ids);
			$when_query = implode("\n", $when_query);
			$db->query("UPDATE ".POINTS_GROUPS_TABLE." SET points = CASE 
				$when_query
			END
			WHERE id IN($ids)");
		}
	}
	
	$result1 = $db->query("SELECT SUM(g_hits) as total_hits FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_stats_hour` WHERE g_year != '0'");
	if(intval($result1->count()) > 0)
	{
		$results = $result1->results();
		$total_hits = intval($results[0]['total_hits']);
	}
	
	$result2 = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_counter");
	if(intval($result2->count()) > 0)
	{
		$rows2 = $result2->results();
		if(!empty($rows2))
		{
			
			$total = $WebTV = $Lynx = $MSIE = $Opera = $Konqueror = $Netscape = $FireFox = $Bot = $Other = $Windows = $Linux = $Mac = $FreeBSD = $SunOS = $IRIX = $BeOS = $OS2 = $AIX = $Other = 0;
			
			foreach($rows2 as $row2)
			{
				if($row2['type'] == 'total')
					$total = intval($row2['count']);
					
				if($row2['type'] == 'browser' && $row2['var'] == 'WebTV')
					$WebTV = intval($row2['count']);
					
				if($row2['type'] == 'browser' && $row2['var'] == 'Lynx')
					$Lynx = intval($row2['count']);
					
				if($row2['type'] == 'browser' && $row2['var'] == 'MSIE')
					$MSIE = intval($row2['count']);
					
				if($row2['type'] == 'browser' && $row2['var'] == 'Opera')
					$Opera = intval($row2['count']);
					
				if($row2['type'] == 'browser' && $row2['var'] == 'Konqueror')
					$Konqueror = intval($row2['count']);
					
				if($row2['type'] == 'browser' && $row2['var'] == 'Netscape')
					$Netscape = intval($row2['count']);
					
				if($row2['type'] == 'browser' && $row2['var'] == 'FireFox')
					$FireFox = intval($row2['count']);
					
				if($row2['type'] == 'browser' && $row2['var'] == 'Bot')
					$Bot = intval($row2['count']);
					
				if($row2['type'] == 'browser' && $row2['var'] == 'Other')
					$Other = intval($row2['count']);
					
				if($row2['type'] == 'os' && $row2['var'] == 'Windows')
					$Windows = intval($row2['count']);
					
				if($row2['type'] == 'os' && $row2['var'] == 'Linux')
					$Linux = intval($row2['count']);
					
				if($row2['type'] == 'os' && $row2['var'] == 'Mac')
					$Mac = intval($row2['count']);
					
				if($row2['type'] == 'os' && $row2['var'] == 'FreeBSD')
					$FreeBSD = intval($row2['count']);
					
				if($row2['type'] == 'os' && $row2['var'] == 'SunOS')
					$SunOS = intval($row2['count']);
					
				if($row2['type'] == 'os' && $row2['var'] == 'IRIX')
					$IRIX = intval($row2['count']);
					
				if($row2['type'] == 'os' && $row2['var'] == 'BeOS')
					$BeOS = intval($row2['count']);
					
				if($row2['type'] == 'os' && $row2['var'] == 'OS/2')
					$OS2 = intval($row2['count']);
					
				if($row2['type'] == 'os' && $row2['var'] == 'AIX')
					$AIX = intval($row2['count']);
					
				if($row2['type'] == 'os' && $row2['var'] == 'Other')
					$Other = intval($row2['count']);
			}
			
			$browsers_sum = $WebTV + $Lynx + $MSIE + $Opera + $Konqueror + $Netscape + $FireFox + $Bot + $Other;
			$oses_sum = $Windows + $Linux + $Mac + $FreeBSD + $SunOS + $IRIX + $BeOS + $OS2 + $AIX + $Other;
			
			$p_MSIE = ($MSIE/$browsers_sum)*100;
			$p_FireFox = ($FireFox/$browsers_sum)*100;
			$p_Opera = ($Opera/$browsers_sum)*100;
			$p_br_others = (($p_MSIE+$p_FireFox+$p_Opera) < 100) ? (100-($p_MSIE+$p_FireFox+$p_Opera)):0;
			
			$p_Windows = ($Windows/$oses_sum)*100;
			$p_Linux = ($Linux/$oses_sum)*100;
			$p_Mac = ($Mac/$oses_sum)*100;
			$p_os_others = (($p_Windows+$p_Linux+$p_Mac) < 100) ? (100-($p_Windows+$p_Linux+$p_Mac)):0;	

			$Msie_hits = round(($total_hits*$p_MSIE)/100);
			$Firefox_hits = round(($total_hits*$p_FireFox)/100);
			$Opera_hits = round(($total_hits*$p_Opera)/100);
			$Others_br_hits = round(($total_hits*$p_br_others)/100);

			$br_diff = ($total_hits - ($Msie_hits + $Firefox_hits + $Opera_hits + $Others_br_hits));
			if($br_diff != 0)
				$Others_br_hits = $Others_br_hits+$br_diff;
			
			$win7_hits = round(($total_hits*$p_Windows)/100);
			$Linux_hits = round(($total_hits*$p_Linux)/100);
			$MacOSX_hits = round(($total_hits*$p_Mac)/100);
			$Others_os_hits = round(($total_hits*$p_os_others)/100);
			$os_diff = ($total_hits - ($win7_hits + $Linux_hits + $MacOSX_hits + $Others_os_hits));
			if($os_diff != 0)
				$Others_os_hits = $Others_os_hits+$os_diff;
		}
	}
		
	$when_query = array();
	$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_mostonline");
	if(intval($result->count()) > 0)
	{
		$rows = $result->results();
		$row = $rows[0];		
		
		$when_query[] = "WHEN `type` = 'browser' AND var = 'Msie' THEN ".$Msie_hits."";
		$when_query[] = "WHEN `type` = 'browser' AND var = 'Firefox' THEN ".$Firefox_hits."";
		$when_query[] = "WHEN `type` = 'browser' AND var = 'Opera' THEN ".$Opera_hits."";
		$when_query[] = "WHEN `type` = 'browser' AND var = 'Others' THEN ".$Others_br_hits."";
		$when_query[] = "WHEN `type` = 'os' AND var = 'win 7' THEN ".$win7_hits."";
		$when_query[] = "WHEN `type` = 'os' AND var = 'Linux' THEN ".$Linux_hits."";
		$when_query[] = "WHEN `type` = 'os' AND var = 'Mac OS X' THEN ".$MacOSX_hits."";
		$when_query[] = "WHEN `type` = 'os' AND var = 'Others' THEN ".$Others_os_hits."";
		$when_query[] = "WHEN `type` = 'total' THEN ".$total_hits."";
		$when_query[] = "WHEN `type` = 'mosts' AND var = 'total' THEN ".$row['total']."";
		$when_query[] = "WHEN `type` = 'mosts' AND var = 'members' THEN ".$row['members']."";
		$when_query[] = "WHEN `type` = 'mosts' AND var = 'guests' THEN ".$row['nonmembers']."";
		
		if(!empty($when_query))
		{
			$when_query = implode("\n", $when_query);
			$db->query("UPDATE ".STATISTICS_COUNTER_TABLE." SET `count` = CASE 
				$when_query
			END
			WHERE `type` = 'mosts' OR `type` = 'total' OR `type` = 'os'  OR `type` = 'browser' AND var IN('total', 'members', 'guests', 'win 7','Linux','Opera','Others','Msie','Firefox','Opera')");
		}
	}

	$insert_query = array();
	$db->query("set names 'latin1'");
	$result = $db->query("SELECT
		p.*,
		GROUP_CONCAT(CONCAT(pd.optionText) SEPARATOR '|') as optionTextlist,
		GROUP_CONCAT(CONCAT(pd.optionCount) SEPARATOR '|') as optionCountlist,
		SUM(CONCAT(pd.optionCount) ) as optionCountSum
	FROM
		`".OLD_DB."`.`".OLD_DB_PREFIX."_poll_desc` as p
	INNER JOIN `".OLD_DB."`.`".OLD_DB_PREFIX."_poll_data` as pd ON p.pollID = pd.pollID AND optionText != ''
	GROUP BY p.pollID
	ORDER BY p.pollID ASC
	");

	if(intval($result->count()) > 0)
	{
		$rows = $result->results();
		
		foreach($rows as $key => $row)
		{
			$optionTextlist = explode("|", $row['optionTextlist']);
			$optionCountlist = explode("|", $row['optionCountlist']);
			
			$options = array();
			
			if(isset($optionTextlist) && !empty($optionTextlist))
			{
				foreach($optionTextlist as $op_key => $optionText)
				{
					if($optionText != '' && isset($optionCountlist[$op_key]))
						$options[] = array($optionText, $optionCountlist[$op_key]);
				}
			}
			$options = phpnuke_serialize($options);
			
			$pollUrl = trim(sanitize(str2url($row['pollTitle'])), "-");
			$pollUrl = get_unique_post_slug(SURVEYS_TABLE, "pollID", $row['pollID'], "pollUrl", $pollUrl, 'publish');
		
			$status = ($key == (sizeof($rows)-1)) ? 1:0;
			$insert_query[] = array($row['pollID'], $status, $default_admin, 1, 0, $row['pollTitle'],$pollUrl,$row['planguage'],$row['optionCountSum'],0,1,$row['comments'], 0, 0, 0, $options);
		}
			
		if(isset($insert_query) && !empty($insert_query))
		{
			$db->query("set names '$pn_dbcharset'");
			$db->table(SURVEYS_TABLE)->multiinsert(array("pollID","status","aid","canVote","main_survey","pollTitle","pollUrl","planguage","voters","to_main","allow_comment","comments","multi_vote","show_voters_num","permissions","options"), $insert_query);
			$db->query("ALTER TABLE `".OLD_DB."`.`".OLD_DB_PREFIX."_poll_check` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`); ");
			$db->query("INSERT INTO `".SURVEYS_CHECK_TABLE."` SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_poll_check`;");
		}
	}
	
	upgrade_header(7, 95);
	echo"<div class=\"wizard-card\">
		<h3>".$nuke_languages['_CMS_UPGRADE']."</h3>
		<div class=\"wizard-input-section\">
			<p>".$nuke_languages['_INSTALL_UPDATING_PATIENT']."<br /><br /></p>
			".$nuke_languages['_INSTALL_UPDATING_SOME_TABLES']."
			<div class=\"progress\">
				<div class=\"progress-bar progress-bar-info progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\"></div>
			</div>
			".$nuke_languages['_INSTALL_TOTAL_UPDATE_PROGRESS']."
			<div class=\"progress\">
				<div class=\"progress-bar progress-bar-primary progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"8\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:8%\"></div>
			</div>
		</div>
	</div>
	<meta http-equiv=\"refresh\" content=\"5;URL='install.php?op=cateogories'\" />";
	upgrade_footer();
}

function upgrade_cateogories()
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $module, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	// update nuke_categories for articles
	$db->query("set names 'latin1'");
	$insert_query = array();
	$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_topics`");
	if(intval($result->count()) > 0)
	{
		$rows = $result->results();
		foreach($rows as $row)
		{
			$insert_query[] = array($row['topicid'],0,'Articles',$row['topicname'],$row['topicimage'],$row['topictext'],$row['topictext'],$row['parent_id'],$row['topicid']);
		}
		$insert_query[] = array(0, 1, 'Articles', 'uncategorized', '', 'uncategorized', 'uncategorized', 0, 0);
	}
	
	// update nuke_categories for downloads
	$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_downloads_categories`");
	if(intval($result->count()) > 0)
	{
		$insert_query[] = array(0, 1, 'Downloads', 'uncategorized', '', 'uncategorized', 'uncategorized', 0, 0);
		$rows = $result->results();
		foreach($rows as $row)
		{
			$insert_query[] = array(0, 0,'Downloads',$row['title'],'',$row['cdescription'],$row['cdescription'],$row['parentid'],$row['cid']);
		}
	}
	
	// update nuke_categories for faqs
	$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_faqcategories`");
	if(intval($result->count()) > 0)
	{
		$insert_query[] = array(0, 1, 'Faqs', 'uncategorized', '', 'uncategorized', 'uncategorized', 0, 0);
		$rows = $result->results();
		foreach($rows as $row)
		{
			$insert_query[] = array(0, 0,'Faqs',$row['categories'],'',$row['categories'],$row['categories'],0,$row['id_cat']);
		}
	}
	
	// update nuke_categories for Pages
	$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_pages_categories`");
	if(intval($result->count()) > 0)
	{
		$insert_query[] = array(0, 1, 'Pages', 'uncategorized', '', 'uncategorized', 'uncategorized', 0, 0);
		$rows = $result->results();
		foreach($rows as $row)
		{
			$insert_query[] = array(0, 0,'Pages',$row['title'],'',$row['title'],$row['description'],0,$row['cid']);
		}
	}
	
	// update nuke_categories for Products
	/*$result = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_products_topics`");
	if(intval($result->count()) > 0)
	{
		$insert_query[] = array(0, 1, 'Products', 'uncategorized', '', 'uncategorized', 'uncategorized', 0, 0);
		$rows = $result->results();
		foreach($rows as $row)
		{
			$insert_query[] = array(0, 0,'Products',$row['topicname'],$row['topicimage'],$row['topictext'],$row['topictext'],$row['parent_id'],$row['topicid']);
		}
	}*/
	
	// update nuke_categories for Gallery
	$insert_query[] = array(0, 1, 'Gallery', 'uncategorized', '', 'uncategorized', 'uncategorized', 0, 0);
	$insert_query[] = array(0, 0, 'Gallery', 'gallery', '', 'gallery', 'gallery', 0, 0);
	
	$insert_query[] = array(0, 1, 'Statics', 'uncategorized', '', 'uncategorized', 'uncategorized', 0, 0);
	$insert_query[] = array(0, 0, 'Statics', 'statics', '', 'statics', 'statics', 0, 0);

	$db->query("set names '$pn_dbcharset'");
	if(isset($insert_query) && !empty($insert_query))
		$db->table(CATEGORIES_TABLE)->multiinsert(array("catid","type","module","catname","catimage","cattext","catdesc","parent_id","imported_id"),$insert_query);
	
	upgrade_header(7, 95);
	echo"<div class=\"wizard-card\">
		<h3>".$nuke_languages['_CMS_UPGRADE']."</h3>
		<div class=\"wizard-input-section\">
			<p>".$nuke_languages['_INSTALL_UPDATING_PATIENT']."<br /><br /></p>
			".$nuke_languages['_INSTALL_TRANSFER_CONTENTS']."
			<div class=\"progress\">
				<div class=\"progress-bar progress-bar-info progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:100%\"></div>
			</div>
			".$nuke_languages['_INSTALL_TOTAL_UPDATE_PROGRESS']."
			<div class=\"progress\">
				<div class=\"progress-bar progress-bar-primary progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"6\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width:6%\"></div>
			</div>
		</div>
	</div>
	<meta http-equiv=\"refresh\" content=\"5;URL='install.php?op=feedbacks'\" />";
	upgrade_footer();
}

function upgrade_feedbacks($start)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $transfer_counter, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$run_per_step = 500;
	$transfer_counter = (isset($transfer_counter) && $transfer_counter != 0) ? $transfer_counter:$run_per_step;
	// update nuke_feedbacks
	$db->query("set names 'latin1'");
	$insert_query = array();
	$result = $db->query("SELECT *, (SELECT COUNT(fid) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_feedbacks`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_feedbacks` ORDER BY fid ASC LIMIT $start, $transfer_counter");
	$fetched_rows = intval($result->count());
	if($fetched_rows > 0)
	{
		$rows = $result->results();
		
		if(!empty($rows))
			$total_rows_set = false;
			$timer = 1;
			foreach($rows as $row)
			{
				if(!$total_rows_set)
				{
					$cache->store('total_rows', intval($row['total_rows']));
					unset($row['total_rows']);
					$total_rows_set = true;
				}
				$insert_query[] = array($row['fid'],$row['sender_name'],$row['sender_email'],$row['subject'],$row['message'],$row['responsibility'],$row['replys'],(_NOWTIME+$timer));
				$timer++;
			}

		$db->query("set names '$pn_dbcharset'");
		if(isset($insert_query) && !empty($insert_query))
			$db->table(FEEDBACKS_TABLE)->multiinsert(array("fid","sender_name","sender_email","subject","message","responsibility","replys","added_time"),$insert_query);
	}
		
	$errors = $db->getErrors('last');
	if(isset($errors['message']) && ($errors['message'] == "MySQL server has gone away" || stristr($errors['message'], "max_allowed_packet")))
	{
		upgrade_header(7, 95);
		echo"
		<form role=\"form\" class=\"form-horizontal\" id=\"nukeform\" action=\"install.php?op=feedbacks".((isset($start) && $start != 0) ? "&start=$start":"")."\" method=\"post\">
			<div class=\"wizard-card-container\" style=\"height: 326px;\">
				<div class=\"wizard-card\" data-cardname=\"group\">
					<h3>".$nuke_languages['_CMS_UPGRADE']."</h3>
					<div class=\"wizard-error\">
						<div class=\"alert alert-danger\">	<strong>".$nuke_languages['_ERROR']." :</strong> ".sprintf($nuke_languages['_INSTALL_TRANSFER_CONTENTS_ERROR'], $transfer_counter)."</div>
					</div>
					<div class=\"wizard-input-section\">
						<div class=\"form-group\">
							<label class=\"control-label col-sm-4\" for=\"transfer_counter\">".$nuke_languages['_INSTALL_TRANSFERABLE_MESSAGES'].":</label>
							<div class=\"col-sm-8\">
								<input type=\"text\" class=\"form-control\" id=\"transfer_counter\" name=\"transfer_counter\" value=\"$transfer_counter\" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class=\"wizard-footer\">
				<div class=\"wizard-buttons-container\">
					<div class=\"btn-group-single pull-"._ALIGN2."\">
						<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>
						<input class=\"btn wizard-next btn-primary\" type=\"submit\" value=\"".$nuke_languages['_NEXT']."\" />
					</div>
				</div>
			</div>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
		</form>";
		upgrade_footer();
		die();
	}
	
	$new_start = $start+$transfer_counter;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_FEEDBACKS'], $total_rows, $fetched_rows, $start, "install.php?op=ipbans", "install.php?op=feedbacks&start=$new_start", 12, $transfer_counter);
}
/*
function upgrade_mtsn($start)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	// update nuke_mtsn
	$db->query("set names 'latin1'");
	$insert_query = array();
	$result = $db->query("SELECT *, (SELECT COUNT(id) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_mtsn`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_mtsn` ORDER BY id ASC LIMIT $start, 2000");
	$fetched_rows = intval($result->count());
	if($fetched_rows > 0)
	{
		$rows = $result->results();

		$total_rows_set = false;
		foreach($rows as $row)
		{
			if(!$total_rows_set)
			{
				$cache->store('total_rows', intval($row['total_rows']));
				unset($row['total_rows']);
				$total_rows_set = true;
			}
			$insert_query[] = array($row['id'],$row['server'],$row['ip'],$row['time'],$row['method']);
		}
		
		$db->query("set names '$pn_dbcharset'");
		if(isset($insert_query) && !empty($insert_query))
			$db->table(MTSN_TABLE)->multiinsert(array("id","server","ip","time","method"),$insert_query);
	}
	
	$new_start = $start+2000;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output("انتقال اطلاعات حملات سایت", $total_rows, $fetched_rows, $start, "install.php?op=ipbans", "install.php?op=mtsn&start=$new_start", 32, 2000);
}
*/
function upgrade_ipbans($start)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	$run_per_step = 2000;
	// update nuke_mtsn_ipban
	$db->query("set names 'latin1'");
	$insert_query = array();
	$result = $db->query("SELECT *, (SELECT COUNT(id) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_mtsn_ipban`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_mtsn_ipban` ORDER BY id ASC LIMIT $start, $run_per_step");
	$fetched_rows = intval($result->count());
	if($fetched_rows > 0)
	{
		$rows = $result->results();

		$total_rows_set = false;
		foreach($rows as $row)
		{
			if(!$total_rows_set)
			{
				$cache->store('total_rows', intval($row['total_rows']));
				unset($row['total_rows']);
				$total_rows_set = true;
			}
			$insert_query[] = array($row['id'],'admin',$row['ipaddress'],$row['reason'],$row['time']);
		}
		$db->query("set names '$pn_dbcharset'");
		if(isset($insert_query) && !empty($insert_query))
			$db->table(MTSN_IPBAN_TABLE)->multiinsert(array("id","blocker","ipaddress","system","time"), $insert_query);
	}
	
	$new_start = $start+$run_per_step;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_BLOCKED_IPS'], $total_rows, $fetched_rows, $start, "install.php?op=reports", "install.php?op=ipbans&start=$new_start", 18, $run_per_step);
}

function upgrade_reports($start)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$run_per_step = 500;
	// update nuke_reports
	$db->query("set names 'latin1'");
	$insert_query = array();
	$result = $db->query("SELECT *, (SELECT COUNT(rid) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_reports` WHERE rcode = '1') as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_reports` WHERE rcode = '1' ORDER BY rid ASC LIMIT $start, $run_per_step");
	$fetched_rows = intval($result->count());
	if($fetched_rows > 0)
	{
		$rows = $result->results();
		
		$total_rows_set = false;
		foreach($rows as $row)
		{
			if(!$total_rows_set)
			{
				$cache->store('total_rows', intval($row['total_rows']));
				unset($row['total_rows']);
				$total_rows_set = true;
			}
			$insert_query[] = array($row['rid'], $row['sid'],$row['user_id'],$row['title'],$row['desc'],$row['time']);
		}
		
		$db->query("set names '$pn_dbcharset'");
		if(isset($insert_query) && !empty($insert_query))
			$db->table(REPORTS_TABLE)->multiinsert(array("rid","post_id","user_id","subject","message","time"), $insert_query);
	}
	
	$new_start = $start+$run_per_step;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_REPORTS'], $total_rows, $fetched_rows, $start, "install.php?op=scores", "install.php?op=reports&start=$new_start", 24, $run_per_step);
}

function upgrade_scores($start)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$run_per_step = 5000;
	// update nuke_scores
	$insert_query = array();
	$result = $db->query("SELECT s.*, u.user_id, (SELECT COUNT(id) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_stories_score`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_stories_score` as s LEFT JOIN `".OLD_DB."`.`".OLD_DB_PREFIX."_users` as u ON u.username = s.username ORDER BY s.id ASC LIMIT $start, $run_per_step");
	$fetched_rows = intval($result->count());
	if($fetched_rows > 0)
	{
		$rows = $result->results();

		$total_rows_set = false;
		foreach($rows as $row)
		{
			if(!$total_rows_set)
			{
				$cache->store('total_rows', intval($row['total_rows']));
				unset($row['total_rows']);
				$total_rows_set = true;
			}
			$insert_query[] = array($row['id'], $row['sid'],'Articles',$row['rating_ip'],$row['score'],$row['user_id'],$row['gust']);
		}
		
		if(isset($insert_query) && !empty($insert_query))
			$db->table(SCORES_TABLE)->multiinsert(array("id","post_id","db_table","rating_ip","score","user_id","gust"), $insert_query);
	}

	$new_start = $start+$run_per_step;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_REPORTS'], $total_rows, $fetched_rows, $start, "install.php?op=statistics", "install.php?op=scores&start=$new_start", 30, $run_per_step);
}

function upgrade_statistics($start = 0)
{
	global $db, $nuke_configs, $cache, $counter, $pn_dbcharset, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
		
	// update nuke_statistics
	$insert_query = array();
	$hourly_info = array();
	$counter = intval($counter);
	
	if($start == 0)
	{
		$db->query("DROP TABLE IF EXISTS `nuke_stats_hour`;");
		$db->query("CREATE TABLE IF NOT EXISTS `nuke_stats_hour` (
		  `j_year` smallint(6) NOT NULL default '0',
		  `j_month` tinyint(4) NOT NULL default '0',
		  `j_date` tinyint(4) NOT NULL default '0',
		  `j_hour` tinyint(4) NOT NULL default '0',
		  `j_hits` int(11) NOT NULL default '0',
		  `g_year` smallint(6) NOT NULL,
		  `g_month` tinyint(4) NOT NULL,
		  `g_date` tinyint(4) NOT NULL,
		  `g_hour` tinyint(4) NOT NULL,
		  `g_hits` int(11) NOT NULL,
		  `h_year` smallint(6) NOT NULL,
		  `h_month` tinyint(4) NOT NULL,
		  `h_date` tinyint(4) NOT NULL,
		  `h_hour` tinyint(4) NOT NULL,
		  `h_hits` int(11) NOT NULL
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

		$db->query("INSERT INTO `nuke_stats_hour` SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_stats_hour`;");
		$db->query("DELETE FROM `nuke_stats_hour` WHERE g_hits = '0'");
		$db->query("ALTER TABLE `nuke_stats_hour` ADD shid INT");
		$db->query("ALTER TABLE `nuke_stats_hour` MODIFY shid INT AUTO_INCREMENT PRIMARY KEY");
		$result1 = $db->query("SELECT shid FROM `nuke_stats_hour` ORDER BY shid DESC LIMIT 0,1");
		$results1 = $result1->results();
		$total_rows = $results1[0]['shid'];
		$cache->store('total_rows', intval($total_rows));
	}
	$run_per_step = 5000;
	$result = $db->query("SELECT shid, g_year, g_month, g_date, g_hour, g_hits FROM `nuke_stats_hour` WHERE shid > '$start' ORDER BY shid ASC LIMIT 0,$run_per_step");

	$fetched_rows = intval($result->count());
	if($fetched_rows > 0)
	{
		$rows = $result->results();

		$hours = 0;
		
		foreach($rows as $row)
		{		
			if($row['g_year'] != 0 && $row['g_month'] != 0 && $row['g_date'] != 0)
			{
				$statistics[$row['g_year']][$row['g_month']][$row['g_date']][$row['g_hour']] = $row['g_hits'];
				
				$start_date = $row['g_year']."-".$row['g_month']."-".$row['g_date']."-".$row['g_hour']."-".$row['shid'];
			}
		}
		
		$start_date = explode("-", $start_date);
		if($start_date[3] != 23)
		{
			$new_start = $start_date[4]-$start_date[3]-1;
			unset($statistics[$start_date[0]][$start_date[1]][$start_date[2]]);
		}
		else
			$new_start = $start_date[4];

		foreach($statistics as $year => $year_data)
		{
			foreach($year_data as $month => $month_data)
			{
				foreach($month_data as $day => $day_data)
				{
					$zero_hour_hits = 0;
					foreach($day_data as $hour => $hits)
					{
						$key = $year."-".$month."-".$day;
						
						if(!array_key_exists($zero_hour_hits, $day_data))
						{
							for($i=$zero_hour_hits;$i<$hour;$i++)
							{
								$hourly_info[$key][$i] = 0;
							}
						}
						$zero_hour_hits = $hour+1;
						
						$hourly_info[$key][$hour] = (int) $hits;
					}
				}
			}
		}
		unset($statistics);
		
		foreach($hourly_info as $hourly_info_key => $hourly_info_value)
		{
			$hourly_info_key = explode("-", $hourly_info_key);
			$hits = 0;
			foreach($hourly_info_value as $key => $hourly_val)
				$hits = (int) ($hits+$hourly_val);
				
			$hourly_info_value = (string) json_encode($hourly_info_value);
			$insert_query[] = array($hourly_info_key[0], $hourly_info_key[1],$hourly_info_key[2],$hourly_info_value,$hits);
		}
		
		if(isset($insert_query) && !empty($insert_query))
			$db->table(STATISTICS_TABLE)->multiinsert(array("year","month","day","hourly_info","hits"), $insert_query);
	}
	
	if($fetched_rows == 0 || ($fetched_rows > 0 && $fetched_rows < $run_per_step && $start != 0))
		$db->query("DROP TABLE IF EXISTS `nuke_stats_hour`;");
	
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_STATISTICS'], $total_rows, $fetched_rows, $start, "install.php?op=tags", "install.php?op=statistics&start=$new_start", 36, $run_per_step);
}

function upgrade_tags($start)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$run_per_step = 1000;
	// update nuke_tags
	$insert_query = array();
	$db->query("set names 'latin1'");
	$result = $db->query("SELECT *, (SELECT COUNT(tag_id) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_tags`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_tags` ORDER BY tag_id ASC LIMIT $start, $run_per_step");
	$fetched_rows = intval($result->count());
	if($fetched_rows > 0)
	{
		$rows = $result->results();
		
		$total_rows_set = false;
		foreach($rows as $row)
		{
			if(!$total_rows_set)
			{
				$cache->store('total_rows', intval($row['total_rows']));
				unset($row['total_rows']);
				$total_rows_set = true;
			}
			$insert_query[] = array($row['tag_id'], $row['tag'],$row['counter']);
		}
		
		$db->query("set names '$pn_dbcharset'");
		if(isset($insert_query) && !empty($insert_query))
			$db->table(TAGS_TABLE)->multiinsert(array("tag_id","tag","counter"), $insert_query);
	}
	
	$new_start = $start+$run_per_step;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_TAGS'], $total_rows, $fetched_rows, $start, "install.php?op=articles", "install.php?op=tags&start=$new_start", 42, $run_per_step);
}

function upgrade_articles($start)
{
	global $db, $nuke_configs, $cache, $transfer_counter, $pn_dbcharset, $pn_dbname, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$install_options = $cache->retrieve('install_options');
	$install_options = phpnuke_unserialize($install_options);
	
	$old_site_path = $install_options['admininfo']['old_site_path'];
	
	$run_per_step = 500;
	// update nuke_articles
	$db->query("set names 'latin1'");
	$insert_query = array();
	$query_add = array();
	$meta_keys = array();
	
	$default_cols = array("sid","aid","title","time","hometext","bodytext","newslevel","news_group","newsurl","comments","counter","topic","informant","notes","ihome","alanguage","acomm","haspoll","pollID","score","ratings","rating_ip","position","story_pass","topic_link");
	
	$transfer_counter = (isset($transfer_counter) && $transfer_counter != 0) ? $transfer_counter:$run_per_step;
		
	$result = $db->query("SELECT *, (SELECT COUNT(sid) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_stories`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_stories` ORDER BY sid ASC LIMIT $start, $transfer_counter");
	$fetched_rows = intval($result->count());
		
	if($fetched_rows > 0)
	{
		$rows = $result->results();

		if(!empty($rows))
		{
			$total_rows_set = false;
			foreach($rows as $row)
			{
				foreach($row as $col_name => $col_val)
				{
					if(!in_array($col_name, $default_cols) && $col_name != 'total_rows')
						$meta_keys[$row['sid']] = array($col_name, $col_val);
				}
			
				if(!$total_rows_set)
				{
					$cache->store('total_rows', intval($row['total_rows']));
					unset($row['total_rows']);
					$total_rows_set = true;
				}
				
				$newsurl = ($row['newsurl'] != '') ? $row['newsurl']:$row['title'];
				
				$post_url = trim(sanitize(str2url($newsurl)), "-");
				$post_url = get_unique_post_slug(POSTS_TABLE, "sid", $row['sid'], "post_url", $post_url, 'publish');
				
				$post_image = '';
				$old_post_image = "$old_site_path/files/News/".$row['sid'].".jpg";
				$new_post_image = "files/Articles/".$row['sid'].".jpg";
				if(file_exists($old_post_image))
				{
					if(copy($old_post_image, $new_post_image))
						$post_image = $new_post_image;
				}
				
				if($row['notes'] != '') 
					$row['notes'] = str_replace(":", ",", $row['notes']);
				$row['comments'] = ($row['comments'] < 0) ? 0:$row['comments'];
				$row['ihome'] = ($row['ihome'] == 0) ? 1:0;
				$row['acomm'] = ($row['acomm'] == 0) ? 1:0;
				
				$insert_query[] = array($row['sid'], 'publish', 'Articles', $row['aid'],$row['title'],$row['time'],$row['hometext'],$row['bodytext'],$post_url,$row['comments'],$row['counter'],$row['topic'],$row['informant'],$row['notes'],$row['ihome'],$row['alanguage'],$row['acomm'],$row['position'],$row['story_pass'],$row['topic_link'],$row['newslevel'],$row['score'],$row['ratings'],$post_image);
			}
			
			$db->query("set names '$pn_dbcharset'");
			
			$new_cols = array("sid","status","post_type","aid","title","time","hometext","bodytext","post_url","comments","counter","cat","informant","tags","ihome","alanguage","allow_comment","position","post_pass","cat_link","permissions","score","ratings","post_image");
			
			if(isset($insert_query) && !empty($insert_query))
				$db->table(POSTS_TABLE)->multiinsert($new_cols,$insert_query);

			if(isset($meta_keys) && !empty($meta_keys))
			{
				foreach($meta_keys as $sid => $meta_data)
					$insert_query[] = array($sid, $meta_data[0], $meta_data[1]);
				$db->table(POSTS_META_TABLE)->multiinsert(["post_id", "meta_key", "meta_value"],$insert_query);
			}			
		}
	}
	
	$errors = $db->getErrors('last');
	if(isset($errors['message']) && ($errors['message'] == "MySQL server has gone away" || stristr($errors['message'], "max_allowed_packet")))
	{
		upgrade_header(7, 95);
		echo"
		<form role=\"form\" class=\"form-horizontal\" id=\"nukeform\" action=\"install.php?op=articles".((isset($start) && $start != 0) ? "&start=$start":"")."\" method=\"post\">
			<div class=\"wizard-card-container\" style=\"height: 326px;\">
				<div class=\"wizard-card\" data-cardname=\"group\">
					<h3>".$nuke_languages['_CMS_UPGRADE']."</h3>
					<div class=\"wizard-error\">
						<div class=\"alert alert-danger\">	<strong>".$nuke_languages['_ERROR']." :</strong> ".sprintf($nuke_languages['_INSTALL_TRANSFER_CONTENTS_ERROR'], $transfer_counter)."</div>
					</div>
					<div class=\"wizard-input-section\">
						<div class=\"form-group\">
							<label class=\"control-label col-sm-4\" for=\"transfer_counter\">".$nuke_languages['_INSTALL_TRANSFERABLE_MESSAGES'].":</label>
							<div class=\"col-sm-8\">
								<input type=\"text\" class=\"form-control\" id=\"transfer_counter\" name=\"transfer_counter\" value=\"$transfer_counter\" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class=\"wizard-footer\">
				<div class=\"wizard-buttons-container\">
					<div class=\"btn-group-single pull-"._ALIGN2."\">
						<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>
						<input class=\"btn wizard-next btn-primary\" type=\"submit\" value=\"".$nuke_languages['_NEXT']."\" />
					</div>
				</div>
			</div>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
		</form>";
		upgrade_footer();
		die();
	}
	$cache->store('last_article_sid', $db->lastInsertid());
	
	$new_start = $start+$transfer_counter;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_POSTS'], $total_rows, $fetched_rows, $start, "install.php?op=staticpages", "install.php?op=articles&start=$new_start&transfer_counter=$transfer_counter", 48, $transfer_counter);
}

function upgrade_staticpages($start)
{
	global $db, $nuke_configs, $cache, $transfer_counter, $pn_dbcharset, $pn_dbname, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
		
	$cat_result = $db->table(CATEGORIES_TABLE)
						->where('module', 'Statics')
						->select(['catid']);
						
	if($cat_result->count() > 0)
		$statics_cat = $cat_result->results()[0]['catid'];
	
	$run_per_step = 500;
	// update nuke_articles
	$db->query("set names 'latin1'");
	$insert_query = array();
	
	$transfer_counter = (isset($transfer_counter) && $transfer_counter != 0) ? $transfer_counter:$run_per_step;

	$default_admin = $cache->retrieve('default_admin', false);
	
	$result = $db->query("SELECT *, (SELECT COUNT(pid) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_staticpages`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_staticpages` ORDER BY pid ASC LIMIT $start, $transfer_counter");
	$fetched_rows = intval($result->count());

	if($fetched_rows > 0)
	{
		$rows = $result->results();

		if(!empty($rows))
		{
			$total_rows_set = false;
			$timer = 1;
			foreach($rows as $row)
			{
				if(!$total_rows_set)
				{
					$cache->store('total_rows', intval($row['total_rows']));
					unset($row['total_rows']);
					$total_rows_set = true;
				}
				
				$post_url = trim(sanitize(str2url($row['title'])), "-");
				$post_url = get_unique_post_slug(POSTS_TABLE, "sid", $pid, "post_url", $post_url, 'publish', false, "AND post_type = 'Statics'");
				if($row['notes'] != '') 
					$row['notes'] = str_replace(":", ",", $row['notes']);
				$row['comments'] = ($row['comments'] < 0) ? 0:$row['comments'];
				$row['ihome'] =  1;
				$row['acomm'] = 1;
				$cat_link = $statics_cat;
				
				$insert_query[] = array($row['pid'], 'publish', 'Statics', $default_admin,$row['title'],(_NOWTIME+$timer),'',$row['text'],$post_url,$row['comments'],$row['counter'],$cat_link,$default_admin,$row['notes'],$row['ihome'],$row['alanguage'],$row['acomm'],1,'',$cat_link,0,0,0);
				$timer++;
			}
			
			$db->query("set names '$pn_dbcharset'");
			
			$new_cols = array("imported_id","status","post_type","aid","title","time","hometext","bodytext","post_url","comments","counter","cat","informant","tags","ihome","alanguage","allow_comment","position","post_pass","cat_link","permissions","score","ratings");
			
			if(isset($insert_query) && !empty($insert_query))
				$db->table(POSTS_TABLE)->multiinsert($new_cols,$insert_query);
		}
	}
	
	$errors = $db->getErrors('last');
	if(isset($errors['message']) && ($errors['message'] == "MySQL server has gone away" || stristr($errors['message'], "max_allowed_packet")))
	{
		upgrade_header(7, 95);
		echo"
		<form role=\"form\" class=\"form-horizontal\" id=\"nukeform\" action=\"install.php?op=staticpages".((isset($start) && $start != 0) ? "&start=$start":"")."\" method=\"post\">
			<div class=\"wizard-card-container\" style=\"height: 326px;\">
				<div class=\"wizard-card\" data-cardname=\"group\">
					".$nuke_languages['_CMS_UPGRADE']."
					<div class=\"wizard-error\">
						<div class=\"alert alert-danger\">	<strong>".$nuke_languages['_ERROR']." :</strong> ".sprintf($nuke_languages['_INSTALL_TRANSFER_CONTENTS_ERROR'], $transfer_counter)."</div>
					</div>
					<div class=\"wizard-input-section\">
						<div class=\"form-group\">
							<label class=\"control-label col-sm-4\" for=\"transfer_counter\">".$nuke_languages['_INSTALL_TRANSFERABLE_MESSAGES'].":</label>
							<div class=\"col-sm-8\">
								<input type=\"text\" class=\"form-control\" id=\"transfer_counter\" name=\"transfer_counter\" value=\"$transfer_counter\" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class=\"wizard-footer\">
				<div class=\"wizard-buttons-container\">
					<div class=\"btn-group-single pull-"._ALIGN2."\">
						<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>
						<input class=\"btn wizard-next btn-primary\" type=\"submit\" value=\"".$nuke_languages['_NEXT']."\" />
					</div>
				</div>
			</div>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
		</form>";
		upgrade_footer();
		die();
	}
	
	$new_start = $start+$transfer_counter;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_STATIGPAGES'], $total_rows, $fetched_rows, $start, "install.php?op=gallery", "install.php?op=staticpages&start=$new_start&transfer_counter=$transfer_counter", 54, $transfer_counter);
}

function upgrade_gallery($start)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $pn_dbname, $gallery_insert, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$install_options = $cache->retrieve('install_options');
	$install_options = phpnuke_unserialize($install_options);
	
	$default_admin = $cache->retrieve('default_admin', false);
	$old_site_path = $install_options['admininfo']['old_site_path'];
	
	if(!file_exists("files/Gallery/"))
		mkdir("files/Gallery/", 0777);
	
	$gallery_cat = 0;
	$gallery_insert = isset($gallery_insert) ? intval($gallery_insert):0;
	
	$cat_result = $db->table(CATEGORIES_TABLE)
						->where('module', 'Gallery')
						->select(['catid']);
						
	if($cat_result->count() > 0)
		$gallery_cat = $cat_result->results()[0]['catid'];

	$run_per_step = 20;
	// update Gallery
	$db->query("set names 'latin1'");
	$insert_query = array();
	if($cache->isCached("gallery_posts") && $start == 0 && $gallery_insert!= 1)
		$cache->erase('gallery_posts');
		
	$gallery_posts = ($cache->isCached('gallery_posts')) ? $cache->retrieve('gallery_posts', false):array();
	
	$result = $db->query("SELECT g.id_cat, g.categories as title, g.catpic as post_image, gp.dateadd as time, gp.picdec as hometext, gp.pictname, gp.picname, gp.count as counter,(SELECT COUNT(g2.id_cat) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_mtgalcat` as g2 LEFT JOIN `".OLD_DB."`.`".OLD_DB_PREFIX."_mtgalpic` as gp2 ON g2.id_cat = gp2.id_cat ) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_mtgalcat` as g LEFT JOIN `".OLD_DB."`.`".OLD_DB_PREFIX."_mtgalpic` as gp ON g.id_cat = gp.id_cat ORDER BY g.id_cat ASC LIMIT $start, $run_per_step");
	$fetched_rows = intval($result->count());

	if($fetched_rows > 0)
	{
		$rows = $result->results();

		if(!empty($rows))
		{
			$total_rows_set = false;
			foreach($rows as $row)
			{
				if(!$total_rows_set)
				{
					$cache->store('total_rows', intval($row['total_rows']));
					unset($row['total_rows']);
					$total_rows_set = true;
				}
				$id_cat = $row['id_cat'];
				
				if(!isset($gallery_posts[$id_cat]))
				{
					$gallery_posts[$id_cat]['title'] = $row['title'];
					$gallery_posts[$id_cat]['cat_id'] = $gallery_cat;
					$gallery_posts[$id_cat]['counter'] = 0;
					$gallery_posts[$id_cat]['download'] = array();
					$post_url = trim(sanitize(str2url($row['title'])), "-");
					$gallery_posts[$id_cat]['post_url'] = get_unique_post_slug(POSTS_TABLE, "sid", '', "post_url", $post_url, 'publish', false, "AND post_type = 'Gallery'");
					
					if(!file_exists("files/Gallery/".$id_cat.""))
						mkdir("files/Gallery/".$id_cat."", 0777);
						
					$old_post_image = "$old_site_path/modules/MT-Gallery/images/".$row['post_image']."";
					$new_post_image = "files/Gallery/".$id_cat."/".$row['post_image']."";
					if(file_exists($old_post_image))
					{
						if(copy($old_post_image, $new_post_image))
						{
							$gallery_posts[$id_cat]['post_image'] = $new_post_image;
						}
					}
					$gallery_posts[$id_cat]['time'] = (isset($row['time'])) ? $row['time']:_NOWTIME;
					$gallery_posts[$id_cat]['hometext'] = $row['hometext'];
				}
				$old_image_path = "$old_site_path/modules/MT-Gallery/images/".$row['pictname']."";
				$new_image_path = "files/Gallery/".$id_cat."/".$row['pictname']."";
				if(file_exists($old_image_path) && !is_dir($old_image_path))
				{
					if(copy($old_image_path, $new_image_path))
					{
						$gallery_posts[$id_cat]['counter']+=$row['counter'];
						$gallery_posts[$id_cat]['download'][] = array(
							$row['picname'],
							$new_image_path,
							filesize($new_image_path),
							'',
							'images'
						);
					}
				}
			}
			
			$cache->store('gallery_posts', $gallery_posts);
		}
	}
	
	if(isset($gallery_insert) && $gallery_insert== 1)
	{
		if(isset($gallery_posts) && !empty($gallery_posts))
		{
			foreach($gallery_posts as $imported_id => $row)
				$insert_query[] = array('NULL','publish','Gallery',$default_admin,$row['title'],$row['time'],$row['hometext'],'',$row['post_url'],0,$row['counter'],$row['cat_id'],$default_admin,'',1,'',1,1,'',$row['cat_id'],0,0,0, ((isset($row['download']) && !empty($row['download'])) ? phpnuke_serialize($row['download']):""), $imported_id);
				
			$db->query("set names '$pn_dbcharset'");
			$new_cols = array("sid","status","post_type","aid","title","time","hometext","bodytext","post_url","comments","counter","cat","informant","tags","ihome","alanguage","allow_comment","position","post_pass","cat_link","permissions","score","ratings", 'download', 'imported_id');
			
			if(isset($insert_query) && !empty($insert_query))
			{
				$db->table(POSTS_TABLE)->multiinsert($new_cols,$insert_query);
				
				$cache->erase('gallery_posts');
			}
		}	
		upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_GALLERY'], 100, 0, 0, "install.php?op=downloads", "", 60, 100);
		die();
	}
	
	$new_start = $start+$run_per_step;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_GALLERY'], $total_rows, $fetched_rows, $start, "install.php?op=gallery&gallery_insert=1", "install.php?op=gallery&start=$new_start", 60, $run_per_step);
}

function upgrade_downloads($start)
{
	global $db, $nuke_configs, $cache, $transfer_counter, $pn_dbcharset, $pn_dbname, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$download_cats = array();
	
	$cat_result = $db->table(CATEGORIES_TABLE)
						->where('module', 'Downloads')
						->select(['catid', 'parent_id', 'imported_id']);
	if($cat_result->count() > 0)
	{
		$cat_rows = $cat_result->results();
		foreach($cat_rows as $cat_row)
		{
			$download_cats[$cat_row['imported_id']] = array('catid' => $cat_row['catid'], 'parent_id' => $cat_row['parent_id']);
		}
	}	
	
	$run_per_step = 500;
	// update nuke_articles
	$db->query("set names 'latin1'");
	$insert_query = array();
	
	$transfer_counter = (isset($transfer_counter) && $transfer_counter != 0) ? $transfer_counter:$run_per_step;
	
	$last_article_sid = $cache->retrieve('last_article_sid');

	$default_admin = $cache->retrieve('default_admin', false);
	
	$result = $db->query("SELECT *, (SELECT COUNT(lid) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_downloads_downloads`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_downloads_downloads` ORDER BY lid ASC LIMIT $start, $transfer_counter");
	$fetched_rows = intval($result->count());

	if($fetched_rows > 0)
	{
		$rows = $result->results();

		if(!empty($rows))
		{
			$total_rows_set = false;
			foreach($rows as $row)
			{
				if(!$total_rows_set)
				{
					$cache->store('total_rows', intval($row['total_rows']));
					unset($row['total_rows']);
					$total_rows_set = true;
				}
				
				$cat_link = (isset($download_cats[$row['cid']]['catid'])) ? $download_cats[$row['cid']]['catid']:0;
				
				$post_url = trim(sanitize(str2url($row['title'])), "-");
				$post_url = get_unique_post_slug(POSTS_TABLE, "sid", '', "post_url", $post_url, 'publish', false, "AND post_type = 'downloads'");
				$download = array();
				$download[] = array($nuke_languages['_FILE'],$row['url'],$row['filesize'], $nuke_languages['_INSTALL_VERSION']." ".(($row['version'] != 0) ? $row['version']:1), 'files');
				
				$insert_query[] = array($row['lid'], 'publish', 'Downloads', $default_admin,$row['title'],$row['date'],$row['description'],'',$post_url,0,$row['hits'],$cat_link,$default_admin,'',1,'',1,1,'',$cat_link,0,0,0, phpnuke_serialize($download));
			}
			
			$db->query("set names '$pn_dbcharset'");
			
			$new_cols = array("imported_id","status","post_type","aid","title","time","hometext","bodytext","post_url","comments","counter","cat","informant","tags","ihome","alanguage","allow_comment","position","post_pass","cat_link","permissions","score","ratings","download");
			
			if(isset($insert_query) && !empty($insert_query))
				$db->table(POSTS_TABLE)->multiinsert($new_cols,$insert_query);
		}
	}
	
	$errors = $db->getErrors('last');
	if(isset($errors['message']) && ($errors['message'] == "MySQL server has gone away" || stristr($errors['message'], "max_allowed_packet")))
	{
		upgrade_header(7, 95);
		echo"
		<form role=\"form\" class=\"form-horizontal\" id=\"nukeform\" action=\"install.php?op=downloads".((isset($start) && $start != 0) ? "&start=$start":"")."\" method=\"post\">
			<div class=\"wizard-card-container\" style=\"height: 326px;\">
				<div class=\"wizard-card\" data-cardname=\"group\">
					".$nuke_languages['_CMS_UPGRADE']."
					<div class=\"wizard-error\">
						<div class=\"alert alert-danger\">	<strong>".$nuke_languages['_ERROR']." :</strong> ".sprintf($nuke_languages['_INSTALL_TRANSFER_CONTENTS_ERROR'], $transfer_counter)."</div>
					</div>
					<div class=\"wizard-input-section\">
						<div class=\"form-group\">
							<label class=\"control-label col-sm-4\" for=\"transfer_counter\">".$nuke_languages['_INSTALL_TRANSFERABLE_MESSAGES'].":</label>
							<div class=\"col-sm-8\">
								<input type=\"text\" class=\"form-control\" id=\"transfer_counter\" name=\"transfer_counter\" value=\"$transfer_counter\" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class=\"wizard-footer\">
				<div class=\"wizard-buttons-container\">
					<div class=\"btn-group-single pull-"._ALIGN2."\">
						<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>
						<input class=\"btn wizard-next btn-primary\" type=\"submit\" value=\"".$nuke_languages['_NEXT']."\" />
					</div>
				</div>
			</div>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
		</form>";
		upgrade_footer();
		die();
	}
	
	$new_start = $start+$transfer_counter;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_DOWNLOAD_FILES'], $total_rows, $fetched_rows, $start, "install.php?op=pages", "install.php?op=downloads&start=$new_start&transfer_counter=$transfer_counter", 66, $transfer_counter);
}

function upgrade_pages($start)
{
	global $db, $nuke_configs, $cache, $transfer_counter, $pn_dbcharset, $pn_dbname, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$pages_cats = array();
	
	$cat_result = $db->table(CATEGORIES_TABLE)
						->where('module', 'Pages')
						->select(['catid', 'parent_id', 'imported_id']);
	if($cat_result->count() > 0)
	{
		$cat_rows = $cat_result->results();
		foreach($cat_rows as $cat_row)
		{
			$pages_cats[$cat_row['imported_id']] = array('catid' => $cat_row['catid'], 'parent_id' => $cat_row['parent_id']);
		}
	}
	
	$run_per_step = 100;
	// update nuke_articles
	$db->query("set names 'latin1'");
	$insert_query = array();
	
	$transfer_counter = (isset($transfer_counter) && $transfer_counter != 0) ? $transfer_counter:$run_per_step;
	
	$last_article_sid = $cache->retrieve('last_article_sid');

	$default_admin = $cache->retrieve('default_admin', false);
	
	$result = $db->query("SELECT *, (SELECT COUNT(pid) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_pages`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_pages` ORDER BY pid ASC LIMIT $start, $transfer_counter");
	$fetched_rows = intval($result->count());

	if($fetched_rows > 0)
	{
		$rows = $result->results();

		if(!empty($rows))
		{
			$total_rows_set = false;
			foreach($rows as $row)
			{
				if(!$total_rows_set)
				{
					$cache->store('total_rows', intval($row['total_rows']));
					unset($row['total_rows']);
					$total_rows_set = true;
				}
				
				$cat_link = (isset($pages_cats[$row['cid']]['catid'])) ? $pages_cats[$row['cid']]['catid']:0;
				
				$post_url = trim(sanitize(str2url($row['title'])), "-");
				$post_url = get_unique_post_slug(POSTS_TABLE, "sid", '', "post_url", $post_url, 'publish', false, "AND post_type = 'pages'");
				
				$hometext = $row['subtitle']."<br />".$row['page_header'];
				$bodytext = $row['text']."<br />".$row['page_footer']."<br />";
				$tags = str_replace(":",",", $row['signature']);
								
				$insert_query[] = array($row['pid'], 'publish', 'Pages', $default_admin,$row['title'],$row['date'],$hometext,$bodytext,$post_url,0,$row['counter'],$cat_link,$default_admin,$tags,1,'',1,1,'',$cat_link,0,0,0);
			}
			
			$db->query("set names '$pn_dbcharset'");
			
			$new_cols = array("imported_id","status","post_type","aid","title","time","hometext","bodytext","post_url","comments","counter","cat","informant","tags","ihome","alanguage","allow_comment","position","post_pass","cat_link","permissions","score","ratings");
			
			if(isset($insert_query) && !empty($insert_query))
				$db->table(POSTS_TABLE)->multiinsert($new_cols,$insert_query);
		}
	}
	
	$errors = $db->getErrors('last');
	if(isset($errors['message']) && ($errors['message'] == "MySQL server has gone away" || stristr($errors['message'], "max_allowed_packet")))
	{
		upgrade_header(7, 95);
		echo"
		<form role=\"form\" class=\"form-horizontal\" id=\"nukeform\" action=\"install.php?op=pages".((isset($start) && $start != 0) ? "&start=$start":"")."\" method=\"post\">
			<div class=\"wizard-card-container\" style=\"height: 326px;\">
				<div class=\"wizard-card\" data-cardname=\"group\">
					".$nuke_languages['_CMS_UPGRADE']."
					<div class=\"wizard-error\">
						<div class=\"alert alert-danger\">	<strong>".$nuke_languages['_ERROR']." :</strong> ".sprintf($nuke_languages['_INSTALL_TRANSFER_CONTENTS_ERROR'], $transfer_counter)."</div>
					</div>
					<div class=\"wizard-input-section\">
						<div class=\"form-group\">
							<label class=\"control-label col-sm-4\" for=\"transfer_counter\">".$nuke_languages['_INSTALL_TRANSFERABLE_MESSAGES'].":</label>
							<div class=\"col-sm-8\">
								<input type=\"text\" class=\"form-control\" id=\"transfer_counter\" name=\"transfer_counter\" value=\"$transfer_counter\" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class=\"wizard-footer\">
				<div class=\"wizard-buttons-container\">
					<div class=\"btn-group-single pull-"._ALIGN2."\">
						<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>
						<input class=\"btn wizard-next btn-primary\" type=\"submit\" value=\"".$nuke_languages['_NEXT']."\" />
					</div>
				</div>
			</div>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
		</form>";
		upgrade_footer();
		die();
	}
	
	$new_start = $start+$transfer_counter;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_PAGES'], $total_rows, $fetched_rows, $start, "install.php?op=faqs", "install.php?op=pages&start=$new_start&transfer_counter=$transfer_counter", 72, $transfer_counter);
}

function upgrade_faqs($start)
{
	global $db, $nuke_configs, $cache, $transfer_counter, $pn_dbcharset, $pn_dbname, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$faqs_cats = array();
	
	$cat_result = $db->table(CATEGORIES_TABLE)
						->where('module', 'Faqs')
						->select(['catid', 'parent_id', 'imported_id']);
	if($cat_result->count() > 0)
	{
		$cat_rows = $cat_result->results();
		foreach($cat_rows as $cat_row)
		{
			$faqs_cats[$cat_row['imported_id']] = array('catid' => $cat_row['catid'], 'parent_id' => $cat_row['parent_id']);
		}
	}
	
	$run_per_step = 100;
	// update nuke_articles
	$db->query("set names 'latin1'");
	$insert_query = array();
	
	$transfer_counter = (isset($transfer_counter) && $transfer_counter != 0) ? $transfer_counter:$run_per_step;
	
	$last_article_sid = $cache->retrieve('last_article_sid');

	$default_admin = $cache->retrieve('default_admin', false);
	
	$result = $db->query("SELECT *, (SELECT COUNT(id) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_faqanswer`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_faqanswer` ORDER BY id ASC LIMIT $start, $transfer_counter");
	$fetched_rows = intval($result->count());

	if($fetched_rows > 0)
	{
		$rows = $result->results();

		if(!empty($rows))
		{
			$total_rows_set = false;
			$timer = 1;
			foreach($rows as $row)
			{
				if(!$total_rows_set)
				{
					$cache->store('total_rows', intval($row['total_rows']));
					unset($row['total_rows']);
					$total_rows_set = true;
				}
				
				$cat_link = (isset($faqs_cats[$row['id_cat']]['catid'])) ? $faqs_cats[$row['id_cat']]['catid']:0;
				
				$post_url = trim(sanitize(str2url($row['question'])), "-");
				$post_url = get_unique_post_slug(POSTS_TABLE, "sid", '', "post_url", $post_url, 'publish', false, "AND post_type = 'faqs'");
				
				$hometext = mb_word_wrap(strip_tags($row['answer']), 200, '...');
				$bodytext = $row['answer'];
								
				$insert_query[] = array($row['id'], 'publish', 'Faqs', $default_admin,$row['question'],(_NOWTIME+$timer),$hometext,$bodytext,$post_url,0,0,$cat_link,$default_admin,'',1,'',1,1,'',$cat_link,0,0,0);
				$timer++;
			}
			
			$db->query("set names '$pn_dbcharset'");
			
			$new_cols = array("imported_id","status","post_type","aid","title","time","hometext","bodytext","post_url","comments","counter","cat","informant","tags","ihome","alanguage","allow_comment","position","post_pass","cat_link","permissions","score","ratings");
			
			if(isset($insert_query) && !empty($insert_query))
				$db->table(POSTS_TABLE)->multiinsert($new_cols,$insert_query);
		}
	}
	
	$errors = $db->getErrors('last');
	if(isset($errors['message']) && ($errors['message'] == "MySQL server has gone away" || stristr($errors['message'], "max_allowed_packet")))
	{
		upgrade_header(7, 95);
		echo"
		<form role=\"form\" class=\"form-horizontal\" id=\"nukeform\" action=\"install.php?op=faqs".((isset($start) && $start != 0) ? "&start=$start":"")."\" method=\"post\">
			<div class=\"wizard-card-container\" style=\"height: 326px;\">
				<div class=\"wizard-card\" data-cardname=\"group\">
					".$nuke_languages['_CMS_UPGRADE']."
					<div class=\"wizard-error\">
						<div class=\"alert alert-danger\">	<strong>".$nuke_languages['_ERROR']." :</strong> ".sprintf($nuke_languages['_INSTALL_TRANSFER_CONTENTS_ERROR'], $transfer_counter)."</div>
					</div>
					<div class=\"wizard-input-section\">
						<div class=\"form-group\">
							<label class=\"control-label col-sm-4\" for=\"transfer_counter\">".$nuke_languages['_INSTALL_TRANSFERABLE_MESSAGES'].":</label>
							<div class=\"col-sm-8\">
								<input type=\"text\" class=\"form-control\" id=\"transfer_counter\" name=\"transfer_counter\" value=\"$transfer_counter\" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class=\"wizard-footer\">
				<div class=\"wizard-buttons-container\">
					<div class=\"btn-group-single pull-"._ALIGN2."\">
						<a href=\"javascript:history.go(-1)\"><button class=\"btn wizard-back\" type=\"button\">".$nuke_languages['_PREV']."</button></a>
						<input class=\"btn wizard-next btn-primary\" type=\"submit\" value=\"".$nuke_languages['_NEXT']."\" />
					</div>
				</div>
			</div>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
		</form>";
		upgrade_footer();
		die();
	}
	
	$new_start = $start+$transfer_counter;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_FAQS'], $total_rows, $fetched_rows, $start, "install.php?op=comments", "install.php?op=faqs&start=$new_start&transfer_counter=$transfer_counter", 78, $transfer_counter);
}

function upgrade_comments($start = 0)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $module, $nuke_languages;
	
	/*if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	*/
	$modules_comments = array(
		"stories_comments" => array(
			"new_table" => POSTS_TABLE,
			"module_desc" => $nuke_languages['_ARTICLES_COMMENTS'],
			"module_id" => "sid",
			"module_pid" => "pid",
			"module_cid" => "sid",
			"module_name" => "Articles",
			"module_table" => "stories",
			"module_title" => "title",
			"subquery" => "SELECT sid FROM ".POSTS_TABLE." WHERE sid = t.sid AND post_type = 'Articles'",
			"progress_link" => "install.php?op=comments&start={NEW_START}",
			"finish_link" => "install.php?op=comments&module=pages_comments",
		),
		"pages_comments" => array(
			"new_table" => POSTS_TABLE,
			"module_desc" => $nuke_languages['_PAGES_COMMENTS'],
			"module_id" => "pageid",
			"module_pid" => "prid",
			"module_cid" => "pid",
			"module_name" => "Pages",
			"module_table" => "pages",
			"module_title" => "title",
			"subquery" => "SELECT sid FROM ".POSTS_TABLE." WHERE imported_id = t.pageid AND post_type = 'Pages'",
			"progress_link" => "install.php?op=comments&module=pages_comments&start={NEW_START}",
			"finish_link" => "install.php?op=comments&module=products_comments",
		),
		"products_comments" => array(
			"new_table" => POSTS_TABLE,
			"module_desc" => $nuke_languages['_PRODUCTS_COMMENTS'],
			"module_id" => "sid",
			"module_pid" => "pid",
			"module_cid" => "sid",
			"module_name" => "Products",
			"module_table" => "products",
			"module_title" => "title",
			"subquery" => "SELECT sid FROM ".POSTS_TABLE." WHERE imported_id = t.sid AND post_type = 'Products'",
			"progress_link" => "install.php?op=comments&module=products_comments&start={NEW_START}",
			"finish_link" => "install.php?op=comments&module=pollcomments",
		),
		"pollcomments" => array(
			"new_table" => SURVEYS_TABLE,
			"module_desc" => $nuke_languages['_POLLS_COMMENTS'],
			"module_id" => "pollID",
			"module_pid" => "pid",
			"module_cid" => "pollID",
			"module_name" => "Surveys",
			"module_table" => "poll_desc",
			"module_title" => "pollTitle",
			"subquery" => "s.pollID",
			"progress_link" => "install.php?op=comments&module=pollcomments&start={NEW_START}",
			"finish_link" => "install.php?op=comments&module=staticpages_comments",
		),
		"staticpages_comments" => array(
			"new_table" => POSTS_TABLE,
			"module_desc" => $nuke_languages['_STATICPAGES_COMMENTS'],
			"module_id" => "staticid",
			"module_pid" => "prid",
			"module_cid" => "pid",
			"module_name" => "Statics",
			"module_table" => "staticpages",
			"module_title" => "title",
			"subquery" => "SELECT sid FROM ".POSTS_TABLE." WHERE imported_id = t.staticid AND post_type = 'Statics'",
			"progress_link" => "install.php?op=comments&module=staticpages_comments&start={NEW_START}",
			"finish_link" => "install.php?op=comments_parents",
		),
	);
	
	$module = (isset($module) && $module != '' && $module != 'stories_comments') ? $module:"stories_comments";
	
	$new_table = $modules_comments[$module]['new_table'];
	$module_desc = $modules_comments[$module]['module_desc'];
	$module_id = $modules_comments[$module]['module_id'];
	$module_pid = $modules_comments[$module]['module_pid'];
	$module_cid = $modules_comments[$module]['module_cid'];
	$subquery = $modules_comments[$module]['subquery'];
	$module_name = $modules_comments[$module]['module_name'];
	$module_table = $modules_comments[$module]['module_table'];
	$module_title = $modules_comments[$module]['module_title'];
	$finish_link = $modules_comments[$module]['finish_link'];
	$progress_link = $modules_comments[$module]['progress_link'];
	
	$comments_config = array();
	$run_per_step = 500;
	// update nuke_comments
	$db->query("set names 'latin1'");
	$result1 = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_comments_config` WHERE code = '1'");
	if(intval($result1->count()) > 0)
	{
		$rows1 = $result1->results();
		foreach($rows1 as $row1)
		{
			if(preg_match("#name#isU", $row1['name']))
				$comments_config[$row1['cfid']] = 'name';
				
			if(preg_match("#email#isU", $row1['name']))
				$comments_config[$row1['cfid']] = 'email';
				
			if(preg_match("#website#isU", $row1['name']))
				$comments_config[$row1['cfid']] = 'url';
				
			if(preg_match("#url#isU", $row1['name']))
				$comments_config[$row1['cfid']] = 'url';
		}
	}

	$fields = array();
	$result2 = $db->query("SELECT * FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_".$module."_fildes`");
	if(intval($result2->count()) > 0)
	{
		$rows2 = $result2->results();
		foreach($rows2 as $row2)
		{
			if(!isset($comments_config[$row2['cfid']]))
				continue;
			$fields[$row2['tid']][$comments_config[$row2['cfid']]] = $row2['cfvalue'];
		}
	}

	$insert_query = array();
	$result = $db->query("SELECT t.*, t.$module_pid as pid, s.$module_title as post_title, ($subquery) as new_post_id, (SELECT COUNT(tid) FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_".$module."`) as total_rows FROM `".OLD_DB."`.`".OLD_DB_PREFIX."_".$module."` as t LEFT JOIN `".OLD_DB."`.`".OLD_DB_PREFIX."_".$module_table."` as s ON s.$module_cid = t.$module_id ORDER BY tid ASC LIMIT $start, $run_per_step");
	$fetched_rows = intval($result->count());

	if($fetched_rows > 0)
	{
		$rows = $result->results();
		$all_rows = array();
		$total_rows_set = false;
		foreach($rows as $row)
		{
			if(!$total_rows_set)
			{
				$cache->store('total_rows', intval($row['total_rows']));
				unset($row['total_rows']);
				$total_rows_set = true;
			}
			$all_rows[$row['tid']] = $row;
		}
		
		unset($rows);
		
		if(!empty($all_rows))
		{
			foreach($all_rows as $row)
			{
				$insert_query[$row['tid']] = array(
					'pid' => $row['pid'], 
					'main_parent' => 0, 
					'module' => $module_name,
					"post_id" => $row['new_post_id'],
					'post_title' => $row['post_title'],
					'date' => $row['date'],
					'name' => ((isset($fields[$row['tid']]['name']) && $fields[$row['tid']]['name'] != '') ? $fields[$row['tid']]['name']:$row['name']),
					'email' => ((isset($fields[$row['tid']]['email']) && $fields[$row['tid']]['email'] != '') ? $fields[$row['tid']]['email']:((isset($row['email'])) ? $row['email']:"")),
					'url' => ((isset($fields[$row['tid']]['url']) && $fields[$row['tid']]['url'] != '') ? $fields[$row['tid']]['url']:$row['url']),
					'host_name' => $row['host_name'],
					'comment' => $row['comment'],
					'score' => $row['score'],
					'reason' => $row['reason'],
					'act' => $row['act'],
					'imported_id' => $row['tid']
				);
				
			}
		}
		
		$db->query("set names '$pn_dbcharset'");
		if(isset($insert_query) && !empty($insert_query))
			$db->table(COMMENTS_TABLE)->multiinsert(array("pid","main_parent","module","post_id","post_title","date","name","email","url","ip","comment","score","reason","status","imported_id"),$insert_query);
	}
	
	$new_start = $start+$run_per_step;
	$total_rows = $cache->retrieve('total_rows');
	
	$progress_link = str_replace("{NEW_START}", $new_start, $progress_link);
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER']." $module_desc", $total_rows, $fetched_rows, $start, $finish_link, $progress_link, 84, $run_per_step);
}

function upgrade_comments_parents($start = 0)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $module, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$module = (isset($module) && $module != '') ? ucfirst($module):"Articles";
	
	$run_per_step = 10000;
	// update nuke_comments pids
	$update_query = array();
	$result = $db->query("SELECT c.cid, (SELECT cid FROM ".COMMENTS_TABLE." WHERE imported_id = c.pid AND module = '$module') as pid, (SELECT COUNT(cid) FROM ".COMMENTS_TABLE." WHERE pid != '0' AND module = '$module') as total_rows FROM ".COMMENTS_TABLE." as c WHERE c.pid != '0' AND c.module = '$module' ORDER BY cid ASC LIMIT $start, $run_per_step");
	$fetched_rows = intval($result->count());

	if($fetched_rows > 0)
	{
		$rows = $result->results();
		$total_rows_set = false;
		
		if(!empty($rows))
		{
			foreach($rows as $row)
			{
				if(!$total_rows_set)
				{
					$cache->store('total_rows', intval($row['total_rows']));
					unset($row['total_rows']);
					$total_rows_set = true;
				}
				
				$cid = $row['cid'];
				$pid = $row['pid'];
				
				$update_query[$cid] = "WHEN cid = '$cid' THEN '$pid'";
			}
		}
		
		if(isset($update_query) && !empty($update_query))
			$db->query("UPDATE ".COMMENTS_TABLE." SET pid = CASE
			".implode("\n", $update_query)."
			END
			WHERE module = '$module' AND cid IN (".implode(",", array_keys($update_query)).")");
	}
	
	$new_start = $start+$run_per_step;
	$total_rows = $cache->retrieve('total_rows');
	$progress_link = "install.php?op=comments_parents&start=$new_start";
	$finish_link = "install.php?op=comments_parents";
	
	switch($module)
	{
		default:
			$progress_link .= "";
			$finish_link .= "&module=pages";
		break;
		case"Pages":
			$progress_link .= "&module=pages";
			$finish_link .= "&module=products";
		break;
		case"Products":
			$progress_link .= "&module=products";
			$finish_link .= "&module=surveys";
		break;
		case"Surveys":
			$progress_link .= "&module=surveys";
			$finish_link .= "&module=statics";
		break;
		case"Statics":
			$progress_link .= "&module=statics";
			$finish_link = "install.php?op=comments_main_parent";
		break;
	}
	
	upgrade_progress_output($nuke_languages['_INSTALL_NESTED_COMMENTS_CORRECT'], $total_rows, $fetched_rows, $start, $finish_link, $progress_link, 90, $run_per_step);
}

function get_main_parent($cid)
{
	global $db;
	
	$result = $db->table(COMMENTS_TABLE)
				->where('cid',$cid)
				->select(['pid']);
	if($result->count() > 0)
	{
		$pid = $result->results()[0]['pid'];
		if($pid == 0)
			return $cid;
		else
			return get_main_parent($pid);
	}
}

function upgrade_comments_main_parent($start = 0)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $module, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
		
	$module = (isset($module) && $module != '') ? ucfirst($module):"Articles";
	
	$run_per_step = 10000;
	// update nuke_comments pids
	$update_query = array();
	$result = $db->query("SELECT cid, pid, (SELECT COUNT(cid) FROM ".COMMENTS_TABLE." WHERE pid != '0' AND module = '$module') as total_rows FROM ".COMMENTS_TABLE." as c WHERE c.pid != '0' AND c.module = '$module' ORDER BY cid ASC LIMIT $start, $run_per_step");
	$fetched_rows = intval($result->count());

	if($fetched_rows > 0)
	{
		$rows = $result->results();
		$total_rows_set = false;
		
		if(!empty($rows))
		{
			foreach($rows as $row)
			{
				if(!$total_rows_set)
				{
					$cache->store('total_rows', intval($row['total_rows']));
					unset($row['total_rows']);
					$total_rows_set = true;
				}
				
				$cid = $row['cid'];
				$pid = $row['pid'];
				$main_parent = get_main_parent($pid);
				
				$update_query[$cid] = "WHEN cid = '$cid' THEN '$main_parent'";
			}
		}
		
		if(isset($update_query) && !empty($update_query))
			$db->query("UPDATE ".COMMENTS_TABLE." SET main_parent = CASE
			".implode("\n", $update_query)."
			END
			WHERE module = '$module' AND cid IN (".implode(",", array_keys($update_query)).")");
	}
	
	$new_start = $start+$run_per_step;
	$total_rows = $cache->retrieve('total_rows');
	$progress_link = "install.php?op=comments_main_parent&start=$new_start";
	$finish_link = "install.php?op=comments_main_parent";
	
	switch($module)
	{
		default:
			$progress_link .= "";
			$finish_link .= "&module=pages";
		break;
		case"Pages":
			$progress_link .= "&module=pages";
			$finish_link .= "&module=products";
		break;
		case"Products":
			$progress_link .= "&module=products";
			$finish_link .= "&module=surveys";
		break;
		case"Surveys":
			$progress_link .= "&module=surveys";
			$finish_link .= "&module=statics";
		break;
		case"Statics":
			$progress_link .= "&module=statics";
			$finish_link = "install.php?op=comments_replies_time";
		break;
	}
	
	upgrade_progress_output($nuke_languages['_INSTALL_NESTED_COMMENTS_CORRECT'], $total_rows, $fetched_rows, $start, $finish_link, $progress_link, 90, $run_per_step);
}

function upgrade_comments_replies_time($start = 0)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $module, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$run_per_step = 1000;
	// update nuke_comments
	$result = $db->query("SELECT c.cid, c.`date`, (SELECT `date` FROM ".COMMENTS_TABLE." WHERE main_parent = c.cid ORDER BY `date` DESC LIMIT 1) as last_replay_time, (SELECT COUNT(cid) FROM `".COMMENTS_TABLE."` WHERE pid = '0') as total_rows FROM ".COMMENTS_TABLE." AS c WHERE c.pid = '0' ORDER BY c.cid ASC LIMIT $start, $run_per_step");
	$fetched_rows = intval($result->count());
	$when_query = array();
	if($fetched_rows > 0)
	{
		$rows = $result->results();
		$all_rows = array();
		$total_rows_set = false;
		foreach($rows as $row)
		{
			if(!$total_rows_set)
			{
				$cache->store('total_rows', intval($row['total_rows']));
				unset($row['total_rows']);
				$total_rows_set = true;
			}
		
			$cid = $row['cid'];
			$date = $row['date'];
			$last_replay_time = (intval($row['last_replay_time']) > 0) ? $row['last_replay_time']:$date;
			
			$when_query[$row['cid']] = "WHEN cid = '$cid' THEN '$last_replay_time'";
		}
		
		if(!empty($when_query))
		{
			$cids = array_keys($when_query);
			$cids = implode(", ", $cids);
			$when_query = implode("\n", $when_query);
			$db->query("UPDATE ".COMMENTS_TABLE." SET last_replay_time = CASE 
				$when_query
			END
			WHERE cid IN($cids)");
		}
	}
	
	$new_start = $start+$run_per_step;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_MODIFY_COMMENTS_ORDER'], $total_rows, $fetched_rows, $start, "install.php?op=forum", "install.php?op=comments_replies_time&start=$new_start", 90, $run_per_step);
}

function upgrade_forum()
{
	global $db, $nuke_configs, $cache, $pn_dbtype, $pn_dbfetch, $pn_dbcharset, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
	
	$install_options = $cache->retrieve('install_options');
	$install_options = phpnuke_unserialize($install_options);
	
	$have_forum = (isset($install_options['db_info']['db_have_forum']) && $install_options['db_info']['db_have_forum'] == 1) ? true:false;
	$transfer_users = (isset($install_options['db_info']['db_have_forum']) && $install_options['db_info']['db_have_forum'] == 2) ? true:false;
		
	$old_users_table_name = "`".OLD_DB."`.`".OLD_DB_PREFIX."_users`";
	$new_users_table_name = ($have_forum) ? ("`".$install_options['db_info']['db_forumname']."`.`".(($install_options['db_info']['db_forumprefix'] != OLD_DB_PREFIX.'_bb3') ? $install_options['db_info']['db_forumprefix']."_users":OLD_DB_PREFIX."_bb3users")."`"):USERS_TABLE;
	
	if(!$transfer_users)
	{
		// Get a listing of all tables
		//$tables_result = $db->query("SHOW TABLES FROM `".OLD_DB."`");
		$tables_result = $db->query("SELECT TABLE_NAME FROM `information_schema`.`TABLES` WHERE TABLE_SCHEMA =  '".OLD_DB."'");

		if(!empty($tables_result))
		{
			if($have_forum)
			{
				// Loop through all tables
				foreach ($tables_result as $row)
				{
					//$forumtable = $row['Tables_in_'.OLD_DB];
					$forumtable = $row['TABLE_NAME'];
					
					if(!preg_match("#".OLD_DB_PREFIX."_bb3#isU", $forumtable))
						continue;
					
					$db->query("DROP TABLE IF EXISTS `".$install_options['db_info']['db_forumname']."`.`$forumtable`");
					
					$query = $db->query("SHOW CREATE TABLE `".OLD_DB."`.`$forumtable`");
					$query_results = $query->results();
					$query_result = $query_results[0];
					
					if($install_options['db_info']['db_forumprefix'] != OLD_DB_PREFIX.'_bb3')
						$new_forumtable = str_replace(OLD_DB_PREFIX.'_bb3', $install_options['db_info']['db_forumprefix']."_", $forumtable);
					
					$sql = str_replace("CREATE TABLE `$forumtable`", "CREATE TABLE `".$install_options['db_info']['db_forumname']."`.`$new_forumtable`", $query_result['Create Table']);
					$sql = preg_replace(
						array(
							"#latin1([a-z0-9A-Z\_+]*)#i",
							"#utf8([a-z0-9A-Z\_+]*)#i",
						),
						"latin1_general_ci",
						$sql
					);
					$sql = str_replace(
						array("DEFAULT CHARSET=latin1_general_ci","CHARACTER SET latin1_general_ci"),
						array("DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci",''),
						$sql
					);
					$db->query($sql);
					
					if($new_forumtable == $install_options['db_info']['db_forumprefix']."_search_wordmatch" || $new_forumtable == $install_options['db_info']['db_forumprefix']."_search_wordlist")
						continue;
					
					$db->query("set names '$pn_dbcharset'");
					$db->query("INSERT INTO `".$install_options['db_info']['db_forumname']."`.`$new_forumtable` SELECT * FROM `".OLD_DB."`.`$forumtable`\n");
				}
				
				$db->query("DROP TABLE IF EXISTS $new_users_table_name");
			
				// update users table
				$users_query = $db->query("SHOW CREATE TABLE $old_users_table_name");
				$users_query_results = $users_query->results();
				$users_query_result = $users_query_results[0];
				$users_sql = str_replace("CREATE TABLE `".OLD_DB_PREFIX."_users`", "CREATE TABLE $new_users_table_name", $users_query_result['Create Table']);
				$users_sql = preg_replace(
					array(
						"#latin1([a-z0-9A-Z\_+]*)#i",
						"#utf8([a-z0-9A-Z\_+]*)#i",
					),
					"latin1_general_ci",
					$users_sql
				);
				$users_sql = str_replace(
					array("DEFAULT CHARSET=latin1_general_ci","CHARACTER SET latin1_general_ci"),
					array("DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci",''),
					$users_sql
				);
				$db->query($users_sql);
				
				$db->query("set names '$pn_dbcharset'");
				$db->query("INSERT INTO $new_users_table_name SELECT * FROM $old_users_table_name");
				// update users table
				
				$db->query("ALTER TABLE `".$install_options['db_info']['db_forumname']."`.`".$install_options['db_info']['db_forumprefix']."_profile_fields_data` ADD `pf_user_phone` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT ''"); 
				$db->query("ALTER TABLE ".$new_users_table_name." ADD `user_credit` bigint(20) UNSIGNED NOT NULL DEFAULT '0'"); 
				
				$db->query("UPDATE `".$install_options['db_info']['db_forumname']."`.`".$install_options['db_info']['db_forumprefix']."_config` SET config_value = '0' WHERE config_name = 'server_port'");
				
				$db->query("TRUNCATE TABLE `".$install_options['db_info']['db_forumname']."`.`".$install_options['db_info']['db_forumprefix']."_modules`");
				$db->query("INSERT INTO `".$install_options['db_info']['db_forumname']."`.`".$install_options['db_info']['db_forumprefix']."_modules` (`module_id`, `module_enabled`, `module_display`, `module_basename`, `module_class`, `parent_id`, `left_id`, `right_id`, `module_langname`, `module_mode`, `module_auth`) VALUES
				(1, 1, 1, '', 'acp', 0, 1, 64, 'ACP_CAT_GENERAL', '', ''),
				(2, 1, 1, '', 'acp', 1, 4, 17, 'ACP_QUICK_ACCESS', '', ''),
				(3, 1, 1, '', 'acp', 1, 18, 41, 'ACP_BOARD_CONFIGURATION', '', ''),
				(4, 1, 1, '', 'acp', 1, 42, 49, 'ACP_CLIENT_COMMUNICATION', '', ''),
				(5, 1, 1, '', 'acp', 1, 50, 63, 'ACP_SERVER_CONFIGURATION', '', ''),
				(6, 1, 1, '', 'acp', 0, 65, 84, 'ACP_CAT_FORUMS', '', ''),
				(7, 1, 1, '', 'acp', 6, 66, 71, 'ACP_MANAGE_FORUMS', '', ''),
				(8, 1, 1, '', 'acp', 6, 72, 83, 'ACP_FORUM_BASED_PERMISSIONS', '', ''),
				(9, 1, 1, '', 'acp', 0, 85, 110, 'ACP_CAT_POSTING', '', ''),
				(10, 1, 1, '', 'acp', 9, 86, 99, 'ACP_MESSAGES', '', ''),
				(11, 1, 1, '', 'acp', 9, 100, 109, 'ACP_ATTACHMENTS', '', ''),
				(12, 1, 1, '', 'acp', 0, 111, 166, 'ACP_CAT_USERGROUP', '', ''),
				(13, 1, 1, '', 'acp', 12, 112, 145, 'ACP_CAT_USERS', '', ''),
				(14, 1, 1, '', 'acp', 12, 146, 153, 'ACP_GROUPS', '', ''),
				(15, 1, 1, '', 'acp', 12, 154, 165, 'ACP_USER_SECURITY', '', ''),
				(16, 1, 1, '', 'acp', 0, 167, 216, 'ACP_CAT_PERMISSIONS', '', ''),
				(17, 1, 1, '', 'acp', 16, 170, 179, 'ACP_GLOBAL_PERMISSIONS', '', ''),
				(18, 1, 1, '', 'acp', 16, 180, 191, 'ACP_FORUM_BASED_PERMISSIONS', '', ''),
				(19, 1, 1, '', 'acp', 16, 192, 201, 'ACP_PERMISSION_ROLES', '', ''),
				(20, 1, 1, '', 'acp', 16, 202, 215, 'ACP_PERMISSION_MASKS', '', ''),
				(21, 1, 1, '', 'acp', 0, 217, 230, 'ACP_CAT_STYLES', '', ''),
				(22, 1, 1, '', 'acp', 21, 218, 221, 'ACP_STYLE_MANAGEMENT', '', ''),
				(23, 1, 1, '', 'acp', 21, 222, 229, 'ACP_STYLE_COMPONENTS', '', ''),
				(24, 1, 1, '', 'acp', 0, 231, 250, 'ACP_CAT_MAINTENANCE', '', ''),
				(25, 1, 1, '', 'acp', 24, 232, 241, 'ACP_FORUM_LOGS', '', ''),
				(26, 1, 1, '', 'acp', 24, 242, 249, 'ACP_CAT_DATABASE', '', ''),
				(27, 1, 1, '', 'acp', 0, 251, 276, 'ACP_CAT_SYSTEM', '', ''),
				(28, 1, 1, '', 'acp', 27, 252, 255, 'ACP_AUTOMATION', '', ''),
				(29, 1, 1, '', 'acp', 27, 256, 267, 'ACP_GENERAL_TASKS', '', ''),
				(30, 1, 1, '', 'acp', 27, 268, 275, 'ACP_MODULE_MANAGEMENT', '', ''),
				(31, 1, 1, '', 'acp', 0, 277, 288, 'ACP_CAT_DOT_MODS', '', ''),
				(32, 1, 1, 'attachments', 'acp', 3, 19, 20, 'ACP_ATTACHMENT_SETTINGS', 'attach', 'acl_a_attach'),
				(33, 1, 1, 'attachments', 'acp', 11, 101, 102, 'ACP_ATTACHMENT_SETTINGS', 'attach', 'acl_a_attach'),
				(34, 1, 1, 'attachments', 'acp', 11, 103, 104, 'ACP_MANAGE_EXTENSIONS', 'extensions', 'acl_a_attach'),
				(35, 1, 1, 'attachments', 'acp', 11, 105, 106, 'ACP_EXTENSION_GROUPS', 'ext_groups', 'acl_a_attach'),
				(36, 1, 1, 'attachments', 'acp', 11, 107, 108, 'ACP_ORPHAN_ATTACHMENTS', 'orphan', 'acl_a_attach'),
				(37, 1, 1, 'ban', 'acp', 15, 155, 156, 'ACP_BAN_EMAILS', 'email', 'acl_a_ban'),
				(38, 1, 1, 'ban', 'acp', 15, 157, 158, 'ACP_BAN_IPS', 'ip', 'acl_a_ban'),
				(39, 1, 1, 'ban', 'acp', 15, 159, 160, 'ACP_BAN_USERNAMES', 'user', 'acl_a_ban'),
				(40, 1, 1, 'bbcodes', 'acp', 10, 87, 88, 'ACP_BBCODES', 'bbcodes', 'acl_a_bbcode'),
				(41, 1, 1, 'board', 'acp', 3, 21, 22, 'ACP_BOARD_SETTINGS', 'settings', 'acl_a_board'),
				(42, 1, 1, 'board', 'acp', 3, 23, 24, 'ACP_BOARD_FEATURES', 'features', 'acl_a_board'),
				(43, 1, 1, 'board', 'acp', 3, 25, 26, 'ACP_AVATAR_SETTINGS', 'avatar', 'acl_a_board'),
				(44, 1, 1, 'board', 'acp', 3, 27, 28, 'ACP_MESSAGE_SETTINGS', 'message', 'acl_a_board'),
				(45, 1, 1, 'board', 'acp', 10, 89, 90, 'ACP_MESSAGE_SETTINGS', 'message', 'acl_a_board'),
				(46, 1, 1, 'board', 'acp', 3, 29, 30, 'ACP_POST_SETTINGS', 'post', 'acl_a_board'),
				(47, 1, 1, 'board', 'acp', 3, 31, 32, 'ACP_SIGNATURE_SETTINGS', 'signature', 'acl_a_board'),
				(48, 1, 1, 'board', 'acp', 3, 33, 34, 'ACP_FEED_SETTINGS', 'feed', 'acl_a_board'),
				(49, 1, 1, 'board', 'acp', 3, 35, 36, 'ACP_REGISTER_SETTINGS', 'registration', 'acl_a_board'),
				(50, 1, 1, 'board', 'acp', 4, 43, 44, 'ACP_AUTH_SETTINGS', 'auth', 'acl_a_server'),
				(51, 1, 1, 'board', 'acp', 4, 45, 46, 'ACP_EMAIL_SETTINGS', 'email', 'acl_a_server'),
				(52, 1, 1, 'board', 'acp', 5, 51, 52, 'ACP_COOKIE_SETTINGS', 'cookie', 'acl_a_server'),
				(53, 1, 1, 'board', 'acp', 5, 53, 54, 'ACP_SERVER_SETTINGS', 'server', 'acl_a_server'),
				(54, 1, 1, 'board', 'acp', 5, 55, 56, 'ACP_SECURITY_SETTINGS', 'security', 'acl_a_server'),
				(55, 1, 1, 'board', 'acp', 5, 57, 58, 'ACP_LOAD_SETTINGS', 'load', 'acl_a_server'),
				(56, 1, 1, 'bots', 'acp', 29, 257, 258, 'ACP_BOTS', 'bots', 'acl_a_bots'),
				(57, 1, 1, 'captcha', 'acp', 3, 37, 38, 'ACP_VC_SETTINGS', 'visual', 'acl_a_board'),
				(58, 1, 0, 'captcha', 'acp', 3, 39, 40, 'ACP_VC_CAPTCHA_DISPLAY', 'img', 'acl_a_board'),
				(59, 1, 1, 'database', 'acp', 26, 243, 244, 'ACP_BACKUP', 'backup', 'acl_a_backup'),
				(60, 1, 1, 'database', 'acp', 26, 245, 246, 'ACP_RESTORE', 'restore', 'acl_a_backup'),
				(61, 1, 1, 'disallow', 'acp', 15, 161, 162, 'ACP_DISALLOW_USERNAMES', 'usernames', 'acl_a_names'),
				(62, 1, 1, 'email', 'acp', 29, 259, 260, 'ACP_MASS_EMAIL', 'email', 'acl_a_email && cfg_email_enable'),
				(63, 1, 1, 'forums', 'acp', 7, 67, 68, 'ACP_MANAGE_FORUMS', 'manage', 'acl_a_forum'),
				(64, 1, 1, 'groups', 'acp', 14, 147, 148, 'ACP_GROUPS_MANAGE', 'manage', 'acl_a_group'),
				(65, 1, 1, 'icons', 'acp', 10, 93, 94, 'ACP_ICONS', 'icons', 'acl_a_icons'),
				(66, 1, 1, 'icons', 'acp', 10, 95, 96, 'ACP_SMILIES', 'smilies', 'acl_a_icons'),
				(67, 1, 1, 'inactive', 'acp', 13, 115, 116, 'ACP_INACTIVE_USERS', 'list', 'acl_a_user'),
				(68, 1, 1, 'jabber', 'acp', 4, 47, 48, 'ACP_JABBER_SETTINGS', 'settings', 'acl_a_jabber'),
				(69, 1, 1, 'language', 'acp', 29, 261, 262, 'ACP_LANGUAGE_PACKS', 'lang_packs', 'acl_a_language'),
				(70, 1, 1, 'logs', 'acp', 25, 233, 234, 'ACP_ADMIN_LOGS', 'admin', 'acl_a_viewlogs'),
				(71, 1, 1, 'logs', 'acp', 25, 235, 236, 'ACP_MOD_LOGS', 'mod', 'acl_a_viewlogs'),
				(72, 1, 1, 'logs', 'acp', 25, 237, 238, 'ACP_USERS_LOGS', 'users', 'acl_a_viewlogs'),
				(73, 1, 1, 'logs', 'acp', 25, 239, 240, 'ACP_CRITICAL_LOGS', 'critical', 'acl_a_viewlogs'),
				(74, 1, 1, 'main', 'acp', 1, 2, 3, 'ACP_INDEX', 'main', ''),
				(75, 1, 1, 'modules', 'acp', 30, 269, 270, 'ACP', 'acp', 'acl_a_modules'),
				(76, 1, 1, 'modules', 'acp', 30, 271, 272, 'UCP', 'ucp', 'acl_a_modules'),
				(77, 1, 1, 'modules', 'acp', 30, 273, 274, 'MCP', 'mcp', 'acl_a_modules'),
				(78, 1, 1, 'permission_roles', 'acp', 19, 193, 194, 'ACP_ADMIN_ROLES', 'admin_roles', 'acl_a_roles && acl_a_aauth'),
				(79, 1, 1, 'permission_roles', 'acp', 19, 195, 196, 'ACP_USER_ROLES', 'user_roles', 'acl_a_roles && acl_a_uauth'),
				(80, 1, 1, 'permission_roles', 'acp', 19, 197, 198, 'ACP_MOD_ROLES', 'mod_roles', 'acl_a_roles && acl_a_mauth'),
				(81, 1, 1, 'permission_roles', 'acp', 19, 199, 200, 'ACP_FORUM_ROLES', 'forum_roles', 'acl_a_roles && acl_a_fauth'),
				(82, 1, 1, 'permissions', 'acp', 16, 168, 169, 'ACP_PERMISSIONS', 'intro', 'acl_a_authusers || acl_a_authgroups || acl_a_viewauth'),
				(83, 1, 0, 'permissions', 'acp', 20, 203, 204, 'ACP_PERMISSION_TRACE', 'trace', 'acl_a_viewauth'),
				(84, 1, 1, 'permissions', 'acp', 18, 181, 182, 'ACP_FORUM_PERMISSIONS', 'setting_forum_local', 'acl_a_fauth && (acl_a_authusers || acl_a_authgroups)'),
				(85, 1, 1, 'permissions', 'acp', 18, 183, 184, 'ACP_FORUM_PERMISSIONS_COPY', 'setting_forum_copy', 'acl_a_fauth && acl_a_authusers && acl_a_authgroups && acl_a_mauth'),
				(86, 1, 1, 'permissions', 'acp', 18, 185, 186, 'ACP_FORUM_MODERATORS', 'setting_mod_local', 'acl_a_mauth && (acl_a_authusers || acl_a_authgroups)'),
				(87, 1, 1, 'permissions', 'acp', 17, 171, 172, 'ACP_USERS_PERMISSIONS', 'setting_user_global', 'acl_a_authusers && (acl_a_aauth || acl_a_mauth || acl_a_uauth)'),
				(88, 1, 1, 'permissions', 'acp', 13, 117, 118, 'ACP_USERS_PERMISSIONS', 'setting_user_global', 'acl_a_authusers && (acl_a_aauth || acl_a_mauth || acl_a_uauth)'),
				(89, 1, 1, 'permissions', 'acp', 18, 187, 188, 'ACP_USERS_FORUM_PERMISSIONS', 'setting_user_local', 'acl_a_authusers && (acl_a_mauth || acl_a_fauth)'),
				(90, 1, 1, 'permissions', 'acp', 13, 119, 120, 'ACP_USERS_FORUM_PERMISSIONS', 'setting_user_local', 'acl_a_authusers && (acl_a_mauth || acl_a_fauth)'),
				(91, 1, 1, 'permissions', 'acp', 17, 173, 174, 'ACP_GROUPS_PERMISSIONS', 'setting_group_global', 'acl_a_authgroups && (acl_a_aauth || acl_a_mauth || acl_a_uauth)'),
				(92, 1, 1, 'permissions', 'acp', 14, 149, 150, 'ACP_GROUPS_PERMISSIONS', 'setting_group_global', 'acl_a_authgroups && (acl_a_aauth || acl_a_mauth || acl_a_uauth)'),
				(93, 1, 1, 'permissions', 'acp', 18, 189, 190, 'ACP_GROUPS_FORUM_PERMISSIONS', 'setting_group_local', 'acl_a_authgroups && (acl_a_mauth || acl_a_fauth)'),
				(94, 1, 1, 'permissions', 'acp', 14, 151, 152, 'ACP_GROUPS_FORUM_PERMISSIONS', 'setting_group_local', 'acl_a_authgroups && (acl_a_mauth || acl_a_fauth)'),
				(95, 1, 1, 'permissions', 'acp', 17, 175, 176, 'ACP_ADMINISTRATORS', 'setting_admin_global', 'acl_a_aauth && (acl_a_authusers || acl_a_authgroups)'),
				(96, 1, 1, 'permissions', 'acp', 17, 177, 178, 'ACP_GLOBAL_MODERATORS', 'setting_mod_global', 'acl_a_mauth && (acl_a_authusers || acl_a_authgroups)'),
				(97, 1, 1, 'permissions', 'acp', 20, 205, 206, 'ACP_VIEW_ADMIN_PERMISSIONS', 'view_admin_global', 'acl_a_viewauth'),
				(98, 1, 1, 'permissions', 'acp', 20, 207, 208, 'ACP_VIEW_USER_PERMISSIONS', 'view_user_global', 'acl_a_viewauth'),
				(99, 1, 1, 'permissions', 'acp', 20, 209, 210, 'ACP_VIEW_GLOBAL_MOD_PERMISSIONS', 'view_mod_global', 'acl_a_viewauth'),
				(100, 1, 1, 'permissions', 'acp', 20, 211, 212, 'ACP_VIEW_FORUM_MOD_PERMISSIONS', 'view_mod_local', 'acl_a_viewauth'),
				(101, 1, 1, 'permissions', 'acp', 20, 213, 214, 'ACP_VIEW_FORUM_PERMISSIONS', 'view_forum_local', 'acl_a_viewauth'),
				(102, 1, 1, 'php_info', 'acp', 29, 263, 264, 'ACP_PHP_INFO', 'info', 'acl_a_phpinfo'),
				(103, 1, 1, 'profile', 'acp', 13, 121, 122, 'ACP_CUSTOM_PROFILE_FIELDS', 'profile', 'acl_a_profile'),
				(104, 1, 1, 'prune', 'acp', 7, 69, 70, 'ACP_PRUNE_FORUMS', 'forums', 'acl_a_prune'),
				(105, 1, 1, 'prune', 'acp', 15, 163, 164, 'ACP_PRUNE_USERS', 'users', 'acl_a_userdel'),
				(106, 1, 1, 'ranks', 'acp', 13, 123, 124, 'ACP_MANAGE_RANKS', 'ranks', 'acl_a_ranks'),
				(107, 1, 1, 'reasons', 'acp', 29, 265, 266, 'ACP_MANAGE_REASONS', 'main', 'acl_a_reasons'),
				(108, 1, 1, 'search', 'acp', 5, 59, 60, 'ACP_SEARCH_SETTINGS', 'settings', 'acl_a_search'),
				(109, 1, 1, 'search', 'acp', 26, 247, 248, 'ACP_SEARCH_INDEX', 'index', 'acl_a_search'),
				(110, 1, 1, 'send_statistics', 'acp', 5, 61, 62, 'ACP_SEND_STATISTICS', 'send_statistics', 'acl_a_server'),
				(111, 1, 1, 'styles', 'acp', 22, 219, 220, 'ACP_STYLES', 'style', 'acl_a_styles'),
				(112, 1, 1, 'styles', 'acp', 23, 223, 224, 'ACP_TEMPLATES', 'template', 'acl_a_styles'),
				(113, 1, 1, 'styles', 'acp', 23, 225, 226, 'ACP_THEMES', 'theme', 'acl_a_styles'),
				(114, 1, 1, 'styles', 'acp', 23, 227, 228, 'ACP_IMAGESETS', 'imageset', 'acl_a_styles'),
				(115, 1, 1, 'update', 'acp', 28, 253, 254, 'ACP_VERSION_CHECK', 'version_check', 'acl_a_board'),
				(116, 1, 1, 'users', 'acp', 13, 113, 114, 'ACP_MANAGE_USERS', 'overview', 'acl_a_user'),
				(117, 1, 0, 'users', 'acp', 13, 125, 126, 'ACP_USER_FEEDBACK', 'feedback', 'acl_a_user'),
				(118, 1, 0, 'users', 'acp', 13, 127, 128, 'ACP_USER_WARNINGS', 'warnings', 'acl_a_user'),
				(119, 1, 0, 'users', 'acp', 13, 129, 130, 'ACP_USER_PROFILE', 'profile', 'acl_a_user'),
				(120, 1, 0, 'users', 'acp', 13, 131, 132, 'ACP_USER_PREFS', 'prefs', 'acl_a_user'),
				(121, 1, 0, 'users', 'acp', 13, 133, 134, 'ACP_USER_AVATAR', 'avatar', 'acl_a_user'),
				(122, 1, 0, 'users', 'acp', 13, 135, 136, 'ACP_USER_RANK', 'rank', 'acl_a_user'),
				(123, 1, 0, 'users', 'acp', 13, 137, 138, 'ACP_USER_SIG', 'sig', 'acl_a_user'),
				(124, 1, 0, 'users', 'acp', 13, 139, 140, 'ACP_USER_GROUPS', 'groups', 'acl_a_user && acl_a_group'),
				(125, 1, 0, 'users', 'acp', 13, 141, 142, 'ACP_USER_PERM', 'perm', 'acl_a_user && acl_a_viewauth'),
				(126, 1, 0, 'users', 'acp', 13, 143, 144, 'ACP_USER_ATTACH', 'attach', 'acl_a_user'),
				(127, 1, 1, 'words', 'acp', 10, 97, 98, 'ACP_WORDS', 'words', 'acl_a_words'),
				(128, 1, 1, 'users', 'acp', 2, 5, 6, 'ACP_MANAGE_USERS', 'overview', 'acl_a_user'),
				(129, 1, 1, 'groups', 'acp', 2, 7, 8, 'ACP_GROUPS_MANAGE', 'manage', 'acl_a_group'),
				(130, 1, 1, 'forums', 'acp', 2, 9, 10, 'ACP_MANAGE_FORUMS', 'manage', 'acl_a_forum'),
				(131, 1, 1, 'logs', 'acp', 2, 11, 12, 'ACP_MOD_LOGS', 'mod', 'acl_a_viewlogs'),
				(132, 1, 1, 'bots', 'acp', 2, 13, 14, 'ACP_BOTS', 'bots', 'acl_a_bots'),
				(133, 1, 1, 'php_info', 'acp', 2, 15, 16, 'ACP_PHP_INFO', 'info', 'acl_a_phpinfo'),
				(134, 1, 1, 'permissions', 'acp', 8, 73, 74, 'ACP_FORUM_PERMISSIONS', 'setting_forum_local', 'acl_a_fauth && (acl_a_authusers || acl_a_authgroups)'),
				(135, 1, 1, 'permissions', 'acp', 8, 75, 76, 'ACP_FORUM_PERMISSIONS_COPY', 'setting_forum_copy', 'acl_a_fauth && acl_a_authusers && acl_a_authgroups && acl_a_mauth'),
				(136, 1, 1, 'permissions', 'acp', 8, 77, 78, 'ACP_FORUM_MODERATORS', 'setting_mod_local', 'acl_a_mauth && (acl_a_authusers || acl_a_authgroups)'),
				(137, 1, 1, 'permissions', 'acp', 8, 79, 80, 'ACP_USERS_FORUM_PERMISSIONS', 'setting_user_local', 'acl_a_authusers && (acl_a_mauth || acl_a_fauth)'),
				(138, 1, 1, 'permissions', 'acp', 8, 81, 82, 'ACP_GROUPS_FORUM_PERMISSIONS', 'setting_group_local', 'acl_a_authgroups && (acl_a_mauth || acl_a_fauth)'),
				(139, 1, 1, '', 'mcp', 0, 1, 10, 'MCP_MAIN', '', ''),
				(140, 1, 1, '', 'mcp', 0, 11, 18, 'MCP_QUEUE', '', ''),
				(141, 1, 1, '', 'mcp', 0, 19, 32, 'MCP_REPORTS', '', ''),
				(142, 1, 1, '', 'mcp', 0, 33, 38, 'MCP_NOTES', '', ''),
				(143, 1, 1, '', 'mcp', 0, 39, 48, 'MCP_WARN', '', ''),
				(144, 1, 1, '', 'mcp', 0, 49, 56, 'MCP_LOGS', '', ''),
				(145, 1, 1, '', 'mcp', 0, 57, 64, 'MCP_BAN', '', ''),
				(146, 1, 1, 'ban', 'mcp', 145, 58, 59, 'MCP_BAN_USERNAMES', 'user', 'acl_m_ban'),
				(147, 1, 1, 'ban', 'mcp', 145, 60, 61, 'MCP_BAN_IPS', 'ip', 'acl_m_ban'),
				(148, 1, 1, 'ban', 'mcp', 145, 62, 63, 'MCP_BAN_EMAILS', 'email', 'acl_m_ban'),
				(149, 1, 1, 'logs', 'mcp', 144, 50, 51, 'MCP_LOGS_FRONT', 'front', 'acl_m_ || aclf_m_'),
				(150, 1, 1, 'logs', 'mcp', 144, 52, 53, 'MCP_LOGS_FORUM_VIEW', 'forum_logs', 'acl_m_,$"."id'),
				(151, 1, 1, 'logs', 'mcp', 144, 54, 55, 'MCP_LOGS_TOPIC_VIEW', 'topic_logs', 'acl_m_,$"."id'),
				(152, 1, 1, 'main', 'mcp', 139, 2, 3, 'MCP_MAIN_FRONT', 'front', ''),
				(153, 1, 1, 'main', 'mcp', 139, 4, 5, 'MCP_MAIN_FORUM_VIEW', 'forum_view', 'acl_m_,$"."id'),
				(154, 1, 1, 'main', 'mcp', 139, 6, 7, 'MCP_MAIN_TOPIC_VIEW', 'topic_view', 'acl_m_,$"."id'),
				(155, 1, 1, 'main', 'mcp', 139, 8, 9, 'MCP_MAIN_POST_DETAILS', 'post_details', 'acl_m_,$"."id || (!$"."id && aclf_m_)'),
				(156, 1, 1, 'notes', 'mcp', 142, 34, 35, 'MCP_NOTES_FRONT', 'front', ''),
				(157, 1, 1, 'notes', 'mcp', 142, 36, 37, 'MCP_NOTES_USER', 'user_notes', ''),
				(158, 1, 1, 'pm_reports', 'mcp', 141, 20, 21, 'MCP_PM_REPORTS_OPEN', 'pm_reports', 'aclf_m_report'),
				(159, 1, 1, 'pm_reports', 'mcp', 141, 22, 23, 'MCP_PM_REPORTS_CLOSED', 'pm_reports_closed', 'aclf_m_report'),
				(160, 1, 1, 'pm_reports', 'mcp', 141, 24, 25, 'MCP_PM_REPORT_DETAILS', 'pm_report_details', 'aclf_m_report'),
				(161, 1, 1, 'queue', 'mcp', 140, 12, 13, 'MCP_QUEUE_UNAPPROVED_TOPICS', 'unapproved_topics', 'aclf_m_approve'),
				(162, 1, 1, 'queue', 'mcp', 140, 14, 15, 'MCP_QUEUE_UNAPPROVED_POSTS', 'unapproved_posts', 'aclf_m_approve'),
				(163, 1, 1, 'queue', 'mcp', 140, 16, 17, 'MCP_QUEUE_APPROVE_DETAILS', 'approve_details', 'acl_m_approve,$"."id || (!$"."id && aclf_m_approve)'),
				(164, 1, 1, 'reports', 'mcp', 141, 26, 27, 'MCP_REPORTS_OPEN', 'reports', 'aclf_m_report'),
				(165, 1, 1, 'reports', 'mcp', 141, 28, 29, 'MCP_REPORTS_CLOSED', 'reports_closed', 'aclf_m_report'),
				(166, 1, 1, 'reports', 'mcp', 141, 30, 31, 'MCP_REPORT_DETAILS', 'report_details', 'acl_m_report,$"."id || (!$"."id && aclf_m_report)'),
				(167, 1, 1, 'warn', 'mcp', 143, 40, 41, 'MCP_WARN_FRONT', 'front', 'aclf_m_warn'),
				(168, 1, 1, 'warn', 'mcp', 143, 42, 43, 'MCP_WARN_LIST', 'list', 'aclf_m_warn'),
				(169, 1, 1, 'warn', 'mcp', 143, 44, 45, 'MCP_WARN_USER', 'warn_user', 'aclf_m_warn'),
				(170, 1, 1, 'warn', 'mcp', 143, 46, 47, 'MCP_WARN_POST', 'warn_post', 'acl_m_warn && acl_f_read,$"."id'),
				(171, 1, 1, '', 'ucp', 0, 1, 12, 'UCP_MAIN', '', ''),
				(172, 1, 1, '', 'ucp', 0, 13, 22, 'UCP_PROFILE', '', ''),
				(173, 1, 1, '', 'ucp', 0, 23, 30, 'UCP_PREFS', '', ''),
				(174, 1, 1, '', 'ucp', 0, 31, 42, 'UCP_PM', '', ''),
				(175, 1, 1, '', 'ucp', 0, 43, 48, 'UCP_USERGROUPS', '', ''),
				(176, 1, 1, '', 'ucp', 0, 49, 54, 'UCP_ZEBRA', '', ''),
				(177, 1, 1, 'attachments', 'ucp', 171, 10, 11, 'UCP_MAIN_ATTACHMENTS', 'attachments', 'acl_u_attach'),
				(178, 1, 1, 'groups', 'ucp', 175, 44, 45, 'UCP_USERGROUPS_MEMBER', 'membership', ''),
				(179, 1, 1, 'groups', 'ucp', 175, 46, 47, 'UCP_USERGROUPS_MANAGE', 'manage', ''),
				(180, 1, 1, 'main', 'ucp', 171, 2, 3, 'UCP_MAIN_FRONT', 'front', ''),
				(181, 1, 1, 'main', 'ucp', 171, 4, 5, 'UCP_MAIN_SUBSCRIBED', 'subscribed', ''),
				(182, 1, 1, 'main', 'ucp', 171, 6, 7, 'UCP_MAIN_BOOKMARKS', 'bookmarks', 'cfg_allow_bookmarks'),
				(183, 1, 1, 'main', 'ucp', 171, 8, 9, 'UCP_MAIN_DRAFTS', 'drafts', ''),
				(184, 1, 0, 'pm', 'ucp', 174, 32, 33, 'UCP_PM_VIEW', 'view', 'cfg_allow_privmsg'),
				(185, 1, 1, 'pm', 'ucp', 174, 34, 35, 'UCP_PM_COMPOSE', 'compose', 'cfg_allow_privmsg'),
				(186, 1, 1, 'pm', 'ucp', 174, 36, 37, 'UCP_PM_DRAFTS', 'drafts', 'cfg_allow_privmsg'),
				(187, 1, 1, 'pm', 'ucp', 174, 38, 39, 'UCP_PM_OPTIONS', 'options', 'cfg_allow_privmsg'),
				(188, 1, 0, 'pm', 'ucp', 174, 40, 41, 'UCP_PM_POPUP_TITLE', 'popup', 'cfg_allow_privmsg'),
				(189, 1, 1, 'prefs', 'ucp', 173, 24, 25, 'UCP_PREFS_PERSONAL', 'personal', ''),
				(190, 1, 1, 'prefs', 'ucp', 173, 26, 27, 'UCP_PREFS_POST', 'post', ''),
				(191, 1, 1, 'prefs', 'ucp', 173, 28, 29, 'UCP_PREFS_VIEW', 'view', ''),
				(192, 1, 1, 'profile', 'ucp', 172, 14, 15, 'UCP_PROFILE_PROFILE_INFO', 'profile_info', ''),
				(193, 1, 1, 'profile', 'ucp', 172, 16, 17, 'UCP_PROFILE_SIGNATURE', 'signature', ''),
				(194, 1, 1, 'profile', 'ucp', 172, 18, 19, 'UCP_PROFILE_AVATAR', 'avatar', 'cfg_allow_avatar && (cfg_allow_avatar_local || cfg_allow_avatar_remote || cfg_allow_avatar_upload || cfg_allow_avatar_remote_upload)'),
				(195, 1, 1, 'profile', 'ucp', 172, 20, 21, 'UCP_PROFILE_REG_DETAILS', 'reg_details', ''),
				(196, 1, 1, 'zebra', 'ucp', 176, 50, 51, 'UCP_ZEBRA_FRIENDS', 'friends', ''),
				(197, 1, 1, 'zebra', 'ucp', 176, 52, 53, 'UCP_ZEBRA_FOES', 'foes', ''),
				(198, 1, 1, 'board', 'acp', 10, 91, 92, 'ACP_POST_SETTINGS', 'post', 'acl_a_board')");
				
				$config_data = "<?php
	// phpBB 3.0.x auto-generated configuration file
	// Do not change anything in this file!
	".'$'."dbms = 'mysqli';
	".'$'."dbhost = 'localhost';
	".'$'."dbport = '';
	".'$'."dbname = '".$install_options['db_info']['db_forumname']."';
	".'$'."dbuser = '".$install_options['db_info']['db_username']."';
	".'$'."dbpasswd = '".$install_options['db_info']['db_password']."';
	".'$'."table_prefix = '".$install_options['db_info']['db_forumprefix']."_';
	".'$'."phpbb_adm_relative_path = 'adm/';
	".'$'."acm_type = 'phpbb\\cache\\driver\\file';

	@define('PHPBB_INSTALLED', true);
	// @define('PHPBB_DISPLAY_LOAD_TIME', true);
	@define('PHPBB_ENVIRONMENT', 'production');
	// @define('DEBUG_CONTAINER', true);

	".'$'."queryString = ".'$'."_SERVER['QUERY_STRING'];
	if ((stristr(".'$'."queryString,'%20union%20')) OR (stristr(".'$'."queryString,'%2f%2a')) OR (stristr(".'$'."queryString,'%2f*')) OR (stristr(".'$'."queryString,'/*')) OR (stristr(".'$'."queryString,'*/union/*')) OR (stristr(".'$'."queryString,'c2nyaxb0')) OR (stristr(".'$'."queryString,'+union+'))  OR ((stristr(".'$'."queryString,'cmd=')) AND (!stristr(".'$'."queryString,'&cmd'))) OR ((stristr(".'$'."queryString,'exec')) AND (!stristr(".'$'."queryString,'execu'))) OR (stristr(".'$'."queryString,'concat'))) {
	die('Illegal Operation');
	}
	?>";
				if(isset($install_options['db_info']['db_forumpath']) && $install_options['db_info']['db_forumpath'] != '' && file_exists($install_options['db_info']['db_forumpath']."/config.php"))
				{
					$fp = fopen($install_options['db_info']['db_forumpath']."/config.php", 'w');
					fputs($fp, $config_data);
					fclose($fp);
				}
			}
		}
	}
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_FORUM_TABLES'], 100, 100, 0, "install.php?op=".(($transfer_users) ? "users":"final")."", "", 99, 1000);
}

function upgrade_users($start)
{
	global $db, $nuke_configs, $cache, $pn_dbcharset, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}	
	
	$old_users_table_name = "`".OLD_DB."`.`".OLD_DB_PREFIX."_users`";
	$run_per_step = 2000;
	// update nuke_tags
	$insert_query = array();
	$db->query("set names 'latin1'");
	$result = $db->query("SELECT *, (SELECT COUNT(user_id) FROM $old_users_table_name) as total_rows FROM $old_users_table_name ORDER BY user_id ASC LIMIT $start, $run_per_step");
	$fetched_rows = intval($result->count());
	if($fetched_rows > 0)
	{
		$rows = $result->results();
		
		$total_rows_set = false;
		foreach($rows as $row)
		{
			if(!$total_rows_set)
			{
				$cache->store('total_rows', intval($row['total_rows']));
				unset($row['total_rows']);
				$total_rows_set = true;
			}
			
			$status = ($row['user_type'] == 0 || $row['user_type'] == 3) ? 1:0;
			
			$insert_query[] = array($row['user_id'], $status, 2,$row['user_ip'],$row['user_regdate'],$row['username'],$row['username_clean'], '',$row['user_email'],$row['user_birthday'],$row['user_lastvisit'],$row['user_lastpage'],$row['user_inactive_reason'],$row['user_inactive_time'],$row['user_lang'],$row['user_allow_viewonline'],$row['user_avatar'],$row['user_avatar_type'],$row['user_sig'],$row['user_from'],$row['user_website'],$row['user_interests'],$row['newsletter'],$row['points'],$row['femail'], 0);
		}
		
		$cols = array("user_id","user_status","group_id","user_ip","user_regdate","username","user_realname","user_password","user_email","user_birthday","user_lastvisit","user_lastpage","user_inactive_reason","user_inactive_time","user_lang","user_allow_viewonline","user_avatar","user_avatar_type","user_sig","user_address","user_website","user_interests","user_newsletter","user_points","user_femail","user_credit");		

		$db->query("set names '$pn_dbcharset'");
		if(isset($insert_query) && !empty($insert_query))
			$db->table(USERS_TABLE)->multiinsert($cols, $insert_query);
	}
	
	$new_start = $start+$run_per_step;
	$total_rows = $cache->retrieve('total_rows');
	
	upgrade_progress_output($nuke_languages['_INSTALL_TRANSFER_USERS'], $total_rows, $fetched_rows, $start, "install.php?op=final", "install.php?op=users&start=$new_start", 99, $run_per_step);
}

function upgrade_final()
{
	global $db, $nuke_configs, $cache, $Req_URL, $visitor_ip, $nuke_languages;
	
	if(!$cache->isCached('install_options'))
	{
		steps_error($nuke_languages['_INSTALL_DB_INFO_ERROR'], 7, 95);
	}
		
	$install_options = $cache->retrieve("install_options");
	$install_options = phpnuke_unserialize($install_options);
			
	$pwd = phpnuke_hash_password($install_options['admininfo']['pwd']);
	
	if($install_options['mode'] == 'install')
	{
		// add new article
		$db->table(POSTS_TABLE)
			->insert(array(
				'status' => 'publish',
				'post_type' => 'Articles',
				'aid' => $install_options['admininfo']['aid'],
				'title' => $nuke_languages['_INSTALL_HELLO_WORLD'],
				'time' => _NOWTIME,
				'ip' => $visitor_ip,
				'hometext' => addslashes($nuke_languages['_INSTALL_HELLO_WORLD_TEXT']),
				'post_url' => $nuke_languages['_INSTALL_HELLO_WORLD_LINK'],
				'alanguage' => '',
				'ihome' => 1,
				'allow_comment' => 1,
				'cat' => 1,
				'cat_link' => 1,
				'permissions' => '0',
				'position' => 1,
			));
		
		// add new blocks
		$default_blocks = array(
			array(1, 'Articles tags', 'block-Articles_tags.php'),
			array(2, 'Languages', 'block-Languages.php'),
			array(3, 'Last 5 Articles', 'block-Last_5_Articles.php'),
			array(4, 'MT-Forums', 'block-MT-Forums.php'),
			array(5, 'MT-ForumsTabed', 'block-MT-ForumsTabed.php'),
			array(6, 'Search', 'block-Search.php'),
			array(7, 'User Info', 'block-User_Info.php'),
			array(8, 'archive', 'block-archive.php'),
			array(9, 'comments', 'block-comments.php'),
			array(10, 'surveys', 'block-surveys.php'),
			array(11, 'Last comments', 'block-Last_comments.php'),
			array(12, 'credits', 'block-Credits.php'),
			array(13, 'invitation', 'block-invitation.php')
		);
		
		$db->table(BLOCKS_TABLE)
			->multiinsert(array('bid','title','blockfile'), $default_blocks);			
		
		$left_blocks_data = array(
			'7' => array(
				'title' => 'User Info',
				'lang_titles' => '',
				'blanguage' => '',
				'weight' => 1,
				'active' => 1,
				'time' => _NOWTIME,
				'permissions' => 0,
				'publish' => 0,
				'expire' => 0,
				'action' => '',
				'theme_block' => ''
			)
		);

		$right_blocks_data = array(
			'3' => array(
				'title' => 'Last 5 Articles',
				'lang_titles' => '',
				'blanguage' => '',
				'weight' => 1,
				'active' => 1,
				'time' => _NOWTIME,
				'permissions' => 0,
				'publish' => 0,
				'expire' => 0,
				'action' => '',
				'theme_block' => ''
			),
			'6' => array(
				'title' => 'Search',
				'lang_titles' => '',
				'blanguage' => '',
				'weight' => 2,
				'active' => 1,
				'time' => _NOWTIME,
				'permissions' => 0,
				'publish' => 0,
				'expire' => 0,
				'action' => '',
				'theme_block' => ''
			),
			'10' => array(
				'title' => 'surveys',
				'lang_titles' => '',
				'blanguage' => '',
				'weight' => 3,
				'active' => 1,
				'time' => _NOWTIME,
				'permissions' => 0,
				'publish' => 0,
				'expire' => 0,
				'action' => '',
				'theme_block' => ''
			),
			'8' => array(
				'title' => 'archive',
				'lang_titles' => '',
				'blanguage' => '',
				'weight' => 4,
				'active' => 1,
				'time' => _NOWTIME,
				'permissions' => 0,
				'publish' => 0,
				'expire' => 0,
				'action' => '',
				'theme_block' => ''
			),
			'12' => array(
				'title' => 'Credits',
				'lang_titles' => '',
				'blanguage' => '',
				'weight' => 4,
				'active' => 1,
				'time' => _NOWTIME,
				'permissions' => 3,
				'publish' => 0,
				'expire' => 0,
				'action' => '',
				'theme_block' => ''
			)
		);
		
		$topcenter_blocks_data = array(
			'5' => array(
				'title' => 'MT-ForumsTabed',
				'lang_titles' => '',
				'blanguage' => '',
				'weight' => 1,
				'active' => 1,
				'time' => _NOWTIME,
				'permissions' => 0,
				'publish' => 0,
				'expire' => 0,
				'action' => '',
				'theme_block' => ''
			)
		);
		
		$comments_blocks_data = array(
			'9' => array(
				'title' => 'comments',
				'lang_titles' => '',
				'blanguage' => '',
				'weight' => 1,
				'active' => 1,
				'time' => _NOWTIME,
				'permissions' => 0,
				'publish' => 0,
				'expire' => 0,
				'action' => '',
				'theme_block' => ''
			)
		);
		
		$left_blocks_data = addslashes(phpnuke_serialize($left_blocks_data));
		$right_blocks_data = addslashes(phpnuke_serialize($right_blocks_data));
		$topcenter_blocks_data = addslashes(phpnuke_serialize($topcenter_blocks_data));
		$comments_blocks_data = addslashes(phpnuke_serialize($comments_blocks_data));
		
		$db->table(BLOCKS_BOXES_TABLE)
			->where("box_id" , 'left')
			->update(array(
				'box_blocks' => '7',
				'box_blocks_data' => $left_blocks_data,
			));
			
		$db->table(BLOCKS_BOXES_TABLE)
			->where("box_id" , 'right')
			->update(array(
				'box_blocks' => '3,6,10,8,12',
				'box_blocks_data' => $right_blocks_data,
			));
			
		$db->table(BLOCKS_BOXES_TABLE)
			->where("box_id" , 'comments')
			->update(array(
				'box_blocks' => '9',
				'box_blocks_data' => $comments_blocks_data,
			));
		if($install_options['db_info']['db_have_forum'] == 1)
		{
			$db->table(BLOCKS_BOXES_TABLE)
				->where("box_id" , 'topcenter')
				->update(array(
					'box_blocks' => '5',
					'box_blocks_data' => $topcenter_blocks_data,
				));
		}	
		
		// add new uncategorized
		$default_cat = array(
			array(1,1,'Articles','uncategorized','a:2:{s:7:"english";s:13:"uncategorized";s:5:"farsi";s:19:"بدون موضوع";}','uncategorized'),
			array(2,1,'Downloads','uncategorized','a:2:{s:7:"english";s:13:"uncategorized";s:5:"farsi";s:19:"بدون موضوع";}','uncategorized'),
			array(3,1,'Pages','uncategorized','a:2:{s:7:"english";s:13:"uncategorized";s:5:"farsi";s:19:"بدون موضوع";}','uncategorized'),
			array(4,1,'Gallery','uncategorized','a:2:{s:7:"english";s:13:"uncategorized";s:5:"farsi";s:19:"بدون موضوع";}','uncategorized'),
			array(5,1,'Faqs','uncategorized','a:2:{s:7:"english";s:13:"uncategorized";s:5:"farsi";s:19:"بدون موضوع";}','uncategorized'),
		);
		$db->table(CATEGORIES_TABLE)
			->multiinsert(array('catid','type','module','catname','cattext','catdesc'),$default_cat);
		
		// add new poll
		$db->table(SURVEYS_TABLE)
			->insert(array(
				'pollID' => 1,
				'status' => 1,
				'aid' => $install_options['admininfo']['aid'],
				'canVote' => 1,
				'to_main' => 1,
				'multi_vote' => 0,
				'show_voters_num' => 0,
				'permissions' => "0",
				'allow_comment' => 1,
				'main_survey' => 1,
				'pollTitle' => $nuke_languages['_INSTALL_FIRST_POLL_TITLE'],
				'pollUrl' => $nuke_languages['_INSTALL_FIRST_POLL_LINK'],
				'start_time' => _NOWTIME,
				'options' => 'a:4:{i:0;a:2:{i:0;s:8:"عالی";i:1;i:0;}i:1;a:2:{i:0;s:6:"خوب";i:1;i:0;}i:2;a:2:{i:0;s:10:"متوسط";i:1;i:0;}i:3;a:2:{i:0;s:8:"ضعیف";i:1;i:0;}}',
			));
			
		// add new users data	
		$users_data = array(	
			array(1, 1, 1, 1, 'anonymous', $nuke_languages['_ANONYMOUS'], _NOWTIME, 'index.html', _NOWTIME,'',''),
			array(2, 3, 3, 1, $install_options['admininfo']['aid'], $install_options['admininfo']['realname'], _NOWTIME, 'index.html', _NOWTIME,$install_options['admininfo']['email'],$pwd)
		);

		$db->table(USERS_TABLE)
			->multiinsert(array('user_id', 'group_id', 'user_groups', 'user_status', 'username', 'user_realname', 'user_lastvisit', 'user_lastpage', 'user_regdate','user_email','user_password'),$users_data);
	}
	
	// add new groups data
	$default_groups = array(
		array(1, 1, 'GUESTS', 'a:2:{s:7:"english";s:6:"guests";s:5:"farsi";s:14:"مهمانان";}', 0, '#000000'),
		array(2, 1, 'REGISTERED', 'a:2:{s:7:"english";s:10:"registered";s:5:"farsi";s:14:"کاربران";}', 0, '#DE21EB'),
		array(3, 1, 'ADMINISTRATORS', 'a:2:{s:7:"english";s:6:"admins";s:5:"farsi";s:12:"مديران";}', 0, '#FF0000')
	);
	
	$db->table(GROUPS_TABLE)
		->multiinsert(array('group_id','group_type','group_name','group_lang_titles','group_options','group_colour'),$default_groups);
	
	// add new modules data	
	$modules_data = array(	
		array(1, 'Articles', 'a:2:{s:7:"english";s:8:"Articles";s:5:"farsi";s:10:"مطالب";}', 1, '0', '', 1, 1, 1, 'a:5:{s:5:"index";s:35:"right|left|topcenter|bottomcenter||";s:4:"more";s:18:"right|||comments||";s:7:"archive";s:10:"right|||||";s:12:"send_article";s:10:"right|||||";s:8:"category";s:10:"right|||||";}'),
		array(2, 'Search', 'a:2:{s:7:"english";s:6:"Search";s:5:"farsi";s:10:"جستجو";}', 1, '0', '', 1, 0, 1, 'a:1:{s:5:"index";s:10:"right|||||";}'),
		array(3, 'Surveys', 'a:2:{s:7:"english";s:7:"Surveys";s:5:"farsi";s:20:"نظر سنجی ها";}', 1, '0', '', 1, 0, 1, 'a:2:{s:5:"index";s:10:"right|||||";s:7:"results";s:10:"right|||||";}'),
		array(4, 'Feedback', 'a:2:{s:7:"english";s:10:"Contact Us";s:5:"farsi";s:18:"تماس با ما";}', 1, '0', '', 1, 0, 1, 'a:1:{s:5:"index";s:10:"right|||||";}'),
		array(6, 'Statistics', 'a:2:{s:7:"english";s:10:"Statistics";s:5:"farsi";s:21:"آمار بازدید";}', 1, '0', '', 1, 0, 1, 'a:2:{s:5:"index";s:10:"right|||||";s:8:"advanced";s:10:"right|||||";}'),
		array(8, 'Feed', 'a:2:{s:7:"english";s:4:"feed";s:5:"farsi";s:14:"خبرخوان";}', 1, '0', '', 0, 0, 0, ''),
		array(9, 'Users', 'a:2:{s:7:"english";s:5:"users";s:5:"farsi";s:23:"سیستم کاربری";}', 0, '0', '', 1, 0, 0, 'a:3:{s:12:"login_signup";s:10:"right|||||";s:4:"edit";s:10:"right|||||";s:7:"profile";s:10:"right|||||";}'),
		array(10, 'Credits', 'a:2:{s:7:"english";s:14:"credits system";s:5:"farsi";s:27:"سیستم اعتبارات";}', 1, '0', '', 1, 0, 1, 'a:2:{s:4:"list";s:10:"right|||||";s:4:"form";s:10:"right|||||";}'),
		array(11, 'Giapi', 'a:2:{s:7:"english";s:15:"Google Indexing";s:5:"farsi";s:15:"Google Indexing";}', 1, '', '', 0, 0, 0, NULL),
		array(12, 'UrlManager', 'a:2:{s:7:"english";s:10:"UrlManager";s:5:"farsi";s:36:"لینکهای تغییر یافته";}', 1, '', '', 0, 0, 0, NULL)
	);
	
	$db->table(MODULES_TABLE)
		->multiinsert(array('mid', 'title', 'lang_titles', 'active', 'mod_permissions', 'admins', 'all_blocks', 'main_module', 'in_menu', 'module_boxes'),$modules_data);
	
	
	// add new nav menu data	
	$nav_menus_data = array(	
		array(1, 1, 1, 0, 1, 'صفحه اصلی', 'index.html', 'a:4:{s:6:"target";s:0:"";s:3:"xfn";s:0:"";s:7:"classes";s:0:"";s:6:"styles";s:0:"";}', 'custom', '', 0),
		array(2, 1, 1, 0, 2, 'تماس با ما', 'index.php?modname=Feedback', 'a:4:{s:6:"target";s:0:"";s:3:"xfn";s:0:"";s:7:"classes";s:0:"";s:6:"styles";s:0:"";}', 'custom', '', 0),
		array(3, 1, 1, 0, 3, 'تالار گفتمان', 'Forum', 'a:4:{s:6:"target";s:0:"";s:3:"xfn";s:0:"";s:7:"classes";s:0:"";s:6:"styles";s:0:"";}', 'custom', '', 0),
		array(4, 1, 1, 0, 4, 'جستجو', 'index.php?modname=Search', 'a:4:{s:6:"target";s:0:"";s:3:"xfn";s:0:"";s:7:"classes";s:0:"";s:6:"styles";s:0:"";}', 'custom', '', 0)
	);
	
	$db->table(NAV_MENUS_TABLE)
		->insert([
			"nav_id" => 1,
			"nav_title" => 'فهرست اصلی',
			"lang_nav_title" => 'a:2:{s:7:"english";s:11:"main navbar";s:5:"farsi";s:19:"فهرست سایت";}',
			"nav_location" => 'primary',
			"status" => 1,
			"date" => '1496770474',
		]);
	
	$db->table(NAV_MENUS_DATA_TABLE)
		->multiinsert(array('nid','status','nav_id','pid','weight','title','url','attributes','type','module','part_id'),$nav_menus_data);
		
	if($install_options['mode'] == 'install')
	{
		// add new admin
		$db->table(AUTHORS_TABLE)
			->insert(array(
				'aid' => $install_options['admininfo']['aid'],
				'name' => 'God',
				'email' => $install_options['admininfo']['email'],
				'pwd' => $pwd,
				'counter' => 1,
				'realname' => $install_options['admininfo']['realname'],
			));
	}
	else
	{
		$db->table(AUTHORS_TABLE)
			->where("pwd" , $install_options['admininfo']['pwd'])
			->update(array(
				'pwd' => $pwd,
			));
	}
	

	// add configs data
	$install_options['siteinfo']['nukeurl'] = (isset($install_options['siteinfo']['nukeurl'])) ? $install_options['siteinfo']['nukeurl']:$Req_URL;
	$install_options['siteinfo']['sitename'] = (isset($install_options['siteinfo']['sitename'])) ? $install_options['siteinfo']['sitename']:"PhpNuke "._NEW_VERSION;
	$install_options['db_info']['db_have_forum'] = (isset($install_options['db_info']['db_have_forum'])) ? $install_options['db_info']['db_have_forum']:0;
	$install_options['db_info']['db_forumcms'] = (isset($install_options['db_info']['db_forumcms'])) ? $install_options['db_info']['db_forumcms']:"";
	$install_options['db_info']['db_forumprefix'] = (isset($install_options['db_info']['db_forumprefix'])) ? $install_options['db_info']['db_forumprefix']:"";
	$install_options['db_info']['db_forumname'] = (isset($install_options['db_info']['db_forumname'])) ? $install_options['db_info']['db_forumname']:"";
	$install_options['db_info']['db_forumpath'] = (isset($install_options['db_info']['db_forumpath'])) ? $install_options['db_info']['db_forumpath']:"";
	
	$config_data = array(
		"WHEN config_name = 'Version_Num' THEN '"._NEW_VERSION."'", 
		"WHEN config_name = 'lock_siteurl' THEN '".((isset($install_options['db_info']['nukeurl'])) ? 1:0)."'", 
		"WHEN config_name = 'nukeurl' THEN '".$install_options['siteinfo']['nukeurl']."'", 
		"WHEN config_name = 'sitename' THEN '".$install_options['siteinfo']['sitename']."'", 
		"WHEN config_name = 'language' THEN '".$install_options['install_lang']."'", 
		"WHEN config_name = 'locale' THEN '".$nuke_languages['_LOCALE']."'", 
		"WHEN config_name = 'have_forum' THEN '".$install_options['db_info']['db_have_forum']."'", 
		"WHEN config_name = 'forum_system' THEN '".$install_options['db_info']['db_forumcms']."'", 
		"WHEN config_name = 'forum_prefix' THEN '".$install_options['db_info']['db_forumprefix']."_'", 
		"WHEN config_name = 'forum_db' THEN '".$install_options['db_info']['db_forumname']."'", 
		"WHEN config_name = 'forum_path' THEN '".$install_options['db_info']['db_forumpath']."'", 
		"WHEN config_name = 'forum_collation' THEN '".$install_options['db_info']['db_forumunicode']."'", 
	);
	
	$db->query("UPDATE ".CONFIG_TABLE." SET config_value = CASE
		".implode("\n", $config_data)."
	END
	WHERE config_name IN ('Version_Num', 'lock_siteurl', 'nukeurl', 'sitename', 'have_forum', 'forum_system', 'forum_prefix', 'forum_db', 'forum_path', 'forum_collation', 'language')");

	change_htacces_RewriteBase();
	
	$rename_error = '';
	$random_install_folder_name = "install".rand(1,1000);
	define("_INSTALL_FOLDER", $random_install_folder_name);
	
	if(!rename("install",$random_install_folder_name) || !rename("install.php","install".rand(2000,9999).".php.back"))
	{
		$rename_error = "<br />".$nuke_configs['_INSTALL_RENAME_ERROR'];
	}	
	
	$cache->flush_caches();
	define("IN_FLUSH", true);
	cache_system('all');
	
	upgrade_progress_output(sprintf($nuke_languages['_INSTALL_FINISH_INSTALL'], (($install_options['mode'] == 'install') ? $nuke_languages['_INSTALL_FINISH_INSTALLED']:$nuke_languages['_INSTALL_FINISH_UPDATED'])).$rename_error, 1, 1, 0, "", "", 100, 1000);
	
	die();

}

$op = (isset($op)) ? $op:"start";
$step = (isset($step)) ? $step:"";
$start = (isset($start)) ? intval($start):0;
$transfer_counter = (isset($transfer_counter)) ? intval($transfer_counter):0;
$counter = (isset($counter)) ? intval($counter):0;

$function = (isset($step) && $step != '') ? "step_".$step:"upgrade_".$op;

$function($start);

?>