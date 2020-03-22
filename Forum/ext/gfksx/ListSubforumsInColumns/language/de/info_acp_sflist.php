<?php
/**
*
* List Subforums In Columns extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* German translation by tas2580 (https://tas2580.net)
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
	'SUBFORUMSLIST_TYPE'			=> 'Die Anzahl der Spalten für die Liste der Unterforen',
	'SUBFORUMSLIST_TYPE_EXPLAIN'	=> 'Gib die Anzahl der Spalten ein in die du die Liste der Unterforen in diesem Forum aufteilen möchtest. Gib 0 ein um diese Funktion zu deaktivieren.',
));
