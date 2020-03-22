<?php
/**
*
* phpBB 3.2.X Project - Persian Translation
* Translators: PHP-BB.IR Group Meis@M Nobari
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

/**
* DO NOT CHANGE
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

$lang = array_merge($lang, array(
	'ACTIVE_TOPICS'			=> 'مباحث فعال',
	'ANNOUNCEMENTS'			=> 'اطلاعیه های کلی',

	'FORUM_PERMISSIONS'		=> 'سطوح دسترسي انجمن',

	'ICON_ANNOUNCEMENT'		=> 'اطلاعیه کلی',
	'ICON_STICKY'			=> 'مهم',

	'LOGIN_NOTIFY_FORUM'	=> 'شما اطلاعاتي درباره اين انجمن نياز داريد ، لطفا وارد شويد و آنرا مشاهده کنيد.',

	'MARK_TOPICS_READ'		=> 'نشانه گذاري مبحث ها بعنوان خوانده شده',

	'NEW_POSTS_HOT'			=> 'پست هاي جديد (محبوب)',	// Not used anymore
	'NEW_POSTS_LOCKED'		=> 'پست هاي جديد (بسته است)',	// Not used anymore
	'NO_NEW_POSTS_HOT'		=> 'بدون پست جديد (محبوب)',	// Not used anymore
	'NO_NEW_POSTS_LOCKED'	=> 'بدون پست جديد (بسته شده)',	// Not used anymore
	'NO_READ_ACCESS'		=> 'شما اجازه خواندن و دیدن مباحث اين انجمن را نداريد.',
	'NO_FORUMS_IN_CATEGORY'	=> 'این شاخه زیر انجمنی ندارد',
	'NO_UNREAD_POSTS_HOT'		=> 'بدون پست خوانده نشده [ مشهور]',
	'NO_UNREAD_POSTS_LOCKED'	=> 'بدون پست خوانده نشده [قفل شده]',

	'POST_FORUM_LOCKED'		=> 'انجمن بسته است.',

	'TOPICS_MARKED'			=> 'مبحث هاي اين انجمن بعنوان خوانده شده نشانه گذاري شدند.',

	'UNREAD_POSTS_HOT'		=> 'پست های خوانده نشده [ مشهور ]',
	'UNREAD_POSTS_LOCKED'	=> 'ست های خوانده نشده [ قفل شده ]',

	'VIEW_FORUM'			=> 'مشاهده انجمن',
	'VIEW_FORUM_TOPICS'		=> array(
		1	=> 'تعداد موضوعات %d',
		2	=> 'تعداد موضوعات %d',
	),
));
