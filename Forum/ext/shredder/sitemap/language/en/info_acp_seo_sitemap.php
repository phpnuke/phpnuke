<?php

/**
*
* info_acp_seo_sitemap [English]
*
* @package phpBB3 SEO Sitemap
* @copyright (c) 2014 www.phpbb-work.ru
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
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
	'SEOMAP'						=> 'SEO Sitemap',
	'SEOMAP_VERSION'				=> 'Extension version: %s. See <a style="font-weight: bold;" href="https://www.phpbb.com/customise/db/extension/seo_sitemap/" onclick="window.open(this.href);return false;">extension page</a> for the latest version or to get help with this extension.',
	'SEOMAP_EXPLAIN'				=> 'Pay attention that use of optional Priority and Changefreq settings could yield some positive results for you, but recommended using them together and really analysing which pages should be considered more important over others before making any decisions. If you do not know or are unsure, it is better to disable these options. <a href="http://www.sitemaps.org/protocol.html#xmlTagDefinitions" onclick="window.open(this.href);return false;">Click here</a> to read more details about priority and changefreq.<br />Enter 0 if you wish to disable priority.',
	'SEOMAP_SETTINGS'				=> 'SEO Sitemap Settings',
	'SEOMAP_SETTINGS_UPDATED'		=> '<strong>Updated SEO Sitemap Settings</strong>',
	'SEOMAP_SAVED'					=> 'Sitemap Settings successfully updated.',
	'SEOMAP_EXCLUDED'				=> 'Excluded forums',
	'SEOMAP_EXCLUDED_EXPLAIN'		=> 'Forums selected here and topics inside them will be excluded from Sitemap.<br /><strong>Attention:</strong> Categories and forums without topics are excluded by default.',
	'SEOMAP_CACHE_TIME'				=> 'Cache time',
	'SEOMAP_CACHE_TIME_EXPLAIN'		=> 'To reduce server load, sitemap will be cached for some time. After this time it will be re-created. Enter the number of hours for which sitemap will be cached or enter 0 for disable caching.',
	'SEOMAP_URL'					=> 'Sitemap URL: <a href="%s" onclick="window.open(this.href);return false;">%s</a>',
	'SEOMAP_URL_COUNT'				=> 'Total amount of URLs in sitemap: %s',
	'SEOMAP_URL_LIMIT'				=> 'URL limit',
	'SEOMAP_URL_LIMIT_EXPLAIN'		=> 'Maximum amount of URLs in sitemap file, up to 50000 according to sitemaps protocol. Decrease this value if you experience problems with exceeding limits. If the amount of URLs on your board exceeds this number, sitemap will automatically be split into multiple files.',
	'SEOMAP_BATCH_SIZE'				=> 'Preferred batch processing size',
	'SEOMAP_BATCH_SIZE_EXPLAIN'		=> 'Decrease the batch size in case of PHP memory limit errors at sitemap data generation stage. Notice the fact that time required for sitemap generation will rise significantly on each decreasing of batch size value.',
	'SEOMAP_PRIORITY_0'				=> 'Priority for default topics',
	'SEOMAP_PRIORITY_1'				=> 'Priority for sticky topics',
	'SEOMAP_PRIORITY_2'				=> 'Priority for announcements',
	'SEOMAP_PRIORITY_3'				=> 'Priority for global announcements',
	'SEOMAP_PRIORITY_4'				=> 'Priority for article topics',
	'SEOMAP_PRIORITY_F'				=> 'Priority for forums',
	'SEOMAP_FREQ_0'					=> 'Changefreq for default topics',
	'SEOMAP_FREQ_1'					=> 'Changefreq for sticky topics',
	'SEOMAP_FREQ_2'					=> 'Changefreq for announcements',
	'SEOMAP_FREQ_3'					=> 'Changefreq for global anouncements',
	'SEOMAP_FREQ_4'					=> 'Changefreq for article topics',
	'SEOMAP_FREQ_F'					=> 'Changefreq for forums',
	'SEOMAP_FREQ_NEVER'				=> 'Never',
	'SEOMAP_FREQ_YEARLY'			=> 'Yearly',
	'SEOMAP_FREQ_MONTHLY'			=> 'Monthly',
	'SEOMAP_FREQ_WEEKLY'			=> 'Weekly',
	'SEOMAP_FREQ_DAILY'				=> 'Daily',
	'SEOMAP_FREQ_HOURLY'			=> 'Hourly',
	'SEOMAP_FREQ_ALWAYS'			=> 'Always',
	'SEOMAP_NO_DATA'				=> 'No data for sitemap.',
	'SEOMAP_NO_FILE'				=> 'Unable to open file:<br /><strong>%s</strong>',
	'SEOMAP_CANT_WRITE'				=> 'Folder <strong>%s</strong> does not exist or is not writable. Fix it manually using FTP-client.',
	'SEOMAP_COPYRIGHT'				=> 'phpBB MODs and Extensions',

// Sync section
	'SEOMAP_SYNC_COMPLETE' 			=> 'Synchronisation successfully completed.<br /><br /><a style="font-weight: bold;" href="%s">&laquo; Go back to settings</a>',
	'SEOMAP_SYNC_PROCESS'			=> '<strong>Sync in progress. Do not close this page and do not interrupt script before it finishes all the actions.</strong><br /><br /><strong>%1$s%%</strong> finished. Processed <strong>%2$s</strong> of all posts. Total posts: <strong>%3$s</strong>.',
	'SEOMAP_SYNC_REQ' 				=> 'You should synchronise posts modification dates before using this sitemap. This is needed to generate last modification time of the board pages. <a style="font-weight: bold;" href="%s">Click here to synchronise</a>.',
));
