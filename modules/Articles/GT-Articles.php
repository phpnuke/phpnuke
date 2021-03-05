<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

$rewrite_rule["Articles"] = array(
	"(.+)$" => array("parse_post_links"),
);

$friendly_links = array(
	"index.php\?([^/]+)$" => array("parse_post_gt_links"),
);

?>