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
	'ADMIN_SIG_PREVIEW'		=> 'پیش نمایش امضا',
	'AT_LEAST_ONE_FOUNDER'	=> 'شما مجاز به تغيير اين بنيانگذار به کاربر عادي نيستيد. در کمترين حالت نياز است يک بنيانگذار براي انجمن تعريف شده باشد. در صورتيکه مي خواهيد وضعيت بنيانگذاري اين کاربران را تغيير دهيد, کاربر ديگري را به بنيانگذار ترفيع دهيد.',

	'BAN_ALREADY_ENTERED'	=> 'این تحریم قبلا با موفقیت وارد شده بود،لیست تحریم ها بروزرسانی نشد.',
	'BAN_SUCCESSFUL'		=> 'تحریم با موفقیت وارد شد.',

	'CANNOT_BAN_ANONYMOUS'			=> 'کاربر anonymous قابل اخراج نیست ! اما می توانید سطوح دسترسی آن را از قسمت تنظیمات سطوح دسترسی تعیین کنید.',
	'CANNOT_BAN_FOUNDER'			=> 'نمی توانید اکانت های صاحبان امتیاز سایت را تحریم کنید.',
	'CANNOT_BAN_YOURSELF'	                => 'شما مجاز به تحریم کردن خودتان نمی باشید.',
	'CANNOT_DEACTIVATE_BOT'	                => 'شما مجاز به غير فعال سازي حساب هاي bot نيستيد. لطفا آنرا غير فعال کنيد.',
	'CANNOT_DEACTIVATE_FOUNDER'		=> 'نمی توانید اکانت های صاحبان امتیاز سایت را غیرفعال کنید.',
	'CANNOT_DEACTIVATE_YOURSELF'	        => 'نمی توانید اکانت خودتان را غیرفعال کنید.',
	'CANNOT_FORCE_REACT_BOT'		=> 'نمی توانید روبات ها را مجبور به فعال سازی مجدد اکانتشان کنید،لطفا برای فعال سازی آنها به صفحه مربوطه رباط ها رجوع کنید.',
	'CANNOT_FORCE_REACT_FOUNDER'	=> 'نمی توانید صاحبان امتیاز سایت را مجبور به فعال سازی مجدد اکانتشان کنید.',
	'CANNOT_FORCE_REACT_YOURSELF'	=> 'نمی توانید خودتان را مجبور به فعال سازی مجدد اکانتتان کنید.',
	'CANNOT_REMOVE_ANONYMOUS'		=> 'نمی توانید اکانت کاربران مهمان را حذف کنید.',
	'CANNOT_REMOVE_FOUNDER'			=> 'نمیتوانید اکانت مدیر کل انجمن را حذف کنید.',
	'CANNOT_REMOVE_YOURSELF'		=> 'نمی توانید اکانت کاربری خودتان را حذف کنید.',
	'CANNOT_SET_FOUNDER_IGNORED'	=> 'نمی توانید کاربران تحریم شده را به عنوان صاحب امتیاز سایت ترفیع رتبه دهید.',
	'CANNOT_SET_FOUNDER_INACTIVE'	=> 'برای ترفیع رتبه کاربر باید ابتدا اکانت وی را فعال کنید.',
	'CONFIRM_EMAIL_EXPLAIN'			=> 'فقط هنگامی به مشخص کردن این گزینه نیاز هست که ایمیل کاربر را تغییر دهید',

	'DELETE_POSTS'			=> 'حذف پست ها',
	'DELETE_USER'			=> 'حذف کاربر',
	'DELETE_USER_EXPLAIN'	=> 'لطفا توجه داشته باشید که حذف کاربر غیرقابل برگشت می باشد.',

	'FORCE_REACTIVATION_SUCCESS'	=> 'با موفقیت مجبور به فعال سازی مجدد شد.',
	'FOUNDER'						=> 'صاحب امتیاز',
	'FOUNDER_EXPLAIN'				=> 'صاحبان امتیاز سطوح دسترسی مدیریت را دارا می باشند و نمی توان آنها را حذف و تحریم کرد و یا هشدار داد.',

	'GROUP_APPROVE'					=> 'تأیید کاربر',
	'GROUP_DEFAULT'					=> 'انتخاب گروه پیشفرض برای کاربر',
	'GROUP_DELETE'					=> 'حذف کاربر از گروه',
	'GROUP_DEMOTE'					=> 'تنزیل رتبه ی رهبر گروه',
	'GROUP_PROMOTE'					=> 'ترفیع رتبه به رهبر گروه',

	'IP_WHOIS_FOR'			=> 'IP whois برای %s',

	'LAST_ACTIVE'			=> 'آخرین فعالیت',

	'MOVE_POSTS_EXPLAIN'	=> 'لطفا انجمنی را انتخاب کنید که می خواهید پست های کاربر را از آن انتقال دهید.',

	'NO_SPECIAL_RANK'		=> 'رتبه مخصوصی مشخص نشده است',
	'NO_WARNINGS'			=> 'بدون هشدار',
	'NOT_MANAGE_FOUNDER'	=> 'نمی توانید اکانت صاحب امتیاز سایت را مدیریت کنید،فقط صاحبان امتیاز سایت می توانند اینگونه اکانت ها را مدیریت کنند.',

	'QUICK_TOOLS'			=> 'ابزار سریع',

	'REGISTERED'			=> 'عضو شده',
	'REGISTERED_IP'			=> 'عضو شده با IP',
	'RETAIN_POSTS'			=> 'نگه داشتن پست ها',

	'SELECT_FORM'			=> 'انتخاب فرم',
	'SELECT_USER'			=> 'انتخاب کاربر',

	'USER_ADMIN'					=> 'مدیریت کاربر',
	'USER_ADMIN_ACTIVATE'			=> 'فعال سازی اکانت',
	'USER_ADMIN_ACTIVATED'			=> 'کاربر با موفقیت فعال شد.',
	'USER_ADMIN_AVATAR_REMOVED'		=> 'با موفقیت آواتار از اکانت کاربران حذف شد.',
	'USER_ADMIN_BAN_EMAIL'			=> 'تحریم توسط ایمیل',
	'USER_ADMIN_BAN_EMAIL_REASON'	=> 'آدرس ایمیل از مدیریت کاربران تحریم شد',
	'USER_ADMIN_BAN_IP'				=> 'تحریم توسط IP',
	'USER_ADMIN_BAN_IP_REASON'		=> 'IP از مدیریت کاربران تحریم شد.',
	'USER_ADMIN_BAN_NAME_REASON'	=> 'نام کاربری از مدیریت کاربران تحریم شد.',
	'USER_ADMIN_BAN_USER'			=> 'تحریم به وسیله نام کاربری',

	'USER_ADMIN_DEACTIVATE'			=> 'غیرفعال سازی اکانت',
	'USER_ADMIN_DEACTIVED'			=> 'اکانت کاربر با موفقیت غیرفعال شد',
	'USER_ADMIN_DEL_ATTACH'			=> 'حدف همه پیوست ها',
	'USER_ADMIN_DEL_AVATAR'			=> 'حذف آواتار',
	'USER_ADMIN_DEL_OUTBOX'			=> 'خالی کردن صندوق خروجی پیغام خصوصی',
	'USER_ADMIN_DEL_POSTS'			=> 'حذف همه پست ها',
	'USER_ADMIN_DEL_SIG'			=> 'حذف امضا',
	'USER_ADMIN_EXPLAIN'			=> 'در این بخش می توانید اطلاعات کاربر را تغییر دهید و اکانت وی را مدیریت کنید.',
	'USER_ADMIN_FORCE'				=> 'اجبار به فعال سازی مجدد',
	'USER_ADMIN_LEAVE_NR'			=> 'حذف از کاربران به تازگی عضو شده',
	'USER_ADMIN_MOVE_POSTS'			=> 'انتقال همه پست ها',
	'USER_ADMIN_SIG_REMOVED'		=> 'با موفقیت امضای کاربر از اکانت وی حذف شد.',

	'USER_ATTACHMENTS_REMOVED'		=> 'با مئفقیت همه پیوست های این کاربر حذف شد.',
	'USER_AVATAR_NOT_ALLOWED'		=> 'آواتار قابل نمایش نیست زیرا ویژگی آواتار غیرفعال شده است.',
	'USER_AVATAR_UPDATED'			=> 'با موفقیت جزئیات آواتار کاربر بروزرسانی شد.',
	'USER_AVATAR_TYPE_NOT_ALLOWED'	=> 'آواتار کنونی قابل نمایش نیست زیرا فرمت آن مجاز نمی باشد.',

	'USER_CUSTOM_PROFILE_FIELDS'	=> 'فیلد های سفارشی پروفایل',
	'USER_DELETED'					=> 'کاربر با موفقیت حذف شد.',

	'USER_GROUP_ADD'				=> 'اضافه کردن کاربر به گروه',
	'USER_GROUP_NORMAL'				=> 'کاربر عضوی از گروه می باشد',
	'USER_GROUP_PENDING'			=> 'کاربر در حال انتظار برای عضویت در گروه است.',
	'USER_GROUP_SPECIAL'			=> 'کاربر عضو گروه از پیش مشخص شده می باشد.',

	'USER_LIFTED_NR'				=> 'حالت "کاربر به تازگی عضو شده" از کاربر حذف شد.',
	'USER_NO_ATTACHMENTS'			=> 'فایل پیوستی برای نمایش وجود ندارد.',
	'USER_NO_POSTS_TO_DELETE'			=> 'کاربر پستی برای حذف شدن ندارد.',
	'USER_OUTBOX_EMPTIED'			=> 'با موفقیت صندوق خروجی پیغام های خصوصی کاربر خالی شد.',
	'USER_OUTBOX_EMPTY'				=> 'صندوق خروجی پیغام خصوصی کاربر در حال حاضر خالی می باشد.',
	'USER_OVERVIEW_UPDATED'			=> 'جزئیات کاربر بروزرسانی شد.',
	'USER_POSTS_DELETED'			=> 'با موفقیت تمامی پست های کاربر حذف شد.',
	'USER_POSTS_MOVED'				=> 'با موفقیت پست های کاربر به انجمن مقصد منتقل شد.',
	'USER_PREFS_UPDATED'			=> 'ویژگی های کاربر بروزرسانی شد.',
	'USER_PROFILE'					=> 'پروفایل کاربر',
	'USER_PROFILE_UPDATED'			=> 'پروفایل کاربر بروزرسانی شد..',
	'USER_RANK'						=> 'رتبه کاربر',
	'USER_RANK_UPDATED'				=> 'رتبه کاربر بروزرسانی شد.',
	'USER_SIG_UPDATED'				=> 'امضای کاربر با موفقیت بروزرسانی شد.',
	'USER_WARNING_LOG_DELETED'		=> 'اطلاعاتی در دسترس نیست،ممکن است رویداد ها حذف شده باشند.',
	'USER_TOOLS'					=> 'ابزار پایه',
));
