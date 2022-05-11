<?php
/**************************************************************************/
/* PHP-NUKE: Advanced Content Management System                           */
/* ============================================                           */
/*                                                                        */
/* This is the language module with all the system messages               */
/*                                                                        */
/* If you made a translation, please go to the site and send to me        */
/* the translated file. Please keep the original text order by modules,   */
/* and just one message per line, also double check your translation!     */
/*                                                                        */
/* You need to change the second quoted phrase, not the capital one!      */
/*                                                                        */
/* If you need to use double quotes (") remember to add a backslash (\),  */
/* so your entry will look like: This is \"double quoted\" text.          */
/* And, if you use HTML code, please double check it.                     */
/**************************************************************************/

if (empty($nuke_languages['english']) || !is_array($nuke_languages['english']))
{
	$nuke_languages['english'] = array();
}

$nuke_languages['english'] = array_merge($nuke_languages['english'], array(
	"_URLS_MANAGER" => "Changed Urls",
	"_REDIRECT_CODE" => "Redirect Code",
	"_OLD_URL" => "Old Url",
	"_NEW_URL" => "New Url",
	"_OLD_URL_EXIST" => "Old url is exists",
	"_DELETE_LINK" => "Are you sure to delete this link ?",
	"_REDIERCT_CODE_ERROR" => "HTTP redirect status code must be a redirection code, 3xx.",
));
?>