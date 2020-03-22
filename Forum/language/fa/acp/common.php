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

// Common
$lang = array_merge($lang, array(
	'ACP_ADMINISTRATORS'		=> 'مدیران',
	'ACP_ADMIN_LOGS'			=> 'رویداد های مدیریت',
	'ACP_ADMIN_ROLES'			=> 'نقش های مدیریت',
	'ACP_ATTACHMENTS'			=> 'پیوست ها',
	'ACP_ATTACHMENT_SETTINGS'	=> 'تنظیمات پیوست',
	'ACP_AUTH_SETTINGS'			=> 'تایید',
	'ACP_AUTOMATION'			=> 'تنظیم خودکار',
	'ACP_AVATAR_SETTINGS'		=> 'تنظیمات نمایه ( آواتار )',

	'ACP_BACKUP'				=> 'پشتيبان',
	'ACP_BAN'					=> ' ',
	'ACP_BAN_EMAILS'			=> 'تحريمE-Mail ها',
	'ACP_BAN_IPS'				=> 'تحريم IP ها',
	'ACP_BAN_USERNAMES'			=> 'تحريم نام هاي کاربري',
	'ACP_BBCODES'				=> 'BBCode ها',
	'ACP_BOARD_CONFIGURATION'	=> 'پيکربندي انجمن',
	'ACP_BOARD_FEATURES'		=> 'خصوصيات انجمن',
	'ACP_BOARD_MANAGEMENT'		=> 'مديريت انجمن',
	'ACP_BOARD_SETTINGS'		=> 'تنظيمات انجمن',
	'ACP_BOTS'					=> 'روبات/عنکبوت هاي جستجوگر',

	'ACP_CAPTCHA'				=> 'کپچا',

	'ACP_CAT_CUSTOMISE'			=> 'سفارشات',
	'ACP_CAT_DATABASE'			=> 'پایگاه داده',
	'ACP_CAT_DOT_MODS'			=> 'افزونه ها',
	'ACP_CAT_FORUMS'			=> 'انجمن ها',
	'ACP_CAT_GENERAL'			=> 'اصلي',
	'ACP_CAT_MAINTENANCE'		=> 'نگهداري و تعمير',
	'ACP_CAT_PERMISSIONS'		=> 'سطوح دسترسي',
	'ACP_CAT_POSTING'			=> 'ارسالات',
	'ACP_CAT_STYLES'			=> 'قالب ها',
	'ACP_CAT_SYSTEM'			=> 'سيستم',
	'ACP_CAT_USERGROUP'			=> 'کاربران و گروه ها',
	'ACP_CAT_USERS'				=> 'کاربران',
	'ACP_CLIENT_COMMUNICATION'	=> 'ارتباط با کاربران',
	'ACP_COOKIE_SETTINGS'		=> 'تنظیمات کوکی',
	'ACP_CONTACT'				=> 'صفحه تماس با ما',
	'ACP_CONTACT_SETTINGS'		=> 'تنظیمات صفحه تماس با ما',
	'ACP_CRITICAL_LOGS'			=> 'گزارش خطا',
	'ACP_CUSTOM_PROFILE_FIELDS'	=> 'فیلد های سفارشی پروفایل',

	'ACP_DATABASE'				=> 'مدیریت پایگاه داده',
	'ACP_DISALLOW'				=> 'نپذیرفتن',
	'ACP_DISALLOW_USERNAMES'	=> 'نام کاربری غیرمجاز',

	'ACP_EMAIL_SETTINGS'		=> 'تنظيماتE-Mail',
	'ACP_EXTENSION_GROUPS'		=> 'مديريت گروه پسوندها',
	'ACP_EXTENSION_MANAGEMENT'	=> 'مدیریت افزونه ها',
	'ACP_EXTENSIONS'			=> 'مدیریت افزونه ها',

	'ACP_FORUM_BASED_PERMISSIONS'	=> 'سطح دسترسي مستقر انجمن',
	'ACP_FORUM_LOGS'				=> 'رویداد های انجمن',
	'ACP_FORUM_MANAGEMENT'			=> 'مدیریت انجمن',
	'ACP_FORUM_MODERATORS'			=> 'مدیران انجمن',
	'ACP_FORUM_PERMISSIONS'			=> 'سطوح دسترسي انجمن',
	'ACP_FORUM_PERMISSIONS_COPY'	=> 'کپی کردن سطوح دسترسی',
	'ACP_FORUM_ROLES'				=> 'نوع دسترسي انجمن',

	'ACP_GENERAL_CONFIGURATION'		=> 'تنظيمات اصلي',
	'ACP_GENERAL_TASKS'				=> 'وظایف عمومی',
	'ACP_GLOBAL_MODERATORS'			=> 'مديران کلي انجمن',
	'ACP_GLOBAL_PERMISSIONS'		=> 'سطوح دسترسي کلي',
	'ACP_GROUPS'					=> 'گروه ها',
	'ACP_GROUPS_FORUM_PERMISSIONS'	=> 'سطح دسترسي گروه هاي انجمن',
	'ACP_GROUPS_MANAGE'				=> 'مديريت گروه ها',
	'ACP_GROUPS_MANAGEMENT'			=> 'مديريت گروه',
	'ACP_GROUPS_PERMISSIONS'		=> 'سطوح دسترسي گروه ها',
	'ACP_GROUPS_POSITION'			=> 'مدیریت محل قرارگیری گروه ها',

	'ACP_HELP_PHPBB'			=> 'کمک پشتیبانی phpBB',
		
	'ACP_ICONS'					=> 'نماد هاي مبحث',
	'ACP_ICONS_SMILIES'			=> 'آیکون های/شکلک های موضوعات',
	'ACP_INACTIVE_USERS'		=> 'کاربران غیرفعال',
	'ACP_INDEX'					=> 'صفحه اصلی کنترل پنل مدیریت',

	'ACP_JABBER_SETTINGS'		=> 'تنظیمات Jabber',

	'ACP_LANGUAGE'				=> 'مدیریت زبان',
	'ACP_LANGUAGE_PACKS'		=> 'بسته های زبانی',
	'ACP_LOAD_SETTINGS'			=> 'تنظیمات بارگذاری',
	'ACP_LOGGING'				=> 'ورود',

	'ACP_MAIN'					=> 'صفحه اصلی کنترل پنل مدیریت',

	'ACP_MANAGE_ATTACHMENTS'			=> 'مدیریت پیوست ها',
	'ACP_MANAGE_ATTACHMENTS_EXPLAIN'	=> 'در این قسمت شما میتوانید لیست و حذف فایل های پیوست در پست ها و پیغام های خصوصی را ببینید.',

	'ACP_MANAGE_EXTENSIONS'		=> 'مدیریت پسوند پیوست ها',
	'ACP_MANAGE_FORUMS'			=> 'مديريت انجمن ها',
	'ACP_MANAGE_RANKS'			=> 'مديريت رتبه ها',
	'ACP_MANAGE_REASONS'		=> 'مديريت دلايل گزارش ها',
	'ACP_MANAGE_USERS'			=> 'مديريت کاربران',
	'ACP_MASS_EMAIL'			=> 'ایمیل دسته جمعی',
	'ACP_MESSAGES'				=> 'پیغام ها',
	'ACP_MESSAGE_SETTINGS'		=> 'تنظیمات پیغام های خصوصی',
	'ACP_MODULE_MANAGEMENT'		=> 'مدیریت مازول ها',
	'ACP_MOD_LOGS'				=> 'اعمال مديرانجمن',
	'ACP_MOD_ROLES'				=> 'نوع دسترسي مدير انجمن',

        'ACP_NO_ITEMS'				=> 'هنوز چيزي موجود نيست.',

	'ACP_ORPHAN_ATTACHMENTS'	=> 'پیوست های بدون صاحب',

	'ACP_PERMISSIONS'			=> 'سطوح دسترسی',
	'ACP_PERMISSION_MASKS'		=> 'ماسک های سطوح دسترسی',
	'ACP_PERMISSION_ROLES'		=> 'نقش های سطوح دسترسی',
	'ACP_PERMISSION_TRACE'		=> 'یافتن سطوح دسترسی',
	'ACP_PHP_INFO'				=> 'اطلاعات PHP',
	'ACP_POST_SETTINGS'			=> 'تنظيمات پست',
	'ACP_PRUNE_FORUMS'			=> 'هرس کردن انجمن ها',
	'ACP_PRUNE_USERS'			=> 'هرس کردن کاربران',
	'ACP_PRUNING'				=> 'هرس كردن',

	'ACP_QUICK_ACCESS'			=> 'دسترسي سريع',

	'ACP_RANKS'					=> 'رتبه ها',
	'ACP_REASONS'				=> 'گزارش ها/دلایل تحریم',
	'ACP_REGISTER_SETTINGS'		=> 'تنظیمات ثبت نام کاربران',

	'ACP_RESTORE'				=> 'بازیابی',

	'ACP_FEED'					=> 'مدیریت خوراک',
	'ACP_FEED_SETTINGS'			=> 'تنظیمات خوراک',

	'ACP_SEARCH'				=> 'پیکربندی جستجو',
	'ACP_SEARCH_INDEX'			=> 'شاخص های جستجو',
	'ACP_SEARCH_SETTINGS'		=> 'تنظیمات جستجو',

	'ACP_SECURITY_SETTINGS'		=> 'تنظیمات امنیتی',
	'ACP_SERVER_CONFIGURATION'	=> 'پیکربندی سرور',
	'ACP_SERVER_SETTINGS'		=> 'تنظیمات سرور',
	'ACP_SIGNATURE_SETTINGS'	=> 'تنظیمات امضا',
	'ACP_SMILIES'				=> 'شکلک ها',
	'ACP_STYLE_MANAGEMENT'		=> 'مدیریت قالب ها',
	'ACP_STYLES'				=> 'قالب ها',
	'ACP_STYLES_CACHE'			=> 'پاک سازی کش',
	'ACP_STYLES_INSTALL'		=> 'نصب قالب ها',

	'ACP_SUBMIT_CHANGES'		=> 'ارسال تغییرات',

	'ACP_TEMPLATES'				=> 'قالب ها (Templates)',
	'ACP_THEMES'				=> 'تم ها (Themes)',

	'ACP_UPDATE'					=> 'درحال به روز رساني',
	'ACP_USERS_FORUM_PERMISSIONS'	=> 'سطوح دسترسي کاربر در انجمن',
	'ACP_USERS_LOGS'				=> 'گزارش هاي کاربران',
	'ACP_USERS_PERMISSIONS'			=> 'سطوح دسترسي کاربر',
	'ACP_USER_ATTACH'				=> 'پيوست ها',
	'ACP_USER_AVATAR'				=> 'نمایه',
	'ACP_USER_FEEDBACK'				=> 'دفترچه يادداشت',
	'ACP_USER_GROUPS'				=> 'گروه ها',
	'ACP_USER_MANAGEMENT'			=> 'مديريت کاربر',
	'ACP_USER_OVERVIEW'				=> 'چشم انداز',
	'ACP_USER_PERM'					=> 'سطوح دسترسي',
	'ACP_USER_PREFS'				=> 'پيکربندي',
	'ACP_USER_PROFILE'				=> 'پروفایل',
	'ACP_USER_RANK'					=> 'رتبه',
	'ACP_USER_ROLES'				=> 'نوع دسترسي کاربر',
	'ACP_USER_SECURITY'				=> 'امنيتي کاربر',
	'ACP_USER_SIG'					=> 'امضا',
	'ACP_USER_WARNINGS'				=> 'هشدارها',

	'ACP_VC_SETTINGS'					=> 'مقابله با هرزنامه ها',
	'ACP_VC_CAPTCHA_DISPLAY'			=> 'پیش نمایش تصویر CAPTCHA',
	'ACP_VERSION_CHECK'					=> 'بررسی جهت به روزرسانی',
	'ACP_VIEW_ADMIN_PERMISSIONS'		=> 'نمايش سطوح دسترسي موسس (مدير كل)',
	'ACP_VIEW_FORUM_MOD_PERMISSIONS'	=> 'نمايش سطوح دسترسي مدير انجمن',
	'ACP_VIEW_FORUM_PERMISSIONS'		=> 'نمايش اجازه نامه هاي انجمن محور',
	'ACP_VIEW_GLOBAL_MOD_PERMISSIONS'	=> 'نمايش سطوح دسترسي مدير كل',
	'ACP_VIEW_USER_PERMISSIONS'			=> 'نمايش سطوح دسترسي مستقر کاربر',

	'ACP_WORDS'					=> 'سانسور کلمات',

	'ACTION'				=> 'عمل',
	'ACTIONS'				=> 'عملیات',
	'ACTIVATE'				=> 'فعال کردن',
	'ADD'					=> 'اضافه کردن',
	'ADMIN'					=> 'مديريت',
	'ADMIN_INDEX'			=> 'فهرست مديريت',
	'ADMIN_PANEL'			=> 'کنترل پنل مديريت',

	'ADM_LOGOUT'			=> 'خروج از&nbsp;کنترل پنل مدیریت',
	'ADM_LOGGED_OUT'		=> 'با موفقیت از کنترل پنل مدیریت خارج شدید.',

	'BACK'					=> 'بازگشت',

	'CANNOT_CHANGE_FILE_GROUP'	=> 'فایل گروه قابل تغییر نیست',
	'CANNOT_CHANGE_FILE_PERMISSIONS'	=> 'دسترسی فایل قابل انجام نیست',
	'CANNOT_COPY_FILES'		=> 'قادر به کپی نمیباشد',
	'CANNOT_CREATE_SYMLINK'	=> 'قادر به ساخت لینک نمی باشد',
	'CANNOT_DELETE_FILES'	=> 'حذف فایل ها از سیستم امکانپذیر نیست',
	'CANNOT_DUMP_FILE'		=> 'حذف فایل مجاز نیست',
	'CANNOT_MIRROR_DIRECTORY'	=> 'امکان دسترسی به فایل اصلی وجود ندارد',
	'CANNOT_RENAME_FILE'	=> 'امکان تغییر نام فایل سیستم وجود ندارد',
	'CANNOT_TOUCH_FILES'	=> 'محدود کردن فایل موجود امکان ندارد',

	'CONTAINER_EXCEPTION' => 'در هنگام نصب افزونه امکان خطا وجود دارد. به همین جهت در این هنگام به صورت موقت تمامی افزونه ها غیر فعال میشود. لطفا کش را از صفحه اصلی پاک کنید. هنگامیکه خطاها رفع شود مجدد افزونه ها فعال خواهد شد. چنانچه این مشکل ادامه دار شد با پشتیبانی زیر تماس بگیرید <a href="https://www.phpbb.com/support">phpBB.com</a> ',
	'EXCEPTION' => 'استثنا',

	'COLOUR_SWATCH'			=> ' تغییر رنگ Web-safe swatch',
	'CONFIG_UPDATED'		=> 'پیکربندی با موفقیت بروزرسانی شد.',
	'CRON_LOCK_ERROR'		=> 'سیستم cron قابل بسته شدن نیست.',
	'CRON_NO_SUCH_TASK'		=> 'وظیفه “%s” در سیستم cron یافت نشد.',
	'CRON_NO_TASK'			=> 'هیچ سیستم وظیفه گر cron در قابل اجرا شدن نیست.',
	'CRON_NO_TASKS'			=> 'سیستم وظیفه گر cron یافت نشد.',
	'CURRENT_VERSION'		=> 'Current version',

	'DEACTIVATE'				=> 'غیرفعال سازی',
	'DIRECTORY_DOES_NOT_EXIST'	=> 'مسیر وارد شده “%s” ، موجود نمی باشد.',
	'DIRECTORY_NOT_DIR'			=> 'مسیر وارد شده “%s” ، دایرکتوری نمی باشد.',
	'DIRECTORY_NOT_WRITABLE'	=> 'مسیر وارد شده “%s” ،قابل نوشتن نیست.',
	'DISABLE'					=> 'غيرفعال',
	'DOWNLOAD'					=> 'دانلود',
	'DOWNLOAD_AS'				=> 'دانلود نتيجه',
	'DOWNLOAD_STORE'			=> 'دريافت يا ذخيره کردن فايل',
	'DOWNLOAD_STORE_EXPLAIN'	=> 'می توانید مستقیما فایل را بارگیری کنید و یا در پوشه <samp>store/</samp> ذخیره کنید.',
	'DOWNLOADS'					=> 'دانلود',

	'EDIT'					=> 'ویرایش',
	'ENABLE'				=> 'فعال',
	'EXPORT_DOWNLOAD'		=> 'دانلود',
	'EXPORT_STORE'			=> 'ذخیره',

	'GENERAL_OPTIONS'		=> 'گزینه های عمومی',
	'GENERAL_SETTINGS'		=> 'تنظیمات عمومی',
	'GLOBAL_MASK'			=> 'ماسک سطوح دسترسی سراسری',

	'INSTALL'				=> 'نصب',
	'IP'					=> 'IP کاربر',
	'IP_HOSTNAME'			=> 'آدرس IP و یا نام هاست (hostname)',

	'LATEST_VERSION'		=> 'آخرین نسخه',
	'LOAD_NOTIFICATIONS'			=> 'نمایش یاد آوری ها',
	'LOAD_NOTIFICATIONS_EXPLAIN'	=> 'نمایش "یادآوری ها" در همه صفحات ( بالای صفحه )',
	'LOGGED_IN_AS'			=> 'وارد شده با نام :',
	'LOGIN_ADMIN'			=> 'برای مدیریت تالار، باید کاربر تایید شده ای باشید.',
	'LOGIN_ADMIN_CONFIRM'	=> 'برای مدیریت تالار باید مجددا خودتان را تایید کنید',
	'LOGIN_ADMIN_SUCCESS'	=> 'با موفقیت تایید شدید و هم اکنون به کنترل پنل مدیریت انتقال داده می شوید.',
	'LOOK_UP_FORUM'			=> 'انجمنی را انتخاب کنید',
	'LOOK_UP_FORUMS_EXPLAIN'=> 'می توانید بیش از یک انجمن را انتخاب کنید.',

	'MANAGE'				=> 'مدیریت',
	'MENU_TOGGLE'			=> 'مخفی کردن و یا نمایش دادن نوار کناری',
	'MORE'					=> 'بیشتر',			// Not used at the moment
	'MORE_INFORMATION'		=> 'اطلاعات بیشتر »',
	'MOVE_DOWN'				=> 'انتقال به پایین',
	'MOVE_UP'				=> 'انتقال به بالا',

	'NOTIFY'				=> 'اطلاعیه',
	'NO_ADMIN'				=> 'اجازه مدیریت این تالار را ندارید',
	'NO_EMAILS_DEFINED'		=> 'آدرس ایمیل معتبری یافت نشد.',
	'NO_FILES_TO_DELETE'	=> 'فایل های پیوست انتخابی جهت حذف وجود ندارند.',
	'NO_PASSWORD_SUPPLIED'	=> 'برای دسترسی به کنترل پنل مدیریت، باید کلمه عبور خود را وارد کنید.',

	'OFF'					=> 'Off',
	'ON'					=> 'On',

	'PARSE_BBCODE'						=> 'تجزیه BBCode',
	'PARSE_SMILIES'						=> 'تجزیه شکلک ها',
	'PARSE_URLS'						=> 'تجزیه لینک ها',
	'PERMISSIONS_TRANSFERRED'			=> 'سطوح دسترسی انتقال یافت',
	'PERMISSIONS_TRANSFERRED_EXPLAIN'	=> 'اخیرا سطح دسترسی %1$s را دارید. می توانید با سطوح دسترسی این کاربر در تالار گردش کنید. ولی تا زمانی که سطوح دسترسی مدیریت انتقال نشود، به کنترل پنل مدیریت دسترسی نخواهید داشت. در هر زمانی می توانید <a href="%2$s">.<strong>به سطوح دسترسی خود برگردید</strong></a>',
	'PROCEED_TO_ACP'					=> '%sرفتن به کنترل پنل مدیریت%s',

	'RELEASE_ANNOUNCEMENT'		=> 'اطلاعیه کلی',
 	'REMIND'							=> 'یادآوری',
	'REPARSE_LOCK_ERROR'				=> 'عملیات دیگری در حال انجام است.',
	'RESYNC'							=> 'انطباق (syncorise)',

	'RUNNING_TASK'			=> 'اجرای وظیفه: %s.',
	'SELECT_ANONYMOUS'		=> 'اتخاب کاربر بی نام',
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
	'USERNAMES_EXPLAIN'		=> 'هر نام کاربری را در سطر جداگانه ای قرار دهید.',
	'USER_CONTROL_PANEL'	=> 'کنترل پنل کاربر',

	'UPDATE_NEEDED'			=> 'انجمن به روز نمیباشد',
	'UPDATE_NOT_NEEDED'		=> 'انجمن به روز میباشد',
	'UPDATES_AVAILABLE'		=> 'آپدیت جدید:',

	'WARNING'				=> 'هشدار',
));

