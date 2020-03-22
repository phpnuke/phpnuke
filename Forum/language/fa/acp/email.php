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

// Email settings
$lang = array_merge($lang, array(
	'ACP_MASS_EMAIL_EXPLAIN'		=> 'در این بخش می توانید <strong>با فعال کردن ایمیل دسته جمعی</strong> به تمامی کاربران و یا به تمامی کاربران موجود در گروه خاصی ایمیل بفرستید. ایمیل فرستنده همان ایمیلی میباشد که به عنوان ایمیل مدیریت تایین شده است.  کپی متن وارد شده در بخش زیر به ایمیل کاربران مشخص شده ارسال خواهد شد. به صورت پیشفرض در هر ایمیل می توانید 50 گیرنده مشخص کنید، برای تعداد گیرنده بیش تر،ایمیل بیش تری ارسال خواهد شد. اگر برای تعداد زیادی گیرنده ایمیل می فرستید، لطفا صبور باشید. در ارسال ایمیل های دسته جمعی وقت گیر بودن این فرآیند طبیعی است.بعد از اتمام ارسال ایمیل ها اسکریپت به شما اطلاع خواهد داد.',
	'ALL_USERS'						=> 'همه کاربران',

	'COMPOSE'				=> 'ایجاد ایمیل',

	'EMAIL_SEND_ERROR'		=> 'یک یا چند خطا در طول فرآیند ارسال روی داد،برای جزئیات بیشتر به %sرویدادهای خطا%s مراجعه کنید.',
	'EMAIL_SENT'			=> 'این پیغام فرستاده شد.',
	'EMAIL_SENT_QUEUE'		=> 'این پیغام را در صف ارسال قرار بده !',

	'LOG_SESSION'			=> 'برای موارد ضروری،رویدادهای نشست ایمیل را ثبت کن!',

	'SEND_IMMEDIATELY'		=> 'ارسال سریع',
	'SEND_TO_GROUP'			=> 'ارسال به گروه',
	'SEND_TO_USERS'			=> 'ارسال به کاربران',
	'SEND_TO_USERS_EXPLAIN'	=> 'وارد کردن نام کاربری در این فیلد،گروه مشخص شده را باطل خواهد کرد،هر نام کاربری را در سطر جداگانه ای وارد کنید.',

	'MAIL_BANNED'			=> 'ارسال ایمیل به کاربران تحریم شده',
	'MAIL_BANNED_EXPLAIN'	=> 'در حین ارسال ایمیل های دسته جمعی می توانید مشخص کنید که آیا کاربران تحریم شده هم بتوانند این ایمیل ها را دریافت کنند و یا نه. ',
	'MAIL_HIGH_PRIORITY'	=> 'بالا',
	'MAIL_LOW_PRIORITY'		=> 'پایین',
	'MAIL_NORMAL_PRIORITY'	=> 'معمولی',
	'MAIL_PRIORITY'			=> 'اولویت ارسال ایمیل',
	'MASS_MESSAGE'			=> 'پیغام شما',
	'MASS_MESSAGE_EXPLAIN'	=> 'توجه داشته باشید که فقط می توانید متن ساده ارسال کنید و هرگونه کدنویسی برای تغییر ظاهر ایمیل بی اثر خواهد بود.',

	'NO_EMAIL_MESSAGE'		=> 'باید پیغامی را وارد کنید.',
	'NO_EMAIL_SUBJECT'		=> 'باید عنوانی را برای پیغامتان انتخاب کنید.',
));
