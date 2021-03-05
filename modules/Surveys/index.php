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
	global $db, $nuke_configs, $admin, $module_name, $admin_file, $userinfo, $hooks;
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

		
	$meta_tags = array(
		"url" => LinkToGT("index.php?modname=Surveys"),
		"title" => _SURVEYS,
		"description" => _POLLS_LIST,
		"extra_meta_tags" => array()
	);
	$meta_tags = $hooks->apply_filters("feedback_header_meta", $meta_tags);
	
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);
	
	$hooks->add_filter("site_breadcrumb", function($breadcrumbs, $block_global_contents) use($meta_tags){
		$breadcrumbs['surveys'] = array(
			"name" => $meta_tags['title'],
			"link" => $meta_tags['url'],
			"itemtype" => "WebPage"
		);
		return $breadcrumbs;
	}, 10);
	unset($meta_tags);
	
	include("header.php");
	
	$contents = $hooks->apply_filters("pollslist", $contents);
	
	$html_output .= show_modules_boxes($module_name, "index", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents, "", array("block-comments.php"));
	include ("footer.php");
}

function poll_show($pollUrl, $mode="")
{
	global $module_name, $nuke_configs, $hooks;
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
	$meta_tags = $hooks->apply_filters("surveys_header_meta", $meta_tags, $poll_data);
		
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);
	
	$hooks->add_filter("site_breadcrumb", function($breadcrumbs, $block_global_contents) use($poll_data, $meta_tags, $mode){
		$breadcrumbs['surveys'] = array(
			"name" => _SURVEYS,
			"link" => LinkToGT("index.php?modname=Surveys"),
			"itemtype" => "WebPage"
		);
		$breadcrumbs['surveys-name'] = array(
			"name" => $poll_data['pollTitle'],
			"link" => $poll_data['survey_link'][0],
			"itemtype" => "WebPage"
		);
		if($mode == "result")
		{
			$breadcrumbs['surveys-result'] = array(
				"name" => _RESULTS,
				"link" => $poll_data['survey_link'][1],
				"itemtype" => "WebPage"
			);
		}
		return $breadcrumbs;
	}, 10);
	unset($meta_tags);	
	
	$hooks->add_filter("site_theme_headers", function ($theme_setup) use($nuke_configs)
	{
		$theme_setup = array_merge_recursive($theme_setup, array(
			"defer_js" => array(
				"<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/bootstrap/js/bootstrap-progressbar.js\" type=\"text/javascript\"></script>",
				'<script>$(document).ready(function() {$(\'.progress .progress-bar\').progressbar();});</script>'
			)
		));
		return $theme_setup;
	}, 10);
	
	include("header.php");
	
	$hooks->add_filter("global_contents", function ($block_global_contents) use($pollID, $poll_data, $module_name)
	{
		$block_global_contents = $poll_data;
		$block_global_contents['post_id'] = $pollID;
		$block_global_contents['post_title'] = filter($poll_data['pollTitle'], "nohtml");
		$block_global_contents['module_name'] = $module_name;
		$block_global_contents['allow_comments'] = intval($poll_data['allow_comment']);
		$block_global_contents['db_table'] = SURVEYS_TABLE;
		$block_global_contents['db_id'] = 'pollID';
		return $block_global_contents;
	}, 10);	
	
	$contents = $hooks->apply_filters("poll_show", $contents);
	
	$html_output .= show_modules_boxes($module_name, "results", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), "<div class=\"surveys\">".$contents."</div>");
	
	include ("footer.php");
}

function pollCollector($pollID, $vote_data)
{
	global $nuke_configs, $visitor_ip, $db, $module_name, $pn_Cookies, $hooks;
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
			
			$hooks->do_action("pollCollector", $pollID, $vote_data);
			
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