// PHP info
$lang = array_merge($lang, array(
	'ACP_PHP_INFO_EXPLAIN'	=> 'این صفحه اطلاعات و جزئیات نسخه PHP نصب شده در سرور را نمایش می دهد و شامل ماژول های بارگذاری شده، داده های در دسترس و تنظیمات پیشفرض می باشد. این اطلاعات در حین حل مشکلات ممکن است سودمند واقع شود. ممکن است بعضی از شرکت های هاستینگ اطلاعات نمایشی را به دلایل امنیتب محدود کنند. توصیه می شود که اطلاعات موجود در این صفحه را به هیچکس جزء <a href="http://www.phpbb.com/about/team/">اعضای تیم رسمی</a> بازگو نکنید.',

	'NO_PHPINFO_AVAILABLE'	=> 'اطلاعات پیکربندی PHP شما قابل تشخیص نیست. Phpinfo() برای دلایل امنیتی بسته شده است.',
));

// Logs
$lang = array_merge($lang, array(
	'ACP_ADMIN_LOGS_EXPLAIN'	=> 'این لیست حاوی همگی عملیات صورت گرفته توسط مدیران تالار می باشد. می توانید بر اساس نام کاربری،IP،تاریخ و عملیات صورت گرفته مرتب سازی کنید.  اگر سطح دسترسی کافی داشته باشید می توانید عملیاتی و یا کل رویداد ها را حذف کنید.',
	'ACP_CRITICAL_LOGS_EXPLAIN'	=> 'این لیست حاوی عملیات صورت گرفته در تالار است. از اطلاعات این لیست می توانید در حل کردن بعضی از مشکلات مانند ایمیل های ارسال نشده استفاده کنید. می توانید براساس نام کاربری،IP، تاریخ و عملیات صورت گرفته مرتب سازی کنید. اگر سطح دسترسی کافی داشته باشید می توانید عملیاتی و یا کل رویداد ها را حذف کنید.',
	'ACP_MOD_LOGS_EXPLAIN'		=> 'این لیست حاوی همگی عملیات صورت گرفته در انجمن ها، موضوعات و پست ها می باشد و همچنین حاوی اطلاعات عملیات صورت گرفته توسط مدیران انجمن بر روی کاربران است. این اطلاعات شامل تحریم کاربران نیز می شود. می توانید براساس نام کاربری،IP، تاریخ و عملیات صورت گرفته مرتب سازی کنید. اگر سطوح دسترسی کافی داشته باشید می توانید عملیاتی و یا کل رویداد ها را حذف کنید.',
	'ACP_USERS_LOGS_EXPLAIN'	=> 'این لیست حاوی اطلاعات عملیات صورت گرفته بر روی کاربران و یا توسط کاربران است (شامل گزارش ها،هشدار ها و یادداشت های کاربران)',
	'ALL_ENTRIES'				=> 'همه ی داده ها',

	'DISPLAY_LOG'	=> 'نمایش داده ها از آخر',

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
	'AVATAR_DIR_SIZE'			=> 'حجم کلی نمایه ها ( آواتارها )',

	'BOARD_STARTED'		=> 'تاریخ شروع فعالیت تالار',
	'BOARD_VERSION'		=> 'نسخه تالار',

	'DATABASE_SERVER_INFO'	=> 'سرور پایگاه داده',
	'DATABASE_SIZE'			=> 'حجم پایگاه داده',	

	// Enviroment configuration checks, mbstring related
	'ERROR_MBSTRING_FUNC_OVERLOAD'					=> 'تابع اضافه بار به طور نامناسب پیکربندی شده است',	
	'ERROR_MBSTRING_FUNC_OVERLOAD_EXPLAIN'			=> '<var>mbstring.func_overload</var> باید 0 یا 4 انتخاب شود، می توانید داده را از صفحه <samp>اطلاعات PHP</samp> کنترل کنید.',
	'ERROR_MBSTRING_ENCODING_TRANSLATION'			=> 'Transparent character encoding به طور نامناسب پیکربندی شده است',	
	'ERROR_MBSTRING_ENCODING_TRANSLATION_EXPLAIN'	=> '<var>mbstring.encoding_translation</var> باید به 0 تنظیم شود . می توانید داده را از صفحه <samp>اطلاعات PHP</samp> کنترل کنید.',
	'ERROR_MBSTRING_HTTP_INPUT'						=> 'HTTP input character conversion به طور نامناسب پیکربندی شده است',	
	'ERROR_MBSTRING_HTTP_INPUT_EXPLAIN'				=> '<var>mbstring.http_input</var> باید به <samp>pass</samp> تنظیم شود. می توانید داده را از صفحه <samp>اطلاعات PHP</samp> کنترل کنید.',
	'ERROR_MBSTRING_HTTP_OUTPUT'					=> 'HTTP output character conversion به طور نامناسب پیکربندی شده است',	
	'ERROR_MBSTRING_HTTP_OUTPUT_EXPLAIN'			=> '<var>mbstring.http_output</var>باید به <samp>pass</samp> تنظیم شود. می توانید داده را از صفحه <samp>اطلاعات PHP</samp> کنترل کنید.',

	'FILES_PER_DAY'		=> 'تعداد پیوست در هر روز',
	'FORUM_STATS'		=> 'آمار تالار',

	'GZIP_COMPRESSION'	=> 'GZip فشرده ساز',

	'NO_SEARCH_INDEX'	=> 'سیستم مدیریتی جستجوی انتخاب شده فاقد صفحه اصلی است.<br />لطفا صفحه ای برای “%1$s” در %2$sصفحه جستجو%3$s ایجاد کنید.',
	'NOT_AVAILABLE'		=> 'در دسترس نیست',
	'NUMBER_FILES'		=> 'تعداد پیوست ',
	'NUMBER_POSTS'		=> 'تعداد پست ',
	'NUMBER_TOPICS'		=> 'تعداد موضوعات',
	'NUMBER_USERS'		=> 'تعداد کاربران',
	'NUMBER_ORPHAN'		=> 'پیوست های بدون صاحب',

	'PHP_VERSION'		=> 'PHP نسخه',
	'PHP_VERSION_OLD'	=> 'نسخه PHP این سرور (%1$s) دیگر توسط نسخه های بعدی phpBB پشتیبانی نخواهد شد حداقل نسخه مورد نیاز برای آینده PHP ورژن %2$sاست %3$sجزئیات%4$s',
	'POSTS_PER_DAY'		=> 'تعداد پست در هر روز',

	'PURGE_CACHE'			=> 'پاکسازی نهانگاه',
	'PURGE_CACHE_CONFIRM'	=> 'آیا از پاکسازی نهانگاه مطمئن هستید ؟',
	'PURGE_CACHE_EXPLAIN'	=> 'پاکسازی همه ی نهانگاه ها شامل نهانگاه قالب و پرس و جو ها.',
	'PURGE_CACHE_SUCCESS'	=> 'نهانگاه با موفقیت پاکسازی شد.',

	'PURGE_SESSIONS'			=> 'پاکسازی همه نشست ها',
	'PURGE_SESSIONS_CONFIRM'	=> 'آیا از پاکسازی همه ی نشست ها مطمئن هستید ؟ این کار موجب خروج همه کاربران خواهد شد.',
	'PURGE_SESSIONS_EXPLAIN'	=> 'پاکسازی نشست ها. این کار با خالی کردن جدول نشست ها موجب خروج کاربران خواهد شد.',
	'PURGE_SESSIONS_SUCCESS'	=> 'نشست با موفقیت پاکسازی شد.',

	'RESET_DATE'					=> 'بازنشانی تاریخ شروع فعالیت تالار',
	'RESET_DATE_CONFIRM'			=> 'آیا از بازنشانی تاریخ شروع تالار مطمئن هستید ؟',
	'RESET_DATE_SUCCESS'				=> 'تاریخ شروع تالار با موفقیت بازنشانی شد.',
	'RESET_ONLINE'					=> 'بازنشانی بیشترین تعداد افراد آنلاین',
	'RESET_ONLINE_CONFIRM'			=> 'آیا از بازنشانی بیشترین تعداد افراد آنلاین مطمئن هستید',
	'RESET_ONLINE_SUCCESS'				=> 'بیشتری افراد آنلاین با موفقیت بازنشانی شد.',
	'RESYNC_POSTCOUNTS'				=> 'بازنشانی دوباره شمارنده پست ها',
	'RESYNC_POSTCOUNTS_EXPLAIN'		=> 'فقط به پست هاي موجود رسيدگي مي شود . پست هاي هرس شده حساب نمي شوند.',
	'RESYNC_POSTCOUNTS_CONFIRM'		=> 'آیا از بازنشانی تعداد پست ها اطمینان دارید؟',
	'RESYNC_POSTCOUNTS_SUCCESS'			=> 'بازنشانی پست ها انجام شد.',
	'RESYNC_POST_MARKING'			=> 'بازنشانی دوباره مباحث علامت گذاري شده.',
	'RESYNC_POST_MARKING_CONFIRM'	=> 'آیا از بازنشانی مباحث علامت گذاری شده اطمینان دارید؟',
	'RESYNC_POST_MARKING_EXPLAIN'	=> 'ابتدا علامت گذاري تمام تاپيك ها از بين مي رود و سپس تاپيك هايي كه در 6 ماه گذشته در انها هرگونه فعاليتي ديده شده علامت گذاري مي شوند.',
	'RESYNC_POST_MARKING_SUCCESS'	=> 'بازنشانی مباحث علامت گذاری شده انجام شد',
	'RESYNC_STATS'					=> 'بازنشانی اطلاعات و آمار',
	'RESYNC_STATS_CONFIRM'			=> 'آیا از بازنشانی اطلاعات و آمار اطمینان دارید؟',
	'RESYNC_STATS_EXPLAIN'			=> 'باز نشانی تعداد پست ها ، مباحث ، اعضا و فایل ها',
	'RESYNC_STATS_SUCCESS'			=> 'بازنشانی اطلاعات و آمار انجام شد.',
	'RUN'							=> 'اجرا',

	'STATISTIC'					=> 'آمار',
	'STATISTIC_RESYNC_OPTIONS'	=> 'مجدد سازی و بازنشانی امار',

	'TIMEZONE_INVALID'	=> 'زمان محلی انتخابی معتبر نیست.',
	'TIMEZONE_SELECTED'	=> '(انتخاب کنونی)',
	'TOPICS_PER_DAY'	=> 'موضوعات در هر روز',

	'UPLOAD_DIR_SIZE'	=> 'حجم پیوست های ارسال شده',
	'USERS_PER_DAY'		=> 'تعداد کاربران در هر روز',

	'VALUE'						=> 'مقدار',
	'VERSIONCHECK_FAIL'			=> 'دریافت آخرین اطلاعات نسخه موفقیت آمیز نبود!',
	'VERSIONCHECK_FORCE_UPDATE'	=> 'کنترل دوباره ی نسخه',
    'VERSION_CHECK'				=> 'بررسی نسخه',
	'VERSION_CHECK_EXPLAIN'		=> 'بررسی به روز بودن نسخه انجمن',
	'VERSIONCHECK_INVALID_ENTRY'	=> 'اطلاعات مربوط به آخرین نسخه شامل مطلب پشتیبانی نشده است.',
	'VERSIONCHECK_INVALID_URL'		=> 'اطلاعات آخرین نسخه شامل آدرس لینک نا معتبر است.',
	'VERSIONCHECK_INVALID_VERSION'	=> 'اطلاعات مربوط به آخرین نسخه شامل نسخه نامربوط است.',
	'VERSION_NOT_UP_TO_DATE_ACP'	=> 'نسخه انجمن شما به روز نمیباشد<br />در پایین لینک اطلاعیه وجود دارد, که شامل اطلاعات مربوط به آپدیت جدید میباشد',
	'VERSION_NOT_UP_TO_DATE_TITLE'	=> 'نسخه انجمن شما به روز نمیباشد',
	'VERSION_UP_TO_DATE_ACP'	=> 'نسخه انجمن شما به روز میباشد. آپدیت جدیدی وجود ندارد.',
	'VIEW_ADMIN_LOG'			=> 'مشاهده رویداد های مدیریت',
	'VIEW_INACTIVE_USERS'		=> 'مشاهده کاربران غیرفعال',

	'WELCOME_PHPBB'			=> 'به phpBB خوش آمدید',
	'WRITABLE_CONFIG'		=> 'فایل پیکربندی (config.php) قابل نوشتن است. به شدّت توصیه می کنیم که سطح دسترسی آن را به 640 و یا حداقل به 644 تغییر دهید. (برای مثال : <a href="http://en.wikipedia.org/wiki/Chmod" rel="external">chmod</a> 640 config.php).',
));

