<?php
/**
*
* Board Announcements extension for the phpBB Forum Software package.
*
* @copyright (c) 2014 phpBB Limited <https://www.phpbb.com>
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
	'BOARD_ANNOUNCEMENTS_SETTINGS'			=> 'Board announcements settings',
	'BOARD_ANNOUNCEMENTS_SETTINGS_EXPLAIN'	=> 'Here you can manage and create a board announcement that will be displayed on each page of your board.',

	'BOARD_ANNOUNCEMENTS_ENABLE'			=> 'Display this board announcement',
	'BOARD_ANNOUNCEMENTS_USERS'				=> 'Who can view this board announcement',
	'BOARD_ANNOUNCEMENTS_DISMISS'			=> 'Allow users to dismiss this board announcement',

	'BOARD_ANNOUNCEMENTS_EVERYONE'			=> 'Everyone',

	'BOARD_ANNOUNCEMENTS_BGCOLOR'			=> 'Board announcement background color',
	'BOARD_ANNOUNCEMENTS_BGCOLOR_EXPLAIN'	=> 'You can change the background color of the announcement using a hex code (e.g: FFFF80). Leave this field blank to use the default color.',

	'BOARD_ANNOUNCEMENTS_EXPIRY'			=> 'Board announcement expiration date',
	'BOARD_ANNOUNCEMENTS_EXPIRY_EXPLAIN'	=> 'Set the date the announcement will expire and become disabled. Leave this field blank if you do not want the announcement to expire.',
	'BOARD_ANNOUNCEMENTS_EXPIRY_INVALID'	=> 'The expiration date was invalid or has already expired.',
	'BOARD_ANNOUNCEMENTS_EXPIRY_FORMAT'		=> 'YYYY-MM-DD HH:MM',

	'BOARD_ANNOUNCEMENTS_TEXT'				=> 'Board announcement message',
	'BOARD_ANNOUNCEMENTS_PREVIEW'			=> 'Board announcement - Preview',

	'BOARD_ANNOUNCEMENTS_UPDATED'			=> 'Board announcement has been updated.',
));
