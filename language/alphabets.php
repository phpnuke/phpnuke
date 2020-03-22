<?php
/**************************************************************************/
/* PHP-NUKE: Advanced Content Management System                           */
/* ============================================                           */
/* Farsi Project BY MashhadTeam.com                                                                       */
/* This is the language module with all the system messages               */
/*                                                                        */
/* If you made a translation, please go to the site and send to me        */
/* the translated file. Please keep the original text order by modules,   */
/* and just one message per line, also double check your translation!     */
/*                                                                        */
/* You need to change the second quoted phrase, not the capital one!      */
/*                                                                        */
/* If you need to use double quotes (") remember to add a backslash (\),  */
/* so your entry will look like: This is \"double quoted\" text.          */
/* And, if you use HTML code, please double check it.                     */
/**************************************************************************/
define("_CHARSET","UTF-8");
$alphabet = array (
	"english" => array ("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"),
	"english small" => array ("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"),
	"farsi" => array ("ا","آ","ب","پ","ت","ث","ج","چ","ح","خ","د","ذ","ر","ز","ژ","س","ش","ص","ض","ط","ظ","ع","غ","ف","ق","ک","گ","ل","م","ن","و","ه","ی"),
	"arabic" => array ("ا","آ","ب","ت","ث","ج","ح","خ","د","ذ","ر","ز","س","ش","ص","ض","ط","ظ","ع","غ","ف","ق","ك","ل","م","ن","و","ه","ي")
);


$nuke_configs['g_month_name'] = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
$nuke_configs['g_week_name'] = array("", "Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday");


$nuke_configs['j_month_name'] = array("", "فروردين ماه", "ارديبهشت ماه", "خرداد ماه", "تير ماه", "مرداد ماه", "شهريور ماه", "مهر ماه", "آبان ماه", "آذر ماه", "دي ماه", "بهمن ماه", "اسفند ماه");
$nuke_configs['j_week_name'] = array("", "شنبه", "يكشنبه", "دوشنبه", "سه شنبه", "چهارشنبه", "پنجشنبه", "جمعه");

$nuke_configs['A_month_name'] = array("محرم", "صفر", "ربيع‌الاول", "ربيع‌الثاني", "جمادي‌الاول", "جمادي‌الثاني", "رجب", "شعبان", "رمضان", "شوال", "ذي‌القعده", "ذي‌الحجه");
$nuke_configs['A_week_name'] = array("", "الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "جمعة", "السبت", "الأحد");

$nuke_configs['g_days_in_month'] = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
$nuke_configs['j_days_in_month'] = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
?>