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

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

global $db, $nuke_banned_ip_cacheData, $visitor_ip;

if(isset($nuke_banned_ip_cacheData) && is_array($nuke_banned_ip_cacheData) && !empty($nuke_banned_ip_cacheData))
{
	$must_deleted_baned_ips = array();
	foreach($nuke_banned_ip_cacheData as $baned_ip_id => $baned_ip_data)
	{
		if($baned_ip_data['expire'] != 0 && $baned_ip_data['expire'] < _NOWTIME)
		{
			$must_deleted_baned_ips[] = $baned_ip_id;
		}
		else
			$baned_ips['blacklistfile'][] = $baned_ip_data['ipaddress'];
	}
	if(!empty($must_deleted_baned_ips))
	{
		$db->table(MTSN_IPBAN_TABLE)
				->where("id", $baned_ip_id)
				->delete();
	}
	
	$baned_ips['whitelistfile'] = array();
	
	include_once(INCLUDE_PATH."/class.ipblocklist.php");
	$checklist = new IpBlockList($baned_ips);
	
	$result = $checklist->ipPass( $visitor_ip );
	if(!$result)
	{
		//$msg = "You have been banned by the administrator: "."(".$checklist->status().") ".$checklist->message(); not need for all :)
		$msg = _YOU_ARE_BANED;
		die("<br><br><div class=\"text-center\"><img src='images/mtsn/mtsn.gif' width=\"146\" height=\"125\" alt=\"mtsn\" title=\"mtsn\"><br><br><b>$msg</b></div>");
	}
}

if($nuke_configs['mtsn_ddos_filter'] == 1 || !$pn_Bots->isCrawler())
{
	$mtsn_requests_pages = isset($nuke_configs['mtsn_requests_pages']) ? intval($nuke_configs['mtsn_requests_pages']):7;
	$mtsn_requests_mintime = isset($nuke_configs['mtsn_requests_mintime']) ? intval($nuke_configs['mtsn_requests_mintime']):2;
	$HTTP_X_REQUESTED_WITH = (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) ? $_SERVER['HTTP_X_REQUESTED_WITH']:"";
	
	
	if($pn_Sessions->exists('last_session_request') && $pn_Sessions->get('last_session_request', false) > (_NOWTIME - $mtsn_requests_mintime) && $HTTP_X_REQUESTED_WITH != "XMLHttpRequest")
	{
		if(!$pn_Sessions->exists('last_request_count'))
			$pn_Sessions->set('last_request_count', 1);
		elseif($pn_Sessions->get('last_request_count', false) < $mtsn_requests_pages)
			$pn_Sessions->set('last_request_count', ($pn_Sessions->get('last_request_count', false)+1));
		elseif($pn_Sessions->get('last_request_count', false) >= $mtsn_requests_pages)
			die("<br><br><div class=\"text-center\"><b>Sorry, too many page loads in so little time!</b></div>");
	}
	else
		$pn_Sessions->set('last_request_count', 1);

	$pn_Sessions->set('last_session_request', _NOWTIME);
}

?>