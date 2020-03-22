<?php
/**
*
* acp_usu_install [english]
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2017 www.phpbb-seo.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
/**
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
	'SEO_RELATED_TOPICS' => 'Related topics',
	'SEO_RELATED' => 'Related topics activation',
	'SEO_RELATED_EXPLAIN' => 'Display or not a related topic list in topic pages.<br/><b style="color:red;">Note :</b><br/>With mySQL >=4.1 and the topic table using MyISAM, related topics will be obtained using a FullText index on  the topic title and will be sorted by relevancy. in other cases, an SQL LIKE will be used, and results will be sorted by publication time',
	'SEO_RELATED_CHECK_IGNORE' => 'Ignore words filter',
	'SEO_RELATED_CHECK_IGNORE_EXPLAIN' => 'Apply, or not, the search_ignore_words.php exclusions while searching for related topics',
	'SEO_RELATED_LIMIT' => 'Related topics limit',
	'SEO_RELATED_LIMIT_EXPLAIN' => 'Maximum amount of related topics to display',
	'SEO_RELATED_ALLFORUMS' => 'Search in all forums',
	'SEO_RELATED_ALLFORUMS_EXPLAIN' => 'Search in all forums instead of searching in the current one.<br/><b style="color:red;">Note :</b><br/>Searching in all forums is a bit slower and does not necessarily bring better results',
	// Install
	'INSTALLED' => 'phpBB SEO Related Topics mod installed',
	'ALREADY_INSTALLED' => 'phpBB SEO Related Topics mod is already installed',
	'FULLTEXT_INSTALLED' => 'MySQL FullText Index installed',
	'FULLTEXT_NOT_INSTALLED' => 'MySQL FullText Index is not available on this server, SQL LIKE will be used instead',
	'INSTALLATION' => 'phpBB SEO Related Topics mod installation',
	'INSTALLATION_START' => '&rArr; <a href="%1$s" ><b>Proceed with installing the mod</b></a><br/><br/>&rArr; <a href="%2$s" ><b>Retry to set the FullText Index</b></a> (MySQL >= 4.1 using Myisam for topic table only)<br/><br/>&rArr; <a href="%3$s" ><b>Proceed with un-installing the mod</b></a>',
	// un-install
	'UNINSTALLED' => 'phpBB SEO Related Topics mod un-installed',
	'ALREADY_UNINSTALLED' => 'phpBB SEO Related Topics mod is already un-installed',
	'UNINSTALLATION' => 'phpBB SEO Related Topics mod un-installation',
	// SQL message
	'SQL_REQUIRED' => 'The configured db user does not have enough privileges to alter tables, you need to run this query manually in order to add or drop the MySQL FullText index :<br/>%1$s',
	// Security
	'SEO_LOGIN'		=> 'The board requires you to be registered and logged in to view this page.',
	'SEO_LOGIN_ADMIN'	=> 'The board requires you to be logged in as admin to view this page.<br/>Your session has been destroyed for security purposes.',
	'SEO_LOGIN_FOUNDER'	=> 'The board requires you to be logged in as the founder to view this page.',
	'SEO_LOGIN_SESSION'	=> 'Session Check failed.<br/>The Settings were not altered.<br/>Your session has been destroyed for security purposes.',
));
