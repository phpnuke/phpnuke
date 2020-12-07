<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/* Block to fit perfectly in the center of the site, remember that not all
   blocks looks good on Center, just try and see yourself what fits your needs */

if ( !defined('BLOCK_FILE') ) {
    Header("Location: ../index.php");
    die();
}

global $nuke_configs, $db, $block_global_contents;

$params = array();

$querylang = "WHERE status = 'publish' AND post_type = 'Articles'";

if ($nuke_configs['multilingual'] == 1) {
    $querylang .= " AND (alanguage=:currentlang OR alanguage=:alanguage)";
	$params[":currentlang"] = $nuke_configs['currentlang'];
	$params[":alanguage"] = '';
}

$content = "<ul class=\"last-5-articles\">";
$thismonth = "";//gregorian date
$thisjmonth = "";//jalali date
$thishmonth = "";//hijri date
$params[] = 0;
$params[] = 5;

$result = $db->query("SELECT sid, title, comments, counter , post_url, time, cat_link FROM ".POSTS_TABLE." $querylang ORDER BY time DESC LIMIT ?,?", $params);

if($db->count())
{
	foreach($result as $row)
	{

		$sid = intval($row['sid']);
		$title = filter($row['title'], "nohtml");
		$post_url = filter($row['post_url'], "nohtml");
		$comtotal = intval($row['comments']);
		$counter = intval($row['counter']);
		$cat_link = intval($row['cat_link']);
		$time = $row['time'];
		$llink = LinkToGT(articleslink($sid, $title, $post_url, $time, $cat_link));
		$content .= "<li>&nbsp;<a href=\"$llink\" title=\"$comtotal "._COMMENTS." - $counter "._READS."\">$title</a></li>";
	}
}

$content .= "</ul>";
$content .= "<br><div class=\"text-center\">[ <a href=\"".LinkToGT("index.php?modname=Articles")."\">"._MORENEWS."</a> ]</div>";

?>