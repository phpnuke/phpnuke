<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2007 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

define('ADMIN_FILE', true);

require_once("mainfile.php");

$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);

if(isset($aid))
{
	if(!empty($aid) AND (!isset($admin) OR empty($admin)) AND $op != 'login')
	{
		unset($aid);
		unset($admin);
		die("Access Denied");
	}
}


$checkurl = $_SERVER['REQUEST_URI'];
if(
	(stripos_clone($checkurl,'AddAuthor')) OR 
	(stripos_clone($checkurl,'VXBkYXRlQXV0aG9y')) OR 
	(stripos_clone($checkurl,'QWRkQXV0aG9y')) OR 
	(stripos_clone($checkurl,'UpdateAuthor')) OR 
	(stripos_clone($checkurl, "?admin")) OR 
	(stripos_clone($checkurl, "&admin"))
)
{
	die("Illegal Operation");
}

if (isset($aid) && (@preg_match("#[^a-zA-Z0-9_-]#",trim($aid))))
{
	die("Begone");
}

if (isset($aid))
	$aid = substr($aid, 0,25);
if (isset($pwd))
	$pwd = substr($pwd, 0,70);

if ((isset($aid)) && (isset($pwd)) && (isset($op)) && ($op == "login"))
{
	if(!empty($aid) AND !empty($pwd))
	{
		$code_accepted = false;
		
		$security_code = (isset($security_code)) ? $security_code:"";
		$security_code_id = (isset($security_code_id)) ? $security_code_id:"";
		
		if(extension_loaded("gd") && in_array("admin_login", $nuke_configs['mtsn_gfx_chk']))
			$code_accepted = code_check($security_code, $security_code_id);
		else
			$code_accepted = true;

		if($code_accepted && isset($nuke_authors_cacheData[$aid]) && !empty($nuke_authors_cacheData[$aid]))
		{
			$rpwd = $nuke_authors_cacheData[$aid]['pwd'];
			$admlanguage = addslashes($nuke_authors_cacheData[$aid]['admlanguage']);
			
			if(phpnuke_check_password($pwd, $rpwd))
			{
				$expiration = 30*24*3600;
				
				$admin = phpnuke_generate_user_cookie("admin", 0, $aid, $rpwd, $expiration);

				$value = "true";
				$pn_Cookies->set("ck",$value, $expiration);
				$pn_Sessions->set('login_user', $admin);
				unset($op);
				
				$admin = base64_decode($admin);
				$admin = addslashes($admin);
				$admin = explode(":", $admin);
				add_log(_SUCCESS_ADMINLOGIN, 1);
			}
			else
			{
				Header("Location: ".$admin_file.".php?error=1");
			}
		}
		else
		{
			Header("Location: ".$admin_file.".php?error=2");
		}
	}
}

$admintest = 0;

if(isset($admin) && !empty($admin))
{
	$aid = addslashes($admin[0]);
	$hmac = $admin[2];
	if (empty($aid) OR empty($hmac))
	{
		$admintest=0;
		$alert = "<html>\n";
		$alert .= "<title>INTRUDER ALERT!!!</title>\n";
		$alert .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\">\n\n<br><br><br>\n\n";
		$alert .= "<div class=\"text-center\"><img src=\"images/eyes.gif\" border=\"0\" title=\"get out\" alt=\"get out\"><br><br>\n";
		$alert .= "<font face=\"Verdana\" size=\"+4\"><b>Get Out!</b></font></div>\n";
		$alert .= "</body>\n";
		$alert .= "</html>\n";
		die($alert);
	}
	
	if (!isset($nuke_authors_cacheData[$aid]))
	{
		login();
	}
	else
	{
		$rname = $nuke_authors_cacheData[$aid]['name'];
		if(phpnuke_validate_user_cookie($admin, "admin"))
		{
			$admintest = 1;
		}
	}
}

if(!isset($op))
{
	$op = "adminMain";
}
elseif(in_array($op, array("mod_authors", "modifyadmin", "UpdateAuthor", "AddAuthor", "deladmin", "deladmin2", "assignarticles", "deladminconf", "remove_permission")) && ($rname != "God"))
{
	die("Illegal Operation");
}

if(!isset($pagetitle))
{
	$hooks->add_filter("set_page_title", function(){return array("admin_page" => '');});
}

$nuke_admins_menu_cacheData = add_remove_nuke_admins_menu();

function add_remove_nuke_admins_menu()
{
	global $db, $nuke_configs;
	
	$nuke_admins_menu_cacheData = get_cache_file_contents('nuke_admins_menu');
	
	$change = false;
	
	if(!isset($main_menus));
		include("admin/links.php");	

	foreach($main_menus as $admin_module_key => $admin_modules_data)
	{
		$new_module = true;
		
		foreach($nuke_admins_menu_cacheData as $nuke_admins_menu => $nuke_admins_menu_info)
		{
			if($nuke_admins_menu_info['atitle'] == $admin_module_key)
			{
				$new_module = false;
				break;
			}
		}
		if ($new_module)
		{
			$insert_query_values[] = array($admin_module_key);
		}
	}
	
	if(isset($insert_query_values) && !empty($insert_query_values))
	{
		$db->table(ADMINS_MENU_TABLE)
			->multiinsert(["atitle"],$insert_query_values);
			
		cache_system("nuke_admins_menu");
		$change = true;
	}	

	foreach($nuke_admins_menu_cacheData as $nuke_admins_menu => $nuke_admins_menu_info)
	{
		$old_module = true;
		
		foreach($main_menus as $admin_module_key => $admin_module_data)
		{	
			if($nuke_admins_menu_info['atitle'] == $admin_module_key){
				$old_module = false;
				break;
			}
		}
		if ($old_module)
		{
			$delete_query_amids[] = $nuke_admins_menu;
		}
	}

	if(isset($delete_query_amids) && !empty($delete_query_amids))
	{
		$db->table(ADMINS_MENU_TABLE)
			->in("amid", $delete_query_amids)
			->delete();
			
		$db->query("optimize table ".ADMINS_MENU_TABLE."");
		cache_system("nuke_admins_menu");
		$change = true;
	}

	if($change)
	{
		unset($nuke_admins_menu_cacheData);
		$nuke_admins_menu_cacheData = get_cache_file_contents('nuke_admins_menu');
		phpnuke_db_error();
	}
	return $nuke_admins_menu_cacheData;
}

