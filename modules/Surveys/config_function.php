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

if (!defined('CONFIG_FUNCTIONS_FILE')) {
	die("You can't access this file directly...");
}
$this_module_name = basename(dirname(__FILE__));

function surveys_link($pollID, $pollTitle = '', $pollUrl = '')
{
	global $nuke_configs, $db;
	$pollID = intval($pollID);
	
	if($pollTitle == '' OR $pollUrl == '')
	{
		$row = $db->table(SURVEYS_TABLE)
					->where('pollID' , $pollID)
					->first(['pollTitle','pollUrl']);
		
		$pollTitle = filter($row['pollTitle'], "nohtml");
		$pollUrl = filter($row['pollUrl'], "nohtml");
	}
	
	if($pollUrl == '')
	{
		$pollUrl = trim(sanitize(str2url($pollTitle)), "-");
		$pollUrl = get_unique_post_slug(SURVEYS_TABLE, "pollID", $pollID, "pollUrl", $pollUrl);
		$db->table(SURVEYS_TABLE)
			->where('pollID' , $pollID)
			->update([
				'pollUrl' => $pollUrl
			]);
	}
	
	$pollUrl = sanitize(str2url((($pollUrl != "") ? $pollUrl:$pollTitle)));
	$pollTitle = sanitize(str2url($pollTitle));

	$pollUrl = str_replace(" ", "-", $pollUrl);

	if($nuke_configs['gtset'] == "1")
		$poll_link = array("".$nuke_configs['nukeurl']."surveys/$pollUrl/", "".$nuke_configs['nukeurl']."surveys/$pollUrl/result/");
	else
		$poll_link = array("index.php?modname=Surveys&op=poll_show&pollID=$pollID-$pollTitle", "index.php?modname=Surveys&op=poll_show&mode=result&pollID=$pollID-$pollTitle");
	
	return $poll_link;
}

function change_poll_status()
{
	global $db, $nuke_configs;
	
	$nuke_surveys_cacheData = get_cache_file_contents("nuke_surveys");
	
	$now_time = _NOWTIME;
	$query_active_set = array();
	$query_deactive_set = array();
	$change = false;
	foreach($nuke_surveys_cacheData as $pollID => $poll_data)
	{
		if($poll_data['start_time'] < $now_time && $poll_data['end_time'] != '' && $poll_data['end_time'] > $now_time && $poll_data['status'] == 2)
		{
			$query_active_set[] = $pollID;
			$nuke_surveys_cacheData[$pollID]['status'] = 1;
			$nuke_surveys_cacheData[$pollID]['canVote'] = 1;
			if($poll_data['to_main'] == 1)
				$nuke_surveys_cacheData[$pollID]['main_survey'] = 1;
		}
			
		if($poll_data['end_time'] != '' && $poll_data['end_time'] < $now_time)
		{
			$query_deactive_set[] = $pollID;
			$nuke_surveys_cacheData[$pollID]['status'] = 0;
			$nuke_surveys_cacheData[$pollID]['canVote'] = 0;
		}
	}
	
	if(!empty($query_active_set))
	{
		$db->table(SURVEYS_TABLE)
			->in('pollID' , $query_active_set)
			->update([
				'status' => 1,
				'canVote' => 1,
				'main_survey' => $poll_data['to_main'],
			]);
		$change = true;
	}
	
	if(!empty($query_deactive_set))
	{
		$db->table(SURVEYS_TABLE)
			->in('pollID' , $query_deactive_set)
			->update([
				'status' => 0,
				'canVote' => 0
			]);
		$change = true;
	}
	
	if($change)
		cache_system("nuke_surveys");
		
	return $nuke_surveys_cacheData;
}

