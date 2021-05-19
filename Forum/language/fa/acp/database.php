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

// Database Backup/Restore
$lang = array_merge($lang, array(
	'ACP_BACKUP_EXPLAIN'	=> 'در این بخش می‌توانید از همگی اطلاعات مربوط به phpBB پشتیان بگیرید. می‌توانید پشتیبان ها را در پوشه <samp>store/</samp> ذخیره کنید و یا آن را به صورت مستقیم بارگیری کنید. بسته به پیکربندی سرورتان ممکن است بتوانید فایل‌های بارگیری را فشرده کنید.',
	'ACP_RESTORE_EXPLAIN'	=> 'در این بخش می‌توانید از اطلاعات تالارتان پشتیبان تهیه کنید که این پشتیبان در آدرس <samp>store/</samp> ذخیره میشود. با توجه به شرایط  سرور می‌توانید آن را با فرمت های مختلف فشرده سازی کنید.',

	'BACKUP_DELETE'			=> 'فایل پشتیبان با موفقیت حذف شد',
	'BACKUP_INVALID'		=> 'فایل نتخاب شده برای پشتیبان گیری معتبر نمی باشد',
	'BACKUP_NOT_SUPPORTED'	=> 'فایل پشتیبان انتخاب شده قابل پشتیبانی نیست.',
	'BACKUP_OPTIONS'		=> 'گزینه‌های پشتیبان گیری',
	'BACKUP_SUCCESS'		=> 'فایل پشتیبان با موفقیت ایجاد شد!',
	'BACKUP_TYPE'			=> 'نوع پشتیبان گیری',

	'DATABASE'			=> 'ابزار پایگاه داده',
	'DATA_ONLY'			=> 'فقط داده‌ها',
	'DELETE_BACKUP'		=> 'حذف پشتیبان',
	'DELETE_SELECTED_BACKUP'	=> 'آیا از حذف پشتیبان انتخاب شده مطمئنید؟',
	'DESELECT_ALL'		=> 'لغو انتخاب همه',
	'DOWNLOAD_BACKUP'	=> 'بارگیری پشتیبان',

	'FILE_TYPE'			=> 'نوع فایل',
	'FILE_WRITE_FAIL'	=> 'نوشتن فایل به منظور باگزاری پوشه مقدور نیست',
	'FULL_BACKUP'		=> 'کامل',

	'RESTORE_FAILURE'		=> 'ممکن است فایل پشتیبان خراب شده باشد',
	'RESTORE_OPTIONS'		=> 'بازیابی گزینه‌ها',
	'RESTORE_SELECTED_BACKUP'	=> 'آیا از بازیابی پایگاه داده انتخاب شده مطمئنید؟',
	'RESTORE_SUCCESS'		=> 'پایگاه داده با موفقیت بازیابی شد.<br /><br />حال تالار شما باید شبیه زمانی باشد که پشتیان در آن تاریخ گرفته شده بود.',

	'SELECT_ALL'			=> 'انتخاب همه',
	'SELECT_FILE'			=> 'انتخاب فایل',
	'START_BACKUP'			=> 'شروع پشتیبان گیری',
	'START_RESTORE'			=> 'شروع بازیابی',
	'STORE_AND_DOWNLOAD'	=> 'ذخیره و بارگیری',
	'STORE_LOCAL'			=> 'ذخیره فایل به صورت محلی',
	'STRUCTURE_ONLY'		=> 'فقط ساختار',

	'TABLE_SELECT'		=> 'انتخاب جدول',
	'TABLE_SELECT_ERROR'=> 'حداقل باید یک جدول را انتخاب کنید',
));
