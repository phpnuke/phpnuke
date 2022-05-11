<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* Part: blocks				                                            */
/* Part Name: block-ads		                                            */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('BLOCK_FILE'))
{
    Header("Location: ../index.php");
    die();
}

function MTForum_block_assets($theme_setup)
{
	global $nuke_configs;
	$theme_setup = array_merge_recursive($theme_setup, array(
		"defer_js" => array(
			"<script type=\"text/javascript\" language=\"javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/MTForum.js\"></script>"
		)
	));
	return $theme_setup;
}

global $db, $nuke_configs, $users_system, $hooks;

$content = "";
$latest_topics = $users_system->MTForumBlock();

$hooks->add_filter("site_theme_headers", "MTForum_block_assets", 10);

$content .= "
<div id=\"MTForumBlock\">
$latest_topics
</div>
<ul class=\"pager\">
	<li class=\"previous\"><a href=\"javascript:ChangeForumPage('Prev')\">"._PREV."</a></li>
	<li id=\"MTFloader\"></li>
	<li class=\"next\"><a href=\"javascript:ChangeForumPage('Next')\">"._NEXT."</a></li>
</ul>";
?>