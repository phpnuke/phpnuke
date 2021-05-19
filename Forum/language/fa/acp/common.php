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

// Common
$lang = array_merge($lang, array(
	'ACP_ADMINISTRATORS'		=> 'مدیران',
	'ACP_ADMIN_LOGS'			=> 'رویداد های مدیریت',
	'ACP_ADMIN_ROLES'			=> 'نقش های مدیریت',
	'ACP_ATTACHMENTS'			=> 'پیوست‌ها',
	'ACP_ATTACHMENT_SETTINGS'	=> 'تنظیمات پیوست',
	'ACP_AUTH_SETTINGS'			=> 'تأیید',
	'ACP_AUTOMATION'			=> 'تنظیم خودکار',
	'ACP_AVATAR_SETTINGS'		=> 'تنظیمات نمایه ( آواتار )',

	'ACP_BACKUP'				=> 'پشتیبان',
	'ACP_BAN'					=> ' ',
	'ACP_BAN_EMAILS'			=> 'تحریمE-Mail ها',
	'ACP_BAN_IPS'				=> 'تحریم IP ها',
	'ACP_BAN_USERNAMES'			=> 'تحریم نام های کاربری',
	'ACP_BBCODES'				=> 'BBCode ها',
	'ACP_BOARD_CONFIGURATION'	=> 'پیکربندی انجمن',
	'ACP_BOARD_FEATURES'		=> 'خصوصیات انجمن',
	'ACP_BOARD_MANAGEMENT'		=> 'مدیریت انجمن',
	'ACP_BOARD_SETTINGS'		=> 'تنظیمات انجمن',
	'ACP_BOTS'					=> 'ربات/عنکبوت های جست‌وجوگر',

	'ACP_CAPTCHA'				=> 'کپچا',

	'ACP_CAT_CUSTOMISE'			=> 'سفارشات',
	'ACP_CAT_DATABASE'			=> 'پایگاه داده',
	'ACP_CAT_DOT_MODS'			=> 'افزونه‌ها',
	'ACP_CAT_FORUMS'			=> 'انجمن‌ها',
	'ACP_CAT_GENERAL'			=> 'اصلی',
	'ACP_CAT_MAINTENANCE'		=> 'نگهداری و تعمیر',
	'ACP_CAT_PERMISSIONS'		=> 'سطوح دسترسی',
	'ACP_CAT_POSTING'			=> 'ارسالات',
	'ACP_CAT_STYLES'			=> 'قالب‌ها',
	'ACP_CAT_SYSTEM'			=> 'سیستم',
	'ACP_CAT_USERGROUP'			=> 'کاربران و گروه‌ها',
	'ACP_CAT_USERS'				=> 'کاربران',
	'ACP_CLIENT_COMMUNICATION'	=> 'ارتباط با کاربران',
	'ACP_COOKIE_SETTINGS'		=> 'تنظیمات کوکی',
	'ACP_CONTACT'				=> 'صفحه تماس با ما',
	'ACP_CONTACT_SETTINGS'		=> 'تنظیمات صفحه تماس با ما',
	'ACP_CRITICAL_LOGS'			=> 'گزارش خطا',
	'ACP_CUSTOM_PROFILE_FIELDS'	=> 'فیلد های سفارشی نمایه',

	'ACP_DATABASE'				=> 'مدیریت پایگاه داده',
	'ACP_DISALLOW'				=> 'نپذیرفتن',
	'ACP_DISALLOW_USERNAMES'	=> 'نام کاربری غیرمجاز',

	'ACP_EMAIL_SETTINGS'		=> 'تنظیماتE-Mail',
	'ACP_EXTENSION_GROUPS'		=> 'مدیریت گروه پسوندها',
	'ACP_EXTENSION_MANAGEMENT'	=> 'مدیریت افزونه‌ها',
	'ACP_EXTENSIONS'			=> 'مدیریت افزونه‌ها',

	'ACP_FORUM_BASED_PERMISSIONS'	=> 'سطح دسترسی مستقر انجمن',
	'ACP_FORUM_LOGS'				=> 'رویداد های انجمن',
	'ACP_FORUM_MANAGEMENT'			=> 'مدیریت انجمن',
	'ACP_FORUM_MODERATORS'			=> 'مدیران انجمن',
	'ACP_FORUM_PERMISSIONS'			=> 'سطوح دسترسی انجمن',
	'ACP_FORUM_PERMISSIONS_COPY'	=> 'کپی کردن سطوح دسترسی',
	'ACP_FORUM_ROLES'				=> 'نوع دسترسی انجمن',

	'ACP_GENERAL_CONFIGURATION'		=> 'تنظیمات اصلی',
	'ACP_GENERAL_TASKS'				=> 'وظایف عمومی',
	'ACP_GLOBAL_MODERATORS'			=> 'مدیران کلی انجمن',
	'ACP_GLOBAL_PERMISSIONS'		=> 'سطوح دسترسی کلی',
	'ACP_GROUPS'					=> 'گروه‌ها',
	'ACP_GROUPS_FORUM_PERMISSIONS'	=> 'سطح دسترسی گروه‌های انجمن',
	'ACP_GROUPS_MANAGE'				=> 'مدیریت گروه‌ها',
	'ACP_GROUPS_MANAGEMENT'			=> 'مدیریت گروه',
	'ACP_GROUPS_PERMISSIONS'		=> 'سطوح دسترسی گروه‌ها',
	'ACP_GROUPS_POSITION'			=> 'مدیریت محل قرارگیری گروه‌ها',

	'ACP_HELP_PHPBB'			=> 'کمک پشتیبانی phpBB',

	'ACP_ICONS'					=> 'نماد های موضوع',
	'ACP_ICONS_SMILIES'			=> 'آیکون‌های/شکلک‌های موضوعات',
	'ACP_INACTIVE_USERS'		=> 'کاربران غیرفعال',
	'ACP_INDEX'					=> 'صفحه اصلی کنترل پنل مدیریت',

	'ACP_JABBER_SETTINGS'		=> 'تنظیمات Jabber',

	'ACP_LANGUAGE'				=> 'مدیریت زبان',
	'ACP_LANGUAGE_PACKS'		=> 'بسته های زبانی',
	'ACP_LOAD_SETTINGS'			=> 'تنظیمات بارگذاری',
	'ACP_LOGGING'				=> 'ورود',

	'ACP_MAIN'					=> 'صفحه اصلی کنترل پنل مدیریت',

	'ACP_MANAGE_ATTACHMENTS'			=> 'مدیریت پیوست‌ها',
	'ACP_MANAGE_ATTACHMENTS_EXPLAIN'	=> 'در این قسمت می‌توانید لیست و حذف فایل‌های پیوست در پست‌ها و پیام‌های خصوصی را ببینید.',

	'ACP_MANAGE_EXTENSIONS'		=> 'مدیریت پسوند پیوست‌ها',
	'ACP_MANAGE_FORUMS'			=> 'مدیریت انجمن‌ها',
	'ACP_MANAGE_RANKS'			=> 'مدیریت رتبه‌ها',
	'ACP_MANAGE_REASONS'		=> 'مدیریت دلایل گزارش ها',
	'ACP_MANAGE_USERS'			=> 'مدیریت کاربران',
	'ACP_MASS_EMAIL'			=> 'ایمیل دسته جمعی',
	'ACP_MESSAGES'				=> 'پیام‌ها',
	'ACP_MESSAGE_SETTINGS'		=> 'تنظیمات پیام‌های خصوصی',
	'ACP_MODULE_MANAGEMENT'		=> 'مدیریت ماژول ها',
	'ACP_MOD_LOGS'				=> 'اعمال مدیرانجمن',
	'ACP_MOD_ROLES'				=> 'نوع دسترسی مدیر انجمن',

	'ACP_NO_ITEMS'				=> 'هنوز چیزی موجود نیست.',

	'ACP_ORPHAN_ATTACHMENTS'	=> 'پیوست‌های بدون صاحب',

	'ACP_PERMISSIONS'			=> 'سطوح دسترسی',
	'ACP_PERMISSION_MASKS'		=> 'ماسک های سطوح دسترسی',
	'ACP_PERMISSION_ROLES'		=> 'نقش های سطوح دسترسی',
	'ACP_PERMISSION_TRACE'		=> 'یافتن سطوح دسترسی',
	'ACP_PHP_INFO'				=> 'اطلاعات PHP',
	'ACP_POST_SETTINGS'			=> 'تنظیمات پست',
	'ACP_PRUNE_FORUMS'			=> 'هرس کردن انجمن‌ها',
	'ACP_PRUNE_USERS'			=> 'هرس کردن کاربران',
	'ACP_PRUNING'				=> 'هرس کردن',

	'ACP_QUICK_ACCESS'			=> 'دسترسی سریع',

	'ACP_RANKS'					=> 'رتبه‌ها',
	'ACP_REASONS'				=> 'گزارش ها/دلایل تحریم',
	'ACP_REGISTER_SETTINGS'		=> 'تنظیمات ثبت نام کاربران',

	'ACP_RESTORE'				=> 'بازیابی',

	'ACP_FEED'					=> 'مدیریت خوراک',
	'ACP_FEED_SETTINGS'			=> 'تنظیمات خوراک',

	'ACP_SEARCH'				=> 'پیکربندی جست‌وجو',
	'ACP_SEARCH_INDEX'			=> 'شاخص های جست‌وجو',
	'ACP_SEARCH_SETTINGS'		=> 'تنظیمات جست‌وجو',

	'ACP_SECURITY_SETTINGS'		=> 'تنظیمات امنیتی',
	'ACP_SERVER_CONFIGURATION'	=> 'پیکربندی سرور',
	'ACP_SERVER_SETTINGS'		=> 'تنظیمات سرور',
	'ACP_SIGNATURE_SETTINGS'	=> 'تنظیمات امضا',
	'ACP_SMILIES'				=> 'شکلک‌ها',
	'ACP_STYLE_MANAGEMENT'		=> 'مدیریت قالب‌ها',
	'ACP_STYLES'				=> 'قالب‌ها',
	'ACP_STYLES_CACHE'			=> 'پاک سازی نهانگاه',
	'ACP_STYLES_INSTALL'		=> 'نصب قالب‌ها',

	'ACP_SUBMIT_CHANGES'		=> 'ارسال تغییرات',

	'ACP_TEMPLATES'				=> 'قالب‌ها (Templates)',
	'ACP_THEMES'				=> 'تم ها (Themes)',

	'ACP_UPDATE'					=> 'درحال به‌روز رسانی',
	'ACP_USERS_FORUM_PERMISSIONS'	=> 'سطوح دسترسی کاربر در انجمن',
	'ACP_USERS_LOGS'				=> 'گزارش های کاربران',
	'ACP_USERS_PERMISSIONS'			=> 'سطوح دسترسی کاربر',
	'ACP_USER_ATTACH'				=> 'پیوست‌ها',
	'ACP_USER_AVATAR'				=> 'نمایه',
	'ACP_USER_FEEDBACK'				=> 'دفترچه یادداشت',
	'ACP_USER_GROUPS'				=> 'گروه‌ها',
	'ACP_USER_MANAGEMENT'			=> 'مدیریت کاربر',
	'ACP_USER_OVERVIEW'				=> 'چشم انداز',
	'ACP_USER_PERM'					=> 'سطوح دسترسی',
	'ACP_USER_PREFS'				=> 'پیکربندی',
	'ACP_USER_PROFILE'				=> 'نمایه',
	'ACP_USER_RANK'					=> 'رتبه',
	'ACP_USER_ROLES'				=> 'نوع دسترسی کاربر',
	'ACP_USER_SECURITY'				=> 'امنیتی کاربر',
	'ACP_USER_SIG'					=> 'امضا',
	'ACP_USER_WARNINGS'				=> 'هشدارها',

	'ACP_VC_SETTINGS'					=> 'مقابله با هرزنامه ها',
	'ACP_VC_CAPTCHA_DISPLAY'			=> 'پیش نمایش تصویر CAPTCHA',
	'ACP_VERSION_CHECK'					=> 'بررسی جهت به روزرسانی',
	'ACP_VIEW_ADMIN_PERMISSIONS'		=> 'نمایش سطوح دسترسی موسس (مدیر کل)',
	'ACP_VIEW_FORUM_MOD_PERMISSIONS'	=> 'نمایش سطوح دسترسی مدیر انجمن',
	'ACP_VIEW_FORUM_PERMISSIONS'		=> 'نمایش اجازه نامه های انجمن محور',
	'ACP_VIEW_GLOBAL_MOD_PERMISSIONS'	=> 'نمایش سطوح دسترسی مدیر کل',
	'ACP_VIEW_USER_PERMISSIONS'			=> 'نمایش سطوح دسترسی مستقر کاربر',

	'ACP_WORDS'					=> 'سانسور کلمات',

	'ACTION'				=> 'عمل',
	'ACTIONS'				=> 'عملیات',
	'ACTIVATE'				=> 'فعال کردن',
	'ADD'					=> 'اضافه کردن',
	'ADMIN'					=> 'مدیریت',
	'ADMIN_INDEX'			=> 'فهرست مدیریت',
	'ADMIN_PANEL'			=> 'کنترل پنل مدیریت',

	'ADM_LOGOUT'			=> 'خروج از&nbsp;کنترل پنل مدیریت',
	'ADM_LOGGED_OUT'		=> 'با موفقیت از کنترل پنل مدیریت خارج شدید.',

	'BACK'					=> 'بازگشت',

	'CONTAINER_EXCEPTION' => 'در هنگام نصب افزونه امکان خطا وجود دارد. به همین جهت در این هنگام به صورت موقت تمامی افزونه‌ها غیرفعال میشود. لطفا سعی کنید نهانگاه را از صفحه اصلی پاک کنید. هنگامیکه خطاها رفع شود، افزونه‌ها مجددا فعال خواهند شد. چنانچه این مشکل ادامه پیدا کرد، با پشتیبانی تماس بگیرید <a href="https://www.phpbb.com/support">phpBB.com</a>.',
	'EXCEPTION' => 'استثنا',

	'COLOUR_SWATCH'			=> ' تغییر رنگ Web-safe swatch',
	'CONFIG_UPDATED'		=> 'پیکربندی با موفقیت به‌روز رسانی شد.',
	'CRON_LOCK_ERROR'		=> 'سیستم cron قابل بسته شدن نیست.',
	'CRON_NO_SUCH_TASK'		=> 'وظیفه “%s” در سیستم cron یافت نشد.',
	'CRON_NO_TASK'			=> 'هیچ سیستم وظیفه گر cron در قابل اجرا شدن نیست.',
	'CRON_NO_TASKS'			=> 'سیستم وظیفه گر cron یافت نشد.',
	'CSV_INVALID'			=>  'تنظیمات جداشده با ویرگول“%1$s” نامعتبر است. مقادیر باید فقط با کاما مشخص شوند ، نباید حاوی جداکننده های اصلی یا انتهایی باشد. ',
	'CURRENT_VERSION'		=> 'نسخه فعلی',

	'DEACTIVATE'				=> 'غیرفعال سازی',
	'DIRECTORY_DOES_NOT_EXIST'	=> 'مسیر وارد شده “%s” ، موجود نمی باشد.',
	'DIRECTORY_NOT_DIR'			=> 'مسیر وارد شده “%s” ، دایرکتوری نمی باشد.',
	'DIRECTORY_NOT_WRITABLE'	=> 'مسیر وارد شده “%s” ،قابل نوشتن نیست.',
	'DISABLE'					=> 'غیرفعال',
	'DOWNLOAD'					=> 'دانلود',
	'DOWNLOAD_AS'				=> 'دانلود نتیجه',
	'DOWNLOAD_STORE'			=> 'دریافت یا ذخیره کردن فایل',
	'DOWNLOAD_STORE_EXPLAIN'	=> 'می‌توانید مستقیما فایل را بارگیری کنید و یا در پوشه <samp>store/</samp> ذخیره کنید.',
	'DOWNLOADS'					=> 'دانلود',

	'EDIT'					=> 'ویرایش',
	'ENABLE'				=> 'فعال',
	'EXPORT_DOWNLOAD'		=> 'دانلود',
	'EXPORT_STORE'			=> 'ذخیره',

	'GENERAL_OPTIONS'		=> 'گزینه‌های عمومی',
	'GENERAL_SETTINGS'		=> 'تنظیمات عمومی',
	'GLOBAL_MASK'			=> 'ماسک سطوح دسترسی سراسری',

	'INSTALL'				=> 'نصب',
	'IP'					=> 'IP کاربر',
	'IP_HOSTNAME'			=> 'آدرس IP و یا نام هاست (hostname)',

	'LATEST_VERSION'		=> 'آخرین نسخه',
	'LOAD_NOTIFICATIONS'			=> 'نمایش اعلانات',
	'LOAD_NOTIFICATIONS_EXPLAIN'	=> 'نمایش فهرست اعلانات در همه صفحات (معمولا در سربرگ).',
	'LOGGED_IN_AS'			=> 'وارد شده با نام :',
	'LOGIN_ADMIN'			=> 'برای مدیریت تالار، باید کاربر تأیید شده ای باشید.',
	'LOGIN_ADMIN_CONFIRM'	=> 'برای مدیریت تالار باید مجددا خودتان را تأیید کنید',
	'LOGIN_ADMIN_SUCCESS'	=> 'با موفقیت تأیید شدید و هم اکنون به کنترل پنل مدیریت انتقال داده می شوید.',
	'LOOK_UP_FORUM'			=> 'انجمنی را انتخاب کنید',
	'LOOK_UP_FORUMS_EXPLAIN'=> 'می‌توانید بیش از یک انجمن را انتخاب کنید.',

	'MANAGE'				=> 'مدیریت',
	'MENU_TOGGLE'			=> 'مخفی کردن و یا نمایش دادن نوار کناری',
	'MORE'					=> 'بیشتر',			// Not used at the moment
	'MORE_INFORMATION'		=> 'اطلاعات بیشتر »',
	'MOVE_DOWN'				=> 'انتقال به پایین',
	'MOVE_UP'				=> 'انتقال به بالا',

	'NOTIFY'				=> 'اطلاعیه',
	'NO_ADMIN'				=> 'اجازه مدیریت این تالار را ندارید',
	'NO_EMAILS_DEFINED'		=> 'آدرس ایمیل معتبری یافت نشد.',
	'NO_FILES_TO_DELETE'	=> 'فایل‌های پیوست انتخابی جهت حذف وجود ندارند.',
	'NO_PASSWORD_SUPPLIED'	=> 'برای دسترسی به کنترل پنل مدیریت، باید گذرواژه خود را وارد کنید.',

	'OFF'					=> 'Off',
	'ON'					=> 'On',

	'PARSE_BBCODE'						=> 'تجزیه BBCode',
	'PARSE_SMILIES'						=> 'تجزیه شکلک‌ها',
	'PARSE_URLS'						=> 'تجزیه لینک ها',
	'PERMISSIONS_TRANSFERRED'			=> 'سطوح دسترسی انتقال یافت',
	'PERMISSIONS_TRANSFERRED_EXPLAIN'	=> 'اخیرا سطح دسترسی %1$s را دارید. می‌توانید با سطوح دسترسی این کاربر در تالار گردش کنید. ولی تا زمانی که سطوح دسترسی مدیریت انتقال نشود، به کنترل پنل مدیریت دسترسی نخواهید داشت. در هر زمانی می‌توانید <a href="%2$s">.<strong>به سطوح دسترسی خود برگردید</strong></a>',
	'PROCEED_TO_ACP'					=> '%sرفتن به کنترل پنل مدیریت%s',

	'RELEASE_ANNOUNCEMENT'		=> 'اطلاعیه',
 	'REMIND'							=> 'یادآوری',
	'REPARSE_LOCK_ERROR'				=> 'عملیات دیگری در حال انجام است.',
	'RESYNC'							=> 'انطباق (syncorise)',

	'RUNNING_TASK'			=> 'اجرای وظیفه: %s.',
	'SELECT_ANONYMOUS'		=> 'انتخاب کاربر بی نام',
	'SELECT_OPTION'			=> 'انتخاب گزینه',

	'SETTING_TOO_LOW'		=> 'داده وارد شده به “%1$s” بسیار کم است. حداقل داده مورد قبول  %2$d می باشد.',
	'SETTING_TOO_BIG'		=> 'داده وارد شده به “%1$s” بسیار بالا است. حداکثر داده مورد قبول %2$d می باشد.',
	'SETTING_TOO_LONG'		=> 'داده وارد شده به “%1$s” بسیار دراز است. حداکثر درازای مورد قبول %2$d می باشد.',
	'SETTING_TOO_SHORT'		=> 'داده وارد شده به “%1$s” بسیار کوتاه است. حداقل درازای مورد قبول %2$d می باشد.',

	'SHOW_ALL_OPERATIONS'	=> 'نمایش همه عملیات',

	'TASKS_NOT_READY'			=> 'وظیفه هنوز آماده نیست:',
	'TASKS_READY'			=> 'وظایف آماده:',
	'TOTAL_SIZE'			=> 'حجم کلی',

	'UCP'					=> 'کنترل پنل کاربر',
	'URL_INVALID'			=> 'آدرس های “%1$s” نا معتبرند.',
	'URL_SCHEME_INVALID'	=> 'طرح ارائه شده “%2$s”در تنظیمات جدا شده با ویرگول “%1$s” نامعتبر است. طرح باید با یک نویسه لاتین و به دنبال آن حروف عددی ، خط فاصله یا نقطه شروع شود. ',
	'USERNAMES_EXPLAIN'		=> 'هر نام کاربری را در سطر جداگانه ای قرار دهید.',
	'USER_CONTROL_PANEL'	=> 'کنترل پنل کاربر',

	'UPDATE_NEEDED'			=> 'انجمن به روز نمی‌باشد',
	'UPDATE_NOT_NEEDED'		=> 'انجمن به روز می‌باشد',
	'UPDATES_AVAILABLE'		=> 'آپدیت جدید:',

	'WARNING'				=> 'هشدار',
));

