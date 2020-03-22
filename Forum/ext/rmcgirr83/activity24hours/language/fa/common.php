<?php

/**
*
*
* @package - Activity 24 hours
* @copyright (c) 2015 RMcGirr83
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
// Some characters you may want to copy&paste:
// ’ » “ ” …

$lang = array_merge($lang, array(
	'USERS_24HOUR_TOTAL'	=> array(
		1 => 'تعداد %d کاربر',
		2 => 'تعداد %d کاربر',
	),
	'GUEST_ONLINE_24'		=> array(
		1 => ' و %d نفر مهمان',
		2 => ' و %d نفر مهمان',
	),
	'TWENTYFOURHOUR_STATS'	=> ' آمار انجمن در 24 ساعت گذشته',
	'24HOUR_TOPICS'			=> '%d موضوع جدید',
	'24HOUR_POSTS'			=> '%d پست جدید',
	'24HOUR_USERS'			=> '%d کاربر جدید',

	'LAST_24_HOURS'			=> 'در 24 ساعت گذشته از انجمن بازدید داشتند',
));
