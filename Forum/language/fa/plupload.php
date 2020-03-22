<?php
/**
*
* phpBB 3.2.X Project - Persian Translation
* Translators: PHP-BB.IR Group Meis@M Nobari
* 
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @copyright (c) 2010-2013 Moxiecode Systems AB
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
	'PLUPLOAD_ADD_FILES'		=> 'افزودن فایل',
	'PLUPLOAD_ADD_FILES_TO_QUEUE'	=> 'فایل جهت بارگزاری به صف انتظار اضافه شد ، برای آغاز روی دکمه شروع کلیک کنید.',
	'PLUPLOAD_ALREADY_QUEUED'	=> '%s در حال حاضر در صف انتظار وجود دارد.',
	'PLUPLOAD_CLOSE'			=> 'بستن',
	'PLUPLOAD_DRAG'				=> 'فایل را اینجا رها کنید.',
	'PLUPLOAD_DUPLICATE_ERROR'	=> 'خطا فایل هم نام.',
	'PLUPLOAD_DRAG_TEXTAREA'	=> 'شما بایستی جهت پیوست فایل ، آن را اینجا بکشید و رها کنید.',
	'PLUPLOAD_ERR_INPUT'		=> 'خطا در باز کردن فایل.',
	'PLUPLOAD_ERR_MOVE_UPLOADED'	=> 'خطا در انتقال فایل بارگزاری شده.',
	'PLUPLOAD_ERR_OUTPUT'		=> 'خطا در باز کردن فایل',
	'PLUPLOAD_ERR_FILE_TOO_LARGE'	=> 'فایل خیلی بزرگ است:',
	'PLUPLOAD_ERR_FILE_COUNT'	=> 'خطا در شمارش فایل',
	'PLUPLOAD_ERR_FILE_INVALID_EXT'	=> 'پسوند مورد نظر نا معتبر است:',
	'PLUPLOAD_ERR_RUNTIME_MEMORY'	=> 'حافظه جهت بارگزاری دچار کمبود است.',
	'PLUPLOAD_ERR_UPLOAD_URL'	=> 'آدرس فایل بارگزاری شده وجود ندارد.',
	'PLUPLOAD_EXTENSION_ERROR'	=> 'خطا در پسوند فایل',
	'PLUPLOAD_FILE'				=> 'فایل: %s',
	'PLUPLOAD_FILE_DETAILS'		=> 'فایل: %s, حجم: %d, حداکثر حجم فایل: %d',
	'PLUPLOAD_FILENAME'			=> 'نام فایل',
	'PLUPLOAD_FILES_QUEUED'		=> '%d فایل در صف انتظار',
	'PLUPLOAD_GENERIC_ERROR'	=> 'خطا کلی.',
	'PLUPLOAD_HTTP_ERROR'		=> 'HTTP خطا.',
	'PLUPLOAD_IMAGE_FORMAT'		=> 'فرمت فایل تصویری نا معتبر یا قابل دسترس نیست.',
	'PLUPLOAD_INIT_ERROR'		=> 'Init خطا.',
	'PLUPLOAD_IO_ERROR'			=> 'IO خطا.',
	'PLUPLOAD_NOT_APPLICABLE'	=> 'N/A',
	'PLUPLOAD_SECURITY_ERROR'	=> 'خطای امنیتی.',
	'PLUPLOAD_SELECT_FILES'		=> 'انتخاب فایل ها',
	'PLUPLOAD_SIZE'				=> 'حجم',
	'PLUPLOAD_SIZE_ERROR'		=> 'خطا حجم فایل.',
	'PLUPLOAD_STATUS'			=> 'وضعیت',
	'PLUPLOAD_START_UPLOAD'		=> 'آغاز بارگزاری',
	'PLUPLOAD_START_CURRENT_UPLOAD'	=> 'آغاز بارگزاری صف انتظار',
	'PLUPLOAD_STOP_UPLOAD'		=> 'توقف بارگزاری',
	'PLUPLOAD_STOP_CURRENT_UPLOAD'	=> 'توقف بارگزاری در حال انجام',
	// Note: This string is formatted independently by plupload and so does not
	// use the same formatting rules as normal phpBB translation strings
	'PLUPLOAD_UPLOADED'			=> 'آپلود شده %d/%d فایل',
));
