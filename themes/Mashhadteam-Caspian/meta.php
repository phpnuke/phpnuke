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
global $db, $nuke_configs, $nuke_rss_codes, $category, $block_global_contents, $modname, $op;
	
$meta_tags['time'] = (isset($meta_tags['time']) && $meta_tags['time'] != '') ? $meta_tags['time']:((isset($block_global_contents['time']) && $block_global_contents['time'] != '') ? $block_global_contents['time']:_NOWTIME);
	
$meta_tags['title'] = $nuke_configs['sitename'].((!defined("HOME_FILE")) ? " - ".((isset($meta_tags['title'])) ? strip_tags($meta_tags['title']):''):"");

$meta_tags['description'] = str_replace(array("\n","\r"),"", (isset($meta_tags['description']) && $meta_tags['description'] != '') ? stripslashes(strip_tags($meta_tags['description'])):stripslashes($nuke_configs['site_description']));

$meta_tags['keywords'] = (isset($meta_tags['keywords']) && $meta_tags['keywords'] != '') ? $meta_tags['keywords']:$nuke_configs['site_keywords'];

$extra_meta_tags = (isset($meta_tags['extra_meta_tags']) && !empty($meta_tags['extra_meta_tags'])) ? $meta_tags['extra_meta_tags']:array();

$meta_contents = array(
	"<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\">",
	"<meta http-equiv=\"content-language\" content=\""._LANGSMALLNAME."\" />",
	"<title>".$meta_tags['title']."</title>",
	"<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">",
);

if($meta_tags['description'] != '')
	$meta_contents[] = "<meta name=\"description\" content=\"".$meta_tags['description']."\">";

if(isset($meta_tags['url']) && $meta_tags['url'] != '')
	$meta_contents[] = "<link rel=\"canonical\" href=\"".$meta_tags['url']."\" />";

if(isset($meta_tags['prev']) && $meta_tags['prev'] != '')
	$meta_contents[] = "<link rel=\"prev\" href=\"".$meta_tags['prev']."\" />";
	
if(isset($meta_tags['next']) && $meta_tags['next'] != '')
	$meta_contents[] = "<link rel=\"next\" href=\"".$meta_tags['next']."\" />";

// Open Graph protocol
$all_medias = get_caspian_posts_media();
$meta_tags['meta_image'] = (isset($all_medias[1])) ? $all_medias[1]:"";
$meta_tags['meta_audio'] = (isset($all_medias[2])) ? $all_medias[2]:"";
$meta_tags['meta_video'] = (isset($all_medias[3])) ? $all_medias[3]:"";

$meta_contents[] = "<meta property=\"og:site_name\" content=\"".$nuke_configs['sitename']."\" />";
$meta_contents[] = "<meta property=\"og:title\" content=\"".$meta_tags['title']."\" />";
$meta_contents[] = "<meta property=\"og:description\" content=\"".$meta_tags['description']."\" />";
$meta_contents[] = "<meta property=\"og:locale\" content=\"".$nuke_configs['locale']."\" />";
$meta_contents[] = "<meta property=\"og:type\" content=\"article\" />";

if(isset($meta_tags['url']) && $meta_tags['url'] != '')
	$meta_contents[] = "<meta property=\"og:url\" content=\"".$meta_tags['url']."\" />";

if($modname == "Articles" && $op == "article_show")
{
	$meta_contents[] = "<meta property=\"article:published_time\" content=\"".date('Y-m-d\TH:i:s+00:00',$meta_tags['time'])."\" />";
	$meta_contents[] = "<meta property=\"article:modified_time\" content=\"".date('Y-m-d\TH:i:s+00:00',$meta_tags['time'])."\" />";


	if(isset($block_global_contents['aid']) && $block_global_contents['aid'] != '')
		$meta_contents[] = "<meta property=\"article:author\" content=\"".$block_global_contents['aid']."\" />";
		
	if(isset($block_global_contents['tags']) && $block_global_contents['tags'] != '')
	{
		$tags_arr = explode(",", $block_global_contents['tags']);
		$tags_arr = array_filter($tags_arr);
		foreach($tags_arr as $tags_ar)
			$meta_contents[] = "<meta property=\"article:tag\" content=\"".$tags_ar."\" />";
	}
}

