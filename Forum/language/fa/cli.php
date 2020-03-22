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

if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'CLI_CONFIG_CANNOT_CACHED'			=> 'تنظیم این گزینه اگر تنیظیمات پیکربندی تغییر کند که اغلب باعث تغییرات موثری در کش میشود.',
	'CLI_CONFIG_CURRENT'				=> 'مقادیر پیکربندی حاضر, استفاده از 0 و 1 برای مقادیر عددی',
	'CLI_CONFIG_DELETE_SUCCESS'			=> 'تنظیمات %s با موفقیت حذف شد.',
	'CLI_CONFIG_NEW'					=> 'مقادیر پیکربندی جدید, استفاده از 0 و 1 برای مقادیر عددی',
	'CLI_CONFIG_NOT_EXISTS'				=> 'تنظیمات %s موجودو نیست.',
	'CLI_CONFIG_OPTION_NAME'			=> 'نام تنظیمات پیکربندی',
	'CLI_CONFIG_PRINT_WITHOUT_NEWLINE'	=> 'تنظیم این گزینه اگر مقدار وارد شده قرار نباشد در خط جداگانه نمایش داده شود.',
	'CLI_CONFIG_INCREMENT_BY'			=> 'اضافه شدن مقادیر توسط:',
	'CLI_CONFIG_INCREMENT_SUCCESS'		=> 'افزایش تنظیمات %s با موفقیت انجام شد.',
	'CLI_CONFIG_SET_FAILURE'			=> 'اماکن تنظیم %s وجود ندارد.',
	'CLI_CONFIG_SET_SUCCESS'			=> 'تنظیمات %s با موفقیت انجام شد.',

	'CLI_DESCRIPTION_CRON_LIST'					=> 'نمایش لیست وظایف زمانی آماده و غیر آماده',
	'CLI_DESCRIPTION_CRON_RUN'					=> 'اجرای تمامی وظایف زمانی',
	'CLI_DESCRIPTION_CRON_RUN_ARGUMENT_1'		=> 'نام وظیفه قابل اجرا',
	'CLI_DESCRIPTION_DB_LIST'					=> 'لیست تمام نصب شده ها و انتقالی ها',
	'CLI_DESCRIPTION_DB_MIGRATE'				=> 'به روز رسانی دیتابیس توسط تثبیت انتقال Migration',
	'CLI_DESCRIPTION_DB_REVERT'					=> 'انتقال مجدد',
	'CLI_DESCRIPTION_DELETE_CONFIG'				=> 'حذف گزینه پیکربندی',
	'CLI_DESCRIPTION_DISABLE_EXTENSION'			=> 'غیر فعال کردن افزونه مورد مشخص.',
	'CLI_DESCRIPTION_ENABLE_EXTENSION'			=> 'فعال کردن افزونه مشخص.',
	'CLI_DESCRIPTION_FIND_MIGRATIONS'			=> 'پیدا کردن انتقال بدون وابسطه.',
	'CLI_DESCRIPTION_FIX_LEFT_RIGHT_IDS'		=> 'تعمیر ساختار درختی انجمن ها و مدل ها ',
	'CLI_DESCRIPTION_GET_CONFIG'				=> 'گرفتن مقدار برای گزینه پیکیربندی',
	'CLI_DESCRIPTION_INCREMENT_CONFIG'			=> 'افزایش مقدار صحیح گزینه های پیکری بندی',
	'CLI_DESCRIPTION_LIST_EXTENSIONS'			=> 'لیست تمامی افزونه های درون دیتابیس و فایل های سیستمی',
	
	'CLI_DESCRIPTION_OPTION_ENV'				=> 'نام محیط',
	'CLI_DESCRIPTION_OPTION_SAFE_MODE'			=> 'اجرا در حالت امن (بدون افزونه ها).',
	'CLI_DESCRIPTION_OPTION_SHELL'				=> 'اجرا shell',
	
	'CLI_DESCRIPTION_PURGE_EXTENSION'			=> 'پاکسازی افزونه مشخص.',

	'CLI_DESCRIPTION_REPARSER_LIST'				=> 'لیست انوع متنی که میتوانید استفاده کنید',
	'CLI_DESCRIPTION_REPARSER_AVAILABLE'				=> 'جایگزینی متن موجود:',
	'CLI_DESCRIPTION_REPARSER_REPARSE'			=> 'متن ذخیره شده فعلی را با سرویس text_formatter جایگزین  میکند',
	'CLI_DESCRIPTION_REPARSER_REPARSE_ARG_1'	=> 'نوع متن برای جایگزینی. برای جایگزینی با هرچیزی این بخش را خالی بگذارید.',
	'CLI_DESCRIPTION_REPARSER_REPARSE_OPT_DRY_RUN'		=> 'چیزی را ذخیره نکنید ، تنها چاپ اتفاق می افتد',
	'CLI_DESCRIPTION_REPARSER_REPARSE_OPT_RANGE_MIN'	=> 'کمترین رکورد ID برای پردازش',
	'CLI_DESCRIPTION_REPARSER_REPARSE_OPT_RANGE_MAX'	=> 'بیشترین رکورد ID برای پردازش',
	'CLI_DESCRIPTION_REPARSER_REPARSE_OPT_RANGE_SIZE'	=> 'تعداد تقریبی رکورد برای هر پردازش در زمان',
	'CLI_DESCRIPTION_REPARSER_REPARSE_OPT_RESUME'		=> 'شروع بازسازی از جایی که آخرین توقف انجام شده ا ست',

	'CLI_DESCRIPTION_RECALCULATE_EMAIL_HASH'			=> 'محاسبه مجدد ستون user_email_hash از جدول کاربران',

	'CLI_DESCRIPTION_SET_ATOMIC_CONFIG'					=> 'مقداردهی تنظیمات پیکربندی تنها هنگامیکه مقادیر فعلی قدیمی باشند',
	'CLI_DESCRIPTION_SET_CONFIG'						=> 'مقداردهی تنظیمات پیکربندی',

	'CLI_DESCRIPTION_THUMBNAIL_DELETE'					=> 'حذف همه ریز عکس های فعلی',
	'CLI_DESCRIPTION_THUMBNAIL_GENERATE'				=> 'تولید همه ریز عکسها',
	'CLI_DESCRIPTION_THUMBNAIL_RECREATE'				=> 'بازسازی همه ریزعکس ها',

	'CLI_DESCRIPTION_UPDATE_CHECK'					=> 'بررسی به روز بودن انجمن',
	'CLI_DESCRIPTION_UPDATE_CHECK_ARGUMENT_1'		=> 'نام افزونه ها برای بررسی ( اگر همه انتخاب شود، همه بررسی میشوند',
	'CLI_DESCRIPTION_UPDATE_CHECK_OPTION_CACHE'		=> 'اجرای دستور بررسیبا سیستم کش',
	'CLI_DESCRIPTION_UPDATE_CHECK_OPTION_STABILITY'	=> 'اجرای دستور انتخاب برای بررسی نسخه های پایدار یا ناپایدار',

	'CLI_DESCRIPTION_UPDATE_HASH_BCRYPT'		=> 'به روز رسانی هش پسوردهای انقضا شده به هش های با bcrypt.',
	
	'CLI_ERROR_INVALID_STABILITY' => '"%s" نیاز به تنظیم به پایدار یا ناپایدار است.',
	
	'CLI_DESCRIPTION_USER_ACTIVATE'				=> 'فعال یا غیر فعال بودن یک کاربر',
	'CLI_DESCRIPTION_USER_ACTIVATE_USERNAME'	=> 'نام کاربری یک اکانت برای فعال سازی',
	'CLI_DESCRIPTION_USER_ACTIVATE_DEACTIVATE'	=> 'غیر فعال سازی اکانت کاربر',
	'CLI_DESCRIPTION_USER_ACTIVATE_ACTIVE'		=> 'کاربر در حال حاضر فعال است',
	'CLI_DESCRIPTION_USER_ACTIVATE_INACTIVE'	=> 'کاربر درحال حاضر غیرفعال است',
	'CLI_DESCRIPTION_USER_ADD'					=> 'اضافه کردن کاربر جدید',
	'CLI_DESCRIPTION_USER_ADD_OPTION_USERNAME'	=> 'نام کاربری کاربر جدید',
	'CLI_DESCRIPTION_USER_ADD_OPTION_PASSWORD'	=> 'پسورد کاربر جدید',
	'CLI_DESCRIPTION_USER_ADD_OPTION_EMAIL'		=> 'آدرس ایمیل کاربر جدید',
	'CLI_DESCRIPTION_USER_ADD_OPTION_NOTIFY'	=> 'ارسال ایمیل فعال سازی کاربر جدید ( به صورت پیش فرض ارسال نمی شود.)',
	'CLI_DESCRIPTION_USER_DELETE'				=> 'حذف اکانت کاربر',
	'CLI_DESCRIPTION_USER_DELETE_USERNAME'		=> 'حذف نام کاربری کاربر',
	'CLI_DESCRIPTION_USER_DELETE_OPTION_POSTS'	=> 'حذف تمامی پست های کاربر. بدون انجام این گزینه پست های کاربر همچنان باقی خواهد ماند.',
	'CLI_DESCRIPTION_USER_RECLEAN'				=> 'پاکسازی مجدد نام های کاربری',
 
	'CLI_EXTENSION_DISABLE_FAILURE'		=> 'امکان غیر فعال سازی افزونه %s وجود ندارد.',
	'CLI_EXTENSION_DISABLE_SUCCESS'		=> 'افزونه %s با موفقیت غیر فعال شد.',
	'CLI_EXTENSION_DISABLED'			=> 'افزونه %sفعال نیست',
	'CLI_EXTENSION_ENABLE_FAILURE'		=> 'امکان فعال سازی افزونه %s وجود ندارد.',
	'CLI_EXTENSION_ENABLED'				=> 'افزووه %s در حال حاضر فعال است.',
	'CLI_EXTENSION_NOT_EXIST'			=> 'افزونه %s وجود ندارد',
	'CLI_EXTENSION_ENABLE_SUCCESS'		=> 'افزونه %s با موفقیت فعال شد.',
	'CLI_EXTENSION_NAME'				=> 'نام افزونه',
	'CLI_EXTENSION_PURGE_FAILURE'		=> 'امکان پاکسازی افزونه %s وجود ندارد.',
	'CLI_EXTENSION_PURGE_SUCCESS'		=> 'پاکسازی افزونه %s با موفقیت انجام شد.',
	'CLI_EXTENSION_UPDATE_FAILURE'		=> 'افزونه %s قابل ارتقا نیست.',
	'CLI_EXTENSION_UPDATE_SUCCESS'		=> 'افزونه %s با موفقیت ارتقا یافت.',
	'CLI_EXTENSION_NOT_FOUND'			=> 'افزونه ای یافت نشد.',
	'CLI_EXTENSION_NOT_ENABLEABLE'		=> 'افزونه %s فعال نیست',
        'CLI_EXTENSIONS_AVAILABLE' => 'موجود',
        'CLI_EXTENSIONS_DISABLED' => 'غیر فعال',
        'CLI_EXTENSIONS_ENABLED' => 'فعال',

    'CLI_FIXUP_FIX_LEFT_RIGHT_IDS_SUCCESS'		=> 'تعمیرات ساختار  درختی انجمن ها و مدل ها با موفقیت انجام شد. ',
	'CLI_FIXUP_RECALCULATE_EMAIL_HASH_SUCCESS'	=> 'هش Hash تمامی ایمیل های با موفقیت محاسبه شد.',
	'CLI_FIXUP_UPDATE_HASH_BCRYPT_SUCCESS'		=> 'پسوردهای منقضی و هش شده با موفقیت به روز رسانی شد.',
	
	'CLI_MIGRATION_NAME'					=> 'نام مهاجرت ، که شامل فضای جمله می شود ( استفاده از اسلش به جای بک اسلش جهت جلوگیری از مشکلات )',
	'CLI_MIGRATIONS_AVAILABLE'				=> 'مهاجرتهای در  دسترس',
	'CLI_MIGRATIONS_INSTALLED'				=> 'مهاجرت ها نصب شدند',
	'CLI_MIGRATIONS_ONLY_AVAILABLE'		    => 'نمایش مهاجرت های در دسترس',
	'CLI_MIGRATIONS_EMPTY'                  => 'مهاجرتی وجود ندارد',

	'CLI_REPARSER_REPARSE_REPARSING'		=> 'اصلاح %1$s (دامنه %2$d..%3$d)',
	'CLI_REPARSER_REPARSE_REPARSING_START'	=> 'اصلاح %s...',
	'CLI_REPARSER_REPARSE_SUCCESS'			=> 'اصلاح شدن با موفقیت انجام شد',

	// In all the case %1$s is the logical name of the file and %2$s the real name on the filesystem
	// eg: big_image.png (2_a51529ae7932008cf8454a95af84cacd) generated.
	'CLI_THUMBNAIL_DELETED'		=> '%1$s (%2$s) حذف شد.',
	'CLI_THUMBNAIL_DELETING'	=> 'حذف ریزعکس ها',
	'CLI_THUMBNAIL_SKIPPED'		=> '%1$s (%2$s) متوقف شد.',
	'CLI_THUMBNAIL_GENERATED'	=> '%1$s (%2$s) ساخته شد.',
	'CLI_THUMBNAIL_GENERATING'	=> 'ساخت ریزعکس ها',
	'CLI_THUMBNAIL_GENERATING_DONE'	=> 'همه ریز عکس ها مجدد ساخته شدند',
	'CLI_THUMBNAIL_DELETING_DONE'	=> 'همه ریز عکس ها حذف شدند',

	'CLI_THUMBNAIL_NOTHING_TO_GENERATE'	=> 'ریزعکسی ساخته نشد',
	'CLI_THUMBNAIL_NOTHING_TO_DELETE'	=> 'ریزعکسی حذف نشد',

	'CLI_USER_ADD_SUCCESS'		=> 'کاربر%sبا موفقیت اضافه شد',
	'CLI_USER_DELETE_CONFIRM'	=> 'برای حذف ‘%s’ مطمئن هستید؟ بله/خیر',
	'CLI_USER_RECLEAN_START'	=> 'پاکسازی مجدد کاربر',
	'CLI_USER_RECLEAN_DONE'		=> [
		0	=> 'پاکسازی کامل شد کاربری برای پاک سازی وجود نداشت.',
		1	=> 'پاکسازی کامل شد. کاربر %d پاکسازی شد.',
		2	=> 'پاکسازی کامل شد. کاربران %d پاکسازی شدند',
	],
));

