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

global $db, $nuke_configs, $block_global_contents, $userinfo;

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

?>