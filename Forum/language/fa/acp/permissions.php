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
	'ACP_PERMISSIONS_EXPLAIN'	=> '
		<p>سطوح دسترسی به چهار بخش تجزیه شده اند و در این چهار بخش طبقه بندی می شوندفاین بخش ها عبارتند از :</p>

		<h2>سطوح دسترسی سراسری</h2>
		<p>این سطوح دسترس، دسترسی به سراسر تالار را کنترل می کنند و خود به جهار بخش سطوح دسترسی کاربران،سطوح دسترسی گروه‌ها،سطوح دسترسی مدیران انجمن‌ها و سطوح دسترسی مدیران تجزیه می شوند. </p>

		<h2>سطوح دسترسی انجمن</h2>
		<p>این سطوح دسترسی، دسترسی به هر انجمنی را کنترل می کنند و خود به چهار بخش سطوح دسترسی انجمن،مدیران انجمن‌ها،سطوح دسترسی انجمن‌ها برای کاربران و سطوح دسترسی انجمن‌ها برای گروه‌ها تجزیه می شوند.</p>

		<h2>نقش های سطوح دسترسی</h2>
		<p>این نقش ها با گردآوری سطوح دسترسی نقش هایی را ایجاد می کند.نقش پیش‌فرض باید تمامی ابعاد مدیریت را پوشش دهد با این وجود می‌توانید نقش هایی را به هر چهار بخش اضافه،ویرایش و یا حذف کنید.</p>

		<h2>ماسک های سطوح دسترسی</h2>
		<p>از این ماسک ها برای مشاهده سطوح دسترسی مختلف کاربران،مدیران انجمن‌ها(محلی،سراسری) استفاده می شود</p>

		<br />

		<p>برای اطلاعات بیشتر در مورد مدیریت سطوح دسترسی در phpBB3 به <a href="https://www.phpbb.com/support/docs/en/3.3/ug/quickstart/permissions/">تنظیمات سطوح دسترسی - راهنمای سریع</a>.</p>
	',

	'ACL_NEVER'					=> 'هیچگاه',
	'ACL_SET'					=> 'تنظیمات سطوح دسترسی',
	'ACL_SET_EXPLAIN'			=> 'سطوح دسترسی بر پایه یک سیستم کوچک <strong>بلی</strong>/<strong>خیر</strong> هستند. تعیین یک گزینه با وضعیت <strong>هیچگاه</strong> برای یک کاربر یا یک گروه ، مقادیر تعیین شده دیگر را مقدم می کند. در صورتی که نمی خواهید یک مقدار را برای گزینه ای تعیین کنید ( برای کاربر یا گروه ) <strong>خیر</strong> را مستقر کنید، اگر مقادیر  برای این گزینه قرار است در جای دیگری تقدم یابد <strong>هیچگاه</strong> مفروض است.',
	'ACL_SETTING'			=> 'تنظیمات',

	'ACL_TYPE_A_'			=> 'سطوح دسترسی مدیریتی',
	'ACL_TYPE_F_'			=> 'سطوح دسترسی انجمن',
	'ACL_TYPE_M_'			=> 'سطوح دسترسی مدیرانجمن',
	'ACL_TYPE_U_'			=> 'سطوح دسترسی کاربر',

	'ACL_TYPE_GLOBAL_A_'	=> 'سطوح دسترسی مدیریتی',
	'ACL_TYPE_GLOBAL_U_'	=> 'سطوح دسترسی کاربر',
	'ACL_TYPE_GLOBAL_M_'	=> 'سطوح دسترسی مدیران کلی انجمن',
	'ACL_TYPE_LOCAL_M_'	=> 'سطوح دسترسی مدیران انجمن',
	'ACL_TYPE_LOCAL_F_'	=> 'سطوح دسترسی انجمن',

	'ACL_NO'			=> 'خیر',
	'ACL_VIEW'			=> 'سطوح دسترسی نمایشی',
	'ACL_VIEW_EXPLAIN'	=> 'در اینجا شما می‌توانید سطوح دسترسی تاثیر پذیر در گروه‌ها / کاربران را مشاهده کنید. عرصه های قرمز رنگ اشاره بر این دارند که گروه / کاربر این سطح دسترسی را ندارد ، عرصه های سبز رنگ نیر حاکی از این است که گروه / کاربر از این سطح دسترسی بهره می برد.',
	'ACL_YES'			=> 'بلی',

	'ACP_ADMINISTRATORS_EXPLAIN'	=> 'در اینجا شما می‌توانید سطوح دسترسی ریاست را به گروه‌ها و کاربران ارجاع دهید. همه کاربران با سطوح دسترسی ریاست ( مدیر کل ) می توانند کنترل پنل مدیریت را مشاهده کنند.',
	'ACP_FORUM_MODERATORS_EXPLAIN'	=> 'در اینجا شما می‌توانید کاربران و گروه‌ها را بعنوان مدیرانجمن مقرر کنید. برای بهره مند کردن کاربران از دسترسی, لطفا به صفحات مناسب با سطوح دسترسی مدیر کل یا مدیر کلی انجمن‌ها مراجعه کنید.',
	'ACP_FORUM_PERMISSIONS_EXPLAIN'				=> 'در اینجا می‌توانید تعیین کنید که چه کاربرانی به چه انجمن‌هایی دسترسی داشته باشند. برای مشخص کردن مدیران انجمن‌ها و یا مدیران لطفا به صفحه مورد نظر مراجعه کنید.',
	'ACP_FORUM_PERMISSIONS_COPY_EXPLAIN'		=> 'در اینجا می‌توانید سطوح دسترسی انجمنی را به بک انجمن دیگر و یا چندین انجمن دیگر کپی کنید.',
	'ACP_GLOBAL_MODERATORS_EXPLAIN'				=> 'در این بخش می‌توانید سطوح مدیریت انجمن‌ها را برای کاربران/گروه‌ها مشخص کنید. سطوح دسترسی مدیران انجمن‌ها شبیه مدیر انجمن است ولی با این تفاوت که مدیران انجمن‌ها سطح دسترسی مدیریت تمامی انجمن‌های تالار را دارند.',
	'ACP_GROUPS_FORUM_PERMISSIONS_EXPLAIN'		=> 'در اینجا می‌توانید سطوح دسترسی انجمن‌ها را برای کاربران مشخص کنید.',
	'ACP_GROUPS_PERMISSIONS_EXPLAIN'			=> 'در اینجا می‌توانید سطوح دسترسی سراسری،سطوح دسترسی مدیران انجمن‌ها و سطوح دسترسی مدیریت را برای کاربران/گروه‌ها مشخص کنید.  سطوح دسترسی کاربران شامل توانایی استفاده از آواتار،پیام خصوصی و ... ، سطوح دسترسی مدیران انجمن‌ها شامل تأیید پست‌ها،تأیید موضوعات،مدیریت تحریم ها و ... و در نهایت سطوح دسترسی مدیران شامل شامل مدیریت سطوح دسترسی،مدیریت BBCode ها،مدیریت انجمن‌ها و ... می باشد. سطوح دسترسی کاربران مختلف باید در موارد نادر تغییر کند. روش توصیه شده این است که کاربران را در گروه‌های مختلف قرار دهید و سپس سطوح دسترسی گروه را مناسب با کاربران عضو تنظیم کنید.',


	'ACP_ADMIN_ROLES_EXPLAIN'		=> 'در اینجا می‌توانید نقش های سطوح دسترسی مدیریت را تنظیم کنید. نقش ها سطوح دسترسی مؤثری هستند،اگر نقشی را تغییر دهید سطوح دسترسی متناسب با آن نیز تغییر خواهد یافت.',
	'ACP_FORUM_ROLES_EXPLAIN'		=> 'در اینجا می‌توانید نقش های سطوح دسترسی انجمن‌ها را مدیریت کنید. نقش ها سطوح دسترسی مؤثری هستند،اگر نقشی را تغییر دهید سطوح دسترسی متناسب با آن نیز تغییر خواهد یافت.',
	'ACP_MOD_ROLES_EXPLAIN'			=> 'در اینجا می‌توانید نقش های سطوح دسترسی مدیران را تنظیم کنید. نقش ها سطوح دسترسی مؤثری هستند،اگر نقشی را تغییر دهید سطوح دسترسی متناسب با آن نیز تغییر خواهد یافت.',
	'ACP_USER_ROLES_EXPLAIN'		=> 'در اینجا می‌توانید نقش های سطوح کاربری را تنظیم کنید. نقش ها سطوح دسترسی مؤثری هستند،اگر نقشی را تغییر دهید سطوح دسترسی متناسب با آن نیز تغییر خواهد یافت.',


	'ACP_USERS_FORUM_PERMISSIONS_EXPLAIN'		=> 'در اینجا می‌توانید سطوح دسترسی انجمن‌ها را برای کاربران مشخص کنید.',
	'ACP_USERS_PERMISSIONS_EXPLAIN'				=> 'در اینجا می‌توانید سطوح دسترسی سراسری را برای کاربران - سطوح دسترسی کاربران،سطوح دسترسی مدیران انجمن‌ها و سطوح دسترسی مدیران مشخص کنید.   سطوح دسترسی کاربران شامل توانایی استفاده از آواتار،پیام خصوصی و ... ، سطوح دسترسی مدیران انجمن‌ها شامل تأیید پست‌ها،تأیید موضوعات،مدیریت تحریم ها و ... و در نهایت سطوح دسترسی مدیران شامل شامل مدیریت سطوح دسترسی،مدیریت BBCode ها،مدیریت انجمن‌ها و ... می باشد. سطوح دسترسی کاربران مختلف باید در موارد نادر تغییر کند. روش توصیه شده این است که کاربران را در گروه‌های مختلف قرار دهید و سپس سطوح دسترسی گروه را مناسب با کاربران عضو تنظیم کنید.',

	'ACP_VIEW_ADMIN_PERMISSIONS_EXPLAIN'		=> 'در اینجا می‌توانید سطوح دسترسی مدیریت مشخص شده برای کاربران/گروه‌های انتخاب شده را مشاهده کنید.',
	'ACP_VIEW_GLOBAL_MOD_PERMISSIONS_EXPLAIN'	=> 'در اینجا می‌توانید سطوح دسترسی"مدیران انجمن‌ها" مشخص شده برای کاربران/گروه‌های انتخاب شده را مشاهده کنید.',
	'ACP_VIEW_FORUM_PERMISSIONS_EXPLAIN'		=> 'در اینجا می‌توانید سطوح دسترسی انجمن‌ها را که برای کاربران/گروه‌ها و انجمن‌های انتخاب شده مشاهده کنید.',
	'ACP_VIEW_FORUM_MOD_PERMISSIONS_EXPLAIN'	=> 'در اینجا می‌توانید سطوح دسترسی مدیران انجمن‌های شخص شده برای کاربران انتخاب شده را مشاهده کنید.',
	'ACP_VIEW_USER_PERMISSIONS_EXPLAIN'			=> 'در اینجا می‌توانید سطوح دسترسی مشخص شده برای کاربران انتخاب شده را مشاهده کنید.',

	'ADD_GROUPS'				=> 'اضافه کردن گروه‌ها',
	'ADD_PERMISSIONS'			=> 'اضافه کردن سطوح دسترسی',
	'ADD_USERS'					=> 'اضافه کردن کاربران',
	'ADVANCED_PERMISSIONS'		=> 'سطوح دسترسی پیشرفته',
	'ALL_GROUPS'				=> 'انتخاب همه گروه‌ها',
	'ALL_NEVER'					=> 'همگی <strong>هرگز</strong>',
	'ALL_NO'					=> 'همگی <strong>نه</strong>',
	'ALL_USERS'					=> 'انتخاب همه کاربران',
	'ALL_YES'					=> 'همگی <strong>بله</strong>',
	'APPLY_ALL_PERMISSIONS'		=> 'تأیید همگی سطوح دسترسی',
	'APPLY_PERMISSIONS'			=> 'تأیید سطوح دسترسی',
	'APPLY_PERMISSIONS_EXPLAIN'	=> 'سطوح دسترسی و نقش های مشخص شده برای این گزینه فقط به این گزینه و سایر گزینه‌های علامت گذاری شده اعمال خواهد شد.',
	'AUTH_UPDATED'				=> 'سطوح دسترسی به‌روز رسانی شدند.',

	'COPY_PERMISSIONS_CONFIRM'				=> 'آیا از ادامه فرآیند مطمئنید؟ اگر ادامه دهید سطوح دسترسی مشخص شده جایگزین سطوح دسترسی قدیمی خواهند شد. ',
	'COPY_PERMISSIONS_FORUM_FROM_EXPLAIN'	=> 'انجمن مبدأ که می خواهید سطوح دسترسی از آن کپی شود.',
	'COPY_PERMISSIONS_FORUM_TO_EXPLAIN'		=> 'انجمن مقصدی که می خواهید سطوح دسترسی به آن کپی شود.',
	'COPY_PERMISSIONS_FROM'					=> 'کپی کردن سطوح دسترسی از',
	'COPY_PERMISSIONS_TO'					=> 'اعمال کردن سطوح دسترسی به',

	'CREATE_ROLE'				=> 'ایجاد نقش',
	'CREATE_ROLE_FROM'			=> 'استفاده از تنظمیمات…',
	'CUSTOM'					=> 'سفارشی…',

	'DEFAULT'					=> 'پیش‌فرض',
	'DELETE_ROLE'				=> 'حذف نقش',
	'DELETE_ROLE_CONFIRM'		=> 'آیا از حذف این نقش مطمئنید؟ حذف این نقش سطوح دسترسی مواردی را که از آن استفاده می کنند را <strong>حذف نخواهد کرد.</strong>',
	'DISPLAY_ROLE_ITEMS'		=> 'مشاهده مواردی که از این نقش استفاده می کنند.',

	'EDIT_PERMISSIONS'			=> 'ویرایش سطوح دسترسی',
	'EDIT_ROLE'					=> 'ویرایش نقش',

	'GROUPS_NOT_ASSIGNED'		=> 'گروهی برای این نقش مشخص نشده است.',

	'LOOK_UP_GROUP'				=> 'بررسی گروه کاربری',
	'LOOK_UP_USER'				=> 'بررسی کاربر',

	'MANAGE_GROUPS'		=> 'مدیریت گروه‌ها',
	'MANAGE_USERS'		=> 'مدیریت کاربران',

	'NO_AUTH_SETTING_FOUND'		=> 'تنظیمات سطوح دسترسی مشخص نشد.',
	'NO_ROLE_ASSIGNED'			=> 'نقشی مشخص نشد …',
	'NO_ROLE_ASSIGNED_EXPLAIN'	=> 'تنظیمات نقش ها سطوح دسترسی را تغییر نمی دهد. اگر می خواهید سطوح دسترسی را تغییر دهید و یا آن‌ها را حذف کنید بر روی لینک “همگی <strong>نه</strong>” کلیک کنید.',
	'NO_ROLE_AVAILABLE'			=> 'نقشی در دسترس نیست',
	'NO_ROLE_NAME_SPECIFIED'	=> 'لطفا برای نقش نامی را تعریف کنید.',
	'NO_ROLE_SELECTED'			=> 'نقش یافت نشد.',
	'NO_USER_GROUP_SELECTED'	=> 'هیچ کاربر و یا گروهی را مشخص نکرده اید.',

	'ONLY_FORUM_DEFINED'	=> 'در انتخابتان فقط انجمنی را انتخاب کرده اید،لطفا نام کاربری و یا گروهی را نیز مشخص کنید.',

	'PERMISSION_APPLIED_TO_ALL'		=> 'سطوح دسترسی و نقش ها برای موارد علامت گذاره شده نیز اعمال می شود',
	'PLUS_SUBFORUMS'				=> '+زیرانجمن‌ها',

	'REMOVE_PERMISSIONS'			=> 'حذف سطوح دسترسی',
	'REMOVE_ROLE'					=> 'حذف نقش',
	'RESULTING_PERMISSION'			=> 'سطوح دسترسی نتایج',
	'ROLE'							=> 'نقش',
	'ROLE_ADD_SUCCESS'				=> 'نقش با موفقیت اضافه شد.',
	'ROLE_ASSIGNED_TO'				=> 'کاربران/گروه‌ها به %s مشخص شدند.',
	'ROLE_DELETED'					=> 'نقش با موفقیت حذف شد',
	'ROLE_DESCRIPTION'				=> 'توضیحات نقش',

	'ROLE_ADMIN_FORUM'			=> 'مدیر انجمن',
	'ROLE_ADMIN_FULL'			=> 'مدیر کامل',
	'ROLE_ADMIN_STANDARD'		=> 'مدیر استاندارد',
	'ROLE_ADMIN_USERGROUP'		=> 'مدیر گروه‌ها و کاربران',
	'ROLE_FORUM_BOT'			=> 'دسترسی ربات ها',
	'ROLE_FORUM_FULL'			=> 'دسترسی کامل',
	'ROLE_FORUM_LIMITED'		=> 'دسترسی محدود شده',
	'ROLE_FORUM_LIMITED_POLLS'	=> 'دسترسی محدود شده + نظرسنجی ها',
	'ROLE_FORUM_NOACCESS'		=> 'بدون دسترسی',
	'ROLE_FORUM_ONQUEUE'		=> 'در صف مدیریت',
	'ROLE_FORUM_POLLS'			=> 'دسترسی استاندارد + نظرسنجی ها',
	'ROLE_FORUM_READONLY'		=> 'فقط دسترسی خواندن',
	'ROLE_FORUM_STANDARD'		=> 'دسترسی استاندارد',
	'ROLE_FORUM_NEW_MEMBER'		=> 'کاربران تازه عضو شده',
	'ROLE_MOD_FULL'				=> 'مدیر انحمن کامل',
	'ROLE_MOD_QUEUE'			=> 'صف مدیر انجمن',
	'ROLE_MOD_SIMPLE'			=> 'مدیر انجمن ساده',
	'ROLE_MOD_STANDARD'			=> 'مدیر انجمن استاندارد',
	'ROLE_USER_FULL'			=> 'همه ویژگی ها',
	'ROLE_USER_LIMITED'			=> 'ویژگی های محدود شده',
	'ROLE_USER_NOAVATAR'		=> 'بدون آواتار',
	'ROLE_USER_NOPM'			=> 'بدون پیام خصوصی',
	'ROLE_USER_STANDARD'		=> 'ویژگی های استاندارد',
	'ROLE_USER_NEW_MEMBER'		=> 'کاربران تاره عضو شده',

	'ROLE_DESCRIPTION_ADMIN_FORUM'			=> 'دسترسی به مدیریت انجمن و سطوح دسترسی انجمن مقدور می باشد.',
	'ROLE_DESCRIPTION_ADMIN_FULL'			=> 'به تمامی بخش های مدیریت این تالار دسترسی خواهد داشت<br />توصیه نمی شود.',
	'ROLE_DESCRIPTION_ADMIN_STANDARD'		=> 'تقریبا به همگی ابزار مدیریت دسترسی دارد ولی دسترسی به ابزار مرتبط به سرور و سایت مقدور نمی باشد.',
	'ROLE_DESCRIPTION_ADMIN_USERGROUP'		=> 'می تواند گروه‌ها و کاربران را مدیریت کند : می تواند سطوح دسترسی و تنظیمات را تغییر دهد و همچنین تحریم ها و رتبه‌های کاربران را مدیریت کند',
	'ROLE_DESCRIPTION_FORUM_BOT'			=> 'این نقش برای ربات ها و عنکبوت های موتور های جست‌وجو توصیه می شود.',
	'ROLE_DESCRIPTION_FORUM_FULL'			=> 'می تواند از تمامی ویژگی های انجمن استفاده کند مانند ارسال اطلاعیه‌ها و موضوعات مهم و نبودن محدودیت زمانی برای ارسالات.<br />برای کاربران معمولی توصیه نمی شود.',


	'ROLE_DESCRIPTION_FORUM_LIMITED'		=> 'می تواند از بعضی ویژگی های انجمن استفاده کند ولی نمی تواند پیوستی ارسال کند و یا از آیکون‌ها استفاده کند. ',
	'ROLE_DESCRIPTION_FORUM_LIMITED_POLLS'	=> 'دسترسی محدود خواهد بود ولی می تواند نظرسنجی ایجاد کند.',
	'ROLE_DESCRIPTION_FORUM_NOACCESS'		=> 'نه میتواند انجمن‌ها را ببیند و نه به آن دسترسی پیدا کند.',
	'ROLE_DESCRIPTION_FORUM_ONQUEUE'		=> 'می تواند از تمامی ویژگی های انجمن استفاده کند ولی پست‌ها و موضوعات وی باید مورد تأیید مدیران قرار بگیرد.',
	'ROLE_DESCRIPTION_FORUM_POLLS'			=> 'دسترسی استاندارد خواهد داشت ولی می تواند نظرسنجی ایجاد کند.',
	'ROLE_DESCRIPTION_FORUM_READONLY'		=> 'می تواند انجمن‌ها را بخواند ول نمی تواند موضوعی ایجاد کند و یا پستی ارسال کند.',
	'ROLE_DESCRIPTION_FORUM_STANDARD'		=> 'می تواند از اکثر ویژگی ها مانند ارسال پیوست و حذف موضوع خود بهره مند شود ولی نمی تواند موضوع خود را قفل کند و یا نظرسنجی ایجاد کند.',
	'ROLE_DESCRIPTION_FORUM_NEW_MEMBER'		=> 'نقشی برای اعضای کاربران به تازگی عضو شده؛ محتوی ویژگی <strong>هرگز</strong> برای سطوح دسترسی ویژگی های قفل شده.',

	'ROLE_DESCRIPTION_MOD_FULL'				=> 'می تواند از تمامی سطوح دسترسی مدیر انجمن از جمله تحریم کاربران بهره مند باشد.',
	'ROLE_DESCRIPTION_MOD_QUEUE'			=> 'فقط می تواند پست‌ها را تأیید و ویرایش کند.',
	'ROLE_DESCRIPTION_MOD_SIMPLE'			=> 'فقط می تواند از ابزار ساده موضوعات استفاده کند،نمی تواند هشدار بدهد و یا از ابزار مدیریت استفاده کند.',
	'ROLE_DESCRIPTION_MOD_STANDARD'			=> 'می تواند از اغلب ابزار مدیریت انجمن‌ها استفاده کند ولی نمی تواند کاربری را تحریم کند و یا نویسنده پستی را عوض کند.',
	'ROLE_DESCRIPTION_USER_FULL'			=> 'می تواند از تمامی ویژگی های موجود برای کاربران استفاده کند از جمله تغییر نام کاربری و لغو محدودیت زمانی ارسالات.<br />توصیه نمی شود.',
	'ROLE_DESCRIPTION_USER_LIMITED'			=> 'می تواند از بعضی ویژگی های کاربر به جز پیوست،ایمیل و پیام خصوصی بهره مند شود.',
	'ROLE_DESCRIPTION_USER_NOAVATAR'		=> 'دسترسی محدود خواهد داشت و نمی تواند آواتار داشته باشد.',
	'ROLE_DESCRIPTION_USER_NOPM'			=> 'دسترسی محدودی به ویژگی ها انجمن خواهد داشت و نمی تواند از بخش پیام‌های خصوصی استفاده کند.',
	'ROLE_DESCRIPTION_USER_STANDARD'		=> 'به اکثر ویژگی های کاربری درسترسی دارد ولی نمی تواند نام کاربریش را تغییر دهد و یا زمان محدودیت ارسالات را لغو کند.',
	'ROLE_DESCRIPTION_USER_NEW_MEMBER'		=> 'نقشی برای اعضای کاربران به تازگی عضو شده؛ محتوی ویژگی <strong>هرگز</strong> برای سطوح دسترسی ویژگی های قفل شده.',

	'ROLE_DESCRIPTION_EXPLAIN'		=> 'می‌توانید توضیحات کوتاهی وارد کنید و توضیح دهید که هر نقش چه ویژگی هایی دارد،این متن در صفحه سطوح دسترسی نیز نمایش داده خواهند شد.',
	'ROLE_DESCRIPTION_LONG'			=> 'توضیحات نقش بسیار طولانی می باشد،حداکثر می‌توانید 400 نویسه وارد کنید.',
	'ROLE_DETAILS'					=> 'جزئیات نقش',
	'ROLE_EDIT_SUCCESS'				=> 'نقش با موفقیت ویرایش شد.',
	'ROLE_NAME'						=> 'نام نقش',
	'ROLE_NAME_ALREADY_EXIST'		=> 'نام نقش <strong>%s</strong> در حال حاضر موجود می باشد.',
	'ROLE_NOT_ASSIGNED'				=> 'نقشی هنوز تعیین نشده است.',

	'SELECTED_FORUM_NOT_EXIST'		=> 'انجمن(ها) انتخاب شده موجود نیست.',
	'SELECTED_GROUP_NOT_EXIST'		=> 'گروه(ها) انتخاب شده موجود نیست.',
	'SELECTED_USER_NOT_EXIST'		=> 'کاربر(ها)انتخاب شده موجود نیست.',
	'SELECT_FORUM_SUBFORUM_EXPLAIN'	=> 'انجمن انتخاب شده شامل زیرانجمن‌های آن نیز خواهد بود.',
	'SELECT_ROLE'					=> 'انتخاب نقش…',
	'SELECT_TYPE'					=> 'انتخاب نوع',
	'SET_PERMISSIONS'				=> 'تنظیم سطوح دسترسی',
	'SET_ROLE_PERMISSIONS'			=> 'تنظیم سطوح دسترسی نقش',
	'SET_USERS_PERMISSIONS'			=> 'تنظیم سطوح دسترسی کاربر',
	'SET_USERS_FORUM_PERMISSIONS'	=> 'تنظیم سطوح دسترسی انجمن کاربر',

	'TRACE_DEFAULT'					=> 'به طور پیش‌فرض همه سطوح دسترسی <strong>نه</strong>هستند (انتخاب نشده). بنابراین سطوح دسترسی می توانند تحت تأثیر سایر تنظیمات تغییر یابند.',
	'TRACE_FOR'						=> 'ردیابی برای',

	'TRACE_GLOBAL_SETTING'			=> '%s (سراسری)',
	'TRACE_GROUP_NEVER_TOTAL_NEVER'	=> 'سطوح دسترسی گروه <strong>هرگز</strong> می باشد. مانند مجموع نتایج، نتایج قدیمی حفط می شوند.',
	'TRACE_GROUP_NEVER_TOTAL_NEVER_LOCAL'	=> 'سطوح دسترسی گروه برای این انجمن <strong>هرگز</strong> می باشد. مانند مجموع نتایج، نتایج قدیمی حفط می شوند.',

	'TRACE_GROUP_NEVER_TOTAL_NO'	=> 'سطوح دسترسی گروه <strong>هرگز</strong> می باشد. داده کلی محسوب می شواد زیرا هنوز تنظیم نشده است. (تنظیم به <strong>نه</strong>).',
	'TRACE_GROUP_NEVER_TOTAL_NO_LOCAL'	=> 'سطوح دسترسی گروه برای این انجمن <strong>هرگز</strong>. داده کلی محسوب می شواد زیرا هنوز تنظیم نشده است. (تنظیم به <strong>نه</strong>).',

	'TRACE_GROUP_NEVER_TOTAL_YES'	=> 'سطوح دسترسی گروه <strong>هرگز</strong> می باشد که مجموع <strong>بله</strong> را به این کاربر به <strong>هرگز</strong> تغییر می دهد.',
	'TRACE_GROUP_NEVER_TOTAL_YES_LOCAL'	=> 'سطوح دسترسی گروه برای این انجمن <strong>هرگز</strong> می باشد که مجموع <strong>بله</strong> را به این کاربر به <strong>هرگز</strong> تغییر می دهد.',

	'TRACE_GROUP_NO'				=> 'سطوح <strong>نه</strong> می باشد مجموع داده‌ها نگهداری شد.',
	'TRACE_GROUP_NO_LOCAL'			=> 'سطوح دسترسی برای این گروه در این انجمن <strong>نه</strong> می باشد و مجموع داده‌ها نگهداری شد.',
	'TRACE_GROUP_YES_TOTAL_NEVER'	=> 'سطوح دسترسی این گروه <strong>بله</strong> می باشد ولی در مجموع <strong>هرگز</strong> تغییر نیافت.',
	'TRACE_GROUP_YES_TOTAL_NEVER_LOCAL'	=> 'سطوح دسترسی این گروه برای انجمن <strong>بله</strong> می باشد ولی در مجموع <strong>هرگز</strong> تغییر نیافت.',

	'TRACE_GROUP_YES_TOTAL_NO'		=> 'سطوح دسترسی این گروه <strong>بله</strong> می باشد داده کلی محسوب می شواد زیرا هنوز تنظیم نشده است. (تنظیم به <strong>نه</strong>).',
	'TRACE_GROUP_YES_TOTAL_NO_LOCAL'	=> 'سطوح دسترسی این گروه برای این انجمن <strong>بله</strong> می باشد  داده کلی محسوب می شواد زیرا هنوز تنظیم نشده است. (تنظیم به <strong>نه</strong>).',
	'TRACE_GROUP_YES_TOTAL_YES'		=> 'سطوح دسترسی این گروه <strong>بله</strong> می باشد و محموع سطوحی دسترسی به <strong>بله</strong>تنظیم شده است،بنابر این مجموع داده نگهداری می شود.',
	'TRACE_GROUP_YES_TOTAL_YES_LOCAL'	=> 'سطوح دسترسی این انجمن برای این گروه <strong>بله</strong> می باشد و مجموع سطوح دسترسی <strong>بله</strong> است،بنابراین نتیجه کلی نگهداری شد.',
	'TRACE_PERMISSION'				=> 'ردیابی سطوح دسترسی - %s',
	'TRACE_RESULT'					=> 'نتیجه ردیابی',
	'TRACE_SETTING'					=> 'تنظیمات ردیابی',

	'TRACE_USER_GLOBAL_YES_TOTAL_YES'		=> 'سطوح دسترسی مستقل انجمن به <strong>بله</strong> ارزیابی شده است ولی هنوز مجموع سطوح دسترسی <strong>بله</strong>می باشد،بنابراین نتیجه کلی نگهداری شد. %sردیابی سطوح دسترسی سراسری%s',
	'TRACE_USER_GLOBAL_YES_TOTAL_NEVER'		=> 'سطوح دسترسی مستقل انجمن به <strong>بله</strong> ارزیابی شده است which overwrites the current local result <strong>NEVER</strong>. %sTrace global permission%s',
	'TRACE_USER_GLOBAL_NEVER_TOTAL_KEPT'	=> 'سطوح دسترسی مستقل انجمن به <strong>هرگز</strong> ارزیابی شده است which doesn’t influence the local permission. %sTrace global permission%s',

	'TRACE_USER_FOUNDER'					=> 'کاربر صاحب امتیاز است،بنابراین همیشه سطوح دسترسی مدیریت برای وی <strong>بله</strong> می باشد.',
	'TRACE_USER_KEPT'						=> 'سطوح دسترسی کاربر <strong>نه</strong> می باشد بنابراین مجموع داده قبلی نگهداری شد.',
	'TRACE_USER_KEPT_LOCAL'					=> 'سطوح دسترسی کاربر برای این انجمن <strong>نه</strong> می باشد بنابراین مجموع داده قبلی نگهداری شد.',
	'TRACE_USER_NEVER_TOTAL_NEVER'			=> 'سطوح دسترسی کاربر <strong>هرگز</strong> می باشد و داده مجموع <strong>هرگز</strong> می باشد،پس چیزی تغییر نکرد.',
	'TRACE_USER_NEVER_TOTAL_NEVER_LOCAL'	=> 'سطوح دسترسی کاربر برای این انجمن <strong>هرگز</strong> می باشد و داده مجموع <strong>هرگز</strong> می باشد،پس چیزی تغییر نکرد.',

	'TRACE_USER_NEVER_TOTAL_NO'				=> 'سطوح دسترسی کاربر <strong>هرگز</strong> می باشد که به دلیل نه بودن داده مجموع به آن تغییر کرد.',
	'TRACE_USER_NEVER_TOTAL_NO_LOCAL'		=> 'سطوح دسترسی کاربر برای این انجمن <strong>هرگز</strong> می باشد که به دلیل نه بودن داده مجموع به آن تغییر کرد.',
	'TRACE_USER_NEVER_TOTAL_YES'			=> 'سطوح دسترسی کاربر <strong>هرگز</strong> می باشد و <strong>بله</strong> قبلی تغییر یافت.',
	'TRACE_USER_NEVER_TOTAL_YES_LOCAL'		=> 'سطوح دسترسی کاربر برای این انجمن <strong>هرگز</strong> می باشد و <strong>بله</strong> قبلی تغییر یافت.',

	'TRACE_USER_NO_TOTAL_NO'				=> 'سطوح دسترسی کاربر <strong>نه</strong> می باشد و داده مجموع نه می باشد،بنابراین گزینه پیش‌فرض <strong>هرگز</strong> می باشد.',
	'TRACE_USER_NO_TOTAL_NO_LOCAL'			=> 'سطوح دسترسی کاربر برای این انجمن <strong>نه</strong> می باشد و داده مجموع نه می باشد،بنابراین گزینه پیش‌فرض <strong>هرگز</strong> می باشد.',
	'TRACE_USER_YES_TOTAL_NEVER'			=> 'سطوح دسترسی کاربر <strong>بله</strong> می باشد ولی مجموع <strong>هرگز</strong> تغییر نیافت.',
	'TRACE_USER_YES_TOTAL_NEVER_LOCAL'		=> 'سطوح دسترسی کاربر برای این انجمن <strong>بله</strong> می باشد ولی مجموع <strong>هرگز</strong> تغییر نیافت.',

	'TRACE_USER_YES_TOTAL_NO'				=> 'سطوح دسترسی کاربر <strong>بله</strong> می باشد که به دلیل نه بودن داده مجموع به آن تغییر کرد.',
	'TRACE_USER_YES_TOTAL_NO_LOCAL'			=> 'سطوح دسترسی کاربر برای این انجمن <strong>بله</strong> می باشد که به دلیل نه بودن داده مجموع به آن تغییر کرد.',
	'TRACE_USER_YES_TOTAL_YES'				=> 'سطوح دسترسی کاربر <strong>بله</strong> می باشد و داده مجموع <strong>بله</strong> می باشد،پس چیزی تغییر نکرد.',
	'TRACE_USER_YES_TOTAL_YES_LOCAL'		=> 'سطوح دسترسی کاربر برای این انجمن <strong>بله</strong> می باشد و داده مجموع <strong>بله</strong> می باشد،پس چیزی تغییر نکرد.',

	'TRACE_WHO'								=> 'چه کسی',
	'TRACE_TOTAL'							=> 'مجموع',

	'USERS_NOT_ASSIGNED'			=> 'کاربری برای این نقش تعیین نشده است.',
	'USER_IS_MEMBER_OF_DEFAULT'		=> 'عضو گروه‌های از پیش مشخص شده مقابل می باشد',
	'USER_IS_MEMBER_OF_CUSTOM'		=> 'عضو گروه‌های انتخاب شده مقابل می باشد',

	'VIEW_ASSIGNED_ITEMS'	=> 'مشاهده موارد مشخص شده',
	'VIEW_LOCAL_PERMS'		=> 'سطوح دسترسی محلی',
	'VIEW_GLOBAL_PERMS'		=> 'سطوح دسترسی سراسری',
	'VIEW_PERMISSIONS'		=> 'مشاهده سطوح دسترسی',

	'WRONG_PERMISSION_TYPE'				=> 'نوع سطح دسترسی اشتباه می باشد.',
	'WRONG_PERMISSION_SETTING_FORMAT'	=> 'فرمت سطوح دسترسی اشتباه می باشد، phpBB نمی تواند آن‌ها را پردازش کند.',
));
