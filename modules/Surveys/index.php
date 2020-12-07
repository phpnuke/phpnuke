<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/
************************************************************************/

if (!defined('MODULE_FILE')) {
	die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));

define('INDEX_FILE', is_index_file($module_name));// to define INDEX_FILE status

if (isset($pollID)) {
	$pollID = intval($pollID);
}

if (isset($voteID)) {
	$voteID = intval($voteID);
}

function pollList()
{
	global $db, $nuke_configs, $admin, $module_name, $admin_file, $userinfo;
	$contents = '';
	
	$nuke_surveys_cacheData = change_poll_status();
	
	$contents .="<div class=\"pollNuke\">";
	$contents .= OpenTable(_POLLS_LIST);
	$contents .= "<ul>";
	foreach($nuke_surveys_cacheData as $pollID => $nuke_surveys_data)
	{
		if ($nuke_surveys_data['status'] != 1 || ($nuke_configs['multilingual'] == 1 && $nuke_surveys_data['planguage'] != "" && $nuke_configs['currentlang'] != $nuke_surveys_data['planguage'])) continue;
		
		$pollTitle = filter($nuke_surveys_data['pollTitle'], "nohtml");
		$pollUrl = filter($nuke_surveys_data['pollUrl'], "nohtml");
		$voters = intval($nuke_surveys_data['voters']);
		$planguage = intval($nuke_surveys_data['planguage']);
		$poll_link = surveys_link($pollID, $pollTitle, $pollUrl);
		
		$contents .="
		<li>
			<a href=\"".LinkToGT($poll_link[0])."\">$pollTitle</a>
			<span> ( 
				<i class=\"glyphicon glyphicon-stats\"></i> 
				<a href=\"".LinkToGT($poll_link[1])."\">"._RESULTS."</a>
				- 
				<i class=\"glyphicon glyphicon-bullhorn\"></i> "._REGISTER_VOTES_NUM." : $voters "._VOTE."";
				if(is_admin())
				{
					$contents .= "- <i class=\"glyphicon glyphicon-edit\"></i> <a href=\"".$admin_file.".php?op=surveys_admin&pollID=$pollID\">"._EDIT."</a>";
				}
		$contents .=")</span>
		</li>";
	}
	$contents .="</ul>";
	$contents .= CloseTable();
	$contents .="</div>";

	include("header.php");
	$html_output .= show_modules_boxes($module_name, "index", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents, "", array("block-comments.php"));
	include ("footer.php");
}

function poll_show($pollUrl, $mode="")
{
	global $module_name, $pagetitle, $nuke_configs, $custom_theme_setup;
	$nuke_surveys_cacheData = change_poll_status();
	$url_found = false;
	foreach($nuke_surveys_cacheData as $pollID => $poll_data)
	{
		if($poll_data['pollUrl'] == $pollUrl)
		{
			$url_found = true;
			break;
		}
	}
	if(!$url_found)
		die_error("404");
		
	$pollID = intval($pollID);
	if(!isset($pollID) || $pollID == 0)
		$pollID = 1;

	$poll_data = $nuke_surveys_cacheData[$pollID];
	
	$poll_data['survey_link'] = surveys_link($pollID, $poll_data['pollTitle'], $poll_data['pollUrl']);
	
	if(intval($poll_data['status']) == 0)
		header("location: ".LinkToGT("index.php?modname=$module_name")."");
	
	$contents ='';
	if($mode == '')
		$contents .= pollMain($nuke_surveys_cacheData, $pollID);
	else
		$contents .= pollResults($nuke_surveys_cacheData, $pollID);
		
	$meta_tags = array(
		"url" => $poll_data['survey_link'][0],
		"title" => ""._POLL." : ".$poll_data['pollTitle'],
		"description" => str_replace(array("\r","\n","\t"), "", strip_tags(stripslashes($poll_data['description']))),
		"extra_meta_tags" => array()
	);
	
	$pagetitle = "- ".$poll_data['pollTitle'];
	
	
	$custom_theme_setup = array_merge_recursive($custom_theme_setup, array(
		"defer_js" => array(
			"<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/bootstrap/js/bootstrap-progressbar.js\" type=\"text/javascript\"></script>",
			'<script>$(document).ready(function() {$(\'.progress .progress-bar\').progressbar();});</script>'
		)
	));
	
	$custom_theme_setup_replace = false;
	
	include("header.php");
	
	unset($meta_tags);
	
	$GLOBALS['block_global_contents'] = $poll_data;
	$GLOBALS['block_global_contents']['post_id'] = $pollID;
	$GLOBALS['block_global_contents']['post_title'] = filter($poll_data['pollTitle'], "nohtml");
	$GLOBALS['block_global_contents']['module_name'] = $module_name;
	$GLOBALS['block_global_contents']['allow_comments'] = intval($poll_data['allow_comment']);
	$GLOBALS['block_global_contents']['db_table'] = SURVEYS_TABLE;
	$GLOBALS['block_global_contents']['db_id'] = 'pollID';
	
	$html_output .= show_modules_boxes($module_name, "results", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), "<div class=\"surveys\">".$contents."</div>");
	
	unset($GLOBALS['block_global_contents']);
	include ("footer.php");
}