if($meta_tags['meta_image'] != '')
{
	$meta_image = LinkToGT("index.php?timthumb=true&src=".$meta_tags['meta_image']."&q=90&w=400&h=400");
	$meta_contents[] = "<meta property=\"og:image\" content=\"".$meta_image."\" />";
	$meta_contents[] = "<meta property=\"og:image:secure_url\" content=\"".$meta_image."\" />";
	$meta_contents[] = "<meta property=\"og:image:type\" content=\"image/jpeg\" />";
	$meta_contents[] = "<meta property=\"og:image:width\" content=\"400\" />";
	$meta_contents[] = "<meta property=\"og:image:height\" content=\"400\" />";
	$meta_contents[] = "<meta property=\"og:image:alt\" content=\"".$meta_tags['title']."\" />";
}

if($meta_tags['meta_audio'] != '')
{
	$meta_contents[] = "<meta property=\"og:audio\" content=\"".$meta_tags['meta_audio']."\" />";
	$meta_contents[] = "<meta property=\"og:audio:secure_url\" content=\"".$meta_tags['meta_audio']."\" />";
	$meta_contents[] = "<meta property=\"og:audio:type\" content=\"audio/mpeg\" />";
}

if($meta_tags['meta_video'] != '')
{
	$meta_contents[] = "<meta property=\"og:video\" content=\"".$meta_tags['meta_video']."\" />";
	$meta_contents[] = "<meta property=\"og:video:secure_url\" content=\"".$meta_tags['meta_video']."\" />";
	$meta_contents[] = "<meta property=\"og:video:type\" content=\"video/mp4\" />";
	$meta_contents[] = "<meta property=\"og:video:width\" content=\"400\" />";
	$meta_contents[] = "<meta property=\"og:video:height\" content=\"300\" />";
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
			$meta_tags['title'], LinkToGT($meta_tags['url']), $meta_tags['title'], $meta_tags['description']
		),
		$nuke_configs['site_meta_tags']
	);
	
	preg_match_all('#\{(.*)\}#isU', $nuke_configs['site_meta_tags'], $matchs);
	
	if(isset($matchs[1][0]))
	{
		foreach($matchs[1] as $var)
		{
			$new_value = '';
			if(isset($meta_tags[strtolower($var)]))
			{
				$new_value = $meta_tags[strtolower($var)];
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
	$meta_contents[] = stripslashes($nuke_configs['site_meta_tags']);
}
// site meta tags settings


if ($nuke_configs['gverify'] != ""._YOUR_CODE."" && $nuke_configs['gverify'] != "")
	$meta_contents[] = "<meta name=\"google-site-verification\" content=\"".$nuke_configs['gverify']."\" />\n";

if ($nuke_configs['alexverify'] != ""._YOUR_CODE."" && $nuke_configs['alexverify'] != "")
	$meta_contents[] = "<meta name=\"alexaVerifyID\" content=\"".$nuke_configs['alexverify']."\" />\n";

if ($nuke_configs['yverify'] != ""._YOUR_CODE."" && $nuke_configs['yverify'] != "")
	$meta_contents[] = "<meta name=\"y_key\" content=\"".$nuke_configs['yverify']."\">\n";

// rss codes
$rsslink = LinkToGT("index.php?modname=Feed");

$meta_contents[] = "<link rel=\"alternate\" type=\"application/atom+xml\" title=\"Atom\" href=\"$rsslink\" />\n";

if(isset($nuke_rss_codes) && is_array($nuke_rss_codes) && !empty($nuke_rss_codes))
	foreach($nuke_rss_codes as $nuke_rss_code)
		eval($nuke_rss_code);
// rss codes

//$extra_meta_tags
if(!empty($extra_meta_tags))
	foreach($extra_meta_tags as $extra_meta_tag)
		$meta_contents[] = $extra_meta_tag;
//$extra_meta_tags

$block_global_contents['module_name'] = $modname;

$contents .= "\n\t\t".implode("\n\t\t", $meta_contents);

// microdata settings
function show_microdata_as_json(&$meta_tags)
{
	global $block_global_contents, $nuke_configs, $nuke_authors_cacheData, $modname, $op, $nuke_articles_categories_cacheData;
	
	$admin_name = "admin";
	
	$now = date('Y-m-d\TH:i:s+00:00',$meta_tags['time']);
	
	$custom_microdata = (isset($meta_tags['microdata']) && $meta_tags['microdata'] != '') ? $meta_tags['microdata']:"";
	
	$json_ld['@context'] = "https://schema.org";
	
	$God = '';
	foreach($nuke_authors_cacheData as $aid => $nuke_authors)
	{
		$God = ($nuke_authors['name'] == 'God') ? $aid:"admin";
		
		$json_ld['@graph'][] = [
			"@type" => [
				"Person",
				"Organization"
			],
			"@id" => $nuke_configs['nukeurl']."#/schema/person/".md5($aid)."",
			"name" => "$aid",
			"url" => $nuke_configs['nukeurl'],
			"image" => [
				"@type" => "ImageObject",
				"@id" => $nuke_configs['nukeurl']."#personlogo_".md5($aid)."",
				"url" => LinkToGT("images/logo.png"),
				"width" => 150,
				"height" => 150,
				"caption" => "$aid"
			],
			"logo" => [
				"@id" => $nuke_configs['nukeurl']."#personlogo_".md5($aid).""
			],
			"sameAs" => []
		];
	}
	
	$json_ld['@graph'][] = [
		"@type" => "WebSite",
		"@id" => $nuke_configs['nukeurl']."#website",
		"url" => $nuke_configs['nukeurl'],
		"name" => $nuke_configs['sitename'],
		"description" => stripslashes($nuke_configs['site_description']),
		"publisher" => [
			"@id" => $nuke_configs['nukeurl']."#/schema/person/".md5($God).""
		],
		"potentialAction" => [
			"@type" => "SearchAction",
			"target" => str_replace(array("%7B", "%7D"),array("{","}"), LinkToGT("index.php?modname=Search&search_query={search_term_string}")),
			"query-input" => "required name=search_term_string"
		]
	];
	
	if(isset($meta_tags['url']) && $meta_tags['url'] != '')
	{
		$json_ld["@graph"][] = [
			"@type" => "WebPage",
			"@id" => LinkToGT($meta_tags['url'])."#webpage",
			"url" => LinkToGT($meta_tags['url']),
			"inLanguage" => "fa-IR",
			"name" => $meta_tags['title'],
			"isPartOf" => [
				"@id" => $nuke_configs['nukeurl']."#website"
			],
			"primaryImageOfPage" => [
				"@id" => LinkToGT($meta_tags['url'])."#primaryimage"
			],
			"datePublished" =>$now,
			"dateModified" => $now,
			"description" => $meta_tags['description'],
			"breadcrumb" => [
				"@id" => LinkToGT($meta_tags['url'])."#breadcrumb"
			]
		];
	}
	
	if(isset($meta_tags['meta_image']) && $meta_tags['meta_image'] != '')
	{
		$image_url = LinkToGT("index.php?timthumb=true&src=".$meta_tags['meta_image']."&w=180&h=180");
		$json_ld["@graph"][] = [
			"@type" => "ImageObject",
			"@id" => $image_url."#primaryimage",
			"url" => $image_url,
			"width" => 180,
			"height" => 180
		];
	}
	
	$breadcrumb_pos = 2;
	$meta_tags['breadcrumb'][] = array(
		"@type" => "ListItem",
		"position" => 1,
		"item" => array(
			"@type" => "WebPage",
			"@id" => $nuke_configs['nukeurl'],
			"url" => $nuke_configs['nukeurl'],
			"name" => $nuke_configs['sitename']
		)
	);
	
	if($modname == 'Articles' && $op == "article_show")
	{
		if($block_global_contents['catname_link'] != 'uncategorized')
		{
			$cat_link = sanitize(filter(implode("/", array_reverse(get_parent_names($block_global_contents['cat_link'], $nuke_articles_categories_cacheData, "parent_id", "catname_url"))), "nohtml"), array("/"));
			$meta_tags['breadcrumb'][] = array(
				"@type" => "ListItem",
				"position" => $breadcrumb_pos,
				"item" => array(
					"@type" => "WebPage",
					"@id" => LinkToGT("index.php?modname=Articles&category=$cat_link"),
					"url" => LinkToGT("index.php?modname=Articles&category=$cat_link"),
					"name" => category_lang_text($nuke_articles_categories_cacheData[$block_global_contents['cat_link']]['cattext']),
				)
			);
			$breadcrumb_pos++;
		}

		$meta_tags['breadcrumb'][] = array(
			"@type" => "ListItem",
			"position" => $breadcrumb_pos,
			"item" => array(
				"@type" => "WebPage",
				"@id" => $meta_tags['url'],
				"url" => $meta_tags['url'],
				"name" => $meta_tags['title']
			)
		);
		
		$json_ld["@graph"][] = [
			"@type" => [
				"Article",
				"MediaObject",
				"BlogPosting"
			],
			"@id" => "".$block_global_contents['article_link']."#article",
			"author" => [
				"@type" => "Person",
				"@id" => "".$nuke_configs['nukeurl']."#/schema/person/".md5($block_global_contents['aid']).""
			],
			"headline" => $block_global_contents['title'],
			"datePublished" => date('Y-m-d\TH:i:s+00:00', $block_global_contents['time']),
			"dateModified" => date('Y-m-d\TH:i:s+00:00', $block_global_contents['time']),
			"commentCount" => $block_global_contents['comments'],
			"mainEntityOfPage" => [
				"@type" => "WebPage",
				"@id" => "".$block_global_contents['article_link']."#webpage"
			],
			"publisher" => [
				"@type" => "Person",
				"@id" => "".$nuke_configs['nukeurl']."#/schema/person/".md5($block_global_contents['aid']).""
			],
			"image" => [
				"@type" => "ImageObject",
				"@id" => "".$block_global_contents['post_image']."#primaryimage",
				"url" => "".$block_global_contents['post_image']
			],
			"keywords" => str_replace(":",",", $block_global_contents['tags']),
			"name" => $block_global_contents['title']
		];
		
		if($block_global_contents['ratings'] != 0)
		{
			$last_key = array_key_last($json_ld["@graph"]);
			$json_ld["@graph"][$last_key]['aggregateRating'] = [
				"@type" => "AggregateRating",
				"bestRating" => 5,
				"worstRating" => 0,
				"ratingCount" => $block_global_contents['ratings'],
				"ratingValue" => (($block_global_contents['ratings'] != 0) ? round($block_global_contents['score']/$block_global_contents['ratings'], 2):0)
			];
		
			$json_ld["@graph"][] = [
				"@type" => "CreativeWorkSeries",
				"name" => $block_global_contents['title'],
				"aggregateRating" => [
					"@type" => "AggregateRating",
					"bestRating" => 5,
					"worstRating" => 0,
					"ratingCount" => $block_global_contents['ratings'],
					"ratingValue" => (($block_global_contents['ratings'] != 0) ? round($block_global_contents['score']/$block_global_contents['ratings'], 2):0)
				]
			];
		}
	}

	if($modname == 'Search')
	{
		global $search_query;
		if(isset($search_query) && $search_query != '')
		{
			$meta_tags['breadcrumb'][] = array(
				"@type" => "ListItem",
				"position" => $breadcrumb_pos,
				"item" => array(
					"@type" => "WebPage",
					"@id" => $meta_tags['url'],
					"url" => $meta_tags['url'],
					"name" => _SEARCH." : ".$search_query
				)
			);
			
			$json_ld["@graph"][] = [
				"@type" => "SearchResultsPage",
				"@id" => $meta_tags['url']."#webpage",
				"url" => $meta_tags['url'],
				"inLanguage" => "fa-IR",
				"name" => _SEARCH." : ".$search_query,
				"isPartOf" => [
					"@id" => $nuke_configs['nukeurl']."#website"
				],
				"breadcrumb" => [
					"@id" => $meta_tags['url']."#breadcrumb"
				]
			];
		}
	}
	
	if(isset($meta_tags['breadcrumb']) && !empty($meta_tags['breadcrumb']) && isset($meta_tags['url']) && $meta_tags['url'] != '')
	{
		$json_ld["@graph"][] = [
			"@type" => "BreadcrumbList",
			"@id" => LinkToGT($meta_tags['url'])."#breadcrumb",
			"itemListElement" => $meta_tags['breadcrumb'],
		];
	}
	
	return "\n\t\t<script type=\"application/ld+json\">".json_encode($json_ld, JSON_UNESCAPED_SLASHES)."</script>";
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