// PHP info
$lang = array_merge($lang, array(
	'ACP_PHP_INFO_EXPLAIN'	=> 'این صفحه اطلاعات و جزئیات نسخه PHP نصب شده در سرور را نمایش می دهد و شامل ماژول های بارگذاری شده، داده‌های در دسترس و تنظیمات پیش‌فرض می باشد. این اطلاعات در حین حل مشکلات ممکن است سودمند واقع شود. ممکن است بعضی از شرکت های هاستینگ اطلاعات نمایشی را به دلایل امنیتب محدود کنند. توصیه می شود که اطلاعات موجود در این صفحه را به هیچکس جزء <a href="http://www.phpbb.com/about/team/">اعضای تیم رسمی</a> بازگو نکنید.',

	'NO_PHPINFO_AVAILABLE'	=> 'اطلاعات پیکربندی PHP شما قابل تشخیص نیست. Phpinfo() برای دلایل امنیتی بسته شده است.',
));

// Logs
$lang = array_merge($lang, array(
	'ACP_ADMIN_LOGS_EXPLAIN'	=> 'این لیست حاوی همگی عملیات صورت گرفته توسط مدیران تالار می باشد. می‌توانید بر اساس نام کاربری،IP،تاریخ و عملیات صورت گرفته مرتب سازی کنید.  اگر سطح دسترسی کافی داشته باشید می‌توانید عملیاتی و یا کل رویداد ها را حذف کنید.',
	'ACP_CRITICAL_LOGS_EXPLAIN'	=> 'این لیست حاوی عملیات صورت گرفته در تالار است. از اطلاعات این لیست می‌توانید در حل کردن بعضی از مشکلات مانند ایمیل های ارسال نشده استفاده کنید. می‌توانید بر اساس نام کاربری،IP، تاریخ و عملیات صورت گرفته مرتب سازی کنید. اگر سطح دسترسی کافی داشته باشید می‌توانید عملیاتی و یا کل رویداد ها را حذف کنید.',
	'ACP_MOD_LOGS_EXPLAIN'		=> 'این لیست حاوی همگی عملیات صورت گرفته در انجمن‌ها، موضوعات و پست‌ها می باشد و همچنین حاوی اطلاعات عملیات صورت گرفته توسط مدیران انجمن بر روی کاربران است. این اطلاعات شامل تحریم کاربران نیز می شود. می‌توانید بر اساس نام کاربری،IP، تاریخ و عملیات صورت گرفته مرتب سازی کنید. اگر سطوح دسترسی کافی داشته باشید می‌توانید عملیاتی و یا کل رویداد ها را حذف کنید.',
	'ACP_USERS_LOGS_EXPLAIN'	=> 'این لیست حاوی اطلاعات عملیات صورت گرفته بر روی کاربران و یا توسط کاربران است (شامل گزارش ها،هشدار ها و یادداشت های کاربران)',
	'ALL_ENTRIES'				=> 'همه ی داده‌ها',

	'DISPLAY_LOG'	=> 'نمایش داده‌ها از آخر',

	'NO_ENTRIES'	=> 'هیچ رویدادی در این بازه وجود ندارد',

	'SORT_IP'		=> 'آدرس IP',
	'SORT_DATE'		=> 'تاریخ',
	'SORT_ACTION'	=> 'عملیات رویداد',
));

