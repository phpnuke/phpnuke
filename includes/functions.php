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

if (!function_exists("floatval"))
{
    function floatval($inputval)
	{
        return (float)$inputval;
    }
}

// We want to use the function stripos,
// but thats only available since PHP5.
// So we cloned the function...
if (!function_exists('stripos'))
{
	function stripos_clone($haystack, $needle, $offset = 0)
	{
		$return = strpos(strtoupper($haystack), strtoupper($needle), $offset);
		if($return === false)
		{
			return false;
		}
		else
		{
            return true;
        }
    }
}
else
{
    // But when this is PHP5, we use the original function
    function stripos_clone($haystack, $needle, $offset = 0)
	{
        $return = stripos($haystack, $needle, $offset = 0);
        if($return === false)
		{
            return false;
        }
		else
		{
            return true;
        }
    }
}

function nukeversion()
{
	global $nuke_configs;
	$nukeversion = $nuke_configs['Version_Num'];
	return $nukeversion;
}

function nuke_set_cookie($name, $cookiedata, $cookietime, $httponly = false)
{
	global $nuke_configs;
	if($cookiedata === false){
		$cookietime = _NOWTIME-3600;
	}
	$name_data = rawurlencode($name) . '=' . rawurlencode($cookiedata);
	$expire = gmdate('D, d-M-Y H:i:s \\G\\M\\T', $cookietime);
	if (!headers_sent())
	{
		header('Set-Cookie: '.$name_data.(($cookietime) ? '; expires='.$expire : '').'; path='.$nuke_configs['sitecookies'].';' . (($httponly) ? ' HttpOnly' : ''), false);
	}
}

function is_ajax()
{
	return (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
}
	
function get_client_ip()
{
	$get_nav = false;
	
	if(function_exists("getenv"))
		$get_nav = true;
		
	$ipaddress = '';
	if (($get_nav && getenv('HTTP_CLIENT_IP')) || isset($_SERVER['HTTP_CLIENT_IP']))
		$ipaddress = ($get_nav) ? getenv('HTTP_CLIENT_IP'):$_SERVER['HTTP_CLIENT_IP'];
	else if(($get_nav && getenv('HTTP_X_FORWARDED_FOR')) || isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ipaddress = ($get_nav) ? getenv('HTTP_X_FORWARDED_FOR'):$_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(($get_nav && getenv('HTTP_X_FORWARDED')) || isset($_SERVER['HTTP_X_FORWARDED']))
		$ipaddress = ($get_nav) ? getenv('HTTP_X_FORWARDED'):$_SERVER['HTTP_X_FORWARDED'];
	else if(($get_nav && getenv('HTTP_X_CLUSTER_CLIENT_IP')) || isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
		$ipaddress = ($get_nav) ? getenv('HTTP_X_CLUSTER_CLIENT_IP'):$_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
	else if(($get_nav && getenv('HTTP_FORWARDED_FOR')) || isset($_SERVER['HTTP_FORWARDED_FOR']))
		$ipaddress = ($get_nav) ? getenv('HTTP_FORWARDED_FOR'):$_SERVER['HTTP_FORWARDED_FOR'];
	else if(($get_nav && getenv('HTTP_FORWARDED')) || isset($_SERVER['HTTP_FORWARDED']))
		$ipaddress = ($get_nav) ? getenv('HTTP_FORWARDED'):$_SERVER['HTTP_FORWARDED'];
	else if(($get_nav && getenv('REMOTE_ADDR')) || isset($_SERVER['REMOTE_ADDR']))
		$ipaddress = ($get_nav) ? getenv('REMOTE_ADDR'):$_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';

	$ipaddress = adv_filter($ipaddress, array(), array('valid_ip'));
	if($ipaddress[0] == 'success')
		return $ipaddress[1];
	else
		return 'UNKNOWN';
}

// cache functions
function cache_system($mode = "", $extra_code=array())
{
	global $db, $cache, $cache_systems, $users_system, $pn_Sessions;

	$args = array_slice(func_get_args(), 2);
	$exept_caches = (isset($args[0])) ? $args[0]:array();
	$new_cache = false;
		
	if(isset($extra_code) && !empty($extra_code))
	{
		if(isset($extra_code['general']) && $extra_code['general'] != '')
			eval($extra_code['general']);
		if(!empty($extra_code['phpcodes']))
		{
			foreach($extra_code['phpcodes'] as $mode_name => $phpcode)
			{
				//nuke_configs
				if((!$cache->isCached($mode_name) OR in_array($mode, array("$mode_name",'all'))) && !in_array("$mode_name", $exept_caches))
				{
					if(!$cache->isCached($mode_name))
						$new_cache = true;
					$extra_codes_rows = array();
					eval($phpcode);
					
					$nuke_extra_cacheData = (!empty($extra_codes_rows)) ? $extra_codes_rows:array();
					$cache->store("$mode_name", $nuke_extra_cacheData);
					/*file_put_contents("cache/cache_nuke_configs.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_configs_cacheData);*/
				}
			}
		}
	}
	else
	{
		//nuke_configs
		if((!$cache->isCached("nuke_configs") OR in_array($mode, array('nuke_configs','all'))) && !in_array('nuke_configs', $exept_caches))
		{
			$config_rows = array();
			$results = $db->table(CONFIG_TABLE)
						->select();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_configs"))
					$new_cache = true;
				foreach($results as $nuke_configs_row)
				{
					$config_rows[$nuke_configs_row['config_name']] = $nuke_configs_row['config_value'];
				}
				unset($results);
			}
			$nuke_configs_cacheData = (!empty($config_rows)) ? $config_rows:array();
			$cache->store('nuke_configs', $nuke_configs_cacheData);
			/*file_put_contents("cache/cache_nuke_configs.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_configs_cacheData);*/
		}
		
		//nuke_admins_menu
		if((!$cache->isCached("nuke_admins_menu") OR in_array($mode, array('nuke_admins_menu','all'))) && !in_array('nuke_admins_menu', $exept_caches))
		{
			$results = $db->table(ADMINS_MENU_TABLE)
						->select();
			$nuke_admins_menu = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_admins_menu"))
					$new_cache = true;
				foreach($results as $row)
				{
					$amid = $row['amid'];
					unset($row['amid']);
					$nuke_admins_menu[$amid] = $row;
				}
				unset($results);
			}
			$nuke_admins_menu_cacheData = (!empty($nuke_admins_menu)) ? $nuke_admins_menu:array();
			$cache->store('nuke_admins_menu', $nuke_admins_menu_cacheData);
			/*file_put_contents("cache/cache_nuke_admins_menu.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_admins_menu_cacheData);*/
		}
		
		//nuke_authors	
		if((!$pn_Sessions->exists('nuke_authors') OR in_array($mode, array('nuke_authors','all'))) && !in_array('nuke_authors', $exept_caches))
		{
			$results = $db->table(AUTHORS_TABLE)
						->select();
			$nuke_authors = array();
			if($db->count() > 0)
			{
				if(!$pn_Sessions->exists('nuke_authors'))
					$new_cache = true;
				foreach($results as $row)
				{
					$aid = $row['aid'];
					unset($row['aid']);
					$nuke_authors[$aid] = $row;
				}
				unset($results);
			}
			$nuke_authors_cacheData = (!empty($nuke_authors)) ? $nuke_authors:array();
			$pn_Sessions->set('nuke_authors', $nuke_authors_cacheData);
			/*file_put_contents("cache/cache_nuke_authors.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_authors_cacheData);*/
		}
		
		//nuke_banners
		/*if((!$cache->isCached("nuke_banner") OR in_array($mode, array('nuke_banners','all'))) && !in_array('nuke_banners', $exept_caches))
		{
			$results = $db->query("SELECT b.*, bc.name as client_name, bc.contact, bc.email, bc.extrainfo FROM ".BANNER_TABLE." AS b LEFT JOIN ".BANNER_TABLE."_clients AS bc ON b.cid = bc.cid ORDER BY b.bid ASC");
			
			$banners = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_banner"))
					$new_cache = true;
				foreach($results as $row)
				{
					$bid = $row['bid'];
					unset($row['bid']);
					$banners[$bid] = $row;
				}
				unset($results);
			}
			$nuke_banners_cacheData = (!empty($banners)) ? $banners:array();
			$cache->store('nuke_banner', $nuke_banners_cacheData);
			//file_put_contents("cache/cache_nuke_banner.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_banners_cacheData);
		}*/
		
		//nuke_blocks
		if((!$cache->isCached("nuke_blocks") OR in_array($mode, array('nuke_blocks','all'))) && !in_array('nuke_blocks', $exept_caches))
		{
			$results = $db->table(BLOCKS_TABLE)
						->order_by(['bid'=> 'ASC'])
						->select();
			$nuke_blocks = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_blocks"))
					$new_cache = true;
				foreach($results as $row)
				{
					$bid = $row['bid'];
					unset($row['bid']);
					$nuke_blocks['blocks'][$bid] = $row;
				}
				unset($results);
			}
			
			if(!empty($nuke_blocks))
			{
				$results = $db->table(BLOCKS_BOXES_TABLE)
							->order_by(['box_id'=> 'ASC'])
							->select();

				if($db->count() > 0)
				{
					foreach($results as $row)
					{
						$box_id = $row['box_id'];
						$box_blocks = (isset($row['box_blocks']) && $row['box_blocks'] != '') ? explode(",", $row['box_blocks']):array();
						$box_blocks_data = (isset($row['box_blocks_data']) && $row['box_blocks_data'] != '') ? phpnuke_unserialize(stripslashes($row['box_blocks_data'])):array();
						
						unset($row['box_blocks']);
						unset($row['box_blocks_data']);
						
						$nuke_blocks['blocks_boxes'][$box_id] = $row;
						if(!empty($box_blocks) && !empty($box_blocks_data))
							foreach($box_blocks as $bid)
								if(isset($box_blocks_data[$bid]) && isset($nuke_blocks['blocks'][$bid]))
								{
									$nuke_blocks['blocks_boxes'][$box_id]['blocks'][$bid] = array_merge($nuke_blocks['blocks'][$bid], $box_blocks_data[$bid]);
								}
					}
					unset($results);
				}
				
				$nuke_blocks_cacheData = (!empty($nuke_blocks)) ? $nuke_blocks:array();
				$cache->store('nuke_blocks', $nuke_blocks_cacheData);
				/*file_put_contents("cache/cache_nuke_blocks.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_blocks_cacheData);*/
			}
		}
		
		//nuke_bookmarksite
		if((!$cache->isCached("nuke_bookmarksite") OR in_array($mode, array('nuke_bookmarksite','all'))) && !in_array('nuke_bookmarksite', $exept_caches))
		{
			$results = $db->table(BOOKMARKSITE_TABLE)
						->order_by(['bid' => 'ASC'])
						->select();
			$bookmarksite = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_bookmarksite"))
					$new_cache = true;
				foreach($results as $row)
				{
					$bid = $row['bid'];
					unset($row['bid']);
					$bookmarksite[$bid] = $row;
				}
				unset($results);
			}
			
			$nuke_bookmarksite_cacheData = (!empty($bookmarksite)) ? $bookmarksite:array();
			$cache->store('nuke_bookmarksite', $nuke_bookmarksite_cacheData);
			/*file_put_contents("cache/cache_nuke_bookmarksite.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_bookmarksite_cacheData);*/
		}
		
		//nuke_categories
		if((!$cache->isCached("nuke_categories") OR in_array($mode, array('nuke_categories','all'))) && !in_array('nuke_categories', $exept_caches))
		{
			$results = $db->table(CATEGORIES_TABLE)
						->order_by(['catid' => 'ASC'])
						->select();
						
			$categories = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_categories"))
					$new_cache = true;
				foreach($results as $row)
				{
					$catid = $row['catid'];
					$module = $row['module'];
					$categories[$module][$catid] = $row;
					$categories[$module][$catid]['catname_url'] = filter(sanitize(str2url($row['catname'])), "nohtml");
				}
				unset($results);
			}
			
			$nuke_categories_cacheData = (!empty($categories)) ? $categories:array();
			$cache->store('nuke_categories', $nuke_categories_cacheData);
			/*file_put_contents("cache/cache_nuke_categories.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_categories_cacheData);*/
		}
		
		//nuke_points_groups
		if((!$cache->isCached("nuke_points_groups") OR in_array($mode, array('nuke_points_groups','all'))) && !in_array('nuke_points_groups', $exept_caches))
		{
			$results = $db->table(POINTS_GROUPS_TABLE)
						->order_by(['id' => 'ASC'])
						->select();
						
			$nuke_points_groups = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_points_groups"))
					$new_cache = true;
				foreach($results as $row)
				{
					$id = $row['id'];
					unset($row['id']);
					$nuke_points_groups[$id] = $row;
				}
				unset($results);
			}
			
			$nuke_points_groups_cacheData = (!empty($nuke_points_groups)) ? $nuke_points_groups:array();
			$cache->store('nuke_points_groups', $nuke_points_groups_cacheData);
			/*file_put_contents("cache/cache_nuke_points_groups.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_points_groups_cacheData);*/
		}
		
		//nuke_nav_menus
		if((!$cache->isCached("nuke_nav_menus") OR in_array($mode, array('nuke_nav_menus','all'))) && !in_array('nuke_nav_menus', $exept_caches))
		{
			$results = $db->query("SELECT nd.*,nd.status as item_status,n.*, n.status as nav_status FROM ".NAV_MENUS_DATA_TABLE." AS nd LEFT JOIN ".NAV_MENUS_TABLE." AS n ON n.nav_id = nd.nav_id ORDER BY nd.weight ASC, nd.nid ASC");

			$nuke_nav_menus = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_nav_menus"))
					$new_cache = true;
				foreach($results as $row)
				{
					$nav_status = intval($row['nav_status']);
					$item_status = intval($row['item_status']);
					
					if($nav_status == 1)
					{
						$nav_id = intval($row['nav_id']);
						$nav_location = filter($row['nav_location'], "nohtml");
						$lang_nav_title = ($row['lang_nav_title'] != '') ? phpnuke_unserialize(stripslashes($row['lang_nav_title'])):array();

						$nid = $row['nid'];
						
						$nuke_nav_menus[$nav_location][$nav_id]['nav_title'] =  filter($row['nav_title'], "nohtml");
						$nuke_nav_menus[$nav_location][$nav_id]['nav_id'] = $nav_id;
						$nuke_nav_menus[$nav_location][$nav_id]['date'] = $row['date'];
						$nuke_nav_menus[$nav_location][$nav_id]['lang_nav_title'] = $lang_nav_title;
						unset($row['nav_id'], $row['nav_title'], $row['lang_nav_title'], $row['nav_location'], $row['date'],  $row['nav_status']);
						$row['attributes'] = ($row['attributes'] != '') ? phpnuke_unserialize(stripslashes($row['attributes'])):array();
						if($item_status > 0)
							$nuke_nav_menus[$nav_location][$nav_id]['nav_items'][$nid] = $row;
					}
				}
				unset($results);
			}
			
			$nuke_nav_menus_cacheData = (!empty($nuke_nav_menus)) ? $nuke_nav_menus:array();
			$cache->store('nuke_nav_menus', $nuke_nav_menus_cacheData);
			/*file_put_contents("cache/cache_nuke_nav_menus.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_nav_menus_cacheData);*/
		}
		
		//nuke_headlines
		if((!$cache->isCached("nuke_headlines") OR in_array($mode, array('nuke_headlines','all'))) && !in_array('nuke_headlines', $exept_caches))
		{
			$results = $db->table(HEADLINES_TABLE)
						->order_by(['hid' => 'ASC'])
						->select();
						
			$nuke_headlines = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_headlines"))
					$new_cache = true;
				foreach($results as $row)
				{
					$hid = $row['hid'];
					unset($row['hid']);
					$nuke_headlines[$hid] = $row;
				}
				unset($results);
			}
			
			$nuke_headlines_cacheData = (!empty($nuke_headlines)) ? $nuke_headlines:array();
			$cache->store('nuke_headlines', $nuke_headlines_cacheData);
			/*file_put_contents("cache/cache_nuke_headlines.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_headlines_cacheData);*/
		}
		
		//nuke_modules
		if((!$cache->isCached("nuke_modules") OR in_array($mode, array('nuke_modules','all'))) && !in_array('nuke_modules', $exept_caches))
		{
			$results = $db->table(MODULES_TABLE)
						->order_by(['mid' => 'ASC'])
						->select();
						
			$nuke_modules = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_modules"))
					$new_cache = true;
				foreach($results as $row)
				{
					$mid = $row['mid'];
					unset($row['mid']);
					$nuke_modules[$mid] = $row;
				}
				unset($results);
			}
			
			$nuke_modules_cacheData = (!empty($nuke_modules)) ? $nuke_modules:array();
			$cache->store('nuke_modules', $nuke_modules_cacheData);
			/*file_put_contents("cache/cache_nuke_modules.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_modules_cacheData);*/
		}
		
		//nuke_languages
		if((!$cache->isCached("nuke_languages") OR in_array($mode, array('nuke_languages','all'))) && !in_array('nuke_languages', $exept_caches))
		{
			$results = $db->table(LANGUAGES_TABLE)
						->order_by(['lid' => 'ASC'])
						->select();
						
			$nuke_languages = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_languages"))
					$new_cache = true;
				foreach($results as $row)
				{
					$main_word = $row['main_word'];
					unset($row['main_word']);
					$nuke_languages[$main_word] = array("lid" => intval($row['lid']), "equals" => (($row['equals'] != "") ? phpnuke_unserialize(stripslashes($row['equals'])):array()));
				}
				unset($results);
			}
			
			$nuke_languages_cacheData = (!empty($nuke_languages)) ? $nuke_languages:array();
			$cache->store('nuke_languages', $nuke_languages_cacheData);
			/*file_put_contents("cache/cache_nuke_languages.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_languages_cacheData);*/
		}
		
		//nuke_modules_friendly_urls
		if((!$cache->isCached("nuke_modules_friendly_urls") OR in_array($mode, array('nuke_modules_friendly_urls','all'))) && !in_array('nuke_modules_friendly_urls', $exept_caches))
		{
			global $friendly_links, $rewrite_rule;
			if(isset($friendly_links) && is_array($friendly_links) && !empty($friendly_links))
			{
				if(!$cache->isCached("nuke_modules_friendly_urls"))
					$new_cache = true;
				foreach($friendly_links as $friendly_link_key => $friendly_links_val){
					$friendly_urls['urlins'][] = "#^$friendly_link_key#";
					$friendly_urls['urlouts'][] = preg_replace("#/+#", "/", preg_replace("#([^/]+)//#" ,"/", $friendly_links_val));
				}
			}
			unset($friendly_links);
			
			$handle=opendir('modules');
			$modules_list = array();
			while($mfile = readdir($handle))
			{
				if(is_dir("modules/$mfile") && $mfile != '.' && $mfile != '..' && $mfile != 'Articles')
					$modules_list[] = $mfile;
			}
			closedir($handle);
			sort($modules_list);
			foreach($modules_list as $modules_name) {
				if(file_exists("modules/$modules_name/GT-$modules_name.php"))
				{
					@include("modules/$modules_name/GT-$modules_name.php");
					if(isset($friendly_links) && is_array($friendly_links) && !empty($friendly_links))
					{
						foreach($friendly_links as $friendly_link_key => $friendly_links_val){
							$friendly_urls['urlins'][] = "#^$friendly_link_key#";
							$friendly_urls['urlouts'][] = preg_replace("#/+#", "/", preg_replace("#([^/]+)//#" ,"/", $friendly_links_val));
						}
					}
				}
				unset($friendly_links);
			}
			
			if(file_exists("modules/Articles/GT-Articles.php"))
			{
				@include("modules/Articles/GT-Articles.php");
				if(isset($friendly_links) && is_array($friendly_links) && !empty($friendly_links))
				{
					foreach($friendly_links as $friendly_link_key => $friendly_links_val){
						$friendly_urls['urlins'][] = "#^$friendly_link_key#";
						$friendly_urls['urlouts'][] = preg_replace("#/+#", "/", preg_replace("#([^/]+)//#" ,"/", $friendly_links_val));
					}
				}
			}
				
			$nuke_modules_friendly_urls_cacheData = (!empty($friendly_urls) && !empty($rewrite_rule)) ? array($friendly_urls, $rewrite_rule):array();
			$cache->store('nuke_modules_friendly_urls', $nuke_modules_friendly_urls_cacheData);
			/*file_put_contents("cache/cache_nuke_modules_friendly_urls.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_modules_friendly_urls_cacheData);*/
		}
		
		//nuke_mtsn_ipban
		if((!$cache->isCached("nuke_mtsn_ipban") OR in_array($mode, array('nuke_mtsn_ipban','all'))) && !in_array('nuke_mtsn_ipban', $exept_caches))
		{
			$results = $db->table(MTSN_IPBAN_TABLE)
						->order_by(['id' => 'ASC'])
						->select();

			$nuke_mtsn_ipbans = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_mtsn_ipban"))
					$new_cache = true;
				foreach($results as $row)
				{
					$id = $row['id'];
					unset($row['id']);
					$nuke_mtsn_ipbans[$id] = $row;
				}
				unset($results);
			}
			
			$nuke_mtsn_ipban_cacheData = (!empty($nuke_mtsn_ipbans)) ? $nuke_mtsn_ipbans:array();
			$cache->store('nuke_mtsn_ipban', $nuke_mtsn_ipban_cacheData);
			/*file_put_contents("cache/cache_nuke_mtsn_ipban.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_mtsn_ipban_cacheData);*/
		}
		
		//nuke_surveys
		if((!$cache->isCached("nuke_surveys") OR in_array($mode, array('nuke_surveys','all'))) && !in_array('nuke_surveys', $exept_caches))
		{			
			$results = $db->table(SURVEYS_TABLE)
						->order_by(['pollID' => 'ASC'])
						->select();
						
			$nuke_mtsn_ipbans = array();
			if($db->count() > 0)
			{
				if(!$cache->isCached("nuke_surveys"))
					$new_cache = true;
				foreach($results as $row)
				{
					$pollID = $row['pollID'];
					$options = stripslashes($row['options']);
					$options = phpnuke_unserialize($options);
					$row['options'] = $options;
					unset($row['pollID']);
					$nuke_surveys[$pollID] = $row;
				}
				unset($results);
			}
			
			$nuke_surveys_cacheData = (!empty($nuke_surveys)) ? $nuke_surveys:array();
			$cache->store('nuke_surveys', $nuke_surveys_cacheData);
			/*file_put_contents("cache/cache_nuke_surveys.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_surveys_cacheData);*/
		}
		
		//nuke_subscriptions
		/*if((!$cache->isCached("nuke_subscriptions") OR in_array($mode, array('nuke_subscriptions','all'))) && !in_array('nuke_subscriptions', $exept_caches))
		{
			if(!$cache->isCached("nuke_subscriptions"))
				$new_cache = true;
			$results = $db->table(SUBSCRIPTIONS_TABLE)
						->order_by(['id' => 'ASC'])
						->select();
						
			$nuke_subscriptions = array();
			if($db->count() > 0)
			{
				foreach($results as $row)
				{
					$id = $row['id'];
					unset($row['id']);
					$nuke_subscriptions[$id] = $row;
				}
				unset($results);
			}
			
			$nuke_subscriptions_cacheData = (!empty($nuke_subscriptions)) ? $nuke_subscriptions:array();
			$cache->store('nuke_subscriptions', $nuke_subscriptions_cacheData);
			//file_put_contents("cache/cache_nuke_subscriptions.php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$nuke_subscriptions_cacheData);
		}*/

		if(is_array($cache_systems) && !empty($cache_systems))
		{
			foreach($cache_systems as $cache_system_name => $cache_system)
			{
				if($cache_system_name != "" && isset($cache_system['main_id']) && $cache_system['main_id'] != "" && isset($cache_system['table']) && $cache_system['table'] != "")
				{
					if((!$cache->isCached($cache_system_name) OR in_array($mode, array("$cache_system_name",'all'))) && !in_array("$cache_system_name", $exept_caches))
					{
						if(isset($cache_system['fetch_type']))
						{
							$db->setFetchType($cache_system['fetch_type']);
						}
						
						$results = $db->query("SELECT * FROM ".$cache_system['table']."".((isset($cache_system['where'])&& $cache_system['where'] != '') ? " WHERE ".$cache_system['where']."":"")."".((isset($cache_system['order'])&& $cache_system['order'] != '') ? " ORDER BY ".$cache_system['main_id']." ".$cache_system['order']."":"")."");
						
						$this_data_array = array();
							
						if(isset($cache_system['first_code']))
							eval($cache_system['first_code']);

						if($db->count() > 0)
						{
							if(!$cache->isCached($cache_system_name))
								$new_cache = true;
							foreach($results as $row)
							{
								$this_main_id = $row[$cache_system['main_id']];
								
								$this_data_array[$this_main_id] = $row;
								
								if(isset($cache_system['loop_code']))
								{
									eval($cache_system['loop_code']);
								}
							}
							unset($results);
						}
						
						if(isset($cache_system['end_code']))
							eval($cache_system['end_code']);
							
						$this_cacheData = (!empty($this_data_array)) ? $this_data_array:array();
						$cache->store($cache_system_name, $this_cacheData);
						/*file_put_contents("cache/cache_".$cache_system_name.".php", '<?php if(!defined("NUKE_FILE")) exit;?>'.$this_cacheData);*/
					}
				}
				elseif($cache->isCached($cache_system_name) && in_array($mode, array("$cache_system_name",'all')) && !in_array("$cache_system_name", $exept_caches))
				{
					$cache->erase($cache_system_name);
				}
			}
		}
	}
	if($new_cache && !defined("IN_FLUSH"))
	{
		// Request URL Redirect To Nuke Url
		$Req_Protocol 	= strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
		$Req_Host     	= $_SERVER['HTTP_HOST'];
		$Req_Uri		= $_SERVER['REQUEST_URI'];
		$Req_URI		= $Req_Protocol . '://' . $Req_Host . $Req_Uri;
		redirect_to($Req_URI);
	}
}

function get_cache_file_contents($cache_file, $secure=false, $recache=false)
{
	global $cache, $pn_Sessions;
	if($recache)
		cache_system($cache_file);
		
	$data = array();
	
	if($secure && $pn_Sessions->exists($cache_file))
		$data = $pn_Sessions->get($cache_file, false);
	else
		$data = $cache->retrieve($cache_file);

	return $data;
}
// cache functions

//serialize
function phpnuke_unserialize( $original )
{
	if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize( $original );
	return $original;
}

function is_serialized( $data, $strict = true )
{
	// if it isn't a string, it isn't serialized
	if ( ! is_string( $data ) )
		return false;
	$data = trim( $data );
 	if ( 'N;' == $data )
		return true;
	$length = strlen( $data );
	if ( $length < 4 )
		return false;
	if ( ':' !== $data[1] )
		return false;
	if ( $strict ) {
		$lastc = $data[ $length - 1 ];
		if ( ';' !== $lastc && '}' !== $lastc )
			return false;
	}
	else
	{
		$semicolon = strpos( $data, ';' );
		$brace     = strpos( $data, '}' );
		// Either ; or } must exist.
		if ( false === $semicolon && false === $brace )
			return false;
		// But neither must be in the first X characters.
		if ( false !== $semicolon && $semicolon < 3 )
			return false;
		if ( false !== $brace && $brace < 4 )
			return false;
	}
	$token = $data[0];
	switch ( $token )
	{
		case 's' :
			if ( $strict ) {
				if ( '"' !== $data[ $length - 2 ] )
					return false;
			}
			elseif ( false === strpos( $data, '"' ) )
			{
				return false;
			}
			// or else fall through
		case 'a' :
		case 'O' :
			return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b' :
		case 'i' :
		case 'd' :
			$end = $strict ? '$' : '';
			return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
	}
	return false;
}

function is_serialized_string( $data )
{
	// if it isn't a string, it isn't a serialized string
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	$length = strlen( $data );
	if ( $length < 4 )
		return false;
	elseif ( ':' !== $data[1] )
		return false;
	elseif ( ';' !== $data[$length-1] )
		return false;
	elseif ( $data[0] !== 's' )
		return false;
	elseif ( '"' !== $data[$length-2] )
		return false;
	else
		return true;
}

