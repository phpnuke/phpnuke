<?php
/**
*
* acp_usu [English]
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
	// ACP Main CAT
	'ACP_CAT_PHPBB_SEO'	=> 'phpBB SEO',
	'ACP_MOD_REWRITE'	=> 'URL Rewriting settings',
	// ACP phpBB seo class
	'ACP_PHPBB_SEO_CLASS'	=> 'phpBB SEO Class settings',
	'ACP_PHPBB_SEO_CLASS_EXPLAIN'	=> 'Here you can set up various options of the phpBB SEO %1$s mod (%2$s).<br/>The various default settings such as the delimiters and suffixes still must be set up in <b>phpBB/ext/phpbbseo/usu/customise.php</b>, since changing these implies an .htaccess update and most likely appropriate redirections.%3$s',
	'ACP_PHPBB_SEO_VERSION' => 'Version',
	'ACP_PHPBB_SEO_MODE' => 'Mode',
	'ACP_SEO_SUPPORT_FORUM' => 'Support Forum',
	// ACP forum URLs
	'ACP_FORUM_URL'	=> 'Forum URL Management',
	'ACP_FORUM_URL_EXPLAIN'		=> 'Here you can see what’s in the cache file containing the forum title to inject in their URLs.<br/>Forum in green colors are cached, the one in red are not yet.<br/><br/><b style="color:red">Please Note :</b><br/><em><b>any-title-fxx/</b> will always be properly redirected with the Zero Duplicate but it won’t be the case if you edit <b>any-title/</b> to <b>something-else/</b>.<br/> In such case, <b>any-title/</b> will for now be treated as a forum that does not exist if you do not set appropriate redirections.</em>',
	'ACP_NO_FORUM_URL'	=> '<b>Forum URL Management disabled<b><br/>The forum URL management is only available in advanced and Mixed mode and when Forum URL caching is activated.<br/>Forum URLs already configured will stay active in advanced and Mixed mode.',
	// ACP .htaccess
	'ACP_REWRITE_CONF'	=> 'Server Config',
	'ACP_REWRITE_CONF_EXPLAIN'	=> 'This tool will help you out building your server config.<br/>The version proposed below is based on your phpBB/ext/phpbbseo/usu/customise.php settings.<br/>You can edit the $seo_ext and $seo_static values before you install the server config to get personalized URLs.<br/>You can for example choose to use .htm instead of .html, "message" instead of "post" "mysite-team" instead of "the-team" and so on.<br/>If you edit these while they were already indexed in SE, you’ll need personalized redirections.<br/>The default settings are not bad at all, you can skip this step without worries if you prefer,<br/>though it’s the best time to do it. Doing it after a while will require some personalized redirections.',
	'SEO_SERVER_CONF_RBASE'	=> 'Config Scope',
	'SEO_SERVER_CONF_RBASE_EXPLAIN' => 'The server config can be limited to phpBB’s physical directory. It is usually desired to limit teh server config to where it is useful, but it can be handy to group everything in the domain’s root.',
	'SEO_SERVER_CONF_SLASH'	=> 'RegEx Right Slash',
	'SEO_SERVER_CONF_SLASH_EXPLAIN'	=> 'Depending on the specific host you are using, you might have to get rid of or add the slash ("/") at the beginning of the right part of each RewriteRules. This particular slash is for example used by default when .htaccess are located at the root level and it’s the contrary for when phpBB would be installed in a sub-folder with an .htaccess in the same folder.<br/>Default settings should generally work, but if it’s not the case, try a server config with this option.',
	'SEO_SERVER_CONF_WSLASH'	=> 'RegEx Left Slash',
	'SEO_SERVER_CONF_WSLASH_EXPLAIN'	=> 'Depending on the specific host you are using, you might have to add a slash ("/") at the beginning of the left part of each RewriteRules. This particular slash ("/") is for example not used by default with Apache but is with Ngix.<br/>Default settings should generally work, but if it’s not the case, try a server config with this option.',
	'SEO_MORE_OPTION'	=> 'More Options',
	'SEO_MORE_OPTION_EXPLAIN' => 'If the first suggested .htaccess does not work.<br/>First make sure mod_rewrite is activated on your server.<br/>Then, make sure you uploaded it in the right folder, and that another one is not perturbing.<br/>If not enough, hit the "more option" button.',
	'SEO_SERVER_CONF_SAVE' => 'Save the server config',
	'SEO_SERVER_CONF_SAVE_EXPLAIN' => 'If checked, server config files will be generated upon submit in the phpbb_seo/cache/ folder. They are ready to go with your last settings, just pick the proper file/config for you server and put it in the right place.',
	'SEO_HTACCESS_ROOT_MSG'	=> 'Once you are ready, you can select the .htaccess code, and paste it in an .htaccess file or use the "Save .htaccess" option below.<br/> This .htaccess is meant to be used in the domain’s root folder, which in your case is where %1$s leads to in your FTP.<br/><br/>You can generate an .htaccess meant to be used in the eventual phpBB sub-directory using the "htaccess location" option below.',
	'SEO_HTACCESS_FOLDER_MSG' => 'Once you are ready, you can select the .htaccess code, and paste it in an .htaccess file or use the "Save .htaccess" option below.<br/> This .htaccess is meant to be used in the folder where phpBB is installed, which in your case is where %1$s leads to in your FTP.',
	'SEO_SERVER_CONF_CAPTION' => 'Caption',
	'SEO_SERVER_CONF_CAPTION_COMMENT' => 'Comments',
	'SEO_SERVER_CONF_CAPTION_STATIC' => 'Static parts, editable in phpBB/ext/phpbbseo/usu/customise.php',
	'SEO_SERVER_CONF_CAPTION_DELIM' => 'Delimiters, editable in phpBB/ext/phpbbseo/usu/customise.php',
	'SEO_SERVER_CONF_CAPTION_SUFFIX' => 'Suffixes, editable in phpBB/ext/phpbbseo/usu/customise.php',
	'SEO_SERVER_CONF_CAPTION_SLASH' => 'Optional slashes',
	'SEO_SLASH_DEFAULT'	=> 'Default',
	'SEO_SLASH_ALT'		=> 'Alternate',
	'SEO_MOD_TYPE_ER'	=> 'The mod rewrite type is not set up properly in phpBB/ext/phpbbseo/usu/core.php.',
	'SEO_SHOW'		=> 'Show',
	'SEO_HIDE'		=> 'Hide',
	'SEO_SELECT_ALL'	=> 'Select all',
	'SEO_APACHE_CONF' => 'Apache config',
	//Ngix
	'SEO_NGIX_CONF' => 'Ngix config',
	'SEO_NGIX_CONF_EXPLAIN' => 'Copy and paste this code into your webserver configuration file and restart Ngix.',
	// ACP extended
	'ACP_SEO_EXTENDED_EXPLAIN' => 'phpBB SEO mods extended settings.',
	'SEO_EXTERNAL_LINKS' => 'External links',
	'SEO_EXTERNAL_LINKS_EXPLAIN' => 'Open, or not, external links in a new browser window / tab',
	'SEO_EXTERNAL_SUBDOMAIN' => 'Sub-domain links',
	'SEO_EXTERNAL_SUBDOMAIN_EXPLAIN' => 'Treat, or not, links to eventual sub-domains of your forum’s domain as internal links to open in the same window',
	'SEO_EXTERNAL_CLASSES' => 'External by css class',
	'SEO_EXTERNAL_CLASSES_EXPLAIN' => 'here you can define some css classes that will activate the new window feature on links using it. Comma separated list of class names, example: postlink,external',
	// Titles
	'SEO_PAGE_TITLES' => 'Page titles',
	'SEO_APPEND_SITENAME' => 'Append site name to page titles',
	'SEO_APPEND_SITENAME_EXPLAIN' => 'Append, or not, site name to page titles.<br/><b style="color:red;">Warning :</b><br/>This option requires that you properly edited all your overall_header.html for the Optimal titles mod, site name could be repeated in page titles otherwise',
	// Meta
	'SEO_META' => 'Meta tags',
	'SEO_META_TITLE' => 'Meta title',
	'SEO_META_TITLE_EXPLAIN' => 'Default Meta title, used on page not defining a page title. Inactivates the meta title tag if empty',
	'SEO_META_DESC' => 'Meta description',
	'SEO_META_DESC_EXPLAIN' => 'Default Meta description, used on page not defining a meta description',
	'SEO_META_DESC_LIMIT' => 'Meta description limit',
	'SEO_META_DESC_LIMIT_EXPLAIN' => 'Limit in words for the Meta description tag',
	'SEO_META_BBCODE_FILTER' => 'BBcodes Filter',
	'SEO_META_BBCODE_FILTER_EXPLAIN' => 'Comma separated list of BBcodes which will be fully filtered in meta tags. Others will simply be deactivated and their content may appear in meta tags.<br/> Default filtered BBcodes are : <b>img,url,flash,code</b>.<br/><b style="color:red;">Attention :</b><br/>Not filtering img, url and flash BBcode is not a good idea, as well as the code one in most cases. Generally speaking, only keep BBcode content for BBcodes that have interesting content for metas',
	'SEO_META_KEYWORDS' => 'Meta keywords',
	'SEO_META_KEYWORDS_EXPLAIN' => 'Default Meta keywords, used on page not defining meta keywords. Simply enter a list of keywords',
	'SEO_META_KEYWORDS_LIMIT' => 'Meta keywords limit',
	'SEO_META_KEYWORDS_LIMIT_EXPLAIN' => 'Limit in words for the Meta keywords tag',
	'SEO_META_MIN_LEN' => 'Short words filter',
	'SEO_META_MIN_LEN_EXPLAIN' => 'Minimum amount of characters in a word to be included in the Meta keywords tag, only words composed of more than this limit will be taken into account',
	'SEO_META_CHECK_IGNORE' => 'Ignore words filter',
	'SEO_META_CHECK_IGNORE_EXPLAIN' => 'Apply, or not, the search_ignore_words.php exclusions in the meta keywords tag',
	'SEO_META_LANG' => 'Meta lang',
	'SEO_META_LANG_EXPLAIN' => 'Lang code used in meta tags',
	'SEO_META_COPY' => 'Meta copyright',
	'SEO_META_COPY_EXPLAIN' => 'Copyright used in meta tags. Inactivates the meta copyright tag if empty',
	'SEO_META_FILE_FILTER' => 'File filter',
	'SEO_META_FILE_FILTER_EXPLAIN' => 'Comma separated list of physical php script file name that should not be indexed (robots:noindex,follow). Example : ucp,mcp',
	'SEO_META_GET_FILTER' => '_GET filter',
	'SEO_META_GET_FILTER_EXPLAIN' => 'Comma separated list of _GET variable that should not be indexed (robots:noindex,follow). Example : style,hilit,sid',
	'SEO_META_ROBOTS' => 'Meta Robots',
	'SEO_META_ROBOTS_EXPLAIN' => 'The Meta Robots tag tells bots how to index your pages. It is set by default to "index,follow", which allow bots to index and cache your pages and to follow links in them. Inactivates the meta Robots tag if empty.<br/><b style="color:red;">Warning :</b><br/>This tag is sensible, if you were to use "noindex", none of your pages would be indexed',
	'SEO_META_NOARCHIVE' => 'Noarchive Meta Robots',
	'SEO_META_NOARCHIVE_EXPLAIN' => 'The Noarchive Meta Robots tag tells bots if they can or not cache the page. It only concerns caching, it has no relation to indexing and SERPs of the page.<br/>You can select here a list of forums that will have the "noarchive" option added to their meta robots.<br/>This feature can be handy, for example when you have some forums opened to bots but closed to guests. Adding the "noarchive" option to them will prevent guests from accessing content through the search engine cache, while the forum and its topic will still appear in SERPs',
	'SEO_META_OG' => 'Open Graph',
	'SEO_META_OG_EXPLAIN' => 'Activate <a href="/docs/technical-guides/opengraph/defining-an-object/">Open Graph tags</a> to allow the Facebook Crawler to generate previews when your content is shared on Facebook.',
	'SEO_META_FB_APP_ID' => 'Facebook App ID',
	'SEO_META_FB_APP_ID_EXPLAIN' => 'The unique ID that lets Facebook know the identity of your site. This is crucial for <a href="https://developers.facebook.com/docs/insights/">Facebook Insights</a> to work properly.',
	// Install
	'SEO_INSTALL_PANEL'	=> 'phpBB SEO Installation Panel',
	'SEO_ERROR_INSTALL'	=> 'An error occurred during the installation process. Uninstall once is safer before you retry.',
	'SEO_ERROR_INSTALLED'	=> 'The %s module is already installed.',
	'SEO_ERROR_ID'	=> 'The %1$ module had no ID.',
	'SEO_ERROR_UNINSTALLED'	=> 'The %s module is already uninstalled.',
	'SEO_ERROR_INFO'	=> 'Information :',
	'SEO_FINAL_INSTALL_PHPBB_SEO'	=> 'Login to ACP',
	'SEO_FINAL_UNINSTALL_PHPBB_SEO'	=> 'Return to forum index',
	'CAT_INSTALL_PHPBB_SEO'	=> 'Installation',
	'CAT_UNINSTALL_PHPBB_SEO'	=> 'Un-Installation',
	'SEO_OVERVIEW_TITLE'	=> 'phpBB SEO Ultimate SEO URL Overview',
	'SEO_OVERVIEW_BODY'	=> 'Welcome to our public release of the %1$s phpBB3 SEO mod rewrite %2$s.</p><p>Please read <a href="%3$s" title="Check the release thread" onclick="window.open(this.href); return false;"><b>the release thread</b></a> for more information</p><p><strong style="text-transform: uppercase;">Note:</strong> You must have already performed the required code changes and uploaded all the new files before you can proceed with this install wizard.</p><p>This installation system will guide you through the process of installing the phpBB3 SEO mod rewrite admin control panel. It will allow you to accurately choose your phpBB rewritten URL standard for the best results in search engines</p>.',
	'CAT_SEO_PREMOD'	=> 'phpBB SEO Premod',
	'SEO_PREMOD_TITLE'	=> 'phpBB SEO Premod overview',
	'SEO_PREMOD_BODY'	=> 'Welcome to our public release of the phpBB SEO Premod.</p><p>Please read <b>the release thread</b> for more information</p><p><strong style="text-transform: uppercase;">Note:</strong> You will be able to choose between the three phpBB3 SEO mod rewrites.<br/><br/><b>The three different URL rewriting standards available :</b><ul><li><b>The Simple mod</b>,</li><li><b>The Mixed mod</b>,</li><li><b>Advanced</b>.</li></ul>This choice is very important, we encourage you to take the time to fully discover the SEO features of this premod before you go online.<br/>This premod is as simple to install as phpBB3, just follow the regular process.<br/><br/>
	<p>Requirements for URL rewriting :</p>
	<ul>
		<li>Apache server (linux OS) with mod_rewrite module.</li>
		<li>IIS server (windows OS) with isapi_rewrite module, but you will need to adapt the RewriteRules in the httpd.ini</li>
	</ul>
	<p>Once installed, you will need to go to the ACP to set up and activate the mod.</p>',
	'SEO_LICENCE_TITLE'	=> 'RECIPROCAL PUBLIC LICENCE',
	'SEO_LICENCE_BODY'	=> 'The phpBB SEO mod rewrites are released under the RPL licence which states you cannot remove the phpBB SEO credits.<br/>For more details about possible exceptions, please contact a phpBB SEO administrator (primarily SeO or dcz).',
	'SEO_PREMOD_LICENCE'	=> 'The phpBB SEO mod rewrites and the Zero duplicate included in this Premod are released under the RPL licence which states you cannot remove the phpBB SEO credits.<br/>For more details about possible exceptions, please contact a phpBB SEO administrator (primarily SeO or dcz).',
	'SEO_SUPPORT_TITLE'	=> 'Support',
	'SEO_SUPPORT_BODY'	=> 'Full support will be given in the <a href="%1$s" title="Visit the %2$s SEO URL forum" onclick="window.open(this.href); return false;"><b>%2$s SEO URL forum</b></a>. We will provide answers to general setup questions, configuration problems, and support for determining common problems.</p><p>Be sure to visit our <a href="http://www.phpbb-seo.org/community/" title="SEO Forum" onclick="window.open(this.href); return false;"><b>Search Engine Optimization forums</b></a>.</p><p>You should <a href="http://www.phpbb-seo.org/community/ucp.php?mode=register" title="Register to phpBB SEO" onclick="window.open(this.href); return false;"><b>register</b></a>, log in and <a href="%3$s" title="Be notified about updates" onclick="window.open(this.href); return false;"><b>subscribe to the release thread</b></a> to be notified by mail upon each update.',
	'SEO_PREMOD_SUPPORT_BODY'	=> 'Full support will be given in the <a href="http://www.phpbb-seo.org/community/" title="Visit the phpBB SEO " onclick="window.open(this.href); return false;"><b>phpBB SEO forum</b></a>. We will provide answers to general setup questions, configuration problems, and support for determining common problems.</p><p>Be sure to visit our <a href="http://www.phpbb-seo.org/community/" title="SEO Forum" onclick="window.open(this.href); return false;"><b>Search Engine Optimization forums</b></a>.</p><p>You should <a href="http://www.phpbb-seo.org/community/ucp.php?mode=register" title="Register to phpBB SEO" onclick="window.open(this.href); return false;"><b>register</b></a>, log in and <a href="http://www.phpbb-seo.org/community/" title="Be notified about updates" onclick="window.open(this.href); return false;"><b>subscribe to the release thread</b></a> to be notified by mail upon each update.',
	'SEO_INSTALL_INTRO'		=> 'Welcome to the phpBB SEO Installation Wizard',
	'SEO_INSTALL_INTRO_BODY'	=> '<p>You are about to install the %1$s phpBB SEO mod rewrite %2$s. This install tool will activate the phpBB SEO mod rewrite control panel in phpBB ACP.</p><p>Once installed, you will need to go to the ACP to set up and activate the mod.</p>
	<p><strong>Note:</strong> If it’s the first time you try this mod, we strongly encourage you to take the time to test the various URL standard this mod can output on a local or private test server. This way, you won’t show different URLs to bots every other day while testing, and you won’t discover a month after that you would have preferred different URLs. Patience is virtue SEO wise and even if the zero duplicate makes the HTTP redirecting very easy, you don’t want to redirect all your forum’s URLs too often.</p><br/>
	<p>Requirements :</p>
	<ul>
		<li>Apache server (linux OS) with mod_rewrite module.</li>
		<li>IIS server (windows OS) with isapi_rewrite module, but you will need to adapt the RewriteRules in the httpd.ini</li>
	</ul>',
	'SEO_INSTALL'		=> 'Install',
	'UN_SEO_INSTALL_INTRO'		=> 'Welcome to the phpBB SEO uninstall Wizard',
	'UN_SEO_INSTALL_INTRO_BODY'	=> '<p>You are about to uninstall the %1$s phpBB SEO mod rewrite %2$s ACP module.</p>
	<p><strong>Note:</strong> This will not deactivate URL rewriting on your board as long as the phpBB files are still modded.</p>',
	'UN_SEO_INSTALL'		=> 'Uninstall',
	'SEO_INSTALL_CONGRATS'			=> 'Congratulations!',
	'SEO_INSTALL_CONGRATS_EXPLAIN'	=> '<p>You have now successfully installed the %1$s phpBB3 SEO mod rewrite %2$s. You should now go to phpBB ACP and proceed with the mod rewrite settings.<p>
	<p>In the new phpBB SEO category, you will be able to :</p>
	<h2>Set up and activate URL rewriting</h2>
		<p>Take your time, that’s where you will choose how your URLs will look like. The zero duplicate options will as well be set up from here when installed.</p>
	<h2>Accurately choose your forum’s URL</h2>
		<p>Using the Mixed or the Advanced mod, you will be able to dissociate Forum URLs from their titles and elect to use whatever keyword you may like in them</p>
	<h2>Generate a personalized .htaccess</h2>
	<p>Once you will have set up the above options, you will be able to generate a personalized .htaccess within no time and save it directly on the server.</p>',
	'UN_SEO_INSTALL_CONGRATS'	=> 'The phpBB SEO ACP module was removed.',
	'UN_SEO_INSTALL_CONGRATS_EXPLAIN'	=> '<p>You have now successfully uninstalled the %1$s phpBB3 SEO mod rewrite %2$s.<p>
	<p>This will not deactivate URL rewriting on your board as long as the phpBB files are still modded.</p>',
	'SEO_VALIDATE_INFO'	=> 'Validation Info :',
	'SEO_SQL_ERROR' => 'SQL error',
	'SEO_SQL_TRY_MANUALLY' => 'The db user does not seems to have enough rights to run the required SQL query, please run it manually (phpMyadmin) :',
	// Security
	'SEO_LOGIN'		=> 'The board requires you to be registered and logged in to view this page.',
	'SEO_LOGIN_ADMIN'	=> 'The board requires you to be logged in as admin to view this page.<br/>Your session has been destroyed for security purposes.',
	'SEO_LOGIN_FOUNDER'	=> 'The board requires you to be logged in as the founder to view this page.',
	'SEO_LOGIN_SESSION'	=> 'Session Check failed.<br/>The Settings were not altered.<br/>Your session has been destroyed for security purposes.',
	// Cache status
	'SEO_CACHE_FILE_TITLE'	=> 'Cache file status',
	'SEO_CACHE_STATUS'	=> 'The cache directory configured is : <b>%s</b>',
	'SEO_CACHE_FOUND'	=> 'The cache directory was successfully found.',
	'SEO_CACHE_NOT_FOUND'	=> 'The cache directory was not found.',
	'SEO_CACHE_WRITABLE'	=> 'The cache directory is writable.',
	'SEO_CACHE_UNWRITABLE'	=> 'The cache directory is not writable. You need to CHMOD it to 0777.',
	'SEO_CACHE_INNER_UNWRITABLE' => 'Some files within the cache directory may not be writable, make sure you properly CHMOD the cache directory AND all files in it.',
	'SEO_CACHE_FORUM_NAME'	=> 'Forum name',
	'SEO_CACHE_URL_OK'	=> 'URL Cached',
	'SEO_CACHE_URL_NOT_OK'	=> 'This Forum URL is not cached',
	'SEO_CACHE_URL'		=> 'Final URL',
	'SEO_CACHE_MSG_OK'	=> 'The cache file was updated successfully.',
	'SEO_CACHE_MSG_FAIL'	=> 'An error occurred while updating the cache file.',
	'SEO_CACHE_UPDATE_FAIL'	=> 'The URL you entered cannot be used, the cache was left untouched.',
	// Seo advices
	'SEO_ADVICE_DUPE'	=> 'A duplicate entry in title was detected for a forum URL : <b>%1$s</b>.<br/>It will stay unchanged until you update it.',
	'SEO_ADVICE_RESERVED'	=> 'A reserved (used by other urls, such as members profiles and such) entry in title was detected for a forum URL : <b>%1$s</b>.<br/>It will stay unchanged until you update it.',
	'SEO_ADVICE_LENGTH'	=> 'The URL cached is a bit too long.<br/>Consider using a smaller one',
	'SEO_ADVICE_DELIM'	=> 'The URL cached contains the SEO delimiter and ID.<br/>Consider setting up an original one.',
	'SEO_ADVICE_WORDS'	=> 'The URL cached contains a bit too many words.<br/>Consider setting up an better one.',
	'SEO_ADVICE_DEFAULT'	=> 'The ending URL, after formatting, is the default.<br/>Consider setting up an original one.',
	'SEO_ADVICE_START'	=> 'Forum URLs cannot end with a pagination parameter.<br/>They were thus removed from the one submitted.',
	'SEO_ADVICE_DELIM_REM'	=> 'Submitted forum URLs cannot end with a forum delimiter.<br/>They were thus removed from one submitted.',
	// Mod Rewrite type
	'ACP_SEO_SIMPLE'	=> 'Simple',
	'ACP_SEO_MIXED'		=> 'Mixed',
	'ACP_SEO_ADVANCED'	=> 'Advanced',
	'ACP_ULTIMATE_SEO_URL'	=> 'Ultimate SEO URL',
	// URL Sync
	'SYNC_REQ_SQL_REW' => 'You must activate SQL Rewriting to use this script !',
	'SYNC_TITLE' => 'URL Synchronization',
	'SYNC_WARN' => 'Attention, do not stop the script until it ends, and back up your db before you use it!',
	'SYNC_COMPLETE' => 'Synchronization completed !',
	'SYNC_RESET_COMPLETE' => 'Reset completed !',
	'SYNC_PROCESSING' => '<b>Processing, please wait ...</b><br/><br/><b>%1$s%%</b> have been processed. <br/>So far, <b>%2$s</b> items have been processed.<br/><b>%3$s</b> items in total, <b>%4$s</b> are processed at a time.<br/>Speed : <b>%5$s item/s.</b><br/>Time spent for this cycle : <b>%6$ss</b><br/>Estimated time left : <b>%7$s minute(s)</b>',
	'SYNC_ITEM_UPDATED' => '<b>%1$s</b> items have been updated',
	'SYNC_TOPIC_URLS' => 'Start topic URLs synchronization',
	'SYNC_RESET_TOPIC_URLS' => 'Reset all topic URLs',
	'SYNC_TOPIC_URL_NOTE' => 'You just activated the SQL Rewriting option, you should now synchronize all your topics URLs by going to %sthis page%s if you did not already.<br/>This will not change any of your current URLs<br/><b style="color:red">Please note :</b><br/><em>You should only synchronize your topics URLs once you have fully set up your URL standard. It’s not a drama if you change your URL standard after your synchronized topic URLs, but you should do it again each time you do.<br/>It’s not a drama either if you don’t, your topic URLs would in such case be updated upon each topic visit in case the topic URL would be empty or not matching your current standard.</em>',
	// phpBB SEO Class option
	'url_rewrite' => 'Activate URL rewriting',
	'url_rewrite_explain' => 'Once you have set up the below options, and generated your personalized .htaccess, you can activate URL rewriting and check if your rewritten URLs do work properly. If you get 404 errors, it’s most likely an .htaccess issue, try some of the .htaccess tool option to generate a new one.',
	'modrtype' => 'URL rewriting type',
	'modrtype_explain' => 'You have here the choice between three phpBB SEO mod rewrite types.<br/>The <b>Simple</b> one,the <b>Mixed</b> one and the <b>Advanced</b> one.<br/><br/><b style="color:red">Please Note :</b><br/><em>Modifying this option will change all your URLs in your web site.<br/>Doing it with an already indexed web site should thus be considered with as much care as when migrating and not too often.<br/>So you’d better be decided to go for it or not.<br/>Changing this option requires an .htaccess update.</em>',
	'sql_rewrite' => 'Activate SQL Rewriting',
	'sql_rewrite_explain' => 'This option will allow you to choose URL for each topic. You will be able to accurately set topic URL when posting new topic or when editing an existing one. This functionality is though limited to forum admins and moderators.<br/><br/><b style="color:red">Please Note :</b><br/><em>Turning on this option will not change topic URLs.  Existing URLs will be stored as they are displayed in the data base. But it may not be the case if you turn it off after you started to use it. In such case, personalized URLs may be treated as if they weren’t.<br/>The feature also has the great advantage to fasten the URL rewriting by a lot, especially when using the virtual folder option in advanced mode, and to make it a lot easier to retrieve rewritten URLs from any page.</em>',
	'profile_inj' => 'Profiles and groups injection',
	'profile_inj_explain' => 'You can here choose to inject nicknames, group names and user messages page (optional see below) in their URLs instead of the default static rewriting, <b>phpBB/nickname-uxx.html</b> instead of <b>phpBB/memberxx.html</b>.',
	'profile_vfolder' => 'Virtual folder Profiles',
	'profile_vfolder_explain' => 'You can here choose to simulate a folder structure for profiles and user messages page (optional see below) URLs, <b>phpBB/nickname-uxx/(topics/)</b> or <b>phpBB/memberxx/(topics/)</b> instead of <b>phpBB/nickname-uxx(-topics).html</b> and <b>phpBB/memberxx(-topics).html</b>.<br/><br/><b style="color:red">Please Note</b><br/><em>Profile ID removing will override this setting.<br/>Changing this option requires an .htaccess update</em>',
	'profile_noids' => 'Profiles ID removing',
	'profile_noids_explain' => 'When Profiles and groups injection is activated, you can here choose to use <b>example.com/phpBB/member/nickname</b> instead of the default <b>example.com/phpBB/nickname-uxx.html</b>. phpBB Uses an extra, but light, SQL query on such pages without user id.<br/><br/><b style="color:red">Please Note</b><br/><em>Special characters won’t be handled the same by all browsers. FF always urlencodes (<a href="http://www.php.net/urlencode">urlencode()</a>), and it seems to use Latin1 first, when IE and Opera do not. For advanced urlencoding options, please read the install file.<br/>Changing this option requires an .htaccess update</em>',
	'rewrite_usermsg' => 'Common Search and User messages pages rewriting',
	'rewrite_usermsg_explain' => 'This option mostly makes sense if you allow public access to both profiles and search pages.<br/> Using this option most likely implies a greater use of the search functions and thus a heavier server load.<br/> The URL rewriting type (with and without ID) follows the one set for profiles and groups.<br/><b>phpBB/messages/nickname/topics/</b> VS <b>phpBB/nickname-uxx-topics.html</b> VS <b>phpBB/memberxx-topics.html</b>.<br/>Additionally, this option will activate the common search page rewriting, such as active topics, unanswered and newposts pages.<br/><br/><b style="color:red">Please Note :</b><br/><em>ID removing on these links will imply the same limitation as per the user profiles.<br/>Changing this option requires an .htaccess update</em>',
	'rewrite_files' => 'Attachment Rewriting',
	'rewrite_files_explain' => 'Activate phpBB Attachment Rewriting. Can be of a great help if you have many attached images worth being indexed. Files of course must be downloadable by bots for this to have a meaning SEO wise.<br/><br/><b style="color:red">Please Note :</b><br/><em>Make sure you have the required RewriteRule (# PHPBB FILES ALL MODES) in your .htaccess when you activate this option</em>',
	'rem_sid' => 'SID Removing',
	'rem_sid_explain' => 'SID will be removed from 100% of the URLs passing through the phpbb_seo class, for guests thus bots.<br/>This ensure bots won’t see any SID on forum, topic and post URLs, but visitors that do not accept cookies will most likely create more than one session.<br/>The Zero duplicate http 301 redirect URL with SID for guests and bots by default.',
	'rem_hilit' => 'Highlights Removing',
	'rem_hilit_explain' => 'Highlights will be removed from 100% of the URLs passing through the phpbb_seo class, for guests thus bots.<br/>This ensures bots won’t see any Highlights on forum, topic and post URLs.<br/>The Zero duplicate will automatically follow this setting, eg http 301 redirect URL with highlights for guests and bots.',
	'rem_small_words' => 'Remove small words',
	'rem_small_words_explain' => 'Allow to remove all words of less than three letters in rewritten URLs.<br/><br/><b style="color:red">Please Note</b><br/><em>The filtering will change potentially a lot of URLs in your web site.<br/>Even though the zero duplicate mod would take care of all the required redirecting when changing this option, starting to use it with an already indexed web site should thus be considered with as much care as when migrating and not too often.<br/>So you’d better be decided to go for it or not.</em>',
	'virtual_folder' => 'Virtual Folder',
	'virtual_folder_explain' => 'Allow to add the forum URL as a virtual folder in topic URLs.<br/><br/><b>Example :</b><br/><em><b>forum-title-fxx/topic-title-txx.html</b> VS <b>topic-title-txx.html</b> for a topic URL.</em><br/><br/><b style="color:red">Please Note</b><br/><em>The Virtual folder injection option can change all your web site’s URLs almost too easily.<br/>Starting to use it with an already indexed web site should thus be considered with as much care as when migrating and not too often.<br/>So you’d better be decided to go for it or not.<br/>Changing this option requires an .htaccess update.</em>',
	'virtual_root' => 'Virtual Root',
	'virtual_root_explain' => 'If phpBB is installed in a sub folder (example phpBB3/), you can simulate a root install for rewritten links.<br/><br/><b>Example :</b><br/><em><b>phpBB3/forum-title-fxx/topic-title-txx.html</b> VS <b>forum-title-fxx/topic-title-txx.html</b> for a topic URL.</em><br/><br/>This can be handy to shorten URLs a bit, especially if you are using the "Virtual Folder" feature. UnRewritten links will continue to appear and work in the phpBB folder.<br/><br/><b style="color:red">Please Note :</b><br/><em>Using this option requires you to use a home page for the forum index (like forum.html).<br/> This option can change all your web site’s URLs almost too easily.<br/>Starting to use it with an already indexed web site should thus be considered with as much care as when migrating and not too often.<br/>So you’d better be decided to go for it or not.<br/>Changing this option requires an .htaccess update.</em>',
	'cache_layer' => 'Forum URL caching',
	'cache_layer_explain' => 'Turns on the cache for forum URLs and allow to separate forum titles from their URL<br/><br/><b>Example :</b><br/><em><b>forum-title-fxx/</b> VS <b>any-title-fxx/</b> for a forum URL.</em><br/><br/><b style="color:red">Please Note</b><br/><em>This option will allow you to change your forum URL, thus potentially many topic URLS if you are using the Virtual Folder option.<br/>The topic URLs will always be redirected properly with the Zero Duplicate.<br/>It will as well be the case for forum URL as long as you keep the delimiter and IDs, see below.</em>',
	'rem_ids' => 'Forum ID Removing',
	'rem_ids_explain' => 'Get rid of the IDs and delimiters in forum URLs. Only apply if Forum URL caching is activated.<br/><br/><b>Example :</b><br/><em><b>any-title-fxx/</b> VS <b>any-title/</b> for a forum URL.</em><br/><br/><b style="color:red">Please Note :</b><br/><em>This option will allow you to change your forum URL, thus potentially many topic URLS if you are using the Virtual Folder option.<br/>The topic URLs will always be redirected properly with the Zero Duplicate.<br/><b>It will not always be the case with the forum URLs :</b><br/><b>any-title-fxx/</b> will always be properly redirected with the Zero Duplicate but it won’t be the case if you edit <b>any-title/</b> to <b>something-else/</b>.<br/> In such a case, <b>any-title/</b> will for now be treated as a forum that does not exist.<br/>So you’d better be decided to go for it or not, but it can really be powerful SEO wise.</em>',
	'redirect_404_forum' => 'Redirect forum 404s',
	'redirect_404_forum_explain' => 'Redirect non existing forums to index with a 301 instead of issuing a 404 with the standard phpBB message.',
	'redirect_404_topic' => 'Redirect topic 404s',
	'redirect_404_topic_explain' => 'Redirect non existing topics adn posts to index with a 301 instead of issuing a 404 with the standard phpBB message.',
	// copyrights
	'copyrights' => 'Copyrights',
	'copyrights_img' => 'Link image',
	'copyrights_img_explain' => 'You can here choose to display the phpBB SEO copyright link as an image or as a text links.',
	'copyrights_txt' => 'Link text',
	'copyrights_txt_explain' => 'You can here choose the text to be used as the phpBB SEO copyright link text anchor. Leave empty for defaults.',
	'copyrights_title' => 'Link title',
	'copyrights_title_explain' => 'You can here choose the text to be used as the phpBB SEO copyright link title. Leave empty for defaults.',
	// Zero duplicate
	// Options
	'ACP_ZERO_DUPE_OFF' => 'Off',
	'ACP_ZERO_DUPE_MSG' => 'Post',
	'ACP_ZERO_DUPE_GUEST' => 'Guest',
	'ACP_ZERO_DUPE_ALL' => 'All',
	'zero_dupe' =>'Zero duplicate',
	'zero_dupe_explain' => 'The following settings concerns the Zero duplicate, you can modify them upon your needs.<br/>These do not imply any .htaccess update.',
	'zero_dupe_on' => 'Activate the Zero duplicate',
	'zero_dupe_on_explain' => 'Allow to activate and deactivate the Zero duplicate redirections.',
	'zero_dupe_strict' => 'Strict Mode',
	'zero_dupe_strict_explain' => 'When activated, the zero dupe will check if the requested URL exactly matches the one attended.<br/>When set to no, the zero dupe will make sure the attended URL is the first part of the one requested.<br/>The interest is to make it easier to deal with mods that could interfere with the zero dupe by adding GET vars.',
	'zero_dupe_post_redir' => 'Posts Redirections',
	'zero_dupe_post_redir_explain' => 'This option will determine how to handle post URLs; it can take four values :<br/><b>&nbsp;off</b>, do not redirect post URL, whatever the case,<br/><b>&nbsp;post</b>, only make sure postxx.html is used for a post URL,<br/><b>&nbsp;guest</b>, redirect guests if required to the corresponding topic URL rather than to the postxx.html, and only make sure postxx.html is used for logged in users,<br/><b>&nbsp;all</b>, redirect if required to the corresponding topic URL.<br/><br/><b style="color:red">Please Note</b><br/><em>Keeping the <b>postxx.html</b> URLs is harmless SEO wise as long as you keep the disallow on post URLs in your robots.txt.<br/>Redirecting them all will most likely produce the most redirections among all.<br/>If you redirect postxx.html in all cases, this means as well that a message that would be posted in a thread and then moved in another one will see its URL changing, which thanks to the zero duplicate mod is of no harm SEO wise, but the previous link to the post won’t link to it anymore in such a case.</em>.',
	// no duplicate
	'no_dupe' => 'No duplicate',
	'no_dupe_on' => 'Activate The No duplicate',
	'no_dupe_on_explain' => 'The No duplicate mod replaces posts URLs with the corresponding Topic URL (with pagination).<br/>It does not add any SQL, just a LEFT JOIN on a query already being performed. This could still mean a bit more work, but should not be a problem for server load.',
));

$lang = array_merge($lang, array(
	'ACP_CAT_PHPBB_SEO' => 'phpBB SEO',
	'ACP_MOD_REWRITE' => 'URL Rewriting settings',
	'ACP_PHPBB_SEO_CLASS' => 'phpBB SEO Class settings',
	'ACP_FORUM_URL' => 'Forum URL Management',
	'ACP_HTACCESS' => '.htaccess',
	'ACP_SEO_EXTENDED' => 'Extended config',
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
