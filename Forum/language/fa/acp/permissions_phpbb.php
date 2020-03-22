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
	'ACL_CAT_FORUMS'		=> 'انجمن ها',
	'ACL_CAT_MISC'			=> 'متفرقه',
	'ACL_CAT_PERMISSIONS'	=> 'سطوح دسترسی',
	'ACL_CAT_PM'			=> 'پیغام های خصوصی',
	'ACL_CAT_POLLS'			=> 'نظرسنجی ها',
	'ACL_CAT_POST'			=> 'پست',
	'ACL_CAT_POST_ACTIONS'	=> 'عملیات پست',
	'ACL_CAT_POSTING'		=> 'ارسال پست',
	'ACL_CAT_PROFILE'		=> 'پروفایل',
	'ACL_CAT_SETTINGS'		=> 'تنظیمات',
	'ACL_CAT_TOPIC_ACTIONS'	=> 'عملیات موضوعات',
	'ACL_CAT_USER_GROUP'	=> 'کاربران و گروه ها',
));

// User Permissions
$lang = array_merge($lang, array(
	'ACL_U_VIEWPROFILE'	=> 'توانایی دیدن پروفایل ، لیست اعضا و افراد انلاین.',
	'ACL_U_CHGNAME'		=> 'توانایی تغییر نام کاربری.',
	'ACL_U_CHGPASSWD'	=> 'توانایی تغییر کلمه عبور.',
	'ACL_U_CHGEMAIL'	=> 'توانایی تغییر ایمیل.',
	'ACL_U_CHGAVATAR'	=> 'توانایی تغییر نمایه',
	'ACL_U_CHGGRP'		=> 'توانایی تغییر گروه کاربری پیش فرض',
	'ACL_U_CHGPROFILEINFO'	=> 'توانایی تغییر فیلد سفارشی پروفایل',

	'ACL_U_ATTACH'		=> 'توانایی ارسال پیوست',
	'ACL_U_DOWNLOAD'	=> 'توانایی دریافت پیوست',
	'ACL_U_SAVEDRAFTS'	=> 'توانایی ذخیره پیش نویس',
	'ACL_U_CHGCENSORS'	=> 'توانایی لغو سانسور کلمات',
	'ACL_U_SIG'			=> 'توانایی استفاده از امضا',

	'ACL_U_SENDPM'		=> 'توانایی ارسال پیغام خصوصی',
	'ACL_U_MASSPM'		=> 'توانایی ارسال پیغام خصوصی به چند نفر',
	'ACL_U_MASSPM_GROUP'=> 'توانایی ارسال پیغام خصوصی به گروه ها',
	'ACL_U_READPM'		=> 'توانایی خواندن پیغام خصوصی',
	'ACL_U_PM_EDIT'		=> 'توانایی ویرایش پیغام خصوصی خود',
	'ACL_U_PM_DELETE'	=> 'توانایی حذف پیغام خصوصی از پوشه خود',
	'ACL_U_PM_FORWARD'	=> 'توانایی ارسال پیغام خصوصی به دیگری',
	'ACL_U_PM_EMAILPM'	=> 'توانایی ارسال پیغام خصوصی به عنوان ایمیل',
	'ACL_U_PM_PRINTPM'	=> 'توانایی چاپ پیغام خصوصی',
	'ACL_U_PM_ATTACH'	=> 'توانایی پیوست فایل در پیغام خصوصی',
	'ACL_U_PM_DOWNLOAD'	=> 'توانایی دریافت فایل در پیغام خصوصی',
	'ACL_U_PM_BBCODE'	=> 'توانایی استفاده از BBCode در پیغام خصوصی',
	'ACL_U_PM_SMILIES'	=> 'توانایی استفاده از شکلک در پیغام خصوصی',
	'ACL_U_PM_IMG'		=> 'توانایی استفاده از تصویر [img] در پیغام خصوصی',
	'ACL_U_PM_FLASH'	=> 'توانایی استفاده از فایل فلش [flash] در پیغام خصوصی',

	'ACL_U_SENDEMAIL'	=> 'توانایی ارسال ایمیل',
	'ACL_U_SENDIM'		=> 'توانایی ارسال پیغام فوری',
	'ACL_U_IGNOREFLOOD'	=> 'توانایی عبور از فواصل زمانی',
	'ACL_U_HIDEONLINE'	=> 'توانايي نشان ندادن وضعيت آنلاين',
	'ACL_U_VIEWONLINE'	=> 'توانايي مشاهده کاربران مخفي آنلاين',
	'ACL_U_SEARCH'		=> 'توانايي جستجو در انجمن',
));

