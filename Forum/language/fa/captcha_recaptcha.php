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

	'CAPTCHA_RECAPTCHA'				=> 'reCaptcha v2',
	'CAPTCHA_RECAPTCHA_V3'			=> 'reCaptcha v3',
	'RECAPTCHA_INCORRECT'			=> 'جواب شما نادرست است.',
	'RECAPTCHA_NOSCRIPT'			=> 'لطفا جاوا اسکریپت مرورگر خود را فعال کنید.',
	'RECAPTCHA_NOT_AVAILABLE'		=> 'برای استفاده از کد ریکپچا, بایستی اکانتی در سایت گوگل ایجاد کنید. <a href="https://www.google.com/recaptcha">www.google.com/recaptcha</a>.',
	'RECAPTCHA_INVISIBLE'			=> 'این کپچا در واقع مخفی خواهد بود. برای تأیید صحت عملکرد آن ، باید یک نماد کوچک در گوشه سمت راست پایین این صفحه ظاهر شود.',

	'RECAPTCHA_PUBLIC'				=> 'کلید سایت',
	'RECAPTCHA_PUBLIC_EXPLAIN'		=> 'کلید reCAPTCHA سایت شما. کلید را میتوانید از اینجا تهیه کنید. <a href="https://www.google.com/recaptcha">www.google.com/recaptcha</a>. لطفا, از reCAPTCHA ورژن2  استفاده کنید که از نوع مخفی است.',
	'RECAPTCHA_V3_PUBLIC_EXPLAIN'	=> 'کلید reCAPTCHA سایت شما. کلید را میتوانید از اینجا تهیه کنید. <a href="https://www.google.com/recaptcha">www.google.com/recaptcha</a>. لطفا از reCAPTCHA ورژن 3 استفاده کنید. ',
 	'RECAPTCHA_PRIVATE'				=> 'کلید reCaptcha سایت',
	'RECAPTCHA_PRIVATE_EXPLAIN'		=> 'کلید مخفی reCAPTCHA شما. از اینجا تهیه کنید. <a href="https://www.google.com/recaptcha">www.google.com/recaptcha</a>. لطفا از reCAPTCHA ورژن2 استفاده کنید که از نوع مخفی است.',
	'RECAPTCHA_V3_PRIVATE_EXPLAIN'	=> 'کلید مخفی reCAPTCHA شما. از اینجا تهیه کنید.  <a href="https://www.google.com/recaptcha">www.google.com/recaptcha</a>. reCAPTCHA ورژن2 استفاده کنید.',
    'RECAPTCHA_V3_DOMAIN'				=> 'Request domain',
	'RECAPTCHA_V3_DOMAIN_EXPLAIN'		=> 'The domain to fetch the script from and to use when verifying the request.<br>Use <samp>recaptcha.net</samp> when <samp>google.com</samp> is not accessible.',

	'RECAPTCHA_V3_METHOD'				=> 'Request method',
	'RECAPTCHA_V3_METHOD_EXPLAIN'		=> 'The method to use when verifying the request.<br>Disabled options are not available within your setup.',
	'RECAPTCHA_V3_METHOD_CURL'			=> 'cURL',
	'RECAPTCHA_V3_METHOD_POST'			=> 'POST',
	'RECAPTCHA_V3_METHOD_SOCKET'		=> 'Socket',

	'RECAPTCHA_V3_THRESHOLD_DEFAULT'			=> 'Default threshold',
	'RECAPTCHA_V3_THRESHOLD_DEFAULT_EXPLAIN'	=> 'Used when none of the other actions are applicable.',
	'RECAPTCHA_V3_THRESHOLD_LOGIN'				=> 'Login threshold',
	'RECAPTCHA_V3_THRESHOLD_POST'				=> 'Post threshold',
	'RECAPTCHA_V3_THRESHOLD_REGISTER'			=> 'Register threshold',
	'RECAPTCHA_V3_THRESHOLD_REPORT'				=> 'Report threshold',
	'RECAPTCHA_V3_THRESHOLDS'					=> 'Thresholds',
	'RECAPTCHA_V3_THRESHOLDS_EXPLAIN'			=> 'reCAPTCHA v3 returns a score (<samp>1.0</samp> is very likely a good interaction, <samp>0.0</samp> is very likely a bot). Here you can set the minimum score per action.',
	'EMPTY_RECAPTCHA_V3_REQUEST_METHOD'			=> 'reCAPTCHA v3 requires to know which available method you want to use when verifying the request.',
]);
