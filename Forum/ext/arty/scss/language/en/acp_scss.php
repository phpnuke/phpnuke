<?php
/**
 *
 * @package SCSS Compiler
 * @author Arty (Vjacheslav Trushkin) cyberalien@gmail.com
 * @copyright (c) 2015 artodia.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* DO NOT CHANGE
*/
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
	// Module name
	'ACP_SASS_MODULE'			=> 'Compile Themes',
	'ACP_RECOMPILE_THEMES'		=> 'Re-compile Themes',

	// Page title
	'ACP_SCSS_COMPILER'			=> 'Sass Compiler',

	// Error messages
	'ACP_SCSS_NO_STYLES'		=> 'Nothing to re-compile. You do not have any styles that use .scss stylesheets.',
));
