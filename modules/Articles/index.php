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
				<a href=\"".$article_info['report_link']."\" data-toggle=\"modal\" data-target=\"#sitemodal\">"._POST_REPORT."</a>
				<a href=\"".$article_info['friend_link']."\" data-toggle=\"modal\" data-target=\"#sitemodal\">"._INTRODUCE_TO_FRIENDS."</a>
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

function articles_home($category='', $tags='', $orderby = '')
{
	global $db, $userinfo, $page, $module_name, $visitor_ip, $nuke_articles_categories_cacheData, $nuke_configs, $articles_votetype, $nuke_modules_cacheData, $nuke_authors_cacheData;
	$link_to = "index.php?modname=$module_name";

	$top_middle = ((isset($tags) && $tags != '') || (isset($page) && intval($page) != 0) || (isset($category) && $category != '')) ? false:true;

	$nuke_modules_cacheData_by_title = phpnuke_array_change_key($nuke_modules_cacheData, "mid", "title");
	
	$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
	$catid = get_category_id($module_name, $category, $nuke_categories_cacheData);	
	
	$catid = intval($catid);
	if($catid < 0)
		header("location: ".LinkToGT("index.php")."");

	if ($nuke_configs['multilingual'] == 1)
	{
		$module_titles = $lang_titles = ($nuke_modules_cacheData_by_title[$module_name]['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($nuke_modules_cacheData_by_title[$module_name]['lang_titles'])):"";
		
		$module_title = (($catid > 0) ? $category." - ":"").$module_titles[$nuke_configs['currentlang']];
	}
	else
		$module_title = (($catid > 0) ? $category." - ":"").$nuke_modules_cacheData_by_title[$module_name]['title'];
	
	$db->table(POSTS_TABLE)
		->where('post_type', 'article')
		->where('status', 'future')
		->where('time', '<',  _NOWTIME)
		->update([
			'status' => 'publish'
		]);
		
	
	$votetype = ($articles_votetype) ? $articles_votetype:$nuke_configs['votetype'];
	
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
	
	if(!is_admin())
		$query_set['status'] = "s.status = 'publish'";
		
	$query_set['alanguage'] = "";
	$query_set['ihome'] = "";
	$query_set['cat'] = array();
	$query_set['post_type'] = "(s.post_type = 'article' OR s.post_type = '')";
	
	if ($nuke_configs['multilingual'] == 1)
	{
		$query_set['alanguage'] = "(s.alanguage='".$nuke_configs['currentlang']."' OR s.alanguage='')";
		
		$query_set['ihome'] = "s.ihome = '1'";
	}
	
	$all_sub_cats = array();

	if ($catid > 0)
	{
		unset($query_set['ihome']);

		$all_sub_cats = array_unique(get_sub_categories_id($module_name, $catid, $nuke_categories_cacheData, array($catid)));

		$c=1;
		foreach($all_sub_cats as $sub_cat)
		{
			$query_set['cat'][] = "FIND_IN_SET(:cat_$c, s.cat)";
			$query_params[":cat_$c"] = $sub_cat;
			$c++;
		}
		$query_set['cat']= "(".implode(" OR ", $query_set['cat']).") OR s.cat_link = :cat_link";
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
			
			$link_to .= "&tags=$tags";
			
			$contents .= OpenTable();
			$contents .= "<div align=\"center\"><h1>".$tags."</h1></div>";
			$contents .= CloseTable();
		}
	}
	
	if ($catid > 0)
	{
		$numrows_a = (isset($nuke_articles_categories_cacheData[$catid]) && !empty($nuke_articles_categories_cacheData[$catid])) ? 1:0;
		$parent_id = intval($nuke_articles_categories_cacheData[$catid]['parent_id']);
		
		if ($numrows_a == 0)
		{
			$contents .= OpenTable();
			$contents .= "<div class=\"text-center\"><font class=\"title\">".$nuke_configs['sitename']."</font><br><br>"._NOINFO4TOPIC."<br><br>[ <a href=\"".LinkToGT("index.php?modname=$module_name")."\">"._GOTONEWSINDEX."</a> | <a href=\"".LinkToGT("index.php?modname=Articles&op=article_categories")."\">"._SELECTNEWTOPIC."</a> ]</div>";
			$contents .= CloseTable();
		}
		else
		{
			$contents .= OpenTable();
			$cat_title = sanitize(filter(implode("/", array_reverse(get_parent_names($catid, $nuke_articles_categories_cacheData, "parent_id", "catname_url"))), "nohtml"), array("/"));
			
			$attrs = array(
				"title" => "{CAT_TEXT}",
				"id" => "category-{CATID}"
			);
			$cats_link_deep = implode("/", category_link($module_name, $cat_title, $attrs));
			
			$contents .= "<div class=\"text-center\"><font class=\"title\">".$nuke_configs['sitename'].": $cats_link_deep</font><br><br>
			<form action=\"".LinkToGT("index.php?modname=Search")."\" method=\"post\">
			<input type=\"hidden\" name=\"search_category\" value=\"$catid\">
			"._SEARCHONTOPIC.": <input type=\"name\" name=\"search_query\" size=\"30\">&nbsp;&nbsp;
			<input type=\"submit\" value=\""._SEARCH."\">
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>
			[ <a href=\"".LinkToGT("index.php")."\">"._GOTOHOME."</a> | <a href=\"".LinkToGT("index.php?modname=Articles&op=article_categories")."\">"._SELECTNEWTOPIC."</a> ]</div><br />";
			$sub_cats_contents_num=0;
			$j=1;
			$sub_cats_contents = "<table width=\"100%\" border=\"0\"><tr><td align=\"center\"><b>"._SUB_CATS."</b><br /><br /></td></tr>";
			foreach($nuke_articles_categories_cacheData as $key => $val)
			{
				if($val['parent_id'] == $catid)
				{
					$cat_link = sanitize(filter(implode("/", array_reverse(get_parent_names($key, $nuke_articles_categories_cacheData, "parent_id", "catname_url"))), "nohtml"), array("/"));
					
					if ($j==1) $sub_cats_contents .= "<tr>";
					$sub_cats_contents .= "<td width=\"100\"><a href=\"".LinkToGT("index.php?modname=Articles&category=$cat_link")."\">".category_lang_text($val['cattext'])."</a></td>";
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
				$contents .= $sub_cats_contents; 
			$contents .= CloseTable();
		}
		$link_to .= "&category=$cat_title";
	}
	
	$total_rows	= 0;
	
	$entries_per_page						= intval($artcle_num);
	$current_page							= (empty($page)) ? 1 : $page;
	$start_at								= intval(($current_page * $entries_per_page) - $entries_per_page);
	$query_params[':start_at'] = $start_at;
	$query_params[':entries_per_page'] = $entries_per_page;
	$article_info = array();
	
	article_result_parse($article_info, $query_set, $query_params, $orderby);
	
	$total_rows	= intval($article_info['total_rows']);
	unset($article_info['total_rows']);
	
	if(!empty($article_info))
	{
		foreach ($article_info as $row)
		{
			if(file_exists("themes/".$nuke_configs['ThemeSel']."/article_index.php"))
				include("themes/".$nuke_configs['ThemeSel']."/article_index.php");
			elseif(function_exists("article_index"))
				$contents .= article_index($row);
			else
				$contents .= "";
		}
		unset($article_info);
	}

	if (intval($page) != 0)
	{
		$meta_url = $link_to."&page=".intval($page)."";
	}
	
	
	if($entries_per_page < $total_rows)
	{
		$contents .= "<div id=\"pagination\">";
		$contents .= clean_pagination($total_rows, $entries_per_page, $current_page, $link_to);
		$contents .= "</div>";
	}
	
	$lastpage = ceil($total_rows/$entries_per_page);
	
	$meta_tags = array(
		"url"				=> LinkToGT($link_to),
		"title"				=> $module_title,
		"description"		=> '',
		"keywords"			=> '',
		"prev"				=> ($page < $lastpage && intval($page) != 0) ? LinkToGT($link_to."&page=".intval($page+1).""):"",
		"next"				=> ($page > 1 && $entries_per_page < $total_rows) ? LinkToGT($link_to."&page=".intval($page-1).""):"",
		"extra_meta_tags"	=> ($catid > 0) ? array(
			"<link rel=\"alternate\" type=\"application/atom+xml\" title=\"Atom - $cat_title\" href=\"".LinkToGT("index.php?modname=Feed&module_link=".$nuke_configs['REQUESTURL']."")."\" />\n"
		):"",
	);
	
	if($top_middle)
		$boxes_contents = show_modules_boxes($module_name, "index", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
	else
		$boxes_contents = show_modules_boxes($module_name, "index", array("bottom_full", "top_full","left","right"), $contents);

	include("header.php");
	$html_output .= $boxes_contents;
	include("footer.php");
}

function article_show($sid=0, $post_url='', $mode = '')
{
	global $db, $module_name, $REQUESTURL, $articles_votetype, $nuke_configs, $userinfo, $articles_ratecookie, $nuke_articles_categories_cacheData, $nuke_bookmarksite_cacheData, $visitor_ip, $pn_Cookies, $nuke_authors_cacheData;
	$article_info = array();
	$sid = intval($sid);

	$query_set = array();
	$query_params = array();
	
	$query_params[':visitor_ip'] = $visitor_ip;
	$query_params[':post_type'] = 'article';
	
	if($nuke_configs['gtset'] == 1)
	{
		$query_set[] = "s.post_url=:post_url OR s.sid=:post_url";
		$query_params[':post_url'] = $post_url;
		$query_params[':sid'] = $post_url;
		
		if($post_url == '')
			die_error("404");
	}
	else
	{
		$query_set[] = "s.sid=:sid";
		$query_params[':sid'] = $sid;
	}
		
	if(!is_admin())
	{
		$query_set[] = "s.status = 'publish'";
		$query_params[':status'] = 'publish';
	}
		
	article_result_parse($article_info, $query_set, $query_params, 'time', 'more');
	
	if(isset($article_info[0]))
	{
		$article_info = $article_info[0];
		$urlop = (($mode != '') && in_array($mode, array("pdf","print","friend","report"))) ? "$mode/":"";
		if(intval($article_info['sid'] > 0))
		{
		    $true_link = trim(articleslink(intval($article_info['sid']), filter($article_info['title'], "nohtml"), filter($article_info['post_url'], "nohtml"), filter($article_info['time'], "nohtml"), intval($article_info['cat_link'])).$urlop, "/")."/";
			if($true_link != trim(rawurldecode($REQUESTURL), "/")."/")
			{
			    redirect_to($true_link);
				die();
			}
		}
		else
			die_error("404");
	}
	else
	{
		die_error("404");
	}
	
	$article_info['next_article_title']	= (intval($article_info['nsid']) != 0) ? filter($article_info['ntitle'], "nohtml"):"";
	$article_info['next_article_link']	= (intval($article_info['nsid']) != 0) ? LinkToGT(articleslink(intval($article_info['nsid']), filter($article_info['ntitle'], "nohtml"), filter($article_info['npost_url'], "nohtml"), $article_info['ntime'], intval($article_info['ncat_link']))):"";
	$article_info['prev_article_title']	= (intval($article_info['psid']) != 0) ? filter($article_info['ptitle'], "nohtml"):"";
	$article_info['prev_article_link']	= (intval($article_info['psid']) != 0) ? LinkToGT(articleslink(intval($article_info['psid']), filter($article_info['ptitle'], "nohtml"), filter($article_info['ppost_url'], "nohtml"), $article_info['ptime'], intval($article_info['pcat_link']))):"";

	if (empty($article_info['aid']))
		Header("Location: ".LinkToGT("index.php?modname=$module_name")."");

	$db->table(POSTS_TABLE)
		->where('sid', $article_info['sid'])
		->update([
			"counter" => ["+", 1]
		]);

	$pagetitle				= $article_info['title'];

	if(empty($article_info['informant']))
		$article_info['informant']	= _ANONYMOUS;

	$allow_to_view					= false;
	$disallow_message				= "";

	$permission_result				= phpnuke_permissions_check($article_info['permissions']);

	$allow_to_view					= $permission_result[0];
	$disallow_message				= $permission_result[1];

	if (!$allow_to_view)
	{
		$article_info['bodytext']		= "<div class=\"text-center\">";
		$article_info['bodytext']		.="$disallow_message<br><br>";
		$article_info['bodytext']		.= _GOBACK;
		$article_info['bodytext']		.= "</div>";
	}

	$this_article_pass = $pn_Cookies->get("this_article_pass".$article_info['sid']);
	$this_article_pass = intval($this_article_pass);

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
		$article_info['print_link'] = "index.php?modname=$module_name&op=article_show&mode=print&sid=".$article_info['sid']."";
		$article_info['pdf_link'] = "index.php?modname=$module_name&op=article_show&mode=pdf&sid=".$article_info['sid']."";
		$article_info['friend_link'] = "index.php?modname=$module_name&op=article_show&mode=friend&sid=".$article_info['sid']."";
		$article_info['report_link'] = "index.php?modname=$module_name&op=article_show&mode=report&sid=".$article_info['sid']."";
	}
	
	$meta_tags = array(
		"url" => $article_info['article_link'],
		"title" => $article_info['title'],
		"description" => str_replace(array("\r","\n","\t"), "", strip_tags($article_info['hometext'])),
		"keywords" => $article_info['tags'],
		"prev" => $article_info['prev_article_link'],
		"next" => $article_info['next_article_link'],
		"extra_meta_tags" => array()
	);
	
	switch($mode)
	{
		default:
			$GLOBALS['block_global_contents'] = $article_info;
			$GLOBALS['block_global_contents']['post_id'] = $article_info['sid'];
			$GLOBALS['block_global_contents']['post_title'] = $article_info['title'];
			$GLOBALS['block_global_contents']['module_name'] = $module_name;
			$GLOBALS['block_global_contents']['allow_comments'] = $article_info['allow_comment'];
			$GLOBALS['block_global_contents']['db_table'] = POSTS_TABLE;
			$GLOBALS['block_global_contents']['db_id'] = 'sid';
			
			$contents = '';
			if(file_exists("themes/".$nuke_configs['ThemeSel']."/article_more.php"))
				include("themes/".$nuke_configs['ThemeSel']."/article_more.php");
			elseif(function_exists("article_more"))
				$contents .= article_more($article_info);
			else 
				$contents .= "";
				
			$boxes_contents = show_modules_boxes($module_name, "more", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
			
			include("header.php");
			
			unset($meta_tags);
			
			unset($GLOBALS['block_global_contents']);
			unset($article_info);
			
			$html_output .= $boxes_contents;
			include ("footer.php");
		break;
		
		case"friend":
		case"report":
			header("X-Robots-Tag: noindex, nofollow", true);
			report_friend_form(false, $mode, $article_info['sid'], $article_info['title'], $module_name, '', '', $article_info['article_link'], '', '');
			die();
		break;
		
		case"pdf":
			header("X-Robots-Tag: noindex, nofollow", true);
			pdf_generate($article_info['aid'], $article_info['tags'], $article_info['title'], $article_info['title'], $article_info['datetime'], $article_info['hometext']."<br /><br />".$article_info['bodytext'], $article_info['article_link']);
			die();
		break;
		
		case"print":
			header("X-Robots-Tag: noindex, nofollow", true);
			$css	= array('includes/Ajax/jquery/bootstrap/css/bootstrap.min.css','includes/Ajax/jquery/bootstrap/css/bootstrap-rtl.css');
			$js		= array('includes/Ajax/jquery/bootstrap/js/bootstrap.min.js');
			$html_content = "<style>.article-header{width:100%;float:right;}.article-header span {width: calc(100% - 270px);float: right;}.article-header span:nth-child(2), .article-header span:nth-child(4) {color: #a7a9ac;}.article-header span:nth-child(3) {font-size: 20px;font-weight: bold;line-height: 35px;color: #333;padding: 9px 0 17px;}.article-header img{float:right;width:250px;margin-left:20px;}.p-nt {margin: 17px 0;float:right;width:100%;padding-top: 17px;border-top:1px dotted #ccc;}.p-nt p {margin-bottom: 19px;} img{max-width:99%;}</style>
			<div class=\"article-header\">
				".(($article_info['post_image'] != "" && $article_info['article_image_width'] != 0 && $article_info['article_image_height'] != 0) ? "<img src=\"".$article_info['post_image']."\" width=\"".$article_info['article_image_width']."\" height=\"".$article_info['article_image_height']."\" alt=\"".$article_info['title']."\" title=\"".$article_info['title']."\" />":"<span></span>")."
				".(($article_info['title_lead'] != '') ? "<span style=\"color:#ccc;\">".$article_info['title_lead']."</span>":"")."
				<span>".$article_info['title']."</span>
				<span>".$article_info['hometext']."</span>
			</div>
			<div class=\"p-nt\"><p class=\"rtejustify\">".$article_info['hometext']."<br />".$article_info['bodytext']."</div>";
			
			print_theme($pagetitle, $article_info['title'], $article_info['datetime'], $article_info['cattext_link'], $html_content, $article_info['article_link'], $css, $js);
			die();
		break;
	}
}

function article_archive($year, $month, $month_l, $mode)
{
	global $userinfo, $db, $user, $page, $module_name, $nuke_configs;
	
	$contents = '';

	$page = isset($page) ? intval($page):0;
	$year = (isset($year) && strlen($year) == 4) ? intval($year):0;
	$month = (isset($month) && strlen($month) > 0 && strlen($month) < 3) ? intval($month):0;
	$month_l = isset($month_l) ? str_replace("-", " ", filter($month_l, "nohtml")):'';
	
	$month_names = ($nuke_configs['datetype'] == 1) ? "j_month_name":(($nuke_configs['datetype'] == 2) ? "h_month_name":"g_month_name");
	
	$month_l = str_replace(" ","-", $nuke_configs[$month_names][$month]);
	
	$link = "index.php?modname=Articles&op=article_archive".
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
		$where_between = array($currenttime, $nexttime);
	}
	
	$entries_per_page			= 20;
	$current_page				= (empty($page)) ? 1 : $page;
	$start_at					= ($current_page * $entries_per_page) - $entries_per_page;
	$link_to					= ($year != 0 && $month != 0 && $month_l != '') ? "index.php?modname=$module_name&op=article_archive&year=$year&month=$month&month_l=$month_l":"index.php?modname=$module_name&op=article_archive";
	
	$total_rows = $db->table(POSTS_TABLE)
					->where('post_type', 'article')
					->whereBetween('time', $where_between)
					->select(['sid'])
					->count();
					
	if(!is_admin())
		$query_set['status']	= "s.status = 'publish'";
		
	$query_set['post_type'] = "(s.post_type = 'article' OR s.post_type = '')";
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
			$article_link			= LinkToGT(articleslink($sid, $title, $post_url, $time, $cat_link));
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
				$print_link			= "index.php?modname=$module_name&op=article_show&mode=print&sid=$sid";
				$pdf_link			= "index.php?modname=$module_name&op=article_show&mode=pdf&sid=$sid";
				$friend_link		= "index.php?modname=$module_name&op=article_show&mode=friend&sid=$sid";
			}
			
			$actions				= "<a href=\"$print_link\"><img src=\"".$nuke_configs['nukecdnurl']."images/print.gif\" border=0 alt=\""._PRINT."\" title=\""._PRINT."\" width=\"16\" height=\"11\"></a>&nbsp;";
			$actions				.= "<a href=\"$pdf_link\"><img src=\"".$nuke_configs['nukecdnurl']."images/pdf.gif\" border=0 alt=\""._PDFFILE."\" title=\""._PDFFILE."\" width=\"16\" height=\"11\"></a>&nbsp;";
			if(is_user())
			{
				$actions			.= "<a href=\"$friend_link\" class=\"thickbox\"><img src=\"".$nuke_configs['nukecdnurl']."images/friend.gif\" border=0 alt=\""._SEND_POST_TO_FRIEND."\" title=\""._SEND_POST_TO_FRIEND."\" width=\"16\" height=\"11\"></a>";
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
	[ <a href=\"".LinkToGT("index.php?modname=$module_name&op=article_select_month")."\">"._ARCHIVESINDEX."</a>".(($mode !== 'all') ? " | <a href=\"".LinkToGT("index.php?modname=$module_name&op=article_archive")."\">"._SHOWALLSTORIES."</a>":"")." ]</div>";
	
	if (intval($page) != 0)
	{
		$meta_url				= $link_to."&page=".intval($page)."";
	}
	
	$next_link					= '';
	$prev_link					= '';
	
	$lastpage					= ceil($total_rows/$entries_per_page);
	
	if($page < $lastpage && $page != 0)
		$next_link				= LinkToGT($link_to."&page=".intval($page+1)."");
	
	if($page > 1 && $entries_per_page < $total_rows)
		$prev_link				= LinkToGT($link_to."&page=".intval($page-1)."");

	$meta_tags = array(
		"url" 					=> $link_to,
		"title" 				=> _STORIESARCHIVE."".(($month_l != '') ? " - $month_l $year":""),
		"description" 			=> '',
		"keywords" 				=> '',
		"prev" 					=> $prev_link,
		"next" 					=> $next_link,
		"extra_meta_tags" 		=> array(
			"<link rel=\"alternate\" type=\"application/atom+xml\" title=\"Atom - "._STORIESARCHIVE." - ".(($month_l != '') ? " - $month_l $year":"")."\" href=\"".LinkToGT("index.php?modname=Feed&module_link=".$nuke_configs['REQUESTURL']."")."\" />\n"
		)
	);
	
	include("header.php");
	$output = '';
	$output .= title($nuke_configs['sitename']." : "._STORIESARCHIVE);
	
	if($month_l != '')
		$output .= title("$month_l $year");
		
	$output .= OpenTable();
	$output .= $contents;
	$output .= CloseTable();
	$html_output .= show_modules_boxes($module_name, "archive", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $output);
	include("footer.php");
}

function article_select_month($in = 0)
{
	_article_select_month($in);
}

function article_categories()
{
	global $userinfo, $db, $user, $page, $module_name, $nuke_articles_categories_cacheData, $nuke_configs;
		
	$contents 								= "";
	$contents 								.= OpenTable();
	
	$sub_cats_contents_num 					= 0;
	$array_contents 						= array();
	foreach($nuke_articles_categories_cacheData as $key => $val)
	{
		if($val['parent_id'] == 0)
		{
			$cat_link						= filter($val['catname_url'], "nohtml");
			$array_contents[$key] = array($val, $cat_link, LinkToGT("index.php?modname=Articles&category=$cat_link"));
			$sub_cats_contents_num++;
		}
	}
	if($sub_cats_contents_num > 0)
	{
		if(file_exists("themes/".$nuke_configs['ThemeSel']."/article_categories.php"))
			include("themes/".$nuke_configs['ThemeSel']."/article_categories.php");
		elseif(function_exists("article_categories_html"))
			$contents .= article_categories_html($array_contents);
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
		"url" 								=> LinkToGT("index.php?modname=$module_name&op=article_categories"),
		"title" 							=> _ARTICLES_CATEGORIES,
		"description" 						=> '',
		"keywords" 							=> '',
		"prev" 								=> '',
		"next" 								=> '',
		"extra_meta_tags" 					=> array()
	);
	
	include("header.php");
	$html_output .= show_modules_boxes($module_name, "category", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), title($nuke_configs['sitename']." : "._ARTICLES_CATEGORIES."").$contents);
	include("footer.php");
}

function send_article($preview, $submit, $article_fields, $security_code, $security_code_id)
{
	global $db, $userinfo, $nuke_configs, $module_name, $nuke_articles_categories_cacheData, $PnValidator, $visitor_ip;
	
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
			'hometext'	=> 'required'
		)); 
		// Get or set the filtering rules
		$PnValidator->filter_rules(array(
			'title'		=> 'sanitize_string',
			'alanguage'	=> 'sanitize_string',
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
					$article_fields['post_type'] = 'article';
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
		
		$all_cats = (!empty($nuke_articles_categories_cacheData)) ? get_sub_lists($nuke_articles_categories_cacheData, $nuke_articles_categories_cacheData, 0, $list_config):"";
		
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
	
	$default_css[] = "<link href=\"includes/Ajax/jquery/jquery-checktree.css\" rel=\"stylesheet\" type=\"text/css\">";
		
	$defer_js[] = "<script src=\"includes/Ajax/jquery/jquery-checktree.js\"></script>";
		
	$custom_theme_setup = array(
		"default_css" => $default_css,
		"defer_js" => $defer_js
	);
	$custom_theme_setup_replace = false;
	
	$meta_tags = array(
		"url" => LinkToGT("index.php?modname=$module_name&op=send_article"),
		"title" => _SEND_POST,
		"description" => _SEND_POST_DESCRIPTION,
		"keywords" => '',
		"extra_meta_tags" => array()
	);
	
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
$article_fields		= isset($article_fields)? $article_fields : array();
$preview			= isset($preview)? filter($preview, "nohtml") : "";
$submit				= isset($submit)? filter($submit, "nohtml") : "";
$security_code		= isset($security_code)? filter($security_code, "nohtml") : "";
$security_code_id	= isset($security_code_id)? filter($security_code_id, "nohtml") : "";
$args 				= isset($args) ? filter($args, "nohtml") : "";

if($args != '' && $nuke_configs['userurl'] != 3)
{
    if ( filter_var($args, FILTER_VALIDATE_INT) !== false) {
        $sid = intval($args);
    }
    
	$result = $db->table(ARTICLES_TABLE)
		->where('sid', intval($sid))
		->select(['post_url',"title","time", "cat_link"]);
	if(intval($result->count()) > 0)
	{
		$row = $result->results()[0];
		$post_url = filter($row['post_url'], "nohtml");
		$title = filter($row['title'], "nohtml");
		$time = filter($row['time'], "nohtml");
		$cat_link = intval($row['cat_link']);
		redirect_to(articleslink($sid, $title, $post_url, $time, $cat_link));
		die();
	}
}

if($year == 0 && $month == 0 && $mode != 'all' && $op == 'article_archive')
	$op = "article_select_month";

switch ($op)
{
	case "article_archive":
		article_archive($year, $month, $month_l, $mode);
	break;
	case"article_select_month":
		article_select_month(0);
	break;
	case"article_categories":
		article_categories();
	break;
	case"send_article":
		send_article($preview, $submit, $article_fields, $security_code, $security_code_id);
	break;
	case"article_show":
		article_show($sid, $post_url, $mode);
	break;
	default:
	articles_home($category, $tags, $orderby);
	break;
}

?>