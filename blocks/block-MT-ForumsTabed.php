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

global $db, $nuke_configs, $block_global_contents, $users_system, $custom_theme_setup;

$content = "";

$MTForumTabed = new MTForumTabed();

$latest_topics = $MTForumTabed->MTForumTabed();
$custom_theme_setup = array_merge_recursive($custom_theme_setup, array(
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
	<div id=\"forum_tabs-1\"><div class=\"MTForumBlock\">$latest_topics</div></div>
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