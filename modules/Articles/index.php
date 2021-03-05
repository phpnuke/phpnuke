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

if(!defined("INDEX_FILE"))
	define('INDEX_FILE', is_index_file($module_name));// to define INDEX_FILE status

if(!function_exists("article_index"))
{
	function article_index($article_info)
	{
		global $ShowTopic,$tipath,$nuke_configs;
		
		$article_info['comments'] = ($article_info['comments']==0) ? "0":$article_info['comments'];
		
		$contents = "
			<!--Articles-->

			<div class=\"Articles\">
			  <div class=\"ArticlesTitle\">
				<div class=\"ArticlesRating\">
					".$article_info['rating_box']."
				</div>
				<div>
				  <h2 class=\"ArticlesTitleText\"><a href=\"".$article_info['article_link']."\" rel=\"bookmark\" title=\"".$article_info['title']."\">
					".$article_info['title']."
					</a></h2>
				</div>
			  </div>
			  <div class=\"ArticlesBody\">
				<div class=\"ArticlesBodyText\">
				  <p>
					".$article_info['hometext']."
				  </p>
				</div>
			  </div>
			  <div class=\"ArticlesFoot\"><a class=\"MoreArticles\" href=\"".$article_info['article_link']."\" title=\"".$article_info['title']."\">
				"._MORE."
				</a>
				<div class=\"ArticlesFootText\">
				  ".$article_info['datetime']."
				  |
				  ".$article_info['comments']."
				  "._COMMENTS."
				  |
				  "._VISITS."
				  [
				  ".$article_info['counter']."
				  ]</div>
			  </div>
			</div>
			<br />";
		return $contents;
	}
}

if(!function_exists("article_more"))
{
	function article_more($article_info)
	{
		global $nuke_configs;
		$contents = '';
		$htmltags = '';
		$posted = _POSTEDON." ".$article_info['datetime']." "._BY." "; 
		$posted .= get_author($article_info['aid']);
		$posted .= "&nbsp;&nbsp;<a href=\"".$article_info['print_link']."\" target=\"_blank\"><img border=\"0\" src=\"".$nuke_configs['nukecdnurl']."images/print.gif\" width=\"16\" height=\"16\" alt=\""._PRINT."\" title=\""._PRINT."\"></a>";
		$tags = str_replace(" ","-",$article_info['tags']);
		$tags = explode(",",$tags);
		$tags = array_filter($tags);
		foreach($tags as $tag)
			$htmltags .= "<i><a href=\"".LinkToGT("index.php?modname=Articles&tags=$tag")."\">".str_replace("_"," ",$tag)."</a></i> ";

		$contents .= "
		<div class=\"Articles\">
			<div class=\"ArticlesTitle\">
				<a href=\"".$article_info['report_link']."\" data-mode=\"inline\" data-toggle=\"modal\" data-target=\"#sitemodal\">"._POST_REPORT."</a>
				<a href=\"".$article_info['friend_link']."\" data-mode=\"inline\" data-toggle=\"modal\" data-target=\"#sitemodal\">"._INTRODUCE_TO_FRIENDS."</a>
				<a href=\"".$article_info['pdf_link']."\">"._PDFFILE."</a>
				<a href=\"".$article_info['print_link']."\">"._PRINT."</a>
				<div class=\"ArticlesRating\">".$article_info['rating_box']."</div>
				<h1 class=\"ArticlesTitleText\"><a href=\"".$article_info['article_link']."\" rel=\"bookmark\" title=\"".$article_info['title']."\">".$article_info['title']."</a></h1>
			</div>
			<div class=\"ArticlesBody\">
				<div class=\"ArticlesBodyText\">
					".$article_info['hometext']."
					<br />
					<br />
					".$article_info['bodytext']."
					<br />
					<br />
					$htmltags
				</div>
			</div>
			<div class=\"ArticlesFoot\">
				<div class=\"ArticlesFootText\">
					$posted
				</div>
			</div>
		</div>
		<br />";
		return $contents;
	}
}

