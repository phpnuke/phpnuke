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

$articles_votetype = false;
$this_module_name = basename(dirname(__FILE__));

function articleslink_all($all_vars = array())
{
	return articleslink($all_vars['sid'], $all_vars['title'], $all_vars['post_url'], $all_vars['time'], $all_vars['cat_link']);
}

function articleslink($sid, $title='', $post_url='', $time='', $cat_link='')
{
	global $nuke_configs, $db, $nuke_articles_categories_cacheData, $HijriCalendar;
	$sid = intval($sid);
	
	if($title == '' OR $post_url == '' OR $time == '' OR $cat_link == '' && empty($all_vars))
	{
		$row = $db->table(POSTS_TABLE)
						->where('sid', $sid)
						->first(['title', 'post_url', 'time', 'cat_link']);
		if(intval($db->count()) > 0)
		{
			$title = filter($row['title'], "nohtml");
			$post_url = filter($row['post_url'], "nohtml");
			$time = $row['time'];
			$cat_link = intval($row['cat_link']);
		}
		else
			return '';
	}
	
	$post_url = sanitize(str2url((($post_url != "") ? $post_url:$title)));
	$title = sanitize(str2url($title));

	$cat_link = intval($cat_link);
	$time = $time;
	$nutime = $time;
	$nudate = date("Y-m-d H:i:s", $time);
	
	$nudate1  = explode(" ", $nudate);
	$nudate1  = explode("-", $nudate1[0]);
	
	if($nuke_configs['datetype'] == 1)
	{
		$timelink = FormalDate2Hejri($nudate);
	}
	elseif($nuke_configs['datetype'] == 2)
	{
		$adateTimes = $HijriCalendar->GregorianToHijri($nutime);
		$timelink = $adateTimes[1].'-'.$adateTimes[0].'-'.$adateTimes[2];
	}
	else
	{
		$timelink = $nudate1[2]."-".$nudate1[1]."-".$nudate1[0];
	}
	
	$timelink = explode("-",$timelink);

	$post_url = str_replace(" ", "-", $post_url);

	$catname_link = sanitize(filter(implode("/", array_reverse(get_parent_names($cat_link, $nuke_articles_categories_cacheData, "parent_id", "catname_url"))), "nohtml"), array("/"));
	
	if($nuke_configs['gtset'] == "1")
	{
		$nuke_configs['pages_links'][$nuke_configs['userurl']] = (isset($nuke_configs['pages_links'][$nuke_configs['userurl']])) ? $nuke_configs['pages_links'][$nuke_configs['userurl']]:1;
		
		$article_link = "".str_replace(array('{ID}','{YEAR}','{MONTH}','{DAY}','{CATEGORY}','{PAGEURL}'), array($sid, $timelink[2], $timelink[1], $timelink[0], $catname_link, $post_url),$nuke_configs['pages_links'][$nuke_configs['userurl']]);
	}
	else
	{
		$article_link = "index.php?modname=Articles&op=article_show&sid=$sid-$title";
	}
	return $article_link;
}

function is_valid_Articles_link($parsed_vars)
{
	global $db, $nuke_configs, $nuke_categories_cacheData;
	
	extract($parsed_vars);
	
	if(isset($op))
	{
		switch($op)
		{
			case"article_show":
				$query_where = array();

				if($nuke_configs['gtset'] == 1)
				{
					if($post_url == '')
						return false;
					$query_params[':post_url'] = $post_url;
					$query_where[] = "post_url=:post_url";
					
				}
				else
				{
					$query_params[':sid'] = intval($sid);
					$query_where[] = "sid=:sid";
				}
					
				if(!is_admin())
					$query_where[] = "status = 'publish'";
					
				$query_where = implode(" AND ", $query_where);

				$result = $db->query("SELECT sid FROM ".POSTS_TABLE." where $query_where", $query_params);

				if(intval($result->count()) > 0)
				{
					$row = $result->results()[0];
					if(intval($row['sid'] > 0))
					{
						if(trim(articleslink(intval($row['sid']), filter($row['title'], "nohtml"), filter($row['post_url'], "nohtml"), filter($row['time'], "nohtml"), intval($row['cat_link'])).$urlop, "/")."/" != trim(rawurldecode($nuke_configs['REQUESTURL']), "/")."/")
						{
							return false;
						}
					}
					return true;
				}
				return false;
			break;
			
			case"article_archive":
				$page = isset($page) ? intval($page):0;
				$year = (isset($year) && strlen($year) == 4) ? intval($year):0;
				$month = (isset($month) && strlen($month) > 0 && strlen($month) < 3) ? intval($month):0;
				$month_l = isset($month_l) ? filter($month_l, "nohtml"):'';
				
				$month_names = ($nuke_configs['datetype'] == 1) ? "j_month_name":(($nuke_configs['datetype'] == 2) ? "h_month_name":"g_month_name");
				
				$month_l = str_replace(" ","-", $nuke_configs[$month_names][$month]);
				
				$link = "index.php?modname=Articles&op=article_archive".
				(($year != 0) ? "&year=$year":"").
				(($month != 0) ? "&month=$month":"").
				(($month_l != '') ? "&month_l=$month_l":"").
				(($page != 0) ? "&page=$page":"");
				
				if(trim(LinkToGT($link), "/")."/" == trim(rawurldecode(LinkToGT($nuke_configs['REQUESTURL'])), "/")."/")
					return true;
				return false;
			break;
		}	
	}
	elseif(isset($category))
	{
		if(!isset($nuke_categories_cacheData))
			$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
			
		$catid = get_category_id('Articles', $category, $nuke_categories_cacheData);	
		$catid = intval($catid);
		if($catid > 0)
			return true;
		return false;
	}
	
	return true;
}

