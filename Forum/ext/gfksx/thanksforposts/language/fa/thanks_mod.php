<?php
/**
*
* thanks_mod[persian]
*
* @package language
* @version $Id: thanks.php,v 133 2011-01-10 10:02:51Палыч $
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
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'CLEAR_LIST_THANKS'			=> 'پاکسازی لیست تشکر ها',
	'CLEAR_LIST_THANKS_CONFIRM'	=> 'آیا از پاکسازی لیست تشکر ها مطمئن هستید؟',
	'CLEAR_LIST_THANKS_GIVE'	=> 'لیست تشکر های صورت گرفته توسط کاربران پاکسازی شد.',
	'CLEAR_LIST_THANKS_POST'	=> 'لیست تشکر ها در پیغام ها پاکسازی شد.',
	'CLEAR_LIST_THANKS_RECEIVE'	=> 'لیست تشکر های دریافت شده از کاربران پاکسازی شد.',

	'DISABLE_REMOVE_THANKS'		=> 'حذف تشکرها توسط مدیر غیرفعال شده است',
	
	'GIVEN'						=> 'تشکر کرده',
	'GLOBAL_INCORRECT_THANKS'	=> 'نمی توانید از اطلاعیه های سراسری که انجمن ریشه ای ندارند تشکر کنید',
	'GRATITUDES'				=> 'تشکر ها',
	
	'INCORRECT_THANKS'			=> 'تشکر نامعتبر',
	
	'JUMP_TO_FORUM'				=> 'پرش به انجمن',
	'JUMP_TO_TOPIC'				=> 'پرش به موضوع',

	'FOR_MESSAGE'				=> ' برای پست',
	'FURTHER_THANKS'     	    => ' و یک کاربر',
	'FURTHER_THANKS_PL'         => ' و %d کاربر',
	
	'NO_VIEW_USERS_THANKS'		=> 'اجازه مشاهده لیست تشکر ها را ندارید',

	'NOTIFICATION_THANKS_GIVE'	=> array(
		1 => '<strong>دریافت تشکر</strong> از طرف %1$s در پست:',
		2 => '<strong>دریافت تشکر</strong> از طرف %1$s در پست:',
	),
	'NOTIFICATION_THANKS_REMOVE'=> array(
		1 => '<strong>حذف تشکر</strong> از طرف %1$s در پست',
		2 => '<strong>حذف تشکر</strong> از طرف %1$s در پست',
	),
	'NOTIFICATION_TYPE_THANKS_GIVE'		=> 'کسی از پست شما تشکر کرد.',
	'NOTIFICATION_TYPE_THANKS_REMOVE'	=> 'کسی تشکر پست شما را حذف کرد.',

	'RECEIVED'					=> 'تشکر شده',
	'REMOVE_THANKS'				=> 'حذف تشکر شما : ',
	'REMOVE_THANKS_SHORT'		=> 'حذف تشکر شما : ',
	'REMOVE_THANKS_CONFIRM'		=> 'آیا از حذف تشکرتان مطمئن هستید ؟',
	'REPUT'						=> 'امتیاز',
	'REPUT_TOPLIST'				=> 'افراد برتر',
	'RETING_LOGIN_EXPLAIN'		=> 'اجازه مشاهده لیست افراد برتر را ندارید.',
	'RATING_NO_VIEW_TOPLIST'	=> 'اجازه مشاهده لیست افراد برتر را ندارید.',
	'RATING_VIEW_TOPLIST_NO'	=> 'لیست افراد برتر خالی است و یا توسط مدیر غیرفعال شده است',
	'RATING_FORUM'				=> 'انجمن',
	'RATING_POST'				=> 'پست',
	'RATING_TOP_FORUM'			=> 'امتیاز انجمن ها',
	'RATING_TOP_POST'			=> 'امتیاز پست ها',
	'RATING_TOP_TOPIC'			=> 'امتیاز موضوعات',	
	'RATING_TOPIC'				=> 'موضوع',
	'RETURN_POST'				=> 'بازگشت',

	'THANK'						=> 'بار',
	'THANK_FROM'				=> 'از',
	'THANK_TEXT_1'				=> 'برای این پست از ',
	'THANK_TEXT_2'				=> ' تشکر شده است : ',
	'THANK_TEXT_2PL'			=> ' تشکر شده است : %d',
	'THANK_POST'				=> 'برای تشکر از پست کاربر : ',
	'THANK_POST_SHORT'			=> 'برای تشکر از پست کاربر : ',
	'THANKS'					=> array(
		1	=> '%d دفعه',
		2	=> '%d دفعه',
	),



	'THANKS_BACK'				=> 'بازگشت',
	'THANKS_INFO_GIVE'			=> 'تشکر شما از پست ثبت شد.',
	'THANKS_INFO_REMOVE'		=> 'تشکر شما از پست حذف شد.',
	'THANKS_LIST'				=> 'مشاهده/بستن لیست',
	'THANKS_PM_MES_GIVE'		=> 'تشکر از پست',
	'THANKS_PM_MES_REMOVE'		=> 'حذف تشکر',
	'THANKS_PM_SUBJECT_GIVE'	=> 'تشکر از پست',
	'THANKS_PM_SUBJECT_REMOVE'	=> 'حذف تشکر',
	'THANKS_USER'				=> 'لیست تشکرها',




	'THANKS_INSTALLED'			=> 'تشکر از پست',
	'THANKS_INSTALLED_EXPLAIN'  => '<strong>CAUTION!<br />You are strongly advised to only run this installation after following the instruction on code changes to the files (or perform the installation using AutoMod)! <br />It is also strongly recommended to select Yes to Display Full Results (below)!</strong>',
	'THANKS_CUSTOM0_FUNCTION'	=> 'Update values for the _thanks table',
	'THANKS_CUSTOM1_FUNCTION'	=> 'Check remove module',
	'THANKS_CUSTOM2_FUNCTION'	=> 'Check refrech cache',
	'TOPLIST'					=> 'برترین پست ها',
));