function check_admin_permission($filename, $only_god=false, $modules=false)
{
	global $db, $admin_file, $nuke_admins_menu_cacheData, $aid;
	
	$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
	$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
	
	$has_permission = false;
	if ($nuke_authors_cacheData[$aid]['aadminsuper'] == 1 && !$modules)
	{
		foreach($nuke_admins_menu_cacheData as $amid => $nuke_admins_menu_info)
		{
			if($nuke_admins_menu_info['atitle'] == $filename)
			{
				$admins = explode(",",$nuke_admins_menu_info['admins']);

				if(in_array($aid, $admins))
				{
					$has_permission = true;
					break;
				}
			}
		}
	}
	
	if ($modules)
	{
		$nuke_modules_cacheData_by_title = phpnuke_array_change_key($nuke_modules_cacheData, "mid", "title");
		
		$module_admins = ($nuke_modules_cacheData_by_title[$filename]['admins'] != '') ? explode(",", $nuke_modules_cacheData_by_title[$filename]['admins']):array();
		if(in_array($aid, $module_admins))
			$has_permission = true;
	}
	
	if($nuke_authors_cacheData[$aid]['radminsuper'] == 1)
		$has_permission = true;
	
	$is_god = is_God();
	
	if($only_god && !$is_god)
		return false;
	
	if($is_god || $has_permission)
		return true;
		
	return false;
}

