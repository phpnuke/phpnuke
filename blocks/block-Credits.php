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

global $db, $nuke_configs, $userinfo;

$content = "";

if(isset($userinfo['is_registered']) && $userinfo['is_registered'] == 1)
{
	$content = "
	<div class=\"text-center\">
	"._CREDITS_REMAIN."<br /><b>".number_format(user_credits_allowed($userinfo['user_id']))." "._RIAL."</b>
	</div>
	<div class=\"text-justify\">
		<ul>
			<li><a href=\"".LinkToGT("index.php?modname=Credits&op=credits_list")."\">"._CREDITS_LIST."</a></li>
			<li><a href=\"".LinkToGT("index.php?modname=Credits")."\">"._CREDITS_CHARGE."</a></li>
		</ul>
	</div>";
}

?>