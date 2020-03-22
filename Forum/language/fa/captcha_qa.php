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
	'CAPTCHA_QA'				=> 'پرسش و پاسخ',
	'CONFIRM_QUESTION_EXPLAIN'	=> 'این سوال یک وسیله برای جلوگیری از ارسال اتوماتیک فرم توسط روباتهای اسپم است.',
	'CONFIRM_QUESTION_WRONG'	=> 'شما به این سوال پاسخ اشتباه داده اید.',
	'CONFIRM_QUESTION_MISSING'	=> 'سوال مربوط به کپچا بازیابی نمیشود. لطفا با مدیریت تماس بگیرید.',
	'QUESTION_ANSWERS'			=> 'پاسخ',
	'ANSWERS_EXPLAIN'			=> 'لطفا پاسخ مناسب را در کادر مریوط به خود بنویسید.',
	'CONFIRM_QUESTION'			=> 'پرسش',

	'ANSWER'					=> 'پاسخ',
	'EDIT_QUESTION'				=> 'ویرایش پرسش',
	'QUESTIONS'					=> 'پرسش',
	'QUESTIONS_EXPLAIN'			=> 'برای هر ارسال فرم جایکه شما افزونه Q&amp;A را فعال کرده باشید, یکی از سوالات تعیین شده در اینجا از کاربران پرسیده خواهد شد. برای استفاده از این افزونه حداقل باید یک سوال در زبان پیش فرض تنظیم شده باشد. پاسخ گویی به این سوالات باید برای مخاطب هدف شما ساده اما فراتر از توانایی یک روبات که قادر است جستجوی گوگل اجرا کند باشد. استفاده از یک مجموعه بزرگ و به طور منظم تغییر شده از سوالات به بهترین نتیجه منجر می شود. اگر سوالات شما متکی به مورد ترکیب شده  است, نقطه گذاری یا فضای خالی, تنظیمات سختگیرانه را فعال کنید.',
	'QUESTION_DELETED'			=> 'پرسش حذف شده',
	'QUESTION_LANG'				=> 'زبان',
	'QUESTION_LANG_EXPLAIN'		=> 'پرسش و پاسخ مربوط به این گزینه ثبت شده است.',
	'QUESTION_STRICT'			=> 'بررسی سختگیرانه',
	'QUESTION_STRICT_EXPLAIN'	=> 'برای مجبور کردن به انجام مورد ترکیب شده, نقطه گذاری و فضای خالی فعال کنید.',

	'QUESTION_TEXT'				=> 'پرسش',
	'QUESTION_TEXT_EXPLAIN'		=> 'پرسشی که به کاربر عرضه می شود.',

	'QA_ERROR_MSG'				=> 'لطفا تمامی فیلدها را پر کنید و حداقل یک پاسخ وارد کنید.',
	'QA_LAST_QUESTION'			=> 'شما نمی توانید هنگامی که پلاگین فعال است تمامی پرسش ها را حذف کنید.',

));
