<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* https://www.phpnuke.ir                                                   */
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

function get_feed_data($module_args)
{
	global $nuke_configs;
	
	$feeds_data = array();
	
	$module_feed_function = (isset($module_args['main_module'])) ? ((is_active($module_args['main_module'])) ? strtolower($module_args['main_module'])."_feed":"articles_feed"):"articles_feed";
	$post_type = (isset($module_args['main_module']) && $module_args['main_module'] != 'Articles') ? $module_args['main_module']:'Articles';
	
	if(function_exists($module_feed_function))
		$feeds_data = $module_feed_function($module_args, $post_type);
	
	return $feeds_data;
}

function feed($module_link = 'Articles', $mode = 'ATOM')
{
	global $db, $userinfo, $page, $module_name, $visitor_ip, $nuke_configs, $nuke_authors_cacheData;

	if(!in_array(strtolower($mode), array('atom','rss1','rss2')))
		$mode = 'atom';
	
	$module_args = parse_GT_link($module_link);
	
	$feeds_data = get_feed_data($module_args[0]);

	include INCLUDE_PATH.'/rss/Item.php';
	include INCLUDE_PATH.'/rss/Feed.php';
	include INCLUDE_PATH.'/rss/'.strtoupper($mode).'.php';
	include INCLUDE_PATH.'/rss/InvalidOperationException.php';

	$now = date('Y-m-d\TH:i:s+00:00',_NOWTIME);

	$rss_ver = ucfirst($mode);
	
	//Creating an instance of RSS1 class.
	$TestFeed = new $rss_ver();

	//Setting the channel elements
	//Use wrapper functions for common elements
	//For other optional channel elements, use setChannelElement() function
	$TestFeed->setTitle($nuke_configs['sitename']);
	$TestFeed->setLink($nuke_configs['nukeurl']);
	$TestFeed->setDescription($nuke_configs['slogan']);
	//It's important for RSS 1.0
	if($mode == 'rss1')
		$TestFeed->setChannelAbout($nuke_configs['nukeurl']);

	if($mode != 'rss1')
	{
		if($mode == 'atom')
			$TestFeed->setDate(new DateTime());
		else
			$TestFeed->setDate(_NOWTIME);
	}
	
	if($mode != 'rss1')
		$TestFeed->setChannelElement('pubDate', $now);

	$TestFeed->setChannelElement('author', array('name'=> $nuke_configs['sitename']));
	
	// An image is optional.
	$TestFeed->setImage(LinkToGT($nuke_configs['nukecdnurl']."images/logo.png"), $nuke_configs['sitename'], $nuke_configs['nukeurl']);
	$TestFeed->setChannelElementsFromArray([
		'language' => $nuke_configs['language'],
		'dc:language' => _LANGSMALLNAME,
		'dc:creator' => $nuke_configs['adminmail'],
		'dc:date' => $now,
		'sy:updatePeriod' => 'hourly',
		'sy:updateFrequency' => '1',
		'sy:updateBase' => $now,
	]);

	//Adding a feed. Generally this portion will be in a loop and add all feeds.

	foreach($feeds_data as $key => $feed_data)
	{
		//Create an empty FeedItem
		$newItem = $TestFeed->createNewItem();
		//Add elements to the feed item
		//Use wrapper functions to add common feed elements
		if(isset($feed_data['title']) && $feed_data['title'] != '')
			$newItem->setTitle($feed_data['title']);
		if($mode != 'rss1' && isset($feed_data['aid']) && isset($nuke_authors_cacheData[$feed_data['aid']]['email']) && $nuke_authors_cacheData[$feed_data['aid']]['email'] != '')
			$newItem->setAuthor($feed_data['aid'], $nuke_authors_cacheData[$feed_data['aid']]['email']);
		if(isset($feed_data['link']) && $feed_data['link'] != '')
			$newItem->setLink($feed_data['link']);
		//The parameter is a timestamp for setDate() function
		if(isset($feed_data['pubDate']) && $feed_data['pubDate'] != '')
			$newItem->setDate($feed_data['pubDate']);
		if(isset($feed_data['description']) && $feed_data['description'] != '')
			$newItem->setDescription($feed_data['description']);
		if($mode == 'atom' && isset($feed_data['content']) && $feed_data['content'] != '')
			$newItem->setContent($feed_data['content']);
		//Use core addElement() function for other supported optional elements
		if(isset($feed_data['title']) && $feed_data['title'] != '')
			$newItem->addElement('dc:subject', $feed_data['title']);
		if(isset($feed_data['dc:creator']) && $feed_data['dc:creator'] != '')
		$newItem->addElement('dc:creator', $feed_data['dc:creator']);
		if(isset($feed_data['dc:date']) && $feed_data['dc:date'] != '')
			$newItem->addElement('dc:date', $feed_data['dc:date']);
		if(isset($feed_data['comments']) && $feed_data['comments'] != '')
			$newItem->addElement('comments', $feed_data['comments']);
		if(isset($feed_data['media']) && $feed_data['media'] != '')
			$newItem->addElement('media', $feed_data['media']);
		if(isset($feed_data['category']) && !empty($feed_data['category']))
			foreach($feed_data['category'] as $category)
				$newItem->addElement('category', $category);
		
		if($mode == 'rss2' && isset($feed_data['noPermaLink']) && $feed_data['noPermaLink'] != '')
			$newItem->setId(LinkToGT($feed_data['noPermaLink']));
		//Now add the feed item
		$TestFeed->addItem($newItem);
	}
	
	//Adding multiple elements from array
	//Elements which have an attribute cannot be added by this way
	//$newItem = $TestFeed->createNewItem();
	//$newItem->addElementArray(array('title'=>'The 2nd feed', 'link'=>'http://www.google.com', 'description'=>'This is a test of the FeedWriter class'));
	//$TestFeed->addItem($newItem);

	//OK. Everything is done. Now generate the feed.
	$TestFeed->printFeed();
	die();
}

