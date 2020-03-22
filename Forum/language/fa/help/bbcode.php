<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * phpBB 3.2.X Project - Persian Translation
 * Translators: PHP-BB.IR Group Meis@M Nobari
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

$lang = array_merge($lang, array(
	'HELP_BBCODE_BLOCK_IMAGES'	=> 'نمایش تصاویر در پست ها',
	'HELP_BBCODE_BLOCK_INTRO'	=> 'معرفی',
	'HELP_BBCODE_BLOCK_LINKS'	=> 'ایجاد لینک',
	'HELP_BBCODE_BLOCK_LISTS'	=> 'ساخت لیست',
	'HELP_BBCODE_BLOCK_OTHERS'	=> 'سایر موارد',
	'HELP_BBCODE_BLOCK_QUOTES'	=> 'نقل قول و خروجی عرض ثابت متن',
	'HELP_BBCODE_BLOCK_TEXT'	=> 'قالب متن',

	'HELP_BBCODE_IMAGES_ATTACHMENT_ANSWER'	=>  'پیوشت ها را میتوانید در هر نقطه از پستتان بیافزایید برای این کار از تگ :<strong>[attachment=][/attachment]</strong> استفاده کنید، اگر مدیر تالار اجازه افزودن پیوست را داده باشد و شما این سطح دسترسی را داشته باشید،میتوانید به پستتان پیوستی بیافزایید. در پستتان منوی کشویی ایجاد میشود که پیوست درون آن قرار میگیرد.',
	'HELP_BBCODE_IMAGES_ATTACHMENT_QUESTION'	=> 'اضافه کردن پیوست به پست',
	'HELP_BBCODE_IMAGES_BASIC_ANSWER'	=> 'phpbb BBCode شامل تگی است که میتوانید به وسیله آن به پستتان تصویر بیافزایید. در هنگام استفاده از این تگ باید دو نکته مهم را رعایت کنید : کاربران مابل لیستند تا در یک پست تصاویر زیادی را ببینند و ثانیا تصاویر انتخاب شده باید بر روی اینترنت و سروری باشند نه در کامپیوتر شخصی شما. برای نمایش تصویر باید لینک تصویر را بین دو تگ <strong>[img][/img]</strong> قرار دهید. برای مثال :<br /><br /><strong>[img]</strong>https://www.phpbb.com/theme/images/logos/blue/160x52.png<strong>[/img]</strong><br /><br />همان گونه که در نکات بالا ذکر کردیم، میتوانید این تگ را با تگ های دیگر ترکیب دهید،برای مثال : <strong>[url][/url]</strong> tag if you wish, e.g.<br /><br /><strong>[url=https://www.phpbb.com/][img]</strong>https://www.phpbb.com/theme/images/logos/blue/160x52.png<strong>[/img][/url]</strong><br /><br />به صورت:<br /><br /><a href="https://www.phpbb.com/"><img src="https://www.phpbb.com/theme/images/logos/blue/160x52.png" alt="" /></a> نمایش داده خواهد شد.',

	'HELP_BBCODE_IMAGES_BASIC_QUESTION'	=> 'اضافه کردن تصویر به پست',

	'HELP_BBCODE_INTRO_BBCODE_ANSWER'	=> 'BBCode برنامه ای برای HTML است، برای استفاده از این ابزار،مدیر تالا باید BBCode را فعال کرده باشد. BBCode مانند تگ های HTML است با این تفاوت که به جای <> باید از قلاب ([]) استفاده کنید.در پست ها میتوانید BBCode غیر فعال کنید.',
	'HELP_BBCODE_INTRO_BBCODE_QUESTION'	=> 'BBCode چيست ؟',

	'HELP_BBCODE_LINKS_BASIC_ANSWER'	=> 'phpbb BBCode از چند روش برای لینک دادن پشتیبانی میکند.(Uniform Resource Indicators) که به اختصار URL نامیده میشود<ul><li>اولین روش تگ <strong>[url=][/url]</strong> است، هر چه بعد از = وارد کنید به عنوان آدرس لینک استفاده خواهد شد. برای مثال برای لینک دادن به phpbb.com<br /><br /><strong>[url=https://www.phpbb.com/]</strong>از phpbb بازدید کنید<strong>[/url]</strong> استفاده کنید.<br /><br />این تگ لینک مقابل را تولید خواهد کرد <a href="https://www.phpbb.com/">از phpbb بازدید کنید</a> توجه داشته باشید که این لینک ممکن است در همان صفحه و یا صفحه جدید باز شود که به مرورگری که استفاده میکنید بستگی دارد.</li><li>اگر میخواهید که آدرس لینک مانند نام لینک نمایش داده شود اینگونه عمل کنید :<br /><br /><strong>[url]</strong>https://www.phpbb.com/<strong>[/url]</strong><br /><br />این لینک مقابل را تولید خواهد کرد : <a href="https://www.phpbb.com/">https://www.phpbb.com/</a></li><li>با این وجود phpbb یک ویژگی دیگر به نام <i>Magic Links</i> دارد، این ویژگی تمامی آدرس های اینترنتی را که به درستی وارد شوند به طور خودکار به لینک تبدیل میکند حتی اگر https://. نداشته باشند. برای مثال اگر www.phpbb.com را وارد کنید به <a href="https://www.phpbb.com/">www.phpbb.com</a>تبدیل خواهد شد.</li><li>همان ویژگی برای ایمیل ها نیز وجود دارد، میتوانید آدرس ایمیلی را دقیقا وارد کنید برای مثال اگر این ایمیل را وارد کنید :<br /><br /><strong>[email]</strong>no.one@domain.adr<strong>[/email]</strong><br /><br /> به صورت <a href="mailto:no.one@domain.adr">no.one@domain.adr</a> نمایش داده خواهد شد و یا ایمیل no.one@domain.adr را در پیغامتان وارد کنید و این ایمیل به طور خودکار به لینک ایمیل تبدیل خواهد شد</li></ul>مانند سایر تگ ها BBCode میتوانید تگ لینک را هم با سایر لینک ها ترکیب دهید. <strong>[img][/img]</strong> (مشاهده ورودی جدید), <strong>[b][/b]</strong>،و یا غیره. در درج تگ ها باید دقت لازم را داشته باشید تا با دقت تگ را باز کرده و آن را ببندید.برای مثال :<br /><br /><strong>[url=https://www.phpbb.com/][img]</strong>https://www.phpbb.com/theme/images/logos/blue/160x52.png<strong>[/url][/img]</strong><br /><br /><span style="text-decoration: underline">درست نیست</span> و ممکن است به حذف شدن پستتان بیانجامد پس دقت لازم را خرج دهید.',
	'HELP_BBCODE_LINKS_BASIC_QUESTION'	=> 'لینک به سایتی دیگر',

	'HELP_BBCODE_LISTS_ORDERER_ANSWER'	=> 'BBCode از دو نوع لیست پشتیبانی میکند،لیست مرتب (عددی)و لیست نامرتب (غیر عددی) آنها در اصل شبیه معادل لیست ها در  HTML هستند. لیست نامرتب(غیر عددی) بدون درج عدد ماده ها را پشت سر هم ردیف میکند. برای ایجاد لیست نامرتب (غیر عددی) از تگ <strong>[list][/list]</strong> استفاده کنید و هر ماده را در <strong>[*]</strong> مشخص کنید.  برای مثال،برای لیست کردن رنگ های مورد علاقه تان از :<br /><br /><strong>[list]</strong><br /><strong>[*]</strong>قرمز<br /><strong>[*]</strong>آبی<br /><strong>[*]</strong>زرد<br /><strong>[/list]</strong><br /><br />استفاده کنید، این کدها به صورت مقابل نمایش داده خواهند شد :<li>قرمز</li><li>آبی</li><li>زرد</li><br />متناوبا شما میتوانید از لیست فرمت های قطور نیز استفاده کنید<strong>[list=disc][/list]</strong>, <strong>[list=circle][/list]</strong>, یا <strong>[list=square][/list]</strong>.',

	'HELP_BBCODE_LISTS_ORDERER_QUESTION'	=> 'لیست نامرتب (بدون عدد)',
	'HELP_BBCODE_LISTS_UNORDERER_ANSWER'	=> 'نوع دوم لیست،لیست مرتب(عددی) است که به شما این امکان را میدهد که ترتیب هر ماده را مشخص کنید. برای ایجاد لیست مرتب(عددی) <strong>[list=1][/list]</strong> باید چند گزینه عدد دار و یا حرف دار را تعریف کنید. <strong>[list=a][/list]</strong> برای لیست های ترتیبی عددی. مانند لیست های نامرتب، ماده ها توسط <strong>[*]</strong> از هم جدا میشوند برای مثال :<br /><strong>[list=1]</strong><br /><strong>[*]</strong>رفتن به فروشگاه ها<br /><strong>[*]</strong>خریدن کامپیوتر جدید<br /><strong>[*]</strong>فخش دادن به کامپیوتر وقتی که از کار افتاد<br /><strong>[/list]</strong><br /><br />به صورت مقابل نمایش داده خواهد شد :<li>رفتن به فروشگاه ها</li><li>خریدن کامپیوتر جدید</li><li>فخش دادن به کامپیوتر وقتی که از کار افتاد</li>همچنین برای لیست های حروفی :<br /><strong>[list=a]</strong><br /><strong>[*]</strong>اولین جواب ممکن<br /><strong>[*]</strong>دومین جواب ممکن<br /><strong>[*]</strong>سومین جواب ممکن<br /><strong>[/list]</strong><br /><br />به صورت :<li>Tاولین جواب ممکن</li><li>دومین جواب ممکن</li><li>سومین جواب ممکن</li> نمایش داده میشود',
	'HELP_BBCODE_LISTS_UNORDERER_QUESTION'	=> 'ایجاد لیست مرتب (عددی)',

	'HELP_BBCODE_OTHERS_CUSTOM_ANSWER'	=> 'اگر مدیر این تالار باشید و سطح دسترسی مورد نظر را داشته باشید میتوانید تگهای دیگری را بیافزایید.',
	'HELP_BBCODE_OTHERS_CUSTOM_QUESTION'	=> 'آیا میتوانم تگ های دیگری را استفاده کنم ؟',

	'HELP_BBCODE_QUOTES_CODE_ANSWER'	=> 'اگر میخواهید تگ کد اضافه کنید یا هر جیزی که عرض ثابت دارد مثلا,. نوعی از فونت برای کد <strong>[code][/code]</strong> تگ  های, e.g.<br /><br /><strong>[code]</strong>echo &quot;بعضی کد ها&quot;;<strong>[/code]</strong><br /><br />تمام قالب ها استفاده می شود <strong>[code][/code]</strong> زمانی که بعدا شما آنها را مشاهده میکنید',	'HELP_BBCODE_QUOTES_CODE_QUESTION'	=> 'خروجی داده ها با عرض ثابت است',
	'HELP_BBCODE_QUOTES_TEXT_ANSWER'	=> 'برای نقل قول دو روش وجود دارد، با مرجع و بدون مرجع.<ul><li>برای نقل قول باید متن نقل قول را در مابین تگ <strong>[quote=&quot;&quot;][/quote]</strong>قرار دهید. این روش نقل قول به شما این امکان را میدهد تا متن نقل قول را به شخصی رجوع دهید. برای مثال اگر بخواهید متنی را که آقای احمدی نوشته است،نقل قول کنید باید اینگونه عمل کنید :<br /><br /><strong>[quote=&quot;MR.mohammadi&quot;]</strong>TEXT<strong>[/quote]</strong><br /><br />این عمل به طور خودکار نام آقای محمدی را قبل از متنی که وی نوشته است اضافه خواهد کرد. به یاد داشته باشید که <strong>باید</strong> علامت&quot;&quot; در اطراف نام قرار دهید، آنها انتخابی نیستند.(در این مثال نام احمدی فقط به منظور ذکر مثال آمده است.)</li><li>در روش دوم نام نویسنده متن ذکر نمیشود. برای اینکار متن را در بین تگ های <strong>[quote][/quote]</strong> قرار دهید. وقتی که پیغام نمایش داده شود،خواهید دید که متن درون جعبه نقل قول قرار گرفته است.</li></ul>',
	'HELP_BBCODE_QUOTES_TEXT_QUESTION'	=> 'نقل قول نوشته در پاسخ ها',

	'HELP_BBCODE_TEXT_BASIC_ANSWER'	=> 'BBCode حاوی تگ هایی است که میتوانید به آسانی با آنها فرمت متن را تغییر دهید. بدین وسیله میتوانید این تغییرات را اعمال کنید : <ul><li>برای بزرگ نمایی نوشته ای (bold) آن را درون<strong>[b][/b]</strong> قرار دهید, برای مثال <br /><br /><strong>[b]</strong>سلام<strong>[/b]</strong><br /><br />به صورت<strong>سلام</strong> نمایش داده خواهد شد. </li><li>برای خط زیرین (underline) از <strong>[u][/u]</strong> استفاده کنید, برای مثال :<br /><br /><strong>[u]</strong>صبح بخیر<strong>[/u]</strong><br /><br />به ضورت <span style="text-decoration: underline">صبح بخیر</span> نمایش داده خواهد شد </li><li>برای یک وری کردن نوشته (italic) از <strong>[i][/i]</strong> استفاده کنید, برای مثال <br /><br />در <strong>[i]</strong>عالی است !<strong>[/i]</strong><br /><br />به صورت<i>عالی است!</i></li></ul> نمایش داده خواهد شد.',
	'HELP_BBCODE_TEXT_BASIC_QUESTION'	=> 'نحوه Bold، italic و underline کردن نوشته.',
	'HELP_BBCODE_TEXT_COLOR_ANSWER'	=>  'برای تغییر رنگ و اندازه نوشته میتوانید از تگ های مقابل استفاده کنید،توجه داشته باشید که این تنظیمات ممکن است در مرورگر های مختلف، متفاوت نمایش داده شود : <ul><li>تغییر رنگ نوشته با قرار دادن آن در <strong>[color=][/color]</strong>صورت میگیرد. میتوانید نام رنگی را مشخص کنید (به انگلیسی وارد شود : red,blue,pink,yellow و ...) یا از کد های رنگ استفاده کنید، مانند #FFFFFF, #000000. برای مثال،برای تولید رنگ قرمز اینگونه عمل کنید :<br /><br /><strong>[color=red]</strong>سلام!<strong>[/color]</strong><br /><br />or<br /><br /><strong>[color=#FF0000]</strong>سلام!<strong>[/color]</strong><br /><br />هر دو به صورت <span style="color:red">سلام!</span> نمایش داده خواه شد.</li><li>تغییر اندازه نوشته نیز همانگونه صورت میگیرد <strong>[size=][/size]</strong>. این تگ به قالب مورد استفاده وابسته است ولی بهترین روش استفاده از اعداد است که اندازه نوشته را در درصد نمایش میدهد، 20(بسیار کوچک) یا 200 (بسیار بزرگ). برای مثال :<br /><br /><strong>[size=30]</strong>کوچک<strong>[/size]</strong><br /><br />که به صورت <span style="font-size:30%;">کوچک</span> نمایش داده خواهد شد<br /><br />در حالی که :<br /><br /><strong>[size=200]</strong>بزرگ!<strong>[/size]</strong><br /><br />به صورت <span style="font-size:200%;">بزرگ!</span> نمایش داده خواهد شد</li></ul>',
	'HELP_BBCODE_TEXT_COLOR_QUESTION'	=> 'نحوه تغییر رنگ و اندازه نوشته',
	'HELP_BBCODE_TEXT_COMBINE_ANSWER'	=> 'بله میتوانید، برای مثال :<br /><br /><strong>[size=200][color=red][b]</strong>به من نگاه کن!<strong>[/b][/color][/size]</strong><br /><br />به صورت <span style="color:red;font-size:200%;"><strong>به من نگاه کن!</strong></span> نمایش داده خواهد شد<br /><br />پیشنهاد میکنیم که در نوشته ها زیاد از این روش استفاده نکنید. به خاطر داشته باشید که استفاده از این تگ ها کاملا به خود نویسنده بستگی دارد،در بستن تگ ها دقت کنید. برای مثال این نادرست است :<br /><br /><strong>[b][u]</strong>این نادرست است<strong>[/b][/u]</strong>',
	'HELP_BBCODE_TEXT_COMBINE_QUESTION'	=> 'آیا میتوانم تگ ها را باهم ترکیب دهم ؟',
));