function pollMain($nuke_surveys_cacheData = '', $pollID, $in_block = false)
{
	global $module_name, $db, $userinfo, $visitor_ip, $nuke_configs, $pn_Cookies;
	
	if($nuke_surveys_cacheData == '')
		$nuke_surveys_cacheData = change_poll_status();
	
	$contents = '';
	$poll_data = $nuke_surveys_cacheData[$pollID];
	
	if(intval($poll_data['status']) == 0)
	{
		if($in_block)
			return _DEACTIVATED_POLL;
		else
			header("location: ".LinkToGT("index.php?modname=Surveys")."");
	}
	$voted_ip_number = $db->table(SURVEYS_CHECK_TABLE)
							->where('pollID', $pollID)
							->where('ip', $visitor_ip)
							->select(['ip'])
							->count();
	
	$poll_cookie = $pn_Cookies->get('poll-'.$pollID);
	
    if (($voted_ip_number != 0 && $poll_cookie == 1) || intval($poll_data['canVote']) == 0 && intval($poll_data['show_result']) == 1)
	{
		$contents .= pollResults($nuke_surveys_cacheData, $pollID, $in_block);
	}
	else
	{
		$contents .= "<script src=\"".$nuke_configs['nukecdnurl']."modules/Surveys/includes/surveys.js\" type=\"text/javascript\" defer=\"defer\"></script>";
		
		$poll_data['pollID'] = intval($pollID);
		$poll_data['pollTitle'] = filter($poll_data['pollTitle'], "nohtml");
		$poll_data['description'] = stripslashes($poll_data['description']);
		$poll_data['pollUrl'] = filter($poll_data['pollUrl'], "nohtml");
		$poll_data['show_voters_num'] = intval($poll_data['show_voters_num']);
		$poll_data['voters'] = intval($poll_data['voters']);
		$poll_data['multi_vote'] = intval($poll_data['multi_vote']);
		$poll_data['input_type'] = ($poll_data['multi_vote'] > 1) ? "checkbox":"radio";
		$poll_data['input_name'] = ($poll_data['multi_vote'] > 1) ? "options_value[]":"options_value";
		$poll_data['poll_link'] = surveys_link($pollID, $poll_data['pollTitle'],  $poll_data['pollUrl']);

		if(file_exists("themes/".$nuke_configs['ThemeSel']."/pollMain.php")) {
			include("themes/".$nuke_configs['ThemeSel']."/pollMain.php");
		}elseif(function_exists("pollMain_html")) {
			$contents = pollMain_html($poll_data, $in_block);
		} else {
			$contents .= "<script src=\"".$nuke_configs['nukecdnurl']."modules/Surveys/includes/surveys.js\" type=\"text/javascript\" defer=\"defer\"></script>";
			if(!$in_block) $contents .= OpenTable($poll_data['pollTitle']);
			
			$contents .= "
			<div id=\"surveys-".(($in_block) ? "b":"m")."-$pollID\">
			<form class=\"text-"._TEXTALIGN1."\" role=\"form\">";
				if($in_block)
				{
					$contents .= "<div class=\"form-group\">
						<div class=\"col-sm-12\">".$poll_data['pollTitle']."</div>
					</div>";
				}
				$contents .= "<div class=\"form-group\">
					<div class=\"col-sm-12\">".$poll_data['description']."</div>
				</div>
				<div class=\"form-group\">
					<div class=\"col-sm-12\">";
						foreach($poll_data['options'] as $option_key => $option_data)
						{
							$contents .= "<div class=\"".$poll_data['input_type']."\"><label><input type=\"".$poll_data['input_type']."\" name=\"".$poll_data['input_name']."\" value=\"$option_key\">".$option_data[0]."</label></div>";
						}
						$contents .= "
					</div>
				</div>
				<div class=\"form-group\"> 
					<div class=\"text-center\">
						<button type=\"button\" class=\"btn btn-success add_vote\" rel=\"surveys-".(($in_block) ? "b":"m")."-$pollID\">"._VOTEUP."</button><br><br>
						<i class=\"glyphicon glyphicon-stats\"></i> <a href=\"".$poll_data['poll_link'][1]."\">"._RESULTS."</a><br>
						<i class=\"glyphicon glyphicon-list-alt\"></i> <a href=\"".LinkToGT("index.php?modname=Surveys")."\">"._SURVEYS."</a><br>
						<p>";
							if($poll_data['show_voters_num'])
								$contents .= "<br><i class=\"glyphicon glyphicon-bullhorn\"></i> "._REGISTER_VOTES_NUM." : ".$poll_data['voters']." "._VOTE."<br>";
							$contents .= "<i class=\"glyphicon glyphicon-comment\"></i> <a href=\"".$poll_data['poll_link'][1]."#postcomments\">"._COMMENTS."</a><br>
						</p>
					</div>
				</div>
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>
			</div>";
			if(!$in_block) $contents .= CloseTable();
		}
	}
	
	return $contents;
}

