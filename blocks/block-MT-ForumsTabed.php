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
########################################################################
# PHP-Nuke Block: MashhadTeam Center Forum Block v.2 tabbed 		   #
# Made for PHP-Nuke 8.4                                                #
#                                                                      #
# Made by mahmood namvar [iman64]                                      #
# phpnukiha@yahoo.com                                				   #
########################################################################

if (!defined('BLOCK_FILE'))
{
    Header("Location: ../index.php");
    die();
}

function MTForumBlock_assets($theme_setup)
{
	global $nuke_configs;
	$theme_setup = array_merge_recursive($theme_setup, array(
		"default_css" => array(),
		"default_js" => array(),
		"defer_js" => array(
			"<script type=\"text/javascript\" language=\"javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/MTForumTabed.js\"></script>",
			"<script>
				$(function() {
					var new_index;
					$(\"#forum_tabs\").tabs({
						show: { effect: \"blind\", duration: 100 },
						activate: function(event, ui) {
							new_index = ui.newTab.index()+1;
							ChangeTabedForumPage('First', new_index, '', '');
						}
					});
					$(\"#forum_tabs\").show(0);
				});
			</script>"
		)
	));
	return $theme_setup;
}

global $db, $nuke_configs, $users_system, $hooks;

$content = "";

$latest_topics = new MTForumTabed();

$hooks->add_filter("site_theme_headers", "MTForumBlock_assets", 10);

$content .= "
<div id=\"forum_tabs\" style=\"display:none;\" class=\"MTForumBlock\">
	<ul>
		<li><a href=\"#forum_tabs-1\">"._ALL."</a></li>
		<li><a href=\"#forum_tabs-2\">"._MOST_VISITED."</a></li>
		<li><a href=\"#forum_tabs-3\">"._MOST_REPLYES."</a></li>
		<li><a href=\"#forum_tabs-4\">"._MOST_IMPORTANT."</a></li>
		<li><a href=\"#forum_tabs-5\">"._ANNOUNCEMENTS."</a></li>
		<li><a href=\"#forum_tabs-6\">"._SEARCH."</a></li>
	</ul>
	<div id=\"forum_tabs-1\"><div class=\"MTForumBlock\">".$latest_topics->result."</div></div>
	<div id=\"forum_tabs-2\"></div>
	<div id=\"forum_tabs-3\"></div>
	<div id=\"forum_tabs-4\"></div>
	<div id=\"forum_tabs-5\"></div>
	<div id=\"forum_tabs-6\"><input type=\"text\" style=\"width:300px;padding:5px;visibility:hidden;\" id=\"MTFSerach_input\" value=\"\" /><input type=\"button\" value=\""._SEARCH."\" style=\"width:50px;padding:5px;visibility:hidden;\" id=\"MTFSerach_submit\" /><br /></div>

	<ul class=\"pager MTFpager\">
		<li class=\"previous\"><a id=\"MTFNext_button\" href=\"javascript:ChangeTabedForumPage('Prev', 1, '', '')\">"._PREV."</a></li>
		<li id=\"MTFloader\"></li>
		<li class=\"next\"><a id=\"MTFPrev_button\" href=\"javascript:ChangeTabedForumPage('Next', 1, '', '')\">"._NEXT."</a></li>
	</ul>
</div>";
?>