function phpnuke_serialize( $data ) {
	if ( is_array( $data ) || is_object( $data ) )
		return serialize( $data );

	if ( is_serialized( $data, false ) )
		return serialize( $data );

	return $data;
}

function is_base64_encoded($data)
{
	return (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data));
}
//serialize

// configs functions
function update_configs( $config_name, $config_value )
{
	global $db, $nuke_configs;

	$config_name = trim($config_name);
	if ( empty($config_name) )
		return false;

	if ( is_object( $config_value ) )
		$config_value = clone $config_value;

	$old_value = (isset($nuke_configs[$config_name])) ? $nuke_configs[$config_name]:false;

	// If the new and old values are the same, no need to update.
	if ( $config_value === $old_value )
		return false;

	if ( false === $old_value )
		return add_configs( $config_name, $config_value );

	$serialized_value = phpnuke_serialize( $config_value );

	$result = $db->table(CONFIG_TABLE)
		->where('config_name', $config_name)
		->update([
			'config_value' => $serialized_value
		]);
	
	if ( ! $result )
		return false;
	cache_system("nuke_configs");
	return true;
}

function add_configs( $config_name, $config_value = '')
{
	global $db, $nuke_configs;

	$config_name = trim($config_name);
	if ( empty($config_name) )
		return false;

	if ( is_object($config_value) )
		$config_value = clone $config_value;

	if ( !is_array( $nuke_configs ) || !isset( $nuke_configs[$config_name] ) )
		if (isset($nuke_configs[$config_name]) && false !== $nuke_configs[$config_name] )
			return false;

	$serialized_value = phpnuke_serialize( $config_value );

	$result = $db->table(CONFIG_TABLE)
		->insert([
			'config_name' => $config_name,
			'config_value' => $serialized_value
		]);
		
	if ( ! $result )
		return false;
	cache_system("nuke_configs");
	return true;
}
// configs functions

// captcha functions
function makePass($cid, $custum_options = array(), $google_recaptcha = false)
{
	global $nuke_configs;
	
	$security_code = array("image" => "", "input" => "");
	
	if(($nuke_configs['seccode_type'] == 2 || $google_recaptcha) && $nuke_configs['google_recaptcha_sitekey'] != '')
	{
		$security_code['input'] = '';
		$security_code['image'] = "<script src=\"https://www.google.com/recaptcha/api.js?hl="._GOOGLE_RECAPTCHA_LANG."\"></script>
		<div id=\"security_code_$cid\" class=\"security_code_container\"><div class=\"g-recaptcha\" data-sitekey=\"".$nuke_configs['google_recaptcha_sitekey']."\" data-size=\"compact\"></div></div>";
	}
	elseif(extension_loaded("gd") && $nuke_configs['seccode_type'] == 1)
	{
		$options = array(
			"height" => (isset($custum_options['height']) ? $custum_options['height']:50),
			"img_attr" => array(
				"id" => (isset($custum_options['img_attr']['id']) ? $custum_options['img_attr']['id']:"capchaimage".$cid),
				"style" => "cursor: pointer;".(isset($custum_options['img_attr']['style']) ? $custum_options['img_attr']['style']:""),
				"title" => _CLICKFORREFRESH.(isset($custum_options['img_attr']['title']) ? $custum_options['img_attr']['title']:""),
				"alt" => _SECCODE.(isset($custum_options['img_attr']['alt']) ? $custum_options['img_attr']['alt']:""),
			),
			"input_attr" => array(
				"id" => (isset($custum_options['input_attr']['id']) ? $custum_options['input_attr']['id']:"security_code".$cid),
			)
		);
		
		if(isset($custum_options['img_attr']['style']))
			unset($custum_options['img_attr']['style']);
		if(isset($custum_options['img_attr']['title']))
			unset($custum_options['img_attr']['title']);
		if(isset($custum_options['img_attr']['alt']))
			unset($custum_options['img_attr']['alt']);
		if(isset($custum_options['img_attr']['id']))
			unset($custum_options['img_attr']['id']);
		if(isset($custum_options['input_attr']['id']))
			unset($custum_options['input_attr']['id']);
		
		if(isset($custum_options['height']))
			unset($custum_options['height']);
			
		
		if(!empty($custum_options))
			$options = array_merge_recursive($options, $custum_options);
		
		$img_attrs = $input_attrs = array();
		foreach($options['img_attr'] as $key => $value)
			$img_attrs[] = ($value != '') ? "$key=\"$value\"":"$key";
		foreach($options['input_attr'] as $key => $value)
			$input_attrs[] = ($value != '') ? "$key=\"$value\"":"$key";
		
		$img_attrs = implode(" ", $img_attrs);
		$input_attrs = implode(" ", $input_attrs);
				
		$security_code['image'] = "<img src=\"".$nuke_configs['nukeurl']."index.php?captcha=true&sid=".md5(uniqid(_NOWTIME))."&id=$cid&language=".$nuke_configs['currentlang']."&height=".$options['height']."\" onclick=\"document.getElementById('capchaimage".$cid."').src = '".$nuke_configs['nukeurl']."index.php?captcha=true&sid=' + Math.random()+'&id=$cid&language=".$nuke_configs['currentlang']."&height=".$options['height']."'; return false\" ".$img_attrs." />";
		$security_code['input'] = "<input name=\"security_code\" type=\"text\" ".$input_attrs." /><input name=\"security_code_id\" type=\"hidden\" value=\"$cid\">";
	}
	return($security_code);
}

function code_check($security_code, $security_code_id, $google_recaptcha = false)
{
	global $nuke_configs, $visitor_ip;
	
	$g_recaptcha = ($nuke_configs['google_recaptcha_sitekey'] != '' && $nuke_configs['google_recaptcha_secretkey'] != '' && ($nuke_configs['seccode_type'] == 2 || $google_recaptcha)) ? true:false;
	
	if($g_recaptcha)
	{
		if(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] != '')
		{
			$g_recaptcha_response = filter($_POST['g-recaptcha-response'], "nohtml");
			if (function_exists('curl_version'))
			{
				require_once(INCLUDE_PATH.'/Curl/Curl.php');
				require_once(INCLUDE_PATH.'/Curl/CaseInsensitiveArray.php');
				
				$curl = new Curl();
				$curl->setUserAgent('');
				$curl->setReferrer('');
				$curl->setHeader('X-Requested-With', 'XMLHttpRequest');
				$curl->post("https://www.google.com/recaptcha/api/siteverify", array(
					"secret" => $nuke_configs['google_recaptcha_secretkey'],
					"response" => $g_recaptcha_response,
					"remoteip" => $visitor_ip,
				));
				$response = $curl->response;
				
				if(!is_object($response) && $response != '')
					$response = json_decode($response);
				
				return ($response->success == 1) ? true:false;
			}
			elseif (extension_loaded('sockets') && function_exists('fsockopen') )
			{
				@require_once(INCLUDE_PATH."/class.HttpFsockopen.php");
				$object = new HttpFsockopen("https://www.google.com/recaptcha/api/siteverify");
				$object->setTimeout(15);
				$object->setPostData(array(
					"secret" => $nuke_configs['google_recaptcha_secretkey'],
					"response" => $g_recaptcha_response,
					"remoteip" => $visitor_ip,
				));
				
				$response = $object->exec();
				if($response->getErrno())
					return false;
				else
				{
					$response = $response->getContent();
				
					if($response != '')
						$response = json_decode($response);
					
					return ($response->success == 1) ? true:false;
				}
			}
			return false;		
		}
		return false;
	}	
	elseif (extension_loaded("gd") && $nuke_configs['seccode_type'] == 1 && !$google_recaptcha)
	{
		include("includes/captcha/securimage.php");
		$img = new Securimage();
		$img->setNamespace($security_code_id);
		if ($img->check($security_code) == true && !empty($security_code))
		{
			return true;
		}
		return false;
	}
		
	return true;
}
// captcha functions

// permissions functions
function is_admin($admin_id = '')
{
    global $nuke_authors_cacheData, $admin; 
    if (!$admin) { return 0; }

    $aid = $admin[0];
    $hmac = $admin[1];
    $expiration = $admin[2];
    $aid = substr(addslashes($aid), 0, 25);
    if (!empty($aid) && !empty($hmac)) {
        if (phpnuke_validate_user_cookie($admin, "admin"))
		{
        	return true;
        }
    }
    return false;
}

function is_an_admin($admin_id = '')
{
    global $nuke_authors_cacheData, $admin; 
	
	if(isset($admin_id) && $admin_id != '')
	{
		return (isset($nuke_authors_cacheData[$admin_id])) ? true:false;
	}
}

function is_God($admin_id='')
{
    global $nuke_authors_cacheData, $admin;
	if($admin_id == '')
	{
		if(!is_admin()) return false;
		$aid = $admin[0];
	}
	else
	{
		$aid = $admin_id;
	}
	
	if($nuke_authors_cacheData[$aid]['name'] == 'God')
		return true;
	else
		return false;
}

function is_user($username='')
{
	global $userinfo, $users_system;
	if($username != '')
	{
		$result = $db->query("SELECT ".$users_system->user_fields['user_id']." as user_id FROM ".$users_system->users_table." WHERE ".$users_system->user_fields['username']." = '$username' ".(($users_system->user_fields['common_where'] != '') ? " AND ".$users_system->user_fields['common_where']:"")."");
		$num_result = intval($result->count());
		if($num_result > 0)
			return true;
	}
	else
	{
		if(isset($userinfo) && !empty($userinfo))
		{
			if(isset($userinfo['user_id']) && (isset($userinfo['is_registered']) && $userinfo['is_registered'] == true))
				return true;
		}
	}
	return false;
}

function is_in_group($group_id = 0, $other_userinfo=array())
{
	global $nuke_points_groups_cacheData, $userinfo;
     if (is_user())
	 {
          $points = (!empty($other_userinfo) && isset($other_userinfo['points'])) ? intval($other_userinfo['points']):intval($userinfo['points']);
          $grp = intval($nuke_points_groups_cacheData[$group_id]['points']);
          if (($points >= 0 AND $points >= $grp) OR $group_id == 0)
		  {
        	return 1;
          }
     }
     return 0;
}

function get_groups_permissions()
{
	$nuke_forum_groups_cacheData = get_cache_file_contents('nuke_forum_groups');
	$permissions = array(
		"0" => _ALLVISITORS,// all visitors
		"1" => _GUESTS,// who userinfo['user_id'] = 0;
		"2" => _BOTS,// who userinfo['user_type'] = 2;
		"3" => _MEMBERS,// who userinfo['user_id'] = 1;
		"4" => _SPECIAL_USERS,// who userinfo['user_special'] = 1;
		"5" => _ADMINS,// who is_admin();
	);
	if(!empty($nuke_forum_groups_cacheData))
	{
		foreach($nuke_forum_groups_cacheData as $group_id => $group_info)
		{
			if($group_info['group_type'] == 3)
				continue;
			
			$permissions = array_merge($permissions, array($group_id => $group_info['group_name']));
		}
	}
	return $permissions;
}

function phpnuke_permissions_check($permissions)
{
	global $userinfo, $pn_Bots;
	
	$allow_to_view = false;
	$disallow_message_arr = array();
	$disallow_message_gr = "";
	$disallow_message = ""._SECTION_SPECIALTO." ";
	
	if (
		(in_array(0, $permissions)) || 
		(in_array(1, $permissions) && isset($userinfo['is_registered']) &&  intval($userinfo['is_registered']) == 0) || 
		(in_array(2, $permissions) && isset($userinfo['is_bot']) && intval($userinfo['is_bot']) == 1) || 
		(in_array(3, $permissions) && isset($userinfo['is_registered']) && intval($userinfo['is_registered']) == 1) || 
		(in_array(4, $permissions) && isset($userinfo['is_special']) && is_paid($userinfo['is_special'])) || 
		(in_array(5, $permissions) && is_admin()) || 
		(isset($userinfo['group_id']) && in_array($userinfo['group_id'], $permissions)) 
	)// this visitors can view this module
	{
		$allow_to_view = true;
	}
	
	if(is_array($permissions) && empty($permissions) && is_God())
		$allow_to_view = true;
		
	if(in_array(1, $permissions) && intval($userinfo['user_id']) != 0)
		$disallow_message_arr[] = _GUESTS;
	if(in_array(2, $permissions) && ((isset($userinfo['user_type']) && intval($userinfo['user_type']) != 2) || !$pn_Bots->isCrawler()))
		$disallow_message_arr[] = _SEARCH_ENGINS;
	if(in_array(3, $permissions) && isset($userinfo['user_id']) && intval($userinfo['user_id']) == 0)
		$disallow_message_arr[] = _SITE_MEMBERS;
	if(in_array(4, $permissions) && isset($userinfo['user_special']) && intval($userinfo['user_special']) == 0)
		$disallow_message_arr[] = _SITE_SPECIAL_MEMBERS;
	if(in_array(5, $permissions) && !is_admin())
		$disallow_message_arr[] = _ADMINS;
	if(isset($userinfo['group_id']) && !in_array($userinfo['group_id'], $permissions))
		$disallow_message_gr = _NOT_IN_ALLOWED_GROUPS;
	
	$disallow_message = $disallow_message.((!empty($disallow_message_arr)) ? implode(" "._AND." ", $disallow_message_arr):"").".".$disallow_message_gr;
	
	return array($allow_to_view, $disallow_message);
}

// permissions functions

// outputs functions
function title($text)
{
	$contents = '';
	$contents .= (defined("ADMIN_FILE")) ? OpenAdminTable():OpenTable();
	$contents .= "<div class=\"text-center\"><span class=\"title\"><strong>$text</strong></span></div>";
	$contents .= (defined("ADMIN_FILE")) ? CloseAdminTable():CloseTable();
	$contents .= "<br>";
	return $contents;
}

function info_box($graphic, $message)
{
	global $nuke_configs;
	$contents = '';
	// Function to generate a message box with a graphic inside
	// $graphic value can be whichever: warning, caution, tip, note.
	// Then the graphic value with the extension .gif should be present inside /images/system/ folder
	if (file_exists("images/system/".$graphic.".gif") AND !empty($message))
	{
		$contents .= Opentable();
		$graphic = filter($graphic, "nohtml");
		$message = filter($message, "");
		$contents .= "<table align=\"center\" border=\"0\" width=\"80%\" cellpadding=\"10\">
			<tr>
				<td valign=\"top\"><img src=\"".$nuke_configs['nukecdnurl']."images/system/".$graphic.".gif\" border=\"0\" alt=\"\" title=\"\" width=\"34\" height=\"34\"></td>
				<td valign=\"top\">$message</td>
			</tr>
		</table>";
		$contents .= CloseTable();
	}
		
	return $contents;
}

function simple_output($message)
{
	include("header.php");
	$html_output .= OpenTable();
	$html_output .= $message;
	$html_output .= CloseTable();
	include("footer.php");
	die();
}
// outputs functions

// db functions
function get_unique_post_slug($table, $id_name, $id_value, $field, $slug, $post_status = '', $ajax=false, $where = '')
{
	$slug = sanitize(str2url($slug));
	if (in_array($post_status, array( 'draft', 'pending' )))
	{
		if($ajax)
			die($slug);
		return $slug;
	}
	
	global $db;

	$original_slug = $slug;

	// Post slugs must be unique across all posts.
	$result = $db->query("SELECT $field FROM $table WHERE $field = '$slug'".(($id_value != 0) ? " AND $id_name != '$id_value'":"")." $where LIMIT 1");
	
	$post_name_check = intval($result->count());

	if ( $post_name_check)
	{
		$suffix = 2;
		do
		{
			$alt_post_name = _truncate_post_slug($slug, 200-(strlen($suffix)+1))."-$suffix";
			$result2 = $db->query("SELECT $field FROM $table WHERE $field = '$alt_post_name'".(($id_value != 0) ? " AND $id_name != '$id_value' $where":"")." LIMIT 1");
			$post_name_check = intval($result2->count());
			$suffix++;
		}
		while($post_name_check);
		$slug = $alt_post_name;
	}
	$slug = trim($slug, "-");
	if($ajax)
		die($slug);
	return $slug;
}

function phpnuke_db_error()
{
	global $db;
	$errors = $db->getErrors();
	$error_result = "";
	if(is_admin() && is_array($errors) && !empty($errors))
	{
		$error_result .= "<p align=\"center\"><span style=\"color:#ff0000;font-weight:bold;\">"._ERROR_IN_OP."</span>";
		foreach($errors as $key => $val)
		{
			$error_result .= "<br /><br />"._DBERROR_CODE." : ".$val['code']."<br /><br />"._DBERROR_MSG." : ".$val['message']."<br /><br />"._DBERROR_QUERY." : <pre><code>".$val['query']."</code></pre>";
		}
		$error_result .= "</p>";
		die($error_result);
	}
}
// db functions

// modules functions
function is_active($module)
{
    global $db;
    static $save;
    if (is_array($save)) {
        if (isset($save[$module])) return ($save[$module]);
        return 0;
    }
	
	$result = $db->table(MODULES_TABLE)
					->where('title', $module)
					->where('active', 1)
					->first(['title']);
					
	if($result->count() > 0) return ($result['title']);
    return 0;
}

function is_index_file($module_name)
{
	global $nuke_modules_cacheData;
	
	foreach($nuke_modules_cacheData as $mid => $module_info)
	{
		if($module_info['title'] == $module_name && $module_info['all_blocks'] == 1)
		{
			return true;
			break;
		}
	}
	return false;
}

function show_modules_boxes($module_name, $part='index', $active_boxes=array(), $html_output="prev", $special_bids=array(), $except_bids=array())
{
	global $nuke_modules_cacheData, $theme_setup;
	
	$theme_boxes_templates = $theme_setup['theme_boxes_templates'];
	$output = "";
	
	$nuke_modules_cacheData_by_title = phpnuke_array_change_key($nuke_modules_cacheData, "mid", "title");
	
	$all_module_boxes = ($nuke_modules_cacheData_by_title[$module_name]['module_boxes'] != '') ? phpnuke_unserialize(stripslashes($nuke_modules_cacheData_by_title[$module_name]['module_boxes'])):array();
	
	$all_module_boxes = (isset($all_module_boxes[$part])) ? explode("|", $all_module_boxes[$part]):array();
	
	$all_boxes = array('','','','','','');
	foreach($all_module_boxes as $key => $module_boxes)
	{
		$module_boxes = explode(",", $module_boxes);
		$all_boxes[$key] = $module_boxes;
	}
	//$all_boxes = array_filter($all_boxes);
	
	foreach($all_boxes as $key => $val)
	{
		$all_boxes[$key] = (is_array($val)) ? array_filter($val):"";
	}
	
	$middle_module_boxes = "";

	if((is_array($all_boxes[0]) && !empty($all_boxes[0])) && (is_array($all_boxes[1]) && !empty($all_boxes[1])))
	{
		if(in_array("right", $active_boxes))
		{
			$middle_module_boxes .= "_r";
		}
		if(in_array("left", $active_boxes))
		{
			$middle_module_boxes .= "_l";
		}
	}
	elseif((is_array($all_boxes[0]) && !empty($all_boxes[0])) && (!is_array($all_boxes[1]) || empty($all_boxes[1])))
	{
		if(in_array("right", $active_boxes))
		{
			$middle_module_boxes .= "_r";
		}
	}
	elseif((!is_array($all_boxes[0]) || empty($all_boxes[0])) && (is_array($all_boxes[1]) && !empty($all_boxes[1])))
	{
		if(in_array("left", $active_boxes))
		{
			$middle_module_boxes .= "_l";
		}
	}
	$middle_module_boxes = str_replace("_r_l", "_l_r", $middle_module_boxes);
	
	$middle_module_boxes_class = "middle_module_boxes".$middle_module_boxes;
	
	
	$sides_output = array();
	
		$output = "<div class=\"modules_boxes ".$theme_boxes_templates['modules_boxes']['extra_class']."\">";
		if(in_array("top_full", $active_boxes) && is_array($all_boxes[4]) && !empty($all_boxes[4]))
		{
			$output .= "<div class=\"top_full_moldule_boxes ".$theme_boxes_templates['top_full_moldule_boxes']['extra_class']."\">";
			foreach($all_boxes[4] as $top_full_moldule_boxes)
			{
				$output .= blocks($top_full_moldule_boxes, $special_bids, $except_bids);
			}
			$output .= "</div>
			<div class=\"Clear\"></div>";
		}
		
		if(in_array("right", $active_boxes) && is_array($all_boxes[0]) && !empty($all_boxes[0]))
		{
			$right_extra_class = (isset($theme_boxes_templates['right_module_boxes']['extra_class'][$middle_module_boxes])) ? " ".$theme_boxes_templates['right_module_boxes']['extra_class'][$middle_module_boxes]:"";
			$right_pull_class = (isset($theme_boxes_templates['right_module_boxes']['pull'][$middle_module_boxes])) ? " ".$theme_boxes_templates['right_module_boxes']['pull'][$middle_module_boxes]:"";
			$right_push_class = (isset($theme_boxes_templates['right_module_boxes']['push'][$middle_module_boxes])) ? " ".$theme_boxes_templates['right_module_boxes']['push'][$middle_module_boxes]:"";
			$right_order = (isset($theme_boxes_templates['right_module_boxes']['order'][$middle_module_boxes])) ? $theme_boxes_templates['right_module_boxes']['order'][$middle_module_boxes]:0;
			
			$sides_output[$right_order] = "<div class=\"right_module_boxes".$right_extra_class."".$right_pull_class."".$right_push_class."\">";
			foreach($all_boxes[0] as $right_module_boxes)
			{
				$sides_output[$right_order] .= blocks($right_module_boxes, $special_bids, $except_bids);
			}
			$sides_output[$right_order] .= "</div>";
		}
		
		if(in_array("left", $active_boxes) && is_array($all_boxes[1]) && !empty($all_boxes[1]))
		{
			$left_extra_class = (isset($theme_boxes_templates['left_module_boxes']['extra_class'][$middle_module_boxes])) ? " ".$theme_boxes_templates['left_module_boxes']['extra_class'][$middle_module_boxes]:"";
			$left_pull_class = (isset($theme_boxes_templates['left_module_boxes']['pull'][$middle_module_boxes])) ? " ".$theme_boxes_templates['left_module_boxes']['pull'][$middle_module_boxes]:"";
			$left_push_class = (isset($theme_boxes_templates['left_module_boxes']['push'][$middle_module_boxes])) ? " ".$theme_boxes_templates['left_module_boxes']['push'][$middle_module_boxes]:"";
			$left_order = (isset($theme_boxes_templates['left_module_boxes']['order'][$middle_module_boxes])) ? $theme_boxes_templates['left_module_boxes']['order'][$middle_module_boxes]:0;
			
			$sides_output[$left_order] = "<div class=\"left_module_boxes".$left_extra_class."".$left_pull_class."".$left_push_class."\">";
			foreach($all_boxes[1] as $left_module_boxes)
			{
				$sides_output[$left_order] .= blocks($left_module_boxes, $special_bids, $except_bids);
			}
			$sides_output[$left_order] .= "</div>";
		}
		
		$middle_extra_class = (isset($theme_boxes_templates['middle_module_boxes']['extra_class'][$middle_module_boxes])) ? " ".$theme_boxes_templates['middle_module_boxes']['extra_class'][$middle_module_boxes]:"";
		$middle_pull_class = (isset($theme_boxes_templates['middle_module_boxes']['pull'][$middle_module_boxes])) ? " ".$theme_boxes_templates['middle_module_boxes']['pull'][$middle_module_boxes]:"";
		$middle_push_class = (isset($theme_boxes_templates['middle_module_boxes']['push'][$middle_module_boxes])) ? " ".$theme_boxes_templates['middle_module_boxes']['push'][$middle_module_boxes]:"";

		$middle_order = (isset($theme_boxes_templates['middle_module_boxes']['order'][$middle_module_boxes])) ? $theme_boxes_templates['middle_module_boxes']['order'][$middle_module_boxes]:0;
		
		$sides_output[$middle_order] = "<div class=\"$middle_module_boxes_class".$middle_extra_class."".$middle_pull_class."".$middle_push_class."\">";

			if(in_array("top_middle", $active_boxes) && is_array($all_boxes[2]) && !empty($all_boxes[2]))
			{
				$sides_output[$middle_order] .= "<div class=\"top_middle_moldule_boxes ".$theme_boxes_templates['top_middle_moldule_boxes']['extra_class']."\">";
				foreach($all_boxes[2] as $top_middle_moldule_boxes)
				{
					$sides_output[$middle_order] .= blocks($top_middle_moldule_boxes, $special_bids, $except_bids);
				}
				$sides_output[$middle_order] .= "</div>";
			}
		
			$sides_output[$middle_order] .= "<div class=\"main_middle_moldule_boxes ".$theme_boxes_templates['main_middle_moldule_boxes']['extra_class']."\">";
			$sides_output[$middle_order] .= $html_output;
			

			$sides_output[$middle_order] .= "</div>";
						
			if(in_array("bottom_middle", $active_boxes) && is_array($all_boxes[3]) && !empty($all_boxes[3]))
			{
				$sides_output[$middle_order] .= "<div class=\"Clear\"></div><div class=\"bottom_middle_moldule_boxes ".$theme_boxes_templates['bottom_middle_moldule_boxes']['extra_class']."\">";
				foreach($all_boxes[3] as $bottom_middle_moldule_boxes)
				{
					$sides_output[$middle_order] .= blocks($bottom_middle_moldule_boxes, $special_bids, $except_bids);
				}
				$sides_output[$middle_order] .= "</div>";
			}
		$sides_output[$middle_order] .= "</div>";
		
		ksort($sides_output);
		foreach($sides_output as $sides_output_html)
			$output .= $sides_output_html;
		
		if(in_array("bottom_full", $active_boxes) && is_array($all_boxes[5]) && !empty($all_boxes[5]))
		{
			$output .= "<div class=\"Clear\"></div>
			<div class=\"bottom_full_moldule_boxes ".$theme_boxes_templates['bottom_full_moldule_boxes']['extra_class']."\">";
			foreach($all_boxes[5] as $bottom_full_moldule_boxes)
			{
				$output .= blocks($bottom_full_moldule_boxes, $special_bids, $except_bids);
			}
			$output .= "</div>
			<div class=\"Clear\"></div>";
		}
		$output .= "</div>
		<div class=\"Clear\"></div>";
	
	return $output;
}

function bulid_meta_fields($meta_part = 'Articles', $data)
{
	global $nuke_meta_keys_parts, $nuke_configs;
	$contents = '';
	
	require_once( INCLUDE_PATH.'/class.form-builder.php' );
	$form = new PhpFormBuilder();
	$form->set_att('form_element', false);
	$form->set_att('add_submit', false);
	
	if(isset($nuke_meta_keys_parts[$meta_part]) && !empty($nuke_meta_keys_parts[$meta_part]))
	{
		$form->add_inputs($nuke_meta_keys_parts[$meta_part]);

		$contents = $form->build_form($data, false);
	}
	
	return $contents;
}

