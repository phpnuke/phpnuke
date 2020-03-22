<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * phpBB 3.2.X Project - Persian Translation
 * Translators: PHP-BB.IR Group Meis@M Nobari
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

// Common installer pages
$lang = array_merge($lang, array(
	'INSTALL_PANEL'	=> 'پنل نصب',
	'SELECT_LANG'	=> 'انتخاب زبان',

	'STAGE_INSTALL'	=> 'نصب phpBB',

	// Introduction page
	'INTRODUCTION_TITLE'	=> 'معرفی',
	'INTRODUCTION_BODY'		=> 'به phpBB3 خوش آمدید !<br /><br />phpBB™ محبوب ترین تالار بازمتن در سطح جهانی است. phpBB3 آخرین محصول بسته ای است که ساخت آن از سال 2000 شروع شده است. phpBB3 مانند اجدادش،غنی از امکانات است و کاربر پسند می باشد، phpBB3 به طور کامل توسط تیم پشتیبانی phpBB پشتیبانی میشود. phpBB3 تا حد زیادی همه امکاناتی را که phpBB2 را محبوب کرده بود را ارتقاء داده و بعضی امکاناتی را که در نسخه های قبلی وجود نداشت،اضافه کرده است. امیدواریم فراتر از انتظارات شما را برآورده کند.<br /><br />این سیستم نصب شما را در نصب phpBB3 ، بروزرسانی آن به آخرین نسخه phpBB3 و تبدیل سایر سیستم های تالارگفتگو (شامل phpBB2 ) به phpBB3 راهنمایی خواهد کرد. برای اطلاعات بیشتر به  <a href="../docs/INSTALL.html"> راهنمای نصب</a> مراجعه کنید. <br /><br />برای خواندن لایسنس phpBB لطفا گزینه مربوط را در منوی کناری انتخاب کنید. برای ادامه از قسمت بالا گزینه مناسب را انتخاب کنید.(نصب یا تبدیل)',
	
	// Support page
	'SUPPORT_TITLE'		=> 'پشتیبانی',
	'SUPPORT_BODY'		=> 'سیستم phpBB پشتیبانی کامل و بدون هزینه ای از نسخه اخیر را که شامل موارد زیر است بر عهده میگیرد:</p><ul><li>نصب</li><li>پیکربندی</li><li>سوالات تخصصی</li><li>مشکلات مربوط به باگ ها و نواقص نرم افزار</li><li>ارتقا از نسخه های کاندید به نسخه های پایدار</li><li>تبدیل از نسخه 2.0 به نسخه 3</li><li>تبدیل سایر انجمن سازها و سیستم ها به این سیستم (مشاهده کنید <a href="https://www.phpbb.com/community/viewforum.php?f=486">تالار تبدیل ها</a>)</li></ul><p>ماکاربرانی را که از نسخه های بتا استفاده میکنند تشویق به استفاده از نسخه های پایدار میکنیم</p><h2>افزونه ها / قالب ها</h2><p>مسائل و مشکلات مربوط به افزونه ها <a href="https://www.phpbb.com/community/viewforum.php?f=451">تالار افزونه ها</a>.<br />مسائل و مشکلات مربوط به قالب ها <a href="https://www.phpbb.com/community/viewforum.php?f=471">تالار قالب ها</a>.<br /><br />چنانچه سوالاتی درباره پیکج نرم افزار دارید هم میتوانید بپرسید.</p><h2>بخش پشتیبانی</h2><p><a href="https://www.phpbb.com/community/viewtopic.php?f=14&amp;t=571070">پکیج خوشآمد گویی</a><br /><a href="https://www.phpbb.com/support/">بخش پشتیبانی</a><br /><a href="https://www.phpbb.com/support/docs/en/3.1/ug/quickstart/">راهنمای سریع</a><br /><br />جهت اطمینان از آخرین اخبار <a href="https://www.phpbb.com/support/">به لیست پستی ما مشترک شوید</a>?<br /><br />',

	// License
	'LICENSE_TITLE'		=> 'General Public License',

	// Install page
	'INSTALL_INTRO'			=> 'به نصب خوش آمدید',
	'INSTALL_INTRO_BODY'	=> 'در این قسمت شما میتوانید برنامه phpBB را در سرور خود نصب کنید.</p><p>برای ادامه مراحل شما به تنظیمات پایگاه داده نیاز دارید. اگر درباره  این موضوع اطلاعاتی ندارید با پشتیبان هاست خود تماس بگیرید. بدون این اطلاعات قادر به ادامه مراحل نیستید شما به این موارد  نیاز دارید:</p>

	<ul>
		<li>نوع پایگاه داده - پایگاه داده ای که از آن استفاده میکنید</li>
		<li>نام هاست سرور پایگاه داده (database hostname) و یا DSN - آدرس سرور پایگاه داده.</li>
		<li>بندرگاه (پورت،port) - پورت سرور پایگاه داده .(اغلب موارد به این گزینه نیاز نیست)</li>
		<li>نام پایگاه داده - نام پایگاه داده در سرور</li>
		<li>نام کاربری و کلمه عبور پایگاه داده - اطلاعات ورود برای دسترسی به پایگاه داده</li>
	</ul>

	<p><strong>توجه:</strong> اگر نصب را با استفاده از SQLite انجام میدهید، باید مسیر کامل به پایگاه داده را در فیلد DSN وارد کنید و فیلد های کلمه عبور و نام کاربری را خالی بگذارید. برای دلایل امنیتی باید مطمئن باشید که پایگاه داده در محلی ذخیره نشده باشد که از وب قابل دسترسی باشد.</p>

	<p>phpBB این پایگاه های داده را پشتیبانی می کند :</p>
	<ul>
		<li>MySQL 3.23 or above (MySQLi supported)</li>
		<li>PostgreSQL 8.3+</li>
		<li>SQLite 3.6.15+</li>
		<li>MS SQL Server 2000 or above (directly or via ODBC)</li>
		<li>MS SQL Server 2005 or above (native)</li>
		<li>Oracle</li>
	</ul>

	<p>فقط پایگاه های داده پشتیبان شده در سرورتان نمایش داده خواهند شد.',

	'ACP_LINK'	=> '<a href="%1$s">مرکز مدیریت</a>',


	'INSTALL_PHPBB_INSTALLED'		=> 'phpBB قبلا نصب شده',
	'INSTALL_PHPBB_NOT_INSTALLED'	=> 'phpBB هنوز نصب نشده',
));

