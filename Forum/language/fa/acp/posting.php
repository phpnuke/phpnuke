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

// BBCodes
// Note to translators: you can translate everything but what's between { and }
$lang = array_merge($lang, array(
	'ACP_BBCODES_EXPLAIN'		=> 'BBCode نوعی کدهایی شبیه به HTML می باشند.در این بخش می توانید BBCode ها را ویرایش کرده و یا آنها را اضافه کنید.',
	'ADD_BBCODE'				=> 'اضافه کردن BBCode',

	'BBCODE_DANGER'				=> 'BBCode قابل اضافه شدن نیست زیرا، {TEXT} مورد نامطابقی از HTML هست. ممکن است خطای امنیتی XSS باشد. سعی کنید که از انواع {SIMPLETEXT} یا {INTTEXT} استفاده کنید. فقط هنگامی این مرحله را ادامه دهید که از پیامدهای خطا آگاه باشید و فقط هنگامی از {TEXT} استفاده کنید که عدم استفاده از آن ممکن نباشد.',
	'BBCODE_DANGER_PROCEED'		=> 'ادامه', //'I understand the risk',

	'BBCODE_ADDED'				=> 'BBCode با موفقیت اضافه شد.',
	'BBCODE_EDITED'				=> 'BBCode با موفقیت ویرایش شد.',
	'BBCODE_DELETED'			=> 'BBCode با موفقیت حذف شد.',
	'BBCODE_NOT_EXIST'			=> 'BBCode انتخاب شده موجود نیست.',
	'BBCODE_HELPLINE'			=> 'گزینه راهنما',
	'BBCODE_HELPLINE_EXPLAIN'	=> 'این فیلد هنگامی نمایش داده خواهد شد که ماوس بر روی BBCode قرار گیرد.',
	'BBCODE_HELPLINE_TEXT'		=> 'متن گزینه راهنما',
	'BBCODE_HELPLINE_TOO_LONG'	=> 'گزینه راهنمای وارد شده بسیار طولانی می باشد.',

	'BBCODE_INVALID_TAG_NAME'	=> 'نام تگ BBCode انتخاب شده در حال حاضر موجود می باشد.',
	'BBCODE_INVALID'			=> 'ساختار BBCode معتبر نمی باشد.',
	'BBCODE_OPEN_ENDED_TAG'		=> 'BBCode سفارشی باید حاوی هر دو تگ باز و بسته کردن باشد.',
	'BBCODE_TAG'				=> 'تگ',
	'BBCODE_TAG_TOO_LONG'		=> 'نام تگ وارد شده بسیار طولانی می باشد.',
	'BBCODE_TAG_DEF_TOO_LONG'	=> 'توضیحات تگ وارد شده بسیار طولانی می باشد.',
	'BBCODE_USAGE'				=> 'کاربرد BBCode	',
	'BBCODE_USAGE_EXAMPLE'		=> '[highlight={COLOR}]{TEXT}[/highlight]<br /><br />[font={SIMPLETEXT1}]{SIMPLETEXT2}[/font]',
	'BBCODE_USAGE_EXPLAIN'		=> 'کاربرد BBCode اینگونه می باشد،متغیرهای دلخواه را جایگزین مثال کنید. (%sدر قسمت پایین می توانید ببینید%s).',

	'EXAMPLE'						=> 'مثال :',
	'EXAMPLES'						=> 'مثال ها :',

	'HTML_REPLACEMENT'				=> 'جایگزین HTML',
	'HTML_REPLACEMENT_EXAMPLE'		=> '&lt;span style="background-color: {COLOR};"&gt;{TEXT}&lt;/span&gt;<br /><br />&lt;span style="font-family: {SIMPLETEXT1};"&gt;{SIMPLETEXT2}&lt;/span&gt;',
	'HTML_REPLACEMENT_EXPLAIN'		=> 'در این بخش جایگزین HTML را وارد کنید. جایگزاری علامت های استفاده شده در بخش بالا را فراموش نکنید.',

	'TOKEN'					=> 'علامت',
	'TOKENS'				=> 'علامت ها',
	'TOKENS_EXPLAIN'		=> 'علامت ها حفره هایی برای ورودی های کاربران می باشند. داده های ورودی فقط هنگامی تأیید می شوند که منطبق بر تعریف باشند. اگر نیاز باشد،می توانید اعدادی را به انتهای متن در پارانتز اضافه کنید. مانند {TEXT1}, {TEXT2}.<br /><br />در جایگزینی HTML می توانید از حلقه های زبانی ای استفاده کنید که در که در دایرکتوری language/ قرار دارند،مانند : {L_<em>&lt;STRINGNAME&gt;</em>} که <em>&lt;STRINGNAME&gt;</em> نام حلقه ترجمه شده توسط شما است و می خواهید آن را اضافه کنید برای مثال، {L_WROTE} به صورت “wrote” و یا ترجمه متناظر با آن(با توجه به زبان محلی) نمایش داده خواهد شد.<br /><br /><strong>لطفا توجه داشته باشید که فقط از علامت هایی می توانید در BBCode استفاده کنید که در لیست زیر موجود می باشند.</strong>',
	'TOKEN_DEFINITION'		=> 'چه می تواند باشد ؟',
	'TOO_MANY_BBCODES'		=> 'نمی توانید بیشتر از این BBCode اضافه کنید،لطفا یک یا چند مورد را حذف کرده و دوباره امتحان کنید.',

	'tokens'	=>	array(
		'TEXT'			=> 'هر نوع متن شامل کاراکترهای خارجی، اعداد و… نباید از این در تگ های HTML استفاده کنید. به جای آن از IDENTIFIER, INTTEXT و یا SIMPLETEXT استفاده کنید.',
		'SIMPLETEXT'	=> 'کاراکتر های زبان لاتین، اعداد, خط فاصله, کاما, نقطه, منها, به علاوه, خط ربط و و خط زیرین.',
		'INTTEXT'		=> 'کاراکتر های یونی کد، اعداد, خط فاصله, کاما, نقطه, متها, به علاوه, خط ربط, خط زیرین و فضای خالی.',
		'IDENTIFIER'	=> 'کاراکتر های زبان لاتین (A-Z) اعداد, خط ربط و خط زیرین.',
		'NUMBER'		=> 'هر مجموعه ای از ارقاک',
		'EMAIL'			=> 'آدرس ایمیل معتبر',
		'URL'			=> 'URL معتبر با هر نوع پروتکل (http, ftp, غیره… سوء استفاده در جاوا اسکریپت ممکن نیست). اگر هیچکدام تعریف نشود “http://” پیشوند حلقه خواهد بود.',
		'LOCAL_URL'		=> 'URL محلی،“%s” فقط باید در صفحات داخلی سایت استفاده شود و حاوی هیچگونه پروتکل و پیشوند نیست.',
		'RELATIVE_URL'	=> 'A relative URL. You can use this to match parts of a URL, but be careful: a full URL is a valid relative URL. When you want to use relative URLs of your board, use the LOCAL_URL token.',
		'COLOR'			=> 'رنگ HTML که میتواند به صورت عددی نوشته شود <samp>#FF1234</samp> و یا <a href="http://www.w3.org/TR/CSS21/syndata.html#value-def-color">کلمات کلیدی رنگ های CSS</a> مانند <samp>سرخابی</samp> یا <samp>مرزغیرفعال</samp>'
	),
));

