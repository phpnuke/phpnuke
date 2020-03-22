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

// User pruning
$lang = array_merge($lang, array(
	'ACP_PRUNE_USERS_EXPLAIN'	=> 'در این بخش میتوانید کاربران را حذف و یا غیرفعال کنید. کاربران می توانند بر اساس  تعداد پست ، فعالیت و ... دسته بندی شوند. می توانید با تعیین کردن شاخصهایی اقدام به هرس کاربران کنید مثلا می توانید تعیین کنید که کاربران کمتر از 10 عدد پست به طور خودکار هرس شوند و یا کاربرانی که از یک تاریخ مشخص غیرفعال شده اند ، به طور خودکار هرس و از سایت حذف شوند. ضمنامی توانید به صورت دستی لیستی از کاربرانی را که می خواهید هرس شوند تهیه کنید.',

	'CRITERIA'				=> 'معیار ها',

	'DEACTIVATE_DELETE'			=> 'غیرفعال کردن و یا حذف',
	'DEACTIVATE_DELETE_EXPLAIN'	=> 'لطفا انتخاب کنید که آیا کاربران حذف شوند و یا غیرفعال،حذف کاربران ممکن است غیرقابل برگشت باشذ!',
	'DELETE_USERS'				=> 'حذف',
	'DELETE_USER_POSTS'			=> 'جذف پست های کاربر هرس شده',
	'DELETE_USER_POSTS_EXPLAIN' => 'حذف پست های کاربران حذف شده،اگر غیرفعال شدن کاربران انتخاب شود،تأثیری ندارد..',

	'JOINED_EXPLAIN'			=> 'تاریخی با فرمت <kbd>YYYY-MM-DD</kbd> وارد کنید.',

	'LAST_ACTIVE_EXPLAIN'		=> 'تاریخی در فرمت <kbd>YYYY-MM-DD</kbd> وارد کنید. <kbd>0000-00-00</kbd> را برای هرس کردن کاربرانی که هیچگاه وارد نشده اند،وارد کنید. در آن صورت <em>قبل</em> و <em>بعد</em> نادیده گرفته خواهند شد.',

	'POSTS_ON_QUEUE'			=> 'پست های در انتظار تایید.',
	'PRUNE_USERS_GROUP_EXPLAIN'	=> 'محدودیت کاربران در گروه انتخاب شده',
	'PRUNE_USERS_GROUP_NONE'	=> 'همه گروه ها',
	'PRUNE_USERS_LIST'				=> 'کاربران هرس شدند',
	'PRUNE_USERS_LIST_DELETE'		=> 'با معیار های مشخص شده توسط شما اکانت های مقابل حذف شدند.',
	'PRUNE_USERS_LIST_DEACTIVATE'	=> 'با معیار های مشخص شده توسط شما اکانت های مقابل غیرفعال شدند.',

	'SELECT_USERS_EXPLAIN'		=> 'لطفا نام کاربری مشخصی را وارد کنید.این نام های کاربری بر معیار های هرس اولویت خواهند داشت،صاحب امتیاز سایت نمی تواند هرس شود.',

	'USER_DEACTIVATE_SUCCESS'	=> 'کاربران انتخاب شده با موفقیت غیرفعال شدند.',
	'USER_DELETE_SUCCESS'		=> 'کاربران انتخاب شده با موفقیت حذف شدند.',
	'USER_PRUNE_FAILURE'		=> 'کاربری با این معیارها وجود ندارد.',

	'WRONG_ACTIVE_JOINED_DATE'	=> 'تاریخ وارد شده اشتباه است،فرمت تاریخ باید <kbd>YYYY-MM-DD</kbd> باشد.',
));

// Forum Pruning
$lang = array_merge($lang, array(
	'ACP_PRUNE_FORUMS_EXPLAIN'	=> 'این بخش موضوعاتی را که برای تعداد روز های مشخص شده بازدیدی نداشته باشند و یا پستی در آنها ارسال نشود،حذف می کند. اگر عددی را وارد نکنید همگی موضوعات حذف خواهند شد. به صورت پیشفرض موضوعات نظرسنجی دار،مهم،اعلامیه دار هرس نمی شوند.',

	'FORUM_PRUNE'		=> 'هرس انجمن',

	'NO_PRUNE'			=> 'هیچ انجمنی هرس نشد.',

	'SELECTED_FORUM'	=> 'انجمن انتخاب شده',
	'SELECTED_FORUMS'	=> 'انجمن های انتخاب شده',

	'POSTS_PRUNED'					=> 'پست ها هرس شدند',
	'PRUNE_ANNOUNCEMENTS'			=> 'هرس اطلاعیه ها',
	'PRUNE_FINISHED_POLLS'			=> 'هرس نظرسنجی های بسته',
	'PRUNE_FINISHED_POLLS_EXPLAIN'	=> 'هرس موضوعاتی که نظرسنجی های آن به پایان رسیده است',
	'PRUNE_FORUM_CONFIRM'			=> 'آیا از هرس موضوعات موجود در انجمن های انتخاب شده با توجه به تنظیمات تعریف شده مطمئن هستید ؟بعد از هرس امکان بازآوری موضوعات هرس شده وجود نخواهد داشت.',
	'PRUNE_NOT_POSTED'				=> 'تعداد روز از آخرین پست',
	'PRUNE_NOT_VIEWED'				=> 'تعداد روز از آخرین بازدید',
	'PRUNE_OLD_POLLS'				=> 'هرس نظرسنجی های قدیمی',
	'PRUNE_OLD_POLLS_EXPLAIN'		=> 'حذف موضوعاتی که از روز ارسال پست به نظرسنجی آن ها رأی داده نشده است.',
	'PRUNE_STICKY'					=> 'هرس موضوعات مهم',
	'PRUNE_SUCCESS'					=> 'هرس انجمن ها موفقیت آمیز بود.',

	'TOPICS_PRUNED'		=> 'موضوعات هرس شدند',
));
