<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('NUKE_FILE')) {
	die ("You can't access this file directly...");
}

if((isset($op) && $op == 'article_admin' && isset($submit) && isset($article_fields) && !empty($article_fields)) || !$cache->isCached("caspian_data"))
{
	if($cache->isCached("caspian_data"))
		csrfProtector::authorisePost(true);
	cache_caspian_data();
}

function cache_caspian_data()
{
	global $db, $nuke_configs, $cache, $caspian_configs, $theme_setup;
	
	if($cache->isCached("caspian_data"))
	{
		$caspian_data = $cache->retrieve("caspian_data");
	}
	else
	{
		$caspian_data = array();
		
		if($cache->isCached('caspian_data'))
			$cache->erase('caspian_data');

		$caspian_configs = (isset($caspian_configs) && !empty($caspian_configs)) ? $caspian_configs:$theme_setup['caspian_configs'];
		
		$result = $db->query("
		(SELECT 1 as articles_mode, sid, title, cat_link, time, post_url, comments, counter,hometext, post_image, post_type FROM ".POSTS_TABLE." WHERE status = 'publish' AND post_type = 'Articles' ORDER BY counter DESC LIMIT 0, 5)
		union
		(SELECT 2 as articles_mode, sid, title, cat_link, time, post_url, comments, counter,hometext, post_image, post_type FROM ".POSTS_TABLE." WHERE status = 'publish' AND post_type = 'Articles' ORDER BY comments DESC LIMIT 0, 5)
		");
		if(intval($db->count()) > 0)
		{
			$rows = $result->results();
			foreach($rows as $row)
			{
				$articles_mode = $row['articles_mode'];
				$sid = intval($row['sid']);
				$title = filter($row['title'], "nohtml");
				$post_url = filter($row['post_url'], "nohtml");
				$post_type = filter($row['post_type'], "nohtml");
				$time = $row['time'];
				$times = nuketimes($row['time']) ;
				$counter = $row['counter'];
				$comments = $row['comments'];
				$cat_link = $row['cat_link'];
				$post_images = $row['post_image'];
				$hometext = $row['hometext'];
				$post_image = get_article_image($sid, $post_images, $hometext);
				$link = LinkTOGT(articleslink($sid, $title, $post_url, $time, $cat_link, $post_type));
				$caspian_data['articles'][$articles_mode][] = array("title" => $title, "link" => $link, "post_type" => $post_type, "post_url" => $post_url, "sid" => $sid, "counter" => $counter, "comments" => $comments, "mtime" => $time, "time" => $times, "post_image" => $post_image, "cat_link" => $cat_link, "hometext" => $hometext);
			}
			$cache->store('caspian_data', $caspian_data);
		}
	}
	
	return $caspian_data;
}

?>