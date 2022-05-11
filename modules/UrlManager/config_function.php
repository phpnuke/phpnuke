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
define("posts_URLS_TABLE", $pn_prefix."_posts_urls");

function UrlManger_redirect($post_url)
{
	global $db;
	
	$post_url_encoded = rawurlencode_map($post_url);

	$result = $db->query("SELECT * FROM ".POSTS_META_TABLE." WHERE meta_part = 'old_urls' AND (meta_key = '$post_url' OR meta_key = '$post_url_encoded')");

	if($result->count() > 0)
	{
		$row = $result->results()[0];
		
		$meta_value = phpnuke_unserialize($row['meta_value']);
		$new_url = $meta_value['url'];
		$redirect_code = $meta_value['code'];
		$post_id = $row['post_id'];
		
		$gone_mode = ($redirect_code == "410") ? 0:"";
		
		if($post_id == 0)
			redirect_to(trim($new_url, "/")."/", $redirect_code, $gone_mode);
		else
			redirect_to(articleslink($post_id), $redirect_code, $gone_mode);
		
		die();
	}
}

function post_manage_related_urls($post_url, $REQUESTURL, $main_module)
{
	global $db;
	
	$post_url = ($main_module != 'Articles') ? strtolower($main_module)."/".$post_url:$post_url;
	$post_url = trim($post_url, "/");
	
	UrlManger_redirect($post_url);
}

$hooks->add_filter("post_url_related", "post_manage_related_urls", 10);

function theme_die_error_action($error_message)
{
	global $db;
	
	$post_url = trim($_SERVER['REQUEST_URI'], "/");
	
	if($post_url != '')
	{
		UrlManger_redirect($post_url);
	}
}

$hooks->add_action("die_error_action", "theme_die_error_action", 10);

function post_before_update_manage_urls($sid, $items, $article_fields)
{
	global $db, $sid;
	
	$result = $db->table(POSTS_TABLE)
				->where('sid', $sid)
				->select();
	$row = $result->results()[0];
	
	$old_url = $row['post_url'];
	
	if($items['post_url'] != $old_url)
	{
		$post_url_encoded = rawurlencode_map($items['post_url']);
		
		$dup_result = $db->query("SELECT * FROM ".POSTS_META_TABLE." WHERE meta_part = 'old_urls' AND (meta_key = '".$items['post_url']."' OR meta_key = '$post_url_encoded')");
		
		if($dup_result->count() > 0)
		{
			$dup_row = $dup_result->results()[0];
			$dup_mid = $dup_row['mid'];
			
			$db->table(POSTS_META_TABLE)
				->where('mid', $dup_mid)
				->delete();
		}
		
		$post_id = $sid;
		
		$errors = array();
		
		$old_url_encoded = rawurlencode_map($old_url);
		
		$result = $db->query("SELECT mid FROM ".POSTS_META_TABLE." WHERE meta_part = 'old_urls' AND post_id = '$post_id' AND (meta_key = '$old_url' OR meta_key = '$old_url_encoded')");
		
		$meta_value = array(
			"code" => 410,
			"url" => '',
		);
		
		$data = array(
			"meta_part" => "old_urls",
			"post_id" => $post_id,
			"meta_key" => trim((($items['post_type'] != 'Articles') ? strtolower($items['post_type'])."/".$old_url:$old_url), "/"),
			"meta_value" => phpnuke_serialize($meta_value),
		);
		
		if($result->count() == 0)
		{
			$db->table(POSTS_META_TABLE)
				->insert($data);
		}
	}
}

$hooks->add_filter("post_before_update", "post_before_update_manage_urls", 10);

?>