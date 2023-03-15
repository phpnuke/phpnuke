<?php
if (!defined('ADMIN_FILE')) {
    exit('Access Denied');
}

$modules = [
    //authors
    'mod_authors' => 'authors',
    'modifyadmin' => 'authors',
    'UpdateAuthor' => 'authors',
    'AddAuthor' => 'authors',
    'deladmin2' => 'authors',
    'deladmin' => 'authors',
    'assignarticles' => 'authors',
    'deladminconf' => 'authors',
    'remove_permission' => 'authors',

    //database
    'database' => 'backup',
    'BackupDB' => 'backup',
    'OptimizeDB' => 'backup',
    'CheckDB' => 'backup',
    'AnalyzeDB' => 'backup',
    'RepairDB' => 'backup',
    'StatusDB' => 'backup',

    //blocks
    'BlocksAdmin' => 'blocks',
    'BlocksEdit' => 'blocks',
    'BlocksSave' => 'blocks',
    'HeadlinesDel' => 'blocks',
    'HeadlinesAdd' => 'blocks',
    'HeadlinesSave' => 'blocks',
    'HeadlinesAdmin' => 'blocks',
    'HeadlinesEdit' => 'blocks',
    'updateweight' => 'blocks',
    'remove_block' => 'blocks',
    'info_block' => 'blocks',
    'change_block_status' => 'blocks',
    'preview_block' => 'blocks',

    //bookmarks
    'bookmarks' => 'bookmarks',
    'save_bookmarks' => 'bookmarks',
    'editbookmark' => 'bookmarks',
    'updatebookmarksweight' => 'bookmarks',

    //caches
    'cache' => 'cache',
    'FlushCache' => 'cache',
    'updatecache' => 'cache',

    //categories
    'categories' => 'categories',
    'categories_admin' => 'categories',
    'categories_delete' => 'categories',

    //comments
    'comments' => 'comments',
    'comments_edit' => 'comments',
    'comments_delete' => 'comments',
    'comments_reply' => 'comments',
    'comments_status' => 'comments',

    //points_groups
    'points_groups' => 'points_groups',
    'group_add' => 'points_groups',
    'group_edit' => 'points_groups',
    'group_edit_save' => 'points_groups',
    'group_del' => 'points_groups',
    'points_update' => 'points_groups',

    //languages
    'language' => 'language',
    'edit_language_word' => 'language',
    'delete_language_word' => 'language',
    'language_options' => 'language',

    //medias
'media_browser' => 'media',
'media_get_menu_files' => 'media',
'media_get_files' => 'media',
'media_upload' => 'media',
'delete_media' => 'media',
'get_media_metadata' => 'media',

//meta_tags seo
'seo' => 'meta_tags',
'saveseo' => 'meta_tags',
'savesets' => 'meta_tags',
'showfeed' => 'meta_tags',
'savepings' => 'meta_tags',

//modules
'modules' => 'modules',
'module_status' => 'modules',
'module_edit' => 'modules',
'module_edit_boxess' => 'modules',
'home_module' => 'modules',
'upload_module' => 'modules',

//mtsn
'mtsn_admin' => 'mtsn',
'set_config' => 'mtsn',
'ip_ban_page' => 'mtsn',
'deleteip' => 'mtsn',
'addnewip' => 'mtsn',
'clearallip' => 'mtsn',
'searchip' => 'mtsn',

//hreferrer
'hreferrer' => 'referrers',
'delreferrer' => 'referrers',

//settings
'settings' => 'settings',
'save_configs' => 'settings',
'options_menu' => 'settings',
'general_config' => 'settings',
'themes_config' => 'settings',
'comments_config' => 'settings',
'language_config' => 'settings',
'referers_config' => 'settings',
'mailing_config' => 'settings',
'security_config' => 'settings',
'uploads_config' => 'settings',
'forums_config' => 'settings',
'smilies_config' => 'settings',
'sms_config' => 'settings',
'others_config' => 'settings',

//upgrade
'upgrade' => 'upgrade',

//reports
'reports' => 'reports',
'reports_delete' => 'reports',

//nav_menus
'nav_menus' => 'nav_menus',
'nav_menus_admin' => 'nav_menus',

//grapesjs
'grapesjs' => 'grapesjs',
];

if (isset($modules[$op])) {
include_once "admin/modules/{$modules[$op]}.php";
} else {
exit("Invalid operation: $op");
}
