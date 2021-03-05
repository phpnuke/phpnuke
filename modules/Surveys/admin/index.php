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
	/* Poll/Surveys Functions                                */
	/*********************************************************/

	function surveys_menu()
	{
		global $db, $admin_file, $nuke_configs;
		$contents = "
		<link rel='stylesheet' href='includes/Ajax/jquery/meter-progressbar.css'>
		<p align=\"center\" style=\"padding:20px; 0;\">[ 
			<a href=\"".$admin_file.".php?op=surveys_admin\">"._ADD_NEW_POLL."</a> | 
			<a href=\"".$admin_file.".php?op=surveys\">"._SHOW_ALL."</a> | 
			<a href=\"".$admin_file.".php?op=surveys&status=1\">"._ACTIVE_POLLS."</a> | 
			<a href=\"".$admin_file.".php?op=surveys&status=0\">"._INACTIVE_POLLS."</a> | 
			<a href=\"".$admin_file.".php?op=surveys&status=2\">"._FUTURE_POLLS."</a>
		]</p>";
		return $contents;
	}
		
	function surveys($status='', $search_query = '', $order_by = '', $sort='DESC')
	{
		global $db, $hooks, $admin_file, $nuke_configs;

		$nuke_surveys_cacheData = change_poll_status();
		
		$contents = '';
		
		switch($status)
		{
			case"0":
				$post_status = _INACTIVE;
			break;
			case"1":
				$post_status = _ACTIVE;
			break;
			case"2":
				$post_status = _PUBLISH_IN_FUTURE;
			break;
			default:
				$post_status = "";
			break;
		}
		
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("surveys" => $pagetitle);});
		
		$link_to_more = "";
		$publish_now = "";
		$where = array();
		$params = array();
		
		if($status !== '')
		{
			$link_to_more .= "&status=$status";
			$where[] = "status = ':status'";
			$params[":status"] = $status;
			if($status == 0)
				$publish_now = "<a href=\"".$admin_file.".php?op=surveys_admin&mode=publish_now&pollID={POLLID}&csrf_token="._PN_CSRF_TOKEN."\" title=\""._PUBLISH."\" class=\"table-icon icon-5 info-tooltip\"></a>";
		}
		
		if(isset($search_query) && $search_query != '')
		{
			$where[] = "pollTitle LIKE :search_query";
			$params[":search_query"] = "%".rawurldecode($search_query)."%";
			$link_to_more .= "&search_query=".rawurlencode($search_query)."";
		}
		
		$where = array_filter($where);
		$params = array_filter($params);
		$where = (!empty($where)) ? "WHERE ".implode(" AND ", $where):'';

		$sort = ($sort != '' && in_array($sort, array("ASC","DESC"))) ? $sort:"DESC";
		$order_by = ($order_by != '' && in_array($order_by, array('pollID', 'pollTitle', 'start_time', 'end_time', 'comments', 'voters'))) ? $order_by:'pollID';
		$sort_reverse = ($sort == 'ASC') ? "DESC":"ASC";	
		
		$entries_per_page = 20;
		$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
		$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
		$link_to = "".$admin_file.".php?op=surveys".$link_to_more;

		$total_rows = 0;
		$result = $db->query("
			SELECT *, 
			(SELECT COUNT(pollID) FROM ".SURVEYS_TABLE." $where) as total_rows
			FROM ".SURVEYS_TABLE."
			$where 
			ORDER BY main_survey DESC, canVote DESC, $order_by $sort LIMIT $start_at, $entries_per_page
		", $params);
		
		$contents .= GraphicAdmin();
		$contents .= surveys_menu();
		
		$contents .= OpenAdminTable();
		$contents .= "
			<table align=\"center\" class=\"product-table no-border no-hover\" width=\"100%\">
				<tr>
					<form action=\"".$admin_file.".php\" method=\"post\">
						<td>
							"._SEARCH_IN_POLLS."
							<input class=\"inp-form\" type=\"text\" name=\"search_query\" size=\"30\" value=\"$search_query\" />
							<input type=\"hidden\" value=\"surveys\" name=\"op\" />
							<input type=\"hidden\" name=\"order_by\" value=\"$order_by\" />
							<input type=\"hidden\" name=\"status\" value=\"$status\" />
							<input type=\"hidden\" name=\"sort\" value=\"$sort\" />
							<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
							<input type=\"submit\" class=\"form-search\" value=\""._SEARCH."\" /></p>
						</td>
					</form>
				</tr>
			</table>
			<table align=\"center\" class=\"product-table\" width=\"100%\">
			<thead>
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=surveys&order_by=pollID&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'pollID') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._ID."</a></th>
				<th class=\"table-header-repeat line-left\"><a href=\"".$admin_file.".php?op=surveys&order_by=pollTitle&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'pollTitle') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._TITLE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a href=\"".$admin_file.".php?op=surveys&order_by=start_time&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'start_time') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._PUBLISH_DATE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a href=\"".$admin_file.".php?op=surveys&order_by=end_time&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'end_time') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._EXPIRE_DATE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=surveys&order_by=comments&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'comments') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._COMMENTS."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=surveys&order_by=voters&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'voters') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._VOTES."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\">"._BY."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:140px;\">"._OPERATION."</th>
			</tr>
			</thead>
			<tbody>";
			if(!empty($result))
			{
				foreach($result as $row)
				{
					$total_rows = intval($row['total_rows']);
					$pollID = intval($row['pollID']);
					$comments = intval($row['comments']);
					$canVote = intval($row['canVote']);
					$pollTitle = filter($row['pollTitle'], "nohtml");
					$pollUrl = filter($row['pollUrl'], "nohtml");
					$start_time = nuketimes($row['start_time'], false, false, false, 1);
					$end_time = ($row['end_time'] != '') ? nuketimes($row['end_time'], false, false, false, 1):_UNLIMITED;
					$auther_id = filter($row['aid'], "nohtml");
					$voters = intval($row['voters']);
					$this_status = filter($row['status'], "nohtml");
					$options = phpnuke_unserialize(stripslashes($row['options']));
					$options = json_encode($options);

					$options = str_replace('"', "'", $options);
					$survey_link = surveys_link($pollID, $pollTitle, $pollUrl);
					
					switch($this_status)
					{
						case"0":
							$this_post_status = " ("._INACTIVE.")";
						break;
						case"1":
							$this_post_status = "";
						break;
						case"2":
							$this_post_status = " ("._PUBLISH_IN_FUTURE.")";
						break;
						default:
							$this_post_status = "";
						break;
					}
					
					$this_post_canVote = ($canVote == 1) ? "":_DISABLED_VOTTING;
			
					$contents .= "<tr>
						<td>$pollID</td>
						<td><a href=\"".$survey_link[0]."\" target=\"_blank\">$pollTitle</a>$this_post_status</td>
						<td align=\"center\">$start_time</td>
						<td align=\"center\">$end_time</td>
						<td align=\"center\">$comments</td>
						<td align=\"center\">$voters</td>
						<td align=\"center\">$auther_id</td>
						<td align=\"center\">
							<a href=\"".$admin_file.".php?op=surveys_admin&mode=edit&pollID=$pollID\" title=\""._EDIT."\" class=\"table-icon icon-1 info-tooltip\"></a>
							<a href=\"".$admin_file.".php?op=surveys_admin&mode=delete&pollID=$pollID&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._DELETE_THIS_SURE."');\"></a>
							".str_replace("{POLLID}",$pollID, $publish_now)."";
							if($this_status == 2)
								$contents .= "<a href=\"".$admin_file.".php?op=surveys_admin&mode=publish_now&pollID=$pollID&csrf_token="._PN_CSRF_TOKEN."\" title=\""._IMMEDIATE_PUBLISH."\" class=\"table-icon icon-5 info-tooltip\"></a>";
							$contents .= "<a href=\"#\" title=\""._RESULTS."\" class=\"table-icon icon-15 info-tooltip show_results\" data-polltitle=\"$pollTitle\" data-voters=\"$voters\" data-options=\"$options\"></a>
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
			$contents .= "<p align=\"center\">"._NO_POLL_FOUND."</p>";
		
		$contents .= "<div id=\"surveys-dialog\"></div>
		<script>
			$(\".show_results\").click(function(e)
			{
				e.preventDefault();
				var voters = $(this).data('voters');
				var pollTitle = $(this).data('polltitle');
				var options = $(this).data('options');
				options = options.replace(/'/g, '\"');
				options = $.parseJSON(options);
				var html_output = '';
				var percent = 0;
				$.each(options, function( index, value ) {
					percent = Math.round((parseInt(value[1])/voters)*100);
					html_output += '<div class=\"meter animate\"><div class=\"meter-info\">'+value[0]+' <b>'+percent+'%</b> ('+value[1]+' "._VOTE.")</div><span style=\"width: '+percent+'%;\"><span></span></span></div>';
				});
				$(\"#surveys-dialog\").html(html_output);
				$(\"#surveys-dialog\").dialog({
					title: '"._POLL_RESULT." '+ pollTitle,
					resizable: false,
					height: 400,
					width: 500,
					modal: true,
					closeOnEscape: true,
					close: function(event, ui)
					{
						$(this).dialog('destroy');
						$(\"#surveys-dialog\").html('');
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
	
	function surveys_admin($pollID = 0, $mode = "new", $submit, $surveys_fields=array())
	{
		global $db, $aid, $visitor_ip, $hooks, $admin_file, $nuke_configs, $PnValidator, $module_name;
		
		$nuke_surveys_cacheData = change_poll_status();
		
		$pollID = intval($pollID);
		
		$article_status = 1;
		$old_options = array();
		// determine radminsuper & article_aid & article_status
		if($mode == "delete" || $mode == 'edit' || $mode == 'publish_now')
		{
			$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
		
			$radminsuper = intval($nuke_authors_cacheData[$aid]['radminsuper']);
			$radminsurvey = ($aid == $nuke_surveys_cacheData[$pollID]['aid']) ? true:false;
			$result2 = $db->table(SURVEYS_TABLE)
							->where('pollID', $pollID)
							->first(['aid as survey_aid','status as survey_status','pollTitle as old_pollTitle','options as old_options',]);
			
			$survey_aid = filter($result2['survey_aid'], "nohtml");
			$survey_status = filter($result2['survey_status'], "nohtml");
			$old_pollTitle = filter($result2['old_pollTitle'], "nohtml");
			$old_options = ($result2['old_options'] != '') ? phpnuke_unserialize(stripslashes($result2['old_options'])):array();
			
			if($survey_aid == '')
				$survey_aid = $aid;
		}

		// delete article
		if($mode == "delete")
		{
			csrfProtector::authorisePost(true);
			if (($radminsurvey AND $survey_aid == $aid) OR ($radminsuper == 1))
			{
				$db->table(SURVEYS_TABLE)
					->where('pollID', $pollID)
					->delete();
				$db->table(SURVEYS_CHECK_TABLE)
					->where('pollID', $pollID)
					->delete();
					
				cache_system("nuke_surveys");
				add_log(""._POLL_DELETE." $old_pollTitle", 1);
				Header("Location: ".$admin_file.".php?op=surveys");
			}
			else
			{
				include("header.php");
				$html_output .= GraphicAdmin();
				$html_output .= OpenAdminTable();
				$html_output .= "<div class=\"text-center\"><font class=\"title\"><b>"._POLL_DELETE." </b></font></div>";
				$html_output .= CloseAdminTable();
				$html_output .= OpenAdminTable();
				$html_output .= "<div class=\"text-center\">"._CANNOT_DELETE_POLLS_OF_OTHERS."</div><br><br>"._GOBACK."";
				$html_output .= CloseAdminTable();
				include("footer.php");
			}
		}
		
		// check edit or publish permission
		if($mode == "edit" || $mode == "publish_now")
		{
			$row = $db->table(SURVEYS_TABLE)
						->where('pollID', $pollID)
						->first();
			if(empty($row))
			{
				include("header.php");
				$html_output .= GraphicAdmin();
				$html_output .= OpenAdminTable();
				$html_output .= "<div class=\"text-center\"><font class=\"title\"><b>"._POLL_EDIT."</b></font></div>";
				$html_output .= CloseAdminTable();
				$html_output .= OpenAdminTable();
				$html_output .= "<div class=\"text-center\">"._NO_POLL_FOUND."</div><br><br>"._GOBACK."";
				$html_output .= CloseAdminTable();
				include("footer.php");
			
			}
			if ((!$radminsurvey OR $survey_aid != $row['aid']) AND ($radminsuper != 1))
			{
				include("header.php");
				$html_output .= GraphicAdmin();
				$html_output .= OpenAdminTable();
				$html_output .= "<div class=\"text-center\"><font class=\"title\"><b>"._POLL_EDIT."</b></font></div>";
				$html_output .= CloseAdminTable();
				$html_output .= OpenAdminTable();
				$html_output .= "<div class=\"text-center\">"._CANNOT_DELETE_POLLS_OF_OTHERS."</div><br><br>"._GOBACK."";
				$html_output .= CloseAdminTable();
				include("footer.php");
			}
		}
		
		// publish future or draft article now
		if($mode == "publish_now")
		{
			csrfProtector::authorisePost(true);
			$pollUrl = trim(sanitize(str2url($surveys_fields['pollTitle'])), "-");
			$pollUrl = get_unique_post_slug(SURVEYS_TABLE, "pollID", $pollID, "pollUrl", $pollUrl, 'publish');
			
			if($row['start_time'] == '' || $row['start_time'] > _NOWTIME)
				$start_time = _NOWTIME;
			else
				$start_time = $row['start_time'];
			
			$db->table(SURVEYS_TABLE)
				->where('pollID', $pollID)
				->update([
					'status' => 1,
					'pollUrl' => $pollUrl,
					'start_time' => $start_time,
				]);
				
			header("location: ".$admin_file.".php?op=surveys_admin&mode=edit&pollID=$pollID");
			die();			
		}		
		
		$languageslists = get_dir_list('language', 'files');
				
		// submit edited data
		if(isset($submit) && isset($surveys_fields) && !empty($surveys_fields))
		{
			$items	= array();

			$PnValidator->add_validator("in_languages", function($field, $input, $param = NULL)
			{
				$param = explode("-", $param);
				return in_array($input[$field], $param);
			}); 
			
			$PnValidator->validation_rules(array(
				'pollTitle'		=> 'required',
				'options'		=> 'is_array',
			)); 
			// Get or set the filtering rules
			$PnValidator->filter_rules(array(
				'pollTitle'			=> 'sanitize_string|filter',
				'pollUrl'			=> 'sanitize_title|str2url',	
				'planguage'			=> 'sanitize_string|filter',
				'module'			=> 'sanitize_string|filter',
				'description'		=> 'addslashes',
				'canVote'			=> 'sanitize_numbers',
				'main_survey'		=> 'sanitize_numbers',
				'allow_comment'		=> 'sanitize_numbers',
				'pollID'			=> 'sanitize_numbers',
				'post_id'			=> 'sanitize_numbers',
				'to_main'			=> 'sanitize_numbers',
				'multi_vote'		=> 'sanitize_numbers',
				'show_voters_num'	=> 'sanitize_numbers',
			)); 

			$surveys_fields = $PnValidator->sanitize($surveys_fields, array('pollTitle','planguage'), true, true);
			$validated_data = $PnValidator->run($surveys_fields);

			// validate submitted data
			if($validated_data !== FALSE)
			{
				$surveys_fields = $validated_data;
			}
			else
			{
				$hooks->add_filter("set_page_title", function() {return array("surveys_admin" => _ADD_NEW_POLL);});
				include("header.php");
				$html_output .= GraphicAdmin();
				$html_output .= surveys_menu();
				$html_output .= OpenAdminTable();
				$html_output .= '<p align=\"center\">'._ERROR_IN_OP.' :<br /><Br />'.$PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br /><br />')._GOBACK."</p>";
				$html_output .= CloseAdminTable();
				include("footer.php");
			}
			
			if(isset($surveys_fields['set_publish_date']) && $surveys_fields['set_publish_date'] == 1)
			{				
				$items['status'] = "2";	
				$items['main_survey'] = "0";
				$items['canVote'] = "0";
			}
			
			if($surveys_fields['status'] == 0)
			{
				$items['main_survey'] = "0";
				$items['canVote'] = "0";
			}
			$sum_voters = 0;
			
			foreach($surveys_fields as $key => $value)
			{
				// check if post_url is empty and set value for it
				if($key == 'pollUrl' && $value == '')
				{
					$value = trim(sanitize(str2url($surveys_fields['pollTitle'])), "-");
					$value = get_unique_post_slug(SURVEYS_TABLE, "pollID", $pollID, "pollUrl", $value, $surveys_fields['status']);
				}
				
				if(($key == "status" && isset($items['status'])) || ($key == "main_survey" && isset($items['main_survey'])) || ($key == "canVote" && isset($items['canVote'])))
				{
					continue;
				}
				
				if($key == "start_time" || $key == "end_time")
				{
					if(isset($surveys_fields['set_publish_date']) && $surveys_fields['set_publish_date'] == 1)
						$time = to_mktime("$value 0:0:0");
					else
					{
						if($key == "start_time")
							$time = _NOWTIME;
						elseif($key == "end_time")
							$time = '';
					}
						
					unset($surveys_fields[$key]);
					$items[$key] = "$time";
					
					$time = '';
					unset($time);					
					continue;
				}
				
				if($key == "set_publish_date")
				{
					unset($surveys_fields['set_publish_date']);
					continue;
				}
				
				if($key == "description")
				{
					$value = rtrim($value, "\n");
					$value = rtrim($value, "\r");
				}
				
				if($key == "post_id")
				{
					if(intval($value) != 0)
					{
						$surveys_fields['main_survey'] = 0;
						$items['main_survey'] = "0";
					}
				}
				
				if($key == "options")
				{
					foreach($old_options as $old_voteID => $old_option_data)
					{
						if(isset($value[$old_voteID][0]) && $value[$old_voteID][0] != '')
						{
							$parsedvalue[$old_voteID][0] = $value[$old_voteID][0];
							if($value[$old_voteID][0] == $old_option_data[0])
								$parsedvalue[$old_voteID][1] = $old_option_data[1];
							else
								$parsedvalue[$old_voteID][1] = 0;
							
							unset($value[$old_voteID]);
						}
					}
					
					foreach($value as $new_voteID => $new_option_data)
					{
						$parsedvalue[$new_voteID][0] = $new_option_data[0];
						$parsedvalue[$new_voteID][1] = 0;
					}
						
					unset($value);
					
					foreach($parsedvalue as $new_voteID => $new_option_data)
						$sum_voters = $sum_voters+$new_option_data[1];
						
					$items['voters'] = $sum_voters;
					
					$value = phpnuke_serialize($parsedvalue);
				}
				
				if($key == "permissions")
				{
					  $value = implode(",", $value);
				}
				
				$items[$key] = $value;
			}
			
			// save to db
			if($mode == "new")
			{
				$surveys_fields['publish_time'] = str_replace("'", "", $items['start_time']);
			
				$db->table(SURVEYS_TABLE)
					->insert($items);

				$pollID = intval($db->lastInsertId());
				
				$surveys_link = surveys_link($pollID, $surveys_fields['pollTitle'], $surveys_fields['pollUrl']);
				$survey_url = LinkToGT($surveys_link[0]);
				
				add_log(""._ADD_NEW_POLL." <a href=\"$survey_url\" target=\"_blank\">".$surveys_fields['pollTitle']."</a>", 1);
			}
			if($mode == "edit")
			{
				$result = $db->table(SURVEYS_TABLE)
								->where('pollID', $pollID)
								->update($items);
				add_log(""._POLL_EDIT." ".$surveys_fields['pollTitle']."", 1);
			}
			
			phpnuke_db_error();
			cache_system("nuke_surveys");
			header("location: ".$admin_file.".php?op=surveys_admin&mode=edit&pollID=$pollID");
			die();
		}
		
		$surveys_fields = array(
			"pollID"			=> ((intval($pollID) != 0) ?			$pollID:0),
			"status"			=> ((isset($row['status'])) ?			$row['status']:1),
			"aid"				=> ((isset($row['aid'])) ?				$row['aid']:$aid),
			"pollTitle"			=> ((isset($row['pollTitle'])) ?		$row['pollTitle']:""),
			"start_time"		=> ((isset($row['start_time'])) ?		$row['start_time']:_NOWTIME),
			"end_time"			=> ((isset($row['end_time'])) ?			$row['end_time']:''),
			"description"		=> ((isset($row['description'])) ?		stripslashes($row['description']):''),
			"pollUrl"			=> ((isset($row['pollUrl'])) ?			$row['pollUrl']:''),
			"permissions"		=> ((isset($row['permissions'])) ?		explode(",", $row['permissions']):array()),
			"options"			=> ((isset($row['options'])) ?			phpnuke_unserialize(stripslashes($row['options'])):array()),
			"planguage"			=> ((isset($row['planguage'])) ?		$row['planguage']:''),
			"post_id"			=> ((isset($row['post_id'])) ?			$row['post_id']:''),
			"module"			=> ((isset($row['module'])) ?			$row['module']:''),
			"allow_comment"		=> ((isset($row['allow_comment'])) ?	$row['allow_comment']:1),
			"canVote"			=> ((isset($row['canVote'])) ?			$row['canVote']:1),
			"main_survey"		=> ((isset($row['main_survey'])) ?		$row['main_survey']:1),
			"to_main"			=> ((isset($row['to_main'])) ?			$row['to_main']:1),
			"multi_vote"		=> ((isset($row['multi_vote'])) ?		$row['multi_vote']:1),
			"show_voters_num"	=> ((isset($row['show_voters_num'])) ?	$row['show_voters_num']:1),
		);
		
		$status_checked1 = ($surveys_fields['status'] == 1) ? "checked":"";
		$status_checked2 = ($surveys_fields['status'] == 0 || $surveys_fields['status'] == 2) ? "checked":"";
		
		$canVote_checked1 = ($surveys_fields['canVote'] == 1) ? "checked":"";
		$canVote_checked2 = ($surveys_fields['canVote'] == 0) ? "checked":"";
		
		$main_survey_checked1 = ($surveys_fields['main_survey'] == 1) ? "checked":"";
		$main_survey_checked2 = ($surveys_fields['main_survey'] == 0) ? "checked":"";
		
		$to_main_checked1 = ($surveys_fields['to_main'] == 1) ? "checked":"";
		$to_main_checked2 = ($surveys_fields['to_main'] == 0) ? "checked":"";
		
		$multi_vote_checked1 = ($surveys_fields['multi_vote'] == 1) ? "checked":"";
		$multi_vote_checked2 = ($surveys_fields['multi_vote'] == 0) ? "checked":"";
		
		$show_voters_num_checked1 = ($surveys_fields['show_voters_num'] == 1) ? "checked":"";
		$show_voters_num_checked2 = ($surveys_fields['show_voters_num'] == 0) ? "checked":"";
		
		$allow_comment_checked1 = ($surveys_fields['allow_comment'] == 1) ? "checked":"";
		$allow_comment_checked2 = ($surveys_fields['allow_comment'] == 0) ? "checked":"";
		
		$start_time = ($surveys_fields['start_time'] != '') ? $surveys_fields['start_time']:_NOWTIME;
		$end_time	= ($surveys_fields['end_time'] != '') ? $surveys_fields['end_time']:'';
		$set_publish_date = ($start_time > _NOWTIME) ? "checked":"";
		
		$start_time = nuketimes($start_time, false, false, false, 1);
		$end_time	= ($end_time != '') ? nuketimes($end_time, false, false, false, 1):"";

		$pagetitle = ($mode == "new") ? _ADD_NEW_POLL:_POLL_EDIT;
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("surveys_admin" => $pagetitle);});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= surveys_menu();
		$contents .= OpenAdminTable();
		$contents .="
		<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>
		
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/calendar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-ar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-fa.js\" type=\"text/javascript\"></script>	
		
		<form action=\"".$admin_file.".php\" method=\"post\" id=\"survey_form\">
			<table width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<th style=\"width:200px\">"._TITLE."</th>
					<td><input type=\"text\" size=\"40\" name=\"surveys_fields[pollTitle]\" id=\"title_field\" value=\"".$surveys_fields['pollTitle']."\" class=\"inp-form\" minlength=\"3\" data-validation=\"required\" data-validation-error-msg==\""._TITLE_IS_REQUIRED."\" /></td>
				</tr>
				<tr>
					<th>"._PERMALINK."</th>
					<td>
						<input type=\"text\" size=\"40\" name=\"surveys_fields[pollUrl]\" id=\"pollUrl\" value=\"".$surveys_fields['pollUrl']."\" class=\"inp-form\" />
					</td>					
				</tr>
				<tr>
					<th>"._ENABLE_POLL."</th>
					<td><input type=\"radio\" name=\"surveys_fields[status]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $status_checked1 /><input type=\"radio\" name=\"surveys_fields[status]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $status_checked2 /></td>					
				</tr>
				<tr>
					<th>"._ENABLE_VOTTING."</th>
					<td><input type=\"radio\" name=\"surveys_fields[canVote]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $canVote_checked1 /><input type=\"radio\" name=\"surveys_fields[canVote]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $canVote_checked2 /></td>					
				</tr>
				<tr>
					<th>"._IS_MAIN_POLL."</th>
					<td><input type=\"radio\" name=\"surveys_fields[main_survey]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $main_survey_checked1 /><input type=\"radio\" name=\"surveys_fields[main_survey]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $main_survey_checked2 /></td>					
				</tr>
				<tr>
					<th>"._ENABLE_MULTIOPTIONS."</th>
					<td><input type=\"radio\" name=\"surveys_fields[multi_vote]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $multi_vote_checked1 /><input type=\"radio\" name=\"surveys_fields[multi_vote]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $multi_vote_checked2 /></td>					
				</tr>
				<tr>
					<th>"._SHOW_VOTERS_NUM."</th>
					<td><input type=\"radio\" name=\"surveys_fields[show_voters_num]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $show_voters_num_checked1 /><input type=\"radio\" name=\"surveys_fields[show_voters_num]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $show_voters_num_checked2 /></td>					
				</tr>
				<tr>
					<th>"._ALLOW_COMMENT."</th>
					<td><input type=\"radio\" name=\"surveys_fields[allow_comment]\" value=\"1\" class=\"styled\" data-label=\""._ACTIVE."\" $allow_comment_checked1 /><input type=\"radio\" name=\"surveys_fields[allow_comment]\" value=\"0\" class=\"styled\" data-label=\""._INACTIVE."\" $allow_comment_checked2 /></td>					
				</tr>";
				if($nuke_configs['multilingual'] == 1)
				{
					$contents .= "
					<tr>
						<th>"._LANGUAGE."</th>
						<td>
							<select name=\"surveys_fields[planguage]\" class=\"styledselect-select\">
								<option value=\"\">"._ALL."</option>";
							foreach($languageslists as $languageslist)
							{
								if($languageslist != "")
								{
									if($languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
									$languageslist = str_replace(".php", "", $languageslist);
									$sel = ($languageslist == $surveys_fields['planguage']) ? "selected":"";
									$contents .= "<option value=\"$languageslist\" $sel>".ucfirst($languageslist)."</option>";
								}
							}
							$contents .= "
							</select>
						</td>
					</tr>";
				}
				else
					$contents .= "<input type=\"hidden\" name=\"surveys_fields[planguage]\" value=\"\" />";
				
				$contents .= "<tr>
					<th>"._DESCRIPTIONS."</th>
					<td>
						".wysiwyg_textarea("surveys_fields[description]", $surveys_fields['description'], "Basic", "50", "12", "500px", "150px")."
					</td>					
				</tr>
				<tr>
					<th>"._ASSIGN_TO_POST."</th>
					<td>
						"._POST_ID." <input type=\"text\" size=\"10\" name=\"surveys_fields[post_id]\" id=\"post_id\" value=\"".$surveys_fields['post_id']."\" class=\"inp-form-ltr\" /> &nbsp; 
						"._MODULE." <input type=\"text\" size=\"20\" name=\"surveys_fields[module]\" value=\"".$surveys_fields['module']."\" class=\"inp-form-ltr\" />
						".bubble_show(_NOTMAIN_IF_ASSIGN)."
					</td>					
				</tr>
				<tr>
					<th>"._SHOWN_FOR."</th>
					<td>";
						$permissions = get_groups_permissions();
						foreach($permissions as $key => $premission_name)
						{
							$checked = (in_array($key, $surveys_fields['permissions'])) ? "checked":'';
							
							$contents .= "<input data-label=\"$premission_name\" type=\"checkbox\" class=\"styled\" id=\"edit-block-permission_$key\" value=\"$key\" name=\"surveys_fields[permissions][]\" $checked />&nbsp; ";
						}
					$contents .= "</td>					
				</tr>
				<tr>
					<th>"._OPTIONS." <button class=\"add_field_button\">"._ADD_NEW_OPTION."</button></th>
					<td>
						<div class=\"input_fields_wrap\">";
						if(empty($surveys_fields['options']))
						{
							$surveys_fields['options'] = array(
								array("",0),
								array("",0)
							);
						}
						$key = 0;
						foreach($surveys_fields['options'] as $voteID => $option_data)
						{
							$option_text = $option_data[0];
							$option_counter = $option_data[1];
							$contents .= "
							<div style=\"margin-bottom:3px;\"><input placeholder=\""._OPTION_NAME."\" type=\"text\" class=\"inp-form\" id=\"option_text_$voteID\" value=\"$option_text\" name=\"surveys_fields[options][$voteID][]\" />&nbsp; $option_counter "._VOTE."".(($key > 1) ? " &nbsp; <a href=\"#\" class=\"remove_field\">Remove</a>":"")."</div>";
							$key++;
						}
						$contents .= "
						</div>".bubble_show(_POLL_MUSTHAVE_ATLEAST_TWO_OPTION)."
					</td>
				</tr>
				<tr>
					<th>"._PUBLISH_DATE."</th>
					<td>
						"._START." <input type=\"text\" name=\"surveys_fields[start_time]\" class=\"inp-form-ltr calendar\" value=\"".$start_time."\">
						"._END." <input type=\"text\" name=\"surveys_fields[end_time]\" class=\"inp-form-ltr calendar\" value=\"".$end_time."\">
						".bubble_show("<div style=\"margin-top:-7px;\"><input id=\"set_publish_date\" type='checkbox' class='styled' name='surveys_fields[set_publish_date]' value='1' data-label=\""._SET_PUBLISH_DATE."\" $set_publish_date style=\"top:10px;\"></div>")."
					</td>
				</tr>
				<tr>
					<th>"._PUBLISH_AS_MAIN."</th>
					<td><input type=\"radio\" name=\"surveys_fields[to_main]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $to_main_checked1 /><input type=\"radio\" name=\"surveys_fields[to_main]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $to_main_checked2 />
					".bubble_show(_SET_TO_MAIN_AFTER_PUBLISH)."
					</td>					
				</tr>
				<tr>
					<td colspan=\"2\">
						<input type=\"submit\" name=\"submit\" value=\"submit\" class=\"form-submit\" />
					</td>					
				</tr>";
			$contents .= "
			</table>
			<input type=\"hidden\" name=\"op\" value=\"surveys_admin\" />
			<input type=\"hidden\" name=\"surveys_fields[aid]\" value=\"$aid\" />
			<input type=\"hidden\" name=\"pollID\" value=\"$pollID\" />
			<input type=\"hidden\" name=\"mode\" value=\"$mode\" />
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>
		<script>
			$(\"#set_publish_date\").on(\"change\",function(){
				if($(this).prop(\"checked\"))
					alert('"._PUBLISH_DATE_ALERT."');
			});
			$(document).ready(function(){
			
				$.validate({
					form : '#survey_form',
					modules : 'security',
				});
				
				if('new' == '$mode')
				{
					$(\"#title_field\").on('blur', function(){
						var title = $(\"#title_field\");
						$.post(\"ajax.php\",
						{
							op: 'get_unique_post_slug',
							table: '".SURVEYS_TABLE."',
							id_name: 'pollID',
							id_value: '$pollID',
							field: 'pollUrl',
							slug: title.val(),
							post_status: '".$surveys_fields['status']."',
							csrf_token : pn_csrf_token
						},
						function(data, status){
							$(\"#pollUrl\").val(data);
						});
					});
				}
				
				var add_field_load = $(\".input_fields_wrap\").add_field({ 
					addButton: $(\".add_field_button\"),
					remove_button: '.remove_field',
					fieldHTML: '<div style=\"margin-bottom:3px;\"><input placeholder=\""._OPTION_NAME."\" type=\"text\" class=\"inp-form\" id=\"option_text_{X}\" value=\"\" name=\"surveys_fields[options][{X}][]\" />&nbsp; 0 "._VOTE." &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a></div></div>',
					x: $key,
				});
			});
		</script>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$status = (isset($status) && $status != '') ? intval($status):'';
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'new';
	$order_by = (isset($order_by)) ? filter($order_by, "nohtml"):'';
	$sort = (isset($sort)) ? filter($sort, "nohtml"):'';
	$search_query = (isset($search_query)) ? filter($search_query, "nohtml"):'';
	$submit = (isset($submit)) ? filter($submit, "nohtml"):'';
	$surveys_fields = request_var('surveys_fields' , array(), '_POST');
	$pollID = (isset($pollID)) ? intval($pollID):0;
	
	switch($op) {
		case "surveys":
			surveys($status, $search_query, $order_by, $sort);
		break;
		
		case"surveys_admin":
			surveys_admin($pollID, $mode, $submit, $surveys_fields);
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