function pollResults($nuke_surveys_cacheData = '', $pollID, $in_block = false)
{
	global $nuke_configs, $module_name, $visitor_ip, $db, $admin_file, $userinfo;
	
	if($nuke_surveys_cacheData == '')
		$nuke_surveys_cacheData = change_poll_status();
	
	$contents = '';
	$poll_data = $nuke_surveys_cacheData[$pollID];
	
	if(!is_admin() && intval($poll_data['show_result']) == 0)
		return _UNAVAILABLE_RESULT;
	
	if(intval($poll_data['status']) == 0)
	{
		if($in_block)
			return _DEACTIVATED_POLL;
		else
			header("location: ".LinkToGT("index.php?modname=Surveys")."");
	}
	
	$voted_ip_number = $db->table(SURVEYS_CHECK_TABLE)
							->where('pollID', $pollID)
							->where('ip', $visitor_ip)
							->select(['ip'])
							->count();
							
	$poll_data['pollID'] = intval($pollID);
	$poll_data['pollTitle'] = filter($poll_data['pollTitle'], "nohtml");
	$poll_data['description'] = stripslashes($poll_data['description']);
	$poll_data['pollUrl'] = filter($poll_data['pollUrl'], "nohtml");
	$poll_data['voters'] = intval($poll_data['voters']);
	$poll_data['show_voters_num'] = intval($poll_data['show_voters_num']);
	$poll_data['multi_vote'] = intval($poll_data['multi_vote']);
	$poll_data['input_type'] = ($poll_data['multi_vote'] > 1) ? "checkbox":"radio";
	$poll_data['input_name'] = ($poll_data['multi_vote'] > 1) ? "options_value[]":"options_value";
	$poll_data['poll_link'] = surveys_link($pollID, $poll_data['pollTitle'], $poll_data['pollUrl']);
	$poll_data['options_data'] = array();
	
	foreach($poll_data['options'] as $option_key => $option_data)
	{
		$option_text = $option_data[0];
		$option_count = $option_data[1];
		$percent = ($poll_data['voters'] != 0) ? number_format((($option_count/$poll_data['voters'])*100), 0):0;
		$poll_data['options_data'][] = array($option_text, $option_count, $percent);
	}
	
	$other_polls = array();
	$key_counter = 0;
	foreach($nuke_surveys_cacheData as $last_pollID => $nuke_surveys_data)
	{
		if ($pollID == $last_pollID || $nuke_configs['multilingual'] == 1 && $nuke_configs['currentlang'] != $nuke_surveys_data['planguage']) continue;
		if($key_counter == 4) break;
		
		$other_polls[$last_pollID]['pollTitle'] = filter($nuke_surveys_data['pollTitle'], "nohtml");
		$other_polls[$last_pollID]['pollUrl'] = filter($nuke_surveys_data['pollUrl'], "nohtml");
		$other_polls[$last_pollID]['voters'] = intval($nuke_surveys_data['voters']);
		$other_polls[$last_pollID]['show_voters_num'] = intval($nuke_surveys_data['show_voters_num']);
		$other_polls[$last_pollID]['planguage'] = intval($nuke_surveys_data['planguage']);
		$other_polls[$last_pollID]['poll_link'] = surveys_link($last_pollID, $pollTitle, $pollUrl);
	}
	
	if(file_exists("themes/".$nuke_configs['ThemeSel']."/pollResult.php")) {
		include("themes/".$nuke_configs['ThemeSel']."/pollResult.php");
	}elseif(function_exists("pollResult_html")) {
		$contents = pollResult_html($poll_data, $in_block);
	} else {
		if(!$in_block)
			$contents .= OpenTable($poll_data['pollTitle']."".((is_admin()) ? " [ <a href=\"".LinkToGT("".$admin_file.".php?op=surveys_admin")."\">"._ADD."</a> | <a href=\"".LinkToGT("".$admin_file.".php?op=surveys_admin&mode=edit&pollID=$pollID")."\">"._EDIT."</a> ]":"")."");
		else
			$contents .= "<div class=\"col-sm-12\">".$poll_data['pollTitle']."<br /><br /></div>";
		
		$contents .= "<div class=\"col-sm-12\">".$poll_data['description']."<br /><br /></div>";
			
		$contents .= "<div class=\"col-sm-12\">";
		
		$striped_classes = array("striped", "success", "info", "warning", "danger");
		foreach($poll_data['options_data'] as $option_data)
		{
			$contents .="		
			<div class=\"progress progress-striped "._TEXTALIGN1."\">
				<div class=\"progress-bar progress-bar-".$striped_classes[rand(0,4)]."\" role=\"progressbar\" data-transitiongoal=\"$option_data[2]\"><i class=\"val\">$option_data[2]%</i><span class=\"skill\">$option_data[0] ".(($poll_data['show_voters_num']) ? "($option_data[1] "._VOTERS.")":"")."</span></div>
			</div>";
		}
		$contents .= "
		</div>";
		if($poll_data['show_voters_num'] && !$in_block)
			$contents .= "<b>"._TOTALVOTES." $voters</b><br>";
		
		if($in_block)
		{
			$contents .= "
				<div class=\"form-group\"> 
					<div class=\"text-center\">
						<i class=\"glyphicon glyphicon-stats\"></i> <a href=\"".$poll_data['poll_link'][1]."\">"._RESULTS."</a><br>
						<i class=\"glyphicon glyphicon-list-alt\"></i> <a href=\"".LinkToGT("index.php?modname=Surveys")."\">"._SURVEYS."</a><br>
						<p>";
							if($poll_data['show_voters_num']) $contents .= "<br><i class=\"glyphicon glyphicon-bullhorn\"></i> "._REGISTER_VOTES_NUM." : $voters راي<br>";
							$contents .= "<i class=\"glyphicon glyphicon-comment\"></i> <a href=\"".$poll_data['poll_link'][1]."#postcomments\">"._COMMENTS."</a><br>
						</p>
					</div>
				</div>";
		}
		if($voted_ip_number == 0)
			 $contents .= "<div class=\"text-center\"><a href=\"".LinkToGT($poll_data['poll_link'][0])."\">"._POLL_PARTICIPATION."</a><br></div>";
		
		if(!$in_block)
		{
			$contents .= CloseTable();
			$contents .="<div class=\"pollNuke\">";
			$contents .= OpenTable(_LAST5POLLS.$nuke_configs['sitename'], "info");
			$contents .= "<ul>";
			$key_counter = 0;
			foreach($other_polls as $last_pollID => $nuke_surveys_data)
			{
				$contents .="
				<li>
					<a href=\"".LinkToGT($nuke_surveys_data['poll_link'][0])."\">$pollTitle</a>
					<span> ( 
						<i class=\"glyphicon glyphicon-stats\"></i> 
						<a href=\"".LinkToGT($nuke_surveys_data['poll_link'][1])."\">"._RESULTS."</a>";
						if($nuke_surveys_data['show_voters_num'])
						$contents .=" - 
						<i class=\"glyphicon glyphicon-bullhorn\"></i> "._REGISTER_VOTES_NUM." : ".$nuke_surveys_data['voters']." "._VOTE."";
						if(is_admin())
						{
							$contents .= " - <i class=\"glyphicon glyphicon-edit\"></i> <a href=\"".$admin_file.".php?op=surveys_admin&pollID=$last_pollID\">"._EDIT."</a>";
						}
				$contents .=")</span>
				</li>";
			}
			$contents .="</ul>
			<a href=\"".LinkToGT("index.php?modname=Surveys")."\"><b>"._MOREPOLLS."</b></a>";
			$contents .= CloseTable();
			$contents .="</div>";
		}
	}
	return $contents;
}

function get_surveys_link($post_link, $module, $post_id)
{
	$post_link = ($post_link == '' && in_array($module, array("Surveys"))) ? surveys_link($post_id, '', '', '', '', $module):$post_link;
	
	if(is_array($post_link))
		$post_link = $post_link[1];
	
	return $post_link;
}
$hooks->add_filter("get_post_link", 'get_surveys_link', 10);

function surveys_have_comments($all_modules_comments)
{
	$all_modules_comments = array_merge($all_modules_comments, array(
		"Surveys" => _SURVEYS
	));
	
	return $all_modules_comments;
}
$hooks->add_filter("modules_have_comments", 'surveys_have_comments', 10);

function surveys_comments_table_data($module_table_data)
{
	$nuke_configs_comments_table['Surveys'] = array('pollID', SURVEYS_TABLE);
	$module_table_data = array_merge($module_table_data, $nuke_configs_comments_table);
	
	return $module_table_data;
}
$hooks->add_filter("modules_comments_table_data", 'surveys_comments_table_data', 10);

function surveys_alert_messages($alerts_messages)
{
	$alerts_messages = array_merge($alerts_messages, array(
		"surveys_comments" => array(
			"prefix"	=> "cs",
			"by"		=> "cid",
			"table"		=> COMMENTS_TABLE,
			"where"		=> "module = 'Surveys' AND status = '0'",
			"color"		=> "green",
			"text"		=> _NEW_POLLS_COMMENT_ALARM,
		)
	));
	
	return $alerts_messages;
}
$hooks->add_filter("admin_alert_messages", 'surveys_alert_messages', 10);

function surveys_statistics_data($modules_statistics_data)
{
	$modules_statistics_data['Surveys'] = array(
		"total_surveys" => array(
			"title"				=> _SURVEYS,
			"table"				=> SURVEYS_TABLE,
			"count"				=> "pollID",
			"as"				=> "total_surveys",
			"where"				=> "status = '1'",
		),
	);
	
	return $modules_statistics_data;
}
$hooks->add_filter("modules_statistics_data", 'surveys_statistics_data', 10);

function surveys_boxes_parts($nuke_modules_boxes_parts)
{
	$nuke_modules_boxes_parts['Surveys'] = array(
		"index" => _INDEX,
		"results" => _RESULTS,
	);
	
	return $nuke_modules_boxes_parts;
}

$hooks->add_filter("modules_boxes_parts", "surveys_boxes_parts", 10);

?>