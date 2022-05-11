<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * Persian Translator : Meis@M Nobari (c) php-bb.ir
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
	'ACP_QUICKREPLY'                       => 'پاسخ سریع پیشرفته',
	'ACP_QUICKREPLY_EXPLAIN'               => 'تنظیمات پاسخ سریع پیشرفته',
	'ACP_QUICKREPLY_TITLE'                 => 'پاسخ سریع پیشرفته',
	'ACP_QUICKREPLY_TITLE_EXPLAIN'         => 'در این قسمت میتوانید تنظیمات کلی و پایه مربوط به پاسخ سریع پیشرفته را انجام دهید<br />توجه: “پذیرفتن پاسخ سریع” و “فعال سازی پاسخ سریع” دو گزینه ای است که باید در تنظیمات اصلی phpBB فعال باشند تا عملکرد خوبی از خود نشان دهند.',
	//
	'ACP_QUICKREPLY_QN'                    => 'تنظیمات نقل قول سریع و نام کاربری سریع',
	'ACP_QUICKREPLY_QN_EXPLAIN'            => 'تنظیمات مربوط به نقل قول سریع و نام کاربری سریع و پیشرفته',
	'ACP_QUICKREPLY_QN_TITLE'              => 'پاسخ سریع پیشرفته',
	'ACP_QUICKREPLY_QN_TITLE_EXPLAIN'      => 'در این قسمت میتوانید تنظیمات مربوط به نقل قول سریع و نام کاربری سریع را انجام دهید<br />توجه: این تنظیمات تاثیری در تالارها هنگامی که پاسخ سریع غیر فعال باشد ندار.',
	//
	'ACP_QUICKREPLY_PLUGINS'               => 'تنظیمات بیشتر',
	'ACP_QUICKREPLY_PLUGINS_EXPLAIN'       => 'تنظیمات بیشتر',
	'ACP_QUICKREPLY_PLUGINS_TITLE'         => 'پاسخ سریع پیشرفته',
	'ACP_QUICKREPLY_PLUGINS_TITLE_EXPLAIN' => 'در این قسمت شما میتوانید تنظیمات حرفه ای مربوط به پاسخ سریع را انجام دهید<br />توجه: این تنظیمات بدون در نظر گرفتن اینکه پاسخ سریع در یک انجمن خاص فعال شده است کار میکند.',
	//
	'ACP_QR_AJAX_PAGINATION'               => 'رفتن به صفحات موضوعات بدون بارگزاری',
	'ACP_QR_AJAX_PAGINATION_EXPLAIN'       => 'با فعال سازی این گزینه کاربران میتوانند بدون رفرش ، به صفحات دیگر موضوعات به صورت ایجکس منتقل شوند.',
	'ACP_QR_AJAX_SUBMIT'                   => 'حالت ایجکس ارسال',
	'ACP_QR_AJAX_SUBMIT_EXPLAIN'           => 'با فعال کردن این گزینه ارسالات در پاسخ سریع به صورت ایجکس و بدون رفرش صفحه انجام خواهد شد.',
	'ACP_QR_ALLOW_FOR_GUESTS'              => 'مجوز استفاده از پاسخ سریع برای مهمانان',
	'ACP_QR_ATTACH'                        => 'مجوز پیوست ها',
	'ACP_QR_ATTACH_EXPLAIN'                => 'مجوز فعال شدن فایل پیوست در پاسخ سریع',
	'ACP_QR_BBCODE'                        => 'نمایش BBcode در پاسخ سریع',
	'ACP_QR_BBCODE_EXPLAIN'                => 'نمایش دکمه های BBcode در پاسخ سریع',
	'ACP_QR_CAPSLOCK'                      => 'فعال کردن حالت تبدیل متن',
	'ACP_QR_COLOUR_NICKNAME'               => 'افزودن متن هنگام ارجاع کاربر',
	'ACP_QR_COMMA'                         => 'افزودن کاما هنگام ارجاع کاربر',
	'ACP_QR_COMMA_EXPLAIN'                 => 'افزودن خودکار کاما هنگام استفاده از قابلیت ارجاع کاربر.',
	'ACP_QR_CTRLENTER_NOTICE'              => 'فعال کردن “Ctrl+Enter” برای راهنمای متنی',
	'ACP_QR_CTRLENTER_NOTICE_EXPLAIN'      => 'راهنمای ابزار بعد از رفتن نشانگر موس روی دکمه "ارسال" در قست پاسخ سریع نمایش داده میشود.',
	'ACP_QR_ENABLE_AJAX_SUBMIT'            => 'فعال کردن حالت ایجکس برای همه تالارها',
	'ACP_QR_ENABLE_AJAX_SUBMIT_EXPLAIN'    => 'مجوز استفاده از حالت ایجکس برای ارسال مطالب در همه تالارها',
	'ACP_QR_ENABLE_RE'                     => 'فعال کردن “پاسخ:”',
	'ACP_QR_ENABLE_RE_EXPLAIN'             => 'افزودن خودکار پیشوند "پاسخ:" در قسمت عنوان پست ها در پاسخ سریع',
	'ACP_QR_ENABLE_QUICK_REPLY'            => 'فعال سازی پاسخ سریع در همه تالارها',
	'ACP_QR_ENABLE_QUICK_REPLY_EXPLAIN'    => 'فعال کردن پاسخ سریع در همه تالارها',
	'ACP_QR_FORM_TYPE'                     => 'نوع پاسخ سریع',
	'ACP_QR_FORM_TYPE_EXPLAIN'             => 'با انتخاب این گزینه حالت استاندارد برای رفتن به صفحات موضوع شکل میگیرد.', // reserved
	'ACP_QR_FORM_TYPE_FIXED'               => 'ثابت',
	'ACP_QR_FORM_TYPE_SCROLL'              => 'ثابت به همراه  ایجکس ارسال', // reserved
	'ACP_QR_FORM_TYPE_STANDARD'            => 'استاندارد',
	'ACP_QR_FORUM_AJAX_SUBMIT'             => 'فعال سازی حالت ایجکس',
	'ACP_QR_FORUM_AJAX_SUBMIT_EXPLAIN'     => 'فعال سازی ارسال پاسخ در حالت ایجکس',
	'ACP_QR_FULL_QUOTE'                    => 'وارد کردن تمامی نقل قول در پاسخ سریع',
	'ACP_QR_FULL_QUOTE_EXPLAIN'            => 'حالت استاندارد پاسخ با نقل قول',
	'ACP_QR_HIDE_SUBJECT_BOX'              => 'مخفی کردن کادر موضوع درصورتیکه حالت امکان تغییر غیر فعال باشد',
	'ACP_QR_HIDE_SUBJECT_BOX_EXPLAIN'      => 'اگر کاربری سطح دسترسی برای ویرایش عنوان موضوع را نداشته باشد ، کادر عنوان مخفی می شود به جای غیر فعال شدن آن.',
	'ACP_QR_LAST_QUOTE'                    => 'فعال کردن نقل قول کامل برای آخرین پست و موضوع',
	'ACP_QR_LAST_QUOTE_EXPLAIN'            => 'مجوز استفاده از نقل قول کامل به صورت دکمه<br /><em>توجه کنید که در این حالت دکمه نقل قول مخفی خواهد شد هنگامیکه این گزینه غیر فعال باشد.این گزینه مجوز کاربر برای نقل قول کامل را منع میکند.</em>',
	'ACP_QR_LEAVE_AS_IS'                   => 'پیش فرض',
	'ACP_QR_LEAVE_AS_IS_EXPLAIN'           => '<em>اگر تنظیمات به حالت"پیشفرض" باشد ، تنظیمات موثر نخواهد بود.</em>',
	'ACP_QR_LEGEND_AJAX'                   => 'تنظیمات ایجکس',
	'ACP_QR_LEGEND_DISPLAY'                => 'نمایش تنظیمات',
	'ACP_QR_LEGEND_GENERAL'                => 'تنظیمات کلی',
	'ACP_QR_LEGEND_QUICKNICK'              => 'تنظیمات نام کابری سریع',
	'ACP_QR_LEGEND_QUICKQUOTE'             => 'تنظیمات نقل قول سریع',
	'ACP_QR_LEGEND_SPECIAL'                => 'جلوه های خاص',
	'ACP_QR_QUICKNICK'                     => 'فعال سازی حالت نام کاربری سریع در منو کشویی',
	'ACP_QR_QUICKNICK_EXPLAIN'             => 'Creates a dropdown with a link “Refer by username” that inserts the post author’s username in the quick reply form. That dropdown is triggered by a click on post author’s username and also contains links to user’s profile and “Reply in PM” (when available).<br />If this setting is enabled and the setting “Enable quick nick (under avatar)” is disabled, the user can switch to the version of the link “Refer by username” under avatar in the User Control Panel.',
	'ACP_QR_QUICKNICK_STRING'              => 'فعال سازی نام کاربری سریع ( زیرنمایه)',
	'ACP_QR_QUICKNICK_STRING_EXPLAIN'      => 'نمایش لینک " ارجاع به کاربر" در بخش پست  کاربران که باعث اضافه شدن نام کاربر در پاسخ سریع میشود.',
	'ACP_QR_QUICKNICK_PM'                  => 'شامل دکمه " پیغام خصوصی" به صورت یک منو کشویی ',
	'ACP_QR_QUICKNICK_REF'                 => 'فعال سازی تگ مخصوص برای ارجاع کاربر',
	'ACP_QR_QUICKNICK_REF_EXPLAIN'         => 'بی بی کد [ref] به جای [b] هنگام استفاده از عملکرد " ارجاع کاربر".<br /><em>توجه کنید هنگام غیرفعال بودن این گزینه کاربران به صورت یک اطلاعیه درباره نقل قول آن مطلع خواهند شد.</em>',
	'ACP_QR_QUICKQUOTE'                    => 'فعال سازی پاپ آپ نقل قول',
	'ACP_QR_QUICKQUOTE_BUTTON'             => 'فعال سازی دکمه نقل قول سریع',
	'ACP_QR_QUICKQUOTE_BUTTON_EXPLAIN'     => 'مجوز تبدیل نقل قول به نقل قول استاندارد دکمه<br /><em>توجه کنید که دکمه نقل قول در این حالت مخفی خواهد شد و کاربران اجازه نقل قول کامل را نخواهند داشت.</em>',
	'ACP_QR_QUICKQUOTE_EXPLAIN'            => 'با فعال کردن این گزینه نقل قول ها به صورت "پاپ اپ" هنگام انتخاب متن ظاهر میشود.',
	'ACP_QR_SCROLL_TIME'                   => 'زمان برای حالت اسکرول و انیمیشن ایجکس',
	'ACP_QR_SCROLL_TIME_EXPLAIN'           => 'زمان بر حسب میلی ثانیه جهت قابلیت اسکرول . وارد کردن مقدار 0 برای حالت استاندارد',
	'ACP_QR_SHOW_BUTTON_TRANSLIT'          => 'نمایش دکمه " تبدیل"',
	'ACP_QR_SHOW_SUBJECTS'                 => 'نمایش عنوان موضوع پست در موضوع',
	'ACP_QR_SHOW_SUBJECTS_IN_SEARCH'       => 'نمایش عنوان پست ها درنتایج جستجو',
	'ACP_QR_SMILIES'                       => 'نمایش شکلک ها',
	'ACP_QR_SMILIES_EXPLAIN'               => 'مجوز استفاده از شکلک ها در پاسخ سریع',
));
