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
	'ABOUT_USER'			=> 'نمایه',
	'ACTIVE_IN_FORUM'		=> 'فعال ترین انجمن',
	'ACTIVE_IN_TOPIC'		=> 'فعال ترین موضوع',
	'ADD_FOE'				=> 'اضافه کردن به عنوان دشمن',
	'ADD_FRIEND'			=> 'اضافه کردن به عنوان دوست',
	'AFTER'					=> 'بعد',

	'ALL'					=> 'همه',

	'BEFORE'				=> 'قبل',

	'CC_SENDER'			=> 'ارسال رونوشتی از این ایمیل برای من.',
	'CONTACT_ADMIN'			=> 'تماس با مدیر کل انجمن',

	'DEST_LANG'				=> 'زبان',
	'DEST_LANG_EXPLAIN'		=> 'زبان مناسبی را (اگر در دسترس بود) برای گیرنده این پیام تعریف کنید.',
	'EDIT_PROFILE'			=> 'ویرایش نمایه',

	'EMAIL_BODY_EXPLAIN'	=> 'این پیام به صورت متن ساده ارسال خواهد شد. در آن از HTML و یا BBCode استفاده نکنید. آدرس بازگشت این پیام، ایمیل شما تنظیم میشود.',
	'EMAIL_DISABLED'		=> 'متاسفیم، تمامی عملکرد های مربوط به ایمیل غیرفعال شده اند.',
	'EMAIL_SENT'			=> 'ایمیل ارسال شد.',
	'EMAIL_TOPIC_EXPLAIN'	=> 'این پیام به صورت متن ساده ارسال خواهد شد. در آن از HTML و یا BBCode استفاده نکنید. توجه داشته باشید که اطلاعات موضوع در پیام ذکر شده است. آدرس بازگشت این پیام، ایمیل شما تنظیم میشود.',
	'EMPTY_ADDRESS_EMAIL'	=> 'باید ایمیل معتبری را برای گیرنده وارد کنید.',
	'EMPTY_MESSAGE_EMAIL'	=> 'باید پیامی را برای ایمیل کردن وارد کنید.',
	'EMPTY_MESSAGE_IM'		=> 'باید پیامی را برای ارسال وارد کنید.',
	'EMPTY_NAME_EMAIL'		=> 'باید نام واقعی گیرنده را وارد کنید.',
	'EMPTY_SENDER_EMAIL'	=> 'باید آدرس ایمیل معتبری وارد کنید.',
	'EMPTY_SENDER_NAME'		=> 'باید نامی انتخاب کنید.',
	'EMPTY_SUBJECT_EMAIL'	=> 'باید عنوانی را برای ایمیل وارد کنید.',
	'EQUAL_TO'				=> 'برابر با',

	'FIND_USERNAME_EXPLAIN'	=> 'برای جست‌وجوی کاربر مشخصی از این فرم استفاده کنید. لازم نیست که همه ی قسمت ها را پر کنید. برای اطلاعاتی که بخشی از آن جزو نتیجه است، از * استفاده کنید. تاریخ ها را با استفاده از فرمت <kbd>YYYY-MM-DD</kbd> وارد کنید، برای مثال <samp>2004-02-29</samp>. برای انتخاب چند نام کاربری، بر روی چک باکس علامت بگذارید (بر اساس فرم ممکن است چند نام کاربری مورد قبول باشد) و بر علامت گذاری بر روی دکمه انتخاب شده کلیک کنید.',
	'FLOOD_EMAIL_LIMIT'		=> 'فعلا نمی‌توانید ایمیل دیگری بفرستید، لطفا بعدا تلاش کنید.',

	'GROUP_LEADER'			=> 'رهبر گروه',

	'HIDE_MEMBER_SEARCH'	=> 'جست‌وجوی کاربران مخفی',

	'IM_ADD_CONTACT'		=> 'اضافه کردن لیست تماس',
	'IM_DOWNLOAD_APP'		=> 'دانلود برنامه',
	'IM_JABBER'				=> 'لطفا توجه داشته باشید که ممکن است کاربران عدم دریافت پیام‌های سریع ناخواسته را انتخاب کرده باشند.',
	'IM_JABBER_SUBJECT'		=> 'این یک پیام خودکار هست، لطفا جواب ندهید! ارسال شده توسط %1$s در %2$s.',
	'IM_MESSAGE'			=> 'پیام شما',
	'IM_NAME'				=> 'نام شما',
	'IM_NO_DATA'			=> 'اطلاعات تماس مناسبی در مورد این کاربر وجود ندارد.',
	'IM_NO_JABBER'			=> 'متاسفیم، کاربران به طور مستقیم نمیتوانند با یکدیگر از طریق Jabber در این تالار حرف بزنند. برای این کار باید کارخواه Jabber بر روی سیستم شما نصب شده باشد.',
	'IM_RECIPIENT'			=> 'گیرنده',
	'IM_SEND'				=> 'ارسال پیام',
	'IM_SEND_MESSAGE'		=> 'ارسال پیام',
	'IM_SENT_JABBER'		=> 'پیام شما به %1$s با موفقیت ارسال شد.',
	'IM_USER'				=> 'ارسال پیام سریع',

	'LAST_ACTIVE'				=> 'آخرین فعالیت',
	'LESS_THAN'					=> 'کمتر از',
	'LIST_USERS'				=> array(
		1	=> '%d کاربر',
		2	=> '%d کاربر',
	),
	'LOGIN_EXPLAIN_TEAM'		=> 'برای مشاهده تیم مدیریت باید ثبت نام کرده و وارد شوید.',
	'LOGIN_EXPLAIN_MEMBERLIST'	=> 'برای مشاهده اعضای تالار باید ثبت نام کرده و وارد شوید.',
	'LOGIN_EXPLAIN_SEARCHUSER'	=> 'برای جست‌وجوی کاربران باید ثبت نام کرده و وارد شوید.',
	'LOGIN_EXPLAIN_VIEWPROFILE'	=> 'برای مشاهده نمایه‌ها باید ثبت نام کرده و وارد شوید.',

	'MANAGE_GROUP'			=> 'مدیریت گروه',
	'MORE_THAN'				=> 'بیشتر از',

	'NO_CONTACT_FORM'		=> 'فرم تماس با مدیریت در حال حاضر غیرفعال است.',
	'NO_CONTACT_PAGE'		=> 'صفحه مربوط به تماس با مدیریت در حال حاضر غیرفعال است.',
	'NO_EMAIL'				=> 'شما اجازه ارسال ایمیل به این کاربر را ندارید.',
	'NO_VIEW_USERS'			=> 'شما اجازه دیدن نمایه این کاربر را ندارید.',

	'ORDER'					=> 'سفارش',
	'OTHER'					=> 'دیگر',

	'POST_IP'				=> 'ارسال شده از آی‌پی/دامنه',

	'REAL_NAME'				=> 'نام گیرنده',
	'RECIPIENT'				=> 'گیرنده',
	'REMOVE_FOE'			=> 'حذف دشمن',
	'REMOVE_FRIEND'			=> 'حذف دوست',

	'SELECT_MARKED'			=> 'انتخاب علامت گذاری شده ها',
	'SELECT_SORT_METHOD'	=> 'انتخاب روش مرتب سازی',
	'SENDER_EMAIL_ADDRESS'	=> 'آدرس ایمیل شما',
	'SENDER_NAME'			=> 'نام شما',
	'SEND_ICQ_MESSAGE'		=> 'ارسال پیام ICQ',
	'SEND_IM'				=> 'پیام سریع',
	'SEND_JABBER_MESSAGE'	=> 'ارسال پیام Jabber',
	'SEND_MESSAGE'			=> 'پیام',
	'SEND_YIM_MESSAGE'		=> 'ارسال پیام YIM',
	'SORT_EMAIL'			=> 'ایمیل',
	'SORT_LAST_ACTIVE'		=> 'آخرین فعالیت',
	'SORT_POST_COUNT'		=> 'تعداد پست‌ها',

	'USERNAME_BEGINS_WITH'	=> 'نام کاربری شروع شده با',
	'USER_ADMIN'			=> 'مدیریت کاربر',
	'USER_BAN'				=> 'تحریم',
	'USER_FORUM'			=> 'آمار کاربر',
	'USER_LAST_REMINDED'	=> array(
		0		=> 'هیچ یادآور فرستاده شده ای وجود ندارد.',
		1		=> '%1$d یادآور فرستاده شد<br />» %2$s',
		2		=> '%1$d یادآور فرستاده شد<br />» %2$s',
	),
	'USER_ONLINE'			=> 'آنلاین',
	'USER_PRESENCE'			=> 'حضور در تالار',
	'USERS_PER_PAGE'		=> 'کاربر در صفحه',

	'VIEWING_PROFILE'		=> 'مشاهده نمایه - %s',
	'VIEW_FACEBOOK_PROFILE'	=> 'مشاهده نمایه فیسبوک',
	'VIEW_SKYPE_PROFILE'	=> 'مشاهده نمایه اسکایپ',
	'VIEW_TWITTER_PROFILE'	=> 'مشاهده نمایه توییتر',
	'VIEW_YOUTUBE_CHANNEL'  => 'مشاهده کانال یوتیوب',
));
