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
	'ACP_MODULE_MANAGEMENT_EXPLAIN'	=> 'در اين اينجا شما مي توانيد همه گونه هاي ماژولي را اداره کنيد. لطفا در نظر داشته باشيد که ماژول هاي بخش مديريت ، ساختار سه مرحله اي دارند (دسته -> دسته -> ماژول) ماژول هاي بخش هاي ديگر ساختار دو مرحله اي دارند (دسته -> ماژول). لطفا همچنین آگاه باشید که شما می توانید با غیر فعال یا حذف کردن ماژول های معتبر و حیاتی خود را در تنگنا قرار دهید .',
	'ADD_MODULE'					=> 'افزودن ماژول',
	'ADD_MODULE_CONFIRM'			=> 'آيا از افزودن ماژول انتخاب شده با حالت برگزيده اطمينان داريد ؟',
	'ADD_MODULE_TITLE'				=> 'افزودن ماژول',

	'CANNOT_REMOVE_MODULE'	=> 'ناتواني در حذف ماژول, اين ماژول زير گروه هايي دارد. لطفا پيش از حذف ماژول زير گروه ها را منتقل يا حذف کنيد.',
	'CATEGORY'				=> 'دسته',
	'CHOOSE_MODE'			=> 'انتخاب حالت ماژول',
	'CHOOSE_MODE_EXPLAIN'	=> 'انتخاب حالت بکار گرفته شده موجود ماژول.',
	'CHOOSE_MODULE'			=> 'انتخاب ماژول',
	'CHOOSE_MODULE_EXPLAIN'	=> 'فايلي را که با اين مود فراخوانده مي شود انتخاب کنيد.',
	'CREATE_MODULE'			=> 'ايجاد ماژول جديد',

	'DEACTIVATED_MODULE'	=> 'ماژول غير فعال شده',
	'DELETE_MODULE'			=> 'حذف ماژول',
	'DELETE_MODULE_CONFIRM'	=> 'آيا از حذف ماژول اطمينان داريد ؟',

	'EDIT_MODULE'			=> 'ويــرايــش مــاژول',
	'EDIT_MODULE_EXPLAIN'	=> 'در اين قسمت مي توانيد تنظيمات ماژول را تعيين و ثبت کنيد.',

	'HIDDEN_MODULE'			=> 'ماژول مخفي',

	'MODULE'					=> 'ماژول',
	'MODULE_ADDED'				=> 'ماژول با موفقيت اضافه شد.',
	'MODULE_DELETED'			=> 'ماژول با موفقيت حذف شد.',
	'MODULE_DISPLAYED'			=> 'نمايان شدن ماژول',
	'MODULE_DISPLAYED_EXPLAIN'	=> 'در صورتي که که نمي خواهيد اين ماژول نمايش داده شود, مي توانيد عبارت "خير" اين گزينه را انتخاب کنيد.',
	'MODULE_EDITED'				=> 'ماژول با موفقيت ويرايش شد.',
	'MODULE_ENABLED'			=> 'فعاليت ماژول',
	'MODULE_LANGNAME'			=> 'زبان ماژول',
	'MODULE_LANGNAME_EXPLAIN'	=> 'نام ثبت شده ماژول را وارد کنيد. در صورتي که در فايل زبان بکار رفته است ، از نام موجود در فايل زبان استفاده کنيد.',
	'MODULE_TYPE'				=> 'نوع ماژول',

	'NO_CATEGORY_TO_MODULE'	=> 'تبدیل شاخه در ماژول ها صورت نگرفته ابتدا زیر مجموعه ها را حذف کنید',
	'NO_MODULE'				=> 'ماژولي پيدا نشد.',
	'NO_MODULE_ID'			=> 'id ماژول تعيين نشده است.',
	'NO_MODULE_LANGNAME'	=> 'نام زبان ماژول تعيين نشدع است.',
	'NO_PARENT'				=> 'بدون ریشه',

	'PARENT'				=> 'ریشه',
	'PARENT_NO_EXIST'		=> 'ریشه موجود نيست.',

	'SELECT_MODULE'			=> 'انتخاب يک ماژول',
));
