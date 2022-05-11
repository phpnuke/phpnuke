<?php
/**
 *
 * @package       QuickReply Reloaded
 * @copyright (c) 2014 - 2017 Tatiana5 and LavIgor
 * @license       http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
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
	'ACP_QUICKREPLY'          => 'پاسخ سریع',
	'ACP_QUICKREPLY_EXPLAIN'  => 'تنظیمات پاسخ سریع',
	//
	'ACL_A_QUICKREPLY'        => 'بتواند تنظیمات پاسخ سریع پیشرفته را تغییر دهد',
	'ACL_F_QR_CHANGE_SUBJECT' => 'بتواند عنوان پاسخ سریع را ویرایش کند',
	'ACL_F_QR_FULL_QUOTE'     => 'بتواند نقل قول کامل در موضوعات داشته باشد<br />',
));
