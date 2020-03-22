<?php
/***********************************************************/
/* Jonathan Estrella (kiedis.axl@gmail.com)			*/
/* http://slaytanic.tk / http://metalrebelde.net.tc		*/
/* Copyright © 2004-2006 by Jonathan Estrella		*/
/***********************************************************/

$module_name = basename(dirname(__FILE__));
$mod_name = "Ajax Feedback";
$author_email = "Mojtaba [AT] MashhadTeam [DOT] com";
$author_homepage = "http://PHPNuke.ir";
$author_name = "Mojtaba Amini";
$license = "GNU/GPL";
$download_location = "http://PHPNuke.ir";
$module_version = "1.0.0";
$module_description = "Ajax FeedBack Module";

function show_copyright() {
    global $mod_cost, $forum, $mod_name, $module_name, $release_date, $author_name, $author_email, $author_homepage, $license, $download_location, $module_version, $module_description;
    if ($mod_name == "") { $mod_name = @str_replace("_", " ", $module_name); }
    echo "<html>\n";
    echo "<head><title>$mod_name: Copyright Information</title></head>\n";
    echo "<body bgcolor=\"#FFFFFF\" link=\"#000000\" alink=\"#000000\" vlink=\"#000000\">\n";
    echo "<div style=\"text-align: center;\">\n";
    echo "<font size=\"2\" face=\"Arial, Helvetica\"><span style=\"font-weight: bold;\">Module Copyright &copy; Information</span><br>";
    echo "$mod_name module for <a href=\"http://phpnuke.org\" target=\"new\">PHP-Nuke</a><br>[<a href=\"javascript:void(0)\" onClick=javascript:self.close()>Close Window</a>]</font>\n";
    echo "</div>\n";
    echo "<hr>\n";
    echo "<font size=\"2\" face=\"Arial, Helvetica\">";
    echo "<img src=\"../../images/arrow.gif\" border=\"0\">&nbsp;<span style=\"font-weight: bold;\">Module's Name:</span> $mod_name<br>\n";
    if ($module_version != "") { echo "<img src=\"../../images/arrow.gif\" border=\"0\">&nbsp;<span style=\"font-weight: bold;\">Module's Version:</span> $module_version<br>\n"; }
    if ($license != "") { echo "<img src=\"../../images/arrow.gif\" border=\"0\">&nbsp;<span style=\"font-weight: bold;\">License:</span> $license<br>\n"; }
    if ($author_name != "") { echo "<img src=\"../../images/arrow.gif\" border=\"0\">&nbsp;<span style=\"font-weight: bold;\">Author's Name:</span> $author_name<br>\n"; }
    if ($author_homepage != "") { echo "<img src=\"../../images/arrow.gif\" border=\"0\">&nbsp;<span style=\"font-weight: bold;\">Author's Homepage:</span> $author_homepage<br>\n"; }
    if ($module_description != "") { echo "<img src=\"../../images/arrow.gif\" border=\"0\">&nbsp;<span style=\"font-weight: bold;\">Module's Description:</span> $module_description<br>\n"; }
    if ($download_location != "") { echo "<img src=\"../../images/arrow.gif\" border=\"0\">&nbsp;<span style=\"font-weight: bold;\">Module's Download:</span> <a href=\"$download_location\" target=\"new\">Download</a><br>\n"; }
    echo "<hr>\n";
    echo "</font>\n";
    echo "</body>\n";
    echo "</html>";
}

show_copyright();

?>