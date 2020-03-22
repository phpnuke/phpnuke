<?php
/**
*
* Share On extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Vinny <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
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
	// ACP
	'SO_ACP'		=> 'Share On',
	'SO_TITLE'		=> 'Share On Settings',
	'SO_EXPLAIN'	=> 'Configure in which sites you want to share your topics or posts.',
	'SHARE_ON_MOD'	=> 'Share On',
	'SO_CONFIG'		=> 'Settings',
	'SO_SAVED'		=> 'Changes Saved.',
	'SO_SELECT'		=> 'Share On',
	'SO_STATUS'		=> 'Enable',
	'SO_FACEBOOK'	=> 'Facebook',
	'SO_TWITTER'	=> 'Twitter',
	'SO_DIGG'		=> 'Digg',
	'SO_REDDIT'		=> 'Reddit',
	'SO_DELICIOUS' 	=> 'Delicious',
	'SO_VK'			=> 'VK',
	'SO_TUENTI'		=> 'Tuenti',
	'SO_TUMBLR'		=> 'Tumblr',
	'SO_GOOGLE'		=> 'Google+',
	'SO_WHATSAPP'	=> 'Whatsapp',
	'SO_POCKET'		=> 'Pocket',

	// Share Type
	'SO_TYPE'			=> 'Share Type',
	'SO_TYPE_EXPLAIN'	=> 'You can choose to share the <strong>topic</strong> link or share every single <strong>post</strong> from the topic.',
));
