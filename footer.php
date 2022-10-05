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

if (stristr(htmlentities($_SERVER['PHP_SELF']), "footer.php"))
{
	Header("Location: index.php");
	die();
}

define('NUKE_FOOTER', true);
global $db, $hooks, $nuke_configs, $users_system, $userinfo;

function footmsg()
{
	global $foot1, $foot2, $foot3, $copyright, $total_time, $start_time, $commercial_license, $footmsg;
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$end_time = $mtime;
	$total_time = ($end_time - $start_time);
	$total_time = _PAGEGENERATION." ".substr($total_time,0,4)." "._SECOND;
	$footmsg = "<span class=\"footmsg\">\n";
	if (!empty($foot1))
		$footmsg .= $foot1."<br>\n";
	if (!empty($foot2))
		$footmsg .= $foot2."<br>\n";
	if (!empty($foot3))
		$footmsg .= $foot3."<br>\n";
	// DO NOT REMOVE THE FOLLOWING COPYRIGHT LINE. YOU'RE NOT ALLOWED TO REMOVE NOR EDIT THIS.
	// IF YOU REALLY NEED TO REMOVE IT AND HAVE MY WRITTEN AUTHORIZATION CHECK: http://phpnuke.org/index.php?modname=Commercial_License
	// PLAY FAIR AND SUPPORT THE DEVELOPMENT, PLEASE!
	if ($commercial_license == 1)
		$footmsg .= $total_time."<br>\n</span>\n";
	else
		$footmsg .= $copyright."<br>$total_time<br>\n</span>\n";
	return $footmsg;
}

function foot()
{
	global $db, $user, $modname, $admin, $commercial_license, $nuke_configs, $users_system, $userinfo;
	//$html_output = @footmsg();

	$html_output = '';
	
	if (basename($_SERVER['PHP_SELF']) != "index.php" AND defined('MODULE_FILE') AND file_exists("modules/$modname/copyright.php") && $commercial_license != 1)
	{
		$cpname = str_replace("_", " ", $modname);
		$copyright_url = LinkToGT("modules/".$modname."/copyright.php");
		$html_output .= "<div align=\"right\"><a href=\"javascript:openwindow('$copyright_url')\">$cpname &copy;</a></div>";
	}
	if (basename($_SERVER['PHP_SELF']) != "index.php" AND defined('MODULE_FILE') AND (file_exists("modules/$modname/admin/panel.php") && is_admin()))
	{
		$html_output .= "<br>";
		$html_output .= OpenTable();
		@include("modules/$modname/admin/panel.php");
		$html_output .= CloseTable();                     
	}
	
	$ganalytic = stripslashes($nuke_configs['ganalytic']);
	if($ganalytic != "" && $ganalytic != 'analytic')
	{
	$html_output .= "<script type=\"text/javascript\">
	var _gaq = _gaq || [];
	_gaq.push(
	['_setAccount', '$ganalytic'],
	['_trackPageview']
	);
	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>";
	}
	if(!defined("HOME_FILE") || !isset($nuke_configs['website_index_theme']) || $nuke_configs['website_index_theme'] == 0 || ($nuke_configs['website_index_theme'] == 1 && !file_exists("themes/".$nuke_configs['ThemeSel']."/website_index.php")))
		$html_output .= themefooter();

	return $html_output;
}

$html_output .= (defined("ADMIN_FILE")) ? adminfooter():foot();
phpnuke_db_error();

if($nuke_configs['have_forum'] == 0)
	$users_system->online();

if(is_user())
{
	$user_id = intval($userinfo['user_id']);
	$db->table($users_system->users_table)
		->where($users_system->user_fields['user_id'], $user_id)
		->update([
			''.$users_system->user_fields['user_lastvisit'].'' => _NOWTIME
		]);
}

send_headers();

$html_output = $hooks->apply_filters("html_output", $html_output);

echo((($nuke_configs['minify_src']) ? minify_html($html_output):$html_output));

die();
?>