// Requirements translation
$lang = array_merge($lang, array(
	// Filesystem requirements
	'FILE_NOT_EXISTS'						=> 'فایل موجود نیست',
	'FILE_NOT_EXISTS_EXPLAIN'				=> 'برای نصب phpBB فایل %1$s موجود نیست.',
	'FILE_NOT_EXISTS_EXPLAIN_OPTIONAL'		=> 'برای تجربه بهتر کاربر باید فایل %1$s موجود باشد.',
	'FILE_NOT_WRITABLE'						=> 'فایل قابل خواندن نیست',
	'FILE_NOT_WRITABLE_EXPLAIN'				=> 'برای نصب phpBB فایل %1$s باید قابل خواندن باشد.',
	'FILE_NOT_WRITABLE_EXPLAIN_OPTIONAL'	=> 'برای تجربه بهتر کاربر باید فایل %1$s قابل خواندن باشد.',

	'DIRECTORY_NOT_EXISTS'						=> 'مسیری وجود ندارد',
	'DIRECTORY_NOT_EXISTS_EXPLAIN'				=> 'برای نصب phpBB مسیر %1$s باید وجود داشته باشد.',
	'DIRECTORY_NOT_EXISTS_EXPLAIN_OPTIONAL'		=> 'برای تجربه بهتر کاربر باید مسیر %1$s وجود داشته باشد.',
	'DIRECTORY_NOT_WRITABLE'					=> 'مسیر قابل خواندن نیست.',
	'DIRECTORY_NOT_WRITABLE_EXPLAIN'			=> 'برای نصب phpBB باید مسیر %1$s قابل خواندن باشد.',
	'DIRECTORY_NOT_WRITABLE_EXPLAIN_OPTIONAL'	=> 'برای تجربه بهتره کاربر باید مسیر %1$s قابل خواندن باشد.',

	// Server requirements
	'PHP_VERSION_REQD'					=> 'نسخه PHP',
	'PHP_VERSION_REQD_EXPLAIN'			=> 'phpBB نیاز به نسخه 5.4.0 PHP دارد.',
	'PHP_GETIMAGESIZE_SUPPORT'			=> 'تابع phpBB getimagesize() در دسترس است',
	'PHP_GETIMAGESIZE_SUPPORT_EXPLAIN'	=> '<strong>ضروری</strong> - برای این که phpBB عملکرد درستی داشته باشد، باید تابع getimagesize در دسترس باشد.',
	'PCRE_UTF_SUPPORT'			     	=> 'PCRE UTF-8 پشتیبانی',
	'PCRE_UTF_SUPPORT_EXPLAIN'		    => 'اگر PHP با UTF-8 سازگار نباشد و از افزونه PCRE پشتیبانی نکند،در آن صورت phpBB  .<strong>اجرا نمی شود</strong>',
	'PHP_JSON_SUPPORT'				    => 'PHP JSON پشتیبانی',
	'PHP_JSON_SUPPORT_EXPLAIN'		    => '<strong>ضروری</strong> - - برای این که phpBB عملکرد درستی داشته باشد، باید تابع PHP JSON در دسترس باشد.',
	'PHP_XML_SUPPORT'					=> 'PHP XML/DOM پشتیبانی',
	'PHP_XML_SUPPORT_EXPLAIN'			=> 'In order for phpBB to function correctly, the PHP XML/DOM extension needs to be available.',
	'PHP_SUPPORTED_DB'				    => 'پایگاه های داده پشتیبانی شده',
	'PHP_SUPPORTED_DB_EXPLAIN'	     	=> '<strong>ضروری</strong> - حداقل باید یک پایگاه داده در سرور پشتیبانی شود. اگر هیچ ماژول پایگاه داده ای نشان داده نشود،برای فعال سازی و جزئیات باید با شرکت خدمات هاستینگ خود تماس بگیرید. ویا توضیحات مربوطه را در مستندات PHP بخوانید. ',

	'RETEST_REQUIREMENTS'	=> 'تست مجدد نیازمندی ها',

	'STAGE_REQUIREMENTS'	=> 'بررسی نیازمندی ها',
));

// General error messages
$lang = array_merge($lang, array(
	'INST_ERR_MISSING_DATA'		=> 'باید همگی فیلد های موجود در این بلوک را پر کنید.',

	'TIMEOUT_DETECTED_TITLE'	=> 'زمان نصب منقضی شد.',
	'TIMEOUT_DETECTED_MESSAGE'	=> 'زمان نصب منقضی شده است، شما باید این صفحه را مجددا رفرش کنید. همچنین میتوانید زمان منقضی شدن را افزایش یا از CLI استفاده کنید.',
));