function articles_feed($module_args=array())
{
	global $db, $nuke_configs, $nuke_articles_categories_cacheData, $noPermaLink;
	
	$feed_data = array();
	
	$query_set = array();
		
	$query_set['status'] = "status = 'publish'";
	$query_set['post_type'] = "post_type = 'article'";
	
	if ($nuke_configs['multilingual'] == 1)
		$query_set['alanguage']	= "(alanguage='".$nuke_configs['currentlang']."' OR alanguage='')";

	if(isset($module_args['file']))
	{
		$module_file = filter($module_args['file'], "nohtml");
		
		switch($module_file)
		{
			case"archive":
				$year = isset($module_args['year']) ? intval($module_args['year']):0;
				$month = isset($module_args['month']) ? intval($module_args['month']):0;
				
				if($year != 0 && $month != 0)
				{
					$jnmonth				= $month +1;
					$jnyear					= $year;
					if ($jnmonth == 13)
					{
						$jnyear++;
						$jnmonth			= "01";
					}
					
					$month					= correct_date_number($month);
					$jnmonth				= correct_date_number($jnmonth);
					
					$currenttime			= to_mktime("$year/$month/1");
					$nexttime				= to_mktime("$jnyear/$jnmonth/1");
					
					$query_set['time']		= 'time BETWEEN '.$currenttime.' AND '.$nexttime.'';
				}
			break;
		}
	}
	
	$all_sub_cats = array();
	
	if(isset($module_args['category']))
	{
		$category = filter($module_args['category'], "nohtml");
		
		$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
		$catid = get_category_id('Articles', $category, $nuke_categories_cacheData);	
		$catid = intval($catid);

		$all_sub_cats = array_unique(get_sub_categories_id('Articles', $catid, $nuke_categories_cacheData, array($catid)));
		
		foreach($all_sub_cats as $sub_cat)
			$query_set['cat'][] = "FIND_IN_SET(?, cat)";
			
		$query_set['cat']= "(".implode(" OR ", $query_set['cat']).")";
	}
		
	$query_set					= implode(" AND ", array_filter($query_set));
	$query_set					= ($query_set != "") ? "WHERE $query_set":"";
	
	$result = $db->query("SELECT aid, sid, informant, title, alanguage, post_url, time, hometext, cat, cat_link, post_image FROM ".POSTS_TABLE." $query_set ORDER BY time DESC LIMIT 0,50", $all_sub_cats);

	if($result->count() > 0)
	{
		$rows = $result->results();
		$row_count = 0;
		foreach ($rows as $row)
		{
			$sid = intval($row['sid']);
			$aid = filter($row['aid'], "nohtml");
			$cat_link = intval($row['cat_link']);
			$cats = ($row['cat'] != '') ? explode(",",$row['cat']):"";
			$informant = ($row['informant'] != '') ? correct_text(filter($row['informant'], "nohtml")):$row['aid'];
			$title = correct_text(filter($row['title'], "nohtml"));
			$alanguage = correct_text(filter($row['alanguage'], "nohtml"));
			$time = $row['time'];
			$post_url = correct_text(filter($row['post_url']));
			$hometext = correct_text(stripslashes($row['hometext']));
			$post_image = get_article_image($sid, $row['post_image'], $hometext);
			$date = date('Y-m-d\TH:i:s+00:00',$time);			

			$link = articleslink($sid, $row['title'], $row['post_url'], $time, $cat_link);
			
			$feed_data[$row_count]['aid'] = $aid;
			$feed_data[$row_count]['sid'] = $sid;
			$feed_data[$row_count]['title'] = $title;
			$feed_data[$row_count]['link'] = LinkToGT($link);
			$feed_data[$row_count]['comments'] = LinkToGT($link."#comments");
			$feed_data[$row_count]['pubDate'] = $date;
			$feed_data[$row_count]['dc:creator'] = _LASTPOSTBY." ".$informant;
			$feed_data[$row_count]['dc:date'] = $date;
			$feed_data[$row_count]['noPermaLink'] = parse_GT_link($link)[3];
			if($post_image)
				$feed_data[$row_count]['media'] = LinkToGT($post_image);
			$feed_data[$row_count]['description'] = strip_tags($hometext);
			$feed_data[$row_count]['content'] = $hometext;
			$feed_data[$row_count]['language'] = $alanguage;
			
			if(!empty($cats))
				foreach($cats as $cat)
				{
					if(isset($nuke_articles_categories_cacheData[$cat]))
						$feed_data[$row_count]['category'][] = filter(category_lang_text($nuke_articles_categories_cacheData[$cat]['cattext']), "nohtml");
				}
			
			$row_count++;
		}
	}
	
	return $feed_data;
}

