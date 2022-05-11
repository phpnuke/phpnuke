<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2002 by Francisco Burzi                                */
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

##################################################
# Include for meta Tags generation               #
##################################################
	
global $hooks;
$all_meta_tags = array();
$all_meta_tags = $hooks->apply_filters("site_header_meta", $all_meta_tags);

$block_global_contents = array();
$block_global_contents = $hooks->apply_filters("global_contents", $block_global_contents);

$all_meta_tags['time'] = (isset($all_meta_tags['time']) && $all_meta_tags['time'] != '') ? $all_meta_tags['time']:((isset($block_global_contents['time']) && $block_global_contents['time'] != '') ? $block_global_contents['time']:_NOWTIME);
	
$all_meta_tags['title'] = $nuke_configs['sitename'].((!defined("HOME_FILE")) ? " - ".((isset($all_meta_tags['title'])) ? strip_tags($all_meta_tags['title']):''):"");

$all_meta_tags['description'] = str_replace(array("\n","\r"),"", (isset($all_meta_tags['description']) && $all_meta_tags['description'] != '') ? stripslashes(strip_tags($all_meta_tags['description'])):stripslashes($nuke_configs['site_description']));

$all_meta_tags['keywords'] = (isset($all_meta_tags['keywords']) && $all_meta_tags['keywords'] != '') ? $all_meta_tags['keywords']:$nuke_configs['site_keywords'];

$extra_all_meta_tags = (isset($all_meta_tags['extra_all_meta_tags']) && !empty($all_meta_tags['extra_all_meta_tags'])) ? $all_meta_tags['extra_all_meta_tags']:array();

$all_meta_tags['url'] = (isset($all_meta_tags['url']) && $all_meta_tags['url'] != '') ? $all_meta_tags['url']:"";

$meta_contents = array(
	"charset" => "<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\">",
	"title" => "<title>".$all_meta_tags['title']."</title>",
	"viewport" => "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">",
);

if($all_meta_tags['description'] != '')
	$meta_contents['description'] = "<meta name=\"description\" content=\"".$all_meta_tags['description']."\">";

if($all_meta_tags['url'] != '')
	$meta_contents['canonical'] = "<link rel=\"canonical\" href=\"".$all_meta_tags['url']."\" />";

if(isset($all_meta_tags['prev']) && $all_meta_tags['prev'] != '')
	$meta_contents['prev'] = "<link rel=\"prev\" href=\"".$all_meta_tags['prev']."\" />";
	
if(isset($all_meta_tags['next']) && $all_meta_tags['next'] != '')
	$meta_contents['next'] = "<link rel=\"next\" href=\"".$all_meta_tags['next']."\" />";

// Open Graph protocol
$theme_get_media_function = '';
$theme_get_media_function = $hooks->apply_filters("get_theme_media_function", $theme_get_media_function);
if(function_exists($theme_get_media_function))
{
	$all_medias = $theme_get_media_function();
	$all_meta_tags['meta_image'] = (isset($all_medias[1])) ? $all_medias[1]:"";
	$all_meta_tags['meta_audio'] = (isset($all_medias[2])) ? $all_medias[2]:"";
	$all_meta_tags['meta_video'] = (isset($all_medias[3])) ? $all_medias[3]:"";

	if($all_meta_tags['meta_image'] != '')
	{
		$meta_image = LinkToGT("index.php?timthumb=true&src=".$all_meta_tags['meta_image']."&q=90&w=400&h=400");
		$meta_contents['og:image'] = "<meta property=\"og:image\" content=\"".$meta_image."\" />";
		$meta_contents['og:image:secure_url'] = "<meta property=\"og:image:secure_url\" content=\"".$meta_image."\" />";
		$meta_contents['og:image:type'] = "<meta property=\"og:image:type\" content=\"image/jpeg\" />";
		$meta_contents['og:image:width'] = "<meta property=\"og:image:width\" content=\"400\" />";
		$meta_contents['og:image:height'] = "<meta property=\"og:image:height\" content=\"400\" />";
		$meta_contents['og:image:alt'] = "<meta property=\"og:image:alt\" content=\"".$all_meta_tags['title']."\" />";
	}

	if($all_meta_tags['meta_audio'] != '')
	{
		$meta_contents['og:audio'] = "<meta property=\"og:audio\" content=\"".$all_meta_tags['meta_audio']."\" />";
		$meta_contents['og:audio:secure_url'] = "<meta property=\"og:audio:secure_url\" content=\"".$all_meta_tags['meta_audio']."\" />";
		$meta_contents['og:audio:type'] = "<meta property=\"og:audio:type\" content=\"audio/mpeg\" />";
	}

	if($all_meta_tags['meta_video'] != '')
	{
		$meta_contents['og:video'] = "<meta property=\"og:video\" content=\"".$all_meta_tags['meta_video']."\" />";
		$meta_contents['og:video:secure_url'] = "<meta property=\"og:video:secure_url\" content=\"".$all_meta_tags['meta_video']."\" />";
		$meta_contents['og:video:type'] = "<meta property=\"og:video:type\" content=\"video/mp4\" />";
		$meta_contents['og:video:width'] = "<meta property=\"og:video:width\" content=\"400\" />";
		$meta_contents['og:video:height'] = "<meta property=\"og:video:height\" content=\"300\" />";
	}
}