// Data obtaining translations
$lang = array_merge($lang, array(
	'STAGE_OBTAIN_DATA'	=> 'داده های نصب را وارد کنید.',

	//
	// Admin data
	//
	'STAGE_ADMINISTRATOR'		=> 'جزئیات مدیر',

	// Form labels
	'ADMIN_CONFIG'				=> 'پیکربندی مدیر',
	'ADMIN_PASSWORD'			=> 'کلمه عبور مدیر',
	'ADMIN_PASSWORD_CONFIRM'	=> 'تایید کلمه عبور مدیر',
	'ADMIN_PASSWORD_EXPLAIN'	=> 'کلمه عبور باید بین 6 تا 30 کاراکتر باشد.',
	'ADMIN_USERNAME'			=> 'نام کاربری مدیر',
	'ADMIN_USERNAME_EXPLAIN'	=> 'نام کاربری باید بین 3 تا 20 کاراکتر باشد. ',
	
	// Errors
	'INST_ERR_EMAIL_INVALID'	=> 'آدرس ایمیل وارد شده معتبر نمی باشد.',
	'INST_ERR_PASSWORD_MISMATCH'	=> 'دو کلمه عبور وارد شده یکسان نمیشوند.',
	'INST_ERR_PASSWORD_TOO_LONG'	=> 'کلمه عبور وارد شده بسیار طولانی می باشد. حداکثر درازای کلمه عبور باید 30 کاراکتر باشد.',
	'INST_ERR_PASSWORD_TOO_SHORT'	=> 'کلمه عبور وارد شده بسیار کوتاه می باشد. حداقل درازای کلمه عبور باید 6 کاراکتر باشد.',
	'INST_ERR_USER_TOO_LONG'	=> 'نام کاربری وارد شده بسیار طولانی می باشد. حداکثر درازا 20 کاراکتر می باشد.',
	'INST_ERR_USER_TOO_SHORT'	=> 'نام کاربری وارد شده بسیار کوتاه می باشد. حداقل کااکتر، 3 می باشد.',

	//
	// Board data
	//
	// Form labels
	'BOARD_CONFIG'		=> 'تنظیمات انجمن',
	'DEFAULT_LANGUAGE'	=> 'زبان پیشفرض',
	'BOARD_NAME'		=> 'عنوان انجمن',
	'BOARD_DESCRIPTION'	=> 'توضیح کوتاهی درباره انجمن',

	//
	// Database data
	//
	'STAGE_DATABASE'			=> 'تنظیمات پایگاه داده',

	// Form labels
	'DB_CONFIG'					=> 'پیکربندی پایگاه داده',
	'DBMS'						=> 'نوع پایگاه داده',
	'DB_HOST'					=> 'نام هاست سرور (hostname) و یا DSN پایگاه داده',
	'DB_HOST_EXPLAIN'			=> 'DSN مخفف Data Source Name می باشد و مربوط به نصب ODBC هست. در PostgreSQL, از localhost برای اتصال به سرور محلی با سوکت UNIX domain استفاده کنید و از 127.0.0.1 برای اتصال به TCP استفاده کنید.',
	'DB_PORT'					=> 'درگاه (پورت،port) پایگاه داده',
	'DB_PORT_EXPLAIN'			=> 'این فیلد را تا زمانی که مطمئن باشید که درگاه سرور استاندارد نیست،خالی بگذارید.',
	'DB_PASSWORD'				=> 'کلمه عبور پایگاه داده',
	'DB_NAME'					=> 'نام پایگاه داده',
	'DB_USERNAME'				=> 'نام کاربری پایگاه داده',
	'DATABASE_VERSION'		=> 'Database version',
	'TABLE_PREFIX'				=> 'پیشوند جداول پایگاه داده',
	'TABLE_PREFIX_EXPLAIN'		=> 'پیشوند باید فقط حاوی حروف باشد و اعداد و کاراکتر ها مورد قبول نیستند.',

	// Database options
	'DB_OPTION_MSSQL_ODBC'	=> 'MSSQL Server 2000+ via ODBC',
	'DB_OPTION_MSSQLNATIVE'	=> 'MSSQL Server 2005+ [ Native ]',
	'DB_OPTION_MYSQL'		=> 'MySQL',
	'DB_OPTION_MYSQLI'		=> 'MySQL with MySQLi Extension',
	'DB_OPTION_ORACLE'		=> 'Oracle',
	'DB_OPTION_POSTGRES'	=> 'PostgreSQL',
	'DB_OPTION_SQLITE3'		=> 'SQLite 3',

	// Errors
	'INST_ERR_DB'					=> 'خطا هنگام نصب دیتابیس',
	'INST_ERR_NO_DB'			    => 'بارگذاری ماژول PHP در این نوع پایگاه داده مقدور نیست.',
    'INST_ERR_DB_INVALID_PREFIX'    => 'پیشوند وارد شده معتبر نمی باشد، پیشوند ها باید فقط شامل حروف باشند و اعداد و کاراکتر ها مورد قبول نیستند.',
	'INST_ERR_PREFIX_TOO_LONG'	    => 'پیشوند انتخاب شده بسیار طولانی است. حداکثر کاراکتر مجاز %d می باشد.',
	'INST_ERR_DB_NO_NAME'	     	=> 'نام پایگاه داده مشخص نشده است.',
	'INST_ERR_DB_FORUM_PATH'    	=> 'فایل پایگاه داده مشخص شده در دایرکتوری تالار قرار دارد. این فایل را باید در مکانی قرار دهید که از وب قابل دسترس نباشد.',	
	'INST_ERR_DB_CONNECT'		    => 'اتصال به پایگاه زیر ممکن نیست.خطا های زیر را کنترل کنید.',
	'INST_ERR_DB_NO_WRITABLE'		=> 'هر دو پایگاه داده و پوشه ها و محتویات آنها باید ',
	'INST_ERR_DB_NO_ERROR'		    => 'خطایی روی نداد.',
	'INST_ERR_PREFIX'			    => 'جداولی با این پیشوند موجود می باشد،لطفا آن را تغییر دهید.',
	'INST_ERR_DB_NO_MYSQLI'		    => 'نسخه MYSQL گزینه  نصب شده در این دستگاه با “MySQL همراه با افزونه MySQLi ” که انتخاب کرده اید، ناسازگار است. لطفا گزینه “MySQL” را انتخاب کنید.',
	'INST_ERR_DB_NO_SQLITE3'	    => 'نسخه افزونه نصب شده SQLite قدیمی می باشد. باید حداقل به نسخه 3.6.15 بروزرسانی شود.',
	'INST_ERR_DB_NO_ORACLE'		    => 'نسخه Oracle نصب شده در این ماشین از شما میخواهد تا پارامتر <var>NLS_CHARACTERSET</var> به <var>UTF8</var> تغییر دهید.سیستم نصب را به 9.2+ ارتقاء دهید و یا پارامتر را تغییر دهید.',
	'INST_ERR_DB_NO_POSTGRES'	    => 'پایگاه داده انتخاب شده در قالب (encoding) <var>UNICODE</var> یا <var>UTF8</var> ایجاد شده است. لطفا نصب پایگاه داده در قالب (encoding) <var>UNICODE</var> یا <var>UTF8</var> امتحان کنید.',
	'INST_SCHEMA_FILE_NOT_WRITABLE'	=> 'فایل schema قابل خواندن نیست',

	//
	// Email data
	//
	'EMAIL_CONFIG'	=> 'تنظیمات ایمیل',

	// Package info
	'PACKAGE_VERSION'					=> 'پکیج این نسخه نصب شد',
	'UPDATE_INCOMPLETE'				=> 'نسخه phpBB شما به درستی نصب نشد',
	'UPDATE_INCOMPLETE_MORE'		=> 'لطفا اطلاعات زیر را جهت رفع خطا بخوانید.',
	'UPDATE_INCOMPLETE_EXPLAIN'		=> '<h1>آپدیت به صورت ناقص</h1>

		<p>ما متوجه شدیم که آخرین آپدیت به صورت کامل نصب نشده است. مشاهده کنید <a href="%1$s" title="%1$s">به روزرساندیتابیس</a>, و مطمئن شوید <em>گزینه به روز رسانی دیتابیس</em> انتخاب شده باشد و کلیک کنید روی <strong>ارسال</strong>. و در آخر پوشه "install"را کاملا پاک کنید</p>',
	//
	// Server data
	//
	// Form labels
	'UPGRADE_INSTRUCTIONS'			=> 'نسخه جدید <strong>%1$s</strong> نگاه کنید <a href="%2$s" title="%2$s"><strong>tاطلاعیه جدید</strong></a>درباره آنچه منتشر شده و پیشنهادات برای این نسخه',
	'SERVER_CONFIG'				=> 'پیکربندی سرور',
	'SCRIPT_PATH'				=> 'مسیر اسکریپت',
	'SCRIPT_PATH_EXPLAIN'		=> 'مسیری که با توجه به دامنه، phpBB در آن قرار دارد. برای مثال .<samp>/phpBB3</samp>',
));