function get_article_image($sid = 0, $article_image = '', $hometext = '')
{
	preg_match_all('#<img(.*)src=["|\'](.*)["|\']#isU', stripslashes($hometext), $images_match);
	if($article_image == '')
		if(file_exists("files/Articles/".$sid.".jpg"))
			$article_image = "files/Articles/".$sid.".jpg";
		else
			if(isset($images_match[2][0]) && $images_match[2][0] != '')
				$article_image = $images_match[2][0];
			else
				if(file_exists("images/no_image.jpg"))
					$article_image = "images/no_image.jpg";

	return $article_image;	
}

if(!function_exists("articles_search"))
{
	function articles_search($row)
	{
		$contents = "";
		$contents .= "
		<div class=\"panel panel-info\">
			<div class=\"panel-heading\"> <span class=\"glyphicon glyphicon-list-alt\"></span><b> <a href=\"".$row['link']."\">".$row['title']."</a></b></div>
			<div class=\"panel-body\">
				".stripslashes($row['hometext'])."
			</div>
		</div>";
		return $contents;	
	}
}

function _article_select_month($in = 0)
{
	global $db, $nuke_configs, $module_name, $HijriCalendar;
	
	$contents = "";
	$contents .= "<div class=\"text-center\"><font class=\"content\">"._SELECTMONTH2VIEW."</font><br><br></div>";
	
	$result = $db->table(POSTS_TABLE)
					->where('post_type', 'article')
					->order_by(['time' => 'DESC'])
					->select(['time']);
						
	$contents .= "<ul>";
	$thismonth = "";//gregorian date
	$thisjmonth = "";//jalali date
	$thishmonth = "";//hijri date

	if(!empty($result))
	{
		foreach($result as $row)
		{
			$time = $row['time'];
			if($nuke_configs['datetype'] == 1)
			{
				$j_datetime = array(date("Y", $time), date("m", $time), date("d", $time));
				$jalalidate= gregorian_to_jalali($j_datetime[0],$j_datetime[1],$j_datetime[2]);
				if ($jalalidate[1] != $thisjmonth)
				{
					$month = $nuke_configs['j_month_name'][$jalalidate[1]];
					$month2 = str_replace(" ","-",$month);
					$contents .= "<li><a href=\"".LinkToGT("index.php?modname=Articles&op=article_archive&year=$jalalidate[0]&month=$jalalidate[1]&month_l=$month2")."\">$month, $jalalidate[0]</a>";
					$thisjmonth = $jalalidate[1];
				}

			}
			elseif($nuke_configs['datetype'] == 2)
			{
				$dateTimes = $HijriCalendar->GregorianToHijri($time);
				$hgetdate = $dateTimes[0]-1;
				if ($dateTimes[0] != $thishmonth)
				{
					$month = $nuke_configs['A_month_name'][$hgetdate];
					$month2 = str_replace(" ","-",$month);
					$contents .= "<li><a href=\"".LinkToGT("index.php?modname=Articles&op=article_archive&year=$dateTimes[2]&month=$dateTimes[0]&month_l=$month2")."\">$month, $dateTimes[2]</a>";
					$thishmonth = $dateTimes[0];
				}	
			}
			else
			{
				$dateTimes_year = date("Y",$time);
				$dateTimes_month = date("m",$time);
				$dateTimes_month = intval($dateTimes_month);
				if ($dateTimes_month != $thismonth)
				{
					$month = $nuke_configs['g_month_name'][$dateTimes_month];
					$month2 = str_replace(" ","-",$month);
					$contents .= "<li><a href=\"".LinkToGT("index.php?modname=Articles&op=article_archive&&year=$dateTimes_year&month=$dateTimes_month&month_l=$month2")."\">$month, $dateTimes_year</a>";
					$thismonth = $dateTimes_month;
				}	
			}	
		}
	}
	$contents .= "</ul>";

	if(intval($in) == 0)
	{
		$meta_tags = array(
			"url" => LinkToGT("index.php?modname=$module_name&op=article_select_month"),
			"title" => _STORIESARCHIVE,
			"description" => '',
			"extra_meta_tags" => array()
		);
		
		include("header.php");
		$output = '';
		$output .= title(_STORIESARCHIVE);
		$output .= OpenTable();
		$contents .= "<br><br><div class=\"text-center\">[ <a href=\"".LinkToGT("index.php?modname=$module_name&op=article_archive")."\">"._SHOWALLSTORIES."</a> ]</div>";
		$output .= $contents;
		$output .= CloseTable();
		$html_output .= show_modules_boxes($module_name, "archive", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $output);
		include("footer.php");
		
	}
	return $contents;
}

