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
	'ACP_SEARCH_INDEX_EXPLAIN'				=> 'در این بخش می توانید مرجع های جستجو را مدیریت کنید،اگر تنها از یک مرجع استفاده می کنید،باید شاخص های سایر مراجع را حذف کنید،تغییر تنظیمات جستجو عملکرد جستجو را بالا خواهد برد.',
	'ACP_SEARCH_SETTINGS_EXPLAIN'			=> 'در این بخش می توانید انتخاب کنید که از کدام مرجع جستجو برای شاخص بندی استفاده شود.',

	'COMMON_WORD_THRESHOLD'					=> 'آستانه کلمات عمومی',
	'COMMON_WORD_THRESHOLD_EXPLAIN'			=> 'کلماتی که در درصد بیشتری از پست ها حضور داشته باشند جزو کلمات عمومی محسوب خواهند شد،این کلمات در نتایج جستجو محسوب نمی شوند،اگر کلمه ای در بیش از 100 پست وجود داشته باشد این تنظیم برای آن کلمه فعال خواهد شد.برای وارد کردن کلمات به طور دستی باید شاخص جستجو را از نو ایجاد کنید.',
	'CONFIRM_SEARCH_BACKEND'				=> 'Are you sure you wish to switch to a different search backend? After changing the search backend you will have to create an index for the new search backend. If you don’t plan on switching back to the old search backend you can also delete the old backend’s index in order to free system resources.',
	'CONTINUE_DELETING_INDEX'				=> 'ادامه فرآیند حذف شاخص گذشته',
	'CONTINUE_DELETING_INDEX_EXPLAIN'		=> 'فرآیند حذف شاخص شروع شد،برای دسترسی به صفحه شاخص جستجو باید این فرآیند تکمیل شده و یا لغو شود.',
	'CONTINUE_INDEXING'						=> 'ادامه فرآیند شاخص بندی گذشته',
	'CONTINUE_INDEXING_EXPLAIN'				=> 'فرآیند  شاخص بندی آغاز شد،برای دسترسی به صفحه شاخص جستجو باید این فرآیند تکمیل شده و یا لغو شود.',
	'CREATE_INDEX'							=> 'ایجاد شاخص',

	'DELETE_INDEX'							=> 'حذف شاخص',
	'DELETING_INDEX_IN_PROGRESS'			=> 'حذف شاخص در حال اجرا می باشد',
	'DELETING_INDEX_IN_PROGRESS_EXPLAIN'	=> 'در حال حاضر مرجع جستجو شاخص خود را پاکسازی می کند،این فرآیند ممکن است چند دقیقه به طول انجامد.',



	'FULLTEXT_MYSQL_INCOMPATIBLE_DATABASE'	=> 'The MySQL fulltext backend can only be used with MySQL4 and above.',
	'FULLTEXT_MYSQL_NOT_SUPPORTED'			=> 'MySQL fulltext indexes can only be used with MyISAM or InnoDB tables. MySQL 5.6.4 or later is required for fulltext indexes on InnoDB tables.',



	'FULLTEXT_MYSQL_TOTAL_POSTS'			=> 'تعداد کل پست های شاخص بندی شده',
	'FULLTEXT_MYSQL_MIN_SEARCH_CHARS_EXPLAIN'	=> 'کلماتی حداقل با این تعداد کاراکتر در جستجو ها محسوب خواهند شد،این تعداد کاراکتر از تنظیمات پیکربندی MYSQL قابل تغییر است.',
	'FULLTEXT_MYSQL_MAX_SEARCH_CHARS_EXPLAIN'	=> 'کلماتی که بیشتر از این تعداد کاراکتر باشند در جستجو ها محسوب خواهند شد،این رقم از تنظیمات پیکربندی MYSQL قابل تغییر است.',

	'FULLTEXT_POSTGRES_INCOMPATIBLE_DATABASE'	=> 'The PostgreSQL fulltext backend can only be used with PostgreSQL.',
	'FULLTEXT_POSTGRES_TOTAL_POSTS'			=> 'Total number of indexed posts',
	'FULLTEXT_POSTGRES_VERSION_CHECK'		=> 'PostgreSQL version',
	'FULLTEXT_POSTGRES_TS_NAME'				=> 'Text search Configuration Profile:',
	'FULLTEXT_POSTGRES_MIN_WORD_LEN'			=> 'Minimum word length for keywords',
	'FULLTEXT_POSTGRES_MAX_WORD_LEN'			=> 'Maximum word length for keywords',
	'FULLTEXT_POSTGRES_VERSION_CHECK_EXPLAIN'		=> 'This search backend requires PostgreSQL version 8.3 and above.',
	'FULLTEXT_POSTGRES_TS_NAME_EXPLAIN'				=> 'The Text search configuration profile used to determine the parser and dictionary.',
	'FULLTEXT_POSTGRES_MIN_WORD_LEN_EXPLAIN'			=> 'Words with at least this many characters will be included in the query to the database.',
	'FULLTEXT_POSTGRES_MAX_WORD_LEN_EXPLAIN'			=> 'Words with no more than this many characters will be included in the query to the database.',

	'FULLTEXT_SPHINX_CONFIGURE'				=> 'Configure the following settings to generate sphinx config file',
	'FULLTEXT_SPHINX_DATA_PATH'				=> 'Path to data directory',
	'FULLTEXT_SPHINX_DATA_PATH_EXPLAIN'		=> 'It will be used to store the indexes and log files. You should create this directory outside the web accessible directories. (should have a trailing slash)',
	'FULLTEXT_SPHINX_DELTA_POSTS'			=> 'Number of posts in frequently updated delta index',
	'FULLTEXT_SPHINX_HOST'					=> 'Sphinx search daemon host',
	'FULLTEXT_SPHINX_HOST_EXPLAIN'			=> 'Host on which the sphinx search daemon (searchd) listens. Leave empty to use the default localhost',
	'FULLTEXT_SPHINX_INDEXER_MEM_LIMIT'		=> 'Indexer memory limit',
	'FULLTEXT_SPHINX_INDEXER_MEM_LIMIT_EXPLAIN'	=> 'This number should at all times be lower than the RAM available on your machine. If you experience periodic performance problems this might be due to the indexer consuming too many resources. It might help to lower the amount of memory available to the indexer.',
	'FULLTEXT_SPHINX_MAIN_POSTS'			=> 'Number of posts in main index',
	'FULLTEXT_SPHINX_PORT'					=> 'Sphinx search daemon port',
	'FULLTEXT_SPHINX_PORT_EXPLAIN'			=> 'Port on which the sphinx search daemon (searchd) listens. Leave empty to use the default Sphinx API port 9312',
	'FULLTEXT_SPHINX_WRONG_DATABASE'		=> 'The sphinx search for phpBB supports MySQL and PostgreSQL only.',
	'FULLTEXT_SPHINX_CONFIG_FILE'			=> 'Sphinx config file',
	'FULLTEXT_SPHINX_CONFIG_FILE_EXPLAIN'	=> 'The generated content of the sphinx config file. This data needs to be pasted into the sphinx.conf which is used by sphinx search daemon. Replace the [dbuser] and [dbpassword] placeholders with your database credentials.',
	'FULLTEXT_SPHINX_NO_CONFIG_DATA'		=> 'The sphinx data directory path is not defined. Please define the path and submit to generate the config file.',

	'GENERAL_SEARCH_SETTINGS'				=> 'تنظیمات عمومی جستجو',
	'GO_TO_SEARCH_INDEX'					=> 'رفتن به صفحه اصلی جستجو',

	'INDEX_STATS'							=> 'آمار شاخص',
	'INDEXING_IN_PROGRESS'					=> 'ضاخص بندی در حال اجرا می باشد',
	'INDEXING_IN_PROGRESS_EXPLAIN'			=> 'مرجع جستجو در حال شاخص بندی تمامی پست های تالار می باشد،این فرآیند ممکن است بسته به حجم تالار بین چند دقیقه تا چند ساعت به طول بیانجامد.',

	'LIMIT_SEARCH_LOAD'						=> 'محدودیت بارگذاری صفحه جستجو',
	'LIMIT_SEARCH_LOAD_EXPLAIN'				=> 'اگر بارگذاری صفحه جستجو بیش از 1 دقیقه طول بکشد،صفحه آفلاین خواهد شد. 1.0 معادل 100% مصرف یک پردازنده می باشد. این عملکرد فقط در سرور های UNIX فعال است.',

	'MAX_SEARCH_CHARS'						=> 'حداکثر تعداد کاراکتر کلمات برای شاخص بندی شدن در جستجو',
	'MAX_SEARCH_CHARS_EXPLAIN'				=> 'کلماتی که بیشتر از این تعداد کاراکتر نباشند،در جستجو شاخص بندی خواهند شد.',
	'MAX_NUM_SEARCH_KEYWORDS'				=> 'بیشترین تعداد مورد قبول کلمات کلیدی',
	'MAX_NUM_SEARCH_KEYWORDS_EXPLAIN'		=> 'بیشترین تعداد کلمه ای که کاربران می توانند جستجو کنند،برای جستجوی نامحدود 0 را وارد کنید.',
	'MIN_SEARCH_CHARS'						=> 'حداقل تعداد کاراکتر کلمات برای شاخص بندی شدن در جستجو',
	'MIN_SEARCH_CHARS_EXPLAIN'				=> 'کلماتی که کمتر از ایت تعداد کاراکتر باشند در جستجو شاخص بندی خواهند شد.',
	'MIN_SEARCH_AUTHOR_CHARS'				=> 'حداقل تعداد کاراکتر برای جستجوی نام نویسنده',
	'MIN_SEARCH_AUTHOR_CHARS_EXPLAIN'		=> 'در جستجوی قسمتی از نام نویسنده کاربر باید حداقل این تعداد کاراکتر را وارد کند،اگر نام نویسنده ای کوتاه تر از این تعداد کاراکتر باشد باز هم اگر نام کامل وی در جستجو درج شود،پست های او نمایش داده خواهند شدو',

	'PROGRESS_BAR'							=> 'نوار پیشرفت',

	'SEARCH_GUEST_INTERVAL'					=> 'محدودیت زمانی جستجو برای مهمان ها',
	'SEARCH_GUEST_INTERVAL_EXPLAIN'			=> 'تعدا ثانیه هایی که مهمانان باید بین دو جستجو صبر کنند،اگر مهمانی جستجویی را انجام دهد،مهمانان دیگر باید این تعداد ثانیه صبر کنند تا بتوانند جستجوی دیگری را انجام دهند.',
	'SEARCH_INDEX_CREATE_REDIRECT'			=> array(
		2	=> 'همه پست ها تا id %1$d از بین %2$d پست در این مرحله شاخص بندی شدند.رتبه شاخص بندی کنونی تقریبا %3$در ثانیه می باشد.<br />شاخص بندی در حال اجرا است.',
	),
	'SEARCH_INDEX_DELETE_REDIRECT'			=> array(
		2	=> 'تمامی پست های شماره %2$d پاک شدند, که تعداد %1$d پست در مرحله اجراست<br />',
	),
	'SEARCH_INDEX_DELETE_REDIRECT_RATE'		=> array(
		2	=> 'زمان تخمینی حذف پست ها % ثانیه به ازاء هر پست است.<br />فرآیند حذف در حال اجرا',
	),
	'SEARCH_INDEX_CREATED'					=> 'با موفقیت تمامی پست ها در پایگاه داده شاخص بندی شدند.',
	'SEARCH_INDEX_REMOVED'					=> 'با موفقیت شاخص این مرجع جستجو حذف شد.',
	'SEARCH_INTERVAL'						=> 'محدودیت زمانی برای جستجوی کاربران',
	'SEARCH_INTERVAL_EXPLAIN'				=> 'تعداد ثانیه هایی که کاربر باید بین دوجستجو صبر کند،این زمان برای هر کاربر مستقل می باشد.',
	'SEARCH_STORE_RESULTS'					=> 'درازای cache برای نتایج جستجو',
	'SEARCH_STORE_RESULTS_EXPLAIN'			=> 'cache نتایج جستجو بعد از این مدت زمان (ثانیه) حذف خواهد شد،برای غیرفعال کردن این ویژگی 0 را وارد کنید.',
	'SEARCH_TYPE'							=> 'مرجع جستجو',
	'SEARCH_TYPE_EXPLAIN'					=> 'phpBB به شما این اجازه را می دهد تا مرجع جستجوی خود را انتخاب کنید،به صورت پیشفرض از مرجع جستجوی متنی phpBB استفاده می شود.',
	'SWITCHED_SEARCH_BACKEND'				=> 'مرجع جستجو را تغییر داده اید،برای استفاده از مرجع جستجو باید مطمئن باشید که شاخصی برای این مرجع وجود داشته باشد.',

	'TOTAL_WORDS'							=> 'تعداد کل کلمات شاخص بندی شده',
	'TOTAL_MATCHES'							=> 'تعدا کل کلماتی که در ارتباط با پست شاخص بندی شده اند',

	'YES_SEARCH'							=> 'فعال سازی امکانات جستجو',
	'YES_SEARCH_EXPLAIN'					=> 'فعال سازی نمای جستجو شامل جستجوی اعضا',
	'YES_SEARCH_UPDATE'						=> 'فعال سازی بروزرسانی متنی',
	'YES_SEARCH_UPDATE_EXPLAIN'				=> 'هنگام ارسال پست های جدید،شاخص جستجو را بروزرسانی می کند،اگر جستجو غیرفعال باشد این گزینه عملکردی نخواهد داشت.',
));
