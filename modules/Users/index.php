<?php

/*********************************************************************************/
/* CNB Your Account: An Advanced User Management System for phpnuke     		*/
/* ============================================                         		*/
/*                                                                      		*/
/* Copyright (c) 2004 by Comunidade PHP Nuke Brasil                     		*/
/* http://dev.phpnuke.org.br & http://www.phpnuke.org.br                		*/
/*                                                                      		*/
/* Contact author: escudero@phpnuke.org.br                              		*/
/* International Support Forum: http://ravenphpscripts.com/forum76.html 		*/
/*                                                                      		*/
/* This program is free software. You can redistribute it and/or modify 		*/
/* it under the terms of the GNU General Public License as published by 		*/
/* the Free Software Foundation; either version 2 of the License.       		*/
/*                                                                      		*/
/*********************************************************************************/
/* CNB Your Account it the official successor of NSN Your Account by Bob Marion	*/
/*********************************************************************************/

if ( !defined('MODULE_FILE') ) {
	die("Illegal Module File Access");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));

if(!defined("INDEX_FILE"))
	define('INDEX_FILE', is_index_file($module_name));// to define INDEX_FILE status

global $db, $user, $nuke_configs, $pn_Cookies, $pn_Sessions, $op, $userinfo, $username;

define('CNBYA', true);
include_once("modules/$module_name/includes/functions.php");

if((isset($username) && $username != ''))
{
	$username_check =  adv_filter($username, array('sanitize_string'), array('required'));
	if($username_check[0] != 'error')
		$username = $username_check[1];
}
	
$userpage = 1;

function userinfo($username='')
{
	global $db, $user, $users_system, $pn_Cookies, $pn_Sessions, $nuke_configs, $ya_config, $module_name, $userinfo, $hooks;
	
	$nuke_groups_cacheData = get_cache_file_contents('nuke_groups');
	
	$contents = '';
	$user_data = array();
			
	if(empty($username))
	{
		$redirect = (isset($userinfo['username']) && $userinfo['user_id'] != 0 && $userinfo['username'] != '') ? "index.php?modname=$module_name&op=userinfo&username=".$userinfo['username']."":"index.php?modname=$module_name&op=login";
		redirect_to($redirect);
		die();
	}
	else
	{
		$session_time = (_NOWTIME - (intval($nuke_configs['session_timeout']) * 60));
		$session_time = $session_time - ((int) ($session_time % 60));
		
		$result = $db->query("SELECT u.*, s.session_user_id as user_on_off FROM ".USERS_TABLE." AS u LEFT JOIN ".SESSIONS_TABLE." AS s ON s.session_user_id = u.user_id AND s.session_time >= ? WHERE u.username = ? GROUP BY u.user_id", [$session_time, $username]);
		
		if ($db->count() == 1)
		{
			$user_data = $result->results()[0];

			ya_custum_userfields($user_data, $user_data['user_id']);
			
			$user_data['is_your_profile'] = ((isset($user[1]) && strtolower($user_data['username']) == strtolower($user[1])) && phpnuke_validate_user_cookie($user, "user", $user_data)) ? true:false;
			
			$user_data['user_avatar'] = $users_system->get_avatar_url($user_data, $ya_config['avatar_max_width'], $ya_config['avatar_max_height']);
			
			$user_data['user_sig'] = stripslashes($user_data['user_sig']);
	
			$user_data['user_realname'] = (isset($user_data['user_realname']) && !empty($user_data['user_realname'])) ? $user_data['user_realname']:$user_data['username'];
	
			$user_data['user_gender'] = ($user_data['user_gender'] == 'mrs') ? _MRS:_MR;
			
			$group_title = (isset($nuke_groups_cacheData[$user_data['group_id']]['group_lang_titles'])&& $nuke_groups_cacheData[$user_data['group_id']]['group_lang_titles'] != '') ? phpnuke_unserialize(stripslashes($nuke_groups_cacheData[$user_data['group_id']]['group_lang_titles'])):"";
			$group_title = ($group_title != '') ? $group_title[$nuke_configs['currentlang']]:"";
			$group_colour = (isset($nuke_groups_cacheData[$user_data['group_id']]['group_colour'])&& $nuke_groups_cacheData[$user_data['group_id']]['group_colour'] != '') ? $nuke_groups_cacheData[$user_data['group_id']]['group_colour']:"#000000";
			$meta_tags = array(
				"url" => LinkToGT("index.php?modname=$module_name&op=userinfo&username=".$user_data['username']),
				"title" => _USERINFO." : $username",
				"description" => str_replace(array("\r","\n","\t"), "", _USERINFO." :  ".strip_tags($user_data['username'])),
				"keywords" => $user_data['username'],
				"prev" => '',
				"next" => '',
				"extra_meta_tags" => array()
			);
			$meta_tags = $hooks->apply_filters("userinfo_header_meta", $meta_tags, $module_name, $username);
				
			$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
			{
				return array_merge($all_meta_tags, $meta_tags);
			}, 10);		
			unset($meta_tags);

			$user_data = $hooks->apply_filters("userinfo_data", $user_data);
			
			$hooks->add_filter("global_contents", function ($block_global_contents) use($user_data, $module_name)
			{
				$block_global_contents = $user_data;
				$block_global_contents['user_id'] = $user_data['user_id'];
				$block_global_contents['username'] = $user_data['username'];
				$block_global_contents['module_name'] = $module_name;
				$block_global_contents['db_table'] = USERS_TABLE;
				$block_global_contents['db_id'] = 'user_id';
				return $block_global_contents;
			}, 10);	
	
			$list_group_ops = array();
			
			if($user_data['is_your_profile']){
				$list_group_ops['settings'] = "<p class=\"text-center\"><a href=\"".LinkToGT("index.php?modname=$module_name&op=edit_user")."\">"._USER_SETTINGS."</a></p>";
				$list_group_ops['logout'] = "<p class=\"text-center\"><a href=\"".LinkToGT("index.php?modname=$module_name&op=logout")."\">"._LOGOUT."</a></p>";
			}
			
			$list_group_ops = $hooks->apply_filters("users_info_ops", $list_group_ops, $user_data);	
	
			$list_group_items = array(
				'user_realname' => array(
					"icon" => "fa fa-user",
					"field_text" => _NAME_FAMILY,
					"field_value" => $user_data['user_realname']
				),
				'username' => array(
					"icon" => "fa fa-user",
					"field_text" => _USERNAME,
					"field_value" => $user_data['username']
				),
				'group_title' => array(
					"icon" => "fa fa-user",
					"color" => $group_colour,
					"field_text" => _GROUP_NAME,
					"field_value" => $group_title
				),
				'user_femail' => array(
					"icon" => "fa fa-user",
					"field_text" => _DISPLAY_EMAIL,
					"field_value" => $user_data['user_femail']
				),
				'user_website' => array(
					"icon" => "fa fa-link",
					"field_text" => _USER_WEBSITE,
					"field_value" => array("url" => $user_data['user_website'])
				),
				'user_gender' => array(
					"icon" => "fa fa-user",
					"field_text" => _USER_GENDER,
					"field_value" => $user_data['user_gender']
				),
				'user_address' => array(
					"icon" => "fa fa-user",
					"field_text" => _USER_ADDRESS,
					"field_value" => $user_data['user_address']
				),
				'user_regdate' => array(
					"icon" => "fa fa-user",
					"field_text" => _USER_REGDATE,
					"field_value" => nuketimes($user_data['user_regdate'])
				),
				'user_lastvisit' => array(
					"icon" => "fa fa-user",
					"field_text" => _USER_LASTVISIT,
					"field_value" => nuketimes($user_data['user_lastvisit'], true, true)
				),
				'user_sig' => array(
					"icon" => "fa fa-user",
					"field_text" => _USER_SIGN,
					"field_value" => $user_data['user_sig']
				),
				'user_interests' => array(
					"icon" => "fa fa-user",
					"field_text" => _USER_INTERESTS,
					"field_value" => $user_data['user_interests']
				),
				'user_points' => array(
					"icon" => "fa fa-user",
					"field_text" => _USER_POINTS,
					"field_value" => $user_data['user_points']
				),
			);
	
			if(!empty($user_data['user_birthday']))
			{
				$list_group_items['user_birthday'] = array(
					"icon" => "fa fa-user",
					"field_text" => _USER_BIRTHDAY,
					"field_value" => nuketimes($user_data['user_birthday'])
				);
			}
			if(isset($user_data['custom_fields']) && !empty($user_data['custom_fields']))
			{
				foreach($user_data['custom_fields'] as $custom_fields_key => $custom_fields_Value)
				{
					$list_group_items[$custom_fields_key] = array(
						"icon" => "fa fa-user",
						"field_text" => $custom_fields_Value[0],
						"field_value" => $custom_fields_Value[1]
					);
				}
			}
			
			if($user_data['is_your_profile'] || is_admin()){
				$list_group_items['user_credit'] = array(
					"icon" => "fa fa-user",
					"field_text" => _CREDITS_REMAIN,
					"field_value" => number_format($user_data['user_credit'])." "._RIAL
				);
			}	
			
			$list_group_items = $hooks->apply_filters("users_info_items", $list_group_items, $user_data);	

			if(file_exists("themes/".$nuke_configs['ThemeSel']."/userinfo.php"))
				include("themes/".$nuke_configs['ThemeSel']."/userinfo.php");
			elseif(function_exists("userinfo_html"))
				$contents .= userinfo_html($user_data);
			else 
			{
				$contents .= "
				<div class=\"container-fluid\">
					<div class=\"row profile_page\">
						<div class=\"col-sm-12\">
							<div class=\"panel panel-default text-"._TEXTALIGN1."\">
								<div class=\"panel-heading\">"._USER_PROFILE." ".$user_data['user_realname']."</div>
								<div class=\"panel-body\">
									<div class=\"col-sm-3 text-center\">
										<p><img src=\"" . (str_replace(' ', '%20', $user_data['user_avatar'])) . "\" width=\"100%\" height=\"100%\" class=\"img-circle\" title=\""._USER_AVATAR." ".$user_data['username']."\" alt=\""._USER_AVATAR." ".$user_data['username']."\" style=\"max-width:180px;\" /></p><br />
											".implode("\n", $list_group_ops)."
									</div>
									<div class=\"col-sm-9\">
										<div class=\"list-group\">
										".implode("\n", $list_group_items)."
										</div>
									</div>";
									if($user_data['user_about'] != '') {
									$contents .= "
									<div class=\"col-sm-12\">
										<div class=\"well well-sm\">
											<h2>"._USER_ABOUT." ".$user_data['user_realname']."</h2>
											".$user_data['user_about']."
										</div>
									</div>";
									}
									$contents .= "
								</div>
							</div>
						</div> 
					</div> 
				</div>";
			}
		}
		else
		{
			if($nuke_configs['have_forum'] == 1)
			{
				$forum_seo_profile_link = $nuke_configs['forum_seo_profile_link'];
				
				$result = $db->query("SELECT ".$users_system->user_fields['user_id']." as user_id FROM ".$users_system->users_table." WHERE ".$users_system->user_fields['username']." = ?", array($username));
				
				if($result->count() > 0)
				{
					$user_id = $result->results()[0]['user_id'];
					redirect_to(sprintf($users_system->profile_url, $user_id, $username));
					die();
				}
			}
			$contents .= OpenTable();
			$contents .= sprintf(_USER_NOT_FOUND, $username);
			$contents .= CloseTable();
		}
	}
	
	$contents = $hooks->apply_filters("userinfo_output", $contents, $username);
	
	$contents = show_modules_boxes($module_name, "profile", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
	
	$hooks->add_functions_vars(
		'userinfo_breadcrumb',
		array(
			"user_data" => $user_data,
		)
	);
	
	$hooks->add_filter("site_breadcrumb", "userinfo_breadcrumb", 10);
	
	include("header.php");
	$html_output .= $contents;	
	unset($user_data);
	include ("footer.php");
}