function articles_home($category='', $tags='', $orderby = '', $main_module = 'Articles')
{
	global $db, $userinfo, $page, $module_name, $visitor_ip, $nuke_configs, $hooks;
	
	$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
	
	$link_to = array();
	$link_to['modname'] = ($main_module != 'Articles') ? "$main_module":"";

	$top_middle = ((isset($tags) && $tags != '') || (isset($page) && intval($page) != 0) || (isset($category) && $category != '')) ? false:true;

	$nuke_modules_cacheData_by_title = phpnuke_array_change_key($nuke_modules_cacheData, "mid", "title");
	
	$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
	$catid = get_category_id($main_module, $category, $nuke_categories_cacheData);	
	
	$catid = intval($catid);
	if($catid < 0)
		header("location: ".LinkToGT("index.php")."");

	switch($main_module)
	{
		case"Downloads":
			$module_name_title = _DOWNLOADS;
		break;
		case"Pages":
			$module_name_title = _PAGESCONTENTS;
		break;
		case"Statics":
			$module_name_title = _STATICS;
		break;
		case"Gallery":
			$module_name_title = _GALLERY;
		break;
		case"Faqs":
			$module_name_title = _FAQS;
		break;
	}
	
	if ($nuke_configs['multilingual'] == 1)
	{
		$module_titles = $lang_titles = ($nuke_modules_cacheData_by_title[$module_name]['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($nuke_modules_cacheData_by_title[$module_name]['lang_titles'])):"";
		
		$module_title = (($catid > 0) ? $category." - ":"").(($main_module == 'Articles') ? $module_titles[$nuke_configs['currentlang']]:$module_name_title);
	}
	else
		$module_title = (($catid > 0) ? $category." - ":"").(($main_module == 'Articles') ? $nuke_modules_cacheData_by_title[$module_name]['title']:$module_name_title);
	
	$module_title = $hooks->apply_filters("post_module_name_title", $module_title, $main_module, $catid);
	
	$db->table(POSTS_TABLE)
		->where('status', 'future')
		->where('time', '<',  _NOWTIME)
		->update([
			'status' => 'publish'
		]);
		
	$hooks->do_action("future_post_publish_after");
	
	switch($orderby)
	{
		case"most-visit":
			$orderby = 'counter';
		break;
		case"most-rate":
			$orderby = 'score';
		break;
		case"most-comment":
			$orderby = 'comments';
		break;
		default:
			$orderby = 'time';
		break;
	}
	
	$query_set = array();
	$query_params = array();
	
	$query_params[':visitor_ip'] = $visitor_ip;
	$query_params[':visitor_ip'] = $visitor_ip;
	
	if(!is_admin())
		$query_set['status'] = "s.status = 'publish'";
		
	$query_set['alanguage'] = "";
	$query_set['ihome'] = "s.ihome = '1'";
	$query_set['cat'] = array();
	$query_set['post_type'] = "(s.post_type = '$main_module')";
	
	if ($nuke_configs['multilingual'] == 1)
		$query_set['alanguage'] = "(s.alanguage='".$nuke_configs['currentlang']."' OR s.alanguage='')";		
	
	$all_sub_cats = array();

	if ($catid > 0)
	{
		$all_sub_cats = array_unique(get_sub_categories_id($main_module, $catid, $nuke_categories_cacheData, array($catid)));

		$c=1;
		foreach($all_sub_cats as $sub_cat)
		{
			$query_set['cat'][] = "FIND_IN_SET(:cat_$c, s.cat)";
			$query_params[":cat_$c"] = $sub_cat;
			$c++;
		}
		$query_set['cat']= "(".implode(" OR ", $query_set['cat'])." OR s.cat_link = :cat_link)";
		$query_params[":cat_link"] = $catid;
	}
			
    if (isset($userinfo['artcle_num']) AND (isset($nuke_configs['user_pagination']) && $nuke_configs['user_pagination'] == 1))
		$artcle_num = intval($userinfo['artcle_num']);
	else
		$artcle_num = intval($nuke_configs['home_pagination']);
	
	$contents = "";
	
	$tags2 = $tags3 = "";
	if($tags != "")
	{
		if($main_module == 'Articles')
			unset($query_set['post_type']);
			
		$tags	= str_replace(array("_","-")," ",$tags);
		$tags	= check_html($tags);
		$tags_arr	= adv_filter($tags, array('sanitize_string'),array('required'));
		if($tags_arr[0] != 'error')
		{
			$tags = $tags_arr[1];
			$tags	= htmlentities(trim($tags), ENT_QUOTES,"utf-8");
			$tags2	= str_replace(_FAANDAR1,_FAANDAR11,$tags);
			$tags2	= str_replace(_FAANDAR2,_FAANDAR22,$tags2);
			$tags3	= str_replace(_FAANDAR11, _FAANDAR1,$tags);
			$tags3	= str_replace(_FAANDAR22, _FAANDAR2,$tags3);
			
			$tagresult = $db->table(TAGS_TABLE)
				->Where('tag', $tags)
				->orWhere('tag', $tags2)
				->orWhere('tag', $tags3)
				->first(['tag_id']);

			if($tagresult->count() > 0)
			{
				$tag_id = intval($tagresult['tag_id']);
				$db->table(TAGS_TABLE)
					->where('tag_id', $tag_id)
					->update([
						'visits' => ["+", 1]
					]);
				
			}
			$query_set['tags'] = "(tags LIKE :tags OR tags LIKE :tags2 OR tags LIKE :tags3)";
			
			$query_params[":tags"] = "%$tags%";
			$query_params[":tags2"] = "%$tags2%";
			$query_params[":tags3"] = "%$tags3%";
			
			$link_to['tags'] = $tags;
			
			$contents .= OpenTable();
			$contents .= "<div calss=\"tag-title\" align=\"center\"><h1>".$tags."</h1></div>";
			$contents .= CloseTable();
			$contents = $hooks->apply_filters("post_tags", $contents);
		
			$hooks->add_filter("global_contents", function ($block_global_contents) use($tags, $main_module)
			{
				$block_global_contents['post_type'] = $main_module;
				$block_global_contents['tags'] = $tags;
				return $block_global_contents;
			}, 10);
		}
	}
	$cat_title = '';
	if ($catid > 0)
	{
		$cat_contents = '';
		$numrows_a = (isset($nuke_categories_cacheData[$main_module][$catid]) && !empty($nuke_categories_cacheData[$main_module][$catid])) ? 1:0;
		$parent_id = intval($nuke_categories_cacheData[$main_module][$catid]['parent_id']);
		
		if ($numrows_a == 0)
		{
			$cat_contents .= OpenTable();
			$cat_contents .= "<div class=\"text-center\"><font class=\"title\">".$nuke_configs['sitename']."</font><br><br>"._NOINFO4TOPIC."<br><br>[ <a href=\"".LinkToGT("index.php?modname=$main_module")."\">"._GOTONEWSINDEX."</a> | <a href=\"".LinkToGT("index.php?modname=$main_module&op=article_categories")."\">"._SELECTNEWTOPIC."</a> ]</div>";
			$cat_contents .= CloseTable();
		}
		else
		{
			$cat_contents .= OpenTable();
			$cat_title = sanitize(filter(implode("/", array_reverse(get_parent_names($catid, $nuke_categories_cacheData[$main_module], "parent_id", "catname_url"))), "nohtml"), array("/"));
			
			$attrs = array(
				"title" => "{CAT_TEXT}",
				"id" => "category-{CATID}"
			);
			$cats_link_deep = implode("/", category_link($main_module, $cat_title, $attrs));
			
			$cat_contents .= "<div class=\"text-center\"><font class=\"title\">".$nuke_configs['sitename'].": $cats_link_deep</font><br><br>
			<form action=\"".LinkToGT("index.php?modname=Search")."\" method=\"post\">
			<input type=\"hidden\" name=\"search_category\" value=\"$catid\">
			<input type=\"hidden\" name=\"search_module\" value=\"$main_module\">
			"._SEARCHONTOPIC.": <input type=\"name\" name=\"search_query\" size=\"30\">&nbsp;&nbsp;
			<input type=\"submit\" value=\""._SEARCH."\">
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>
			[ <a href=\"".LinkToGT("index.php")."\">"._GOTOHOME."</a> | <a href=\"".LinkToGT("index.php?modname=$main_module&op=article_categories")."\">"._SELECTNEWTOPIC."</a> ]</div><br />";
			$sub_cats_contents_num=0;
			$j=1;
			$sub_cats_contents = "<table width=\"100%\" border=\"0\"><tr><td align=\"center\"><b>"._SUB_CATS."</b><br /><br /></td></tr>";
			foreach($nuke_categories_cacheData[$main_module] as $key => $val)
			{
				if($val['parent_id'] == $catid)
				{
					$cat_link = sanitize(filter(implode("/", array_reverse(get_parent_names($key, $nuke_categories_cacheData[$main_module], "parent_id", "catname_url"))), "nohtml"), array("/"));
					
					if ($j==1) $sub_cats_contents .= "<tr>";
					$sub_cats_contents .= "<td width=\"100\"><a href=\"".LinkToGT("index.php?modname=$main_module&category=$cat_link")."\">".category_lang_text($val['cattext'])."</a></td>";
					if ($j==5)
					{
						$j=0;
						$sub_cats_contents .= "</tr>";
					}
					$j++;
					$sub_cats_contents_num++;
				}
			}
			$sub_cats_contents .= "</table>";
			if($sub_cats_contents_num > 0)
				$cat_contents .= $sub_cats_contents; 
			$cat_contents .= CloseTable();
		}
		$contents = $contents.$cat_contents;
		$contents = $hooks->apply_filters("post_subcats", $contents);
		$link_to['category'] = $cat_title;
		
		$hooks->add_filter("global_contents", function ($block_global_contents) use($catid, $main_module)
		{
			$block_global_contents['post_type'] = $main_module;
			$block_global_contents['cat_link'] = $catid;
			return $block_global_contents;
		}, 10);
	}
	
	$total_rows	= 0;
	
	$entries_per_page						= intval($artcle_num);
	$current_page							= (empty($page)) ? 1 : $page;
	$start_at								= intval(($current_page * $entries_per_page) - $entries_per_page);
	$query_params[':start_at'] = $start_at;
	$query_params[':entries_per_page'] = $entries_per_page;
	$article_info = array();
	
	$query_set = $hooks->apply_filters("post_index_query_set", $query_set, $main_module);
	$query_params = $hooks->apply_filters("post_index_query_params", $query_params, $main_module);
	
	article_result_parse($article_info, $query_set, $query_params, $orderby, 'index');
	
	$total_rows	= intval($article_info['total_rows']);
	unset($article_info['total_rows']);
	
	if(!empty($article_info))
	{
		foreach ($article_info as $row)
		{
			$contents = $hooks->apply_filters("posts_contents_before", $contents, $row);
			if(file_exists("themes/".$nuke_configs['ThemeSel']."/".$main_module."_index.php"))
				include("themes/".$nuke_configs['ThemeSel']."/".$main_module."_index.php");
			elseif(function_exists("".$main_module."_index"))
			{
				$module_function_name = "".$main_module."_index";
				$contents .= $module_function_name($row);
			}
			elseif(!function_exists("".$main_module."_index"))
			{
				$contents .= article_index($row);
			}
			else
				$contents .= "";
				
			$contents = $hooks->apply_filters("posts_contents_after", $contents, $row);
		}
		unset($article_info);
	}

	$link_to = $hooks->apply_filters("posts_linkto", $link_to);
	
	$_link_to = array();
	foreach($link_to as $link_to_key => $link_to_val)
	{
		$_link_to[] = $link_to_key."=".$link_to_val;
	}
	
	$_link_to = "index.php?".implode("&", $_link_to);
	
	$meta_url = $_link_to;
	if (intval($page) != 0)
	{
		$meta_url .= "&page=".intval($page)."";
	}	
	$meta_url = $hooks->apply_filters("posts_meta_url", $meta_url, $_link_to, $page);
	
	$pagination_contents = '';
	if($entries_per_page < $total_rows)
	{
		$pagination_contents .= "<div id=\"pagination\">";
		$pagination_contents .= clean_pagination($total_rows, $entries_per_page, $current_page, $_link_to);
		$pagination_contents .= "</div>";
	}
	$pagination_contents = $hooks->apply_filters("posts_paginations", $pagination_contents, $total_rows, $entries_per_page, $current_page, $_link_to);
	
	$contents .= $pagination_contents;
	
	$lastpage = ceil($total_rows/$entries_per_page);
	
	$meta_tags = array(
		"url"				=> LinkToGT($meta_url),
		"title"				=> $module_title,
		"description"		=> '',
		"keywords"			=> '',
		"prev"				=> ($page < $lastpage && intval($page) != 0) ? LinkToGT($_link_to."&page=".intval($page+1).""):"",
		"next"				=> ($page > 1 && $entries_per_page < $total_rows) ? LinkToGT($_link_to."&page=".intval($page-1).""):"",
		"extra_meta_tags"	=> ($catid > 0) ? array(
			"<link rel=\"alternate\" type=\"application/atom+xml\" title=\"Atom - $cat_title\" href=\"".LinkToGT("index.php?modname=Feed&module_link=".$nuke_configs['REQUESTURL']."")."\" />\n"
		):"",
	);
	
	$meta_tags = $hooks->apply_filters("posts_header_meta", $meta_tags, $meta_url, $page, $lastpage, $_link_to, $entries_per_page, $catid, $total_rows, $cat_title);

	$hooks->add_filter("site_breadcrumb", "posts_breadcrumb", 10);
	
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);		
	unset($meta_tags);	
		
	$main_module_box = (($main_module != 'Articles')) ? strtolower($main_module)."_index":"index";
	
	if($top_middle)
		$boxes_contents = show_modules_boxes($module_name, $main_module_box, array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
	else
		$boxes_contents = show_modules_boxes($module_name, $main_module_box, array("bottom_full", "top_full","left","right"), $contents);
		
	$boxes_contents = $hooks->apply_filters("posts_contents", $boxes_contents);

	$hooks->do_action("posts_before_show", $boxes_contents);
	
	include("header.php");
	$html_output .= $boxes_contents;
	include("footer.php");
}

function article_show($sid=0, $post_url='', $mode = '', $main_module = 'Articles')
{
	global $db, $module_name, $REQUESTURL, $nuke_configs, $userinfo, $articles_ratecookie, $visitor_ip, $pn_Cookies, $your_pass, $hooks;
	$article_info = array();
	$sid = intval($sid);

	$query_set = array();
	$query_params = array();
	
	$query_params[':visitor_ip'] = $visitor_ip;
	$query_params[':post_type'] = $main_module;
	
	if($nuke_configs['gtset'] == 1)
	{
		if($post_url == '')
			die_error("404");
			
		$query_set[] = "(s.post_url=:post_url OR s.sid=:post_url)";
		$query_params[':post_url'] = $post_url;
		$query_params[':sid'] = intval($post_url);
	}
	else
	{
		$query_set[] = "s.sid=:sid";
		$query_params[':sid'] = $sid;
	}
	
	$query_params[':status'] = 'publish';
	if(!is_admin())
	{
		$query_set[] = "s.status = :status";
	}
	
	$query_set[] = "s.post_type = :post_type";
	$query_set = $hooks->apply_filters("post_more_query_set", $query_set, $main_module);
	$query_params = $hooks->apply_filters("post_more_query_params", $query_params, $main_module);
	
	article_result_parse($article_info, $query_set, $query_params, 'time', 'more');
	
	if(isset($article_info[0]))
	{
		$article_info = $article_info[0];
		$urlop = (($mode != '') && in_array($mode, array("pdf","print","friend","report"))) ? "$mode/":"";
		if(intval($article_info['sid'] > 0))
		{
		    $true_link = trim(articleslink(intval($article_info['sid']), filter($article_info['title'], "nohtml"), filter($article_info['post_url'], "nohtml"), filter($article_info['time'], "nohtml"), intval($article_info['cat_link']), $main_module).$urlop, "/")."/";
			
			$hooks->do_action("post_url_conflict", $true_link, $REQUESTURL, $main_module);
			
			if($true_link != trim(rawurldecode($REQUESTURL), "/")."/")
			{
			    redirect_to($true_link);
				die();
			}
		}
		else
		{
			$hooks->do_action("post_url_noexist", $article_info, $REQUESTURL, $main_module);
			die_error("404");
		}
	}
	else
	{
		//check if link is related to another module
		$result = $db->table(POSTS_TABLE)
					->where('post_url',$post_url)
					->select(['sid']);
		if($result->count() == 1)
		{
			$sid = intval($result->results()[0]['sid']);
			redirect_to(LinkToGT(articleslink($sid)));
			die();
		}
		die_error("404");
	}
	
	$article_info['next_article_title']	= (intval($article_info['nsid']) != 0) ? filter($article_info['ntitle'], "nohtml"):"";
	$article_info['next_article_link']	= (intval($article_info['nsid']) != 0) ? LinkToGT(articleslink(intval($article_info['nsid']), filter($article_info['ntitle'], "nohtml"), filter($article_info['npost_url'], "nohtml"), $article_info['ntime'], intval($article_info['ncat_link']), $main_module)):"";
	$article_info['prev_article_title']	= (intval($article_info['psid']) != 0) ? filter($article_info['ptitle'], "nohtml"):"";
	$article_info['prev_article_link']	= (intval($article_info['psid']) != 0) ? LinkToGT(articleslink(intval($article_info['psid']), filter($article_info['ptitle'], "nohtml"), filter($article_info['ppost_url'], "nohtml"), $article_info['ptime'], intval($article_info['pcat_link']), $main_module)):"";

	if (empty($article_info['aid']))
		Header("Location: ".LinkToGT("index.php?modname=$main_module")."");

	$db->table(POSTS_TABLE)
		->where('sid', $article_info['sid'])
		->update([
			"counter" => ["+", 1]
		]);

	$pagetitle						= $hooks->apply_filters("post_more_pagetitle", $article_info['title'], $main_module);

	if(empty($article_info['informant']))
		$article_info['informant']	= _ANONYMOUS;

	$allow_to_view					= false;
	$disallow_message				= "";

	$permission_result				= phpnuke_permissions_check($article_info['permissions']);

	$allow_to_view					= $permission_result[0];
	$disallow_message				= $permission_result[1];

	$allow_to_view					= $hooks->apply_filters("post_more_allow_to_view", $allow_to_view, $main_module);
	if (!$allow_to_view)
	{
		$article_info['bodytext']		= "<div class=\"text-center\">";
		$article_info['bodytext']		.="$disallow_message<br><br>";
		$article_info['bodytext']		.= _GOBACK;
		$article_info['bodytext']		.= "</div>";
	}

	$this_article_pass = $pn_Cookies->get("this_article_pass".$article_info['sid']);
	$this_article_pass = intval($this_article_pass);

	$this_article_pass = $hooks->apply_filters("post_more_article_pass", $this_article_pass, $main_module);
	
	if($article_info['post_pass'] != "" && $this_article_pass != "1" && !is_admin())
	{
		$your_pass = (isset($your_pass)) ? filter($your_pass, "nohtml"):'';

		if($your_pass == '')
		{
			$article_info['bodytext']= "<div class=\"text-center\">";
			$article_info['bodytext'].=""._REQUIRED_PASS."<br><br><form action=\"".LinkToGT($article_info['article_link'])."\" method=\"post\">";
			$article_info['bodytext'].=""._ENTER_PASSWORD." &nbsp;<input type=\"password\" name=\"your_pass\" /> &nbsp;<input type=\"submit\" value=\""._SEND."\" /><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> </form>";
			$article_info['bodytext'].= "</div>";
			$ending = 1;
		}
		elseif($your_pass != '' && $article_info['post_pass'] != md5($your_pass))
		{
			$article_info['bodytext']= "<div class=\"text-center\">";
			$article_info['bodytext'].=""._WRONG_PASS."<br><br><form action=\"".LinkToGT($article_info['article_link'])."\" method=\"post\">";
			$article_info['bodytext'].=""._ENTER_PASSWORD." &nbsp;<input type=\"password\" name=\"your_pass\" /> &nbsp;<input type=\"submit\" value=\""._SEND."\" /><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> </form>";
			$article_info['bodytext'].= "</div>";
			$ending = 1;
		}
		elseif($your_pass != '' && $article_info['post_pass'] == md5($your_pass))
		{
			$pn_Cookies->set("this_article_pass".$article_info['sid'],"1",3600);
			Header("Location: ".LinkToGT($article_info['article_link'])."");
		}
	}

	if($nuke_configs['gtset'] == 1)
	{
		
		$article_info['print_link'] = $article_info['article_link']."print/";
		$article_info['pdf_link'] = $article_info['article_link']."pdf/";
		$article_info['friend_link'] = $article_info['article_link']."friend/";
		$article_info['report_link'] = $article_info['article_link']."report/";
	}
	else
	{
		$article_info['print_link'] = "index.php?modname=$main_module&op=article_show&mode=print&sid=".$article_info['sid']."";
		$article_info['pdf_link'] = "index.php?modname=$main_module&op=article_show&mode=pdf&sid=".$article_info['sid']."";
		$article_info['friend_link'] = "index.php?modname=$main_module&op=article_show&mode=friend&sid=".$article_info['sid']."";
		$article_info['report_link'] = "index.php?modname=$main_module&op=article_show&mode=report&sid=".$article_info['sid']."";
	}
	
	$article_info = $hooks->apply_filters("post_more_article_info", $article_info);
	
	$hooks->add_filter("site_breadcrumb", "posts_breadcrumb", 10);
	$hooks->add_filter("microdata_json", "Articles_microdata_json", 10);
	
	$meta_tags = array(
		"url" => $article_info['article_link'],
		"title" => $article_info['title'],
		"description" => str_replace(array("\r","\n","\t"), "", strip_tags($article_info['hometext'])),
		"keywords" => $article_info['tags'],
		"prev" => $article_info['prev_article_link'],
		"next" => $article_info['next_article_link'],
		"extra_meta_tags" => array()
	);
	$meta_tags = $hooks->apply_filters("post_more_meta_tags", $meta_tags, $article_info);
	
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);
	unset($meta_tags);
	
	switch($mode)
	{
		default:
			$hooks->add_filter("global_contents", function ($block_global_contents) use($article_info, $main_module)
			{
				$block_global_contents = $article_info;
				$block_global_contents['post_id'] = $article_info['sid'];
				$block_global_contents['post_title'] = $article_info['title'];
				$block_global_contents['module_name'] = $main_module;
				$block_global_contents['allow_comments'] = $article_info['allow_comment'];
				$block_global_contents['module_boxes'] = (isset($article_info['post_module_boxes'])) ? $article_info['post_module_boxes']:"";
				$block_global_contents['db_table'] = POSTS_TABLE;
				$block_global_contents['db_id'] = 'sid';
				return $block_global_contents;
			}, 10);			
			
			$contents = '';
			if(file_exists("themes/".$nuke_configs['ThemeSel']."/".$article_info['post_type']."_more.php"))
				include("themes/".$nuke_configs['ThemeSel']."/".$article_info['post_type']."_more.php");
			elseif(function_exists("".$article_info['post_type']."_more"))
			{
				$module_func_name = "".$article_info['post_type']."_more";
				$contents .= $module_func_name($article_info);
			}
			elseif(!function_exists("".$article_info['post_type']."_more"))
			{
				$contents .= article_more($article_info);
			}
			else 
				$contents .= "";
				
			$main_module_box = (($main_module != 'Articles')) ? strtolower($main_module)."_more":"more";
			$boxes_contents = show_modules_boxes($module_name, $main_module_box, array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
			$boxes_contents = $hooks->apply_filters("post_more_boxes_contents", $boxes_contents);

			$hooks->do_action("post_more_before_show", $boxes_contents);
			
			include("header.php");
		
			unset($article_info);
			
			$html_output .= $boxes_contents;
			include ("footer.php");
		break;
		
		case"friend":
		case"report":
			$hooks->do_action("post_report_friend_before", $article_info);
			header("X-Robots-Tag: noindex, nofollow", true);
			report_friend_form(false, $mode, $article_info['sid'], $article_info['title'], $main_module, '', '', $article_info['article_link'], '', '');
			die();
		break;
		
		case"pdf":
			$hooks->do_action("post_pdf_before", $article_info);
			header("X-Robots-Tag: noindex, nofollow", true);
			pdf_generate($article_info['aid'], $article_info['tags'], $article_info['title'], $article_info['title'], $article_info['datetime'], $article_info['hometext']."<br /><br />".$article_info['bodytext'], $article_info['article_link']);
			die();
		break;
		
		case"print":
			$hooks->do_action("post_print_before", $article_info);		
			print_theme($pagetitle, $article_info);
			die();
		break;
	}
}

function article_archive($year = 0, $month = 0, $month_l = '', $mode = '', $main_module = 'Articles')
{
	global $userinfo, $db, $user, $page, $module_name, $nuke_configs, $hooks;
	
	$contents = '';

	$page = isset($page) ? intval($page):0;
	$year = (isset($year) && strlen($year) == 4) ? intval($year):0;
	$month = (isset($month) && strlen($month) > 0 && strlen($month) < 3) ? intval($month):0;
	$month_l = isset($month_l) ? str_replace("-", " ", filter($month_l, "nohtml")):'';
	
	$month_names = ($nuke_configs['datetype'] == 1) ? "j_month_name":(($nuke_configs['datetype'] == 2) ? "h_month_name":"g_month_name");
	
	$month_l = str_replace(" ","-", $nuke_configs[$month_names][$month]);
	
	$link = "index.php?modname=$main_module&op=article_archive".
	(($year != 0) ? "&year=$year":"").
	(($month != 0) ? "&month=$month":"").
	(($month_l != '') ? "&month_l=$month_l":"").
	(($page != 0) ? "&page=$page":"");
	
	if(trim(LinkToGT($link), "/")."/" != trim(rawurldecode(LinkToGT($nuke_configs['REQUESTURL'])), "/")."/")
		die_error("404");
		
	$where_between = array();
	
	if($mode != 'all' && $year == 0 && $month == 0)
		$contents .= article_select_month(1);
	
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
		
		$query_set['time']		= "s.time BETWEEN '$currenttime' AND '$nexttime'";
	}
	
	$entries_per_page			= 20;
	$current_page				= (empty($page)) ? 1 : $page;
	$start_at					= ($current_page * $entries_per_page) - $entries_per_page;
	$link_to					= ($year != 0 && $month != 0 && $month_l != '') ? "index.php?modname=$main_module&op=article_archive&year=$year&month=$month&month_l=$month_l":"index.php?modname=$main_module&op=article_archive";
					
	if(!is_admin())
		$query_set['status']	= "s.status = 'publish'";
		
	$query_set['post_type'] = "(s.post_type = '$main_module' OR s.post_type = '')";
	$query_set['alanguage']		= "";
	
	if ($nuke_configs['multilingual'] == 1)
		$query_set['alanguage']	= "(s.alanguage='".$nuke_configs['currentlang']."' OR s.alanguage='')";
	
	$query_set					= implode(" AND ", array_filter($query_set));
	$query_set					= ($query_set != "") ? "WHERE $query_set":"";
	
    $result						= $db->query("
	SELECT s.sid, s.title, s.time, s.post_url, s.comments, s.counter, s.alanguage, s.cat_link, s.score, s.ratings, s.status, 
	(SELECT COUNT(s2.sid) FROM ".POSTS_TABLE." as s2 ".str_replace("s.","s2.", $query_set).") as total_rows 
	FROM ".POSTS_TABLE." AS s 
	$query_set 
	GROUP BY s.sid 
	ORDER BY s.time DESC, s.sid DESC LIMIT $start_at, $entries_per_page");
	
	$contents					.="
	<div class=\"table-responsive\">
	<table border=\"0\" width=\"100%\" class=\"table-striped table-hover table-condensed\">
		<tr>
			<th align=\"right\"><b>"._ARTICLES."</b></th>
			<th align=\"center\"><b>"._COMMENTS."</b></th>
			<th align=\"center\"><b>"._READS."</b></th>
			<th align=\"center\"><b>"._USCORE."</b></th>
			<th align=\"center\"><b>"._DATE."</b></th>
			<th align=\"center\"><b>"._OPERATION."</b></th>
		</tr>";
	if(!empty($result))
	{
		foreach ($result as $row)
		{
			$total_rows				= intval($row['total_rows']);
			$sid					= intval($row['sid']);
			$title					= filter($row['title'], "nohtml");
			$time					= $row['time'];
			$post_url				= filter($row['post_url'], "nohtml");
			$comments				= intval($row['comments']);
			$counter				= intval($row['counter']);
			$alanguage				= $row['alanguage'];
			$cat_link				= intval($row['cat_link']);
			$score					= intval($row['score']);
			$ratings				= intval($row['ratings']);
			$article_link			= LinkToGT(articleslink($sid, $title, $post_url, $time, $cat_link, $main_module));
			$time					= nuketimes($time);

			$this_status			= filter($row['status'], "nohtml");	

			switch($this_status)
			{
				case"future":
					$this_post_status = " ("._PUBLISH_IN_FUTURE.")";
				break;
				case"draft":
					$this_post_status = " ("._DRAFT.")";
				break;
				case"pending":
					$this_post_status = " ("._PENDING_POST.")";
				break;
				default:
					$this_post_status = "";
				break;
			}
					
			if($nuke_configs['gtset'] == 1)
			{
				
				$print_link			= $article_link."print/";
				$pdf_link			= $article_link."pdf/";
				$friend_link		= $article_link."friend/";
			}
			else
			{
				$print_link			= "index.php?modname=$main_module&op=article_show&mode=print&sid=$sid";
				$pdf_link			= "index.php?modname=$main_module&op=article_show&mode=pdf&sid=$sid";
				$friend_link		= "index.php?modname=$main_module&op=article_show&mode=friend&sid=$sid";
			}
			
			$actions				= "<a href=\"$print_link\" title=\""._PRINT."\"><i class=\"fa fa-print\"></i></a>&nbsp;";
			$actions				.= "<a href=\"$pdf_link\" title=\""._PDFFILE."><i class=\"fa fa-pdf\"></i></a>&nbsp;";
			if(is_user())
			{
				$actions			.= "<a href=\"$friend_link\" class=\"thickbox\" title=\""._SEND_POST_TO_FRIEND."\"><i class=\"fa fa-email\"></i></a>";
			}
			if ($score != 0)
			{
				$rated				= substr($score / $ratings, 0, 4);
			}
			else
			{
				$rated = 0;
			}
			$title					= "<a href=\"$article_link\">$title</a>";
			if ($nuke_configs['multilingual'] == 1)
			{
				if (empty($alanguage))
				{
					$alanguage		= $nuke_configs['language'];
				}
				$alt_language		= ucfirst($alanguage);
				$lang_img			= "<img src=\"".$nuke_configs['nukecdnurl']."images/language/flag-$alanguage.png\" border=\"0\" hspace=\"2\" alt=\"$alt_language\" title=\"$alt_language\">";
			}
			else
			{
				$lang_img			= "<strong><big><b>&middot;</b></big></strong>";
			}
			$contents .="<tr>
				<td align=\"right\">$lang_img $title$this_post_status</td>
				<td align=\"center\">$comments</td>
				<td align=\"center\">$counter</td>
				<td align=\"center\">$rated</td>
				<td align=\"center\">$time</td>
				<td align=\"center\">$actions</td>
			</tr>";
		}
	}
	$contents .="
		<tr>
			<td valign=\"top\" align=\"center\" colspan=\"6\">
			<div id=\"pagination\" class=\"pagination\">";
			$contents			.= clean_pagination($total_rows, $entries_per_page, $current_page, $link_to);
			$contents			.="</div>
			</td>
		</tr>
	</table>
	</div>
	<br><br><hr size=\"1\" noshade>";
	$contents					.= article_select_month(1);
	$contents					.="<div align=\"center\">
	[ <a href=\"".LinkToGT("index.php?modname=$main_module&op=article_select_month")."\">"._ARCHIVESINDEX."</a>".(($mode !== 'all') ? " | <a href=\"".LinkToGT("index.php?modname=$main_module&op=article_archive")."\">"._SHOWALLSTORIES."</a>":"")." ]</div>";
	
	$meta_url				= $link_to;
	if (intval($page) != 0)
	{
		$meta_url				.= "&page=".intval($page)."";
	}
	$meta_url = $hooks->apply_filters("posts_archive_meta_url", $meta_url, $link_to, $page);
	
	$next_link					= '';
	$prev_link					= '';
	
	$lastpage					= ceil($total_rows/$entries_per_page);
	
	if($page < $lastpage && $page != 0)
		$next_link				= LinkToGT($link_to."&page=".intval($page+1)."");
	
	if($page > 1 && $entries_per_page < $total_rows)
		$prev_link				= LinkToGT($link_to."&page=".intval($page-1)."");

	$meta_tags = array(
		"url" 					=> $meta_url,
		"title" 				=> _STORIESARCHIVE."".(($month_l != '') ? " - $month_l $year":""),
		"description" 			=> '',
		"keywords" 				=> '',
		"prev" 					=> $prev_link,
		"next" 					=> $next_link,
		"extra_meta_tags" 		=> array(
			"<link rel=\"alternate\" type=\"application/atom+xml\" title=\"Atom - "._STORIESARCHIVE." - ".(($month_l != '') ? " - $month_l $year":"")."\" href=\"".LinkToGT("index.php?modname=Feed&module_link=".$nuke_configs['REQUESTURL']."")."\" />\n"
		)
		
	);
	$meta_url = $hooks->apply_filters("posts_archive_meta_tags", $meta_tags, $prev_link, $next_link, $meta_url, $month_l, $year);
	
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);	
	
	$breadcrumb_title = ($mode == 'all') ? 	_SHOWALLSTORIES:("$month_l $year");
	
	$hooks->add_filter("site_breadcrumb", function($breadcrumbs, $block_global_contents) use($main_module, $meta_tags, $breadcrumb_title){
		$breadcrumbs['archive_main'] = array(
			"name" => _STORIESARCHIVE,
			"link" => LinkToGT("index.php?modname=".$main_module."&op=article_select_month"),
			"itemtype" => "WebPage"
		);
		
		$breadcrumbs['archive_path'] = array(
			"name" => str_replace("-", " ", $breadcrumb_title),
			"link" => $meta_tags['url'],
			"itemtype" => "WebPage"
		);
		return $breadcrumbs;
	}, 10);
	unset($meta_tags);
	
	include("header.php");
	$output = '';
	$output .= title($nuke_configs['sitename']." : "._STORIESARCHIVE);
	
	if($month_l != '')
		$output .= title("$month_l $year");
		
	$output .= OpenTable();
	$output .= $contents;
	$output .= CloseTable();
	$output = $hooks->apply_filters("post_archive_contents", $output);
	$html_output .= show_modules_boxes($module_name, "archive", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $output);
	include("footer.php");
}

function article_select_month($in = 0)
{
	_article_select_month($in);
}

function article_categories($main_module = 'Articles')
{
	global $userinfo, $db, $user, $page, $module_name, $nuke_configs, $hooks;
	
	$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
	$contents 								= "";
	$contents 								.= OpenTable();
	
	$sub_cats_contents_num 					= 0;
	$array_contents 						= array();
	foreach($nuke_categories_cacheData[$main_module] as $key => $val)
	{
		if($val['parent_id'] == 0)
		{
			$cat_link						= filter($val['catname_url'], "nohtml");
			$array_contents[$key] = array($val, $cat_link, LinkToGT("index.php?modname=".$main_module."&category=$cat_link"));
			$sub_cats_contents_num++;
		}
	}
	if($sub_cats_contents_num > 0)
	{
		if(file_exists("themes/".$nuke_configs['ThemeSel']."/".$main_module."_categories.php"))
			include("themes/".$nuke_configs['ThemeSel']."/".$main_module."_categories.php");
		elseif(function_exists("".$main_module."_categories_html"))
		{
			$module_func_name = "".$main_module."_categories_html";
			$contents .= $module_func_name($array_contents);
		}
		else
		{
			$j 								= 1;
			$contents .= "<table width=\"100%\" border=\"0\">";
			foreach($array_contents as $key => $val)
			{
				if($val[0]['type'] == 1) continue;
				if ($j==1) $contents	.= "<tr>";
				$contents				.= "<td width=\"100\"><a href=\"".$val[2]."\">".category_lang_text($val[0]['cattext'])."</a></td>";
				if ($j==5)
				{
					$j=0;
					$contents			.= "</tr>";
				}
				$j++;
			}
			$contents .= "</table>";
		}
	}
	$contents								.= CloseTable();
		
	$meta_tags = array(
		"url" 								=> LinkToGT("index.php?modname=$main_module&op=article_categories"),
		"title" 							=> _ARTICLES_CATEGORIES,
		"description" 						=> '',
		"keywords" 							=> '',
		"prev" 								=> '',
		"next" 								=> '',
		"extra_meta_tags" 					=> array()
	);
	$meta_tags = $hooks->apply_filters("post_categories_meta_tags", $meta_tags, $main_module);
	
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);		
	unset($meta_tags);
	
	$hooks->add_filter("site_breadcrumb", function($breadcrumbs, $block_global_contents) use($main_module){
		$breadcrumbs['category'] = array(
			"name" => _CATEGORIES,
			"link" => LinkToGT("index.php?modname=".$main_module."&op=article_categories"),
			"itemtype" => "WebPage"
		);
		return $breadcrumbs;
	}, 10);
	
	$contents = $hooks->apply_filters("post_categories_contents", $contents);
	include("header.php");
	$html_output .= show_modules_boxes($module_name, "category", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), title($nuke_configs['sitename']." : "._ARTICLES_CATEGORIES."").$contents);
	include("footer.php");
}

function send_article($preview = '', $submit = '', $article_fields = array(), $security_code = '', $security_code_id = '', $main_module = 'Articles')
{
	global $db, $userinfo, $nuke_configs, $module_name, $PnValidator, $visitor_ip, $hooks;
	
	$finish = false;
	$contents = '';
	header("X-Robots-Tag: noindex, nofollow", true);
	$contents .= OpenTable();
	$contents .= "<div align = 'center'><font class=\"title\"><b>"._SEND_POST."</b></div></font>";
	$contents .= CloseTable();
	$contents .= "<br>";
	$contents .= info_box("caution", _SUBMITADVICE);
	$contents .= "<br>";
	
	$username = (isset($userinfo['username'])) ? $userinfo['username']:$nuke_configs['anonymous'];
	$languageslist = get_dir_list('language', 'files');
	foreach($languageslist as $key => $val)
	{
		if($val == 'index.html' || $val == '.htaccess' || $val == 'alphabets.php')
		{
			unset($languageslist[$key]);
			continue;
		}
		$languageslist[$key] = str_replace(".php", "", $val);
	}
	
	if(isset($article_fields) && is_array($article_fields) && !empty($article_fields))
	{
		$PnValidator->add_validator("in_languages", function($field, $input, $param = NULL) {
			$param = explode("-", $param);
			return in_array($input[$field], $param);
		}); 

		$PnValidator->validation_rules(array(
			'title'		=> 'required',
			'alanguage'	=> 'required|alpha|in_languages,'.implode("-",$languageslist).'',
			'hometext'	=> 'required',
			'post_type'	=> 'required'
		)); 
		// Get or set the filtering rules
		$PnValidator->filter_rules(array(
			'title'		=> 'sanitize_string',
			'alanguage'	=> 'sanitize_string',
			'post_type'	=> 'addslashes',
			'hometext'	=> 'addslashes',
			'bodytext'	=> 'addslashes'
		)); 

		$article_fields = $PnValidator->sanitize($article_fields, array('title','alanguage'), true, true);
		$validated_data = $PnValidator->run($article_fields);
		
		if($validated_data !== FALSE)
		{
			$article_fields = $validated_data;

		
			$title = filter($article_fields['title'], "nohtml");
			$alanguage = filter($article_fields['alanguage'], "nohtml");
			$hometext = $article_fields['hometext'];
			$bodytext = $article_fields['bodytext'];
			$cats = $article_fields['cats'];
			
			if(isset($submit) && $submit == _OK)
			{
				$code_accepted = false;
				
				if(extension_loaded("gd") && in_array("send_post", $nuke_configs['mtsn_gfx_chk']))
					$code_accepted = code_check($security_code, $security_code_id);
				else
					$code_accepted = true;
					
				if($code_accepted)
				{
					$article_fields['ip'] = $visitor_ip;
					$article_fields['status'] = 'pending';
					$article_fields['time'] = _NOWTIME;
					$article_fields['cat'] = (isset($article_fields['cats']) && !empty($article_fields['cats'])) ? implode(",",$article_fields['cats']):"";
					unset($article_fields['cats']);
					$article_fields['informant'] = (is_user()) ? $userinfo['username']:$nuke_configs['anonymous'];

					$db->table(POSTS_TABLE)
						->insert($article_fields);
					
					$contents .= OpenTable();
					$contents .= "<div class=\"text-center\"><font class=\"title\">"._POST_SENT."</font><br><br>"
					."<font class=\"content\"><b>"._POST_SENT_THANKS."</b><br><br>"
					.""._POST_SENT_DESCRIPTION."</div>";
					$contents .= CloseTable();
					$finish = true;
				}
				else
				{
					$contents .= OpenTable();
					$contents .= "<p align=\"center\">"._BADSECURITYCODE."<br />"._GOBACK."</p>";
					$contents .= CloseTable();
					$finish = true;
				}	
			}			
		}
		else
		{
			$contents .= OpenTable();
			$contents .= "<p align=\"center\">".$PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br />')."</p>";
			$contents .= CloseTable();
			$finish = true;
		}
	}
	else
	{
		$title = '';
		$alanguage = '';
		$hometext = '';
		$bodytext = '';
		$cats = 0;
	}

	if(!$finish)
	{
		$list_config = array(
			'checked_list'	=> ((isset($article_fields['cats']) && is_array($article_fields['cats']) && !empty($article_fields['cats'])) ? $article_fields['cats']:array()),
			'has_input'		=> true,
			'input_type'	=> 'checkbox',
			'input_name'	=> 'article_fields[cats][]',
			'var_pid'		=> 'parent_id',
			'class_name'	=> '',
			'var_id'		=> 'catid-{ID}',
			'var_value'		=> 'catname'
		);
		
		$all_cats = (!empty($nuke_categories_cacheData[$main_module])) ? get_sub_lists($nuke_categories_cacheData[$main_module], $nuke_categories_cacheData[$main_module], 0, $list_config):"";
		
		$contents .= OpenTable();
		$contents .= "
		<form action=\"".LinkToGT("index.php?modname=$module_name&op=send_article")."\" method=\"post\" id=\"article_form\">
		<table widht=\"100%\">
			<tr>
				<th>"._NAME.":</th>
				<td>";
				if (is_user())
				{
					$contents .= "<a href=\"".LinkToGT("index.php?modname=Users")."\">$username</a> <font class=\"content\">[ <a href=\"index.php?modname=Users&amp;op=logout\">"._LOGOUT."</a> ]</font>";
				}
				else
				{
					$contents .= "".$nuke_configs['anonymous']." <font class=\"content\">[ <a href=\"".LinkToGT("index.php?modname=Users&op=sign_up")."\">"._NEWUSER."</a> ]</font>";
				}
				$contents .= "</td>
			</tr>
			<tr>
			<th>"._TITLE.":</th>
				<td>
					<input type=\"text\" name=\"article_fields[title]\" value=\"$title\" maxlength=\"80\" required>
					<br>("._BEDESCRIPTIVE.")
				</td>
			</tr>
			<tr>
				<th>"._CATEGORY.":</th>
				<td>
					<div style=\"max-height:200px;width:95%;overflow:auto;line-height:32px;background:#efefef;border-radius:5px;padding:8px;\">
						<ul id=\"checkbox_tree\">$all_cats</ul>
					</div>
				</td>
			</tr>";
			if ($nuke_configs['multilingual'] == 1)
			{
				$contents .= "
				<tr>
					<th>"._LANGUAGE.":</th>
					<td>
						<select name=\"article_fields[alanguage]\">";
						foreach($languageslist as $language_name)
						{
							$sel = ($alanguage != '' && $language_name == $alanguage) ? "selected":(($alanguage == '' && $language_name == $nuke_configs['language']) ? "selected":"");
							$contents .= "<option value=\"$language_name\" $sel>".ucfirst($language_name)."</option>\n";
						}
					$contents .= "</select>
					</td>
				</tr>";
			}
			else
			{
				$contents .= "<input type=\"hidden\" name=\"article_fields[alanguage]\" value=\"$language\"></td></tr></table>";
			}
			$contents .= "
			<tr>
				<th>"._HOMETEXT.":</th>
				<td>";
					$contents .= wysiwyg_textarea('article_fields[hometext]', stripslashes($hometext), 'PHPNukeUser', '50', '12');
				$contents .= "
				</td>
			</tr>
			<tr>
				<th>"._BODYTEXT.":</th>
				<td>";
					$contents .= wysiwyg_textarea('article_fields[bodytext]', stripslashes($bodytext), 'PHPNukeUser', '50', '12');
					$contents .="<br /><font class=\"content\">("._AREYOUSURE.")
				</td>
			</tr>";
			if(isset($preview) && $preview == _PREVIEW)
			{
				if(extension_loaded("gd") && in_array("send_post", $nuke_configs['mtsn_gfx_chk']))
				{
					$security_code_input = makePass("_send_article");
					$contents .= "
					<tr>
						<th>"._SECCODE.":</th>
						<td>".$security_code_input['image']."<br /><br />".$security_code_input['input']."</td>
					</tr>";
				}
			}		
			$contents .= "
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type=\"submit\" name=\"preview\" value=\""._PREVIEW."\">
					&nbsp;&nbsp; 
					".((isset($preview) && $preview == _PREVIEW) ? "<input type=\"submit\" name=\"submit\" value=\""._OK."\">":"&nbsp;")." &nbsp; ("._SUBPREVIEW.")
				</td>
			</tr>
		</table>
		<input type=\"hidden\" name=\"op\" value=\"send_article\">
		<input type=\"hidden\" name=\"main_module\" value=\"$main_module\">
		<input type=\"hidden\" name=\"article_fields[post_type]\" value=\"$main_module\">
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>
		<script>
			$(document).ready(function(){
				$(\"#article_form\").validate();
			});
			$('#checkbox_tree').checktree();
		</script>";
		$contents .= CloseTable();	
	}
	
	$hooks->add_filter("site_theme_headers", function ($theme_setup) use($nuke_configs)
	{
		$theme_setup = array_merge_recursive($theme_setup, array(
			"default_css" => array(
				"<link href=\"".INCLUDE_PATH."/Ajax/jquery/jquery-checktree.css\" rel=\"stylesheet\" type=\"text/css\">"
			),
			"defer_js" => array(
				"<script src=\"".INCLUDE_PATH."/Ajax/jquery/jquery-checktree.js\"></script>"
			)
		));
		return $theme_setup;
	}, 10);
	
	$meta_tags = array(
		"url" => LinkToGT("index.php?modname=$main_module&op=send_article"),
		"title" => _SEND_POST,
		"description" => _SEND_POST_DESCRIPTION,
		"keywords" => '',
		"extra_meta_tags" => array()
	);
	$meta_tags = $hooks->apply_filters("post_categories_meta_tags", $meta_tags, $main_module);
	
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);		
	unset($meta_tags);
	
	$hooks->add_filter("site_breadcrumb", function($breadcrumbs, $block_global_contents) use($main_module){
		$breadcrumbs['send-article'] = array(
			"name" => _SEND_POST,
			"link" => LinkToGT("index.php?modname=$main_module&op=send_article"),
			"itemtype" => "WebPage"
		);
		return $breadcrumbs;
	}, 10);
	
	include ("header.php");
	$html_output .= show_modules_boxes($module_name, "send_article", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
	include ('footer.php');
}

$op 				= isset($op) ? filter($op, "nohtml") : "articles_home";
$tags 				= isset($tags) ? filter($tags, "nohtml") : "";
$category 			= isset($category) ? filter($category, "nohtml") : "";
$orderby 			= isset($orderby) ? filter($orderby, "nohtml") : "DESC";
$sid				= (isset($sid) && intval($sid) > 0) ? intval($sid) : 0;
$in					= (isset($in) && intval($in) > 0) ? intval($in) : 0;
$year				= (isset($year) && intval($year) > 0) ? intval($year) : 0;
$month				= (isset($month) && intval($month) > 0) ? intval($month) : 0;
$month_l			= isset($month_l)? filter($month_l, "nohtml") : "";
$mode				= isset($mode)? filter($mode, "nohtml") : "";
$post_url			= isset($post_url)? filter($post_url, "nohtml") : "";
$article_fields		= request_var('article_fields', array(), "_POST");
$preview			= isset($preview)? filter($preview, "nohtml") : "";
$submit				= filter(request_var('submit', '', "_POST"), "nohtml");
$security_code		= filter(request_var('security_code', '', "_POST"), "nohtml");
$security_code_id	= filter(request_var('security_code_id', '', "_POST"), "nohtml");
$main_module		= isset($main_module)? filter($main_module, "nohtml") : "Articles";
	
if($year == 0 && $month == 0 && $mode != 'all' && $op == 'article_archive')
	$op = "article_select_month";

$hooks->do_action("post_operations", $main_module);

switch ($op)
{
	case "article_archive":
		article_archive($year, $month, $month_l, $mode, $main_module);
	break;
	case"article_select_month":
		article_select_month(0);
	break;
	case"article_categories":
		article_categories($main_module);
	break;
	case"send_article":
		send_article($preview, $submit, $article_fields, $security_code, $security_code_id, $main_module);
	break;
	case"article_show":
		article_show($sid, $post_url, $mode, $main_module);
	break;
	default:
	articles_home($category, $tags, $orderby, $main_module);
	break;
}

?>