// Index page
$lang = array_merge($lang, array(
	'ADMIN_INTRO'				=> 'از انتخاب phpBB متشکریم. این صفحه اطلاعات جامع و سریعی را از آمار تالار در اختیارتان قرار می دهد. لینک های موجود در سمت راست صفحه به شما این امکان را می دهد تا تالارتان را به صورت جامع مدیریت کنید. هر صفحه حاوی دستور العمل های نحوه کارکرد تنظیمات می باشد.',
	'ADMIN_LOG'					=> 'عملیات صورت گرفته توسط مدیران',
	'ADMIN_LOG_INDEX_EXPLAIN'	=> 'این بخش 5 عملیات اخیر صورت گرفته توسط مدیران را نمایش می دهد. همه ی رویدادها در منوی مربوطه و یا از لینک زیر در دسترس است.',
	'AVATAR_DIR_SIZE'			=> 'حجم شاخه آواتار',

	'BOARD_STARTED'		=> 'تاریخ شروع فعالیت تالار',
	'BOARD_VERSION'		=> 'نسخه تالار',

	'DATABASE_SERVER_INFO'	=> 'سرور پایگاه داده',
	'DATABASE_SIZE'			=> 'حجم پایگاه داده',	

	// Environment configuration checks, mbstring related
	'ERROR_MBSTRING_FUNC_OVERLOAD'					=> 'تابع اضافه بار به طور نامناسب پیکربندی شده است',	
	'ERROR_MBSTRING_FUNC_OVERLOAD_EXPLAIN'			=> '<var>mbstring.func_overload</var> باید 0 یا 4 انتخاب شود، می‌توانید داده را از صفحه <samp>اطلاعات PHP</samp> کنترل کنید.',
	'ERROR_MBSTRING_ENCODING_TRANSLATION'			=> 'Transparent character encoding به طور نامناسب پیکربندی شده است',	
	'ERROR_MBSTRING_ENCODING_TRANSLATION_EXPLAIN'	=> '<var>mbstring.encoding_translation</var> باید به 0 تنظیم شود . می‌توانید داده را از صفحه <samp>اطلاعات PHP</samp> کنترل کنید.',
	'ERROR_MBSTRING_HTTP_INPUT'						=> 'HTTP input character conversion به طور نامناسب پیکربندی شده است',	
	'ERROR_MBSTRING_HTTP_INPUT_EXPLAIN'				=> '<var>mbstring.http_input</var> باید به <samp>pass</samp> تنظیم شود. می‌توانید داده را از صفحه <samp>اطلاعات PHP</samp> کنترل کنید.',
	'ERROR_MBSTRING_HTTP_OUTPUT'					=> 'HTTP output character conversion به طور نامناسب پیکربندی شده است',	
	'ERROR_MBSTRING_HTTP_OUTPUT_EXPLAIN'			=> '<var>mbstring.http_output</var>باید به <samp>pass</samp> تنظیم شود. می‌توانید داده را از صفحه <samp>اطلاعات PHP</samp> کنترل کنید.',
	'ERROR_DEFAULT_CHARSET'							=> 'Default charset is improperly configured',
	'ERROR_DEFAULT_CHARSET_EXPLAIN'					=> '<var>default_charset</var> must be set to <samp>UTF-8</samp>. You can check the current value on the <samp>PHP information</samp> page.',

	'FILES_PER_DAY'		=> 'تعداد پیوست در هر روز',
	'FORUM_STATS'		=> 'آمار تالار',

	'GZIP_COMPRESSION'	=> 'GZip فشرده ساز',

	'NO_SEARCH_INDEX'	=> 'سیستم مدیریتی جست‌وجوی انتخاب شده فاقد صفحه اصلی است.<br />لطفا صفحه‌ای برای “%1$s” در %2$sصفحه جست‌وجو%3$s ایجاد کنید.',
	'NOT_AVAILABLE'		=> 'در دسترس نیست',
	'NUMBER_FILES'		=> 'تعداد پیوست ',
	'NUMBER_POSTS'		=> 'تعداد پست ',
	'NUMBER_TOPICS'		=> 'تعداد موضوعات',
	'NUMBER_USERS'		=> 'تعداد کاربران',
	'NUMBER_ORPHAN'		=> 'پیوست‌های بدون صاحب',

	'PHP_VERSION'		=> 'PHP نسخه',
	'PHP_VERSION_OLD'	=> 'نسخه PHP این سرور (%1$s) دیگر توسط نسخه‌های بعدی phpBB پشتیبانی نخواهد شد حداقل نسخه مورد نیاز برای آینده PHP نسخه %2$sاست %3$sجزئیات%4$s',

	'POSTS_PER_DAY'		=> 'تعداد پست در هر روز',

	'PURGE_CACHE'			=> 'پاکسازی نهانگاه',
	'PURGE_CACHE_CONFIRM'	=> 'آیا از پاکسازی نهانگاه مطمئنید؟',
	'PURGE_CACHE_EXPLAIN'	=> 'پاکسازی همه ی نهانگاه ها شامل نهانگاه قالب و پرس و جو ها.',
	'PURGE_CACHE_SUCCESS'	=> 'نهانگاه با موفقیت پاکسازی شد.',

	'PURGE_SESSIONS'			=> 'پاکسازی همه نشست ها',
	'PURGE_SESSIONS_CONFIRM'	=> 'آیا از پاکسازی همه ی نشست ها مطمئنید؟ این کار موجب خروج همه کاربران خواهد شد.',
	'PURGE_SESSIONS_EXPLAIN'	=> 'پاکسازی نشست ها. این کار با خالی کردن جدول نشست ها موجب خروج کاربران خواهد شد.',
	'PURGE_SESSIONS_SUCCESS'	=> 'نشست با موفقیت پاکسازی شد.',

	'RESET_DATE'					=> 'بازنشانی تاریخ شروع فعالیت تالار',
	'RESET_DATE_CONFIRM'			=> 'آیا از بازنشانی تاریخ شروع تالار مطمئنید؟',
	'RESET_DATE_SUCCESS'			=> 'تاریخ شروع تالار با موفقیت بازنشانی شد',
	'RESET_ONLINE'					=> 'بازنشانی بیشترین تعداد افراد آنلاین',
	'RESET_ONLINE_CONFIRM'			=> 'آیا از بازنشانی بیشترین تعداد افراد آنلاین مطمئنید؟',
	'RESET_ONLINE_SUCCESS'			=> 'بیشترین افراد آنلاین با موفقیت بازنشانی شد',
	'RESYNC_POSTCOUNTS'				=> 'همگام سازی مجدد تعداد پست‌ها',
	'RESYNC_POSTCOUNTS_EXPLAIN'		=> 'فقط به پست‌های موجود رسیدگی می شود . پست‌های هرس شده حساب نمی شوند.',
	'RESYNC_POSTCOUNTS_CONFIRM'		=> 'آیا از همگام سازی مجدد تعداد پست‌ها مطمئنید؟',
	'RESYNC_POSTCOUNTS_SUCCESS'		=> 'همگام سازی مجدد پست‌ها انجام شد',
	'RESYNC_POST_MARKING'			=> 'همگام سازی مجدد موضوعات علامت گذاری شده',
	'RESYNC_POST_MARKING_CONFIRM'	=> 'آیا از همگام سازی مجدد موضوعات علامت گذاری شده مطمئنید؟',
	'RESYNC_POST_MARKING_EXPLAIN'	=> 'ابتدا علامت گذاری تمام موضوعات از بین می رود و سپس موضوعاتی که در 6 ماه گذشته در آن‌ها هرگونه فعالیتی دیده شده علامت گذاری می شوند.',
	'RESYNC_POST_MARKING_SUCCESS'	=> 'همگام سازی مجدد موضوعات علامت گذاری شده انجام شد',
	'RESYNC_STATS'					=> 'همگام سازی مجدد آمار',
	'RESYNC_STATS_CONFIRM'			=> 'آیا از همگام سازی مجدد آمار مطمئنید؟',
	'RESYNC_STATS_EXPLAIN'			=> 'محاسبه مجدد تعداد پست‌ها ، موضوعات ، اعضا و فایل‌ها.',
	'RESYNC_STATS_SUCCESS'			=> 'همگام سازی مجدد آمار انجام شد',
	'RUN'							=> 'اجرا',

	'STATISTIC'					=> 'آمار',
	'STATISTIC_RESYNC_OPTIONS'	=> 'همگام سازی مجدد یا بازنشانی آمار',

	'TIMEZONE_INVALID'	=> 'زمان محلی انتخابی معتبر نیست.',
	'TIMEZONE_SELECTED'	=> '(انتخاب کنونی)',
	'TOPICS_PER_DAY'	=> 'موضوعات در هر روز',

	'UPLOAD_DIR_SIZE'	=> 'حجم پیوست‌های ارسال شده',
	'USERS_PER_DAY'		=> 'تعداد کاربران در هر روز',

	'VALUE'						=> 'مقدار',
	'VERSIONCHECK_FAIL'			=> 'دریافت آخرین اطلاعات نسخه موفقیت آمیز نبود!',
	'VERSIONCHECK_FORCE_UPDATE'	=> 'کنترل دوباره ی نسخه',
	'VERSION_CHECK'				=> 'بررسی نسخه',
	'VERSION_CHECK_EXPLAIN'		=> 'بررسی به روز بودن نسخه انجمن',
	'VERSIONCHECK_INVALID_ENTRY'	=> 'اطلاعات مربوط به آخرین نسخه شامل مطلب پشتیبانی نشده است.',
	'VERSIONCHECK_INVALID_URL'		=> 'اطلاعات آخرین نسخه شامل آدرس لینک نا معتبر است.',
	'VERSIONCHECK_INVALID_VERSION'	=> 'اطلاعات مربوط به آخرین نسخه شامل نسخه نامربوط است.',
	'VERSION_NOT_UP_TO_DATE_ACP'	=> 'نسخه انجمن شما به روز نمی‌باشد<br />در پایین لینک اطلاعیه وجود دارد, که شامل اطلاعات مربوط به آپدیت جدید می‌باشد',
	'VERSION_NOT_UP_TO_DATE_TITLE'	=> 'نسخه انجمن شما به روز نمی‌باشد',
	'VERSION_UP_TO_DATE_ACP'	=> 'نسخه انجمن شما به روز می‌باشد. آپدیت جدیدی وجود ندارد.',
	'VIEW_ADMIN_LOG'			=> 'مشاهده رویداد های مدیریت',
	'VIEW_INACTIVE_USERS'		=> 'مشاهده کاربران غیرفعال',

	'WELCOME_PHPBB'			=> 'به phpBB خوش آمدید',
	'WRITABLE_CONFIG'		=> 'فایل پیکربندی (config.php) قابل نوشتن است. به شدّت توصیه می کنیم که سطح دسترسی آن را به 640 و یا حداقل به 644 تغییر دهید. (برای مثال : <a href="http://en.wikipedia.org/wiki/Chmod" rel="external">chmod</a> 640 config.php).',
));

