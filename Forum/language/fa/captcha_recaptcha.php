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
	'RECAPTCHA_LANG'				=> 'fa', // زبان مربوطه را اینجا پیدا کنید https://developers.google.com/recaptcha/docs/language - If no code exists for your language you can use "en" or leave the string empty
	'RECAPTCHA_NOT_AVAILABLE'		=> 'برای استفاده از reCaptcha شما باید یک حساب روی <a href="http://www.google.com/recaptcha">www.google.com/recaptcha</a> ایجاد کرده باشید.',
	'CAPTCHA_RECAPTCHA'				=> 'reCaptcha',
	'RECAPTCHA_INCORRECT'			=> 'جواب شما نادرست است.',
	'RECAPTCHA_NOSCRIPT'			=> 'لطفا جاوا اسکریپت مرورگر خود را فعال کنید.',

	'RECAPTCHA_PUBLIC'				=> 'کلید عمومی reCaptcha',
	'RECAPTCHA_PUBLIC_EXPLAIN'		=> 'کلید عمومی reCaptcha شما. کلیدها می تواند روی <a href="http://www.google.com/recaptcha">www.google.com/recaptcha</a> تهیه شود.',
	'RECAPTCHA_PRIVATE'				=> 'کلید خصوصی reCaptcha',
	'RECAPTCHA_PRIVATE_EXPLAIN'		=> 'Your private reCaptcha key. Keys can be obtained on <a href="http://www.google.com/recaptcha">www.google.com/recaptcha</a>.',
	'RECAPTCHA_PRIVATE_EXPLAIN' 	=> 'کلید خصوصی reCaptcha شما. کلیدها می تواند روی <a href="http://www.google.com/recaptcha">www.google.com/recaptcha</a> تهیه شود.',
	'RECAPTCHA_EXPLAIN'				=> 'جهت جلوگیری از ارسال خودکارقسمت مربوطه را کامل کنید.',
));