// Smilies and topic icons
$lang = array_merge($lang, array(
	'ACP_ICONS_EXPLAIN'		=> 'در این بخش می توانید آیکون های مورد استفاده در موضوعات و پست ها را ویرایش،حذف و اضافه کنید،ای آیکون معمئلا در کنار نام موضوعات در لیست موضوعات و عنوان پست در لیست پست های یک موضوع نمایش داده می شوند.اضافه کردن بسته آیکون نیز مقدور می باشد.',
	'ACP_SMILIES_EXPLAIN'	=> 'شکلک ها تصاویر کوچکی می باشند که احساسات کاربران را نشان می دهند،در این بخش می توانید این شکلک ها را که در پست ها و پیغام های خصوصی مورد استفاده قرار می گیرند،ویرایش،حذف و اضافه کنید.',

	'ADD_SMILIES'			=> 'اضافه کردن چندین شکلک',
	'ADD_SMILEY_CODE'		=> 'اضافه کردن کد فرعی شکلک',
	'ADD_ICONS'				=> 'اضافه کردن چندین آیکون',
	'AFTER_ICONS'			=> 'بعد از %s',
	'AFTER_SMILIES'			=> 'بعد از %s',

	'CODE'						=> 'کد',
	'CURRENT_ICONS'				=> 'آیکون های کنونی',
	'CURRENT_ICONS_EXPLAIN'		=> 'لطفا انتخاب کنید که چه عملیاتی بر روی آیکون های نصب شده انجام گیرد.',
	'CURRENT_SMILIES'			=> 'شکلک های کنونی',
	'CURRENT_SMILIES_EXPLAIN'	=> 'لطفا انتخاب کنید که چه عملیاتی بر روی شکلک های نصب شده انجام گیرد.',

	'DISPLAY_ON_POSTING'		=> 'نمایش در صفحه ارسالات',
	'DISPLAY_POSTING'			=> 'در صفحه ارسالات',
	'DISPLAY_POSTING_NO'		=> 'در صفحه ارسالات نیست',

	'EDIT_ICONS'				=> 'ویرایش آیکون ها',
	'EDIT_SMILIES'				=> 'ویرایش شکلک ها',
	'EMOTION'					=> 'احساس',
	'EXPORT_ICONS'				=> 'بارگیری icones.pak',
	'EXPORT_ICONS_EXPLAIN'		=> '%sاگر بر روی این لینک کلیک کنید،پیکربندی بسته آیکون ها در <samp>icons.pak</samp> ذخیره خواهد شد و بعد از بارگیری می توانید آن را در آرشیو <samp>.zip</samp> یا <samp>.tgz</samp> قرار دهید که هم حاوی فایل پیکربندی <samp>icons.pak</samp> و هم خود آیکون ها می باشد.%s.',
	'EXPORT_SMILIES'			=> 'بارگیری فایل icons.pak',
	'EXPORT_SMILIES_EXPLAIN'	=> '%sاگر بر روی این لینک کلیک کنید،پیکربندی بسته شکلک ها در <samp>icons.pak</samp> ذخیره خواهد شد و بعد از بارگیری می توانید آن را در آرشیو <samp>.zip</samp> یا <samp>.tgz</samp> قرار دهید که هم حاوی فایل پیکربندی <samp>icons.pak</samp> و هم خود شکلک ها می باشد.%s.',

	'FIRST'			=> 'نخست',

	'ICONS_ADD'				=> 'اضافه کردن آیکون جدید',
	'ICONS_ADDED'			=> array(
		0	=> 'آیکون جدیدی اضافه نشده است.',
		1	=> 'آیکون با موفقیت اضافه شد.',
		2	=> 'آیکون ها با موفقیت اضافه شدند.',
	),
	'ICONS_CONFIG'			=> 'پیکربندی آیکون',
	'ICONS_DELETED'			=> 'آیکون با موفقیت حدذف شد.',
	'ICONS_EDIT'			=> 'ویرایش آیکون',
	'ICONS_EDITED'			=> array(
		0	=> 'آیکونی بروزرسانی نشد.',
		1	=> 'آیکون با موفیت بروزرسانی شد.',
		2	=> 'آیکون ها با موفقیت بروزرسانی شدند.',
	),
	'ICONS_HEIGHT'			=> 'ارتفاع آیکون',
	'ICONS_IMAGE'			=> 'تصویر آیکون',
	'ICONS_IMPORTED'		=> 'بسته آیکون با موفقیت اضافه شد.',
	'ICONS_IMPORT_SUCCESS'	=> 'بسته های آیکون با موفقیت آپلود شدند.',
	'ICONS_LOCATION'		=> 'محل آیکون',
	'ICONS_NOT_DISPLAYED'	=> 'آیکون های مقابل در صفحه ارسالات نمایش داده نمی شوند.',
	'ICONS_ORDER'			=> 'ترتیب آیکون',
	'ICONS_URL'				=> 'فایل تصویر ایکون',
	'ICONS_WIDTH'			=> 'عرض آیکون',
	'IMPORT_ICONS'			=> 'نصب بسته آیکون ها',
	'IMPORT_SMILIES'		=> 'نصب بسته شکلک ها',

	'KEEP_ALL'			=> 'همه را نگهدار',

	'MASS_ADD_SMILIES'	=> 'اضافه کردن چندین شکلک',

	'NO_ICONS_ADD'		=> 'آیکونی برای اضافه کردن در دسترس نیست.',
	'NO_ICONS_EDIT'		=> 'آیکونی برای تغییر در دسترس نیست.',
	'NO_ICONS_EXPORT'	=> 'آیکونی برای ایجاد بسته وجود ندارد.',
	'NO_ICONS_PAK'		=> 'بسته آیکونی یافت نشد.',
	'NO_SMILIES_ADD'	=> 'شکلکی برای اضافه کردن در دسترس نیست.',
	'NO_SMILIES_EDIT'	=> 'شکلکی برای تغییر در دسترس نیست.',
	'NO_SMILIES_EXPORT'	=> 'شکلکی برای ایجاد بسته وجود ندارد.',
	'NO_SMILIES_PAK'	=> 'بسته شکلکی یافت نشد.',

	'PAK_FILE_NOT_READABLE'		=> 'فایل <samp>.pak</samp> خوانده نمی شود.',

	'REPLACE_MATCHES'	=> 'جایگزینی تطابق ها',

	'SELECT_PACKAGE'			=> 'فایل بسته ای را انتخاب کنید',
	'SMILIES_ADD'				=> 'اضافه کردن شکلک جدید',
	'SMILIES_ADDED'				=> array(
		0	=> 'شکلکی اضافه نشد.',
		1	=> 'شکلک با موفقیت اضافه شد.',
		2	=> 'شکلک ها با موفقیت اضافه شدند.',
	),
	'SMILIES_CODE'				=> 'کد شکلک',
	'SMILIES_CONFIG'			=> 'پیکربندی شکلک',
	'SMILIES_DELETED'			=> 'شکلک با موفقیت حذف شد.',
	'SMILIES_EDIT'				=> 'ویرایش شکلک',
	'SMILIE_NO_CODE'			=> 'شکلک “%s”  پذیرفته نشد،زیرا کدی وارد نشده بود.',
	'SMILIE_NO_EMOTION'			=> 'شکلک “%s” پذیرفته نشد،زیرا احساس آن تعیین نشده بود.',
	'SMILIE_NO_FILE'			=> 'شکلک “%s” پذیرفته نشد،زیرا فایل آن یافت نشد.',
	'SMILIES_EDITED'			=> array(
		0	=> 'شکلکی بروزرسانی نشد.',
		1	=> 'شکلک با موفقیت بروزرسانی شد.',
		2	=> 'شکلک ها با موفقیت بروزرسانی شدند.',
	),
	'SMILIES_EMOTION'			=> 'احساس',
	'SMILIES_HEIGHT'			=> 'ارتفاع شکلک',
	'SMILIES_IMAGE'				=> 'تصویر شکلک',
	'SMILIES_IMPORTED'			=> 'بسته شکلک با موفقیت اضافه شد.',
	'SMILIES_IMPORT_SUCCESS'	=> 'بسته شکلک با موفقیت اضافه شد.',
	'SMILIES_LOCATION'			=> 'محل شکلک',
	'SMILIES_NOT_DISPLAYED'		=> 'شکلک های مقابل در صفحه ارسالات نمایش داده نمی شوند.',
	'SMILIES_ORDER'				=> 'ترتیب شکلک',
	'SMILIES_URL'				=> 'تصویر فایل شکلک',
	'SMILIES_WIDTH'				=> 'عرض شکلک',

	'TOO_MANY_SMILIES'			=> array(
		1	=> 'محدودیت %d شکلک پر شده است.',
		2	=> 'محدودیت %d شکلک پر شده است.',
	),

	'WRONG_PAK_TYPE'	=> 'بسته مشخص شده حاوی اطلاعات مورد نیاز نیست.',
));