// Default database schema entries...
$lang = array_merge($lang, array(
	'CONFIG_BOARD_EMAIL_SIG'		=> 'با تشکر، مدیریت',
	'CONFIG_SITE_DESC'				=> 'توضیحاتی مختصر درباره انجمن',
	'CONFIG_SITENAME'				=> 'دامنه شما.com',

	'DEFAULT_INSTALL_POST'			=> 'این یک مطلب آزمایشی است. کار فارسی سازی این سیستم توسط میثم نوبری تنها عضو رسمی پشتیبان سیستم phpBB فارسی در دنیا انجام شده است .به نظر می رسد که همه چیز به درستی کار می کند. اگر بخواهید می توانید این پست را حذف کنید و برای سازماندهی انجمنتان دست به کار شوید. در طول فرایند نصب، اولین گروه و اولین انجمنتان تشکیل شدند و سطوح دسترسی آن ها با توجه به گروه های کاربری پیشفرض یعنی مدیران،روبات ها،مدیران انجمن،مهمانان،کاربران عضو شده و کاربران عضو شده COPPA تنظیم شدند. همچنین میتوانید اولین گروه و اولین انجمنتان را نیز حذف کنید. لطفا تنظیم سطوح دسترسی را در هنگام ایجاد انجمن ها و گروه ها، برای گروه های کاربری  فراموش نکنید. توصیه میشود اولین گروه و اولین انجمن را حذف نکرده و فقط تغییر نام دهید تا هنگام ایجاد انجمن ها و گروه های جدید سطوح دسترسی این دو را به آنها کپی کنید.حال از این انجمن لذت ببرید.!
چنانچه از نحوه کار با این سیستم اطلاعاتی ندارید و دنبال قالب های شکیل و مدرن به صورت فارسی و همچنین افزونه های قدرتمند فارسی هستید میتوانید به مرجع پشتیبانی فارسی phpBB در ایران مراجعه کنید. 
',

	'FORUMS_FIRST_CATEGORY'			=> 'اولین گروه شما',
	'FORUMS_TEST_FORUM_DESC'		=> 'توضیحات اولین انجمن شما',
	'FORUMS_TEST_FORUM_TITLE'		=> 'اولین انجمن شما',

	'RANKS_SITE_ADMIN_TITLE'		=> 'مدیر کل سایت',
	'REPORT_WAREZ'					=> 'پست به نرم افزاری غیر قانونی و یا دزدی لینک دارد.',
	'REPORT_SPAM'					=> 'پست گزارش شده فقط به هدف تبلیغ وبسایت و یا محصولی ایجاد شده است. ',
	'REPORT_OFF_TOPIC'				=> 'پست گزارش شده خارج از موضوع است',
	'REPORT_OTHER'					=> 'پست گزارش شده شامل هیچکدام از موارد بالا نمی باشد،لطفا از فیلد اطلاعات بیش تر استفاده کنید.',

	'SMILIES_ARROW'					=> 'پیکان',
	'SMILIES_CONFUSED'				=> 'سردرگم',
	'SMILIES_COOL'					=> 'خون سرد',
	'SMILIES_CRYING'				=> 'گریه می کند و یا خیلی ناراحت است',
	'SMILIES_EMARRASSED'			        => 'خجالتی',
	'SMILIES_EVIL'					=> 'بد و یا خبلی عصبانی',
	'SMILIES_EXCLAMATION'			        => 'فریاد',
	'SMILIES_GEEK'					=> 'عجیب',
	'SMILIES_IDEA'					=> 'فکر',
	'SMILIES_LAUGHING'				=> 'می خندد',
	'SMILIES_MAD'					=> 'عصبانی',
	'SMILIES_MR_GREEN'				=> 'آقای سبز',
	'SMILIES_NEUTRAL'				=> 'بدون طرف',
	'SMILIES_QUESTION'				=> 'سؤال',
	'SMILIES_RAZZ'					=> 'مسخره باز',
	'SMILIES_ROLLING_EYES'			        => 'چشم های چرخان',
	'SMILIES_SAD'					=> 'غمگین',
	'SMILIES_SHOCKED'				=> 'شوکه',
	'SMILIES_SMILE'					=> 'لبخند',
	'SMILIES_SURPRISED'				=> 'سوپرایز',
	'SMILIES_TWISTED_EVIL'			        => 'خیلی بد',
	'SMILIES_UBER_GEEK'				=> 'خیلی عجیب',
	'SMILIES_VERY_HAPPY'			        => 'خیلی خوشحال',
	'SMILIES_WINK'					=> 'چشمک',

	'TOPICS_TOPIC_TITLE'			=> 'به phpBB3 خوش آمدید',
));

// Common navigation items' translation
$lang = array_merge($lang, array(
	'MENU_OVERVIEW'		=> 'نگاه کلی',
	'MENU_INTRO'		=> 'معرفی',
	'MENU_LICENSE'		=> 'لایسنس',
	'MENU_SUPPORT'		=> 'پشتیبانی',
));

// Task names
$lang = array_merge($lang, array(
	// Install filesystem
	'TASK_CREATE_CONFIG_FILE'	=> 'ساخت پیکربندی فایل ها',

	// Install database
	'TASK_ADD_CONFIG_SETTINGS'			=> 'اضافه کردن پیکربندی تنظیمات',
	'TASK_ADD_DEFAULT_DATA'				=> 'اضافه کردن تنظیمات پیشفرض به دیتابیس',
	'TASK_CREATE_DATABASE_SCHEMA_FILE'	=> 'ساخت فایل های schema دیتابیس',
	'TASK_SETUP_DATABASE'				=> 'تنظیم دیتابیس',
	'TASK_CREATE_TABLES'				=> 'ساخت جداول',

	// Install data
	'TASK_ADD_BOTS'				=> 'ثبت ربات های جستجو',
	'TASK_ADD_LANGUAGES'		=> 'نصب بسته های زبانی موجود',
	'TASK_ADD_MODULES'			=> 'نصب مدل ها',
	'TASK_CREATE_SEARCH_INDEX'	=> 'ساخت صفحه جستجو',

	// Install finish tasks
	'TASK_INSTALL_EXTENSIONS'	=> 'نصب پیکج افزونه ها',
	'TASK_NOTIFY_USER'			=> 'ارسال اطلاعیه ایمیل',
	'TASK_POPULATE_MIGRATIONS'	=> 'محاسبه مهاجرت ها',

	// Installer general progress messages
	'INSTALLER_FINISHED'	=> 'نصب کننده با موفقیت پایان یافت',
));

// Installer's general messages
$lang = array_merge($lang, array(
	'MODULE_NOT_FOUND'				=> 'مدل یافت نشد',
	'MODULE_NOT_FOUND_DESCRIPTION'	=> 'مدل یافت نشد ، زیرا %s تعریف نشده است',

	'TASK_NOT_FOUND'				=> 'وظیفه یافت نشد',
	'TASK_NOT_FOUND_DESCRIPTION'	=> 'وظیفه یافت نشد ،زیرا سرویس %s تعریف نشده است.',

	'SKIP_MODULE'	=> 'گذر از مدل “%s”',
	'SKIP_TASK'		=> 'گذر از وظیفه “%s”',

	'TASK_SERVICE_INSTALLER_MISSING'	=> 'تمامی سرویس های وظایف باید توسط سیستم نصاب شروع شود',
	'TASK_CLASS_NOT_FOUND'				=> 'سرویس وظیفه نصب کننده نامعتبر است. Service name “%1$s” given, the expected class namespace is “%2$s” for that. For more information please see the documentation of task_interface.',

	'INSTALLER_CONFIG_NOT_WRITABLE'	=> 'فایل config نصاب قابل خواندن نیست',
));

// CLI messages
$lang = array_merge($lang, array(
	'CLI_INSTALL_BOARD'				=> 'نصب phpBB',
	'CLI_UPDATE_BOARD'				=> 'به روزرسانی phpBB',
	'CLI_INSTALL_SHOW_CONFIG'		=> 'نمایش پیکربندی استفاده شده',
	'CLI_INSTALL_VALIDATE_CONFIG'	=> 'اعتبار سازی فایل پیکربندی',
	'CLI_CONFIG_FILE'				=> 'پیکربندی فایل جهت استفاده',
	'MISSING_FILE'					=> 'دسترسی به فایل های امکان پذیر نیست  %1$s',
	'MISSING_DATA'					=> 'پیکربندی به دلیل ازبین رفتن داده ها امکان ندارد',
	'INVALID_YAML_FILE'				=> 'Could not parse YAML file %1$s',
	'CONFIGURATION_VALID'			=> 'پیکربندی فایل ها معتبر است',
));