function login($submit = '', $username = '', $user_password = '', $remember_me = 0, $security_code = '', $security_code_id = '')
{
	global $db, $nuke_configs, $ya_config, $pn_Sessions, $pn_Cookies, $currentpage, $module_name, $visitor_ip, $users_system, $hooks;

	$login_errors = array();
	
	if(isset($submit) && $submit != '' && $username != '' && $user_password != '')
	{
		$hooks->do_action("users_login_submit", $username, $user_password);
		
		$code_accepted = true;
		
		if(extension_loaded("gd") && in_array("user_login", $nuke_configs['mtsn_gfx_chk']))
			$code_accepted = code_check($security_code, $security_code_id);
		
		if($code_accepted)
		{
			$currentpage = $pn_Cookies->get('currentpage');
			$setinfo = $db->table(USERS_TABLE)
							->where('username', $username)
							->first();
			$setinfo = $hooks->apply_filters("users_login_submit_result", $setinfo, $username);
			
			if ($db->count() == 0)
			{
				$login_errors[] = _INCORRECT_USERNAME;
			}
			elseif($setinfo['user_login_attempts'] >= $nuke_configs['mtsn_login_attempts'])
			{
				$login_errors[] = sprintf(_LAST_LOGIN_ATTEMPTS_ERROR, nuketimes($setinfo['user_login_block_expire'], true, true, true, 1));
			}
			elseif($db->count() == 1 AND $setinfo['user_id'] != 1 AND $setinfo['user_password'] != "" AND $setinfo['user_status'] == 1)
			{
				$dbpass = $setinfo['user_password'];
				
				if(phpnuke_check_password($user_password, $dbpass))
				{
					$expiration = (isset($remember_me) && $remember_me == 1) ? 2592000:false;

					$pn_Sessions->remove("userinfo");
					
					$user = phpnuke_generate_user_cookie("user", $setinfo['user_id'], $username, $dbpass, $expiration);
					add_log(sprintf(_SUCCESS_LOGIN_LOG, $username), 1);
					$db->table(SESSIONS_TABLE)
						->where('session_ip', $visitor_ip)
						->where('guest', 1)
						->delete();
						
					$db->table(USERS_TABLE)
						->where('username', $username)
						->update([
							'last_ip' => $visitor_ip,
							'user_login_attempts' => 0,
							'user_login_block_expire' => 0,
						]);
				
					// notifications
					///send message with sms to admins or members
					if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && isset($ya_config['notify']['sms']) && $ya_config['notify']['sms'] == 1 && $setinfo['user_phone'] != '')
					{
						$message = sprintf(_SUCCESS_LOGIN_LOG, $username."<br />".nuketimes(_NOWTIME, true, true, true, 1));
						pn_sms('send', $setinfo['user_phone'], $message);
					}
					///send message with sms to admins or members
					// notifications
					
					$users_system->online();
					
					$hooks->do_action("users_login_after");
					
					redirect_to($currentpage);
					die();
				}
				else
				{
					if(($setinfo['user_login_attempts']+1) == $nuke_configs['mtsn_login_attempts'])
					{
						$db->table(USERS_TABLE)
							->where('username', $username)
							->update([
								'user_login_attempts' => ["+", 1],
								'user_login_block_expire' => ($nuke_configs['mtsn_login_attempts_time']+_NOWTIME)
							]);
					}
						
					$login_errors[] = sprintf(_BAD_LOGIN_ATTEMPTS_ERROR, ($setinfo['user_login_attempts']+1), $nuke_configs['mtsn_login_attempts']);
				
					// notifications
					///send message with sms to admins or members
					if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && isset($ya_config['notify']['sms']) && $ya_config['notify']['sms'] == 1 && $setinfo['user_phone'] != '')
					{
						$message = sprintf(_ERROR_LOGIN_LOG, $username."<br />".nuketimes(_NOWTIME, true, true, true, 1));
						pn_sms('send', $setinfo['user_phone'], $message);
					}
					///send message with sms to admins or members
					// notifications
				}
			}
			elseif($db->count() == 1 OR $setinfo['user_status'] != 1)
			{
				if ($setinfo['user_status'] == 0)
					$login_errors[] = "<br><div class=\"text-center\"><font class=\"title\"><b>".sprintf(_USER_SUSPENDED, $username)."</b></font></div><br>\n";
				elseif ($setinfo['user_status'] == -1)
					$login_errors[] = "<br><div class=\"text-center\"><font class=\"title\"><b>".sprintf(_USER_DELETED, $username)."</b></font></div><br>\n";
				elseif ($setinfo['user_id'] == 1)
					$login_errors[] = "<br><div class=\"text-center\"><font class=\"title\"><b>"._USER_UNALLOWED."</b></font></div><br>\n";
				elseif ($setinfo['user_status'] == 2)
					$login_errors[] = "<br><div class=\"text-center\"><font class=\"title\"><b>".sprintf(_USER_NOT_APPROVED, $username)."</b></font></div><br>\n";
				elseif ($setinfo['user_status'] == 3)
					$login_errors[] = "<br><div class=\"text-center\"><font class=\"title\"><b>"._USER_NO_ACTIVATED."</b></font></div><br>\n";
				else
					$login_errors[] = "<br><div class=\"text-center\"><font class=\"title\"><b>".sprintf(_USER_OTHER_REASONS, $username)."</b></font></div><br>\n";
			}
			else
				$login_errors[] = _USER_POST_INCORRECT;
		}
		else
			$login_errors[] = _BADSECURITYCODE;
	}
	elseif(isset($submit) && $submit != '' && ($username == '' || $user_password == ''))
		$login_errors[] = _USER_OR_PASS_NOT_SENT;

	$login_errors = $hooks->apply_filters("users_login_errors", $login_errors);
		
	if (extension_loaded("gd") AND in_array("user_login" ,$nuke_configs['mtsn_gfx_chk']))
	{
		$sec_code_options = array(
			"input_attr" => array(
				"class" => "form-control input input-lg",
				"placeholder" => _SECCODE
			),
			"img_attr" => array(
				"width" => 90,
				"height" => 32,
				"class" => "code"
			)
		);	
		
		$sec_code_options = $hooks->apply_filters("users_login_seccode", $sec_code_options);
		
		$security_code_input = makepass("_USER_LOGIN", $sec_code_options);
	}
	
	if(file_exists("themes/".$nuke_configs['ThemeSel']."/login.html"))
		include("themes/".$nuke_configs['ThemeSel']."/login.html");
	elseif(function_exists("user_login"))
		$contents = users_login();
	else
	{
		$contents = "
		<form id=\"PNform\" method=\"post\" action=\"".LinkToGT("index.php?modname=$module_name")."\">
			<div class=\"title text-center\">
				<div class=\"row\">
					<div class=\"col-md-4 col-xs-4\">
						<div class=\"logo\"></div>
					</div>
					<div class=\"col-md-8 col-xs-8\">
						<h2>"._USER_LOGIN_TITLE."</h2>
					</div>
				</div>
			</div>
			<span class=\"login_errors\">".((!empty($login_errors)) ? implode("<br />", $login_errors):"")."</span>
			<div class=\"form-group\">
				<div class=\"input-group\"> <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-envelope login_form_icons\"></i></span> 
					<input type=\"username\" name=\"username\" class=\"form-control input input-lg\" placeholder=\""._USERNAME."\">
				</div>
			</div>
			<div class=\"form-group\">
				<div class=\"input-group\"> <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-lock login_form_icons\"></i></span> 
					<input type=\"password\" name=\"user_password\" class=\"form-control input input-lg\" >
				</div>
			</div>";
			if (extension_loaded("gd") AND in_array("user_login" ,$nuke_configs['mtsn_gfx_chk']) && ($security_code_input['input'] != '' || $security_code_input['image'] != ''))
				$contents .= "
				<div class=\"form-group\">
					<div class=\"input-group\"> <span class=\"input-group-addon\">".$security_code_input['image']."</span> 
						".$security_code_input['input']."
					</div>
				</div>
				";										
			$contents .="<div class=\"checkbox text-left\">
				<label>
					<input type=\"checkbox\" name=\"remember_me\" value=\"1\"><span class=\"remember_me\">"._REMEMBER_ME."</span>
				</label>
			</div>
			<div class=\"butn\">
				<button class=\"btn btn-default btn-block btn-lg\" type=\"submit\" name=\"submit\" value=\"submit\"><span class=\"glyphicon glyphicon-share-alt\"></span> "._LOGIN."</button>
			</div>
			<div class=\"forgot text-center\"> <a href=\"".LinkToGT("index.php?modname=$module_name&op=reset_password")."\"  data-toggle=\"modal\" data-target=\"#sitemodal\">"._USER_FORGET_PASSWORD."</a>
			</div>
			<div class=\"new_here text-center\">
				<p>"._USER_NOT_REGISTERED." <a href=\"".LinkToGT("index.php?modname=$module_name&op=register")."\">"._USER_REGISTER_INSITE."</a>
				</p>
			</div>
			<input type=\"hidden\" name=\"op\" value=\"login\" />
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>";
	}
	$contents = (!empty($login_errors)) ? "<div class=\"text-center\">".implode("<br />", $login_errors)."<br />"._GOBACK."</div>":$contents;
	
	$contents = $hooks->apply_filters("user_login_form", $contents);

	$ya_config['meta_tags'] = array(
		"url" => LinkToGT("index.php?modname=$module_name"),
		"title" => _LOGIN,
		"description" => "",
		"keywords" => "",
		"prev" => '',
		"next" => '',
		"extra_meta_tags" => array()
	);
	
	$ya_config['meta_tags'] = $hooks->apply_filters("login_header_meta", $ya_config['meta_tags'], $module_name);
			
	ya_html_output($ya_config, $contents);
}

