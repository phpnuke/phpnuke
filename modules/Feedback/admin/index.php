<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('ADMIN_FILE')) {
	die ("Access Denied");
}

if (check_admin_permission($module_name, false, true))
{

	function feedbacks_menu()
	{
		global  $admin_file, $nuke_configs, $module_name;
		$contents = "
		<link rel=\"stylesheet\" href=\"modules/$module_name/includes/feedback.css\" type=\"text/css\" media=\"screen\" />
		<div class=\"text-center\">[ <a href='".$admin_file.".php?op=feedbacks'>"._FEEDBACK_MESSAGES."</a> | <a href='".$admin_file.".php?op=feedbacks_config'>"._FEEDBACK_SETTINGS."</a> ]</div>\n";
		return $contents;
	}

	function feedbacks($mode, $fids, $search_query = '', $order_by = '', $sort='DESC')
	{
		global $db, $hooks, $admin_file, $nuke_configs;
		
		if($mode == "delete")
		{
			csrfProtector::authorisePost(true);
			$deleted_fids = array();
			
			if(is_array($fids) && !empty($fids))
				$deleted_fids = $fids;
			elseif(intval($fids) != 0)
				$deleted_fids = array($fids);
			
			if(!empty($deleted_fids))
			{
				$db->table(FEEDBACKS_TABLE)
					->in('fid', $deleted_fids)
					->delete();
			}
			header("location: ".$admin_file.".php?op=feedbacks");
			die($mode);
		}
		
		$feedback_configs = (isset($nuke_configs['feedbacks']) && $nuke_configs['feedbacks'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['feedbacks'])):array();
	
		$contents = '';
		
		$hooks->add_filter("set_page_title", function(){return array("feedbacks" => _FEEDBACKS_ADMIN);});
		
		$link_to_more = "";
		$where = array();
		$params = array();
		
		if(isset($search_query) && $search_query != '')
		{
			$params[":search_query"] = "%".rawurldecode($search_query)."%";
			$where[] = "subject LIKE :search_query OR message LIKE :search_query OR sender_name LIKE :search_query OR sender_email LIKE :search_query";
			$link_to_more .= "&search_query=".rawurlencode($search_query)."";
		}
		
		$where = array_filter($where);
		$where = (!empty($where)) ? "WHERE ".implode(" AND ", $where):'';

		$sort = ($sort != '' && in_array($sort, array("ASC","DESC"))) ? $sort:"DESC";
		$order_by = ($order_by != '' && in_array($order_by, array('fid', 'subject', 'added_time', 'sender_name', 'sender_email', 'responsibility'))) ? $order_by:'fid';
		$sort_reverse = ($sort == 'ASC') ? "DESC":"ASC";	
		
		$entries_per_page = 20;
		$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
		$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
		$link_to = "".$admin_file.".php?op=feedbacks".$link_to_more;

		$total_rows = 0;
		$result = $db->query("
			SELECT *, 
			(SELECT COUNT(fid) FROM ".FEEDBACKS_TABLE." $where) as total_rows
			FROM ".FEEDBACKS_TABLE."
			$where 
			ORDER BY $order_by $sort LIMIT $start_at, $entries_per_page
		", $params);		
		
		$contents .= GraphicAdmin();
		$contents .= feedbacks_menu();
		
		$contents .= OpenAdminTable();
		
		$contents .= "
			<table align=\"center\" class=\"product-table no-border no-hover\" width=\"100%\">
				<tr>
					<form action=\"".$admin_file.".php\" method=\"post\">
						<td>
							<input class=\"inp-form\" type=\"text\" name=\"search_query\" size=\"30\" value=\"$search_query\" />
							<input type=\"hidden\" value=\"feedbacks\" name=\"op\" />
							<input type=\"hidden\" name=\"order_by\" value=\"$order_by\" />
							<input type=\"hidden\" name=\"sort\" value=\"$sort\" />
							<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
							<input type=\"submit\" class=\"form-search\" value=\""._SEARCH."\" />
							".bubble_show(_FEEDBACK_SEARCH)."
						</td>
					</form>
				</tr>
			</table>
			<form action=\"".$admin_file.".php\" method=\"post\">
			<table align=\"center\" class=\"product-table\" width=\"100%\" id=\"feedback\">
			<thead>
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=feedbacks&order_by=fid&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'fid') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._ID."</a></th>
				<th class=\"table-header-repeat line-left\"><a href=\"".$admin_file.".php?op=feedbacks&order_by=subject&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'subject') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._SUBJECT."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a href=\"".$admin_file.".php?op=feedbacks&order_by=added_time&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'added_time') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._DATE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a href=\"".$admin_file.".php?op=feedbacks&order_by=sender_name&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'sender_name') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._SENDERNAME."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=feedbacks&order_by=sender_email&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'sender_email') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._EMAIL."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=feedbacks&order_by=responsibility&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'responsibility') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._RECIVER."</a></th>
				<th class=\"table-header-repeat line-left no-padding\" style=\"text-align:center;width:120px;\"><input data-label=\""._OPERATION."\" type=\"checkbox\" class=\"styled select-all\" data-element=\"#feedback\"></th>
			</tr>
			</thead>
			<tbody>";
			if(!empty($result))
			{
				foreach($result as $row)
				{
					$total_rows = intval($row['total_rows']);
					$fid = intval($row['fid']);
					$subject = filter($row['subject'], "nohtml");
					$sender_name = filter($row['sender_name'], "nohtml");
					$sender_email = filter($row['sender_email'], "nohtml");
					$added_time = nuketimes($row['added_time'], false, false, false, 1);
					$responsibility = intval($row['responsibility']);
					$custom_fields = phpnuke_unserialize(stripslashes($row['custom_fields']));

					$responsibility = ($responsibility != 0 && isset($feedback_configs['depts'][$responsibility]['name'])) ? $feedback_configs['depts'][$responsibility]['name']:"";
					
					$contents .= "<tr>
						<td>$fid</td>
						<td>$subject</td>
						<td align=\"center\">$added_time</td>
						<td align=\"center\">$sender_name</td>
						<td align=\"center\">$sender_email</td>
						<td align=\"center\">$responsibility</td>
						<td align=\"center\">
							<a href=\"".$admin_file.".php?op=feedbacks&mode=delete&fids=$fid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._DELETE_THIS_SURE."');\"></a>
							<a href=\"#\" title=\""._VIEW_AND_REPLY."\" class=\"table-icon icon-15 info-tooltip show_results\" data-fid=\"$fid\"></a>
							 <input type=\"checkbox\" class=\"styled\" name=\"fids[]\" value=\"$fid\" />
						</td>
						</tr>";
				}
			}
			$contents .= "
				<tr>
					<td colspan=\"7\" align=\"center\"><input type=\"submit\" value=\""._DELETE_SELECTED."\" onclick=\"return confirm('"._DELETE_ALL_THIS_SURE."');\" /></td>
				</tr>
			</tbody>
		</table>
		<input type=\"hidden\" value=\"delete\" name=\"mode\" />
		<input type=\"hidden\" value=\"feedbacks\" name=\"op\" />
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>";
		if($total_rows > 0)
		{
			$contents .= "<div id=\"pagination\" class=\"pagination\">";
			$contents .= admin_pagination($total_rows, $entries_per_page, $current_page, $link_to);
			$contents .= "</div>";
		}
		else
			$contents .= "<p align=\"center\">"._NO_FEEDBACK_FOUND."</p>";
		
		$contents .= "<div id=\"feedbacks-dialog\"></div>
		<script>

			$(\".show_results\").click(function(e)
			{
				e.preventDefault();
				var fid = $(this).data('fid');
				$.ajax({
					type : 'post',
					url : '".$admin_file.".php',
					data : {'op' : 'reply_feedback_pm', 'fid' : fid, 'csrf_token' : pn_csrf_token},
					success : function(responseText){
						$(\"#feedbacks-dialog\").html(responseText);

						$(\"#feedbacks-dialog\").dialog({
							title: '"._VIEW_AND_REPLY."',
							resizable: false,
							height: 500,
							width: 800,
							modal: true,
							closeOnEscape: true,
							close: function(event, ui)
							{
								$(this).dialog('destroy');
								$(\"#feedbacks-dialog\").html('');
							}
						});
					}
				});
			});
		</script>";

		$contents .= CloseAdminTable();
		phpnuke_db_error();
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function reply_feedback_pm($fid, $submit = '', $feedback_reply_message = '', $inline=0)
	{
		global $db, $admin_file, $nuke_configs, $module_name, $aid, $userinfo, $visitor_ip;
		
		$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
		
		$row = $db->table(FEEDBACKS_TABLE)
					->where('fid', $fid)
					->first();
		$replys = ($row['replys'] != '') ? phpnuke_unserialize(stripslashes($row['replys'])):array();
		$custom_fields = ($row['custom_fields'] != '') ? phpnuke_unserialize(stripslashes($row['custom_fields'])):"";
		
		$feedback_configs = (isset($nuke_configs['feedbacks']) && $nuke_configs['feedbacks'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['feedbacks'])):array();
		
		$feedback_custom_fields = (isset($feedback_configs['custom_fields']) && !empty($feedback_configs['custom_fields'])) ? $feedback_configs['custom_fields']:array();
		$feedback_depts = (isset($feedback_configs['depts']) && !empty($feedback_configs['depts'])) ? $feedback_configs['depts']:array();
	
		if(isset($submit) && $submit != "" && isset($feedback_reply_message) && $feedback_reply_message != '')
		{
			$sender_name		= isset($row['sender_name']) ? $row['sender_name']:$aid;
			$sender_email		= isset($row['sender_email']) ? $row['sender_email']:$nuke_authors_cacheData[$aid]['email'];
			$nowtime = _NOWTIME;
			$feedback_reply_message = str_replace("\n","<br />", $feedback_reply_message);
			$subject = ""._ADMIN_REPLY_ABOUT_SUBJECT." : ".$row['subject']."";
			
			$replys[] = array("aid" => $sender_name, "email" => $sender_email, "time" => $nowtime, "message" => addslashes($feedback_reply_message));
			$replys2 = addslashes(phpnuke_serialize($replys));
			
			$update_result = $db->table(FEEDBACKS_TABLE)
								->where('fid', $fid)
								->update([
									'replys' => $replys2
								]);
			if($update_result)
			{				
				$message = array(
					"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "reply_pm.txt"),
					"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
					"sender_name" => $sender_name,
					"message" => $row['message'], 
					"feedback_reply_message" => $feedback_reply_message
				);				
				
				if(phpnuke_mail($sender_email, $subject, $message))
				{
					$response = json_encode(array(
						"status" => "success",
						"sender_name" => "$sender_name",
						"time" => nuketimes($nowtime, true, true, false, 1),
						"direction" => _TEXTALIGN1,
						"message" => $feedback_reply_message
					));
				}
				else
				{
					$response = json_encode(array(
						"status" => "error",
						"message" => _ERROR_IN_EMAIL
					));
				}
			}
			else
			{
				$response = json_encode(array(
					"status" => "error",
					"message" => ""._ERROR_IN_OP." : ".$db->getErrors('last')['message'].""
				));
			}
			die($response);
		}
		
		$contents ="
		<script src=\"modules/$module_name/includes/feedback.js\"></script>
		<div id=\"feedback-history\">
				<div class=\"container clearfix\">
					<div class=\"chat\">
						<div class=\"chat-header clearfix\">
							<img src=\"images/avatar-s.png\" class=\""._TEXTALIGN1."\" alt=\"avatar\" />

							<div class=\"chat-about\" dir=\""._DIRECTION."\">
								<div class=\"chat-with\">"._MESSAGE_SENT_BY." ".filter($row['sender_name'], "nohtml")."</div>
								<div class=\"chat-sub-with\">
								".filter($row['sender_email'], "nohtml")."";
								if($custom_fields != '' && !empty($custom_fields) && is_array($feedback_custom_fields) && !empty($feedback_custom_fields))
								{
									foreach($custom_fields as $key => $val)
									{
										foreach($feedback_custom_fields as $ckey => $cval)
										{
											if($cval['name'] == $key)
											{
												$contents .= "<br />".$cval['title']." : ".$val;
												break;
											}
										}
									}
								}
								$contents .="</div>
							</div>
						</div> <!-- end chat-header -->

						<div class=\"chat-history\">
							<ul>
								<li class=\"clearfix\">
									<div class=\"message-data align-right\">
										<span class=\"message-data-name\" >".filter($row['sender_name'], "nohtml")."</span>
										<span class=\"message-data-time\" >".nuketimes($row['added_time'], true, true, false, 1)."</span>
										<span class=\"clearfix\"></span>
									</div>
									<div class=\"message other-message float-right align-"._TEXTALIGN1."\">
										".(($inline != 0) ? "<br /><Br />":"").stripslashes($row['message'])."
									</div>
								</li>";
								if($replys != '' && $inline == 0)
								{
								foreach($replys as $reply)
								{
								$contents .="<li>
									<div class=\"message-data\">
										<span class=\"message-data-name\">".$reply['aid']."</span>
										<span class=\"message-data-time\">".nuketimes($reply['time'], true, true, false, 1)."</span>
										<span class=\"clearfix\"></span>
									</div>
									<div class=\"message my-message align-"._TEXTALIGN1."\">
										".stripslashes($reply['message'])."
									</div>
								</li>";
								}
								}
							$contents.="</ul>        
						</div>";
						if($inline == 0)
						{
						$contents.="<div class=\"chat-message clearfix\">
							<textarea name=\"message\" dir=\""._DIRECTION."\" id=\"feedback_reply_message\" placeholder =\""._ENTER_YOUR_REPLY."\" rows=\"3\"></textarea>
							<button id=\"reply_feedback_submit\" data-adminfile=\"".$admin_file."\" data-fid=\"".$fid."\">"._REPLY_SENT."</button>
						</div>";
						}
					$contents.="</div>   
				</div>
			</div>";
		die($contents);
	}

	function feedbacks_config()
	{
		global $db, $hooks, $admin_file, $nuke_configs, $module_name;
		
		$feedback_configs = (isset($nuke_configs['feedbacks']) && $nuke_configs['feedbacks'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['feedbacks'])):array(
			'letreceive' => 1,
			'delay' => 600,
			'notify' => array(
				'sms' => 1
			),
			'description' => '',
			'phone' => '',
			'mobile' => '',
			'fax' => '',
			'address' => '',
			'meta_description' => '',
			'meta_keywords' => '',
			'map_active' => 1,
			'google_api' => '',
			'map_position' => '36.28795445718431,59.61575198173523',
		);
		
		$contents = '';
		
		$hooks->add_filter("set_page_title", function(){return array("feedbacks_config" => _FEEDBACKS_ADMIN." "._SETTINGS);});
		
		$letreceive_checked1 = ($feedback_configs['letreceive'] == 1) ? "checked":"";
		$letreceive_checked2 = ($feedback_configs['letreceive'] == 0) ? "checked":"";
		
		$sms_checked1 = ($feedback_configs['notify']['sms'] == 1) ? "checked":"";
		$sms_checked2 = ($feedback_configs['notify']['sms'] == 0) ? "checked":"";

		$map_active_checked1 = ($feedback_configs['map_active'] == 1) ? "checked":"";
		$map_active_checked2 = ($feedback_configs['map_active'] == 0) ? "checked":"";
		
		$map_position = ($feedback_configs['map_position'] != "") ? explode(",", $feedback_configs['map_position']):array('36.28795445718431','59.61575198173523');
						
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= feedbacks_menu();
		$contents .= OpenAdminTable();
		if(isset($feedback_configs['google_api']) && $feedback_configs['google_api'] != '')
		{
			$contents .= "<script type=\"text/javascript\" src=\"http://maps.googleapis.com/maps/api/js?sensor=false&language=fa&v=3&key=".$feedback_configs['google_api']."\"></script>";
		}
		$contents .= "<script type=\"text/javascript\" src=\"includes/Ajax/jquery/jquery.load-map.js\"></script>
		<form action=\"".$admin_file.".php\" method=\"post\" id=\"feedback_form\">
			<table width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<th>"._FEEDBACK_CUSTOM_FIELDS." <span class=\"add_field_icon add_field_button\" title=\""._ADD_NEW_FIELD."\"></span></th>
					<td>
						<div class=\"input_fields_wrap\">";
						$x1 = 1;
						if(isset($feedback_configs['custom_fields']) && !empty($feedback_configs['custom_fields']))
						{
							foreach($feedback_configs['custom_fields'] as $x1 => $custom_fields_data)
							{
								$custom_fields_data2 = array_filter($custom_fields_data);
								if(empty($custom_fields_data2))
									continue;
									
								$option_name = $custom_fields_data['name'];
								$option_title = $custom_fields_data['title'];
								$option_desc = $custom_fields_data['desc'];
								$option_data_msg = $custom_fields_data['data-msg'];
								
								$option_required_check1 = (intval($custom_fields_data['required']) == 1) ? "selected":"";
								$option_required_check2 = (intval($custom_fields_data['required']) == 0) ? "selected":"";
								
								$option_data_rule_check1 = ($custom_fields_data['data-rule'] == "number") ? "selected":"";
								$option_data_rule_check2 = ($custom_fields_data['data-rule'] == "string") ? "selected":"";
								$contents .= "
								<div style=\"margin-bottom:3px;\">
									<input placeholder=\""._LATIN_NAME."\" type=\"text\" class=\"inp-form-ltr\" value=\"$option_name\" name=\"config_fields[feedbacks][custom_fields][$x1][name]\" size=\"10\" />&nbsp;<input placeholder=\""._TITLE."\" type=\"text\" class=\"inp-form\" value=\"$option_title\" name=\"config_fields[feedbacks][custom_fields][$x1][title]\" size=\"10\" />&nbsp;<input placeholder=\""._DESCRIPTIONS."\" type=\"text\" class=\"inp-form\" value=\"$option_desc\" name=\"config_fields[feedbacks][custom_fields][$x1][desc]\" size=\"10\" />&nbsp;<select class=\"styledselect-select\" name=\"config_fields[feedbacks][custom_fields][$x1][required]\" style=\"width:100px;\"><option value=\"1\" $option_required_check1>"._REQUIRED."</option><option value=\"0\" $option_required_check2>"._NOT_REQUIRED."</option></select><select class=\"styledselect-select\" name=\"config_fields[feedbacks][custom_fields][$x1][data-rule]\" style=\"width:100px;\"><option value=\"number\" $option_data_rule_check1>"._NUMERIC."</option><option value=\"string\" $option_data_rule_check2>"._STRING."</option></select>&nbsp;<input placeholder=\""._FEEDBACK_ERROR_MESSAGE."\" type=\"text\" class=\"inp-form\" value=\"$option_data_msg\" name=\"config_fields[feedbacks][custom_fields][$x1][data-msg]\" size=\"10\" />&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a>
								</div>";
							}
						}
						$contents .= "
						</div>
					</td>
				</tr>
				<tr>
					<th>"._RESPONSIBLES." <span class=\"add_field_icon add_dept_field_button\" title=\""._ADD_NEW_RESPONSIBLE."\"></span></th>
					<td>
						<div class=\"input_dept_fields_wrap\">";
						$x2 = 1;
						if(isset($feedback_configs['depts']) && !empty($feedback_configs['depts']))
						{
							foreach($feedback_configs['depts'] as $x2 => $depts_data)
							{
								$depts_data2 = array_filter($depts_data);
								if(empty($depts_data2))
									continue;
									
								$option_name = $depts_data['name'];
								$option_responsibility = $depts_data['responsibility'];
								$option_email = $depts_data['email'];
								$option_mobile = $depts_data['mobile'];
								$contents .= "
								<div style=\"margin-bottom:3px;\">
									<input placeholder=\""._NAME_FAMILY."\" type=\"text\" class=\"inp-form\" value=\"$option_name\" name=\"config_fields[feedbacks][depts][$x2][name]\" />&nbsp;<input placeholder=\""._RESPONSIBILITY."\" type=\"text\" class=\"inp-form\" value=\"$option_responsibility\" name=\"config_fields[feedbacks][depts][$x2][responsibility]\" />&nbsp;&nbsp;<input placeholder=\""._EMAIL."\" type=\"text\" class=\"inp-form\" value=\"$option_email\" name=\"config_fields[feedbacks][depts][$x2][email]\" />&nbsp;&nbsp;<input placeholder=\""._MOBILE_PHONE."\" type=\"text\" class=\"inp-form\" value=\"$option_mobile\" name=\"config_fields[feedbacks][depts][$x2][mobile]\" />&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a>
								</div>";
							}
						}
						$contents .= "
						</div>
					</td>
				</tr>
				<tr>
					<th>"._LETSENDRECEIVE."</th>
					<td>
						<input type=\"radio\" name=\"config_fields[feedbacks][letreceive]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $letreceive_checked1 />
						<input type=\"radio\" name=\"config_fields[feedbacks][letreceive]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $letreceive_checked2 />
					</td>					
				</tr>
				<tr>
					<th>"._FEEDBACK_DELAY."</th>
					<td>
						<input type=\"text\" name=\"config_fields[feedbacks][delay]\" value=\"".$feedback_configs['delay']."\" class=\"inp-form-ltr\" />".bubble_show(""._ENTER_TIME_IN_SECONDS."")."
					</td>					
				</tr>
				<tr>
					<th>"._FEEDBACK_SEND_SMS."</th>
					<td>
						<input type=\"radio\" name=\"config_fields[feedbacks][notify][sms]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $sms_checked1 />
						<input type=\"radio\" name=\"config_fields[feedbacks][notify][sms]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $sms_checked2 />
					</td>
				</tr>
				<tr>
					<th>"._DESCRIPTIONS."</th>
					<td>
						".wysiwyg_textarea("config_fields[feedbacks][description]", $feedback_configs['description'], "Basic", "50", "12", "500px", "150px")."
					</td>					
				</tr>
				<tr>
					<th>"._LANDLINE_PHONE."</th>
					<td>
						<input type=\"text\" name=\"config_fields[feedbacks][phone]\" value=\"".$feedback_configs['phone']."\" class=\"inp-form-ltr\" />
					</td>					
				</tr>
				<tr>
					<th>"._MOBILE_PHONE."</th>
					<td>
						<input type=\"text\" name=\"config_fields[feedbacks][mobile]\" value=\"".$feedback_configs['mobile']."\" class=\"inp-form-ltr\" />
					</td>					
				</tr>
				<tr>
					<th>"._FAX."</th>
					<td>
						<input type=\"text\" name=\"config_fields[feedbacks][fax]\" value=\"".$feedback_configs['fax']."\" class=\"inp-form-ltr\" />
					</td>					
				</tr>
				<tr>
					<th>"._ADDRESS."</th>
					<td>
						<textarea class=\"form-textarea\" name='config_fields[feedbacks][address]' cols='70' rows='5'>".$feedback_configs['address']."</textarea>
					</td>
				</tr>
				<tr>
					<th>"._FEEDBACK_HEADER_DESCRIPTION."</th>
					<td>
						<textarea class=\"form-textarea\" name='config_fields[feedbacks][meta_description]' cols='70' rows='5'>".$feedback_configs['meta_description']."</textarea>
					</td>
				</tr>
				<tr>
					<th>"._KEYWORDS."</th>
					<td>
						<select class=\"styledselect-select tag-input\" name=\"config_fields[feedbacks][meta_keywords][]\" multiple=\"multiple\" style=\"width:100%\">";
						if(isset($feedback_configs['meta_keywords']) && !empty($feedback_configs['meta_keywords']))
						{
							foreach($feedback_configs['meta_keywords'] as $feedback_keyword)
								$contents .= "<option value=\"$feedback_keyword\" selected>$feedback_keyword</option>\n";
						}
						$contents .= "</select>
					</td>
				</tr>
				<tr>
					<th>"._ENABLE_MAP."</th>
					<td><input type=\"radio\" name=\"config_fields[feedbacks][map_active]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $map_active_checked1 /><input type=\"radio\" name=\"config_fields[feedbacks][map_active]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $map_active_checked2 /></td>					
				</tr>
				<tr>
					<th>google API</th>
					<td>
						<input type=\"text\" name=\"config_fields[feedbacks][google_api]\" value=\"".$feedback_configs['google_api']."\" class=\"inp-form-ltr\" />
					</td>					
				</tr>
				<tr>
					<th>"._POSITION_IN_GOOGLEMAP."</th>
					<td>";
						$contents .= "<div id=\"map_canvas\" style=\"width: 550px; height: 300px;\"></div>
						<input type=\"hidden\" name=\"config_fields[feedbacks][map_position]\" id=\"feedback_configs_position\" value=\"$map_position[0],$map_position[1]\">
					</td>
				</tr>
				<tr>
					<td colspan=\"2\">
						<input type=\"submit\" name=\"submit\" value=\"submit\" class=\"form-submit\" />
					</td>					
				</tr>";
			$contents .= "
			</table>
			<input type=\"hidden\" name=\"op\" value=\"save_configs\" />
			<input type=\"hidden\" name=\"return_op\" value=\"feedbacks_config\" />
			<input type=\"hidden\" name=\"log_message\" value=\""._FEEDBACK_SETTINGS_LOG."\" />
			<input type=\"hidden\" name=\"array_level[]\" value=\"feedbacks\" />
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
		</form>
		<script>
			$(document).ready(function(){
				
				$(\".input_fields_wrap\").add_field({ 
					addButton: $(\".add_field_button\"),
					remove_button: '.remove_field',
					fieldHTML: '<div style=\"margin-bottom:3px;\"><input placeholder=\""._LATIN_NAME."\" type=\"text\" class=\"inp-form-ltr\" value=\"\" name=\"config_fields[feedbacks][custom_fields][{X}][name]\" size=\"10\" />&nbsp;<input placeholder=\""._SUBJECT."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"config_fields[feedbacks][custom_fields][{X}][title]\" size=\"10\" />&nbsp;<input placeholder=\""._DESCRIPTIONS."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"config_fields[feedbacks][custom_fields][{X}][desc]\" size=\"10\" />&nbsp;<select class=\"styledselect-select\" name=\"config_fields[feedbacks][custom_fields][{X}][required]\" style=\"width:100px;\"><option value=\"1\">"._REQUIRED."</option><option value=\"0\">"._NOT_REQUIRED."</option></select><select class=\"styledselect-select\" name=\"config_fields[feedbacks][custom_fields][{X}][data-rule]\" style=\"width:100px;\"><option value=\"number\">"._NUMERIC."</option><option value=\"string\">"._STRING."</option></select>&nbsp;<input placeholder=\""._FEEDBACK_ERROR_MESSAGE."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"config_fields[feedbacks][custom_fields][{X}][data-msg]\" size=\"10\" />&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a></div>',
					x: ".($x1+1).",
				});
				
				$(\".input_dept_fields_wrap\").add_field({ 
					addButton: $(\".add_dept_field_button\"),
					remove_button: '.remove_field',
					fieldHTML: '<div style=\"margin-bottom:3px;\"><input placeholder=\""._NAME_FAMILY."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"config_fields[feedbacks][depts][{X}][name]\" />&nbsp;<input placeholder=\""._RESPONSIBILITY."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"config_fields[feedbacks][depts][{X}][responsibility]\" />&nbsp;&nbsp;<input placeholder=\""._EMAIL."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"config_fields[feedbacks][depts][{X}][email]\" />&nbsp;&nbsp;<input placeholder=\""._MOBILE_PHONE."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"config_fields[feedbacks][depts][{X}][mobile]\" />&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a></div>',
					x: ".($x2+1).",
				});";
				if(isset($feedback_configs['google_api']) && $feedback_configs['google_api'] != '')
				{
					$contents .= "
					$(\"#map_canvas\").load_map({
						marker: {
							initLat: $map_position[0],
							initLng: $map_position[1],
							input_dragend: \"feedback_configs_position\",
						}
					});";
				}
			$contents .="
			});
		</script>";
		$contents .= CloseAdminTable();
		
		phpnuke_db_error();
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$inline = (isset($inline)) ? intval($inline):0;
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'';
	$fids = (isset($fids)) ? $fids:'';
	$order_by = (isset($order_by)) ? filter($order_by, "nohtml"):'';
	$sort = (isset($sort)) ? filter($sort, "nohtml"):'';
	$search_query = (isset($search_query)) ? filter($search_query, "nohtml"):'';
	$submit = filter(request_var('submit', '', '_POST'), "nohtml");
	$feedback_reply_message = stripslashes(request_var('feedback_reply_message', '', '_POST'));
	$fid = (isset($fid)) ? intval($fid):0;
	
	switch($op) {
		default:
			feedbacks($mode, $fids, $search_query, $order_by, $sort);
		break;

		case "feedbacks_config":
			feedbacks_config();
		break;

		case "reply_feedback_pm":
			reply_feedback_pm($fid, $submit, $feedback_reply_message, $inline);
		break;
	}

}
else
{
  include("header.php");
  GraphicAdmin();
  OpenAdminTable();
  echo "<div class=\"text-center\"><b>"._ERROR."</b><br><br>You do not have administration permission for module \"$module_name\"</div>";
  CloseAdminTable();
  include("footer.php");
}

?>