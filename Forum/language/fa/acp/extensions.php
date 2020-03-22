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
	'EXTENSION'					=> 'افزونه',
	'EXTENSIONS'				=> 'افزونه ها',
	'EXTENSIONS_ADMIN'			=> 'مدیریت افزونه ها',
	'EXTENSIONS_EXPLAIN'		=> 'مدیریت افزونه ابزاریست در انجمن شما که میتوانید افزونه های نصب شده را مدیریت ، مشاهده و اطلاعات آنها را بررسی کنید.',
	'EXTENSION_INVALID_LIST'	=> 'افزونه “%s” معتبر نیست.<br />%s<br /><br />',
	'EXTENSION_NOT_AVAILABLE'	=> 'افزونه انتخابی در انجمن شما قابل دسترس نیست. لطفا نسخه phpBB و php انجمن خود را بررسی کنید. (مشاهده صفحه جزئیات).',
	'EXTENSION_DIR_INVALID'		=> 'افزونه انتخابی در مسیر نامعتبری آپلود شده است و قابل فعال سازی نیست.',
	'EXTENSION_NOT_ENABLEABLE'	=> 'افزونه انتخابی قابل فعال سازی نمیباشد, لطفا موارد لازم را بررسی نمایید.',
	'EXTENSION_NOT_INSTALLED'	=> 'افزونه %s دردسترس نیست. لطفا بررسی کنید که به درستی نصب شده است.',


	'DETAILS'				=> 'جزئیات',

	'EXTENSIONS_DISABLED'	=> 'افزونه های غیر فعال',
	'EXTENSIONS_ENABLED'	=> 'افزونه های فعال',

	'EXTENSION_DELETE_DATA'	=> 'حذف کلی اطلاعات',
	'EXTENSION_DISABLE'		=> 'غیر فعال',
	'EXTENSION_ENABLE'		=> 'فعال',

	'EXTENSION_DELETE_DATA_EXPLAIN'	=> 'حذف کلی اطلاعات افزونه به معنی حذف کامل داده ها و تنظیمات مربوط به آن است. اما فایل ها و پوشه های افزونه همچنان باقیست و میتوان دوباره آن را فعال کرد.',
	'EXTENSION_DISABLE_EXPLAIN'		=> 'غیر فعال کردن افزونه به معنای حفظ داده ها ، فایل ها و تنظیمات است اما عملکرد و کارایی افزونه تا فعال کردن مجدد از بین خواهد رفت.',
	'EXTENSION_ENABLE_EXPLAIN'		=> 'فعال کردن افزونه به شما اجازه استفاده از آن را میدهد.',

	'EXTENSION_DELETE_DATA_IN_PROGRESS'	=> 'داده های افزونه در حال حذف می باشند. لطفا تا کامل شدن مراحل حذف ،این صفحه را ترک یا رفرش نکنید.',
	'EXTENSION_DISABLE_IN_PROGRESS'	=> 'افزونه در حال غیر فعال شدن است. تا کامل شدن مراحل ، لطفا این صفحه را ترک یا رفرش نکنید.',
	'EXTENSION_ENABLE_IN_PROGRESS'	=> 'افزونه در حال فعال شدن است. تا کامل شدن مراحل لطفا این صفحه را ترک یا رفرش نکنید.',

	'EXTENSION_DELETE_DATA_SUCCESS'	=> 'داده های مربوط به افزونه با موفقیت حذف شد.',
	'EXTENSION_DISABLE_SUCCESS'		=> 'افزونه با موفقیت غیر فعال شد.',
	'EXTENSION_ENABLE_SUCCESS'		=> 'افزونه با موفقیت فعال شد.',

	'EXTENSION_NAME'			=> 'افزونه ها',
	'EXTENSION_ACTIONS'			=> 'عملکرد',
	'EXTENSION_OPTIONS'			=> 'گزینه ها',
	'EXTENSION_INSTALL_HEADLINE'=> 'نصب افزونه',
	'EXTENSION_INSTALL_EXPLAIN'	=> '<ol>
			<li>دانلود یک افزونه از پایگاه phpBB</li>
			<li>در آوردن از حالت فشرده و اپلود فایل ها در پوشه <samp>ext/</samp> واقع در دایرکتوری phpBB</li>
			<li>فعال سازی افزونه واقع در بخش مدیریت افزونه ها</li>
		</ol>',
	'EXTENSION_UPDATE_HEADLINE'	=> 'مراحل به روز رسانی افزونه',
	'EXTENSION_UPDATE_EXPLAIN'	=> '<ol>
			<li>غیر فعال کردن افزونه</li>
			<li>حذف فایل های افزونه آپلود شده</li>
			<li>بارگزاری فایل های جدید</li>
			<li>فعال سازی افزونه</li>
		</ol>',
	'EXTENSION_REMOVE_HEADLINE'	=> 'مراحل حذف کامل افزونه از انجمن',
	'EXTENSION_REMOVE_EXPLAIN'	=> '<ol>
			<li>غیر فعال کردن افزونه</li>
			<li>حذف داده های افزونه</li>
			<li>حذف فایل های افزونه آپلود شده</li>
		</ol>',

	'EXTENSION_DELETE_DATA_CONFIRM'	=> 'آیا از حذف داده های مرتبط با افزونه “%s” اطمینان دارید؟<br /><br />این کار باعث میشود تمامی تنظیمات مربوط به افزونه از بین برود.',
	'EXTENSION_DISABLE_CONFIRM'		=> 'آیا از غیر فعال کردن افزونه “%s” اطمینان دارید؟',
	'EXTENSION_ENABLE_CONFIRM'		=> 'آیا از فعال کردن افزونه “%s” اطمینان دارید؟',
	'EXTENSION_FORCE_UNSTABLE_CONFIRM'	=> 'آیا از استفاده کردن اجباری این افزونه اطمینان دارید؟',

	'RETURN_TO_EXTENSION_LIST'	=> 'بازگشت به لیست افزونه ها',

	'EXT_DETAILS'			=> 'جزئیات افزونه ها',
	'DISPLAY_NAME'			=> 'نمایش نام',
	'CLEAN_NAME'			=> 'نام',
	'TYPE'					=> 'نوع',
	'DESCRIPTION'			=> 'توضیحات',
	'VERSION'				=> 'نسخه',
	'HOMEPAGE'				=> 'صفحه اصلی',
	'PATH'					=> 'مسیر فایل',
	'TIME'					=> 'تاریخ انتشار',
	'LICENSE'				=> 'لایسنس',

	'REQUIREMENTS'			=> 'پیش نیاز',
	'PHPBB_VERSION'			=> 'phpBB ورژن',
	'PHP_VERSION'			=> 'PHP ورژن',
	'AUTHOR_INFORMATION'	=> 'اطلاعات نویسنده',
	'AUTHOR_NAME'			=> 'نام',
	'AUTHOR_EMAIL'			=> 'ایمیل',
	'AUTHOR_HOMEPAGE'		=> 'صفحه اصلی',
	'AUTHOR_ROLE'			=> 'نقش',

	'NOT_UP_TO_DATE'		=> '%s به روز نیست',
	'UP_TO_DATE'			=> '%s کاملا به روز است',
	'ANNOUNCEMENT_TOPIC'	=> 'انتشار اطلاعیه',
	'DOWNLOAD_LATEST'		=> 'دانلود نسخه',
	'NO_VERSIONCHECK'		=> 'هیچ اطلاعاتی برای بررسی نسخه دریافت نشد.',

	'VERSIONCHECK_FORCE_UPDATE_ALL'		=> 'بررسی مجدد نسخه',
	'FORCE_UNSTABLE'					=> 'بررسی همیشگی جهت نسخه های ناپایدار',
	'EXTENSIONS_VERSION_CHECK_SETTINGS'	=> 'تنظیمات بررسی نسخه',

	'BROWSE_EXTENSIONS_DATABASE'		=> 'جستجو در پایگاه افزونه ها',
	
	'META_FIELD_NOT_SET'	=> 'بخش متا دیتا %s تنظیم نشده است.',
	'META_FIELD_INVALID'	=> 'بخش متا %s نامعتبر است.',
));
