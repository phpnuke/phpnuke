<?php

if (!defined('NUKE_FILE')) {
	die ("You can't access this file directly...");
}

function cache_caspian_data()
{
	global $db, $nuke_configs, $cache, $theme_setup, $op;
	
	$op = isset($op) ? filter($op, "nohtml") : "";
	
	if($cache->isCached("caspian_data") && ((_NOWTIME-$cache->retrieve("caspian_data", true)) <= 3600) && $op != 'article_admin')
	{
		$caspian_data = $cache->retrieve("caspian_data");
	}
	else
	{
		$caspian_data = array();
		
		if($cache->isCached('caspian_data'))
			$cache->erase('caspian_data');

		$caspian_configs = $theme_setup['caspian_configs'];
		
		$result = $db->query("
		(SELECT 1 as articles_mode, sid, title, cat_link, time, post_url, comments, counter,hometext, post_image, post_type FROM ".POSTS_TABLE." WHERE status = 'publish' AND post_type = 'Articles' AND ihome = '1' ORDER BY counter DESC LIMIT 0, 5)
		union
		(SELECT 2 as articles_mode, sid, title, cat_link, time, post_url, comments, counter,hometext, post_image, post_type FROM ".POSTS_TABLE." WHERE status = 'publish' AND post_type = 'Articles' AND ihome = '1' ORDER BY comments DESC LIMIT 0, 5)
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
$hooks->add_action("post_save_finish", "cache_caspian_data", 10);

?>