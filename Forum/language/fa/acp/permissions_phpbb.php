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

/**
*	EXTENSION-DEVELOPERS PLEASE NOTE
*
*	You are able to put your permission sets into your extension.
*	The permissions logic should be added via the 'core.permissions' event.
*	You can easily add new permission categories, types and permissions, by
*	simply merging them into the respective arrays.
*	The respective language strings should be added into a language file, that
*	start with 'permissions_', so they are automatically loaded within the ACP.
*/

$lang = array_merge($lang, array(
	'ACL_CAT_ACTIONS'		=> 'عملیات',
	'ACL_CAT_CONTENT'		=> 'محتوا',
	'ACL_CAT_FORUMS'		=> 'انجمن‌ها',
	'ACL_CAT_MISC'			=> 'متفرقه',
	'ACL_CAT_PERMISSIONS'	=> 'سطوح دسترسی',
	'ACL_CAT_PM'			=> 'پیام‌های خصوصی',
	'ACL_CAT_POLLS'			=> 'نظرسنجی ها',
	'ACL_CAT_POST'			=> 'پست',
	'ACL_CAT_POST_ACTIONS'	=> 'عملیات پست',
	'ACL_CAT_POSTING'		=> 'ارسال پست',
	'ACL_CAT_PROFILE'		=> 'نمایه',
	'ACL_CAT_SETTINGS'		=> 'تنظیمات',
	'ACL_CAT_TOPIC_ACTIONS'	=> 'عملیات موضوعات',
	'ACL_CAT_USER_GROUP'	=> 'کاربران و گروه‌ها',
));

// User Permissions
$lang = array_merge($lang, array(
	'ACL_U_VIEWPROFILE'	=> 'توانایی دیدن نمایه ، لیست اعضا و افراد انلاین.',
	'ACL_U_CHGNAME'		=> 'توانایی تغییر نام کاربری.',
	'ACL_U_CHGPASSWD'	=> 'توانایی تغییر گذرواژه.',
	'ACL_U_CHGEMAIL'	=> 'توانایی تغییر ایمیل.',
	'ACL_U_CHGAVATAR'	=> 'توانایی تغییر نمایه',
	'ACL_U_CHGGRP'		=> 'توانایی تغییر گروه کاربری پیش‌فرض',
	'ACL_U_CHGPROFILEINFO'	=> 'توانایی تغییر فیلد سفارشی نمایه',

	'ACL_U_ATTACH'		=> 'توانایی ارسال پیوست',
	'ACL_U_DOWNLOAD'	=> 'توانایی دریافت پیوست',
	'ACL_U_SAVEDRAFTS'	=> 'توانایی ذخیره پیش‌نویس',
	'ACL_U_CHGCENSORS'	=> 'توانایی لغو سانسور کلمات',
	'ACL_U_SIG'			=> 'توانایی استفاده از امضا',
	'ACL_U_EMOJI'		=> 'توانایی استفاده از ایموجی و نویسه‌های غنی در عنوان موضوعات',


	'ACL_U_SENDPM'		=> 'توانایی ارسال پیام خصوصی',
	'ACL_U_MASSPM'		=> 'توانایی ارسال پیام خصوصی به چند نفر',
	'ACL_U_MASSPM_GROUP'=> 'توانایی ارسال پیام خصوصی به گروه‌ها',
	'ACL_U_READPM'		=> 'توانایی خواندن پیام خصوصی',
	'ACL_U_PM_EDIT'		=> 'توانایی ویرایش پیام خصوصی خود',
	'ACL_U_PM_DELETE'	=> 'توانایی حذف پیام خصوصی از پوشه خود',
	'ACL_U_PM_FORWARD'	=> 'توانایی ارسال پیام خصوصی به دیگری',
	'ACL_U_PM_EMAILPM'	=> 'توانایی ارسال پیام خصوصی به عنوان ایمیل',
	'ACL_U_PM_PRINTPM'	=> 'توانایی چاپ پیام خصوصی',
	'ACL_U_PM_ATTACH'	=> 'توانایی پیوست فایل در پیام خصوصی',
	'ACL_U_PM_DOWNLOAD'	=> 'توانایی دریافت فایل در پیام خصوصی',
	'ACL_U_PM_BBCODE'	=> 'توانایی استفاده از BBCode در پیام خصوصی',
	'ACL_U_PM_SMILIES'	=> 'توانایی استفاده از شکلک در پیام خصوصی',
	'ACL_U_PM_IMG'		=> 'توانایی استفاده از تصویر [img] در پیام خصوصی',
	'ACL_U_PM_FLASH'	=> 'توانایی استفاده از فایل فلش [flash] در پیام خصوصی',

	'ACL_U_SENDEMAIL'	=> 'توانایی ارسال ایمیل',
	'ACL_U_SENDIM'		=> 'توانایی ارسال پیام فوری',
	'ACL_U_IGNOREFLOOD'	=> 'توانایی عبور از فواصل زمانی',
	'ACL_U_HIDEONLINE'	=> 'توانایی نشان ندادن وضعیت آنلاین',
	'ACL_U_VIEWONLINE'	=> 'توانایی مشاهده کاربران مخفی آنلاین',
	'ACL_U_SEARCH'		=> 'توانایی جست‌وجو در انجمن',
));

