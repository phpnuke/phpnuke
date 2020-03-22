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
	'ACP_ATTACHMENT_SETTINGS_EXPLAIN'	=> 'در اين قسمت شما مي توانيد تنظيمات پيشفرض پيوست ها و همچنين شاخه هاي ويژه را پيکربندي نماييد.',
	'ACP_EXTENSION_GROUPS_EXPLAIN'		=> 'در اين قسمت شما مي توانيد گروه هاي پسوندي را اضافه, حذف, ويرايش و يا غير فعال کنيد. گزینه های بیشتر در شاخه های ویژه آنها قرار دارد.تغییر مکانیزم دانلود و تعیین یک آیکون برای نمایش در جلوی پیوست برای تشخیص هویت از این گزینه ها محسوب می شوند.',
	'ACP_MANAGE_EXTENSIONS_EXPLAIN'		=> 'در اين اينجا شما مي توانيد همه پسوند هاي فعال را اداره کنيد. براي فعال سازي پسوندتان, لطفا به قاب "مديريت گروه پسوندها" رجوع فرماييد. ما شديدا به شما توصيه مي کنيم که پسوند هاي اسکريپتي را فعال نکنيد (مثال از اين قبيل <code>php</code>, <code>php3</code>, <code>php4</code>, <code>phtml</code>, <code>pl</code>, <code>cgi</code>, <code>py</code>, <code>rb</code>, <code>asp</code>, <code>aspx</code>, و ...).',
	'ACP_ORPHAN_ATTACHMENTS_EXPLAIN'	=> 'در اين قسمت شما مي توانيد فايل هاي غير فعال شده را مشاهده کنيد. اين رخ داد غالبا زماني به وقع مي پيوندد که کاربر فايل را پيوست کرده ولي پست را ارسال نمي کند. اينک شما قادريد اين فايل ها را حذف يا ضميمه پست هاي موجود نماييد. پيوست کردن اين پستها  نيازمند  شماره (ID) صحيحي از پست مي باشد, مشخص کردن شماره پست بر عهده خود شماست. پيوست هايي که پيش از اين آپلودشده اند مي توانند در پست مشخص شده شما گنجانده شوند.',
	'ADD_EXTENSION'						=> 'افزودن پسوند',
	'ADD_EXTENSION_GROUP'				=> 'افزودن گروه پسوند',
	'ADMIN_UPLOAD_ERROR'				=> 'خطا در حين تلاش براي پيوست فايل: “%s”.',
	'ALLOWED_FORUMS'					=> 'انجمن هاي مجاز',
	'ALLOWED_FORUMS_EXPLAIN'			=> 'توانایی ارسال پسوندهای تعیین شده در در انجمن های منتخب ( یا همه آنها ).',
	'ALLOWED_IN_PM_POST'				=> 'فعال شده',
	'ALLOW_ATTACHMENTS'					=> 'فعال بودن پيوست ها',
	'ALLOW_ALL_FORUMS'					=> 'مجاز در همه انجمن ها',
	'ALLOW_IN_PM'						=> 'فعال بودن در پیام هاي خصوصي',
	'ALLOW_PM_ATTACHMENTS'				=> 'فعال بودن پيوست ها در پیام هاي خصوصي',
	'ALLOW_SELECTED_FORUMS'				=> 'فقط انجمن هاي انتخاب شده ذيل',
	'ASSIGNED_EXTENSIONS'				=> 'پسوند هاي تعيين شده',
	'ASSIGNED_GROUP'					=> 'گروه پسوندي تعيين شده',
	'ATTACH_EXTENSIONS_URL'				=> 'پسوند ها',
	'ATTACH_EXT_GROUPS_URL'				=> 'گروه هاي پسوندي',
	'ATTACH_ID'							=> 'ID',
	'ATTACH_MAX_FILESIZE'				=> 'حداکثر حجم فايل',
	'ATTACH_MAX_FILESIZE_EXPLAIN'		=> 'حد اکثر حجم هر فايل, با 0 مقداردهی می شود، به وسیله تنظیمات پیکربندی PHP می توانید اندازه فایل های آپلود را محدودسازی نمایید.',
	'ATTACH_MAX_PM_FILESIZE'			=> 'حداکثر حجم فايل در پیام هاي خصوصي',
	'ATTACH_MAX_PM_FILESIZE_EXPLAIN'	=> 'حداكثر حجم هر فايل ضميمه شده در پيام هاي خصوصي. مقدار 0 يعني بدون محدوديت',
	'ATTACH_ORPHAN_URL'					=> 'پيوست هاي غيرفعال',
	'ATTACH_POST_ID'					=> 'ID پست',
	'ATTACH_POST_TYPE'					=> 'نوع پست',
	'ATTACH_QUOTA'						=> 'سهميه کل پيوست',
	'ATTACH_QUOTA_EXPLAIN'				=> 'حداکثر فضا جهت در اختیار قرار دادن پیوست ها در انجمن. عدد 0 به معنای نامحدود بودن است.',
	'ATTACH_TO_POST'					=> 'پيوست فايل در پست',

	'CAT_FLASH_FILES'			=> 'فایل های فلش',
	'CAT_IMAGES'				=> 'تصاویر',
	'CHECK_CONTENT'				=> 'کنترل فایل های پیوست',
	'CHECK_CONTENT_EXPLAIN'		=> 'بعضی از مرورگر ها ممکن هست دچار اشتباه شوند و آپلود بعضی از فایل های نامجاز را که نوع فرمت آنها (mimetype) تغییر یافته است،قبول کنند. این گزینه باعث اطمینان از این نوع فایل ها خواهد شد.',
	'CREATE_GROUP'				=> 'ایجاد گروه جدید',
	'CREATE_THUMBNAIL'			=> 'ایجاد ریز تصویر',
	'CREATE_THUMBNAIL_EXPLAIN'	=> 'ایجاد ریزتصویر در هر حالت ممکن',

	'DEFINE_ALLOWED_IPS'			=> 'شناسایی IP/hostname های مجاز',
	'DEFINE_DISALLOWED_IPS'			=> 'شناسایی IP/hostname غیر مجاز',
	'DOWNLOAD_ADD_IPS_EXPLAIN'		=> 'برای مشخص کردن چندین IP و یا hostname،هر یک از آن ها را در سطر های جداگانه وارد کنید. برای مشخص کردن بازه ای از IP ها، IP آغازی و پایانی را با خط تیره (-) مشخص کنید. برای مشخص کردن کلمات از (*) استفاده کنید.',
	'DOWNLOAD_REMOVE_IPS_EXPLAIN'	=> 'با ادغام ماوس و صفحه کلید کامپیوتر می توانید و با کمک گرفتن از مرورگر میتوانید  IP های بیشتری را انتخاب کنید. IP های انتخاب نشده با رنگ آبی نمای داده خواهند شد.',
	'DISPLAY_INLINED'				=> 'نمایش خطی تصاویر',
	'DISPLAY_INLINED_EXPLAIN'		=> 'اگر تصویر پیوست بر روی نه تنظیم شده باشد،پیوست ها به صورت لینک نمایش داده خواهند شد.',
	'DISPLAY_ORDER'					=> 'ترتیب نمایش پیوست ها',
	'DISPLAY_ORDER_EXPLAIN'			=> 'نمایش پیوست ها به ترتیب زمان',

	'EDIT_EXTENSION_GROUP'			=> 'ويرايش پسوند گروه',
	'EXCLUDE_ENTERED_IP'			=> 'اين را براي محروميت IP/HostName وارد شده فعال کنيد.',
	'EXCLUDE_FROM_ALLOWED_IP'		=> 'حدود IP براي IPs/hostnames فعال',
	'EXCLUDE_FROM_DISALLOWED_IP'	=> 'مستثنی کردن کردن IP از IP/HostName هاي غير فعال',
	'EXTENSIONS_UPDATED'			=> 'پسوند با موفقيت بروز شد.',
	'EXTENSION_EXIST'				=> 'پسوند %s پيش از اين فعال بوده است.',
	'EXTENSION_GROUP'				=> 'پسوند گروه',
	'EXTENSION_GROUPS'				=> 'پسوند گروه ها',
	'EXTENSION_GROUP_DELETED'		=> 'پسوند گروه با موفقيت حذف شد.',
	'EXTENSION_GROUP_EXIST'			=> 'پسوند گروه %s پيش اين اين موجود بوده است.',

	'EXT_GROUP_ARCHIVES'			=> 'آرشیو',
	'EXT_GROUP_DOCUMENTS'			=> 'اسناد',
	'EXT_GROUP_DOWNLOADABLE_FILES'	=> 'فایل های قابل بارگیری',
	'EXT_GROUP_FLASH_FILES'			=> 'فایل های فلش',
	'EXT_GROUP_IMAGES'				=> 'تصاویر',
	'EXT_GROUP_PLAIN_TEXT'			=> 'متن ساده',

	'FILES_GONE'			=> 'بعضی از فایل های پیوستی انتخاب شده جهت حذف دیگه وجود ندارند. که یعنی قبلا حذف شده اند. به یاد داشته باشید پیوست های ناموجود یعنی حذف شده اند.',
	'FILES_STATS_WRONG'		=> 'آمار فایل ها ظاهرا نادرست است و نیاز به همگام سازی مجدد است. مقدار واقعی: تعداد فایل های پیوست = %1$d, حجم فایل های پیوست = %2$s.<br />کلیک کنید %3$sاینجا%4$s جهت همگام سازی همه پیوست ها.',

	'GO_TO_EXTENSIONS'		=> 'برو به صفحه مديريت پسوندها',
	'GROUP_NAME'			=> 'نام گروه',

	'IMAGE_LINK_SIZE'			=> 'ابعاد تصویر لینک',
	'IMAGE_LINK_SIZE_EXPLAIN'	=> 'اگر تصویر نمایش داده شده بزرگتر از قالب تالار باشد در آن صورت به صورت لینکی مکایش داده خواهد شد. برای غیر فعال کردن این ویژگی، داده های 0 px و 0 px را وارد کنید',
	'IMAGICK_PATH'				=> 'Imagemagick مسیر ',
	'IMAGICK_PATH_EXPLAIN'		=> 'مسیر کامل به نرم افزار تبدیل Imagemagic. برای مثال <samp>/usr/bin/</samp>.',

	'MAX_ATTACHMENTS'				=> 'حداکثر تعداد پیوست در هر پست',
	'MAX_ATTACHMENTS_PM'			=> 'حداکثر تعداد پیوست در هر پیغام خصوصی',
	'MAX_EXTGROUP_FILESIZE'			=> 'حداکثر حجم فایل',
	'MAX_IMAGE_SIZE'				=> 'حداکثر ابعاد تصویر',
	'MAX_IMAGE_SIZE_EXPLAIN'		=> 'حداکثر ابعاد پیوست تصویر.برای غیر فعال کردن کنترل ابعاد، در هر دو فیلد 0 px و 0 px وارد کنید.',
	'MAX_THUMB_WIDTH'				=> 'حداکثر عرض/ طول ریز تصویر',
	'MAX_THUMB_WIDTH_EXPLAIN'		=> 'تصویر کوچک ساخته شده از این ابعاد تجاوز نخواهد کرد',
	'MIN_THUMB_FILESIZE'			=> 'حداقل حجم تصویر کوچک',
	'MIN_THUMB_FILESIZE_EXPLAIN'	=> 'برای ریز تصویر های کوچکتر از این ، ریز تصویر ساخته نمی شود',
	'MODE_INLINE'					=> 'به ترتیب',
	'MODE_PHYSICAL'					=> 'فیزیکی',

	'NOT_ALLOWED_IN_PM'			=> 'فقط در پست ها مورد قبول است',
	'NOT_ALLOWED_IN_PM_POST'	=> 'مجاز نیست',
	'NOT_ASSIGNED'				=> 'شناسایی نشد',
	'NO_ATTACHMENTS'			=> 'هیچ پیوستی در این دوره پیدا نشد.',
	'NO_EXT_GROUP'				=> 'هیچکدام',
	'NO_EXT_GROUP_NAME'			=> 'نام گروهی وارد نشده است.',
	'NO_EXT_GROUP_SPECIFIED'	=> 'هیچ گروه پسوندی انتخاب نشده است.',
	'NO_FILE_CAT'				=> 'هیچ کدام',
	'NO_IMAGE'					=> 'بدون تصویر',
	'NO_THUMBNAIL_SUPPORT'		=> 'پشتيباني از Thumbnail غيرفعال است. براي داشتن تابع مناسب بايد يکي از دو برنامه GD extension و يا imagemagic نصب شده باشند. هردو يافت نشدند.',
	'NO_UPLOAD_DIR'				=> 'دایرکتوری upload انتخاب شده موجود نیست',
	'NO_WRITE_UPLOAD'			=> 'دایرکتوری upload قابل نوشتن نیست،لطفا سطوح دسترسی آن را تغییر دهید تا سرور بتواند بر روی آن بنویسد.',

	'ONLY_ALLOWED_IN_PM'	=> 'فقط در پیام های خصوصی مجاز است.',
	'ORDER_ALLOW_DENY'		=> 'اجازه',
	'ORDER_DENY_ALLOW'		=> 'عدم اجازه',

	'ONLY_ALLOWED_IN_PM'	=> 'فقط در پیغام های خصوصی مورد قبول است.',
	'ORDER_ALLOW_DENY'		=> 'پذیرفتن',
	'ORDER_DENY_ALLOW'		=> 'رد کردن',

	'REMOVE_ALLOWED_IPS'		=> 'IP/hostnam های <em> مجاز </em> را حذف کرده و یا قبول کنید.',
	'REMOVE_DISALLOWED_IPS'		=> 'IP/hostnam های <em>غیر مجاز</em> را حذف کرده و یا قبول کنید.',
	'RESYNC_FILES_STATS_CONFIRM'	=> 'آیا از هم زمان سازی آمار فایل های پیوست اطمینان دارید؟',
		
	'SEARCH_IMAGICK'				=> 'جستجو براي Imagemagick',
	'SECURE_ALLOW_DENY'				=> 'ليست پذيريش/عدم پذيريش',
	'SECURE_ALLOW_DENY_EXPLAIN'		=> 'دگرگون سازی رفتار پیشفرض هنگامی فعال بودن دانلود ایمن در لیست پذیریش/عدم پذیریش و دربرابر آن یک <strong>لیست سفید</strong> ( فعال ) و یک <strong>لیست سیاه</strong> غیر فعال.',
	'SECURE_DOWNLOADS'				=> 'فعال بودن دانلود ايمن',
	'SECURE_DOWNLOADS_EXPLAIN'		=> 'با فعال شدن این گزینه ، دانلودهای منحصر به IP/HostName های تعریف شده شما هستند .',
	'SECURE_DOWNLOAD_NOTICE'		=> 'دانلود ايمن فعال نيست. پيش از فعال سازي دانلود ايمن ، تنظيمات زير را بکارگيري کنيد.',
	'SECURE_DOWNLOAD_UPDATE_SUCCESS'=> 'ليست IP با موفقيت بروز رساني شد.',
	'SECURE_EMPTY_REFERRER'			=> 'فعاليت خالي کردن رجوع کنندگان',
	'SECURE_EMPTY_REFERRER_EXPLAIN'	=> 'دانلود ايمن بر پايه رجوع کنندگان است. آيا شما مي خواهيد دانلودها را براي از حذف رجوع کنندگان فعال کنيد ؟',
	'SETTINGS_CAT_IMAGES'			=> 'تنظيمات شاخه تصوير',
	'SPECIAL_CATEGORY'				=> 'شاخه ويژه',
	'SPECIAL_CATEGORY_EXPLAIN'		=> 'تفاوت داشتن شاخه های ویژه به نحو محسوس در میان پست ها.',
	'SUCCESSFULLY_UPLOADED'			=> 'با موفقيت آپلود شد.',
	'SUCCESS_EXTENSION_GROUP_ADD'	=> 'گروه پسوند با موفقيت اضافه شده.',
	'SUCCESS_EXTENSION_GROUP_EDIT'	=> 'گروه پسوند با موفقيت بروز شد.',

	'UPLOADING_FILES'				=> 'آپلود فایل ها',
	'UPLOADING_FILE_TO'				=> 'آپلود فایل “%1$s” به پست شماره %2$d…',
	'UPLOAD_DENIED_FORUM'			=> 'سطح دسترسی آپلود فایل به “%s” را ندارید.',
	'UPLOAD_DIR'					=> 'دایرکتوری آپلود',
	'UPLOAD_DIR_EXPLAIN'			=> 'مسیر ذخیره پیوست ها. لطفا توجه داشته باشید که اگر این مسیر را تغییر دهید،باید فایل های پیوست کنونی را به صورت دستی به مسیر جدید انتقال دهید.',
	'UPLOAD_ICON'					=> 'آیکون آپلود',
	'UPLOAD_NOT_DIR'				=> 'محل مشخص شده برای آپلود به نظر نمی رسد که یک دایرکتوری باشد.',
));
