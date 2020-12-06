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

global $align;
$content = "
<form action=\"".LinkToGT("index.php?modname=Search")."\" method=\"post\" role=\"search\">
<div class=\"form-group input-group text-center\">
	<input type=\"text\" class=\"form-control\" placeholder=\"Search..\" value=\"\" name=\"search_query\">
	<span class=\"input-group-btn\">
		<button class=\"btn btn-default\" type=\"submit\" name=\"submit\" value=\"ok\">
			<span class=\"glyphicon glyphicon-search\"></span>
		</button>
	</span>
</div>
<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
</form>";

?>