function article_result_parse(&$article_info = array(), $query_set = array(), $query_params = array(), $orderby = 'time', $mode = 'index')
{
	global $db, $nuke_articles_categories_cacheData, $nuke_authors_cacheData, $pn_Cookies, $votetype, $nuke_configs, $articles_votetype, $nuke_meta_keys_parts, $this_module_name, $visitor_ip;
		
	// AND (sc.rating_ip = '$ip' OR sc.username = '".$userinfo['username']."') was removed for all
	$user_id = (isset($userinfo['user_id']) && isset($userinfo['is_registered']) && $userinfo['is_registered'] == 1) ? intval($userinfo['user_id']):0;
	
	$query_params[':user_id'] = $user_id;
	$query_params[':visitor_ip'] = $visitor_ip;
	
	$votetype = ($articles_votetype) ? $articles_votetype:$nuke_configs['votetype'];
	$vote_where = ($user_id != 0) ? "sc.user_id = :user_id":"sc.rating_ip = :visitor_ip";

	if($votetype == 1)
	{
		$votes_query2	= "SUM(IF(score > 0, score, -score)) AS score, COUNT(id) AS ratings, 0 as likes, 0 as dislikes";
		$newvotetype = 1;
	}
	else
	{
		$votes_query2	= "SUM(IF(score >= 1, 1, 0)) AS likes, SUM(IF(score < 0, 1, 0)) AS dislikes, COUNT(id) AS ratings, 0 as score";
			$newvotetype = "2,3";
	}
		
	if(!empty($query_set))
	{
		$query_set								= implode(" AND ", array_filter($query_set));
		$query_set								= ($query_set != "") ? "WHERE $query_set":"";
		
		$total_rows_sql = '';
		$prev_next_sql = '';
		$prev_next_left = '';
		$oreder_group_by_sql = '';
		$post_ids = array();
		$meta_datas = array();
		$scores_data = array();
		
		if($mode == 'index')
		{
			$total_rows_sql = ", (SELECT COUNT(sid) FROM ".POSTS_TABLE." ".str_replace("s.","", $query_set).") as total_rows";
			$oreder_group_by_sql = "GROUP BY s.sid 
		ORDER BY s.$orderby DESC, s.sid DESC LIMIT :start_at, :entries_per_page";
		}
		
		if($mode == 'more')
		{
			$prev_next_sql = ", s1.sid as psid, s1.title as ptitle, s1.post_url as ppost_url, s1.time as ptime, s1.cat_link as pcat_link,
		s2.sid as nsid, s2.title as ntitle, s2.post_url as npost_url, s2.time as ntime, s2.cat_link as ncat_link";
			$prev_next_left = "LEFT JOIN ".POSTS_TABLE." AS s1 ON s1.time = (
			SELECT time
			FROM ".POSTS_TABLE." 
			WHERE time < s.time AND post_type = s.post_type ".((!is_admin()) ? "AND status = 'publish'":"")."
			ORDER BY time DESC LIMIT 1
		) 
		LEFT JOIN ".POSTS_TABLE." AS s2 ON s2.time = (
			SELECT time
			FROM ".POSTS_TABLE." 
			WHERE time > s.time AND post_type = s.post_type ".((!is_admin()) ? "AND status = 'publish'":"")."
			ORDER BY time ASC LIMIT 1
		) ";
		}
		
		$result					= $db->query("
		SELECT s.*
		$total_rows_sql
		$prev_next_sql
		FROM ".POSTS_TABLE." AS s 
		$prev_next_left
		$query_set
		$oreder_group_by_sql", $query_params);
		
		if(!empty($result))
		{
			$rows = $result->results();
		}
	}
	else
		$rows = $article_info;

	foreach($rows as $row)
		$post_ids[] = intval($row['sid']);
	
	if(!empty($post_ids))
	{
		// get posts meta
		$result = $db->query("SELECT post_id, meta_key, meta_value FROM ".POSTS_META_TABLE." WHERE post_id IN (".implode(",", $post_ids).") AND meta_part = 'articles'");
		if(!empty($result))
		{
			$meta_data = $result->results();
			foreach($meta_data as $meta_data_val)
				$meta_datas[$meta_data_val['post_id']][$meta_data_val['meta_key']] = $meta_data_val['meta_value'];
		}
		// get posts meta
		
		// get posts scores
		$result = $db->query("SELECT s.post_id, $votes_query2, (SELECT sc.id FROM ".SCORES_TABLE." as sc WHERE ($vote_where) AND s.post_id = sc.post_id AND sc.db_table = 'articles' ORDER BY sc.id ASC LIMIT 1) AS you_rated FROM ".SCORES_TABLE." as s WHERE s.post_id IN (".implode(",", $post_ids).") AND s.votetype IN ($newvotetype) AND s.db_table = 'articles' GROUP BY s.post_id", $query_params);
		
		if(!empty($result))
		{
			$scores = $result->results();
			foreach($scores as $scores_val)
			{
				$scores_data[$scores_val['post_id']] = array(
					'you_rated' => intval($scores_val['you_rated']),
					'likes' => $scores_val['likes'],
					'dislikes' => $scores_val['dislikes'],
					'ratings' => $scores_val['ratings'],
					'score' => $scores_val['score']
				);
			}
		}
		// get posts scores
	}
	unset($result);
	
	$article_info['total_rows'] = 0;
	foreach ($rows as $key => $row)
	{
		$article_info[$key] = $row;
		$article_info['total_rows']			= (!isset($article_info['total_rows'])) ? ((isset($row['total_rows'])) ? intval($row['total_rows']):1):$article_info['total_rows'];
		$article_info[$key]['sid']			= intval($row['sid']);
		$article_info[$key]['aid']			= filter($row['aid'], "nohtml");
		$article_info[$key]['post_type']		= filter($row['post_type'], "nohtml");
		$article_info[$key]['aid_url']		= (isset($nuke_authors_cacheData[$row['aid']]['url'])) ? filter($nuke_authors_cacheData[$row['aid']]['url'], "nohtml"):$nuke_configs['nukeurl'];
		$article_info[$key]['time']			= filter($row['time']);
		$article_info[$key]['title']			= filter($row['title'], "nohtml");
		$article_info[$key]['title_lead']		= filter($row['title_lead'], "nohtml");
		$article_info[$key]['title_color']	= filter($row['title_color'], "nohtml");
		$hometext						= text_rel2abs(stripslashes($row['hometext']));
		$article_info[$key]['hometext']		= codereplace($hometext,$article_info[$key]['sid']);
		$bodytext						= text_rel2abs(stripslashes($row['bodytext']));
		$article_info[$key]['bodytext']		= codereplace($bodytext,$article_info[$key]['sid']);
		$article_info[$key]['comments']		= intval($row['comments']);
		$article_info[$key]['counter']		= intval($row['counter']);
		$article_info[$key]['micro_meta']		= (isset($row['micro_meta']) && $row['micro_meta'] != "") ? unserialize(stripslashes($row['micro_meta'])):array();
		$article_info[$key]['download']		= (isset($row['download']) && $row['download'] != "") ? unserialize(stripslashes($row['download'])):array();
		$article_info[$key]['permissions']	= ($row['permissions'] != "") ? explode(",",$row['permissions']):array(0);
		$article_info[$key]['cats']			= ($row['cat'] != "") ? explode(",",$row['cat']):array();
		$article_info[$key]['cat_link']		= $row['cat_link'];
		$article_info[$key]['informant']		= filter($row['informant'], "nohtml");
		$article_info[$key]['post_url']		= filter($row['post_url']);
		$article_info[$key]['tags']			= filter($row['tags']);
		$tags2							= filter($row['tags']);
		$article_info[$key]['allow_comment']	= intval($row['allow_comment']);
		$article_info[$key]['score']			= intval($row['score']);
		$article_info[$key]['ratings']		= intval($row['ratings']);
		$article_info[$key]['post_pass']		= ($row['post_pass'] != '') ? md5($row['post_pass']):"";
		$article_info[$key]['likes']			= (isset($row['likes'])) ? intval($row['likes']):0;
		$article_info[$key]['dislikes']		= (isset($row['dislikes'])) ? intval($row['dislikes']):0;
		$article_info[$key]['datetime']		= nuketimes($article_info[$key]['time']);
		$article_info[$key]['post_image']	= get_article_image($article_info[$key]['sid'], $row['post_image'], $row['hometext']);
		
		if(isset($meta_datas[$article_info[$key]['sid']]) && !empty($meta_datas[$article_info[$key]['sid']]))
			$article_info[$key] = array_merge($article_info[$key], $meta_datas[$article_info[$key]['sid']]);
			
		if(isset($nuke_meta_keys_parts['Articles']) && !empty($nuke_meta_keys_parts['Articles']))
		{
			foreach($nuke_meta_keys_parts['Articles'] as $meta_key => $article_fields_meta_field)
			{
				if(isset($article_info[$key][$meta_key]) && isset($article_fields_meta_field['php_load']) && $article_fields_meta_field['php_load'] != '')
				{
					$continue2 = false;
					eval($article_fields_meta_field['php_load']);
					if($continue2)
						continue 2;
				}
			}
		}
		
		$article_info[$key]['likes'] = $article_info[$key]['dislikes'] = $article_info[$key]['score'] = $article_info[$key]['ratings'] = 0;
		if(isset($scores_data[$article_info[$key]['sid']]) && !empty($scores_data[$article_info[$key]['sid']]))
			$article_info[$key] = array_merge($article_info[$key], $scores_data[$article_info[$key]['sid']]);
			
		if(!empty($article_info[$key]['cats']))
		{
			foreach($article_info[$key]['cats'] as $cat)
			{
				if(!isset($nuke_articles_categories_cacheData[$cat])) continue;
				$article_info[$key]['cats_data'][$cat]		= array(
					"cattext"			=> filter(category_lang_text($nuke_articles_categories_cacheData[$cat]['cattext']), "nohtml"),
					"catname"			=> filter($nuke_articles_categories_cacheData[$cat]['catname'], "nohtml"),
					"catimage"			=> filter($nuke_articles_categories_cacheData[$cat]['catimage'], "nohtml"),
					"catlink"			=> LinkToGT("index.php?modname=Articles&category=".filter($nuke_articles_categories_cacheData[$cat]['catname_url'], "nohtml")),
				);
			}			
		}

		$article_info[$key]['article_image_width'] = $article_info[$key]['article_image_height'] = 0;
		if($article_info[$key]['post_image'] != '' && file_exists($article_info[$key]['post_image']))
			list($article_info[$key]['article_image_width'], $article_info[$key]['article_image_height']) = getimagesize($article_info[$key]['post_image']);
				
		$disabled_rating = false;

		if ($pn_Cookies->exists('Articles_ratecookie'))
		{
			$rcookie				= base64_decode($pn_Cookies->get('Articles_ratecookie'));
			$rcookie				= addslashes($rcookie);
			$r_cookie				= explode(",", $rcookie);
			if(in_array($article_info[$key]['sid'], $r_cookie))
			{
				$disabled_rating	= true;
			}
		}

		if(isset($article_info[$key]['you_rated']) && $article_info[$key]['you_rated'] > 0)
			$disabled_rating	= true;
		
		$article_info[$key]['disabled_rating']	= $disabled_rating;
		$article_info[$key]['rating_box']		= rating_load($article_info[$key]['score'], $article_info[$key]['ratings'], $article_info[$key]['likes'], $article_info[$key]['dislikes'], 'Articles', "sid", $article_info[$key]['sid'], $disabled_rating, $votetype);
					
		if(isset($nuke_articles_categories_cacheData[$article_info[$key]['cat_link']]))
		{
			$article_info[$key]['cattext_link']		= filter(category_lang_text($nuke_articles_categories_cacheData[$article_info[$key]['cat_link']]['cattext']), "nohtml");
			$article_info[$key]['catname_link']		= filter($nuke_articles_categories_cacheData[$article_info[$key]['cat_link']]['catname'], "nohtml");
			$article_info[$key]['catimage_link']		= filter($nuke_articles_categories_cacheData[$article_info[$key]['cat_link']]['catimage'], "nohtml");
		}
		else
		{
			$article_info[$key]['cattext_link'] = "";
			$article_info[$key]['catname_link'] = "";
			$article_info[$key]['catimage_link'] = "";
		}
		
		$article_info[$key]['article_link']		= LinkToGT(articleslink($article_info[$key]['sid'], $article_info[$key]['title'], $article_info[$key]['post_url'], $article_info[$key]['time'], $article_info[$key]['cat_link']));
		
		if(!empty($article_info[$key]['download']))
		{
			foreach($article_info[$key]['download'] as $key2 => $download_data)
			{
				$article_info[$key]['download'][$key2] = (!empty($download_data)) ? $download_data:array();
			}

		}
		if(isset($article_info[$key]['meta_keys']))
		{
			unset($article_info[$key]['meta_keys']);
			unset($article_info[$key]['meta_values']);
		}
	}
}

