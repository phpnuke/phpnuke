<?php
/**
*
* mod_thanks [English]
*
* @package language
* @version $Id: info_acp_thanks.php 131 2011-01-10 10:02:51Палыч $
* @copyright (c) 2008 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
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
	'acl_f_thanks' 						=> array('lang' => 'می توانید از پست ها تشکر کنید', 'cat' => 'misc'),
	'acl_m_thanks' 						=> array('lang' => 'می توانید لیست تشکر ها را پاکسازی کنید', 'cat' => 'misc'),
	'acl_u_viewthanks' 					=> array('lang' => 'می توانید لیست تشکر ها را ببینید', 'cat' => 'misc'),
	'acl_u_viewtoplist'					=> array('lang' => 'می توانید لیست برترین ها را ببینید', 'cat' => 'misc'),
	'ACP_DELTHANKS'						=> 'تشکر های ثبت شده ی حذف شده',
	'ACP_DELPOST'						=> 'بدون پست (حذف شده)',
	'ACP_DELUPOST'						=> 'بدون کاربر (حذف شده)',	
	'ACP_POSTS'							=> 'مجموع پست ها',
	'ACP_POSTSEND'						=> 'پست های تشکر شاده باقی می ماند',
	'ACP_POSTSTHANKS'					=> 'مجموع پست های تشکر شده',
	'ACP_THANKS'						=> 'تشکر ها از پست ها',
	'ACP_THANKS_MOD_VER'				=> 'نسخه افزونه : ',
	'ACP_THANKS_TRUNCATE'				=> 'پاکسازی لیست تشکر ها',
	'ACP_ALLTHANKS'						=> 'به اکانت تشکرها منتقل شد',
	'ACP_THANKSEND'						=> 'برای انتقال به اکانت تشکر ها باقی می ماند',
	'ACP_THANKS_REPUT'					=> 'امتیاز گزینه ها',
	'ACP_THANKS_REPUT_SETTINGS'			=> 'امتیاز گزینه ها',
	'ACP_THANKS_REPUT_SETTINGS_EXPLAIN'	=> 'در این بخش می توانید سیستم امتیاز دهی به پست ها،موضوعات و انجمن ها را مدیریت کنید <br /> عنوانی (پست،موضوع،انجمن) که بیشترین تشکر ها را دریافت کرده باشد،امتیاز 100% را دارا خواهد بود.',
	'ACP_THANKS_SETTINGS'				=> 'تنظیمات تشکر',
	'ACP_THANKS_SETTINGS_EXPLAIN'		=> 'در این بخش می توانید تابع های تشکر ها را مدیریت کنید',
	'ACP_THANKS_REFRESH'				=> 'بروزرسانی شمارنده ها',
	'ACP_USERS'							=> 'مجموع کاربران (شامل روبات ها و مهمان ها)',
	'ACP_UPDATETHANKS'					=> 'رکورد ها به روز شد',
	'ACP_USERSEND'						=> 'باقی ماندن کاربران تشکر کرده',
	'ACP_USERSTHANKS'					=> 'مجموع کاربرانی که تشکر کرده اند',

	'GRAPHIC_BLOCK_BACK'				=> 'styles/prosilver/theme/images/reput_block_back.gif',
	'GRAPHIC_BLOCK_RED'					=> 'styles/prosilver/theme/images/reput_block_red.gif',
	'GRAPHIC_DEFAULT'					=> 'تصاویر',
	'GRAPHIC_OPTIONS'					=> 'گزینه های گرافیک',
	'GRAPHIC_STAR_BACK'					=> 'styles/prosilver/theme/images/reput_star_back.gif',
	'GRAPHIC_STAR_BLUE'					=> 'styles/prosilver/theme/images/reput_star_blue.gif',
	'GRAPHIC_STAR_GOLD'					=> 'styles/prosilver/theme/images/reput_star_gold.gif',
	
	'IMG_THANKPOSTS'					=> 'برای تشکر از پیغام',
	'IMG_REMOVETHANKS'					=> 'لغو تشکرها',

	'REFRESH'							=> 'بارگذاری مجدد',
	'REMOVE_THANKS'						=> 'حذف تشکر ها',
	'REMOVE_THANKS_EXPLAIN'				=> 'اگر فعال شود،کاربران می توانند تشکر های خود را لغو کنند',
	
	'STEPR'								=> ' - اجرا, مرحله %s',
	'THANKS_GLOBAL_POST'				=> 'تشکر در اطلاعیه های سراسری',
	'THANKS_FORUM_REPUT_VIEW_COLUMN'	=> 'نمایش امتیازها در انجمن',
	'THANKS_FORUM_REPUT_VIEW_COLUMN_EXPLAIN' => 'با فعال سازی این گزینه ، امتیاز موضوعات در قالب یک ستون در انجمن ها قابل مشاهده خواهد بود.',	'THANKS_GLOBAL_POST_EXPLAIN'		=> 'اگر فعال شود میتوان اطلاعیه های سراسری را نیز تشکر کرد.',
	'THANKS_COUNTERS_VIEW'				=> 'شمارنده تشکرها',
	'THANKS_COUNTERS_VIEW_EXPLAIN'		=> 'اگر فعال شود در بلوک نویسنده تعداد دفعات تشکر شده/کرده نمایش داده خواهد شد',
	'THANKS_FORUM_REPUT_VIEW'			=> 'نمایش امتیازات انجمن',
	'THANKS_FORUM_REPUT_VIEW_EXPLAIN'	=> 'اگر فعال شود،امتیازات انجمن در لیست انجمن ها نمایش داده خواهد شد',
	'THANKS_INFO_PAGE'					=> 'پیغام اطلاعات',
	'THANKS_INFO_PAGE_EXPLAIN'			=> 'اگر فعال شود بعد از ارسال/لغو تشکر،پیغام اطلاعات نمایش داده خواهد شد',
	'THANKS_NUMBER'						=> 'تعداد دفعات تشکر شده از کاربر در پروفایل',
	'THANKS_NUMBER_EXPLAIN'				=> 'تعداد نمایش اعداد در پروفایل <br /> <strong> توجه داشته باشید که اگر بیش تر از 250 وارد شود،کاهش سرعت محسوس خواهد بود </strong>',
	'THANKS_NOTICE_ON'					=> 'اطلاع رسانی فعال است',
	'THANKS_NOTICE_ON_EXPLAIN'			=> 'اگر فعال باشد، امکان اطلاع رسانی به کاربران مقدور خواهد بود و قابل تنظیم از پروفایل کاربر است.',
	'THANKS_NUMBER'						=> 'تعداد تشکرهای که در لیست تشکرهای پروفایل نمایش داده شود.',
	'THANKS_NUMBER_EXPLAIN'				=> 'حداکثر تعداد تشکرهایی که در پروفایل کاربر نمایش داده شود. <br /> <strong> توجه داشته باشید که اگر این داده بالای 250 باشد کاهش سرعت محسوس خواهد بود </strong>',
	'THANKS_NUMBER_DIGITS'				=> 'تعداد دهگان های محل های امتیازات',
	'THANKS_NUMBER_DIGITS_EXPLAIN'		=> 'تعداد دهگان ها را برای امتیاز دهی تأیین کنید',
	'THANKS_NUMBER_ROW_REPUT'			=> 'تعداد سطور صفحه افراد برتر',
	'THANKS_NUMBER_ROW_REPUT_EXPLAIN'	=> 'تعداد سطور صفحه افراد برتر را در این بخش وارد کنید',
	'THANKS_NUMBER_POST'				=> 'تعدا تشکر ها در پست',
	'THANKS_NUMBER_POST_EXPLAIN'		=> 'مشخص کنید که چه تعداد از تشکر ها در پست نمایش داده شود <br /> <strong> لطفا توجه داشته باشید که اگر بیش تر از 250 وارد شود،کاهش سرعت محسوس خواهد بود </strong>',
	'THANKS_ONLY_FIRST_POST'			=> 'فقط اولین پست موضوع',
	'THANKS_ONLY_FIRST_POST_EXPLAIN'	=> 'اگر فعال شود،تشکرها فقط در پست اول موضوع نمایش داده خواهند شد',
	'THANKS_POST_REPUT_VIEW'			=> 'نمایش امتیازات پست ها',
	'THANKS_POST_REPUT_VIEW_EXPLAIN'	=> 'اگر فعال شود،امتیاز پست ها در حین بازدید از موضوع نمایش داده خواهند شد',
	'THANKS_POSTLIST_VIEW'				=> 'لیست تشکر ها در پست',
	'THANKS_POSTLIST_VIEW_EXPLAIN'		=> 'اگر فعال باشد،لیست افراد تشکر کرده نمایش داده خواهد شد. <br/> توجه داشته باشید که این گزینه هنگامی مؤثر واقع می شود که سطوح دسترسی تشکر کردن در آن انجمن فعال باشد.',
	'THANKS_PROFILELIST_VIEW'			=> 'لیست تشکر ها در پروفایل',
	'THANKS_PROFILELIST_VIEW_EXPLAIN'	=> 'اگر این گزینه فعال باشد،اطلاعات تشکر ها مانند تعداد دفعات تشکر شده و پست های تشکر شده از کاربر در پروفایل کاربر نمایش داده خواهند شد',
	'THANKS_REFRESH'					=> 'بروزرسانی شمارنده تشکر ها',
	'THANKS_REFRESH_EXPLAIN'			=> 'در این بخش می توانید شمارنده تشکر ها را بعد از حذف دسته جمعی پست ها/کاربران/موضوعات،  پیوستن،جدا کردن موضوعات، سراسرس کردن موضوعی و یا جایگزینی نویسنده پست و .... بروزرسانی کنید.این کار ممکن است کمی طول بکشد.<br /><strong>مهم : برای عملکرد صحیح بروزرسانی شمارنده در نسخه MYSQL 4.1 به بعد ضروری است.</strong>',
	'THANKS_REFRESH_MSG'				=> 'فرآیند ارتقا ممکن است کمی طول بکشد،تمام تشکرهای نامعتبر حذف خواهند شد. <br /> این عملیات غیر قابل برگشت است',
	'THANKS_REFRESHED_MSG'				=> 'شمارنده ها بروزرسانی شدند',
	'THANKS_REPUT_GRAPHIC'				=> 'نمایش گرافیکی امتیازات',
	'THANKS_REPUT_GRAPHIC_EXPLAIN'		=> 'اگر فعال شود داده های امتیازات با استفاده تصاویر زیر نمایش داده خواهند شد',
	'THANKS_REPUT_HEIGHT'				=> 'ارتفاع گرافیک ها',
	'THANKS_REPUT_HEIGHT_EXPLAIN'		=> 'ارتفاع اسلایدر را مشخص کنید <br /> <strong> توجه! برای عملکر صحیح ارتفاع باید نمایش دهنده ارتفاع تصویر مقابل باشد </strong>',
	'THANKS_REPUT_IMAGE'				=> 'تصویر اصلی برای اسلایدر',
	'THANKS_REPUT_IMAGE_DEFAULT'		=> '<strong>نمونه تصاویر گرافیکی</strong>',
	'THANKS_REPUT_IMAGE_DEFAULT_EXPLAIN' => 'در این بخش می توانید تصاویر و مسیر آنها در قالب prosilver را مشاهده کنید. ابعاد تصویر 15x15 پیکسل می باشند. <br />می توانید تصاویر خود را رسم کنید، پس زمینه و پیش زمینه -  می توانید از فایل reput_star_.psd در پوشه contrib استفاده کنید. <strong>طول و عرض باید در مقیاسی صحیحی از قالب نمونه باشد</strong>',
	'THANKS_REPUT_IMAGE_EXPLAIN'		=> 'مسیری از روت phpBB برای دریافت مقیاس گرافیکی',
	'THANKS_REPUT_IMAGE_NOEXIST'		=> 'فایل تصویر اصلی مقیاس گرافیکی موجود نیست',
	'THANKS_REPUT_IMAGE_BACK'			=> 'تصویر پس زمینه اسلایدر',
	'THANKS_REPUT_IMAGE_BACK_EXPLAIN'	=> 'محل تصویر پس زمینه در روت phpBB ،برای استفاده در مقیاس تصویری',
	'THANKS_REPUT_IMAGE_BACK_NOEXIST'	=> 'فایل پس زمینه برای مقیاس گرافیکی یافت نشد',
	'THANKS_REPUT_LEVEL'				=> 'تعداد تصاویر در مقیاس گرافیکی',
	'THANKS_REPUT_LEVEL_EXPLAIN'		=> 'بیش ترین تعداد تصاویر متناظر با  100% داده مقیاس گرافیکی امتیازات',
	'THANKS_TIME_VIEW'					=> 'زمان تشکر',
	'THANKS_TIME_VIEW_EXPLAIN'			=> 'اگر فعال شود،زمان تشکر نمایش داده خواهد شد',
	'THANKS_TOP_NUMBER'					=> 'تعداد کاربران در لیست برترین ها',
	'THANKS_TOP_NUMBER_EXPLAIN'			=> 'تعداد کاربران مورد قبول برای نمایش در لیست برترین ها را وارد کنید',
	'THANKS_TOPIC_REPUT_VIEW'			=> 'امتیاز دهی به موضوعات',
	'THANKS_TOPIC_REPUT_VIEW_EXPLAIN'	=> 'اگر فعال شود،امتیاز موضوعات را در حین مشاهده انجمن،نمایش خواد داد',
	'THANKS_TOPIC_REPUT_VIEW_EXPLAIN'	=> 'اگرفعال شود، رتبه موضوع درحین مشاهده از انجمن نمایش داده خواهد شد.',
	'THANKS_TOPIC_REPUT_VIEW_COLUMN'	=> 'نمایش رتبه بندی موضوع در ستون',
	'THANKS_TOPIC_REPUT_VIEW_COLUMN_EXPLAIN' => 'اگر فعال شود، رتبه موضوع درحین مشاهده انجمن نمایش داده خواهد شد. <br /> توجه داشته باشید که این گزینه فقط در قالب های وابسته به Prosilver عمل می کند.',
	'TRUNCATE'							=> 'پاکسازی',	
	'TRUNCATE_THANKS'					=> 'پاکسازی لیست تشکر ها',
	'TRUNCATE_THANKS_EXPLAIN'			=> 'این فرآیند شمارنده همه تشکر ها را حذف خواهد کرد<br /> این عملیات غیر قابل بازگشت می باشد',
	'TRUNCATE_THANKS_MSG'				=> 'شمارنده تشکر ها پاکسازی شدند',
	'ALLOW_THANKS_PM_ON'				=> 'اگر کسی از پست من تشکر کند، با پیغام خصوصی به من اطلاع بده',
	'ALLOW_THANKS_EMAIL_ON'				=> 'اگر کسی از پست من تشکر کند، با ایمیل به من اطلاع بده',
));
?>