// Forum Permissions
$lang = array_merge($lang, array(
	'ACL_F_LIST'		=> 'توانایی مشاهده انجمن',
	'ACL_F_LIST_TOPICS' => 'توانایی مشاهده موضوعات',
	'ACL_F_READ'		=> 'توانایی خواندن انجمن',
	'ACL_F_SEARCH'		=> 'توانایی جست‌وجو در انجمن',
	'ACL_F_SUBSCRIBE'	=> 'توانایی اشتراک در انجمن',
	'ACL_F_PRINT'		=> 'توانایی پرینت موضوعات',
	'ACL_F_EMAIL'		=> 'توانایی ایمیل موضوعات',
	'ACL_F_BUMP'		=> 'توانایی بامپ موضوعات',
	'ACL_F_USER_LOCK'	=> 'توانایی بستن موضوعات خود',
	'ACL_F_DOWNLOAD'	=> 'توانایی دریافت فایل‌ها',
	'ACL_F_REPORT'		=> 'توانایی گزارش پست‌ها',

	'ACL_F_POST'		=> 'توانایی ارسال موضوع جدید',
	'ACL_F_STICKY'		=> 'توانایی ارسال مهم',
	'ACL_F_ANNOUNCE'	=> 'توانایی ارسال اطلاعیه',
	'ACL_F_ANNOUNCE_GLOBAL'	=> 'توانایی ارسال پست سراسری',
	'ACL_F_REPLY'		=> 'توانایی پاسخ به موضوع',
	'ACL_F_EDIT'		=> 'توانایی ویرایش پست خود',
	'ACL_F_DELETE'		=> 'توانایی حذف دائمی پست خود',
	'ACL_F_SOFTDELETE'	=> 'توانایی حذف موقت پست خود<br /><em>مدیرانی که دسترسی تأیید پست‌ها را دارند ، میتوانند پست‌ها را بازیابی کنند</em>',
	'ACL_F_IGNOREFLOOD' => 'توانایی عبور از فواصل زمانی',
	'ACL_F_POSTCOUNT'	=> 'افزایش شمارنده پست<br /><em>لطفا در نظر داشته باشید که این وضعیت فقط برای پست‌های جدید موثر است.</em>',
	'ACL_F_NOAPPROVE'	=> 'توانایی ارسال پست بدون پذیرش',

	'ACL_F_ATTACH'		=> 'توانایی پیوست فایل‌ها',
	'ACL_F_ICONS'		=> 'توانایی استفاده از آیکن موضوعات و پست‌ها',
	'ACL_F_BBCODE'		=> 'توانایی استفاده از BBCode',
	'ACL_F_FLASH'		=> 'توانایی استفاده از فایل فلش [flash]',
	'ACL_F_IMG'			=> 'توانایی استفاده از تصویر [img]',
	'ACL_F_SIGS'		=> 'توانایی استفاده از امضا',
	'ACL_F_SMILIES'		=> 'توانایی استفاده از شکلک',

	'ACL_F_POLL'		=> 'توانایی ایجاد نظر سنجی',
	'ACL_F_VOTE'		=> 'توانایی رای دادن در نظر سنجی',
	'ACL_F_VOTECHG'		=> 'توانایی تغییر آرا در نظر سنجی',
));

// Moderator Permissions
$lang = array_merge($lang, array(
	'ACL_M_EDIT'		=> 'توانایی ویرایش پست‌ها',
	'ACL_M_DELETE'		=> 'توانایی حذف دائم پست',
	'ACL_M_SOFTDELETE'	=> 'توانایی حذف موقت پست‌ها<br /><em>مدیرانی که دسترسی تأیید پست‌ها را دارند ، میتوانند پست‌ها را بازیابی کنند.</em>',
	'ACL_M_APPROVE'		=> 'توانایی تأیید و بازیابی پست',
	'ACL_M_REPORT'		=> 'توانایی بستن و حذف پست‌ها',
	'ACL_M_CHGPOSTER'	=> 'توانایی تغییر نام نویسنده',

	'ACL_M_MOVE'	=> 'توانایی انتقال موضوعات',
	'ACL_M_LOCK'	=> 'توانایی بستن موضوعات',
	'ACL_M_SPLIT'	=> 'توانایی تقسیم موضوعات',
	'ACL_M_MERGE'	=> 'توانایی ادغام موضوعات',

	'ACL_M_INFO'	=> 'توانایی مشاهده جزئیات پست',
	'ACL_M_WARN'	=> 'توانایی صادر کردن اخطار ها<br /><em>این تنظیم سراسری می باشد و وابسته به انجمن نیست</em>', // This moderator setting is only global (and not local)
	'ACL_M_PM_REPORT'	=> 'میتواند گزارش های پیام‌های خصوصی را حذف  یا ببندد<br /><em>این تنطیمات در بخش سراسری خواهد بود.</em>', // This moderator setting is only global (and not local)
	'ACL_M_BAN'		=> 'توانایی اداره کردن تحریم ها<br /><em>این تنظیم سراسری می باشد و وابسته به انجمن نیست</em>', // This moderator setting is only global (and not local)
));

