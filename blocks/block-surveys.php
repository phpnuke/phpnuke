<?php
/**
 *
 * This file is part of the PHP-NUKE Software package.
 *
 * @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('BLOCK_FILE')) {
    Header("Location: ../index.php");
    die();
}

global $db,
    $module_name,
    $nuke_configs,
    $block_global_contents,
    $comments_op,
    $hooks;

$content = "";

$block_global_contents = [];
$block_global_contents = $hooks->apply_filters(
    "global_contents",
    $block_global_contents
);

$hooks->add_filter(
    "site_theme_headers",
    function ($theme_setup) use ($nuke_configs) {
        $theme_setup = array_merge_recursive($theme_setup, [
            "defer_js" => [
                "<script src=\"" .
                $nuke_configs['nukecdnurl'] .
                "includes/Ajax/jquery/bootstrap/js/bootstrap-progressbar.js\" type=\"text/javascript\"></script>",
                '<script>$(document).ready(function() {$(\'.progress .progress-bar\').progressbar();});</script>',
            ],
        ]);
        return $theme_setup;
    },
    10
);

$nuke_surveys_cacheData = change_poll_status();

$pollID = 0; // change to special poll
if (!isset($pollID) || $pollID == 0) {
    $this_pollID = 0;

    foreach ($nuke_surveys_cacheData as $pollID => $poll_data) {
        if (
            isset($block_global_contents['module_name']) &&
            $block_global_contents['module_name'] == $module_name &&
            isset($block_global_contents['post_id']) &&
            $poll_data['post_id'] == $block_global_contents['post_id']
        ) {
            $this_pollID = $pollID;
            $title .=
                "(" .
                _RELATEDTO .
                " " .
                $block_global_contents['post_title'] .
                " )";
            break;
        }
    }

    if ($this_pollID == 0) {
        foreach ($nuke_surveys_cacheData as $pollID => $poll_data) {
            if ($poll_data['main_survey'] == 1 && $poll_data['status'] == 1) {
                $this_pollID = $pollID;
                break;
            }
        }
    }
}
if ($this_pollID != 0) {
    $poll_data = $nuke_surveys_cacheData[$pollID];

    if (!empty($poll_data) && intval($poll_data['status']) != 1) {
        $content = _POLL_IS_DESABLED;
    } elseif (empty($poll_data)) {
        $content = _POLL_NOT_EXISTS;
    } else {
        $content .= pollMain($nuke_surveys_cacheData, $pollID, true);
    }
} else {
    $content = _NO_ACTIVE_POLL_FOUND;
}

?>
