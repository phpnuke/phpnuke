<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/


if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

$rewrite_rule["phpnukemain"] = array(
	"error/([^\.]*).html$" => 'index.php?error=$1',
	"index.html$" => 'index.php',
	"index.htm$" => 'index.php',
	"thumbs/(.*)$" => array('parse_timthumbs_args'),
);

$rewrite_rule["report"] = array(
	"report/([^/]+?)/([0-9]{1,}+)/([^/]+?)/(([^/]+?)/?)?$" => 'index.php?sop=report&module_name=$1&post_id=$2&post_title=$3&post_link=$5',
);

$friendly_links = array(
	"index.php\?sop=([^/]+)$" => array("parse_phpnuke_main"),
	"index.php\?error=(.*)$" => 'error/$1.html',
	"index.php$" => 'index.html',
	"index.php\?timthumb=true&(.*)$" => array("parse_timthumbs_str"),
);

$nuke_configs['links_function']['comments'] = '';

$admin_top_menus = array(
	"contents" => array("id" => 'contents', "parent_id" => 0, "title" => "_CONTENTS", "url" => "#", "icon" => "pencil"),
	"categories" => array("id" => 'categories', "parent_id" => 0, "title" => "_CATEGORIES", "url" => "#", "icon" => "pencil"),
	"recives" => array("id" => 'recives', "parent_id" => 0, "title" => "_RECIVESS", "url" => "#", "icon" => "pencil"),
	"comments" => array("id" => 'comments', "parent_id" => 0, "title" => "_COMMENTS", "url" => "".$admin_file.".php?op=comments", "icon" => "pencil"),
);

$admin_top_menus['recives']['children'][] = array(
	"id" => 'reports', 
	"parent_id" => 'recives', 
	"title" => "_REPORTS", 
	"url" => "".$admin_file.".php?op=reports", 
	"icon" => ""
);

define("ADMINS_MENU_TABLE",				$pn_prefix."_admins_menu");
define("ARTICLES_TABLE",				$pn_prefix."_posts");
define("AUTHORS_TABLE",					$pn_prefix."_authors");
define("BANNER_TABLE",					$pn_prefix."_banner");
define("BANNER_CLIENTS_TABLE",			$pn_prefix."_banner_clients");
define("BANNER_PLANS_TABLE",			$pn_prefix."_banner_plans");
define("BANNER_POSITIONS_TABLE",		$pn_prefix."_banner_positions");
define("BANNER_TERMS_TABLE",			$pn_prefix."_banner_terms");
define("BLOCKS_BOXES_TABLE",			$pn_prefix."_blocks_boxes");
define("BLOCKS_TABLE",					$pn_prefix."_blocks");
define("BOOKMARKSITE_TABLE",			$pn_prefix."_bookmarksite");
define("CATEGORIES_TABLE",				$pn_prefix."_categories");
define("COMMENTS_TABLE",				$pn_prefix."_comments");
define("CONFIG_TABLE",					$pn_prefix."_config");
define("FEEDBACKS_TABLE",				$pn_prefix."_feedbacks");
define("HEADLINES_TABLE",				$pn_prefix."_headlines");
define("LANGUAGES_TABLE",				$pn_prefix."_languages");
define("LOG_TABLE",						$pn_prefix."_log");
define("MODULES_TABLE",					$pn_prefix."_modules");
define("MTSN_TABLE",					$pn_prefix."_mtsn");
define("MTSN_IPBAN_TABLE",				$pn_prefix."_mtsn_ipban");
define("NAV_MENUS_TABLE",				$pn_prefix."_nav_menus");
define("NAV_MENUS_DATA_TABLE",			$pn_prefix."_nav_menus_data");
define("POSTS_TABLE",					$pn_prefix."_posts");
define("POSTS_META_TABLE",				$pn_prefix."_postsmeta");
define("SURVEYS_TABLE",					$pn_prefix."_surveys");
define("SURVEYS_CHECK_TABLE",			$pn_prefix."_surveys_check");
define("POINTS_GROUPS_TABLE",			$pn_prefix."_points_groups");
define("REFERRER_TABLE",				$pn_prefix."_referrer");
define("REPORTS_TABLE",					$pn_prefix."_reports");
define("SCORES_TABLE",					$pn_prefix."_scores");
define("SESSIONS_TABLE",				$pn_prefix."_sessions");
define("TRANSACTIONS_TABLE",			$pn_prefix."_transactions");
define("STATISTICS_TABLE",				$pn_prefix."_statistics");
define("STATISTICS_COUNTER_TABLE",		$pn_prefix."_statistics_counter");
define("SUBSCRIPTIONS_TABLE",			$pn_prefix."_subscriptions");
define("TAGS_TABLE",					$pn_prefix."_tags");

define("GROUPS_TABLE",					$pn_prefix."_groups");
define("USERS_TABLE",					$pn_prefix."_users");
define("USERS_FIELDS_TABLE",			$pn_prefix."_users_fields");
define("USERS_FIELDS_VALUES_TABLE",		$pn_prefix."_users_fields_values");
define("USERS_INVITES_TABLE",			$pn_prefix."_users_invites");

define("_GRAVATAR_URL",					"http://secure.gravatar.com/avatar/");

?>