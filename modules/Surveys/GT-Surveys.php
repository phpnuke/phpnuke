<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

$rewrite_rule["surveys"] = array(
	"surveys/([^/]+)/(result)?/?$" => 'index.php?modname=Surveys&op=poll_show&pollUrl=$1&mode=$2',
	"surveys/$" => 'index.php?modname=Surveys',
);

$friendly_links = array(
	"index.php\?modname=Surveys$" => "surveys/",
);

?>