// Inactive Users
$lang = array_merge($lang, array(
	'INACTIVE_DATE'					=> 'تاریخ عدم فعالیت',
	'INACTIVE_REASON'				=> 'دلیل',
	'INACTIVE_REASON_MANUAL'		=> 'اکانت توسط مدیر غیرفعال شده است',
	'INACTIVE_REASON_PROFILE'		=> 'جزئیات پروفایل تغییر یافت',
	'INACTIVE_REASON_REGISTER'		=> 'اکانت هایی که به تازگی ثبت نام کرده اند',
	'INACTIVE_REASON_REMIND'		=> 'کاربر مجبور به فعال سازی دوباره اکانت خود خواهد شد.',
	'INACTIVE_REASON_UNKNOWN'		=> 'نامعلوم',
	'INACTIVE_USERS'				=> 'کاربرن غیرفعال',
	'INACTIVE_USERS_EXPLAIN'		=> 'این لیستی از کاربران ثبت نام شده می باشد که اخیرا غیرفعال هستند. می توانید این کاربران را فعال،حذف کنید و یا به آنها ایمیلی به منظور یادآوری ارسال کنید.',
	'INACTIVE_USERS_EXPLAIN_INDEX'	=> 'این لیستی از 10 کاربر اخیر ثبت نام کرده می باشد که اکانت آن ها غیرفعال هست. لیست کامل در منوی مربوطه و یا در لینک زیر در دسترس می باشد که در آن جا می توانید این کاربران را فعال و یا حذف کنید و همچنین می توانید به آنها ایمیل یادآوری ارسال کنید.',

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
	'EXPLAIN_SEND_STATISTICS'	=> 'لطفا اطلاعات سرور و پیکربندی تالارتان را به phpBB به منظور آنالیز آنها،ارسال کنید. تمام اطلاعاتی که ممکن است حاوی هویت شما باشد،حذف خواهند شد. - اطلاعات کاملا <strong>بی نام</strong>خواهند بود. این اطلاعات برای عموم قابل دسترسی است و ما براساس این اطلاعات در مورد نسخه های بعدی phpBB تصمیم گیری می کنیم.همچنین این آمار با پروژه PHP که زبان برنامه نویسی phpBB هست نیز به اشتراک گذاشته خواهد شد.',
	'EXPLAIN_SHOW_STATISTICS'	=> 'با استفاده از دکمه زیر می توانید تمامی اطلاعاتی را که منتقل خواهند شد، ببینید.',
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
	'LOG_ACL_ADD_GROUP_GLOBAL_U_'		=> '<strong>سطوح دسترسی گروه ها ویرایش و یا اضافه شدند.</strong><br />» %s',
	'LOG_ACL_ADD_USER_GLOBAL_M_'		=> '<strong>سطوح دسترسی مدیریت سراسری کاربران ویرایش و یا اضافه شدند.</strong><br />» %s',
	'LOG_ACL_ADD_GROUP_GLOBAL_M_'		=> '<strong>سطوح دسترسی مدیریت سراسری گروه ها ویرایش و یا اضافه شدند.</strong><br />» %s',
	'LOG_ACL_ADD_USER_GLOBAL_A_'		=> '<strong>سطوح دسترسی مدیریت کاربران ویرایش و یا اضافه شدند.</strong><br />» %s',
	'LOG_ACL_ADD_GROUP_GLOBAL_A_'		=> '<strong>سطوح دسترسی مدیریت گروه ها ویرایش و یا اضافه شدند.</strong><br />» %s',

	'LOG_ACL_ADD_ADMIN_GLOBAL_A_'		=> '<strong>مدیران ویرایش و یا اضافه شدند </strong><br />» %s',
	'LOG_ACL_ADD_MOD_GLOBAL_M_'			=> '<strong>مدیران انجمن ها ویرایش و یا اضافه شدند.</strong><br />» %s',

	'LOG_ACL_ADD_USER_LOCAL_F_'			=> '<strong>دسترسی کاربران به انجمن ویرایش و یا اضافه شدند</strong> from %1$s<br />» %2$s',
	'LOG_ACL_ADD_USER_LOCAL_M_'			=> '<strong>دسترسی کاربران به مدیریت انجمن ویرایش و یا اضافه شدند</strong> from %1$s<br />» %2$s',
	'LOG_ACL_ADD_GROUP_LOCAL_F_'		=> '<strong>دسترسی گروه ها به انجمن ویرایش و یا اضافه شدند</strong> from %1$s<br />» %2$s',
	'LOG_ACL_ADD_GROUP_LOCAL_M_'		=> '<strong>دسترسی گروه ها به مدیریت انجمن ویرایش و یا اضافه شدند</strong> from %1$s<br />» %2$s',

	'LOG_ACL_ADD_MOD_LOCAL_M_'			=> '<strong>مدیران انجمن ویرایش و یا اضافه شدند</strong> از %1$s<br />» %2$s',
	'LOG_ACL_ADD_FORUM_LOCAL_F_'		=> '<strong>سطوح دسترسی انجمن ویرایش و یا اضافه شدند</strong> از %1$s<br />» %2$s',

	'LOG_ACL_DEL_ADMIN_GLOBAL_A_'		=> '<strong>حذف مدیران</strong><br />» %s',
	'LOG_ACL_DEL_MOD_GLOBAL_M_'			=> '<strong>حذف مدیران انجمن ها</strong><br />» %s',
	'LOG_ACL_DEL_MOD_LOCAL_M_'			=> '<strong>حذف مدیران انجمن</strong> از %1$s<br />» %2$s',
	'LOG_ACL_DEL_FORUM_LOCAL_F_'		=> '<strong>حذف سطوح دسترسی کاربران/گروه ها به انجمن</strong> از %1$s<br />» %2$s',

	'LOG_ACL_TRANSFER_PERMISSIONS'		=> '<strong>سطوح دسترسی از</strong><br />» %s منتقل شدند.',
	'LOG_ACL_RESTORE_PERMISSIONS'		=> '<strong>سطوح دسترسی بازنگری شدند</strong><br />» %s',

	'LOG_ADMIN_AUTH_FAIL'		=> '<strong>ورود به مدیریت موفقیت آمیز نبود</strong>',
	'LOG_ADMIN_AUTH_SUCCESS'	=> '<strong>ورود به مدیریت موفقیت آمیز بود</strong>',

	'LOG_ATTACHMENTS_DELETED'	=> '<strong>پیوست های کاربر حذف شدند</strong><br />» %s',

	'LOG_ATTACH_EXT_ADD'		=> '<strong>پسوندها پیوست ویرایش و یا حذف شدند</strong><br />» %s',
	'LOG_ATTACH_EXT_DEL'		=> '<strong>پسوندها پیوست حذف شدند</strong><br />» %s',
	'LOG_ATTACH_EXT_UPDATE'		=> '<strong>پسوندها پیوست بروزرسانی شدند</strong><br />» %s',
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

	'LOG_BOT_ADDED'		=> '<strong>روبات جدیدی اضافه شد</strong><br />» %s',
	'LOG_BOT_DELETE'	=> '<strong>روبات حذف شد</strong><br />» %s',
	'LOG_BOT_UPDATED'	=> '<strong>روبات کنونی بروزرسانی شد</strong><br />» %s',

	'LOG_CLEAR_ADMIN'		=> '<strong>رویدادهای مدیریت پاک شدند</strong>',
	'LOG_CLEAR_CRITICAL'	=> '<strong>رویدادهای خطاها پاک شدند</strong>',
	'LOG_CLEAR_MOD'			=> '<strong>رویدادهای مدیران انجمن پاک شدند</strong>',
	'LOG_CLEAR_USER'		=> '<strong>رویدادهای کاربر پاک شد</strong><br />» %s',
	'LOG_CLEAR_USERS'		=> '<strong>رویدادهای کاربران پاک شدند</strong>',

	'LOG_CONFIG_ATTACH'			=> '<strong>تنظیمات پیوست تغییر یافتند</strong>',
	'LOG_CONFIG_AUTH'			=> '<strong>تنظیمات تایید تغییر یافتند</strong>',
	'LOG_CONFIG_AVATAR'			=> '<strong>تنظیمات آواتار تغییر یافتند</strong>',
	'LOG_CONFIG_COOKIE'			=> '<strong>تنظیمات کوکی تغییر یافتند</strong>',
	'LOG_CONFIG_EMAIL'			=> '<strong>تنظیمات ایمیل تغییر یافتند</strong>',
	'LOG_CONFIG_FEATURES'		=> '<strong>ویژگی های تالار تغییر یافتند</strong>',
	'LOG_CONFIG_LOAD'			=> '<strong>تنظیمات بارگذاری تغییر یافتند</strong>',
	'LOG_CONFIG_MESSAGE'		=> '<strong>تنظیمات پیغام های خصوصی تغییر یافتند</strong>',
	'LOG_CONFIG_POST'			=> '<strong>تنظیمات پست تغییر یافتند</strong>',
	'LOG_CONFIG_REGISTRATION'	=> '<strong>تنظیمات ثبت نام کاربران تغییر یافتند</strong>',
	'LOG_CONFIG_FEED'			=> '<strong>تنظیمات خوراک تغییر یافتند</strong>',
	'LOG_CONFIG_SEARCH'			=> '<strong>تنظیمات جستجو تغییر یافتند</strong>',
	'LOG_CONFIG_SECURITY'		=> '<strong>تنظیمات امنیت تغییر یافتند</strong>',
	'LOG_CONFIG_SERVER'			=> '<strong>تنظیمات سرور تغییر یافتند</strong>',
	'LOG_CONFIG_SETTINGS'		=> '<strong>تنظیمات تالار تغییر یافتند</strong>',
	'LOG_CONFIG_SIGNATURE'		=> '<strong>تنظیمات امضا تغییر یافتند</strong>',
	'LOG_CONFIG_VISUAL'			=> '<strong>تنظیمات کد تایید تغییر یافتند</strong>',

	'LOG_APPROVE_TOPIC'			=> '<strong>موضوع تایید شد</strong><br />» %s',
	'LOG_BUMP_TOPIC'			=> '<strong>موضوع کاربر بامپ (bump) شد</strong><br />» %s',
	'LOG_DELETE_POST'			=> '<strong>حذف پست “%1$s” نوشته شده توسط “%2$s” به دلیل</strong><br />» %3$s',
	'LOG_DELETE_SHADOW_TOPIC'	=> '<strong>موضوع سایه دار حذف شد</strong><br />» %s',
	'LOG_DELETE_TOPIC'			=> '<strong>موضوع حذف شد</strong><br />» %2$s',
	'LOG_DELETE_TOPIC'			=> '<strong>موضوع حذف شد “%1$s” توسط “%2$s” به دلیل</strong><br />» %3$s',
	'LOG_FORK'					=> '<strong>موضوع کپی شد</strong><br />» from %s',
	'LOG_LOCK'					=> '<strong>موضوع قفل شد</strong><br />» %s',
	'LOG_LOCK_POST'				=> '<strong>پست قفل شد</strong><br />» %s',
	'LOG_MERGE'					=> '<strong>پست ها در این موضوع ادغام شدند</strong><br />» %s',
	'LOG_MOVE'					=> '<strong>موضوع انتقال داده شد</strong><br />» از %1$s به %2$s',
	'LOG_MOVED_TOPIC'			=> '<strong>انتقال موضوع</strong><br />» %s',
	'LOG_PM_REPORT_CLOSED'		=> '<strong>گزارش پیغام خصوصی بسته شد</strong><br />» %s',
	'LOG_PM_REPORT_DELETED'		=> '<strong>گزارش پیغام خصوصی حذف شد</strong><br />» %s',

	'LOG_POST_APPROVED'			=> '<strong>پست تایید شد</strong><br />» %s',
	'LOG_POST_DISAPPROVED'		=> '<strong>پست تایید نشده “%1$s” نوشته شده توسط “%3$s” به دلیل</strong><br />» %2$s',
	'LOG_POST_EDITED'			=> '<strong>پست ویرایش شد “%1$s” نوشته شده توسط “%2$s” به دلیل</strong><br />» %3$s',
	'LOG_POST_RESTORED'			=> '<strong>پست بازیابی شد</strong><br />» %s',
	'LOG_REPORT_CLOSED'			=> '<strong>گزارش بسته شد</strong><br />» %s',
	'LOG_REPORT_DELETED'		=> '<strong>گزارش حذف شد</strong><br />» %s',
	'LOG_RESTORE_TOPIC'			=> '<strong>موضوع “%1$s بازیابی شد. نوشته شده توسط</strong><br />» %2$s',
	'LOG_SOFTDELETE_POST'		=> '<strong>حذف موقت پست “%1$s” نوشته شده توسط “%2$s” به دلیل</strong><br />» %3$s',
	'LOG_SOFTDELETE_TOPIC'		=> '<strong>حذف موقت موضوع “%1$s” نوشته شده توسط “%2$s” به دلیل</strong><br />» %3$s',
	'LOG_SPLIT_DESTINATION'		=> '<strong>پست های دوبخشی منقل شدند</strong><br />» به %s',
	'LOG_SPLIT_SOURCE'			=> '<strong>پست های دوبخشی</strong><br />» از %s',

	'LOG_TOPIC_APPROVED'		=> '<strong>موضوع تایید شد</strong><br />» %s',
	'LOG_TOPIC_RESTORED'		=> '<strong>موضوع بازیابی شد</strong><br />» %s',
	'LOG_TOPIC_DISAPPROVED'		=> '<strong>موضوع تایید نشد “%1$s” نوشته شده توسط “%3$s” به دلیل</strong><br />» %2$s',
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
	'LOG_FORUM_DEL_FORUMS'					=> '<strong>انجمن و زیرانجمن های آن حذف شدند</strong><br />» %s',
	'LOG_FORUM_DEL_MOVE_FORUMS'				=> '<strong>انجمن حذف شد و زیرانجمن های آن </strong> به %1$s منتقل شدند<br />» %2$s',
	'LOG_FORUM_DEL_MOVE_POSTS'				=> '<strong>انجمن حذف شد و پست های آن </strong> به %1$s منتقل شدند<br />» %2$s',
	'LOG_FORUM_DEL_MOVE_POSTS_FORUMS'		=> '<strong>انجمن و زیرانجمن های آن حذف شدند و پست ها</strong> به %1$s منتقل شدند<br />» %2$s',
	'LOG_FORUM_DEL_MOVE_POSTS_MOVE_FORUMS'	=> '<strong>انجمن حذف شد و پست ها</strong> به %1$s منتقل شدند <strong>و زیرانجمن ها نیز</strong> به %2$s منتقل شدند<br />» %3$s',
	'LOG_FORUM_DEL_POSTS'					=> '<strong>انجمن و پست های آن حذف شدند</strong><br />» %s',
	'LOG_FORUM_DEL_POSTS_FORUMS'			=> '<strong>انجمن همراه با زیرانجمن ها و پست های آن حذف شدند </strong><br />» %s',
	'LOG_FORUM_DEL_POSTS_MOVE_FORUMS'		=> '<strong>انجمن و پست های ان حذف شدند و زیرانجمن های آن نیز</strong> به %1$s منتقل شدند<br />» %2$s',
	'LOG_FORUM_EDIT'						=> '<strong>جزئیات انجمن ویرایش شد</strong><br />» %s',
	'LOG_FORUM_MOVE_DOWN'					=> '<strong>انجمن</strong> %1$s به <strong>زیر</strong> %2$s منقل شد',
	'LOG_FORUM_MOVE_UP'						=> '<strong>انجمن</strong> %1$s به <strong>بالای</strong> %2$s منقل شد',
	'LOG_FORUM_SYNC'						=> '<strong>انجمن منطبق (resync) شد</strong><br />» %s',

	'LOG_GENERAL_ERROR'	=> '<strong>خطایی عمومی رخ داد</strong>: %1$s <br />» %2$s',

	'LOG_GROUP_CREATED'		=> '<strong>گروه کاربری جدیدی ایجاد شد</strong><br />» %s',
	'LOG_GROUP_DEFAULTS'	=> '<strong>گروه “%1$s” به عنوان گروه پیشفرض برای کاربران تایید شد</strong><br />» %2$s',
	'LOG_GROUP_DELETE'		=> '<strong>گروه کاربری حدف شد</strong><br />» %s',
	'LOG_GROUP_DEMOTED'		=> '<strong>رتبه رهبران گروه کاربری در</strong> %1$s تنزیل شد<br />» %2$s',
	'LOG_GROUP_PROMOTED'	=> '<strong>رتبه کاربران  گروه کاربری در </strong> %1$s ارتقاء یافت<br />» %2$s',
	'LOG_GROUP_REMOVE'		=> '<strong>اعضا از گروه کاربری</strong> %1$s حذف شدند<br />» %2$s',
	'LOG_GROUP_UPDATED'		=> '<strong>جزئیات گروه کاربری بروزرسانی شد</strong><br />» %s',
	'LOG_MODS_ADDED'		=> '<strong>رهبران جدید به گروه کاربری </strong> %1$s اضافه شدند<br />» %2$s',
	'LOG_USERS_ADDED'		=> '<strong>اعضای جدید به گروه کاربری</strong> %1$s اضافه شدند<br />» %2$s',
	'LOG_USERS_APPROVED'	=> '<strong>اعضا در گروه کاربری</strong> %1$s تایید شدند<br />» %2$s',
	'LOG_USERS_PENDING'		=> '<strong>کاربران دخواست عضویت در گروه “%1$s” ارسال کرده اند که نیازمند تایید می باشد</strong><br />» %2$s',

	'LOG_IMAGE_GENERATION_ERROR'	=> '<strong>خطایی در حین ایجاد تصویر روی داد</strong><br />» خطا رد %1$s و خط %2$s: %3$s',

	'LOG_INACTIVE_ACTIVATE'	=> '<strong>کاربران غیرفعال،فعال شدند</strong><br />» %s',
	'LOG_INACTIVE_DELETE'	=> '<strong>کاربران غیرفعال، حذف شدند</strong><br />» %s',
	'LOG_INACTIVE_REMIND'	=> '<strong>ایمیل یادآوری به کاربران غیرفعال ارسال شد</strong><br />» %s',
	'LOG_INSTALL_CONVERTED'	=> '<strong>از %1$s به phpBB %2$s تبدیل شد</strong>',
	'LOG_INSTALL_INSTALLED'	=> '<strong>phpBB %s نصب شد</strong>',

	'LOG_IP_BROWSER_FORWARDED_CHECK'	=> '<strong>کنترل نشست IP،مرورگر و X_FORWARDED_FOR شکست خورد</strong><br />»IP کاربر “<em>%1$s</em>” در IP نشست “<em>%2$s</em>” کنترل شد , حلقه مرورگر کاربر “<em>%3$s</em>” در نشست حلقه مرورگر “<em>%4$s</em>” کنترل شد و X_FORWARDED_FOR string کاربر “<em>%5$s</em>” در حلقه نشست X_FORWARDED_FOR “<em>%6$s</em>” کنترل شد.',

	'LOG_JAB_CHANGED'			=> '<strong>اکانت Jabeer تغییر یافت</strong>',
	'LOG_JAB_PASSCHG'			=> '<strong>کلمه عبور Jabber تغییر یافت</strong>',
	'LOG_JAB_REGISTER'			=> '<strong>اکانت Jabber ثبت نام شد</strong>',
	'LOG_JAB_SETTINGS_CHANGED'	=> '<strong>تنظیمات Jabber تغییر یافت</strong>',

	'LOG_LANGUAGE_PACK_DELETED'		=> '<strong>بسته زبانی حذف شد</strong><br />» %s',
	'LOG_LANGUAGE_PACK_INSTALLED'	=> '<strong>بسته زبانی نصب شد</strong><br />» %s',
	'LOG_LANGUAGE_PACK_UPDATED'		=> '<strong>جزئیات بسته زبانی بروزرسانی شد</strong><br />» %s',
	'LOG_LANGUAGE_FILE_REPLACED'	=> '<strong>فایل های زبان جایگزین شدند</strong><br />» %s',
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

	'LOG_PROFILE_FIELD_ACTIVATE'	=> '<strong>فیلد پروفایل فعال شد</strong><br />» %s',
	'LOG_PROFILE_FIELD_CREATE'		=> '<strong>فیلد پروفایل اضافه شد</strong><br />» %s',
	'LOG_PROFILE_FIELD_DEACTIVATE'	=> '<strong>فیلد پروفایل غیرفعال شد</strong><br />» %s',
	'LOG_PROFILE_FIELD_EDIT'		=> '<strong>فیلد پروفایل تغییر یافت</strong><br />» %s',
	'LOG_PROFILE_FIELD_REMOVED'		=> '<strong>فیلد پروفایل حذف شد</strong><br />» %s',

	'LOG_PRUNE'					=> '<strong>انجمن ها هرس شدند</strong><br />» %s',
	'LOG_AUTO_PRUNE'			=> '<strong>انجمن ها به صورت خودکار هرس شدند</strong><br />» %s',
	'LOG_PRUNE_SHADOW'		=> '<strong>سایه مباحث به صورت خودکار هرس شدند.</strong><br />» %s',
	'LOG_PRUNE_USER_DEAC'		=> '<strong>کاربران غیرفعال شدند</strong><br />» %s',
	'LOG_PRUNE_USER_DEL_DEL'	=> '<strong>کاربران هرس شده و پست ها حذف شدند </strong><br />» %s',
	'LOG_PRUNE_USER_DEL_ANON'	=> '<strong>کاربران هرس شده و پست ها حفظ شدند</strong><br />» %s',

	'LOG_PURGE_CACHE'			=> '<strong>پاکسازی شدند cach</strong>',
	'LOG_PURGE_SESSIONS'		=> '<strong>نشست ها پاکسازی شدند</strong>',

	'LOG_RANK_ADDED'		=> '<strong>رتبه جدیدی اضافه شد</strong><br />» %s',
	'LOG_RANK_REMOVED'		=> '<strong>رتبه حذف شد</strong><br />» %s',
	'LOG_RANK_UPDATED'		=> '<strong>رتبه بروزرسانی شد</strong><br />» %s',

	'LOG_REASON_ADDED'		=> '<strong>گزارش/دلیل تحریم اضافه شد</strong><br />» %s',
	'LOG_REASON_REMOVED'	=> '<strong>گزارش/دلیل تحریم حذف شد</strong><br />» %s',
	'LOG_REASON_UPDATED'	=> '<strong>گزارش/دلیل تحریم بروزرسانی</strong><br />» %s',

	'LOG_REFERER_INVALID'		=> '<strong>تایید رجوع کننده موفقیت آمیز نبود</strong><br />»رجوع کننده “<em>%1$s</em>” بود که درخواست آن رد شده و نشست بسته شد',
	'LOG_RESET_DATE'			=> '<strong>تاریخ شروع فعالیت تالار بازشماری شد</strong>',
	'LOG_RESET_ONLINE'			=> '<strong>بیشترین تعداد افراد آنلاین بازشماری شد</strong>',
	'LOG_RESYNC_FILES_STATS'	=> '<strong>آمار فایل ها بازنشانی شد</strong>',
	'LOG_RESYNC_POSTCOUNTS'		=> '<strong>شمارنده پست کاربر بازشماری شد</strong>',
	'LOG_RESYNC_POST_MARKING'	=> '<strong>موضوعات نقطه گذاری شده بازشماری شد. </strong>',
	'LOG_RESYNC_STATS'			=> '<strong>آمار کاربران،موضوعات و پست ها بازشماری شدند.</strong>',

	'LOG_SEARCH_INDEX_CREATED'	=> '<strong>شاخص جستجو ایجاد شد</strong><br />» %s',
	'LOG_SEARCH_INDEX_REMOVED'	=> '<strong>شاخص جستجو حذف شد</strong><br />» %s',
	'LOG_SPHINX_ERROR'			=> '<strong>Sphinx خطا</strong><br />» %s',
	'LOG_STYLE_ADD'				=> '<strong>قالب جدید اضافه شد</strong><br />» %s',
	'LOG_STYLE_DELETE'			=> '<strong>قالب حذف شد</strong><br />» %s',
	'LOG_STYLE_EDIT_DETAILS'	=> '<strong>Edited style</strong><br />» %s',
	'LOG_STYLE_EXPORT'			=> '<strong>Exported style</strong><br />» %s',

	// @deprecated 3.1
	'LOG_TEMPLATE_ADD_DB'			=> '<strong>مجموعه قالب جدیدی به پایگاه داده اضافه شد</strong><br />» %s',
	// @deprecated 3.1
	'LOG_TEMPLATE_ADD_FS'			=> '<strong>مجموعه قالب جدیدی به سیستم فایل اضافه شد</strong><br />» %s',
	'LOG_TEMPLATE_CACHE_CLEARED'	=> '<strong>نسخه های cach پایگاه داده برای مجموعه فالب، حذف شد <em>%1$s</em></strong><br />» %2$s',
	'LOG_TEMPLATE_DELETE'			=> '<strong>مجموعه قالب حذف شد</strong><br />» %s',
	'LOG_TEMPLATE_EDIT'				=> '<strong>مجموعه قالب <em>%1$s</em> ویرایش شد</strong><br />» %2$s',
	'LOG_TEMPLATE_EDIT_DETAILS'		=> '<strong>جزئیات قالب ویرایش شد</strong><br />» %s',
	'LOG_TEMPLATE_EXPORT'			=> '<strong>مجموعه قالب به بیرون برده شد</strong><br />» %s',
	// @deprecated 3.1
	'LOG_TEMPLATE_REFRESHED'		=> '<strong>مجموعه قالب بروزرسانی شد</strong><br />» %s',

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
	'LOG_THEME_REFRESHED'		=> '<strong>تم بروزرسانی شد</strong><br />» %s',

	'LOG_UPDATE_DATABASE'	=> '<strong>پایگاه داده از نسخه %1$s به نسخه %2$s بروزرسانی شد</strong>',
	'LOG_UPDATE_PHPBB'		=> '<strong>phpBB از نسخه %1$s به نسخه %2$s بروزرسانی شد.</strong>',

	'LOG_USER_ACTIVE'		=> '<strong>کاربر فعال شد</strong><br />» %s',
	'LOG_USER_BAN_USER'		=> '<strong>کاربر توسط مدیریت اعضا</strong> به دلیل “<em>%1$s</em>” تحریم شد <br />» %2$s',
	'LOG_USER_BAN_IP'		=> '<strong>IP توسط مدیریت اعضا</strong> به دلیل “<em>%1$s</em>” تحریم شد <br />» %2$s',
	'LOG_USER_BAN_EMAIL'	=> '<strong>ایمیل توسط مدیریت اعضا</strong> به دلیل “<em>%1$s</em>” تحریم شد <br />» %2$s',
	'LOG_USER_DELETED'		=> '<strong>کاربر حذف شد</strong><br />» %s',
	'LOG_USER_DEL_ATTACH'	=> '<strong>همه ی پیوست های متعلق به کاربر حذف شد</strong><br />» %s',
	'LOG_USER_DEL_AVATAR'	=> '<strong>نمایه کاربر حذف شد</strong><br />» %s',
	'LOG_USER_DEL_OUTBOX'	=> '<strong>صندوق خروجی کاربر تخلیه شد</strong><br />» %s',
	'LOG_USER_DEL_POSTS'	=> '<strong>همه ی پست های متعلق به کاربر حذف شد</strong><br />» %s',
	'LOG_USER_DEL_SIG'		=> '<strong>امضای کاربر حذف شد</strong><br />» %s',
	'LOG_USER_INACTIVE'		=> '<strong>کاربر غیرفعال شد</strong><br />» %s',
	'LOG_USER_MOVE_POSTS'	=> '<strong>پست های کاربر منتقل شدند</strong><br />» پست های متعلق به “%1$s” به انجمن “%2$s” منتقل شدند',
	'LOG_USER_NEW_PASSWORD'	=> '<strong>کلمه عبور کاربر تغییر یافت</strong><br />» %s',
	'LOG_USER_REACTIVATE'	=> '<strong>اکنون کاربر مجبور به فعال سازی دوباره اکانت خود می باشد</strong><br />» %s',
	'LOG_USER_REMOVED_NR'	=> '<strong>بیان کاربر جدید از کاربر حذف شد</strong><br />» %s',

	'LOG_USER_UPDATE_EMAIL'	=> '<strong>ایمیل کاربر “%1$s” </strong><br />» از “%2$s” به “%3$s” تغییر یافت',
	'LOG_USER_UPDATE_NAME'	=> '<strong>نام کاربری</strong><br />» از “%1$s” به “%2$s” تغییر یافت',
	'LOG_USER_USER_UPDATE'	=> '<strong>جزئیات کاربر بروزرسانی شد</strong><br />» %s',

	'LOG_USER_ACTIVE_USER'		=> '<strong>اکانت کاربر فعال شد</strong>',
	'LOG_USER_DEL_AVATAR_USER'	=> '<strong>نمایه کاربر حذف شد</strong>',
	'LOG_USER_DEL_SIG_USER'		=> '<strong>امضای کاربر حذف شد</strong>',
	'LOG_USER_FEEDBACK'			=> '<strong>بازخورد به کاربر اضافه شد</strong><br />» %s',
	'LOG_USER_GENERAL'			=> '<strong>داده اضافه شد :</strong><br />» %s',
	'LOG_USER_INACTIVE_USER'	=> '<strong>اکانت کاربر غیرفعال شد</strong>',
	'LOG_USER_LOCK'				=> '<strong>کاربر موضوع خود را قفل کرد</strong><br />» %s',
	'LOG_USER_MOVE_POSTS_USER'	=> '<strong>همه پست ها به انجمن</strong>» %s منتقل شدند',
	'LOG_USER_REACTIVATE_USER'	=> '<strong>اکنون کاربر مجبور به فعال سازی مجدد اکانت خود می باشد</strong>',
	'LOG_USER_UNLOCK'			=> '<strong>کاربر قفل موضوع خود را باز کرد</strong><br />» %s',
	'LOG_USER_WARNING'			=> '<strong>هشدار به کاربر اضافه شد</strong><br />» %s',
	'LOG_USER_WARNING_BODY'		=> '<strong>هشدار مقابل به کاربر ارسال شد</strong><br />» %s',

	'LOG_USER_GROUP_CHANGE'			=> '<strong>کاربر گروه پیشفرضش را تغییر داد</strong><br />» %s',
	'LOG_USER_GROUP_DEMOTE'			=> '<strong>رتبه رهبری کاربر در گروه کاربری تنزل یافت</strong><br />» %s',
	'LOG_USER_GROUP_JOIN'			=> '<strong>کاربر در گروه عضو شد</strong><br />» %s',
	'LOG_USER_GROUP_JOIN_PENDING'	=> '<strong>کاربر در گروه عضو شد و نیازمند تایید است</strong><br />» %s',
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
	'LOG_EXT_DISABLE'	=> '<strong>افزونه غیر فعال شد</strong><br />» %s',
	'LOG_EXT_PURGE'		=> '<strong>داده های افزونه حذف شد.</strong><br />» %s',
	'LOG_EXT_UPDATE'	=> '<strong>افزونه به روز میباشد</strong><br />» %s',
));
