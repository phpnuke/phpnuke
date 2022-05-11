<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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

$lang = array_merge($lang, array(
	'QR_CHANGE_QUICKNICK_STRING' => 'تغییر منوکشویی هنگام کلیک روی نام کاربری جهت استفاده از گزینه"ارجاع به کاربر"',
	'QR_ENABLE_AJAX_PAGINATION'  => 'بارگزاری نکردن صفحه هنگام ارسال در پاسخ سریع پیشرفته',
	'QR_ENABLE_SCROLL'           => 'فعال سازی خودکار اسکرول هنگام مشاهده موضوع',
	'QR_ENABLE_SOFT_SCROLL'      => 'فعال سازی اسکرول و حالت انیمیشن هنگام مشاهده موضوع و بعد ارسال',
	'QR_ENABLE_WARNING'          => 'هشدار هنگامیکه پاسخ سریع پیشرفته امکان از بین رفتن متن را داشته باشد.',
	'QR_FIX_EMPTY_FORM'          => 'مجوز تصحیح فرم پاسخ سریع پیشرفته هنگامیکه خالی است',
));