// Admin Permissions
$lang = array_merge($lang, array(
	'ACL_A_BOARD'		=> 'توانایی تغییر تنظیمات انجمن/جست‌وجو و بررسی به روزرسانی ها',
	'ACL_A_SERVER'		=> 'توانایی تغییر تنظیمات سرور/ارتباطات',
	'ACL_A_JABBER'		=> 'توانایی تغییر  تنظیمات Jabber',
	'ACL_A_PHPINFO'		=> 'توانایی مشاهده تنظیمات php',

	'ACL_A_FORUM'		=> 'توانایی اداره انجمن‌ها',
	'ACL_A_FORUMADD'	=> 'توانایی افزودن انجمن‌های جدید',
	'ACL_A_FORUMDEL'	=> 'توانایی حذف انجمن‌ها',
	'ACL_A_PRUNE'		=> 'توانایی هرس انجمن‌ها',

	'ACL_A_ICONS'		=> 'توانایی تغییر نماد پست‌ها / موضوعات و شکلک‌ها',
	'ACL_A_WORDS'		=> 'توانایی تغییر سانسور کلمات',
	'ACL_A_BBCODE'		=> 'توانایی تعیین تگ های BBCode',
	'ACL_A_ATTACH'		=> 'توانایی تغییر تنظیمات وابسته پیوست',

	'ACL_A_USER'		=> 'توانایی اداره کاربران<br /><em>همچنین دیدن نوع مرورگر وب کاربر در لیست کاربران آنلاین</em>',
	'ACL_A_USERDEL'		=> 'توانایی حذف / هرس کاربران',
	'ACL_A_GROUP'		=> 'توانایی اداره گروه‌ها',
	'ACL_A_GROUPADD'	=> 'توانایی افزودن گروه‌های جدید',
	'ACL_A_GROUPDEL'	=> 'توانایی حذف گروه‌ها',
	'ACL_A_RANKS'		=> 'توانایی اداره رتبه‌ها',
	'ACL_A_PROFILE'		=> 'توانایی اداره فیلد های سفارشی مشخصات',
	'ACL_A_NAMES'		=> 'توانایی اداره نام های غیرفعال',
	'ACL_A_BAN'			=> 'توانایی اداره تحریم ها',

	'ACL_A_VIEWAUTH'	=> 'توانایی مشاهده نهان های سطح دسترسی',
	'ACL_A_AUTHGROUPS'	=> 'توانایی تغییر سطوح دسترسی متعلق به گروه‌ها',
	'ACL_A_AUTHUSERS'	=> 'توانایی تغییر سطوح دسترسی متعلق به کاربران',
	'ACL_A_FAUTH'		=> 'توانایی تغییر نوع سطح دسترسی انجمن',
	'ACL_A_MAUTH'		=> 'توانایی تغییر نوع سطح دسترسی مدیر انجمن',
	'ACL_A_AAUTH'		=> 'توانایی نوع سطح دسترسی مدیر کل',
	'ACL_A_UAUTH'		=> 'توانایی تغییر سطح دسترسی کاربر',
	'ACL_A_ROLES'		=> 'توانایی اداره نقش ها',
	'ACL_A_SWITCHPERM'	=> 'توانایی بکارگیری سطوح دسترسی دیگر',

	'ACL_A_STYLES'		=> 'توانایی مدیریت قالب‌ها',
	'ACL_A_EXTENSIONS'	=> 'توانایی مدیریت افزونه‌ها',
	'ACL_A_VIEWLOGS'	=> 'توانایی مشاهده رویدادها',
	'ACL_A_CLEARLOGS'	=> 'توانایی پاکسازی رویداد ها',
	'ACL_A_MODULES'		=> 'توانایی مدیریت ماژول ها',
	'ACL_A_LANGUAGE'	=> 'توانایی مدیریت بسته های زبانی',
	'ACL_A_EMAIL'		=> 'توانایی ارسال ایمیل گروهی',
	'ACL_A_BOTS'		=> 'توانایی مدیریت ربات های جست‌وجو',
	'ACL_A_REASONS'		=> 'توانایی اداره دلایل عدم پذیرش ها و گزارش ها',
	'ACL_A_BACKUP'		=> 'توانایی ایجاد و بازگردانی پشتیبان',
	'ACL_A_SEARCH'		=> 'توانایی اداره مرجع جست‌وجو و تنظیمات',
));