// Inactive Users
$lang = array_merge($lang, array(
	'INACTIVE_DATE'					=> 'تاریخ عدم فعالیت',
	'INACTIVE_REASON'				=> 'دلیل',
	'INACTIVE_REASON_MANUAL'		=> 'حساب توسط مدیر غیرفعال شده است',
	'INACTIVE_REASON_PROFILE'		=> 'جزئیات نمایه تغییر یافت',
	'INACTIVE_REASON_REGISTER'		=> 'حساب هایی که به تازگی ثبت نام کرده اند',
	'INACTIVE_REASON_REMIND'		=> 'کاربر مجبور به فعال سازی دوباره حساب خود خواهد شد.',
	'INACTIVE_REASON_UNKNOWN'		=> 'نامعلوم',
	'INACTIVE_USERS'				=> 'کاربران غیرفعال',
	'INACTIVE_USERS_EXPLAIN'		=> 'این لیستی از کاربران ثبت نام کرده می باشد که حساب آن‌ها غیرفعال است. می‌توانید این کاربران را فعال، حذف و یا به آن‌ها ایمیلی به منظور یادآوری ارسال کنید.',
	'INACTIVE_USERS_EXPLAIN_INDEX'	=> 'این لیستی از 10 کاربر اخیر ثبت نام کرده می باشد که حساب آن‌ها غیرفعال است. لیست کامل در منوی مربوطه و یا در لینک زیر در دسترس می باشد که در آن جا می‌توانید این کاربران را فعال، حذف و یا به آن‌ها ایمیلی به منظور یادآوری ارسال کنید.',

	'NO_INACTIVE_USERS'	=> 'کاربر غیرفعالی وجود ندارد',

	'SORT_INACTIVE'		=> 'تاریخ عدم فعالیت',
	'SORT_LAST_VISIT'	=> 'آخرین بازدید',
	'SORT_REASON'		=> 'دلیل',
	'SORT_REG_DATE'		=> 'تاریخ ثبت نام',
	'SORT_LAST_REMINDER'=> 'آخرین یادآوری',
	'SORT_REMINDER'		=> 'یادآور ارسال شد',

	'USER_IS_INACTIVE'		=> 'کاربر غیرفعال می باشد',
));

// Help support phpBB page
$lang = array_merge($lang, array(
	'EXPLAIN_SEND_STATISTICS'	=> 'لطفا اطلاعات سرور و پیکربندی تالارتان را به phpBB به منظور آنالیز آن‌ها،ارسال کنید. تمام اطلاعاتی که ممکن است حاوی هویت شما باشد،حذف خواهند شد. - اطلاعات کاملا <strong>بی نام</strong>خواهند بود. این اطلاعات برای عموم قابل دسترسی است و ما بر اساس این اطلاعات در مورد نسخه‌های بعدی phpBB تصمیم گیری می کنیم.همچنین این آمار با پروژه PHP که زبان برنامه نویسی phpBB هست نیز به اشتراک گذاشته خواهد شد.',
	'EXPLAIN_SHOW_STATISTICS'	=> 'با استفاده از دکمه زیر می‌توانید تمامی اطلاعاتی را که منتقل خواهند شد، ببینید.',
	'DONT_SEND_STATISTICS'		=> 'اگر مایل به ارسال آمار به phpBB نیستید، به کنترل پنل مدیریت بازگردید.',
	'GO_ACP_MAIN'				=> 'بازگشت به صفحه شروع کنترل پنل مدیریت.',
	'HIDE_STATISTICS'			=> 'مخفی کردن جزئیات',
	'SEND_STATISTICS'			=> 'ارسال آمار',
	'SEND_STATISTICS_LONG'		=> 'ارسال اطلاعات آماری',
	'SHOW_STATISTICS'			=> 'نمایش جزئیات',
	'THANKS_SEND_STATISTICS'	=> 'از ارسال این اطلاعات به ما متشکریم',
	'FAIL_SEND_STATISTICS'		=> 'امکان ارسال اطلاعات وجود ندارد.',
));