function login()
{
	global $nuke_configs, $admin_file, $error, $pn_Sessions;

	define("ADMIN_LOGIN", true);
	$contents = '';
	
	$pn_Sessions->remove('nuke_authors');
	
	$sec_code_options = array(
		"input_attr" => array(
			"class" => "gscode",
			"placeholder" => _SECCODE
		),
		"img_attr" => array(
			"width" => 108,
			"height" => 40,
			"class" => "code"
		)
	);	
	$security_code_input = makepass("_ADMIN_LOGIN", $sec_code_options);
	$security_code_input2 = makepass("_ADMIN_FORGET", $sec_code_options);
	
	$error_text = '';
	if($error == 1)
		$error_text = "<p>"._BAD_USER_OR_PASS."</p>";
	elseif($error == 2)
		$error_text = "<p>"._BADSECURITYCODE."</p>";
			
	$contents .= "
	<script>
		$(document).ready(function(){
			$('.toggle').click(function(){
				/* Switches the Icon*/
				$(this).children('i').toggleClass('fa-pencil');
				if($(this).children('i').hasClass('fa-pencil'))
				{
					$(this).children('div').text('"._FORGET_PASSWORD."');
				}
				else
				{
					$(this).children('div').text('"._LOGIN_TO_ADMIN."');
				}
				
					
				/* Switches the forms  */
				$('.form').animate({
					height: \"toggle\",
					'padding-top': 'toggle',
					'padding-bottom': 'toggle',
					opacity: \"toggle\"
				}, \"slow\");
			});
		});
	</script>
	<div class=\"module form-module\">
		<div class=\"toggle\"><i class=\"fa fa-times fa-pencil\"></i>
			<div class=\"tooltip\">"._FORGET_PASSWORD."</div>
		</div>
		<div class=\"form\">
			<div class=\"logo\"></div>
			<h1>"._PHPNUKE_MT_EDITION."</h1>
			<h2>"._LOGIN_TO_ADMIN."</h2>
			$error_text
			<form action=\"".$admin_file.".php\" method=\"post\">
				<input type=\"text\" id=\"aid\" placeholder=\""._USERNAME."\" name=\"aid\" maxlength=\"25\" />
				<input type=\"password\" name=\"pwd\" maxlength=\"40\" placeholder=\""._PASSWORD."\" />";
				
				if (extension_loaded("gd") AND in_array("admin_login" ,$nuke_configs['mtsn_gfx_chk']))
					$contents .= "".$security_code_input['input']."
					".$security_code_input['image']."";
				
				$contents .= "<button type=\"submit\">"._LOGIN."</button>
				<input type=\"hidden\" name=\"op\" value=\"login\">
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>
		</div>
		<div class=\"form\">
			<div class=\"logo\"></div>
			<h1>"._PHPNUKE_MT_EDITION."</h1>
			<h2>"._RESET_PASSWORD."</h2>
			<form action=\"".$admin_file.".php\" method=\"post\">
				<input type=\"text\" name=\"id_admin\" placeholder=\""._USERNAME."\" />
				<input type=\"email\" name=\"admin_email\" placeholder=\""._EMAIL."\" />";
				
				if (extension_loaded("gd") AND in_array("admin_login" ,$nuke_configs['mtsn_gfx_chk']))
					$contents .= "".$security_code_input2['input']."
					".$security_code_input2['image']."";
				
				$contents .= "
				<button type=\"submit\">"._SEND."</button>
				<input type=\"hidden\" name=\"op\" value=\"forget_password\">
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>
		</div>
	</div>";
	
	include("header.php");
	$html_output .= $contents;
	include("footer.php");

}

function forget_password($mode='', $code='', $id_admin='', $admin_email='', $security_code='', $security_code_id='', $new_admin_password=array())
{
	global $db, $nuke_configs, $pn_salt, $admin_file, $pn_Sessions, $pn_Cookies;
	
	$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
	
	define("ADMIN_LOGIN", true);
	
	$contents = '';
	$code_accepted = true;
		
	if(extension_loaded("gd") && in_array("admin_login", $nuke_configs['mtsn_gfx_chk']))
		$code_accepted = code_check($security_code, $security_code_id);
		
	if($mode == 'reset_confirm' && $id_admin != '' && isset($nuke_authors_cacheData[$id_admin]) && isset($new_admin_password) && $new_admin_password[0] != '' && $new_admin_password[1] != '')
	{
		if($code_accepted)
		{
			if($new_admin_password[0] != $new_admin_password[1])
			{
				$contents .= "
				<div class=\"module form-module\">
					<div class=\"forget\">
						<div class=\"logo\"></div>
						<h1>"._PHPNUKE_MT_EDITION."</h1>
						<h2>"._CHANGE_PASSWORD."</h2>
						"._PASSWORDS_NOTMATCHED."
					</div>
				</div>";			
			}
			else
			{
				$new_admin_password = substr($new_admin_password[0], 0,70);
				$hashed_admin_password = phpnuke_hash_password($new_admin_password);
				
				$expiration = _NOWTIME+2592000;
				
				$db->table(AUTHORS_TABLE)
					->where("aid", $id_admin)
					->update([
						"pwd" => $hashed_admin_password,
						"password_reset" => ''
					]);
					
				cache_system("nuke_authors");
				
				$admin = phpnuke_generate_user_cookie("admin", 0, $id_admin, $new_admin_password, $expiration);

				$pn_Cookies->set("ck","true", $expiration);
				$pn_Sessions->set('login_user', $admin);
				
				$admin = base64_decode($admin);
				$admin = addslashes($admin);
				$admin = explode(":", $admin);
				add_log(sprintf(_CHANGE_ADMIN_PASSWORDLOG, $id_admin), 1);
				header("location: ".$admin_file.".php");
				die();
			}
		}
		else
		{
			$contents .= "
			<div class=\"module form-module\">
				<div class=\"forget\">
					<div class=\"logo\"></div>
					<h1>"._PHPNUKE_MT_EDITION."</h1>
					<h2>"._CHANGE_PASSWORD."</h2>
					"._BADSECURITYCODE."
				</div>
			</div>";
		}
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	if($mode == 'reset' && $code != '' && $id_admin != '' && isset($nuke_authors_cacheData[$id_admin]))
	{
		$password_reset = ($nuke_authors_cacheData[$id_admin]['password_reset'] != '') ? phpnuke_unserialize(stripslashes($nuke_authors_cacheData[$id_admin]['password_reset'])):array();
		if(in_array($code, $password_reset))
		{	
			$sec_code_options = array(
				"input_attr" => array(
					"class" => "gscode",
					"placeholder" => _SECCODE
				),
				"img_attr" => array(
					"width" => 108,
					"height" => 40,
					"class" => "code"
				)
			);
			$security_code_input = makepass("_ADMIN_RESET", $sec_code_options);
			
			$contents .= "
			<div class=\"module form-module\">
				<div class=\"form\"></div>
				<div class=\"form\">
					<div class=\"logo\"></div>
					<h1>"._PHPNUKE_MT_EDITION."</h1>
					<h2>"._RESET_PASSWORD."</h2>
					<form action=\"".$admin_file.".php\" method=\"post\">
						<input type=\"password\" name=\"new_admin_password[]\" placeholder=\""._NEWPASSWORD."\" />
						<input type=\"password\" name=\"new_admin_password[]\" placeholder=\""._NEWPASSWORD_REPEAT."\" />";
						
						if (extension_loaded("gd") AND in_array("admin_login" ,$nuke_configs['mtsn_gfx_chk']))
							$contents .= "".$security_code_input['input']."
							".$security_code_input['image']."";
						
						$contents .= "
						<button type=\"submit\">"._SEND."</button>
						<input type=\"hidden\" name=\"id_admin\" value=\"$id_admin\">
						<input type=\"hidden\" name=\"mode\" value=\"reset_confirm\">
						<input type=\"hidden\" name=\"op\" value=\"forget_password\">
						<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
					</form>
				</div>
			</div>";
		}
		else
		{
			$contents .= "
			<div class=\"module form-module\">
				<div class=\"forget\">
					<div class=\"logo\"></div>
					<h1>"._PHPNUKE_MT_EDITION."</h1>
					<h2>"._CHANGE_PASSWORD."</h2>
					"._BAD_RESETCODE."
				</div>
			</div>";
		}
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	if(!$code_accepted)
		$errors[] = _BADSECURITYCODE;
		
	if(!validate_mail($admin_email))
		$errors[] = _INVALID_EMAIL;
		
	if(!isset($nuke_authors_cacheData[$id_admin]))
		$errors[] = _INVALID_USERNAME;
		
	if(isset($nuke_authors_cacheData[$id_admin]['email']) && $nuke_authors_cacheData[$id_admin]['email'] != $admin_email)
		$errors[] = _USERNAME_EMAIL_NOMATCHED;
	if(!empty($errors))
		$text = "<p>".implode("<br />\n", $errors)."<br />"._GOBACK."</p>";
	else
	{
		$result = $db->table(AUTHORS_TABLE)
						->where("aid", $id_admin)
						->first(["password_reset"]);
		$row = $result->results();
		
		$password_reset = ($row['password_reset'] != '') ? phpnuke_unserialize(stripslashes($row['password_reset'])):array();
		$new_hashed_code = sha1($pn_salt._NOWTIME.$id_admin.$admin_email);
		$password_reset[_NOWTIME] = $new_hashed_code;
		$result['password_reset'] = addslashes(phpnuke_serialize($password_reset));
		
		$result->save();
		
		cache_system("nuke_authors");
		
		$recovery_link = $nuke_configs['nukeurl'].$admin_file.".php?op=forget_password&id_admin=$id_admin&mode=reset&code=$new_hashed_code";
		
		$message = "<table class=\"email-body_inner\" align=\"center\" width=\"570\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; margin: 0 auto; padding: 0; width: 570px;direction:"._DIRECTION."\" bgcolor=\"#FFFFFF\">

			<tr>
				<td class=\"content-cell\" style=\"box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; padding: 35px;\">
					<h1 style=\"box-sizing: border-box; color: #2F3133; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 19px; font-weight: bold; margin-top: 0;\" align=\"left\">سلام ".$nuke_authors_cacheData[$id_admin]['name'].",</h1>
					<p style=\"box-sizing: border-box; color: #74787E; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0;\" align=\"left\">"._REQUEST_RESET_PASSWORD." <strong style=\"box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif;\">"._REQUEST_RESET_PASSWORD_EXPIRE."</strong></p>

					<table class=\"body-action\" align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; margin: 30px auto; padding: 0; text-align: center; width: 100%;\">
						<tr>
							<td align=\"center\" style=\"box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif;\">

								<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif;\">
									<tr>
										<td align=\"center\" style=\"box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif;\">
											<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif;\">
												<tr>
													<td style=\"box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif;\">
														<a href=\"$recovery_link\" class=\"button button--green\" target=\"_blank\" style=\"-webkit-text-size-adjust: none; background: #22BC66; border-color: #22bc66; border-radius: 3px; border-style: solid; border-width: 10px 18px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); box-sizing: border-box; color: #FFF; display: inline-block; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; text-decoration: none;\">Reset your password</a>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<p style=\"box-sizing: border-box; color: #74787E; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0;\" align=\"left\">".sprintf(_NO_RESET_PASS_REQUEST, "<a href=\"".$nuke_configs['adminmail']."\" style=\"box-sizing: border-box; color: #3869D4; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif;\">contact support</a>")."</p>
					<p style=\"box-sizing: border-box; color: #74787E; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 16px; line-height: 1.5em; margin-top: 0;\" align=\"left\">"._THANKYOU."</p>

					<table class=\"body-sub\" style=\"border-top-color: #EDEFF2; border-top-style: solid; border-top-width: 1px; box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; margin-top: 25px; padding-top: 25px;\">
						<tr>
							<td style=\"box-sizing: border-box; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif;\">
								<p class=\"sub\" style=\"box-sizing: border-box; color: #74787E; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin-top: 0;\" align=\"left\">"._RESET_PASS_LINK_PROBLEM."</p>
								<p class=\"sub\" style=\"box-sizing: border-box; color: #74787E; font-family: Tahoma, Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 12px; line-height: 1.5em; margin-top: 0;\" align=\"left\">$recovery_link</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>";
		
		phpnuke_mail($nuke_authors_cacheData[$id_admin]['email'], _RESET_PASSWORD ,$message);
		$text = ""._RESET_PASS_LINK_SENT."<br />"._GOBACK;
	}

	$contents .= "
	<div class=\"module form-module\">
		<div class=\"forget\">
			<div class=\"logo\"></div>
			<h1>"._PHPNUKE_MT_EDITION."</h1>
			<h2>"._FORGET_PASSWORD."</h2>
			$text
		</div>
	</div>";
		
	include("header.php");
	$html_output .= $contents;
	include("footer.php");
}

function adminmenu($url, $title, $image)
{
	global $nuke_configs;
	
	if (file_exists($image))
		$admin_icon = $image;
	elseif (file_exists("themes/".$nuke_configs['ThemeSel']."/images/admin/$image"))
		$admin_icon = "themes/".$nuke_configs['ThemeSel']."/images/admin/$image";
	else
		$admin_icon = "images/admin/$image";
	
	if ($nuke_configs['admingraphic'] == 1)
		$content = "<div class = 'axe'  style = 'background-image:url($admin_icon)'></div><span>$title</span>";
	else
		$content = "<span>$title</span>";
	
	$menu = "<a href=\"$url\" class=\"info-tooltip\" title=\"$title\">$content</a>";
	return $menu;
}

function ipcount()
{
	$nuke_mtsn_ipban_cacheData = get_cache_file_contents('nuke_mtsn_ipban');
	$numattacks = (is_array($nuke_mtsn_ipban_cacheData) && !empty($nuke_mtsn_ipban_cacheData)) ? sizeof($nuke_mtsn_ipban_cacheData):0;
	return $numattacks;
}

function get_latest_vershion_from_nuke($mode='')
{
	global $nuke_configs;
	$file_info = phpnuke_get_url_contents("https://www.phpnuke.ir/version.html", true);

	if($file_info)
	{
		$version_number = "";
		if($file_info != '' && isset($mode) && $mode == 'adminmain')
			die("".$file_info."");
		
		return $file_info;
	}
	else
	{
		if(isset($mode) && $mode == "adminmain")
			die("".$nuke_configs['Version_Num']."");
		return $nuke_configs['Version_Num'];
	}
}
		
function admin_tables_sortable($fields=array(), $sort = 'DESC', $link_to_more='', $order_by='')
{
	global $admin_file;
	$contents = '';
	$sort_reverse = ($sort == 'ASC') ? "DESC":"ASC";	
	$sort_icon = ($sort == 'ASC') ? "up":"down";	
	if(is_array($fields) && !empty($fields))
	{
		foreach($fields as $field)
		{
			if(!isset($field['op']) || (isset($field['op']) && $field['op'] == ''))
			{
				$contents .="<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:".$field['width'].";\">".$field['text']."</th>";
			}
			else{
				$contents .="<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:".$field['width'].";\"><a href=\"".$admin_file.".php?op=".$field['op']."&order_by=".$field['id']."&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == $field['id']) ? " class=\"arrow_".strtolower($sort)."\"":"").">".$field['text']."</a></th>";
			}
		}
	}

	return $contents;
}

function GraphicAdmin()
{
	global $aid, $nuke_configs, $op, $admin, $db, $nuke_admins_menu_cacheData, $admin_file;

	$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
	$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
	
	$radminsuper = intval($nuke_authors_cacheData[$aid]['radminsuper']);
	$aadminsuper = intval($nuke_authors_cacheData[$aid]['aadminsuper']);
	$nuke_admins_menu_cacheData_by_atitle = phpnuke_array_change_key($nuke_admins_menu_cacheData, 'amid', 'atitle');
	$nuke_modules_cacheData_by_title = phpnuke_array_change_key($nuke_modules_cacheData, 'mid', 'title');
	$contents = '';
	if($op != 'adminMain')
	{
		$contents .= "
		<div id=\"admin_menus\">
		<div class=\"admin_menu_toogle\">"._SHOW_ADMINMENUS."</div><div class=\"admin_menu_icons\" style=\"display:none;\">";
	}
	
	if ($radminsuper == 1 && $op == 'adminMain')
	{
		$contents .= OpenAdminTable();
		$nukever = nukeversion();
		
		$adminfile = ($admin_file == 'admin') ? _NO:_YES;

		$errors = ($nuke_configs['display_errors'] == '1') ? _ACTIVE:_INACTIVE;
		
		$mtsnactive = ($nuke_configs['mtsn_status']=="1") ? _ACTIVE:_INACTIVE;

		$mtsnactive = stripslashes($mtsnactive);
		$adminfile = stripslashes($adminfile);
		$errors = stripslashes($errors);

		$total_articles = 0;
		$total_tags = 0;
		$total_attackes = 0;
		$articles = $db->query("SELECT COUNT(sid) AS total_articles,
		(SELECT COUNT(tag_id) FROM ".TAGS_TABLE.") as total_tags,
		(SELECT COUNT(id) FROM ".MTSN_TABLE.") as total_attackes
		FROM ".POSTS_TABLE." WHERE post_type = 'article'");
		if(intval($db->count()) > 0)
		{
			$articles_rows = $articles->results()[0];
			$total_articles = intval($articles_rows['total_articles']);
			$total_tags = intval($articles_rows['total_tags']);
			$total_attackes = intval($articles_rows['total_attackes']);
		}
		
		$lockicon = "<img border='0' src='images/lock.png'>";
		$infoicon = "<img border='0' src='images/checkbox_checked_todo_icon.png'>";
		$contents .= "<table border='0' cellspacing='0' style='border: 0 solid #808080; ' cellpadding='0' width='100%'>
		<tr>
			<td  width='254' colspan='4'></td>
		</tr>
		<tr>
			<td  width='354'>$infoicon "._SEQ_STAT." : $mtsnactive</td>
			<td  width='354'>$infoicon "._CLOSED_IP." : ".ipcount()."</td>
			<td  width='354' >$infoicon "._TOTAL_ARTICLES." : $total_articles</td>
			<td  width='354'>$infoicon "._TOTAL_TAGS." :$total_tags </td>
		</tr>
		<tr>
			<td  width='354'>$infoicon "._NUKE_VERSION." : $nukever</td>
			<td  width='354'>$infoicon "._LATEST_NUKE_VERSION." : <span id=\"version_check\"></span>
			<script type=\"text/javascript\">
				$(document).ready(function(){ 
					$.ajax({  
						type: \"POST\",  
						url: \"".$admin_file.".php\",  
						data: { op: \"get_latest_vershion_from_nuke\", mode : \"adminmain\", 'csrf_token' : pn_csrf_token},  
						success: function(theResponse) {
							$(\"#version_check\").html(theResponse);
						}  
					});
				});
			</script>
			</td>
			<td  width='354'>$infoicon "._ADMIN_FILE_RENAME." : $adminfile </td>
			<td  width='354'>$infoicon "._LATEST_SEQ_SYS."</td>
		</tr>
		<tr>
			<td  width='354'>$infoicon "._ATTACK_TIME." : $total_attackes</td>
			<td  width='354'>$infoicon "._SHOW_ERROR_MESS." : $errors</td>
			<td width='354' >$infoicon "._TOTAL_USERS." : ".numusers()."</td>
			<td width='354' ></td>
		</tr>
		</table>";
		$contents .= CloseAdminTable(); 
		$contents .= "<br><br>";
	}
	
	$counter = 1;
	$max_cols = 10;
	$cols_width_percent = 100/$max_cols;
	$main_menus = array();
	if(!isset($main_menus));
		include("admin/links.php");	
	
	foreach ($main_menus as $menu_item_key => $menu_item_data)
	{
		if(!empty($menu_item_key) && !empty($menu_item_data))
		{
			$admins = explode(",", $nuke_admins_menu_cacheData_by_atitle[strtolower($menu_item_key)]['admins']);
			if($radminsuper != 1 && ($aadminsuper != 1 || !in_array($aid,$admins)))
				unset($main_menus[$menu_item_key]);
		}
	}
	
	$module_menus = array();

	if(isset($nuke_modules_cacheData) && !empty($nuke_modules_cacheData))
	{
		foreach ($nuke_modules_cacheData as $mid => $module_info)
		{
			$module_admins = explode(",", $module_info['admins']);
			
			$auth_user = (in_array($aid, $module_admins)) ? 1:0;

			if ($radminsuper == 1 OR $auth_user == 1)
			{
				if (file_exists("modules/".$module_info['title']."/admin/index.php") AND file_exists("modules/".$module_info['title']."/admin/links.php") AND file_exists("modules/".$module_info['title']."/admin/case.php"))
				{
					include("modules/".$module_info['title']."/admin/links.php");
				}
			}
		}
	}

	if(sizeof($main_menus) > 1 || !empty($module_menus))
	{
		$admin_menus = array_merge($main_menus, $module_menus);
		
		$admin_menus['logout'] = adminmenu("".$admin_file.".php?op=logout&csrf_token="._PN_CSRF_TOKEN."", ""._ADMIN_LOGOUT."", "logout.png");	
		
		$contents .= OpenAdminTable();
		$contents .= "<table border=\"0\" width=\"100%\" cellspacing=\"1\" class=\"product-table td-hover\">\n";
		foreach($admin_menus as $admin_menu)
		{
			if($counter == 1)
				$contents .= "<tr>\n";
			$contents .= "<td align=\"center\" valign=\"top\" width=\"$cols_width_percent%\" >$admin_menu</td>\n";
			if($counter == $max_cols)
			{
				$contents .= "</tr>\n";
				$counter = 0;
			}
			$counter++;
		}
		
		if($counter != 1)
		{
			$diff = $max_cols-$counter+1;
			$contents .= "<td colspan=\"$diff\">&nbsp;</td>
			</tr>\n";
		}
		$contents .= "</table>";
		$contents .= CloseAdminTable();
		$contents .= "<br>";		
	}
	
	if($op != 'adminMain')
	{
		$contents .="</div></div>";
	}
	return $contents;
}

function adminMain()
{
	global $nuke_configs, $admin, $aid, $db, $admin_file, $sort, $users_system, $pn_dbname, $pn_dbcharset, $hooks;
	define("ADMIN_MAIN",true);
	$contents = '';
	
	$now_time = _NOWTIME;
	
	$today_day = date("d");
	$today_month = date("m");
	$today_year = date("Y");
	$start_of_day = mktime(0,0,0,$today_month,$today_day,$today_year);
	
	$last_month = $start_of_day-2592000;
	$last_month_day = correct_date_number(date("d", $last_month));
	$last_month_month = correct_date_number(date("m", $last_month));
	$last_month_year = date("Y", $last_month);
	
	$users_table_exists = users_table_exists();
	
	$registered_users = array();
	if($users_table_exists)
	{
		$result1 = $db->query("SELECT ".$users_system->user_fields['user_regdate']." as user_regdate FROM ".$users_system->users_table." WHERE ".$users_system->user_fields['user_regdate']." >= '$last_month' AND ".$users_system->user_fields['user_regdate']." <= '$now_time' AND ".$users_system->user_fields['common_where']."");
		if($db->count() > 0)
		{
			foreach($result1 as $row1)
			{
				$user_regdate = $row1['user_regdate'];
				$user_regdate_year = date("Y", $user_regdate);
				$user_regdate_month = date("m", $user_regdate);
				$user_regdate_day = date("d", $user_regdate);
				//$user_regdate_hour = date("H", $user_regdate);
				if(isset($registered_users[$user_regdate_year][$user_regdate_month][$user_regdate_day]))
					$registered_users[$user_regdate_year][$user_regdate_month][$user_regdate_day]++;
				else
					$registered_users[$user_regdate_year][$user_regdate_month][$user_regdate_day] = 1;
			}
		}
	}
	
	$contents .= GraphicAdmin();
	
	$result = $db->query("SELECT year, month, day, visitors, hits FROM ".STATISTICS_TABLE." WHERE id >= COALESCE((SELECT id FROM ".STATISTICS_TABLE." WHERE year = '$last_month_year' AND month = '$last_month_month' AND day >= '$last_month_day' ORDER BY id ASC LIMIT 1), 1) AND id <= COALESCE((SELECT id FROM ".STATISTICS_TABLE." WHERE year = '$today_year' AND month = '$today_month' AND day <= '$today_day' ORDER BY id DESC LIMIT 1), (SELECT id FROM ".STATISTICS_TABLE." ORDER BY id DESC LIMIT 1))");
	
	$contents .= "
	<script src=\"includes/amcharts/amcharts.js\"></script>
		<script src=\"includes/amcharts/serial.js\"></script>
		<script src=\"includes/amcharts/themes/light.js\"></script>
		<script src=\"includes/amcharts/plugins/responsive/responsive.min.js\"></script>
		<style>
		#chartdiv {
			width	: 100%;
			height	: 500px;
		}									
		</style>
		<script type=\"text/javascript\">			
			var chart = AmCharts.makeChart(\"chartdiv\",
				{
					\"type\": \"serial\",
					\"categoryField\": \"date\",
					\"fontFamily\": \"Tahoma\",
					\"marginRight\": 40,
					\"marginLeft\": 40,
					\"autoMarginOffset\": 20,
					\"mouseWheelZoomEnabled\":true,
					\"colors\": [
						\"#356e8c\",
						\"#8c7335\",
						\"#851d1d\",
					],
					\"categoryAxis\": {
						\"gridPosition\": \"start\",
						\"autoGridCount\": false
					},
					\"chartCursor\": {
						\"enabled\": true,
						\"cursorPosition\": \"mouse\",
						\"showNextAvailable\":true,
						\"cursorColor\": \"#888888\"
					},
					\"export\": {
						\"enabled\": true
					},
					\"chartScrollbar\": {
						\"enabled\": true
					},
					\"trendLines\": [],
					\"graphs\": [
						{
							\"bullet\": \"round\",
							\"hideBulletsCount\": 60,
							\"id\": \"AmGraph-1\",
							\"title\": \""._VIEWERS_STATISTICS."\",
							\"valueField\": \"column-1\",
							\"balloonText\": \"<span style='font-size:15px;'>[[value]]</span>\"
						},
						{
							\"bullet\": \"square\",
							\"hideBulletsCount\": 60,
							\"id\": \"AmGraph-2\",
							\"title\": \""._IP_INPUT."\",
							\"valueField\": \"column-2\",
							\"balloonText\": \"<span style='font-size:15px;'>[[value]]</span>\"
						},
						{
							\"bullet\": \"round\",
							\"hideBulletsCount\": 60,
							\"id\": \"AmGraph-3\",
							\"title\": \""._REGISTER."\",
							\"valueField\": \"column-3\",
							\"balloonText\": \"<span style='font-size:15px;'>[[value]]</span>\"
						}
					],
					\"balloon\": {
						\"borderThickness\": 1,
						\"shadowAlpha\": 0
					},
					\"guides\": [],
					\"valueAxes\": [
						{
							\"id\": \"ValueAxis-1\",
							\"title\": \""._NUMBER."\"
						}
					],
					\"allLabels\": [],
					\"legend\": {
						\"enabled\": true,
						\"useGraphSettings\": true
					},
					\"titles\": [
						{
							\"id\": \"Title-1\",
							\"size\": 15,
							\"face\": 'tahoma',
							\"text\": \""._ADMIN_CHART_TITLE."\"
						}
					],
					\"responsive\": {
						\"enabled\": true
					},
					\"dataProvider\": [
						";
						if($db->count() > 0)
						{
							foreach($result as $row)
							{
								//$hourly_info = objectToArray(json_decode($row['hourly_info']));
								$hits = intval($row['hits']);
								$visitors = intval($row['visitors']);
								$this_year = intval($row['year']);
								$this_month = correct_date_number(intval($row['month']));
								$this_day = correct_date_number(intval($row['day']));
								
								$this_time = mktime(0,0,0, $this_month, $this_day, $this_year);
								$datetime = nuketimes($this_time, false, false, false, 1);
								
								$contents .= '
								{
									"column-1": '.$hits.',
									"column-2": '.$visitors.',
									"column-3": '.((isset($registered_users[$this_year][$this_month][$this_day])) ? $registered_users[$this_year][$this_month][$this_day]:0).',
									"date": "'.$datetime.'\n",
								},'."\n";
								/*foreach($hourly_info as $hour => $hits)
								{
									if($hits == 0) continue;
									$this_time = mktime($hour,0,0, $this_month, $this_day, $this_year);
									$datetime = nuketimes($this_time, false, false, false, 1);
									$contents .= '
									{
										"column-1": '.$hits.',
										"column-2": '.$visitors.',
										"column-3": '.((isset($registered_users[$this_year][$this_month][$this_day][$hour])) ? $registered_users[$this_year][$this_month][$this_day][$hour]:0).',
										"date": "'.$datetime.'\n'._HOUR.' '.$hour.'",
									},'."\n";
								}
								$hourly_info = null;*/
								
							}
						}
						$contents .= "
					]
				}
			);

			chart.addListener(\"rendered\", zoomChart);

			zoomChart();

			function zoomChart() {
				chart.zoomToIndexes(chart.dataProvider.length - 26, chart.dataProvider.length - 1);
			}
		</script>
		<div id=\"chartdiv\" style=\"width: 100%; height: 500px; background-color: #FFFFFF;\" dir=\"ltr\" ></div>
			<table width=\"100%\" class=\"product-table min_table\">
				<tr>
					<td colspan=\"4\" style=\"background:#eee;\" align=\"center\"><b>"._ADMIN_ACTIVITIES."</b><span style=\"float:left;\"><a href=\"".$admin_file.".php?op=show_log_list&log_type=1\">"._ADMIN_ACTIVITIES_ARCHIVE."</a> | <a href=\"".$admin_file.".php?op=show_log_list&log_type=2\">"._USERS_ACTIVITIES_ARCHIVE."</a></span></td>
				</tr>
				<tr>
					<th class=\"table-header-repeat line-left\" style=\"width:80px\">"._USERNAME."</th>
					<th class=\"table-header-repeat line-left\" style=\"width:100px\">"._IP."</th>
					<th class=\"table-header-repeat line-left\" style=\"width:200px\">"._DATE."</th>
					<th class=\"table-header-repeat line-left\">"._ACTIONS."</th>
				</tr>
			<tbody>";
			$result_log = $db->table(LOG_TABLE)
							->where("log_type", 1)
							->order_by(["log_time" => "DESC"])
							->limit(0,5)
							->select();

			if($db->count() > 0)
			{
				foreach($result_log as $row_log)
				{
					$log_by = filter($row_log['log_by'], "nohtml");
					$log_ip = $row_log['log_ip'];
					$log_time = nuketimes($row_log['log_time'], true, true, true, 1);
					$log_message = stripslashes($row_log['log_message']);
					$contents .= "<tr>
						<td align=\"center\">$log_by</td>
						<td align=\"center\">$log_ip</td>
						<td align=\"center\">$log_time</td>
						<td align=\""._TEXTALIGN1."\">$log_message</td>
					</tr>";
				}
			}
			$contents .= "
			</tbody>
		</table>
		<div class=\"clear\"></div>
		
		<div class=\"admin_main_box fr\" style=\"width:47%;\">
			<div class=\"admin_main_box_title\">
				"._LATEST_10_COMMENTS."
			</div>
			<div class=\"admin_main_box_content\">
				<table width=\"100%\" class=\"product-table min_table no-border\">
					<tr>
						<th>"._COMMENT."</th>
						<th style=\"width:90px\">"._APPROVE."</th>
					</tr>
					<tbody>";
						
					$result = $db->table(COMMENTS_TABLE)
								->where('status', 0)
								->order_by(['cid' => 'DESC'])
								->limit(0,5)
								->select();
								
					if($db->count() > 0)
					{
						foreach($result as $row)
						{
							$cid = intval($row['cid']);
							$module = filter($row['module'], "nohtml");
							$post_title = filter($row['post_title'], "nohtml");
							$comment = stripslashes($row['comment']);
							$comment_short = mb_word_wrap($comment, 150);
							$contents .= "<tr style=\"border-bottom:1px solid #eee;\">
								<td style=\"padding-right:10px;\"><span title=\"".sprintf(_COMMENTS_RELATEDTO, $post_title, $module)."\">$comment_short</span></td>
								<td align=\"center\">
									<a href=\"".$admin_file.".php?op=comments_delete&cid=$cid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._SURETODELCOMMENTS."');\"></a>
									<a href=\"".$admin_file.".php?op=comments_status&cid=$cid&new_status=1&csrf_token="._PN_CSRF_TOKEN."\" title=\""._APPROVE."\" class=\"table-icon icon-5 info-tooltip\"></a>
									<a href=\"#TB_inline?height=300&amp;width=600&amp;inlineId=comments_".$cid."\" title=\""._SHOW."\" class=\"table-icon icon-7 info-tooltip thickbox\"></a>
									<div id=\"comments_".$cid."\" style=\"display:none;\">
										<p>$comment</p>
									</div>
								</td>
							</tr>";
						}
					}
					$contents .= "
					</tbody>
				</table>
			</div>
		</div>
		<div class=\"admin_main_box fl\" style=\"width:47%;\">
			<div class=\"admin_main_box_title\">
				"._LATEST_FEEDBACKS."
			</div>
			<div class=\"admin_main_box_content\">

				<table width=\"100%\" class=\"product-table min_table no-border\">
					<tr>
						<th>"._TITLE."</th>
						<th style=\"width:60px\">"._OPERATION."</th>
					</tr>
					<tbody>";
						
					$result = $db->query("SELECT * FROM ".FEEDBACKS_TABLE." WHERE replys IS NULL OR replys = '' ORDER BY fid DESC LIMIT 0,5");
								
					if($db->count() > 0)
					{
						foreach($result as $row)
						{

							$fid = intval($row['fid']);
							$subject = filter($row['subject'], "nohtml");
							$sender_name = filter($row['sender_name'], "nohtml");
							$sender_email = filter($row['sender_email'], "nohtml");
							$added_time = nuketimes($row['added_time'], false, false, false, 1);
							$responsibility = intval($row['responsibility']);
							$custom_fields = phpnuke_unserialize(stripslashes($row['custom_fields']));
					
							$contents .= "<tr style=\"border-bottom:1px solid #eee;\">
								<td style=\"padding-right:10px;\">$subject</td>
								<td align=\"center\">
									<a href=\"".$admin_file.".php?op=feedbacks&mode=delete&fids=$fid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._FEEDBACK_DELETE_CONFIRM."');\"></a>
									<a href=\"".$admin_file.".php?op=reply_feedback_pm&fid=$fid&inline=1\" title=\""._SHOW."\" class=\"table-icon icon-7 info-tooltip thickbox\"></a>
								</td>
							</tr>";
						}
					}
					$contents .= "
					</tbody>
				</table>
			</div>
		</div>
		<div class=\"clear\"></div>
		
		<div class=\"admin_main_box fr\" style=\"width:47%;\">
			<div class=\"admin_main_box_title\">
				"._LATEST_USERS."
			</div>
			<div class=\"admin_main_box_content\">
				<table width=\"100%\" class=\"product-table min_table no-border\">
					<tr>
						<th>"._USERNAME."</th>
						<th style=\"width:120px\">"._REGISTERDATE."</th>
					</tr>
					<tbody>";
					if($users_table_exists)
					{
						$db->query("SET NAMES '".$users_system->collation."'");
						$result = $db->query("SELECT ".$users_system->user_fields['user_id']." as user_id, ".$users_system->user_fields['username']." as username, ".$users_system->user_fields['user_regdate']." as user_regdate FROM ".$users_system->users_table."".(($users_system->user_fields['common_where'] != '') ? " WHERE ".$users_system->user_fields['common_where']."":"")." ORDER BY ".$users_system->user_fields['user_id']." DESC LIMIT 0,5");
						$rows = $result->results();
						
						if($db->count() > 0)
						{
							foreach($rows as $row)
							{

								$user_id = intval($row['user_id']);
								$username = filter($row['username'], "nohtml");
								$user_regdate = nuketimes($row['user_regdate'], false, false, false, 1);
								$profile_url = sprintf($users_system->profile_url, $user_id, $username);
								
								$contents .= "<tr style=\"border-bottom:1px solid #eee;\">
									<td style=\"padding-right:10px;\"><a href=\"$profile_url\" target=\"_blank\">$username</a></td>
									<td style=\"padding:5px;\">$user_regdate</td>
								</tr>";
							}
						}
						$db->query("SET NAMES '".$pn_dbcharset."'");
					}
					$contents .= "
					</tbody>
				</table>
			</div>
		</div>
		<div class=\"admin_main_box fl\" style=\"width:47%;\">
			<div class=\"admin_main_box_title\">
				"._LATEST_REPORTS."
			</div>
			<div class=\"admin_main_box_content\">
				<table width=\"100%\" class=\"product-table min_table no-border\">
					<tr>
						<th>"._TITLE."</th>
						<th style=\"width:60px\">"._VIEW."</th>
					</tr>
					<tbody>";						
						
					$result = $db->table(REPORTS_TABLE)
								->order_by(['rid' => 'DESC'])
								->limit(0,5)
								->select();
								
					if($db->count() > 0)
					{
						foreach($result as $row)
						{

							$rid = intval($row['rid']);
							$subject = filter($row['subject'], "nohtml");
							$post_title = filter($row['post_title'], "nohtml");
							$module = filter($row['module'], "nohtml");
							$message = str_replace(array("\n","\r","\t"),"", strip_tags($row['message']));
							$post_id = intval($row['post_id']);
							$post_link = '';
							$post_link = $hooks->apply_filters("get_post_link", $post_link, $module, $post_id);
						
							$contents .= "<tr style=\"border-bottom:1px solid #eee;\">
								<td style=\"padding-right:10px;\"><a href=\"$post_link\" target=\"_blank\">$subject</a></td>
								<td align=\"center\">
									<a href=\"".$admin_file.".php?op=reports&mode=delete&rids=$rid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._REPORT_DELETE_CONFIRM."');\"></a>
									<a href=\"#TB_inline?height=300&amp;width=600&amp;inlineId=reports_".$rid."\" title=\""._SHOW."\" class=\"table-icon icon-7 info-tooltip thickbox\"></a>
									<div id=\"reports_".$rid."\" style=\"display:none;\">
										<p>$message</p>
									</div>
								</td>
							</tr>";
						}
					}
					$contents .= "
					</tbody>
				</table>
			</div>
		</div>
		<div class=\"clear\"></div>
		<div align=\"center\">
		<table width=\"98%\" class=\"product-table min_table no-border\">
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"width:50px;\"><a>"._LATEST_NUKE_NEWS."</a></th>
			</tr>
			<tr><td><ul style=\"list-style:none;margin-"._TEXTALIGN1.":5px;\">".get_latest_news_from_nuke()."</ul></td></tr>
		</table>
		</div>";
	include("header.php");
	$html_output .= $contents;
	include("footer.php");
}