$meta_contents['og:site_name'] = "<meta property=\"og:site_name\" content=\"".$nuke_configs['sitename']."\" />";
$meta_contents['og:title'] = "<meta property=\"og:title\" content=\"".$all_meta_tags['title']."\" />";
$meta_contents['og:description'] = "<meta property=\"og:description\" content=\"".$all_meta_tags['description']."\" />";
$meta_contents['og:locale'] = "<meta property=\"og:locale\" content=\"".$nuke_configs['locale']."\" />";
$meta_contents['og:type'] = "<meta property=\"og:type\" content=\"article\" />";

if($all_meta_tags['url'] != '')
	$meta_contents['og:url'] = "<meta property=\"og:url\" content=\"".$all_meta_tags['url']."\" />";

if($modname == "Articles" && $op == "article_show")
{
	$meta_contents['article:published_time'] = "<meta property=\"article:published_time\" content=\"".date('Y-m-d\TH:i:s+00:00',$all_meta_tags['time'])."\" />";
	$meta_contents['article:modified_time'] = "<meta property=\"article:modified_time\" content=\"".date('Y-m-d\TH:i:s+00:00',$all_meta_tags['time'])."\" />";


	if(isset($block_global_contents['aid']) && $block_global_contents['aid'] != '')
		$meta_contents['article:author'] = "<meta property=\"article:author\" content=\"".$block_global_contents['aid']."\" />";
		
	if(isset($block_global_contents['tags']) && $block_global_contents['tags'] != '')
	{
		$tags_arr = explode(",", $block_global_contents['tags']);
		$tags_arr = array_filter($tags_arr);
		foreach($tags_arr as $tags_ar)
			$meta_contents['article:tag'] = "<meta property=\"article:tag\" content=\"".$tags_ar."\" />";
	}
}
// Open Graph protocol