// Forum Permissions
$lang = array_merge($lang, array(
	'ACL_F_LIST'		=> 'توانايي مشاهده انجمن',
	'ACL_F_LIST_TOPICS' => 'توانایی مشاهده موضوعات',
	'ACL_F_READ'		=> 'توانايي خواندن انجمن',
	'ACL_F_SEARCH'		=> 'توانایی جستجو در انجمن',
	'ACL_F_SUBSCRIBE'	=> 'توانايي اشتراک در انجمن',
	'ACL_F_PRINT'		=> 'توانايي پرينت مباحث',
	'ACL_F_EMAIL'		=> 'توانايي ايميل مباحث',
	'ACL_F_BUMP'		=> 'توانايي بامپ مباحث',
	'ACL_F_USER_LOCK'	=> 'توانايي بستن مباحث خود',
	'ACL_F_DOWNLOAD'	=> 'توانایی دریافت فایل ها',
	'ACL_F_REPORT'		=> 'توانایی گزارش پست ها',

	'ACL_F_POST'		=> 'توانایی ارسال موضوع جدید',
	'ACL_F_STICKY'		=> 'توانايي ارسال مهم',
	'ACL_F_ANNOUNCE'	=> 'توانايي ارسال اطلاعيه',
	'ACL_F_ANNOUNCE_GLOBAL'	=> 'توانایی ارسال پست سراسری',
	'ACL_F_REPLY'		=> 'توانایی پاسخ به موضوع',
	'ACL_F_EDIT'		=> 'توانایی ویرایش پست خود',
	'ACL_F_DELETE'		=> 'توانایی حذف دائمی پست خود',
	'ACL_F_SOFTDELETE'	=> 'توانایی حذف موقت پست خود<br /><em>مدیرانی که دسترسی تایید پست ها را دارند ، میتوانند پست ها را بازیابی کنند</em>',
	'ACL_F_IGNOREFLOOD' => 'توانایی عبور از فواصل زمانی',
	'ACL_F_POSTCOUNT'	=> 'افزایش شمارنده پست<br /><em>لطفا در نظر داشته باشيد که اين وضعيت فقط براي پست هاي جديد موثر است.</em>',
	'ACL_F_NOAPPROVE'	=> 'توانايي ارسال پست بدون تاييد شدن',

	'ACL_F_ATTACH'		=> 'توانايي پيوست فايل ها',
	'ACL_F_ICONS'		=> 'توانایی استفاده از آیکن موضوعات و پست ها',
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
	'ACL_M_EDIT'		=> 'توانایی ویرایش پست ها',
	'ACL_M_DELETE'		=> 'توانایی حذف دائم پست',
	'ACL_M_SOFTDELETE'	=> 'توانایی حذف موقت پست ها<br /><em>مدیرانی که دسترسی تایید پست ها را دارند ، میتوانند پست ها را بازیابی کنند.</em>',
	'ACL_M_APPROVE'		=> 'توانایی تایید و بازیابی پست',
	'ACL_M_REPORT'		=> 'توانایی بستن و حذف پست ها',
	'ACL_M_CHGPOSTER'	=> 'توانایی تغییر نام نویسنده',

	'ACL_M_MOVE'	=> 'توانایی انتقال موضوعات',
	'ACL_M_LOCK'	=> 'توانایی بستن موضوعات',
	'ACL_M_SPLIT'	=> 'توانایی تقسیم موضوعات',
	'ACL_M_MERGE'	=> 'توانایی ادغام موضوعات',

	'ACL_M_INFO'	=> 'توانایی مشاهده جزئیات پست',
	'ACL_M_WARN'	=> 'توانايي صادر کردن اخطار ها<br /><em>این تنظیم سراسری می باشد و وابسته به انجمن نیست</em>', // This moderator setting is only global (and not local)
	'ACL_M_PM_REPORT'	=> 'میتواند گزارش های پیام های خصوصی را حذف  یا ببندد<br /><em>این تنطیمات در بخش سراسری خواهد بود.</em>', // This moderator setting is only global (and not local)
	'ACL_M_BAN'		=> 'توانايي اداره کردن تحريم ها<br /><em>این تنظیم سراسری می باشد و وابسته به انجمن نیست</em>', // This moderator setting is only global (and not local)
));

