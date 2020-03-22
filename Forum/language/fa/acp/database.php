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

// Database Backup/Restore
$lang = array_merge($lang, array(
	'ACP_BACKUP_EXPLAIN'	=> 'در این بخش می توانید از همگی اطلاعات مربوط به phpBB پشتیان بگیرید. می توانید پشتیبان ها را در پوشه <samp>store/</samp> ذخیره کنید و یا آن را به صورت مستقیم بارگیری کنید. بسته به پیکربندی سرورتان ممکن است بتوانید فایل های بارگیری را فشرده کنید.',
	'ACP_RESTORE_EXPLAIN'	=> 'این اقدام همگی جداول و داده های phpBB را به حالتی باز خواهد گرداند که در فایل پشتیبان مشخص شده است. اگر سرورتان پشتیبانی کند می توایند از gzip و یا bzip2 استفاده کنید؛فشردگی به صورت خودکار باز خواهد شد. <strong>هشدار</strong> این اقدام،تمامی اطلاعات موجود را بازنویسی خواهد کرد. بازنویسی داده ها ممکن طول بکشد،لطفا تا اتمام عملیات از این صفحه خارج نشوید. پشتیبان ها در پوشه <samp>store/</samp> ذخیره شده و توسط توابع phpBB ایجاد می شوند. پشتیبان هایی که توسط سیستم ایجاد نشده اند ممکن است با موفقیت بازنویسی شده و یا در مواردی با موفقیت بازنویسی نشوند.',


	'BACKUP_DELETE'		=> 'فایل پشتیبان با موفقیت حذف شد',
	'BACKUP_INVALID'	=> 'فایل نتخاب شده برای پشتیبان گیری معتبر نمی باشد',
	'BACKUP_OPTIONS'	=> 'گزینه های پشتیبان گیری',
	'BACKUP_SUCCESS'	=> 'فایل پشتیبان با موفقیت ایجاد شد!',
	'BACKUP_TYPE'		=> 'نوع پشتیبان گیری',

	'DATABASE'			=> 'ابزار پایگاه داده',
	'DATA_ONLY'			=> 'فقط داده ها',
	'DELETE_BACKUP'		=> 'حذف پشتیبان',
	'DELETE_SELECTED_BACKUP'	=> 'آیا از حذف پشتیبان انتخاب شده مطمئن هستید ؟',
	'DESELECT_ALL'		=> 'لغو انتخاب همه',
	'DOWNLOAD_BACKUP'	=> 'بارگیری پشتیبان',

	'FILE_TYPE'			=> 'نوع فایل',
	'FILE_WRITE_FAIL'	=> 'نوشتن فایل به منظور باگزاری پوشه مقدور نیست',
	'FULL_BACKUP'		=> 'کامل',

	'RESTORE_FAILURE'		=> 'ممکن است فایل پشتیبان خراب شده باشد',
	'RESTORE_OPTIONS'		=> 'بازیابی گزینه ها',	
        'RESTORE_SELECTED_BACKUP'	=> 'آیا از بازیابی پایگاه داده انتخاب شده مطمئن هستید ؟',
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
