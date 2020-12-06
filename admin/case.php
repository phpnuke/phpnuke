<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System										  */
/* ===========================										  */
/*																	  */
/* Copyright (c) 2006 by Francisco Burzi								*/
/* https://www.phpnuke.ir												   */
/*																	  */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.	   */
/************************************************************************/

if (!defined('ADMIN_FILE')) {
	die ("Access Denied");
}

switch($op) {
	//authors
	case "mod_authors":
	case "modifyadmin":
	case "UpdateAuthor":
	case "AddAuthor":
	case "deladmin2":
	case "deladmin":
	case "assignarticles":
	case "deladminconf":
	case "remove_permission":
		include("admin/modules/authors.php");
	break;
	
	//database
	case "database":
	case "BackupDB":
	case "OptimizeDB":
	case "CheckDB":
	case "AnalyzeDB":
	case "RepairDB":
	case "StatusDB":
		include("admin/modules/backup.php");
	break;
	
	//blocks
	case "BlocksAdmin":
	case "BlocksEdit":
	case "BlocksSave":   
	case "HeadlinesDel":
	case "HeadlinesAdd":
	case "HeadlinesSave":
	case "HeadlinesAdmin":
	case "HeadlinesEdit":
	case "updateweight":
	case "remove_block":
	case "info_block":
	case "change_block_status":
	case "preview_block":
		include("admin/modules/blocks.php");
	break;
	
	//bookmarks
	case "bookmarks":
	case "save_bookmarks":
	case "editbookmark":
	case "updatebookmarksweight":
		include("admin/modules/bookmarks.php");
	break;
	
	//caches
	case "cache":
	case "FlushCache":	
	case "updatecache":
		include("admin/modules/cache.php");
	break;
	
	//categories
	case "categories":
	case "categories_admin":
	case "categories_delete":
		include("admin/modules/categories.php");
	break;
	
	//comments
	case "comments":
	case "comments_edit":
	case "comments_delete":
	case "comments_reply":
	case "comments_status":
		include("admin/modules/comments.php");
	break;
	
	//points_groups
	case "points_groups":
	case "group_add":
	case "group_edit":
	case "group_edit_save":
	case "group_del":
	case "points_update":
		include("admin/modules/points_groups.php");
	break;
	
	//languages
	case "language":
	case "edit_language_word":
	case "delete_language_word":
	case "language_options":
		include("admin/modules/language.php");
	break;

	//medias
	case "media_browser":
	case "media_get_menu_files":
	case "media_get_files":
	case "media_upload":
	case "delete_media":
	case "get_media_metadata":
		include("admin/modules/media.php");
	break;
	
	//meta_tags seo
	case "seo":
	case "saveseo":
	case "savesets":
	case "showfeed":
	case "savepings":
		include("admin/modules/meta_tags.php");
	break;
	
	//modules
	case "modules":
	case "module_status":
	case "module_edit":
	case "module_edit_boxess":
	case "home_module":
	case "upload_module":
		include("admin/modules/modules.php");
	break;
	
	//mtsn
	case "mtsn_admin":
	case "set_config":
	case "ip_ban_page":
	case "deleteip":
	case "addnewip":
	case "clearallip":
	case "searchip":
		include("admin/modules/mtsn.php");
	break;
	
	
	//hreferrer
	case "hreferrer":
	case "delreferrer":
		include("admin/modules/referrers.php");
	break;
	
	//settings
	case "settings":
	case "save_configs":
	case "options_menu":
	case "general_config":
	case "themes_config":
	case "comments_config":
	case "language_config":
	case "referers_config":
	case "mailing_config":
	case "security_config":
	case "uploads_config":
	case "forums_config":
	case "smilies_config":
	case "sms_config":
	case "others_config":
		include("admin/modules/settings.php");
	break;
	
	//upgrade
	case "upgrade":
		include("admin/modules/upgrade.php");
	break;
	
	//reports
	case "reports":
	case "reports_delete":
		include("admin/modules/reports.php");
	break;
	
	//nav_menus
	case "nav_menus":
	case "nav_menus_admin":
		include("admin/modules/nav_menus.php");
	break;
}

?>