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
	'ADD_ATTACHMENT'			=> 'بارگذاری پیوست',
	'ADD_ATTACHMENT_EXPLAIN'	=> 'اگر مایل به پیوست یک یا چند فایل هستید، لطفا جزئیات را وارد کنید.',
	'ADD_FILE'					=> 'اضافه کردن فایل',
	'ADD_POLL'					=> 'ایجاد نظرسنجی',
	'ADD_POLL_EXPLAIN'			=> 'اگر نمی خواهید نظرسنجی به موضوع خود اضافه کنید، لطفا قسمت زیر را خالی بگذارید.',
	'ALREADY_DELETED'			=> 'متاسفیم ولی این  پیام قبلا حذف شده است.',
	'ATTACH_COMMENT_NO_EMOJIS'	=> 'توضیحات مربوط به فایل پیوست حاوی نویسه‌های غیر مجاز است ( ایموجی)',
	'ATTACH_DISK_FULL'			=> 'فضای کافی جهت پیوست فایل وجود ندارد.',
	'ATTACH_QUOTA_REACHED'		=> 'متاسفیم، سهمیه پیوست فایل در تالار برای شما اتمام یافته است.',
	'ATTACH_SIG'				=> 'پیوست امضا (امضایتان را می‌توانید از کنترل پنل کاربر تغییر دهید)',

	'BBCODE_A_HELP'				=> 'پیوست‌های بارگذاری شده : [attachment=]filename.ext[/attachment]',
	'BBCODE_B_HELP'				=> 'نوشته ضخیم : [b]نوشته[/b]',
	'BBCODE_C_HELP'				=> 'نمایش کد : [code]کد[/code]',
	'BBCODE_D_HELP'				=> 'فلش: [flash=width,height]پیوند مورد نظر[/flash]',
	'BBCODE_F_HELP'				=> 'اندازه فونت : [size=85]نوشته کوچک[/size]',
	'BBCODE_IS_OFF'				=> '%sBBCode%s  <em>بسته</em> است.',
	'BBCODE_IS_ON'				=> '%sBBCode%s <em>باز</em> است',
	'BBCODE_I_HELP'				=> 'نوشته کج : [i]نوشته[/i]',
	'BBCODE_L_HELP'				=> 'لیست : [list][*]نوشته[/list]',
	'BBCODE_LISTITEM_HELP'		=> 'ایتم های لیست : [*]نوشته',
	'BBCODE_O_HELP'				=> 'لیست منظم : [list=1][*]اولین نکته[/list] یا [list=a][*]نکته یک[/list]',
	'BBCODE_P_HELP'				=> 'اضافه کردن تصویر : [img]پیوند تصویر[/img]',
	'BBCODE_Q_HELP'				=> 'نقل قول نوشته : [quote]نوشته[/quote]',
	'BBCODE_S_HELP'				=> 'رنگ فونت : [color=red]نوشته[/color]  یا [color=#FF0000]نوشته[/color]',
	'BBCODE_U_HELP'				=> 'خط زیرین نوشته : [u]نوشته[/u]',
	'BBCODE_W_HELP'				=> 'اضافه کردن URL : [url]پیوند مورد نظر[/url] یا [url=http://url]نوشته مورد نظر[/url]',
	'BBCODE_Y_HELP'				=> 'لیست: اضافه کردن عنصر جدید',
	'BUMP_ERROR'				=> 'بعد از آخرین پستتان در این موضوع در چنین زمان کوتاهی نمی‌توانید مضوع را بامپ (bump) کنید.',

	'CANNOT_DELETE_REPLIED'		=> 'متاسفیم ولی فقط پست‌هایی را می‌توانید حذف کنید که به آن‌ها پاسخ داده نشده باشد.',
	'CANNOT_EDIT_POST_LOCKED'	=> 'این پست قفل شده است و دیگر نمی‌توانید آن را ویرایش کنید.',
	'CANNOT_EDIT_TIME'			=> 'دیگر نمی‌توانید پست را ویرایش و یا حذف کنید.',
	'CANNOT_POST_ANNOUNCE'		=> 'متاسفیم، ولی نمی‌توانید اطلاعیه ای را ارسال کنید.',
	'CANNOT_POST_STICKY'		=> 'متاسفیم، ولی نمی‌توانید موضوع مهمی را ارسال کنید.',
	'CHANGE_TOPIC_TO'			=> 'تغییر نوع موضوع به',
	'CHARS_POST_CONTAINS'		=> array(
		1	=> 'پیام شما شامل %1$d نویسه است.',
		2	=> 'پیام شما شامل %1$d نویسه است.',
	),
	'CHARS_SIG_CONTAINS'		=> array(
		1	=> 'امضا شما شامل %1$d نویسه است.',
		2	=> 'امضا شما شامل %1$d نویسه است.',
	),
	'CLOSE_TAGS'				=> 'بستن تگ',
	'CURRENT_TOPIC'				=> 'موضوع کنونی',

	'DELETE_FILE'				=> 'حذف فایل',
	'DELETE_MESSAGE'			=> 'حذف پیام',
	'DELETE_MESSAGE_CONFIRM'	=> 'آیا از حذف کردن این پیام مطمئنید؟',
	'DELETE_OWN_POSTS'			=> 'متاسفیم ولی فقط می‌توانید پست‌های خودتان را ویرایش کنید.',
	'DELETE_PERMANENTLY'		=> 'حذف دائمی',
	'DELETE_POST_CONFIRM'		=> 'آیا از حذف این پست مطمئنید؟',
	'DELETE_POST_PERMANENTLY_CONFIRM'	=> 'آیا از حذف <strong>دائمی</strong> این پست مطمئنید؟',
	'DELETE_POST_PERMANENTLY'	=> array(
		1	=> 'حذف دائمی به معنای غیر قابل بازگشت بودن پست می‌باشد.',
		2	=> 'حذف دائمی %1$d پست‌ها به معنای غیر قابل برگشت پذیر بودن آن‌ها است.',
	),
	'DELETE_POSTS_CONFIRM'		=> 'آیا از حذف این پست‌ها مطمئنید؟',
	'DELETE_POSTS_PERMANENTLY_CONFIRM'	=> 'آیا از حذف <strong>دائمی</strong> این پست‌ها مطمئنید؟',
	'DELETE_REASON'				=> 'دلیل حذف',
	'DELETE_REASON_EXPLAIN'		=> 'دلیل حذف مشخص شده تنها برای مدیران قابل رویت است.',
	'DELETE_POST_WARN'			=> 'حذف پست',
	'DELETE_TOPIC_CONFIRM'		=> 'آیا از حذف این موضوع مطمئنید؟',
	'DELETE_TOPIC_PERMANENTLY'	=> array(
		1	=> 'حذف دائمی موضوع به معنای غیر قابل بازگشت بودن آن است.',
		2	=> 'حذف دائمی %1$d موضوعات به معنای غیر قابل بازگشت بودن آن‌ها است.',
	),
	'DELETE_TOPIC_PERMANENTLY_CONFIRM'	=> 'آیا از حذف <strong>دائمی</strong> این موضوع مطمئنید؟',
	'DELETE_TOPICS_CONFIRM'		=> 'آیا از حذف این موضوعات مطمئنید؟',
	'DELETE_TOPICS_PERMANENTLY_CONFIRM'	=> 'آیا از حذف <strong>دائمی</strong> موضوعات مطمئنید؟',
	'DISABLE_BBCODE'			=> 'غیرفعال کردن BBCode',
	'DISABLE_MAGIC_URL'			=> 'به طور خودکار لینک ها را تجزیه نکن',
	'DISABLE_SMILIES'			=> 'غیرفعال کردن شکلک‌ها',
	'DISALLOWED_CONTENT'		=> 'بارگذاری تأیید نشد، زیرا نوع فایل بارگذاری شده ممکن است به عنوان حمله به سایت باشد.',
	'DISALLOWED_EXTENSION'		=> 'افزونه %s مجاز نمی‌باشد.',
	'DRAFT_LOADED'				=> 'پیش‌نویس در منطقه ارسال پست اضافه شد. ممکن است بخواهید این نوشته را تکمیل کنید.<br />پیش‌نویس بعد از ارسال پست حذف خواهد شد.',
	'DRAFT_LOADED_PM'			=> 'پیش‌نویس در منطقه ارسال پیام خصوصی اضافه شد. ممکن است بخواهید نوشته این پیام خصوصی را تکمیل کنید.<br />پیش‌نویس پس از ارسال پیام خصوصی حذف خواهد شد.',
	'DRAFT_SAVED'				=> 'پیش‌نویس با موفقیت ذخیره شد.',
	'DRAFT_TITLE'				=> 'عنوان پیش‌نویس',

	'EDIT_REASON'				=> 'دلیل ویرایش این پست',
	'EMPTY_FILEUPLOAD'			=> 'فایل بارگذاری شده خالی است.',
	'EMPTY_MESSAGE'				=> 'هنگام ارسال پست باید متنی را وارد کنید.',
	'EMPTY_REMOTE_DATA'			=> 'فایل بارگذاری نشد، لطفا بارگذاری را به صورت دستی امتحان کنید.',

	'FLASH_IS_OFF'				=> '[flash] <em>بسته</em> است',
	'FLASH_IS_ON'				=> '[flash] <em>باز</em> است',
	'FLOOD_ERROR'				=> 'نمی‌توانید بعد از ارسال پستی بلافاصله پست دیگری را ارسال کنید.',
	'FONT_COLOR'				=> 'رنگ فونت.',
	'FONT_COLOR_HIDE'			=> 'مخفی کردن رنگ فونت',
	'FONT_HUGE'					=> 'بسیار بزرگ',
	'FONT_LARGE'				=> 'بزرگ',
	'FONT_NORMAL'				=> 'معمولی',
	'FONT_SIZE'					=> 'اندازه فونت',
	'FONT_SMALL'				=> 'کوچک',
	'FONT_TINY'					=> 'بسیار کوچک',

	'GENERAL_UPLOAD_ERROR'		=> 'بارگذاری پیوست به %s صورت نگرفت.',

	'IMAGES_ARE_OFF'			=> '[img] <em>بسته</em> است',
	'IMAGES_ARE_ON'				=> '[img] <em>باز</em> است',
	'INVALID_FILENAME'			=> 'نام فایل %s نا معتبر است.',

	'LOAD'						=> 'بارگزاری',
	'LOAD_DRAFT'				=> 'بارگزاری پیش‌نویس',
	'LOAD_DRAFT_EXPLAIN'		=> 'در این بخش می‌توانید پیش‌نویس‌هایی را که مایل به تکمیل کردن آن‌ها هستید، انتخاب کنبد. پست کنونی شما لغو و محتویات آن حذف خواهد شد. برای مشاهده، ویرایش و یا حذف پیش‌نویس‌ها به کنترل پنل کاربر مراجعه کنید.',
	'LOGIN_EXPLAIN_BUMP'		=> 'برای بامپ(bump) کردن انجمن، باید ابتدا وارد شوید.',
	'LOGIN_EXPLAIN_DELETE'		=> 'برای حذف پست‌های این انجمن باید ابتدا وارد شوید.',
	'LOGIN_EXPLAIN_SOFT_DELETE'	=> 'برای پاک کردن پست‌های این انجمن باید ابتدا وارد شوید',
	'LOGIN_EXPLAIN_POST'		=> 'برای ارسال پستی در این انجمن باید ابتدا وارد شوید.',
	'LOGIN_EXPLAIN_QUOTE'		=> 'برای نقل قول پست‌ها در این انجمن باید ابتدا وارد شوید.',
	'LOGIN_EXPLAIN_REPLY'		=> 'برای پاسخ به موضوعی در این انجمن باید ابتدا وارد شوید.',

	'MAX_ATTACHMENT_FILESIZE'	=> 'حداکثر حجم فایل برای هر پیوست: %s.',
	'MAX_FONT_SIZE_EXCEEDED'	=> 'فونت ها را تا اندازه %1$d می‌توانید استفاده کنید.',
	'MAX_FLASH_HEIGHT_EXCEEDED'	=> array(
		1	=> 'طول فایل فلش باید حداکثر تا %1$d پیکسل باشد.',
		2	=> 'طول فایل فلش باید حداکثر تا %1$d پیکسل باشد.',
	),
	'MAX_FLASH_WIDTH_EXCEEDED'	=> array(
		1	=> 'عرض فایل فلش باید حداکثر تا %1$d پیکسل باشد.',
		2	=> 'عرض فایل فلش باید حداکثر تا %1$d پیکسل باشد.',
	),
	'MAX_IMG_HEIGHT_EXCEEDED'	=> array(
		1	=> 'طول تصویر باید حداکثر تا %1$d پیکسل باشد.',
		2	=> 'طول تصویر باید حداکثر تا %1$d پیکسل باشد.',
	),
	'MAX_IMG_WIDTH_EXCEEDED'	=> array(
		1	=> 'عرض تصویر باید حداکثر تا %1$d پیکسل باشد.',
		2	=> 'عرض تصویر باید حداکثر تا %1$d پیکسل باشد.',
	),

	'MESSAGE_BODY_EXPLAIN'		=> array(
		0	=> '', // zero means no limit, so we don't view a message here.
		1	=> 'پیام خودتان را در اینجا وارد کنید، متن پیام نباید بیشتر از <strong>%d</strong> نویسه باشد.',
		2	=> 'پیام خودتان را در اینجا وارد کنید، متن پیام نباید بیشتر از <strong>%d</strong> نویسه باشد.',
	),
	'MESSAGE_DELETED'			=> 'این پیام با موفقیت حذف شد.',
	'MORE_SMILIES'				=> 'شکلک‌های بیشتر',

	'NOTIFY_REPLY'				=> 'وقتی که پاسخی ارسال شد، مرا با خبر کن',
	'NOT_UPLOADED'				=> 'فایل بارگذاری نشد.',
	'NO_DELETE_POLL_OPTIONS'	=> 'نمی‌توانید گزینه‌های نظرسنجی موجود را حذف کنید.',
	'NO_PM_ICON'				=> 'بدون آیکون',
	'NO_POLL_TITLE'				=> 'باید عنوانی را برای نظرسنجی وارد کنید.',
	'NO_POST'					=> 'پست درخواست شده موجود نیست.',
	'NO_POST_MODE'				=> 'حالت پست مشخص نشده است.',
	'NO_TEMP_DIR'				=> 'پوشه موقت موجود نیست یا قابل نوشتن نمی باشد.',

	'PARTIAL_UPLOAD'			=> 'فقط قسمتی از فایل انتخاب شده بارگذاری شد.',
	'PHP_UPLOAD_STOPPED'		=> 'افزونه PHP، بارگذاری فایل را متوقف کرد.',
	'PHP_SIZE_NA'				=> 'حجم فایل پیوست شده بسیار زیاد است.<br />حداکثر حجم فایل در php.ini تعیین نشده است.',
	'PHP_SIZE_OVERRUN'			=> 'حجم فایل پیوست شده بسیار زیاد است. حداکثر حجمs %1$d %2$s می‌باشد.<br />توجه داشته باشید که این مقدار در php.ini تعریف شده است و قابل بازنویسی نیست.',
	'PLACE_INLINE'				=> 'قرار دادن داخل خط',
	'POLL_DELETE'				=> 'حذف نظرسنجی',
	'POLL_FOR'					=> 'اجرای نظرسنجی برای',
	'POLL_FOR_EXPLAIN'			=> 'برای نامحدود کردن زمان پایان نظرسنجی، 0 را وارد کنید.',
	'POLL_MAX_OPTIONS'			=> 'گزینه‌ها برای هر کاربر',
	'POLL_MAX_OPTIONS_EXPLAIN'	=> 'این تعداد گزینه‌هایی می‌باشد که کاربر میتواند در یک نظرسنجی انتخاب کند.',
	'POLL_OPTIONS'				=> 'گزینه‌های نظرسنجی',
	'POLL_OPTIONS_EXPLAIN'		=> array(
		1	=> 'هرکدام را در سطری جدید تعریف کنید. می‌توانید <strong>%d</strong> گزینه انتخاب کنید.',
		2	=> 'هرکدام را در سطری جدید تعریف کنید. می‌توانید <strong>%d</strong> گزینه انتخاب کنید.',
	),
	'POLL_OPTIONS_EDIT_EXPLAIN'		=> array(
		1	=> 'هرکدام را در سطری جدید تعریف کنید. می‌توانید <strong>%d</strong> گزینه انتخاب کنید. اگر گزینه ای را حذف و یا اضافه کنید، تمام رای های فعلی حذف خواهد شد.',
		2	=> 'هرکدام را در سطری جدید تعریف کنید. می‌توانید <strong>%d</strong> گزینه انتخاب کنید. اگر گزینه ای را حذف و یا اضافه کنید، تمام رای های فعلی حذف خواهد شد.',
	),
	'POLL_QUESTION'				=> 'سوال نظرسنجی',
	'POLL_TITLE_TOO_LONG'		=> 'عنوان نظرسنجی باید کمتر از 100 نویسه باشد.',
	'POLL_TITLE_COMP_TOO_LONG'	=> 'اندازه عنوان نظرسنجی شما بسیار زیاد است، حذف BBCode و یا شکلک‌ها را در نظر بگیرید.',
	'POLL_VOTE_CHANGE'			=> 'پذیرفتن تغییر رای',
	'POLL_VOTE_CHANGE_EXPLAIN'	=> 'اگر این گزینه را فعال کنید، کاربران میتوانند رای خود را تغییر دهند.',
	'POSTED_ATTACHMENTS'		=> 'پیوست‌های ارسال شده',
	'POST_APPROVAL_NOTIFY'		=> 'هنگامی که پست شما تأیید شود، به شما خبر داده خواهد شد.',
	'POST_CONFIRMATION'			=> 'تأیید پست',
	'POST_CONFIRM_EXPLAIN'		=> 'برای جلوگیری از هرزنامه ها، لطفا کد تأییدی را که در تصویر زیر نمایش داده میشود، وارد کنید. اگر مشکل بینایی دارید و یا نمی‌توانید کد را وارد کنید، لطفا با %sمدیر تالار%s تماس بگیرید.',
	'POST_DELETED'				=> 'این پیام با موفقیت حذف شد.',
	'POST_EDITED'				=> 'این پیام با موفقیت ویرایش شد.',
	'POST_EDITED_MOD'			=> 'این پیام با موفقیت ویرایش شد، ولی برای نمایش عموم ابتدا باید توسط مدیر تأیید شود.',
	'POST_GLOBAL'				=> 'سراسری',
	'POST_ICON'					=> 'آیکون پست',
	'POST_NORMAL'				=> 'معمولی',
	'POST_REVIEW'				=> 'بررسی پست',
	'POST_REVIEW_EDIT'			=> 'بررسی پست',
	'POST_REVIEW_EDIT_EXPLAIN'	=> 'این پست توسط کاربری دیگر در هنگامی که این پست را ویرایش میکردید، ویرایش شد. ممکن هست بخواهید نسخه جدید پست را بررسی کنید و سپس تغییراتتان را اعمال کنید.',
	'POST_REVIEW_EXPLAIN'		=> 'حداقل یک پست به این موضوع ارسال شده است، ممکن است بخواهید ابتدا آن را بررسی کنید.',
	'POST_STORED'				=> 'این پیام با موفقیت ارسال شد.',
	'POST_STORED_MOD'			=> 'این پیام با موفقیت ثبت شد، ولی برای نمایش عموم باید ابتدا توسط مدیر تأیید شود.',
	'POST_TOPIC_AS'				=> 'ارسال موضوع به عنوان',
	'PROGRESS_BAR'				=> 'نوار پیشرفت',

	'QUOTE_DEPTH_EXCEEDED'		=> array(
		1	=> 'شما می‌توانید حداکثر %1$d نقل قول تو در تو استفاده کنید',
		2	=> 'شما می‌توانید حداکثر %1$d نقل قول تو در تو استفاده کنید',
	),
	'QUOTE_NO_NESTING'			=> 'شما نمی‌توانید از نقل قول تو در تو استفاده کنید.',

	'REMOTE_UPLOAD_TIMEOUT'		=> 'فایل مشخص شده بارگذاری نمی شود زیرا زمان درخواست به پایان رسیده است.',
	'SAVE'						=> 'ذخیره',
	'SAVE_DATE'					=> 'ذخیره در',
	'SAVE_DRAFT'				=> 'ذخیره پیش‌نویس',
	'SAVE_DRAFT_CONFIRM'		=> 'توجه داشته باشید که پیش‌نویس ذخیره شده فقط شامل متن و عنوان پست می‌باشد. سایر عناصر حذف خواهند شد. آیا از ذخیره پیش‌نویس مطمئنید؟',
	'SMILIES'					=> 'شکلک‌ها',
	'SMILIES_ARE_OFF'			=> 'شکلک‌ها <em>بسته</em> هستند',
	'SMILIES_ARE_ON'			=> 'شکلک‌ها <em>باز</em> هستند',
	'STICKY_ANNOUNCE_TIME_LIMIT'=> 'محدودیت زمانی موضوعات مهم/سراسری/اطلاعیه‌ها',
	'STICK_TOPIC_FOR'			=> 'مهم کردن موضوع برای',
	'STICK_TOPIC_FOR_EXPLAIN'	=> 'برای نامحدود کردن زمان پایان موضوعات مهم/سراسری/اطلاعیه‌ها، 0 را وارد کنید. توجه داشته باشید که این عدد به تاریخ پست بستگی دارد.',
	'STYLES_TIP'				=> 'نکته: سبک ها را می توان به سرعت در متن انتخاب شده اعمال کرد.',

	'TOO_FEW_CHARS'				=> 'متن شما حاوی نویسه‌های کوتاهی است.',
	'TOO_FEW_CHARS_LIMIT'		=> array(
		1	=> 'شما حداقل باید %1$d نویسه وارد کنید.',
		2	=> 'شما حداقل باید %1$d نویسه وارد کنید.',
	),
	'TOO_FEW_POLL_OPTIONS'		=> 'حداقل باید 2 گزینه برای نظرسنجی تعریف کنید.',
	'TOO_MANY_ATTACHMENTS'		=> 'نمی‌توانید پیوست دیگری را اضافه کنید. حداکثر تعداد پیوست %d می‌باشد.',
	'TOO_MANY_CHARS'			=> 'نویسه‌های متن شما بسیار زیاد است.',
	'TOO_MANY_CHARS_LIMIT'		=> array(
		2	=> 'حداکثر تعداد نویسه‌های متن باید %1$d باشد.',
	),
	'TOO_MANY_POLL_OPTIONS'		=> 'گزینه‌های بسیار زیادی را برای نظرسنجی وارد کردید.',
	'TOO_MANY_SMILIES'			=> 'پیام شما حاوی تعداد زیادی شکلک است. حداکثر تعداد شکلک مورد قبول، %d می‌باشد.',
	'TOO_MANY_URLS'				=> 'پیام شما حاوی تعداد زیادی لینک است. حداثر تعداد لینک مورد قبول، %d می‌باشد.',
	'TOO_MANY_USER_OPTIONS'		=> 'تعداد گزینه برای هر کاربر نمیتواند بیشتر از تعداد کل گزینه‌ها باشد.',
	'TOPIC_BUMPED'				=> 'این موضوع با موفقیت بامپ(bump) شد.',

	'UNAUTHORISED_BBCODE'		=> 'از BBCode های مقابل نمی‌توانید استفاده کنید : %s.',
	'UNSUPPORTED_CHARACTERS_MESSAGE'	=> 'متن شما حاوی نویسه‌های غیر مجاز زیر است:<br />%s',
	'UNSUPPORTED_CHARACTERS_SUBJECT'	=> 'متن شما حاوی نویسه‌های غیر مجاز زیر است:<br />%s',
	'UPDATE_COMMENT'			=> 'به‌روز رسانی نظر',
	'URL_INVALID'				=> 'لینک مشخص شده معتبر نیست.',
	'URL_NOT_FOUND'				=> 'فایل مشخص شده یافت نشد.',
	'URL_IS_OFF'				=> '[url] <em>بسته</em> است',
	'URL_IS_ON'					=> '[url] <em>باز</em> است',
	'USER_CANNOT_BUMP'			=> 'موضوعات را در این انجمن نمی‌توانید بامپ(bump) کنید.',
	'USER_CANNOT_DELETE'		=> 'نمی‌توانید پست‌ها را در این انجمن حذف کنید.',
	'USER_CANNOT_EDIT'			=> 'نمی‌توانید پست‌ها را در این انجمن ویرایش کنید.',
	'USER_CANNOT_REPLY'			=> 'در این انجمن نمی‌توانید پاسخی را ارسال کنید.',
	'USER_CANNOT_FORUM_POST'	=> 'در این انجمن نمی‌توانید عملیاتی را ارسال کنید. زیرا نوع انجمن از آن پشتیبانی نمیکند.',

	'VIEW_MESSAGE'				=> '%sمشاهده پیام ارسال شده شما%s',
	'VIEW_PRIVATE_MESSAGE'		=> '%sمشاهده پیام خصوصی ارسال شده شما%s',

	'WRONG_FILESIZE'			=> 'حجم فایل خیلی زیاد است, حداکثر حجم مجاز %1$d %2$s.',
	'WRONG_SIZE'				=> 'تصویر باید حداقل %1$s عرض، %2$s طول و حداکثر %3$s عرض و %4$s طور داشته باشد. تصویر ارسال شده %5$s عرض و %6$s طول دارد.',
));