function insert_update_meta_fields($data, $id = 0, $meta_part = "Articles")
{
	global $db, $nuke_meta_keys_parts, $nuke_configs;
	$contents = '';
	$id = intval($id);
	if($id == 0)
		return;
	
	$meta_keys = array_keys($nuke_meta_keys_parts[$meta_part]);
	
	$insert_keys = $update_keys = $old_meta_keys = array();
	
	$result = $db->table(POSTS_META_TABLE)
				->where('post_id', $id)
				->where('meta_part', strtolower($meta_part))
				->select();
	if($result->count() > 0)
	{
		$rows = $result->results();
		foreach($rows as $row)
		{
			$mid = intval($row['mid']);
			$meta_key = filter($row['meta_key'], "nohtml");
			
			$old_meta_keys[$mid] = $meta_key;
			
			if(in_array($meta_key, $meta_keys))
				$update_keys[$mid] = $meta_key;
		}
		foreach($meta_keys as $meta_key)
		{
			if(!in_array($meta_key, $old_meta_keys))
				$insert_keys[] = $meta_key;
		}
		
		if(!empty($update_keys))
		{
			foreach($update_keys as $mid => $meta_key)
			{
				$db->table(POSTS_META_TABLE)
					->where('post_id', $id)
					->where('mid', $mid)
					->where('meta_key', $meta_key)
					->update([
						"meta_value" => (($data[$meta_key] == '' || $data[$meta_key] === null || !isset($data[$meta_key])) ? '':$data[$meta_key])
					]);
			}
		}
	}
	else
		foreach($meta_keys as $meta_key)
			$insert_keys[] = $meta_key;
			
	if(!empty($insert_keys))
	{
		foreach($insert_keys as $meta_key)
		{
			$db->table(POSTS_META_TABLE)
				->insert([
					"post_id" => $id,
					"meta_part" => strtolower($meta_part),
					"meta_key" => $meta_key,
					"meta_value" => (($data[$meta_key] == '' || $data[$meta_key] === null || !isset($data[$meta_key])) ? '':$data[$meta_key])
				]);
		}
	}
	return true;
}
// modules functions

// widgets, boxess and blocks functions
function admin_blocks_box_theme($title, $content, $theme_block)
{
	global $db, $align, $nuke_configs;
	$html_out = '';
	if($theme_block == "")
	{
		$html_out = "<div class=\"Block\">
		  <div class=\"TitleBlock\">
			<h5 class=\"TitleBlockText\">
			  $title
			</h5>
		  </div>
		  <div class=\"BodyBlock\">
			<div class=\"BodyBlockText\">
			  $content
			</div>
		  </div>
		</div>";
	}
	else
	{
		include("themes/".$nuke_configs['ThemeSel']."/blocks/themes/$theme_block");
	}
	return $html_out;
}

function boxes($widget_id, $special_box_ids = array(), $except_box_ids = array(), $special_bids = array(), $except_block_files = array())
{
	global $nuke_configs, $theme_setup;
	$theme_widgets = (isset($theme_setup['theme_widgets'])) ? $theme_setup['theme_widgets']:array();
	$nuke_blocks_cacheData = get_cache_file_contents('nuke_blocks');

	if(!defined("BLOCK_FILE"))
		define("BLOCK_FILE", true);
	
	$contents = '';
	$widgets = array();
	
	if($widget_id == '')
		return '';
	
	if(!isset($theme_widgets[$widget_id]))
		return '';
		
	foreach($nuke_blocks_cacheData['blocks_boxes'] as $box_id => $box_data)
	{
		if($box_data['box_theme_location'] != $widget_id)
			continue;
		
		if(isset($special_box_ids) && !empty($special_box_ids) && !in_array($box_id, $special_box_ids))
			continue;
		
		if(isset($except_box_ids) && !empty($except_box_ids) && in_array($box_id, $except_box_ids))
			continue;
		if(isset($widgets[$widget_id][$box_data['box_theme_priority']]))
			$widgets[$widget_id][$box_data['box_theme_priority']] .= blocks($box_id,$special_bids, $except_block_files);
		else
			$widgets[$widget_id][$box_data['box_theme_priority']] = blocks($box_id,$special_bids, $except_block_files);
			
	}
	if(!empty($widgets))
	{
		ksort($widgets[$widget_id]);
		$contents = implode("\n", $widgets[$widget_id]);
	}
	return $contents;	
}