// Common updater messages
$lang = array_merge($lang, array(
	'UPDATE_INSTALLATION'			=> 'به روزرسانی phpBB',
	'UPDATE_INSTALLATION_EXPLAIN'	=> 'در این قسمت میتوانید برنامه phpBB را با آخرین نسخه ارتقا دهید<br />در طی این فرآیند فایل های شما بررسی خواهد شد. تفاوت فایل ها قبل و بعد از آپدیت مشخص خواهد بود.<br /><br />آپدیت فایل ها به دو شیوه خواهد بود.</p><h2>به صورت دستی</h2><p>با این روش شما فایل هایی که تغییر یافته اند را دانلود کرده و به صورت دستی در هاست آپلود میکنید و بعد ببرسی کنید که فایل ها درجای دقیق خود قرار گرفته اند.</p><h2>به صورت خودکار از طریق FTP</h2><p>این روش شبیه روش اولاست اما نیاز به دانلود فایل ها و آپلود آن ها در هاست نیست. برای انجام این روش باید کار با اف تی پی را بلد باشید و پس از آن شما میتوانیدبررسی کنید که فایل ها رد جای خود قرار گرفته اند یا نه.<br /><br />',
	'UPDATE_INSTRUCTIONS'			=> '

		<h1>اطلاعیه ی انتشار</h1>

		<p>لطفا <a href="%1$s" title="%1$s"><strong>اطلاعیه ی انتشار نسخه جدید</strong> را قبل از بروزرسانی بخوانید. </a> ممکن است حاوی اطلاعات مفیدی باشد. همچنین در آنجا لینک کامل بارگیری و لیست تغییرات موجود است.</p>

		<br />
 
		<h1>چگونه با استفاده از پکیج کامل phpBB را آپدیت کنیم؟</h1>

		<p>روش پیشنهادی برای آپدیت به صورت پکیج کامل به صورت زیر است. اگر فایل های هسته سیستم دستکاری شده باشد باید از طریق سیستم خودکار این آپدیت صورت گیرد در غیر این صورت لازم نیست. همچنین روش های دیگری برا آپدیت وجود دارد که در فایل INSTALL.html میتوانید مشاهده کنید.آموزش گام به گام به صورت پکیج کامل به صورت زیر است.</p>

		<ol style="margin-left: 20px; font-size: 1.1em;">
			<li><strong class="error">از فایل های و دیتابیس خود بک آپ کامل بگیرید.</strong></li>
			<li>به سایت دانلود مراجعه کنید <a href="https://www.phpbb.com/downloads/" title="https://www.phpbb.com/downloads/">phpBB.com صفحه دانلود</a> و "Full Package" را دانلود کنید.</li>
			<li>فایل را از حالت زیپ خارج کنید.</li>
			<li>فایل <code class="inline">config.php</code> را حذف کنید, و همچنین فایل های <code class="inline">/images</code>, <code class="inline">/store</code> و <code class="inline">/files</code> <em>را از پکیج دانلود حذف کنید</em> (از سایت پاک نشود).</li>
			<li>به مرکز مدیریت بروید, در قسمت تنظیمات تالار قالب پیش فرض را پروسیلور قرار دهید.</li>
			<li>فایل های <code class="inline">/vendor</code> و <code class="inline">/cache</code> از هاست خود حذف کنید.</li>
			<li>از طریق FTP یا SSH و یا روشهای دلخواه پکیج را به داخل هاست خود منتقل کنید تا فایل ها جایگزین قبلی شود. (توجه: مراقب باشید افزونه های شما پاک نشود <code class="inline">/ext</code> که در این پوشه هستند.)</li>
			<li><strong><a href="%1$s" title="%1$s">حالا استارت آپدیت را بزنید !</a>.</strong></li>
			<li>گام های مربوط به ارتقا را طی کنید تا مراحل تمام شود.</li>
			<li>فایل <code class="inline">/install</code> را پاک کنید<br><br></li>
		</ol>
		
		<p>حالا انجمن شما با موفقیت آپدیت شده. اقدامات زیر را هم انجام دهید:</p>
		<ul style="margin-left: 20px; font-size: 1.1em;">
			<li>آپدیت فایل زبانی</li>
			<li>آپدیت قالبbr><br></li>
		</ul>

		<h1>نحوه بروزرسانی نصب با استفاده از بسته های خودکار بروزرسانی</h1>

		<p>روش های توصیه شده برای بروزرسانی نصب فقط برای نصب خودکار معتبر است. بروزرسانی را می توانید با استفاده از روش هایی که در  INSTALL.html ذکر شده است نیزانجام دهید. مرحله های بروزرسانی خودکار phpBB :</p>

		<ol style="margin-left: 20px; font-size: 1.1em;">
			<li>به <a href="https://www.phpbb.com/downloads/" title="https://www.phpbb.com/downloads/">phpBB.com صفحه بارگیری</a> بروید و آرشیو "Automatic Update Package" را بارگیری کنید.<br /><br /></li>
			<li>آرشیو را از حالت فشردگی خارج کنید<br /><br /></li>
			<li>پوشه های "vendor" و install را بدون فشردگی و حالت زیپ، به روت phpBB آپلود کنید .(جایی که config.php وجود دارد)<br /><br /></li>
		</ol>

		<p>هنگامی که پوشه install اپلود شود، تالار برای کاربران معمولی غیرفعال خواهد بود.<br /><br />
		<strong><a href="%1$s" title="%1$s">حال با استفاده از مرورگرتان به پوشه نصبتان بروید.</a></strong><br />
		<br />
		در طول مراحل بروزرسانی راهنمایی خواهید شد و هنگامی که بروزرسانی پایان یابد،اطلاع داده میشود.
		</p>
	',
));

// Updater forms
$lang = array_merge($lang, array(
	// Updater types
	'UPDATE_TYPE'			=> 'نوع به روزرسانی جهت اجرا',

	'UPDATE_TYPE_ALL'		=> 'به روز رسانی فایل ها و دیتابیس',
	'UPDATE_TYPE_DB_ONLY'	=> 'تنها آپدیت دیتابیس',

	// File updater methods
	'UPDATE_FILE_METHOD_TITLE'		=> 'روش به روزرسان فایل',

	'UPDATE_FILE_METHOD'			=> 'روش به روزرسان فایل',
	'UPDATE_FILE_METHOD_DOWNLOAD'	=> 'دانلود فایل های تغییر یافته در آرشیو',
	'UPDATE_FILE_METHOD_FTP'		=> 'آپدیت فایل ها توسط FTP به صورت خودکار',
	'UPDATE_FILE_METHOD_FILESYSTEM'	=> 'به روزرسانی فایل ها به صورت مستقیم - خودکار',

	// File updater archives
	'SELECT_DOWNLOAD_FORMAT'	=> 'انتخاب نوع آرشیو فایل ها',

	// FTP settings
	'FTP_SETTINGS'			=> 'FTP تنظیمات',
));

