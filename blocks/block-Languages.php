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

if ( !defined('BLOCK_FILE') ) {
    Header("Location: ../index.php");
    die();
}

global $nuke_configs, $cdatetype;
	$sel0 = (!isset($cdatetype)) ? "selected":"";
	$sel1 = (isset($cdatetype) && $cdatetype == 1) ? "selected":"";
	$sel2 = (isset($cdatetype) && $cdatetype == 2) ? "selected":"";
	$sel3 = (isset($cdatetype) && $cdatetype == 3) ? "selected":"";
	
    $content = "<div class=\"row text-center\"><span class=\"content\">"._SELECTDATETYPE."<br><br></span>";
    $content .= "
	<link rel=\"stylesheet\" href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/select2.css\">
	<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/select2.min.js\" /></script>
	<select class=\"selectpicker\" style=\"width: 75%\" onchange=\"top.location.href=this.options[this.selectedIndex].value\">
		<option value=\"".$nuke_configs['nukeurl']."index.php?datetype=0\" $sel0>"._DEFAULT."</option>
		<option value=\"".$nuke_configs['nukeurl']."index.php?datetype=1\"  $sel1>"._JALALI."</option>
		<option value=\"".$nuke_configs['nukeurl']."index.php?datetype=2\"  $sel2>"._HIJRI."</option>
		<option value=\"".$nuke_configs['nukeurl']."index.php?datetype=3\"  $sel3>"._JULIAN."</option>
	</select>";
	if(_DIRECTION == 'rtl')
	{
	$content .= "<script>
	$(function(){
		$(\".selectpicker\").select2({
			dir: \"rtl\"
		});
	});
	</script>";
	}
	$content .= "<br><br>
	<span class=\"content\">"._SELECTGUILANG."<br><br>";
	$all_languages = get_dir_list('language', 'files');
	$cols_number = (12/sizeof($all_languages));
	foreach($all_languages as $language)
	{
		if($language == 'index.html' || $language == '.htaccess' || $language == 'alphabets.php') continue;
		$language = str_replace(".php", "", $language);
		$altlang = ucfirst($language);
		$content .= "<div class=\"col-xs-$cols_number\"><a href=\"".$nuke_configs['nukeurl']."index.php?lang=$language\"><img src=\"".$nuke_configs['nukeurl']."images/language/flag-$language.png\" border=\"0\" alt=\"$altlang\" title=\"$altlang\" height=\"16\" width=\"30\"></a></div>\n";
	}
	$content .= "<br>
	</span>
	</div>";

?>