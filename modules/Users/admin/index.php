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

if (!defined('ADMIN_FILE')) {
	die ("Access Denied");
}

if (check_admin_permission($module_name, false, true))
{
	/*********************************************************/
	/* Users Functions                                  */
	/*********************************************************/
	define("MODULE_FILE", true);
	include_once("modules/$module_name/includes/functions.php");
	function users_menu()
	{
		global $db, $admin_file, $nuke_configs;
		$contents = "
		<p align=\"center\" style=\"padding:20px; 0;\">[ 
			<a href=\"".$admin_file.".php?op=users\">"._USERS_ADMIN."</a> | 
			<a href=\"".$admin_file.".php?op=users_admin\">"._ADD_USER."</a> | 
			<a href=\"".$admin_file.".php?op=users_groups\">"._GROUPS_ADMIN."</a> | 
			<a href=\"".$admin_file.".php?op=users_fields_admin\">"._USERS_FIELDS_SETTING."</a> | 
			<a href=\"".$admin_file.".php?op=settings#users_config\">"._USER_CONFIGS."</a>
		]</p>";
		return $contents;
	}
	
	function users($user_status='', $search_query = '', $order_by = '', $sort='DESC')
	{
		global $db, $hooks, $admin_file, $nuke_configs;
			
		$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
		
		$contents = '';
		
		switch($user_status)
		{
			case USER_STATUS_ACTIVE:
				$title_status = " "._NORMAL_USERS."";
			break;
			case USER_STATUS_DELETED:
				$title_status = " "._DELETED_USERS."";
			break;
			case USER_STATUS_INACTIVE:
				$title_status = " "._SUSPENDED_USERS."";
			break;
			case USER_STATUS_REQUIRE_ADMIN:
				$title_status = " "._PENDING_USERS."";
			break;
			case USER_STATUS_EMAIL_ACTIVATE:
				$title_status = " "._NOT_ACTIVATED_USERS."";
			break;
			default:
				$title_status = "";
			break;
		}
		
		$pagetitle = _USERS_ADMIN." - ".$title_status;
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("users" => $pagetitle);});
		
		$link_to_more = "";
		$where = array();
		$params = array();
		
		$where[] = "u.user_id != '1'";
		
		if($user_status != '')
		{
			$link_to_more .= "&user_status=$user_status";
			$params[":user_status"] = $user_status;
			$where[] = "u.user_status = :user_status";
		}
		else
		{
			$where[] = "u.user_status != :user_status";
			$params[":user_status"] = -1;
		}
		
		
		if(isset($search_query) && $search_query != '')
		{
			$params[":search_query"] = "%".rawurldecode($search_query)."%";
			$where[] = "u.username LIKE :search_query";
			$link_to_more .= "&search_query=".rawurlencode($search_query)."";
		}
		
		$where = array_filter($where);
		$where = (!empty($where)) ? "WHERE ".implode(" AND ", $where):'';

		$sort = ($sort != '' && in_array($sort, array("ASC","DESC"))) ? $sort:"DESC";
		$order_by = ($order_by != '') ? $order_by:'user_id';
		$sort_reverse = ($sort == 'ASC') ? "DESC":"ASC";	
		
		$entries_per_page = 20;
		$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
		$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
		$link_to = "".$admin_file.".php?op=users".$link_to_more;

		$total_rows = 0;
		$result = $db->query("
			SELECT u.*, 
			(SELECT COUNT(user_id) FROM ".USERS_TABLE." ".str_replace("s.","", $where).") as total_rows, 
			(SELECT g.group_lang_titles FROM ".GROUPS_TABLE." AS g WHERE g.group_id = u.group_id) as group_lang_titles
			FROM ".USERS_TABLE." AS u 
			$where 
			ORDER BY u.$order_by $sort LIMIT $start_at, $entries_per_page
		", $params);
		$sel0 = ($user_status == '') ? "selected":"";
		$sel1 = ($user_status == 1) ? "selected":"";
		$sel2 = ($user_status == -1) ? "selected":"";
		$sel3 = ($user_status == 0 && $user_status != '') ? "selected":"";
		$sel4 = ($user_status == 2) ? "selected":"";
		$sel5 = ($user_status == 3) ? "selected":"";
		
		$contents .= GraphicAdmin();
		$contents .= users_menu();
		
		$contents .= OpenAdminTable();
		$contents .= "
			<table align=\"center\" class=\"product-table no-border no-hover\" width=\"100%\">
				<tr>
					<form action=\"".$admin_file.".php\" method=\"post\">
						<td>
							"._SEARCH_BY_USERNAME." 
							<input class=\"inp-form\" type=\"text\" name=\"search_query\" size=\"30\" value=\"$search_query\" />
							<input type=\"hidden\" value=\"users\" name=\"op\" />
							<input type=\"hidden\" name=\"order_by\" value=\"$order_by\" />
							<input type=\"hidden\" name=\"user_status\" value=\"$user_status\" />
							<input type=\"hidden\" name=\"sort\" value=\"$sort\" />
							<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
							<input type=\"submit\" class=\"form-search\" value=\""._SEARCH."\" /></p>
						</td>
					</form>
					<td>
						"._SORT_USERS_BY."
						<select id=\"change_status\" class=\"select2 styledselect-select\">
							<option value=\"\" $sel0>"._ALL."</option>
							<option value=\"1\" $sel1>"._NORMAL_USERS."</option>
							<option value=\"0\" $sel3>"._SUSPENDED_USERS."</option>
							<option value=\"-1\" $sel2>"._DELETED_USERS."</option>
							<option value=\"2\" $sel4>"._PENDING_USERS."</option>
							<option value=\"3\" $sel5>"._NOT_ACTIVATED_USERS."</option>
						</select>
					</td>
				</tr>
			</table>
			<table align=\"center\" class=\"product-table\" width=\"100%\">
			<thead>
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=users&order_by=user_id&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'user_id') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._ID."</a></th>
				<th class=\"table-header-repeat line-left\"><a href=\"".$admin_file.".php?op=users&order_by=username&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'username') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._USERNAME."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a href=\"".$admin_file.".php?op=users&order_by=user_regdate&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'user_regdate') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._USER_REGDATE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:170px;\"><a href=\"".$admin_file.".php?op=users&order_by=group_id&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'group_id') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._USER_GROUP."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:150px;\">"._REALNAME."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:150px;\">"._EMAIL."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:240px;\">"._OPERATION."</th>
			</tr>
			</thead>
			<tbody>";
			if(!empty($result))
			{
				foreach($result as $row)
				{
					$total_rows = intval($row['total_rows']);
					$user_id = intval($row['user_id']);
					$group_id = intval($row['group_id']);
					$username = filter($row['username'], "nohtml");
					$group_title = ($row['group_lang_titles'] != '') ? phpnuke_unserialize(stripslashes($row['group_lang_titles'])):"";
					$group_name = ($group_title != '') ? $group_title[$nuke_configs['currentlang']]:"";
					$user_regdate = nuketimes($row['user_regdate'], false, false, false, 1);
					$user_realname = filter($row['user_realname'], "nohtml");
					$user_email = filter($row['user_email'], "nohtml");
					$user_status = filter($row['user_status'], "nohtml");
					
					switch($user_status)
					{
						case USER_STATUS_DELETED:
							$this_user_status = " ("._DELETED_USERS.")";
						break;
						case USER_STATUS_INACTIVE:
							$this_user_status = " ("._SUSPENDED_USERS.")";
						break;
						case USER_STATUS_REQUIRE_ADMIN:
							$this_user_status = " ("._PENDING_USERS.")";
						break;
						case USER_STATUS_EMAIL_ACTIVATE:
							$this_user_status = " ("._NOT_ACTIVATED_USERS.")";
						break;
						default:
							$this_user_status = "";
						break;
					}
					
					$operations = array();
					/*$operations[] = "<a href=\"#\" data-mode=\"view\" data-user-id=\"$user_id\" data-title=\""._VIEW_USER_PROFILE." $username\" title=\""._VIEW."\" class=\"table-icon icon-7 info-tooltip operation\"></a>";*/
					$operations[] = "<a href=\"".$admin_file.".php?op=users_admin&mode=edit&user_id=$user_id\" title=\""._EDIT."\" class=\"table-icon icon-1 info-tooltip\"></a>";
					if($user_status != -1)
						$operations[] = "<a href=\"".$admin_file.".php?op=users_admin&mode=delete&user_id=$user_id\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._DELETE_THIS_SURE."');\"></a>";
					if(!isset($nuke_authors_cacheData[$username]))
						$operations[] = "<a href=\"".$admin_file.".php?op=mod_authors&user_id=$user_id\" title=\""._PROMOTEUSER."\" class=\"table-icon icon-3 info-tooltip\"></a>";
					if($user_status != 0)
						$operations[] = "<a href=\"#\" data-mode=\"suspend\" data-user-id=\"$user_id\" data-title=\""._SUSPENDUSER." $username\" title=\""._SUSPENDUSER."\" class=\"table-icon icon-13 info-tooltip operation\"></a>";
					if($user_status == 2 || $user_status == 3)
						$operations[] = "<a href=\"".$admin_file.".php?op=users_admin&user_id=$user_id&mode=approve&csrf_token="._PN_CSRF_TOKEN."\" title=\""._APPROVEUSER."\" class=\"table-icon icon-5 info-tooltip\"></a>";
					if($user_status == 3)
						$operations[] = "<a href=\"#\" data-mode=\"resend_email\" data-user-id=\"$user_id\" data-title=\""._RESEND_EMAIL." $username\" title=\""._RESEND_EMAIL."\" class=\"table-icon icon-10 info-tooltip operation\"></a>";
					$is_admin = is_an_admin($username) ? "("._IS_USER_ADMIN.")":"";
					$contents .= "<tr>
						<td>$user_id</td>
						<td><a href=\"".LinkToGT("index.php?modname=Users&op=userinfo&username=$username")."\" target=\"_blank\">$username $is_admin</a>$this_user_status</td>
						<td align=\"center\">$user_regdate</td>
						<td align=\"center\">$group_name</td>
						<td align=\"center\">$user_realname</td>
						<td align=\"center\">$user_email</td>
						<td align=\"center\">
							".implode("\n", $operations)."
						</td>
						</tr>";
				}
			}
			$contents .= "
			</tbody>
		</table>";
		
		if($total_rows > 0)
		{
			$contents .= "<div id=\"pagination\" class=\"pagination\">";
			$contents .= admin_pagination($total_rows, $entries_per_page, $current_page, $link_to);
			$contents .= "</div>";
		}
		else
			$contents .= "<p align=\"center\">"._NO_USER_FOUND."</p>";
		
		$contents .= "<div id=\"users-dialog\"></div>
		<script>
			$(\"#change_status\").on('change', function()
			{
				top.location.href='".$admin_file.".php?op=users'+(($(this).val() != '') ? '&user_status='+$(this).val():'');
			});
			$(\".operation\").click(function(e)
			{
				e.preventDefault();
				var user_id = $(this).data('user-id');
				var user_mode = $(this).data('mode');
				var dialog_title = $(this).data('title');
				
				$.post(\"".$admin_file.".php?op=users_admin\",
				{
					mode: user_mode,
					user_id: user_id,
					csrf_token : pn_csrf_token
				},
				function(responseText, status){
					$(\"#users-dialog\").html(responseText);
					$(\"#users-dialog\").dialog({
						title: dialog_title,
						resizable: false,
						height: 300,
						width: 500,
						modal: true,
						closeOnEscape: true,
						close: function(event, ui)
						{
							$(this).dialog('destroy');
							$(\"#users-dialog\").html('');
						}
					});
				});
			});
		</script>";

		$contents .= CloseAdminTable();
		phpnuke_db_error();
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	function users_admin($user_id=0, $mode="new", $submit, $users_fields=array(), $uploadfile = array())
	{
		global $db, $hooks, $aid, $ya_config, $admin_file, $nuke_configs, $PnValidator, $module_name;
		
		$user_id = intval($user_id);
		
		$mode = (!in_array($mode, array("new", "edit", "delete", "approve", "suspend", "resend_email"))) ? "new":$mode;
		$user_status = USER_STATUS_ACTIVE;

		$userinfo = array();
		// get all userinfo
		if($mode != "new")
		{
			$result = $db->table(USERS_TABLE)
							->where("user_id", $user_id)
							->first();
			$userinfo = $result->results();
			ya_custum_userfields($userinfo, $user_id);
		}
		else
		{
			$result = $db->query("EXPLAIN ".USERS_TABLE."");
			$rows = $result->results();
			foreach($rows as $row)
			{
				$default = $row['Default'];
				$default = (stristr($row['Type'], "int")) ? (($default !== null) ? $default:0) : "";
				$userinfo[$row['Field']] = $default;
			}
		}
		
		// delete user
		if($mode == "delete")
		{
			if($userinfo['user_avatar_type'] == "upload" && file_exists($ya_config['avatar_path'] . '/' . get_avatar_filename($userinfo['user_avatar'])))
				unlink($ya_config['avatar_path'] . '/' . get_avatar_filename($userinfo['user_avatar']));
			
			$db->table(USERS_TABLE)
				->where("user_id", $user_id)
				->update(array("user_status" => "-1", "user_password" => "", "user_credit" => 0));
			
			$db->table(USERS_FIELDS_VALUES_TABLE)
				->where("uid", $user_id)
				->delete();

			$message = array(
				"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "delete_user.txt"),
				"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
				"username" => $userinfo['username'], 
				"user_realname" => $userinfo['user_realname'], 
				"user_regdate" => $userinfo['user_regdate'], 
				"user_deleted_date" => _NOWTIME
			);
			
			phpnuke_mail($userinfo['user_email'], sprintf(_USER_DELETE_LOG, $userinfo['username'], $nuke_configs['sitename']), $message);
			add_log(sprintf(_USER_DELETE_LOG, $userinfo['username']), 1);
			redirect_to("".$admin_file.".php?op=users");
			die();
		}
				
		// approve and activate user
		if($mode == "approve")
		{
			$db->table(USERS_TABLE)
				->where("user_id", $user_id)
				->update([
					'user_status' => '1',
					'check_num' => '',
				]);
			
			$message = array(
				"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "approve_user.txt"),
				"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
				"username" => $userinfo['username'], 
				"user_realname" => $userinfo['user_realname'], 
				"user_regdate" => $userinfo['user_regdate'], 
				"user_approved_date" => _NOWTIME
			);
			
			phpnuke_mail($userinfo['user_email'], sprintf(_USER_APPROVED, $userinfo['username'], $nuke_configs['sitename']), $message);
			add_log(sprintf(_USER_APPROVED, $userinfo['username']), 1);
			redirect_to("".$admin_file.".php?op=users");
			die();			
		}
		
		// suspend user
		if($mode == "suspend")
		{
			if(isset($submit) && isset($users_fields) && isset($users_fields['suspend_reason']) && $users_fields['suspend_reason'] != '' && isset($users_fields['suspend_expire']) && $users_fields['suspend_expire'] != '')
			{
				$users_fields['suspend_expire'] = (isset($users_fields['suspend_expire']) && intval($users_fields['suspend_expire']) < 1) ? 1:intval($users_fields['suspend_expire']);
				$db->table(USERS_TABLE)
					->where("user_id", $user_id)
					->update([
						'user_status' => '0',
						'user_inactive_reason ' => $users_fields['suspend_reason'],
						'user_inactive_time ' => (($users_fields['suspend_expire'] != -1) ? (_NOWTIME+$users_fields['suspend_expire']):(-1)),
					]);
					
				$message = array(
					"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "suspend_user.txt"),
					"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
					"username" => $userinfo['username'], 
					"user_realname" => $userinfo['user_realname'], 
					"user_suspend_reason" => $users_fields['suspend_reason'], 
					"user_suspend_expire" => nuketimes($users_fields['suspend_expire']),
					"user_suspend_date" => nuketimes(_NOWTIME)
				);
				
				phpnuke_mail($userinfo['user_email'], sprintf(_USER_SUSPENDED, $userinfo['username'], $nuke_configs['sitename']), $message);
				add_log(sprintf(_USER_SUSPENDED, $userinfo['username']), 1);
				redirect_to("".$admin_file.".php?op=users");
				die();
			}
			else
			{
				$contents = "
				<form action=\"".$admin_file.".php\" method=\"post\">
					<table width=\"100%\" class=\"product-table no-border\">
						<tr>
							<th>
								"._USER_SUSPEND_REASON."
							</th>
							<td>
								<input type=\"text\" class=\"inp-form\" size=\"50\" name=\"users_fields[suspend_reason]\" />
							</td>
						</tr>
						<tr>
							<th>
								"._USER_SUSPEND_EXPIRE_DATE."
							</th>
							<td>
								<input type=\"text\" class=\"inp-form\" size=\"10\" name=\"users_fields[suspend_expire]\" />
								".bubble_show(_USER_SUSPEND_EXPIRE_DESC)."
							</td>
						</tr>
						<tr>
							<td colspan=\"2\">
								<input type=\"submit\" class=\"form-submit\" name=\"submit\" />
							</td>
						</tr>
					</table>
					<input type=\"hidden\" name=\"user_id\" value=\"$user_id\" />
					<input type=\"hidden\" name=\"op\" value=\"users_admin\" />
					<input type=\"hidden\" name=\"mode\" value=\"suspend\" />
					<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
				</form>
				";
			}
			die($contents);			
		}
		
		//resend activation email
		if($mode == "resend_email")
		{
			$check_num = random_str(32);
			
			$db->table(USERS_TABLE)
				->where("user_id", $user_id)
				->update([
					'check_num' => $check_num,
				]);

			$finishlink = LinkToGT("index.php?modname=$module_name&op=register&username=".$userinfo['username']."&check_num=$check_num");

			$message = array(
				"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "user_email_activation.txt"),
				"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
				"username" => $userinfo['username'], 
				"user_password" => "******", 
				"user_email" => $userinfo['user_email'], 
				"user_realname" => $userinfo['user_realname'],  
				"finishlink" => $finishlink
			);
			
			phpnuke_mail($userinfo['user_email'], sprintf(_REGISTRATIONSUB, $userinfo['username'], $nuke_configs['sitename']), $message);
			add_log(sprintf(_USER_EMAIL_ACTIVATION_RESENT, $userinfo['username']), 1);
			echo sprintf(_USER_EMAIL_ACTIVATION_RESENT, $userinfo['username']);
			redirect_to("".$admin_file.".php?op=users", 5);
			die();
		}
		
		$languageslists = get_dir_list('language', 'files');
		
		// submit edited data
		if(isset($submit) && isset($users_fields) && !empty($users_fields))
		{
			$modify_errors = $fids = array();
			global $PnValidator;
			
			$PnValidator->add_filter("fixtext", 'ya_fixtext'); 

			$validation_rules = array();
			$filter_rules = array();
			$username_chenged = ($users_fields['username'] != $userinfo['username']) ? true:false;
			$email_chenged = ($ya_config['allowmailchange'] == 1 && $users_fields['user_email'] != $userinfo['user_email']) ? true:false;
			
			if ($email_chenged)
			{
				$validation_rules['user_email'] = 'required|valid_email';
				$filter_rules['user_email'] = 'sanitize_email';
			}
			
			if($users_fields['new_user_password'] != '')
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
			$validation_rules['group_id'] = 'integer';
			
			$filter_rules['user_realname'] = 'sanitize_string';
			$filter_rules['user_femail'] = 'sanitize_email';
			$filter_rules['user_birthday'] = 'addslashes';
			$filter_rules['user_about'] = 'addslashes';
			$filter_rules['user_address'] = 'addslashes';
			$filter_rules['user_interests'] = 'addslashes';
			$filter_rules['user_sig'] = 'addslashes|sanitize_string';
			$filter_rules['user_gender'] = 'sanitize_string';
			$filter_rules['user_avatar_type'] = 'sanitize_string';
			$filter_rules['user_newsletter'] = 'sanitize_numbers';
			$filter_rules['user_allow_viewonline'] = 'sanitize_numbers';
			$filter_rules['remove_avatar'] = 'sanitize_numbers';
			$filter_rules['group_id'] = 'sanitize_numbers';

			if(isset($users_fields['custom_fields']) && !empty($users_fields['custom_fields']))
			{
				$cs_result = $db->query("SELECT * FROM ".USERS_FIELDS_TABLE." where act = '1' ORDER BY pos ASC");
				
				$cs_results = $cs_result->results();
				if(!empty($cs_results))
				{
					foreach($cs_results as $field => $value)
					{
						$fids[$value['name']] = $value['fid'];
						if(isset($users_fields['custom_fields'][$value['name']]))
						{
							$users_fields[$value['name']] = $users_fields['custom_fields'][$value['name']];
							if($value['need'] == 1)
								$validation_rules[$value['name']] = 'required'.(($value['type'] == 'number') ? '|numeric':'');
								
							if($value['type'] == 'number')
								$filter_rules[$value['name']] = 'sanitize_numbers';
							elseif($value['type'] == 'string')
								$filter_rules[$value['name']] = 'sanitize_string';
							elseif($value['type'] == 'textarea')
								$filter_rules[$value['name']] = 'addslashes';
						}
					}
				}
			}
			
			// Get or set the filtering rules

			$PnValidator->validation_rules($validation_rules); 
			$PnValidator->filter_rules($filter_rules);
			
			$users_fields = $PnValidator->sanitize($users_fields, array('username'), true, true);
			$validated_data = $PnValidator->run($users_fields);

			if($validated_data !== FALSE)
				$users_fields = $validated_data;
			else
				$modify_errors[] = "<p align=\"center\">".$PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br />')."</p>";
			
			if(empty($modify_errors))
			{
				extract($users_fields);
				
				$user_result = $db->table(USERS_TABLE)
								->where('username', $username)
								->where('user_id', '!=', $user_id)
								->select(['user_id']);
				if($user_result->count() > 0)
					$modify_errors[] = _USER_HAS_REGISTERED;
				
				$bad_username = ($ya_config['bad_username'] != '' && !is_array($ya_config['bad_username'])) ? explode("\n", str_replace("\r","",$ya_config['bad_username'])):array();
				$bad_mail = ($ya_config['bad_mail'] != '' && !is_array($ya_config['bad_mail'])) ? explode("\n", str_replace("\r","",$ya_config['bad_mail'])):array();
				$bad_nick = ($ya_config['bad_nick'] != '' && !is_array($ya_config['bad_nick'])) ? explode("\n", str_replace("\r","",$ya_config['bad_nick'])):array();
				define("NOAJAX_REQUEST", true);
				
				// email check
				if($email_chenged && in_array($user_email, $bad_mail))
					$modify_errors[] = _USER_BAD_EMAIL;
				
				if($email_chenged && !_check_register_fields('user_email', $user_email, $userinfo['user_email']))
					$modify_errors[] = _USER_EMAIL_HAS_SELECTED;
				
				// username check
				if($username_chenged && in_array($username, $bad_username))
					$modify_errors[] = _USER_BAD_NAME;
				if($username_chenged && !_check_register_fields('username', $username, $userinfo['username']))
					$modify_errors[] = _USER_HAS_REGISTERED;
				
				if(strrpos($username,' ') > 0)
					$modify_errors[] = _NICKNOSPACES;
				
				// realname check
				if(in_array($user_realname, $bad_nick))
					$modify_errors[] = _USER_BAD_REALNAME;
					
				if(empty($modify_errors))
				{
					$hashed_user_password = '';
					if($new_user_password != '')
					{						
						$hashed_user_password = ($new_user_password != '' && $new_user_password_cn != '' && $new_user_password == $new_user_password_cn) ? phpnuke_hash_password($new_user_password):"";
					}
											
					$update_fields['username'] = ($username != $userinfo['username']) ? $username:$userinfo['username'];
					$update_fields['user_regdate'] = _NOWTIME;
					$update_fields['user_realname'] = $user_realname;
					$update_fields['user_femail'] = $user_femail;
					$update_fields['user_website'] = $user_website;
					if($user_birthday != '')
					{
						$update_fields['user_birthday'] = to_mktime($user_birthday);
					}
					$update_fields['user_phone'] = $user_phone;
					$update_fields['user_about'] = $user_about;
					$update_fields['user_address'] = $user_address;
					$update_fields['user_interests'] = $user_interests;
					$update_fields['user_sig'] = $user_sig;
					$update_fields['user_newsletter'] = $user_newsletter;
					$update_fields['user_allow_viewonline'] = $user_allow_viewonline;
					$update_fields['user_lang'] = $user_lang;
					$update_fields['user_gender'] = $user_gender;
					$update_fields['user_avatar_type'] = $user_avatar_type;
					$update_fields['group_id'] = intval($group_id);
					$update_fields['user_groups'] = (!empty($user_groups)) ? implode(",", array_values(array_unique($user_groups))):"";
					if($remove_avatar == 1)
					{
						avatar_delete('user', $userinfo);
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
								if($userinfo['user_avatar'] != $avatar['remotelink'] && $avatar['remotelink'] != '')
									$avatar_must_changeed = true;
							break;
							
							case"gravatar":
								if($userinfo['user_avatar'] != $avatar['gravatar_email'] && $avatar['gravatar_email'] != '')
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
					
					if($mode == "new")
					{
						$result = $db->table(USERS_TABLE)
									->insert($update_fields);
					
						$user_id = $userinfo['user_id'] = $db->lastInsertId();
					}
					else
					{
						$result = $db->table(USERS_TABLE)
									->where("user_id", $userinfo['user_id'])
									->update($update_fields);
					}
					
					if(!$result)
						$modify_errors[] = $db->getErrors('last')['message'];
					else
					{
						foreach($users_fields['custom_fields'] as $custom_fields_key => $custom_fields_val)
						{
							if(isset($users_fields[$custom_fields_key]))
							{
								$cs_result2 = $db->table(USERS_FIELDS_VALUES_TABLE)
												->where('uid', $userinfo['user_id'])
												->where('fid', $fids[$custom_fields_key])
												->first();

								if($cs_result2->count() > 0)
								{
									$cs_result2['value'] = $users_fields[$custom_fields_key];
									$cs_result2->save();								
								}
								else
								{
									$db->table(USERS_FIELDS_VALUES_TABLE)
										->insert([
											'uid' => $user_id,
											'fid' => $fids[$custom_fields_key],
											'value' => $users_fields[$custom_fields_key],
										]);									
								}
							}
						}
					}
				}
			}
			
			if(!empty($modify_errors))
				die_error(implode("<br />", $modify_errors));
			
			phpnuke_db_error();
			add_log(sprintf((($mode == 'new') ? _USER_ADD_LOG:_USER_EDIT_LOG), $username), 1);
			redirect_to("".$admin_file.".php?op=users_admin&mode=edit&user_id=$user_id");
			die();
		}
		
		global $users_system;
		$user_avatar = $users_system->get_avatar_url($userinfo, 180, 180);

		$userinfo['user_groups'] = ($userinfo['user_groups'] != '') ? explode(",", $userinfo['user_groups']):array();
		$userinfo['user_sig'] = stripslashes($userinfo['user_sig']);
		
		$nl_checked1 = ($userinfo['user_newsletter'] == 1) ? "checked":"";
		$nl_checked2 = ($userinfo['user_newsletter'] == 0) ? "checked":"";
		
		$av_checked1 = ($userinfo['user_allow_viewonline'] == 1) ? "checked":"";
		$av_checked2 = ($userinfo['user_allow_viewonline'] == 0) ? "checked":"";
		
		$gsel1 = ($userinfo['user_gender'] == 'mr') ? "checked":"";
		$gsel2 = ($userinfo['user_gender'] == 'mrs') ? "checked":"";
		
		$userinfo['user_website'] = (isset($userinfo['user_website']) && !empty($userinfo['user_website'])) ? correct_url($userinfo['user_website']):"";
		
		$avatar_upload_style = ($userinfo['user_avatar_type'] == 'upload' || $userinfo['user_avatar_type'] == '') ? "":" style=\"display:none;\"";
		$avatar_remote_style = ($userinfo['user_avatar_type'] == 'remote') ? "":" style=\"display:none;\"";
		$avatar_gravatar_style = ($userinfo['user_avatar_type'] == 'gravatar') ? "":" style=\"display:none;\"";
		
		$ava_sel1 = ($userinfo['user_avatar_type'] == 'upload' || $userinfo['user_avatar_type'] == '') ? "selected":"";
		$ava_sel2 = ($userinfo['user_avatar_type'] == 'remote') ? "selected":"";
		$ava_sel3 = ($userinfo['user_avatar_type'] == 'gravatar') ? "selected":"";
		$pagetitle = _USER_PROFILE_EDIT." <b>".$userinfo['username']."</b>";
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("users" => $pagetitle);});
		$ya_config['data_verification']['user_password']['data-validation'] = "";
		$ya_config['data_verification']['user_password_cn']['data-validation'] = "";
				
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= users_menu();
		$contents .= "
		<script type=\"text/javascript\" src=\"includes/Ajax/jquery/jquery.mockjax.js\"></script>
		<script type=\"text/javascript\" src=\"includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>
		<p align=\"center\" id=\"avatar_preview\">
			<img src=\"" . (str_replace(' ', '%20', $user_avatar)) . "\" width=\"100%\" height=\"100%\" alt=\""._USER_AVATAR." ".$userinfo['username']."\" title=\""._USER_AVATAR." ".$userinfo['username']."\" style=\"max-width:180px;margin-bottom:10px;\" /><br />
			<button type=\"button\" class=\"avatar_options\" title=\""._USER_EDIT_AVATAR."\">"._USER_EDIT_AVATAR."</button>
			<button type=\"button\" class=\"btn btn-danger\" title=\""._USER_DELETE_AVATAR."\" id=\"remove_avatar\">"._USER_DELETE_AVATAR."</button>
		</p><br /><br />
		<form id=\"user_configs_form\" class=\"form-horizontal\" role=\"form\" action=\"".$admin_file.".php?op=users&mode=edit\" method=\"post\" enctype=\"multipart/form-data\">
			<table width=\"100%\" class=\"product-table id-form no-border\">
				<tr>
					<th>"._USERNAME."</th>
					<td>
						<input type=\"text\" size=\"40\" class=\"inp-form-ltr\" value=\"".$userinfo['username']."\" name=\"users_fields[username]\" ".str_replace(array('{MODE}','{DEFAULT}'), array($mode, $userinfo['username']), data_verification_parse($ya_config['data_verification']['username']))."  />
						".bubble_show(_YA_CHNGRISK)."
					</td>
				</tr>
				<tr>
					<th>"._USER_FAMILYNAME."</th>
					<td>
						<input type=\"text\" size=\"40\" class=\"inp-form\" name=\"users_fields[user_realname]\" value=\"".$userinfo['user_realname']."\" ".str_replace(array('{MODE}','{DEFAULT}'), array($mode, $userinfo['user_realname']), data_verification_parse($ya_config['data_verification']['user_realname']))." />
					</td>
				</tr>
				<tr>
					<th>"._EMAIL."</th>
					<td>
						".(($ya_config['allowmailchange'] == 1) ? "<input type=\"text\" size=\"40\" class=\"inp-form-ltr\" name=\"users_fields[user_email]\" value=\"".$userinfo['user_email']."\" ".str_replace(array('{MODE}','{DEFAULT}'), array($mode, $userinfo['user_email']), data_verification_parse($ya_config['data_verification']['user_email']))." />":"".$userinfo['user_email']."")."						
					</td>
				</tr>";

				$group_result = $db->query("SELECT * FROM ".GROUPS_TABLE." ORDER BY group_id ASC");
				
				$group_rows = $group_result->results();
				if(!empty($group_rows))
				{
				$contents .= "
				<tr>
					<th>"._USER_GROUP."</th>
					<td>
						<select name=\"users_fields[group_id]\" class=\"styledselect-select\" style=\"width:100%\">";
					foreach($group_rows as $grow)
					{
						$group_id = $grow['group_id'];
						if($group_id == 1) continue;
						$group_title = ($grow['group_lang_titles'] != '') ? phpnuke_unserialize(stripslashes($grow['group_lang_titles'])):"";

						$group_title = ($group_title != '') ? $group_title[$nuke_configs['currentlang']]:"";
						$sel = ($group_id == $userinfo['group_id']) ? "selected":"";
						$contents .= "<option value=\"$group_id\" $sel>".$group_title."</option>";
					}
					$contents .= "</select>
					</td>
				</tr>";
				$contents .= "
				<tr>
					<th>"._OTHER_USER_GROUP."</th>
					<td>
						<select name=\"users_fields[user_groups][]\" class=\"styledselect-select\" multiple=\"multiple\" style=\"width:100%\">";
					foreach($group_rows as $grow)
					{
						$group_id = $grow['group_id'];
						if($group_id == 1) continue;
						$group_title = ($grow['group_lang_titles'] != '') ? phpnuke_unserialize(stripslashes($grow['group_lang_titles'])):"";

						$group_title = ($group_title != '') ? $group_title[$nuke_configs['currentlang']]:"";
						$sel = (in_array($group_id, $userinfo['user_groups'])) ? "selected":"";
						$contents .= "<option value=\"$group_id\" $sel>".$group_title."</option>";
					}
					$contents .= "</select>
					</td>
				</tr>";
				}
				$contents .="
				<tr>
					<th>"._DISPLAY_EMAIL."</th>
					<td>
						<input type=\"text\" size=\"40\" class=\"inp-form-ltr\" name=\"users_fields[user_femail]\" value=\"".$userinfo['user_femail']."\" />
					</td>
				</tr>
				<tr>
					<th>"._USER_WEBSITE."</th>
					<td>
						<input type=\"text\" size=\"40\" class=\"inp-form-ltr\" name=\"users_fields[user_website]\" value=\"".$userinfo['user_website']."\" />
					</td>
				</tr>
				<tr>
					<th>"._USER_BIRTHDAY."</th>
					<td>
						<input type=\"text\" size=\"40\" class=\"inp-form-ltr calendar\" name=\"users_fields[user_birthday]\" value=\"".nuketimes($userinfo['user_birthday'], false, false, false, 1)."\" />
					</td>
				</tr>";
				
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
						
						if($fields['type'] == 'text' && intval($fields['size']) != 0)
							$attrs['size'] = $fields['size'];
						if($fields['type'] == 'select')
							$attrs['class'] = 'styledselect-select';
						if($fields['type'] == 'text')
							$attrs['class'] = 'inp-form';
						if($fields['type'] == 'textarea')
						{
							$attrs['class'] = 'form-textarea';
							$attrs['style'] = 'width:310px;';
						}
						if($fields['type'] == 'radio' || $fields['type'] == 'checkbox')
						{
							$attrs['class'] = 'styled';
						}
						
						if(in_array($fields['type'], array("radio","checkbox", "select")) && is_array($cs_value) && !empty($cs_value))
						{
							foreach($cs_value as $cs_val)
							{
								$options[] = array("", $cs_val, $cs_val, "attrs" => array_merge($attrs, array('data-label' => $cs_val))); 
							}
						}
						
						$fdata = array(
							"".$fields['name']."" => array(
								"label" => $fields['display'],
								"opts" => array(
									'wrap_tag'  => '',
									'before_html'  => "<tr><th><label for=\"cs_".$fields['name']."\">".$fields['display']." :</label></th><td>",
									'after_html'  => '</td>
									</tr>',
									'id'  => "cs_".$fields['name'],
									'name'  => "users_fields[custom_fields][".$fields['name']."]",
									'type'  => $fields['type'],
									'add_label'  => false,
									'label'  => ((in_array($fields['type'], array("radio","checkbox"))) ? '':$fields['name']),
									'value'  => (isset($userinfo[$fields['name']])) ? $userinfo[$fields['name']]:"",
									'required'  => ($fields['need'] == 1) ? true:false,
									'max'  => ($fields['size'] != 0) ? intval($fields['size']):'',
									'options'   => $options,
									'attrs'  => $attrs,
								)
							)
						);
						
						$form->add_inputs($fdata);
						$contents .= $form->build_form($userinfo, false);
						$form->clear_form();
						
						$userinfo[$fields['name']] = $fields['value'];
						$userinfo['custom_fields'][$fields['name']] = array($fields['display'], $fields['value']);
					}
				}
				$contents .="
				<tr>
					<th>"._MOBILE_PHONE."</th>
					<td>
						<input type=\"text\" size=\"40\" class=\"inp-form-ltr\" name=\"users_fields[user_phone]\" value=\"".$userinfo['user_phone']."\" />
					</td>
				</tr>
				<tr>
					<th>"._USER_ABOUT_ME."</th>
					<td>
						<textarea class=\"form-textarea\" id=\"user_about\" name=\"users_fields[user_about]\" style=\"width:310px;\">".$userinfo['user_about']."</textarea>
					</td>
				</tr>
				<tr>
					<th>"._USER_ADDRESS."</th>
					<td>
						<textarea class=\"form-textarea\" id=\"user_address\" name=\"users_fields[user_address]\" style=\"width:310px;\">".$userinfo['user_address']."</textarea>
					</td>
				</tr>
				<tr>
					<th>"._USER_INTERESTS."</th>
					<td>
						<textarea class=\"form-textarea\" id=\"user_interests\" name=\"users_fields[user_interests]\" style=\"width:310px;\">".$userinfo['user_interests']."</textarea>
					</td>
				</tr>
				<tr>
					<th>"._USER_SIGN."</th>
					<td>
						<textarea class=\"form-textarea\" id=\"user_sig\" name=\"users_fields[user_sig]\" style=\"width:310px;\">".$userinfo['user_sig']."</textarea>
					</td>
				</tr>
				<tr>
					<th>"._USER_NEWSLETTER."</th>
					<td>
						<input type=\"radio\" class=\"styled\" data-label=\""._YES."\" name=\"users_fields[user_newsletter]\" value=\"1\" $nl_checked1/> &nbsp; 
						<input type=\"radio\" class=\"styled\" data-label=\""._NO."\" name=\"users_fields[user_newsletter]\" value=\"0\" $nl_checked2/>
					</td>
				</tr>
				<tr>
					<th>"._USER_VIEW_ONLINE."</th>
					<td>
						<input type=\"radio\" class=\"styled\" data-label=\""._YES."\" name=\"users_fields[user_allow_viewonline]\" value=\"1\" $av_checked1/> &nbsp; 
						<input type=\"radio\" class=\"styled\" data-label=\""._NO."\" name=\"users_fields[user_allow_viewonline]\" value=\"0\" $av_checked2/>
					</td>
				</tr>
				<tr>
					<th>"._LANGUAGE."</th>
					<td>
						<select name=\"users_fields[user_lang]\" id=\"user_lang\" class=\"styledselect-select\" style=\"width:323px;\">
							<option value=\"\">"._ALL."</option>";
								foreach($languageslists as $languageslist)
								{
									if($languageslist == '' || $languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
									$languageslist = str_replace(".php", "", $languageslist);
									$sel = ($languageslist == $userinfo['user_lang']) ? "selected":"";
									$contents .= "<option value=\"$languageslist\" $sel>".ucfirst($languageslist)."</option>";
								}
								$contents .= "
						</select>
					</td>
				</tr>
				<tr>
					<th>"._USER_GENDER."</th>
					<td>
						<select name=\"users_fields[user_gender]\" id=\"user_gender\" class=\"styledselect-select\" style=\"width:323px;\">
							<option value=\"mr\" $gsel1>"._MR."</option>
							<option value=\"mrs\" $gsel2>"._MRS."</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>"._USER_NEW_PASSWORD."</th>
					<td>
						<input type=\"text\" size=\"40\" class=\"inp-form\" name=\"users_fields[new_user_password]\" value=\"\" ".data_verification_parse($ya_config['data_verification']['user_password'])." />
					</td>
				</tr>
				<tr>
					<th>"._USER_RETYPE_NEW_PASSWORD."</th>
					<td>
						<input type=\"text\" size=\"40\" class=\"inp-form\" name=\"users_fields[new_user_password_cn]\" value=\"\" ".data_verification_parse($ya_config['data_verification']['user_password_cn'])." />
					</td>
				</tr>
				<tr>
					<td colspan=\"2\">
						<input type=\"submit\" class=\"form-submit\" value=\""._SEND."\" />
					</td>
				</tr>
			</table>
			<input type=\"hidden\" name=\"op\" value=\"users_admin\" />
			<input type=\"hidden\" name=\"mode\" value=\"$mode\" />
			<input type=\"hidden\" name=\"user_id\" value=\"$user_id\" />
			<input type=\"hidden\" name=\"users_fields[remove_avatar]\" id=\"remove_avatar_input\" value=\"0\" />
			<div id=\"users-dialog\" style=\"display:none;\">
				<div class=\"form-group\">
					<label class=\"control-label col-sm-3\" for=\"user_avatar_type\">"._USER_AVATAR_TYPE." :</label>
					<div class=\"col-sm-9\">
						<select name=\"users_fields[user_avatar_type]\" id=\"user_avatar_type\" class=\"selectpicker\" style=\"width:300px;\">
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
							<input type=\"file\" name=\"uploadfile\" class=\"uploadfile\" id=\"uploadfile\" accept=\"image/*\" />
							<br />".sprintf(_USER_AVATAR_DIMENTIONS, $ya_config['avatar_max_width'], $ya_config['avatar_max_height'], formatBytes($ya_config['avatar_filesize'], 0, true))."
						</div>
					</div>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-3\" for=\"avatar_uploadurl\">"._USER_DIRECT_AVATAR_UPLOAD." :</label>
						<div class=\"col-sm-9\">
							<input type=\"url\" name=\"users_fields[avatar][uploadurl]\" class=\"inp-form text-left\" id=\"avatar_uploadurl\" />
							<br />"._USER_DIRECT_AVATAR_UPLOAD_MSG."
						</div>
					</div>
				</div>
				<div class=\"remote_avater\"$avatar_remote_style>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-3\" for=\"avatar_remote\">"._USER_DIRECT_AVATAR_LINK." :</label>
						<div class=\"col-sm-9\">
							<input type=\"url\" name=\"users_fields[avatar][remotelink]\" class=\"inp-form text-left\" id=\"avatar_remote\" value=\"".(($userinfo['user_avatar_type'] == 'remote') ? $userinfo['user_avatar']:"")."\" />
						</div>
					</div>
				</div>
				<div class=\"gravatar_avater\"$avatar_gravatar_style>
					<div class=\"form-group\">
						<label class=\"control-label col-sm-3\" for=\"gravatar_avater\">"._USER_EMAIL_IN_GRAVATAR." :</label>
						<div class=\"col-sm-9\">
							<input type=\"email\" name=\"users_fields[avatar][gravatar_email]\" class=\"inp-form text-left\" id=\"gravatar_avater\" value=\"".(($userinfo['user_avatar_type'] == 'gravatar') ? $userinfo['user_avatar']:"")."\" />
						</div>
					</div>
				</div>
				<div class=\"modal-footer\">
					<button type=\"button\" id=\"close-dialog\" data-dismiss=\"modal\">"._CLOSE_AND_CONTINUE."</button>
				</div>
			</div>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/calendar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-ar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-fa.js\" type=\"text/javascript\"></script>
		<script>
			$(document).ready(function(){
				$.validate({
					form : '#user_configs_form',
					modules : 'security',
				});
				
				var allowmailchange = ".(($ya_config['allowmailchange'] == 1) ? 'true':'false').";
				var remote_url = '".LinkToGT("index.php?modname=$module_name&op=check_register_fields")."';
				var pass_min = ".$ya_config['pass_min'].", pass_max = ".$ya_config['pass_max'].";

				$(\".avatar_options\").click(function(e)
				{
					e.preventDefault();
					$(\"#users-dialog\").dialog({
						title: 'avatar',
						resizable: false,
						height: 300,
						width: 500,
						modal: true,
						closeOnEscape: true,
						close: function(event, ui)
						{
							$(this).dialog('destroy');
						}
					});
				});
				$(\"#close-dialog\").on('click', function(event){
					$(\"#users-dialog\").dialog('destroy');
				});
			});
		</script>
		<script type=\"text/javascript\" src=\"modules/$module_name/includes/users.js\"></script>";

		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
		
	function users_groups($search_query = '')
	{
		global $db, $hooks, $admin_file, $nuke_configs;
		$contents = '';
		
		$hooks->add_filter("set_page_title", function() {return array("users_groups" => _GROUPS_ADMIN);});
		
		$link_to_more = "";
		$where = array();
		$params = array();
				
		if(isset($search_query) && $search_query != '')
		{
			$params[":search_query"] = "%".rawurldecode($search_query)."%";
			$where[] = "g.group_name LIKE :search_query";
			$link_to_more .= "&search_query=".rawurlencode($search_query)."";
		}
		
		$where = array_filter($where);
		$where = (!empty($where)) ? "WHERE ".implode(" AND ", $where):'';
		
		$entries_per_page = 50;
		$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
		$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
		$link_to = "".$admin_file.".php?op=groups_admin".$link_to_more;

		$total_rows = 0;
		$result = $db->query("
			SELECT g.*, 
			(SELECT COUNT(group_id) FROM ".GROUPS_TABLE." ".str_replace("g.","", $where).") as total_rows
			FROM ".GROUPS_TABLE." AS g
			$where 
			ORDER BY g.group_id ASC LIMIT $start_at, $entries_per_page
		", $params);
		
		$contents .= GraphicAdmin();
		$contents .= users_menu();
		
		$contents .= OpenAdminTable();
		$contents .= "
			<table align=\"center\" class=\"product-table no-border no-hover\" width=\"100%\">
				<tr>
					<form action=\"".$admin_file.".php\" method=\"post\">
						<td>
							"._SEARCH_BY_GROUPNAME." 
							<input class=\"inp-form\" type=\"text\" name=\"search_query\" size=\"30\" value=\"$search_query\" />
							<input type=\"hidden\" value=\"users_groups\" name=\"op\" />
							<input type=\"submit\" class=\"form-search\" value=\""._SEARCH."\" />&nbsp;&nbsp;
							<a href=\"#\" data-mode=\"add\" data-group-id=\"0\" data-title=\""._ADD_NEW_GROUP."\" title=\""._ADD_NEW_GROUP."\" class=\"info-tooltip operation\">"._ADD_NEW_GROUP."</a>
							</p>
						</td>
						<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
					</form>
			</table>
			<table align=\"center\" class=\"product-table\" width=\"100%\">
			<thead>
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\">"._ID."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\">"._GROUP_NAME."</th>
				<th class=\"table-header-repeat line-left\">"._GROUP_TITLE_IN_LANG."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:100px;\">"._OPERATION."</th>
			</tr>
			</thead>
			<tbody>";
			if(!empty($result))
			{
				foreach($result as $row)
				{
					$total_rows = intval($row['total_rows']);
					$group_id = intval($row['group_id']);
					$group_type = intval($row['group_type']);
					$group_name = filter($row['group_name'], "nohtml");
					$group_colour = filter($row['group_colour'], "nohtml");
					$group_title = ($row['group_lang_titles'] != '') ? phpnuke_unserialize(stripslashes($row['group_lang_titles'])):"";
					$group_title = ($group_title != '') ? $group_title[$nuke_configs['currentlang']]:"";
					
					$contents .= "<tr>
						<td>$group_id</td>
						<td style=\"color:$group_colour\">$group_name</td>
						<td align=\"center\" style=\"color:$group_colour\">$group_title</td>
						<td align=\"center\">
							<a href=\"#\" data-mode=\"edit\" data-group-id=\"$group_id\" data-title=\""._EDIT_GROUP."\" title=\""._EDIT_GROUP."\" class=\"table-icon icon-1 info-tooltip operation\"></a>";
							if($group_type != 1)
								$contents .= "<a href=\"#\" data-mode=\"delete\" data-group-id=\"$group_id\" data-title=\""._DELETE_GROUP."\" title=\""._DELETE_GROUP."\" class=\"table-icon icon-2 info-tooltip operation\"></a>";
						$contents .= "</td>
					</tr>";
				}
			}
			$contents .= "
			</tbody>
		</table>";
		
		if($total_rows > 0)
		{
			$contents .= "<div id=\"pagination\" class=\"pagination\">";
			$contents .= admin_pagination($total_rows, $entries_per_page, $current_page, $link_to);
			$contents .= "</div>";
		}
		else
			$contents .= "<p align=\"center\">"._NO_GROUP_FOUND."</p>";
		
		$contents .= "<div id=\"users-dialog\"></div>
		<script>
			$(\".operation\").click(function(e)
			{
				e.preventDefault();
				var group_id = $(this).data('group-id');
				var group_mode = $(this).data('mode');
				var dialog_title = $(this).data('title');
				
				$.post(\"".$admin_file.".php?op=users_groups_admin\",
				{
					mode: group_mode,
					group_id: group_id,
					csrf_token : pn_csrf_token
				},
				function(responseText, status){
					$(\"#users-dialog\").html(responseText);
					$(\"#users-dialog\").dialog({
						title: dialog_title,
						resizable: false,
						height: 450,
						width: 600,
						modal: true,
						closeOnEscape: true,
						close: function(event, ui)
						{
							$(this).dialog('destroy');
							$(\"#users-dialog\").html('');
						}
					});
				});
			});
		</script>";

		$contents .= CloseAdminTable();
		phpnuke_db_error();
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function users_groups_admin($group_id=0, $mode="new", $submit, $group_fields=array())
	{
		global $db, $aid, $visitor_ip, $hooks, $admin_file, $nuke_configs, $PnValidator, $module_name;
		
		$group_id = intval($group_id);
		
		$mode = (!in_array($mode, array("new", "edit", "delete"))) ? "new":$mode;
		
		$error = array();
		
		if($group_id != 0)
		{
			$row = $db->table(GROUPS_TABLE)
					->where("group_id", $group_id)
					->first();
			if($row->count() > 0)
			{
				$group_type = $row['group_type'];
				$group_name = $row['group_name'];
			}
			else
				$error[] = _NO_GROUP_FOUND;
		}
		elseif($mode != 'new' && $group_id == 0)
			$error[] = _NO_GROUP_SELECTED;
			
		// delete group
		if($mode == "delete")
		{
			// submit edited data
			if(isset($submit) && isset($group_fields['user_groups']) && !empty($group_fields['user_groups']))
			{
				$result = $db->query("SELECT user_id, group_id, user_groups FROM ".USERS_TABLE." WHERE FIND_IN_SET($group_id, user_groups)");
				
				if($result->count() > 0)
				{
					$group_id_query = array();
					$group_ids_query = array();
					$rows = $result->results();
					foreach($rows as $urow)
					{
						$user_id = $urow['user_id'];
						$user_group_id = ($urow['group_id'] == $group_id) ? $group_fields['user_groups'][0]:$urow['group_id'];
						$user_group_ids = ($urow['user_groups'] != '') ? explode(",", $urow['user_groups']):array();
						foreach($user_group_ids as $key => $user_group_id2)
						{
							if($user_group_id2 == $group_id)
							{
								unset($user_group_ids[$key]);
								$user_group_ids = array_merge($user_group_ids, $group_fields['user_groups']);
							}
						}
						$user_group_ids = implode(",", array_values(array_unique($user_group_ids)));
						$group_id_query[] = "WHEN user_id = '$user_id' THEN '$user_group_id'";
						$group_ids_query[] = "WHEN user_id = '$user_id' THEN '$user_group_ids'";
						$user_ids[] = $user_id;
					}
					
					if(!empty($group_id_query) && !empty($group_ids_query))
					{
						$group_id_query = implode("\n", $group_id_query);
						$group_ids_query = implode("\n", $group_ids_query);
						$user_ids = implode("\n", $user_ids);

						$result = $db->query("UPDATE ".USERS_TABLE." SET group_id = CASE
							$group_id_query
						END, user_groups = CASE
							$group_ids_query
						END
						WHERE user_id IN($user_ids)");
					}
					$db->table(GROUPS_TABLE)
					->where("group_id", $group_id)
					->delete();
				}
				cache_system("nuke_groups");
				add_log(sprintf(_DELETE_GROUP, $group_name), 1);
				Header("Location: ".$admin_file.".php?op=users_groups");
			}
			
			$groups_row = $db->table(GROUPS_TABLE)
							->select(['group_id','group_name','group_lang_titles']);
			
			$group_fields = array(
				"group_id"			=> ((intval($group_id) != 0) ?				$group_id:0),
				"group_type"		=> ((isset($row['group_type'])) ?			$row['group_type']:2),
				"group_name"		=> ((isset($row['group_name'])) ?			$row['group_name']:""),
				"group_lang_titles"	=> ((isset($row['group_lang_titles'])) ?	phpnuke_unserialize(stripslashes($row['group_lang_titles'])):array()),
				"group_options"		=> ((isset($row['group_options'])) ?		$row['group_options']:0),
				"group_colour"		=> ((isset($row['group_colour'])) ?			$row['group_colour']:"#000000"),
			);
			
			$contents ="
			"._MUST_SELECT_REPLACE_GROUP."
			<form action=\"".$admin_file.".php\" method=\"post\" id=\"groups_form\">
				<table width=\"100%\" class=\"id-form product-table no-border\">
					<tr>
						<th style=\"width:200px\">"._REPLACED_GROUP."</th>
						<td>
							<select name=\"group_fields[user_groups][]\" class=\"styledselect-select\" multiple=\"multiple\" style=\"width:100%\">";
							foreach($groups_row as $groups_data)
							{
								$r_group_id = $groups_data['group_id'];										
								$r_group_lang_titles = ($groups_data['group_lang_titles'] != '') ? phpnuke_unserialize(stripslashes($groups_data['group_lang_titles'])):"";
								$r_group_lang_titles = ($r_group_lang_titles != '') ? $r_group_lang_titles[$nuke_configs['currentlang']]:"";
								$sel = ($r_group_id == 2) ? "selected":"";
								$contents .= "<option value=\"$r_group_id\" $sel>".$r_group_lang_titles."</option>";
							}					
							$contents .= "</select>
						</td>
					</tr>
					<tr>
						<td colspan=\"2\">
							<input type=\"submit\" name=\"submit\" value=\"submit\" class=\"form-submit\" />
						</td>					
					</tr>
				</table>
				<input type=\"hidden\" name=\"op\" value=\"users_groups_admin\" />
				<input type=\"hidden\" name=\"group_id\" value=\"$group_id\" />
				<input type=\"hidden\" name=\"mode\" value=\"$mode\" />
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>
			<script>
				$(document).ready(function(){
					$(\"#grousp_form\").validate();
				});
			</script>";
		}
		
		// edit group
		if($mode == "edit" || $mode == "new")
		{
			$languageslists = get_dir_list('language', 'files');
			
			// submit edited data
			if(isset($submit) && isset($group_fields) && !empty($group_fields))
			{
				$items	= array();

				$PnValidator->add_validator("is_main_group", function($field, $input, $param = NULL) use ($group_type, $group_name, $group_fields)
				{
					if($input[$field] == '')
						return false;
					
					if($group_type == 1 && $input[$field] != $group_name)
						return false;
					
					return true;
				}); 
				$PnValidator->add_validator("in_languages", function($field, $input, $param = NULL) {
					$param = explode("-", $param);
					return in_array($input[$field], $param);
				}); 
				$PnValidator->validation_rules(array(
					'group_name'			=> 'is_main_group',
					'group_lang_titles'		=> 'in_languages,'.implode("-",$languageslists).'',
				)); 
				// Get or set the filtering rules
				$PnValidator->filter_rules(array(
					'group_name'			=> 'sanitize_string|filter',
					'group_colour'			=> 'sanitize_string|filter',
				)); 

				$group_fields = $PnValidator->sanitize($group_fields, array('group_name', 'group_colour'), true, true);
				$validated_data = $PnValidator->run($group_fields);

				// validate submitted data
				if($validated_data !== FALSE)
				{
					$group_fields = $validated_data;
				}
				else
				{
					$pagetitle = ($mode == "new") ? _ADD_NEW_GROUP:_EDIT_GROUP;
					$hooks->add_filter("set_page_title", function() use($pagetitle){return array("users_groups_admin" => $pagetitle);});
					include("header.php");
					$html_output .= GraphicAdmin();
					$html_output .= users_menu();
					$html_output .= OpenAdminTable();
					$html_output .= '<p align=\"center\">'._ERROR_IN_OP.' :<br /><Br />'.$PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br /><br />')._GOBACK."</p>";
					$html_output .= CloseAdminTable();
					include("footer.php");
				}
									
				foreach($group_fields as $key => $value)
				{
					if(is_array($value) && !empty($value))
					{
						$value = addslashes(phpnuke_serialize($value));
					}
					
					$items[$key] = $value;
				}
				
				// save to db
				if($mode == "new")
				{
					$insert_result = $db->table(GROUPS_TABLE)
										->insert($items);

					$group_id = intval($db->lastInsertId());
					
					add_log(sprintf(_ADD_NEW_GROUP, $items['group_name']), 1);
				}
				
				if($mode == "edit")
				{
					$db->table(GROUPS_TABLE)
						->where("group_id", $group_id)
						->update($items);
										
					add_log(sprintf(_EDIT_GROUP, $items['group_name']), 1);
				}
				cache_system("nuke_groups");
				
				phpnuke_db_error();
				header("location: ".$admin_file.".php?op=users_groups");
				die();
			}
					
			$group_fields = array(
				"group_id"			=> ((intval($group_id) != 0) ?				$group_id:0),
				"group_type"		=> ((isset($row['group_type'])) ?			$row['group_type']:2),
				"group_name"		=> ((isset($row['group_name'])) ?			$row['group_name']:""),
				"group_lang_titles"	=> ((isset($row['group_lang_titles'])) ?	phpnuke_unserialize(stripslashes($row['group_lang_titles'])):array()),
				"group_options"		=> ((isset($row['group_options'])) ?		$row['group_options']:0),
				"group_colour"		=> ((isset($row['group_colour'])) ?			$row['group_colour']:"#000000"),
			);
			
			$contents ="
			<!-- MiniColors -->
			<script src=\"includes/Ajax/jquery/jquery.minicolors.min.js\"></script>
			<link rel=\"stylesheet\" href=\"includes/Ajax/jquery/jquery.minicolors.css\">
			<!-- MiniColors -->
			<script type=\"text/javascript\" src=\"includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>
			
			<form action=\"".$admin_file.".php\" method=\"post\" enctype=\"multipart/form-data\" id=\"groups_form\">
				<table width=\"100%\" class=\"id-form product-table no-border\">
					<tr>
						<th style=\"width:200px\">"._TITLE."</th>
						<td><input type=\"text\" size=\"40\" name=\"group_fields[group_name]\" id=\"title_field\" value=\"".$group_fields['group_name']."\" class=\"inp-form\" minlength=\"3\"  data-validation=\"required\" data-validation-error-msg=\""._TITLE_IS_REQUIRED."\" ".(($group_fields['group_type'] == 1) ? "disabled":"")." /></td>
					</tr>";
					
					if($nuke_configs['multilingual'] != 1)
					{
						$contents .= "
						<tr>
							<th style=\"width:160px;\">"._TITLE_INLANG.":</th>
							<td>
								<input class=\"inp-form\" type=\"text\" name=\"group_fields[group_lang_titles]\" size=\"30\" value=\"".$group_fields['group_lang_titles']."\">
							</td>
						</tr>";
					}
					else
					{
						foreach($languageslists as $languageslist)
						{
							if($languageslist != "")
							{
								if($languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
								$languageslist = str_replace(".php", "", $languageslist);
								$contents .= "
								<tr>
									<th style=\"width:160px;\">"._TITLE_INLANG." : ".ucfirst($languageslist)."</th>
									<td id=\"edit-block-lang_titles\">
										<input class=\"inp-form\" type=\"text\" name=\"group_fields[group_lang_titles][$languageslist]\" data-language=\"$languageslist\" size=\"30\" maxlength=\"60\" value=\"".(isset($group_fields['group_lang_titles'][$languageslist]) ? $group_fields['group_lang_titles'][$languageslist]:"")."\">
									</td>
								</tr>";
							}
						}
					}
					$contents .= "
					<tr>
						<th>"._GROUP_COLOR."</th>
						<td><input type=\"text\" name=\"group_fields[group_colour]\" size=\"37\" data-letterCase=\"uppercase\" value=\"".$group_fields['group_colour']."\" class=\"color-picker inp-form\" id=\"swatches-opacity\" class=\"demo\" data-opacity=\"1\" data-swatches=\"#fff|#000|#f00|#0f0|#00f|#ff0\" value=\"".$group_fields['group_colour']."\" /></td>					
					</tr>
					<tr>
						<td colspan=\"2\">
							<input type=\"submit\" name=\"submit\" value=\"submit\" class=\"form-submit\" />
						</td>					
					</tr>
				</table>
				<input type=\"hidden\" name=\"op\" value=\"users_groups_admin\" />
				<input type=\"hidden\" name=\"group_id\" value=\"$group_id\" />
				<input type=\"hidden\" name=\"mode\" value=\"$mode\" />
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>
			<script>
				$(document).ready(function(){
					$.validate({
						form : '#grousp_form',
						modules : 'security',
					});
				});
			</script>";
		}

		if(!empty($error))
			$contents = implode("<br />", $error);
		$contents .= jquery_codes_load();
		die($contents);
	}	
	
	function users_fields_admin($submit, $users_fields=array())
	{
		global $db, $hooks, $admin_file, $nuke_configs, $PnValidator, $module_name;
		
		$error = array();
		
		$result = $db->table(USERS_FIELDS_TABLE)
			->select();
		$rows = $result->results();
		$rows = phpnuke_array_change_key($rows, "", "fid");
		
		// submit edited data
		
		if(isset($submit) && isset($users_fields) && !empty($users_fields))
		{
			$deleted_fields = $added_fields = array();
			
			foreach($rows as $row)
			{
				$fid = $row['fid'];
				
				if(!isset($users_fields[$fid]))
				{
					unset($rows[$fid]);
					$deleted_fields[] = $fid;
				}
			}
			
			foreach($users_fields as $fid => $users_field)
			{
				if(!isset($rows[$fid]))
				{
					$users_field['fid'] = $fid;
					$added_fields[$fid] = $users_field;
				}
				
				if(isset($users_field['value']) && is_array($users_field['value']))
					$users_field['value'] = implode(",", $users_field['value']);
					
				$users_field['size'] = ($users_field['size'] != '') ? intval($users_field):0;
					
				$db->table(USERS_FIELDS_TABLE)
					->where('fid', $fid)
					->update($users_field);
			}
			
			if(!empty($deleted_fields))
			{
				$db->table(USERS_FIELDS_TABLE)
					->in('fid', $deleted_fields)
					->delete();
				$db->table(USERS_FIELDS_VALUES_TABLE)
					->in('fid', $deleted_fields)
					->delete();
			}
			
			if(!empty($added_fields))
			{
				foreach($added_fields as $added_field)
				{
					if(isset($added_field['value']) && is_array($added_field['value']))
						$added_field['value'] = implode(",", $added_field['value']);
					
					$added_field['size'] = ($added_field['size'] != '') ? intval($added_field):0;
					
					$db->table(USERS_FIELDS_TABLE)
						->insert($added_field);
				}
			}			
			
			phpnuke_db_error();
			header("location: ".$admin_file.".php?op=users_fields_admin");
			die();
		}
		
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= users_menu();
		
		$contents .= OpenAdminTable();
		$contents .="			
		<form action=\"".$admin_file.".php\" method=\"post\" enctype=\"multipart/form-data\" id=\"user_fileds_form\">
			<table width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<td align=\"center\">"._ADD_NEW_FIELD." <span class=\"add_field_icon add_field_button\" title=\""._ADD_NEW_FIELD."\"></td>
				</tr>
				<tr>
					<td>
						<div class=\"input_fields_wrap\">";
						$x = 1;
						if(intval($result->count()) > 0)
						{
							$key = 0;
							foreach($rows as $row)
							{
								if($row['name'] == '' || $row['display'] == '')
									continue;
								$key = $row['fid'];
								$text_sel = ($row['type'] == 'text') ? " selected":"";
								$select_sel = ($row['type'] == 'select') ? " selected":"";
								$radio_sel = ($row['type'] == 'radio') ? " selected":"";
								$checkbox_sel = ($row['type'] == 'checkbox') ? " selected":"";
								$textarea_sel = ($row['type'] == 'textarea') ? " selected":"";
								
								$need_sel = ($row['need'] == 1) ? " selected":"";
								$noneed_sel = ($row['need'] == 0) ? " selected":"";
								
								$act_sel = ($row['act'] == 1) ? " selected":"";
								$noact_sel = ($row['act'] == 0) ? " selected":"";
								
								$values_contents = '';
								if($row['value'] != '')
								{
									$row['value'] = explode(",", $row['value']);
									$row['value'] = array_filter($row['value']);
									if(!empty($row['value']))
										foreach($row['value'] as $val)
											$values_contents .= "<option value=\"$val\" selected>$val</option>\n";
								}
								
								
								$contents .="<div style=\"margin:3px 0;\">
									"._USER_FILED_NAME." <input type=\"text\" name=\"users_fields[$key][name]\" class=\"inp-form-ltr\" size=\"6\" value=\"".$row['name']."\"> &nbsp;
									"._USER_FILED_DISPLAY." <input type=\"text\" name=\"users_fields[$key][display]\" class=\"inp-form\" size=\"7\" value=\"".$row['display']."\"> &nbsp;
									"._USER_FILED_VALUE." <select class=\"styledselect-select tag-input\" name=\"users_fields[$key][value][]\" multiple=\"multiple\" style=\"width:130px\">$values_contents</select>&nbsp; 
									"._USER_FILED_TYPE." <select class=\"styledselect-select\" name=\"users_fields[$key][type]\" style=\"width:85px\">
										<option value=\"text\"$text_sel>text</option>
										<option value=\"select\"$select_sel>select</option>
										<option value=\"radio\"$radio_sel>radio</option>
										<option value=\"checkbox\"$checkbox_sel>checkbox</option>
										<option value=\"textarea\"$textarea_sel>textarea</option>
									</select>&nbsp;
									"._USER_FILED_SIZE." <input type=\"text\" name=\"users_fields[$key][size]\" size=\"4\" class=\"inp-form-ltr\" value=\"".$row['size']."\"> &nbsp; 
									"._USER_FILED_REQUIERD." <select class=\"styledselect-select\" name=\"users_fields[$key][need]\" style=\"width:60px\">>
										<option value=\"1\"$need_sel>"._YES."</option>
										<option value=\"0\"$noneed_sel>"._NO."</option>
									</select> &nbsp; 
									"._USER_FILED_ACTIVE." <select class=\"styledselect-select\" name=\"users_fields[$key][act]\" style=\"width:60px\">>
										<option value=\"1\"$act_sel>"._YES."</option>
										<option value=\"0\"$noact_sel>"._NO."</option>
									</select>&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a>
									</div>";
							}
							$x = $key+1;
						}
						$contents .="</div>
					</td>
				</tr>
				<tr>
					<td colspan=\"2\">
						<input type=\"submit\" name=\"submit\" value=\"submit\" class=\"form-submit\" />
					</td>					
				</tr>
			</table>
			<input type=\"hidden\" name=\"op\" value=\"users_fields_admin\" />
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>
		<script>
			$(document).ready(function(){
				$(\".input_fields_wrap\").add_field({ 
					addButton: $(\".add_field_button\"),
					remove_button: '.remove_field',
					fieldHTML: '<div style=\"margin:3px 0;\">"._USER_FILED_NAME." <input type=\"text\" name=\"users_fields[{X}][name]\" class=\"inp-form-ltr\" size=\"6\" value=\"\"> &nbsp; "._USER_FILED_DISPLAY." <input type=\"text\" name=\"users_fields[{X}][display]\" class=\"inp-form\" size=\"7\" value=\"\"> &nbsp; "._USER_FILED_VALUE." <select class=\"styledselect-select tag-input\" name=\"users_fields[{X}][value][]\" multiple=\"multiple\" style=\"width:130px\"></select>&nbsp; "._USER_FILED_TYPE." <select class=\"styledselect-select\" name=\"users_fields[{X}][type]\" style=\"width:85px\"><option value=\"text\">text</option><option value=\"select\">select</option><option value=\"radio\">radio</option><option value=\"checkbox\">checkbox</option><option value=\"textarea\">textarea</option></select> &nbsp; "._USER_FILED_SIZE." <input type=\"text\" name=\"users_fields[{X}][size]\" size=\"4\" class=\"inp-form-ltr\" value=\"\"> &nbsp; "._USER_FILED_REQUIERD." <select class=\"styledselect-select\" name=\"users_fields[{X}][need]\" style=\"width:60px\"><option value=\"1\">"._YES."</option><option value=\"0\">"._NO."</option></select> &nbsp; "._USER_FILED_ACTIVE." <select class=\"styledselect-select\" name=\"users_fields[{X}][act]\" style=\"width:60px\"><option value=\"1\">"._YES."</option><option value=\"0\">"._NO."</option></select>&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a></div>',
					x: $x,
				});
			});
		</script>";
		$contents .= CloseAdminTable();
		

		if(!empty($error))
			$contents = implode("<br />", $error);
			
		phpnuke_db_error();
		$hooks->add_filter("set_page_title", function() {return array("users_fields_admin" => _USERS_FIELDS_SETTING);});
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	global $pn_prefix;
	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$user_status = (isset($user_status)) ? filter($user_status):'';
	$order_by = (isset($order_by)) ? filter($order_by, "nohtml"):'';
	$sort = (isset($sort)) ? filter($sort, "nohtml"):'';
	$search_query = (isset($search_query)) ? filter($search_query, "nohtml"):'';
	$submit = (isset($submit)) ? filter($submit, "nohtml"):'';
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'new';
	$users_fields = request_var('users_fields', array(), '_POST');
	$group_fields = request_var('group_fields', array(), '_POST');
	$uploadfile	= request_var('uploadfile', array(), '_FILES');
	$user_id = (isset($user_id)) ? intval($user_id):0;
	$group_id = (isset($group_id)) ? intval($group_id):0;
	
	switch($op)
	{
		default:
		case"users":
			users($user_status, $search_query, $order_by, $sort);
		break;
		
		case"users_admin":
			users_admin($user_id, $mode, $submit, $users_fields, $uploadfile);
		break;
		
		case"users_groups":
			users_groups($search_query);
		break;
		
		case"users_groups_admin":
			users_groups_admin($group_id, $mode, $submit, $group_fields);
		break;
		
		case"users_fields_admin":
			users_fields_admin($submit, $users_fields);
		break;
	}
	
} else {
	include("header.php");
	GraphicAdmin();
	OpenAdminTable();
	echo "<div class=\"text-center\"><b>"._ERROR."</b><br><br>You do not have administration permission for module \"$module_name\"</div>";
	CloseAdminTable();
	include("footer.php");
}

?>