function register($submit = '', $users_fields = array(), $invitation_code = '', $security_code = '', $security_code_id = '')
{
	global $db, $nuke_configs, $pn_Cookies, $pn_Sessions, $ya_config, $coppa_yes, $tos_yes, $invitation_code, $invited_email, $module_name, $visitor_ip, $check_num, $username, $hooks;
	
	if($ya_config['email_activatation'] == 1 && isset($check_num) && $check_num != '' && isset($username) && $username != '')
	{
		$check_num_check =  adv_filter($check_num, array('sanitize_string'), array('required'));
		if($check_num_check[0] != 'error')
			$check_num = $check_num_check[1];
	
		$db->table(USERS_TABLE)
					->where("username", $username)
					->where("check_num", $check_num)
					->update(array("user_status" => "1", "check_num" => ''));
		if($ya_config['sendaddmail'] == 1 && $db->count() > 0)
		{
			$result = $db->table(USERS_TABLE)
					->where("username", $username)
					->select(array("user_email", "user_realname"));
			$user_email = $result->results()[0]['user_email'];
			$user_realname = $result->results()[0]['user_realname'];
		
			$message = array(
				"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "user_email_activated.txt"),
				"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
				"username" => $username, 
				"user_realname" => $user_realname,  
				"login_url" => LinkToGT("index.php?modname=$module_name"), 
			);
			
			phpnuke_mail($user_email, sprintf(_USER_EMAIL_ACTIVATED, $username, $nuke_configs['sitename']), $message);
			phpnuke_mail($nuke_configs['adminmail'], sprintf(_USER_EMAIL_ACTIVATED, $username, $nuke_configs['sitename']), $message);
			
			$register_message = sprintf(_USER_REGENERATE_PASS_MESSAGE5, $nuke_configs['sitename']);
			$link_to_redirect = LinkToGT("index.php?modname=$module_name&op=userinfo&username=$username");
			$link_to_redirect_html = "<a class=\"btn btn-default btn-block btn-lg\" href=\"".$link_to_redirect."\"><span class=\"glyphicon glyphicon-repeat\"></span> "._USER_PROFILE_PAGE."</a>";
		}
		else
		{
			$register_message = _USER_REGENERATE_PASS_MESSAGE6;
			$link_to_redirect = LinkToGT($nuke_configs['nukeurl']);
			$link_to_redirect_html = "<a class=\"btn btn-default btn-block btn-lg\" href=\"".$link_to_redirect."\"><span class=\"glyphicon glyphicon-repeat\"></span> "._GO_TO_MAIN_PAGE."</a>";
		}
		
		$contents = "
		<form id=\"Pnform\">
			<div class=\"title text-center\">
				<div class=\"row\">
					<div class=\"col-md-4 col-xs-4\">
						<div class=\"logo\"></div>
					</div>
					<div class=\"col-md-8 col-xs-8\">
				<h2 class=\"text-center\">"._USER_END_OF_REGISTERATION."</h2>
					</div>
				</div>
			</div>
			<div class=\"row\">
				<div class=\"col-xs-12 col-sm-12 col-md-12\">
					<p class=\"text-justify\">".$register_message."</p>
				</div>
			</div>
			<div class=\"butn\">
				$link_to_redirect_html
			</div>
		</form>";
			
		$ya_config['meta_tags'] = array(
			"url" => LinkToGT("index.php?modname=$module_name&op=register&username=$username&check_num=$check_num"),
			"title" => _REGISTER,
			"description" => "",
			"keywords" => "",
			"prev" => '',
			"next" => '',
			"extra_meta_tags" => array("<meta http-equiv=\"refresh\" content=\"5;URL='$link_to_redirect'\" />")
		);
		
		$ya_config['meta_tags'] = $hooks->apply_filters("register_email_activatation_header_meta", $ya_config['meta_tags'], $module_name, $username, $check_num, $link_to_redirect);
	
		ya_html_output($ya_config, $contents);
	}
	
	$contents = '';
	
	if($ya_config['allowuserreg'] != 1)
	{
		include("header.php");
		$html_output .= OpenTable();
		$html_output .="<p class=\"text-center\" style=\"height:200px;\">"._USER_REGISTRATION_DISABLED."</p>";
		$html_output .= CloseTable();
		include("footer.php");
	}
	
	if(!isset($_POST) || (isset($_POST) && empty($_POST)))
	{
		$pn_Sessions->remove('coppa_yes');
		$pn_Sessions->remove('tos_yes');
		$pn_Sessions->remove('invitation_yes');
		$pn_Sessions->remove('invitation_code');
		$pn_Sessions->remove('register_yes');
	}
	
	// coppa check
	if($ya_config['coppa'] == 1)
	{
		$contents = '';
		$coppa_yes = intval($coppa_yes);
		
		if($coppa_yes == 0)
		{
			$contents .= "<form id=\"Pnform\" method=\"post\" action=\"".LinkToGT("index.php?modname=$module_name&op=register")."\">
				<div class=\"title text-center\">
					<div class=\"row\">
						<div class=\"col-md-4 col-xs-4\">
							<div class=\"logo\"></div>
						</div>
						<div class=\"col-md-8 col-xs-8\">
						<h2 class=\"text-center\">"._YACOPPA1."</h2>
						</div>
					</div>
				</div>
				<div class=\"row\">
					<div class=\"col-xs-12 col-sm-12 col-md-12\">
						<p>"._YACOPPA2."</p>
						<p>"._YACOPPA3."</p>
					</div>
				</div>
				<div class=\"form-group text-center\">
					<label class=\"radio-inline\">
						<input type=\"radio\" name=\"coppa_yes\" value=\"1\"> "._YES."
					</label>
					<label class=\"radio-inline\">
						<input type=\"radio\" name=\"coppa_yes\" value=\"2\" checked> "._NO."
					</label>
				</div>
				<div class=\"butn\">
					<button class=\"btn btn-default btn-block btn-lg\" type=\"submit\" name=\"submit\" value=\"submit\"><span class=\"glyphicon glyphicon-share-alt\"></span> "._YA_CONTINUE."</button>
				</div>
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>";
		}
		elseif ($coppa_yes == 1)
			$pn_Sessions->set('coppa_yes', 1);
		elseif ($coppa_yes == 2)
		{
			$contents .= "
			<form id=\"Pnform\">
				<div class=\"title text-center\">
					<div class=\"row\">
						<div class=\"col-md-4 col-xs-4\">
							<div class=\"logo\"></div>
						</div>
						<div class=\"col-md-8 col-xs-8\">
							<h2 class=\"text-center\">"._YACOPPA1."</h2>
						</div>
					</div>
				</div>
				<div class=\"row\">
					<div class=\"col-xs-12 col-sm-12 col-md-12\">
						<p class=\"login_errors\">"._YACOPPA2."</p>
						<p class=\"login_errors\">".sprintf(_YACOPPA4, "<a href=\"".LinkToGT("index.php?modname=Feedback")."\" />"._CLICK_HERE."</a>")."</p>
					</div>
				</div>
				<div class=\"butn\">
					<button class=\"btn btn-default btn-block btn-lg\" type=\"submit\" name=\"submit\" value=\"submit\"><span class=\"glyphicon glyphicon-repeat\"></span> انتخاب مجدد</button>
				</div>
			</form>";
		}
	}
	else
		$pn_Sessions->set('coppa_yes', 1);
	
	// tos check
	if($ya_config['tos'] == 1)
	{
		if($pn_Sessions->get('coppa_yes', false) == 1)
		{
			$tos_yes = intval($tos_yes);
			$mttos = $ya_config['mttos'];
			$contents = '';
			if($tos_yes == 0)
			{
				$contents .= "<form id=\"Pnform\" method=\"post\" action=\"".LinkToGT("index.php?modname=$module_name&op=register")."\">
					<div class=\"title text-center\">
						<div class=\"row\">
							<div class=\"col-md-4 col-xs-4\">
								<div class=\"logo\"></div>
							</div>
							<div class=\"col-md-8 col-xs-8\">
								<h2 class=\"text-center\">"._YATOS1."</h2>
							</div>
						</div>
					</div>
					<div class=\"row\">
						<div class=\"col-xs-12 col-sm-12 col-md-12\">
							$mttos
						</div>
					</div>
					<div class=\"form-group text-center\">
						<label class=\"radio-inline\">
							<input type=\"radio\" name=\"tos_yes\" value=\"1\"> "._USER_CONFIRMED."
						</label>
						<label class=\"radio-inline\">
							<input type=\"radio\" name=\"tos_yes\" value=\"2\" checked> "._USER_NOT_CONFIRMED."
						</label>
					</div>
					<div class=\"butn\">
						<button class=\"btn btn-default btn-block btn-lg\" type=\"submit\" name=\"submit\" value=\"submit\"><span class=\"glyphicon glyphicon-share-alt\"></span> "._YA_CONTINUE."</button>
					</div>
					<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
				</form>";
			}
			elseif ($tos_yes == 2)
			{
				$contents .= "
				<form id=\"Pnform\">
					<div class=\"title text-center\">
						<div class=\"row\">
							<div class=\"col-md-4 col-xs-4\">
								<div class=\"logo\"></div>
							</div>
							<div class=\"col-md-8 col-xs-8\">
								<h2 class=\"text-center\">"._YATOS1."</h2>
							</div>
						</div>
					</div>
					<div class=\"row\">
						<div class=\"col-xs-12 col-sm-12 col-md-12\">
							<p class=\"login_errors text-justify\">"._YATOS4."</p>
							<p class=\"login_errors text-justify\">"._YATOS5."</p>
						</div>
					</div>
					<div class=\"butn\">
						<button class=\"btn btn-default btn-block btn-lg\" type=\"submit\" name=\"submit\" value=\"submit\"><span class=\"glyphicon glyphicon-repeat\"></span> "._RESELECT."</button>
					</div>
				</form>";
			}
			elseif ($tos_yes == 1)
				$pn_Sessions->set('tos_yes', 1);
		}
	}
	else
		$pn_Sessions->set('tos_yes', 1);
	
	// invitation check
	if($ya_config['invitation'] == 1)
	{
		if($pn_Sessions->get('coppa_yes', false) == 1 && $pn_Sessions->get('tos_yes', false) == 1)
		{
			$contents = '';
			
			if($invitation_code != '' && $invited_email != '')
			{
				$invited_email_check =  adv_filter($invitation_code, array('sanitize_email'), array('required','valid_email'));
				if($invited_email_check[0] != 'error')
					$invited_email = $invited_email_check[1];
				$invitation_code_check =  adv_filter($invitation_code, array('sanitize_string'), array('required'));
				if($invitation_code_check[0] != 'error')
					$invitation_code = $invitation_code_check[1];
			}
	
			if($invitation_code == '' && $invited_email == '')
			{
				$contents .= "<form id=\"Pnform\" method=\"post\" action=\"".LinkToGT("index.php?modname=$module_name&op=register")."\">
					<div class=\"title text-center\">
						<div class=\"row\">
							<div class=\"col-md-4 col-xs-4\">
								<div class=\"logo\"></div>
							</div>
							<div class=\"col-md-8 col-xs-8\">
								<h2 class=\"text-center\">"._YINVITE1."</h2>
							</div>
						</div>
					</div>
					<div class=\"row\">
						<div class=\"col-xs-12 col-sm-12 col-md-12\">
							"._ENTERINCITATIONCODE."
						</div>
					</div>
					<div class=\"form-group\">
						<div class=\"input-group\"> <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-envelope login_form_icons\"></i></span> 
							<input type=\"text\" name=\"invitation_code\" class=\"form-control input input-lg\" placeholder=\""._YINVITE."\">
						</div>
					</div>
					<div class=\"form-group\">
						<div class=\"input-group\"> <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-envelope login_form_icons\"></i></span> 
							<input type=\"text\" name=\"invited_email\" class=\"form-control input input-lg\" placeholder=\""._YINVITED_EMAIL."\">
						</div>
					</div>
					<div class=\"butn\">
						<button class=\"btn btn-default btn-block btn-lg\" type=\"submit\" name=\"submit\" value=\"submit\"><span class=\"glyphicon glyphicon-share-alt\"></span> "._YA_CONTINUE."</button>
					</div>
					<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
				</form>";
			}
			else
			{
				$invitation_referrer = checkinvite($invitation_code, $invited_email);
				if ($invitation_referrer == 0)
				{
					$contents .= "
					<form id=\"Pnform\">
						<div class=\"title text-center\">
							<div class=\"row\">
								<div class=\"col-md-4 col-xs-4\">
									<div class=\"logo\"></div>
								</div>
								<div class=\"col-md-8 col-xs-8\">
									<h2 class=\"text-center\">"._YINVITE1."</h2>
								</div>
							</div>
						</div>
						<div class=\"row\">
							<div class=\"col-xs-12 col-sm-12 col-md-12\">
								<p class=\"login_errors text-justify\">"._WRONG_CODE."</p>
							</div>
						</div>
						<div class=\"butn\">
							<button class=\"btn btn-default btn-block btn-lg\" type=\"submit\" name=\"submit\" value=\"submit\"><span class=\"glyphicon glyphicon-repeat\"></span> "._RESELECT."</button>
						</div>
					</form>";
				}
				elseif($invitation_referrer > 0)
				{
					$pn_Sessions->set('invitation_code', $invitation_code);
					$pn_Sessions->set('invitation_referrer', $invitation_referrer);
					$pn_Sessions->set('invitation_yes', 1);
				}
			}
		}
	}
	else
		$pn_Sessions->set('invitation_yes', 1);

	if(isset($submit) && $submit != '' && !empty($users_fields) && $pn_Sessions->get('register_yes', false) == 1)
	{
		$hooks->do_action("users_register_submit", $users_fields['username'], $users_fields['user_password']);
		
		$register_errors = array();
		$code_accepted = true;
		
		$contents = '';
		$ok_register_contents = '';
		if(extension_loaded("gd") && in_array("user_sign_up", $nuke_configs['mtsn_gfx_chk']))
			$code_accepted = code_check($security_code, $security_code_id);
		
		if($code_accepted)
		{
			global $PnValidator;
			
			$PnValidator->add_filter("fixtext", 'ya_fixtext'); 

			$validation_rules = array(
				'username'			=> 'required|max_len,'.$ya_config['nick_max'].'|min_len,'.$ya_config['nick_min'].'',
				'user_email'		=> 'required|valid_email',
				'user_password'		=> 'required|max_len,'.$ya_config['pass_max'].'|min_len,'.$ya_config['pass_min'].'',
				'user_password_cn'	=> 'required|equalsfield,user_password|max_len,'.$ya_config['pass_max'].'|min_len,'.$ya_config['pass_min'].'',
			);
			
			$filter_rules = array(
				'username'			=> 'sanitize_string|fixtext',
				'user_email'		=> 'sanitize_email|fixtext',
				'user_password'		=> 'addslashes|fixtext',
				'user_password_cn'	=> 'addslashes|fixtext',
			);
			
			// Get or set the filtering rules
			
			if($ya_config['doublecheckemail'] == 1)
			{
				$validation_rules['user_email_cn'] = 'required|equalsfield,user_email';
				$filter_rules['user_email_cn'] = 'sanitize_email|fixtext';
			}
			
			$validation_rules = $hooks->apply_filters("users_registre_validation_rules", $validation_rules);
			$filter_rules = $hooks->apply_filters("users_registre_filter_rules", $filter_rules);			
			
			$PnValidator->validation_rules($validation_rules); 
			$PnValidator->filter_rules($filter_rules);
			
			$users_fields = $PnValidator->sanitize($users_fields, array('username'), true, true);
			$validated_data = $PnValidator->run($users_fields);

			if($validated_data !== FALSE)
				$users_fields = $validated_data;
			else
				$register_errors[] = "<p align=\"center\">".$PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br />')."</p>";
			
			if(empty($register_errors))
			{
				extract($users_fields);
				define("NOAJAX_REQUEST", true);
				
				if(!check_register_fields('username', $username))
					$register_errors[] = _USER_HAS_REGISTERED;
				
				if(!check_register_fields('user_email', $user_email))
					$register_errors[] = _USER_EMAIL_HAS_SELECTED;
				
				$bad_mail = ($ya_config['bad_mail'] != '' && !is_array($ya_config['bad_mail'])) ? explode("\n", str_replace("\r","",$ya_config['bad_mail'])):array();
				$bad_username = ($ya_config['bad_username'] != '' && !is_array($ya_config['bad_username'])) ? explode("\n", str_replace("\r","",$ya_config['bad_username'])):array();
				$bad_nick = ($ya_config['bad_nick'] != '' && !is_array($ya_config['bad_nick'])) ? explode("\n", str_replace("\r","",$ya_config['bad_nick'])):array();
				
				if(in_array($user_email, $bad_mail))
					$register_errors[] = _USER_BAD_EMAIL;
				
				if(in_array($username, $bad_username))
					$register_errors[] = _USER_BAD_NAME;
				
				if(strrpos($username,' ') > 0)
					$register_errors[] = _NICKNOSPACES;
				
				if(in_array($user_realname, $bad_nick))
					$register_errors[] = _USER_BAD_REALNAME;
					
				if($ya_config['doublecheckemail'] == 1 && $user_email != $user_email_cn)
					$register_errors[] = _USER_EMAILS_NOT_EQUALED;
				
				$register_errors = $hooks->apply_filters("users_registre_check_fields", $register_errors, $users_fields);
				
				if(empty($register_errors))
				{
					$hashed_user_password = phpnuke_hash_password($user_password);
				
					$last_user_id = $db->table(USERS_TABLE)
										->order_by(['user_id' => 'DESC'])
										->limit(0,1)
										->first();
										
					$last_user_id = (isset($last_user_id['user_id'])) ? (intval($last_user_id['user_id'])+1):0;

					$user_status = USER_STATUS_ACTIVE;
					$check_num = '';
					
					if($ya_config['requireadmin'] == 1)
						$user_status = USER_STATUS_REQUIRE_ADMIN;	
					
					if($ya_config['email_activatation'] == 1)
					{
						$user_status = USER_STATUS_EMAIL_ACTIVATE;
						$check_num = random_str(32);
					}
					
					$insert_query = $hooks->apply_filters("users_register_insert_query", [
						'user_id' => $last_user_id,
						'group_id' => 2,
						'username' => $username,
						'user_email' => $user_email,
						'user_ip' => $visitor_ip,
						'user_realname' => $user_realname,
						'user_password' => $hashed_user_password,
						'user_regdate' => _NOWTIME,
						'user_lastvisit' => _NOWTIME,
						'check_num' => $check_num,
						'user_status' => $user_status,
						'user_referrer' => (($ya_config['invitation'] == 1 && $pn_Sessions->exists('invitation_referrer')) ? $pn_Sessions->get('invitation_referrer', false):0),
					]);
					
					$result = $db->table(USERS_TABLE)
									->insert($insert_query);
					
					if(!$result)
						$register_errors[] = $db->getErrors('last')['message'];
					else
					{
						if($ya_config['invitation'] == 1)
						{
							$referer_user_id = $pn_Sessions->get('invitation_referrer', false);
							
							$result = $db->table(USERS_INVITES_TABLE)
								->where('code', $pn_Sessions->get('invitation_code', false))
								->where('rid', $referer_user_id)
								->delete();
								
							update_points(22,$referer_user_id);
						}
						
						if($ya_config['email_activatation'] == 1)
						{
							$finishlink = LinkToGT("index.php?modname=$module_name&op=register&username=$username&check_num=$check_num");

							$message = array(
								"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "user_email_activation.txt"),
								"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
								"username" => $username, 
								"user_password" => $user_password, 
								"user_email" => $user_email, 
								"from_ip" => $visitor_ip, 
								"user_realname" => $user_realname,  
								"finishlink" => $finishlink
							);
							
							phpnuke_mail($user_email, sprintf(_REGISTRATIONSUB, $username, $nuke_configs['sitename']), $message);
						}
						
						if ($ya_config['send_email_af_reg'] == 1 && $ya_config['email_activatation'] != 1)
						{
							$message = array(
								"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "user_added.txt"),
								"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
								"username" => $username, 
								"user_password" => $user_password, 
								"user_email" => $user_email, 
								"from_ip" => $visitor_ip, 
								"user_realname" => $user_realname,  
								"login_link" => LinkToGT("index.php?modname=Users")
							);
							
							phpnuke_mail($user_email, sprintf(_REGISTRATIONSUB, $username, $nuke_configs['sitename']), $message);
							if($ya_config['sendaddmail'] == 1)
								phpnuke_mail($nuke_configs['adminmail'], sprintf(_REGISTRATIONSUB, $username, $nuke_configs['sitename']), $message);
						}
						
						$userinfo = $db->table(USERS_TABLE)
									->where('username', $username)
									->first();
									
						if ($db->count() == 1)
						{
							$expiration = 2592000;
							$ok_register_contents .= "<div class=\"text-center\"><b>$userinfo[username]:</b></div>";
							
							if($user_status == USER_STATUS_ACTIVE)
							{
								$user = phpnuke_generate_user_cookie("user", $last_user_id, $username, $hashed_user_password, $expiration);
								$ok_register_contents .= "<div class=\"text-center\">".sprintf(_USER_REGENERATE_PASS_MESSAGE5, $nuke_configs['sitename'])."</div>";
							}
							
							if($user_status == USER_STATUS_EMAIL_ACTIVATE)
								$ok_register_contents .= "<div class=\"text-center\">"._USER_ACTIVATION_EMAIL_SENT."</div>";
							
							if($user_status == USER_STATUS_REQUIRE_ADMIN)
								$ok_register_contents .= "<div class=\"text-center\">".sprintf(_USER_ADMIN_APPROVE_EMAIL_SENT, $nuke_configs['sitename'])."</div>";
						}
						else
							$register_errors[] = _SOMETHINGWRONG;
					}
				}
			}
		}
		else
			$register_errors[] = _BADSECURITYCODE;
		
		if(empty($register_errors))
		{
			$currentpage = $pn_Cookies->get('currentpage');
			$currentpage = (isset($currentpage) && $currentpage != '') ? LinkToGT($currentpage):$nuke_configs['nukeurl'];
			$contents = "
			<form id=\"Pnform\">
				<div class=\"title text-center\">
					<div class=\"row\">
						<div class=\"col-md-4 col-xs-4\">
							<div class=\"logo\"></div>
						</div>
						<div class=\"col-md-8 col-xs-8\">
					<h2 class=\"text-center\">"._USER_END_OF_REGISTERATION."</h2>
						</div>
					</div>
				</div>
				<div class=\"row\">
					<div class=\"col-xs-12 col-sm-12 col-md-12\">
						<p class=\"text-justify\">$ok_register_contents</p>
					</div>
				</div>
				<div class=\"butn\">
					<a class=\"btn btn-default btn-block btn-lg\" href=\"$currentpage\"><span class=\"glyphicon glyphicon-repeat\"></span> "._GO_TO_MAIN_PAGE."</a>
				</div>
			</form>";
			
			$contents = $hooks->apply_filters("users_register_ok", $contents);
			$pn_Sessions->remove('coppa_yes');
			$pn_Sessions->remove('tos_yes');
			$pn_Sessions->remove('invitation_yes');
			$pn_Sessions->remove('invitation_code');
			$pn_Sessions->remove('register_yes');
			
			$hooks->do_action("users_register_after");
		}
	}
	
	if($pn_Sessions->get('coppa_yes', false) == 1 && $pn_Sessions->get('tos_yes', false) == 1 && $pn_Sessions->get('invitation_yes', false) == 1)
	{
		$security_code_input = '';
		if (extension_loaded("gd") AND in_array("user_sign_up" ,$nuke_configs['mtsn_gfx_chk']))
		{
			$sec_code_options = array(
				"input_attr" => array(
					"class" => "form-control input input-lg",
					"placeholder" => _SECCODE
				),
				"img_attr" => array(
					"width" => 90,
					"height" => 32,
					"class" => "code"
				)
			);	
			
			$sec_code_options = $hooks->apply_filters("users_register_seccode", $sec_code_options);
			
			$security_code_input = makepass("_USER_REGISTER", $sec_code_options);
		}
		$pn_Sessions->set('register_yes', 1);
		
		$col_num = ($ya_config['doublecheckemail'] == 1) ? 6:12;
	
		if(file_exists("themes/".$nuke_configs['ThemeSel']."/register.html"))
			include("themes/".$nuke_configs['ThemeSel']."/register.html");
		elseif(function_exists("register_html"))
			$contents = register_html($ya_config, $module_name, $security_code_input);
		else
		{
			$contents = "
			<form id=\"Pnform\" method=\"post\" action=\"".LinkToGT("index.php?modname=$module_name&op=register")."\">
				<div class=\"title text-center\">
					<div class=\"row\">
						<div class=\"col-md-4 col-xs-4\">
							<div class=\"logo\"></div>
						</div>
						<div class=\"col-md-8 col-xs-8\">
							<h2 class=\"text-center\">"._USER_REGISTRATION_TITLE."</h2>
						</div>
					</div>
				</div>";
				if(!empty($register_errors))
				{
				$contents .="<div class=\"row\">
					<div class=\"col-xs-12 col-sm-12 col-md-12\">
						<div class=\"form-group login_errors\">
							".implode("<br />", $register_errors)."
						</div>
					</div>
				</div>";
				}
				$contents .="
				<div class=\"row\">
					<div class=\"col-xs-12 col-sm-6 col-md-6\">
						<div class=\"form-group\">
							<div class=\"input-group\">	<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-user registration_form_icons\"></i></span> 
								<input type=\"text\" name=\"users_fields[username]\" id=\"register_username\" class=\"form-control input input-lg text-left required\" placeholder=\""._USERNAME."\" ".str_replace(array('{MODE}','{DEFAULT}'), array('new', ''), data_verification_parse($ya_config['data_verification']['username']))." />
							</div>
						</div>
					</div>
					<div class=\"col-xs-12 col-sm-6 col-md-6\">
						<div class=\"form-group\">
							<div class=\"input-group\">	<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-user registration_form_icons\"></i></span> 
								<input type=\"text\" name=\"users_fields[user_realname]\" id=\"register_user_realname\" class=\"form-control input input-lg text-"._TEXTALIGN1."\" placeholder=\""._REALNAME."\" ".str_replace(array('{MODE}','{DEFAULT}'), array('new', ''), data_verification_parse($ya_config['data_verification']['user_realname']))." />
							</div>
						</div>
					</div>
				</div>
				<div class=\"row\">
					<div class=\"col-xs-12 col-sm-$col_num col-md-$col_num\">
						<div class=\"form-group\">
							<div class=\"input-group\">	<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-envelope registration_form_icons\"></i></span> 
								<input type=\"email\" name=\"users_fields[user_email]\" id=\"register_user_email\" class=\"form-control input input-lg text-left\" placeholder=\""._EMAIL."\" ".str_replace(array('{MODE}','{DEFAULT}'), array('new', ''), data_verification_parse($ya_config['data_verification']['user_email']))." />
							</div>
						</div>
					</div>";
					if($ya_config['doublecheckemail'] == 1)
					{
					$contents .="
					<div class=\"col-xs-12 col-sm-$col_num col-md-$col_num\">
						<div class=\"form-group\">
							<div class=\"input-group\">	<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-envelope registration_form_icons\"></i></span> 
								<input type=\"email\" name=\"users_fields[user_email_cn]\" id=\"register_user_email_cn\" class=\"form-control input input-lg text-left\" placeholder=\""._RETYPEEMAIL."\" ".data_verification_parse($ya_config['data_verification']['user_email_cn'])." />
							</div>
						</div>
					</div>";			
					}
					$contents .="
				</div>
				<div class=\"row\">
					<div class=\"col-xs-12 col-sm-6 col-md-6\">
						<div class=\"form-group\">
							<div class=\"input-group\">	<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-lock registration_form_icons\"></i></span> 
								<input type=\"password\" name=\"users_fields[user_password]\" id=\"register_user_password\" class=\"form-control input input-lg text-left\" placeholder=\""._PASSWORD."\" ".data_verification_parse($ya_config['data_verification']['user_password'])." >
							</div>
						</div>
					</div>
					<div class=\"col-xs-12 col-sm-6 col-md-6\">
						<div class=\"form-group\">
							<div class=\"input-group\">	<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-lock registration_form_icons\"></i></span> 
								<input type=\"password\" name=\"users_fields[user_password_cn]\" id=\"register_user_password_cn\" class=\"form-control input input-lg text-left\" placeholder=\""._RETYPEPASSWORD."\" ".data_verification_parse($ya_config['data_verification']['user_password_cn'])." />
							</div>
						</div>
					</div>
				</div>";
				if (extension_loaded("gd") AND in_array("user_sign_up" ,$nuke_configs['mtsn_gfx_chk']) && ($security_code_input['input'] != '' || $security_code_input['image'] != ''))
				{
					$contents .= "
					<div class=\"form-group\">
						<div class=\"input-group\"> <span class=\"input-group-addon\">".$security_code_input['image']."</span> 
							".$security_code_input['input']."
						</div>
					</div>
					";	
				}
				$contents .="
				<div class=\"butn\">
					<button class=\"btn btn-default btn-block btn-lg\" type=\"submit\" name=\"submit\" value=\""._REGISTER."\"><span class=\"glyphicon glyphicon-share-alt\"></span> "._REGISTER."</button>
				</div>
				<div class=\"already_member\">
					<p>"._USER_IS_REGISTERED." <a href=\"".LinkToGT("index.php?modname=$module_name&op=login")."\"> "._LOGIN."</a>
					</p>
				</div>
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>
			<script>
			  $.validate({
				form : '#Pnform',
				modules : 'security',
			  });
			</script>";
		}
	}
	
	$contents = $hooks->apply_filters("users_register_form", $contents);
	
	$ya_config['meta_tags'] = array(
		"url" => LinkToGT("index.php?modname=$module_name&op=register"),
		"title" => _REGISTER,
		"description" => "",
		"keywords" => "",
		"prev" => '',
		"next" => '',
		"extra_meta_tags" => array()
	);
	$ya_config['meta_tags'] = $hooks->apply_filters("register_header_meta", $ya_config['meta_tags'], $module_name);
		
	ya_html_output($ya_config, $contents);
}

