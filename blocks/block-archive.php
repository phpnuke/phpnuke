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

global $db, $nuke_configs, $HijriCalendar;

$content = "";

$content .=
    "<div class=\"text-center\"><font class=\"content\">" .
    _SELECTMONTH2VIEW .
    "</font><br><br></div>";
$result = $db
    ->table(POSTS_TABLE)
    ->where('post_type', 'Articles')
    ->where('ihome', '1')
    ->order_by(['time' => 'DESC'])
    ->select();
$content .= "<ul>";
$thismonth = ""; //gregorian date
$thisjmonth = ""; //jalali date
$thishmonth = ""; //hijri date

if ($db->count()) {
    foreach ($result as $row) {
        $time = $row['time'];
        if ($nuke_configs['datetype'] == 1) {
            $j_datetime = [
                date("Y", $time),
                date("m", $time),
                date("d", $time),
            ];
            $jalalidate = gregorian_to_jalali(
                $j_datetime[0],
                $j_datetime[1],
                $j_datetime[2]
            );
            if ($jalalidate[1] != $thisjmonth) {
                $month = $nuke_configs['j_month_name'][$jalalidate[1]];
                $month2 = str_replace(" ", "-", $month);
                $content .=
                    "<li><a href=\"" .
                    LinkToGT(
                        "index.php?modname=Articles&op=article_archive&year=$jalalidate[0]&month=$jalalidate[1]&month_l=$month2"
                    ) .
                    "\">$month, $jalalidate[0]</a>";
                $thisjmonth = $jalalidate[1];
            }
        } elseif ($nuke_configs['datetype'] == 2) {
            $dateTimes = $HijriCalendar->GregorianToHijri($time);
            $hgetdate = $dateTimes[0] - 1;
            if ($dateTimes[0] != $thishmonth) {
                $month = $nuke_configs['A_month_name'][$hgetdate];
                $month2 = str_replace(" ", "-", $month);
                $content .=
                    "<li><a href=\"" .
                    LinkToGT(
                        "index.php?modname=Articles&op=article_archive&year=$dateTimes[2]&month=$dateTimes[0]&month_l=$month2"
                    ) .
                    "\">$month, $dateTimes[2]</a>";
                $thishmonth = $dateTimes[0];
            }
        } else {
            $dateTimes_year = date("Y", $time);
            $dateTimes_month = date("m", $time);
            $dateTimes_month = intval($dateTimes_month);
            if ($dateTimes_month != $thismonth) {
                $month = $nuke_configs['g_month_name'][$dateTimes_month];
                $month2 = str_replace(" ", "-", $month);
                $content .=
                    "<li><a href=\"" .
                    LinkToGT(
                        "index.php?modname=Articles&op=article_archive&&year=$dateTimes_year&month=$dateTimes_month&month_l=$month2"
                    ) .
                    "\">$month, $dateTimes_year</a>";
                $thismonth = $dateTimes_month;
            }
        }
    }
}
$content .=
    "</ul><br><br>
<div class=\"text-center\">[ <a href=\"" .
    LinkToGT("index.php?modname=Articles&op=article_archive") .
    "\">" .
    _SHOWALLARTICLES .
    "</a> ]</div>";

?>