// Word censors
$lang = array_merge($lang, array(
	'ACP_WORDS_EXPLAIN'		=> 'در این بخش می توانید کلماتی را که به طور خودکار از انجمن حذف خواهند شد،حذف،ویرایش و یا اضافه کنید.کلمات سانسور شده در نام کاربری کاربران مؤثر نخواهد بود.می توانید با استفاده از ستاره (*) می توانید پیشوند و پسوند کلمات را مشخص کنید به طور مثال اگر *test را وارد کنید،کلمه detest سانسور خواهد شد و اگر test* را وارد کنید، testing هم سانسور خواهد شد. ',
	'ADD_WORD'				=> 'اضافه کردن کلمه جدید',

	'EDIT_WORD'		=> 'ویرایش سانسور کلمه',
	'ENTER_WORD'	=> 'باید کلمه و جایگزین آن را وارد کنید.',

	'NO_WORD'	=> 'کلمه ای برای ویرایش انتخاب نشده است.',

	'REPLACEMENT'	=> 'جایگزین',

	'UPDATE_WORD'	=> 'بروزرسانی سانسور کلمات',

	'WORD'				=> 'کلمه',
	'WORD_ADDED'		=> 'کلمه سانسور با موفقیت اضافه شد.',
	'WORD_REMOVED'		=> 'کلمه سانسور با موفقیت حذ شد.',
	'WORD_UPDATED'		=> 'کلمه سانسور با موفقیت بروزرسانی شد.',
));

