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
	'ACP_SASS_MODULE'			=> 'تدوین گر قالب',
	'ACP_RECOMPILE_THEMES'		=> 'تدوین قالب',
	'ACP_SCSS_COMPILER'			=> 'تدوین گر قالب',

	// Styles list data
	'COMPILE_THEME'				=> 'تدوین قالب',
	'RECOMPILE_THEME'			=> 'تدوین مجدد قالب',
	'SCSS_STATUS_OK'			=> 'قالب شما به روز است',
	'SCSS_STATUS_MISSING_CSS'	=> 'فایل stylesheet.css نیست ، نیاز به تدوین مجدد است',
	'SCSS_STATUS_RECOMPILE'		=> 'ظاهرا فایل های Scss تغییر کرده است. نیاز به تدوین است. ',

	// Compilation notices
	'SCSS_MESSAGE_TITLE'		=> 'تدوین قالب: ',
	'SCSS_COMPILED'				=> 'قالب با موفقیت تدوین شد',

	// Error messages
	'ACP_SCSS_MISSING_CURL'		=> 'CURL support is not available in your PHP installation. This module cannot work without CURL support.',
	'ACP_SCSS_MISSING_JSON'		=> 'JSON support is not available in your PHP installation. This module cannot work without JSON support.',
	'STYLESHEET_ISNOT_WRITABLE'	=> 'File theme/stylesheet.css is not writable. Check file permissions.',
	'MISSING_STYLESHEET_SCSS'	=> 'Cannot read file theme/stylesheet.scss',
	'NOTHING_TO_COMPILE'		=> 'Nothing to compile.',
	'SERVER_RETURNED_EMPTY_RESULT'	=> 'Sass compiler returned empty data.',
	'SERVER_RETURNED_INVALID_RESULT'	=> 'Sass compiler returned invalid data.',
));
