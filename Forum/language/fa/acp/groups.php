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
	'ACP_GROUPS_MANAGE_EXPLAIN'		=> 'در این بخش می توانید گروه های کاربری را مدیریت کنید. می توانید گروه های فعلی را حذف و یا ویرایش کرده و یا گروه های جدیدی را ایجاد کنید. و همچنین می توانید رهبران گروه ها را مشخص کنید و یا حالت گروهی را تغییر دهید.',
	'ADD_GROUP_CATEGORY'			=> 'اضافه کردن دسته',
	'ADD_USERS'						=> 'اضافه کردن کاربران',
	'ADD_USERS_EXPLAIN'				=> 'در این گزینه میتوایند کاربران را به گروه اضافه کنید،تایین کنید که آیا گروه برای کاربران انتخاب شده پیشفرض باشد و یا نه و یا رهبران گروه را مشخص کنید. لطفا هر نام کاربری را در سطر جداگانه ای وارد کنید.',

	'COPY_PERMISSIONS'				=> 'کپی کردن سطوح دسترسی از',
	'COPY_PERMISSIONS_EXPLAIN'		=> 'هنگام ایجاد گروه،گروه سطح دسترسی مشابه مورد انتخاب شده در این گزینه را خواهد داشت',
	'CREATE_GROUP'					=> 'ایجاد گروه جدید',

	'GROUPS_NO_MEMBERS'				=> 'این گروه عضوی ندارد',
	'GROUPS_NO_MODS'				=> 'رهبری برای گروه مشخص نشده است',

	'GROUP_APPROVE'					=> 'تأیید اعضا',
	'GROUP_APPROVED'				=> 'اعضای تأیید شده',
	'GROUP_AVATAR'					=> 'نمایه گروه',
	'GROUP_AVATAR_EXPLAIN'			=> 'این تصویر در کنترل پنل گروه نمایش داده خواهد شد.',
	'GROUP_CATEGORY_NAME'			=> 'نام دسته',
	'GROUP_CLOSED'					=> 'بسته شده',
	'GROUP_COLOR'					=> 'رنگ گروه',
	'GROUP_COLOR_EXPLAIN'			=> 'رنگی را مشخص می کند که نام کاربری کاربر پس از عضویت در گروه در آن رنگ نمایش داده خواهد شد،برای حالت پیشفرض این فیلد را خالی بگزارید.',
	'GROUP_CONFIRM_ADD_USERS'		=> array(
		1	=> 'آیا از اضافه کردن %2$s به گروه اطمینان دارید؟',
		2	=> 'آیا از اضافه کردن کاربران %2$s به گروه اطمینان دارید؟',
	),
	'GROUP_CREATED'					=> 'گروه با موفقیت ایجاد شد',
	'GROUP_DEFAULT'					=> 'گروه برای اعضا پیشفرض باشد',
	'GROUP_DEFS_UPDATED'			=> 'گروه پیشفرض برای تمامی تعضای انتخاب شده تنظیم شد.',
	'GROUP_DELETE'					=> 'حذف عضو از گروه',
	'GROUP_DELETED'					=> 'گروه حذف و گروه پیشفرض کاربری با موفقیت تنظیم شد',
	'GROUP_DEMOTE'					=> 'تنزل رتبه ی رهبر گروه',
	'GROUP_DESC'					=> 'توضیحات گروه',
	'GROUP_DETAILS'					=> 'جزئیات گروه',
	'GROUP_EDIT_EXPLAIN'			=> 'در این بخش می توانید گروه های موجود را ویرایش کنید، نام،توضیحات گروه را وارد کرده و حالت(باز،بسته و...) آن را مشخص کنید. همچنین می توانید تنظیمات مربوط به گروه مشخصی مانند رنگ و رتبه و ... را تنظیم کنید. از دیگر امکانات این بخش اعمال تنظیم بر روی کاربران می باشد،لطفا توجه داشته باشید که تا زمانی که سطوح دسترسی مشخصی تعریف نشود، اعضای گروه می توانند آواتار گروه را برای خود تغییر دهند.',
	'GROUP_ERR_USERS_EXIST'			=> 'کاربران مشخص شده درحال حاضر عضو گروه می باشند',
	'GROUP_FOUNDER_MANAGE'			=> 'فقط توسط مؤسس سایت مدیریت شود',
	'GROUP_FOUNDER_MANAGE_EXPLAIN'	=> 'مدیریت گروه را فقط به مؤسس محدود می کند،همچنان کاربران می توانند گروه و اعضای ان را ببینند.',
	'GROUP_HIDDEN'					=> 'مخفی',
	'GROUP_LANG'					=> 'زبان گروه',
	'GROUP_LEAD'					=> 'رهبران گروه',
	'GROUP_LEADERS_ADDED'			=> 'رهبران جدید با موفقیت به گروه اضافه شدند.',
	'GROUP_LEGEND'					=> 'نمایش اطلاعات معرفی گروه',
	'GROUP_LIST'					=> 'اعضای کنونی',
	'GROUP_LIST_EXPLAIN'			=> 'این لیست کاملی از اعضای این گروه می باشد. می توانید اعضای موجود را حذف کنید (به استثنای بعضی گروه های ویژه) و یا اعضای جدیدی را به گروه اضافه کنید.',
	'GROUP_MEMBERS'					=> 'اعضای گروه',
	'GROUP_MEMBERS_EXPLAIN'			=> 'این لیست کاملی از تمامی اعضای این گروه می باشد. بخش مجزایی برای رهبران گروه و کاربران مورد انتظار و اعضا موجود است. از این بخش می توانید به تمامی کاربران نظارت و نقش ها آنان را تایین کنید،برای این که رهبری را از رهبری حذف کنید می توانید او را تنزیل رتبه دهید که در این حالت عضو تنزیل رتبه شده از رتبه رهبری برکنار خواهد شد ولی همچنان در گروه عضو خواهد بود.در حالت برعکس این مورد، می توانید عضوی را ارتقاء رتبه دهید و وی را به عنوان رهبر گروه تأیین کنید.',
	'GROUP_MESSAGE_LIMIT'			=> 'محدودیت پیغام خصوصی گروه در هر پوشه',
	'GROUP_MESSAGE_LIMIT_EXPLAIN'	=> 'این گزینه محدودیت موجود در تعداد پیغام خصوصی موجود در هر پوشه را تغییر خواهد داد. حداکثر برای همه گروه های کاربر مقدار واقعی تعیین میشود<br />با تعیین مقدار 0 تنظیمات به صورت تنظیمات پیشفرض تالار خواهد بود.',
	'GROUP_MODS_ADDED'				=> 'رهبر جدید گروه با موفقیت اضافه شد.',
	'GROUP_MODS_DEMOTED'			=> 'رتبه رهبران گروه با موفقیت تنزّل یافت.',
	'GROUP_MODS_PROMOTED'			=> 'رتبه اعضای گروه با موفقیت ارتقاء یافت.',
	'GROUP_NAME'					=> 'نام گروه',
	'GROUP_NAME_TAKEN'				=> 'گروهی با این نام وجود دارد،لطفا نام وارد شده را تغییر دهید.',
	'GROUP_OPEN'					=> 'باز',
	'GROUP_PENDING'					=> 'مورد انتظار عضویت',
	'GROUP_MAX_RECIPIENTS'			=> 'حداکثر تعداد گیرنده در هر پیغام خصوصی',
	'GROUP_MAX_RECIPIENTS_EXPLAIN'	=> 'مشخص کننده حداکثر تعداد گیرنده در هر پیغام خصوصی می باشد.  حداکثر برای همه گروه های کاربر مقدار واقعی تعیین میشود.<br />با تعیین مقدار 0 تنظیمات به صورت تنظیمات پیشفرض تالار خواهد بود.',
	'GROUP_OPTIONS_SAVE'			=> 'گزینه های گروه',
	'GROUP_PROMOTE'					=> 'ارتقاء به رهبری گروه',
	'GROUP_RANK'					=> 'رتبه گروه',
	'GROUP_RECEIVE_PM'				=> 'گروه می تواند پیغام خصوصی دریافت کند.',
	'GROUP_RECEIVE_PM_EXPLAIN'		=> 'لطفا توجه داشته باشید که گروه های مخفی در هر شرایطی نمی توانند پیغام خصوصی دریافت کنند.',
	'GROUP_REQUEST'					=> 'درخواست',
	'GROUP_SETTINGS_SAVE'			=> 'تنظیمات گروه',
	'GROUP_SKIP_AUTH'				=> 'معافیت رهبر گروه از سطوح دسترسی',
	'GROUP_SKIP_AUTH_EXPLAIN'		=> 'اگر فعال شود، دریگر سطوح دسترسی رهبر گروه با سطوح دسترسی اعضای گروه مشابه نخواهد بود.',
	'GROUP_SPECIAL'					=> 'از پیش تعریف شده',
	'GROUP_TEAMPAGE'				=> 'نمایش گروه در لیست مدیران',
	'GROUP_TYPE'					=> 'نوع گروه',
	'GROUP_TYPE_EXPLAIN'			=> 'مشخص می کند که چه کسانی بتوانند در گروه عضو شوند و یا آن را ببینند.',
	'GROUP_UPDATED'					=> 'تنظیمات گروه با موفقیت بروزرسانی شدند.',

	'GROUP_USERS_ADDED'				=> 'کاربران جدید با موفقیت اضافه شدند.',
	'GROUP_USERS_EXIST'				=> 'کاربران انتخاب شده در حال حاضر عضو می باشند.',
	'GROUP_USERS_REMOVE'			=> 'کاربران با موفقیت از گروه حذف شدند.',

	'LEGEND_EXPLAIN'				=> 'گروه هایی که در " لیست مدیران" نمایش داده می شوند:',
	'LEGEND_SETTINGS'				=> 'تنظیمات گروه',
	'LEGEND_SORT_GROUPNAME'			=> 'مرتب سازی بر اساس نام گروه',
	'LEGEND_SORT_GROUPNAME_EXPLAIN'	=> 'هنگامیکه گزینه فعال باشد از این دستور چشم پوشی میشود.',

	'MANAGE_LEGEND'			=> 'مدیریت گروه های کاربری',
	'MANAGE_TEAMPAGE'		=> 'مدیریت لیست مدیران',
	'MAKE_DEFAULT_FOR_ALL'	=> 'پیشفرض کردن گروه به تمامی اعضا',
	'MEMBERS'				=> 'اعضا',

	'NO_GROUP'					=> 'گروهی مشخص نشده است.',
	'NO_GROUPS_ADDED'			=> 'گروهی اضافه نشده است.',
	'NO_GROUPS_CREATED'			=> 'گروهی ایجاد نشده است.',
	'NO_PERMISSIONS'			=> 'کپی نکردن سطوح دسترسی ',
	'NO_USERS'					=> 'کاربری را وارد نکرده اید.',
	'NO_USERS_ADDED'			=> 'هیچ کاربری به گروه اضافه نشده است.',
	'NO_VALID_USERS'			=> 'کاربر واجد شرایطی را برای انجام عملیات انتخاب نکرده اید.',

	'PENDING_MEMBERS'			=> 'در حال انتظار',
	
	'SELECT_GROUP'				=> 'انتخاب یک گروه',
	'SPECIAL_GROUPS'			=> 'گروه های از پیش تعریف شده',
	'SPECIAL_GROUPS_EXPLAIN'	=> 'گروه های از پیش مشخص شده گروه هایی ویژه میباشند که نمی توانید آنها را ویرایش کنید و یا تغییر دهید.با این وجود می توانید کاربرانی را به این گروه ها اضافه کنید و یا تنظیمات ابتدایی آنها را تغییر دهید.',

	'TEAMPAGE'					=> 'لیست مدیران',
	'TEAMPAGE_DISP_ALL'			=> 'همه اعضا',
	'TEAMPAGE_DISP_DEFAULT'		=> 'تنها اعضای پیش فرض گروه',
	'TEAMPAGE_DISP_FIRST'		=> 'تنها اولین فرد گروه',
	'TEAMPAGE_EXPLAIN'			=> 'گروه هایی که در " لیست مدیران " نمایش داده میشوند:',
	'TEAMPAGE_FORUMS'			=> 'نمایش انجمن های مدیریت شده',
	'TEAMPAGE_FORUMS_EXPLAIN'	=> 'اگر "بله" انتخاب شود, لیستی از انجمن هایی که مدیران سطوح دسترسی برای مدیریت آن را دارند در ستونی در صفحه "لیست مدیران" به نمایش در می آید ، این کار باعث فشار آمدن به دیتابیس میشود.',
	'TEAMPAGE_MEMBERSHIPS'		=> 'عضویت در صفحه کاربر',
	'TEAMPAGE_SETTINGS'			=> 'تنظیمات لیست مدیران',
	'TOTAL_MEMBERS'				=> 'اعضا',

	'USERS_APPROVED'				=> 'اعضا با موفقیت تایید شدند',
	'USER_DEFAULT'					=> 'پیشفرض کاربر',
	'USER_DEF_GROUPS'				=> 'گروه های ساخته شده جدید',
	'USER_DEF_GROUPS_EXPLAIN'		=> 'این ها گروه هایی هستند که توسط شما و یا سایر مدیران ایجاد شده اند.می توانید آن ها را ویرایش کنید و یا حتی حذف کنید.',
	'USER_GROUP_DEFAULT'			=> 'به عنوان گروه پیشفرض انتخاب شود',
	'USER_GROUP_DEFAULT_EXPLAIN'	=> 'اگر بله را انتخاب کنید،این گروه،گروه پیشفرض برای اعضا خواهد بود.',
	'USER_GROUP_LEADER'				=> 'به عنوان رهبر گروه انتخاب شود',
));