function get_post_download_files($files, $title=_ARTICLE_FILES, $form_field_name)
{
	global $nuke_configs;
	
	$contents = "
	<a href=\"#\" class=\"table-icon icon-6 post-files-btn\"></a>
	<div id=\"post-files-dialog\" style=\"display:none;\">
		"._ADD_NEW_FIELD." <span class=\"add_field_icon add_post_field_button\" title=\""._ADD_NEW_FIELD."\"></span>
		<div class=\"input_post_fields_wrap\">";
		$i = 0;
		
		if(isset($files) && !empty($files))
		{
			foreach($files as $type => $files_data)
			{
				if(is_array($files_data) && !empty($files_data))
				{
					foreach($files_data as $file_data)
					{
						if(empty($file_data))
							continue;
							
						$filename = $file_data[0];
						$filelink = $file_data[1];
						$filesize = $file_data[2];
						$filedesc = $file_data[3];
						$filetype = (isset($file_data[3]) && $file_data[3] != '') ? $file_data[3]:$type;
						$sel1 = ($filetype == "files") ? "selected":"";
						$sel2 = ($filetype == "images") ? "selected":"";
						$sel3 = ($filetype == "audios") ? "selected":"";
						$sel4 = ($filetype == "videos") ? "selected":"";
						$contents .= "
						<div style=\"margin-bottom:3px;\">
							<input placeholder=\""._FILENAME."\" type=\"text\" class=\"inp-form\" value=\"$filename\" name=\"".$form_field_name."[$type][$i][]\" />&nbsp;<input placeholder=\""._FILELINK."\" type=\"text\" class=\"inp-form-ltr\" value=\"$filelink\" name=\"".$form_field_name."[$type][$i][]\" size=\"40\" />&nbsp;&nbsp;<input placeholder=\""._FILESIZE."\" type=\"text\" class=\"inp-form\" value=\"$filesize\" name=\"".$form_field_name."[$type][$i][]\" size=\"8\" />&nbsp;&nbsp;<input placeholder=\""._FILEDESC."\" type=\"text\" class=\"inp-form\" value=\"$filedesc\" name=\"".$form_field_name."[$type][$i][]\" />&nbsp;&nbsp;"._FILETYPE." <select class=\"styledselect-select field_type_select\" name=\"".$form_field_name."[$type][$i][]\" style=\"width:120px;\"><option value=\"files\" $sel1>"._FILES."</option><option value=\"images\" $sel2>"._IMAGES."</option><option value=\"audios\" $sel3>"._AUDIOS."</option><option value=\"videos\" $sel4>"._VIDEOS."</option></select>&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a>
						</div>";
						$i++;
					}	
				}
			}
		}
		$contents .= "
		</div>
	</div>
	<script>
		$(document).ready(function(){
		
			$(\".post-files-btn\").click(function(e)
			{
				e.preventDefault();
				$(\"#post-files-dialog\").dialog({
					title: '$title',
					resizable: false,
					height: 500,
					width: 900,
					modal: true,
					closeOnEscape: true,
					close: function(event, ui)
					{
						$(this).dialog('destroy');
					}
				});
			});
			
			var fields_name = '".$form_field_name."[{FIELD_TYPE}][{X}][]';
			
			$(document).on('change','.field_type_select' ,function(e)
			{
				var new_type = $(this).val();
				var all_inputs = $(this).parent().find('input, select');
				all_inputs.each(function(){
					var this_name = $(this).attr('name');
					$(this).attr('name', this_name.replace(/{FIELD_TYPE}/g, new_type))
				});
			});
			
			$(\".input_post_fields_wrap\").add_field({ 
				addButton: $(\".add_post_field_button\"),
				remove_button: '.remove_field',
				fieldHTML: '<div style=\"margin-bottom:3px;\"><input placeholder=\""._FILENAME."\" type=\"text\" class=\"inp-form-ltr\" value=\"\" name=\"'+fields_name+'\" />&nbsp;<input placeholder=\""._FILELINK."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"'+fields_name+'\" size=\"40\" />&nbsp;&nbsp;<input placeholder=\""._FILESIZE."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"'+fields_name+'\" size=\"8\" />&nbsp;&nbsp;<input placeholder=\""._FILEDESC."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"'+fields_name+'\" />&nbsp;&nbsp;"._FILETYPE." <select class=\"styledselect-select field_type_select\" name=\"'+fields_name+'\" style=\"width:120px;\"><option value=\"files\" selected>"._FILES."</option><option value=\"images\">"._IMAGES."</option><option value=\"audios\">"._AUDIOS."</option><option value=\"videos\">"._VIDEOS."</option></select>&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a></div>',
				x: '$i',
			});
		});
	</script>";
	
	return $contents;
}

