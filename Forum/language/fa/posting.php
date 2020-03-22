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
	'ADD_ATTACHMENT'			=> 'بارگزاری پیوست',
	'ADD_ATTACHMENT_EXPLAIN'	=> 'اگر مایل به پیوست یک یا چند فایل هستید، لطفا جزئیات را وارد کنید.',
	'ADD_FILE'					=> 'اضافه کردن فایل',
	'ADD_POLL'					=> 'ایجاد نظرسنجی',
	'ADD_POLL_EXPLAIN'			=> 'اگر نمیخواهد نظرسنجی ای را برای موضوع خود ایجاد منید،لطفا فیلد زیر را خالی بگزارید.',
	'ALREADY_DELETED'			=> 'با عرض پوزش این پیغام قبلا حذف شده است.',
	'ATTACH_DISK_FULL'			=> 'فضای کافی جهت پیوست فایل وجود ندارد.',
	'ATTACH_QUOTA_REACHED'		=> 'با عرض پوزش،سهمیه پیوست فایل در تالار برای شما اتمام یافته است.',
	'ATTACH_SIG'				=> 'پیوست امضا (امضایتان را میتوانید از کنترل پنل کاربر تغییر دهید)',

	'BBCODE_A_HELP'				=> 'پیوست های آپلود شده : [attachment=]filename.ext[/attachment]',
	'BBCODE_B_HELP'				=> 'نوشته ضخیم (bold) [b]متن[/b]',
	'BBCODE_C_HELP'				=> 'نمایش کد : [code]code[/code]',
	'BBCODE_D_HELP'				=> 'فلش: [flash=width,height]http://url[/flash]',
	'BBCODE_F_HELP'				=> 'اندازه فونت : [size=85]متن کوچک[/size]',
	'BBCODE_IS_OFF'				=> '%sBBCode%s  <em>بسته</em> است.',
	'BBCODE_IS_ON'				=> '%sBBCode%s <em>باز</em> است',
	'BBCODE_I_HELP'				=> 'نوشته کج(italic) [i]نوشته[/i]',
	'BBCODE_L_HELP'				=> 'لیست : [list]نوشته[/list]',
	'BBCODE_LISTITEM_HELP'		=> 'ماده های لیست : [*]نوشته[/*]',
	'BBCODE_O_HELP'				=> 'لیست ترتیبی (عددی) [list=]نوشته[/list]',
	'BBCODE_P_HELP'				=> 'اضافه کردن تصویر : [img]http://image_url[/img]',
	'BBCODE_Q_HELP'				=> 'نقل قول نوشته : [quote]نوشته[/quote]',
	'BBCODE_S_HELP'				=> 'رنگ فونت : [color=red]نوشته[/color]  یا [color=#FF0000]text[/color]',
	'BBCODE_U_HELP'				=> 'خط زیرین (underline) نوشته : [u]نوشته[/u]',
	'BBCODE_W_HELP'				=> 'اضافه کردن URL : [url]http://url[/url] یا [url=http://url]متن URL[/url]',
	'BBCODE_Y_HELP'				=> 'لیست: اضافه کردن عنصر جدید',
	'BUMP_ERROR'				=> 'بعد از آخرین پستتان در این موضوع در چنین زمان کوتاهی نمیتوانید مضوع را بامپ (bump) کنید.',

	'CANNOT_DELETE_REPLIED'		=> 'با عرض پوزرش فقط پست هایی را میتوانید حذف کنید که به آنها پاسخ داده نشده باشد.',
	'CANNOT_EDIT_POST_LOCKED'	=> 'این پست قفل شده است و دیگر نمیتوانید آن را ویرایش کنید.',
	'CANNOT_EDIT_TIME'			=> 'دیگر نمیتوانید پست را ویرایش و یا حذف کنید.',
	'CANNOT_POST_ANNOUNCE'		=> 'با عرض پوزش نمیتوانید اطلاعیه ای را ارسال کنید.',
	'CANNOT_POST_STICKY'		=> 'با عرض پوزش نمیتوانید موضوع مهمی را ارسال کنید.',
	'CHANGE_TOPIC_TO'			=> 'تغییر نوع موضوع به',
	'CHARS_POST_CONTAINS'		=> array(
		1	=> 'پیام شما شامل %1$d کاراکتر است.',
		2	=> 'پیام شما شامل %1$d کاراکتر است.',
	),
	'CHARS_SIG_CONTAINS'		=> array(
		1	=> 'امضا شما شامل %1$d کاراکتر است.',
		2	=> 'امضا شما شامل %1$d کاراکتر است.',
	),
	'CLOSE_TAGS'				=> 'بستن تگ',
	'CURRENT_TOPIC'				=> 'موضوع کنونی',

	'DELETE_FILE'				=> 'حذف فایل',
	'DELETE_MESSAGE'			=> 'حذف پیام',
	'DELETE_MESSAGE_CONFIRM'	=> 'آیا از حذف کردن این پیغام مطمئن هستید ؟',
	'DELETE_OWN_POSTS'			=> 'با عرض پوزش،فقط میتوانید پست های خودتان را ویرایش کنید.',
	'DELETE_PERMANENTLY'		=> 'حذف دائمی',
	'DELETE_POST_CONFIRM'		=> 'آیا از حذف این پست اطمینان دارید؟',
	'DELETE_POST_PERMANENTLY_CONFIRM'	=> 'آیا از حذف <strong>دائمی</strong> این پست اطمینان دارید؟',
	'DELETE_POST_PERMANENTLY'	=> array(
		1	=> 'حذف دائمی به معنای غیر قابل بازگشت بودن پست میباشد.',
		2	=> 'حذف دائمی %1$d پست به معنای غیر قابل برگشت پذیر بودن آن است.',
	),
	'DELETE_POSTS_CONFIRM'		=> 'آیا از حذف این پست ها اطمینان دارید؟',
	'DELETE_POSTS_PERMANENTLY_CONFIRM'	=> 'آیا از حذف <strong>دائمی</strong> این پست ها اطمینان دارید؟',
	'DELETE_REASON'				=> 'دلیل حذف',
	'DELETE_REASON_EXPLAIN'		=> 'دلیل حذف مشخص شده تنها برای مدیران قابل رویت است.',
	'DELETE_POST_WARN'			=> 'حذف پست',
	'DELETE_TOPIC_CONFIRM'		=> 'آیا از حذف این موضوع اطمینان دارید؟',
	'DELETE_TOPIC_PERMANENTLY'	=> array(
		1	=> 'حذف دائمی موضوع به معنای غیر قابل بازگشت بودن آن است.',
		2	=> 'حذف دائمی %1$d موضوع به معنای غیر قابل بازگشت بودن آن است.',
	),
	'DELETE_TOPIC_PERMANENTLY_CONFIRM'	=> 'آیا از حذف <strong>دائمی</strong> این موضوع اطمینان دارید؟',
	'DELETE_TOPICS_CONFIRM'		=> 'آیا از حذف این موضوعات اطمینان دارید؟',
	'DELETE_TOPICS_PERMANENTLY_CONFIRM'	=> 'آیا از حذف <strong>دائمی</strong> موضوعات اطمینان دارید؟',
	'DISABLE_BBCODE'			=> 'غیرفعال کردن BBCode',
	'DISABLE_MAGIC_URL'			=> 'به طور خودکار لینک ها را تجزیه نکن',
	'DISABLE_SMILIES'			=> 'غیر فعال کردن شکلک ها',
	'DISALLOWED_CONTENT'		=> 'آپلود تایید نشد،زیرا نوع فایل آپلود شده ممکن است به عنوان حمله ای به سایت باشد.',
	'DISALLOWED_EXTENSION'		=> 'افزونه %s مجاز نمیباشد.',
	'DRAFT_LOADED'				=> 'پیش نویس در منطقه ارسال پست لود شد. ممکن است بخواهید این نوشته را تکمیل کنید.<br />پیش نویس بعد از ارسال پست حذف خواهد شد.',
	'DRAFT_LOADED_PM'			=> 'پیش نویس در منطقه پیغام لود شد. ممکن است بخواهید نوشته این پیغام خصوصی را تکمیل کنید.<br />پیش نویس پس از ارسال پیغام خصوصی حذف خواهد شد.',
	'DRAFT_SAVED'				=> 'پیش نویس با موفقیت ذخیره شد.',
	'DRAFT_TITLE'				=> 'عنوان پیشنویس',

	'EDIT_REASON'				=> 'دلیل ویرایش این پست',
	'EMPTY_FILEUPLOAD'			=> 'فایل آپلود شده خالی است.',
	'EMPTY_MESSAGE'				=> 'در حین ارسال پستی باید متنی را وارد کنید.',
	'EMPTY_REMOTE_DATA'			=> 'فایل آپلود نشد،لطفا آپلود را به صورت دستی امتحان کنید.',

	'FLASH_IS_OFF'				=> '[flash] <em>بسته</em> است',
	'FLASH_IS_ON'				=> '[flash] <em>باز</em> است',
	'FLOOD_ERROR'				=> 'نمیتوانید بعد از ارسال پستی بلافاصله پست دیگری را ارسال کنید.',
	'FONT_COLOR'				=> 'رنگ فونت.',
	'FONT_COLOR_HIDE'			=> 'مخفی کردن رنگ فونت',
	'FONT_HUGE'					=> 'بسیار بزرگ',
	'FONT_LARGE'				=> 'بزرگ',
	'FONT_NORMAL'				=> 'معمولی',
	'FONT_SIZE'					=> 'اندازه فونت',
	'FONT_SMALL'				=> 'کوچک',
	'FONT_TINY'					=> 'بسیار کوچک',

	'GENERAL_UPLOAD_ERROR'		=> 'آپلود پیوست به %s صورت نگرفت.',

	'IMAGES_ARE_OFF'			=> '[img] <em>بسته</em> است',
	'IMAGES_ARE_ON'				=> '[img] <em>باز</em> است',
	'INVALID_FILENAME'			=> 'نام فایل %s نا معتبر است.',

	'LOAD'						=> 'بارگذاري',
	'LOAD_DRAFT'				=> 'بارگذاري پيش نويس',
	'LOAD_DRAFT_EXPLAIN'		=> 'در این بخش میتوانید پیش نویس هایی را که مایل به تکمیل کردن آنها هستید،انتخاب کنبد. پست کنونی شما لغو و حذف خواهد شد.برای مشاهده،ویرایش و یا حذف پیش نویس ها به کنترل پنل کاربر مراجعه کنید.',
	'LOGIN_EXPLAIN_BUMP'		=> 'برای بامپ(bump) کردن انجمن،باید ابتدا وارد شوید.',
	'LOGIN_EXPLAIN_DELETE'		=> 'برای پاک کردن پست های این انجمن باید ابتدا وارد شوید.',
	'LOGIN_EXPLAIN_POST'		=> 'برای ارسال پستی در این انجمن باید ابتدا وارد شوید.',
	'LOGIN_EXPLAIN_QUOTE'		=> 'برای نقل قول پست ها در این انجمن باید ابتدا وارد شوید.',
	'LOGIN_EXPLAIN_REPLY'		=> 'برای پاسخ به موضوعی باید ابتدا وارد شوید.',

	'MAX_FONT_SIZE_EXCEEDED'	=> 'فونت ها را تا اندازه %1$d میتوانید استفاده کنید.',
	'MAX_FLASH_HEIGHT_EXCEEDED'	=> array(
		1	=> 'طول فایل فلش باید تا %1$d پیکسل باشد.',
		2	=> 'طول فایل فلش باید تا %1$d پیکسل باشد.',
	),
	'MAX_FLASH_WIDTH_EXCEEDED'	=> array(
		1	=> 'عرض فایل فلش باید تا %1$d پیکسل باشد.',
		2	=> 'عرض فایل فلش باید تا %1$d پیکسل باشد.',
	),
	'MAX_IMG_HEIGHT_EXCEEDED'	=> array(
		1	=> 'طول تصویر باید تا %1$d پیکسل باشد.',
		2	=> 'طول تصویر باید تا %1$d پیکسل باشد.',
	),
	'MAX_IMG_WIDTH_EXCEEDED'	=> array(
		1	=> 'عرض تصویر باید تا %1$d پیکسل باشد.',
		2	=> 'عرض تصویر باید تا %1$d پیکسل باشد.',
	),

	'MESSAGE_BODY_EXPLAIN'		=> array(
		0	=> '', // zero means no limit, so we don't view a message here.
		1	=> 'پیغام خودتان را در اینجا وارد کنید،متن پیغام نباید بیشتر از <strong>%d</strong> کاراکتر باشد.',
		2	=> 'پیغام خودتان را در اینجا وارد کنید،متن پیغام نباید بیشتر از <strong>%d</strong> کاراکتر باشد.',
	),
	'MESSAGE_DELETED'			=> 'این پیغام با موفقیت حذف شد.',
	'MORE_SMILIES'				=> 'شکلک های بیشتر',

	'NOTIFY_REPLY'				=> 'وقتی که پاسخی ارسال شد،مرا با خبر کن',
	'NOT_UPLOADED'				=> 'فایل آپلود نشد.',
	'NO_DELETE_POLL_OPTIONS'	=> 'نمیتوانید گزینه های نظرسنجی موجود را حذف کنید.',
	'NO_PM_ICON'				=> 'بدون آیکون',
	'NO_POLL_TITLE'				=> 'باید عنوانی را برای نظرسنجی وارد کنید.',
	'NO_POST'					=> 'پست درخواست شده موجود نیست.',
	'NO_POST_MODE'				=> 'حالت پست مشخص نشده است.',
	'NO_TEMP_DIR'				=> 'پوشه موقت موجود نیست یا قابل خواندن نمی باشد.',

	'PARTIAL_UPLOAD'			=> 'فقط قسمتی از فایل انتخاب شده آپلود شد.',
	'PHP_UPLOAD_STOPPED'		=> 'افزونه PHP جهت آپلود متوقف شده است.',
	'PHP_SIZE_NA'				=> 'حجم فایل پیوست شده بسیار زیاد است.<br />حداکثر حجم فایل در php.ini تایین نشده است.',
	'PHP_SIZE_OVERRUN'			=> 'حجم فایل پیوست شده بسیار زیاد است. حداکثر حجمs %1$d %2$s میباشد.<br />توجه داشته باشید که این مقدار در php.ini تعریف شده است و قابل بازنویسی نیست.',
	'PLACE_INLINE'				=> 'قرار دادن داخل متن',
	'POLL_DELETE'				=> 'حذف نظرسنجی',
	'POLL_FOR'					=> 'اجرای نظرسنجی برای',
	'POLL_FOR_EXPLAIN'			=> 'برای نامحدود کردن زمان پایان نظرسنجی،0 را وارد کنید.',
	'POLL_MAX_OPTIONS'			=> 'گزینه ها برای هر کاربر',
	'POLL_MAX_OPTIONS_EXPLAIN'	=> 'این تعداد گزینه هایی میباشد که کاربر میتواند در یک نظرسنجی انتخاب کند.',
	'POLL_OPTIONS'				=> 'گزینه های نظرسنجی',
	'POLL_OPTIONS_EXPLAIN'		=> array(
		1	=> 'هرکدام را در سطری جدید نعریف کنید. میتوانید <strong>%d</strong> گزینه انتخاب کنید.',
		2	=> 'هرکدام را در سطری جدید نعریف کنید. میتوانید <strong>%d</strong> گزینه انتخاب کنید.',
	),
	'POLL_OPTIONS_EDIT_EXPLAIN'		=> array(
		1	=> 'هرکدام را در سطری جدید تعریف کنید. میتوانید <strong>%d</strong> گزینه انتخاب کنید. اگر گزینه ای را حذف و یا ویرایش کنید،تمام رای های فعلی حذف خواهد شد.',
		2	=> 'هرکدام را در سطری جدید تعریف کنید. میتوانید <strong>%d</strong> گزینه انتخاب کنید. اگر گزینه ای را حذف و یا ویرایش کنید،تمام رای های فعلی حذف خواهد شد.',
	),
	'POLL_QUESTION'				=> 'سوا نظرسنجی',
	'POLL_TITLE_TOO_LONG'		=> 'عنوان نظرسنجی باید کمتر از 100 کاراکتر باشد.',
	'POLL_TITLE_COMP_TOO_LONG'	=> 'حجم عنوان نظرسنجی شما بسیار زیاد است، حذف BBCode و یا شکلک ها را در نظر بگیرید.',
	'POLL_VOTE_CHANGE'			=> 'پذیرفتن تغییر رای',
	'POLL_VOTE_CHANGE_EXPLAIN'	=> 'اگر این گزینه را فعال کنید،کاربران میتوانند رای خود را تغییر دهند.',
	'POSTED_ATTACHMENTS'		=> 'پیوست های ارسال شده',
	'POST_APPROVAL_NOTIFY'		=> 'هنگامی که پست شما تایید شود،به شما خبر داده خواهد شد.',
	'POST_CONFIRMATION'			=> 'تایید پست',
	'POST_CONFIRM_EXPLAIN'		=> 'برای جلوگیری از هرزنامه ها،لطفا کد تاییدی را که در تصویر زیر نمایش داده میشود، وارد کنید.  اگر مشکل بینایی دارید و یا نمیتوانید کد را وارد کنید، لطفا با %sمدیر تالار%s تماس بگیرید.',
	'POST_DELETED'				=> 'این پیغام با موفقیت حذف شد.',
	'POST_EDITED'				=> 'این پیغام با موفقیت ویرایش شد.',
	'POST_EDITED_MOD'			=> 'این پیغام با موفقیت ویرایش شد، ولی برای نمایش عموم ابتدا باید توسط مدیر تایید شود.',
	'POST_GLOBAL'				=> 'سراسری',
	'POST_ICON'					=> 'آیکون پست',
	'POST_NORMAL'				=> 'معمولی',
	'POST_REVIEW'				=> 'بررسی پست',
	'POST_REVIEW_EDIT'			=> 'بررسی پست',
	'POST_REVIEW_EDIT_EXPLAIN'	=> 'این پست توسط کاربری دیگر در هنگامی که این پست را ویرایش میکردید،ویرایش شد. ممکن هست بخواهید نسخه جدید پست را بررسی کنید و سپس تغییراتتان را اعمال کنید.',
	'POST_REVIEW_EXPLAIN'		=> 'حداقل یک پست به این موضوع ارسال شده است،ممکن است بخواهید ابتدا آن را بررسی کنید.',
	'POST_STORED'				=> 'این پیغام با موفقیت ارسال شد.',
	'POST_STORED_MOD'			=> 'این پیغام با موفقیت ثبت شد، ولی برای نمایش عموم باید ابتدا توسط مدیر تایید شود.',
	'POST_TOPIC_AS'				=> 'ارسال موضوع به عنوان',
	'PROGRESS_BAR'				=> 'نوار پیشرفت',

	'QUOTE_DEPTH_EXCEEDED'		=> array(
		1	=> 'شما می توانید حداکثر %1$d نقل قول تو در تو استفاده کنید',
		2	=> 'شما می توانید حداکثر %1$d نقل قول تو در تو استفاده کنید',
	),
	'QUOTE_NO_NESTING'			=> 'شما نمیتوانید از نقل قول تو در تو استفاده کنید.',

	'REMOTE_UPLOAD_TIMEOUT'		=> 'درخواست فایل جهت بارگزاری امکان پذیر نیست زیرا درخواست از زمان مشخصی گذشته است.',
	'SAVE'						=> 'ذخیره',
	'SAVE_DATE'					=> 'ذخیره در',
	'SAVE_DRAFT'				=> 'ذخیره پیش نویس',
	'SAVE_DRAFT_CONFIRM'		=> 'توجه داشته باشید که پیش نویس ذخیره شده فقط شامل متن و عنوان پست میباشد. سایر عناصر حذف خواهند شد. آیا از ذخیره پیش نویس مطمئن هستید ؟',
	'SMILIES'					=> 'شکلک ها',
	'SMILIES_ARE_OFF'			=> 'شکلک ها <em>بسته</em> هستند',
	'SMILIES_ARE_ON'			=> 'شکلک ها <em>باز</em> هستند',
	'STICKY_ANNOUNCE_TIME_LIMIT'=> 'محدودیت زمانی موضوعات مهم/سراسری /اطلاعیه ها',
	'STICK_TOPIC_FOR'			=> 'مهم کردن موضوع برای',
	'STICK_TOPIC_FOR_EXPLAIN'	=> 'برای نامحدود کردن زمان پایان موضوعات مهم/سراسری/اطلاعیه ها،0 را وارد کنید. توجه داشته باشید که این عدد به تاریخ پست بستگی دارد.',
	'STYLES_TIP'				=> 'نکته : استایل ها سریعتر از متن قابل نمایش هستند.',

	'TOO_FEW_CHARS'				=> 'متن شما حاوی کاراکترهای کوتاهی است.',
	'TOO_FEW_CHARS_LIMIT'		=> array(
		1	=> 'شما حداقل باید %1$d کاراکتر وارد کنید.',
		2	=> 'شما حداقل باید %1$d کاراکتر وارد کنید.',
	),
	'TOO_FEW_POLL_OPTIONS'		=> 'حداقل باید 2 گزینه را برای نظرسنجی تعریف کنید.',
	'TOO_MANY_ATTACHMENTS'		=> 'نمیتوانید پیوست دیگری را اضافه کنید. حداکثر تعداد پیوست %d میباشد.',
	'TOO_MANY_CHARS'			=> 'کاراکتر های متن شما بسیار زیاد است.',
	'TOO_MANY_CHARS_LIMIT'		=> array(
		2	=> 'حداکثر تعداد کاراکترهای متن باید %1$d باشد.',
	),
	'TOO_MANY_POLL_OPTIONS'		=> 'گزینه های بسیار زیادی را برای نظرسنجی وارد کردید.',
	'TOO_MANY_SMILIES'			=> 'پیغام شما حاوی تعداد زیادی شکلک است. حداکثر تعداد شکلک مورد قبول، %d میباشد.',
	'TOO_MANY_URLS'				=> 'پیغام شما حاوی تعداد زیادی لینک است. حداثر تعداد لینک مورد قبول، %d میباشد.',
	'TOO_MANY_USER_OPTIONS'		=> 'تعداد گزینه برای هر کاربر نمیتواند بیشتر از تعداد کل گزینه ها باشد.',
	'TOPIC_BUMPED'				=> 'این موضوع با موفقیت بامپ(bump) شد',

	'UNAUTHORISED_BBCODE'		=> 'از BBCode های مقابل نمیتوانید استفاده کنید : %s.',
	'UNGLOBALISE_EXPLAIN'		=> 'برای بازگردانی این موضوع از حالت سراسری به حالت معمولی،باید انجمنی را انتخاب کنید که میخواهید موضوع در آن انجمن ذخیره شود.',
	'UNSUPPORTED_CHARACTERS_MESSAGE'	=> 'متن شما حاوی کاراکترهای غیر مجاز زیر است:<br />%s',
	'UNSUPPORTED_CHARACTERS_SUBJECT'	=> 'متن شما حاوی کاراکترهای غیر مجاز زیر است:<br />%s',
	'UPDATE_COMMENT'			=> 'بروزرسانی نظر',
	'URL_INVALID'				=> 'لینک مشخص شده معتبر نیست.',
	'URL_NOT_FOUND'				=> 'فایل مشخص شده یافت نشد.',
	'URL_IS_OFF'				=> '[url] <em>بسته</em> است',
	'URL_IS_ON'					=> '[url] <em>باز</em> است',
	'USER_CANNOT_BUMP'			=> 'موضوعات را در این انجمن نمیتوانید بامپ(bump) کنید.',
	'USER_CANNOT_DELETE'		=> 'نمیتوانید پست ها را در این انجمن حذف کنید.',
	'USER_CANNOT_EDIT'			=> 'نمیتوانید پست ها را در این انجمن ویرایش کنید.',
	'USER_CANNOT_REPLY'			=> 'در این انجمن نمیتوانید پاسخی را ارسال کنید.',
	'USER_CANNOT_FORUM_POST'	=> 'در این انجمن نمیتوانید عملیاتی را ارسال کنید. زیرا نوع انجمن از آن پشتیبانی نمیکند.',

	'VIEW_MESSAGE'				=> '%sمشاهده پیغام ثبت شده شما%s',
	'VIEW_PRIVATE_MESSAGE'		=> '%sمشاهده پیغام خصوصی ثبت شده شما%s',

	'WRONG_FILESIZE'			=> 'حجم فایل خیلی زیاد است, حداکثر حجم مجاز %1$d %2$s.',
	'WRONG_SIZE'				=> 'The image must be at least %1$s wide, %2$s high and at most %3$s wide and %4$s high. The submitted image is %5$s wide and %6$s high.',
));