function logout()
{
	global $pn_Cookies, $pn_Sessions, $module_name, $userinfo, $db, $currentpage, $nuke_configs, $hooks;
	
	$hooks->do_action("users_logout_before");
	
	$pn_Cookies->delete("user");
	$pn_Sessions->remove("userinfo");
		
	$db->table(SESSIONS_TABLE)
		->where('session_user_id', $userinfo['user_id'])
		->delete();
	
	$db->query("OPTIMIZE TABLE ".SESSIONS_TABLE."")->alter();
	
	$meta_tags = array(
		"url" => LinkToGT("index.php?modname=$module_name&op=logout"),
		"title" => _LOGOUT,
		"description" => "",
		"keywords" => "",
		"prev" => '',
		"next" => '',
		"extra_meta_tags" => array("<meta http-equiv=\"refresh\" content=\"5;URL='".LinkToGT($currentpage)."'\" />")
	);
	$meta_tags = $hooks->apply_filters("logout_header_meta", $meta_tags, $module_name, $currentpage);
		
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);		
	unset($meta_tags);
	
	$hooks->do_action("users_logout_after");
	
	include("header.php");
	$html_output .= title(_YOUARELOGGEDOUT);
	include("footer.php");

}

function reset_password($mode='', $credit_code='', $reset_password_username='', $reset_password_user_email='', $security_code='', $security_code_id='', $new_user_password=array(), $resend=false)
{
	global $db, $nuke_configs, $ya_config, $pn_salt, $pn_Sessions, $pn_Cookies, $module_name, $visitor_ip, $hooks;
	
	$contents = '';
	$code_accepted = true;
	$result = array();
	$num_user = 0;
	
	$reset_password_username = (isset($reset_password_username) && $reset_password_username != '') ? $reset_password_username:(($pn_Cookies->exists('reset_password_username')) ? $pn_Cookies->get('reset_password_username'):"");

	if((isset($reset_password_username) && $reset_password_username != '') || (isset($reset_password_user_email) && $reset_password_user_email != ''))
	{
		if($reset_password_username != '' && $reset_password_user_email != '')
		{
			$result = $db->table(USERS_TABLE)
						->where('username', $reset_password_username)
						->orWhere('user_email', $reset_password_user_email)
						->first();
		}
		elseif($reset_password_username != '' && $reset_password_user_email == '')
		{
			$result = $db->table(USERS_TABLE)
						->where('username', $reset_password_username)
						->first();
		}
		elseif($reset_password_username == '' && $reset_password_user_email != '')
		{
			$result = $db->table(USERS_TABLE)
						->where('user_email', $reset_password_user_email)
						->first();
		}
		
		$num_user = intval($db->count());
	}
	
	$result = $hooks->apply_filters("users_reset_password_result", $result);	
	$num_user = $hooks->apply_filters("users_reset_password_number", $num_user);

	if($mode == '')
	{
		if (extension_loaded("gd") AND in_array("user_login" ,$nuke_configs['mtsn_gfx_chk']))
		{
			$sec_code_options = array(
				"input_attr" => array(
					"class" => "form-control text-left input input-lg",
					"placeholder" => _SECCODE,
					"required" => 'required',
					"data-msg-required" => _ENTER_SECCODEPLEASE
				),
				"img_attr" => array(
					"width" => 90,
					"height" => 32,
					"class" => "code"
				)
			);	
			
			$sec_code_options = $hooks->apply_filters("users_reset_password", $sec_code_options);
			
			$security_code_input = makepass("_RESET_PASSWORD_FORM", $sec_code_options);
		}
		
		$contents ="
			<form id=\"reset_password_form\">	
				<!-- Modal -->
				<div class=\"modal-header\">
					<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
					<h4 class=\"modal-title\">"._USER_REGENERATE_PASSWORD."</h4>
				</div>
				<div class=\"modal-body\" id=\"modal-body\">
					<div id=\"post-message\"></div>
					<div class=\"row\">
						<div class=\"col-sm-5\">
							<div class=\"form-group\">
								<div class=\"input-group\">	<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-envelope registration_form_icons\"></i></span> 
									<input class=\"form-control input input-lg text-left\" id=\"reset_password_username\" name=\"reset_password_username\" type=\"text\" placeholder=\""._ENTER_USERNAME."\" />
								</div>
							</div>
						</div>
						<div class=\"col-sm-2\"><div class=\"form-group text-center\">"._OR."</div></div>
						<div class=\"col-sm-5\">
							<div class=\"form-group\">
								<div class=\"input-group\">	<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-envelope registration_form_icons\"></i></span> 
									<input class=\"form-control input input-lg text-left\" id=\"reset_password_user_email\" name=\"reset_password_user_email\" type=\"text\" placeholder=\""._ENTER_USER_EMAIL."\" />
								</div>
							</div>
						</div>
					</div>";
					if (extension_loaded("gd") AND in_array("user_login" ,$nuke_configs['mtsn_gfx_chk']))
					{
						$contents .= "
						<div class=\"form-group\">
							<div class=\"input-group\"> <span class=\"input-group-addon\">".$security_code_input['image']."</span> 
								".$security_code_input['input']."
							</div>
						</div>
						";
					}
					$contents .="
				</div>
				<div class=\"modal-footer\">
					<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
					<button class=\"btn btn-primary\" id=\"reset-password-form-submit\" name=\"submit\" type=\"submit\">"._SEND."</button>
				</div>
			</form>
			<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."modules/$module_name/includes/users.js\"></script>";
			
			$contents = $hooks->apply_filters("users_reset_password_form", $contents);
			
			die($contents);
	}
	
	if($mode == 'form')
	{
		$code_accepted = true;
		if(extension_loaded("gd") && in_array("user_login", $nuke_configs['mtsn_gfx_chk']) && $resend == false)
			$code_accepted = code_check($security_code, $security_code_id);
		
		$contents = '';
		$errors = array();
		
		if(!$code_accepted)
			$errors[] = _BADSECURITYCODE;
		else
		{
			if($reset_password_username == '' && $reset_password_user_email == '')
				$errors[] = _ENTER_EMAIL_OR_USERNAME;
			elseif($reset_password_username == '' && !validate_mail($reset_password_user_email))
				$errors[] = _USER_BAD_EMAIL;
			elseif($resend == true && $pn_Sessions->get('step-1', false) != 1)
				$errors[] = _RESTART_RESENT_CODE;
			else
			{			
				if(!isset($result['user_id']) || intval($result['user_id']) == 0 || $num_user != 1)
					$errors[] = _USER_BAD_NAME;
				elseif($reset_password_user_email != ''  && isset($result['user_email']) && $result['user_email'] != $reset_password_user_email)
					$errors[] = _USER_EMAILS_NOT_EQUALED_TO_USER;
			}
		}
		
		if(!empty($errors))
			$contents = "<p>".implode("<br />\n", $errors)."</p>";
		else
		{
			$password_reset = ($result['password_reset'] != '') ? phpnuke_unserialize(stripslashes($result['password_reset'])):array();
			
			$new_code = rand(100000,999999);
			$password_reset[] = array('credit_code' => $new_code, 'expire' => (_NOWTIME+86400), 'ip' => $visitor_ip);
			$result['password_reset'] = addslashes(phpnuke_serialize($password_reset));
			$user_email = $result['user_email'];
			
			$message = array(
				"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "user_regen_pass.txt"),
				"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
				"username" => $result['username'], 
				"user_realname" => $result['user_realname'], 
				"new_code" => $new_code
			);
			
			phpnuke_mail($user_email, _USER_REGENERATE_PASSWORD, $message);
			if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && isset($ya_config['notify']['sms']) && $ya_config['notify']['sms'] == 1 && $result['user_phone'] != '')
			{
				$message = sprintf(_USER_REGEN_PASSWORD_SMS, $new_code);
				pn_sms('send', $result['user_phone'], $message);
			}			
			
			$result->save();
			
			$contents = "
			<div id=\"post-message\"></div>
			<div class=\"title text-center\">
				<div class=\"row\">
					<div class=\"col-md-4 col-xs-4\">
						<div class=\"logo\"></div>
					</div>
					<div class=\"col-md-8 col-xs-8\">
						<h2>"._USER_REGENERATE_PASSWORD."</h2>
					</div>
				</div>
			</div>
			<div class=\"form-group text-center\">
				"._USER_REGENERATE_PASS_MESSAGE4."<br /><br />
				<input type=\"text\" name=\"credit_code\" id=\"credit_code\" class=\"form-control input input-lg text-center\" placeholder=\"- - - - - -\" size=\"6\" maxlength=\"6\" style=\"width:200px;margin:0 auto;font-size:25px;\">
				<br /><a id=\"resend_reset_password_code\" href=\"#\">"._USER_REGENERATE_PASS_RESEND_CODE."</a>
			</div>";
			$pn_Sessions->set('step-1', 1);
			$pn_Cookies->set('reset_password_username', $reset_password_username);
		}
	}
	
	if($mode == 'reset' && $pn_Sessions->get('step-1', false) == 1 && $reset_password_username != '' && $credit_code != '' && $num_user == 1)
	{
		$contents = '';
		$errors  = array();
		$credit_code_accepted = false;
		
		$password_reset = (isset($result['password_reset']) && $result['password_reset'] != '') ? phpnuke_unserialize(stripslashes($result['password_reset'])):array();
		
		if(!empty($password_reset))
		{
			foreach($password_reset as $key => $password_reset_data)
			{
				if($credit_code == $password_reset_data['credit_code'])
				{
					if($password_reset_data['expire'] < _NOWTIME)
						$errors[] = _USER_REGENERATE_PASS_CODE_EXPIRED;
					$credit_code_accepted = true;
					break;
				}
			}
			
			if(!$credit_code_accepted)
				$errors[] = _USER_REGENERATE_PASS_CODE_INCORRECT;
		}
		else
			$errors[] = _USER_REGENERATE_PASS_CODE_NOTFOUND;
			
		if($credit_code_accepted)
		{
			$contents = "
				<div class=\"form-group\">
					<div class=\"input-group\"> <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-envelope login_form_icons\"></i></span> 
						<input type=\"password\" name=\"new_user_password[]\" class=\"form-control input input-lg\" placeholder=\""._USER_NEW_PASSWORD."\" value=\"\" autocomplete=\"off\">
					</div>
				</div>
				<div class=\"form-group\">
					<div class=\"input-group\"> <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-lock login_form_icons\"></i></span> 
						<input type=\"password\" name=\"new_user_password[]\" class=\"form-control input input-lg\" placeholder=\""._USER_RETYPE_NEW_PASSWORD."\" value=\"\" autocomplete=\"off\">
					</div>
				</div>";
				$pn_Sessions->set('step-2', 1);
		}
		
		if(!empty($errors))
		{
			$contents = "<p>".implode("<br />\n", $errors)."</p>";
		}
		else
		{
			$contents = "
				<div id=\"post-message\"></div>
				<div class=\"title text-center\">
					<div class=\"row\">
						<div class=\"col-md-4 col-xs-4\">
							<div class=\"logo\"></div>
						</div>
						<div class=\"col-md-8 col-xs-8\">
							<h2>"._USER_REGENERATE_PASSWORD."</h2>
						</div>
					</div>
				</div>
				<div class=\"form-group\">
					$contents
				</div>";
		}
	}

	if($mode == 'reset_password_confirm' && $pn_Sessions->get('step-2', false) == 1 && $reset_password_username != '' && $num_user == 1 && isset($new_user_password))
	{
		$contents = '';
		$errors = array();
		
		if($new_user_password[0]['value'] == '' || $new_user_password[1]['value'] == '')
			$errors[] = _USER_PASSWORDS_NOT_EXISTS;
		elseif($new_user_password[0]['value'] != $new_user_password[1]['value'])
			$errors[] = _USER_PASSWORDS_NOT_EQUALED;
		else
		{
			$new_user_password = substr($new_user_password[0]['value'], 0,70);
			$hashed_user_password = phpnuke_hash_password($new_user_password);
			
			$expiration = 2592000;
			
			$db->table(USERS_TABLE)
				->where(["user_id" => intval($result['user_id']), "username" => $reset_password_username], true)
				->update([
					"user_password" => $hashed_user_password,
					"password_reset" => ''
				]);
			
			$pn_Cookies->delete("user");
			$pn_Cookies->delete("reset_password_username");
			phpnuke_generate_user_cookie("user", intval($result['user_id']), $reset_password_username, $hashed_user_password, $expiration);
			add_log(sprintf(_SUCCESS_LOGIN_LOG, $reset_password_username), 0);
		}
		
		if(!empty($errors))
			$contents = "<p>".implode("<br />\n", $errors)."</p>";
		else
			$contents .= _USER_PASSWORD_CHANGED;
	}

	die(json_encode(array('message' => $contents, 'status' => (!empty($errors) ? "danger":"success"))));
}

