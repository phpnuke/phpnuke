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

    "_GENERAL_SETTINGS" => "General settings",
    "_ARTICLES_SETTINGS" => "Articles settings",
    "_SOCIAL_SETTINGS" => "Social networks settings",
    "_FOOTER_SETTINGS" => "Footer settings",
    "_SITE_LOGO" => "Site Logo",
    "_SITE_MOBILE_LOGO" => "Mobile logo",
	"_LOGO_IMG" => "Logo image",
    "_STICKY_HEADER" => "Sticky header",
    "_STICKY_HEADER_DESC" => "Do you want to enable sticky nav?",
    "_TOPBAR" => "Topbar",
    "_TOPBAR_DESC" => "Do you want to show topbar?",
    "_MENU_STYLE" =>  "Menu style",
    "_BOXED" =>  "Boxed",
    "_WIDE" =>  "Wide",
    "_CATEGORY_TEMPLATE" =>  "Category template",
    "_CATEGORY_TEMPLATE_DESC" => "Articles block style in category pages",
    "_STYLE" => "Style",
    "_ARTICLES_IMAGES" => "Articles images",
    "_ORIGINAL_PHOTO" => "Original photo",
    "_THUMB_PHOTO" => "Thumbnail images",
    "_SHOW_CATEGORY" => "Show category ",
    "_SHOW_CATEGORY_DESC" => "Do you want to show articles category ?",
    "_SHOW_EXCERPT_ARTICLES" => "Show excerpt articles",
    "_READ_MORE_BUTTON" => "Read more",
    "_READ_MORE_BUTTON_DESC" => "Do you want to show read more buttons?",
    "_READ_MORE_BUTTON_TEXT" => "Read more text",
    "_WHATSAPP_LINKS" => "Whatsapp link",
    "_INSTAGRAM_LINKS" => "Instagram link",
    "_FACEBOOK_LINKS" => "Facebook link",
    "_TELEGRAM_LINKS" => "Telegram link",
    "_SHOW_OLD_ARTICLES" => "Show old articless",
    "_SHOW_OLD_ARTICLES_FOOTER" => "Footer top / old articless show or hide.",
    "_OLD_ARTICLES" => "Old post",
    "_OLD_ARTICLES_DESC" => "See also the old post",
    "_SOCIAL_SHARE" => "Social share",
    "_SOCIAL_SHARE_FOOTER" => "Do you want to show social share buttons in footer?",
    "_BACK_TO_TOP" => "Back to top",
    "_ACTIVATE_SPECIAL_ARTICLES" => "Special articles",
    "_SPECIAL_ARTICLES_DESC" => "Set the position of the article to 2",
    "_ACTIVATE_TOP_ARTICLES" => " Top articles",
    "_TOP_ARTICLES_DESC" => "Set the position of the article to 3",
    "_NEXT_ARTICLES" => "Next post",
    "_PRV_ARTICLES" => "Prv post",
    "_COPYRIGHT_TEXT" => "Copyright text",
    "_COPYRIGHT_DESC" => "This text will be shown at the footer of all pages",
    "_COPYRIGHT_TITLE" => "All rights reserved for this site.",
    "_DSN" => "Design and development:<a href='http://toptemp.ir/'> TOPTEMP </a>" ,
    "POWERED_BY" => "Powered by <a href='http://phpnuke.ir/'> PHPNuke Farsi </a>" ,

));
?>