$cache_systems['nuke_articles_categories'] = array(
	'name'			=> "_ARTICLES_CATEGORIES",
	"main_id"		=> 'catid',
	'table'			=> CATEGORIES_TABLE,
	'where'			=> "module = '$this_module_name'",
	'order'			=> 'ASC',
	'fetch_type'	=> \PDO::FETCH_ASSOC,
	'first_code'	=> '',
	'loop_code'		=> '$this_data_array[$this_main_id][\'catname_url\'] = sanitize(str2url($row[\'catname\']));',
	'end_code'		=> '',
	'auto_load'		=> true
);

/*
$cache_systems[''.$pn_prefix.'_articles_configs'] = array(
	'name' => _ARTICLES_CONFIG,
	"main_id" => 'id',
	'table' => ''.$pn_prefix.'_articles_configs',
	'order' => 'ASC',
	'fetch_type' => MYSQL_ASSOC,
	'first_code' => '',
	'loop_code' => '',
	'end_code' => '',
	'auto_load' => true
);*/

$alerts_messages['articles_comments'] = array(
	"prefix"	=> "cs",
	"by"		=> "cid",
	"table"		=> COMMENTS_TABLE,
	"where"		=> "module = '$this_module_name' AND status = '0'",
	"color"		=> "green",
	"text"		=> "_HAVE_N_NEW_COMMENTS",
);