// Additional help for commands.
$lang = array_merge($lang, array(
	'CLI_HELP_CRON_RUN'			=> $lang['CLI_DESCRIPTION_CRON_RUN'] . ' در صورت تمایل شما میتوانید نام یک و ظیفه را برای اجرا برای یک کار مشخص کنید.',
		'CLI_HELP_USER_ACTIVATE'	=> 'فعال سازی یا غیر فعال سازی اکانت  ها توسط <info>--غیر فعال</info> گزینه.
به صورت اختیاری ایمیل فعال سازی برای کاربر ارسال کنید <info>--ارسال ایمیل</info> گزینه.',
	'CLI_HELP_USER_ADD'			=> ' <info>%command.name%</info> دستور اضافه کردن کاربر:
اگر دستور اجرا بدون گزینه انجام شود از شما میخواهد که وارد آن شوید.
به صورت اختیاری ایمیل فعال سازی برای کاربر ارسال کنید <info>--ارسال ایمیل</info> گزینه.',
	'CLI_HELP_USER_RECLEAN'		=> 'پاکسازی مجدد نام های کاربریبررسی میکند تمام نامهای کاربری ذخیره شده و مطمئن میشود که دقیقا ذخیره خواهند شد. نام های کاربری پاکسازی شده یک فرم غیر حساس است, NFC به صورت عادی تبدیل میشود به ASCII.',
));
