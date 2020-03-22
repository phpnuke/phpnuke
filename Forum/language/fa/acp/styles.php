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
	'ACP_STYLES_EXPLAIN'	=> 'در این بخش میتوانید به مدیریت قالب های انجمن خود بپردازید.',

	'CANNOT_BE_INSTALLED'			=> 'امکان نصب وجود ندارد.',
	'CONFIRM_UNINSTALL_STYLES'		=> 'آیا از حذف قالب انتخابی اطمینان دارید؟',
	'COPYRIGHT'						=> 'کپی رایت',

	'DEACTIVATE_DEFAULT'		=> 'نمی توانید قالب پیشفرض را غیرفعال کنید.',
	'DELETE_FROM_FS'			=> 'حذف از سیستم فایل',
	'DELETE_STYLE_FILES_FAILED'	=> 'خطا در حذف فایل های قالب "%s".',
	'DELETE_STYLE_FILES_SUCCESS'	=> 'فایل های قالب "%s" حذف شدند.',
	'DETAILS'					=> 'جزئیات',

	'INHERITING_FROM'			=> 'برگرفته از',
	'INSTALL_STYLE'				=> 'نصب قالب',
	'INSTALL_STYLES'			=> 'نصب قالب ها',
	'INSTALL_STYLES_EXPLAIN'		=> 'در این بخش می توانید قالبی را همراه با عناصر آن نصب کنید،عناصر اضافه شده موجب حذف شدن عناصر قبلی نخواهند شد،بعضی از قالب ها نیازمند عناصر پیشفرض هستند،اگر قالبی این چنین بود، به شما اطلاع داده خواهد شد.',
	'INVALID_STYLE_ID'			=> 'شماره قالب معتبر نیست.',

	'NO_MATCHING_STYLES_FOUND'	=> 'قالبی یافت نشد.',
	'NO_UNINSTALLED_STYLE'		=> 'قالب نصب نشده ای یافت نشد.',

	'PURGED_CACHE'				=> 'کش پاکسازی شد.',

	'REQUIRES_STYLE'			=> 'برای نصب این قالب نیاز به قالب "%s" دارید.',

	'STYLE_ACTIVATE'			=> 'فعال سازی',
	'STYLE_ACTIVE'				=> 'فعال',
	'STYLE_DEACTIVATE'			=> 'غیر فعال',
	'STYLE_DEFAULT'				=> 'به عنوان قالب پیش فرض',
	'STYLE_DEFAULT_CHANGE_INACTIVE'	=> 'قبل از انتخاب به عنوان قالب پیش فرض باید قالب را فعال کنید.',
	'STYLE_ERR_INVALID_PARENT'	=> 'پدر قالب نامعتبر است.',
	'STYLE_ERR_NAME_EXIST'		=> 'قالبی با این عنوان موجود است.',
	'STYLE_ERR_STYLE_NAME'		=> 'برای این قالب باید نامی انتخاب کنید.',
	'STYLE_INSTALLED'			=> 'قالب "%s" با موفقیت نصب شد.',
	'STYLE_INSTALLED_RETURN_INSTALLED_STYLES'	=> 'بازگشت به لیست قالب های نصب شده',
	'STYLE_INSTALLED_RETURN_UNINSTALLED_STYLES'	=> 'نصب قالب های بیشتر',
	'STYLE_NAME'				=> 'نام قالب',
    'STYLE_NAME_RESERVED'		=> 'قالب "%s" قابل نصب نیست زیرا هم نام آن قبلا استفاده شده است.',
	'STYLE_NOT_INSTALLED'		=> 'قالب "%s" نصب نشد.',
	'STYLE_PATH'				=> 'مسیر قالب',
	'STYLE_UNINSTALL'			=> 'حذف',
	'STYLE_UNINSTALL_DEPENDENT'	=> 'قالب "%s" قابل حذف شدن نیست زیرا دارای فرزند است.',
	'STYLE_UNINSTALLED'			=> 'قالب "%s" با موفقیت حذف شد.',
	'STYLE_PHPBB_VERSION'		=> 'نسخه phpBB',
	'STYLE_USED_BY'				=> 'استفاده توسط جستجوگرها',
	'STYLE_VERSION'				=> 'نسخه قالب',

	'UNINSTALL_DEFAULT'		=> 'نمی توانید قالب پیش فرض را حذف کنید.',
	
	'BROWSE_STYLES_DATABASE'	=> 'جستجوی قالب در پایگاه',
));
