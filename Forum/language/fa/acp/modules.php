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
	'ACP_MODULE_MANAGEMENT_EXPLAIN'	=> 'در این اینجا شما می‌توانید همه گونه های ماژولی را اداره کنید. لطفا در نظر داشته باشید که ماژول های بخش مدیریت ، ساختار سه مرحله ای دارند (دسته -> دسته -> ماژول) ماژول های بخش های دیگر ساختار دو مرحله ای دارند (دسته -> ماژول). لطفا همچنین آگاه باشید که شما می‌توانید با غیرفعال یا حذف کردن ماژول های معتبر و حیاتی خود را در تنگنا قرار دهید .',
	'ADD_MODULE'					=> 'افزودن ماژول',
	'ADD_MODULE_CONFIRM'			=> 'آیا از افزودن ماژول انتخاب شده با حالت برگزیده مطمئنید؟',
	'ADD_MODULE_TITLE'				=> 'افزودن ماژول',

	'CANNOT_REMOVE_MODULE'	=> 'ناتوانی در حذف ماژول, این ماژول زیر گروه‌هایی دارد. لطفا پیش از حذف ماژول زیر گروه‌ها را منتقل یا حذف کنید.',
	'CATEGORY'				=> 'دسته',
	'CHOOSE_MODE'			=> 'انتخاب حالت ماژول',
	'CHOOSE_MODE_EXPLAIN'	=> 'انتخاب حالت بکار گرفته شده موجود ماژول.',
	'CHOOSE_MODULE'			=> 'انتخاب ماژول',
	'CHOOSE_MODULE_EXPLAIN'	=> 'فایلی را که با این مود فراخوانده می شود انتخاب کنید.',
	'CREATE_MODULE'			=> 'ایجاد ماژول جدید',

	'DEACTIVATED_MODULE'	=> 'ماژول غیرفعال شده',
	'DELETE_MODULE'			=> 'حذف ماژول',
	'DELETE_MODULE_CONFIRM'	=> 'آیا از حذف ماژول مطمئنید؟',

	'EDIT_MODULE'			=> 'ویــرایــش مــاژول',
	'EDIT_MODULE_EXPLAIN'	=> 'در این قسمت می‌توانید تنظیمات ماژول را تعیین و ثبت کنید.',

	'HIDDEN_MODULE'			=> 'ماژول مخفی',

	'MODULE'					=> 'ماژول',
	'MODULE_ADDED'				=> 'ماژول با موفقیت اضافه شد.',
	'MODULE_DELETED'			=> 'ماژول با موفقیت حذف شد.',
	'MODULE_DISPLAYED'			=> 'نمایان شدن ماژول',
	'MODULE_DISPLAYED_EXPLAIN'	=> 'در صورتی که که نمی خواهید این ماژول نمایش داده شود, می‌توانید عبارت "خیر" این گزینه را انتخاب کنید.',
	'MODULE_EDITED'				=> 'ماژول با موفقیت ویرایش شد.',
	'MODULE_ENABLED'			=> 'فعالیت ماژول',
	'MODULE_LANGNAME'			=> 'زبان ماژول',
	'MODULE_LANGNAME_EXPLAIN'	=> 'نام ثبت شده ماژول را وارد کنید. در صورتی که در فایل زبان بکار رفته است ، از نام موجود در فایل زبان استفاده کنید.',
	'MODULE_TYPE'				=> 'نوع ماژول',

	'NO_CATEGORY_TO_MODULE'	=> 'تبدیل شاخه در ماژول ها صورت نگرفته ابتدا زیر مجموعه ها را حذف کنید',
	'NO_MODULE'				=> 'ماژولی پیدا نشد.',
	'NO_MODULE_ID'			=> 'id ماژول تعیین نشده است.',
	'NO_MODULE_LANGNAME'	=> 'نام زبان ماژول تعیین نشدع است.',
	'NO_PARENT'				=> 'بدون ریشه',

	'PARENT'				=> 'ریشه',
	'PARENT_NO_EXIST'		=> 'ریشه موجود نیست.',

	'SELECT_MODULE'			=> 'انتخاب یک ماژول',
));
