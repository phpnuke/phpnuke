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
	'ACP_FILES'						=> 'فایل های زبانی مدیریت',
	'ACP_LANGUAGE_PACKS_EXPLAIN'	=> 'در این بخش می توانید بسته های زبانی را حذف/نصب کنید. بسته زبانی پیشفرض با * مشخص شده است.',

	'DELETE_LANGUAGE_CONFIRM'		=> 'آیا از حذف “%s” اطمینان دارید؟',

	'INSTALLED_LANGUAGE_PACKS'		=> 'پکیج زبان نصب شد.',

	'LANGUAGE_DETAILS_UPDATED'			=> 'جزئیات زبان با موفقیت به روز رسانی شد.',
	'LANGUAGE_PACK_ALREADY_INSTALLED'	=> 'پکیج زبانی قبلا نصب شده بود.',
	'LANGUAGE_PACK_DELETED'				=> 'بسته زبانی <strong>%s</strong> با موفقیت حذف شد. زبان کاربرانی که از این بسته زبانی استفاده می کرند به زبان پیشفرض تالار تغییر یافت.',
	'LANGUAGE_PACK_DETAILS'				=> 'جزئیات بسته زبانی',
	'LANGUAGE_PACK_INSTALLED'			=> 'بسته زبانی <strong>%s</strong> با موفقیت نصب شد.',
	'LANGUAGE_PACK_CPF_UPDATE'			=> 'تابع های زبانی فیلد های سفارشی از زبان پیشفرض کپی می شوند،در صورت نیاز آنها را تغییر دهید.',
	'LANGUAGE_PACK_ISO'					=> 'ISO',
	'LANGUAGE_PACK_LOCALNAME'			=> 'نام محلی',
	'LANGUAGE_PACK_NAME'				=> 'نام',
	'LANGUAGE_PACK_NOT_EXIST'			=> 'بسته زبانی انتخاب شده موجود نیست.',
	'LANGUAGE_PACK_USED_BY'				=> 'استفاده شده توسط(شامل موتورهای جستجو)',
	'LANGUAGE_VARIABLE'					=> 'متغیر های زبان',
	'LANG_AUTHOR'						=> 'نویسنده بسته زبانی',
	'LANG_ENGLISH_NAME'					=> 'نام انگلیسی',
	'LANG_ISO_CODE'						=> 'کد ISO',
	'LANG_LOCAL_NAME'					=> 'نام محلی',

	'MISSING_LANG_FILES'		=> 'فایل زبانی گم شده ',
	'MISSING_LANG_VARIABLES'	=> 'متغیر های بسته زبانی گم شده است',

	'NO_FILE_SELECTED'				=> 'فایل زبانی را مشخص نکرده اید.',
	'NO_LANG_ID'					=> 'بسته زبانی را مشخص نکرده اید.',
	'NO_REMOVE_DEFAULT_LANG'		=> 'نمی توانید بسته زبانی پیشفرض را حذف کنید.<br />اگر مایل به حذف این بسته زبانی هستید ابتدا بسته زبانی پیشفرض تالارتان را تغییر دهید.',
	'NO_UNINSTALLED_LANGUAGE_PACKS'	=> 'هیچ بسته زبانی حذف نشده است.',

	'THOSE_MISSING_LANG_FILES'			=> 'فایل های مقابل از بسته زبانی %s گم شده اند',
	'THOSE_MISSING_LANG_VARIABLES'		=> 'متغیر های زبانی مقابل از بسته زبانی <strong>%s</strong> گم شده اند',

	'UNINSTALLED_LANGUAGE_PACKS'	=> 'بسته های زبانی حذف شده',
	
	'BROWSE_LANGUAGE_PACKS_DATABASE'	=> 'جستجو بسته های زبانی در پایگاه',
));
