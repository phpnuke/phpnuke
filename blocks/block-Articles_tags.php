<?php
/**
 *
 * This file is part of the PHP-NUKE Software package.
 *
 * @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/* Block to fit perfectly in the center of the site, remember that not all
 blocks looks good on Center, just try and see yourself what fits your needs */

if (!defined('BLOCK_FILE')) {
    Header("Location: ../index.php");
    die();
}

global $nuke_configs, $db;

$params = [];
$tags = [];

$params[] = 0;
$params[] = 5;
$result = $db->query(
    "SELECT * FROM " . TAGS_TABLE . " ORDER BY counter DESC LIMIT ?,?",
    $params
);

if ($db->count()) {
    foreach ($result as $row) {
        $tag_id = intval($row['tag_id']);
        $tag = filter($row['tag'], "nohtml");
        $counter = intval($row['counter']);
        $visits = intval($row['visits']);
        $link = LinkToGT("index.php?modname=Articles&tags=" . $tag . "");
        $tags[$tag_id] = [
            "tag_id" => $tag_id,
            "tag" => $tag,
            "counter" => $counter,
            "visits" => $visits,
            "link" => $link,
        ];
    }
}
if (!empty($tags)) {
    $content = MT_Cloud_Tag($tags);
} else {
    $content = _NOTAGFOUND;
}

?>