function pollCollector($pollID, $vote_data)
{
	global $nuke_configs, $visitor_ip, $db, $module_name, $pn_Cookies;
	$pollID = intval($pollID);
	
	$row = $db->table(SURVEYS_TABLE)
				->where('pollID', $pollID)
				->first(['status', 'canVote']);
				
	if(intval($row['status']) == 0)
		$response = json_encode(array(
			"status" => "error",
			"message" => _DEACTIVATED_POLL
		));
	
	if(intval($row['canVote']) == 0)
		$response = json_encode(array(
			"status" => "error",
			"message" => _NOT_ALLOWED_VOTE
		));
	
	$vote_data = (!is_array($vote_data)) ? explode(",", $vote_data):$vote_data;
	
	$voted_ip_number = $db->table(SURVEYS_CHECK_TABLE)
							->where('ip', $visitor_ip)
							->where('pollID', $pollID)
							->select(['ip'])
							->count();
	$now_time = _NOWTIME;
	if ($voted_ip_number == 0)
	{		
		$change = false;
		$nuke_surveys_cacheData = change_poll_status();
		$options = $nuke_surveys_cacheData[$pollID]['options'];
		foreach($vote_data as $voteID => $vote_val)
		{
			if($vote_val == 0) continue;
			$options[$voteID][1] = intval($options[$voteID][1]);
			$options[$voteID][1]++;
			$change = true;
		}
		if($change)
		{
			$options = addslashes(phpnuke_serialize($options));
			$db->table(SURVEYS_TABLE)
				->where('pollID', $pollID)
				->update([
					"options" => $options,
					"voters" => ["+", 1]
				]);
			
			update_points(8);
			$db->table(SURVEYS_CHECK_TABLE)
				->insert([
					"ip" => $visitor_ip,
					"time" => $now_time,
					"pollID" => $pollID,
				]);
			
			$pn_Cookies->set("poll-".$pollID,1,(365*24*3600));
			cache_system("nuke_surveys");
			$contents = pollResults('', $pollID, true);
			$response = json_encode(array(
				"status" => "success",
				"message" => $contents
			));
		}
		else
			$response = json_encode(array(
				"status" => "error",
				"message" => _NO_OPTION_SELECTED
			));
		
	}
	else
		$response = json_encode(array(
			"status" => "error",
			"message" => _PARTICIPATED_POLL
		));
		
	die($response);
}

$op = (isset($op)) ? filter($op, "nohtml"):'';
$mode = (isset($mode)) ? filter($mode, "nohtml"):'';
$pollUrl = (isset($pollUrl)) ? filter($pollUrl, "nohtml"):'';
$pollID = (isset($pollID)) ? intval($pollID):0;
$voteID = (isset($voteID)) ? intval($voteID):0;

switch($op)
{
	default:
		pollList();
	break;
	
	case"pollCollector":
		pollCollector($pollID, $vote_data);
	break;

	case"poll_show":
		poll_show($pollUrl, $mode);
	break;
}


?>