// Requirements messages
$lang = array_merge($lang, array(
	'UPDATE_FILES_NOT_FOUND'	=> 'آپدیت معتبری یافت نشد ، لطفا مطمئن شوید فایل ها به درستی آپلود شده اند.',

	'NO_UPDATE_FILES_UP_TO_DATE'	=> 'نسخه شما بروز می باشد و نیازی به اعمال ابزار بروزرسانی نیست. اگر می خواهید از بروز بودن تمامی فایل های موجود اطمینان حاصل کنید،از فایل های مناسب برای بروزرسانی استفاده کنید.',
	'OLD_UPDATE_FILES'		        => 'فایل های بروزرسانی قدیمی هستند. فایل های بروزرسانی برای بروز کردن phpBB از نسخه %1$s به نسخه phpBB %2$s هستند،ولی آخرین نسخه، phpBB %3$s می باشد.',
	'INCOMPATIBLE_UPDATE_FILES'		=> 'فایل های بروزرسانی یافت شده با نسخه کنونی شما سازگار نیستند. نسخه نصب شده شما %1$s می باشد و نسخه مورد نیاز برای بروزرسانی phpBB بین %2$s تا %3$s می باشد.',
));

// Update files
$lang = array_merge($lang, array(
	'STAGE_UPDATE_FILES'		=> 'به روزرسانی فایل ها',

	// Check files
	'UPDATE_CHECK_FILES'	=> 'بررسی فایل ها برای آپدیت',

	// Update file differ
	'FILE_DIFFER_ERROR_FILE_CANNOT_BE_READ'	=> 'قارد به باز کردن فایل %s نیست.',

	'UPDATE_FILE_DIFF'		=> 'فایل های تغییر یافته',
	'ALL_FILES_DIFFED'		=> 'تمامی فایل های تغییر یافته ارشیو شدند.',

	// File status
	'UPDATE_CONTINUE_FILE_UPDATE'	=> 'به روز رسانی فایل ها',

	'DOWNLOAD'							=> 'دانلود',
	'DOWNLOAD_CONFLICTS'				=> 'بارگیری تضاد های این فایل',
	'DOWNLOAD_CONFLICTS_EXPLAIN'		=> 'جستجوی &lt;&lt;&lt; to برای یافتن تضاد ها',
	'DOWNLOAD_UPDATE_METHOD'			=> 'بارگیری فایل های تغییر یافته',
	'DOWNLOAD_UPDATE_METHOD_EXPLAIN'	=> 'هنگامی که دانلود شد باید از حالت فشردگی خارج کنید، در این بسته فایل هایی را که باید به روت (root) phpBB آپلود کنید،خواهید یافت. این فایل ها را به مکان های مشخص شده آپلود کرده و سپس بر روی دکمه کنترل دوباره فایل ها کلیک کنید. ',

	'FILE_ALREADY_UP_TO_DATE'		=> 'فایل در حال حاضر بروز است',
	'FILE_DIFF_NOT_ALLOWED'			=> 'فایل قابل مقایسه نیست.',
	'FILE_USED'						=> 'اطلاعات استفاده شده از',				// Single file
	'FILES_CONFLICT'				=> 'فایل های متضاد',
	'FILES_CONFLICT_EXPLAIN'		=> 'فایل های مقابل عوض شدند و فایلی از نسخه قبلی جایگزین آن ها نشد. اگر فایل ها انطباق شدن را امتحان کرده باشند،phpBB به این نتیجه رسیده است که این دو فایل با یکدیگر سازگاری ندارند. لطفا تضاد ها را بررسی و سعی کنید که به صورت دستی این تضاد ها را انطباق دهید و یا روش انطباق سازی را انتخاب کنید. اگر این تضاد ها را به طور دستی اصلاح کردید،دوباره فایل ها را کنترل کنید. همچنین میتوانید روش های منطبق سازی توصیه شده را برای همه فایل ها به کار گیرید. اولی نتیجه گم شدن سطر های متضاد در فایل قدیمی خواهد بود. دومی نتیجه گم شدن تغییرات در فایل جدید خواهد بود.',
	'FILES_DELETED'					=> 'فایل ها حذف شدند.',
	'FILES_DELETED_EXPLAIN'			=> 'فایل های زیر در نسخه جدید وجود ندارند. این فایل ها باید حذف شوند',
	'FILES_MODIFIED'				=> 'فایل های تغییر یافته',
	'FILES_MODIFIED_EXPLAIN'		=> 'فایل های مقابل تغییر یافتند و هیچ فایلی از تالار قبلی جایگزین آنها نشد. فایل بروز شده با توسط شما با فایل های جدید ادغام خواهد شد.',
	'FILES_NEW'						=> 'فایل های جدید',
	'FILES_NEW_EXPLAIN'				=> 'فایل های مقابل در بخش نصب وجود ندارند.این فایل ها به بخش نصب اضافه خواهند شد.',
	'FILES_NEW_CONFLICT'			=> 'فایل های متضاد جدید',
	'FILES_NEW_CONFLICT_EXPLAIN'	=> 'فایل های مقابل در نسخه جدید وجود دارند ولی به نظر می رسد که فایلی با همان  نام و مکان در سرورتان وجود دارند، این فایل با فایل های جدید تعویض خواهد شد.',
	'FILES_NOT_MODIFIED'			=> 'فایل های تغییر نیافته',
	'FILES_NOT_MODIFIED_EXPLAIN'	=> 'فایل های مقابل تغییر نیافتند و اصل این فایل ها در phpBB و نسخه جدید آن یکی هستند.',
	'FILES_UP_TO_DATE'				=> 'فایل ها در حال حاضر بروز میباشند.',
	'FILES_UP_TO_DATE_EXPLAIN'		=> 'فایل های مقابل بروز هستند و نیازی به بروزرسانی ان ها نیست.',
	'FILES_VERSION'					=> 'نسخه فایل ها',
	'TOGGLE_DISPLAY'				=> 'مشاهده/مخفی لیست فایل',
	
	// File updater
	'UPDATE_UPDATING_FILES'	=> 'به روز رسانی فایل ها',

	'UPDATE_FILE_UPDATER_HAS_FAILED'	=> 'به روزرسان “%1$s“ نا موفق بود.نصاب مجدد “%2$s“تلاش خواهد کرد',
	'UPDATE_FILE_UPDATERS_HAVE_FAILED'	=> 'به روزرسان فایل ناموفق بود. هیچ روش دیگری وجود ندارد',

	'UPDATE_CONTINUE_UPDATE_PROCESS'	=> 'ادامه مراحل بهروزرسانی',
	'UPDATE_RECHECK_UPDATE_FILES'		=> 'بررسی مجدد فایل ها',
));

// Update database
$lang = array_merge($lang, array(
	'STAGE_UPDATE_DATABASE'		=> 'بهروزرسانی دیتابیس',

	'INLINE_UPDATE_SUCCESSFUL'		=> 'به روزرسانی دیتابیس با موفقیت انجام شد.',
	'TASK_UPDATE_EXTENSIONS'	=> 'به روزرسانی افزونه',
));

