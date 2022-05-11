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

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

if (check_admin_permission($filename, true))
{
	function get_title_lang_name($atitle)
	{
		global $nuke_configs;
		$atitle2  = "";
		switch($atitle){
			case"authors":
				$atitle2=_AUTHORSADMIN;
			break;
			case"backup":
				$atitle2=_DATABASE;
			break;
			case"blocks":
				$atitle2=_BLOCKS;
			break;
			case"bookmarks":
				$atitle2=_BOOKMARK;
			break;
			case"cache":
				$atitle2=_CACHE;
			break;
			case"categories":
				$atitle2=_CATEGORIES;
			break;
			case"comments":
				$atitle2=_COMMENTS;
			break;
			case"groups":
				$atitle2=_GROUPS;
			break;
			case"language":
				$atitle2=_LANGUAGE;
			break;
			case"media":
				$atitle2=_MULTIMEDIA;
			break;
			case"meta_tags":
				$atitle2=_SEO_ADMIN;
			break;
			case"modules":
				$atitle2=_MODULES;
			break;
			case"mtsn":
				$atitle2=_MTSNADMIN;
			break;
			case"points_groups":
				$atitle2=_POINTS_GROUPS;
			break;
			case"patch":
				$atitle2=sprintf(_PATCH, $nuke_configs['Version_Num']);
			break;
			case"referrers":
				$atitle2=_REFERRERS;
			break;
			case"reports":
				$atitle2=_REPORTS;
			break;
			case"settings":
				$atitle2=_PREFERENCES;
			break;
			case"nav_menus":
				$atitle2=_NAV_ADMIN;
			break;
			case"upgrade":
				$atitle2=_UPGRADE;
			break;
		}
		return $atitle2;
	}
	
	function displayadmins($user_id=0)
	{
		global $admin, $hooks, $db, $users_system, $nuke_configs, $admin_file, $nuke_admins_menu_cacheData;
		
		$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
		
		include("admin/links.php");	
		
		$hooks->add_filter("set_page_title", function(){return array("displayadmins" => _AUTHORS_AND_ADMINS);});
		
		$contents = '';
		$contents .= GraphicAdmin();
		if (is_God())
		{
			if($user_id != 0)
			{
				$result = $db->query("SELECT ".$users_system->user_fields['username']." as username, ".$users_system->user_fields['realname']." as realname, ".$users_system->user_fields['user_email']." as user_email, ".$users_system->user_fields['user_website']." as user_website, ".$users_system->user_fields['user_password']." as user_password FROM ".$users_system->users_table." WHERE ".$users_system->user_fields['user_id']."= ?", [$user_id]);
				
				$row = (isset($result->results()[0])) ? $result->results()[0]:array('','','','','');
				
				list($uname, $rname, $remail, $rsite, $rupass) = array_values($row);
				$rphone = $rimage = $rrule = "";
			}
			else
				$uname = $rname = $remail = $rsite = $rupass = $rphone = $rimage = $rrule = "";

			$contents .= "<div class=\"text-center\"><font class=\"title\"><b>" . _AUTHORSADMIN . "</b></font></div>";
			
			$contents .= "<br>";
			
			$contents .= "
			<link href=\"includes/Ajax/jquery/jquery.tagit.css\" rel=\"stylesheet\" type=\"text/css\">
			<script src=\"includes/Ajax/jquery/jquery.tag-it.min.js\" type=\"text/javascript\" charset=\"utf-8\"></script>
			<div class=\"text-center\"><font class=\"option\"><b>" . _EDITADMINS . "</b></font></div>
			<br>
			<div align=\"center\">
			<table align=\"center\" class=\"product-table\" width=\"100%\"><tr>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:100px;\"><a>"._ADMIN_NAME."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:150px;\"><a>"._NICKNAME." , "._PHONE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:200px;\"><a>"._ADMIN_TASK."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;\"><a>دسترسی ها</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a>"._LANGUAGES."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:100px;\"><a>"._OPERATION."</a></th>
			</tr>";
			$result = $db->table(AUTHORS_TABLE)
							->select(["aid", "name", "realname", "admlanguage", "radminsuper", "rule", "phone", "image"]);
			if($db->count() > 0)
			{
				foreach ($result as $row)
				{
					$a_aid = filter($row['aid'], "nohtml");
					$name = filter($row['name'], "nohtml");
					$realname = filter($row['realname'], "nohtml");
					$rule = stripslashes($row['rule']);
					$phone = stripslashes($row['phone']);
					$image = stripslashes($row['image']);
					$admlanguage = $row['admlanguage'];
					$radminsuper = $row['radminsuper'];
					$a_aid = substr("$a_aid", 0,25);
					$name = substr("$name", 0,50);
					
					$admin_image = ($image != '') ? "<img src=\"$image\" width=\"60\" height=\"60\" style=\"border-radius:50%;\" /><br />":"";
					
					$contents .= "<tr><td align=\"center\">".$admin_image."".$a_aid."".(($name == "God") ? "<br /><i>("._SUPER_ADMIN.")</i>":"")."</td>";
					
					if (empty($admlanguage))
					{
						$admlanguage = "" . _ALL . "";
					}
					$contents .= "<td align=\"center\" style=\"line-height:20px;\">$realname<br />$phone</td>";
					$contents .= "<td align=\"center\">$rule</td>";
					$contents .= "<td align=\"center\">";
							if($radminsuper == 1)
							{
								$contents .=_ALL_PERMISSIONS;
							}
							else
							{
							$contents .="<ul class=\"rtl tagit ui-widget ui-widget-content ui-corner-all\" style=\"border:0;\">";
								foreach($nuke_admins_menu_cacheData as $amid => $nuke_admins_menu_info)
								{
									//$atitle = @ereg_replace("_", " ", $nuke_admins_menu_info['atitle']);
									$atitle = $nuke_admins_menu_info['atitle'];
									$atitle2 = get_title_lang_name($atitle);
									$main_admins = explode(",", $nuke_admins_menu_info['admins']);
									
									if (array_key_exists($atitle, $main_menus) && (in_array($a_aid,$main_admins)))
									{

										$contents .= "
										<li class=\"tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable\" id=\"perm_$amid\">
											<span class=\"tagit-label\">$atitle2</span>
											<a class=\"tagit-close\" data-aid=\"$a_aid\" data-amid=\"$amid\" data-type=\"admin\" data-parent=\"#perm_$amid\">
												<span class=\"text-icon\">×</span>
												<span class=\"ui-icon ui-icon-close\"></span>
											</a>
										</li>";
									}
								}
								
								
								foreach($nuke_modules_cacheData as $mid => $nuke_modules_info)
								{

									$mtitle = filter($nuke_modules_info['title'], "nohtml");
									$lang_titles = filter($nuke_modules_info['lang_titles'], "nohtml");

									$lang_titles = ($nuke_modules_info['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($nuke_modules_info['lang_titles'])):"";
								
									if($lang_titles != "" && $nuke_configs['multilingual'] == 1)
									{
										$lang_title = filter($lang_titles[$nuke_configs['currentlang']], "nohtml");
									}
									else
									{
										$lang_title = $mtitle;
									}
									$modules_admins = explode(",", $nuke_modules_info['admins']);
									
									if (file_exists("modules/".$nuke_modules_info['title']."/admin/index.php") AND file_exists("modules/".$nuke_modules_info['title']."/admin/links.php") AND file_exists("modules/".$nuke_modules_info['title']."/admin/case.php") && (in_array($a_aid,$modules_admins))) {
										$contents .= "
										<li class=\"tagit-choice ui-widget-content ui-state-default ui-corner-all tagit-choice-editable\" id=\"mperm_$mid\">
											<span class=\"tagit-label\">$lang_title</span>
											<a class=\"tagit-close\" data-aid=\"$a_aid\" data-mid=\"$mid\" data-type=\"module\" data-parent=\"#mperm_$mid\">
												<span class=\"text-icon\">×</span>
												<span class=\"ui-icon ui-icon-close\"></span>
											</a>
										</li>";
									}
								}
							$contents .="</ul>";
							}
						$contents .="</td>";
					$contents .= "<td align=\"center\">$admlanguage</td>";
					$contents .= "<td align=\"center\"><a class=\"table-icon icon-1 info-tooltip\" href=\"".$admin_file.".php?op=modifyadmin&amp;chng_aid=$a_aid\" title=\""._EDIT_ADMIN."\"></a>";
					if($name != "God")
					{
						$contents .= "<a class=\"table-icon icon-2 info-tooltip\" href=\"".$admin_file.".php?op=deladmin&amp;del_aid=$a_aid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE_ADMIN."\"></a></td></tr>";
					}
				}
			}
			$contents .= "</table>
			</div>
			<script>
				$(document).ready(function(){
					$(\".tagit-close\").on('click', function(){
						var admin_id = $(this).data('aid');
						var amid = $(this).data('amid');
						var mid = $(this).data('mid');
						var type = $(this).data('type');
						var parent = $(this).data('parent');
						
						$.post(
						'".$admin_file.".php',
						{'op' : 'remove_permission', 'admin_id' : admin_id, 'amid' : amid, 'mid' : mid , 'type' : type , 'csrf_token' : '"._PN_CSRF_TOKEN."' },
						function(data)
						{
							if(data == 'true')
							{
								$(parent).fadeOut('1000', function(){
									$(parent).remove();
								});
							}
						});
					});
				});
			</script>			
			<br>
			<div class=\"text-center\"><font class=\"tiny\">" . _GODNOTDEL . "</font></div>";
			$contents .= "<br>";
			$contents .= "<div class=\"text-center\"><font class=\"option\"><b>" . _ADD_ADMIN . "</b></font></div>
			<form action=\"".$admin_file.".php\" method=\"post\">
				<table border=\"0\" cellpadding=\"3\" cellspacing=\"3\" class=\"product-table no-border id-form\" width=\"100%\">
					<tr>
						<th width=\"130\"><b>"._DISPLAY_NAME."</th>
						<td><input type=\"text\" name=\"author_fields[add_realname]\" size=\"30\" maxlength=\"50\" class=\"inp-form\" value=\"$rname\"/> <font class=\"tiny\">" . _REQUIRED . "</font></td>
					</tr>
					<tr>
						<th>" . _NICKNAME . "</th>
						<td><input type=\"text\" style=\"direction:ltr\" name=\"author_fields[add_aid]\" size=\"30\" maxlength=\"25\" class=\"inp-form-ltr\" value=\"$uname\" /> <font class=\"tiny\">" . _REQUIRED . "</font></td>
					</tr>
					<tr>
						<th>" . _EMAIL . "</th>
						<td><input type=\"text\" style=\"direction:ltr\" name=\"author_fields[add_email]\" size=\"30\" maxlength=\"60\" class=\"inp-form-ltr\" value=\"$remail\" /> <font class=\"tiny\">" . _REQUIRED . "</font></td>
					</tr>
					<tr>
						<th>" . _URL . "</th>
						<td><input type=\"text\" style=\"direction:ltr\" name=\"author_fields[add_url]\" size=\"30\" maxlength=\"60\" class=\"inp-form-ltr\" value=\"$rsite\" /></td>
					</tr>
					<tr>
						<th>"._PHONE."</th>
						<td><input type=\"text\" style=\"direction:ltr\" name=\"author_fields[add_phone]\" size=\"30\" maxlength=\"60\" class=\"inp-form-ltr\" value=\"$rphone\" /> <font class=\"tiny\">" . _REQUIRED . "</font></td>
					</tr>
					<tr>
						<th>"._PICTURE."</th>
						<td><input type=\"text\" style=\"direction:ltr\" name=\"author_fields[add_image]\" size=\"30\" class=\"inp-form-ltr\" value=\"$rimage\" /> </td>
					</tr>
					<tr>
						<th>"._ADMIN_TASK."</th>
						<td>";
							$contents .= wysiwyg_textarea('author_fields[add_rule]', $rrule, 'Basic', 10, 5, '500px', '150px');
						$contents .="</td>
					</tr>";
					if ($nuke_configs['multilingual'] == 1)
					{
						$contents .= "<tr><th>" . _LANGUAGE . "</th>
						<td>
							<select name=\"author_fields[add_admlanguage]\" class=\"styledselect-select\">";
						
							$languageslist = get_dir_list('language', 'files');
							foreach($languageslist as $language_name)
							{
								if($language_name == '' || $language_name == 'index.html' || $language_name == '.htaccess' || $language_name == 'alphabets.php') continue;
								$language_name = str_replace(".php", "", $language_name);
								$contents .= "<option value=\"$language_name\"".(($language_name == $nuke_configs['language']) ? "selected":"").">".ucfirst($language_name)."</option>\n";
							}
							$contents .= "<option value=\"\">" . _ALL . "</option>
							</select>
						</td>
					</tr>";
					}
					else
					{
						$contents .= "<input type=\"hidden\" name=\"author_fields[add_admlanguage]\" value=\"\">";
					}
					$contents .="
					<tr><th>" . _PERMISSIONS . "</th>
						<td>
							<table width=\"100%\">
								<tr>
									<th colspan=\"4\" style=\"text-align:center;\">"._MAIN_MENUS."</th>
								</tr>";

								$i = 1;
								foreach($nuke_admins_menu_cacheData as $amid => $nuke_admins_menu_info)
								{
									//$atitle = str_replace("_", " ", $nuke_admins_menu_info['atitle']);
									$atitle = $nuke_admins_menu_info['atitle'];
									
									$atitle2 = get_title_lang_name($atitle);
									
									if (array_key_exists($atitle, $main_menus))
									{
										if($i == 1)
										{
											$contents .="<tr>\n";
										}
										$contents .= "\t<td width=\"25%\">&nbsp;<input type=\"checkbox\" data-label=\"$atitle2\" class=\"styled\" name=\"author_fields[auth_main_admins][]\" value=\"".intval($amid)."\" id=\"auth_main_admins_".intval($amid)."\"></td>\n";
										if ($i == 4)
										{
											$contents .= "</tr>\n";
											$i = 0;
										}
										$i++;
									}
									$atitle2 = "";
								}
								if($i > 1)
								{
									$diff = 4-$i;
									for($i = 0;$i <= $diff; $i++)
									{
										$contents .="<td>&nbsp;</td>";
									}
									$contents .="</tr>";
								}
						
								$contents .="
								<tr>
									<th colspan=\"4\" style=\"text-align:center;\">"._MODULES."</th>
								</tr>";
								
								$i = 1;
								foreach($nuke_modules_cacheData as $nuke_modules_key => $nuke_modules_info)
								{
									$mtitle = filter($nuke_modules_info['title'], "nohtml");
									$lang_titles = filter($nuke_modules_info['lang_titles'], "nohtml");

									$lang_titles = ($nuke_modules_info['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($nuke_modules_info['lang_titles'])):"";
									
									if($lang_titles != "" && $nuke_configs['multilingual'] == 1)
									{
										$lang_title = filter($lang_titles[$nuke_configs['currentlang']], "nohtml");
									}
									else
									{
										$lang_title = $mtitle;
									}

									if (file_exists("modules/".$nuke_modules_info['title']."/admin/index.php") AND file_exists("modules/".$nuke_modules_info['title']."/admin/links.php") AND file_exists("modules/".$nuke_modules_info['title']."/admin/case.php"))
									{
										if($i == 1)
										{
											$contents .="<tr>";
										}
										$contents .= "<td width=\"25%\">&nbsp;<input data-label=\"$lang_title\" type=\"checkbox\" class=\"styled\" name=\"author_fields[auth_modules][]\" value=\"".intval($nuke_modules_key)."\" id=\"auth_modules_".intval($nuke_modules_key)."\"></td>";
										if ($i == 4)
										{
											$contents .= "</tr>";
											$i = 0;
										}
										$i++;
									}
								}
						
								if($i > 1)
								{
									$diff = 4-$i;
									for($i = 0;$i < $diff; $i++)
									{
										$contents .= "<td>&nbsp;</td>";
									}
									$contents .= "</tr>";
								}
							$contents .= "</table>
						</td>
					</tr>";
						
					if(isset($user_id) && $user_id > 1)
					{
						$contents .= "<input type=\"hidden\" value=\"$user_id\" name=\"author_fields[user_id]\"‌/>";
					}
					$contents .= "
					<tr>
						<td colspan=\"2\"><input data-label=\"<b>" . _SUPER_ADMIN . "</b>\" type=\"checkbox\" class=\"styled\" name=\"author_fields[add_radminsuper]\" id=\"add_radminsuper\" value=\"1\"></td>
					</tr>
					<tr>
						<td colspan=\"2\"><font class=\"tiny\"><i>" . _SUPERWARNING . "</i></font></td>
					</tr>
					<tr>
						<td>" . _PASSWORD . "</td>
						<td><input type=\"password\" name=\"author_fields[add_pwd]\" size=\"12\" maxlength=\"40\" class=\"inp-form\" value=\"$rupass\" /> <font class=\"tiny\">" . _REQUIRED . "</font></td>
					</tr>
					<tr>
						<td colspan=\"2\">
							<input type=\"submit\" value=\"" . _ADD_ADMIN_SAVE . "\" class=\"form-submit\">
							<input type=\"hidden\" name=\"op\" value=\"AddAuthor\">
							<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
						</td>
					</tr>
				</table>
			</form>
			";
			phpnuke_db_error();
		}
		else
		{
			$contents .= "<div class=\"text-center\"><font class=\"title\"><b>Authors Admin</b></font></div>";
			$contents .= "<br>";
			$contents .= "<div class=\"text-center\"><b>Not Authorized</b><br><br>"
				."Unauthorized editing of authors detected<br><br>"
				.""._GOBACK."</div>";
		}
		
		$contents = $hooks->apply_filters("display_admins", $contents, $user_id);
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function modifyadmin($chng_aid)
	{
		global $admin, $hooks, $db, $nuke_configs, $admin_file, $nuke_admins_menu_cacheData;

		$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
		$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);

		if(!isset($main_menus));
			include("admin/links.php");	
			
		$hooks->add_filter("set_page_title", function() use($chng_aid){return array("modifyadmin" => _EDIT_ADMIN." : $chng_aid");});
		
		$contents = '';
		if (is_God())
		{
			$contents .= GraphicAdmin();
			$contents .= "<div class=\"text-center\"><font class=\"option\"><b>" . _EDIT_ADMIN . "</b></font></div><br><br>";
			$adm_aid = filter($chng_aid, "nohtml");
			$adm_aid = trim($adm_aid);
			$adm_aid = substr("$adm_aid", 0,25);
			
			$chng_name = filter($nuke_authors_cacheData[$adm_aid]['name'], "nohtml");
			$chng_realname = filter($nuke_authors_cacheData[$adm_aid]['realname'], "nohtml");
			$chng_url = filter($nuke_authors_cacheData[$adm_aid]['url'], "nohtml");
			$chng_email = filter($nuke_authors_cacheData[$adm_aid]['email'], "nohtml");
			$chng_pwd = filter($nuke_authors_cacheData[$adm_aid]['pwd'], "nohtml");
			$chng_phone = filter($nuke_authors_cacheData[$adm_aid]['phone'], "nohtml");
			$chng_image = filter($nuke_authors_cacheData[$adm_aid]['image'], "nohtml");
			$chng_rule = stripslashes($nuke_authors_cacheData[$adm_aid]['rule']);
			$chng_radminsuper = intval($nuke_authors_cacheData[$adm_aid]['radminsuper']);
			$chng_admlanguage = $nuke_authors_cacheData[$adm_aid]['admlanguage'];
		
			$contents .= "
			<form action=\"".$admin_file.".php\" method=\"post\">
				<table border=\"0\" cellpadding=\"3\" cellspacing=\"3\" class=\"product-table no-border id-form\" width=\"100%\">
					<tr>
						<th style=\"width:130px;\">"._NICKNAME."</th>
						<td><input type=\"text\" name=\"author_fields[chng_realname]\" value=\"$chng_realname\" class=\"inp-form\"> <font class=\"tiny\">" . _REQUIRED . "</font></td>
					</tr>
					<tr>
						<th>" . _USERNAME . "</th>
						<td><input type=\"text\" name=\"author_fields[chng_aid]\" value=\"$adm_aid\" size=\"30\" maxlength=\"25\" class=\"inp-form-ltr\"> <font class=\"tiny\">" . _REQUIRED . "</font></td>
					</tr>
					<tr>
						<th>" . _EMAIL . "</th>
						<td><input type=\"text\" name=\"author_fields[chng_email]\" value=\"$chng_email\" size=\"30\" maxlength=\"60\" class=\"inp-form-ltr\"> <font class=\"tiny\">" . _REQUIRED . "</font></td>
					</tr>
					<tr>
						<th>" . _URL . "</th>
						<td><input type=\"text\" name=\"author_fields[chng_url]\" value=\"$chng_url\" size=\"30\" maxlength=\"60\" class=\"inp-form-ltr\"></td>
					</tr>
					<tr>
						<th>"._PHONE."</th>
						<td><input type=\"text\" style=\"direction:ltr\" name=\"author_fields[chng_phone]\" size=\"30\" maxlength=\"60\" class=\"inp-form-ltr\" value=\"$chng_phone\" /> <font class=\"tiny\">" . _REQUIRED . "</font></td>
					</tr>
					<tr>
						<th>"._PICTURE."</th>
						<td><input type=\"text\" style=\"direction:ltr\" name=\"author_fields[chng_image]\" size=\"30\" class=\"inp-form-ltr\" value=\"$chng_image\" /></td>
					</tr>
					<tr>
						<th>"._ADMIN_TASK."</th>
						<td>";
							$contents .= wysiwyg_textarea('author_fields[chng_rule]', $chng_rule, 'Basic', 10, 5, '500px', '150px');
						$contents .= "</td>
					</tr>";
			
					if ($nuke_configs['multilingual'] == 1)
					{
						$contents .= "<tr><td><b>" . _LANGUAGE . "</b></td><td>"
						."<select name=\"author_fields[chng_admlanguage]\" class=\"styledselect-select\">";
						
						$languageslist = get_dir_list('language', 'files');
						foreach($languageslist as $language_name)
						{
							if($language_name == '' || $language_name == 'index.html' || $language_name == '.htaccess' || $language_name == 'alphabets.php') continue;
							$language_name = str_replace(".php", "", $language_name);
							$contents .= "<option value=\"$language_name\"".(($language_name == $chng_admlanguage) ? "selected":"").">".ucfirst($language_name)."</option>\n";
						}
						$allsel = (empty($chng_admlanguage)) ? "selected":"";
						$contents .= "<option value=\"\" $allsel>" . _ALL . "</option></select></td></tr>";
					}
					else
					{
						$contents .= "<input type=\"hidden\" name=\"author_fields[chng_admlanguage]\" value=\"\">";
					}
					
					if ($nuke_authors_cacheData[$adm_aid]['name'] != "God") {
						$contents .= "<tr><td><b>" . _PERMISSIONS . "</b></td><td><table width=\"100%\">";
						
						$contents .= "<tr><td colspan=\"4\" align=\"center\"><b>"._MAIN_MENUS."</b></td></tr>";
						
						$i = 1;
						foreach($nuke_admins_menu_cacheData as $nuke_admins_menu => $nuke_admins_menu_info)
						{
							//$atitle = @ereg_replace("_", " ", $nuke_admins_menu_info['atitle']);
							$atitle = $nuke_admins_menu_info['atitle'];
							$atitle2 = get_title_lang_name($atitle);
							
							if (array_key_exists($atitle, $main_menus)) {
								if($i == 1){
									$contents .= "<tr>\n";
								}
								$main_admins = explode(",", $nuke_admins_menu_info['admins']);

								$checked = (in_array($adm_aid,$main_admins)) ? "checked":"";
								
								$contents .= "\t<td width=\"25%\">&nbsp;<input data-label=\"$atitle2\" type=\"checkbox\" class=\"styled\" name=\"author_fields[auth_main_admins][]\" value=\"".intval($nuke_admins_menu)."\" $checked></td>\n";
								if ($i == 4) {
									$contents .= "</tr>\n";
									$i = 0;
								}
								$i++;
							}
							$atitle2 = "";
						}
						
						if($i > 1)
						{
							$diff = 4-$i;
							for($i = 0;$i <= $diff; $i++)
							{
								$contents .= "<td>&nbsp;</td>";
							}
							$contents .= "</tr>";
						}
						
						$contents .= "<tr><td colspan=\"4\" align=\"center\"><b>"._MODULES."</b></td></tr>";
						
						$i = 1;
						foreach($nuke_modules_cacheData as $nuke_modules_key => $nuke_modules_info)
						{

							$mtitle = filter($nuke_modules_info['title'], "nohtml");
							$lang_titles = filter($nuke_modules_info['lang_titles'], "nohtml");

							$lang_titles = ($nuke_modules_info['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($nuke_modules_info['lang_titles'])):"";
						
							if($lang_titles != "" && $nuke_configs['multilingual'] == 1)
							{
								$lang_title = filter($lang_titles[$nuke_configs['currentlang']], "nohtml");
							}
							else
							{
								$lang_title = $mtitle;
							}

							if (file_exists("modules/".$nuke_modules_info['title']."/admin/index.php") AND file_exists("modules/".$nuke_modules_info['title']."/admin/links.php") AND file_exists("modules/".$nuke_modules_info['title']."/admin/case.php")) {
								if($i == 1){
									$contents .= "<tr>";
								}
								$modules_admins = explode(",", $nuke_modules_info['admins']);

								$checked = (in_array($adm_aid,$modules_admins)) ? "checked":"";
								
								$contents .= "<td width=\"25%\">&nbsp;<input data-label=\"$lang_title\" type=\"checkbox\" class=\"styled\" name=\"author_fields[auth_modules][]\" value=\"".intval($nuke_modules_key)."\" $checked></td>";
								if ($i == 4) {
									$contents .= "</tr>";
									$i = 0;
								}
								$i++;
							}
						}
						
						if($i > 1)
						{
							$diff = 4-$i;
							for($i = 0;$i < $diff; $i++)
							{
								$contents .= "<td>&nbsp;</td>";
							}
							$contents .= "</tr>";
						}
						$contents .= "</table></td></tr>";
						$sel1 = '';
						if ($chng_radminsuper == 1)
						{
							$sel1 = "checked";
						}
						$contents .= "<tr><td>&nbsp;</td><td><input data-label=\"<b>" . _SUPER_ADMIN . "</b>\" type=\"checkbox\" class=\"styled\" name=\"author_fields[chng_radminsuper]\" value=\"1\" $sel1> </td></tr>";
						$contents .="<tr><td>&nbsp;</td><td><font class=\"tiny\"><i>" . _SUPERWARNING . "</i></font></td></tr>";
					}
			
					$contents .="
					<tr>
						<th>" . _PASSWORD . ":</th>
						<td><input type=\"password\" name=\"author_fields[chng_pwd]\" size=\"12\" maxlength=\"40\" class=\"inp-form\"></td>
					</tr>
					<tr>
						<th>" . _RETYPEPASSWD . ":</th>
						<td><input type=\"password\" name=\"author_fields[chng_pwd2]\" size=\"12\" maxlength=\"40\" class=\"inp-form\"> <font class=\"tiny\">" . _FORCHANGES . "</font></td>
					</tr>
					<tr>
						<td><input type=\"submit\" class=\"form-submit\" value=\"" . _SAVE . "\"></td>
					</tr>
					<tr>
						<td>"._GOBACK."</td>
					</tr>
				</table>
				<input type=\"hidden\" name=\"author_fields[adm_aid]\" value=\"$adm_aid\">
				<input type=\"hidden\" name=\"author_fields[chng_name]\" value=\"$chng_name\">
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
				<input type=\"hidden\" name=\"op\" value=\"UpdateAuthor\">
			</form>";
		
			phpnuke_db_error();
			
			
			$contents = $hooks->apply_filters("modify_admins", $contents, $chng_aid);
			
			include("header.php");
			$html_output .= $contents;
			include("footer.php");
		}
		else
		{
			$contents .= GraphicAdmin();			
			$contents .= "<div class=\"text-center\"><b>"._NOPERMISSIONS."</b><br><br>"
				.""._ONLY_GOD."<br><br>"
				.""._GOBACK."</div>";
			
			include("header.php");
			$html_output .= $contents;
			include("footer.php");
		}
	}
	
	function updateadmin($author_fields)
	{
		global $admin, $aid, $db, $admin_file, $nuke_configs, $nuke_admins_menu_cacheData, $hooks;
		
		$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
		
		$hooks->do_action("update_admins_before", $author_fields);
		$author_fields = $hooks->apply_filters("update_admins_fields", $author_fields);
		
		$contents = '';
		if (is_God())
		{
			if(isset($author_fields) && !empty($author_fields))
				extract($author_fields);
			$sql_qrray = array();
			$chng_aid = trim($chng_aid);
			$chng_aid = substr("$chng_aid", 0,25);

			if (!($chng_aid && $chng_name && $chng_email))
			{
				Header("Location: ".$admin_file.".php?op=mod_authors");
			}
			
			$sql_qrray['aid'] = $chng_aid;
			$sql_qrray['realname'] = $chng_realname;
			$sql_qrray['email'] = $chng_email;
			$sql_qrray['url'] = $chng_url;
			$sql_qrray['phone'] = $chng_phone;
			$sql_qrray['image'] = $chng_image;
			$sql_qrray['rule'] = $chng_rule;
			$sql_qrray['admlanguage'] = $chng_admlanguage;
			
			if(!empty($chng_pwd2) && $chng_pwd2 != "")
			{
				if($chng_pwd != $chng_pwd2) {
					$contents .= GraphicAdmin();
					
					$contents .= "" . _PASSWDNOMATCH . "<br><br>"
					."<div class=\"text-center\">" . _GOBACK . "</div>";
					
			
					include("header.php");
					$html_output = $contents;
					include("footer.php");
					exit;
				}
				$chng_pwd = phpnuke_hash_password($chng_pwd);
				$sql_qrray['pwd'] = $chng_pwd;
			}
			if ($adm_aid != $chng_aid)
			{
				$db->query("update ".POSTS_TABLE." set aid=?, informant = IF(informant = ?, ?, informant) where aid = ?", [$chng_aid, $adm_aid, $chng_aid, $adm_aid]);
			}
			$sql_qrray['radminsuper'] = (is_God($adm_aid) && $chng_name == 'God') ? 1:0;
			
			if(isset($chng_radminsuper))
			{
				if($chng_radminsuper== 1)
				{
					unset($auth_main_admins);
					$auth_main_admins = array();
					unset($auth_modules);
					$auth_modules = array();
				}
				$sql_qrray['radminsuper'] = $chng_radminsuper;
			}
			
			$aadminsuper = (isset($auth_main_admins) && sizeof($auth_main_admins) > 0) ? 1:0;
			
			$db->table(AUTHORS_TABLE)
				->where("name", $chng_name)
				->where("aid", $adm_aid)
				->update($sql_qrray);
			
			cache_system('nuke_authors');
			add_log(_EDIT_ADMIN." $adm_aid", 1);
			
			if(isset($auth_modules) && is_array($auth_modules) && !empty($auth_modules))
			{
				foreach($nuke_modules_cacheData as $nuke_modules_mid => $nuke_modules_info)
				{
					$admins = explode(",", $nuke_modules_info['admins']);
					if(!in_array($nuke_modules_mid, $auth_modules))
					{
						if(in_array($chng_aid, $admins))
						{
							$key = array_search($chng_aid, $admins);
							$admins[$key] = "";
							$admins = array_filter($admins);
						}
					}
					$admins = implode(",",$admins);
					$db->table(MODULES_TABLE)
						->where("mid", intval($nuke_modules_mid))
						->update(['admins' => $admins]);
						
					cache_system('nuke_modules');
				}
				unset($admins);
			
				foreach($auth_modules as $auth_module)
				{
					if($nuke_modules_cacheData[$auth_module]['admins'] != "")
					{
						$admins = explode(",",$nuke_modules_cacheData[$auth_module]['admins']);
						if(!in_array($chng_aid, $admins))
						{
							$admins[] = $chng_aid;
						}
					}
					else
					{
						$admins[] = $chng_aid;
					}
					
					$admins = implode(",",$admins);
					$db->table(MODULES_TABLE)
						->where("mid", intval($auth_module))
						->update(['admins' => $admins]);
					
					unset($admins);
				}
				cache_system('nuke_modules');
			}
			
			if(isset($auth_main_admins) && is_array($auth_main_admins) && !empty($auth_main_admins))
			{
				foreach($nuke_admins_menu_cacheData as $nuke_admins_menu_key => $nuke_admins_menu_info)
				{
					$admins = explode(",", $nuke_admins_menu_info['admins']);
					if(!in_array($nuke_admins_menu_key, $auth_main_admins))
					{
						if(in_array($chng_aid, $admins))
						{
							$key = array_search($chng_aid, $admins);
							$admins[$key] = "";
							$admins = array_filter($admins);
						}
					}
					$admins = implode(",",$admins);
					$db->table(ADMINS_MENU_TABLE)
						->where("amid", intval($nuke_admins_menu_key))
						->update(['admins' => $admins]);
					cache_system('nuke_admins_menu');
				}
				unset($admins);
			
				foreach($auth_main_admins as $auth_main_admin)
				{
					if($nuke_admins_menu_cacheData[$auth_main_admin]['admins'] != "")
					{
						$admins = explode(",",$nuke_admins_menu_cacheData[$auth_main_admin]['admins']);
						if(!in_array($chng_aid, $admins))
						{
							$admins[] = $chng_aid;
						}
					}
					else
					{
						$admins[] = $chng_aid;
					}
					
					$admins = implode(",",$admins);
					$db->table(ADMINS_MENU_TABLE)
						->where("amid", intval($auth_main_admin))
						->update(['admins' => $admins]);
					unset($admins);
				}
				cache_system('nuke_admins_menu');
			}
			
			$hooks->do_action("update_admins_after", $author_fields);
			phpnuke_db_error();
			Header("Location: ".$admin_file.".php?op=mod_authors");
		}
		else
		{
			$contents .= GraphicAdmin();
			$contents .= "<div class=\"text-center\"><b>"._NOPERMISSIONS."</b><br><br>"._ONLY_GOD."<br><br>"._GOBACK."</div>";
			
			include("header.php");
			$html_output .= $contents;
			include("footer.php");
		}
	}

	function addauthor($author_fields)
	{
		global $db, $aid, $hooks, $admin_file, $nuke_configs, $nuke_admins_menu_cacheData;
		
		$hooks->do_action("add_admins_before", $author_fields);
		$author_fields = $hooks->apply_filters("add_admins_fields", $author_fields);
		
		$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
		
		if(isset($author_fields) && !empty($author_fields))
			extract($author_fields);
		
		$add_aid = substr("$add_aid", 0,25);
		
		$hooks->add_filter("set_page_title", function() use($add_aid){return array("addauthor" => _ADD_ADMIN." : $add_aid");});
		
		$contents = '';
		if (!($add_aid && $add_email && $add_pwd))
		{
			$contents .=GraphicAdmin();
			$contents .= "<div class=\"text-center\"><font class=\"option\"><b>" . _CREATIONERROR . "</b></font><br><br>" . _COMPLETEFIELDS . "<br><br>" . _GOBACK . "</div>";
			
			include("header.php");
			$html_output .= $contents;
			include("footer.php");
			return;
		}
		
		if (isset($nuke_admins_menu_cacheData[$add_aid]) && !empty($nuke_admins_menu_cacheData[$add_aid]))
		{
			$contents .=GraphicAdmin();
			$contents .= "<div class=\"text-center\"><font class=\"option\"><b>" . _CREATIONERROR . "</b></font><br><br>" . _ADMIN_IS_EXISTS . "<br><br>" . _GOBACK . "</div>";
			
			include("header.php");
			$html_output .= $contents;
			include("footer.php");
			return;
		}
		
		if(!isset($user_id) || $user_id <= 1){$add_pwd = phpnuke_hash_password($add_pwd);}
			
		if(isset($add_radminsuper) && $add_radminsuper== 1)
		{
			unset($auth_main_admins);
			$auth_main_admins = array();
			unset($auth_modules);
			$auth_modules = array();
		}
		
		if(isset($auth_modules) && is_array($auth_modules) && !empty($auth_modules))
		{
			foreach($auth_modules as $auth_module)
			{
				if($nuke_modules_cacheData[$auth_module]['admins'] != ""){
					$admins = explode(",",$nuke_modules_cacheData[$auth_module]['admins']);
					if(!in_array($add_aid, $admins)){
						$admins[] = $add_aid;
					}
				}else{
					$admins[] = $add_aid;
				}
				
				$admins = implode(",",$admins);
				$db->table(MODULES_TABLE)
					->where("mid", intval($auth_module))
					->update(['admins' => $admins]);
				unset($admins);
			}
			cache_system('nuke_modules');
		}
		
		if(isset($auth_main_admins) && is_array($auth_main_admins) && !empty($auth_main_admins))
		{
			foreach($auth_main_admins as $auth_main_admin)
			{
				if($nuke_admins_menu_cacheData[$auth_main_admin]['admins'] != ""){
					$admins = explode(",",$nuke_admins_menu_cacheData[$auth_main_admin]['admins']);
					if(!in_array($add_aid, $admins)){
						$admins[] = $add_aid;
					}
				}else{
					$admins[] = $add_aid;
				}
				
				$admins = implode(",",$admins);
				$db->table(ADMINS_MENU_TABLE)
					->where("amid", intval($auth_main_admin))
					->update(['admins' => $admins]);
				unset($admins);
			}
			cache_system('nuke_admins_menu');
		}
		
		if(sizeof($auth_main_admins) > 0)
		{
			$aadminsuper = 1;
		}
		else
		{
			$aadminsuper = 0;
		}
		
		$add_rule = addslashes($add_rule);
		
		$db->table(AUTHORS_TABLE)
			->insert([
				'aid' => $add_aid,
				'name' => $add_aid,
				'realname' => $add_realname,
				'url' => $add_url,
				'email' => $add_email,
				'phone' => $add_phone,
				'image' => $add_image,
				'rule' => $add_rule,
				'pwd' => $add_pwd,
				'radminsuper' => ((isset($add_radminsuper) && $add_radminsuper == 1) ? 1:0),
				'admlanguage' => $add_admlanguage,
				'aadminsuper' => $aadminsuper
			]);
		
		$hooks->do_action("add_admins_after", $author_fields);
		phpnuke_db_error();
		cache_system('nuke_authors');
		add_log(_ADD_ADMIN." $add_aid", 1);
		Header("Location: ".$admin_file.".php?op=mod_authors");
	}
	
	function remove_permission($admin_id, $amid, $mid, $type)
	{
		global $db, $aid, $admin_file, $nuke_configs, $nuke_admins_menu_cacheData, $hooks;
		
		$hooks->do_action("remove_admins_permission_before", $admin_id, $amid, $mid, $type);
		
		$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
		
		$admin_id = trim($admin_id);
		
		$cache_data = ($type == 'admin') ? $nuke_admins_menu_cacheData[$amid]:$nuke_modules_cacheData[$mid];
		
		$table = ($type == 'admin') ? ADMINS_MENU_TABLE:MODULES_TABLE;
		
		$tvar = ($type == 'admin') ? "amid":"mid";
		
		$tval = ($type == 'admin') ? "$amid":"$mid";
		
		$menu_title = ($type == 'admin') ? $nuke_admins_menu_cacheData[$amid]['atitle']:$nuke_modules_cacheData[$mid]['title'];
		
		$main_admins = explode(",", $cache_data['admins']);

		if(in_array($admin_id, $main_admins))
		{
			$key = array_search($admin_id, $main_admins);
			unset($main_admins[$key]);
		}
		$main_admins = array_filter($main_admins);
		$main_admins = implode(",", $main_admins);
		if($db->query("UPDATE ".$table." SET admins = ? WHERE $tvar  = ?", [$main_admins,$tval]))
		{
			add_log(sprintf(_REMOVE_ADMIN_PERMISSIONS, $admin_id, $menu_title), 1);
			
			($type == 'admin') ?  cache_system("nuke_admins_menu"):cache_system("nuke_modules");
			
			$return = 'true';
		}
		else
			$return = 'false';
			
		die($return);
	}

	function deladmin($del_aid)
	{
		csrfProtector::authorisePost(true);
		global $db, $hooks, $admin_file, $nuke_configs, $nuke_admins_menu_cacheData;
		
		$hooks->add_filter("set_page_title", function() use($del_aid){return array("addauthor" => _DELETE_ADMIN." : $del_aid");});
		
		$contents = '';
		$del_aid = trim($del_aid);
		$contents .= GraphicAdmin();
		$contents .= "<div class=\"text-center\"><font class=\"option\"><b>" . _DELETE_ADMIN . "</b></font><br><br>"
		."" . _ADMINDELSURE . " <i>$del_aid</i>?<br><br>";
		$contents .= "[ <a href=\"".$admin_file.".php?op=deladmin2&amp;del_aid=$del_aid&csrf_token="._PN_CSRF_TOKEN."\">" . _YES . "</a> | <a href=\"".$admin_file.".php?op=mod_authors\">" . _NO . "</a> ]</div>";
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function deladmin2($del_aid)
	{
		csrfProtector::authorisePost(true);
		global $admin, $hooks, $db, $admin_file, $nuke_configs, $nuke_admins_menu_cacheData;

		$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
		
		$hooks->add_filter("set_page_title", function() use($del_aid){return array("addauthor" => _DELETE_ADMIN." : $del_aid");});
		$contents = '';
		if (is_God())
		{
			$del_aid = substr("$del_aid", 0,25);
			
			$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
			foreach($nuke_modules_cacheData as $nuke_modules_info)
			{
				if($nuke_modules_info['title'] == "Articles")
				{
					$articles_admins = $nuke_modules_info['admins'];
					break;
				}
			}
			
			$radminarticle = 0;
			
			if($articles_admins != "")
			{
				$articles_admins = explode(",",$articles_admins);
				if(in_array($nuke_authors_cacheData[$del_aid]['name'], $articles_admins)){
					$radminarticle = 1;
				}
			}
			
			if ($radminarticle == 1)
			{
				//$db->sql_query("SELECT sid from ".POSTS_TABLE." where aid='$del_aid'");
				$aid_articles = $db->table(POSTS_TABLE)
								->where("aid", $del_aid)
								->select(['sid'])
								->count();
								
				$aid_articles = intval($aid_articles);
				if ($aid_articles > 0)
				{
					$contents .= GraphicAdmin();
					$contents .= "<div class=\"text-center\"><font class=\"option\"><b>" . _PUBLISHEDSTORIES . "</b></font></div><br><br>"
					."" . _SELECTNEWADMIN . ":<br><br>";
					$contents .= "<form action=\"".$admin_file.".php\" method=\"post\"><select name=\"newaid\" class=\"styledselect-select\">";
					foreach ($nuke_authors_cacheData as $nuke_authors_aid => $nuke_authors_info)
					{
						$old_aid = filter($nuke_authors_aid, "nohtml");
						$old_aid = substr("$old_aid", 0,25);
						if($old_aid == $del_aid) continue;
						$contents .= "<option value=\"$old_aid\">$old_aid</option>";
					}
					$contents .= "</select><input type=\"hidden\" name=\"del_aid\" value=\"$del_aid\">"
					."<input type=\"hidden\" name=\"op\" value=\"assignarticles\"><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> "
					."<input type=\"submit\" value=\"" . _OK . "\">"
					."</form>";
					phpnuke_db_error();
					
					include("header.php");
					$html_output .= $contents;
					include("footer.php");
					return;
				}
			}
			Header("Location: ".$admin_file.".php?op=deladminconf&del_aid=$del_aid&csrf_token="._PN_CSRF_TOKEN."");
		} else {
			$contents .= GraphicAdmin();
			$contents .= "<div class=\"text-center\"><b>"._NOPERMISSIONS."</b><br><br>"._ONLY_GOD."<br><br>"._GOBACK."</div>";
					
			include("header.php");
			$html_output .= $contents;
			include("footer.php");
		}
	}
	
	function assignarticles($del_aid, $newaid)
	{
		global $admin, $db, $admin_file, $nuke_configs, $nuke_admins_menu_cacheData;
		
		$hooks->do_action("assignarticles_before", $del_aid, $newaid);
		
		$del_aid = trim($del_aid);
		
		$numrows = $db->table(POSTS_TABLE)
						->where("aid", $del_aid)
						->select(['sid'])
						->count();
		$numrows = intval($numrows);
		
		$db->table(POSTS_TABLE)
			->where('aid', $del_aid)
			->where('informant', $del_aid)
			->update([
				"aid" => $newaid,
				"informant" => $newaid
			]);
			
		$db->table(POSTS_TABLE)
			->where('aid', $del_aid)
			->update([
				"aid" => $newaid
			]);
		
		$db->query("update ".AUTHORS_TABLE." set counter=counter+$numrows where aid=?", [$newaid]);
		
		$hooks->do_action("assignarticles_after", $del_aid, $newaid);
		
		phpnuke_db_error();
		cache_system('nuke_authors');
		Header("Location: ".$admin_file.".php?op=deladminconf&del_aid=$del_aid&csrf_token="._PN_CSRF_TOKEN."");
	}
	
	function deladminconf($del_aid)
	{
		csrfProtector::authorisePost(true);
		global $admin, $db, $admin_file, $nuke_configs, $nuke_admins_menu_cacheData, $hooks;
		
		$hooks->do_action("deladminconf_before", $del_aid);
		
		$del_aid = trim($del_aid);

		$db->table(AUTHORS_TABLE)
			->where('aid', $del_aid)
			->where('name', "!=", "God")
			->delete();
			
		cache_system('nuke_authors');
	
		foreach($nuke_admins_menu_cacheData as $nuke_admins_menu_key => $nuke_admins_menu_info)
		{
			if($nuke_admins_menu_info['admins'] != ""){
				$admins = explode(",",$nuke_admins_menu_info['admins']);
			}
			else continue;
			
			if(in_array($del_aid, $admins))
			{
				$key = array_search($del_aid, $admins);
				$admins[$key] = "";
				$admins = array_filter($admins);
			}
			$admins = implode(",",$admins);
			
			$db->table(ADMINS_MENU_TABLE)
				->where('amid', intval($nuke_admins_menu_key))
				->update([
					"admins" => $admins
				]);
			cache_system('nuke_admins_menu');
		}
		unset($admins);
		
		$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
		foreach($nuke_modules_cacheData as $nuke_modules_mid => $nuke_modules_info)
		{
			if($nuke_modules_info['admins'] != ""){
				$admins = explode(",",$nuke_modules_info['admins']);
			}
			else continue;
			if(in_array($del_aid, $admins)){
				$key = array_search($del_aid, $admins);
				$admins[$key] = "";
				$admins = array_filter($admins);
			}
			$admins = implode(",",$admins);
			$db->table(MODULES_TABLE)
				->where('mid', intval($nuke_modules_mid))
				->update([
					"admins" => $admins
				]);
		}
		$hooks->do_action("deladminconf_after", $del_aid);
		
		cache_system('nuke_modules');
		
		add_log(_DELETE_ADMIN." $del_aid", 1);

		phpnuke_db_error();
		Header("Location: ".$admin_file.".php?op=mod_authors");
	}
	
	$user_id = (isset($user_id)) ? intval($user_id):0;
	$mid = (isset($mid)) ? intval($mid):0;
	$amid = (isset($amid)) ? intval($amid):0;
	$chng_radminsuper = (isset($chng_radminsuper)) ? intval($chng_radminsuper):0;
	$auth_modules = (isset($auth_modules)) ? $auth_modules:array();
	$author_fields = (isset($author_fields)) ? $author_fields:array();
	 
	switch ($op)
	{

		case "mod_authors":
			displayadmins($user_id);
		break;

		case "modifyadmin":
			modifyadmin($chng_aid);
		break;

		case "UpdateAuthor":
			updateadmin($author_fields);
		break;

		case "AddAuthor":
			addauthor($author_fields);
		break;

		case "deladmin":
			deladmin($del_aid);
		break;

		case "deladmin2":
		deladmin2($del_aid);
		break;

		case "assignarticles":
			assignarticles($del_aid, $newaid);
		break;

		case "deladminconf":
			deladminconf($del_aid);
		break;

		case "remove_permission":
			remove_permission($admin_id, $amid, $mid, $type);
		break;

	}

}
else
{
	header("location: ".$admin_file.".php");
}

?>