function sitemap()
{
	global $db, $nuke_configs, $mode, $is_index, $module, $year, $month;

	$is_index				= (isset($is_index)) ? intval($is_index):0;
	$year					= (isset($year)) ? $year:0;
	$month					= (isset($month)) ? $month:0;
	$module					= (isset($module)) ? filter($module, "nohtml"):"";
	$mode					= (isset($mode)) ? filter($mode, "nohtml"):"";
	
	if($year != 0 && $month != 0)
	{
		$jnmonth = $month +1;
		$jnyear = $year;
		if ($jnmonth == 13){$jnyear++ ; $jnmonth = 1;};
		
		$currenttime = mktime(0,0,0,$month,1,$year);
		$nexttime = mktime(0,0,0,$jnmonth,1,$jnyear);
	}
	
	header("Content-Type: text/xml");
	if((isset($is_index) && $is_index != "") && (!isset($module) OR (!preg_match("#tags#isU", $module))))
	{
		$maintag = "sitemapindex";
		$xsi_link = "siteindex.xsd";
	}
	else
	{
		$maintag = "urlset";
		$xsi_link = "sitemap.xsd";
	}
	
	
	$contents ='<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="'.$nuke_configs['nukeurl'].'modules/Feed/includes/sitemap.xsl"?>
<'.$maintag.' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/'.$xsi_link.'" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	
	$result = $db->table(POSTS_TABLE)
				->order_by(['time', 'DESC'])
				->limit(0,1)
				->select(['time']);
				
	$site_last_modified = _NOWTIME;
	if(intval($db->count()) > 0)
	{
		$rows = $result->results();
		if(!empty($rows) && isset($rows[0]))
			$site_last_modified = intval($row[0]['time']);
	}

	if(isset($module) && $module == 'tags')
		$mode = 'modules';

	if($mode == "misc")
	{
		$contents .='<url>
			<loc>'.$nuke_configs['nukeurl'].'</loc>
			<lastmod>'.date('Y-m-d\TH:i:s+00:00',$site_last_modified).'</lastmod>
			<changefreq>daily</changefreq>
			<priority>1.0</priority>
		</url>
		<url>
			<loc>'.$nuke_configs['nukeurl'].'sitemap.xml</loc>
			<lastmod>'.date('Y-m-d\TH:i:s+00:00',$site_last_modified).'</lastmod>
			<changefreq>monthly</changefreq>
			<priority>0.5</priority>
		</url>';
		$result = $db->table(MODULES_TABLE)
					->where('active', 1)
					->where('in_menu', 1)
					->order_by(['mid' => 'ASC'])
					->select(['title','lang_titles']);
					
		if(intval($db->count()) > 0)
		{
			$rows = $result->results();
			foreach($rows as $row)
			{
				$title = filter($row['title'], "nohtml");
				$lang_titles = ($row['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($row['lang_titles'])):array();
				if($title == 'Articles')
					continue;
				$contents .='<url>
								<loc>'.LinkToGT("index.php?modname=$title").'</loc>
								<lastmod>'.date('Y-m-d\TH:i:s+00:00',$site_last_modified).'</lastmod>
								<changefreq>never</changefreq>
								<priority>0.1</priority>
							</url>';
			}
		}
		
		if($site_last_modified > 0)
		{
			$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
			$array_contents = array();
			
			foreach($nuke_categories_cacheData as $module_name => $cat_data)
			{
				foreach($cat_data as $key => $val)
				{
					$catid = get_category_id($module, $val['catname_url'], $cat_data);	
					$cat_link = sanitize(filter(implode("/", array_reverse(get_parent_names($key, $cat_data, "parent_id", "catname_url"))), "nohtml"), array("/"))."";
					$cat_link = LinkToGT("index.php?modname=$module_name&category=$cat_link");
					$contents .="<url>
						<loc>".$cat_link."</loc>
							<lastmod>" . date('Y-m-d\TH:i:s+00:00',$site_last_modified) . "</lastmod>
							<changefreq>weekly</changefreq>
						<priority>0.5</priority>
					</url>";
				}
			}
		}
	}
	elseif($mode == "modules")
	{
		if(preg_match("#tags#isU", $module))
		{
			$result = $db->table(TAGS_TABLE)
							->order_by(['tag_id' => 'ASC'])
							->select(['tag']);
			if(intval($db->count()) > 0)
			{
				$rows = $result->results();
				foreach($rows as $row)
				{
					$tag = $row['tag'];
					if($tag == '')
						continue;
					$contents .='<url>
						<loc>'.LinkToGT("index.php?modname=Articles&tags=$tag").'</loc>
						<lastmod>'.date('Y-m-d\TH:i:s+00:00',$site_last_modified).'</lastmod>
						<changefreq>daily</changefreq>
						<priority>0.7</priority>
					</url>';
				}
			}		
		}
		else
		{
			$result = $db->query("SELECT sid, title, time, post_url, cat_link from ".POSTS_TABLE." WHERE post_type = '$module' AND time >= '$currenttime' AND time < '$nexttime' order by time DESC");
			if(intval($db->count()) > 0)
			{
				$rows = $result->results();
				foreach($rows as $row)
				{
					$sid = $row['sid'];
					$title = $row['title'];
					$post_url = $row['post_url'];
					$time = $row['time'];
					$cat_link = $row['cat_link'];
					$link = LinkToGT(articleslink($sid, $title, $post_url, $time, $cat_link, $module));
					$contents .='<url>
						<loc>'.$link.'</loc>
						<lastmod>'.date('Y-m-d\TH:i:s+00:00',$time).'</lastmod>
						<changefreq>daily</changefreq>
						<priority>0.7</priority>
					</url>';
				}
			}
		}
	}
	else
	{
		$contents .='<sitemap>
			<loc>'.LinkToGT("sitemap-misc.xml").'</loc>
			<lastmod>'.date('Y-m-d\TH:i:s+00:00',$site_last_modified).'</lastmod>
		</sitemap>';
		if($site_last_modified > 0)
		{
			$result = $db->table(POSTS_TABLE)
						->where('status', 'publish')
						->order_by(['time' => 'DESC'])
						->select(['post_type','time']);
			if(intval($db->count()) > 0)
			{
				$thismonth = "";
				$rows = $result->results();
				$all_modules_sitemap = array();
				foreach($rows as $row)
				{
					$time = $row['time'];
					$post_type = $row['post_type'];
					$year_month = date("Y-m",$time);
					if(!isset($all_modules_sitemap[$post_type][$year_month]))
					{
						$all_modules_sitemap[$post_type][$year_month] ='<sitemap>
							<loc>'.LinkToGT('sitemap-'.$post_type.'-'.$year_month.'.xml').'</loc>
							<lastmod>'.date('Y-m-d\TH:i:s+00:00',$time).'</lastmod>
						</sitemap>';
					}
				}
				unset($rows);
				
				if(!empty($all_modules_sitemap))
				{
					ksort($all_modules_sitemap);
					foreach($all_modules_sitemap as $post_type => $sitemap_dates)
					{
						foreach($sitemap_dates as $sitemap_data)
							$contents .= $sitemap_data;
					}
				}
				unset($all_modules_sitemap);
			}
		
			$result = $db->table(TAGS_TABLE)
						->first(['tag_id']);
			if(intval($db->count()) > 0)
			{
				$contents .='<sitemap>
					<loc>'.LinkToGT("sitemap-tags.xml").'</loc>
					<lastmod>'.date('Y-m-d\TH:i:s+00:00',$site_last_modified).'</lastmod>
				</sitemap>';
			}
		}
	}
	$contents .='</'.$maintag.'>';
	
	die($contents);
}

$module_link			= (isset($module_link)) ? filter($module_link, "nohtml"):"";
$mode					= (isset($mode) && $mode != '') ? filter($mode, "nohtml"):"atom";
$op						= (isset($op)) ? filter($op, "nohtml"):"feed";

switch ($op)
{
	default:
		feed($module_link, $mode);
	break;
	
	case"sitemap":
		sitemap();
	break;
}

?>