// Ranks
$lang = array_merge($lang, array(
	'ACP_RANKS_EXPLAIN'		=> 'با استفاده از این فرم می توانید رتبه ها را ویرایش،حذف و یا اضافه کنید.اضافه کردن رتبه های ویژه که از کنترل پنل کاربر اعمال می شود نیز مقدور می باشد.',
	'ADD_RANK'				=> 'اضافه کردن رتبه جدید',

	'MUST_SELECT_RANK'		=> 'باید رتبه ای را انتخاب کنید',

	'NO_ASSIGNED_RANK'		=> 'رتبه ای مشخص نشده است.',
	'NO_RANK_TITLE'			=> 'عنوانی برای رتبه مشخص نکرده اید.',
	'NO_UPDATE_RANKS'		=> 'رتبه با موفقیت حذف شد،با این وجود رتبه در اکانت کاربران بروزرسانی نشده است و این کار را باید دستی انجام دهید.',

	'RANK_ADDED'			=> 'رتبه با موفقیت اضافه شد.',
	'RANK_IMAGE'			=> 'تصویر رتبه',
	'RANK_IMAGE_EXPLAIN'	=> 'از این گزینه برای تعریف تصویر کوچکی استفاده کنید،این تصویر باید در دایرکتوری مربوطه قرار گیرد.',
	'RANK_IMAGE_IN_USE'		=> '(در حال استفاده)',
	'RANK_MINIMUM'			=> 'حداقل پست',
	'RANK_REMOVED'			=> 'رتبه با موفقیت حذف شد.',
	'RANK_SPECIAL'			=> 'به عنوان رتبه ویژه انتخاب شود.',
	'RANK_TITLE'			=> 'عنوان رتبه',
	'RANK_UPDATED'			=> 'رتبه با موفقیت اضافه شد.',
));

