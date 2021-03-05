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