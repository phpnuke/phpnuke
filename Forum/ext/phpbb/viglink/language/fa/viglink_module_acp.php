<?php
/**
 *
 * VigLink extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
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
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'ACP_VIGLINK_SETTINGS'			=> 'VigLink تنظیمات',
	'ACP_VIGLINK_SETTINGS_EXPLAIN'	=> 'VigLink یک سرویس شخص سوم که به صورت گزینشی آدرس های پست های کاربران را در این سرویس بدون تغییر ایجاد میکند. هنگامیکه کاربران و مهمان ها روی لینک های شما کلیک میکنند VigLink حق پرداختی به پروژه phpBB تقدیم  میکند. با فعال سازی این افزونه و پرداخت هایی که این سرویس انجا میدهد شما در واقع به تیم پشتیبانی سیستم متن باز phpBB کمک شایانی کرده اید. ',
	'ACP_VIGLINK_SETTINGS_CHANGE'	=> 'شما هر وقت بخواهید میتوانید تنظیمات را تغییر دهید “<a href="%1$s">VigLink settings</a>” پنل',
	'ACP_VIGLINK_SUPPORT_EXPLAIN'	=> 'شما هرگز دیگر به این صفحه هنگامیکه گزینه های مورد نظر را ثبت میکنید هدایت نخواهید شد.',
	'ACP_VIGLINK_ENABLE'			=> 'فعال سازی VigLink',
	'ACP_VIGLINK_ENABLE_EXPLAIN'	=> 'فعال سازی استفاده از خدمات VigLink',
	'ACP_VIGLINK_EARNINGS'			=> 'برای خود کسب درآمد کنید ، اختیاری',
	'ACP_VIGLINK_EARNINGS_EXPLAIN'  => 'شما میتوانید برای خودتان نیز کسب در آمد کنید تنها کافیست در سایت VigLink ثبت نام کنید.',
	'ACP_VIGLINK_DISABLED_PHPBB'	=> 'سرویس VigLink توسط phpBB غیر فعال شده است.',
	'ACP_VIGLINK_CLAIM'				=> 'کسب در آمد خودتان',
	'ACP_VIGLINK_CLAIM_EXPLAIN'		=> 'شما میتوانید برای خود نیزکسب در آمد کنید به جای اینکه این سیستم را برای کسب در آمد پروژه phpBB کنید.  کافیست به سایت VigLink رفته و در آن ثبت نام کنید و به قسمت VigLink Convert رفته و تبدیل اکانت کنید.',
	'ACP_VIGLINK_CONVERT_ACCOUNT'	=> 'تبدیل اکانت',
	'ACP_VIGLINK_NO_CONVERT_LINK'	=> 'لینک تبدیل VigLink قابل بازیابی نیست.',
));
