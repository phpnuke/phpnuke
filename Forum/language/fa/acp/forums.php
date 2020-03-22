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

// Forum Admin
$lang = array_merge($lang, array(
	'AUTO_PRUNE_DAYS'			=> 'هرس کردن خودکار پست هاي کهنه شده',
	'AUTO_PRUNE_DAYS_EXPLAIN'	=> 'مدت زمانی (به روز) که اگر بعد از آن پستی ارسال نشود،موضوع مربوطه حذف خواهد شد.',
	'AUTO_PRUNE_FREQ'			=> 'دوره هرس خودکار',
	'AUTO_PRUNE_FREQ_EXPLAIN'	=> 'تعداد روزهای مورد نیاز برای هرس دوباره',
	'AUTO_PRUNE_VIEWED'			=> 'سن بازدید از پست در هرس خودکار',
	'AUTO_PRUNE_VIEWED_EXPLAIN'	=> 'تعداد روزی که اگر از پستی در موضوع بازدید نشود،آن موضوع حذف خواهد شد.',
	'AUTO_PRUNE_SHADOW_FREQ'	=> 'دوره هرس سایه مباحث',
	'AUTO_PRUNE_SHADOW_DAYS'	=> 'مدت بین روزهای رویداد هرس سایه مباحث',
	'AUTO_PRUNE_SHADOW_DAYS_EXPLAIN'	=> 'تعداد روزهای تعیین شده ای که بعد از آن سایه مباحث حذف خواهد شد.',
	'AUTO_PRUNE_SHADOW_FREQ_EXPLAIN'	=> 'تعداد دفعات هرس خودکار سایه مباحث در روز',

	'CONTINUE'						=> 'ادامه',
	'COPY_PERMISSIONS'				=> 'کپي کردن سطح دسترسي از',
	'COPY_PERMISSIONS_EXPLAIN'		=> 'با کپی کردن سطوح دسترسی موجود از انجمنی می توانید به آسانی سطوح دسترسی انجمن جدیدتان را تنظیم کنید.',
	'COPY_PERMISSIONS_ADD_EXPLAIN'	=> 'در صورت انتخاب یک انجمن از اینجا، این انجمن سطوح دسترسی مشابه انجمن انتخاب شده خواهد داشت. در صورتی که انجمنی انتخاب نگردد، تا هنگامی که سطوح دسترسی مورد نظر تنظیم نشده باشند،انجمن قابل نمایش نخواهد بود.',
	'COPY_PERMISSIONS_EDIT_EXPLAIN'	=> 'اگر کپی کردن سطوح دسترسی انجمن را انتخاب کرده باشید،سطوح دسترسی فعلی انجمنتان با سطوح دسترسی ای که در این بخش مشخص می شود بازنویسی خواهد شد. اگر انجمنی انتخاب نشود،سطوح دسترسی فعلی حفظ خواهند شد.',
	'COPY_TO_ACL'					=> 'همچنین می توانید %sسطوح دسترسی جدیدی%s را برای این انجمن تنظیم کنید.',
	'CREATE_FORUM'					=> 'ایجاد انجمن جدید',

	'DECIDE_MOVE_DELETE_CONTENT'		=> 'حذف محتوا و یا انتقال به انجمن',
	'DECIDE_MOVE_DELETE_SUBFORUMS'		=> 'حذف زیرانجمن ها و یا انتقال به انجمن',
	'DEFAULT_STYLE'						=> 'قالب پیشفرض',
	'DELETE_ALL_POSTS'					=> 'حذف پست ها',
	'DELETE_SUBFORUMS'					=> 'حذف زیرانجمن ها و پست ها',
	'DISPLAY_ACTIVE_TOPICS'				=> 'فعال کردن موضوعات فعال',
	'DISPLAY_ACTIVE_TOPICS_EXPLAIN'		=> 'اگر بله انتخاب شود،موضوعات فعال در زیرانجمن های انتخاب شده در زیر این گروه نمایش داده خواهند شد.',

	'EDIT_FORUM'					=> 'ویرایش انجمن',
	'ENABLE_INDEXING'				=> 'فعال سازی شاخص بندی جستجو',
	'ENABLE_INDEXING_EXPLAIN'		=> 'اگر بله انتخاب شود،پست های ایجاد شده در شاخص های جستجو قرار خواهند گرفت.',
	'ENABLE_POST_REVIEW'			=> 'فعال سازی پیشنمایش پست ها',
	'ENABLE_POST_REVIEW_EXPLAIN'	=> 'اگر بله انتخاب شود،کاربران در حین ارسال پست خواهند توانست پیشنمایش مطلب ارسالی را ببینند،در انجمن های چت این گزینه باید غیرفعال باشد.',
	'ENABLE_QUICK_REPLY'			=> 'فعال سازی پاسخ سریع',
	'ENABLE_QUICK_REPLY_EXPLAIN'	=> 'این گزینه پاسخ سریع را در انجمن فعال خواهد کرد. اگر پاسخ سریع در کل تالار غیرفعال باشد،این گزینه تاثیری نخواهد داشت. پاسخ سریع برای کاربرانی نمایش داده خواهد شد که سطح دسترسی لازم برای ارسال پست را داشته باشند',
	'ENABLE_RECENT'					=> 'نمایش موضوعات فعال',
	'ENABLE_RECENT_EXPLAIN'			=> 'اگر بله انتخاب شود،موضوعات فعال موجود در این انجمن در لیست موضوعات فعال نمایش داده خواهد شد.',
	'ENABLE_TOPIC_ICONS'			=> 'فعال سازی آیکون های موضوع',

	'FORUM_ADMIN'						=> 'مدیریت انجمن',
	'FORUM_ADMIN_EXPLAIN'				=> ' در phpBB همه چیز براساس انجمن ها است، گروه ها نیز نوع ویژه ای از انجمن ها هستند، همه چیز براساس انجمن ها ساخته می شود به طوری که می توانید به تعداد نامحدودی زیرانجمن ایجاد کرده و مشخص کنید که در کدام یک از آنها امکان ارسال پست وجود داشته باشد (برای مثال تایین این که مانند گروه های قدیمی عمل کند و یا نه). در این بخش می توانید علاوه بر برخی از تنظیمات انجمن ها را اضافه،ویرایش،حذف و یا قفل کنید اگر پست ها و موضوعات انطباقات خود را از دست بدهند، می توانید مجددا آنها را منطبق (resync) کنید. <strong>برای نمایش انجمن های جدید باید سطوح دسترسی برای آنها تایین کنید و یا از سایر انجمن ها سطوح دسترسی را کپی کنید.</strong>',
	'FORUM_AUTO_PRUNE'					=> 'فعال سازی هرس خودکار',
	'FORUM_AUTO_PRUNE_EXPLAIN'			=> 'انجمن موضوعات را به طور خودکار هرس می کند.پارامتر های سن/تناوب را در قسمت پایین تکمیل کنید.',
	'FORUM_CREATED'						=> 'انجمن با موفقیت ایجاد شد.',
	'FORUM_DATA_NEGATIVE'				=> 'پارامتر های هرس نمی تواند منفی باشد.',
	'FORUM_DESC_TOO_LONG'				=> 'توضیخات انجمن بسیار طولانی است،توضیحات باید کمتر از 4000 کاراکتر باشند.',
	'FORUM_DELETE'						=> 'حذف انجمن',
	'FORUM_DELETE_EXPLAIN'				=> 'با استفاده از فرم زیر می توانید انجمنی را حذف کنید و اگر فرم قابل ارسال باشد می توانید مشخص کنید که پست های(و یا زیرانجمن های)انجمن به کجا منتقل شوند.',
	'FORUM_DELETED'						=> 'انجمن با موفقیت حذف شد.',
	'FORUM_DESC'						=> 'توضیحات',
	'FORUM_DESC_EXPLAIN'				=> 'وارد کردن هرگونه کد HTML تاثیری در نمایش متن نخواهد داشت. اگر انجمن انتخابی به عنوان شاخه در نظر گرفته شود توضیحات نمایش داده نخواهد شد',
	'FORUM_EDIT_EXPLAIN'				=> 'با استفاده از فرم زیر می توانید انجمنتان را مدیریت کنید. لطفا توجه داشته باشید که تنظیمات مدیریت و شمارنده پست ها برای کاربران و گروه های کاربری در سطوح دسترسی اعمال می شود. ',
	'FORUM_IMAGE'						=> 'تصویر انجمن',
	'FORUM_IMAGE_EXPLAIN'				=> 'محلی در دایرکتوری مربوط به phpBB که تصویر مربوط به انجمن را نشان خواهد داد.',
	'FORUM_IMAGE_NO_EXIST'				=> 'توصیر مشخص شده ای برای انجمن موجود نیست.',
	'FORUM_LINK_EXPLAIN'				=> 'URL کامل (شامل پروتکل،برای مثال : <samp>http://</samp>) که اگر کاربر بر روی انجمن کلیک کند به لینک مشخص شده انتقال خواهد یافت، برای مثال  برای مثال : <samp>http://www.phpbb.com/</samp>.',
	'FORUM_LINK_TRACK'					=> 'پیگیری لینک انتقال',
	'FORUM_LINK_TRACK_EXPLAIN'			=> 'تعداد دفعات انتقال مسیر رل ذخیره می کند.',
	'FORUM_NAME'						=> 'نام انجمن',
	'FORUM_NAME_EMPTY'					=> 'باید نامی را برای این انجمن وارد کنید.',
	'FORUM_PARENT'						=> 'انجمن ریشه',
	'FORUM_PASSWORD'					=> 'کلمه عبور انجمن',
	'FORUM_PASSWORD_CONFIRM'			=> 'تایید کلمه عبور انجمن',
	'FORUM_PASSWORD_CONFIRM_EXPLAIN'	=> 'اعمال این تنظیمات فقط هنگامی لازم است که کلمه عبوری وارد شود.',
	'FORUM_PASSWORD_EXPLAIN'			=> 'با استفاده از سطوح دسترسی سیستم، کلمه عبوری را برای انجمن تایین می کند.',
	'FORUM_PASSWORD_UNSET'				=> 'حذف کلمه عبور انجمن',
	'FORUM_PASSWORD_UNSET_EXPLAIN'		=> 'اگر می خواهید که کلمه عبور انجمن حذف شود، بر روی این گزینه تیک بزنید.',
	'FORUM_PASSWORD_OLD'				=> 'کلمه عبور انجمن از روش قدیمی هش استفاده می کند و باید تغییر داده شود.',
	'FORUM_PASSWORD_MISMATCH'			=> 'دو کلمه عبور وارد شده یکسان نمی باشند',
	'FORUM_PRUNE_SETTINGS'				=> 'تنظیمات هرس انجمن',
	'FORUM_PRUNE_SHADOW'				=> 'فعال سازی هرس خودکار سایه مباحث',
	'FORUM_PRUNE_SHADOW_EXPLAIN'			=> 'فعال سازی هرس خودکار سایه مباحث به معنای دوره و زمان هرس آن خواهد بود.',
	'FORUM_RESYNCED'					=> 'انجمن “%s” با موفقیت منطبق شد (resync)',
	'FORUM_RULES_EXPLAIN'				=> 'قوانین انجمن در هر صفحه مشخص شده از انجمن نمایش داده خواهد شد.',
	'FORUM_RULES_LINK'					=> 'لینک به قوانین انجمن',
	'FORUM_RULES_LINK_EXPLAIN'			=> 'می توانید پست و یا لینکی را در این تنظیم وارد کنید، انتخاب شما جایگزین متن پیشفرض قوانین تالار خواهد شد. ',
	'FORUM_RULES_PREVIEW'				=> 'آزمایش قوانین انجمن',
	'FORUM_RULES_TOO_LONG'				=> 'قوانین انجمن باید کمتر از 4000 کاراکتر باشد.',
	'FORUM_SETTINGS'					=> 'تنظیمات انجمن',
	'FORUM_STATUS'						=> 'حالت انجمن',
	'FORUM_STYLE'						=> 'قالب انجمن',
	'FORUM_TOPICS_PAGE'					=> 'موضوعات در هر صفحه',
	'FORUM_TOPICS_PAGE_EXPLAIN'			=> 'اگر عددی به غیر از 0 را وارد کنید، این تعداد جایگزین تعداد پپیشفرض خواهد شد.',
	'FORUM_TYPE'						=> 'نوع انجمن',
	'FORUM_UPDATED'						=> 'اطلاعات انجمن با موفقیت بروزرسانی شد.',

	'FORUM_WITH_SUBFORUMS_NOT_TO_LINK'		=> 'در حال تبدیل انجمنی زیرانجمن دار به لینک هستید،قبل از پردازش این فرآیند لطفا زیرانجمن ها را به انجمنی دیگر منتقل دهید زیرا پس از تبدیل این انجمن به لینک،زیرانجمن های آن در دسترس نخواهد بود.',

	'GENERAL_FORUM_SETTINGS'	=> 'تنظیمات عمومی انجمن',

	'LINK'						=> 'لینک',
	'LIST_INDEX'				=> 'نمايش زير انجمنها در فهرست',
	'LIST_INDEX_EXPLAIN'		=> 'با فعال کردن اين گزينه, انجمن مورد نظر در هر کجاي انجمنها باشد در نخستين صفحه قابل نمايش ميباشد. براي آنکه تمامي زير انجمنها فقط در انجمن والد خود نمایش داده شوند گزینه "نمايش فهرست زير انجمنها در انجمن والد" را انتخاب نمائید.',
	'LIST_SUBFORUMS'			=> 'نمايش فهرست زير انجمنها در انجمن والد',
	'LIST_SUBFORUMS_EXPLAIN'	=> 'در صورت فعال نمودن اين گزينه تمامي زير انجمنهاي هر والد فقط در فهرست زير انجمن خود والد نمايش داده ميشوند. در صورتي که ميخواهيد انجمنها در صفحه نخست هم نمايش داده شوند اين گزينه "نمايش زير انجمنها در فهرست" را فعال نمائيد.',
	'LOCKED'					=> 'قفل شده',

	'MOVE_POSTS_NO_POSTABLE_FORUM'	=> 'در انجمنی که برای انتقال پست ها به آن انتخاب کرده اید،ارسال پست مقدور نیست، لطفا انجمنی را انتخاب کنید که ارسال پست در آن مقدر باشد.',
	'MOVE_POSTS_TO'					=> 'انتقال پست ها به',
	'MOVE_SUBFORUMS_TO'				=> 'انتقال زیرانجمن ها به',

	'NO_DESTINATION_FORUM'			=> 'انجمنی را برای انتقال محتوا به آن انتخاب نکرده اید.',
	'NO_FORUM_ACTION'				=> 'مشخص نشده است که چه عملیاتی بر روی محتوای انجمن صورت بگیرد.',
	'NO_PARENT'						=> 'بدون ریشه',
	'NO_PERMISSIONS'				=> 'سطوح دسترسی را کپی نکن',
	'NO_PERMISSION_FORUM_ADD'		=> 'سطح دسترسی کافی برای اضافه کردن انجمن ها را ندارید.',
	'NO_PERMISSION_FORUM_DELETE'	=> 'سطح دسترسی کافی برای حذف انجمن ها را ندارید.',

	'PARENT_IS_LINK_FORUM'		=> 'در انجمن هایی که به صورت لینک هستند،نگهداری زیرانجمن ها مقدور نیست.',
	'PARENT_NOT_EXIST'			=> 'شاخه ریشه موجود نیست',
	'PRUNE_ANNOUNCEMENTS'		=> 'هرس کردن اطلاعیه ها',
	'PRUNE_STICKY'				=> 'هرس کردن موضوعات مهم',
	'PRUNE_OLD_POLLS'			=> 'هرس کردن نظرسنجی های قدیمی',
	'PRUNE_OLD_POLLS_EXPLAIN'	=> 'اگر به تعداد روز سن پست ها به نظرسنجی رای داده نشود، نظرسنجی موضوع حذف خواهد شد.',

	'REDIRECT_ACL'	=> 'حال می توانید %sسطوح دسترسی را برای این انجمن تنظیم کنید%s.',

	'SYNC_IN_PROGRESS'			=> 'همزمان سازي انجمن',
	'SYNC_IN_PROGRESS_EXPLAIN'	=> ' همسان سازی کنونی انجمن های %1$d/%2$d.',

	'TYPE_CAT'			=> 'گروه',
	'TYPE_FORUM'		=> 'انجمن',
	'TYPE_LINK'			=> 'لینک',

	'UNLOCKED'	=> 'باز شده',
));