// Converter
$lang = array_merge($lang, array(
	// Common converter messages
	'CONVERT_NOT_EXIST'			=> 'تبدیل کننده انتخاب شده موجود نمی باشد',
	'DEV_NO_TEST_FILE'			=> 'مقداری برای متغیر test_file در تبدیل گر وارد نشده است. اگر کاربری از تبدیل گر هستیدنباید این خطا را ببینید.لطفا با نویسنده تبدیل گر تماس بگیرید. اگر نویسنده تبدیل گر هستید،باید نام فایلی را در تالار قبلی مشخص کنید که مسیر تایید را مشخص می کند.',
	'COULD_NOT_FIND_PATH'		=> 'مسیر تالار سابق شما یافت نشد، لطفا تنظیمات را کنترل کرده و دوباره امتحان کنید.<br />» %s به عنوان مسیر مشخص شده است. ',
	'CONFIG_PHPBB_EMPTY'		=> 'متغیر phpBB3 config برای “%s” خالی است.',

	'MAKE_FOLDER_WRITABLE'		=> 'لطفا اطمینان حاصل کنید که این پوشه موجود و توسط سرور قابل نوشتن است :<br />»<strong>%s</strong>.',
	'MAKE_FOLDERS_WRITABLE'		=> 'لطفا اطمینان حاصل کنید که این پوشه ها موجود و توسط سرور قابل نوشتن هستند :<br />»<strong>%s</strong>.',

	'INSTALL_TEST'				=> 'آزمایش مجدد',

	'NO_TABLES_FOUND'			=> 'جداول پیدا نشد.',
	'TABLES_MISSING'			=> 'این جداول یافت نشدند<br />» <strong>%s</strong>.',
	'CHECK_TABLE_PREFIX'		=> 'لطفا پیشوند جداول را کنترل کرده و دوباره امتحان کنید',

	// Conversion in progress
	'CONTINUE_CONVERT'			=> 'ادامه تبدیل',
	'CONTINUE_CONVERT_BODY'		=> 'قبلا فرایند تبدیل انجام گرفته است. حال میتوانید فرآیند تبدیل جدیدی را شروع کنید و یا فرآیند انجام شده را ادامه دهید.',
	'CONVERT_NEW_CONVERSION'	=> 'تبدیل جدید',
	'CONTINUE_OLD_CONVERSION'	=> 'فرآیند تبدیل قبلی را ادامه بده',

	// Start conversion
	'SUB_INTRO'					=> 'مقدمه',
	'CONVERT_INTRO'				=> 'به phpBB Unified Convertor Framework خوش آمدید',
	'CONVERT_INTRO_BODY'		=> 'در این بخش میتوانید اطلاعات را از سایر سیستم های تالار (نصب شده) انتقال دهید. لیست زیر تمامی افزونه های موجود را نمایش میدهد. اگر افزونه سیستم شما در لیست موجود نمی باشد،لطفا برای جزئیات بیشتر و بارگیری سایر افزونه های به سایتمان مراجعه کنید.',
	'AVAILABLE_CONVERTORS'		=> 'تبدیل کننده های در دسترس',
	'NO_CONVERTORS'				=> 'تبدیل گری برای استفاده در دسترس نیست',
	'CONVERT_OPTIONS'			=> 'گزینه ها',
	'SOFTWARE'					=> 'نرم افزار تالار',
	'VERSION'					=> 'نسخه',
	'CONVERT'					=> 'تبدیل',

	// Settings
	'STAGE_SETTINGS'			=> 'تنظیمات',
	'TABLE_PREFIX_SAME'			=> 'باید پیشوند نرم افزار قبلی ای باشد که میخواهید آن را تبدیل کنید.<br />» پیشوند مشخص شده %s بود.',
	'DEFAULT_PREFIX_IS'			=> 'تبدیل گر قادر به دریافت جداول با پیشوند خاص نیست. لطفا اطمینان حاصل کنید که اطلاعات تالار قبلی را به درستی وارد کرده اید. پیشوند پیشفرض برای %1$s ،<strong>%2$s</strong>می باشد.',
	'SPECIFY_OPTIONS'			=> 'گزینه تبدیل گر را مشخص کنید',
	'FORUM_PATH'				=> 'مسیر تالار',
	'FORUM_PATH_EXPLAIN'		=> 'این <strong>مسیری است که از نصب کنونی phpBB</strong> به مسیر <strong>مرتبط با تالار قبلیتان وصل میشود.</strong>.',
	'REFRESH_PAGE'				=> 'برای ادامه فرآیند تبدیل،صفحه را مجددا بارگزاری کنید. ',
	'REFRESH_PAGE_EXPLAIN'		=> 'اگر بله انتخاب شود،تبدیل گر بعد از اتمام هر مرحله صفحه را به طور خودکار مجددا بارگیری خواهد کرد. اگر این اولین باری است که از تبدیل گر استفاده میکنید و فقط میخواهید از این بخش به عنوان آزمایش استفاده کنید،به شما توصیه میکنیم که نه را انتخاب کنید. و هرگونه خطای موجود را تشخیص دهید',
	
	// Conversion
	'STAGE_IN_PROGRESS'			=> 'فرآیند تبدیل در حال پردازش است',

	'AUTHOR_NOTES'				=> 'یادداشت های نویسنده<br />» %s',
	'STARTING_CONVERT'			=> 'شروع پردازش فرآیند تبدیل',
	'CONFIG_CONVERT'			=> 'تبدیل پیکربندی',
	'DONE'						=> 'انجام شد',
	'PREPROCESS_STEP'				=> 'اجرای پیش پردازش تابع ها/پرس و جو ها (queries)',
	'FILLING_TABLE'				=> 'جدول پر شده <strong>%s</strong>',
	'FILLING_TABLES'			=> 'جدول های پر شده',
	'DB_ERR_INSERT'				=> ' خطا در پردازش پرس و جوی <code>INSERT</code>. ',
	'DB_ERR_LAST'				=> 'خطا در پردازش <var>query_last</var>.',
	'DB_ERR_QUERY_FIRST'		=> 'خطا در پردازش <var>query_first</var>.',
	'DB_ERR_QUERY_FIRST_TABLE'	=> 'خطا در پردازش <var>query_first</var>, %s (“%s”).',
	'DB_ERR_SELECT'				=> 'خطا در اجرای پرس و جوی <code>SELECT</code> .',
	'STEP_PERCENT_COMPLETED'	=> 'مرحله <strong>%d</strong> از <strong>%d</strong>',
	'FINAL_STEP'				=> 'پردازش مرحله آخر',
	'SYNC_FORUMS'				=> 'شروع انطباق انجمن ها',
	'SYNC_POST_COUNT'			=> 'انطباق post_counts',
	'SYNC_POST_COUNT_ID'		=> 'انطباق post_counts از <var>ورودی</var> %1$s به %2$s.',
	'SYNC_TOPICS'				=> 'شروع انطباق موضوعات',
	'SYNC_TOPIC_ID'				=> 'انطباق موضوعات از <var>topic_id</var> %1$s به %2$s.',
	'PROCESS_LAST'					=> 'پردازش بیانیه ی قبلی',
	'UPDATE_TOPICS_POSTED'		=> 'تولید اطلاعات ثبت شده در موضوعات',
	'UPDATE_TOPICS_POSTED_ERR'	=> 'خطایی در تولید اطلاعات ثبت شده روی داد. میتوانید پس از پایان فرآیند تبدیل این مرحله را در کنترل پنل مدیریت دوباره امتحان کنید.',
	'CONTINUE_LAST'				=> 'آخرین توضیحات را ادامه بده',
	'CLEAN_VERIFY'				=> 'تمیز کردن و تایید ساختار نهایی',
	'NOT_UNDERSTAND'			=> ' %s #%d درک نمیشود، جدول %s (“%s”)',
	'NAMING_CONFLICT'			=> 'تضاد در نام گذاری : %s و %s با هم تضاد دارند.<br /><br />%s',

	// Finish conversion
	'CONVERT_COMPLETE'			=> 'فرآیند تبدیل اتمام یافت',
    'CONVERT_COMPLETE_EXPLAIN'	=> 'انجمن شما با موفقیت به نسخه phpBB 3.2 ارتقا پیدا کرد شما میتوانید هم اکنوان وارد اکانت خود شوید.<a href="../">دسترسی به انجمن</a>. لطفا قبل از حذف پوشه نصب و فعال سازی انجمن از تغییر تمامی تنظیمات جدید اطمینان حاصل نمایید. راهنمای استفاده از این سیستم در این آدرس موجود است. <a href="https://www.phpbb.com/support/docs/en/3.2/ug/">Documentation</a> و <a href="https://www.phpbb.com/community/viewforum.php?f=466">انجمن پشتیبانی</a>.',

	'CONV_ERROR_ATTACH_FTP_DIR'			=> 'در تالار قدیمی،آپلود FTP برای پیوست ها فعال می باشد. لطفا آپلود پیوست با FTP را غیر فعال کنید و مطمئن باشید که دایرکتوری مشخصی برای آپلود تایین شده است. و سپس تمام پیوست ها را به این دایرکتوری قابل دسترس آپلود کرده و تبدیل گر را دوباره فعال کنید.',
	'CONV_ERROR_CONFIG_EMPTY'			=> 'اطلاعات پیکربندی برای تبدیل گر وجود ندارد.',
	'CONV_ERROR_FORUM_ACCESS'			=> 'اطلاعات دسترسی به انجمن دریافت نمیشود.',
	'CONV_ERROR_GET_CATEGORIES'			=> 'گروه ها دریافت نمیشوند.',
	'CONV_ERROR_GET_CONFIG'				=> 'پیکربندی تالار بازیابی نمیشود.',
	'CONV_ERROR_COULD_NOT_READ'			=> 'دسترسی/خواندن “%s” مقدور نیست.',
	'CONV_ERROR_GROUP_ACCESS'			=> 'اطلاعات احزار هویت گروه ها دریافت نمیشوند.',
	'CONV_ERROR_INCONSISTENT_GROUPS'	=> 'در جدول گروه ها درون add_bots() ناسازگاری هایی وجود دارد. - باید همگی گروه های ویژه را دستی وارد کنید.',
	'CONV_ERROR_INSERT_BOT'				=> 'قرار دادن ربات ها در جدول کاربران مقدور نیست.',
	'CONV_ERROR_INSERT_BOTGROUP'		=> 'قرار دادن ربات ها در جدول ربات ها مقدور نیست.',
	'CONV_ERROR_INSERT_USER_GROUP'		=> 'قرار دادن کاربران در جدول user_group مقدور نیست.',
	'CONV_ERROR_MESSAGE_PARSER'			=> 'خطای پیغام تجزیه کننده',
	'CONV_ERROR_NO_AVATAR_PATH'			=> 'قابل توجه توسعه دهندگان : باید $convertor[\'avatar_path\'] را برای استفاده %s مشخص کنید.',
	'CONV_ERROR_NO_FORUM_PATH'			=> 'مسیر مربوطه به تالار منبع مشخص نشده است.',
	'CONV_ERROR_NO_GALLERY_PATH'		=> 'قابل توجه توسعه دهندگان : باید $convertor[\'avatar_gallery_path\'] را برای استفاده %s مشخص کنید.',
	'CONV_ERROR_NO_GROUP'				=> 'گروه “%1$s” در %2$s یافت نشد.',
	'CONV_ERROR_NO_RANKS_PATH'			=> 'قابل توجه توسعه دهندگان : باید $convertor[\'ranks_path\'] را برای استفاده %s مشخص کنید.',
	'CONV_ERROR_NO_SMILIES_PATH'		=> 'قابل توجه توسعه دهندگان : باید $convertor[\'smilies_path\'] را برای استفاده %s مشخص کنید.',
	'CONV_ERROR_NO_UPLOAD_DIR'			=> 'قابل توجه توسعه دهندگان : باید $convertor[\'upload_path\'] را برای %s مشخص کنید.',
	'CONV_ERROR_PERM_SETTING'			=> 'درج/بروزرسانی سطوح دسترسی مقدور نیست.',
	'CONV_ERROR_PM_COUNT'				=> 'انتخاب پوشه شمار پیغام خصوصی مقدور نیست.',
	'CONV_ERROR_REPLACE_CATEGORY'		=> 'اضافه کردن انجمن جدید به جای گروه قدیمی مقدور نیست',
	'CONV_ERROR_REPLACE_FORUM'			=> 'اضافه کردن انجمن جدید به جای انجمن قدیمی مقدور نیست.',
	'CONV_ERROR_USER_ACCESS'			=> 'دریافت تاًییدیه اطلاعات کاربران مقدور نیست.',
	'CONV_ERROR_WRONG_GROUP'			=> 'گروه “%1$s” به اشتباه در %2$s مشخص شد.',
	'CONV_OPTIONS_BODY'					=> 'این صفحه اطلاعات لازم برای دسترسی به تالار منبع را فراههم می کند. جزئیات پایگاه داده تالار قبلی را وارد کنید؛ تبدیل گر هیچ مطلبی را به پایگاه داده وارد شده اضافه نخواهد کرد،برای پایداری فرآیند تبدیل باید تالار منبع را غیرفعال کنید.',
	'CONV_SAVED_MESSAGES'				=> 'پیغام های ذخیره شده',

	'PRE_CONVERT_COMPLETE'			=> 'همه مراحل پیش پردازش با موفقیت اتمام یافت. ممکن است بخواهید مراحل اصلی پرداش تبدیل گر را شروع کنید. میتوان چند گزینه را به طور دستی تنظیم کنید. بعد از فرایند تبدیل، سطوح دسترسی را تنظیم کنید. شاخص های جستجو را که تبدیل نشده اند را بازسازی کنید و اطمینان حاصل کنید که فایل ها به درستی کپی شده اند، برای مثال آواتار ها و شکلک ها. ',
));