// Log Entries
$lang = array_merge($lang, array(
	'LOG_ACL_ADD_USER_GLOBAL_U_'		=> '<strong>سطوح دسترسی کاربران ویرایش و یا اضافه شدند.</strong><br />» %s',
	'LOG_ACL_ADD_GROUP_GLOBAL_U_'		=> '<strong>سطوح دسترسی گروه‌ها ویرایش و یا اضافه شدند.</strong><br />» %s',
	'LOG_ACL_ADD_USER_GLOBAL_M_'		=> '<strong>سطوح دسترسی مدیریت سراسری کاربران ویرایش و یا اضافه شدند.</strong><br />» %s',
	'LOG_ACL_ADD_GROUP_GLOBAL_M_'		=> '<strong>سطوح دسترسی مدیریت سراسری گروه‌ها ویرایش و یا اضافه شدند.</strong><br />» %s',
	'LOG_ACL_ADD_USER_GLOBAL_A_'		=> '<strong>سطوح دسترسی مدیریت کاربران ویرایش و یا اضافه شدند.</strong><br />» %s',
	'LOG_ACL_ADD_GROUP_GLOBAL_A_'		=> '<strong>سطوح دسترسی مدیریت گروه‌ها ویرایش و یا اضافه شدند.</strong><br />» %s',

	'LOG_ACL_ADD_ADMIN_GLOBAL_A_'		=> '<strong>مدیران ویرایش و یا اضافه شدند </strong><br />» %s',
	'LOG_ACL_ADD_MOD_GLOBAL_M_'			=> '<strong>مدیران انجمن‌ها ویرایش و یا اضافه شدند.</strong><br />» %s',

	'LOG_ACL_ADD_USER_LOCAL_F_'			=> '<strong>دسترسی کاربران به انجمن ویرایش و یا اضافه شدند</strong> به %1$s<br />» %2$s',
	'LOG_ACL_ADD_USER_LOCAL_M_'			=> '<strong>دسترسی کاربران به مدیریت انجمن ویرایش و یا اضافه شدند</strong> به %1$s<br />» %2$s',
	'LOG_ACL_ADD_GROUP_LOCAL_F_'		=> '<strong>دسترسی گروه‌ها به انجمن ویرایش و یا اضافه شدند</strong> به %1$s<br />» %2$s',
	'LOG_ACL_ADD_GROUP_LOCAL_M_'		=> '<strong>دسترسی گروه‌ها به مدیریت انجمن ویرایش و یا اضافه شدند</strong> به %1$s<br />» %2$s',

	'LOG_ACL_ADD_MOD_LOCAL_M_'			=> '<strong>مدیران انجمن ویرایش و یا اضافه شدند</strong> برای %1$s<br />» %2$s',
	'LOG_ACL_ADD_FORUM_LOCAL_F_'		=> '<strong>سطوح دسترسی انجمن ویرایش و یا اضافه شدند</strong> برای %1$s<br />» %2$s',

	'LOG_ACL_DEL_ADMIN_GLOBAL_A_'		=> '<strong>حذف مدیران</strong><br />» %s',
	'LOG_ACL_DEL_MOD_GLOBAL_M_'			=> '<strong>حذف مدیران انجمن‌ها</strong><br />» %s',
	'LOG_ACL_DEL_MOD_LOCAL_M_'			=> '<strong>حذف مدیران انجمن</strong> از %1$s<br />» %2$s',
	'LOG_ACL_DEL_FORUM_LOCAL_F_'		=> '<strong>حذف سطوح دسترسی کاربران/گروه‌ها به انجمن</strong> از %1$s<br />» %2$s',

	'LOG_ACL_TRANSFER_PERMISSIONS'		=> '<strong>سطوح دسترسی واگذار شده از</strong><br />» %s',
	'LOG_ACL_RESTORE_PERMISSIONS'		=> '<strong>سطوح دسترسی خودتان بازگردانی شدند، پس از استفاده از سطوع دسترسی واگذار شده از</strong><br />» %s',

	'LOG_ADMIN_AUTH_FAIL'		=> '<strong>ورود به مدیریت موفقیت آمیز نبود</strong>',
	'LOG_ADMIN_AUTH_SUCCESS'	=> '<strong>ورود به مدیریت موفقیت آمیز بود</strong>',

	'LOG_ATTACHMENTS_DELETED'	=> '<strong>پیوست‌های کاربر حذف شدند</strong><br />» %s',

	'LOG_ATTACH_EXT_ADD'		=> '<strong>پسوندها پیوست ویرایش و یا حذف شدند</strong><br />» %s',
	'LOG_ATTACH_EXT_DEL'		=> '<strong>پسوندها پیوست حذف شدند</strong><br />» %s',
	'LOG_ATTACH_EXT_UPDATE'		=> '<strong>پسوندها پیوست به‌روز رسانی شدند</strong><br />» %s',
	'LOG_ATTACH_EXTGROUP_ADD'	=> '<strong>گروه پسوندها اضافه شدند</strong><br />» %s',
	'LOG_ATTACH_EXTGROUP_EDIT'	=> '<strong>گروه پسوندها ویرایش شدند</strong><br />» %s',
	'LOG_ATTACH_EXTGROUP_DEL'	=> '<strong>گروه پسوندها حذف شدند</strong><br />» %s',
	'LOG_ATTACH_FILEUPLOAD'		=> '<strong>فایل بدون صاحب به پستی با ای ID اضافه شد :</strong><br />» %1$d - %2$s',
	'LOG_ATTACH_ORPHAN_DEL'		=> '<strong>فایل بدون صاحب حذف شد</strong><br />» %s',

	'LOG_BAN_EXCLUDE_USER'	=> '<strong>تحریم کاربر لغو شد</strong> به دلیل “<em>%1$s</em>”<br />» %2$s',
	'LOG_BAN_EXCLUDE_IP'	=> '<strong>تحریم IP لغو شد </strong> به دلیل “<em>%1$s</em>”<br />» %2$s',
	'LOG_BAN_EXCLUDE_EMAIL' => '<strong>تحریم ایمیل لغو شد</strong> به دلیل “<em>%1$s</em>”<br />» %2$s',
	'LOG_BAN_USER'			=> '<strong>کاربر تحریم شد</strong> به دلیل “<em>%1$s</em>”<br />» %2$s',
	'LOG_BAN_IP'			=> '<strong>IP تحریم شد</strong> به دلیل “<em>%1$s</em>”<br />» %2$s',
	'LOG_BAN_EMAIL'			=> '<strong>ایمیل تحریم شد</strong> به دلیل “<em>%1$s</em>”<br />» %2$s',
	'LOG_UNBAN_USER'		=> '<strong>تحریم کاربر لغو شد</strong><br />» %s',
	'LOG_UNBAN_IP'			=> '<strong>تحریم IP لغو شد</strong><br />» %s',
	'LOG_UNBAN_EMAIL'		=> '<strong>تحریم ایمیل لغو شد</strong><br />» %s',

	'LOG_BBCODE_ADD'		=> '<strong>BBCode جدیدی اضافه شد</strong><br />» %s',
	'LOG_BBCODE_EDIT'		=> '<strong>BBCode ویرایش شد</strong><br />» %s',
	'LOG_BBCODE_DELETE'		=> '<strong>BBCode حذف شد</strong><br />» %s',
	'LOG_BBCODE_CONFIGURATION_ERROR'	=> '<strong>خطا هنگام تنظیمات BBCode</strong>: %1$s<br />» %2$s',

	'LOG_BOT_ADDED'		=> '<strong>ربات جدیدی اضافه شد</strong><br />» %s',
	'LOG_BOT_DELETE'	=> '<strong>ربات حذف شد</strong><br />» %s',
	'LOG_BOT_UPDATED'	=> '<strong>ربات کنونی به‌روز رسانی شد</strong><br />» %s',

	'LOG_CLEAR_ADMIN'		=> '<strong>رویدادهای مدیریت پاک شدند</strong>',
	'LOG_CLEAR_CRITICAL'	=> '<strong>رویدادهای خطاها پاک شدند</strong>',
	'LOG_CLEAR_MOD'			=> '<strong>رویدادهای مدیران انجمن پاک شدند</strong>',
	'LOG_CLEAR_USER'		=> '<strong>رویدادهای کاربر پاک شد</strong><br />» %s',
	'LOG_CLEAR_USERS'		=> '<strong>رویدادهای کاربران پاک شدند</strong>',

	'LOG_CONFIG_ATTACH'			=> '<strong>تنظیمات پیوست تغییر یافتند</strong>',
	'LOG_CONFIG_AUTH'			=> '<strong>تنظیمات تأیید تغییر یافتند</strong>',
	'LOG_CONFIG_AVATAR'			=> '<strong>تنظیمات آواتار تغییر یافتند</strong>',
	'LOG_CONFIG_COOKIE'			=> '<strong>تنظیمات کوکی تغییر یافتند</strong>',
	'LOG_CONFIG_EMAIL'			=> '<strong>تنظیمات ایمیل تغییر یافتند</strong>',
	'LOG_CONFIG_FEATURES'		=> '<strong>ویژگی های تالار تغییر یافتند</strong>',
	'LOG_CONFIG_LOAD'			=> '<strong>تنظیمات بارگذاری تغییر یافتند</strong>',
	'LOG_CONFIG_MESSAGE'		=> '<strong>تنظیمات پیام‌های خصوصی تغییر یافتند</strong>',
	'LOG_CONFIG_POST'			=> '<strong>تنظیمات پست تغییر یافتند</strong>',
	'LOG_CONFIG_REGISTRATION'	=> '<strong>تنظیمات ثبت نام کاربران تغییر یافتند</strong>',
	'LOG_CONFIG_FEED'			=> '<strong>تنظیمات خوراک تغییر یافتند</strong>',
	'LOG_CONFIG_SEARCH'			=> '<strong>تنظیمات جست‌وجو تغییر یافتند</strong>',
	'LOG_CONFIG_SECURITY'		=> '<strong>تنظیمات امنیت تغییر یافتند</strong>',
	'LOG_CONFIG_SERVER'			=> '<strong>تنظیمات سرور تغییر یافتند</strong>',
	'LOG_CONFIG_SETTINGS'		=> '<strong>تنظیمات تالار تغییر یافتند</strong>',
	'LOG_CONFIG_SIGNATURE'		=> '<strong>تنظیمات امضا تغییر یافتند</strong>',
	'LOG_CONFIG_VISUAL'			=> '<strong>تنظیمات کد تأیید تغییر یافتند</strong>',

	'LOG_APPROVE_TOPIC'			=> '<strong>موضوع تأیید شد</strong><br />» %s',
	'LOG_BUMP_TOPIC'			=> '<strong>موضوع کاربر بامپ (bump) شد</strong><br />» %s',
	'LOG_DELETE_POST'			=> '<strong>حذف پست “%1$s” نوشته شده توسط “%2$s” به دلیل</strong><br />» %3$s',
	'LOG_DELETE_SHADOW_TOPIC'	=> '<strong>موضوع سایه دار حذف شد</strong><br />» %s',
	'LOG_DELETE_TOPIC'			=> '<strong>حذف موضوع “%1$s” نوشته شده توسط “%2$s” به دلیل</strong><br />» %3$s',
	'LOG_FORK'					=> '<strong>موضوع کپی شد</strong><br />» from %s',
	'LOG_LOCK'					=> '<strong>موضوع قفل شد</strong><br />» %s',
	'LOG_LOCK_POST'				=> '<strong>پست قفل شد</strong><br />» %s',
	'LOG_MERGE'					=> '<strong>پست‌ها در این موضوع ادغام شدند</strong><br />» %s',
	'LOG_MOVE'					=> '<strong>موضوع انتقال داده شد</strong><br />» از %1$s به %2$s',
	'LOG_MOVED_TOPIC'			=> '<strong>انتقال موضوع</strong><br />» %s',
	'LOG_PM_REPORT_CLOSED'		=> '<strong>گزارش پیام خصوصی بسته شد</strong><br />» %s',
	'LOG_PM_REPORT_DELETED'		=> '<strong>گزارش پیام خصوصی حذف شد</strong><br />» %s',
	'LOG_POST_APPROVED'			=> '<strong>پست پذیرفته شد</strong><br />» %s',
	'LOG_POST_DISAPPROVED'		=> '<strong>پست پذیرفته نشده “%1$s” نوشته شده توسط “%3$s” به دلیل</strong><br />» %2$s',
	'LOG_POST_EDITED'			=> '<strong>پست ویرایش شده “%1$s” نوشته شده توسط “%2$s” به دلیل</strong><br />» %3$s',
	'LOG_POST_RESTORED'			=> '<strong>پست بازیابی شد</strong><br />» %s',
	'LOG_REPORT_CLOSED'			=> '<strong>گزارش بسته شد</strong><br />» %s',
	'LOG_REPORT_DELETED'		=> '<strong>گزارش حذف شد</strong><br />» %s',
	'LOG_RESTORE_TOPIC'			=> '<strong>بازیابی موضوع “%1$s نوشته شده توسط</strong><br />» %2$s',
	'LOG_SOFTDELETE_POST'		=> '<strong>حذف موقت پست “%1$s” نوشته شده توسط “%2$s” به دلیل</strong><br />» %3$s',
	'LOG_SOFTDELETE_TOPIC'		=> '<strong>حذف موقت موضوع “%1$s” نوشته شده توسط “%2$s” به دلیل</strong><br />» %3$s',
	'LOG_SPLIT_DESTINATION'		=> '<strong>پست‌های دوبخشی منقل شدند</strong><br />» به %s',
	'LOG_SPLIT_SOURCE'			=> '<strong>پست‌های دوبخشی</strong><br />» از %s',

	'LOG_TOPIC_APPROVED'		=> '<strong>موضوع تأیید شد</strong><br />» %s',
	'LOG_TOPIC_RESTORED'		=> '<strong>موضوع بازیابی شد</strong><br />» %s',
	'LOG_TOPIC_DISAPPROVED'		=> '<strong>موضوع تأیید نشده “%1$s” نوشته شده توسط “%3$s” به دلیل</strong><br />» %2$s',
	'LOG_TOPIC_RESYNC'			=> '<strong>انطباق (resync) شمارنده موضوع</strong><br />» %s',
	'LOG_TOPIC_TYPE_CHANGED'	=> '<strong>نوع موضوع تغییر یافت</strong><br />» %s',
	'LOG_UNLOCK'				=> '<strong>قفل موضوع باز شد</strong><br />» %s',
	'LOG_UNLOCK_POST'			=> '<strong>قفل پست باز شد</strong><br />» %s',

	'LOG_DISALLOW_ADD'		=> '<strong>نام کاربری غیرمجاز اضافه شد</strong><br />» %s',
	'LOG_DISALLOW_DELETE'	=> '<strong>نام کاربری غیرمجاز حذف شد</strong>',

	'LOG_DB_BACKUP'			=> '<strong>پشتیبان گیری از پایگاه داده</strong>',
	'LOG_DB_DELETE'			=> '<strong>پشتیبان پایگاه داده حذف شد</strong>',
	'LOG_DB_RESTORE'		=> '<strong>در پشتیبان پایگاه داده بازنگری شد</strong>',

	'LOG_DOWNLOAD_EXCLUDE_IP'	=> '<strong>IP/hostname از لیست بارگیری لغو شد</strong><br />» %s',
	'LOG_DOWNLOAD_IP'			=> '<strong>IP/hostname به لیست بارگیری اضافه شد</strong><br />» %s',
	'LOG_DOWNLOAD_REMOVE_IP'	=> '<strong>IP/hostname از لیست بارگیری حذف شد</strong><br />» %s',

	'LOG_ERROR_JABBER'		=> '<strong>خطای jabber</strong><br />» %s',
	'LOG_ERROR_EMAIL'		=> '<strong>خطای ایمیل</strong><br />» %s',
	'LOG_ERROR_CAPTCHA'		=> '<strong>CAPTCHA خطا در</strong><br />» %s',

	'LOG_FORUM_ADD'							=> '<strong>انجمن جدید ایجاد شد</strong><br />» %s',
	'LOG_FORUM_COPIED_PERMISSIONS'			=> '<strong>سطوح دسترسی انجمن </strong> از %1$s کپی شدند<br />» %2$s',
	'LOG_FORUM_DEL_FORUM'					=> '<strong>انجمن حذف شذ</strong><br />» %s',
	'LOG_FORUM_DEL_FORUMS'					=> '<strong>انجمن و زیرانجمن‌های آن حذف شدند</strong><br />» %s',
	'LOG_FORUM_DEL_MOVE_FORUMS'				=> '<strong>انجمن حذف شد و زیرانجمن‌های آن </strong> به %1$s منتقل شدند<br />» %2$s',
	'LOG_FORUM_DEL_MOVE_POSTS'				=> '<strong>انجمن حذف شد و پست‌های آن </strong> به %1$s منتقل شدند<br />» %2$s',
	'LOG_FORUM_DEL_MOVE_POSTS_FORUMS'		=> '<strong>انجمن و زیرانجمن‌های آن حذف شدند و پست‌ها</strong> به %1$s منتقل شدند<br />» %2$s',
	'LOG_FORUM_DEL_MOVE_POSTS_MOVE_FORUMS'	=> '<strong>انجمن حذف شد و پست‌ها</strong> به %1$s منتقل شدند <strong>و زیرانجمن‌ها نیز</strong> به %2$s منتقل شدند<br />» %3$s',
	'LOG_FORUM_DEL_POSTS'					=> '<strong>انجمن و پست‌های آن حذف شدند</strong><br />» %s',
	'LOG_FORUM_DEL_POSTS_FORUMS'			=> '<strong>انجمن همراه با زیرانجمن‌ها و پست‌های آن حذف شدند </strong><br />» %s',
	'LOG_FORUM_DEL_POSTS_MOVE_FORUMS'		=> '<strong>انجمن و پست‌های ان حذف شدند و زیرانجمن‌های آن نیز</strong> به %1$s منتقل شدند<br />» %2$s',
	'LOG_FORUM_EDIT'						=> '<strong>جزئیات انجمن ویرایش شد</strong><br />» %s',
	'LOG_FORUM_MOVE_DOWN'					=> '<strong>انجمن</strong> %1$s به <strong>زیر</strong> %2$s منقل شد',
	'LOG_FORUM_MOVE_UP'						=> '<strong>انجمن</strong> %1$s به <strong>بالای</strong> %2$s منقل شد',
	'LOG_FORUM_SYNC'						=> '<strong>انجمن منطبق (resync) شد</strong><br />» %s',

	'LOG_GENERAL_ERROR'	=> '<strong>خطایی عمومی رخ داد</strong>: %1$s <br />» %2$s',

	'LOG_GROUP_CREATED'		=> '<strong>گروه کاربری جدیدی ایجاد شد</strong><br />» %s',
	'LOG_GROUP_DEFAULTS'	=> '<strong>گروه “%1$s” به عنوان گروه پیش‌فرض برای کاربران تأیید شد</strong><br />» %2$s',
	'LOG_GROUP_DELETE'		=> '<strong>گروه کاربری حدف شد</strong><br />» %s',
	'LOG_GROUP_DEMOTED'		=> '<strong>رتبه رهبران گروه کاربری در</strong> %1$s تنزیل شد<br />» %2$s',
	'LOG_GROUP_PROMOTED'	=> '<strong>رتبه کاربران  گروه کاربری در </strong> %1$s ارتقاء یافت<br />» %2$s',
	'LOG_GROUP_REMOVE'		=> '<strong>اعضا از گروه کاربری</strong> %1$s حذف شدند<br />» %2$s',
	'LOG_GROUP_UPDATED'		=> '<strong>جزئیات گروه کاربری به‌روز رسانی شد</strong><br />» %s',
	'LOG_MODS_ADDED'		=> '<strong>رهبران جدید به گروه کاربری </strong> %1$s اضافه شدند<br />» %2$s',
	'LOG_USERS_ADDED'		=> '<strong>اعضای جدید به گروه کاربری</strong> %1$s اضافه شدند<br />» %2$s',
	'LOG_USERS_APPROVED'	=> '<strong>اعضا در گروه کاربری</strong> %1$s پذیرفته شدند<br />» %2$s',
	'LOG_USERS_PENDING'		=> '<strong>کاربران دخواست عضویت در گروه “%1$s” ارسال کرده اند که نیازمند تأیید می باشد</strong><br />» %2$s',

	'LOG_IMAGE_GENERATION_ERROR'	=> '<strong>خطایی در حین ایجاد تصویر روی داد</strong><br />» خطا رد %1$s و خط %2$s: %3$s',

	'LOG_INACTIVE_ACTIVATE'	=> '<strong>کاربران غیرفعال،فعال شدند</strong><br />» %s',
	'LOG_INACTIVE_DELETE'	=> '<strong>کاربران غیرفعال، حذف شدند</strong><br />» %s',
	'LOG_INACTIVE_REMIND'	=> '<strong>ایمیل یادآوری به کاربران غیرفعال ارسال شد</strong><br />» %s',
	'LOG_INSTALL_CONVERTED'	=> '<strong>از %1$s به phpBB %2$s تبدیل شد</strong>',
	'LOG_INSTALL_INSTALLED'	=> '<strong>phpBB %s نصب شد</strong>',

	'LOG_IP_BROWSER_FORWARDED_CHECK'	=> '<strong>کنترل نشست IP،مرورگر و X_FORWARDED_FOR شکست خورد</strong><br />»IP کاربر “<em>%1$s</em>” در IP نشست “<em>%2$s</em>” کنترل شد , حلقه مرورگر کاربر “<em>%3$s</em>” در نشست حلقه مرورگر “<em>%4$s</em>” کنترل شد و X_FORWARDED_FOR string کاربر “<em>%5$s</em>” در حلقه نشست X_FORWARDED_FOR “<em>%6$s</em>” کنترل شد.',

	'LOG_JAB_CHANGED'			=> '<strong>حساب Jabeer تغییر یافت</strong>',
	'LOG_JAB_PASSCHG'			=> '<strong>گذرواژه Jabber تغییر یافت</strong>',
	'LOG_JAB_REGISTER'			=> '<strong>حساب Jabber ثبت نام شد</strong>',
	'LOG_JAB_SETTINGS_CHANGED'	=> '<strong>تنظیمات Jabber تغییر یافت</strong>',

	'LOG_LANGUAGE_PACK_DELETED'		=> '<strong>بسته زبانی حذف شد</strong><br />» %s',
	'LOG_LANGUAGE_PACK_INSTALLED'	=> '<strong>بسته زبانی نصب شد</strong><br />» %s',
	'LOG_LANGUAGE_PACK_UPDATED'		=> '<strong>جزئیات بسته زبانی به‌روز رسانی شد</strong><br />» %s',
	'LOG_LANGUAGE_FILE_REPLACED'	=> '<strong>فایل‌های زبان جایگزین شدند</strong><br />» %s',
	'LOG_LANGUAGE_FILE_SUBMITTED'	=> '<strong>فایل زبان ارسال و در پوشه مورد نظر ذخیره شد</strong><br />» %s',

	'LOG_MASS_EMAIL'		=> '<strong>ایمیل های دسته جمعی ارسال شدند</strong><br />» %s',

	'LOG_MCP_CHANGE_POSTER'	=> '<strong>ارسال کننده در موضوع “%1$s” تغییر یافت </strong><br />» از %2$s به %3$s',

	'LOG_MODULE_DISABLE'	=> '<strong>ماژول غیرفعال شد</strong><br />» %s',
	'LOG_MODULE_ENABLE'		=> '<strong>افزونه فعال شد</strong><br />» %s',
	'LOG_MODULE_MOVE_DOWN'	=> '<strong>افزونه به پایین انتقال یافت</strong><br />» %1$s below %2$s',
	'LOG_MODULE_MOVE_UP'	=> '<strong>افزونه به بالا انتقال یافت</strong><br />» %1$s above %2$s',
	'LOG_MODULE_REMOVED'	=> '<strong>افزونه حذف شد</strong><br />» %s',
	'LOG_MODULE_ADD'		=> '<strong>افزونه اضافه شد</strong><br />» %s',
	'LOG_MODULE_EDIT'		=> '<strong>افزونه ویرایش شد</strong><br />» %s',

	'LOG_A_ROLE_ADD'		=> '<strong>نقش مدیریت اضافه شد</strong><br />» %s',
	'LOG_A_ROLE_EDIT'		=> '<strong>نقش مدیریت ویرایش شد</strong><br />» %s',
	'LOG_A_ROLE_REMOVED'	=> '<strong>نقش مدیریت حذف شد</strong><br />» %s',
	'LOG_F_ROLE_ADD'		=> '<strong>نقش انجمن اضافه شد</strong><br />» %s',
	'LOG_F_ROLE_EDIT'		=> '<strong>نقش انجمن ویرایش شد</strong><br />» %s',
	'LOG_F_ROLE_REMOVED'	=> '<strong>نقش انجمن حذف شد</strong><br />» %s',
	'LOG_M_ROLE_ADD'		=> '<strong>نقش مدیر انجمن اضافه شد</strong><br />» %s',
	'LOG_M_ROLE_EDIT'		=> '<strong>نقش مدیر انجمن ویرایش شد</strong><br />» %s',
	'LOG_M_ROLE_REMOVED'	=> '<strong>نقش مدیر انجمن حذف شذ</strong><br />» %s',
	'LOG_U_ROLE_ADD'		=> '<strong>نقش کاربر اضافه شد</strong><br />» %s',
	'LOG_U_ROLE_EDIT'		=> '<strong>نقش کاربر ویرایش شد</strong><br />» %s',
	'LOG_U_ROLE_REMOVED'	=> '<strong>نقش کاربر حذف شد</strong><br />» %s',

	'LOG_PLUPLOAD_TIDY_FAILED'		=> '<strong>قادر به باز کردن %1$s نیستم, سطوح دسترسی را بررسی کنید..</strong><br />استثنا: %2$s<br />مقدار: %3$s',

	'LOG_PROFILE_FIELD_ACTIVATE'	=> '<strong>فیلد نمایه فعال شد</strong><br />» %s',
	'LOG_PROFILE_FIELD_CREATE'		=> '<strong>فیلد نمایه اضافه شد</strong><br />» %s',
	'LOG_PROFILE_FIELD_DEACTIVATE'	=> '<strong>فیلد نمایه غیرفعال شد</strong><br />» %s',
	'LOG_PROFILE_FIELD_EDIT'		=> '<strong>فیلد نمایه تغییر یافت</strong><br />» %s',
	'LOG_PROFILE_FIELD_REMOVED'		=> '<strong>فیلد نمایه حذف شد</strong><br />» %s',

	'LOG_PRUNE'					=> '<strong>انجمن‌ها هرس شدند</strong><br />» %s',
	'LOG_AUTO_PRUNE'			=> '<strong>انجمن‌ها به صورت خودکار هرس شدند</strong><br />» %s',
	'LOG_PRUNE_SHADOW'		=> '<strong>سایه موضوعات به صورت خودکار هرس شدند.</strong><br />» %s',
	'LOG_PRUNE_USER_DEAC'		=> '<strong>کاربران غیرفعال شدند</strong><br />» %s',
	'LOG_PRUNE_USER_DEL_DEL'	=> '<strong>کاربران هرس شده و پست‌ها حذف شدند </strong><br />» %s',
	'LOG_PRUNE_USER_DEL_ANON'	=> '<strong>کاربران هرس شده و پست‌ها حفظ شدند</strong><br />» %s',

	'LOG_PURGE_CACHE'			=> '<strong>نهانگاه پاکسازی شد</strong>',
	'LOG_PURGE_SESSIONS'		=> '<strong>نشست ها پاکسازی شدند</strong>',

	'LOG_RANK_ADDED'		=> '<strong>رتبه جدیدی اضافه شد</strong><br />» %s',
	'LOG_RANK_REMOVED'		=> '<strong>رتبه حذف شد</strong><br />» %s',
	'LOG_RANK_UPDATED'		=> '<strong>رتبه به‌روز رسانی شد</strong><br />» %s',

	'LOG_REASON_ADDED'		=> '<strong>گزارش/دلیل تحریم اضافه شد</strong><br />» %s',
	'LOG_REASON_REMOVED'	=> '<strong>گزارش/دلیل تحریم حذف شد</strong><br />» %s',
	'LOG_REASON_UPDATED'	=> '<strong>گزارش/دلیل تحریم به‌روز رسانی</strong><br />» %s',

	'LOG_REFERER_INVALID'		=> '<strong>تأیید رجوع کننده موفقیت آمیز نبود</strong><br />»رجوع کننده “<em>%1$s</em>” بود که درخواست آن رد شده و نشست بسته شد',
	'LOG_RESET_DATE'			=> '<strong>تاریخ شروع فعالیت تالار بازنشانی شد</strong>',
	'LOG_RESET_ONLINE'			=> '<strong>بیشترین تعداد افراد آنلاین بازنشانی شد</strong>',
	'LOG_RESYNC_FILES_STATS'	=> '<strong>آمار فایل‌ها همگام سازی مجدد شد</strong>',
	'LOG_RESYNC_POSTCOUNTS'		=> '<strong>تعداد پست کاربر همگام سازی مجدد شد</strong>',
	'LOG_RESYNC_POST_MARKING'	=> '<strong>موضوعات علامت گذاری شده همگام سازی مجدد شد. </strong>',
	'LOG_RESYNC_STATS'			=> '<strong>آمار پست‌ها، موضوعات و کاربران همگام سازی مجدد شدند.</strong>',

	'LOG_SEARCH_INDEX_CREATED'	=> '<strong>شاخص جست‌وجو ایجاد شد</strong><br />» %s',
	'LOG_SEARCH_INDEX_REMOVED'	=> '<strong>شاخص جست‌وجو حذف شد</strong><br />» %s',
	'LOG_SPHINX_ERROR'			=> '<strong>Sphinx خطا</strong><br />» %s',
	'LOG_STYLE_ADD'				=> '<strong>قالب جدید اضافه شد</strong><br />» %s',
	'LOG_STYLE_DELETE'			=> '<strong>قالب حذف شد</strong><br />» %s',
	'LOG_STYLE_EDIT_DETAILS'	=> '<strong>قالب ویرایش شد</strong><br />» %s',
	'LOG_STYLE_EXPORT'			=> '<strong>قالب وارد شد</strong><br />» %s',

	// @deprecated 3.1
	'LOG_TEMPLATE_ADD_DB'			=> '<strong>مجموعه قالب جدیدی به پایگاه داده اضافه شد</strong><br />» %s',
	// @deprecated 3.1
	'LOG_TEMPLATE_ADD_FS'			=> '<strong>مجموعه قالب جدیدی به سیستم فایل اضافه شد</strong><br />» %s',
	'LOG_TEMPLATE_CACHE_CLEARED'	=> '<strong>نسخه‌های کش شده فایل های قالب در مجموعه قالب، حذف شد <em>%1$s</em></strong><br />» %2$s',
	'LOG_TEMPLATE_DELETE'			=> '<strong>مجموعه قالب حذف شد</strong><br />» %s',
	'LOG_TEMPLATE_EDIT'				=> '<strong>مجموعه قالب <em>%1$s</em> ویرایش شد</strong><br />» %2$s',
	'LOG_TEMPLATE_EDIT_DETAILS'		=> '<strong>جزئیات قالب ویرایش شد</strong><br />» %s',
	'LOG_TEMPLATE_EXPORT'			=> '<strong>مجموعه قالب به بیرون برده شد</strong><br />» %s',
	// @deprecated 3.1
	'LOG_TEMPLATE_REFRESHED'		=> '<strong>مجموعه قالب به‌روز رسانی شد</strong><br />» %s',

	// @deprecated 3.1
	'LOG_THEME_ADD_DB'			=> '<strong>تم جدیدی به پایگاه داده اضافه شد</strong><br />» %s',
	// @deprecated 3.1
	'LOG_THEME_ADD_FS'			=> '<strong>مجموعه قالب جدیدی به سیستم فایل اضافه شد</strong><br />» %s',
	'LOG_THEME_DELETE'			=> '<strong>تم حذف شد</strong><br />» %s',
	'LOG_THEME_EDIT_DETAILS'	=> '<strong>جزئیات تم ویرایش شد</strong><br />» %s',
	'LOG_THEME_EDIT'			=> '<strong>تم <em>%1$s</em> ویرایش شد </strong>',
	'LOG_THEME_EDIT_FILE'		=> '<strong>تم <em>%1$s</em> ویرایش شد </strong><br />» فایل <em>%2$s</em> تغییر یافت ',
	'LOG_THEME_EXPORT'			=> '<strong>تم به بیرون برده شد</strong><br />» %s',
	// @deprecated 3.1
	'LOG_THEME_REFRESHED'		=> '<strong>تم به‌روز رسانی شد</strong><br />» %s',

	'LOG_UPDATE_DATABASE'	=> '<strong>پایگاه داده از نسخه %1$s به نسخه %2$s به‌روز رسانی شد</strong>',
	'LOG_UPDATE_PHPBB'		=> '<strong>phpBB از نسخه %1$s به نسخه %2$s به‌روز رسانی شد.</strong>',

	'LOG_USER_ACTIVE'		=> '<strong>کاربر فعال شد</strong><br />» %s',
	'LOG_USER_BAN_USER'		=> '<strong>کاربر توسط مدیریت اعضا</strong> به دلیل “<em>%1$s</em>” تحریم شد <br />» %2$s',
	'LOG_USER_BAN_IP'		=> '<strong>IP توسط مدیریت اعضا</strong> به دلیل “<em>%1$s</em>” تحریم شد <br />» %2$s',
	'LOG_USER_BAN_EMAIL'	=> '<strong>ایمیل توسط مدیریت اعضا</strong> به دلیل “<em>%1$s</em>” تحریم شد <br />» %2$s',
	'LOG_USER_DELETED'		=> '<strong>کاربر حذف شد</strong><br />» %s',
	'LOG_USER_DEL_ATTACH'	=> '<strong>همه ی پیوست‌های متعلق به کاربر حذف شد</strong><br />» %s',
	'LOG_USER_DEL_AVATAR'	=> '<strong>نمایه کاربر حذف شد</strong><br />» %s',
	'LOG_USER_DEL_OUTBOX'	=> '<strong>صندوق خروجی کاربر تخلیه شد</strong><br />» %s',
	'LOG_USER_DEL_POSTS'	=> '<strong>همه ی پست‌های متعلق به کاربر حذف شد</strong><br />» %s',
	'LOG_USER_DEL_SIG'		=> '<strong>امضای کاربر حذف شد</strong><br />» %s',
	'LOG_USER_INACTIVE'		=> '<strong>کاربر غیرفعال شد</strong><br />» %s',
	'LOG_USER_MOVE_POSTS'	=> '<strong>پست‌های کاربر منتقل شدند</strong><br />» پست‌های متعلق به “%1$s” به انجمن “%2$s” منتقل شدند',
	'LOG_USER_NEW_PASSWORD'	=> '<strong>گذرواژه کاربر تغییر یافت</strong><br />» %s',
	'LOG_USER_REACTIVATE'	=> '<strong>اکنون کاربر مجبور به فعال سازی دوباره حساب خود می باشد</strong><br />» %s',
	'LOG_USER_REMOVED_NR'	=> '<strong>بیان کاربر جدید از کاربر حذف شد</strong><br />» %s',

	'LOG_USER_UPDATE_EMAIL'	=> '<strong>ایمیل کاربر “%1$s” </strong><br />» از “%2$s” به “%3$s” تغییر یافت',
	'LOG_USER_UPDATE_NAME'	=> '<strong>نام کاربری</strong><br />» از “%1$s” به “%2$s” تغییر یافت',
	'LOG_USER_USER_UPDATE'	=> '<strong>جزئیات کاربر به‌روز رسانی شد</strong><br />» %s',

	'LOG_USER_ACTIVE_USER'		=> '<strong>حساب کاربر فعال شد</strong>',
	'LOG_USER_DEL_AVATAR_USER'	=> '<strong>نمایه کاربر حذف شد</strong>',
	'LOG_USER_DEL_SIG_USER'		=> '<strong>امضای کاربر حذف شد</strong>',
	'LOG_USER_FEEDBACK'			=> '<strong>بازخورد به کاربر اضافه شد</strong><br />» %s',
	'LOG_USER_GENERAL'			=> '<strong>داده اضافه شد :</strong><br />» %s',
	'LOG_USER_INACTIVE_USER'	=> '<strong>حساب کاربر غیرفعال شد</strong>',
	'LOG_USER_LOCK'				=> '<strong>کاربر موضوع خود را قفل کرد</strong><br />» %s',
	'LOG_USER_MOVE_POSTS_USER'	=> '<strong>همه پست‌ها به انجمن</strong>» %s منتقل شدند',
	'LOG_USER_REACTIVATE_USER'	=> '<strong>اکنون کاربر مجبور به فعال سازی مجدد حساب خود می باشد</strong>',
	'LOG_USER_UNLOCK'			=> '<strong>کاربر قفل موضوع خود را باز کرد</strong><br />» %s',
	'LOG_USER_WARNING'			=> '<strong>هشدار به کاربر اضافه شد</strong><br />» %s',
	'LOG_USER_WARNING_BODY'		=> '<strong>هشدار مقابل به کاربر ارسال شد</strong><br />» %s',

	'LOG_USER_GROUP_CHANGE'			=> '<strong>کاربر گروه پیش‌فرضش را تغییر داد</strong><br />» %s',
	'LOG_USER_GROUP_DEMOTE'			=> '<strong>رتبه رهبری کاربر در گروه کاربری تنزل یافت</strong><br />» %s',
	'LOG_USER_GROUP_JOIN'			=> '<strong>کاربر در گروه عضو شد</strong><br />» %s',
	'LOG_USER_GROUP_JOIN_PENDING'	=> '<strong>کاربر در گروه عضو شد و نیازمند تأیید است</strong><br />» %s',
	'LOG_USER_GROUP_RESIGN'			=> '<strong>عضویت کاربر در گروه لغو شد</strong><br />» %s',

	'LOG_WARNING_DELETED'		=> '<strong>هشدار کاربر حذف شد</strong><br />» %s',
	'LOG_WARNINGS_DELETED'		=> array(
		1 => '<strong>هشدار کاربر حذف شد</strong><br />» %1$s',
		2 => '<strong>%2$d هشدار کاربر حذف شد</strong><br />» %1$s', // Example: '<strong>Deleted 2 user warnings</strong><br />» username'
	),
	'LOG_WARNINGS_DELETED_ALL'	=> '<strong>همگی هشدار های کاربر حذف شدند</strong><br />» %s',

	'LOG_WORD_ADD'			=> '<strong>سانسور کلمه اضافه شد</strong><br />» %s',
	'LOG_WORD_DELETE'		=> '<strong>سانسور کلمه حذف شد</strong><br />» %s',
	'LOG_WORD_EDIT'			=> '<strong>سانسور کلمه ویرایش شد</strong><br />» %s',

	'LOG_EXT_ENABLE'	=> '<strong>افزونه فعال شد</strong><br />» %s',
	'LOG_EXT_DISABLE'	=> '<strong>افزونه غیرفعال شد</strong><br />» %s',
	'LOG_EXT_PURGE'		=> '<strong>داده‌های افزونه حذف شد.</strong><br />» %s',
	'LOG_EXT_UPDATE'	=> '<strong>افزونه به روز می‌باشد</strong><br />» %s',
));
