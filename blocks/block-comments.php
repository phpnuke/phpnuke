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

global $db, $nuke_configs, $comments_op;

$content = "";

$comments = new phpnuke_comments();
$content = $comments->display_comments();

?>