function get_latest_news_from_nuke()
{
	global $PnValidator, $nuke_configs, $cache;
	
	
	if($cache->isCached("latest_news_from_nuke") && (_NOWTIME-$cache->retrieve("latest_news_from_nuke", true)) < 3600)
	{
		return $cache->retrieve("latest_news_from_nuke");
	}
	else
	{
		$latest_news_from_nuke = array();
		
		if($cache->isCached('latest_news_from_nuke'))
			$cache->erase('latest_news_from_nuke');
	
	
		$file_info = phpnuke_get_url_contents("https://www.phpnuke.ir/notice.html", true);

		if ($file_info)
		{
			$phpnuke_news = objectToArray(json_decode($file_info));
			$contents = "";
			if(is_array($phpnuke_news) && !empty($phpnuke_news))
			{
				foreach($phpnuke_news as $article)
				{
					$PnValidator->validation_rules(array(
						'title'	=> 'required',
						'link'	=> 'required',
					));
				
					// Get or set the filtering rules
					$PnValidator->filter_rules(array(
						'title'		=> 'sanitize_string',
						'link'		=> 'urldecode|strip_tags',
					)); 
					$article = $PnValidator->sanitize($article, array('title'), true, true);
					$validated_data = $PnValidator->run($article);
					if($validated_data !== FALSE)
					{
						$article = $validated_data;
					}
					$contents .= "<li><a href=\"".$article['link']."\" target=\"_blank\" rel=\"no-follow\">".$article['title']."</a></li>";
				}
			}
			$cache->store('latest_news_from_nuke', $contents);
			return $contents;			
		}
		
		return _DOWNLOAD_PHPNUKEIR_PROBLEM;
	}
}

