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
	'APPROVE'							=> 'تأیید',
	'ATTACHMENT'						=> 'پیوست',
	'ATTACHMENT_FUNCTIONALITY_DISABLED'	=> 'قابلیت پیوست غیرفعال شده است.',

	'BOOKMARK_ADDED'		=> 'موضوع با موفقیت نشانه‌گذاری شد.',
	'BOOKMARK_ERR'			=> 'نشانه‌گذاری  این موضوع با شکست مواجه شد. لطفا مجددا تلاش کنید.',
	'BOOKMARK_REMOVED'		=> 'نشانه‌گذاری موضوع با موفقیت حذف شد.',
	'BOOKMARK_TOPIC'		=> 'نشانه‌گذاری موضوع',
	'BOOKMARK_TOPIC_REMOVE'	=> 'حذف از نشانه‌گذاری ها',
	'BUMPED_BY'				=> 'آخرین بالا اندازی (Bump) توسط %1$s در %2$s.',
	'BUMP_TOPIC'			=> 'بالا انداختن (Bump کردن)',

	'DELETE_TOPIC'			=> 'حذف موضوع',
	'DELETED_INFORMATION'	=> 'حذف شده توسط %1$s در %2$s',
	'DISAPPROVE'			=> 'تأیید نشده',
	'DOWNLOAD_NOTICE'		=> 'شما برای مشاهده فایل پیوست شده در این پست، دسترسی ندارید.',

	'EDITED_TIMES_TOTAL'	=> array(
		1	=> 'آخرین ویرایش توسط %2$s در %3$s، در مجموع %1$d بار ویرایش شده است.',
		2	=> 'آخرین ویرایش توسط %2$s در %3$s، در مجموع %1$d بار ویرایش شده است.',
	),
	'EMAIL_TOPIC'			=> 'ارسال به دوستان',
	'ERROR_NO_ATTACHMENT'	=> 'پیوست انتخاب شده دیگر موجود نیست.',

	'FILE_NOT_FOUND_404'	=> 'فایل <strong>%s</strong> موجود نیست.',
	'FORK_TOPIC'			=> 'کپی موضوع',
	'FULL_EDITOR'			=> 'ارسال پیشرفته و پیش نمایش',

	'LINKAGE_FORBIDDEN'		=> 'شما مجاز به مشاهده، بارگیری یا پیوند از/به این سایت نیستید.',
	'LOGIN_NOTIFY_TOPIC'	=> 'شما در مورد این موضوع مطلع شده اید، لطفاً برای مشاهده آن وارد شوید.',
	'LOGIN_VIEWTOPIC'		=> 'برای مشاهده این موضوع حتما باید عضو شوید و با شناسه کاربری خود وارد شوید.',

	'MAKE_ANNOUNCE'				=> 'تغییر به ”اطلاعیه“',
	'MAKE_GLOBAL'				=> 'تغییر به ”اطلاعیه سراسری“',
	'MAKE_NORMAL'				=> 'تغییر به ”موضوع استاندارد“',
	'MAKE_STICKY'				=> 'تغییر به ”سنجاق شده“',
	'MAX_OPTIONS_SELECT'		=> array(
		1	=> 'شما می‌توانید <strong>%d</strong> گزینه را انتخاب نمایید',
		2	=> 'شما می‌توانید حداکثر تا <strong>%d</strong> گزینه را انتخاب نمایید',
	),
	'MISSING_INLINE_ATTACHMENT'	=> 'فایل پیوست <strong>%s</strong> دیگر موجود نیست',
	'MOVE_TOPIC'				=> 'انتقال موضوع',

	'NO_ATTACHMENT_SELECTED'=> 'شما هیچ پیوستی برای دریافت یا نمایش انتخاب نکردید.',
	'NO_NEWER_TOPICS'		=> 'موضوع جدید تری در این انجمن وجود ندارد.',
	'NO_OLDER_TOPICS'		=> 'موضوع قدیمی تری در این انجمن وجود ندارد.',
	'NO_UNREAD_POSTS'		=> 'پست ناخوانده دیگری در این انجمن موجود نیست.',
	'NO_VOTE_OPTION'		=> 'در هنگام ارسال رأی، باید یک گزینه را انتخاب کنید.',
	'NO_VOTES'				=> 'بدون رأی',
	'NO_AUTH_PRINT_TOPIC'	=> 'شما اجازه چاپ موضوع را ندارید.',

	'POLL_ENDED_AT'			=> 'پایان نظرسنجی در %s',
	'POLL_RUN_TILL'			=> 'مدت زمان نظر سنجی تا %s ادامه خواهد داشت.',
	'POLL_VOTED_OPTION'		=> 'شما به این گزینه رأی داده اید',
	'POST_DELETED_RESTORE'	=> 'این پست حذف شده، اما قابل بازگردانی می‌باشد.',
	'PRINT_TOPIC'			=> 'نمایش حالت چاپ',

	'QUICK_MOD'				=> 'ابزار فوری',
	'QUICKREPLY'			=> 'پاسخ سریع',

	'REPLY_TO_TOPIC'		=> 'پاسخ به موضوع',
	'RESTORE'				=> 'بازگردانی',
	'RESTORE_TOPIC'			=> 'بازگردانی موضوع',
	'RETURN_POST'			=> '%sبازگشت به پست%s',

	'SUBMIT_VOTE'			=> 'ثبت رأی شما',

	'TOPIC_TOOLS'			=> 'ابزار موضوع',
	'TOTAL_VOTES'			=> 'مجموع رأی ها',

	'UNLOCK_TOPIC'			=> 'بازکردن موضوع',

	'VIEW_INFO'				=> 'جزئیات پست',
	'VIEW_NEXT_TOPIC'		=> 'موضوع بعدی',
	'VIEW_PREVIOUS_TOPIC'	=> 'موضوع قبلی',
	'VIEW_RESULTS'			=> 'نمایش نتیجه',
	'VIEW_TOPIC_POSTS'		=> array(
		1	=> 'تعداد پست‌ها:%d',
		2	=> 'تعداد پست‌ها:%d',
	),
	'VIEW_UNREAD_POST'		=> 'اولین پست خوانده نشده',
	'VOTE_SUBMITTED'		=> 'رأی شما با موفقیت ثبت شد.',
	'VOTE_CONVERTED'		=> 'امکان تغییر رأی وجود ندارد.',

));
