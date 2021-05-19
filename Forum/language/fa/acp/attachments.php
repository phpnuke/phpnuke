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

$lang = array_merge($lang, array(
	'ACP_ATTACHMENT_SETTINGS_EXPLAIN'	=> 'در این قسمت شما می‌توانید تنظیمات پیش‌فرض پیوست‌ها و همچنین شاخه های ویژه را پیکربندی نمایید.',
	'ACP_EXTENSION_GROUPS_EXPLAIN'		=> 'در این قسمت شما می‌توانید گروه‌های پسوندی را اضافه, حذف, ویرایش و یا غیرفعال کنید. گزینه‌های بیشتر در شاخه های ویژه آن‌ها قرار دارد.تغییر مکانیزم دانلود و تعیین یک آیکون برای نمایش در جلوی پیوست برای تشخیص هویت از این گزینه‌ها محسوب می شوند.',
	'ACP_MANAGE_EXTENSIONS_EXPLAIN'		=> 'در این اینجا شما می‌توانید همه پسوند های فعال را اداره کنید. برای فعال سازی پسوندتان, لطفا به قاب "مدیریت گروه پسوندها" رجوع فرمایید. ما شدیدا به شما توصیه می کنیم که پسوند های اسکریپتی را فعال نکنید (مثال از این قبیل <code>php</code>, <code>php3</code>, <code>php4</code>, <code>phtml</code>, <code>pl</code>, <code>cgi</code>, <code>py</code>, <code>rb</code>, <code>asp</code>, <code>aspx</code>, و ...).',
	'ACP_ORPHAN_ATTACHMENTS_EXPLAIN'	=> 'در این قسمت شما می‌توانید فایل‌های غیرفعال شده را مشاهده کنید. این رخ داد غالبا زمانی به وقع می پیوندد که کاربر فایل را پیوست کرده ولی پست را ارسال نمی کند. اینک شما قادرید این فایل‌ها را حذف یا ضمیمه پست‌های موجود نمایید. پیوست کردن این پستها  نیازمند  شماره (ID) صحیحی از پست می باشد, مشخص کردن شماره پست بر عهده خود شماست. پیوست‌هایی که پیش از این بارگذاریشده اند می توانند در پست مشخص شده شما گنجانده شوند.',
	'ADD_EXTENSION'						=> 'افزودن پسوند',
	'ADD_EXTENSION_GROUP'				=> 'افزودن گروه پسوند',
	'ADMIN_UPLOAD_ERROR'				=> 'خطا در حین تلاش برای پیوست فایل: “%s”.',
	'ALLOWED_FORUMS'					=> 'انجمن‌های مجاز',
	'ALLOWED_FORUMS_EXPLAIN'			=> 'توانایی ارسال پسوندهای تعیین شده در در انجمن‌های منتخب ( یا همه آن‌ها ).',
	'ALLOWED_IN_PM_POST'				=> 'فعال شده',
	'ALLOW_ATTACHMENTS'					=> 'فعال بودن پیوست‌ها',
	'ALLOW_ALL_FORUMS'					=> 'مجاز در همه انجمن‌ها',
	'ALLOW_IN_PM'						=> 'فعال بودن در پیام‌های خصوصی',
	'ALLOW_PM_ATTACHMENTS'				=> 'فعال بودن پیوست‌ها در پیام‌های خصوصی',
	'ALLOW_SELECTED_FORUMS'				=> 'فقط انجمن‌های انتخاب شده ذیل',
	'ASSIGNED_EXTENSIONS'				=> 'پسوند های تعیین شده',
	'ASSIGNED_GROUP'					=> 'گروه پسوندی تعیین شده',
	'ATTACH_EXTENSIONS_URL'				=> 'پسوند ها',
	'ATTACH_EXT_GROUPS_URL'				=> 'گروه‌های پسوندی',
	'ATTACH_ID'							=> 'ID',
	'ATTACH_MAX_FILESIZE'				=> 'حداکثر حجم فایل',
	'ATTACH_MAX_FILESIZE_EXPLAIN'		=> 'حد اکثر حجم هر فایل, با 0 مقداردهی می شود، به وسیله تنظیمات پیکربندی PHP می‌توانید اندازه فایل‌های بارگذاری را محدودسازی نمایید.',
	'ATTACH_MAX_PM_FILESIZE'			=> 'حداکثر حجم فایل در پیام‌های خصوصی',
	'ATTACH_MAX_PM_FILESIZE_EXPLAIN'	=> 'حداکثر حجم هر فایل ضمیمه شده در پیام‌های خصوصی. مقدار 0 یعنی بدون محدودیت',
	'ATTACH_ORPHAN_URL'					=> 'پیوست‌های غیرفعال',
	'ATTACH_POST_ID'					=> 'ID پست',
	'ATTACH_POST_TYPE'					=> 'نوع پست',
	'ATTACH_QUOTA'						=> 'سهمیه کل پیوست',
	'ATTACH_QUOTA_EXPLAIN'				=> 'حداکثر فضا جهت در اختیار قرار دادن پیوست‌ها در انجمن. عدد 0 به معنای نامحدود بودن است.',
	'ATTACH_TO_POST'					=> 'پیوست فایل در پست',

	'CAT_IMAGES'				=> 'تصاویر',
	'CHECK_CONTENT'				=> 'کنترل فایل‌های پیوست',
	'CHECK_CONTENT_EXPLAIN'		=> 'بعضی از مرورگر ها ممکن هست دچار اشتباه شوند و بارگذاری بعضی از فایل‌های نامجاز را که نوع فرمت آن‌ها (mimetype) تغییر یافته است،قبول کنند. این گزینه باعث اطمینان از این نوع فایل‌ها خواهد شد.',
	'CREATE_GROUP'				=> 'ایجاد گروه جدید',
	'CREATE_THUMBNAIL'			=> 'ایجاد ریز تصویر',
	'CREATE_THUMBNAIL_EXPLAIN'	=> 'ایجاد ریزتصویر در هر حالت ممکن',

	'DEFINE_ALLOWED_IPS'			=> 'شناسایی IP/hostname های مجاز',
	'DEFINE_DISALLOWED_IPS'			=> 'شناسایی IP/hostname غیر مجاز',
	'DOWNLOAD_ADD_IPS_EXPLAIN'		=> 'برای مشخص کردن چندین IP و یا hostname،هر یک از آن‌ها را در سطر های جداگانه وارد کنید. برای مشخص کردن بازه ای از IP ها، IP آغازی و پایانی را با خط تیره (-) مشخص کنید. برای مشخص کردن کلمات از (*) استفاده کنید.',
	'DOWNLOAD_REMOVE_IPS_EXPLAIN'	=> 'با ادغام ماوس و صفحه کلید کامپیوتر می‌توانید و با کمک گرفتن از مرورگر می‌توانید  IP های بیشتری را انتخاب کنید. IP های انتخاب نشده با رنگ آبی نمای داده خواهند شد.',
	'DISPLAY_INLINED'				=> 'نمایش خطی تصاویر',
	'DISPLAY_INLINED_EXPLAIN'		=> 'اگر تصویر پیوست بر روی نه تنظیم شده باشد،پیوست‌ها به صورت لینک نمایش داده خواهند شد.',
	'DISPLAY_ORDER'					=> 'ترتیب نمایش پیوست‌ها',
	'DISPLAY_ORDER_EXPLAIN'			=> 'نمایش پیوست‌ها به ترتیب زمان',

	'EDIT_EXTENSION_GROUP'			=> 'ویرایش پسوند گروه',
	'EXCLUDE_ENTERED_IP'			=> 'این را برای محرومیت IP/HostName وارد شده فعال کنید.',
	'EXCLUDE_FROM_ALLOWED_IP'		=> 'حدود IP برای IPs/hostnames فعال',
	'EXCLUDE_FROM_DISALLOWED_IP'	=> 'مستثنی کردن کردن IP از IP/HostName های غیرفعال',
	'EXTENSIONS_UPDATED'			=> 'پسوند با موفقیت بروز شد.',
	'EXTENSION_EXIST'				=> 'پسوند %s پیش از این فعال بوده است.',
	'EXTENSION_GROUP'				=> 'پسوند گروه',
	'EXTENSION_GROUPS'				=> 'پسوند گروه‌ها',
	'EXTENSION_GROUP_DELETED'		=> 'پسوند گروه با موفقیت حذف شد.',
	'EXTENSION_GROUP_EXIST'			=> 'پسوند گروه %s پیش این این موجود بوده است.',

	'EXT_GROUP_ARCHIVES'			=> 'آرشیو',
	'EXT_GROUP_DOCUMENTS'			=> 'اسناد',
	'EXT_GROUP_DOWNLOADABLE_FILES'	=> 'فایل‌های قابل بارگیری',
	'EXT_GROUP_IMAGES'				=> 'تصاویر',
	'EXT_GROUP_PLAIN_TEXT'			=> 'متن ساده',

	'FILES_GONE'			=> 'بعضی از فایل‌های پیوستی انتخاب شده جهت حذف دیگه وجود ندارند. که یعنی قبلا حذف شده اند. به یاد داشته باشید پیوست‌های ناموجود یعنی حذف شده اند.',
	'FILES_STATS_WRONG'		=> 'آمار فایل‌ها ظاهرا نادرست است و نیاز به همگام سازی مجدد است. مقدار واقعی: تعداد فایل‌های پیوست = %1$d, حجم فایل‌های پیوست = %2$s.<br />کلیک کنید %3$sاینجا%4$s جهت همگام سازی مجدد همه پیوست‌ها.',

	'GO_TO_EXTENSIONS'		=> 'برو به صفحه مدیریت پسوندها',
	'GROUP_NAME'			=> 'نام گروه',

	'IMAGE_LINK_SIZE'			=> 'ابعاد تصویر لینک',
	'IMAGE_LINK_SIZE_EXPLAIN'	=> 'اگر تصویر نمایش داده شده بزرگتر از قالب تالار باشد در آن صورت به صورت لینکی مکایش داده خواهد شد. برای غیرفعال کردن این ویژگی، داده‌های 0 px و 0 px را وارد کنید',
	'IMAGE_QUALITY'				=> 'کیفیت عکس های بارگذاری شده در پیوست ( فقط JPEG )',
	'IMAGE_QUALITY_EXPLAIN'		=> 'تعیین مقدار بین 50%  (اندازه فایل کوچکتر) تا 9% (کیفیت بالاتر). کیفیت بالاتر از  90% باعث افزایش حجم فایل میشود و غیرفعال خواهد بود. تنظیم فقط درصورتی اعمال می شود که حداکثر ابعاد تصویر به مقداری غیر از 0px توسط 0px روی مقداری تنظیم شود.',
	'IMAGE_STRIP_METADATA'		=> 'تعیین متادیتا تصاویر ( فقط JPEG )',
	'IMAGE_STRIP_METADATA_EXPLAIN'	=> 'تعیین متادیتا, مثلا. نام نویسنده, مختصات GPS , و یا جزئیات دوربین. تنظیم فقط درصورتی اعمال می شود که حداکثر ابعاد تصویر به مقداری غیر از 0px توسط 0px روی مقداری تنظیم شود.',

	'MAX_ATTACHMENTS'				=> 'حداکثر تعداد پیوست در هر پست',
	'MAX_ATTACHMENTS_PM'			=> 'حداکثر تعداد پیوست در هر پیام خصوصی',
	'MAX_EXTGROUP_FILESIZE'			=> 'حداکثر حجم فایل',
	'MAX_IMAGE_SIZE'				=> 'حداکثر ابعاد تصویر',
	'MAX_IMAGE_SIZE_EXPLAIN'		=> 'حداکثر ابعاد پیوست تصویر.برای غیرفعال کردن کنترل ابعاد، در هر دو فیلد 0 px و 0 px وارد کنید.',
	'MAX_THUMB_WIDTH'				=> 'حداکثر عرض/ طول ریز تصویر',
	'MAX_THUMB_WIDTH_EXPLAIN'		=> 'تصویر کوچک ساخته شده از این ابعاد تجاوز نخواهد کرد',
	'MIN_THUMB_FILESIZE'			=> 'حداقل حجم تصویر کوچک',
	'MIN_THUMB_FILESIZE_EXPLAIN'	=> 'برای ریز تصویر های کوچکتر از این ، ریز تصویر ساخته نمی شود',
	'MODE_INLINE'					=> 'به ترتیب',
	'MODE_PHYSICAL'					=> 'فیزیکی',

	'NOT_ALLOWED_IN_PM'			=> 'فقط در پست‌ها مورد قبول است',
	'NOT_ALLOWED_IN_PM_POST'	=> 'مجاز نیست',
	'NOT_ASSIGNED'				=> 'شناسایی نشد',
	'NO_ATTACHMENTS'			=> 'هیچ پیوستی در این دوره پیدا نشد.',
	'NO_EXT_GROUP'				=> 'هیچکدام',
	'NO_EXT_GROUP_ALLOWED_PM'	=> '<a href="%s">مجوز پیشوند گروه</a> برای پیام‌های خصوصی وجود ندارد.',
	'NO_EXT_GROUP_ALLOWED_POST'	=> '<a href="%s">مجوز پیشوند گروه‌ها</a> برای پست‌ها وجود ندارد.',
	'NO_EXT_GROUP_NAME'			=> 'نام گروهی وارد نشده است.',
	'NO_EXT_GROUP_SPECIFIED'	=> 'هیچ گروه پسوندی انتخاب نشده است.',
	'NO_FILE_CAT'				=> 'هیچ کدام',
	'NO_IMAGE'					=> 'بدون تصویر',
	'NO_UPLOAD_DIR'				=> 'دایرکتوری upload انتخاب شده موجود نیست',
	'NO_WRITE_UPLOAD'			=> 'دایرکتوری upload قابل نوشتن نیست،لطفا سطوح دسترسی آن را تغییر دهید تا سرور بتواند بر روی آن بنویسد.',

	'ONLY_ALLOWED_IN_PM'	=> 'فقط در پیام‌های خصوصی مورد قبول است.',
	'ORDER_ALLOW_DENY'		=> 'پذیرفتن',
	'ORDER_DENY_ALLOW'		=> 'رد کردن',

	'REMOVE_ALLOWED_IPS'		=> 'IP/hostnam های <em> مجاز </em> را حذف کرده و یا قبول کنید.',
	'REMOVE_DISALLOWED_IPS'		=> 'IP/hostnam های <em>غیر مجاز</em> را حذف کرده و یا قبول کنید.',
	'RESYNC_FILES_STATS_CONFIRM'	=> 'آیا از همگام سازی مجدد آمار فایل‌ها مطمئنید؟',
		
	'SECURE_ALLOW_DENY'				=> 'لیست پذیریش/عدم پذیریش',
	'SECURE_ALLOW_DENY_EXPLAIN'		=> 'دگرگون سازی رفتار پیش‌فرض هنگامی فعال بودن دانلود ایمن در لیست پذیریش/عدم پذیریش و دربرابر آن یک <strong>لیست سفید</strong> ( فعال ) و یک <strong>لیست سیاه</strong> غیرفعال.',
	'SECURE_DOWNLOADS'				=> 'فعال بودن دانلود ایمن',
	'SECURE_DOWNLOADS_EXPLAIN'		=> 'با فعال شدن این گزینه ، دانلودهای منحصر به IP/HostName های تعریف شده شما هستند .',
	'SECURE_DOWNLOAD_NOTICE'		=> 'دانلود ایمن فعال نیست. پیش از فعال سازی دانلود ایمن ، تنظیمات زیر را بکارگیری کنید.',
	'SECURE_DOWNLOAD_UPDATE_SUCCESS'=> 'لیست IP با موفقیت به‌روز رسانی شد.',
	'SECURE_EMPTY_REFERRER'			=> 'فعالیت خالی کردن رجوع کنندگان',
	'SECURE_EMPTY_REFERRER_EXPLAIN'	=> 'دانلود ایمن بر پایه رجوع کنندگان است. آیا شما می خواهید دانلودها را برای از حذف رجوع کنندگان فعال کنید ؟',
	'SETTINGS_CAT_IMAGES'			=> 'تنظیمات شاخه تصویر',
	'SPECIAL_CATEGORY'				=> 'شاخه ویژه',
	'SPECIAL_CATEGORY_EXPLAIN'		=> 'تفاوت داشتن شاخه های ویژه به نحو محسوس در میان پست‌ها.',
	'SUCCESSFULLY_UPLOADED'			=> 'با موفقیت بارگذاری شد.',
	'SUCCESS_EXTENSION_GROUP_ADD'	=> 'گروه پسوند با موفقیت اضافه شده.',
	'SUCCESS_EXTENSION_GROUP_EDIT'	=> 'گروه پسوند با موفقیت بروز شد.',

	'UPLOADING_FILES'				=> 'بارگذاری فایل‌ها',
	'UPLOADING_FILE_TO'				=> 'بارگذاری فایل “%1$s” به پست شماره %2$d…',
	'UPLOAD_DENIED_FORUM'			=> 'سطح دسترسی بارگذاری فایل به “%s” را ندارید.',
	'UPLOAD_DIR'					=> 'دایرکتوری بارگذاری',
	'UPLOAD_DIR_EXPLAIN'			=> 'مسیر ذخیره پیوست‌ها. لطفا توجه داشته باشید که اگر این مسیر را تغییر دهید،باید فایل‌های پیوست کنونی را به صورت دستی به مسیر جدید انتقال دهید.',
	'UPLOAD_ICON'					=> 'آیکون بارگذاری',
	'UPLOAD_NOT_DIR'				=> 'محل مشخص شده برای بارگذاری به نظر نمی رسد که یک دایرکتوری باشد.',
));
