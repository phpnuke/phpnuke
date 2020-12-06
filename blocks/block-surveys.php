<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* Part: blocks				                                            */
/* Part Name: block-ads		                                            */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('BLOCK_FILE'))
{
    Header("Location: ../index.php");
    die();
}

global $db, $module_name, $nuke_configs, $block_global_contents, $comments_op, $custom_theme_setup;

$content = "";

$custom_theme_setup = array_merge_recursive($custom_theme_setup, array(
	"defer_js" => array(
		"<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/bootstrap/js/bootstrap-progressbar.js\" type=\"text/javascript\"></script>",
		'<script>$(document).ready(function() {$(\'.progress .progress-bar\').progressbar();});</script>'
	)
));
	
$nuke_surveys_cacheData = change_poll_status();
 
$pollID = 0;// change to special poll
if(!isset($pollID) || $pollID == 0)
{
	$this_pollID = 0;
	
	foreach($nuke_surveys_cacheData as $pollID => $poll_data)
	{
		if($block_global_contents['module_name'] == $module_name && isset($block_global_contents['post_id']) && $poll_data['post_id'] == $block_global_contents['post_id'])
		{
			$this_pollID = $pollID;
			$title .= "("._RELATEDTO." ".$block_global_contents['post_title']." )";
			break;
		}
	}
	
	if($this_pollID == 0)
	{
		foreach($nuke_surveys_cacheData as $pollID => $poll_data)
		{
			if($poll_data['main_survey'] == 1 && $poll_data['status'] == 1)
			{
				$this_pollID = $pollID;
				break;
			}
		}
	}
}
if($this_pollID != 0)
{
	$poll_data = $nuke_surveys_cacheData[$pollID];

	if(!empty($poll_data) && intval($poll_data['status']) != 1)
		$content = _POLL_IS_DESABLED;
	elseif(empty($poll_data))
		$content = _POLL_NOT_EXISTS;
	else
		$content .= pollMain($nuke_surveys_cacheData, $pollID, true);
}
else
	$content = _NO_ACTIVE_POLL_FOUND;

?>