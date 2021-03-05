<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('BLOCK_FILE'))
{
    Header("Location: ../index.php");
    die();
}

global $db, $nuke_configs, $users_system, $hooks;

$content = "";
$latest_topics = $users_system->MTForumBlock();

$hooks->add_filter("site_theme_headers", function ($theme_setup) use($nuke_configs)
{
	$theme_setup = array_merge_recursive($theme_setup, array(
		"defer_js" => array(
			"<script type=\"text/javascript\" language=\"javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/MTForum.js\"></script>"
		)
	));
	return $theme_setup;
}, 10);

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