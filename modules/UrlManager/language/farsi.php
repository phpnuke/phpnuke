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

if (empty($nuke_languages['farsi']) || !is_array($nuke_languages['farsi']))
{
	$nuke_languages['farsi'] = array();
}

$nuke_languages['farsi'] = array_merge($nuke_languages['farsi'], array(
	"_URLS_MANAGER" => "لینکهای تغییر یافته",
	"_REDIRECT_CODE" => "کد ریدارکت",
	"_OLD_URL" => "آدرس قدیمی",
	"_NEW_URL" => "آدرس جدید",
	"_OLD_URL_EXIST" => "آدرس قدیمی قبلا ثبت شده است",
	"_DELETE_LINK" => "آیا ار حدف این لینک اطمینان دارید ؟",
	"_REDIERCT_CODE_ERROR" => "عدد ریدایرکت باید به شکل <span dir=\"ltr\" style=\"text-align:left;direction:ltr;\">3xx</span> باشد",
));
?>