$alerts_messages['articles_pending'] = array(
	"prefix"	=> "ps",
	"by"		=> "sid",
	"table"		=> POSTS_TABLE,
	"where"		=> "status = 'pending' AND post_type = 'article'",
	"color"		=> "green",
	"text"		=> "_HAVE_N_NEW_PENDING_ARTICLE",
);

$admin_top_menus['contents']['children'][] = array(
	"id" => 'articles', 
	"parent_id" => 'contents', 
	"title" => _ARTICLES, 
	"url" => "".$admin_file.".php?op=articles", 
	"icon" => "",
	"children" => array(
		array(
			"id" => 'articles_add', 
			"parent_id" => 'articles', 
			"title" => "_ADD_NEW_ARTICLE", 
			"url" => "".$admin_file.".php?op=article_admin", 
			"icon" => ""
		),
		array(
			"id" => 'articles_comments', 
			"parent_id" => 'articles', 
			"title" => "_ARTICLES_COMMENTS", 
			"url" => "".$admin_file.".php?op=comments&module=articles", 
			"icon" => ""
		),
		array(
			"id" => 'articles_categories', 
			"parent_id" => 'articles', 
			"title" => "_ARTICLES_CATEGORIES", 
			"url" => "".$admin_file.".php?op=categories&module=articles", 
			"icon" => ""
		)
	)
);
$admin_top_menus['categories']['children'][] = array(
	"id" => 'articles_cat', 
	"parent_id" => 'categories', 
	"title" => "_ARTICLES_CATEGORIES", 
	"url" => "".$admin_file.".php?op=categories&module_name=Articles", 
	"icon" => ""
);
$admin_top_menus['recives']['children'][] = array(
	"id" => 'articles_pending', 
	"parent_id" => 'recives', 
	"title" => "_PENDING_ARTICLES", 
	"url" => "".$admin_file.".php?op=articles&status=pending", 
	"icon" => ""
);

