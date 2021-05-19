<?php
/**
*
* phpBB 3.3.X Project - Persian Translation
* Translators: PHP-BB.IR Group Meis@M Nobari
* 
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
	'ALREADY_DEFAULT_GROUP'		=> 'گروه انتخابی شما از قبل به عنوان پیش‌فرض انتخاب شده بود.',
	'ALREADY_IN_GROUP'			=> 'شما در حال حاضر کاربر گروه انتخابی هستید.',
	'ALREADY_IN_GROUP_PENDING'	=> 'شما قبلا درخواست خود را برای عضویت در این گروه ارائه کرده اید.',

	'CANNOT_JOIN_GROUP'			=> 'شما نمی‌توانید وارد این گروه شوید. شما فقط می‌توانید در گروه‌های باز-رایگان یا باز عضو شوید.',
	'CANNOT_RESIGN_GROUP'		=> 'شما نمی‌توانید از این گروه کناره گیری کنید.شما فقط می‌توانید در گروه‌های باز یا آزاد عضویت خود را لغو کنید.',
	'CHANGED_DEFAULT_GROUP'		=> 'تغییر گروه پیش‌فرض با موفقیت انجام شد.',

	'GROUP_AVATAR'						=> 'آواتار گروه',
	'GROUP_CHANGE_DEFAULT'				=> 'آیا از تغییر گروه پیش‌فرض خود به گروه “%s” مطمئنی هستید؟',
	'GROUP_CLOSED'						=> 'بسته شده',
	'GROUP_DESC'						=> 'توضیحات گروه',
	'GROUP_HIDDEN'						=> 'مخفی',
	'GROUP_INFORMATION'					=> 'اطلاعات گروه کاربری',
	'GROUP_IS_CLOSED'					=> 'این یک گروه بسته است، اعضای جدید فقط با دعوت نامه از طرف رهبر گروه میتوانند وارد شوند.',
	'GROUP_IS_FREE'						=> 'این یک گروه باز-رایگان است، همه اعضای جدید میتوانند وارد شوند.',
	'GROUP_IS_HIDDEN'					=> 'این یک گروه پنهانی است، فقط اعضای این گروه میتوانند لیست اعضای این گروه را مشاهده کنند.',
	'GROUP_IS_OPEN'						=> 'این یک گروه باز است، اعضا میتوانند درخواست عضویت را تأیید کنند.',
	'GROUP_IS_SPECIAL'					=> 'این یک گروه ویژه است، گروه‌های ویژه توسط مدیر کل سایت، مدیریت میشوند.',
	'GROUP_JOIN'						=> 'عضویت در گروه',
	'GROUP_JOIN_CONFIRM'				=> 'آیا از عضویت در گروه انتخاب شده مطمئنید؟',
	'GROUP_JOIN_PENDING'				=> 'درخواست برای عضویت',
	'GROUP_JOIN_PENDING_CONFIRM'		=> 'آیا از درخواست عضویت در گروه انتخاب شده مطمئنید؟',
	'GROUP_JOINED'						=> 'با موفقیت در گروه انتخاب شده عضو شدید.',
	'GROUP_JOINED_PENDING'				=> 'درخواست عضویت شما با موفقیت ثبت شد، لطفا صبر کنید تا رهبر گروه درخواست شما را تأیید کند.',
	'GROUP_LIST'						=> 'مدیریت کاربران',
	'GROUP_MEMBERS'						=> 'اعضای گروه',
	'GROUP_NAME'						=> 'نام گروه',
	'GROUP_OPEN'						=> 'باز',
	'GROUP_RANK'						=> 'رتبه گروه',
	'GROUP_RESIGN_MEMBERSHIP'			=> 'استعفا از گروه کاربری',
	'GROUP_RESIGN_MEMBERSHIP_CONFIRM'	=> 'آیا از استعفا دادن از گروه انتخاب شده مطمئنید؟',
	'GROUP_RESIGN_PENDING'				=> 'استعفا از عضویت گروه در انتظار',
	'GROUP_RESIGN_PENDING_CONFIRM'		=> 'آیا از استعفا دادن از گروه در انتظار انتخاب شده مطمئنید؟',
	'GROUP_RESIGNED_MEMBERSHIP'			=> 'شما با موفقیت از گروه انتخاب شده حذف شدید.',
	'GROUP_RESIGNED_PENDING'			=> 'شما با موفقیت از گروه در انتظار انتخاب شده حذف شدید',
	'GROUP_TYPE'						=> 'نوع گروه',
	'GROUP_UNDISCLOSED'					=> 'گروه مخفی',
	'FORUM_UNDISCLOSED'					=> 'مدیریت انجمن‌های مخفی',

	'LOGIN_EXPLAIN_GROUP'	=> 'برای دیدن جزئیات گروه باید وارد حساب خود شوید.',

	'NO_LEADERS'					=> 'شما رهبر هیچ گروه کاربری نیستید.',
	'NOT_LEADER_OF_GROUP'			=> 'درخواست شما برای انجام عملیات بی فایده است، زیرا شما رهبر گروه انتخاب شده نیستید.',
	'NOT_MEMBER_OF_GROUP'			=> 'درخواست شما برای انجام عملیات بی فایده است، زیرا شما عضو گروه انتخاب شد نیستید یا عضویت شما هنوز تأیید نشده است.',
	'NOT_RESIGN_FROM_DEFAULT_GROUP'	=> 'شما اجازه استعفا از گروه پیش‌فرض را ندارید.',

	'PRIMARY_GROUP'		=> 'گروه اصلی',

	'REMOVE_SELECTED'		=> 'حذف انتخاب شده ها',

	'USER_GROUP_CHANGE'			=> 'از “%1$s” به گروه“%2$s”',
	'USER_GROUP_DEMOTE'			=> 'تنزل رهبر گروه',
	'USER_GROUP_DEMOTE_CONFIRM'	=> 'آیا از تنزل مقام رهبر گروه مطمئنید؟',
	'USER_GROUP_DEMOTED'		=> 'شما با موفقیت از رهبریت گروه نزول درجه پیدا کردید.',
));