function edit_user($user_configs = array(), $uploadfile = array(), $submit='')
{
	global $db, $user, $module_name, $users_system, $pn_Cookies, $pn_Sessions, $nuke_configs, $ya_config, $userinfo, $hooks;
	
	$contents = '';
	
	if(!is_user())
		login();
	else
	{
		$user_id = addslashes($user[0]);
		$username = addslashes($user[1]);
		$hmac = $user[3];
		if (empty($username) OR empty($hmac))
		{
			$admintest=0;
			$alert = "<html>\n";
			$alert .= "<title>INTRUDER ALERT!!!</title>\n";
			$alert .= "<body bgcolor=\"#FFFFFF\" text=\"#000000\">\n\n<br><br><br>\n\n";
			$alert .= "<div class=\"text-center\"><img src=\"images/eyes.gif\" border=\"0\"><br><br>\n";
			$alert .= "<font face=\"Verdana\" size=\"+4\"><b>Get Out!</b></font></div>\n";
			$alert .= "</body>\n";
			$alert .= "</html>\n";
			die($alert);
		}
		
		$result = $db->table(USERS_TABLE)
					->where('user_id', $user_id)
					->where('username', $username)
					->select();
		$user_data = $result->results()[0];
		
		if ($db->count() != 1)
		{
			$contents .= _USERNAME_NOT_EXISTS;
		}
		else
		{
			if(phpnuke_validate_user_cookie($user, "user", $user_data))
			{
				ya_custum_userfields($user_data, $user_data['user_id']);
				
				$can_upload = (file_exists($ya_config['avatar_path']) && (@ini_get('file_uploads') || strtolower(@ini_get('file_uploads')) == 'on')) ? true : false;
				
				if(isset($submit) && isset($user_configs) && !empty($user_configs))
				{
					$edit_content = '';
					$modify_errors = $fids = array();
					
					global $PnValidator;
					
					$PnValidator->add_filter("fixtext", 'ya_fixtext'); 

					$validation_rules = array();
					$filter_rules = array();
					$email_chenged = ($ya_config['allowmailchange'] == 1 && $user_configs['user_email'] != $user_data['user_email']) ? true:false;
					
					if ($email_chenged)
					{
						$validation_rules['user_email'] = 'required|valid_email';
						$filter_rules['user_email'] = 'sanitize_email';
					}
					
					if($user_configs['user_password'] != '')
					{
						$validation_rules['new_user_password_cn'] = 'equalsfield,new_user_password';
						
						$filter_rules['new_user_password'] = 'addslashes|fixtext';
						$filter_rules['new_user_password_cn'] = 'addslashes|fixtext';
					}
					
					$validation_rules['user_website'] = 'valid_url';
					$validation_rules['user_femail'] = 'valid_email';
					$validation_rules['user_newsletter'] = 'integer';
					$validation_rules['user_allow_viewonline'] = 'integer';
					$validation_rules['remove_avatar'] = 'integer';
					
					$filter_rules['user_realname'] = 'sanitize_string';
					$filter_rules['user_femail'] = 'sanitize_email';
					$filter_rules['user_birthday'] = 'addslashes';
					$filter_rules['user_about'] = 'addslashes';
					$filter_rules['user_address'] = 'addslashes';
					$filter_rules['user_phone'] = 'sanitize_numbers';
					$filter_rules['user_interests'] = 'addslashes';
					$filter_rules['user_sig'] = 'addslashes|sanitize_string';
					$filter_rules['user_gender'] = 'sanitize_string';
					$filter_rules['user_avatar_type'] = 'sanitize_string';
					$filter_rules['user_newsletter'] = 'sanitize_numbers';
					$filter_rules['user_allow_viewonline'] = 'sanitize_numbers';
					$filter_rules['remove_avatar'] = 'sanitize_numbers';

					if(isset($user_configs['custom_fields']) && !empty($user_configs['custom_fields']))
					{
						$cs_result = $db->query("SELECT * FROM ".USERS_FIELDS_TABLE." WHERE act = '1' ORDER BY pos ASC");
						
						$cs_results = $cs_result->results();
						if(!empty($cs_results))
						{
							foreach($cs_results as $field => $value)
							{
								$fids[$value['name']] = $value['fid'];
								if(isset($user_configs['custom_fields'][$value['name']]))
								{
									$user_configs[$value['name']] = $user_configs['custom_fields'][$value['name']];
									if($value['need'] == 1)
										$validation_rules[$value['name']] = 'required'.(($value['type'] == 'number') ? '|numeric':'');
										
									if($value['type'] == 'number')
										$filter_rules[$value['name']] = 'sanitize_numbers';
									elseif($value['type'] == 'text')
										$filter_rules[$value['name']] = 'sanitize_string';
									elseif($value['type'] == 'textarea')
										$filter_rules[$value['name']] = 'addslashes';
								}
							}
						}
					}
					
					$validation_rules = $hooks->apply_filters("users_edit_validation_rules", $validation_rules);
					$filter_rules = $hooks->apply_filters("users_edit_filter_rules", $filter_rules);
			
					// Get or set the filtering rules
					$PnValidator->validation_rules($validation_rules); 
					$PnValidator->filter_rules($filter_rules);
					
					$user_configs = $PnValidator->sanitize($user_configs, array('username'), true, true);
					$validated_data = $PnValidator->run($user_configs);

					if($validated_data !== FALSE)
						$user_configs = $validated_data;
					else
						$modify_errors[] = "<p align=\"center\">".$PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br />')."</p>";

					$modify_errors = $hooks->apply_filters("users_edit_check_fields", $modify, $user_configs);
					
					if(empty($modify_errors))
					{
						unset($user_configs['username']);
						extract($user_configs);

						$pn_Sessions->remove("userinfo");
						
						$bad_mail = ($ya_config['bad_mail'] != '' && !is_array($ya_config['bad_mail'])) ? explode("\n", str_replace("\r","",$ya_config['bad_mail'])):array();
						$bad_username = ($ya_config['bad_username'] != '' && !is_array($ya_config['bad_username'])) ? explode("\n", str_replace("\r","",$ya_config['bad_username'])):array();
						$bad_nick = ($ya_config['bad_nick'] != '' && !is_array($ya_config['bad_nick'])) ? explode("\n", str_replace("\r","",$ya_config['bad_nick'])):array();
						define("NOAJAX_REQUEST", true);
						
						// email check
						if($email_chenged && in_array($user_email, $bad_mail))
							$modify_errors[] = _USER_BAD_EMAIL;
						
						if($email_chenged && !check_register_fields('user_email', $user_email, $user_data['user_email']))
							$modify_errors[] = _USER_EMAIL_HAS_SELECTED;
							
						// realname check
						if(in_array($user_realname, $bad_nick))
							$modify_errors[] = _USER_BAD_REALNAME;
						
						if(empty($modify_errors))
						{
							$hashed_user_password = '';
							if($user_password != '')
							{
								$user_configs['user_password'] = phpnuke_hash_password($user_password);
								
								$hashed_user_password = ($new_user_password != '' && $new_user_password_cn != '' && $new_user_password == $new_user_password_cn && phpnuke_check_password($user_password, $user_data['user_password'])) ? phpnuke_hash_password($new_user_password):"";
								
								if($hashed_user_password == '')
									$modify_errors[] = _USER_INCORRECT_CURRENT_PASSWORD;
							}
													
							$update_fields['user_realname'] = $user_realname;
							$update_fields['user_femail'] = $user_femail;
							$update_fields['user_website'] = $user_website;
							if($user_birthday != '')
							{
								$update_fields['user_birthday'] = to_mktime($user_birthday);
							}
							$update_fields['user_about'] = $user_about;
							$update_fields['user_address'] = $user_address;
							$update_fields['user_phone'] = $user_phone;
							$update_fields['user_interests'] = $user_interests;
							$update_fields['user_sig'] = $user_sig;
							$update_fields['user_newsletter'] = $user_newsletter;
							$update_fields['user_allow_viewonline'] = $user_allow_viewonline;
							$update_fields['user_lang'] = $user_lang;
							$update_fields['user_gender'] = $user_gender;
							$update_fields['user_avatar_type'] = $user_avatar_type;
							
							if($remove_avatar == 1)
							{
								avatar_delete('user', $user_data);
								$update_fields['user_avatar'] = '';
								$update_fields['user_avatar_type'] = '';
							}
							elseif(isset($avatar) || (isset($uploadfile) && $uploadfile['error'] == 0 && $uploadfile['name'] != ''))
							{
								$avatar_must_changeed = false;
								switch($user_avatar_type)
								{
									case"upload":
										if(($uploadfile['error'] == 0 && $uploadfile['name'] != '') || $avatar['uploadurl'] != '')
											$avatar_must_changeed = true;
									break;
									
									case"remote":
										if($avatar['remotelink'] != '' && $user_data['user_avatar'] != $avatar['remotelink'])
											$avatar_must_changeed = true;
									break;
									
									case"gravatar":
										if($avatar['gravatar_email'] != '' && $user_data['user_avatar'] != $avatar['gravatar_email'])
											$avatar_must_changeed = true;
									break;
								}
								
								if($avatar_must_changeed)
									$update_fields['user_avatar'] = avatar_process_user($user_avatar_type, $avatar, false);
							}
							
							if($email_chenged)
								$update_fields['user_email'] = $user_email;
							
							if($hashed_user_password != '')
								$update_fields['user_password'] = $hashed_user_password;
								
							$update_fields = $hooks->apply_filters("users_edit_update_query", $update_fields);
					
							$result = $db->table(USERS_TABLE)
											->where("user_id", $user_data['user_id'])
											->update($update_fields);

							if(!$result)
								$modify_errors[] = $db->getErrors('last')['message'];
							else
							{
								if(isset($user_configs['custom_fields']) && !empty($user_configs['custom_fields']))
								{
									foreach($user_configs['custom_fields'] as $custom_fields_key => $custom_fields_val)
									{
										if(isset($user_configs[$custom_fields_key]))
										{
											$cs_result2 = $db->table(USERS_FIELDS_VALUES_TABLE)
															->where('uid', $user_data['user_id'])
															->where('fid', $fids[$custom_fields_key])
															->first();

											if($cs_result2->count() > 0)
											{
												$cs_result2['value'] = $user_configs[$custom_fields_key];
												$cs_result2->save();								
											}
											else
											{
												$db->table(USERS_FIELDS_VALUES_TABLE)
													->insert([
														'uid' => $user_data['user_id'],
														'fid' => $fids[$custom_fields_key],
														'value' => $user_configs[$custom_fields_key],
													]);									
											}
										}
									}
								}
								$expiration = 2592000;
								
								if($user_data['user_status'] == USER_STATUS_ACTIVE && $hashed_user_password != '')
									$user = phpnuke_generate_user_cookie("user", $user_data['user_id'], $user_data['username'], $hashed_user_password, $expiration);
								$users_system->getuserinfo(true);
								
								$edit_content = "<div class=\"text-center\">"._USER_PROFILE_UPDATED."<br /><a href=\"".LinkToGT("index.php?modname=$module_name&op=userinfo&username=".$user_data['username'])."\">"._USER_PROFILE_PAGE."</a> | <a href=\"".LinkToGT("index.php?modname=$module_name&op=edit_user")."\">"._USER_GO_TO_PROFILE_SETTINGS."</a></div>";
								
								$hooks->do_action("users_edit_after");
							}
						}
					}
					
					if(!empty($modify_errors))
					{
						$edit_content = "<div class=\"text-center\"><span style=\"color:red;padding:5px;\">".implode("<br />", $modify_errors)."</span><br /><a href=\"".LinkToGT("index.php?modname=$module_name&op=userinfo&username=".$user_data['username'])."\">"._USER_PROFILE_PAGE."</a> | <a href=\"".LinkToGT("index.php?modname=$module_name&op=edit_user")."\">"._USER_GO_TO_PROFILE_SETTINGS."</a></div>";
					}
					
					$contents = OpenTable();
					$contents .= $edit_content;
					$contents .= CloseTable();
					include("header.php");
					$html_output .= show_modules_boxes($module_name, "edit", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
					include ("footer.php");
					die();
				}
				
				$user_avatar = $users_system->get_avatar_url($user_data, 180, 180);

				$user_data['user_sig'] = stripslashes($user_data['user_sig']);
				
				$nl_checked1 = ($user_data['user_newsletter'] == 1) ? "checked":"";
				$nl_checked2 = ($user_data['user_newsletter'] == 0) ? "checked":"";
				
				$av_checked1 = ($user_data['user_allow_viewonline'] == 1) ? "checked":"";
				$av_checked2 = ($user_data['user_allow_viewonline'] == 0) ? "checked":"";
				
				$gsel1 = ($user_data['user_gender'] == 'mr') ? "checked":"";
				$gsel2 = ($user_data['user_gender'] == 'mrs') ? "checked":"";
				
				$user_data['user_website'] = (isset($user_data['user_website']) && !empty($user_data['user_website'])) ? correct_url($user_data['user_website']):"";
				
				$avatar_upload_style = ($user_data['user_avatar_type'] == 'upload' || $user_data['user_avatar_type'] == '') ? "":" style=\"display:none;\"";
				$avatar_remote_style = ($user_data['user_avatar_type'] == 'remote') ? "":" style=\"display:none;\"";
				$avatar_gravatar_style = ($user_data['user_avatar_type'] == 'gravatar') ? "":" style=\"display:none;\"";
				
				$ava_sel1 = ($user_data['user_avatar_type'] == 'upload' || $user_data['user_avatar_type'] == '') ? "selected":"";
				$ava_sel2 = ($user_data['user_avatar_type'] == 'remote') ? "selected":"";
				$ava_sel3 = ($user_data['user_avatar_type'] == 'gravatar') ? "selected":"";
				
				$languageslists = get_dir_list('language');
				$ya_config['data_verification']['user_password']['data-validation'] = "";
				$ya_config['data_verification']['user_password_cn']['data-validation'] = "";
	
				$contents .= "
						<p class=\"text-center\" id=\"avatar_preview\">
							<img src=\"" . (str_replace(' ', '%20', $user_avatar)) . "\" width=\"100%\" height=\"100%\" class=\"img-thumbnail\" alt=\""._USER_AVATAR." ".$user_data['username']."\" title=\""._USER_AVATAR." ".$user_data['username']."\" style=\"max-width:180px;margin-bottom:10px;\" /><br />
							<button type=\"button\" class=\"btn btn-info\" data-toggle=\"modal\" data-target=\"#avatar_modal\" title=\""._USER_EDIT_AVATAR."\"><i class=\"glyphicon glyphicon-pencil\"></i></button>
							<button type=\"button\" class=\"btn btn-danger\" title=\""._USER_DELETE_AVATAR."\" id=\"remove_avatar\"><i class=\"glyphicon glyphicon-remove\"></i></button>
						</p>
						<hr>
						<form id=\"user_configs_form\" class=\"form-horizontal\" role=\"form\" action=\"".LinkToGT("index.php?modname=$module_name")."\" method=\"post\" enctype=\"multipart/form-data\">
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"username\">"._USERNAME." :</label>
									<div class=\"col-sm-9\"> 
										<input type=\"text\" class=\"form-control\" value=\"".$user_data['username']."\" disabled />
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_realname\">"._USER_FAMILYNAME." :</label>
									<div class=\"col-sm-9\"> 
										<input type=\"text\" name=\"user_configs[user_realname]\" class=\"form-control\" id=\"user_realname\" value=\"".$user_data['user_realname']."\" ".str_replace(array('{MODE}','{DEFAULT}'), array('edit', $user_data['user_realname']), data_verification_parse($ya_config['data_verification']['user_realname'])).">
									</div>
								</div>";
								if ($ya_config['allowmailchange'] == 1)
								{
								$contents .= "<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_email\">"._EMAIL." :</label>
									<div class=\"col-sm-9\"> 
										<input type=\"email\" name=\"user_configs[user_email]\" class=\"form-control\" id=\"user_email\" value=\"".$user_data['user_email']."\"  ".str_replace(array('{MODE}','{DEFAULT}'), array('edit', $user_data['user_email']), data_verification_parse($ya_config['data_verification']['user_email']))." />
									</div>
								</div>";
								}
								$contents .= "<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_femail\">"._DISPLAY_EMAIL." :</label>
									<div class=\"col-sm-9\"> 
										<input type=\"email\" name=\"user_configs[user_femail]\" class=\"form-control\" id=\"user_femail\" value=\"".$user_data['user_femail']."\">
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_website\">"._USER_WEBSITE." :</label>
									<div class=\"col-sm-9\"> 
										<input type=\"url\" name=\"user_configs[user_website]\" class=\"form-control\" id=\"user_website\" value=\"".$user_data['user_website']."\">
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_birthday\">"._USER_BIRTHDAY." :</label>
									<div class=\"col-sm-9\"> 
										<input type=\"text\" name=\"user_configs[user_birthday]\" class=\"form-control calendar\" id=\"user_birthday\" value=\"".nuketimes($user_data['user_birthday'], false, false, false, 1)."\">
									</div>
								</div>";
								
								$cs_result = $db->query("SELECT * FROM ".USERS_FIELDS_TABLE." WHERE act = '1' ORDER BY pos ASC");
								$cs_results = $cs_result->results();
								if(!empty($cs_results))
								{
									require_once( INCLUDE_PATH.'/class.form-builder.php' );
									$form = new PhpFormBuilder();
									$form->set_att('form_element', false);
									$form->set_att('add_submit', false);
								
									foreach($cs_results as $fields)
									{
										$options = $attrs = array();
										$attrs['data-validation'] = '';
										
										$cs_value = ($fields['value'] != '') ? explode(",", $fields['value']):"";
										if($fields['need'] == 1){
											$attrs['data-validation'] =  "required";
											$attrs['data-validation-error-msg-required'] = sprintf(_CUSTOM_FIELDS_ERROR, $fields['display']);
										}
										if($fields['size'] != 0){
											$attrs['data-validation'] .=  " length";
											$attrs['data-validation-length'] =  "max".intval($fields['size']);
											$attrs['data-validation-error-msg-length'] = sprintf(_USER_FILED_SIZE_MAX_ERROR, intval($fields['size']));
										}					
										
										$attrs['class'] = 'form-control';
										if($fields['type'] == 'text' && intval($fields['size']) != 0)
											$attrs['size'] = $fields['size'];
										if($fields['type'] == 'select')
											$attrs['class'] = 'selectpicker';
										if($fields['type'] == 'textarea')
											$attrs['rows'] = '5';
											
										if(in_array($fields['type'], array("radio","checkbox", "select")) && is_array($cs_value) && !empty($cs_value))
										{
											foreach($cs_value as $cs_val)
											{
												$options[] = array("", $cs_val, $cs_val, "attrs" => array()); 
											}
										}
										
										$fdata = array(
											"".$fields['name']."" => array(
												"label" => $fields['display'],
												"opts" => array(
													'wrap_tag'  => '',
													'before_html'  => "<div class=\"form-group\">
														<label class=\"control-label col-sm-3\" for=\"cs_".$fields['name']."\">".$fields['display']." :</label>
														<div class=\"col-sm-9\">",
													'after_html'  => '</div>
													</div>',
													'id'  => "cs_".$fields['name'],
													'name'  => "user_configs[custom_fields][".$fields['name']."]",
													'type'  => $fields['type'],
													'add_label'  => false,
													'label'  => $fields['name'],
													'value'  => (isset($user_data[$fields['name']])) ? $user_data[$fields['name']]:"",
													'required'  => ($fields['need'] == 1) ? true:false,
													'max'  => ($fields['size'] != 0) ? intval($fields['size']):'',
													'options'   => $options,
													'attrs'  => $attrs,
												)
											)
										);
										$form->add_inputs($fdata);
										$contents .= $form->build_form($user_data, false);
										$form->clear_form();
										
										$user_data[$fields['name']] = $fields['value'];
										$user_data['custom_fields'][$fields['name']] = array($fields['display'], $fields['value']);
									}
								}
								
								$contents .="
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_phone\">"._MOBILE_PHONE." :</label>
									<div class=\"col-sm-9\">
										<input type=\"text\" name=\"user_configs[user_phone]\" class=\"form-control text-left\" id=\"user_phone\" value=\"".$user_data['user_phone']."\" />
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_about\">"._USER_ABOUT_ME." :</label>
									<div class=\"col-sm-9\">
										<textarea class=\"form-control\" rows=\"5\" id=\"user_about\" name=\"user_configs[user_about]\">".$user_data['user_about']."</textarea>
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_address\">"._USER_ADDRESS." :</label>
									<div class=\"col-sm-9\">
										<textarea class=\"form-control\" rows=\"5\" id=\"user_address\" name=\"user_configs[user_address]\">".$user_data['user_address']."</textarea>
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_interests\">"._USER_INTERESTS." :</label>
									<div class=\"col-sm-9\">
										<textarea class=\"form-control\" rows=\"5\" id=\"user_interests\" name=\"user_configs[user_interests]\">".$user_data['user_interests']."</textarea>
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_sig\">"._USER_SIGN." :</label>
									<div class=\"col-sm-9\">
										<textarea class=\"form-control\" rows=\"5\" id=\"user_sig\" name=\"user_configs[user_sig]\">".$user_data['user_sig']."</textarea>
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"text\">"._USER_NEWSLETTER." :</label>
									<div class=\"col-sm-9\">
										"._YES." : <input type=\"radio\" name=\"user_configs[user_newsletter]\" value=\"1\" $nl_checked1/> &nbsp; 
										"._NO." : <input type=\"radio\" name=\"user_configs[user_newsletter]\" value=\"0\" $nl_checked2/>
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"text\">"._USER_VIEW_ONLINE." :</label>
									<div class=\"col-sm-9\">
										"._YES." : <input type=\"radio\" name=\"user_configs[user_allow_viewonline]\" value=\"1\" $av_checked1/> &nbsp; 
										"._NO." : <input type=\"radio\" name=\"user_configs[user_allow_viewonline]\" value=\"0\" $av_checked2/>
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_lang\">"._LANGUAGE." :</label>
									<div class=\"col-sm-9\">
										<select name=\"user_configs[user_lang]\" id=\"user_lang\" class=\"selectpicker\" style=\"width:150px;\">
											<option value=\"\">"._ALL."</option>";
												foreach($languageslists as $languageslist)
												{
													if($languageslist == '' || $languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
													$languageslist = str_replace(".php", "", $languageslist);
													$sel = ($languageslist == $user_data['user_lang']) ? "selected":"";
													$contents .= "<option value=\"$languageslist\" $sel>".ucfirst($languageslist)."</option>";
												}
												$contents .= "
										</select>
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_gender\">"._USER_GENDER." :</label>
									<div class=\"col-sm-9\">
										<select name=\"user_configs[user_gender]\" id=\"user_gender\" class=\"selectpicker\" style=\"width:150px;\">
											<option value=\"mr\" $gsel1>"._MR."</option>
											<option value=\"mrs\" $gsel2>"._MRS."</option>
										</select>
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"user_password\">"._USER_CURRENT_PASSWORD." :</label>
									<div class=\"col-sm-9\">
										<input type=\"password\" name=\"user_configs[user_password]\" class=\"form-control\" id=\"user_password\" />
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"new_user_password\">"._USER_NEW_PASSWORD." :</label>
									<div class=\"col-sm-9\">
										<input type=\"password\" name=\"user_configs[new_user_password]\" class=\"form-control\" id=\"new_user_password\" ".data_verification_parse($ya_config['data_verification']['user_password'])." />
									</div>
								</div>
								<div class=\"form-group\">
									<label class=\"control-label col-sm-3\" for=\"new_user_password_cn\">"._USER_RETYPE_NEW_PASSWORD." :</label>
									<div class=\"col-sm-9\">
										<input type=\"password\" name=\"user_configs[new_user_password_cn]\" class=\"form-control\" id=\"new_user_password_cn\" ".data_verification_parse($ya_config['data_verification']['user_password_cn'])." />
									</div>
								</div>
								<div class=\"form-group\"> 
									<div class=\"col-sm-9\">
										<input type=\"submit\" class=\"btn btn-default\" id=\"submit-form\" value=\""._SEND."\" />
									</div>
								</div>
								<div class=\"modal fade\" id=\"avatar_modal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"avatar_edit\" aria-hidden=\"true\">
									<div class=\"modal-dialog modal-lg\">
										<div class=\"modal-content\">
											<div class=\"modal-header\">
												<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
												<h4 class=\"modal-title\">"._USER_EDIT_AVATAR." ".$user_data['username']."</h4>
											</div>
											<div class=\"modal-body\">
												<div class=\"form-group\">
													<label class=\"control-label col-sm-3\" for=\"user_avatar_type\">"._USER_AVATAR_TYPE." :</label>
													<div class=\"col-sm-9\">
														<select name=\"user_configs[user_avatar_type]\" id=\"user_avatar_type\" class=\"selectpicker\" style=\"width:180px;\">
															<option value=\"upload\" $ava_sel1>"._USER_AVATAR_UPLOAD."</option>
															<option value=\"remote\" $ava_sel2>"._USER_AVATAR_DIRECT."</option>
															<option value=\"gravatar\" $ava_sel3>"._USER_AVATAR_GRAVATAR."</option>
														</select>
													</div>
												</div>
												<div class=\"upload_avater\"$avatar_upload_style>
													<div class=\"form-group btn-input-file\">
														<label class=\"control-label col-sm-3\" for=\"uploadfile\">"._USER_SELECT_FILE." :</label>
														<div class=\"col-sm-9\">
															<input type=\"file\" name=\"uploadfile\" style=\"display:none;\" class=\"uploadfile\" id=\"uploadfile\" accept=\"image/*\" />
															<div class=\"input-group input-file\">           
																<span class=\"input-group-btn\">
																<button class=\"btn btn-default btn-choose\" type=\"button\">"._SELECT."</button>
																</span>
																<input type=\"text\" class=\"form-control input-sub-file\" readonly />
																<span class=\"input-group-btn\">
																<button class=\"btn btn-default btn-reset\" type=\"button\">"._USER_RESET."</button>
																</span>
															</div>
															<br />".sprintf(_USER_AVATAR_DIMENTIONS, $ya_config['avatar_max_width'], $ya_config['avatar_max_height'], formatBytes($ya_config['avatar_filesize'], 0, true))."
														</div>
													</div>
													<div class=\"form-group\">
														<label class=\"control-label col-sm-3\" for=\"avatar_uploadurl\">"._USER_DIRECT_AVATAR_UPLOAD." :</label>
														<div class=\"col-sm-9\">
															<input type=\"url\" name=\"user_configs[avatar][uploadurl]\" class=\"form-control text-left\" id=\"avatar_uploadurl\" />
															<br />"._USER_DIRECT_AVATAR_UPLOAD_MSG."
														</div>
													</div>
												</div>
												<div class=\"remote_avater\"$avatar_remote_style>
													<div class=\"form-group\">
														<label class=\"control-label col-sm-3\" for=\"avatar_remote\">"._USER_DIRECT_AVATAR_LINK." :</label>
														<div class=\"col-sm-9\">
															<input type=\"url\" name=\"user_configs[avatar][remotelink]\" class=\"form-control text-left\" id=\"avatar_remote\" value=\"".(($user_data['user_avatar_type'] == 'remote') ? $user_data['user_avatar']:"")."\" />
														</div>
													</div>
												</div>
												<div class=\"gravatar_avater\"$avatar_gravatar_style>
													<div class=\"form-group\">
														<label class=\"control-label col-sm-3\" for=\"gravatar_avater\">"._USER_EMAIL_IN_GRAVATAR." :</label>
														<div class=\"col-sm-9\">
															<input type=\"email\" name=\"user_configs[avatar][gravatar_email]\" class=\"form-control text-left\" id=\"gravatar_avater\" value=\"".(($user_data['user_avatar_type'] == 'gravatar') ? $user_data['user_avatar']:"")."\" />
														</div>
													</div>
												</div>
											</div>
											<div class=\"modal-footer\">
												<button type=\"button\" class=\"btn btn-success\" data-dismiss=\"modal\">"._CLOSE_AND_CONTINUE."</button>
											</div>
										
										</div>
										<!-- /.modal-content -->
									</div>
									<!-- /.modal-dialog -->
								</div>
								<input type=\"hidden\" name=\"op\" value=\"edit_user\" />
								<input type=\"hidden\" name=\"user_configs[remove_avatar]\" id=\"remove_avatar_input\" value=\"0\" />
								<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
							</form>";
			}
			else
				$contents .= _USERNAME_NOT_EXISTS;
		}
	}

	$hooks->add_functions_vars(
		'edit_users_assets',
		array(
			"module_name" => $module_name,
			"ya_config" => $ya_config,
		)
	);
	$hooks->add_filter("site_theme_headers", "edit_users_assets", 10);
	
	$contents = OpenTable(_USER_PROFILE_EDIT.' '.$user_data['username']).$contents.CloseTable();
	
	$contents = $hooks->apply_filters("edit_user_form", $contents, $user_data);
	
	$meta_tags = array(
		"url" => LinkToGT("index.php?modname=$module_name&op=edit_user"),
		"title" => _USER_CONFIGS,
		"description" => "",
		"keywords" => "",
		"prev" => '',
		"next" => '',
		"extra_meta_tags" => array()
	);
	$meta_tags = $hooks->apply_filters("edit_user_header_meta", $meta_tags, $module_name);
		
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);		
	unset($meta_tags);
	$hooks->add_functions_vars(
		'user_edit_breadcrumb',
		array(
			"user_data" => $user_data,
		)
	);
	$hooks->add_filter("site_breadcrumb", "user_edit_breadcrumb", 10);
	
	include("header.php");
	$html_output .= show_modules_boxes($module_name, "edit", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
	include ("footer.php");
}

function check_register_fields($field = 'username', $value = '', $default_value = '', $title = '', $primary = true)
{
	return _check_register_fields($field, $value, $default_value, $title, $primary);
}

function get_user_avatar($avatar)
{
	global $ya_config;
	
	return _get_user_avatar($avatar);
}

function delete_cookies()
{
	global $pn_Cookies, $pn_Sessions, $module_name;
	$pn_Cookies->delete("user");
	$pn_Sessions->remove("userinfo");
	redirect_to(LinkToGT("index.php?modname=$module_name"));
	die();
}

function send_invitation_code($invited_email)
{
	global $db, $nuke_configs, $userinfo, $ya_config, $module_name, $hooks;

	$response = array(
	  'valid' => false,
	  'message' => 'Post argument is missing.'
	);
	
	$email_check =  adv_filter($invited_email, array('sanitize_email'), array('required','valid_email'));
	if($email_check[0] != 'error')
		$invited_email = $email_check[1];
	else
	{
		$response['message'] = _ENTER_VALID_EMAIL;
		die(json_encode($response));
	}
	
	if(check_register_fields('user_email', $invited_email, ''))
	{
		$response['message'] = _USER_EMAIL_HAS_SELECTED;
		die(json_encode($response));
	}
	
	$today_mktime_start = mktime(0,0,0,date("m"),date("d"),date("y"));
	$today_mktime_end = mktime(23,59,59,date("m"),date("d"),date("y"));
	
	$result = $db->table(USERS_INVITES_TABLE)
				->where('rid', $userinfo['user_id'])
				->where('time', '>=', $today_mktime_start)
				->where('time', '<=', $today_mktime_end)
				->select();
	if(intval($result->count()) > $ya_config['max_invitation'])
	{
		$response['message'] = sprintf(_MAX_INVITATIONS_DONE, $ya_config['max_invitation']);
		die(json_encode($response));
	}
	
	$invitation_code = random_str(10, '1234567890');
	
	$db->table(USERS_INVITES_TABLE)
		->insert([
			'rid' => $userinfo['user_id'],
			'code' => $invitation_code,
			'email' => $invited_email,
			'time' => _NOWTIME,
		]);
	
	$message = array(
		"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "user_invitation.txt"),
		"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
		"username" => $userinfo['username'], 
		"invitation_code" => $invitation_code, 
		"invited_email" => $invited_email
	);
	
	phpnuke_mail($invited_email, sprintf(_INVITATION_MESSAGE, $userinfo['username'], $nuke_configs['sitename']), $message);
	
	$response['valid'] = true;
	$response['message'] = _SUCCESS_INVITATION;
	
	die(json_encode($response));
}

$submit							= (isset($submit)) ? filter($submit, "nohtml"):"";
$username						= (isset($username)) ? filter($username, "nohtml"):"";
$mode							= (isset($mode)) ? filter($mode, "nohtml"):"";
$resend							= (isset($resend)) ? (bool) $resend:false;
$credit_code					= filter(request_var('credit_code', '', '_POST'), "nohtml");
$reset_password_username		= filter(request_var('reset_password_username', '', '_POST'), "nohtml");
$reset_password_user_email		= filter(request_var('reset_password_user_email', '', '_POST'), "nohtml");
$new_user_password				= request_var('new_user_password', array(), '_POST');
$user_password					= filter(request_var('user_password', '', '_POST'), "nohtml");
$security_code					= filter(request_var('security_code', '', '_POST'), "nohtml");
$security_code_id				= filter(request_var('security_code_id', '', '_POST'), "nohtml");
$invitation_code				= filter(request_var('invitation_code', '', '_POST'), "nohtml");
$invited_email					= filter(request_var('invited_email', '', '_POST'), "nohtml");
$remember_me					= filter(request_var('remember_me', '', '_POST'), "nohtml");
$field							= filter(request_var('field', '', '_POST'), "nohtml");
$avatar							= (isset($avatar)) ? filter($avatar, "nohtml"):"";
$users_fields					= request_var('users_fields', array(), '_POST');
$user_configs					= request_var('user_configs', array(), '_POST');
$uploadfile						= request_var('uploadfile', array(), '_FILES');
$value							= filter(request_var('value', '', '_POST'), "nohtml");
$default_value					= filter(request_var('default_value', '', '_POST'), "nohtml");
$title							= filter(request_var('title', '', '_POST'), "nohtml");
$primary						= filter_var(request_var('primary', true, '_POST'), FILTER_VALIDATE_BOOLEAN);
$op								= (isset($op)) ? filter($op, "nohtml"):"";

switch($op)
{	
	case"logout":
		logout();
	break;
	
	case"register":
		register($submit, $users_fields, $invitation_code, $security_code, $security_code_id);
	break;
	
	case"reset_password":
		reset_password($mode, $credit_code, $reset_password_username, $reset_password_user_email, $security_code, $security_code_id, $new_user_password, $resend);
	break;
	
	case"userinfo":
		userinfo($username);
	break;
	
	case"edit_user":
		edit_user($user_configs, $uploadfile, $submit);
	break;
	
	case"check_register_fields":
		check_register_fields($field, $value, $default_value, $title, $primary);
	break;
	
	case"get_user_avatar":
		get_user_avatar($avatar);
	break;
	
	case"delete_cookies":
		delete_cookies();
	break;
	
	case"send_invitation_code":
		send_invitation_code($invited_email);
	break;
	
	default:
		if(is_user())
			userinfo();
		else
			login($submit, $username, $user_password, $remember_me, $security_code, $security_code_id);
	break;
}

?>