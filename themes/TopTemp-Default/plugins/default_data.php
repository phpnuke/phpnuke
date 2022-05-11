<?php

if (!defined('NUKE_FILE')) {
	die ("You can't access this file directly...");
}

function cache_toptemp_default_data()
{
	global $db, $nuke_configs, $cache, $theme_setup, $op;
	
	$op = isset($op) ? filter($op, "nohtml") : "";
	
	if($cache->isCached("toptemp_default_data"))
	{
		$toptemp_default_data = $cache->retrieve("toptemp_default_data");
	}
	else
	{
		$cat_id = !empty($toptemp_default_configs['cat_id']) ? "AND cat_link = ".$toptemp_default_configs['cat_id']." " : '';
		
		$toptemp_default_data = array();

		if($cache->isCached('toptemp_default_data'))
			$cache->erase('toptemp_default_data');

		$toptemp_default_configs = (isset($toptemp_default_configs) && !empty($toptemp_default_configs)) ? $toptemp_default_configs:$theme_setup['toptemp_default_configs'];

		$result = $db->query("
		(SELECT 1 as articles_mode, sid, aid, title, cat_link, time, post_url, comments, counter,hometext, post_image, post_type FROM ".POSTS_TABLE." WHERE status = 'publish' AND post_type = 'Articles' ORDER BY counter ASC LIMIT 0, 3)
		union
		(SELECT 2 as articles_mode, sid, aid, title, cat_link, time, post_url, comments, counter,hometext, post_image, post_type FROM ".POSTS_TABLE." WHERE status = 'publish' AND post_type = 'Articles' ORDER BY counter LIMIT 0, 4)
        union
		(SELECT 3 as articles_mode, sid, aid, title, cat_link, time, post_url, comments, counter,hometext, post_image, post_type FROM ".POSTS_TABLE." WHERE status = 'publish' AND post_type = 'Articles' ORDER BY sid ASC LIMIT 0, 6)
        union
		(SELECT 4 as articles_mode, sid, aid, title, cat_link, time, post_url, comments, counter,hometext, post_image, post_type FROM ".POSTS_TABLE." WHERE status = 'publish' AND post_type = 'Articles' ORDER BY  RAND() LIMIT 0, 5)
        union
		(SELECT 5 as articles_mode, sid, aid, title, cat_link, time, post_url, comments, counter,hometext, post_image, post_type FROM ".POSTS_TABLE." WHERE status = 'publish' AND post_type = 'Articles' AND position  = 2 ORDER BY comments DESC LIMIT 0, 3)
		");
		if(intval($db->count()) > 0)
		{
			$rows = $result->results();
			foreach($rows as $row)
			{
				$articles_mode = $row['articles_mode'];
				$sid = intval($row['sid']);
				$title = filter($row['title'], "nohtml");
				$aid = filter($row['aid']);
				$post_url = filter($row['post_url'], "nohtml");
				$post_type = filter($row['post_type'], "nohtml");
				$time = $row['time'];
				$times = nuketimes($row['time']) ;
				$counter = $row['counter'];
				$comments = $row['comments'];
				$cat_link = $row['cat_link'];
				$post_images = $row['post_image'];
				$hometext = $row['hometext'];
				$post_image =  LinkTOGT(get_article_image($sid, $post_images, $hometext));
				$link = LinkTOGT(articleslink($sid, $title, $post_url, $time, $cat_link, $post_type));
				$toptemp_default_data['articles'][$articles_mode][] = array("title" => $title, "link" => $link, "post_type" => $post_type, "post_url" => $post_url, "sid" => $sid, "counter" => $counter, "comments" => $comments, "mtime" => $time, "time" => $times, "post_image" => $post_image, "cat_link" => $cat_link, "hometext" => $hometext, "aid" => $aid);
			}
			$cache->store('toptemp_default_data', $toptemp_default_data);
		}
	}

	return $toptemp_default_data;
}
$hooks->add_action("post_save_finish", "cache_toptemp_default_data", 10);

function limit_words($string, $word_limit) {
    $string = strip_tags($string);
    $words = explode(' ', strip_tags($string));
    $return = trim(implode(' ', array_slice($words, 0, $word_limit)));
    if(strlen($return) < strlen($string)){
        $return .= "...";
        }
        return $return;
}

?>