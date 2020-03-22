<?php
/**
*
* info_acp_usu [English]
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
	'ACP_CAT_PHPBB_SEO' => 'phpBB SEO',
	'ACP_MOD_REWRITE' => 'URL Rewriting settings',
	'ACP_PHPBB_SEO_CLASS' => 'phpBB SEO Class settings',
	'ACP_FORUM_URL' => 'Forum URL Management',
	'ACP_REWRITE_CONF' => 'Server Config',
	'ACP_SEO_EXTENDED' => 'Extended config',
	'ACP_SYNC_URL' => 'URL Synchronization',
	'ACP_PREMOD_UPDATE' => '<h1>Release announcement</h1>
	<p>This update does only concern the premod, not the phpBB core.</p>
	<p>A new version of the phpBB SEO premod is thus available : %1$s<br/>Make sure you visit<a href="%2$s" title="The release thread"><b>the release thread</b></a> and update your installation.</p>',
	'SEO_LOG_INSTALL_PHPBB_SEO' => '<strong>phpBB SEO mod rewrite installed (v%s)</strong>',
	'SEO_LOG_INSTALL_PHPBB_SEO_FAIL' => '<strong>phpBB SEO mod rewrite install attempt failed</strong><br/>%s',
	'SEO_LOG_UNINSTALL_PHPBB_SEO' => '<strong>phpBB SEO mod rewrite uninstalled (v%s)</strong>',
	'SEO_LOG_UNINSTALL_PHPBB_SEO_FAIL' => '<strong>phpBB SEO mod rewrite uninstall attempts failed</strong><br/>%s',
	'SEO_LOG_CONFIG_SETTINGS' => '<strong>Altered phpBB SEO Class settings</strong>',
	'SEO_LOG_CONFIG_FORUM_URL' => '<strong>Altered Forum URLs</strong>',
	'SEO_LOG_CONFIG_HTACCESS' => '<strong>Generated new .htaccess</strong>',
	'SEO_LOG_CONFIG_EXTENDED' => '<strong>Altered phpBB SEO extended config</strong>',
));
