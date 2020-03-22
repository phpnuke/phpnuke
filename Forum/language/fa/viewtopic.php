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
	'APPROVE'								=> 'تایید',
	'ATTACHMENT'						=> 'پیوست',
	'ATTACHMENT_FUNCTIONALITY_DISABLED'	=> 'بخش پیوست غیرفعال شده است.',

	'BOOKMARK_ADDED'		=> 'مبحث با موفقیت نشانه گذاری شد.',
	'BOOKMARK_ERR'			=> 'نشان گذاری (Bookmark) این موضوع با شکست مواجه شد.لطفا مجددا تلاش کنید',
	'BOOKMARK_REMOVED'		=> 'نشانه گذاری مبحث با موفقیت حذف شد.',
	'BOOKMARK_TOPIC'		=> 'نشانه گذاری مبحث',
	'BOOKMARK_TOPIC_REMOVE'	=> 'حذف از نشانه گذاری ها',
	'BUMPED_BY'				=> 'آخرین بالا اندازی (Bump) توسط %1$s در %2$s.',
	'BUMP_TOPIC'			=> 'بالا انداختن (Bump کردن) ',

	'CODE'					=> 'کد',

	'DELETE_TOPIC'			=> 'حذف موضوع',
	'DELETED_INFORMATION'	=> 'حذف شده توسط %1$s در تاریخ %2$s',
	'DISAPPROVE'					=> 'تایید نشده',
	'DOWNLOAD_NOTICE'		=> 'شما دسترسی جهت مشاهده فایل پیوست این پست را ندارید.',

	'EDITED_TIMES_TOTAL'	=> array(
		1	=> 'آخرین ويرايش توسط %1$s on %2$s, ويرايش شده در %3$d.',
		2	=> 'آخرین ويرايش توسط %1$s on %2$s, ويرايش شده در %3$d.',
	),
	'EMAIL_TOPIC'			=> 'ارسال به دوستان',
	'ERROR_NO_ATTACHMENT'	=> 'ضمیمه انتخاب شده دیگر موجود نیست.',

	'FILE_NOT_FOUND_404'	=> 'فایل <strong>%s</strong> موجود نیست.',
	'FORK_TOPIC'			=> 'کپی موضوع',
	'FULL_EDITOR'			=> 'ارسال پیشرفته و پیش نمایش',

	'LINKAGE_FORBIDDEN'		=> 'شما اجازه نمایش،دریافت یا لینک دادن از/به این سایت را ندارید.',
	'LOGIN_NOTIFY_TOPIC'	=> 'شما از این تاپیک مطلع شدید.برای مشاهده باید با شناسه کاربری خود وارد شوید.',
	'LOGIN_VIEWTOPIC'		=> 'برای مشاهده این موضوع حتما باید عضو شوید و با شناسه کاربری خود وارد شوید.',

	'MAKE_ANNOUNCE'				=> 'تغییر به "اطلاعیه"',
	'MAKE_GLOBAL'				=> 'تغيير به “اطلاعيه کلي (درهمه انجمن ها)”',
	'MAKE_NORMAL'				=> 'تغيير به “مبحث استاندارد”',
	'MAKE_STICKY'				=> 'تغيير به “مهم”',
	'MAX_OPTIONS_SELECT'		=> array(
		1	=> 'شما می توانید حداکثر تا <strong>%d</strong> گزینه را انتخاب نمایید.',
		2	=> 'شما مي توانيد <strong>%d</strong> گزينه را انتخاب نماييد',
	),
	'MISSING_INLINE_ATTACHMENT'	=> 'فایل ضمیمه <strong>%s</strong> دیگر موجود نیست',
	'MOVE_TOPIC'				=> 'انتقال موضوع',

	'NO_ATTACHMENT_SELECTED'=> 'شماهیچ ضمیمه ای برای دریافت یا نمایش انتخاب نکردید.',
	'NO_NEWER_TOPICS'		=> 'موضوع جدید تری در این انجمن وجود ندارد.',
	'NO_OLDER_TOPICS'		=> 'موضوع قدیمی تری در این انجمن وجود ندارد.',
	'NO_UNREAD_POSTS'		=> 'پست ناخوانده دیگری در این انجمن موجود نیست.',
	'NO_VOTE_OPTION'		=> 'در هنگام ارسال راي لطفا يک گزينه را انتخاب کنيد.',
	'NO_VOTES'				=> 'بدون راي',

	'POLL_ENDED_AT'			=> 'پايان نظرسنجي در %s',
	'POLL_RUN_TILL'			=> 'مدت زمان نظر سنجی تا %s ادامه خواهد داشت.',
	'POLL_VOTED_OPTION'		=> 'شما به اين گزينه راي داده ايد',
	'POST_DELETED_RESTORE'	=> 'این پست حذف شده است اما قابل بازگردانی میباشد.',
	'PRINT_TOPIC'			=> 'نمایش حالت پرینت',

	'QUICK_MOD'				=> 'ابزار فوري',
	'QUICKREPLY'			=> 'پاسخ سریع',
	'QUOTE'					=> 'نقل قول',

	'REPLY_TO_TOPIC'		=> 'پاسخ به موضوع',
	'RESTORE'				=> 'بازگردانی',
	'RESTORE_TOPIC'			=> 'بازگردانی موضوع',
	'RETURN_POST'			=> '%sبازگشت به پست%s',

	'SUBMIT_VOTE'			=> 'ثبت رای شما',

	'TOPIC_TOOLS'			=> 'ابزار موضوع',
	'TOTAL_VOTES'			=> 'مجموع رای گیری',

	'UNLOCK_TOPIC'			=> 'بازکردن موضوع',

	'VIEW_INFO'				=> 'جزئیات پست',
	'VIEW_NEXT_TOPIC'		=> 'موضوع بعدی',
	'VIEW_PREVIOUS_TOPIC'	=> 'موضوع قبلی',
	'VIEW_RESULTS'			=> 'نمایش نتیجه',
	'VIEW_TOPIC_POSTS'		=> array(
		1	=> 'تعداد پست ها:%d',
		2	=> 'تعداد پست ها:%d',
	),
	'VIEW_UNREAD_POST'		=> 'اولین پست خوانده نشده',
	'VOTE_SUBMITTED'		=> 'رای شما با موفقیت ثبت شد.',
	'VOTE_CONVERTED'		=> 'امکان تغییر رای وجود ندارد.',

));