$nuke_configs_links_function[$this_module_name] = "articleslink";
$nuke_configs_comments_table[$this_module_name] = array('sid', POSTS_TABLE);
$nuke_configs_categories_link[$this_module_name] = "index.php?modname=Articles&category={CAT_NAME_URL}";
$nuke_configs_categories_delete[$this_module_name]['data'] = array(
	"table"		=> POSTS_TABLE,
	"col_id"	=> "sid",
	"col_cats"	=> array("cat", "cat_link"),
	"where"		=> "post_type = 'article'",
	"recache"	=> "".$pn_prefix."_articles_categories"
);

$nuke_configs_search_data[$this_module_name] = array(
	"title"				=> "_ARTICLES",
	"table"				=> POSTS_TABLE,
	"have_comments"		=> true,
	"category_field"	=> "cat_link",
	"categories_field"	=> "cat",
	"time_field"		=> "time",
	"author_field"		=> "aid",
	"orderby"			=> "time",
	"where"				=> "status = 'publish' AND post_type = 'article'",
	"search_in_field"	=> array("title" => "_TITLE", "hometext" => "_HOMETEXT", "bodytext" => "_BODYTEXT", "tags" => "_KEYWORDS", "post_url" => "_ARTICLE_URL"),
	"fetch_fields"		=> array("*"),
	"more_link"			=> "articleslink_all",
	"search_template"	=> "articles_search",
	"parse_result"	=> "article_result_parse",
);

$nuke_configs_statistics_data[$this_module_name] = array(
	"total_articles" => array(
		"title"				=> "_ARTICLES",
		"table"				=> POSTS_TABLE,
		"count"				=> "sid",
		"as"				=> "total_articles",
		"where"				=> "status = 'publish' AND post_type = 'article'",
	),
	"pending_articles" => array(
		"title"				=> "_PENDING_ARTICLES",
		"table"				=> POSTS_TABLE,
		"count"				=> "sid",
		"as"				=> "pending_articles",
		"where"				=> "status = 'publish' AND aid != informant AND informant != '' AND post_type = 'article'",
	)
);


$nuke_rss_codes[$this_module_name] = '';

$nuke_modules_boxes_parts[$this_module_name] = array(
	"index" => "_INDEX",
	"more" => "_ARTICLE_MORE",
	"archive" => "_STORIESARCHIVE",
	"send_article" => "_SEND_POST",
	"category" => "_ARTICLES_CATEGORIES",
);


?>