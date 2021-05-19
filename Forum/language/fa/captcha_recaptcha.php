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
	$lang = [];
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

$lang = array_merge($lang, [
	// Find the language/country code on https://developers.google.com/recaptcha/docs/language
	// If no code exists for your language you can use "en" or leave the string empty
	'RECAPTCHA_LANG'				=> 'fa',

	'CAPTCHA_RECAPTCHA'				=> 'ریکپچا نسخه۲',
	'CAPTCHA_RECAPTCHA_V3'			=> 'ریکپچا نسخه۳',
	'RECAPTCHA_INCORRECT'			=> 'جواب شما نادرست است.',
	'RECAPTCHA_NOSCRIPT'			=> 'لطفا جاوا اسکریپت مرورگر خود را فعال کنید.',
	'RECAPTCHA_NOT_AVAILABLE'		=> 'برای استفاده از کد ریکپچا، باید یک حساب در سایت گوگل ایجاد کنید. <a href="https://www.google.com/recaptcha">www.google.com/recaptcha</a>.',
	'RECAPTCHA_INVISIBLE'			=> 'این کپچا در واقع مخفی خواهد بود. برای تأیید صحت عملکرد آن، باید یک نماد کوچک در گوشه سمت راست پایین این صفحه ظاهر شود.',
	'RECAPTCHA_V3_LOGIN_ERROR_ATTEMPTS'	=> 'شما بیشتر از حداکثر تعداد مجاز ورود به سیستم تلاش کرده اید.<br>علاوه بر نام کاربری و گذرواژه، از ریکپچا نسخه۳ نامرئی برای تأیید اعتبار جلسه شما استفاده خواهد شد.',

	'RECAPTCHA_PUBLIC'				=> 'کلید سایت',
	'RECAPTCHA_PUBLIC_EXPLAIN'		=> 'کلید ریکپچا سایت شما. کلید را می‌توانید از اینجا تهیه کنید. <a href="https://www.google.com/recaptcha">www.google.com/recaptcha</a>. لطفا, از ریکپچا نسخه۲ استفاده کنید که از نوع مخفی است.',
	'RECAPTCHA_V3_PUBLIC_EXPLAIN'	=> 'کلید ریکپچا سایت شما. کلید را می‌توانید از اینجا تهیه کنید. <a href="https://www.google.com/recaptcha">www.google.com/recaptcha</a>. لطفا از ریکپچا نسخه 3 استفاده کنید.',
 	'RECAPTCHA_PRIVATE'				=> 'کلید مخفی',
	'RECAPTCHA_PRIVATE_EXPLAIN'		=> 'کلید مخفی ریکپچا شما. از اینجا تهیه کنید. <a href="https://www.google.com/recaptcha">www.google.com/recaptcha</a>. لطفا از ریکپچا نسخه۲ استفاده کنید که از نوع مخفی است.',
	'RECAPTCHA_V3_PRIVATE_EXPLAIN'	=> 'کلید مخفی ریکپچا شما. از اینجا تهیه کنید.  <a href="https://www.google.com/recaptcha">www.google.com/recaptcha</a>. ریکپچا نسخه2 استفاده کنید.',
	'RECAPTCHA_V3_DOMAIN'				=> 'دامنه درخواست',
	'RECAPTCHA_V3_DOMAIN_EXPLAIN'		=> 'دامنه ای برای دریافت اسکریپت و استفاده برای تأیید درخواست.<br>از <samp>recaptcha.net</samp> استفاده کنید، هنگامی که <samp>google.com</samp> در دسترس نیست.',

	'RECAPTCHA_V3_METHOD'				=> 'روش درخواست',
	'RECAPTCHA_V3_METHOD_EXPLAIN'		=> 'روشی که هنگام تأیید درخواست استفاده می شود.<br>گزینه‌های غیرفعال در تنظیمات شما در دسترس نیستند.',
	'RECAPTCHA_V3_METHOD_CURL'			=> 'cURL',
	'RECAPTCHA_V3_METHOD_POST'			=> 'POST',
	'RECAPTCHA_V3_METHOD_SOCKET'		=> 'Socket',

	'RECAPTCHA_V3_THRESHOLD_DEFAULT'			=> 'آستانه پیش‌فرض',
	'RECAPTCHA_V3_THRESHOLD_DEFAULT_EXPLAIN'	=> 'زمانی استفاده می شود که هیچ یک از اقدامات دیگر قابل استفاده نباشد.',
	'RECAPTCHA_V3_THRESHOLD_LOGIN'				=> 'آستانه ورود',
	'RECAPTCHA_V3_THRESHOLD_POST'				=> 'آستانه پست',
	'RECAPTCHA_V3_THRESHOLD_REGISTER'			=> 'آستانه ثبت نام',
	'RECAPTCHA_V3_THRESHOLD_REPORT'				=> 'آستانه گزارش',
	'RECAPTCHA_V3_THRESHOLDS'					=> 'آستانه ها',
	'RECAPTCHA_V3_THRESHOLDS_EXPLAIN'			=> 'ریکپچا نسخه۳ یک نمره را برمیگرداند (<samp>1.0</samp> به احتمال زیاد یک تعامل خوب است، <samp>0.0</samp> به احتمال زیاد یک ربات است). در اینجا می‌توانید حداقل امتیاز را برای هر اقدام تعیین کنید.',
	'EMPTY_RECAPTCHA_V3_REQUEST_METHOD'			=> 'ریکپچا نسخه۳ لازم است بداند از کدام روش موجود می خواهید هنگام تأیید درخواست استفاده کنید.',
]);