// Admin Permissions
$lang = array_merge($lang, array(
	'ACL_A_BOARD'		=> 'توانایی تغییر تنظیمات انجمن/جستجو و بررسی به روزرسانی ها',
	'ACL_A_SERVER'		=> 'توانايي تغيير تنظيمات سرور/ارتباطات',
	'ACL_A_JABBER'		=> 'توانايي تغيير  تنظيمات Jabber',
	'ACL_A_PHPINFO'		=> 'توانايي مشاهده تنظيمات php',

	'ACL_A_FORUM'		=> 'توانايي اداره انجمن ها',
	'ACL_A_FORUMADD'	=> 'توانايي افزودن انجمن هاي جديد',
	'ACL_A_FORUMDEL'	=> 'توانايي حذف انجمن ها',
	'ACL_A_PRUNE'		=> 'توانايي هرس انجمن ها',

	'ACL_A_ICONS'		=> 'توانايي تغيير نماد پست ها / مبحث ها و شکلکها',
	'ACL_A_WORDS'		=> 'توانايي تغيير سانسور کلمات',
	'ACL_A_BBCODE'		=> 'توانايي تعيين تگ هاي BBCode',
	'ACL_A_ATTACH'		=> 'توانايي تغيير تنظيمات وابسته پيوست',

	'ACL_A_USER'		=> 'توانايي اداره کاربران<br /><em>همچنین دیدن نوع مرورگر وب کاربر در لیست کاربران آنلاین</em>',
	'ACL_A_USERDEL'		=> 'توانايي حذف / هرس کاربران',
	'ACL_A_GROUP'		=> 'توانايي اداره گروه ها',
	'ACL_A_GROUPADD'	=> 'توانايي افزودن گروه هاي جديد',
	'ACL_A_GROUPDEL'	=> 'توانايي حذف گروه ها',
	'ACL_A_RANKS'		=> 'توانايي اداره رتبه ها',
	'ACL_A_PROFILE'		=> 'توانايي اداره فيلد هاي سفارشي مشخصات',
	'ACL_A_NAMES'		=> 'توانايي اداره نام هاي غيرفعال',
	'ACL_A_BAN'			=> 'توانايي اداره تحريم ها',

	'ACL_A_VIEWAUTH'	=> 'توانايي مشاهده نهان هاي سطح دسترسي',
	'ACL_A_AUTHGROUPS'	=> 'توانايي تغيير سطوح دسترسي متعلق به گروه ها',
	'ACL_A_AUTHUSERS'	=> 'توانايي تغيير سطوح دسترسي متعلق به کاربران',
	'ACL_A_FAUTH'		=> 'توانايي تغيير نوع سطح دسترسي انجمن',
	'ACL_A_MAUTH'		=> 'توانايي تغيير نوع سطح دسترسي مدير انجمن',
	'ACL_A_AAUTH'		=> 'توانايي نوع سطح دسترسي مدير کل',
	'ACL_A_UAUTH'		=> 'توانايي تغيير سطح دسترسي کاربر',
	'ACL_A_ROLES'		=> 'توانايي اداره نقش ها',
	'ACL_A_SWITCHPERM'	=> 'توانايي بکارگيري سطوح دسترسي ديگر',

	'ACL_A_STYLES'		=> 'توانایی مدیریت قالب ها',
	'ACL_A_EXTENSIONS'	=> 'توانایی مدیریت افزونه ها',
	'ACL_A_VIEWLOGS'	=> 'توانایی مشاهده رویدادها',
	'ACL_A_CLEARLOGS'	=> 'توانایی پاکسازی رویداد ها',
	'ACL_A_MODULES'		=> 'توانایی مدیریت ماژول ها',
	'ACL_A_LANGUAGE'	=> 'توانایی مدیریت بسته های زبانی',
	'ACL_A_EMAIL'		=> 'توانایی ارسال ایمیل گروهی',
	'ACL_A_BOTS'		=> 'توانایی مدیریت روبات های جستجو',
	'ACL_A_REASONS'		=> 'توانايي اداره دلايل عدم پذيرش ها و گزارش ها',
	'ACL_A_BACKUP'		=> 'توانايي ايجاد و بازگرداني پشتيبان',
	'ACL_A_SEARCH'		=> 'توانايي اداره مرجع جستجو و تنظيمات',
));
