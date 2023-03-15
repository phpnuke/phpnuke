<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('BLOCK_FILE')) {
    header("Location: ../index.php");
    exit();
}

global $nuke_configs, $db;

$params = array();
$tags = array();

$params[] = 0;
$params[] = 5;

$result = $db->query("SELECT * FROM ".TAGS_TABLE." ORDER BY counter DESC LIMIT ?,?", $params);

if($db->count()) {
    foreach($result as $row) {
        $tag_id = intval($row['tag_id']);
        $tag = filter($row['tag'], "nohtml");
        $counter = intval($row['counter']);
        $visits = intval($row['visits']);
        $link = LinkToGT("index.php?modname=Articles&tags=".$tag."");
        $tags[$tag_id] = array(
            "tag_id" => $tag_id,
            "tag" => $tag,
            "counter" => $counter,
            "visits" => $visits,
            "link" => $link
        );
    }
}

if(!empty($tags)) {
    $content = MT_Cloud_Tag($tags);
} else {
    $content = _NOTAGFOUND;
}

?>
