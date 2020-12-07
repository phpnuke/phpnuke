<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('CONFIG_FUNCTIONS_FILE')) {
	die("You can't access this file directly...");
}

$articles_votetype = false;
$this_module_name = basename(dirname(__FILE__));

function articleslink_all($all_vars = array())
{
	return articleslink($all_vars['sid'], $all_vars['title'], $all_vars['post_url'], $all_vars['time'], $all_vars['cat_link'], $all_vars['post_type']);
}

function articleslink($sid, $title='', $post_url='', $time='', $cat_link='', $post_type='Articles', $mode='full')
{
	global $nuke_configs, $db, $HijriCalendar;
	$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
	$sid = intval($sid);
	
	if($title == '' OR $post_url == '' OR $time == '' OR $cat_link == '')
	{
		$row = $db->table(POSTS_TABLE)
						->where('sid', $sid)
						->first(['title', 'post_url', 'time', 'cat_link', 'post_type']);
		if(intval($db->count()) > 0)
		{
			$title = filter($row['title'], "nohtml");
			$post_type = filter($row['post_type'], "nohtml");
			$post_url = filter($row['post_url'], "nohtml");
			$time = $row['time'];
			$cat_link = intval($row['cat_link']);
		}
		else
			return '';
	}
	
	$module = (isset($post_type) && $post_type != '') ? $post_type:"Articles";
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

	$catname_link = (isset($nuke_categories_cacheData[$module])) ? sanitize(filter(implode("/", array_reverse(get_parent_names($cat_link, $nuke_categories_cacheData[$module], "parent_id", "catname_url"))), "nohtml"), array("/")):"";
	
	if($nuke_configs['gtset'] == "1")
	{
		if($mode == 'full')
		{
			$nuke_configs['pages_links'][$nuke_configs['userurl']] = (isset($nuke_configs['pages_links'][$nuke_configs['userurl']])) ? $nuke_configs['pages_links'][$nuke_configs['userurl']]:1;
			
			$article_link = (($post_type == 'Articles') ? "":strtolower($post_type)."/")."".str_replace(array('{ID}','{YEAR}','{MONTH}','{DAY}','{CATEGORY}','{PAGEURL}'), array($sid, $timelink[2], $timelink[1], $timelink[0], $catname_link, $post_url),$nuke_configs['pages_links'][$nuke_configs['userurl']]);
		}
		else
			$article_link = (($post_type == 'Articles' || $post_type == '') ? "":strtolower($post_type)."/").$sid."/";
	}
	else
		$article_link = "index.php?modname=$module&op=article_show&sid=$sid".(($mode == 'full') ? "-$title":"");

	return $article_link;
}

function articles_feed($module_args=array(), $post_type = 'Articles')
{
	global $db, $nuke_configs, $noPermaLink;
	
	$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
	$feed_data = array();
	$module = (isset($post_type) && $post_type != '') ? $post_type:"Articles";
	
	$query_set = array();
		
	$query_set['status'] = "status = 'publish'";
	$query_set['post_type'] = "post_type = '$post_type'";
	
	if ($nuke_configs['multilingual'] == 1)
		$query_set['alanguage']	= "(alanguage='".$nuke_configs['currentlang']."' OR alanguage='')";

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
		
	$all_sub_cats = array();
	
	if(isset($module_args['category']))
	{
		$category = filter($module_args['category'], "nohtml");
		
		$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
		$catid = get_category_id($module_args['main_module'], $category, $nuke_categories_cacheData);	
		$catid = intval($catid);

		$all_sub_cats = array_unique(get_sub_categories_id($module_args['main_module'], $catid, $nuke_categories_cacheData, array($catid)));
		
		foreach($all_sub_cats as $sub_cat)
			$query_set['cat'][] = "FIND_IN_SET(?, cat)";
			
		$query_set['cat']= "(".implode(" OR ", $query_set['cat'])." OR cat_link = '$catid')";
	}
		
	$query_set					= implode(" AND ", array_filter($query_set));
	$query_set					= ($query_set != "") ? "WHERE $query_set":"";
	
	$result = $db->query("SELECT aid, post_type, sid, informant, title, alanguage, post_url, time, hometext, cat, cat_link, post_image FROM ".POSTS_TABLE." $query_set ORDER BY time DESC LIMIT 0,50", $all_sub_cats);

	if($result->count() > 0)
	{
		$rows = $result->results();
		$row_count = 0;
		foreach ($rows as $row)
		{
			$sid = intval($row['sid']);
			$aid = filter($row['aid'], "nohtml");
			$post_type = filter($row['post_type'], "nohtml");
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

			$link = articleslink($sid, $row['title'], $row['post_url'], $time, $cat_link, $post_type);
			
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
					if(isset($nuke_categories_cacheData[$module][$cat]))
						$feed_data[$row_count]['category'][] = filter(category_lang_text($nuke_categories_cacheData[$module][$cat]['cattext']), "nohtml");
				}
			
			$row_count++;
		}
	}
	
	return $feed_data;
}

