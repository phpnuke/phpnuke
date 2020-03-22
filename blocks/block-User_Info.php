<?php
/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if ( !defined('BLOCK_FILE') )
{
    Header("Location: ../index.php");
    die();
}

global $users_system, $userinfo, $nuke_configs, $block_global_contents;

$statistics_contents = $users_system->user_statistics();

if(file_exists("themes/".$nuke_configs['ThemeSel']."/userinfo.php"))
	include("themes/".$nuke_configs['ThemeSel']."/userinfo.php");
else
{
	$content = "
	<p><a href=\"".LinkToGT("index.php?modname=Statistics")."\">"._SITESTATISTICS."</a></p>
	<div class=\"statistic\">";
		if(is_user())
		{
			$content .= "
			<p><span class=\"glyphicon glyphicon-bullhorn\"></span> ".$statistics_contents['wellcom_user']."</p>
			<p class=\"text-center\"><a title=\""._SPECIFICATIONS."\" href=\"".$statistics_contents['user_profile_url']."\"><img src=\"".$statistics_contents['user_avatar_image']."\" class=\"img-rounded\" alt=\"".$statistics_contents['wellcom_user']."\" width=\"".$userinfo['user_avatar_width']."\" height=\"".$userinfo['user_avatar_height']."\"></a></p>
			<p><a title=\""._SPECIFICATIONS."\" href=\"".$statistics_contents['user_profile_url']."\"><span class=\"glyphicon glyphicon-home\"></span> "._USER_PROFILE."</a></p>
			<ul>
				<li>"._POINTS.": ".$statistics_contents['user_points']."</li>";
				if(isset($statistics_contents['user_new_privmsg']))
				{
					$content .= "<li>"._NEW_PMS.": ".$statistics_contents['user_new_privmsg']."</li>
					<li>"._UNREAD_PMS.": ".$statistics_contents['user_unread_privmsg']."</li>";
				}
				$content .= "<li><a href=\"".$statistics_contents['user_logout_url']."\">"._LOGOUT."</a></li>
			</ul>";
		}
		else
		{
			$content .= "<p><span class=\"glyphicon glyphicon-bullhorn\"></span> ".$statistics_contents['wellcom_guest']."</p>";
			$content .= $statistics_contents['login_form'];
		}
		
		$content .= "
		<p><span class=\"glyphicon glyphicon-user\"></span> "._REGISTER."</p>
		<ul>
			<li>"._TODAY.": ".$statistics_contents['today_register']."</li>
			<li>"._YESTERDAY.": ".$statistics_contents['yesterday_register']."</li>
			<li>"._TOTAL_USERS.": ".$statistics_contents['total_users']."</li>
			<li>"._NEWEST_USER.": <a href=\"".$statistics_contents['last_user_profile_url']."\">".$statistics_contents['last_username']."</a></li>
		</ul>
		<p><span class=\"glyphicon glyphicon-time\"></span> "._VIEWERS_STATISTICS."</p>
		<ul>
			<li>"._TODAY_VIEWS." : ".$statistics_contents['today_visits']."</li>
			<li>"._YESTERDAY_VIEWS." : ".$statistics_contents['yesterday_visits']."</li>
			<li>"._TOTAL_VIEWS." : ".$statistics_contents['total_visits']."</li>
		</ul>
		<p><span class=\"glyphicon glyphicon-equalizer\"></span> "._MOSTONLINE."</p>
		<ul>
			<li>"._GUEST.": ".$statistics_contents['total_gusts']."</li>
			<li>"._MEMBERS.": ".$statistics_contents['total_members']."</li>
			<li>"._TOTAL.": ".$statistics_contents['total_mostonline']."</li>
		</ul>
		<p><span class=\"glyphicon glyphicon-globe\"></span>  "._ONLINE_STATUS."</p>
		<ul>
			<li>"._GUEST.": ".sizeof($statistics_contents['online_gusts'])."</li>
			<li>"._MEMBERS.": ".sizeof($statistics_contents['online_members'])."</li>";
			if(is_admin())
				$content .= "<li>"._HIDDEN.": ".$statistics_contents['hidden_online']."</li>";
			$content .= "<li>"._TOTAL.": ".$statistics_contents['total_onlines']."</li>
		</ul>
		<p><span class=\"glyphicon glyphicon-eye-open\"></span>  "._ONLINE_MEMBERS."</p>
		<ul class=\"list-group\">";
		if(!empty($statistics_contents['online_members']))
		{
			foreach($statistics_contents['online_members'] as $online_member)
				$content .= "<li class=\"list-group-item\">".$online_member['where'].".&nbsp; <a href=\"".$online_member['profile']."\" style=\"color:#".str_replace("#","", $online_member['group_colour'])." !important;\">".$online_member['username']."</a> <span class=\"badge\">".$online_member['user_posts']."</span></li>";
		}
		$content .= "</ul>
		<p class=\"text-center\"><span class=\"glyphicon glyphicon-refresh\"></span></p>
		<div class=\"clearfix\"></div>
	</div>
	";
}

?>