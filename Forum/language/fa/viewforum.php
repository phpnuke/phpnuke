<?php
/**
*
* phpBB 3.3.X Project - Persian Translation
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
	'ACTIVE_TOPICS'		=> 'موضوعات فعال',
	'ANNOUNCEMENTS'		=> 'اطلاعیه‌ها',

	'FORUM_PERMISSIONS'		=> 'سطوح دسترسی انجمن',

	'ICON_ANNOUNCEMENT'		=> 'اطلاعیه‌ها',
	'ICON_STICKY'			=> 'سنجاق شده',

	'LOGIN_NOTIFY_FORUM'		=> 'شما در مورد این انجمن مطلع شده اید، لطفاً برای مشاهده آن وارد شوید.',

	'MARK_TOPICS_READ'		=> 'نشانه‌گذاری موضوعات بعنوان خوانده شده',

	'NEW_POSTS_HOT'		=> 'پست‌های جدید [ مشهور ]',	// Not used anymore
	'NEW_POSTS_LOCKED'		=> 'پست‌های جدید [ قفل شده ]',	// Not used anymore
	'NO_NEW_POSTS_HOT'		=> 'بدون پست جدید [ مشهور ]',	// Not used anymore
	'NO_NEW_POSTS_LOCKED'	=> 'بدون پست جدید [ قفل شده ]',	// Not used anymore
	'NO_READ_ACCESS'		=> 'شما اجازه خواندن و دیدن موضوعات این انجمن را ندارید.',
	'NO_FORUMS_IN_CATEGORY'	=> 'در این شاخه هیچ انجمنی وجود ندارد',
	'NO_UNREAD_POSTS_HOT'	=> 'بدون پست خوانده نشده [ مشهور ]',
	'NO_UNREAD_POSTS_LOCKED'	=> 'بدون پست خوانده نشده [ قفل شده ]',

	'POST_FORUM_LOCKED'		=> 'انجمن قفل شده است.',

	'TOPICS_MARKED'		=> 'موضوعات این انجمن بعنوان خوانده شده نشانه‌گذاری شدند.',

	'UNREAD_POSTS_HOT'		=> 'پست‌های خوانده نشده [ مشهور ]',
	'UNREAD_POSTS_LOCKED'	=> 'پست‌های خوانده نشده [ قفل شده ]',

	'VIEW_FORUM'			=> 'مشاهده انجمن',
	'VIEW_FORUM_TOPICS'		=> array(
		1	=> '%d موضوع',
		2	=> '%d موضوع',
	),
));