// Disallow Usernames
$lang = array_merge($lang, array(
	'ACP_DISALLOW_EXPLAIN'	=> 'در این بخش می توانید نام کاربری های غیرمجاز را مشخص کنید،استفاده از این نام های کارری در ثبت نام مجاز نخواهد بود،می توانید از ستاره (*) استفاده کنید همچنین نمی توانید نام کاربری های موجود را به این لیست اضافه کنید،برای این مار ابتدا باید آن نام کاربری را حذف کنید.',
	'ADD_DISALLOW_EXPLAIN'	=> 'با ستاره (*) می توانید کاراکتر های مطابق با نام کاربری غیر مجاز را هم غیر مجاز کنید.',
	'ADD_DISALLOW_TITLE'	=> 'اضافه کردن نام کاربری غیرمجاز',

	'DELETE_DISALLOW_EXPLAIN'	=> 'برای حذف نام کاربری از لیست نام های کاربری غیر مجاز،ابتدا بر روی ان علامت بگذارید و سپس بر روی ارسال کلیک کنید.',
	'DELETE_DISALLOW_TITLE'		=> 'حذف نام کاربری غیرمجاز',
	'DISALLOWED_ALREADY'		=> 'نام وارد شده به لیست اضافه نشد زیرا یا در حال حاضر در لیست موجود است یا در لیست کلمات سانسور شده موجود است و یا این نام کاربری در تالارتان موجود می باشد.',
	'DISALLOWED_DELETED'		=> 'نام کاربری غیرمجاز با موفقیت حذف شد.',
	'DISALLOW_SUCCESSFUL'		=> 'نام کاربری غیرمجاز با موفقیت اضفه شد.',

	'NO_DISALLOWED'				=> 'نام کاربری غیرمجازی وجود ندارد.',
	'NO_USERNAME_SPECIFIED'		=> 'نام کاربری را انتخاب و یا وارد نکرده اید.',
));

