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

// Banning
$lang = array_merge($lang, array(
	'1_HOUR'		=> '1 ساعت',
	'30_MINS'		=> '30 دقیقه',
	'6_HOURS'		=> '6 ساعت',

	'ACP_BAN_EXPLAIN'	=> 'در این بخش می توانید کاربران را بر اساس نام،IP و یا آدرس ایمیل تحریم کنید. این روش ها از دسترسی کاربر به تمامی بخش های تالار جلوگیری می کند. اگر بخواهید می توانید متن کوتاهی را (حداکثر 3000 کاراکتر) درباره علت تحریم کاربری ارائه دهید. این در رویداد های مدیریت نمایش داده خواهد شد. همچنان میتوانید تاریخ انقضای تحریم را مشخص کنید، اگر می خواهید تحریم کاربری بعد از مدتی لغو شود،. <span style="text-decoration: underline;">تا -&gt;</span> را انتخاب کنید و تاریخی را در فرمت <kbd>YYYY-MM-DD</kbd> وارد کنید.',

	'BAN_EXCLUDE'			=> 'جدا کردن از تحریم شدگان',
	'BAN_LENGTH'			=> 'تاریخ انقضای تحریم شدگان',
	'BAN_REASON'			=> 'دلیل تحریم',
	'BAN_GIVE_REASON'		=> 'دلیل نمایش داده شده به کاربر تحریم شده',
	'BAN_UPDATE_SUCCESSFUL'	=> 'لیست تحریم با موفقیت بروزرسانی شد.',
	'BANNED_UNTIL_DATE'		=> 'تا %s', // برای مثال : "until Mon 13.Jul.2009, 14:44"
	'BANNED_UNTIL_DURATION'	=> '%1$s (تا %2$s)', // Example: "7 days (until Tue 14.Jul.2009, 14:44)"

	'EMAIL_BAN'					=> 'تحریم یک یا چند آدرس ایمیل',
	'EMAIL_BAN_EXCLUDE_EXPLAIN'	=> 'اگر این گزینه را فعال کنید، ایمیل وارد شده جدا از سایر ایمیل های تحریم شده نگهداری خواهد شد.',
	'EMAIL_BAN_EXPLAIN'			=> 'برای مشخص کردن بیش از یک ایمیل،هر ایمیل را در سطر جداگانه ای وارد کنید. برای ایمیل هایی که قسمتی از ایمیل مورد نظر هستند،از (*) استفاده کنید. برای مثال <samp>*@hotmail.com</samp>, <samp>*@*.domain.tld</samp>, و یا غیره ... .',
	'EMAIL_NO_BANNED'			=> 'آدرس ایمیل تحریم شده ای وجود ندارد',
	'EMAIL_UNBAN'				=> 'لغو تحریم ایمیل ها',
	'EMAIL_UNBAN_EXPLAIN'		=> 'با ادغام ماوس و صفحه کلید همچنین با کمک مرورگرتان می تواند بیش از یک ایمیل را از حالت تحریم خارج کنید. به ایمیل هایی که جدا مانده اند تاکید شده است.',

	'IP_BAN'					=> 'تحریم یک یا چند IP',
	'IP_BAN_EXCLUDE_EXPLAIN'	=> 'اگر این گزینه را فعال کنید، IP وارد شده جدا از سایر تحریم شدگان نگهداری خواهد شد.',
	'IP_BAN_EXPLAIN'			=> 'برای مشخص کردن چند IP و یا hostname هر کدام از آنها را در سطر های جداگانه وارد کنید. برای مشخص کردن بازه ای از IP ها، IP اولی و آخری را با خط تیره (-) مشخص کنید. برای مشخص کردن کلمات از (*) استفاده کنید.',
	'IP_HOSTNAME'				=> 'آدرس IP و یا hostname',
	'IP_NO_BANNED'				=> 'IP تحریم شده ای وجود ندارد',
	'IP_UNBAN'					=> 'لغو تحریم IP ها',
	'IP_UNBAN_EXPLAIN'			=> 'با ادغام ماوس و صفحه کلید همچنین با کمک مرورگرتان می تواند بیش از یک IP را از حالت تحریم خارج کنید. به IP هایی که جدا مانده اند تاکید شده است.',

	'LENGTH_BAN_INVALID'		=> 'تاریخ باید در فرمت <kbd>YYYY-MM-DD</kbd> باشد.',

        'OPTIONS_BANNED'			=> 'تحریم شده',	
        'OPTIONS_EXCLUDED'			=> 'تحریم لغو شده',

	'PERMANENT'		=> 'همیشگی',

	'UNTIL'						=> 'تا',
	'USER_BAN'					=> 'تحریم یک یا چند نام کاربری',
	'USER_BAN_EXCLUDE_EXPLAIN'	=> 'اگر این گزینه را فعال کنید، کاربر وارد شده جدا از سایر کاربران تحریم شده نگهداری خواهد شد.',
	'USER_BAN_EXPLAIN'			=> 'برای وارد کردن چندین نام کاربری، هر یک را در سطر جداگانه ای وارد کنید. از <span style="text-decoration: underline;">جستجوی کاربران</span> برای یافتن نام های کاربری کاربران استفاده کنید.',
	'USER_NO_BANNED'			=> 'نام کاربری تحریم شده ای وجود ندارد',
	'USER_UNBAN'				=> 'لغو تحریم نام های کاربری',
	'USER_UNBAN_EXPLAIN'		=> 'با ادغام ماوس و صفحه کلید همچنین با کمک مرورگرتان می تواند بیش از یک کاربر را از حالت تحریم خارج کنید. به کاربرانی  که جدا مانده اند تاکید شده است.',
));