function show_log_list($log_type=1)
{
	global $db, $admin_file, $hooks;
	$contents = '';
	$contents .= GraphicAdmin();
	$log_name = ($log_type == 1) ? _ADMINS:_USERS;
	
	$hooks->add_filter("set_page_title", function() use($log_name){return array("admin_page" => "فعالیتهای $log_name");});
	
	$contents .= "<table width=\"100%\" class=\"product-table min_table\">
			<tr>
				<td colspan=\"4\" style=\"background:#eee;\" align=\"center\"><b>".sprintf(_ADMIN_USERS_ACTIONS, $log_name)."</b></td>
			</tr>
				<tr>
					<th class=\"table-header-repeat line-left\" style=\"width:80px\">"._USERNAME."</th>
					<th class=\"table-header-repeat line-left\" style=\"width:100px\">"._IP."</th>
					<th class=\"table-header-repeat line-left\" style=\"width:200px\">"._DATE."</th>
					<th class=\"table-header-repeat line-left\">"._OPERATION."</th>
				</tr>
			<tbody>";
			
			$entries_per_page = 50;
			$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
			$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
			$link_to = "".$admin_file.".php?op=show_log_list&log_type=".$log_type;
			
			$total_rows = 0;
			
			$result_log = $db->query("SELECT *, (SELECT COUNT(lid) FROM ".LOG_TABLE." WHERE log_type = ?) as total_rows FROM ".LOG_TABLE." WHERE log_type = ? ORDER BY log_time DESC LIMIT $start_at, $entries_per_page", array($log_type, $log_type));
			
			if($db->count() > 0)
			{
				foreach($result_log as $row_log)
				{
					$total_rows = intval($row_log['total_rows']);
					$log_by = filter($row_log['log_by'], "nohtml");
					$log_ip = $row_log['log_ip'];
					$log_time = nuketimes($row_log['log_time'], true, true, true, 1);
					$log_message = stripslashes($row_log['log_message']);
					$contents .= "<tr>
						<td align=\"center\">$log_by</td>
						<td align=\"center\">$log_ip</td>
						<td align=\"center\">$log_time</td>
						<td align=\""._TEXTALIGN1."\">$log_message</td>
					</tr>";
				}
			}
			$contents .= "
			</tbody>
		</table>";
		$contents .= "<div id=\"pagination\" class=\"pagination\">";
		$contents .= admin_pagination($total_rows, $entries_per_page, $current_page, $link_to);
		$contents .= "</div>";
		
	include("header.php");
	$html_output .= $contents;
	include("footer.php");
}