// site meta tags settings	
if(isset($nuke_configs['site_meta_tags']) && $nuke_configs['site_meta_tags'] != '')
{
	$nuke_configs['site_meta_tags'] = stripslashes($nuke_configs['site_meta_tags']);
	
	$nuke_configs['site_meta_tags'] = str_replace(
		array(
			"{TITLE}", "{URL}", "{PAGETITLE}", "{DESCRIPTION}"
		),
		array(
			$all_meta_tags['title'], LinkToGT($all_meta_tags['url']), $all_meta_tags['title'], $all_meta_tags['description']
		),
		$nuke_configs['site_meta_tags']
	);
	
	preg_match_all('#\{(.*)\}#isU', $nuke_configs['site_meta_tags'], $matchs);
	
	if(isset($matchs[1][0]))
	{
		foreach($matchs[1] as $var)
		{
			$new_value = '';
			if(isset($all_meta_tags[strtolower($var)]))
			{
				$new_value = $all_meta_tags[strtolower($var)];
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
				$nuke_configs['site_meta_tags'] = str_replace('{'.$var.'}', $new_value, $nuke_configs['site_meta_tags']);
		}
	}	
	$meta_contents['site_meta_tags'] = stripslashes($nuke_configs['site_meta_tags']);
}
// site meta tags settings


if ($nuke_configs['gverify'] != ""._YOUR_CODE."" && $nuke_configs['gverify'] != "")
	$meta_contents['gverify'] = "<meta name=\"google-site-verification\" content=\"".$nuke_configs['gverify']."\" />";

if ($nuke_configs['alexverify'] != ""._YOUR_CODE."" && $nuke_configs['alexverify'] != "")
	$meta_contents['alexverify'] = "<meta name=\"alexaVerifyID\" content=\"".$nuke_configs['alexverify']."\" />";

if ($nuke_configs['yverify'] != ""._YOUR_CODE."" && $nuke_configs['yverify'] != "")
	$meta_contents['yverify'] = "<meta name=\"y_key\" content=\"".$nuke_configs['yverify']."\">";

// rss codes
$rsslink = LinkToGT("index.php?modname=Feed");
$meta_contents['Atom'] = "<link rel=\"alternate\" type=\"application/atom+xml\" title=\"Atom\" href=\"$rsslink\" />";
// rss codes

//$extra_meta_tags
if(isset($all_meta_tags['extra_meta_tags']) && !empty($all_meta_tags['extra_meta_tags']))
	$meta_contents = array_merge_recursive($meta_contents, $all_meta_tags['extra_meta_tags']);
//$extra_meta_tags

$block_global_contents['module_name'] = $modname;
$theme_setup = $hooks->apply_filters("site_theme_headers", $theme_setup);
$theme_setup = array_unique_recursive($theme_setup);

$meta_contents['default_meta'] = $meta_contents['default_link_rel'] = $meta_contents['default_css'] = $meta_contents['default_js'] = '';
if(isset($theme_setup['default_meta']) && !empty($theme_setup['default_meta']))
	foreach($theme_setup['default_meta'] as $key => $default_meta)
		if($default_meta != '')
			$meta_contents['default_meta'] .= (($key != 0) ? "\n\t\t":"")."".$default_meta;

if(isset($theme_setup['default_link_rel']) && !empty($theme_setup['default_link_rel']))
	foreach($theme_setup['default_link_rel'] as $key => $default_link_rel)
		if($default_link_rel != '')
			$meta_contents['default_link_rel'] .= (($key != 0) ? "\n\t\t":"")."".$default_link_rel;

if(isset($theme_setup['default_css']) && !empty($theme_setup['default_css']))
	foreach($theme_setup['default_css'] as $key => $default_css)
		if($default_css != '')
			$meta_contents['default_css'] .= (($key != 0) ? "\n\t\t":"")."".$default_css;
	
if(isset($theme_setup['default_js']) && !empty($theme_setup['default_js']))
	foreach($theme_setup['default_js'] as $key => $default_js)
		if($default_js != '')
			$meta_contents['default_js'] .= (($key != 0) ? "\n\t\t":"")."".$default_js;

if(!defined("_ERROR_PAGE"))
	$meta_contents['microdata'] = show_microdata_as_json($all_meta_tags, $block_global_contents);

$meta_contents = array_filter($meta_contents);
$meta_contents = $hooks->apply_filters("site_headers_after", $meta_contents);

$contents .= "\n\t\t".implode("\n\t\t", $meta_contents);
unset($meta_contents);

// microdata settings
function show_microdata_as_json(&$meta_tags, $block_global_contents)
{
	global $nuke_configs, $modname, $op, $main_module, $hooks;
	
	$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
	
	$admin_name = "admin";
	$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
	
	$nuke_categories_cacheData = isset($nuke_categories_cacheData[$main_module]) ? $nuke_categories_cacheData[$main_module]:array();
	
	$now = date('Y-m-d\TH:i:s+00:00',$meta_tags['time']);
	
	$custom_microdata = (isset($meta_tags['microdata']) && $meta_tags['microdata'] != '') ? $meta_tags['microdata']:"";
	
	$json_ld = new stdClass();
	
	$context_key  = "@context";
	$graph_key  = "@graph";
	$json_ld->$context_key = "https://schema.org";
	$json_ld->$graph_key = array();
	
	$God = '';
	foreach($nuke_authors_cacheData as $aid => $nuke_authors)
	{
		$God = ($nuke_authors['name'] == 'God') ? $aid:"admin";
		
		$json_ld->$graph_key[] = (object)[
			"@type" => [
				"Person",
				"Organization"
			],
			"@id" => $nuke_configs['nukeurl']."#/schema/person/".md5($aid)."",
			"name" => "$aid",
			"url" => $nuke_configs['nukeurl'],
			"image" => (object)[
				"@type" => "ImageObject",
				"@id" => $nuke_configs['nukeurl']."#personlogo_".md5($aid)."",
				"url" => LinkToGT("images/logo.png"),
				"width" => 150,
				"height" => 150,
				"caption" => "$aid"
			],
			"logo" => (object)[
				"@id" => $nuke_configs['nukeurl']."#personlogo_".md5($aid).""
			],
			"sameAs" => []
		];
	}
	
	$json_ld->$graph_key['WebSite'] = (object)[
		"@type" => "WebSite",
		"@id" => $nuke_configs['nukeurl']."#website",
		"url" => $nuke_configs['nukeurl'],
		"name" => $nuke_configs['sitename'],
		"description" => stripslashes($nuke_configs['site_description']),
		"publisher" => (object)[
			"@id" => $nuke_configs['nukeurl']."#/schema/person/".md5($God).""
		],
		"potentialAction" => [
			(object)[
				"@type" => "SearchAction",
				"target" => str_replace(array("%7B", "%7D"),array("{","}"), LinkToGT("index.php?modname=Search&search_query={search_term_string}")),
				"query-input" => "required name=search_term_string"
			]
		],
		"inLanguage" => $nuke_configs['locale']
	];
	
	if(isset($meta_tags['url']) && $meta_tags['url'] != '')
	{
		$json_ld->$graph_key['WebPage'] = [
			"@type" => "WebPage",
			"@id" => LinkToGT($meta_tags['url'])."#webpage",
			"url" => LinkToGT($meta_tags['url']),
			"inLanguage" => $nuke_configs['locale'],
			"name" => $meta_tags['title'],
			"isPartOf" => (object)[
				"@id" => $nuke_configs['nukeurl']."#website"
			],
			"primaryImageOfPage" => (object)[
				"@id" => LinkToGT($meta_tags['url'])."#primaryimage"
			],
			"datePublished" =>$now,
			"dateModified" => $now,
			"description" => $meta_tags['description'],
			"breadcrumb" => (object)[
				"@id" => LinkToGT($meta_tags['url'])."#breadcrumb"
			]
		];
	}
	
	if(isset($meta_tags['meta_image']) && $meta_tags['meta_image'] != '')
	{
		$image_url = LinkToGT("index.php?timthumb=true&src=".$meta_tags['meta_image']."&w=180&h=180");
		$json_ld->$graph_key['ImageObject'] = (object)[
			"@type" => "ImageObject",
			"@id" => $image_url."#primaryimage",
			"url" => $image_url,
			"width" => 180,
			"height" => 180
		];
	}
	
	$breadcrumbs['home'] = array(
		"name" => $nuke_configs['sitename'],
		"link" => $nuke_configs['nukeurl'],
		"itemtype" => "WebPage"
	);
	
	$breadcrumbs = $hooks->apply_filters("site_breadcrumb", $breadcrumbs, $block_global_contents);
		
	if(isset($breadcrumbs) && !empty($breadcrumbs) && isset($meta_tags['url']) && $meta_tags['url'] != '')
	{
		$breadcrumbs_items = array();
		$breadcrumb_pos = 1;
		foreach($breadcrumbs as $breadcrumb)
		{
			$breadcrumbs_items[] = [
				"@type" => "ListItem",
				"position" => $breadcrumb_pos,
				"item" => [
					"@type" => $breadcrumb['itemtype'],
					"@id" => $breadcrumb['link'],
					"url" => $breadcrumb['link'],
					"name" => $breadcrumb['name']
				]
			];
			$breadcrumb_pos++;
		}
	
		$json_ld->$graph_key['BreadcrumbList'] = (object)[
			"@type" => "BreadcrumbList",
			"@id" => LinkToGT($meta_tags['url'])."#breadcrumb",
			"itemListElement" => $breadcrumbs_items,
		];
	}
	
	$json_ld = $hooks->apply_filters("microdata_json", $json_ld, $meta_tags, $block_global_contents);
	$json_ld->$graph_key = array_values($json_ld->$graph_key);

	return "<script type=\"application/ld+json\">".json_encode($json_ld, JSON_UNESCAPED_SLASHES)."</script>";
}
// microdata settings

###############################################
# DO NOT REMOVE THE FOLLOWING COPYRIGHT LINE! #
# YOU'RE NOT ALLOWED TO REMOVE NOR EDIT THIS. #
###############################################

// IF YOU REALLY NEED TO REMOVE IT AND HAVE MY WRITTEN AUTHORIZATION CHECK: http://phpnuke.org/index.php?modname=Commercial_License
// PLAY FAIR AND SUPPORT THE DEVELOPMENT, PLEASE!

//echo "<meta name=\"GENERATOR\" content=\"PHP-Nuke - Copyright by http://phpnuke.org\">\n";
?>