function get_article_image($sid = 0, $article_image = '', $hometext = '', $post_type = 'Articles')
{
	preg_match_all('#<img(.*)src=["|\'](.*)["|\']#isU', stripslashes($hometext), $images_match);
	if($article_image == '')
		if(file_exists("files/$post_type/".$sid.".jpg"))
			$article_image = "files/$post_type/".$sid.".jpg";
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

function _article_select_month($in = 0, $module = 'Articles', $post_type = 'Articles')
{
	global $db, $nuke_configs, $module_name, $HijriCalendar;
	
	$contents = "";
	$contents .= "<div class=\"text-center\"><font class=\"content\">"._SELECTMONTH2VIEW."</font><br><br></div>";
	
	$result = $db->table(POSTS_TABLE)
					->where('post_type', $post_type)
					->order_by(['time' => 'DESC'])
					->select(['time']);

	$module_name = (isset($module) && $module != 'Articles') ? $module:$module_name;
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
					$contents .= "<li><a href=\"".LinkToGT("index.php?modname=$module_name&op=article_archive&year=$jalalidate[0]&month=$jalalidate[1]&month_l=$month2")."\">$month, $jalalidate[0]</a>";
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
					$contents .= "<li><a href=\"".LinkToGT("index.php?modname=$module_name&op=article_archive&year=$dateTimes[2]&month=$dateTimes[0]&month_l=$month2")."\">$month, $dateTimes[2]</a>";
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
					$contents .= "<li><a href=\"".LinkToGT("index.php?modname=$module_name&op=article_archive&&year=$dateTimes_year&month=$dateTimes_month&month_l=$month2")."\">$month, $dateTimes_year</a>";
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
	global $db, $nuke_authors_cacheData, $pn_Cookies, $votetype, $nuke_configs, $articles_votetype, $nuke_meta_keys_parts, $this_module_name, $visitor_ip, $userinfo;
	
	$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
	
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
				$prev_next_left = "LEFT JOIN ".POSTS_TABLE." AS s1 ON s1.sid = (
				SELECT MAX(sid)
				FROM ".POSTS_TABLE." 
				WHERE sid < s.sid AND post_type = s.post_type ".((!is_admin()) ? "AND status = :status":"")."
			) 
			LEFT JOIN ".POSTS_TABLE." AS s2 ON s2.sid = (
				SELECT MIN(sid)
				FROM ".POSTS_TABLE." 
				WHERE sid > s.sid AND post_type = s.post_type ".((!is_admin()) ? "AND status = :status":"")."
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
	{
		$post_ids[] = intval($row['sid']);
		$post_types[] = "'".$row['post_type']."'";
	}
	
	if(!empty($post_ids))
	{
		// get posts meta
		$result = $db->query("SELECT post_id, meta_key, meta_value FROM ".POSTS_META_TABLE." WHERE post_id IN (".implode(",", $post_ids).") AND meta_part IN (".implode(",", $post_types).")");
		if(!empty($result))
		{
			$meta_data = $result->results();
			foreach($meta_data as $meta_data_val)
				$meta_datas[$meta_data_val['post_id']][$meta_data_val['meta_key']] = $meta_data_val['meta_value'];
		}
		// get posts meta
		
		// get posts scores
		$result = $db->query("SELECT s.post_id, db_table, $votes_query2, (SELECT sc.id FROM ".SCORES_TABLE." as sc WHERE ($vote_where) AND s.post_id = sc.post_id AND sc.db_table = s.db_table ORDER BY sc.id ASC LIMIT 1) AS you_rated FROM ".SCORES_TABLE." as s WHERE s.post_id IN (".implode(",", $post_ids).") AND s.votetype IN ($newvotetype) AND s.db_table IN (".implode(",", $post_types).") GROUP BY s.post_id", $query_params);
		
		if(!empty($result))
		{
			$scores = $result->results();
			foreach($scores as $scores_val)
			{
				$scores_data[$scores_val['db_table']][$scores_val['post_id']] = array(
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
		$article_info['total_rows']			= ($key == 0 && isset($row['total_rows'])) ? intval($row['total_rows']):$article_info['total_rows'];
		$article_info[$key]['sid']			= intval($row['sid']);
		$article_info[$key]['aid']			= filter($row['aid'], "nohtml");
		$article_info[$key]['post_type']	= filter($row['post_type'], "nohtml");
		$article_info[$key]['aid_url']		= (isset($nuke_authors_cacheData[$row['aid']]['url'])) ? filter($nuke_authors_cacheData[$row['aid']]['url'], "nohtml"):$nuke_configs['nukeurl'];
		$article_info[$key]['time']			= filter($row['time']);
		$article_info[$key]['title']		= filter($row['title'], "nohtml");
		$article_info[$key]['title_lead']	= filter($row['title_lead'], "nohtml");
		$article_info[$key]['title_color']	= filter($row['title_color'], "nohtml");
		$hometext							= text_rel2abs(stripslashes($row['hometext']));
		$article_info[$key]['hometext']		= codereplace($hometext,$article_info[$key]['sid']);
		$bodytext							= text_rel2abs(stripslashes($row['bodytext']));
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
		$article_info[$key]['post_image']	= get_article_image($article_info[$key]['sid'], $row['post_image'], $row['hometext'].$row['bodytext'], $article_info[$key]['post_type']);
		
		if(isset($meta_datas[$article_info[$key]['sid']]) && !empty($meta_datas[$article_info[$key]['sid']]))
			$article_info[$key] = array_merge($article_info[$key], $meta_datas[$article_info[$key]['sid']]);
			
		if(isset($nuke_meta_keys_parts[$article_info[$key]['post_type']]) && !empty($nuke_meta_keys_parts[$article_info[$key]['post_type']]))
		{
			foreach($nuke_meta_keys_parts[$article_info[$key]['post_type']] as $meta_key => $article_fields_meta_field)
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
		if(isset($scores_data[$article_info[$key]['post_type']][$article_info[$key]['sid']]) && !empty($scores_data[$article_info[$key]['post_type']][$article_info[$key]['sid']]))
			$article_info[$key] = array_merge($article_info[$key], $scores_data[$article_info[$key]['post_type']][$article_info[$key]['sid']]);
			
		if(!empty($article_info[$key]['cats']))
		{
			foreach($article_info[$key]['cats'] as $cat)
			{
				if(!isset($nuke_categories_cacheData[$article_info[$key]['post_type']][$cat])) continue;
				$article_info[$key]['cats_data'][$cat]		= array(
					"cattext"			=> filter(category_lang_text($nuke_categories_cacheData[$article_info[$key]['post_type']][$cat]['cattext']), "nohtml"),
					"catname"			=> filter($nuke_categories_cacheData[$article_info[$key]['post_type']][$cat]['catname'], "nohtml"),
					"catimage"			=> filter($nuke_categories_cacheData[$article_info[$key]['post_type']][$cat]['catimage'], "nohtml"),
					"catlink"			=> LinkToGT("index.php?modname=".$article_info[$key]['post_type']."&category=".filter($nuke_categories_cacheData[$article_info[$key]['post_type']][$cat]['catname_url'], "nohtml")),
				);
			}			
		}

		$article_info[$key]['article_image_width'] = $article_info[$key]['article_image_height'] = 0;
		if($article_info[$key]['post_image'] != '' && file_exists($article_info[$key]['post_image']))
			list($article_info[$key]['article_image_width'], $article_info[$key]['article_image_height']) = getimagesize($article_info[$key]['post_image']);
				
		$disabled_rating = false;

		if ($pn_Cookies->exists(''.$article_info[$key]['post_type'].'_ratecookie'))
		{
			$rcookie				= base64_decode($pn_Cookies->get(''.$article_info[$key]['post_type'].'_ratecookie'));
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
		$article_info[$key]['rating_box']		= rating_load($article_info[$key]['score'], $article_info[$key]['ratings'], $article_info[$key]['likes'], $article_info[$key]['dislikes'], $article_info[$key]['post_type'], "sid", $article_info[$key]['sid'], $disabled_rating, $votetype);
					
		if(isset($nuke_categories_cacheData[$article_info[$key]['post_type']][$article_info[$key]['cat_link']]))
		{
			$article_info[$key]['cattext_link']		= filter(category_lang_text($nuke_categories_cacheData[$article_info[$key]['post_type']][$article_info[$key]['cat_link']]['cattext']), "nohtml");
			$article_info[$key]['catname_link']		= filter($nuke_categories_cacheData[$article_info[$key]['post_type']][$article_info[$key]['cat_link']]['catname'], "nohtml");
			$article_info[$key]['catimage_link']		= filter($nuke_categories_cacheData[$article_info[$key]['post_type']][$article_info[$key]['cat_link']]['catimage'], "nohtml");
		}
		else
		{
			$article_info[$key]['cattext_link'] = "";
			$article_info[$key]['catname_link'] = "";
			$article_info[$key]['catimage_link'] = "";
		}
		
		$article_info[$key]['article_link']		= LinkToGT(articleslink($article_info[$key]['sid'], $article_info[$key]['title'], $article_info[$key]['post_url'], $article_info[$key]['time'], $article_info[$key]['cat_link'], $article_info[$key]['post_type']));
		$article_info[$key]['article_short_link'] = LinkToGT(articleslink($article_info[$key]['sid'], $article_info[$key]['title'], $article_info[$key]['post_url'], $article_info[$key]['time'], $article_info[$key]['cat_link'], $article_info[$key]['post_type'], "short"));
		
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
			foreach($files as $file_data)
			{
				if(empty($file_data))
					continue;
				
				$filename = $file_data[0];
				$filelink = $file_data[1];
				$filesize = $file_data[2];
				$filedesc = $file_data[3];
				$filetype = (isset($file_data[4]) && $file_data[4] != '') ? $file_data[4]:'files';
				$sel1 = ($filetype == "files") ? "selected":"";
				$sel2 = ($filetype == "images") ? "selected":"";
				$sel3 = ($filetype == "audios") ? "selected":"";
				$sel4 = ($filetype == "videos") ? "selected":"";
				$contents .= "
				<div style=\"margin-bottom:3px;\">
					<input placeholder=\""._FILENAME."\" type=\"text\" class=\"inp-form\" value=\"$filename\" name=\"".$form_field_name."[$i][]\" />&nbsp;<input placeholder=\""._FILELINK."\" type=\"text\" class=\"inp-form-ltr\" value=\"$filelink\" name=\"".$form_field_name."[$i][]\" size=\"40\" />&nbsp;&nbsp;<input placeholder=\""._FILESIZE."\" type=\"text\" class=\"inp-form\" value=\"$filesize\" name=\"".$form_field_name."[$i][]\" size=\"8\" />&nbsp;&nbsp;<input placeholder=\""._FILEDESC."\" type=\"text\" class=\"inp-form\" value=\"$filedesc\" name=\"".$form_field_name."[$i][]\" />&nbsp;&nbsp;"._FILETYPE." <select class=\"styledselect-select field_type_select\" name=\"".$form_field_name."[$i][]\" style=\"width:120px;\"><option value=\"files\" $sel1>"._FILES."</option><option value=\"images\" $sel2>"._IMAGES."</option><option value=\"audios\" $sel3>"._AUDIOS."</option><option value=\"videos\" $sel4>"._VIDEOS."</option></select>&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a>
				</div>";
				$i++;
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
			
			var fields_name = '".$form_field_name."[{X}][]';
			
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
				maxField: 1000,
				remove_button: '.remove_field',
				fieldHTML: '<div style=\"margin-bottom:3px;\"><input placeholder=\""._FILENAME."\" type=\"text\" class=\"inp-form-ltr\" value=\"\" name=\"'+fields_name+'\" />&nbsp;<input placeholder=\""._FILELINK."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"'+fields_name+'\" size=\"40\" />&nbsp;&nbsp;<input placeholder=\""._FILESIZE."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"'+fields_name+'\" size=\"8\" />&nbsp;&nbsp;<input placeholder=\""._FILEDESC."\" type=\"text\" class=\"inp-form\" value=\"\" name=\"'+fields_name+'\" />&nbsp;&nbsp;"._FILETYPE." <select class=\"styledselect-select field_type_select\" name=\"'+fields_name+'\" style=\"width:120px;\"><option value=\"files\" selected>"._FILES."</option><option value=\"images\">"._IMAGES."</option><option value=\"audios\">"._AUDIOS."</option><option value=\"videos\">"._VIDEOS."</option></select>&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a></div>',
				x: '$i',
			});
		});
	</script>";
	
	return $contents;
}

function parse_post_gt_links($matches)
{
	$output = array();
	if(isset($matches[1]))
	{
		parse_str($matches[1], $post_url);
		$vars = array('modname' => 'filter', 'category' => 'filter', 'orderby' => 'filter', 'page' => 'int', 'tags' => 'filter', 'year' => 'int', 'month' => 'int', 'day' => 'int', 'post_url' => 'filter', 'op' => 'filter', 'sid' => 'int', 'month_l' => 'filter');
		
		foreach($vars as $key => $filter)
		{
			$vars[$key] = (isset($post_url[$key]) && $post_url[$key] != '') ? (($filter == "int") ? intval($post_url[$key]):filter($post_url[$key], "nohtml")):"";
		}
		
		$output[] = ($vars['modname'] == 'Articles') ? "":strtolower($vars['modname']);
		
		if($vars['category'] != '')
		{
			$output[] = ($vars['post_url'] == '') ? "category":"";
			$output[] = $vars['category'];
		}
		
		if($vars['tags'] != '')
		{
			$output[] = 'tags';
			$output[] = $vars['tags'];
		}
		
		if($vars['op'] != '' && ($vars['op'] == 'article_archive' || $vars['op'] == 'article_select_month'))
		{
			$output[] = "archive";
		}
		if($vars['year'] != '')
			$output[] = $vars['year'];
		
		if($vars['month'] != '')
			$output[] = $vars['month'];
		
		if($vars['day'] != '')
			$output[] = $vars['day'];
		
		if($vars['month_l'] != '')
			$output[] = $vars['month_l'];
		
		if($vars['orderby'] != '' && in_array($vars['orderby'], array("most-visit","most-comment","most-rate")))
			$output[] = $vars['orderby'];
		
		if($vars['sid'] != '')
			$output[] = $vars['sid'];
		
		if($vars['post_url'] != '')
			$output[] = $vars['post_url'];
			
		if($vars['op'] != '' && in_array($vars['op'], array('pdf','print','friend','report','article_categories','send_article','article_archive','article_select_month')))
		{
			if(in_array($vars['op'], array('pdf','print','friend','report')))
				$output[] = $vars['op'];
			elseif($vars['op'] == 'article_categories')
				$output[] = "category";
			elseif($vars['op'] == 'send_article')
				$output[] = "send-article";
			elseif($vars['op'] == 'article_archive' && $vars['year'] == '')
			{
				$output[] = "all";
			}
		}
		
		if($vars['page'] != '')
		{
			$output[] = ($vars['post_url'] != '') ? "comment-page":"page";
			$output[] = $vars['page'];
		}
	}
	unset($vars);
	return trim(implode("/", array_filter($output)), "/")."/";
}

function parse_post_links($matches)
{
	$output = array();
	
	if(isset($matches[1]))
	{
		$post_url = explode("/", trim($matches[1], "/"));
		
		if(!empty($post_url))
		{
			$output[] = "modname=".((in_array($post_url[0], array("downloads","pages","gallery","statics","faqs"))) ? ((is_active($post_url[0])) ? ucfirst($post_url[0]):"Articles&main_module=".ucfirst($post_url[0]).""):"Articles&main_module=Articles");
			
			if(in_array($post_url[0], array("downloads","pages","gallery","statics","faqs")))
				unset($post_url[0]);
			$post_url = array_values($post_url);
			
			foreach($post_url as $key => $arg)
			{
				if(in_array($arg, array("most-visit", "most-comment", "most-rate")))
					$output[] = "orderby=$arg";
				if(in_array($arg, array("page")) && isset($post_url[$key+1]) && intval($post_url[$key+1]) != 0)
				{
					$output[] = "page=".$post_url[$key+1]."";
					unset($post_url[$key]);
					unset($post_url[$key+1]);
					$post_url = array_values($post_url);
				}
				if($arg == "send-article")
					$output[] = "op=send_article";
				if($arg == "archive")
					$output[] = "op=article_archive";
				if(in_array($arg, array("category")) && !isset($post_url[$key+1]))
					$output[] = "op=article_categories";
				if(in_array($arg, array("friend","pdf","print","report")))
				{
					$output[] = "mode=$arg";
					unset($post_url[$key]);
					$post_url = array_values($post_url);
				}
				if(preg_match("#comment-page-([0-9]+)#", $arg, $arg_page))
				{
					$output[] = "page=$arg_page[1]";
					unset($post_url[$key]);
					$post_url = array_values($post_url);
				}
			}
			
			if(isset($post_url[0]))
			{
				if(in_array($post_url[0], array("category", "tags")))
					$output[] = (isset($post_url[1]) && $post_url[1] != '') ? "$post_url[0]=$post_url[1]":"";
				else
				{
					if($post_url[0] == 'archive')
					{
						if(isset($post_url[1]) && intval($post_url[1]) != 0)
							$output[] = "year=$post_url[1]";
						if(isset($post_url[2]) && intval($post_url[2]) != 0)
							$output[] = "month=$post_url[2]";
						if(isset($post_url[3]) && $post_url[3] != '' && $post_url[3] != 'page')
							$output[] = "month_l=$post_url[3]";
						if(isset($post_url[1]) && $post_url[1] == 'all')
							$output[] = "mode=all";
					}
					if(intval($post_url[0]) != 0)
					{
						if(!isset($post_url[1]) || $post_url[1] == '')
							$output[] = "op=article_show&sid=$post_url[0]&post_url=$post_url[0]";
						elseif(isset($post_url[1]))
							if(intval($post_url[1]) != 0)
								$output[] = "op=article_show&post_url=".((intval($post_url[2]) != 0) ? $post_url[3]:$post_url[2]);
							else
								$output[] = "op=article_show&post_url=$post_url[1]";
					}
					if(intval($post_url[0]) == 0 && $post_url[0] != '' && !in_array($post_url[0], array("send-article", "archive")))
					{
						if(!isset($post_url[1]) || $post_url[1] == '')
							$output[] = "op=article_show&post_url=$post_url[0]";
						elseif(isset($post_url[1]) && $post_url[1] != '')
							$output[] = "op=article_show&post_url=$post_url[1]";
					}
				}
			}
		}
	}
	
	return "index.php?".implode("&", array_filter($output));
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
	"where"		=> "module = 'Articles' AND status = '0'",
	"color"		=> "green",
	"text"		=> "_HAVE_N_NEW_COMMENTS",
);

$alerts_messages['articles_pending'] = array(
	"prefix"	=> "ps",
	"by"		=> "sid",
	"table"		=> POSTS_TABLE,
	"where"		=> "status = 'pending' AND post_type = 'Articles'",
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
			"url" => "".$admin_file.".php?op=comments&module=Articles", 
			"icon" => ""
		),
		array(
			"id" => 'articles_categories', 
			"parent_id" => 'articles', 
			"title" => "_ARTICLES_CATEGORIES", 
			"url" => "".$admin_file.".php?op=categories&module=Articles", 
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

$nuke_configs_links_function['Articles'] = $nuke_configs_links_function['Downloads'] = $nuke_configs_links_function['Pages'] =$nuke_configs_links_function['Faqs'] = $nuke_configs_links_function['Gallery'] = $nuke_configs_links_function['Statics'] = "articleslink";
$nuke_configs_comments_table['Articles'] = $nuke_configs_comments_table['Downloads'] = $nuke_configs_comments_table['Pages'] =$nuke_configs_comments_table['Faqs'] = $nuke_configs_comments_table['Gallery'] = $nuke_configs_comments_table['Statics'] = array('sid', POSTS_TABLE);
$nuke_configs_categories_link['Articles'] = "index.php?modname=Articles&category={CAT_NAME_URL}";
$nuke_configs_categories_link['Downloads'] = "index.php?modname=Downloads&category={CAT_NAME_URL}";
$nuke_configs_categories_link['Pages'] = "index.php?modname=Pages&category={CAT_NAME_URL}";
$nuke_configs_categories_link['Faqs'] = "index.php?modname=Faqs&category={CAT_NAME_URL}";
$nuke_configs_categories_link['Gallery'] = "index.php?modname=Gallery&category={CAT_NAME_URL}";
$nuke_configs_categories_link['Statics'] = "index.php?modname=Statics&category={CAT_NAME_URL}";

$nuke_configs_categories_delete['Articles']['data'] = array(
	"table"		=> POSTS_TABLE,
	"col_id"	=> "sid",
	"col_cats"	=> array("cat", "cat_link"),
	"where"		=> "post_type = 'Articles'",
	"recache"	=> "".$pn_prefix."_articles_categories"
);

$nuke_configs_search_data['All'] = $nuke_configs_search_data['Downloads'] = $nuke_configs_search_data['Pages'] = $nuke_configs_search_data['Gallery'] = $nuke_configs_search_data['Faqs'] = $nuke_configs_search_data['Statics'] = $nuke_configs_search_data['Articles'] = array(
	"title"				=> "_ARTICLES",
	"table"				=> POSTS_TABLE,
	"have_comments"		=> true,
	"category_field"	=> "cat_link",
	"categories_field"	=> "cat",
	"time_field"		=> "time",
	"author_field"		=> "aid",
	"orderby"			=> "time",
	"where"				=> "status = 'publish' AND post_type = 'Articles'",
	"search_in_field"	=> array("title" => "_TITLE", "hometext" => "_HOMETEXT", "bodytext" => "_BODYTEXT", "tags" => "_KEYWORDS", "post_url" => "_ARTICLE_URL"),
	"fetch_fields"		=> array("*"),
	"more_link"			=> "articleslink_all",
	"search_template"	=> "articles_search",
	"parse_result"		=> "article_result_parse",
);

$nuke_configs_search_data['All']['title'] = "_ALL";
$nuke_configs_search_data['All']['where'] = "status = 'publish'";
$nuke_configs_search_data['Downloads']['title'] = "_DOWNLOADS";
$nuke_configs_search_data['Downloads']['where'] = "status = 'publish' AND post_type = 'Downloads'";
$nuke_configs_search_data['Pages']['title'] = "_PAGESCONTENTS";
$nuke_configs_search_data['Pages']['where'] = "status = 'publish' AND post_type = 'Pages'";
$nuke_configs_search_data['Gallery']['title'] = "_GALLERY";
$nuke_configs_search_data['Gallery']['where'] = "status = 'publish' AND post_type = 'Gallery'";
$nuke_configs_search_data['Faqs']['title'] = "_FAQS";
$nuke_configs_search_data['Faqs']['where'] = "status = 'publish' AND post_type = 'Faqs'";
$nuke_configs_search_data['Statics']['title'] = "_STATICS";
$nuke_configs_search_data['Statics']['where'] = "status = 'publish' AND post_type = 'Statics'";

$nuke_configs_statistics_data[$this_module_name] = array(
	"total_articles" => array(
		"title"				=> "_ARTICLES",
		"table"				=> POSTS_TABLE,
		"count"				=> "sid",
		"as"				=> "total_articles",
		"where"				=> "status = 'publish' AND post_type = 'Articles'",
	),
	"total_downloads" => array(
		"title"				=> "_DOWNLOADS",
		"table"				=> POSTS_TABLE,
		"count"				=> "sid",
		"as"				=> "total_downloads",
		"where"				=> "status = 'publish' AND post_type = 'Downloads'",
	),
	"total_pages" => array(
		"title"				=> "_PAGES",
		"table"				=> POSTS_TABLE,
		"count"				=> "sid",
		"as"				=> "total_pages",
		"where"				=> "status = 'publish' AND post_type = 'Pages'",
	),
	"total_pages" => array(
		"title"				=> "_GALLERY",
		"table"				=> POSTS_TABLE,
		"count"				=> "sid",
		"as"				=> "total_galleries",
		"where"				=> "status = 'publish' AND post_type = 'Gallery'",
	),
	"total_pages" => array(
		"title"				=> "_FAQS",
		"table"				=> POSTS_TABLE,
		"count"				=> "sid",
		"as"				=> "total_faqs",
		"where"				=> "status = 'publish' AND post_type = 'Faqs'",
	),
	"total_statics" => array(
		"title"				=> "_STATICS",
		"table"				=> POSTS_TABLE,
		"count"				=> "sid",
		"as"				=> "total_faqs",
		"where"				=> "status = 'publish' AND post_type = 'Statics'",
	),
	"pending_articles" => array(
		"title"				=> "_PENDING_ARTICLES",
		"table"				=> POSTS_TABLE,
		"count"				=> "sid",
		"as"				=> "pending_articles",
		"where"				=> "status = 'publish' AND aid != informant AND informant != '' AND post_type = 'Articles'",
	)
);


$nuke_rss_codes[$this_module_name] = '';

$nuke_modules_boxes_parts[$this_module_name] = array(
	"index" => "_INDEX",
	"more" => "_ARTICLE_MORE",
	"archive" => "_STORIESARCHIVE",
	"send_article" => "_SEND_POST",
	"category" => "_ARTICLES_CATEGORIES",
	"gallery_index" => "_GALLERY_INDEX",
	"gallery_more" => "_GALLERY_MORE",
	"downloads_index" => "_DOWNLOADS_INDEX",
	"downloads_more" => "_DOWNLOADS_MORE",
	"pages_index" => "_PAGES_INDEX",
	"pages_more" => "_PAGES_MORE",
	"faqs_index" => "_FAQS_INDEX",
	"faqs_more" => "_FAQS_MORE",
	"statics_index" => "_STATICS_INDEX",
	"statics_more" => "_STATICS_MORE",
);


?>