// Reasons
$lang = array_merge($lang, array(
	'ACP_REASONS_EXPLAIN'	=> 'در این بخش می توانید دلایل درج شده در گزارش ها را ویرایش،حذف و یا اضافه کنید. توجه داشته باشید که حذف یکی از دلایل ( که با ستاره مشخص شده است) مقدور نیست،از این گزینه برای گزارش سایر موارد که در سؤال ها وجود ندارد استفاده می شود.',
	'ADD_NEW_REASON'		=> 'اضافه کردن دلیل جدید',
	'AVAILABLE_TITLES'		=> 'عنوان در دسترس دلیل محلی',

	'IS_NOT_TRANSLATED'			=> 'دلیل <strong>محلی نیست</strong> .',
	'IS_NOT_TRANSLATED_EXPLAIN'	=> 'دلیل <strong>محلی نیست</strong> . برای ایجاد دلایل محلی،کلید مناسب را در فایل زبانی (قسمت دلایل) وارد کنید.',
	'IS_TRANSLATED'				=> 'دلیل محلی می باشد',
	'IS_TRANSLATED_EXPLAIN'		=> 'دلیل محلی می باشد،اگر عنوان وارد شده در این بخش در فایل زبانی نیز وجود داشته باشد،از عنوان و توضیحات موجود در فایل زبانی استفاده خواهد شد.',

	'NO_REASON'					=> 'دلیلی یافت نشد.',
	'NO_REASON_INFO'			=> 'شما باید عنوان و توضیحی برای دلیل خود مشخص کنید.',
	'NO_REMOVE_DEFAULT_REASON'	=> 'شما قادر به حذف دلیل پیش فرض نیستید “دیگر”.',
	
	'REASON_ADD'				=> 'اضافه کردن دلیل گزارش',
	'REASON_ADDED'				=> 'دلیل گزارش با موفقیت اضافه شد.',
	'REASON_ALREADY_EXIST'		=> 'دلیلی با این عنوان وجود دارد،لطفا عنوان دیگری را وارد کنید.',
	'REASON_DESCRIPTION'		=> 'توضیحات دلیل',
	'REASON_DESC_TRANSLATED'	=> 'توضیحات دلیل نمایشی',
	'REASON_EDIT'				=> 'ویرایش دلیل گزارش',
	'REASON_EDIT_EXPLAIN'		=> 'در این بخش می توانید دلیلی را ویرایش و یا اضاقه کنید،اگر ترجنه دلیل محلی وجود داشته باشد از آن استفاده خواهد شد.',
	'REASON_REMOVED'			=> 'دلیل گزارش با موفقیت حذف شد.',
	'REASON_TITLE'				=> 'عنوان دلیل',
	'REASON_TITLE_TRANSLATED'	=> 'عنوان دلیل نمایشی',
	'REASON_UPDATED'			=> 'دلیل گزارش با موفقیت بروزرسانی شد.',

	'USED_IN_REPORTS'		=> 'استفاده شده در گزارش ها',
));
