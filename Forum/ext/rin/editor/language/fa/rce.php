<?php

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
	// Front
	'RCE_RESTORE'					=> 'بازگردانی',
	'RCE_MORE'						=> 'بیشتر',
	'RCE_INSERT_A_VIDEO'			=> 'وارد کردن ویدیو',
	'RCE_ENTER_URL'					=> 'آدرس:',
	'RCE_ENTER_THE_IMAGE_URL'		=> 'آدرس تصویر:',
    'RCE DESCRIPTION OPTIONAL'	=> 'توضیحات ( اختیاری)',
    'RCE_INSERT'					=> 'وارد کردن',
	'RCE_VIDEO_URL'					=> 'آدرس ویدیو یا ای دی',
	'RCE_QUICK_QUOTE'				=> 'متن نقل قول',

	// ACP
	'ACP_RCE_TITLE'			=>	'ویرایشگر',
	'ACP_RCE_SETTING'		=>	'تنظیمات مربوط به ویرایشگر',
	'RCE_CONFIG_UPDATE'		=>	'تنظیمات ویرایشگر به روز رسانی شد.',
	'RCE_SETTING_SAVED'		=>	'تنظیمات با موفقیت به روزرسانی شد.',
	'RCE_LANGUAGE_TITLE'	=>	'زبان ویرایشگر',
	'RCE_LANGUAGE_DESC'		=>	'زبان ویرایشگر را انتخاب کنید.',
	'RCE_BBCODE_TITLE'		=>	'انتخاب BBCode',
	'RCE_BBCODE_DESC'		=>	'BBcode مورد نظر برای دسترسی کاربران را مشخص کنید',
	'RCE_PBBCODE_TITLE'		=>	'دسترسی برای ',
	'RCE_PBBCODE_DESC'		=>	'گروهی که میخواهید از این BBcode استفاده کنید را انتخاب کنید.',
	'RCE_MOBMS_TITLE'		=>	'حالت گوشی همراه',
	'RCE_MOBMS_DESC'		=>	'اگر میخواهید ویرایشگر در گوشی همراه نیز فعال باشد.',
	'RCE_ENBQUICK_TITLE'	=>	'نمایش ویرایشگر در پاسخ سریع',
	'RCE_ENBQUICK_DESC'		=>	'با انتخاب گزینه بله ویرایشگر در پاسخ سریع به نمایش در می آید.',
	'RCE_SCSMILEY_TITLE'	=>	'حالت شکلک ها در ویرایشگر',
	'RCE_SCSMILEY_DESC'		=>	'اگر میخواهید حالت شکلک ها در ویرایشگر تغییر یابد این گزینه را فعال کنید.',
	'RCE_AUTOSAVE_TITLE'	=>	'ذخیره خودکار',
	'RCE_AUTOSAVE_DESC'		=>	'فعال کردن حالت ذخیره خودکار متن ها',
	'RCE_AUTOSAVEMSG_TITLE' =>	'اعلان ذخیره خودکار',
	'RCE_AUTOSAVEMSG_DESC'	=>	'با انتخاب گزینه بله این قابلیت فعال میشود.',
	'RCE_QUICKQUOTE_TITLE'	=>	'حالت نقل قول سریع',
	'RCE_QUICKQUOTE_DESC'	=>	'با انتخاب گزینه نقل قول سریع میتوانیدن از ین قابلیت استفاده کنید.',
	'RCE_SUPSMENT_TITLE'	=>	'فعال سازی برای افزونه ارجاع کاربر',
	'RCE_SUPSMENT_DESC'		=>	'با فعال سازی این گزینه و فعال بودن افزونه ارجاع کاربر این قابلیت فعلا خواهد شد.<br /><strong>توجه.</strong> افزونه ارجاع کاربر. https://www.phpbb.com/customise/db/extension/simple_mentions/',
	'RCE_HEIGHT_TITLE'		=>	'ارتفاع ویرایشگر',
	'RCE_HEIGHT_DESC'		=>	'مقدار ارتفاع جعبه متن بر حسب پیکسل',
	'RCE_MAX_HEIGHT_TITLE'	=>	'حداکثرمقدار جعبه متن',
	'RCE_MAX_HEIGHT_DESC'	=>	'حداکثر مقدار ارتفاع جعبه متن بر حسب پیسکل',
	'RCE_SUPEXT_TITLE'		=>	'فعال سازی پشتیبانی از دکمه های خارجی',
	'RCE_SUPEXT_DESC'		=>	'با فعال سازی این گزینه ویرایشگر از دکمه های خارجی پشتیبانی میکند.',
	'RCE_DESNOPOP_TITLE'	=>	'غیر فعال شدن پاپ آپ و توضیحات برای دکمه ها',
	'RCE_DESNOPOP_DESC'		=>	'با فعال سازی این گزینه پاپ آپ و توضیحات برای دکمه ها غیر فعال خواهد شد',
	'RCE_PARTIAL_TITLE'		=>	'حالت تبدیل',
	'RCE_PARTIAL_DESC'		=>	'با فعال سازی این گزینه حالت تبدیل فعال میشود',
	'RCE_SELTXT_TITLE'		=>	'جایگزین نشدن متن ها',
	'RCE_SELTXT_DESC'		=>	'با فعال سازی این گزینه متن های انتخابی در ویرایشگر را میتوان درون BBcode ها قرار داد.',
	'RCE_RMV_ACP_COLOR_TITLE'	=>	'حذف رنگ ویرایشگر',
	'RCE_RMV_ACP_COLOR_DESC'	=>	'با فعال سازی این گزینه رنگ ویرایشگر حذف میشود.',
	'RCE_CACHE_TITLE'		=>	'نهانگاه',
	'RCE_CACHE_DESC'		=>	'تنظیم زمان پاکسازی نهانگاه و عدد 0 به معنای غیر فعال شدن و حداکثر مقدار 86400 برای آن',
	'RCE_IMGUR_TITLE'		=>	'Imgur',
	'RCE_IMGUR_DESC'		=>	'تنظیم API سایت imgur برای آپلود تصاویر در ویرایشگر.<br /><strong>Ps.</strong> گرفتن کد در سایت  https://imgur.com/register/api_anon ',
	'RCE_STYLE_TITLE'		=>	'انتخاب قالب',
	'RCE_STYLE_DESC'		=>	'انتخاب قالب ویرایشگر',
	'RCE_SKIN_TITLE'		=>	'تنظیمات پوسته ',
	'RCE_SKIN_DESC'			=>	'نام پوسته <br /><strong>محل قرارگیری پوسته:</strong> root/ext/rin/editor/styles/all/template/js/skins/',
	'RCE_TXTA_TITLE'		=>	'رنگ جعبه ویرایشگر',
	'RCE_TXTA_DESC'			=>	'با فعال سازی آن رنگ جعبه متن به سیاه تغییر می یابد',
));
