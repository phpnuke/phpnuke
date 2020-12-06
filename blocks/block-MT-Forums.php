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

global $db, $nuke_configs, $block_global_contents, $users_system, $custom_theme_setup;

$content = "";
$latest_topics = $users_system->MTForumBlock();
$custom_theme_setup = array_merge_recursive($custom_theme_setup, array(
	"defer_js" => array(
		"<script type=\"text/javascript\" language=\"javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/MTForum.js\"></script>"
	)
));

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