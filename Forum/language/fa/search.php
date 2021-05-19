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
	'ALL_AVAILABLE'			=> 'همه قابل استفاده ها',
	'ALL_RESULTS'			=> 'همه نتایج',

	'DISPLAY_RESULTS'		=> 'نمایش نتایج به صورت',

	'FOUND_SEARCH_MATCHES'		=> array(
		1	=> 'نتایج هماهنگ با عبارت جست‌وجو شده : %d',
		2	=> 'نتایج هماهنگ با عبارت جست‌وجو شده : %d',
	),
	'FOUND_MORE_SEARCH_MATCHES'		=> array(
		1	=> 'نتایج هماهنگ پیدا شده بیشتر از %d',
		2	=> 'نتایج هماهنگ پیدا شده بیشتر از %d',
	),

	'GLOBAL'				=> 'اطلاعیه سراسری',

	'IGNORED_TERMS'			=> 'نادیده گرفته شده',
	'IGNORED_TERMS_EXPLAIN'	=> 'این کلمات در نتیجه جست‌وجو نادیده  گرفته شد، زیرا این کلمات بسیار عمومی هستند : <strong>%s</strong>.',

	'JUMP_TO_POST'			=> 'پرش به پست',

	'LOGIN_EXPLAIN_EGOSEARCH'	=> 'برای مشاهده پست‌های خودتان باید ثبت نام کرده و وارد شوید.',
	'LOGIN_EXPLAIN_UNREADSEARCH'=> 'برای مشاهده پست‌های خوانده نشده باید ثبت نام کرده و وارد شوید.',
	'LOGIN_EXPLAIN_NEWPOSTS'	=> 'برای مشاهده پست‌های جدید از آخرین بازدیدتان باید ثبت نام کرده و وارد شوید.',

	'MAX_NUM_SEARCH_KEYWORDS_REFINE'	=> array(
		1	=> 'برای جست‌وجو کلمات زیادی را تعیین کرده اید.حداکثر %1$d کلمه می‌توانید وارد کنید.',
		2	=> 'برای جست‌وجو کلمات زیادی را تعیین کرده اید.حداکثر %1$d کلمه می‌توانید وارد کنید.',
	),

	'NO_KEYWORDS'			=> 'برای جست‌وجو باید حداقل یک کلمه را انتخاب کنید. هر کلمه باید حداقل دارای %s نویسه باشد و نویسه‌های بیشتر از %s مورد قبول نخواهد بود.',
	'NO_RECENT_SEARCHES'	=> 'اخیرا جست‌وجو ای صورت نگرفته است.',
	'NO_SEARCH'				=> 'متاسفیم، نمی‌توانید از بخش جست‌وجو استفاده کنید.',
	'NO_SEARCH_RESULTS'		=> 'نتیجه مناسبی یافت نشد.',
	'NO_SEARCH_LOAD'		=> 'متاسفیم، در حال حاضر امکان جست‌وجو برای شما وجود ندارد. بارگزاری سرور بسیار سنگین شده است ، لطفا مجدد تلاش نمایید.',
	'NO_SEARCH_TIME'		=> array(
		1	=> 'در حال حاضر نمی‌توانید جست‌وجویی را انجام دهید،لطفا بعد از چند دقیقه دوباره امتحان کنید.',
		2	=> 'در حال حاضر نمی‌توانید جست‌وجویی را انجام دهید،لطفا بعد از چند دقیقه دوباره امتحان کنید.',
	),
	'NO_SEARCH_UNREADS'		=> 'متاسفیم، جست‌وجوی پست‌های خوانده نشده در این تالار غیرفعال شده است.',
	'WORD_IN_NO_POST'		=> 'پستی یافت نشد زیرا کلمه <strong>%s</strong> در هیچ پستی وجود ندارد.',
	'WORDS_IN_NO_POST'		=> 'پستی یافت نشد زیرا کلمات<strong>%s</strong> در هیچ پستی وجود ندارند.',

	'POST_CHARACTERS'		=> 'نویسه از پست (این تعداد نویسه موجود در ابتدای پست بررسی خواهد شد)',
	'PHRASE_SEARCH_DISABLED'	=> 'جست‌وجو بر اساس عبارت دقیق در این انجمن پشتیبانی نمی شود.',

	'RECENT_SEARCHES'		=> 'جست‌وجو های اخیر',
	'RESULT_DAYS'			=> 'نتایج را به گذشته محدود کنید',
	'RESULT_SORT'			=> 'مرتب کردن نتایج بر اساس',
	'RETURN_FIRST'			=> 'اول برگرد',
	'RETURN_FIRST_EXPLAIN'	=> 'برای نمایش کامل پست 0 را وارد کنید.',
	'GO_TO_SEARCH_ADV'		=> 'رفتن به جست‌وجوی پیشرفته',

	'SEARCHED_FOR'				=> 'عبارت استفاده شده در جست‌وجو',
	'SEARCHED_TOPIC'			=> 'موضوع جست‌وجو شده',
	'SEARCHED_QUERY'			=> 'صف جست‌وجو شده',
	'SEARCH_ALL_TERMS'			=> 'جست‌وجوی همه مطالب و یا موارد مشخص شده',
	'SEARCH_ANY_TERMS'			=> 'جست‌وجوی هرگونه مطلب',
	'SEARCH_AUTHOR'				=> 'جست‌وجو بر اساس نویسنده',
	'SEARCH_AUTHOR_EXPLAIN'		=> 'اگر کلمه وارد شده قسمتی از نتیجه احتمالی است، لطفا * را وارد کنید.',
	'SEARCH_FIRST_POST'			=> 'فقط اولین پست موضوع',
	'SEARCH_FORUMS'				=> 'جست‌وجو در انجمن‌ها',
	'SEARCH_FORUMS_EXPLAIN'		=> 'انجمن و یا انجمن‌هایی را که میخواهید جست‌وجو کنید را وارد کنید. تا زمانی که جست‌وجوی زیر انجمن‌ها را غیرفعال نکرده باشید، زیرانجمن‌ها به طور خودکار جست‌وجو خواهند شد',
	'SEARCH_IN_RESULTS'			=> 'جست‌وجوی این نتایج',
	'SEARCH_KEYWORDS_EXPLAIN'	=> 'علامت <strong>+</strong> در مقابل کلمه ای قرار دهید که باید یافت شود و علامت <strong>-</strong> را در مقابل کلمه ای قرار دهید که باید یافت نشود. اگر میخواهید یکی از کلمات وارد شده یافت شود، آن کلمات را درون براکت قرار دهید و با استفاده از علامت <strong>|</strong> از یکدیگر جدا کنید. اگر کلمه وارد شده قسمتی از نتیجه احتمالی باشد آن را با * مشخص کنید.',
	'SEARCH_MSG_ONLY'			=> 'فقط متن پیام',
	'SEARCH_OPTIONS'			=> 'گزینه‌های جست‌وجو',
	'SEARCH_QUERY'				=> 'کلمه مورد جست‌وجو',
	'SEARCH_SUBFORUMS'			=> 'جست‌وجوی زیرانجمن‌ها',
	'SEARCH_TITLE_MSG'			=> 'ارسال عناوین و متن پیام‌ها',
	'SEARCH_TITLE_ONLY'			=> 'فقط عنوان موضوع',
	'SEARCH_WITHIN'				=> 'جست‌وجو درون',
	'SORT_ASCENDING'			=> 'صعودی',
	'SORT_AUTHOR'				=> 'نویسنده',
	'SORT_DESCENDING'			=> 'نزولی',
	'SORT_FORUM'				=> 'انجمن',
	'SORT_POST_SUBJECT'			=> 'عنوان پست',
	'SORT_TIME'					=> 'تاریخ پست',
	'SPHINX_SEARCH_FAILED'		=> 'جست‌وجو نا موفق: %s',
	'SPHINX_SEARCH_FAILED_LOG'	=> 'متاسفیم، امکان جست‌وجو وجود ندارد.جهت اطلاعات بیشتر درباره این موضوع باید وارد حساب خود شوید.',

	'TOO_FEW_AUTHOR_CHARS'	=> array(
		1	=> 'حداقل باید %d نویسه از نام نویسنده را مشخص کنید.',
		2	=> 'حداقل باید %d نویسه از نام نویسنده را مشخص کنید.',
	),
));
