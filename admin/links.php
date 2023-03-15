<?php

if (!defined('ADMIN_FILE')) {
    die ("Access Denied");
}

$mainMenus = [
    'authors' => [
        'url' => 'admin.php?op=mod_authors',
        'label' => _EDITADMINS,
        'icon' => 'authors.png',
    ],
    'backup' => [
        'url' => 'admin.php?op=database',
        'label' => _DATABASE,
        'icon' => 'backup.png',
    ],
    'blocks' => [
        'url' => 'admin.php?op=BlocksAdmin',
        'label' => _BLOCKS,
        'icon' => 'blocks.png',
    ],
    'cache' => [
        'url' => 'admin.php?op=cache',
        'label' => _CACHE,
        'icon' => 'cache.png',
    ],
    'bookmarks' => [
        'url' => 'admin.php?op=bookmarks',
        'label' => _BOOKMARK,
        'icon' => 'bookmark.png',
    ],
    'language' => [
        'url' => 'admin.php?op=language',
        'label' => _LANGUAGE,
        'icon' => 'lang.png',
    ],
    'groups' => [
        'url' => 'admin.php?op=points_groups',
        'label' => _POINTS,
        'icon' => 'groups.png',
    ],
    'media' => [
        'url' => 'admin.php?op=media_browser',
        'label' => _MULTIMEDIA,
        'icon' => 'multimedia.png',
    ],
    'modules' => [
        'url' => 'admin.php?op=modules',
        'label' => _MODULES,
        'icon' => 'modules.png',
    ],
    'meta_tags' => [
        'url' => 'admin.php?op=seo',
        'label' => _SEO_ADMIN,
        'icon' => 'meta.png',
    ],
    'mtsn' => [
        'url' => 'admin.php?op=mtsn_admin',
        'label' => _MTSNADMIN,
        'icon' => 'mtsn.png',
    ],
    'settings' => [
        'url' => 'admin.php?op=settings',
        'label' => _PREFERENCES,
        'icon' => 'preferences.png',
    ],
    'referrers' => [
        'url' => 'admin.php?op=hreferrer',
        'label' => _WHOLINKS,
        'icon' => 'referers.png',
    ],
    'nav_menus' => [
        'url' => 'admin.php?op=nav_menus',
        'label' => _NAVS_ADMIN,
        'icon' => 'nav_menus.png',
    ],
    'upgrade' => [
        'url' => 'admin.php?op=upgrade',
        'label' => _UPGRADE,
        'icon' => 'upgrade.png',
    ],
];

foreach ($mainMenus as $menu) {
    echo adminmenu($menu['url'], $menu['label'], $menu['icon']);
}