unset($nuke_authors_cacheData);

$hooks->do_action("run_admin_plugins");

if($admintest)
{	
	global $pn_Sessions;
	switch($op)
	{		
		case "get_latest_news_from_nuke":
			get_latest_news_from_nuke();
		break;
		
		case "get_latest_vershion_from_nuke":
			$mode = (isset($mode)) ? $mode:"";
			get_latest_vershion_from_nuke($mode);
		break;

		case "adminMain":
			adminMain();
		break;

		case "show_log_list":
			$log_type = (isset($log_type)) ? $log_type:1;
			show_log_list($log_type);
		break;

		case "logout":
			$contents = '';
			$pn_Cookies->set("admin",false,'');
			$pn_Cookies->set("admin",false,-1);
			$pn_Cookies->delete("admin");
			$pn_Cookies->set("ck",false,'');
			$pn_Cookies->set("ck",false,-1);
			$pn_Cookies->delete("ck");
			$pn_Sessions->remove('login_user');
			$admin = "";
			add_log(_ADMIN_LOGOUT_LOG, 1);
			Header("Refresh: 3; url=".$admin_file.".php");
			include("header.php");
			$contents .= OpenAdminTable();
			$contents .=  "<div class=\"text-center\"><font class=\"title\"><b>"._YOUARELOGGEDOUT."</b></font></div>";
			$contents .= CloseAdminTable();
			$html_output .= $contents;
			include("footer.php");
		break;

		case "login";
			unset($op);

		default:
			if (!is_admin())
			{
				login();
			}
			
			require_once("admin/case.php");
			$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
			if(isset($nuke_modules_cacheData) && is_array($nuke_modules_cacheData))
			{
				foreach ($nuke_modules_cacheData as $mid => $module_info)
				{
					$mod_title = $module_info['title'];
					if (file_exists("modules/$mod_title/admin/index.php") AND file_exists("modules/$mod_title/admin/links.php") AND file_exists("modules/$mod_title/admin/case.php"))
					{
						include("modules/$mod_title/admin/case.php");
					}
				}
			}
			unset($module_info);
			unset($nuke_modules_cacheData);
		break;
	}
}
else
{
	$mode = (isset($mode)) ? $mode:"";
	$code = (isset($code)) ? $code:"";
	$id_admin = (isset($id_admin)) ? $id_admin:"";
	$admin_email = (isset($admin_email)) ? $admin_email:"";
	$security_code = (isset($security_code)) ? $security_code:"";
	$security_code_id = (isset($security_code_id)) ? $security_code_id:"";
	$new_admin_password = (isset($new_admin_password)) ? $new_admin_password:array();
	switch($op)
	{
		case"forget_password":
			forget_password($mode, $code, $id_admin, $admin_email, $security_code, $security_code_id, $new_admin_password);
		break;
		default:
			login();
		break;
	}
}

?>