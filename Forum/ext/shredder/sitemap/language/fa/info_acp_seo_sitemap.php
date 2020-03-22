<?php

/**
*
* info_acp_seo_sitemap [Persian]
*
* @package phpBB3 SEO Sitemap
* @copyright (c) 2014 www.phpbb-work.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
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
	'SEOMAP'						=> 'سئو سایت مپ',
	'SEOMAP_VERSION'				=> 'نسخه افزونه: %s. مشاهده <a style="font-weight: bold;" href="https://www.phpbb.com/customise/db/extension/seo_sitemap/" onclick="window.open(this.href);return false;">صفحه مربوط به افزونه</a> جهت به روز رسانی های بعدی',
	'SEOMAP_EXPLAIN'				=> 'توجه داشته باشید که استفاده از تنظیمات اختیاری مربوط به دوره ها و فرکانس های تولید در موضوعات ممکن است نتایج مثبتی برای شما داشته باشد ، اما توصیه می شود از تنظیمات پیش فرض استفاده کنید و قبلا از هر گونه تغییر تجزیه و تحلیل نمایید اگر درباره بعضی گزینه ها مطمئن نیستید بهتر است آن را در حالت غیر فعال نگه دارید یا از این لینک کمک بگیرید. <a href="http://www.sitemaps.org/protocol.html#xmlTagDefinitions" onclick="window.open(this.href);return false;">لینک</a> <br /> با قرار دادن مقدار 0 دوره تولید را غیر فعال کنید.',
	'SEOMAP_SETTINGS'				=> 'تنظیمات سئو سایت مپ',
	'SEOMAP_SETTINGS_UPDATED'		=> '<strong>تنظیمات سئو سایت مپ به روز شد</strong>',
	'SEOMAP_SAVED'					=> 'تنظیمات مربوطه با موفقیت به روز رسانی شد.',
	'SEOMAP_EXCLUDED'				=> 'تالارهای ممنوعه',
	'SEOMAP_EXCLUDED_EXPLAIN'		=> 'تالارهای انتخابی و موضوعات درون آن شامل سایت مپ نخواهد شد<br /><strong>توجه:</strong> دسته ها و تالارهای فاقد موضوع به صورت پیش فرض ممنوعه فرض میشود',
	'SEOMAP_CACHE_TIME'				=> 'زمان ساخت',
	'SEOMAP_CACHE_TIME_EXPLAIN'		=> 'جهت کاهش بار روی سرور ساخت نقشه راه در یک زمان معین انجام میشود. بعد از این زمان مجدد ساخته میشود. لطفا مقدار ساعتی که میخواهید هر دوره سایت مپ ساخته شود را وارد نمایید. برای غیر فعال کردن نیز مقدار 0 را وارد کنید.',
	'SEOMAP_URL'					=> 'آدرس سایت مپ شما: <a href="%s" onclick="window.open(this.href);return false;">%s</a>',
	'SEOMAP_URL_COUNT'				=> 'مقدار کل آدرس های در سایت مپ: %s',
	'SEOMAP_URL_LIMIT'				=> 'محدودیت لینک',
	'SEOMAP_URL_LIMIT_EXPLAIN'		=> 'حداکثر مقدار تعداد لینک ها سایت مپ را تعیین کنید که تا 50000 قابل انجام است. اگر این مقدار را کاهش دهید و تعداد لینک های شما بیشتر از این مقدرا باشد سایت مپ شما به صورت چند فایل تولید خواهد شد که اشکالی هم ندارد.',
	'SEOMAP_BATCH_SIZE'				=> 'اندازه پردازش دسته ای',
	'SEOMAP_BATCH_SIZE_EXPLAIN'		=> 'با کاهش اندازه پردازش دسته ای که با تنظیمات مربوط به PHP سرور است سایت مپ بدون مشکل ساخته خواهد شد. توجه داشته باشید زمان لازم برای تولید سایت مپ به طور قابل ملاحظه ای در هر کاهش مقدار اندازه دسته افزایش خواهد یافت.',
	'SEOMAP_PRIORITY_0'				=> 'تغییر اولویت موضوعات',
	'SEOMAP_PRIORITY_1'				=> 'تغییر اولویت موضوعات مهم',
	'SEOMAP_PRIORITY_2'				=> 'تغییر اولویت اطلاعیه ها',
	'SEOMAP_PRIORITY_3'				=> 'تغییر اولویت اطلاعیه های سراسری',
	'SEOMAP_PRIORITY_4'				=> 'تغییر اولویت مقالات موضوعات',
	'SEOMAP_PRIORITY_F'				=> 'تغییر اولویت تالارها',
	'SEOMAP_FREQ_0'					=> 'تغییر دوره موضوعات',
	'SEOMAP_FREQ_1'					=> 'تغییر دوره موضوعات مهم',
	'SEOMAP_FREQ_2'					=> 'تغییر دوره اطلاعیه ها',
	'SEOMAP_FREQ_3'					=> 'تغییر دوره اطلاعیه های سراسری',
	'SEOMAP_FREQ_4'					=> 'تغییر دوره مقالات موضوعات',
	'SEOMAP_FREQ_F'					=> 'تغییر دوره تالارها',
	'SEOMAP_FREQ_NEVER'				=> 'هرگز',
	'SEOMAP_FREQ_YEARLY'			=> 'سالیانه',
	'SEOMAP_FREQ_MONTHLY'			=> 'ماهیانه',
	'SEOMAP_FREQ_WEEKLY'			=> 'هفتگی',
	'SEOMAP_FREQ_DAILY'				=> 'روزانه',
	'SEOMAP_FREQ_HOURLY'			=> 'ساعتی',
	'SEOMAP_FREQ_ALWAYS'			=> 'همیشه',
	'SEOMAP_NO_DATA'				=> 'داده ای در سایت مپ نیست.',
	'SEOMAP_NO_FILE'				=> 'امکان باز کردن فایل نیست.:<br /><strong>%s</strong>',
	'SEOMAP_CANT_WRITE'				=> 'پوشه <strong>%s</strong> موجود یا قابل خواندن نیست لطفا به صورت دستی از FTP این مشکل را رفع کنید.',
	'SEOMAP_COPYRIGHT'				=> 'افزونه سایت مپ',

// Sync section
	'SEOMAP_SYNC_COMPLETE' 			=> 'Synchronisation successfully completed.<br /><br /><a style="font-weight: bold;" href="%s">&laquo; Go back to settings</a>',
	'SEOMAP_SYNC_PROCESS'			=> '<strong>Sync in progress. Do not close this page and do not interrupt script before it finishes all the actions.</strong><br /><br /><strong>%1$s%%</strong> finished. Processed <strong>%2$s</strong> of all posts. Total posts: <strong>%3$s</strong>.',
	'SEOMAP_SYNC_REQ' 				=> 'You should synchronise posts modification dates before using this sitemap. This is needed to generate last modification time of the board pages. <a style="font-weight: bold;" href="%s">Click here to synchronise</a>.',
));