function blocks($box_id, $special_bids = array(), $except_block_files = array())
{
	global $nuke_configs, $db, $admin, $user;
	$nuke_blocks_cacheData = get_cache_file_contents('nuke_blocks');

	$box_blocks = (isset($nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'])) ? $nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks']:array();

	if(!defined("BLOCK_FILE"))
		define("BLOCK_FILE", true);
		
	$contents = '';
	foreach($box_blocks as $bid => $block_info)
	{
		if(isset($special_bids) && !empty($special_bids) && !in_array($bid, $special_bids))
			continue;
			
		if(isset($except_block_files) && !empty($except_block_files) && in_array($block_info['blockfile'], $except_block_files))
			continue;
		
		$block_active = intval($block_info['active']);
		if($block_active != 1) continue;
		
		if ($nuke_configs['multilingual'] == 1)
			if($block_info['blanguage'] != '' && $block_info['blanguage'] != $nuke_configs['currentlang'])
				continue;
				
		if ($nuke_configs['multilingual'] == 1)
		{
			$block_info['lang_titles'] = ($block_info['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($block_info['lang_titles'])):array($nuke_configs['currentlang'] => $block_info['title']);

			$block_title = filter($block_info['lang_titles'][$nuke_configs['currentlang']], "nohtml");
		}
		else
			$block_title = filter($block_info['title'], "nohtml");
		
		$content = stripslashes($block_info['content']);
		$blockfile = filter($block_info['blockfile'], "nohtml");
		$block_permissions = ($block_info['permissions'] !== '') ? explode(",", $block_info['permissions']):array(0);
		$now = _NOWTIME;
		$theme_block = filter($block_info['theme_block'], "nohtml");
		
		$block_allow_to_view = false;
		$block_disallow_message = "";
		
		$block_permission_result = phpnuke_permissions_check($block_permissions);
		
		$block_allow_to_view = $block_permission_result[0];
		$block_disallow_message = $block_permission_result[1];
		
		if($block_allow_to_view)
		{
			if($blockfile != '')
			{
				if(file_exists("themes/".$nuke_configs['ThemeSel']."/blocks/".$blockfile.""))
					include("themes/".$nuke_configs['ThemeSel']."/blocks/".$blockfile."");
				elseif(file_exists("blocks/".$blockfile.""))
					include("blocks/".$blockfile."");
				else
					$content = _BLOCKPROBLEM;
			}

			if ($content == '')
			{
				$content = _BLOCKNOCONTENTS;
			}
			
			$contents .= (defined("ADMIN_FILE")) ? admin_blocks_box_theme($block_title, $content, $theme_block):blocks_box_theme($block_title, $content, $theme_block);
		}
	}
	unset($nuke_blocks_cacheData);
	unset($box_blocks);
	unset($content);
	return $contents;
}

function update_blocks()
{
	global $nuke_configs, $db, $admin, $user, $nuke_modules_cacheData;
	
	$nuke_blocks_cacheData = get_cache_file_contents('nuke_blocks');
	
	$changes = false;
	
	if(!isset($nuke_blocks_cacheData['blocks_boxes']) || empty($nuke_blocks_cacheData['blocks_boxes']))
		return;
	
	foreach($nuke_blocks_cacheData['blocks_boxes'] as $box_id => $box_data)
	{
		if(isset($box_data['blocks']) && !empty($box_data['blocks']))
		{
			foreach($box_data['blocks'] as $bid => $block_data)
			{
				$url = filter($block_data['url'], "nohtml");
				$blockfile = intval($block_data['blockfile']);
				$active = intval($block_data['active']);
				$publish = intval($block_data['publish']);
				$refresh = intval($block_data['refresh']);
				$last_refresh = intval($block_data['last_refresh']);
				$expire = intval($block_data['expire']);
				$action = filter($block_data['action'], "nohtml");
				$action = substr($action, 0,1);
				$now = _NOWTIME;
				
				if($url != '' && $active == 1)
				{
					if($last_refresh <= $now)
					{
						$new_refresh_time = (($last_refresh == '') ? $now:$last_refresh) + ($refresh*60);
						$rss_content = get_rss_contents($url, 10);
						$content = '';
						foreach($rss_content as $rss_item)
						{
							$content .= "<a href=\"".$rss_item['link']."\" class=\"nuke_rss_content\" title=\"".$rss_item['title']."\">".$rss_item['title']."</a>\n";
						}
						$db->table(BLOCKS_TABLE)
							->where('bid', $bid)
							->update([
								'content' => $content,
								'last_refresh' => $new_refresh_time
							]);
						$changes = true;
					}
				}
				
				if($publish != 0 && $publish <= $now)
				{
					$nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['active'] = 1;
					$nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['publish'] = '';

					$changes = true;
				}
				
				if($expire != 0 && $expire <= $now)
				{
					if($action == 'd')
					{
						$nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['active'] = 0;
						$nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['expire'] = '';
						$nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['publish'] = '';
					}
					else
						if($blockfile != '')
						{
							$nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['active'] = 0;
							$nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['expire'] = 0;
							$nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['publish'] = 0;
							$nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['permissions'] = 0;
							$db->table(BLOCKS_TABLE)
								->where('bid', $bid)
								->update([
									'refresh' => 0,
									'last_refresh' => 0,
									'content' => '',
									'url' => ''
								]);
						}
						else
						{
							unset($nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]);
							$db->table(BLOCKS_TABLE)
								->where('bid', $bid)
								->delete();
						}
					
					$changes = true;
				}
			}
		}
		
		if($changes)
		{
			$box_blocks = addslashes(phpnuke_serialize($nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks']));
			$db->table(BLOCKS_BOXES_TABLE)
				->where('box_id', $box_id)
				->update([
					'box_blocks' => $box_blocks
				]);
			cache_system("nuke_blocks");
		}
		
		$changes2 = false;
	
		if(isset($nuke_modules_cacheData) && !empty($nuke_modules_cacheData))
		{
			foreach($nuke_modules_cacheData as $mid => $module_info)
			{
				$this_changes = false;
				if($module_info['module_boxes'] != '')
				{
					$module_all_boxes = ($module_info['module_boxes'] != '') ? phpnuke_unserialize(stripslashes($module_info['module_boxes'])):array();
					foreach($module_all_boxes as $module_file_name => $module_boxes)
					{
						$module_file_data = explode("|", $module_boxes);
						foreach($module_file_data as $key => $boxes)
						{
							if($boxes != '')
							{
								$boxes = @explode(",", $boxes);
								foreach($boxes as $key2 => $box)
								{
									if(!isset($nuke_blocks_cacheData['blocks_boxes'][$box]))
									{
										unset($boxes[$key2]);
										$module_file_data[$key] = @implode(",", $boxes);
										$changes2 = true;
									}
								}
								unset($boxes);
							}
						}
						$module_all_boxes[$module_file_name] = implode("|", $module_file_data);
					}
				}
				if($changes2)
					$db->table(MODULES_TABLE)
						->where('mid', $mid)
						->update([
							'module_boxes' => addslashes(phpnuke_serialize($module_all_boxes))
						]);
			}
		}
		if($changes2)
			cache_system("nuke_modules");
	}
		
	unset($nuke_blocks_cacheData);
	unset($box_blocks);
	unset($content);
}
// widgets, boxess and blocks functions

// hash functions
function phpnuke_hash_password($password)
{
	global $phpnuke_hasher;

	if ( empty($phpnuke_hasher) )
	{
		require_once(INCLUDE_PATH.'/class.PasswordHash.php');
		// By default, use the portable hash from phpass
		$phpnuke_hasher = new PasswordHash(8, FALSE);
	}

	return $phpnuke_hasher->HashPassword( trim( $password ) );
}

function phpnuke_check_password($password, $hash)
{
	global $phpnuke_hasher;

	// If the hash is still md5...
	if ( strlen($hash) <= 32 && $hash != '' && $hash !== null)
	{
		$check = hash_equals( $hash, md5( $password ) );

		return $check;
	}

	// If the stored hash is longer than an MD5, presume the
	// new style phpass portable hash.
	if ( empty($phpnuke_hasher) )
	{
		require_once(INCLUDE_PATH.'/class.PasswordHash.php');
		// By default, use the portable hash from phpass
		$phpnuke_hasher = new PasswordHash(8, FALSE);
	}

	$check = $phpnuke_hasher->CheckPassword($password, $hash);

	return $check;
}

if(!function_exists("hash_equals"))
{
	function hash_equals( $a, $b )
	{
		$a_length = strlen( $a );
		if ( $a_length !== strlen( $b ) )
		{
			return false;
		}
		$result = 0;

		// Do not attempt to "optimize" this.
		for ( $i = 0; $i < $a_length; $i++ )
		{
			$result |= ord( $a[ $i ] ) ^ ord( $b[ $i ] );
		}

		return $result === 0;
	}
}
// hash functions

// editor functions
function wysiwyg_textarea($name, $value, $config = 'basic', $cols = 50, $rows = 10, $width = '100%', $height = '300px', $class = '')
{
    global $nuke_configs, $admin_file;
    // Don't waste bandwidth by loading WYSIWYG editor for crawlers
    if ($nuke_configs['nuke_editor'] == 0 or !isset($_COOKIE))
    {
        return "".base64_decode("PHRleHRhcmVhIG5hbWU9IiIgY29scz0iIiByb3dzPSIiPjwvdGV4dGFyZWE+")."";
    } else {
		switch($config)
		{
			default:
			case"comment":
			case"basic":
				$toolbar			= "[
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
					{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline' ] },
					{ name: 'paragraph', groups: [ 'blocks', 'align' ], items: [ '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
					{ name: 'links', items: [ 'Link', 'Unlink' ] },
					{ name: 'insert', items: [ 'Image', 'Smiley' ] },
					{ name: 'styles', items: [ 'FontSize' ] },
					{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
					{ name: 'others', items: [ '-' ] },
					{ name: 'about', items: [ 'About' ] }
				]";
				$extraPlugins		= array("autogrow");
				$plugins_configs	= array();
			break;
			case"PHPNukeUser":
				$toolbar			= "[
					{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
					{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
					{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
					'/',
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
					{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
					{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
					'/',
					{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
					{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
					{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
					{ name: 'others', items: [ '-' ] },
					{ name: 'about', items: [ 'About' ] }
				]";
				$extraPlugins		= array("autogrow");
				$plugins_configs	= array();
			break;
			case"PHPNukeAdmin":
				$toolbar			= "[
					{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
					{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
					{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
					{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
					'/',
					{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
					{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'centering_image', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
					{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
					'/',
					{ name: 'insert', items: [ 'CodeSnippet', 'Image', 'Flash', 'Html5audio', 'Video', 'aparat', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
					{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
					{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
					{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
					{ name: 'others', items: [ '-' ] },
					{ name: 'about', items: [ 'About' ] }
				]";
				$extraPlugins		= array("autogrow","centering_image","codesnippet","html5audio","video","aparat");
				$plugins_configs	= array(
					"codeSnippet_theme: 'monokai_sublime'",
					"image_prefillDimensions: false",
					 "'filebrowserFlashBrowseUrl': '".$admin_file.".php?op=media_browser&ckeditor=true'",
					 "'filebrowserImageBrowseUrl': '".$admin_file.".php?op=media_browser&ckeditor=true'",
					 //"'filebrowserFlashUploadUrl': 'imgupload.php'",
					 //"'filebrowserImageUploadUrl': 'imgupload.php'",
				 );
			break;
		}
		
		$ckeditor = "<script src=\"".$nuke_configs['nukecdnurl']."includes/editors/ckeditor/ckeditor.js\"></script>
		<textarea class=\"ckeditor$class\" cols=\"$cols\" id=\"editor_$name\" name=\"$name\" rows=\"$rows\">$value</textarea>
		<script>
			CKEDITOR.replace('editor_$name', {
				language : '".substr($nuke_configs['currentlang'], 0,2)."',
				contentsLangDirection : '"._DIRECTION."',
				dialog_buttonsOrder : '"._TEXTALIGN1."',
				customConfig: '',
				toolbar: $toolbar,
				width:' $width',
				".(is_admin() ? "allowedContent : true,":"")."
				extraAllowedContent : 'video [*]{*}(*);source [*]{*}(*);',
				height : '$height',
				".((!empty($extraPlugins)) ? "'extraPlugins':'".implode(",", $extraPlugins)."',\n":"")."
				".((!empty($plugins_configs)) ? "".implode(",\n", $plugins_configs).",\n":"")."
			});
		</script>";
		return $ckeditor;
    }
}
// editor functions

// other functions
function headlines($bid, $cenbox=0, $themetype)
{
	global $db;
	$bid = intval($bid);
	$row = $db->table(BLOCKS_TABLE)
		->where('bid', $bid)
		->first(['title', 'content', 'url', 'refresh', 'time']);
		
	$title = filter($row['title'], "nohtml");
	$content = filter($row['content']);
	$url = filter($row['url'], "nohtml");
	$refresh = intval($row['refresh']);
	$themetype = intval($row['blocks_sides']);
	$otime = $row['time'];
	$past = _NOWTIME-$refresh;
	$cont = 0;
	if ($otime < $past) {
		$btime = _NOWTIME;
		$rdf = parse_url($url);
		$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
		if (!$fp) {
			$content = "";
			$db->table(BLOCKS_TABLE)
				->where('bid', $bid)
				->update([
					'content' => $content,
					'time' => $btime
				]);
				
			$cont = 0;
			if ($cenbox == 0) {
				themesidebox($title, $content, $themeview, $themetype);
			} else {
				themecenterbox($title, $content, $themeview, $themetype);
			}
			return;
		}
		if ($fp) {
			if (!empty($rdf['query']))
			$rdf['query'] = "?" . $rdf['query'];

			fputs($fp, "GET " . $rdf['path'] . $rdf['query'] . " HTTP/1.0\r\n");
			fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
			$string	= "";
			while(!feof($fp)) {
				$pagetext = fgets($fp,300);
				$string .= chop($pagetext);
			}
			fputs($fp,"Connection: close\r\n\r\n");
			fclose($fp);
			$items = explode("</item>",$string);
			$content = "<font class=\"content\">";
			for ($i=0;$i<10;$i++) {
				$link = @preg_replace("#.*<link>#","",$items[$i]);
				$link = @preg_replace("#</link>.*#","",$link);
				$title2 = @preg_replace("#.*<title>#","",$items[$i]);
				$title2 = @preg_replace("#</title>.#*","",$title2);
				$title2 = stripslashes($title2);
				if (empty($items[$i]) AND $cont != 1) {
					$content = "";
					$db->table(BLOCKS_TABLE)
						->where('bid', $bid)
						->update([
							'content' => $content,
							'time' => $btime
						]);
					$cont = 0;
					if ($cenbox == 0) {
						themesidebox($title, $content, $themeview, $themetype);
					} else {
						themecenterbox($title, $content, $themeview=1, $themetype);
					}
					return;
				} else {
					if (strcmp($link,$title2) AND !empty($items[$i])) {
						$cont = 1;
						$content .= "<strong><big>&middot;</big></strong><a href=\"$link\" target=\"new\">$title2</a><br>\n";
					}
				}
			}

		}
		$db->table(BLOCKS_TABLE)
			->where('bid', $bid)
			->update([
				'content' => $content,
				'time' => $btime
			]);
	}
	$siteurl = str_replace("http://","",$url);
	$siteurl = explode("/",$siteurl);
	if (($cont == 1) OR (!empty($content))) {
		$content .= "<br><a href=\"http://$siteurl[0]\" target=\"blank\"><b>"._HREADMORE."</b></a></font>";
	} elseif (($cont == 0) OR (empty($content))) {
		$content = "<font class=\"content\">"._RSSPROBLEM."</font>";
	}
	if ($cenbox == 0) {
		themesidebox($title, $content, $themeview, $themetype);
	} else {
		themecenterbox($title, $content, $themeview=1, $themetype);
	}
}

function get_theme()
{
    global $user, $userinfo, $nuke_configs, $modname, $op;
    if (isset($ThemeSelSave)) return $ThemeSelSave;
    if (is_user() && ($modname != "Users" OR $op != "logout")) {
        if(empty($userinfo['theme'])) $userinfo['theme']=$nuke_configs['Default_Theme'];
        if(file_exists("themes/".$userinfo['theme']."/theme.php")) {
            $ThemeSel = "".$userinfo['theme']."";
        } else {
            $ThemeSel = "".$nuke_configs['Default_Theme']."";
        }
    } else {
        $ThemeSel = "".$nuke_configs['Default_Theme']."";
    }
    static $ThemeSelSave;
    $ThemeSelSave = $ThemeSel;
    return $ThemeSelSave;
}

function ads($position)
{
	global $db, $admin, $nuke_configs;
	$position = intval($position);
	if (is_paid()) {
		return;
	}
	$numrows = $db->sql_numrows($db->sql_query("SELECT * FROM ".BANNER_TABLE." WHERE position='$position' AND active='1'"));
	/* Get a random banner if exist any. */
	if ($numrows > 1) {
		$numrows = $numrows-1;
		mt_srand((double)microtime()*1000000);
		$bannum = mt_rand(0, $numrows);
	} else {
		$bannum = 0;
	}
	$sql = "SELECT bid, impmade, imageurl, clickurl, alttext FROM ".BANNER_TABLE." WHERE position='$position' AND active='1' LIMIT $bannum,1";
	$result = $db->sql_query($sql);
	list($bid, $impmade, $imageurl, $clickurl, $alttext) = $db->sql_fetchrow($result);
	$bid = intval($bid);
	$imageurl = filter($imageurl, "nohtml");
	$clickurl = filter($clickurl, "nohtml");
	$alttext = filter($alttext, "nohtml");
	$db->sql_query("UPDATE ".BANNER_TABLE." SET impmade=impmade+1 WHERE bid='$bid'");
	if($numrows > 0) {
		$sql2 = "SELECT cid, imptotal, impmade, clicks, date, ad_class, ad_code, ad_width, ad_height FROM ".BANNER_TABLE." WHERE bid='$bid'";
		$result2 = $db->sql_query($sql2);
		list($cid, $imptotal, $impmade, $clicks, $date, $ad_class, $ad_code, $ad_width, $ad_height) = $db->sql_fetchrow($result2);
		$cid = intval($cid);
		$imptotal = intval($imptotal);
		$impmade = intval($impmade);
		$clicks = intval($clicks);
		$ad_class = filter($ad_class, "nohtml");
		$ad_width = intval($ad_width);
		$ad_height = intval($ad_height);
		/* Check if this impression is the last one and print the banner */
		if (($imptotal <= $impmade) AND ($imptotal != 0)) {
			$db->sql_query("UPDATE ".BANNER_TABLE." SET active='0' WHERE bid='$bid'");
			$sql3 = "SELECT name, contact, email FROM ".BANNER_TABLE."_clients WHERE cid='$cid'";
			$result3 = $db->sql_query($sql3);
			list($c_name, $c_contact, $c_email) = $db->sql_fetchrow($result3);
			$c_name = filter($c_name, "nohtml");
			$c_contact = filter($c_contact, "nohtml");
			$c_email = filter($c_email, "nohtml");
			if (!empty($c_email)) {
				$from = "".$nuke_configs['sitename']." <".$nuke_configs['adminmail'].">";
				$to = "$c_contact <$c_email>";
				$message = _HELLO." $c_contact:\n\n";
				$message .= _THISISAUTOMATED."\n\n";
				$message .= _THERESULTS."\n\n";
				$message .= _TOTALIMPRESSIONS." $imptotal\n";
				$message .= _CLICKSRECEIVED." $clicks\n";
				$message .= _IMAGEURL." $imageurl\n";
				$message .= _CLICKURL." $clickurl\n";
				$message .= _ALTERNATETEXT." $alttext\n\n";
				$message .= _HOPEYOULIKED."\n\n";
				$message .= _THANKSUPPORT."\n\n";
				$message .= "- ".$nuke_configs['sitename']." "._TEAM."\n";
				$message .= "".$nuke_configs['nukeurl']."";
				$subject = "".$nuke_configs['sitename'].": "._BANNERSFINNISHED."";
				phpnuke_mail($to,$subject,$message);
			}
		}
		if ($ad_class == "code") {
			$ad_code = stripslashes(FixQuotes($ad_code));
			$ads = "<div class=\"text-center\">$ad_code</div>";
		} elseif ($ad_class == "flash") {
			$ads = "<div class=\"text-center\">
				<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\"
				codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0\"
				WIDTH=\"$ad_width\" HEIGHT=\"$ad_height\" id=\"$bid\">
				<PARAM NAME=movie VALUE=\"$imageurl\">
				<PARAM NAME=quality VALUE=high>
				<EMBED src=\"$imageurl\" quality=high WIDTH=\"$ad_width\" HEIGHT=\"$ad_height\"
				NAME=\"$bid\" ALIGN=\"\" TYPE=\"application/x-shockwave-flash\"
				PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\">
				</EMBED>
				</OBJECT>
				</div>";
		} else {
			$ads = "<div class=\"text-center\"><a href=\"index.php?op=ad_click&amp;bid=$bid\" target=\"_blank\"><img src=\"$imageurl\" border=\"0\" alt=\"$alttext\" title=\"$alttext\" width=\"$ad_width\" height=\"$ad_height\"></a></div>";
		}
	} else {
		$ads = "";	
	}
	return $ads;
}

function redir($content)
{
	global $nuke_configs;
	unset($location);
	$content = filter($content);
	$links = array();
	$hrefs = array();
	$pos = 0;
	while (!(($pos = strpos($content,"<",$pos)) === false)) {
		$pos++;
		$endpos = strpos($content,">",$pos);
		$tag = substr($content,$pos,$endpos-$pos);
		$tag = trim($tag);
		if (isset($location)) {
			if (!strcasecmp(strtok($tag," "),"/A")) {
				$link = substr($content,$linkpos,$pos-1-$linkpos);
				$links[] = $link;
				$hrefs[] = $location;
				unset($location);
			}
			$pos = $endpos+1;
		} else {
			if (!strcasecmp(strtok($tag," "),"A")) {
				if (@preg_match("#HREF[ \t\n\r\v]*=[ \t\n\r\v]*\"([^\"]*)\"#i",$tag,$regs));
				else if (@preg_match("#HREF[ \t\n\r\v]*=[ \t\n\r\v]*([^ \t\n\r\v]*)#i",$tag,$regs));
				else $regs[1] = "";
				if ($regs[1]) {
					$location = $regs[1];
				}
				$pos = $endpos+1;
				$linkpos = $pos;
			} else {
				$pos = $endpos+1;
			}
		}
	}
	for ($i=0; $i<sizeof($hrefs); $i++) {
		$url = urlencode($hrefs[$i]);
		$content = str_replace("<a href=\"$hrefs[$i]\">", "<a href=\"".$nuke_configs['nukeurl']."/index.php?url=$url\" target=\"_blank\">", $content);
	}
	return($content);
}

function codereplace($text,$sid, $title="")
{
	global $nuke_configs, $user, $admin;
	if($nuke_configs['show_effect'] == 1)
	{
		$patterns[] = "#\<img(.*?)src=\"(.*?)\"(.*?)\>#si";
		$replacements[] = "<a href=\"$2\" onMouseOver=\"SMR_setLink(this);\" target=\"_blank\" rel=\"lightbox$sid\"><img $1 src=\"$2\" alt=\"$title\" title=\"$title\" onLoad=\"SMR_resize(this);\" border=\"0\" $3 /></a>";

		if($nuke_configs['show_links'] == 0)
		{
			if((is_user()) OR (is_admin())){}else{
				$patterns[] = "#\<a (.*?)\>(.*?)</a>#si";
				$replacements[] = ""._JUSTMEMBERS."";
			}
		}
		$text = preg_replace($patterns, $replacements, $text);
	}
	
	return $text;
}

function clean_pagination($total_rows, $entries_per_page=20, $current_page, $link_to, $tagname="", $gtset_page_name = '')
{
	global $nuke_configs;
	
	$stages = $nuke_configs['pagination_number'];

	if($gtset_page_name == '')
		$str_page  = (strpos($link_to, "?") === false) ? "?page=%d" : "&page=%d";
	else
		$str_page  = $gtset_page_name;
		
	$tagname = ($tagname != "") ? "$tagname#":"";
	
	// Initial page num setup
	if ($current_page == 0)
	{
		$current_page = 1;
	}
	
	$prev = $current_page - 1;	
	$next = $current_page + 1;							
	$lastpage = ceil($total_rows/$entries_per_page);		
	$LastPagem1 = $lastpage - 1;					
	
	$paginate = '';
	
	if($lastpage > 1)
	{
		$paginate .= "<ul class='pagination'>";
		// Previous
		if ($current_page > 1){
			//$paginate.= "<li class=\"first\"><a href='".LinkToGT("$link_to")."'>&laquo; "._MT_FIRST."</a></li>";
			$paginate.= "<li class=\"prev\"><a href='".LinkToGT("$link_to".sprintf("$str_page",$prev)."")."'>&lsaquo; "._PREV."</a></li>";
		}else{
			//$paginate.= "<li class=\"disabled\">&laquo; "._MT_FIRST."</li>";
			$paginate.= "<li class=\"disabled\"><a>&lsaquo; "._PREV."</a></li>";
		}
		
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $current_page){
					$paginate.= "<li class=\"active\"><a>$counter</a></li>";
				}else{
					$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",$counter)."")."'>$counter</a></li>";
				}					
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($current_page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $current_page){
						$paginate.= "<li class=\"active\"><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",$counter)."")."'>$counter</a></li>";
					}					
				}
				$paginate.= "<li class=\"disabled\"><a>...</a></li>";
				$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",$LastPagem1)."")."'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",$lastpage)."")."'>$lastpage</a></li>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $current_page && $current_page > ($stages * 2))
			{
				$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",1)."")."'>1</a></li>";
				if($current_page == 3 && $stages == 1){}
				else
					$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",2)."")."'>2</a></li>";
				$paginate.= "<li class=\"disabled\"><a>...</a></li>";
				for ($counter = $current_page - $stages; $counter <= $current_page + $stages; $counter++)
				{
					if ($counter == $current_page){
						$paginate.= "<li class=\"active\"><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",$counter)."")."'>$counter</a></li>";
					}					
				}
				$paginate.= "<li class=\"disabled\"><a>...</a></li>";
				$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",$LastPagem1)."")."'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",$lastpage)."")."'>$lastpage</a></li>";		
			}
			// End only hide early pages
			else
			{
				$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",1)."")."'>1</a></li>";
				$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",2)."")."'>2</a></li>";
				$paginate.= "<li class=\"disabled\"><a>...</a></li>";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $current_page){
						$paginate.= "<li class=\"active\"><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='".LinkToGT("$link_to".sprintf("$str_page",$counter)."")."'>$counter</a></li>";}					
				}
			}
		}
					
				// Next
		if ($current_page < $counter - 1){ 
			$paginate.= "<li class=\"next\"><a href='".LinkToGT("$link_to".sprintf("$str_page",$next)."")."'>"._NEXT." &rsaquo;</a></li>";
			//$paginate.= "<li class=\"last\"><a href='".LinkToGT("$link_to".sprintf("$str_page",$lastpage)."")."'>"._MT_END." &raquo;</a></li>";
		}else{
			$paginate.= "<li class=\"disabled\"><a>"._NEXT." &rsaquo;</a></li>";
			//$paginate.= "<li class=\"disabled\">"._MT_END." &raquo;</li>";
		}
			
		$paginate.= "</ul>";		
	}
	return $paginate;
}

function upload_image($id,$image_file,$module_name)
{
	$target = "files/$module_name/";
	$filename = $image_file['name'];
	$target = $target . basename($image_file['name']) ;

	if(move_uploaded_file($image_file['tmp_name'], $target)){
		$name = basename($image_file['name']);
		rename("files/$module_name/$name" , "files/$module_name/$id.jpg");
		return true;
	}
	
	return false;
}

function Resize($Dir,$Image,$NewDir,$NewImage,$MaxWidth,$MaxHeight,$Quality)
{
	list($ImageWidth,$ImageHeight,$TypeCode)=getimagesize($Dir.$Image);
	$ImageType=($TypeCode==1) ? "gif":(($TypeCode==2) ? "jpeg":(($TypeCode == 3) ? "png":FALSE));
	$CreateFunction="imagecreatefrom".$ImageType;
	$OutputFunction="image".$ImageType;
	if ($ImageType)
	{
		$Ratio=($ImageHeight/$ImageWidth);
		$ImageSource=$CreateFunction($Dir.$Image);
		if ($ImageWidth > $MaxWidth || $ImageHeight > $MaxHeight)
		{
			if ($ImageWidth > $MaxWidth)
			{
				$ResizedWidth=$MaxWidth;
				$ResizedHeight=$ResizedWidth*$Ratio;
			}
			else {
				$ResizedWidth=$ImageWidth;
				$ResizedHeight=$ImageHeight;
			}       
			if ($ResizedHeight > $MaxHeight)
			{
				$ResizedHeight=$MaxHeight;
				$ResizedWidth=$ResizedHeight/$Ratio;
			}      
			$ResizedImage=imagecreatetruecolor($ResizedWidth,$ResizedHeight);
			imagecopyresampled($ResizedImage,$ImageSource,0,0,0,0,$ResizedWidth,
			$ResizedHeight,$ImageWidth,$ImageHeight);
		}
		else
		{
			$ResizedWidth=$ImageWidth;
			$ResizedHeight=$ImageHeight;     
			$ResizedImage=$ImageSource;
		}
		
		$OutputFunction($ResizedImage,$NewDir.$NewImage,$Quality);
		return true;
	}   
	else
	return false;
}

function custom_headlines($url)
{
	$meta = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1256\">";
	$style = "<style>body {font-family:Tahoma;font-size:11px;direction:rtl;text-align:justify} .rss a {text-decoration:none;color:#0000FF;} .rss a:hover{text-decoration:none;color:#FF0000;}</style>";
	$rdf = parse_url($url);
	$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
	if ($fp) {
		fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n");
		fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
		$string = "";
		while(!feof($fp)) {
			$pagetext = fgets($fp,228);
			$string .= chop($pagetext);
		}
		fputs($fp,"Connection: close\r\n\r\n");
		fclose($fp);
		$items = explode("</item>",$string);
		$content = "<font class=\"content\">";
		for ($i=0;$i<10;$i++) {
			$link = @preg_replace("#.*<link>#","",$items[$i]);
			$link = @preg_replace("#</link>.*#","",$link);
			$title2 = @preg_replace("#.*<title>#","",$items[$i]);
			$title2 = @preg_replace("#</title>.*#","",$title2);
			if ($items[$i] == "" AND $cont != 1) {
				$content = "";
			} else {
				if (strcmp($link,$title2) AND !empty($items[$i])) {
						$cont = 1;
						$content .= "<strong><big>&middot;</big></strong>&nbsp;<a onmouseover=\"this.style.color='#ff0000';\" onmouseout=\"this.style.color='#0000FF'\" style=\"text-align:justify;text-decoration:none;\" href=\"$link\" target=\"new\">$title2</a><br>\n";
				}
			}
		}
	$content = "$meta$style$content";
	return $content;
}

}

function phpnuke_auto_increment($table, $new_auto_increment = 0, $cache_file = '')
{
	global $db, $pn_dbname;
	
	if($new_auto_increment == 0)
	{
		$results = $db->query("SELECT TABLE_ROWS as table_rows FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?", array($pn_dbname, $table));
		$row = $results[0];
		$table_rows = intval($row['table_rows']);
	}
	
	$new_auto_increment = ($new_auto_increment != 0) ? $new_auto_increment:($table_rows+1);
	
	$db->query("ALTER TABLE $table auto_increment = ?", array($new_auto_increment));
	if($cache_file != '')
	{
		cache_system($cache_file);
	}
}

function add_log($message, $type = 0, $time = '', $ip = '', $log_by = '')
{
	global $db, $visitor_ip, $nuke_configs, $userinfo, $aid;
	
	$ip = ($ip == '') ? $visitor_ip:$ip;
	
	$time = ($time == '') ? _NOWTIME:$time;
	
	$type = intval($type);
	
	$log_by = ($log_by == '') ? (($type == 1) ? $aid:$userinfo['username']):$log_by;
	
	$db->table(LOG_TABLE)
		->insert([
			'log_type' => $type,
			'log_by' => $log_by,
			'log_time' => $time,
			'log_ip' => $ip,
			'log_message' => $message,
		]);

	$result = $db->table(LOG_TABLE)
				->where('log_type', $type)
				->order_by(['lid' => 'ASC'])
				->limit(intval($nuke_configs['max_log_numbers']), 1)
				->first(['lid']);

	if($result->count() > 0)
	{
		$lid = intval($result['lid']);
		if($lid > 0)
			$db->table(LOG_TABLE)
				->where('lid', '<', $lid)
				->where('log_type', $type)
				->delete();
	}	
}

function correct_url($url)
{
	global $nuke_configs;
	if(strpos($url,'://')) return $url;
	if(substr($url,0,2)=='//') return 'http:'.$url;
	if($url[0]=='/') return 'http://'.$url;
	return $url;
}

function suspend_site_show()
{
	global $nuke_configs;
	if($nuke_configs['suspend_site'] == 1 && !defined("ADMIN_FILE"))
	{
		$nuke_configs['suspend_start'] = (isset($nuke_configs['suspend_start']) && $nuke_configs['suspend_start'] != '') ? to_mktime($nuke_configs['suspend_start']):(_NOWTIME);
		$nuke_configs['suspend_expire'] = (isset($nuke_configs['suspend_expire']) && $nuke_configs['suspend_expire'] != '') ? to_mktime($nuke_configs['suspend_expire']):(_NOWTIME+604800);
		if(isset($nuke_configs['suspend_start']) && $nuke_configs['suspend_start'] <= _NOWTIME && $nuke_configs['suspend_expire'] > _NOWTIME)
		{
			foreach($nuke_configs as $key => $val)
			{
				$upkey = strtoupper($key);
				if(!is_array($val))
					$nuke_configs['suspend_template'] = str_replace("{".$upkey."}", $val, $nuke_configs['suspend_template']);
			}
			die($nuke_configs['suspend_template']);
		}
	}
}

function redirect_to($location = null, $refresh = '')
{
	if (!$location || $location === null)
		$location = "index.php";
	
	if (is_numeric($location)) {
		switch ($location) {
			case '404':
				die_error("404");
			break;
		}
	}
	$location = LinkToGT($location);
	if (!headers_sent() && $refresh === ''){
		header('Location: '.$location);
		exit();
	} else {
		$refresh = intval($refresh);
		echo '<script type="text/javascript">';
		echo 'setTimeout(function(){location.href="'.$location.'"}, '.($refresh*1000).');';
		echo '</script>';
		echo '<noscript>';
		echo '<meta http-equiv="refresh" content="'.intval($refresh).';url='.$location.'" />';
		echo '</noscript>'; exit;
	 }
}

function custom_theme_setup(&$theme_setup, $custom_theme_setup = array(), $theme_setup_keys = array(), $replace = false)
{
	$theme_setup_keys = (!isset($theme_setup_keys) || empty($theme_setup_keys)) ? array('default_meta', 'default_link_rel', 'default_css', 'default_js', 'defer_js'):$theme_setup_keys;
	foreach($theme_setup_keys as $theme_setup_key)
	{
		$custom_theme_setup[$theme_setup_key] = isset($custom_theme_setup[$theme_setup_key]) ? $custom_theme_setup[$theme_setup_key]:array();
		$theme_setup[$theme_setup_key] = isset($theme_setup[$theme_setup_key]) ? $theme_setup[$theme_setup_key]:array();
		
		if(!empty($custom_theme_setup[$theme_setup_key]))
		{
			$theme_setup[$theme_setup_key] = ($replace) ? $custom_theme_setup[$theme_setup_key]:array_merge($theme_setup[$theme_setup_key], $custom_theme_setup[$theme_setup_key]);
		}
	}
}
// other functions


//contact functions!
function phpnuke_mail($to, $subject='' ,$message='' ,$from='', $from_desc='', $attachments=array())
{
	global $nuke_configs;
	if($from == ""){
		$from = $nuke_configs['adminmail'];
	}
	if($from_desc == ""){
		$from_desc = $nuke_configs['sitename'];
	}
	
	if(file_exists("images/logo.gif")){
		$ext = "gif";
	}elseif(file_exists("images/logo.png")){
		$ext = "png";
	}elseif(file_exists("images/logo.jpg")){
		$ext = "jpg";
	}
	
	$logoimage = "<img alt='".$nuke_configs['sitename']."' src='".$nuke_configs['nukecdnurl']."images/logo.$ext' width='108' height='69' alt=\"".$nuke_configs['sitename']."\" title=\"".$nuke_configs['sitename']."\" />";
	
	//start email
	require_once(INCLUDE_PATH.'/phpmailer/PHPMailer.php');
	require_once(INCLUDE_PATH.'/phpmailer/SMTP.php');
	require_once(INCLUDE_PATH.'/phpmailer/POP3.php');
	
	$mail = new PHPMailer;
	
	$mail->CharSet="utf-8"; 
		
	if($nuke_configs['smtp_email_server'] != '' && $nuke_configs['smtp_email_user'] != '' && $nuke_configs['smtp_email_pass'] != '')
	{
		$mail->isSMTP();
		$mail->Host = $nuke_configs['smtp_email_server'];
		$mail->SMTPAuth = true;
		$mail->Username = $nuke_configs['smtp_email_user'];
		$mail->Password = $nuke_configs['smtp_email_pass'];
		
		if($nuke_configs['smtp_secure'] != '')
			$mail->SMTPSecure = $nuke_configs['smtp_secure'];
		if($nuke_configs['smtp_port'] != '')
			$mail->Port = $nuke_configs['smtp_port'];
	}
	
	if($nuke_configs['smtp_debug'] != 0)
		$mail->SMTPDebug = $nuke_configs['smtp_debug'];
		
	$mail->From = $from;
	$mail->FromName = "=?UTF-8?B?".base64_encode($from_desc).'?=';
	
	$to_emails = (is_array($to)) ? $to:explode(",", $to);
	foreach($to_emails as $to_email){
		$mail->addAddress($to_email);
	}			

	if($nuke_configs['is_html_mail'] != '')
		$mail->isHTML(true);

	$mail->Subject = $subject;
	$is_template = false;
	
	if(is_array($message))
	{
		$is_template = true;
		if(isset($message['template']) && !empty($message['template']) && isset($message['template']['file']))
		{
			$template_path = $message['template']['path'];
			$template_file = $message['template']['file'];
			
			$template = (file_exists("themes/".$nuke_configs['ThemeSel']."/emails/$template_file")) ? "themes/".$nuke_configs['ThemeSel']."/emails/$template_file":((file_exists($template_path.$template_file)) ? $template_path.$template_file:"");
			
			if($template != '')
			{
				unset($message['template']);
				$template_data = phpnuke_get_url_contents($template, true, false, true);
				
				preg_match_all("#\{\((.*)\)\}#isU", $template_data, $matchs);
				if(isset($matchs[1]))
				{
					foreach($matchs[1] as $key => $value)
					{
						$first_text = $matchs[0][$key];
						preg_match_all("#\[\[(.*)\]\]#isU", $value, $matchs2);
						if(isset($matchs2[1]))
						{
							$constant_var = $matchs2[1][0];
							array_shift($matchs2[1]);
							$template_data = str_replace($first_text, vsprintf(constant("$constant_var"), $matchs2[1]), $template_data);
						}
					}
				}
				
				preg_match_all("#\{\{(.*)\}\}#isU", $template_data, $matchs);
				$email_message = '';

				if(isset($matchs[1][0]))
					foreach($matchs[1] as $var)
					{
						$new_value = '';
						if(isset($message[strtolower($var)]))
						{
							$new_value = $message[strtolower($var)];
						}
						elseif(isset($nuke_configs[strtolower($var)]))
						{
							$new_value = $nuke_configs[strtolower($var)];
						}
						elseif(defined("$var"))
						{
							$new_value = constant("$var");
						}
						
						if($new_value != '')
							$template_data = str_replace('{{'.$var.'}}', $new_value, $template_data);
					}
				$message = $template_data;
			}
			else
				return false;			
		}
	}
	
	if(function_exists('mail_theme'))
		$message = mail_theme($subject, $logoimage, $message);
	elseif(!$is_template)
	{
		$message = "
		<html>\n
			<head>\n
				<meta http-equiv='content-type' content='text/html; charset=utf-8'>\n
			</head>\n
			<body>\n
			<span dir='"._DIRECTION."'>\n
				$message
			</span>\n
		</body>\n
		</html>\n";
	}
		
	$mail->Body		= $message;

	if(isset($attachments) && !empty($attachments) && $nuke_configs['allow_attachement_mail'] == 1)
	{
		foreach($attachments as $attachment_name => $attachment_path)
		{
			$mail->clearAttachments();
			$mail->addAttachment($attachment_path, $attachment_name);
		}
	}
	
	if(!$mail->send())
		return $mail->ErrorInfo;
		
	return true;
}

function pn_sms($mode = 'send', $to = array(), $message = '', $flash = 0, $recId = '')
{
	global $nuke_configs;
	
	if($mode == 'send' && ($to == '' || empty($to) || $message == ''))
		return false;
	
	$pn_sms_config = (isset($pn_sms_config) && !empty($pn_sms_config)) ? $pn_sms_config:((isset($nuke_configs['pn_sms']) && $nuke_configs['pn_sms'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['pn_sms'])):array());

	require_once(INCLUDE_PATH."/sms/class.".$pn_sms_config['operator'].".php");
	
	$class_name = "pn_".$pn_sms_config['operator'];

	$sms = new $class_name($pn_sms_config['username'], $pn_sms_config['password'], $pn_sms_config['default_number']);
	
	switch($mode)
	{
		case"send":
			$sms->AddRecipient($to);
			$sms->msg = $message;
			$sms->flash = $flash;
	
			$recIds = $sms->send();
			
			return $recIds;
		break;
		
		case"get_credit":
			return $sms->GetCredit();
		break;
		
		case"get_delivery":
			$sms->AddRecipient($to);
			return $sms->GetDelivery();
		break;
	}
	
	return true;
}

//contact functions!

// datetimes functions

/*With the functions provided below you can easily convert gregorian date to hejri date
Hejri Functions*/

function div($a, $b)
{
   return (int) ($a / $b);
}

function isnum($str)
{
    if (strlen($str) == 0)
	return 0;
    for ($i = 0; $i < strlen($str); $i++)
        if (ord($str[$i]) < ord("0") || ord($str[$i]) > ord("9"))
            return 0;
    return 1;
}

function hex2int($str)
{
    $str = strtoupper($str);
    for ($i = 0, $n = 0; $i < strlen($str); $i++)
    {
	$n *= 0x10;
        if (ord($str[$i]) < ord("0") || ord($str[$i]) > ord("9"))
            $n += 10 + ord($str[$i]) - ord("A");
	else
            $n += ord($str[$i]) - ord("0");
    }
    return $n;
}

function gregorian_to_jalali($g_y, $g_m, $g_d)
{
   global $nuke_configs;

   $gy = $g_y-1600;
   $gm = $g_m-1;
   $gd = $g_d-1;

   $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400);

   for ($i=0; $i < $gm; ++$i)
      $g_day_no += $nuke_configs['g_days_in_month'][$i];
   if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
      /* leap and after Feb */
      $g_day_no++;
   $g_day_no += $gd;

   $j_day_no = $g_day_no-79;

   $j_np = div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
   $j_day_no = $j_day_no % 12053;

   $jy = 979+33*$j_np+4*div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */

   $j_day_no %= 1461;

   if ($j_day_no >= 366) {
      $jy += div($j_day_no-1, 365);
      $j_day_no = ($j_day_no-1)%365;
   }

   for ($i = 0; $i < 11 && $j_day_no >= $nuke_configs['j_days_in_month'][$i]; ++$i)
      $j_day_no -= $nuke_configs['j_days_in_month'][$i];
   $jm = $i+1;
   $jd = $j_day_no+1;

   return array($jy, $jm, $jd);
}

function gregorian_week_day($g_y, $g_m, $g_d)
{
   global $nuke_configs;

   $gy = $g_y-1600;
   $gm = $g_m-1;
   $gd = $g_d-1;

   $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400);

   for ($i=0; $i < $gm; ++$i)
      $g_day_no += $nuke_configs['g_days_in_month'][$i];
   if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
      /* leap and after Feb */
      ++$g_day_no;
   $g_day_no += $gd;

   return ($g_day_no + 5) % 7 + 1;
}

function jalali_to_gregorian($j_y, $j_m, $j_d)
{
   global $nuke_configs;

   $jy = $j_y-979;
   $jm = $j_m-1;
   $jd = $j_d-1;

   $j_day_no = 365*$jy + div($jy, 33)*8 + div($jy%33+3, 4);
   for ($i=0; $i < $jm; ++$i)
      $j_day_no += $nuke_configs['j_days_in_month'][$i];

   $j_day_no += $jd;

   $g_day_no = $j_day_no+79;

   $gy = 1600 + 400*div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
   $g_day_no = $g_day_no % 146097;

   $leap = true;
   if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
   {
      $g_day_no--;
      $gy += 100*div($g_day_no,  36524); /* 36524 = 365*100 + 100/4 - 100/100 */
      $g_day_no = $g_day_no % 36524;

      if ($g_day_no >= 365)
         $g_day_no++;
      else
         $leap = false;
   }

   $gy += 4*div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
   $g_day_no %= 1461;

   if ($g_day_no >= 366) {
      $leap = false;

      $g_day_no--;
      $gy += div($g_day_no, 365);
      $g_day_no = $g_day_no % 365;
   }

   for ($i = 0; $g_day_no >= $nuke_configs['g_days_in_month'][$i] + ($i == 1 && $leap); $i++)
      $g_day_no -= $nuke_configs['g_days_in_month'][$i] + ($i == 1 && $leap);
   $gm = $i+1;
   $gd = $g_day_no+1;

   return array($gy, $gm, $gd);
}

function jalali_week_day($j_y, $j_m, $j_d)
{
   global $nuke_configs;

   $jy = $j_y-979;
   $jm = $j_m-1;
   $jd = $j_d-1;

   $j_day_no = 365*$jy + div($jy, 33)*8 + div($jy%33+3, 4);

   for ($i=0; $i < $jm; ++$i)
      $j_day_no += $nuke_configs['j_days_in_month'][$i];

   $j_day_no += $jd;

   return ($j_day_no + 2) % 7 + 1;
}

function jcheckdate($j_m, $j_d, $j_y)
{
   global $nuke_configs;

   if ($j_y < 0 || $j_y > 32767 || $j_m < 1 || $j_m > 12 || $j_d < 1 || $j_d >
           ($nuke_configs['j_days_in_month'][$j_m-1] + ($j_m == 12 && !(($j_y-979)%33%4))))
       return false;
   return true;
}

function formatTimestamp($time)
{
	global $nuke_configs;
    setlocale (LC_TIME, $nuke_configs['locale']);
    @preg_match ("#([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#", $time, $datetime);
	$JalaliDate = gregorian_to_jalali($datetime[1],$datetime[2],$datetime[3]);
	$jd = jalali_week_day($JalaliDate[0],$JalaliDate[1],$JalaliDate[2]);
	$datetime = $nuke_configs['j_week_name'][$jd]
	." ".$JalaliDate[2]
	." ".$nuke_configs['j_month_name'][$JalaliDate[1]]
	." ".$JalaliDate[0];
    return($datetime);
}

function FormalDate2Hejri($time)
{
	@preg_match ("#([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})#", $time, $datetime);
	$JalaliDate = gregorian_to_jalali($datetime[1],$datetime[2],$datetime[3]);
	$datetime = $JalaliDate[2]."-".$JalaliDate[1]."-".$JalaliDate[0];
	return $datetime;
}

function FormalDate2Hejri1($time)
{
	global $nuke_configs;
	@preg_match ("#([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})#", $time, $datetime);
	$JalaliDate = gregorian_to_jalali($datetime[1],$datetime[2],$datetime[3]);
	$datetime = $JalaliDate[2]." ".$nuke_configs['j_month_name'][$JalaliDate[1]]."، ".$JalaliDate[0];
	return $datetime;
}

function FormalDate2Hejri2($time)
{
	@preg_match ("#([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#", $time, $datetime);
	$JalaliDate = gregorian_to_jalali($datetime[1],$datetime[2],$datetime[3]);
	$datetime = $JalaliDate[2]."-".$JalaliDate[1]."-".$JalaliDate[0]." ".$datetime[4].":".$datetime[5].":".$datetime[6];
	return $datetime;
}

function FormalDate2Hejri3($time)
{
	global $nuke_configs;
    @preg_match ("#([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#", $time, $datetime);
	$JalaliDate = gregorian_to_jalali($datetime[1],$datetime[2],$datetime[3]);
	$jd = jalali_week_day($JalaliDate[0],$JalaliDate[1],$JalaliDate[2]);
	$datetime = $nuke_configs['j_week_name'][$jd]
	."، ".$JalaliDate[2]
	." ".$nuke_configs['j_month_name'][$JalaliDate[1]];
    return($datetime);
}

function FormalDate2Hejri4($time)
{
	@preg_match ("#([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})#", $time, $datetime);
	$JalaliDate = gregorian_to_jalali($datetime[3],$datetime[2],$datetime[1]);
	$datetime = $JalaliDate[2]."-".$JalaliDate[1]."-".$JalaliDate[0];
	return $datetime;
}

function FormalDate2Hejri5($time)
{
	global $nuke_configs;
    @preg_match ("#([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#", $time, $datetime);
	$JalaliDate = gregorian_to_jalali($datetime[1],$datetime[2],$datetime[3]);
	$datetime = $JalaliDate[2]." ".$nuke_configs['j_month_name'][$JalaliDate[1]]."، ".$JalaliDate[0]
	." @ ".$datetime[4].":".$datetime[5].":".$datetime[6];
	return $datetime;
}

function FormalDate2Hejri6($time)
{
	global $nuke_configs;
    @preg_match ("#([A-z]{3}), ([0-9]{1,2}) ([0-9]{1,2}) ([A-z]{1,2})#", $time, $datetime);
	$JalaliDate = gregorian_to_jalali($datetime[1],$datetime[2],$datetime[3]);
	$datetime = $JalaliDate[2]." ".$nuke_configs['j_month_name'][$JalaliDate[1]]."، ".$JalaliDate[0]
	." @ ".$datetime[4].":".$datetime[5].":".$datetime[6];
	return $datetime;
}

function back2formaldate($time)
{
    @preg_match ("#([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#", $time, $datetime);
	$gdate = jalali_to_gregorian($datetime[1],$datetime[2],$datetime[3]);
	$time = $gdate[0]."-".$gdate[1]."-".$gdate[2]." ".$datetime[4].":".$datetime[5].":".$datetime[6];
	return $time;
}

function now_in_hejri($now)
{
	$now = getdate();
	$now = $now['year']."-".$now['mon']."-".$now['mday']." ".$now['hours'].":".$now['minutes'].":".$now['seconds'];
	$now = formatTimestamp($now);
	return $now;
}

function echo_now()
{
	$now = now_in_hejri($now);
	return $now;
}

function forum_date($time)
{
	global $nuke_configs;
    @preg_match ("#([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#", $time, $datetime);
	$JalaliDate = gregorian_to_jalali($datetime[1],$datetime[2],$datetime[3]);
	$jd = jalali_week_day($JalaliDate[0],$JalaliDate[1],$JalaliDate[2]);
	$datetime = $nuke_configs['j_week_name'][$jd]
	."، ".$JalaliDate[2]
	." ".$nuke_configs['j_month_name'][$JalaliDate[1]]
	."، ".$JalaliDate[0]
	." ".$datetime[4].":".$datetime[5].":".$datetime[6];
return($datetime);
}

function is_kabise($year)
{
	if(($year%33==1) OR($year%33==5) OR($year%33==9) OR($year%33==13) OR($year%33==17) OR($year%33==22) OR($year%33==26) OR($year%33==30))
		return true;
	return false;
}

//**********************Gregorian date to hehri date
class HijriCalendar
{
	function monthName($i) // $i = 1..12
	{
		global $nuke_configs;
		return $nuke_configs['A_month_name'][$i-1];
	}
	function GregorianToHijri($time = null)
	{
		if ($time === null) $time = _NOWTIME;
		$m = date('m', $time);
		$d = date('d', $time);
		$y = date('Y', $time);
		return $this->JDToHijri(
			cal_to_jd(CAL_GREGORIAN, $m, $d, $y));
	}
	function HijriToGregorian($y, $m, $d)
	{
		return jd_to_cal($this->HijriToJD($y, $m, $d));
	}
	# Julian Day Count To Hijri
	function JDToHijri($jd)
	{
		$jd = $jd - 1948440 + 10632;
		$n  = (int)(($jd - 1) / 10631);
		$jd = $jd - 10631 * $n + 354;
		$j  = ((int)((10985 - $jd) / 5316)) *
		((int)(50 * $jd / 17719)) +
		((int)($jd / 5670)) *
		((int)(43 * $jd / 15238));
		$jd = $jd - ((int)((30 - $j) / 15)) *
		((int)((17719 * $j) / 50)) -
		((int)($j / 16)) *
		((int)((15238 * $j) / 43)) + 29;
		$m  = (int)(24 * $jd / 709);
		$d  = $jd - (int)(709 * $m / 24);
		$y  = 30*$n + $j - 30;
		return array($m, $d, $y);
	}
	# Hijri To Julian Day Count
	function HijriToJD($y, $m, $d)
	{
		return 0; 
	}
}

function jd_to_cal($y, $m, $d)
{
	$jd = ((11 * $y + 3) / 30) + 354 * $y + 30 * $m - (($m - 1) / 2) + $d + 1948440 - 385;
	//
    // get the date from the Julian day number
    //
    $intgr   = floor($jd);
    $frac    = $jd - $intgr;
    $gregjd  = 2299161;
    if( $intgr >= $gregjd ) {                //Gregorian calendar correction
        $tmp = floor( ( ($intgr - 1867216) - 0.25 ) / 36524.25 );
        $j1 = $intgr + 1 + $tmp - floor(0.25*$tmp);
    } else
        $j1 = $intgr;

    //correction for half day offset
    $dayfrac = $frac + 0.5;
    if( $dayfrac >= 1.0 ) {
        $dayfrac -= 1.0;
        ++$j1;
    }

    $j2 = $j1 + 1524;
    $j3 = floor( 6680.0 + ( ($j2 - 2439870) - 122.1 )/365.25 );
    $j4 = floor($j3*365.25);
    $j5 = floor( ($j2 - $j4)/30.6001 );

    $d = floor($j2 - $j4 - floor($j5*30.6001));
    $m = floor($j5 - 1);
    if( $m > 12 ) $m -= 12;
    $y = floor($j3 - 4715);
    if( $m > 2 )   --$y;
    if( $y <= 0 )  --$y;

    //
    // get time of day from day fraction
    //
    $hr  = floor($dayfrac * 24.0);
    $mn  = floor(($dayfrac*24.0 - $hr)*60.0);
         $f  = (($dayfrac*24.0 - $hr)*60.0 - $mn)*60.0;
    $sc  = floor($f);
         $f -= $sc;
    if( $f > 0.5 ) ++$sc;
    if( $sc == 60 ) {
        $sc = 0;
        ++$mn;
    }
    if( $mn == 60 )  {
        $mn = 0;
        ++$hr;
    }
    if( $hr == 24 )  {
        $hr = 0;
        ++$d;            //this could cause a bug, but probably will never happen in practice
    }

    if( $y < 0 )
         $y = -$y;
	
	return array($y, $m, $d);
}

function Gregorian($month)
{
	global $nuke_configs;
	return $nuke_configs['g_month_name'][$month];
}

function get_month_name($month)
{
	global $nuke_configs, $HijriCalendar;
	$month_name = ($nuke_configs['datetype'] == 1) ? $nuke_configs['j_month_name'][$month]:(($nuke_configs['datetype'] == 2) ? $HijriCalendar->monthName($month):Gregorian($month));
	return $month_name;
}

function nuketimes($time = 0, $hour=false, $min=false, $sec=false, $mode=3)
{
	global $nuke_configs, $HijriCalendar;
	
	$time = (intval($time) == 0) ? _NOWTIME:$time;
	
	$date = date("Y-m-d H:i:s l",$time);
	
	$year = date("Y", $time);
	$month = date("m", $time);
	$day = date("d", $time);
	$time_hour = date("H", $time);
	$time_min = date("i", $time);
	$time_sec = date("s", $time);
	
	if($nuke_configs['datetype'] == 1)
	{
		if($mode == 1)//return format YYYY/MM/DD
		{
			$dateTimes = gregorian_to_jalali($year, $month, $day);
			
			$dateTimes[1] = correct_date_number($dateTimes[1]);
			$dateTimes[2] = correct_date_number($dateTimes[2]);
			
			$dateTimes = implode("/",$dateTimes);
		}
		elseif($mode == 2)// return array
		{
			$dateTimes = gregorian_to_jalali($year, $month, $day);
		}
		else// return formatted as language
		{
			$dateTimes = formatTimestamp($date);
		}
	}
	elseif($nuke_configs['datetype'] == 2)
	{
		$week = date("N", $time);
		switch($week)
		{
			case "1":
				$weekname = $nuke_configs['A_week_name'][1];
				break;
			case "2":
				$weekname = $nuke_configs['A_week_name'][2];
				break;
			case "3":
				$weekname = $nuke_configs['A_week_name'][3];
				break;
			case "4":
				$weekname = $nuke_configs['A_week_name'][4];
				break;
			case "5":
				$weekname = $nuke_configs['A_week_name'][5];
				break;
			case "6":
				$weekname = $nuke_configs['A_week_name'][6];
				break;
			case "7":
				$weekname = $nuke_configs['A_week_name'][7];
				break;
		}
		
		$dateTime = $HijriCalendar->GregorianToHijri($time);
		$dateTimes = array($dateTime[2], $dateTime[0], $dateTime[1]);
		
		if($mode == 1)//return format YYYY/MM/DD
		{
			
			$dateTimes[1] = correct_date_number($dateTimes[1]);
			$dateTimes[2] = correct_date_number($dateTimes[2]);
			
			$dateTimes = implode("/",$dateTimes);
		}
		elseif($mode == 2)// return array
		{
			//
		}
		else// return formatted as language
		{
			$dateTimes = $weekname.' '.$dateTimes[2].' '.$HijriCalendar->monthName($dateTimes[1]).' '.$dateTimes[0];
		}
	}
	else
	{
		$date = explode(" ", $date);
		$date_only	= explode("-", $date[0]);
		$weekname	= $date[2];
		
		if($mode == 1)//return format YYYY/MM/DD
		{
			
			$date_only[1] = correct_date_number($date_only[1]);
			$date_only[2] = correct_date_number($date_only[2]);
			$dateTimes = implode("/",$date_only);
		}
		elseif($mode == 2)// return array
		{
			$dateTimes = $date_only;
		}
		else// return formatted as language
		{
			$dateTimes = $weekname." ".$date_only[2]." ".Gregorian(intval($date_only[1]))." ".$date_only[0];
		}
	}
	
	$add_Times = '';
	
	$time_hour = correct_date_number($time_hour);
	$time_min = correct_date_number($time_min);
	$time_sec = correct_date_number($time_sec);
	
	if($hour)
		if(is_array($dateTimes))
			$add_Times[] = $time_hour;
		else
			$add_Times .= " "._HOUR." $time_hour";
			
	if($min)
		if(is_array($dateTimes))
			$add_Times[] = $time_min;
		else
			$add_Times .= ":$time_min";
			
	if($sec)
		if(is_array($dateTimes))
			$add_Times[] = $time_sec;
		else
			$add_Times .= ":$time_sec";
			
	if(is_array($dateTimes))
		return ($add_Times != '') ? array_merge($dateTimes, $add_Times):$dateTimes;
	else
		return $dateTimes.$add_Times;
	
}

function all_to_gregorian($year, $month, $day)
{
	global $nuke_configs;
	
	if($nuke_configs['datetype'] == 1)
		$dateTimes = jalali_to_gregorian($year, $month, $day);
	elseif($nuke_configs['datetype'] == 2)
		$dateTimes = jd_to_cal($year, $month, $day);
	else
		$dateTimes = array($year,$month,$day);
		
	return $dateTimes;
}

function to_mktime($datetime, $datetime_delimiter=" ", $date_delimiter="/", $time_delimiter=":")
{
	$datetime_array = explode("$datetime_delimiter", $datetime);
	$date_array = explode("$date_delimiter", $datetime_array[0]);
	$time_array = (isset($datetime_array[1]) && !empty($datetime_array[1])) ? explode("$time_delimiter", $datetime_array[1]):array(0,0,0);
	$gregorian_date = all_to_gregorian($date_array[0], $date_array[1], $date_array[2]);
	return mktime($time_array[0], $time_array[1], $time_array[2], $gregorian_date[1], $gregorian_date[2], $gregorian_date[0]);
}

function correct_date_number($number)
{
	if(intval($number) < 10 && substr($number, 0, 1) != 0)
		$number = "0$number";
	return $number;
}

// datetimes functions


// ratings functions
function rating_load($score=0, $ratings=0, $likes=0, $dislikes=0, $db_table='Articles', $db_table_var="sid", $id=0, $disabled_rating = false, $c_votetype = 0)
{
	global $nuke_configs;

	$votetype = ($c_votetype == 0) ? $nuke_configs['votetype']:$c_votetype;
	
	$average_rated = 0;
	if($votetype == 1)
	{
		if($score != 0)
		{
			$average_rated = number_format(($score / $ratings), 2);
		}
	}
		
	return ''.(($votetype == 1) ? '<div dir="ltr">':'').'<div class="rating-load'.(($disabled_rating) ? " jDisabled":"").'" '.(($votetype == 1) ? 'data-average="'.$average_rated.'"':'').' data-id="'.$id.'" data-score="'.$score.'" data-ratings="'.$ratings.'" data-db-table="'.$db_table.'" data-db-table-var="'.$db_table_var.'" data-c-votetype="'.$votetype.'" data-likesrate="'.$likes.",".$dislikes.'"></div>'.(($votetype == 1) ? '</div>':'').'';
}

function submit_ratings($jrating_Response)
{
	global $db, $rate_cookie, $user, $userinfo, $idBox, $rate, $old_rate, $old_score, $db_table, $db_table_var, $c_votetype, $oldlikesrate, $visitor_ip, $pn_Cookies, $pn_prefix;
	$PnValidator = new GUMP();
	$contents = "";
	$rate = floatval($rate);
	$idBox = intval($idBox);
	$c_votetype = intval($c_votetype);
	// AND (rating_ip='$ip' OR username = '$uname') was removed for all
	
	if(is_user())
	{
		$user_id_ip = $userinfo['user_id'];
		$gust = 0;
	}
	else
	{
		$user_id_ip = 0;
		$gust = 1;
	}
	
	$rated = false;
		
	if ($pn_Cookies->exists($db_table.'_ratecookie'))
	{
		$rcookie = base64_decode($pn_Cookies->get($db_table.'_ratecookie'));
		$rcookie = addslashes($rcookie);
		$r_cookie = explode(",", $rcookie);
		if(in_array($idBox, $r_cookie)){
			$rated = true;
		}else{
			$r_cookie[] = $idBox;
		}
	}else{
		$r_cookie[] = $idBox;
	}
	
	if(!$rated)
	{
		$db_table_var = array('db_table_var' => $db_table_var);
		$PnValidator->validation_rules(array(
			'db_table_var'	=> 'required|alpha'
		)); 
		// Get or set the filtering rules
		$PnValidator->filter_rules(array(
			'db_table_var'	=> 'sanitize_string',
		)); 

		$db_table_var = $PnValidator->sanitize($db_table_var, array('db_table_var'), true, true);
		$validated_data = $PnValidator->run($db_table_var);
		
		if($validated_data !== FALSE)	
		{
			$db_table_var = $validated_data['db_table_var'];	
	
			$result = $db->query("update ".$pn_prefix."_".$db_table." set score=score+?, ratings=ratings+1 where $db_table_var=?", array($rate, $idBox));
			$db->table(SCORES_TABLE)
				->insert([
					'post_id' => $idBox,
					'db_table' => $db_table,
					'votetype' => $c_votetype,
					'rating_ip' => $visitor_ip,
					'score' => $rate,
					'user_id' => $user_id_ip,
					'gust' => $gust,
					'vote_time' => _NOWTIME,
				]);
			
			$new_ratecookie = base64_encode(implode(",",$r_cookie));
			$pn_Cookies->set($db_table.'_ratecookie',$new_ratecookie,(365*24*3600));
			update_points(7);
			
			if($oldlikesrate == '')
				$oldlikesrate = "0,0";
		
			$oldlikesrate = explode(",", $oldlikesrate);
			
			if(count($oldlikesrate) != 2)
				$oldlikesrate = array(0,0);
				
			$oldlikesrate = array_map("pn_array_map", $oldlikesrate);
			
			if($rate > 0)
				$oldlikesrate[0]++;
			else
				$oldlikesrate[1]++;
			
			$oldlikesrate = implode(",", $oldlikesrate);
			
			$jrating_Response['message'] = _SUCCESS_VOTED;
			$jrating_Response['server'] = $oldlikesrate;
			$contents .= json_encode($jrating_Response);
		}
		else
		{
			$jrating_Response['error'] = true;
			$jrating_Response['message'] = _ERROR_IN_VOTE;
			$contents .= json_encode($jrating_Response);
		}
	}
	else
	{
		$jrating_Response['error'] = true;
		$jrating_Response['message'] = _ERROR_IN_VOTE;
		$contents .= json_encode($jrating_Response);
	}
	die($contents);
}
// ratings functions

// gtset functions
function LinkToGT($link)
{
	global $nuke_configs, $nuke_modules_friendly_urls_cacheData;

	if($link == "index.php")
		return $nuke_configs['nukeurl']."index.html";
	if(isset($nuke_configs['gtset']) && $nuke_configs['gtset'] == 1 && isset($nuke_modules_friendly_urls_cacheData[0]['urlins']))
	{
		$the_link = $link; 
		$the_link = @preg_replace("(&(?!([a-zA-Z]{2,6}|[0-9\#]{1,6})[\;]))", "&amp;", $the_link); 
		$the_link = str_replace(array("&amp;&amp;", "&amp;middot;", "&amp;nbsp;"), array("&&", "&middot;", "&nbsp;"), $the_link);

		$urlins = $nuke_modules_friendly_urls_cacheData[0]['urlins'];
		$urlouts = $nuke_modules_friendly_urls_cacheData[0]['urlouts'];

		$the_link = str_replace("&amp;","&",$the_link);

		$friendly_link = $the_link;
		
		foreach($urlins as $key => $urlin)
		{
			if ( preg_match($urlin."isU", $the_link, $matches) || preg_match($urlin."isU", rawurldecode($the_link), $matches) )
			{
				if(is_array($urlouts[$key]))
				{
					$function = '';
					if(isset($urlouts[$key]['function']))
					{
						$arg = $urlouts[$key]['function'];
						$function = function($match) use($arg){return eval((string) $arg);};
					}
					elseif(isset($urlouts[$key][0]))
						$function = $urlouts[$key][0];
					$friendly_link = ($function != '') ? preg_replace_callback($urlin."isU", $function, $the_link):$the_link;
				}
				else
					$friendly_link = preg_replace($urlin."isU", $urlouts[$key], $the_link);
				break;
			}
		}

		if(0 !== stripos( $link, 'ftp://' ) && 0 !== stripos( $link, 'http://' ) && 0 !== stripos( $link, 'https://' )){
			$friendly_link = $nuke_configs['nukeurl'].$friendly_link;
		}
	}else{
		$friendly_link = str_replace("&","&amp;",$link);
		if(0 !== stripos( $link, 'ftp://' ) && 0 !== stripos( $link, 'http://' ) && 0 !== stripos( $link, 'https://' )){
			$friendly_link = (isset($nuke_configs['nukeurl'])) ? $nuke_configs['nukeurl'].$friendly_link:$friendly_link;
		}
	}

	$friendly_link = preg_replace('/(https?:\/\/)|(\/){2,}/i', "$1$2", $friendly_link);
	
	return $friendly_link; 
}

function parse_GT_link($REQUESTURL)
{
	global $nuke_configs, $nuke_modules_friendly_urls_cacheData;
	$parsed_vars = array();
	$unfriendly_link = '';
	
	if ($offset = strpos($REQUESTURL,'?'))
	{
		$REQUESTURL = substr($REQUESTURL,0,$offset);
	}
	else if($offset = strpos($REQUESTURL,'#'))
	{
		$REQUESTURL = substr($REQUESTURL,0,$offset);
	}
	$chop = -strlen(basename($_SERVER['SCRIPT_NAME']));

	if(!defined('DOC_ROOT'))
		define('DOC_ROOT',substr($_SERVER['SCRIPT_FILENAME'],0,$chop));
	if(!defined('URL_ROOT'))
		define('URL_ROOT',substr($_SERVER['SCRIPT_NAME'],0,$chop));

	if (URL_ROOT != '/' && strrpos($REQUESTURL, URL_ROOT) !== false) $REQUESTURL=substr($REQUESTURL,strlen(URL_ROOT));

	$REQUESTURL = trim($REQUESTURL,'/');
			
	if ($nuke_configs['gtset'] == "1")
	{
		$matched_rule = '';
		$matched_query = '';
		
		$parsed_url = (($REQUESTURL == '') || ($REQUESTURL == 'index.php')) ? array() : explode('/',html_entity_decode($REQUESTURL));

		if (file_exists(DOC_ROOT.'/'.$REQUESTURL) && ($_SERVER['SCRIPT_FILENAME'] != DOC_ROOT.$REQUESTURL) && ($REQUESTURL != '') && ($REQUESTURL != 'index.php'))
			header("location: ".LinkToGT("index.php?error=404")."");
		else
		{
			$REQUESTURL = (strrpos($REQUESTURL,".") !== false) ? $REQUESTURL:$REQUESTURL."/";
			
			if(!isset($nuke_modules_friendly_urls_cacheData) || empty($nuke_modules_friendly_urls_cacheData))
				$nuke_modules_friendly_urls_cacheData = get_cache_file_contents('nuke_modules_friendly_urls');

			$rewrite_found = false;	
			foreach( (array) $nuke_modules_friendly_urls_cacheData[1] as $key => $rewrite)
			{
				if(isset($parsed_url[0]) && ($key == $parsed_url[0] || in_array($key, array('feed', 'phpnukemain'))))
				{
					$rewrite = array_merge($nuke_modules_friendly_urls_cacheData[1]['phpnukemain'], $nuke_modules_friendly_urls_cacheData[1]['feed']);
					
					if($parsed_url[0] != '' && isset($nuke_modules_friendly_urls_cacheData[1][$parsed_url[0]]))
						$rewrite = array_merge($rewrite, $nuke_modules_friendly_urls_cacheData[1][$parsed_url[0]]);
				}
				else
					$rewrite = $nuke_modules_friendly_urls_cacheData[1]['article'];

				foreach ( (array) $rewrite as $match => $query )
				{
					if ( preg_match("#^$match#is", $REQUESTURL, $matches) || preg_match("#^$match#isU", rawurldecode($REQUESTURL), $matches) )
					{
						$matched_rule = $match;
						$matched_query = $query;
						
						if(is_array($query))
						{
							$function = '';
							if(isset($query['function']))
							{
								$arg = $query['function'];
								$function = function($match) use($arg){return eval((string) $arg);};
							}
							elseif(isset($query[0]))
								$function = $query[0];
							$unfriendly_link = ($function != '') ? preg_replace_callback("#^$match#isU", $function, $REQUESTURL):$REQUESTURL;
						}
						else
							$unfriendly_link = preg_replace("#^$match#isU", $query, $REQUESTURL);
							
						$unfriendly_link_arr = parse_url($unfriendly_link);
						if(isset($unfriendly_link_arr['query']))
							parse_str($unfriendly_link_arr['query'], $parsed_vars);
						$rewrite_found = true;	
						break;
					}
				}
				if($rewrite_found)
					break;
			}
			unset($handle, $modules_list, $test);
			$REQUESTURL = preg_replace("|comment-page-([0-9]{1,}+)\/|isU","", $REQUESTURL);
		}
		return array($parsed_vars, $REQUESTURL, $matched_rule, $unfriendly_link, $matched_query);
	}
	else
	{
		if(isset($_GET) && is_array($_GET))
		{
			$i=0;
			foreach($_GET as $key => $val)
			{
				$par = ($i == 0) ? "?":"&";
				$REQUESTURL .= $par."$key=$val";
				$i++;
			}
		}
		return array($_GET, $REQUESTURL, '', $REQUESTURL, '');
	}
}

function parse_old_links($die_404 = true)
{
	global $nuke_configs, $REQUESTURL, $old_site_link;

	$old_links = array(
		"article([0-9]*)\.html",
		"article-([0-9]*)\.html",
	);
	
	foreach($old_links as $old_link)
	{
		if(preg_match("#$old_link#isU", $REQUESTURL, $match))
		{
			$sid = $match[1];
			redirect_to(LinkToGT(articleslink($sid)));
			die();
		}
	}
	
	if($old_site_link != '' && $die_404)
	{
		$file_headers = get_headers($old_site_link.$REQUESTURL);
		if($file_headers) {
			list($version,$status_code,$msg) = explode(' ',$file_headers[0], 3);
			if($status_code != '404')
			{
				redirect_to($old_site_link.$REQUESTURL);
				die();
			}
		}
	}
}

function parse_phpnuke_main($matches)
{
	parse_str("sop=".$matches[1], $output);
	foreach($output as $key => $val)
	{
		if($key == "page")
			$returns[] = "page";
			
		$returns[] = "$val";
	}
	$return = implode("/", $returns)."/";
	
	return $return;
}

function parse_timthumbs_str($matches)
{
	parse_str($matches[1], $output);

	$src = "";
	$urls = array();
	$dims = array();
	foreach($output as $key => $val)
	{
		if($key == "src")
			$src = base64_encode($val).".jpg";
		if($key == "w")
			$dims[0] = $val;
		if($key == "h")
			$dims[1] = $val;
		if($key == "q")
			$urls[] ="q-$val";
		if($key == "a")
			$urls[] ="a-$val";
		if($key == "zc")
			$urls[] ="zc-$val";
		if($key == "f")
			$urls[] ="f-$val";
		if($key == "s")
			$urls[] ="s-$val";
		if($key == "cc")
			$urls[] ="cc-$val";
		if($key == "ct")
			$urls[] ="ct-$val";
	}
	if(!empty($dims) && isset($dims[0]) && $dims[0] != 0 && isset($dims[1]) && $dims[1] != 0)
		$urls[] .= "$dims[0]-$dims[1]";
	
	$url = ($src != '') ? ("thumbs/".((!empty($urls)) ? "".implode("/", $urls)."/":"").$src):"";
	return $url;
}

function parse_timthumbs_args($inputs)
{
	$timtumb_data = array();
	$inputs = explode("/", $inputs); 
	foreach($inputs as $key => $val)
	{
		if(preg_match('#(\d+\-\d+)#i', $val))
		{
			$val = explode("-", $val);
			$timtumb_data['w'] = intval($val[0]);
			$timtumb_data['h'] = intval($val[1]);
			unset($inputs[$key]);
		}
		elseif(substr($val, 0, 2) == "a-")
		{
			$timtumb_data['a'] = filter(str_replace("a-","", $val), "nohtml");
			unset($inputs[$key]);
		}
		elseif(substr($val, 0, 2) == "q-")
		{
			$timtumb_data['q'] = intval(str_replace("q-","", $val));
			unset($inputs[$key]);
		}
		elseif(substr($val, 0, 3) == "zc-")
		{
			$timtumb_data['zc'] = intval(str_replace("zc-","", $val));
			unset($inputs[$key]);
		}
		elseif(substr($val, 0, 2) == "f-")
		{
			$timtumb_data['zc'] = intval(str_replace("zc-","", $val));
			$url .="&f=".str_replace("f-","", $val);
			unset($inputs[$key]);
		}
		elseif(substr($val, 0, 2) == "f-")
		{
			$timtumb_data['s'] = str_replace("s-","", $val);
			unset($inputs[$key]);
		}
		elseif(substr($val, 0, 3) == "cc-")
		{
			$timtumb_data['cc'] = str_replace("cc-","", $val);
			unset($inputs[$key]);
		}
		elseif(substr($val, 0, 3) == "ct-")
		{
			$timtumb_data['ct'] = str_replace("ct-","", $val);
			unset($inputs[$key]);
		}		
	}
	
	if(!empty($inputs))
	{
		$src = end($inputs);
		$src = str_replace(".jpg", "", $src);
		$timtumb_data['src'] = is_base64_encoded($src) ? base64_decode($src):$src;
	}
	
	return $timtumb_data;
}

if(!function_exists('get_headers'))
{
    function get_headers($url,$format=0)
    {
		if(extension_loaded('sockets') && function_exists('fsockopen'))
		{
			$url=parse_url($url);
			$end = "\r\n\r\n";
			$fp = fsockopen($url['host'], (empty($url['port'])?80:$url['port']), $errno, $errstr, 30);
			if ($fp)
			{
				$out  = "GET / HTTP/1.1\r\n";
				$out .= "Host: ".$url['host']."\r\n";
				$out .= "Connection: Close\r\n\r\n";
				$var  = '';
				fwrite($fp, $out);
				while (!feof($fp))
				{
					$var.=fgets($fp, 1280);
					if(strpos($var,$end))
						break;
				}
				fclose($fp);

				$var=preg_replace("/\r\n\r\n.*\$/",'',$var);
				$var=explode("\r\n",$var);
				if($format)
				{
					foreach($var as $i)
					{
						if(preg_match('/^([a-zA-Z -]+): +(.*)$/',$i,$parts))
							$v[$parts[1]]=$parts[2];
					}
					return $v;
				}
				else
					return $var;
			}
		}
		else
		{
			$handle = curl_init($url);
			curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

			/* Get the HTML or whatever is linked in $url. */
			$response = curl_exec($handle);

			/* Check for 404 (file not found). */
			$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
			$httpcode = array($httpCode);
			curl_close($handle);
			return $httpcode;
		}
    }
}

function notis_404($url)
{
	if(function_exists("get_headers"))
	{
		$headers = get_headers($url, 1);
		if ($headers[0] == 'HTTP/1.1 200 OK') {
			return true;
		}

		if ($headers[0] == 'HTTP/1.1 301 Moved Permanently') {
			return true;
		}
	}
	
	return false;
}
// gtset functions

// sockets functions
function phpnuke_get_url_contents($url, $is_file=false, $curl=true, $local_file = false)
{

	$file_content = '';
	
	$url = ($is_file) ? $url:trim($url, "/")."/";
	
	if($local_file && is_file($url))
	{
		$handle = fopen($url, 'rb');
		$file_content = fread($handle,filesize($url));
	}
	elseif (function_exists('curl_version') && $curl)
	{
		require_once(INCLUDE_PATH.'/Curl/Curl.php');
		require_once(INCLUDE_PATH.'/Curl/CaseInsensitiveArray.php');
		
		$curl = new Curl();
		$curl->setUserAgent('');
		$curl->setReferrer('');
		$curl->setHeader('X-Requested-With', 'XMLHttpRequest');
		$curl->get($url);
		$file_content = $curl->response;
	}
	elseif( ini_get('allow_url_fopen') )
	{
		$file_content = file_get_contents("$url");
	}
	elseif (extension_loaded('sockets') && function_exists('fsockopen') )
	{
		@require_once(INCLUDE_PATH."/class.HttpFsockopen.php");
		$object = new HttpFsockopen($url);
		$object->setTimeout(15);
		$respond = $object->exec();
		if($respond->getErrno())
			$file_content = $respond->getErrstr();
		else
			$file_content = $respond->getContent();
	}
	
	return $file_content;
}

function get_rss_contents($url, $limit = -1)
{
	$contents = array();
	$rss_contents = phpnuke_get_url_contents($url);
	preg_match_all("#<item>(.*)</item>#isU", $rss_contents, $items_match);
	$i = 0;
	foreach($items_match[1] as $items_match_val){
		preg_match_all("#<title>(.*)</title>#isU", $items_match_val, $titles_match);
		preg_match_all("#<link>(.*)</link>#isU", $items_match_val, $links_match);
		$contents[] = array("title" => $titles_match[1][0], "link" => $links_match[1][0]);
		$i++;
		if($i == $limit) break;
	}
	return $contents;	
}
// sockets functions

// keywords functions
function _pn_object_name_sort_cb( $a, $b )
{
	return strnatcasecmp( $a['tag'], $b['tag'] );
}

function _pn_object_count_sort_cb( $a, $b )
{
	return ( $a['counter'] > $b['counter'] );
}

function default_topic_count_scale( $counter )
{
	return round(log10($counter + 1) * 100);
}

function MT_Cloud_Tag( $tags, $args = '' )
{
	$defaults = array(
		'smallest' => 8, 'largest' => 22, 'unit' => 'px', 'number' => 0,
		'format' => 'flat', 'separator' => "\n", 'orderby' => 'tag', 'order' => 'ASC',
		'topic_count_scale_callback' => 'default_topic_count_scale', 'filter' => 1,
	);

	$args = pn_parse_args( $args, $defaults );

	$return = ( 'array' === $args['format'] ) ? array() : '';

	if ( empty( $tags ) ) {
		return $return;
	}
	
	if ( 'RAND' === $args['order'] ) {
		shuffle( $tags );
	} else {
		if ( 'tag' === $args['orderby'] ) {
			uasort( $tags, '_pn_object_name_sort_cb' );
		} else {
			uasort( $tags, '_pn_object_count_sort_cb' );
		}

		if ( 'DESC' === $args['order'] ) {
			$tags = array_reverse( $tags, true );
		}
	}

	if ( $args['number'] > 0 )
		$tags = array_slice( $tags, 0, $args['number'] );

	$counts = array();
	$real_counts = array(); // For the alt tag
	foreach ( (array) $tags as $key => $tag ) {
		$real_counts[ $key ] = $tag['counter'];
		$counts[ $key ] = call_user_func( $args['topic_count_scale_callback'], $tag['counter'] );
	}

	$min_count = min( $counts );
	$spread = max( $counts ) - $min_count;
	if ( $spread <= 0 )
		$spread = 1;
	$font_spread = $args['largest'] - $args['smallest'];
	if ( $font_spread < 0 )
		$font_spread = 1;
	$font_step = $font_spread / $spread;

	// Assemble the data that will be used to generate the tag cloud markup.
	$tags_data = array();
	foreach ( $tags as $key => $tag ) {
		$tag_id = isset( $tag['tag_id'] ) ? $tag['tag_id'] : $key;

		$counter = $counts[ $key ];
		$real_count = $real_counts[ $key ];

		$tags_data[] = array(
			'tag_id'        => $tag_id,
			'url'        => (isset($tag['link']) && '#' != $tag['link']) ? $tag['link'] : '#',
			'tag'	     => $tag['tag'],
			'real_count' => $real_count,
			'class'	     => 'tag-link-' . $tag_id,
			'font_size'  => $args['smallest'] + ( $counter - $min_count ) * $font_step,
		);
	}

	$a = array();

	// generate the output links array
	foreach ( $tags_data as $key => $tag_data ) {
		$class = $tag_data['class'] . ' tag-link-position-' . ( $key + 1 );
		$a[] = "<a href='" . LinkToGT( $tag_data['url'] ) . "' class='$class' title='' style='font-size: " . str_replace( ',', '.', $tag_data['font_size'] ) . $args['unit'] . ";'>" . $tag_data['tag'] . "</a>";
	}

	switch ( $args['format'] ) {
		case 'array' :
			$return =& $a;
			break;
		case 'list' :
			$return = "<ul class='pn-tag-cloud'>\n\t<li>";
			$return .= join( "</li>\n\t<li>", $a );
			$return .= "</li>\n</ul>\n";
			break;
		default :
			$return = join( $args['separator'], $a );
			break;
	}

	return $return;
}
			
function update_tags($old_tags, $updated_tags = '')
{
	global $db, $nuke_configs;
	
	$deleted_tags = array();
	$new_tags = array();
	$update_tags = array();
	
	if(!empty($old_tags))
	{
		foreach($old_tags as $old_tag)
		{
			if(!isset($updated_tags) || !in_array($old_tag, $updated_tags))
				$deleted_tags[] = $old_tag;
		}
	}

	if(isset($updated_tags) && !empty($updated_tags))
	{
		foreach($updated_tags as $tag)
		{
			$tag_nums = $db->table(TAGS_TABLE)
							->where('tag', $tag)
							->select(['tag_id']);

			if(intval($db->count()) > 0)
			{
				$tag_row = $tag_nums->results()[0];
				$tag_id = intval($tag_row['tag_id']);
				if(!in_array($tag, $old_tags))
				{
					$update_tags[] = $tag_id;
				}
			}
			else
			{
				$new_tags[] = array($tag, 1);
				continue;
			}
		}
		
		if(!empty($deleted_tags))
		{
			foreach($deleted_tags as $deleted_tag)
			{
				$tag_nums = $db->table(TAGS_TABLE)
								->where('tag', $deleted_tag)
								->select(['tag_id']);
				if(intval($db->count()) > 0)
				{
					$tag_row = $tag_nums->results()[0];
					$tag_ids[] = intval($tag_row['tag_id']);
				}
			}
			
			if(!empty($tag_ids))
			{
				$delete = $db->table(TAGS_TABLE)
					->in('tag_id', $tag_ids)
					->update([
						'counter' => false
					]);
			}
		}
		
		if(!empty($new_tags))
		{
			$inserted = $db->table(TAGS_TABLE)
				->multiinsert(
					['tag', 'counter'],
					$new_tags
				);
		}

		if(!empty($update_tags))
		{
			$db->table(TAGS_TABLE)
				->in('tag_id', $update_tags)
				->update([
					'counter' => ["+", 1]
				]);
		}
	}
}

function formatBytes($size, $precision = 2, $show_unit=false)
{
	global $nuke_configs;
    $base = log($size) / log(1024);
    $suffixes = array('', 'K', 'M', 'G', 'T');   

    return round(pow(1024, $base - floor($base)), $precision) . (($show_unit == true) ? " ".$nuke_configs['formatBytes'][$suffixes[floor($base)]]:"");
}

// keywords functions

/* pdf genearting function by iman64 */
function pdf_generate($author = "", $keywords = "", $subject = "", $title = "", $datetime = "", $contents="", $link = "")
{
	global $db, $nuke_configs;
	
	if(file_exists(INCLUDE_PATH."/pdf/mpdf.php"))
	{
		require_once(INCLUDE_PATH.'/pdf/mpdf.php');
		$mpdf = new mPDF('ar','A4', 0, '', 5, 5, 25, 15, 4, 4, 'P');
		$mpdf->SetDirectionality(_DIRECTION);
		$mpdf->rtlAsArabicFarsi = true;
		($author != "") ? $mpdf->SetAuthor($author):false;
		$mpdf->SetCreator($nuke_configs['sitename']);
		($keywords != "") ? $mpdf->SetKeywords($keywords):false;
		($subject != "") ? $mpdf->SetSubject($subject):false;
		($title != "") ? $mpdf->SetTitle($title):false;
		
		$parsed_link = parse_url($link);
		if($parsed_link['host'] == "localhost"){
			$link = str_replace("http://localhost","http://127.0.0.1", $link);
		}
		
		$mpdf->SetHTMLHeader('<table width="100%" style="border-bottom:1px solid #000;vertical-align: top; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
		<td style="text-align: right; " valign="middle">'.$title.'</td>
		<td width="80"><span style="font-weight: bold; font-style: italic;"><img src="'.$nuke_configs['nukecdnurl'].'images/logo.png" width="70" height=\"69\" alt="'.$nuke_configs['sitename'].'" title="'.$nuke_configs['sitename'].'"/></span></td>
		</tr></table>');
		if($link != "")
		{
			$link_to_show = explode("/", $link);
			$link_to_show = array_filter($link_to_show);
			$link_to_show = array_reverse($link_to_show);

			$link_last_start = "<span>//</span>".end($link_to_show);
			
			$link_last = "";
			foreach($link_to_show as $key => $link_to_show_val){
				if($key == (sizeof($link_to_show)-1)) continue;
				$link_last .="<span>/</span>$link_to_show_val";
			}
			$link_last = $link_last.$link_last_start;
		}
		else
		{
			$link_last = "";
		}
		
		$mpdf->SetHTMLFooter('<table width="100%" style="border-top:1px solid #000;vertical-align: top; color: #000000;font-size:11px;font-family:Tahoma;"><tr>
		<td width="33%" align="right">'.$datetime.'</td>
		<td width="33%" align="center">{PAGENO}/{nbpg}</td>
		<td width="33%" align="left">'.$link_last.'</td>
		</tr></table>');
		
		
		$mpdf->WriteHTML($contents);
		$mpdf->Output(null, 'I');
		exit;
	}
	else
	{
		include("header.php");
		$html_output .= OpenTable();
		$html_output .="<p align=\"center\">".sprintf(_NO_PDF_LIBRARIES_EXISTS, INCLUDE_PATH)."</p>";
		$html_output .= CloseTable();
		include("footer.php");
	}
	die();
}
/* pdf genearting function by iman64 */

function report_friend_form($submit = false, $mode = 'friend', $post_id = '', $post_title = '', $module_name='', $subject='', $message='', $post_link = '', $friend_name='', $friend_email='')
{
	global $db, $nuke_configs, $userinfo, $visitor_ip, $inline;
	$PnValidator = new GUMP();
	$content = '';
	if($submit)
	{
		if($mode == 'friend')
		{
			$friend_email = explode(",", $friend_email);
			$friend_email = $friend_email[0];
			
			$friend_message = "<p align=\"justify\">"._HELLO."<br />".sprintf(_FRIENDS_RECOMMENDED, "<a href=\"$post_link\">$post_title</a>")."<br />".$message."<br /><br />".sprintf(_MESSAGE_FROM_OURSITE, $nuke_configs['sitename'], $nuke_configs['nukeurl'])."</p>";
			
			$user_email = (isset($userinfo['user_email']) && $userinfo['user_email'] != '') ? $userinfo['user_email']:"";
			
			$realname = (isset($userinfo['realname']) && $userinfo['realname'] != '') ? $userinfo['realname']:"";
			
			$email_send = phpnuke_mail($friend_email, ""._ARTICLE_INTRODUCTION." $post_title", $friend_message, $user_email, $realname);
			
			if($email_send !== true)
				$results = array(
					'status' => 'danger',
					'message' => $email_send,
				);
			else
				$results = array(
					'status' => 'success',
					'message' => _SUCCESS_MSG_SENT,
				);
		}
		elseif($mode == 'report')
		{
			if(!isset($userinfo) || !isset($userinfo['is_registered']) || (isset($userinfo['is_registered']) && !$userinfo['is_registered']))
			{
				$results = array(
					'status' => 'danger',
					'message' => _ONLYMEMBERSCANREPORT,
				);
			}
			else
			{
				$report_form_fields = array("post_id" => $post_id, "post_title" => $post_title, "post_link" => $post_link, "module" => $module_name, "subject" => $subject, "message" => $message);
				
				$PnValidator->validation_rules(array(
					'post_id'		=> 'required|alpha_numeric',
					'subject'		=> 'required|max_len,500|min_len,3',
					'message'		=> 'required',
					'module'		=> 'required',
					'post_link'		=> 'required|valid_url',
				)); 
			
				// Get or set the filtering rules
				$PnValidator->filter_rules(array(
					'post_id'		=> 'sanitize_numbers',
					'post_title'	=> 'trim|sanitize_string',
					'subject'		=> 'trim|addslashes',
					'post_link'		=> 'trim',
					'message'		=> 'addslashes',
					'module'		=> 'trim|sanitize_string',
				));
				
				$report_form_fields = $PnValidator->sanitize($report_form_fields, array('subject','message'), true, true);
				$validated_data = $PnValidator->run($report_form_fields);

				if($validated_data !== FALSE)
				{
					$report_form_fields = $validated_data;	
					
					$result = $db->table(REPORTS_TABLE)
						->insert([
							'post_id' => $report_form_fields['post_id'],
							'user_id' => ((isset($userinfo['user_id'])) ? intval($userinfo['user_id']):0),
							'post_title' => $report_form_fields['post_title'],
							'post_link' => $report_form_fields['post_link'],
							'subject' => $report_form_fields['subject'],
							'message' => $report_form_fields['message'],
							'module' => $report_form_fields['module'],
							'time' => _NOWTIME,
							'ip' => $visitor_ip,
						]);
					if($result)
					{
						if($report_form_fields['module'] == 'comments')
						{
						
							$db->table(COMMENTS_TABLE)
								->where('cid', $report_form_fields['post_id'])
								->update(['reported' => 1]);
						}
						
						$results = array(
							'status' => 'success',
							'message' => _SUCCESS_REPORT_SENT,
						);
					}
				}
				else
				{
					$results = array(
						'status' => 'danger',
						'message' => $PnValidator->get_readable_errors(true,'','','<br />'),
					);
				}
			}
		}
		die(json_encode($results));
	}
	else
	{
		if($post_link == "" && $module_name == 'comments')
		{
		
			$result = $db->table(COMMENTS_TABLE)
						->where('cid', $post_id)
						->first(['module', 'post_title', 'post_id']);
						
			if($result->count() > 0)
			{
				$module_post_title = filter($result['post_title'], "nohtml");
				$post_title .= " $module_post_title";
				$module_post_id = intval($result['post_id']);
				$module = filter($result['module'], "nohtml");
				$post_link = (isset($nuke_configs['links_function'][$module]) && $nuke_configs['links_function'][$module] != '' && function_exists($nuke_configs['links_function'][$module])) ? $nuke_configs['links_function'][$module]($module_post_id):"";
				
				if(is_array($post_link))
					$post_link = $post_link[0];
			}
		}
		$post_link = LinkToGT($post_link);
		$report_firnd_form = ($mode == 'friend') ? "friend_form":"report_form";
		$header_of_form = ($mode == 'friend') ? _INTRODUCE_TO_FRIENDS:_PROBLEM_REPORT;
		$text_of_alert_form = ($mode == 'friend') ? "<div class=\"alert alert-info\">".sprintf(_INTRODUCE_AN_ARTICLE_TO_FRIENDS, $post_title)."</div>":"<div class=\"alert alert-danger\">".sprintf(_REPORT_AN_ARTICLE_TO_FRIENDS,$post_title)."</div>";
		
		$inline = (isset($inline) && $inline != '') ? $inline:'';
		if(function_exists("$report_firnd_form"))
			echo $report_firnd_form($post_id, $post_title, $module_name, $message, $post_link, $friend_name, $friend_email);
		else
		{
			$sec_code_options = array(
				"input_attr" => array(
					"class" => "form-control text-left",
					"data-rule-required" => "true",
					"data-msg-required" => _ENTER_SECCODEPLEASE
				)
			);
			
			$security_code_input = makepass("_FRIEND_REPORT_FORM", $sec_code_options);
			$content .="<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>
			<form id=\"friend_form\">
				<!-- Modal -->
				<div class=\"modal-header\">
					<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
					<h4 class=\"modal-title\">$header_of_form</h4>
				</div>
				<div class=\"modal-body\">
					$text_of_alert_form
					<div id=\"post-message\"></div>";
					if($mode == 'friend')
					{
					$content .="
					<div class=\"form-group\"> <!-- Name field -->
						<label class=\"control-label \" for=\"friend_form_name\">"._NAME."</label>
						<input class=\"form-control\" id=\"friend_form_name\" name=\"friend_form_name\" type=\"text\" placeholder=\""._ENTER_YOUR_FRIENDNAME."\" data-validation=\"required\"  />
					</div>

					<div class=\"form-group\"> <!-- Email field -->
						<label class=\"control-label\" for=\"friend_form_email\">"._EMAIL."<span class=\"asteriskField\">*</span></label>
						<input class=\"form-control text-left\" id=\"friend_form_email\" name=\"friend_form_email\" type=\"text\" placeholder=\""._ENTER_YOUR_FRIENDEMAIL."\" data-validation=\"required email\" data-validation-error-msg=\""._ENTER_YOUR_CORRECT_FRIENDEMAIL."\" />
					</div>

					<div class=\"form-group\"> <!-- Message field -->
						<label class=\"control-label \" for=\"friend_form_message\">"._MESSAGE."</label>
						<textarea class=\"form-control\" cols=\"40\" id=\"friend_form_message\" name=\"friend_form_message\" rows=\"3\" placeholder=\""._ENTER_MESSAGE."\" data-validation=\"required\" ></textarea>
					</div>";
					}
					elseif($mode == 'report')
					{
					$content .="
					<div class=\"form-group\"> <!-- Subject field -->
						<label class=\"control-label \" for=\"report_form_subject\">"._REPORT_TITLE."</label>
						<input class=\"form-control\" id=\"report_form_subject\" name=\"report_form_subject\" type=\"text\" placeholder=\""._ENTER_BRIEF_TITLE."\" data-validation=\"required\" data-validation-error-msg=\""._ENTER_TITLE_PLEASE."\" />
					</div>

					<div class=\"form-group\"> <!-- Message field -->
						<textarea class=\"form-control\" cols=\"40\" id=\"report_form_message\" name=\"report_form_message\" rows=\"3\" placeholder=\""._ENTER_CLEARLY_REPORTTEXT."\" data-validation=\"required\" data-validation-error-msg=\""._ENTER_REPORT_TEXT_PLEASE."\"></textarea>
					</div>";
					}
					$content .="<div class=\"form-group\"> <!-- Secure Code field -->
						<label class=\"control-label \" for=\"security_code_FRIEND_REPORT_FORM\">"._SECCODE."</label>
						".$security_code_input['image']."<br /><br />".$security_code_input['input']."
					</div>
				</div>
				<div class=\"modal-footer\">
					<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
					<button class=\"btn btn-primary\" id=\"friend-form-submit\" name=\"submit\" type=\"submit\">"._SEND."</button>
				</div>
			</form>
			<script>
				$( document ).ready( function () {
					$.validate({
						form : '#friend_form',
						modules : 'security',
						errorElementClass : 'has-error',
						onSuccess : function($"."form)
						{
							$.post(\"".LinkToGT("ajax.php")."\",
							{
								op: 'send_to_report_friend',
								mode: '$mode',";
								if($mode == "friend")
								{
								$content .="
								report_friend_form_name: $('#friend_form_name').val(),
								report_friend_form_email: $('#friend_form_email').val(),
								report_friend_form_message: $('#friend_form_message').val(),";
								}
								elseif($mode == "report")
								{
								$content .="
								report_friend_form_subject: $('#report_form_subject').val(),
								report_friend_form_message: $('#report_form_message').val(),";
								}
								$content .= "
								report_friend_form_post_title: '$post_title',
								report_friend_form_post_link: '$post_link',			
								report_friend_form_post_id: '$post_id',			
								report_friend_form_module_name: '$module_name',			
								report_friend_form_security_code: $('#security_code_FRIEND_REPORT_FORM').val(),			
								report_friend_form_security_code_id: '_FRIEND_REPORT_FORM',			
								csrf_token: '"._PN_CSRF_TOKEN."',			
							},
							function(data, status){
								data = typeof data === 'object' ? data : JSON.parse(data);
								var message = '<div class=\"alert alert-'+data.status+'\">'+data.message+'</div>';
								$('#post-message').fadeIn('1000').html(message);
								$('#friend-form-submit').hide(function(){
									if(data.status == 'success')
									{
										$('#sitemodal').modal('toggle');
									}
									setTimeout(\"$('#friend-form-submit').show('1000');$('#post-message').fadeOut('5000');\", 3000);
								});
							});
							
							return false;
						}
					});
				} );
			</script>";
			if($inline == '')
			{
				$contents = show_modules_boxes('Articles', "index", array("right"), $content);
				include("header.php");
				$html_output .=  $contents;
				include("footer.php");
			}
			die($content);
		}
	}
}

// id3 functions
function read_media_metadata( $file, $full = false )
{
	if ( ! file_exists( $file ) )
		return false;

	$metadata = array();

	if ( ! class_exists( 'getID3' ) )
		require_once('includes/ID3/getid3.php');
		
	$id3 = new getID3();
	$data = $id3->analyze( $file );
	if($full)
		return $data;
		
	if ( isset( $data['video']['lossless'] ) )
		$metadata['lossless'] = $data['video']['lossless'];
	if ( isset( $data['comments']['picture'][0]['data'] ) )
	{
		$metadata['cover']['data'] = $data['comments']['picture'][0]['data'];
		$metadata['cover']['mime_type'] = $data['comments']['picture'][0]['image_mime'];
	}
	if ( isset( $data['filename'] ) )
		$metadata['filename'] = $data['filename'];
	if ( ! empty( $data['video']['bitrate'] ) )
		$metadata['bitrate'] = (int) $data['video']['bitrate'];
	if ( ! empty( $data['video']['bitrate_mode'] ) )
		$metadata['bitrate_mode'] = $data['video']['bitrate_mode'];
	if ( ! empty( $data['filesize'] ) )
		$metadata['filesize'] = (int) $data['filesize'];
	if ( ! empty( $data['mime_type'] ) )
		$metadata['mime_type'] = $data['mime_type'];
	if ( ! empty( $data['playtime_seconds'] ) )
		$metadata['length'] = (int) ceil( $data['playtime_seconds'] );
	if ( ! empty( $data['playtime_string'] ) )
		$metadata['length_formatted'] = $data['playtime_string'];
	if ( ! empty( $data['video']['resolution_x'] ) )
		$metadata['width'] = (int) $data['video']['resolution_x'];
	if ( ! empty( $data['video']['resolution_y'] ) )
		$metadata['height'] = (int) $data['video']['resolution_y'];
	if ( ! empty( $data['fileformat'] ) )
		$metadata['fileformat'] = $data['fileformat'];
	if ( ! empty( $data['video']['dataformat'] ) )
		$metadata['dataformat'] = $data['video']['dataformat'];
	if ( ! empty( $data['video']['encoder'] ) )
		$metadata['encoder'] = $data['video']['encoder'];
	if ( ! empty( $data['video']['codec'] ) )
		$metadata['codec'] = $data['video']['codec'];
	if ( ! empty( $data['encoding'] ) )
		$metadata['encoding'] = $data['encoding'];
	if ( ! empty( $data['video']['dataformat'] ) )
	{
		if ( ! empty( $data[$data['video']['dataformat']]['exif']['IFD0']['Make'] ) )
			$metadata['make'] = $data[$data['video']['dataformat']]['exif']['IFD0']['Make'];
		if ( ! empty( $data[$data['video']['dataformat']]['exif']['IFD0']['Model'] ) )
			$metadata['model'] = $data[$data['video']['dataformat']]['exif']['IFD0']['Model'];
		if ( ! empty( $data[$data['video']['dataformat']]['exif']['IFD0']['DateTime'] ) )
			$metadata['datetime'] = $data[$data['video']['dataformat']]['exif']['IFD0']['DateTime'];
	}
	
	if ( ! empty( $data['audio'] ) ) {
		unset( $data['audio']['streams'] );
		$metadata['audio'] = $data['audio'];
	}
	return $metadata;
}

function ext2type($ext)
{
	$ext = strtolower($ext);
	$ext2type = array(
		'image'       => array( 'jpg', 'jpeg', 'jpe',  'gif',  'png',  'bmp',   'tif',  'tiff', 'ico' ),
		'audio'       => array( 'aac', 'ac3',  'aif',  'aiff', 'm3a',  'm4a',   'm4b',  'mka',  'mp1',  'mp2',  'mp3', 'ogg', 'oga', 'ram', 'wav', 'wma' ),
		'video'       => array( 'asf', 'avi',  'divx', 'dv',   'flv',  'm4v',   'mkv',  'mov',  'mp4',  'mpeg', 'mpg', 'mpv', 'ogm', 'ogv', 'qt',  'rm', 'vob', 'wmv' ),
		'document'    => array( 'doc', 'docx', 'docm', 'dotm', 'odt',  'pages', 'pdf',  'rtf',  'wp',   'wpd' ),
		'spreadsheet' => array( 'numbers',     'ods',  'xls',  'xlsx', 'xlsm',  'xlsb' ),
		'interactive' => array( 'swf', 'key',  'ppt',  'pptx', 'pptm', 'pps',   'ppsx', 'ppsm', 'sldx', 'sldm', 'odp' ),
		'text'        => array( 'asc', 'csv',  'tsv',  'txt' ),
		'archive'     => array( 'bz2', 'cab',  'dmg',  'gz',   'rar',  'sea',   'sit',  'sqx',  'tar',  'tgz',  'zip', '7z' ),
		'code'        => array( 'css', 'htm',  'html', 'php',  'js' ),
	);

	foreach ($ext2type as $type => $exts)
		if (in_array($ext,$exts))
			return $type;

	return null;
}

function get_allowed_mime_types()
{
	$t = get_mime_types();
	unset( $t['swf'], $t['exe'], $t['htm|html'] );
	return $t;
}

function check_filetype($filename, $mimes = null)
{
	if (empty($mimes))
		$mimes = get_allowed_mime_types();
	$type = false;
	$ext = false;

	foreach ($mimes as $ext_preg => $mime_match)
	{
		$ext_preg = '!\.(' . $ext_preg . ')$!i';
		if (preg_match($ext_preg, $filename, $ext_matches))
		{
			$type = $mime_match;
			$ext = $ext_matches[1];
			break;
		}
	}

	return compact( 'ext', 'type' );
}

function get_mime_types()
{
	// Accepted MIME types are set here as PCRE unless provided.
	return array(
	// Image formats
	'jpg|jpeg|jpe' => 'image/jpeg',
	'gif' => 'image/gif',
	'png' => 'image/png',
	'bmp' => 'image/bmp',
	'tif|tiff' => 'image/tiff',
	'ico' => 'image/x-icon',
	// Video formats
	'asf|asx' => 'video/x-ms-asf',
	'wmv' => 'video/x-ms-wmv',
	'wmx' => 'video/x-ms-wmx',
	'wm' => 'video/x-ms-wm',
	'avi' => 'video/avi',
	'divx' => 'video/divx',
	'flv' => 'video/x-flv',
	'mov|qt' => 'video/quicktime',
	'mpeg|mpg|mpe' => 'video/mpeg',
	'mp4|m4v' => 'video/mp4',
	'ogv' => 'video/ogg',
	'webm' => 'video/webm',
	'mkv' => 'video/x-matroska',
	// Text formats
	'txt|asc|c|cc|h' => 'text/plain',
	'csv' => 'text/csv',
	'tsv' => 'text/tab-separated-values',
	'ics' => 'text/calendar',
	'rtx' => 'text/richtext',
	'css' => 'text/css',
	'htm|html' => 'text/html',
	// Audio formats
	'mp3|m4a|m4b' => 'audio/mpeg',
	'ra|ram' => 'audio/x-realaudio',
	'wav' => 'audio/wav',
	'ogg|oga' => 'audio/ogg',
	'mid|midi' => 'audio/midi',
	'wma' => 'audio/x-ms-wma',
	'wax' => 'audio/x-ms-wax',
	'mka' => 'audio/x-matroska',
	// Misc application formats
	'rtf' => 'application/rtf',
	'js' => 'application/javascript',
	'pdf' => 'application/pdf',
	'swf' => 'application/x-shockwave-flash',
	'class' => 'application/java',
	'tar' => 'application/x-tar',
	'zip' => 'application/zip',
	'gz|gzip' => 'application/x-gzip',
	'rar' => 'application/rar',
	'7z' => 'application/x-7z-compressed',
	'exe' => 'application/x-msdownload',
	// MS Office formats
	'doc' => 'application/msword',
	'pot|pps|ppt' => 'application/vnd.ms-powerpoint',
	'wri' => 'application/vnd.ms-write',
	'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
	'mdb' => 'application/vnd.ms-access',
	'mpp' => 'application/vnd.ms-project',
	'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
	'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
	'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
	'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
	'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
	'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
	'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
	'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
	'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
	'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
	'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
	'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
	'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
	'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
	'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
	'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
	'sldm' => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
	'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
	// OpenOffice formats
	'odt' => 'application/vnd.oasis.opendocument.text',
	'odp' => 'application/vnd.oasis.opendocument.presentation',
	'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
	'odg' => 'application/vnd.oasis.opendocument.graphics',
	'odc' => 'application/vnd.oasis.opendocument.chart',
	'odb' => 'application/vnd.oasis.opendocument.database',
	'odf' => 'application/vnd.oasis.opendocument.formula',
	// WordPerfect formats
	'wp|wpd' => 'application/wordperfect',
	// iWork formats
	'key' => 'application/vnd.apple.keynote',
	'numbers' => 'application/vnd.apple.numbers',
	'pages' => 'application/vnd.apple.pages',
	);
}
// id3 functions

// array functions
function asd($array)
{
	die(print_r($array));
}

function array_sort_by_values($array, $main_key, $valuekey, $mode="asc")
{
	$new_array = array();
	if(!empty($array))
	{
		foreach($array as $key => $val)
		{
			if(isset($val[$valuekey]))
			{
				$new_key = $val[$valuekey];
				unset($val[$valuekey]);
				$val[$main_key] = $key;
				$new_array[$new_key] = $val;
			}
		}
	}
	if($mode == "asc")
		ksort($new_array);
	else
		krsort($new_array);
		
	return $new_array;
}

function phpnuke_array_change_key($array, $main_key, $new_key)
{
	$new_array = array();
	if(!empty($array))
	{
		foreach($array as $key => $val)
		{
			if(isset($val[$new_key]))
			{
				$new_key_val = $val[$new_key];
				$old_key_val = $key;
				$new_array[$new_key_val] = $val;
				
				if($main_key != '')
					$new_array[$new_key_val][$main_key] = $old_key_val;
			}
		}
	}
	return $new_array;
}

function multi_array_search($array, $search)
{
	// Create the result array
	$result = array();
	// Iterate over each array element
	foreach ($array as $key => $value)
	{
		// Iterate over each search condition
		foreach ($search as $k => $v)
		{
			// If the array element does not meet the search condition then continue to the next element
			if (!isset($value[$k]) || $value[$k] != $v)
			{
				continue 2;
			}
		}
		// Add the array element's key to the result array
		$result[] = $key;
	}
	// Return the result array
	return $result;
}

function is_list_array(array $array)
{
	$keys	= array_keys($array);
	$i=0;
	$is_list = false;
	foreach($keys as $key)
	{
		if($key === $i)
			$is_list = true;
		else
		{
			$is_list = false;
			break;
		}
		$i++;
	}
	
	return $is_list;
}

function is_multi_array($array)
{
    $rv = array_filter($array,'is_array');
    if(count($rv)>0) return true;
    return false;
}

function remove_id_in_array($id, $array)
{
	if(in_array($id, $array))
	{
		$key = array_search($id, $array);
		unset($array[$key]);
		$array = array_filter($array);
	}
	
	return $array;
}

function simple_array_flatten(array $array)
{
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}

function array_flatten($array, $parent_id = 0, $main_key = 'id', $parent_key = 'parent_id', $child_key = 'children', array $specific_keys, array $return)
{
	foreach($array as $key => $val)
	{
		unset($other_keys_values);
		$other_keys_values = array();
		$parent_id = ($parent_id == '' && isset($val[$parent_key])) ? $val[$parent_key]:$parent_id;
		if(isset($specific_keys) && !empty($specific_keys))
		{
			foreach($specific_keys as $specific_key)
			{
				if(isset($val[$specific_key]))
					$other_keys_values[$specific_key] = $val[$specific_key];
			}
		}
		
		$return[$val[$main_key]] = array_merge(array($main_key => $val[$main_key], $parent_key => $parent_id), $other_keys_values);
		
		if(isset($val[$child_key]))
			$return = array_flatten($val[$child_key], $val[$main_key], $main_key, $parent_key, $child_key, $specific_keys, $return);
	}
	return $return;
}

function phpnuke_array_remove_dup($array)
{
	for($i=0;$i < sizeof($array);$i++)
	{
		for($j=$i+1;$j < sizeof($array); $j++)
		{
			if($array[$i] == $array[$j])
			{
				$array[$j] = null;
			}
		}
	}
	$array = array_filter($array);
	$array = array_values($array);
	return $array;
}

function get_sub_lists($main_list=array(), $sub_list=array(), $id=0, $list_configs=array())
{
	$list_configs_array = array("checked_list", "list_type", "var_pid", "var_id", "has_input", "class_name", "input_name", "input_type", "var_value");
	foreach($list_configs_array as $list_config)
		$list_configs[$list_config] = (isset($list_configs[$list_config])) ? $list_configs[$list_config]:'';

	$sub_list_keys = array_keys($sub_list);
	$style = "";
	foreach($sub_list_keys as $sub_list_key)
	{
		if(is_array($list_configs['checked_list']) && !empty($list_configs['checked_list']) && in_array($sub_list_key, $list_configs['checked_list'])){
			$style = " style=\"display:block;\"";
		}
	}

	$list_type = (isset($list_configs['list_type']) && $list_configs['list_type'] != '') ? $list_configs['list_type']:"ul";
	
	$content = ($id != 0) ? "<".$list_type."".$style.">\n":"";
	
	foreach($sub_list as $list_key => $list_val){
		if($list_val[$list_configs['var_pid']] != $id) continue;
		$hassub = 0;
		unset($sub_list);
		$sub_list = array();
		foreach($main_list as $main_list_key => $main_list_val){
			if($main_list_key <= $list_key) continue;
			if($main_list_val[$list_configs['var_pid']] == $list_key){
				$sub_list[$main_list_key] = $main_list[$main_list_key];
				$hassub++;
			}
		}
		$checked = "";
		if(is_array($list_configs['checked_list']) && !empty($list_configs['checked_list']) && in_array($list_key, $list_configs['checked_list'])){
			$checked = "checked";
		}
		$content .= "\t<li>";
		
		$input_id = str_replace("{ID}", $list_key, $list_configs['var_id']);
		
		if($list_configs['has_input'])
			$content .= "<input class=\"".$list_configs['class_name']."\" name=\"".$list_configs['input_name']."\" value=\"$list_key\" type=\"".$list_configs['input_type']."\" id=\"$input_id\" $checked> <label for=\"$input_id\">".$list_val[$list_configs['var_value']]."</label>\n";
		else
			$content .= "".$list_val[$list_configs['var_value']]."\n";
		
		if($hassub > 0){
			$content .= get_sub_lists($main_list, $sub_list, $list_key, $list_configs);
		}
		
		$content .= "\t</li>\n";
	}
	if($id != 0) $content .= "</".$list_type.">\n";
	return $content;
}

function array_to_nested($array=array(), $sub_array=array(), $level_by_key="parent_id", $depth=0)
{
	$depth == intval($depth);
	$new_array = array();
	foreach($sub_array as $list_key => $list_val)
	{
		if($list_val[$level_by_key] != $depth) continue;
		$hassub = 0;
		unset($sub_array2);
		$sub_array2 = array();
		foreach($array as $array_list_key => $array_list_val)
		{
			//if($array_list_key <= $list_key) continue;
			if($array_list_val[$level_by_key] == $list_key)
			{
				$sub_array2[$array_list_key] = $array[$array_list_key];
				$hassub++;
			}
		}
		
		$new_array[$list_key] = $list_val;
		
		if($hassub > 0)
			$new_array[$list_key]['subs'] = array_to_nested($array, $sub_array2, $level_by_key, $list_key);
	}
	return $new_array;
}

function array_children_ids($array, &$output = array(), $parent_id = 0, $parent_id_name)
{
	if($parent_id != 0 && !in_array($parent_id, $output))
		$output[] = $parent_id;
		
	foreach($array as $id => $data)
	{
		if(in_array($data[$parent_id_name], $output))
			$output[] = $id;
	}
}

function stripslashes_from_strings_only( $value ) {
    return is_string( $value ) ? stripslashes( $value ) : $value;
}

function stripslashes_deep( $value ) {
    return map_deep( $value, 'stripslashes_from_strings_only' );
}

function map_deep( $value, $callback ) {
    if ( is_array( $value ) ) {
        foreach ( $value as $index => $item ) {
            $value[ $index ] = map_deep( $item, $callback );
        }
    } elseif ( is_object( $value ) ) {
        $object_vars = get_object_vars( $value );
        foreach ( $object_vars as $property_name => $property_value ) {
            $value->$property_name = map_deep( $property_value, $callback );
        }
    } else {
        $value = call_user_func( $callback, $value );
    }
 
    return $value;
}

function pn_parse_str( $string, &$array ) 
{
	parse_str( $string, $array );
	$array = stripslashes_deep( $array );
}

function pn_parse_args( $args, $defaults = '' )
{
	if ( is_object( $args ) )
		$r = get_object_vars( $args );
	elseif ( is_array( $args ) )
		$r =& $args;
	else
		pn_parse_str( $args, $r );

	if ( is_array( $defaults ) )
		return array_merge( $defaults, $r );
	if($r)
		return $r;
	
	return $args;
}

function get_parent_names($id, $array, $parent_field, $name_field, $names=array())
{
	$id = intval($id);
	if(!isset($array[$id][$name_field]))
		return array();
	$names[] = $array[$id][$name_field];
	if(isset($array[$id][$parent_field]) && $array[$id][$parent_field] != 0)
	{
		$names = get_parent_names($array[$id][$parent_field], $array, $parent_field, $name_field, $names);
	}
	return $names;
}

function arrayToObject($array)
{
	if (is_object($array))
	{
		return $array;
	}
	if(!empty($array))
	{
		foreach( $array as $key => $value )
		{
			if( is_array( $value ) )
			{
				$array[ $key ] = arrayToObject( $value );
			}
		}
	}
	
	return (object) $array;
}

function objectToArray($object)
{
	if (is_array($object) || is_object($object))
	{
		$result = array();
		if(!empty($object))
		{
			foreach ($object as $key => $value)
			{
				$result[$key] = objectToArray($value);
			}
		}
		return $result;
	}
	return $object;
}

function pn_array_map($value)
{
	 return (int)$value; 
}

function get_main_childs_parent($all_rows, $pid=0, $parent_id = 'pid')
{
	if(isset($all_rows[$pid]))
	{
		if($all_rows[$pid][$parent_id] == 0)
			return $pid;
		else
			return get_main_childs_parent($all_rows, $all_rows[$pid][$parent_id], $parent_id);
	
	}
}

// array functions

// security functions
function mtsn()
{
	global $db;
	@require_once(INCLUDE_PATH."/class.mtsn_injection.php");
	if ($_SERVER["QUERY_STRING"] != "")
	{
		$sql = new sql_inject;  //Nuke 8.4 fix
		$sql->test($_SERVER["QUERY_STRING"]);
	}
}

function data_verification_parse($data)
{
	$return_value = array();
	$quate = '"';
	if(is_array($data) && !empty($data))
	{
		foreach($data as $data_key => $data_value)
		{
			if(is_array($data_value) && isset($data_value[0]) && isset($data_value[1]) && $data_value[0] == 'json')
			{
				$quate = "'";
				$data_value = $data_value[1];
			}
			$return_value[] = "$data_key=".$quate."".$data_value."".$quate."";
			$quate = '"';
		}
		if(!empty($return_value))
			$return_value = implode(" ", $return_value);
	}
	
	return $return_value;
}

function get_form_token()
{
	global $pn_Sessions, $visitor_ip, $users_system;
	
	$token = (isset($users_system->session_id) && !defined("ADMIN_FILE")) ? $users_system->session_id:(csrfProtector::generateAuthToken());

	$csrf_token = $pn_Sessions->get('csrf_token', false);

	$csrf_token = phpnuke_unserialize($csrf_token);

	$csrf_token[$token] = array(
		'time' => _NOWTIME,
		'token'	=> $token,
	);

	$pn_Sessions->set('csrf_token', phpnuke_serialize($csrf_token));
	
	return $token;
}

// security functions

// category functions
function get_category_id($module_name, $category, $nuke_categories_cacheData=array())
{
	$categories = explode("/", trim($category,"/"));

	if(empty($nuke_categories_cacheData) || !isset($nuke_categories_cacheData))
		$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');

	$last_catid = 0;
	$last_catname = 0;
	$parent_id = 0;
	$categories_names = array();
	
	if(!isset($nuke_categories_cacheData[$module_name]))
		return false;
		
	foreach($categories as $category_name)
	{
		$category_name = sanitize(str2url($category_name));
		
		foreach($nuke_categories_cacheData[$module_name] as $catid => $cat_value)
		{
			//echo"".$cat_value['catname_url']." == $category_name && ".$cat_value['parent_id']." == $parent_id\n";
			if($cat_value['catname_url'] == $category_name && $cat_value['parent_id'] == $parent_id)
			{
				$categories_names[] = $category_name;
				$parent_id = $catid;
				$last_catid = $catid;
				$last_catname = $category_name;
				break;
			}
		}
	}
	
	$categories_names = implode("/", $categories_names);

	if($categories_names == $category)
		return $last_catid;
	else
		return (-1);	
}

function get_sub_categories_id($module_name, $parent_id, $nuke_categories_cacheData, $catids = array())
{
	$catids[] = $parent_id;
	$nested_categories = array_to_nested($nuke_categories_cacheData[$module_name], $nuke_categories_cacheData[$module_name]);
	if(isset($nested_categories[$parent_id]['subs']))
	{
		foreach($nested_categories[$parent_id]['subs'] as $catid => $cat_data)
			$catids = get_sub_categories_id($module_name, $catid, $nuke_categories_cacheData, $catids);
	}
	return $catids;
}

function category_link($module_name, $cat_title, $attrs=array(), $link_mode=1)
{
	global $nuke_configs;
	if(!isset($module_name))
		global $module_name;
	
	$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
		
	$cats_link_deep = array();
	$cats_link = array();
	
	$cat_titles = explode("/", $cat_title);
	
	foreach($cat_titles as $key => $cat_name)
	{
		$cats_link[$key] = (isset($cats_link[$key-1])) ? $cats_link[$key-1]."/".$cat_name:$cat_name;
	}
	
	if(!empty($attrs))
	{
		foreach($attrs as $key => $attribute)
		{
			$attributes[] = $key."=\"".$attribute."\"";
		}
		$attributes = implode(" ", $attributes);
	}
	else
		$attributes = '';
		
	foreach($cats_link as $cat_link)
	{
		if($cat_link == '') continue;
		
		$catid = get_category_id($module_name, $cat_link, $nuke_categories_cacheData);
		
		if($catid < 0) break;
		
		$cattext = filter(category_lang_text($nuke_categories_cacheData[$module_name][$catid]['cattext']), "nohtml");
		$catname = filter($nuke_categories_cacheData[$module_name][$catid]['catname'], "nohtml");
		$catname_url = filter($nuke_categories_cacheData[$module_name][$catid]['catname_url'], "nohtml");
		$module = filter($nuke_categories_cacheData[$module_name][$catid]['module'], "nohtml");
		
		$attributes_show = str_replace(
			array('{CAT_NAME}','{CAT_NAME_URL}','{CAT_IMAGE}','{CAT_TEXT}','{CATID}','{PARENT_ID}'), 
			array($catname, $catname_url, filter($nuke_categories_cacheData[$module_name][$catid]['catimage'], "nohtml"), $cattext, $catid, intval($nuke_categories_cacheData[$module_name][$catid]['parent_id'])), 
			$attributes
		);
		
		$cat_link = LinkToGT(str_replace("{CAT_NAME_URL}", $cat_link, $nuke_configs['categories_link'][$module]));
		if($link_mode == 1)
			$cats_link_deep[] = "<a href=\"$cat_link\"".((isset($attributes_show) && $attributes_show != '') ? " $attributes_show":'').">$cattext</a>";
		elseif($link_mode == 2)
			$cats_link_deep[] = array("<a href=\"$cat_link\"".((isset($attributes_show) && $attributes_show != '') ? " $attributes_show":'').">","$cattext","</a>");
		elseif($link_mode == 3)
			$cats_link_deep[] = $cat_link;
	}
	
	return $cats_link_deep;
}

function category_lang_text($cattext)
{
	global $nuke_configs;
	
	if($cattext != '')
	{
		$cattext_arr = phpnuke_unserialize(stripslashes($cattext));
		
		if(isset($cattext_arr[$nuke_configs['currentlang']]))
			return $cattext_arr[$nuke_configs['currentlang']];
		else
			return $cattext;
	}
	else
		return '';
}

class categories_list
{
	public $result = array();
	public $categories = '';
	public $exept_categories = array();
	public $special_categories = array();
	
	function __construct($categories_array)
	{
		$this->categories = $categories_array;
	}
	
	function categories_list($parent_id=0)
	{
		foreach($this->categories as $catid => $cat_data)
		{				
			//if($this->check_parent_cats($cat_data['parent_id']))
			//	$this->result[$cid][] = $this->categories_parent_list($cat_data['parent_id']);
				
			//$this->result[$cid][] = $cat_data['catname'];
			
			//$this->result[$cid] = implode("/", $this->result[$cid]);
			if(isset($this->exept_categories) && !empty($this->exept_categories) && in_array($catid, $this->exept_categories))
				continue;
			if(isset($this->special_categories) && !empty($this->special_categories) && !in_array($catid, $this->special_categories))
				continue;
				
			$this->result[$catid] = sanitize(filter(implode("/", array_reverse(get_parent_names($catid, $this->categories, "parent_id", "catname"))), "nohtml"), array("/"));
		}
		
	}
	
	/*
	
	function categories_parent_list($parent_id, $parent_name='')
	{
		foreach($this->categories as $cid => $cat_data)
		{					
			if($cid != $parent_id) continue;
			
			if($this->check_parent_cats($cat_data['parent_id']))
				$result[] = $this->categories_parent_list($cat_data['parent_id']);
				
			$result[] = $cat_data['catname'];
			$result = implode("/", $result);
		}
		return $result;
	}
	
	function check_parent_cats($parent_id)
	{
		foreach($this->categories as $cid => $cat_data)
		{
			if($cid == $parent_id)
				return true;
		}
		
		return false;
	}*/
}
// category functions

// nav menus functions
function pn_nav_menu($args = array())
{
	global $theme_setup;
	
	$nuke_nav_menus = get_cache_file_contents("nuke_nav_menus");

	if(empty($nuke_nav_menus))
		return;
	
	$menu = '';
	
	static $menu_id_slugs = array();

	$defaults = array(
		'nav_php_class' => 'Walker_nav_menus', 
		'menu' => '', 
		'list_type' => 'ul', 
		'container' => 'div', 
		'container_class' => '', 
		'container_id' => '', 
		'menu_class' => 'menu', 
		'menu_id' => '',
		'before' => '', 
		'after' => '', 
		'link_before' => '', 
		'link_after' => '', 
		'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth' => 0, 
		'walker' => '', 
		'theme_location' => ''
	);
	
	$args['nuke_categories'] = get_cache_file_contents('nuke_categories');
	$args = pn_parse_args( $args, $defaults );
	$args = (object) $args;
	
	// Get the nav menu based on the theme_location
	if ($args->theme_location && isset($theme_setup['theme_nav_menus'][ $args->theme_location ]) && isset($nuke_nav_menus[$args->theme_location]))
	{
		foreach($nuke_nav_menus[$args->theme_location] as $our_menu)
		{
			$menu = $our_menu;
			break;
		}
	}	
	
	if (empty( $args->menu ))
		$args->menu = $menu;

	$nav_menu = $items = '';

	$show_container = false;
	if ( $args->container ) {
	
		$allowed_tags = array( 'div', 'nav' );
		if (is_string($args->container) && in_array($args->container, $allowed_tags))
		{
			$show_container = true;
			$class = $args->container_class ? ' class="' . filter($args->container_class, "nohtml") . '"' : ' class="menu-'. rawurlencode($menu['nav_title']) .'-container"';
			$id = $args->container_id ? ' id="' . filter( $args->container_id, "nohtml") . '"' : '';
			$nav_menu .= '<'. $args->container . $id . $class . '>';
		}
	}

	$sorted_menu_items = $menu_items_with_children = array();
	$nav_title = $nav_location = '';
	
	if(!empty($menu))
	{
		if($nav_title == '')
		{
			$nav_title = filter($menu['nav_title'],"nohtml");
		}
		if(!empty($menu['nav_items']))
		{
			foreach($menu['nav_items'] as $nav_menu_data)
			{
				$nid = intval($nav_menu_data['nid']);
				$pid = intval($nav_menu_data['pid']);
				
				if($pid != 0 && !isset($sorted_menu_items[$pid]))
				{
					continue;
				}
				
				$title = filter($nav_menu_data['title'],"nohtml");
				$url = LinkToGT($nav_menu_data['url']);
				$type = filter($nav_menu_data['type'],"nohtml");
				$module = filter($nav_menu_data['module'],"nohtml");
				$part_id = intval($nav_menu_data['part_id']);
				$status_id = intval($nav_menu_data['status']);
				$attributes = ($nav_menu_data['attributes'] != '') ? $nav_menu_data['attributes']:array();
				$sorted_menu_items[$nid] = (object) array('nid' => $nid, 'pid' => $pid, 'title' => $title, 'url' => $url, 'type' => $type, 'module' => $module, 'part_id' => $part_id, 'status' => $status_id, "attributes" => (object) $attributes);
			}
		}
	}	
	
	if(!empty($sorted_menu_items))
	{
		$walker = new $args->nav_php_class;
		$args2 = array($sorted_menu_items, 'nid', 'pid', $args->depth, $args);
		$items .= call_user_func_array(array($walker, "walk"), $args2);
	}
	unset($sorted_menu_items);

	// Attributes
	if (! empty($args->menu_id))
		$wrap_id = $args->menu_id;
	else
	{
		$wrap_id = 'menu-' . rawurlencode($menu['nav_title']);
		while (in_array($wrap_id, $menu_id_slugs))
		{
			if ( preg_match( '#-(\d+)$#', $wrap_id, $matches ) )
				$wrap_id = preg_replace('#-(\d+)$#', '-' . ++$matches[1], $wrap_id );
			else
				$wrap_id = $wrap_id . '-1';
		}
	}
	$menu_id_slugs[] = $wrap_id;
	
	$wrap_class = (isset($args->menu_class)) ? $args->menu_class : '';
	
	// Don't print any markup if there are no items at this point.
	if ( empty( $items ) )
		return false;
		
	$nav_menu .= sprintf( $args->items_wrap, filter($wrap_id, "nohtml"), filter($wrap_class, "nohtml"), $items );
	unset( $items );

	if ( $show_container )
		$nav_menu .= '</' . $args->container . '>';

	return $nav_menu;
}
// nav menus functions


// users functions
function update_points($id,$uid=false)
{
    global $db, $userinfo, $users_system, $nuke_points_groups_cacheData;
	
	$id = intval($id);
	$points = intval($nuke_points_groups_cacheData[$id]['points']);
	$users_table_exists = false;
    if (is_user() && !$uid)
	{
        $username = trim($userinfo['username']);
		$users_table_exists = users_table_exists();
	}
	
	if($points > 0 && $users_table_exists)
	{
		$col_result = $db->query("SHOW COLUMNS FROM ".$users_system->users_table." LIKE '".$users_system->user_fields['user_points']."'");
		if(intval($col_result->count()) > 0)
		{
			if(isset($uid) && $uid > 0)
			{
				$col = $users_system->user_fields['user_id'];
				$col_val = $uid;
			}
			elseif($username != '')
			{
				$col = $users_system->user_fields['username'];
				$col_val = $username;
			}
			
			$db->table($users_system->users_table)
				->where($col, $col_val)
				->update([
					''.$users_system->user_fields['user_points'].'' => ["+", $points]
				]);
		}
	}
}

function phpnuke_validate_user_cookie($user, $type="user", $userinfo=array())
{
	global $pn_salt, $nuke_authors_cacheData;

	if(!isset($userinfo))
		global $userinfo;
		
	if(!is_array($user))
	{
		$user = base64_decode($user);
		$user = addslashes($user);
		$user = explode(':', $user);
	}
	
	$user_id = ($type == "user") ? $user[0]:0;
	$username = ($type == "user") ? $user[1]:$user[0];
	$expiration = ($type == "user") ? $user[2]:$user[1];
	$hmac = ($type == "user") ? $user[3]:$user[2];
	
	if (!$username)
		return false;
	
	if($type == "user" && isset($userinfo['user_password']))
	{
		$userinfo['pwd'] = $userinfo['user_password'];
	}
	
	$user_info = ($type == "user") ? $userinfo:((isset($nuke_authors_cacheData[$username])) ? $nuke_authors_cacheData[$username]:'');

	if($user_info == '')
		return false;
		
	$pass_frag = substr($user_info['pwd'], 8, 4);

	$key = hash_hmac('md5', $username . $pass_frag . '|' . $expiration, $pn_salt);
	$hash = hash_hmac('md5', $username . '|' . $expiration, $key);

	if (!hash_equals($hash, $hmac))
		return false;

	return true;
}

function phpnuke_generate_user_cookie($type="user", $user_id=0, $username, $user_password, $expiration=3600, $set_cookie=true)
{
	global $pn_salt, $userinfo, $pn_Cookies;
	
	$pass_frag = substr($user_password, 8, 4);

	$key = hash_hmac('md5', $username . $pass_frag . '|' . $expiration, $pn_salt);
	$hash = hash_hmac('md5', $username . '|' . $expiration, $key);

	$cookie = (($type == "user") ? "$user_id:":"") . $username . ':' . $expiration . ':' . $hash;
	
	$cookie = base64_encode(addslashes($cookie));
	
	if($set_cookie)
	{
		if($expiration)
			$pn_Cookies->set($type,$cookie,$expiration);
		else
			$pn_Cookies->set($type,$cookie);
	}
	
	return $cookie;
}

function get_author($aid)
{
	global $nuke_authors_cacheData, $nuke_configs;
	
	if(isset($nuke_authors_cacheData[$aid]))
	{
		$aidurl = filter($nuke_authors_cacheData[$aid]['url'], "nohtml");
		$aidmail = filter($nuke_authors_cacheData[$aid]['email'], "nohtml");
		
		if($aidurl != '')
		{
			$aidurl = adv_filter($aidurl, array(), array('valid_url'));
			$aidmail = adv_filter($aidmail, array('sanitize_email'), array('valid_email'));
			
			if ($aidurl[0] == 'success')
				$aid = "<a href=\"".$aidurl[1]."\">$aid</a>";
			elseif ($aidmail[0] == 'success')
				$aid = "<a href=\"mailto:".$aidmail[1]."\">$aid</a>";
			else
				$aid = "<a href=\"".$nuke_configs['nukeurl']."\">$aid</a>";
		}
		else
			$aid = "<a href=\"".$nuke_configs['nukeurl']."\">$aid</a>";
	}
    return $aid;
}

function formatAidHeader($aid)
{
  $AidHeader = get_author($aid);
  return $AidHeader;
}

function is_paid($user_id=0)
{
	global $db, $user, $cookie, $nuke_configs;
	$user_id = intval($user_id);
	if (is_user() || $user_id != 0)
	{
		$user_id = ($user_id != 0) ? $user_id:intval($userinfo['user_id']);
		
		$nuke_subscriptions_cacheData = get_cache_file_contents("nuke_subscriptions");
		
		if(array_key_exists($user_id, $nuke_subscriptions_cacheData))
			return true;
		else
			return false;
	}
	else
		return false;
}

function last_user()
{
    global $db, $users_system;
    $result = $db->query("SELECT ".$users_system->user_fields['username']." as username FROM ".$users_system->users_table." WHERE ".$users_system->user_fields['user_active']." = 1 ".(($users_system->user_fields['common_where'] != '') ? " AND ".$users_system->user_fields['common_where']:"")." ORDER BY ".$users_system->user_fields['user_id']." DESC LIMIT 0,1");
	$row = $result->last();
	$lastuser = $row['username'];
	
    return $lastuser;
}

function users_table_exists()
{
	global $db, $users_system, $nuke_configs;
	$users_table = $db->query("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME IN ('".str_replace(array($nuke_configs['forum_db'], ".", "`"),"", $users_system->users_table)."') AND TABLE_SCHEMA='".$nuke_configs['forum_db']."'");
	return ($users_table->count() > 0) ? true:false;
}
// users functions

//Total Members
function numusers()
{
    global $db, $users_system;
    $result = $db->query("SELECT COUNT(".$users_system->user_fields['user_id'].") as numusers FROM ".$users_system->users_table." ".(($users_system->user_fields['common_where'] != '') ? "WHERE ".$users_system->user_fields['common_where']:"")."");
	if(intval($db->count()) > 0)
	{
		$row = $result->results()[0];
		
		$numrows = $row['numusers'];
		$numrows = number_format($numrows);
		return $numrows;
	}
	return 0;
}

//New Users Today and Yesterday
function new_users()
{
    global $db, $users_system;
	$nowday = mktime(0,0,0,date("m"),date("d"),date("y"));
	$yesterday = $nowday-86400;
	$result1 = $db->query("SELECT COUNT(".$users_system->user_fields['user_id'].") as nowday_new_users FROM ".$users_system->users_table." WHERE ".$users_system->user_fields['user_regdate']." > '$nowday' ".(($users_system->user_fields['common_where'] != '') ? "AND ".$users_system->user_fields['common_where']:"")."");
	
	$userCount[0] = $result1[0]['nowday_new_users'];
	
	
	$result2 = $db->query("SELECT COUNT(".$users_system->user_fields['user_id'].") as yesterday_new_users FROM ".$users_system->users_table." WHERE(".$users_system->user_fields['user_regdate']." > '$yesterday' AND ".$users_system->user_fields['user_regdate']." < '$nowday' ".(($users_system->user_fields['common_where'] != '') ? "AND ".$users_system->user_fields['common_where']:"").")");
	
	$userCount[1] = $result2[0]['yesterday_new_users'];
	
    return $userCount;
}
// users functions

// filter functions

if (!function_exists('random_int'))//for PHP >= 5.1
{
	function random_int($min, $max)
	{
		if (!function_exists('mcrypt_create_iv'))
		{
			trigger_error(
			'mcrypt must be loaded for random_int to work',
			E_USER_WARNING
			);
			return null;
		}

		if (!is_int($min) || !is_int($max))
		{
			trigger_error("$min and $max must be integer values", E_USER_NOTICE);
			$min = (int)$min;
			$max = (int)$max;
		}

		if ($min > $max)
		{
			trigger_error("$max can't be lesser than $min", E_USER_WARNING);
			return null;
		}

		$range = $counter = $max - $min;
		$bits = 1;

		while ($counter >>= 1)
		{
			++$bits;
		}

		$bytes = (int)max(ceil($bits/8), 1);
		$bitmask = pow(2, $bits) - 1;

		if ($bitmask >= PHP_INT_MAX)
		{
			$bitmask = PHP_INT_MAX;
		}

		do {
			$result = hexdec(
				bin2hex(
					mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM)
				)
			) & $bitmask;
		} while ($result > $range);

		return $result + $min;
	}
}

function validateLatin($string) {
    $result = false;

    if (preg_match("/^[\w\d\s.,-]*$/", $string)) {
        $result = true;
    }

    return $result;
}

function text_rel2abs_call($matches)
{
	global $nuke_configs;
	if (empty($base))
		$base = $nuke_configs['nukeurl'];

	// base url needs trailing /
	if (substr($base, -1, 1) != "/")
		$base .= "/";
		
	$matches[3] = rel2abs( $matches[3], $base);
	
	return $matches[1]."=".$matches[2].$matches[3].$matches[4];
	
}

function text_rel2abs($text, $base = '')
{
	$pattern = "#(href|src)=('|\")(.*)('|\")#isU";
	$text = preg_replace_callback($pattern, "text_rel2abs_call", $text);
	// Done
	return $text;
}

function rel2abs($rel0, $base0)
{
	// init
	$base = parse_url($base0);
	$rel = parse_url($rel0);
	// init paths so we can blank the base path if we have a rel host
	if (array_key_exists("path", $rel))
		$relPath = $rel["path"];
	else
		$relPath = "";

	if (array_key_exists("path", $base))
		$basePath = $base["path"];
	else
		$basePath = "";

	// if rel has scheme, it has everything
	if (array_key_exists("scheme", $rel))
		return $rel0;
		
	// else use base scheme
	if (array_key_exists("scheme", $base))
		$abs = $base["scheme"];
	else
		$abs = "";
		
	if (strlen($abs) > 0)
		$abs .= "://";

	// if rel has host, it has everything, so blank the base path
	// else use base host and carry on
	if (array_key_exists("host", $rel))
	{
		$abs .= $rel["host"];
		if (array_key_exists("port", $rel))
		{
			$abs .= ":";
			$abs .= $rel["port"];
		}
		$basePath = "";
	}
	elseif(array_key_exists("host", $base))
	{
		$abs .= $base["host"];
		if (array_key_exists("port", $base))
		{
			$abs .= ":";
			$abs .= $base["port"];
		}
	}
	// if rel starts with slash, that's it
	if (strlen($relPath) > 0 && $relPath[0] == "/")
		return $abs . $relPath;

	// split the base path parts
	$parts = array();
	$absParts = explode("/", $basePath);
	foreach ($absParts as $part)
		array_push($parts, $part);

	// remove the first empty part
	while (count($parts) >= 1 && strlen($parts[0]) == 0)
		array_shift($parts);

	// split the rel base parts
	$relParts = explode("/", $relPath);
	if (count($relParts) > 0 && strlen($relParts[0]) > 0)
		array_pop($parts);
	
	// iterate over rel parts and do the math
	$addSlash = false;
	foreach ($relParts as $part)
	{
		if ($part == "")
		{
			//do nothing
		}
		elseif ($part == ".")
			$addSlash = true;
		elseif ($part == "..")
		{
			array_pop($parts);
			$addSlash = true;
		}
		else
		{
			array_push($parts, $part);
			$addSlash = false;
		}
	}
	// combine the result
	foreach ($parts as $part)
	{
		$abs .= "/";
		$abs .= $part;
	}
	
	if ($addSlash)
		$abs .= "/";
	
	if (array_key_exists("query", $rel))
	{
		$abs .= "?";
		$abs .= $rel["query"];
	}

	if (array_key_exists("fragment", $rel))
	{
		$abs .= "#";
		$abs .= $rel["fragment"];
	}

	return $abs;
}

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

function replaceAccentedChars($str)
{
    $patterns = array(
        /* Lowercase */
        '/[\x{0105}\x{00E0}\x{00E1}\x{00E2}\x{00E3}\x{00E4}\x{00E5}]/u',
        '/[\x{00E7}\x{010D}\x{0107}]/u',
        '/[\x{010F}]/u',
        '/[\x{00E8}\x{00E9}\x{00EA}\x{00EB}\x{011B}\x{0119}]/u',
        '/[\x{00EC}\x{00ED}\x{00EE}\x{00EF}]/u',
        '/[\x{0142}\x{013E}\x{013A}]/u',
        '/[\x{00F1}\x{0148}]/u',
        '/[\x{00F2}\x{00F3}\x{00F4}\x{00F5}\x{00F6}\x{00F8}]/u',
        '/[\x{0159}\x{0155}]/u',
        '/[\x{015B}\x{0161}]/u',
        '/[\x{00DF}]/u',
        '/[\x{0165}]/u',
        '/[\x{00F9}\x{00FA}\x{00FB}\x{00FC}\x{016F}]/u',
        '/[\x{00FD}\x{00FF}]/u',
        '/[\x{017C}\x{017A}\x{017E}]/u',
        '/[\x{00E6}]/u',
        '/[\x{0153}]/u',

        /* Uppercase */
        '/[\x{0104}\x{00C0}\x{00C1}\x{00C2}\x{00C3}\x{00C4}\x{00C5}]/u',
        '/[\x{00C7}\x{010C}\x{0106}]/u',
        '/[\x{010E}]/u',
        '/[\x{00C8}\x{00C9}\x{00CA}\x{00CB}\x{011A}\x{0118}]/u',
        '/[\x{0141}\x{013D}\x{0139}]/u',
        '/[\x{00D1}\x{0147}]/u',
        '/[\x{00D3}]/u',
        '/[\x{0158}\x{0154}]/u',
        '/[\x{015A}\x{0160}]/u',
        '/[\x{0164}]/u',
        '/[\x{00D9}\x{00DA}\x{00DB}\x{00DC}\x{016E}]/u',
        '/[\x{017B}\x{0179}\x{017D}]/u',
        '/[\x{00C6}]/u',
        '/[\x{0152}]/u');

    $replacements = array(
            'a', 'c', 'd', 'e', 'i', 'l', 'n', 'o', 'r', 's', 'ss', 't', 'u', 'y', 'z', 'ae', 'oe',
            'A', 'C', 'D', 'E', 'L', 'N', 'O', 'R', 'S', 'T', 'U', 'Z', 'AE', 'OE'
        );

    return preg_replace($patterns, $replacements, $str);
}

function str2url($str)
{
    if (function_exists('mb_strtolower'))
        $str = mb_strtolower($str, 'utf-8');

    $str = trim($str);
    if (!function_exists('mb_strtolower'))
        $str = replaceAccentedChars($str);

    // Remove all non-whitelist chars.
    $str = preg_replace('/[^a-zA-Z0-9\s\'\:\/\[\]\-\pL]/u', '', $str);
    $str = preg_replace('/[\s\'\:\/\[\]-]+/', ' ', $str);
    $str = str_replace(array(' ', '/'), '-', $str);

    // If it was not possible to lowercase the string with mb_strtolower, we do it after the transformations.
    // This way we lose fewer special chars.
    if (!function_exists('mb_strtolower'))
        $str = strtolower($str);

    return $str;
}

function sanitize($string, $except=array(), $force_lowercase = true, $anal = false)
{
    $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "â€”", "â€“", ",", "<", "،", ".", ">", "/", "?", "؟", "ً", "ٌ", "ٍ", "َ", "ُ", "ِ", "ّ", "ة", "×", "÷");
	if(is_array($except) && !empty($except))
	{
		foreach($except as $value)
		{
			$index = array_search($value,$strip);
			if($index !== FALSE){
				unset($strip[$index]);
			}
		}
	}
	
    $clean = trim(str_replace($strip, "", strip_tags($string)));
    $clean = preg_replace('/\s+/', "-", $clean);
    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
	
    return ($force_lowercase) ?
        (function_exists('mb_strtolower')) ?
            mb_strtolower($clean, 'UTF-8') :
            strtolower($clean) :
        $clean;
}

function check_html ($str, $strip="")
{
if ($strip == "nohtml")
{
        $str = @trim($str);
        $str = stripslashes($str);
        return htmlentities($str,ENT_QUOTES,"utf-8");
    }
	else
	{

        $str = @trim($str);
		$str = stripslashes($str);
        return mres($str);
    }
}

function filter($str , $strip="")
{
	if ($strip == "nohtml"){
		$str = filter_var($str, FILTER_SANITIZE_STRING);
		$str = strip_tags(trim($str));
		return $str;
	}else {
		if(isset($_POST))
		{
			foreach ($_POST as $key => $value)							
			$_POST[$key] =str_replace("'", '', $_POST[$key]);		
		}
		if(isset($_GET))
		{
			foreach ($_GET as $key => $value)							
			$_GET[$key] =str_replace("'", '', $_GET[$key]);		
		}
		$str = addslashes(trim($str));
		$str= mres($str);
		return $str;
	}
} //End Nuke 8.4 Fix

function FixQuotes ($what = "")
{
	while (stristr($what, "\\\\'"))
	{
		$what = str_replace("\\\\'","'",$what);
	}
	return $what;
}

function _truncate_post_slug($slug, $length = 200)
{
	if (strlen($slug) > $length)
	{
		$decoded_slug = urldecode($slug);
		if ($decoded_slug === $slug)
			$slug = substr($slug, 0, $length);
		else
			$slug = utf8_uri_encode($decoded_slug, $length);
	}

	return rtrim($slug, '-');
}

/*function minify_js($match)
{
	$script = $match[2];
	if($script != '')
	{
		$script = explode("\n", $script);
		foreach($script as $key => $line)
		{
			$line2 = trim($line, "\t");
			if(substr($line2, 0, 2) == '//')
				$script[$key] = "/*".substr($line2, 2, -1)."";
		}
		$script = implode("\n", $script);
	}
	return "<script".(($match[1] != '') ? $match[1]:"").">".$script."</script>";
}*/

// HTML Minifier
function minify_html($input) {
    if(trim($input) === "") return $input;
    // Remove extra white-space(s) between HTML attribute(s)
    $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
        return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
    }, str_replace("\r", "", $input));
    // Minify inline CSS declaration(s)
    if(strpos($input, ' style=') !== false) {
        $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
            return '<' . $matches[1] . ' style=' . $matches[2] . minify_css($matches[3]) . $matches[2];
        }, $input);
    }
    if(strpos($input, '</style>') !== false) {
      $input = preg_replace_callback('#<style(.*?)>(.*?)</style>#is', function($matches) {
        return '<style' . $matches[1] .'>'. minify_css($matches[2]) . '</style>';
      }, $input);
    }
    if(strpos($input, '</script>') !== false) {
      $input = preg_replace_callback('#<script(.*?)>(.*?)</script>#is', function($matches) {
        return '<script' . $matches[1] .'>'. minify_js($matches[2]) . '</script>';
      }, $input);
    }
    return preg_replace(
        array(
            // t = text
            // o = tag open
            // c = tag close
            // Keep important white-space(s) after self-closing HTML tag(s)
            '#<(img|input)(>| .*?>)#s',
            // Remove a line break and two or more white-space(s) between tag(s)
            '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
            '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
            '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
            '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
            '#<(img|input)(>| .*?>)<\/\1>#s', // reset previous fix
            '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
            '#(?<=\>)(&nbsp;)(?=\<)#', // --ibid
            // Remove HTML comment(s) except IE comment(s)
            '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
        ),
        array(
            '<$1$2</$1>',
            '$1$2$3',
            '$1$2$3',
            '$1$2$3$4$5',
            '$1$2$3$4$5$6$7',
            '$1$2$3',
            '<$1$2',
            '$1 ',
            '$1',
            ""
        ),
    $input);
}
// CSS Minifier => http://ideone.com/Q5USEF + improvement(s)
function minify_css($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
            // Remove unused white-space(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
            '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
            // Replace `:0 0 0 0` with `:0`
            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
            // Replace `background-position:0` with `background-position:0 0`
            '#(background-position):0(?=[;\}])#si',
            // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
            '#(?<=[\s:,\-])0+\.(\d+)#s',
            // Minify string value
            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
            // Minify HEX color code
            '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
            // Replace `(border|outline):none` with `(border|outline):0`
            '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
            // Remove empty selector(s)
            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
        ),
        array(
            '$1',
            '$1$2$3$4$5$6$7',
            '$1',
            ':0',
            '$1:0 0',
            '.$1',
            '$1$3',
            '$1$2$4$5',
            '$1$2$3',
            '$1:0',
            '$1$2'
        ),
    $input);
}

// JavaScript Minifier
function minify_js($input) {
    if(trim($input) === "") return $input;
    if($input != "")
	{
		return preg_replace(
			array(
				// Remove comment(s)
				'#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
				// Remove white-space(s) outside the string and regex
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
				// Remove the last semicolon
				'#;+\}#',
				// Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
				'#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
				// --ibid. From `foo['bar']` to `foo.bar`
				'#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
			),
			array(
				'$1',
				'$1$2',
				'}',
				'$1$3',
				'$1.$3'
			),
		$input);
	}
}

/**
 * shortens the supplied text after last word
 * @param string $string
 * @param int $max_length
 * @param string $end_substitute text to append, for example "..."
 * @param boolean $html_linebreaks if LF entities should be converted to <br />
 * @return string
 */
function mb_word_wrap($string, $max_length, $end_substitute = null, $html_linebreaks = true)
{ 
    if($html_linebreaks) $string = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    $string = strip_tags($string); //gets rid of the HTML

    if(empty($string) || mb_strlen($string) <= $max_length)
	{
        if($html_linebreaks) $string = nl2br($string);
        return $string;
    }

    /*if($end_substitute) $max_length -= mb_strlen($end_substitute, 'UTF-8');

    $stack_count = 0;
    while($max_length > 0)
	{
        $char = mb_substr($string, --$max_length, 1, 'UTF-8');
        if(preg_match('#[^\p{L}\p{N}]#iu', $char)) $stack_count++; //only alnum characters
        elseif($stack_count > 0) {
            $max_length++;
            break;
        }
    }*/
	
    $string = mb_substr($string, 0, $max_length, 'UTF-8').$end_substitute;
    if($html_linebreaks) $string = nl2br($string);

    return $string;
}

function removecrlf($str)
{
	return strtr($str, "\015\012", ' ');
}

function validate_mail($email)
{
	global $nuke_configs;
	$PnValidator = new GUMP();
	
	$email_array = array("email" => $email);
	
	if($nuke_configs['phpver'] > '5.2.0')
	{
		$PnValidator->add_validator("email_match", function($field, $input, $param = NULL)
		{
			$email = $input[$field];
			return (preg_match('/@.+\./', $email) && !preg_match('/@\[/', $email) && !preg_match('/".+@/', $email)&& !preg_match('/=.+@/', $email));
		}); 
		
		$PnValidator->validation_rules(array(
			'email'			=> 'valid_email|email_match'
		)); 
		// Get or set the filtering rules
		$PnValidator->filter_rules(array(
			'email'			=> 'sanitize_email'
		)); 

		$validated_data = $PnValidator->run($email_array);

		// validate submitted data
		if($validated_data !== FALSE)
			$email_array = $validated_data;
		else
			return false;
	}
	else
	{
		$pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
		if(!preg_match($pattern,$email_array['email']))
			return false;
	}
	
	return $email_array['email'];
}

function adv_filter($string, $filters = array(), $validation = array(), $sanitize=true)
{
	$PnValidator = new GUMP();
	
	$string = array("string" => $string);
	
	if(!empty($validation))
	{
		$PnValidator->validation_rules(array("string" => "".implode("|", $validation).""));
	}
	if(!empty($filters))
	{
		// Get or set the filtering rules
		$PnValidator->filter_rules(array("string" => "".implode("|", $filters)."")); 
	}
	
	if($sanitize)
		$string = $PnValidator->sanitize($string, array('string'), true, true);
	
	$validated_data = $PnValidator->run($string);
	if($validated_data !== FALSE)
		return array('success', $validated_data['string']);
	
	return array('error', $PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br />'));
	
}

function highlightWords($text, $words)
{
	if(preg_match("|($words)|Uui", $text, $matchs))
	{	
		$matchs = array_filter($matchs);
		if(!empty($matchs))
			$text = preg_replace("|($words)|Uui", "<span class=\"highlight_word\">$1</span>", $text);
	}
    return $text;
}

function closetags($html)
{
	#put all opened tags into an array
	@preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
	$openedtags = $result[1];
	#put all closed tags into an array
	@preg_match_all('#</([a-z]+)>#iU', $html, $result);
	$closedtags = $result[1];
	$len_opened = count($openedtags);
	# all tags are closed
	if (count($closedtags) == $len_opened)
		return $html;
		
	$openedtags = array_reverse($openedtags);
	# close tags
	for ($i=0; $i < $len_opened; $i++)
	{
		if (!in_array($openedtags[$i], $closedtags))
			$html .= '</'.$openedtags[$i].'>';
		else
			unset($closedtags[array_search($openedtags[$i], $closedtags)]);
	}
	return $html;
}

function correct_text($text)
{
	$bad_chars = array(
		"&quot;",
		"&zwnj;",
		"&laquo;",
		"&raquo;",
		"&rlm;",
		"&lrm;",
		"&ndash;",
		"&zwj;",
		"&not;",
	);
	$escape_chars = array(
		"&" => "&amp;",
		"'" => "&apos;",
		'"' => "&quot;",
	);
	$text = str_replace($bad_chars,"",$text);
	$text = str_replace(array_keys($escape_chars),array_values($escape_chars),$text);
	return $text;
}

function mres($value)
{
	//if(function_exists("mysql_real_escape_string"))
	//	return mysql_real_escape_string($value);
	
    $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
    $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

    return str_replace($search, $replace, $value);
}

function smilies_parse($text, $generate = false)
{
	global $nuke_configs;
	
	$smilies_configs = ($nuke_configs['smilies'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['smilies'])):array();

	if(!empty($smilies_configs))
	{
		foreach($smilies_configs as $smilie_data)
		{
			$smilie_data = array_filter($smilie_data);
			if(empty($smilie_data))
				continue;
				
			$smilie_name = $smilie_data['name'];
			$option_code = $smilie_data['code'];
			$option_url = $smilie_data['url'];
			$option_dimentions = $smilie_data['dimentions'];
			$option_dimentions = explode("*", $option_dimentions);
			$option_dimentions_w = $option_dimentions[0];
			$option_dimentions_h = $option_dimentions[1];

			if($generate)
				$text .="<a href=\"javascript:Smiles('$option_code')\"><img src=\"".LinkToGT($option_url)."\" border=0 alt='$option_code' height=\"$option_dimentions_h\" width=\"$option_dimentions_w\" title=\"$smilie_name\"></a> ";
			else
			{
				$img_code = "<img src=\"".LinkToGT($option_url)."\" border=0 alt='$option_code' height=\"$option_dimentions_h\" width=\"$option_dimentions_w\" title=\"$smilie_name\"></a>";
				$text = str_replace("$option_code ", "$img_code ", $text);
				$text = str_replace(" $option_code", " $img_code", $text);
			}
		}
	}
	return $text;
}
// filter functions


// languages functions
function selectlanguage()
{
	global $useflags, $nuke_configs,$align;
	if ($useflags == 1) {
		$title = _SELECTLANGUAGE;
		$content = "<div class=\"text-center\"><font class=\"content\">"._SELECTGUILANG."<br><br>";
		$langdir = dir("language");
		while($func=$langdir->read()) {
		/*if(substr($func, 0, 5) == "lang-") {
			$menulist .= "$func ";
		}*/
		if($func!="." && $func!=".."&&$func!="index.html" && $func!=".htaccess"){
			$menulist .= "$func ";
		}
		}
		closedir($langdir->handle);
		$menulist = explode(" ", $menulist);
		sort($menulist);
		$content = "<a href=\"".$nuke_configs['nukeurl']."index.php?newlang=$nolang\"><img src=\"".$nuke_configs['nukecdnurl']."images/language/flag-farsi.png\" width=\"30\" height=\"16\" border=\"0\" alt=\"".DEFLANG."\" title=\"".DEFLANG."\" hspace=\"3\" vspace=\"3\"></a><br>";
		for ($i=0; $i < sizeof($menulist); $i++) {
			if($menulist[$i]!="") {
				$tl = str_replace("lang-","",$menulist[$i]);
				$tl = str_replace(".php","",$tl);
				$altlang = ucfirst($tl);
				$content .= "<a href=\"".$nuke_configs['nukeurl']."index.php?newlang=".$tl."&alignment=$align\"><img src=\"".$nuke_configs['nukecdnurl']."images/language/flag-".$tl.".png\" border=\"0\" alt=\"$altlang\" title=\"$altlang\" hspace=\"3\" vspace=\"3\" width=\"30\" height=\"16\" /></a> ";
			}
		}
		$content .= "</font></div>";
		themesidebox($title, $content, $themeview, $themetype);
	} else {
		$title = _SELECTLANGUAGE;
		$content = "<div class=\"text-center\"><font class=\"content\">"._SELECTGUILANG."<br><br></font>";
		$content .= "<form action=\"".$nuke_configs['nukeurl']."index.php\" method=\"get\"><select name=\"newlanguage\" onChange=\"top.location.href=this.options[this.selectedIndex].value\">
		<option value=\"\">"._NOLANG."</option>
		<option value=\"".$nuke_configs['nukeurl']."index.php?newlang=nolang\">"._DEFLANG."</option>
		";
		$handle=opendir('language');
		$languageslist = "";
		while ($file = readdir($handle)) {
			if($file != "." && $file != ".." && $file != "index.html" && $file != ".htaccess"){
			$languageslist[] = $file;
			}
		}
		closedir($handle);
		sort($languageslist);
		for ($i=0; $i < sizeof($languageslist); $i++) {
			if($languageslist[$i]!="") {
				$content .= "<option value=\"index.php?newlang=".$languageslist[$i]."&alignment=$align\" ";
				if($languageslist[$i]==$nuke_configs['currentlang']) $content .= " selected";
				$content .= ">".ucfirst($languageslist[$i])."</option>\n";
			}
		}
		$content .= "</select><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> </form></div>";
		themesidebox($title, $content, $themeview, $themetype);
	}
}

function get_dir_list($path, $options='folders', $sort=false, $except_list = array(".","..",""))
{
	$path = trim($path, "/");
	$handle=opendir($path);
	$dir_list = array();
	while ($file = readdir($handle))
	{
		if(!in_array($file, $except_list))
		{
			if($options == "folders" && is_dir($path."/$file"))
			{    
				$dir_list[] = "$file";
			}
			elseif($options == "files" && !is_dir($path."/$file"))
			{  
				$dir_list[] = "$file";
			}
			elseif($options == "both")
			{ 
				$dir_list[] = "$file";
			}
		}
	}

	closedir($handle);
	if(!empty($dir_list))
	{
		$dir_list = array_filter($dir_list);

		if($sort)
			sort($dir_list);
	}
	
	return $dir_list;
}

function get_languages_data($mode="")
{
	global $nuke_configs, $moduleslist, $nuke_languages_cacheData;

	if($mode == "all")
	{
		$all_languages = get_dir_list('language', "files", true);
		foreach($all_languages as $language)
		{
			if($language == 'index.html' || $language == '.htaccess' || $language == 'alphabets.php') continue;
			$language = str_replace(".php", "", $language);
			@include("language/".$language.".php");
			if(file_exists("themes/".$nuke_configs['ThemeSel']."/language/".$language.".php"))
			{
				@include("themes/".$nuke_configs['ThemeSel']."/language/".$language.".php");
			}
		}
				
		foreach($moduleslist as $modulename)
		{
			foreach($all_languages as $language)
			{
				if($language == 'index.html' || $language == '.htaccess' || $language == 'alphabets.php') continue;
				$language = str_replace(".php", "", $language);
				if(file_exists("modules/$modulename/language/".$language.".php"))
					@include("modules/$modulename/language/".$language.".php");
			}
		}
	}
	else
	{
		@include("language/".$nuke_configs['currentlang'].".php");
		
		foreach($moduleslist as $modulename)
		{
			if(file_exists("modules/$modulename/language/".$nuke_configs['currentlang'].".php"))
				@include("modules/$modulename/language/".$nuke_configs['currentlang'].".php");
		}
		
		if(file_exists("themes/".$nuke_configs['ThemeSel']."/language/".$nuke_configs['currentlang'].".php"))
		{
			@include("themes/".$nuke_configs['ThemeSel']."/language/".$nuke_configs['currentlang'].".php");
		}
	}
	
	if(is_array($nuke_languages_cacheData) && !empty($nuke_languages_cacheData))
	{
		foreach($nuke_languages_cacheData as $key => $val)
		{
			$main_word = filter($key, "nohtml");
			$equals = ($val['equals'] != '') ? $val['equals']:array();
			$equal_in_currentlang = isset($equals[$nuke_configs['currentlang']]) ? $equals[$nuke_configs['currentlang']]:$main_word;
			
			if($mode == "all")
			{
				foreach($all_languages as $language)
				{
					if($language == 'index.html' || $language == '.htaccess' || $language == 'alphabets.php') continue;
					$language = str_replace(".php", "", $language);
					$nuke_languages[$language] = array_merge($nuke_languages[$language], array($main_word => isset($equals[$language]) ? $equals[$language]:$main_word));
					
				}
			}
			else
				$nuke_languages[$nuke_configs['currentlang']] = array_merge($nuke_languages[$nuke_configs['currentlang']], array($main_word => $equal_in_currentlang));
		}
	}
	
	return $nuke_languages;
}

// languages functions

?>