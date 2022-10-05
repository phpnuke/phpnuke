<?PHP

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2017 by MashhadTeam                                    */
/* http://www.phpnuke.ir                                                */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('ADMIN_FILE'))
{
  die ("Access Denied");
}

global $db, $admin_file;

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

if (check_admin_permission($filename))
{
	global $nuke_configs;

	function settings()
	{
		global $admin_file, $nuke_configs, $hooks;
		
		$hooks->add_filter("set_page_title", function(){return array("settings" => _GENERAL_SYSTEM_SETTINGS);});
		
		$other_admin_configs = $hooks->apply_filters("other_admin_configs", []);
		
		$contents = '';
		$contents .="
		<script type=\"text/javascript\" src=\"admin/template/js/jquery/jquery.scrollabletab.js\"></script>
		<script type=\"text/javascript\">
		$(function() {
			var index = $('#container ul li[id=\"'+window.location.hash.replace('#','')+'\"]').index();
			if(index == (-1))
				index = 0;

			$('#container').tabs({
				active : parseInt(index),
				hide: { effect: 'fade', duration: 300 },
				beforeActivate: function(event, ui) {
					ui.oldPanel.empty();
					ui.newPanel.html('<img src=\"images/ajax-loader.gif\" />');
				},
				scrollable: true,
				changeOnScroll: false,
				closable: true
			});                            
		});
		</script>";
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><font class='title'><b>" . _GENERAL_SYSTEM_SETTINGS . "</b></font></div>";
		$contents .= CloseAdminTable();
		$contents .= "<br>";
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><b>"._SITECONFIGURE."</b><br><br>";
		$contents .= "<div id=\"container\">
            <ul>
                <li id=\"general_config\"><a href=\"".$admin_file.".php?op=general_config\"><span>"._GENERAL."</span></a></li>
                <li id=\"themes_config\"><a href=\"".$admin_file.".php?op=themes_config\"><span>"._THEMES."</span></a></li>
                <li id=\"comments_config\"><a href=\"".$admin_file.".php?op=comments_config\"><span>"._COMMENTS."</span></a></li>
                <li id=\"language_config\"><a href=\"".$admin_file.".php?op=language_config\"><span>"._LANGUAGES."</span></a></li>
                <li id=\"referers_config\"><a href=\"".$admin_file.".php?op=referers_config\"><span>"._REFERRERS."</span></a></li>
                <li id=\"mailing_config\"><a href=\"".$admin_file.".php?op=mailing_config\"><span>"._MAILING."</span></a></li>";
				if(is_God()) $contents .="<li id=\"uploads_config\"><a href=\"".$admin_file.".php?op=uploads_config\"><span>"._FILE_UPLOAD."</span></a></li>";
				$contents .="<li id=\"forums_config\"><a href=\"".$admin_file.".php?op=forums_config\"><span>"._FORUMS_AND_USERS_SYSTEM."</span></a></li>
				<li id=\"smilies_config\"><a href=\"".$admin_file.".php?op=smilies_config\"><span>"._SMILIES."</span></a></li>
				<li id=\"sms_config\"><a href=\"".$admin_file.".php?op=sms_config\"><span>"._SMS."</span></a></li>
				<li id=\"others_config\"><a href=\"".$admin_file.".php?op=others_config\"><span>"._OTHERS."</span></a></li>";
				if(isset($other_admin_configs) && is_array($other_admin_configs) && !empty($other_admin_configs))
				{
					foreach($other_admin_configs as $config_name => $config_data)
					{
						if($config_name == 'themes') continue;
						if(isset($config_data['God']) && $config_data['God'] && !is_God()) continue;
						if(isset($config_data['title']))
						{
							$config_data['title'] = (defined("".$config_data['title']."")) ? constant($config_data['title']):$config_data['title'];
							$contents .="<li id=\"$config_name\"><a href=\"".$admin_file.".php?op=others_config&other_admin_config=$config_name\"><span>".$config_data['title']."</span></a></li>";
						}
					}
				}
            $contents .= "</ul>
		</div>
		</div>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/calendar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-ar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-fa.js\" type=\"text/javascript\"></script>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function save_configs($submit, $config_fields, $return_op = 'settings', $log_message = '', $array_level = array())
	{
		global $db, $admin_file, $hooks, $pn_Sessions, $pn_Cookies;
		
		$nuke_configs = get_cache_file_contents('nuke_configs');
		
		if(isset($submit) && isset($config_fields))
		{
			$config_fields = $hooks->apply_filters("save_cinfigs", $config_fields, $return_op, $log_message, $array_level);
			
			if(isset($config_fields['have_forum']))
			{
				$users_table = $db->query("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME IN ('".$nuke_configs['forum_prefix']."users') AND TABLE_SCHEMA='".$config_fields['forum_db']."'");
				if($users_table->count() <= 0)
					$config_fields['have_forum'] = 0;
			}
			
			$insert_query = array();
			$query_set = array();
			$query_IN = array();
			$array_level = array_merge(array('upload_allowed_info','comments'), $array_level);
			
			foreach($config_fields as $key => $val){
				
				if(is_array($val))
				{
					if(in_array($key, $array_level))
					{
						$val = phpnuke_serialize($val);
					}
					elseif(!is_list_array($val) || is_multi_array($val))
					{
						$val = phpnuke_serialize($val);
					}
					else
						$val = implode(",", $val);
				}
				
				if(!array_key_exists($key, $nuke_configs))
				{
					$insert_query[] = array($key, $val);
					continue;
				}
				
				$query_set[] = "WHEN config_name = '$key' THEN :$key";
				$query_val[":$key"] = $val;
				$params_index[] = "?";
				$query_IN[] = $key;
			}
			
			$query_set = $hooks->apply_filters("save_cinfigs_update", $query_set, $config_fields);
			$insert_query = $hooks->apply_filters("save_cinfigs_insert", $insert_query, $config_fields);
			
			if(!empty($query_set))
			{
				$query_set = implode("\n", $query_set);
				$params_index = implode(" , ", $params_index);
				$params = array_merge($query_val, $query_IN);
				
				$db->query("UPDATE ".CONFIG_TABLE." SET config_value = CASE 
					$query_set
				END
				WHERE config_name IN ($params_index)", $params);
			}
			
			if(!empty($insert_query))
			{				
				$db->table(CONFIG_TABLE)
					->multiinsert(["config_name", "config_value"], $insert_query);
			}
			
			if(isset($config_fields['language']))
				$pn_Cookies->set("nukelang",$config_fields['language'],(365*24*3600));
				
			$hooks->do_action("save_configs_after", $config_fields, $return_op, $log_message, $array_level);
			
			cache_system("nuke_configs");
			$pn_Sessions->destroy();
			$log_message = ($log_message != '') ? $log_message:_CONFIGS_LOG;
			add_log($log_message, 1);
			redirect_to("".$admin_file.".php?op=$return_op");
			die();
		}	
	}
	
	function general_config()
	{
		global $nuke_configs, $admin_file, $hooks;
		$contents = '';
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
		
		$contents .= "<div class=\"text-center\"><font class='option'><b>" . _GENSITEINFO . "</b></font></div>
		<form action='".$admin_file.".php' method='post'>
		<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
		<tr><th style=\"width:250px\">" . _SITENAME . "</th><td><input class=\"inp-form\" type='text' name='config_fields[sitename]' value='".filter($nuke_configs['sitename'], "nohtml")."' size='40' maxlength='255'>
		</td></tr>
		<tr><th>" . _SITEURL . "</th><td>
		<input class=\"inp-form dleft\" type='text' name='config_fields[nukeurl]' value='".filter($nuke_configs['nukeurl'], "nohtml")."' size='40' maxlength='255'>
		".bubble_show(_SITEURL_HELP)."
		</td></tr>
		<tr><th>" . _LOCK_SITEURL . "</th><td>";
		$check1 = (intval($nuke_configs['lock_siteurl']) == 1) ? "checked":"";
		$check2 = (intval($nuke_configs['lock_siteurl']) == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[lock_siteurl]' value='1' data-label=\""._YES."\" $check1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[lock_siteurl]' value='0' data-label=\""._NO."\" $check2>
		</td></tr>		
		<tr><th>" . _SITECDNURL . "</th><td>
		<input class=\"inp-form dleft\" type='text' name='config_fields[nukecdnurl]' value='".filter($nuke_configs['nukecdnurl'], "nohtml")."' size='40' maxlength='255'>
		</td></tr>
		<tr><th>" . _SITESLOGAN . "</th><td><input class=\"inp-form\" type='text' name='config_fields[slogan]' value='".filter($nuke_configs['slogan'], "nohtml")."' size='40' maxlength='255'>
		</td></tr>
		<tr><th>" . _STARTDATE . "</th><td>
		<input type=\"text\" name='config_fields[startdate]' class=\"inp-form-ltr calendar\" id=\"startdate\" value=\"".filter($nuke_configs['startdate'], "nohtml")."\">
		</td></tr>";
		$sel1= (intval($nuke_configs['datetype']) == 1) ? "selected":"";
		$sel2= (intval($nuke_configs['datetype']) == 2) ? "selected":"";
		$sel3= (intval($nuke_configs['datetype']) == 3) ? "selected":"";
		$contents .="<tr><th>" . _NUKEDATETYPE . "</th><td><select name=\"config_fields[datetype]\" class=\"styledselect-select\"><option value=\"1\" $sel1>" . _JALALI . "</option><option value=\"2\" $sel2>" . _HIJRI . "</option><option value=\"3\" $sel3>" . _JULIAN . "</option></select>";
		$sel1=""; $sel2=""; $sel3="";
		$contents .="</td></tr>
		<tr><th>" . _ADMINGRAPHIC . "</th><td>";
		$check1 = (intval($nuke_configs['admingraphic']) == 1) ? "checked":"";
		$check2 = (intval($nuke_configs['admingraphic']) == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[admingraphic]' value='1' data-label=\""._YES."\" $check1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[admingraphic]' value='0' data-label=\""._NO."\" $check2>
		</td></tr>";
		
		$sel1 = (intval($nuke_configs['nuke_editor']) == 0) ? "selected":"";
		$sel2 = (intval($nuke_configs['nuke_editor']) == 1) ? "selected":"";
		$contents .= "<tr><th>"._SITE_EDITOR."</th><td>
		<select name='config_fields[nuke_editor]' class=\"styledselect-select\">
			<option name='config_fields[nuke_editor]' value='0' $sel1>"._WITHOUTEDITOR."</option>
			<option name='config_fields[nuke_editor]' value='1' $sel2>"._WITHEDITOR."</option>
		</select></td></tr>
		</td></tr>
		<tr><th>"._DISPLAYPHPERR."</th><td>";
		$check1 = (intval($nuke_configs['display_errors']) == 1) ? "checked":"";
		$check2 = (intval($nuke_configs['display_errors']) == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[display_errors]' value='1' data-label=\""._YES."\" $check1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[display_errors]' value='0' data-label=\""._NO."\" $check2>";

		$contents .= "<tr><th>"._SHOW_LINKS_GUESTS."</th><td>";
		
		$check1 = (intval($nuke_configs['show_links']) == 1) ? "checked":"";
		$check2 = (intval($nuke_configs['show_links']) == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[show_links]' value='1' data-label=\""._YES."\" $check1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[show_links]' value='0' data-label=\""._NO."\" $check2>";

		$contents .= "</td></tr>";
		$contents .= "<tr><th>"._SHOW_IMAGE_EFFECT."</th><td>";
		$check1 = (intval($nuke_configs['show_effect']) == 1) ? "checked":"";
		$check2 = (intval($nuke_configs['show_effect']) == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[show_effect]' value='1' data-label=\""._YES."\" $check1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[show_effect]' value='0' data-label=\""._NO."\" $check2>";

		$contents .= "</td></tr><tr><th>"._VOTETYPE."</th><td>";
		
		$votetype_ch1 = (intval($nuke_configs['votetype']) == 1) ? "checked":"";
		$votetype_ch2 = (intval($nuke_configs['votetype']) == 2) ? "checked":"";
		$votetype_ch3 = (intval($nuke_configs['votetype']) == 3) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[votetype]' value='1' data-label=\""._STAR_VOTE."\" $votetype_ch1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[votetype]' value='2' data-label=\""._POSITIVEANDNEGATIVE_VOTE."\" $votetype_ch2> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[votetype]' value='3' data-label=\"" . _LIKE_DISLIKE_VOTE . "\" $votetype_ch3>";

		$contents .= "</td></tr><tr><th>"._ENABLE_MOBILE_MODE."</th><td>";
		$check1 = (intval($nuke_configs['mobile_mode']) == 1) ? "checked":"";
		$check2 = (intval($nuke_configs['mobile_mode']) == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[mobile_mode]' value='1' data-label=\"" . _ACTIVE . "\" $check1> &nbsp;<input type='radio' class='styled' name='config_fields[mobile_mode]' value='0' data-label=\"" . _INACTIVE . "\" $check2>";

		$contents .= "</td></tr><tr><th>"._ENABLE_ADMIN_BAR."</th><td>";
		$check1 = (intval($nuke_configs['admin_bar']) == 1) ? "checked":"";
		$check2 = (intval($nuke_configs['admin_bar']) == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[admin_bar]' value='1' data-label=\"" . _ACTIVE . "\" $check1> &nbsp;<input type='radio' class='styled' name='config_fields[admin_bar]' value='0' data-label=\"" . _INACTIVE . "\" $check2>";

		$contents .="</td></tr>
		<tr><td>&nbsp;</td><td halign=\"left\"><input type='hidden' name='op' value='save_configs'><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<br><br><input class=\"form-submit\" type='submit' name='submit' name='submit' value='" . _SAVECHANGES . "'></form></td></tr></table>";
		$contents = $hooks->apply_filters("general_config", $contents);
		die($contents);
	}
	
	function themes_config()
	{
		global $admin_file, $nuke_configs, $hooks;
		
		$other_admin_configs = $hooks->apply_filters("other_admin_configs", []);
		
		$contents = '';
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
		$contents .="
		<div class=\"text-center\"><font class='option'><b>"._SELECT_SITE_TEMPLATE."</b></font></div>
		<form action='".$admin_file.".php' method='post'>
		<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
		<tr><th style=\"width:250px;\">" . _DEFAULTTHEME . "</th><td><select name='config_fields[Default_Theme]' class=\"styledselect-select\">";
		$handle=opendir("themes");
		while ($file = readdir($handle))
		{
			if ($file != '.' && $file != '..' && $file !="index.html" && $file != ".htaccess")
			{
				$themelist[] = $file;
			}
		}
		closedir($handle);
		sort($themelist);
		foreach($themelist as $theme_name)
		{
			$sel = ($theme_name == $nuke_configs['Default_Theme']) ? " selected":"";
			$contents .= "<option name='config_fields[Default_Theme]' value='$theme_name'$sel>$theme_name</option>\n";
		}
		//$contents .= "</select>".bubble_show("برای ویرایش قالب مورد نظرتان <a href=\"\" id=\"edit_theme\">اینجا</a> را کلیک کنید.")."";
		$contents .= "</td></tr>";
		$contents .="
		<tr><th>"._LETUSERSCHANGETHM."</th><td>";
		$psel1= ($nuke_configs['overwrite_theme'] == 1) ? "checked":"";
		$psel2= ($nuke_configs['overwrite_theme'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[overwrite_theme]' value='1' data-label=\"" . _YES . "\" $psel1> &nbsp;
			<input type='radio' class='styled' name='config_fields[overwrite_theme]' value='0' data-label=\"" . _NO . "\" $psel2>";
		$contents .= "".bubble_show(_LETUSERSCHANGETHM_HELP)."</td></tr>";
		
		$contents .="
		<tr><th>"._SPECIAL_SITEINDEX."</th><td>";
		$psel1= ($nuke_configs['website_index_theme'] == 1) ? "checked":"";
		$psel2= ($nuke_configs['website_index_theme'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[website_index_theme]' value='1' data-label=\"" . _YES . "\" $psel1> &nbsp;
			<input type='radio' class='styled' name='config_fields[website_index_theme]' value='0' data-label=\"" . _NO . "\" $psel2>";
		$contents .= "".bubble_show(_SPECIAL_SITEINDEX_HELP)."</td></tr>";

		if(isset($other_admin_configs['themes'][$nuke_configs['Default_Theme']]))
		{
			$config_function = $other_admin_configs['themes'][$nuke_configs['Default_Theme']]['function'];
			$contents .= $config_function();
		}
		
		$contents .="
		<tr><td colspan=\"2\"><input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr>
		<input type='hidden' name='return_op' value='settings#themes_config'>
		<input type='hidden' name='op' value='save_configs'>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>
		</table>";
		$contents = $hooks->apply_filters("themes_config", $contents);
		die($contents);		
	}
		
	function comments_config()
	{
		global $nuke_configs, $admin_file, $hooks;
		$contents = '';
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
		$comments_configs = phpnuke_unserialize(stripslashes($nuke_configs['comments']));
		
		$contents .="
		<div class=\"text-center\"><font class='option'><b>"._COMMENTSCONFIG."</b></font></div>
		<form action='".$admin_file.".php' method='post'>
		<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
		
		<tr><th>"._COMMENTS_ACTIVATE."</th><td>";
		$checked1 = ($comments_configs['allow'] == 1) ? "checked":"";
		$checked2 = ($comments_configs['allow'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[comments][allow]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[comments][allow]' value='0' data-label=\"" . _NO . "\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th style=\"width:280px\">"._ALLOWANOMCOM."</th><td>";
		$checked1 = ($comments_configs['anonymous'] == 1) ? "checked":"";
		$checked2 = ($comments_configs['anonymous'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[comments][anonymous]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[comments][anonymous]' value='0' data-label=\"" . _NO . "\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._CONFIRM_NEED."</th><td>";
		$checked1 = ($comments_configs['confirm_need'] == 1) ? "checked":"";
		$checked2 = ($comments_configs['confirm_need'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[comments][confirm_need]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[comments][confirm_need]' value='0' data-label=\"" . _NO . "\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._ALLOWED_COMMENTS_CHAR."</th><td><input class=\"inp-form\" type='text' name='config_fields[comments][limit]' value='".$comments_configs['limit']."' size='11' maxlength='10'>".bubble_show(_ALLOWED_COMMENTS_CHAR_HELP)."
		</td></tr>
		
		<tr><th>" . _COMMENT_EDITOR . "</th><td>";
		$checked1 = ($comments_configs['editor'] == 1) ? "checked":"";
		$checked2 = ($comments_configs['editor'] == 2) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[comments][editor]' value='1' data-label=\"" . _SIMPLE . "\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[comments][editor]' value='2' data-label=\"" . _ADVANCED . "\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._COMMENTS_NAME_REQUIRED."</th><td>";
		$checked1 = (isset($comments_configs['inputs']['name_act']) && $comments_configs['inputs']['name_act'] == 1) ? "checked":"";
		$checked2 = (isset($comments_configs['inputs']['name_req']) && $comments_configs['inputs']['name_req'] == 1) ? "checked":"";
		$contents .= "<input type='checkbox' class='styled' name='config_fields[comments][inputs][name_act]' value='1' data-label=\""._ACTIVE."/"._INACTIVE."\" $checked1> &nbsp;&nbsp;&nbsp;<input type='checkbox' class='styled' name='config_fields[comments][inputs][name_req]' value='1' data-label=\""._REQUIRED."/"._NOT_REQUIRED."\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._COMMENTS_NAME_CHANGE."</th><td>";
		$checked = (isset($comments_configs['inputs']['name_enter']) && $comments_configs['inputs']['name_enter'] == 1) ? "checked":"";
		$contents .= "<input type='checkbox' class='styled' name='config_fields[comments][inputs][name_enter]' value='1' data-label=\""._ACTIVE."/"._INACTIVE."\" $checked>";
		$contents .= "</td></tr>
		
		<tr><th>"._COMMENTS_EMAIL_REQUIRED."</th><td>";
		$checked1 = (isset($comments_configs['inputs']['email_act']) && $comments_configs['inputs']['email_act'] == 1) ? "checked":"";
		$checked2 = (isset($comments_configs['inputs']['email_req']) && $comments_configs['inputs']['email_req'] == 1) ? "checked":"";
		$contents .= "<input type='checkbox' class='styled' name='config_fields[comments][inputs][email_act]' value='1' data-label=\""._ACTIVE."/"._INACTIVE."\" $checked1> &nbsp;<input type='checkbox' class='styled' name='config_fields[comments][inputs][email_req]' value='1' data-label=\""._REQUIRED."/"._NOT_REQUIRED."\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._COMMENTS_EMAIL_CHANGE."</th><td>";
		$checked = (isset($comments_configs['inputs']['email_enter']) && $comments_configs['inputs']['email_enter'] == 1) ? "checked":"";
		$contents .= "<input type='checkbox' class='styled' name='config_fields[comments][inputs][email_enter]' value='1' data-label=\""._ACTIVE."/"._INACTIVE."\" $checked>";
		$contents .= "</td></tr>
		
		<tr><th>"._COMMENTS_WEBSITE_REQUIRED."</th><td>";
		$checked1 = (isset($comments_configs['inputs']['url_act']) && $comments_configs['inputs']['url_act'] == 1) ? "checked":"";
		$checked2 = (isset($comments_configs['inputs']['url_req']) && $comments_configs['inputs']['url_req'] == 1) ? "checked":"";
		$contents .= "<input type='checkbox' class='styled' name='config_fields[comments][inputs][url_act]' value='1' data-label=\""._ACTIVE."/"._INACTIVE."\" $checked1> &nbsp;<input type='checkbox' class='styled' name='config_fields[comments][inputs][url_req]' value='1' data-label=\""._REQUIRED."/"._NOT_REQUIRED."\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._COMMENTS_WEBSITE_CHANGE."</th><td>";
		$checked = (isset($comments_configs['inputs']['url_enter']) && $comments_configs['inputs']['url_enter'] == 1) ? "checked":"";
		$contents .= "<input type='checkbox' class='styled' name='config_fields[comments][inputs][url_enter]' value='1' data-label=\""._ACTIVE."/"._INACTIVE."\" $checked>";
		$contents .= "</td></tr>
		
		<tr><th>"._ADMIN_NOTIFY_METHOD_COMMENTS."</th><td>";
		$checked1 = (isset($comments_configs['notify']['email']) && $comments_configs['notify']['email'] == 1) ? "checked":"";
		$checked2 = (isset($comments_configs['notify']['sms']) && $comments_configs['notify']['sms'] == 1) ? "checked":"";
		$contents .= "<input type='checkbox' class='styled' name='config_fields[comments][notify][email]' value='1' data-label=\""._EMAIL."\" $checked1> &nbsp;<input type='checkbox' class='styled' name='config_fields[comments][notify][sms]' value='1' data-label=\""._SMS."\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._COMMENTS_VIEW_ORDER."</th><td>";
		$checked1 = ($comments_configs['order_by'] == 1) ? "checked":"";
		$checked2 = ($comments_configs['order_by'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[comments][order_by]' value='1' data-label=\""._DESCENDING."\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[comments][order_by]' value='0' data-label=\""._ASCENDING."\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._COMMENTS_RATING."</th><td>";
		$checked1 = ($comments_configs['allow_rating'] == 1) ? "checked":"";
		$checked2 = ($comments_configs['allow_rating'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[comments][allow_rating]' value='1' data-label=\""._ACTIVE."\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[comments][allow_rating]' value='0' data-label=\""._INACTIVE."\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._COMMENTS_REPORTING."</th><td>";
		$checked1 = ($comments_configs['allow_reporting'] == 1) ? "checked":"";
		$checked2 = ($comments_configs['allow_reporting'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[comments][allow_reporting]' value='1' data-label=\""._ACTIVE."\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[comments][allow_reporting]' value='0' data-label=\""._INACTIVE."\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._COMMENTS_PER_PAGE."</th><td>
			<input class=\"inp-form\" type='text' name='config_fields[comments][item_per_page]' value=\"".$comments_configs['item_per_page']."\" />".bubble_show(_ZERO_MEANS_UNLIMITED)."
		</td></tr>
		
		<tr><th>"._NESTED_COMMENTS_DEPTH."</th><td>
			<input class=\"inp-form\" type='text' name='config_fields[comments][depth]' value=\"".$comments_configs['depth']."\" />".bubble_show(_ZERO_MEANS_UNLIMITED)."
		</td></tr>
		
		<tr><td colspan=\"2\"><input type='hidden' name='op' value='save_configs'><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>";
		$contents .= "
		<div class=\"text-center\"><font class='option'><b>"._COMMENTS_DELETE."</b></font></div>
		<form action='".$admin_file.".php' method='post'>
		<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
				
		<tr><th style=\"width:280px;\">"._ENTER_USERNAME_OR_IP."</th><td><input class=\"inp-form\" type='text' name='comment_username'>
		</td></tr>
		
		<tr><td colspan=\"2\"><input type='hidden' name='op' value='comments_delete'>
		<input type='hidden' name='return_op' value='settings#comments_config'><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>";
		$contents = $hooks->apply_filters("comments_config", $contents);
		die($contents);		
	}
		
	function language_config()
	{
		global $nuke_configs, $admin_file, $hooks;
		$contents = '';
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
			
		$contents .= "
		<div class=\"text-center\"><font class='option'><b>"._LANGUAGESCONFIG."</b></font></div>
		<form action='".$admin_file.".php' method='post'>
		<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
		
		<tr><th style=\"width:280px\">"._SELLANGUAGE."</th><td>
		<select name='config_fields[language]' class=\"styledselect-select\">";
		$languageslists = get_dir_list('language', 'files');
		foreach($languageslists as $language_name)
		{
			if($language_name != "")
			{
				if($language_name == 'index.html' || $language_name == '.htaccess' || $language_name == 'alphabets.php') continue;
				$language_name = str_replace(".php", "", $language_name);
				$sel = ($language_name == $nuke_configs['language']) ? "selected":"";
				$contents .= "<option value=\"$language_name\" $sel>".ucfirst($language_name)."</option>\n";
			}
		}
		$contents .= "</select>
		</td></tr>
		
		<tr><th style=\"width:280px\">"._LOCALEFORMAT."</th><td>
		<select name='config_fields[locale]' class=\"styledselect-select\">";
		$locales = array('aa_DJ','aa_ER','aa_ER@saaho','aa_ET','af_ZA','agr_PE','ak_GH','am_ET','anp_IN','an_ES','ar_AE','ar_BH','ar_DZ','ar_EG','ar_IN','ar_IQ','ar_JO','ar_KW','ar_LB','ar_LY','ar_MA','ar_OM','ar_QA','ar_SA','ar_SD','ar_SS','ar_SY','ar_TN','ar_YE','ast_ES','as_IN','ayc_PE','ay_PE','az_AZ','bem_ZM','bem_ZM','ber_DZ','ber_MA','be_BY','be_BY@latin','bg_BG','bho_IN','bi_TV','bn_BD','bn_IN','bo_CN','brx_IN','br_FR','bs_BA','byn_ER','ca_ES','ce_RU','cmn_TW','crh_UA','csb_PL','cs_CZ','cv_RU','cy_GB','da_DK','de_AT','de_BE','de_CH','de_DE','de_LU','doi_IN','dv_MV','dz_BT','el_GR','en_AU','en_CA','en_DK','en_GB','en_HK','en_IE','en_IN','en_NG','en_NZ','en_PH','en_SG','en_US','en_ZA','es_AR','es_BO','es_CL','es_CO','es_CR','es_DO','es_EC','es_ES','es_GT','es_HN','es_MX','es_NI','es_PA','es_PE','es_PR','es_PY','es_SV','es_US','es_UY','es_VE','et_EE','eu_ES','fa_IR','ff_SN','fil_PH','fi_FI','fo_FO','fr_BE','fr_CA','fr_CH','fr_FR','fr_LU','fur_IT','fy_DE','fy_NL','ga_IE','gd_GB','gez_ER','gez_ET','gl_ES','gu_IN','gv_GB','hak_TW','ha_NG','he_IL','hi_IN','hne_IN','hr_HR','hsb_DE','ht_HT','hu_HU','hy_AM','ia_FR','id_ID','ig_NG','ik_CA','is_IS','it_CH','it_IT','iu_CA','ja_JP','ka_GE','kk_KZ','kl_GL','km_KH','kn_IN','kok_IN','ko_KR','ks_IN','ks_IN@devanagari','ku_TR','kw_GB','ky_KG','lb_LU','lg_UG','lij_IT','li_BE','li_NL','lo_LA','lt_LT','lv_LV','lzh_TW','mag_IN','mg_MG','mhr_RU','mh_MH','mi_NZ','mk_MK','ml_IN','mni_IN','mn_MN','mr_IN','ms_MY','mt_MT','myv_RU','myv_RU@cyrillic','my_MM','nan_TW','nan_TW@latin','nb_NO','nds_DE','nds_NL','ne_NP','nhn_MX','niu_NU','niu_NZ','nl_BE','nl_NL','nn_NO','nr_ZA','nso_ZA','oc_FR','om_ET','om_KE','or_IN','os_RU','pap_AN','pap_AW','pap_CW','pa_IN','pa_PK','pl_PL','ps_AF','pt_BR','pt_PT','quz_PE','ro_RO','ru_RU','ru_UA','rw_RW','sat_IN','sa_IN','sc_IT','sd_IN','sd_IN@devanagari','se_NO','shs_CA','sid_ET','si_LK','sk_SK','sl_SI','son_ML','so_DJ','so_ET','so_KE','so_SO','sq_AL','sr_ME','sr_RS','sr_RS@latin','ss_ZA','st_ZA','sv_FI','sv_SE','sw_KE','sw_KE','sw_TZ','szl_PL','ta_IN','te_IN','tg_TJ','th_TH','tig_ER','ti_ER','ti_ET','tk_TM','tl_PH','tn_ZA','tr_TR','ts_ZA','tt_RU','tt_RU@iqtelif','ug_CN','uk_UA','unm_US','ur_IN','ur_PK','uz_UZ','uz_UZ@cyrillic','ve_ZA','vi_VN','wae_CH','wal_ET','wa_BE','wo_SN','xh_ZA','yi_US','yo_NG','yue_HK','zh_CN','zh_HK','zh_SG','zh_TW','zu_ZA');
		foreach($locales as $locale)
		{
			$sel = ($locale == $nuke_configs['locale']) ? "selected":"";
			$contents .= "<option value=\"$locale\" $sel>$locale</option>";
		}
		$contents .= "</select>
		</td></tr>
		
		<tr><th>"._ACTMULTILINGUAL."</th><td>";
		$checked1 = ($nuke_configs['multilingual'] == 1) ? "checked":"";
		$checked2 = ($nuke_configs['multilingual'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[multilingual]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[multilingual]' value='0' data-label=\"" . _NO . "\" $checked2>";
		$contents .= "</td></tr>		
		<tr><td colspan=\"2\"><input type='hidden' name='op' value='save_configs'>
		<input type='hidden' name='return_op' value='settings#language_config'>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>";
		$contents = $hooks->apply_filters("language_config", $contents);
		die($contents);		
	}
			
	function referers_config()
	{
		global $nuke_configs, $admin_file, $hooks;
		$contents = '';
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
		
		$contents .="
		<div class=\"text-center\"><font class='option'><b>"._REFCONFIG."</b></font></div>
		<form action='".$admin_file.".php' method='post'>
		<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">

		<tr><th style=\"width:300px;\">"._ACTIVATEHTTPREF."</th><td>";
		$checked1 = ($nuke_configs['httpref'] == 1) ? "checked":"";
		$checked2 = ($nuke_configs['httpref'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[httpref]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[httpref]' value='0' data-label=\"" . _NO . "\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._REFSHOWMODE."</th><td>";
		$checked1 = ($nuke_configs['httprefmode'] == 1) ? "checked":"";
		$checked2 = ($nuke_configs['httprefmode'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[httprefmode]' value='1' data-label=\"" . _ABRIDGED . "\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[httprefmode]' value='0' data-label=\""._UNABRIDGED."\" $checked2>" . "";
		$contents .= "</td></tr>
		
		<tr><th>"._MAXREF."</th><td>
			<input type=\"text\" name='config_fields[httprefmax]' class=\"inp-form\" value=\"".$nuke_configs['httprefmax']."\">
		</td></tr>
		
		<tr><td colspan=\"2\"><input type='hidden' name='op' value='save_configs'>
		<input type='hidden' name='return_op' value='settings#referers_config'>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>";
		$contents = $hooks->apply_filters("referers_config", $contents);
		die($contents);		
	}
	
	function mailing_config()
	{
		global $nuke_configs, $admin_file, $hooks;
		$contents = '';
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs

		$contents .="
		<div class=\"text-center\"><font class='option'><b>"._MAILCONFIG."</b></font></div>
		<form action='".$admin_file.".php' method='post'>
		<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">

		<tr><th>"._ADMINEMAIL."</th><td>
			<input type=\"text\" name='config_fields[adminmail]' class=\"inp-form-ltr\" size=\"40\" value=\"".$nuke_configs['adminmail']."\">
			".bubble_show(_ADMINEMAILDESC)."
		</td></tr>
		<tr><th>"._ADMINEMAILNAME."</th><td>
			<input type=\"text\" name='config_fields[adminmail_name]' class=\"inp-form\" size=\"40\" value=\"".$nuke_configs['adminmail_name']."\">
			".bubble_show(_ADMINEMAILNAMEDESC)."
		</td></tr>
		
		<tr><th style=\"width:300px;\">"._NOTIFYSUBMISSION."</th><td>";
		$checked1 = ($nuke_configs['notify'] == 1) ? "checked":"";
		$checked2 = ($nuke_configs['notify'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[notify]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[notify]' value='0' data-label=\"" . _NO . "\" $checked2>";
		$contents .= "</td></tr>
		
		<tr><th>"._EMAILSUBJECT."</th><td>
			<input type=\"text\" name='config_fields[notify_subject]' size=\"40\" class=\"inp-form\" value=\"".$nuke_configs['notify_subject']."\">
		</td></tr>
		
		<tr><th>"._EMAILMSG."</th><td>
			<textarea class=\"form-textarea\" name='config_fields[notify_message]' cols='70' rows='5'>".$nuke_configs['notify_message']."</textarea>
		</td></tr>
		
		<tr><th>"._SMTP_SERVER."</th><td>
			<input type=\"text\" name='config_fields[smtp_email_server]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['smtp_email_server']."\">
		</td></tr>
		
		<tr><th>"._USERNAME."</th><td>
			<input type=\"text\" name='config_fields[smtp_email_user]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['smtp_email_user']."\">
		</td></tr>
		
		<tr><th>"._PASSWORD."</th><td>
			<input type=\"password\" name='config_fields[smtp_email_pass]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['smtp_email_pass']."\">
		</td></tr>
		
		<tr><th style=\"width:300px;\">"._EMAIL_SEND_IN_PROTOCOL."</th><td>";
		$checked1 = ($nuke_configs['smtp_secure'] == 'ssl') ? "checked":"";
		$checked2 = ($nuke_configs['smtp_secure'] == 'tls') ? "checked":"";
		$checked3 = ($nuke_configs['smtp_secure'] == '') ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[smtp_secure]' value='ssl' data-label=\"ssl\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[smtp_secure]' value='tls' data-label=\"tls\" $checked2> &nbsp;<input type='radio' class='styled' name='config_fields[smtp_secure]' value='' data-label=\""._ANYONE."\" $checked3>";
		$contents .= "</td></tr>
		
		<tr><th>"._PORT."</th><td>
			<input type=\"text\" name='config_fields[smtp_port]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['smtp_port']."\">
		</td></tr>
		
		<tr><th>"._EMAIL_ERROR_SHOW."</th><td>
			<input type=\"number\" min=\"0\" max=\"4\" name='config_fields[smtp_debug]' size=\"40\" class=\"inp-form\" value=\"".$nuke_configs['smtp_debug']."\">
		</td></tr>
		
		<tr><th style=\"width:300px;\">"._HTML_EMAIL."</th><td>";
		$checked1 = ($nuke_configs['is_html_mail'] == 1) ? "checked":"";
		$checked2 = ($nuke_configs['is_html_mail'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[is_html_mail]' value='1' data-label=\""._ACTIVE."\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[is_html_mail]' value='0' data-label=\""._INACTIVE."\" $checked2> ";
		$contents .= "</td></tr>
		
		<tr><th style=\"width:300px;\">"._EMAIL_ATTACHEMENT."</th><td>";
		$checked1 = ($nuke_configs['allow_attachement_mail'] == 1) ? "checked":"";
		$checked2 = ($nuke_configs['allow_attachement_mail'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[allow_attachement_mail]' value='1' data-label=\""._ACTIVE."\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[allow_attachement_mail]' value='0' data-label=\""._INACTIVE."\" $checked2> ";
		$contents .= "</td></tr>
		
		<tr><td colspan=\"2\"><input type='hidden' name='op' value='save_configs'>
		<input type='hidden' name='return_op' value='settings#mailing_config'>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>";
		$contents = $hooks->apply_filters("mailing_config", $contents);
		die($contents);		
	}
	
	function uploads_config()
	{
		global $admin_file, $hooks;
		$contents = '';
		if(is_God())
		{
			$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
			
			global $nuke_configs;
			$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
			$nuke_configs['upload_pagesitems'] = ($nuke_configs['upload_pagesitems'] > 100) ? 100:$nuke_configs['upload_pagesitems'];
			$contents .= "
			<div class=\"text-center\"><font class='option'><b>"._FILE_UPLOAD_CONIFG."</b><br /><br />"._ONLY_MULTIMEDIA_FILES_ALLOWED."<br /><br /></font></div>
			<form action='".$admin_file.".php' method='post'>
			<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">

			<tr><th style=\"width:320px;\">"._FILES_PER_PAGE."</th><td>
				<input type=\"text\" name='config_fields[upload_pagesitems]' class=\"inp-form-ltr\" value=\"".$nuke_configs['upload_pagesitems']."\">
				".bubble_show(_NUMBER_IN_HUNDRED)."
			</td></tr>
			
			<tr><th colspan=\"2\" style=\"text-align:center;\">"._ALLOWED_ADMIN_PATH."</th></tr>";
			$upload_allowed_info = phpnuke_unserialize(stripslashes($nuke_configs['upload_allowed_info']));
			foreach($nuke_authors_cacheData as $admin_id => $author_info)
			{
				if(is_God($admin_id)) continue;
				$admin_id_path_value = isset($upload_allowed_info[$admin_id]['path']) ? $upload_allowed_info[$admin_id]['path']:"";
				$contents .= "<tr><th>$admin_id</th><td>
				<input type=\"text\" name='config_fields[upload_allowed_info][$admin_id][path]' class=\"inp-form-ltr\" value=\"$admin_id_path_value\" size=\"40\" />
			</td></tr>";
			}
			
			$contents .= "<tr><td colspan=\"2\"><input type='hidden' name='op' value='save_configs'>
			<input type='hidden' name='return_op' value='settings#uploads_config'>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>";
			$contents = $hooks->apply_filters("uploads_config", $contents);
			die($contents);
		}
		else
		{
			$contents = $hooks->apply_filters("uploads_config_permission", $contents);
			die('no God');
		}
	}
	
	function forums_config()
	{
		global $nuke_configs, $db, $pn_dbname, $users_system, $admin_file, $hooks;
		$contents = '';
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
						
		$contents .="
		<div class=\"text-center\"><font class='option'><b>"._FORUM_AND_USERS_SYSTEM_CONFIG."</b></font></div>
		<form action='".$admin_file.".php' method='post'>
		<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
		
		<tr><th style=\"width:200px;\">"._HAVE_FORUM."</th><td>";
		$checked1 = ($nuke_configs['have_forum'] == 1) ? "checked":"";
		$checked2 = ($nuke_configs['have_forum'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[have_forum]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[have_forum]' value='0' data-label=\"" . _NO . "\" $checked2>
		".bubble_show(_HAVE_FORUM_HELP)."
		</td></tr>
		
		<tr><th style=\"width:200px;\">"._ENABLE_ADVANCED_FORUM_LINK."</th><td>";
		$checked1 = ($nuke_configs['forum_GTlink_active'] == 1) ? "checked":"";
		$checked2 = ($nuke_configs['forum_GTlink_active'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[forum_GTlink_active]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[forum_GTlink_active]' value='0' data-label=\"" . _NO . "\" $checked2>
		</td></tr>
		<tr><th>"._LAST_FORUM_IN_BLOCK."</th><td>
			<input type=\"text\" name='config_fields[forum_last_number]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_last_number']."\">
		</td></tr>		
		<tr><th colspan=\"2\" style=\"text-align:center\">"._ADVANCED_FORUM_LINKS."</th></tr>
		<tr><th>"._POST_URL_ENTRY."</th><td>
			<input type=\"text\" name='config_fields[forum_seo_post_link]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_seo_post_link']."\">
			".bubble_show(""._TOPIC_ID_HELP." - "._POST_ID_HELP." - "._FORUM_ID_HELP." - "._POST_NAME_HELP."")."
		</td></tr>
		<tr><th>"._TOPIC_URL_ENTRY."</th><td>
			<input type=\"text\" name='config_fields[forum_seo_topic_link]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_seo_topic_link']."\">
			".bubble_show(""._TOPIC_ID_HELP." - "._FORUM_ID_HELP." - "._TOPIC_NAME_HELP."")."
		</td></tr>
		<tr><th>"._FORUM_URL_ENTRY."</th><td>
			<input type=\"text\" name='config_fields[forum_seo_forum_link]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_seo_forum_link']."\">
			".bubble_show(""._FORUM_ID_HELP." - "._FORUM_NAME_HELP."")."
		</td></tr>
		<tr><th>"._VIEW_PROFILE_URL_ENTRY."</th><td>
			<input type=\"text\" name='config_fields[forum_seo_profile_link]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_seo_profile_link']."\">
			".bubble_show(""._USER_ID_HELP." - "._USERNAME_HELP."")."
		</td></tr>
		<tr><th>"._EDIT_PROFILE_URL_ENTRY."</th><td>
			<input type=\"text\" name='config_fields[forum_seo_ucp_link]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_seo_ucp_link']."\">
			".bubble_show(""._USER_ID_HELP." - "._USERNAME_HELP."")."
		</td></tr>
		<tr><th>"._INBOX_URL_ENTRY."</th><td>
			<input type=\"text\" name='config_fields[forum_seo_pm_link]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_seo_pm_link']."\">
		</td></tr>
		<tr><th>"._REGISTER_URL_ENTRY."</th><td>
			<input type=\"text\" name='config_fields[forum_seo_register_link]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_seo_register_link']."\">
		</td></tr>
		<tr><th>"._LOGIN_URL_ENTRY."</th><td>
			<input type=\"text\" name='config_fields[forum_seo_login_link]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_seo_login_link']."\">
		</td></tr>
		<tr><th>"._FORGET_PASSWORD_URL_ENTRY."</th><td>
			<input type=\"text\" name='config_fields[forum_seo_passlost_link]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_seo_passlost_link']."\">
		</td></tr>
		<tr><th>"._LOGOUT_URL_ENTRY."</th><td>
			<input type=\"text\" name='config_fields[forum_seo_logout_link]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_seo_logout_link']."\">
		</td></tr>
		
		<tr><th>collation</th><td>
			<input type=\"text\" name='config_fields[forum_collation]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_collation']."\">
		</td></tr>
		
		<tr><th>"._FORUM_PATH."</th><td>
			<input type=\"text\" name='config_fields[forum_path]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_path']."\">
			".bubble_show(_FORUM_PATH_HELP)."
		</td></tr>
		
		<tr><th>"._FORUM_SYSTEM."</th><td><select name='config_fields[forum_system]' class=\"styledselect-select\">";
		$handle=opendir(INCLUDE_PATH."/forums_classes");
		while ($file = readdir($handle))
		{
			if ($file != '.' && $file != '..' && $file !="index.html" && $file != ".htaccess" && $file != "class.no_forum.php" && !is_dir(INCLUDE_PATH."/forums_classes/".$file))
			{
				$file = str_replace(array("class.",".php"),"", $file);
				$forumslist[] = $file;
			}
		}
		closedir($handle);
		sort($forumslist);
		foreach($forumslist as $forum_name)
		{
			$sel = ($forum_name == $nuke_configs['forum_system']) ? " selected":"";
			$contents .= "<option value='$forum_name'$sel>$forum_name</option>\n";
		}
		$contents .= "</select>
		</td></tr>
		
		<tr><th>"._FORUM_DBNAME."</th><td>
			<input type=\"text\" name='config_fields[forum_db]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_db']."\">
			".bubble_show(_FORUM_DBNAME_HELP)."
		</td></tr>
		
		<tr><th>"._FORUM_PREFIX."</th><td>
			<input type=\"text\" name='config_fields[forum_prefix]' size=\"40\" class=\"inp-form-ltr\" value=\"".$nuke_configs['forum_prefix']."\">
		</td></tr>
		
		<tr><td colspan=\"2\"><input type='hidden' name='op' value='save_configs'>
		<input type='hidden' name='return_op' value='settings#forums_config'>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>";
		$contents = $hooks->apply_filters("forums_config", $contents);
		die($contents);
	}
	
	function others_config($other_admin_config = '')
	{
		global $nuke_configs, $admin_file, $other_admin_configs, $hooks;
		$contents = '';
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
		
		$other_admin_configs = $hooks->apply_filters("other_admin_configs", []);
		
		if($other_admin_config != '' && isset($other_admin_configs[$other_admin_config]) && isset($other_admin_configs[$other_admin_config]['function']) && function_exists($other_admin_configs[$other_admin_config]['function'])) 
		{
			$contents .= $other_admin_configs[$other_admin_config]['function']();
		}
		else
		{
			$contents .="
			<div class=\"text-center\"><font class='option'><b>"._OTHER_SETTINGS."</b></font></div>
			
			<form action='".$admin_file.".php' method='post'>
			<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
			
			<tr><th style=\"width:200px;\">"._ITEMS_PER_PAGE."</th><td>
				<input type=\"text\" name='config_fields[home_pagination]' class=\"inp-form-ltr\" value=\"".$nuke_configs['home_pagination']."\">
			</td></tr>
			
			<tr><th>"._PAGINATION_ITEMS."</th><td>
				<input type=\"text\" name='config_fields[pagination_number]' class=\"inp-form-ltr\" value=\"".$nuke_configs['pagination_number']."\">
			</td></tr>
			
			<tr><th>"._MAINSITECOOKIPATH."</th><td>
				<input type=\"text\" name='config_fields[sitecookies]' class=\"inp-form-ltr\" value=\"".$nuke_configs['sitecookies']."\">
			</td></tr>
			
			<tr>
				<th>"._TIMTHUMB_ALLWED."</th>
				<td>
					<textarea class=\"form-textarea\" name=\"config_fields[timthumb_allowed]\" style=\"height: 100px; width: 400px;direction:ltr;padding:10px;\">".$nuke_configs['timthumb_allowed']."</textarea>
				".bubble_show(_TIMTHUMB_ALLWED_HELP)."
				</td>
			</tr>	
			
			<tr><th>"._SESSIONS_PREFIX."</th><td>
				<input type=\"text\" name='config_fields[sessions_prefix]' class=\"inp-form-ltr\" value=\"".$nuke_configs['sessions_prefix']."\">
			</td></tr>
			
			<tr><th>"._LOGS_ITEMS_SAVED."</th><td>
				<input type=\"text\" name='config_fields[max_log_numbers]' class=\"inp-form-ltr\" value=\"".$nuke_configs['max_log_numbers']."\">
				".bubble_show(_LOGS_ITEMS_SAVED_HELP)."
			</td></tr>
			
			<tr><th>"._CSRF_TOKEN_EXPIRETIME."</th><td>
				<input type=\"text\" name='config_fields[csrf_token_time]' class=\"inp-form-ltr\" value=\"".$nuke_configs['csrf_token_time']."\">
				".bubble_show(_ENTER_TIME_IN_SEC)."
			</td></tr>
			
			<tr><th>"._STATISTICS_REFRESH_TIME."</th><td>
				<input type=\"text\" name='config_fields[statistics_refresh]' class=\"inp-form-ltr\" value=\"".$nuke_configs['statistics_refresh']."\">
			</td></tr>
			
			<tr><th>"._SITE_SUSPEND."</th><td>";
			$checked1 = ($nuke_configs['suspend_site'] == 1) ? "checked":"";
			$checked2 = ($nuke_configs['suspend_site'] == 0) ? "checked":"";
			$contents .= "<input type='radio' class='styled' name='config_fields[suspend_site]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[suspend_site]' value='0' data-label=\"" . _NO . "\" $checked2>";
			$contents .= "</td></tr>
			
			<tr><th>"._SUSPEND_STARTAT."</th><td>";
				$contents .="<input type=\"text\" name='config_fields[suspend_start]' class=\"inp-form-ltr calendar\" id=\"suspend_start\" value=\"".$nuke_configs['suspend_start']."\">
			</td></tr>
			<tr><th>"._SUSPEND_ENDAT."</th><td>
				<input type=\"text\" name='config_fields[suspend_expire]' class=\"inp-form-ltr calendar\" id=\"suspend_expire\" value=\"".$nuke_configs['suspend_expire']."\">
				".bubble_show(_SUSPEND_ENDAT_HELP)."
			</td></tr>
			
			<tr><th>"._SUSPEND_HTML_TEMPLATE."</th><td>
			<textarea id=\"html_code_editor\" class=\"simple-code-editor\" name=\"config_fields[suspend_template]\" style=\"height: 300px; width: 98%;direction:ltr;padding:10px;\">".$nuke_configs['suspend_template']."</textarea>
			</td></tr>
			
			<tr><th>"._MINIFY_SOURCE."</th><td>";
			$checked1 = (isset($nuke_configs['minify_src']) && $nuke_configs['minify_src'] == 1) ? "checked":"";
			$checked2 = (isset($nuke_configs['minify_src']) && $nuke_configs['minify_src'] == 0) ? "checked":"";
			$contents .= "<input type='radio' class='styled' name='config_fields[minify_src]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[minify_src]' value='0' data-label=\"" . _NO . "\" $checked2>";
			$contents .= "</td></tr>
			
			<tr><td colspan=\"2\"><input type='hidden' name='op' value='save_configs'>
			<input type='hidden' name='return_op' value='settings#others_config'>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>
			
			<script src=\"admin/template/js/jquery/jquery.selection.js\" type=\"text/javascript\"></script>";
		}
		$contents = $hooks->apply_filters("others_config", $contents, $other_admin_config);
		
		die($contents);
	}
	
	function smilies_config()
	{
		global $nuke_configs, $admin_file, $hooks;
		$contents = '';
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
		$nuke_configs['smilies'] = (isset($nuke_configs['smilies'])) ? $nuke_configs['smilies']:"";
		$smilies_configs = ($nuke_configs['smilies'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['smilies'])):array();
		$contents .="
		<div class=\"text-center\"><font class='option'><b>"._SMILIES_SETTINGS."</b></font></div>
		
		<form action='".$admin_file.".php' method='post'>
		<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
		
		<tr>
			<th>"._SMILIES_FIELDS." <span class=\"add_field_icon add_field_button\" title=\""._ADD_NEW_FIELD."\" data-fields-max=\"1000\" data-fields-wrapper=\".input_fields_wrap\" data-fields-html=\"#input_fields_wrap_html\"></span></th>
			<td>
				<template id=\"input_fields_wrap_html\">
					<div style=\"margin-bottom:3px;\">
						<input placeholder=\""._TITLE."\" type=\"text\" class=\"inp-form-ltr\" value=\"\" name=\"config_fields[smilies][{X}][name]\" size=\"20\" />&nbsp;
						<input placeholder=\""._CODE."\" type=\"text\" class=\"inp-form-ltr\" value=\"\" name=\"config_fields[smilies][{X}][code]\" size=\"20\" />&nbsp;
						<input placeholder=\""._URL."\" type=\"text\" class=\"inp-form-ltr\" value=\"\" name=\"config_fields[smilies][{X}][url]\" size=\"30\" />&nbsp;
						<input placeholder=\""._DIMENTIONS."\" type=\"text\" class=\"inp-form-ltr\" value=\"\" name=\"config_fields[smilies][{X}][dimentions]\" size=\"10\" />&nbsp; &nbsp; 
						<a href=\"#\" class=\"remove_field\">"._REMOVE."</a>
					</div>
				</template>
				<div class=\"input_fields_wrap\">";
					if(!empty($smilies_configs))
					{
						foreach($smilies_configs as $x1 => $smilie_data)
						{
							$smilie_data = array_filter($smilie_data);
							if(empty($smilie_data))
								continue;
								
							$smilie_name = $smilie_data['name'];
							$option_code = $smilie_data['code'];
							$option_url = $smilie_data['url'];
							$option_dimentions = $smilie_data['dimentions'];
							$option_dimentions_arr = explode("*", $option_dimentions);
							$width = (isset($option_dimentions_arr[0])) ? $option_dimentions_arr[0]:20;
							$height = (isset($option_dimentions_arr[1])) ? $option_dimentions_arr[1]:20;
							$contents .= "
							<div style=\"margin-bottom:3px;\" data-key=\"$x1\">
								<input placeholder=\""._TITLE."\" type=\"text\" class=\"inp-form-ltr\" value=\"$smilie_name\" name=\"config_fields[smilies][$x1][name]\" size=\"20\" />&nbsp;
								<input placeholder=\""._CODE."\" type=\"text\" class=\"inp-form-ltr\" value=\"$option_code\" name=\"config_fields[smilies][$x1][code]\" size=\"20\" />&nbsp;
								<input placeholder=\""._URL."\" type=\"text\" class=\"inp-form-ltr\" value=\"$option_url\" name=\"config_fields[smilies][$x1][url]\" size=\"30\" />&nbsp;
								<input placeholder=\""._DIMENTIONS."\" type=\"text\" class=\"inp-form-ltr\" value=\"$option_dimentions\" name=\"config_fields[smilies][$x1][dimentions]\" size=\"10\" />&nbsp; &nbsp; 
								<img src=\"".LinkToGT($option_url)."\" width=\"$width\" height=\"$height\" /> 
								<a href=\"#\" class=\"remove_field\">"._REMOVE."</a>
							</div>";
						}
					}
				$contents .="
			</td>
		</tr>
		<tr><td colspan=\"2\"><input type='hidden' name='op' value='save_configs'>
		<input type='hidden' name='return_op' value='settings#smilies_config'>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>		
		<script src=\"admin/template/js/jquery/jquery.selection.js\" type=\"text/javascript\"></script>";
		$contents = $hooks->apply_filters("smilies_config", $contents);
		die($contents);
	}
	
	function sms_config()
	{
		global $nuke_configs, $admin_file, $hooks;
		$contents = '';
		$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs
		$nuke_configs['pn_sms'] = (isset($nuke_configs['pn_sms'])) ? $nuke_configs['pn_sms']:"";
		$pn_sms = ($nuke_configs['pn_sms'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['pn_sms'])):array();
				
		$contents .="
		<div class=\"text-center\"><font class='option'><b>"._SMS_SETTINGS."</b></font></div>
		
		<form action='".$admin_file.".php' method='post'>
		<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
		
		<tr><th style=\"width:300px;\">"._ACTIVE."</th><td>";
		$checked1 = ($nuke_configs['sms'] == 1) ? "checked":"";
		$checked2 = ($nuke_configs['sms'] == 0) ? "checked":"";
		$contents .= "<input type='radio' class='styled' name='config_fields[sms]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp;<input type='radio' class='styled' name='config_fields[sms]' value='0' data-label=\"" . _NO . "\" $checked2>";
		$contents .= "</td></tr>
		
		<tr>
			<th>"._SMS_DEFAULT_OPERATOR."</th>
			<td><select name=\"config_fields[pn_sms][operator]\" class=\"styledselect-select\">";
				$sms_operators_list = get_dir_list(INCLUDE_PATH.'/sms', "files", true);
				foreach($sms_operators_list as $sms_operator)
				{
					if($sms_operator == 'index.html' || $sms_operator == '.htaccess') continue;
					$sms_operator = str_replace(array("class.",".php"), "", $sms_operator);
					$sel = ($sms_operator == $pn_sms['operator']) ? "selected":"";
					$contents .="<option value=\"$sms_operator\" $sel>$sms_operator</option>";
				}
			$contents .="</td>
		</tr>
		<tr>
			<th>"._USERNAME."</th>
			<td>
				<input type=\"text\" name='config_fields[pn_sms][username]' size=\"40\" class=\"inp-form-ltr\" value=\"".$pn_sms['username']."\">
			</td>
		</tr>
		<tr>
			<th>"._PASSWORD."</th>
			<td>
				<input type=\"text\" name='config_fields[pn_sms][password]' size=\"40\" class=\"inp-form-ltr\" value=\"".$pn_sms['password']."\">
			</td>
		</tr>
		<tr>
			<th>"._SMS_NUMBER."</th>
			<td>
				<input type=\"text\" name='config_fields[pn_sms][default_number]' size=\"40\" class=\"inp-form-ltr\" value=\"".$pn_sms['default_number']."\">
			</td>
		</tr>
		<tr>
			<th>"._SMS_CREDIT."</th>
			<td>
				".pn_sms('get_credit')." "._RIAL."
			</td>
		</tr>
		<tr>
			<th>"._SMS_RECIPIENTS."</th>
			<td>
				<input type=\"text\" name='config_fields[pn_sms][recipients]' size=\"40\" class=\"inp-form-ltr\" value=\"".$pn_sms['recipients']."\">
			</td>
		</tr>
		<tr><td colspan=\"2\"><input type='hidden' name='op' value='save_configs'>
		<input type='hidden' name='return_op' value='settings#sms_config'>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>";
		$contents = $hooks->apply_filters("sms_config", $contents);
		die($contents);
	}
	
	$submit				= (isset($submit))				? filter($submit, "nohtml"):'';
	$log_message		= (isset($log_message))			? filter($log_message, "nohtml"):'';
	$return_op			= (isset($return_op))			? filter($return_op, "nohtml"):'settings';
	$other_admin_config	= (isset($other_admin_config))	? filter($other_admin_config, "nohtml"):'';
	$config_fields		= (isset($config_fields))		? $config_fields:array();
	$array_level		= (isset($array_level))			? $array_level:array();
	
	switch($op)
	{
		
		default:
			settings();
		break;
		
		case "save_configs":
			save_configs($submit, $config_fields, $return_op, $log_message, $array_level);
		break;
		
		case "general_config":
			general_config();
		break;
		
		case "themes_config":
			themes_config();
		break;
		
		case "comments_config":
			comments_config();
		break;
		
		case "language_config":
			language_config();
		break;
		
		case "referers_config":
			referers_config();
		break;
		
		case "mailing_config":
			mailing_config();
		break;
		
		case "forums_config":
			forums_config();
		break;
		
		case "others_config":
			others_config($other_admin_config);
		break;
		
		case "smilies_config":
			smilies_config();
		break;
		
		case "sms_config":
			sms_config();
		break;
		
		case "uploads_config":
			uploads_config();
		break;
	}

}
else
	header("location: ".$admin_file.".php");

?>