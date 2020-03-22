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
	'CONFIG_NOT_EXIST'					=> 'تنظیمات پیکر بندی "%s" به طور غیر منتظره ای وجود ندارد.',

	'GROUP_NOT_EXIST'					=> 'گروه "%s" به طور غیر منتظره ای وجود ندارد.',

	'MIGRATION_APPLY_DEPENDENCIES'		=> 'تثبیت وابستگی های %s.',
	'MIGRATION_DATA_DONE'				=> 'داده نصب شده: %1$s; زمان: %2$.2f ثانیه',
	'MIGRATION_DATA_IN_PROGRESS'		=> 'نصب داده: %1$s; زمان: %2$.2f ثانیه',
	'MIGRATION_DATA_RUNNING'			=> 'نصب داده: %s.',
	'MIGRATION_EFFECTIVELY_INSTALLED'	=> 'انتقال با موفقیت نصب شده است (پرش): %s',
	'MIGRATION_EXCEPTION_ERROR'			=> 'مشکل غیر منتظره ای در هنگام پرش بوجود آمده است. قبل از تغییرات خطایی بوجود آمده است, بایستی این خطا را در انجمن خود بیابید.',
	'MIGRATION_NOT_FULFILLABLE'			=> 'انتقال "%1$s" قابل انجام نیست, خطایی در انتقال "%2$s".',
	'MIGRATION_NOT_INSTALLED'			=> 'انتقال "%s" نصب نشده است.',
	'MIGRATION_NOT_VALID'				=> '%s مهاجرت معتبری نیست.',
	'MIGRATION_SCHEMA_DONE'				=> 'نصب شده Schema: %1$s; زمان: %2$.2f ثانیه',
	'MIGRATION_SCHEMA_IN_PROGRESS'		=> 'نصب Schema: %1$s; زمان: %2$.2f ثانیه',
	'MIGRATION_SCHEMA_RUNNING'			=> 'نصب Schema: %s.',

	'MIGRATION_REVERT_DATA_DONE'		=> 'بازگشت داده: %1$s; زمان: %2$.2f ثانیه',
	'MIGRATION_REVERT_DATA_IN_PROGRESS'	=> 'بازگشت داده: %1$s; زمان: %2$.2f ثانیه',
	'MIGRATION_REVERT_DATA_RUNNING'		=> 'بازگشت داده: %s.',
	'MIGRATION_REVERT_SCHEMA_DONE'		=> 'بازگشت Schema: %1$s; زمان: %2$.2f ثانیه',
	'MIGRATION_REVERT_SCHEMA_IN_PROGRESS'	=> 'بازگردانی Schema: %1$s; زمان: %2$.2f ثانیه',
	'MIGRATION_REVERT_SCHEMA_RUNNING'	=> 'بازگشت Schema: %s.',

	'MIGRATION_INVALID_DATA_MISSING_CONDITION'		=> 'A migration is invalid. An if statement helper is missing a condition.',
	'MIGRATION_INVALID_DATA_MISSING_STEP'			=> 'A migration is invalid. An if statement helper is missing a valid call to a migration step.',
	'MIGRATION_INVALID_DATA_CUSTOM_NOT_CALLABLE'	=> 'A migration is invalid. A custom callable function could not be called.',
	'MIGRATION_INVALID_DATA_UNKNOWN_TYPE'			=> 'A migration is invalid. An unknown migration tool type was encountered.',
	'MIGRATION_INVALID_DATA_UNDEFINED_TOOL'			=> 'A migration is invalid. An undefined migration tool was encountered.',
	'MIGRATION_INVALID_DATA_UNDEFINED_METHOD'		=> 'A migration is invalid. An undefined migration tool method was encountered.',

	'MODULE_ERROR'						=> 'در هنگام ساخت مدل خطایی بوجود آمده است: %s',
	'MODULE_EXISTS'						=> 'مدل درحال حاضر موجود است: %s',
	'MODULE_EXIST_MULTIPLE'				=> 'چندین مدل با مدل های قبلی وجود دارد: %s. از کلید های قبل و بعد استفاده کنید.',
	'MODULE_INFO_FILE_NOT_EXIST'		=> 'اطلاعات مدل درخواست شده وجود ندارد: %2$s',
	'MODULE_NOT_EXIST'					=> 'مدل درخواست شده وجود ندارد: %s',

	'PARENT_MODULE_FIND_ERROR'			=> 'امکان تعیین مدل والد وجود ندارد: %s',
	'PERMISSION_NOT_EXIST'				=> 'تنظیمات سطوح دسترسی "%s" به طور غیر منتظره ای وجود ندارد',

	'ROLE_NOT_EXIST'					=> 'نقش سطوح دسترسی "%s" به طور غیر منتظره ای وجود ندارد.',
));
