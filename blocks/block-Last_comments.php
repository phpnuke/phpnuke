<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/* Block to fit perfectly in the center of the site, remember that not all
   blocks looks good on Center, just try and see yourself what fits your needs */

if ( !defined('BLOCK_FILE') ) {
    Header("Location: ../index.php");
    die();
}

global $nuke_configs, $db, $block_global_contents, $users_system;

$params = array();
$nuke_comments_configs = ($nuke_configs['comments'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['comments'])):array();
	
	$where = array();
	$where_values = array();
	$select2 = "";
	$post_id = (isset($post_id)) ? intval($post_id):0;
	
	$where[] = "c.status =1";
	
	$where = array_filter($where);
	$where = implode(" AND ", $where);
	$where_values = array_filter($where_values, function($v){
		return $v !== false && !is_null($v) && ($v != '' || $v == '0');
	});
	
	$result = $db->query("
		SELECT c.*, IF(c.username = '', '', (SELECT u.".$users_system->user_fields['group_id']." FROM ".$users_system->users_table." AS u WHERE u.".$users_system->user_fields['username']." = c.username)) as group_id,
		(SELECT COUNT(c4.cid) FROM ".COMMENTS_TABLE." AS c4 WHERE ".str_replace("c.","c4.", $where)." AND c4.cid ".(($nuke_comments_configs['order_by'] == 1) ? ">":"<")."= IF(c.main_parent =0, c.cid,c.main_parent) AND c4.pid ='0') as position
		FROM ".COMMENTS_TABLE." AS c 
		WHERE $where 
		ORDER BY c.cid DESC LIMIT 0,5
	", $where_values);
	
	$content = '<ul class="list-group">';
	if($db->count() > 0)
	{
		foreach($result as $row)
		{
			$position = intval($row['position']);
			$cid = intval($row['cid']);
			$pid = intval($row['pid']);
			$group_id = intval($row['group_id']);
			$module = filter($row['module'], "nohtml");
			$post_title = filter($row['post_title'], "nohtml");
			$post_id = intval($row['post_id']);
			$date = nuketimes($row['date'], true, true, true);
			$username = filter($row['username'], "nohtml");
			$name = filter($row['name'], "nohtml");
			$email = filter($row['email'], "nohtml");
			$url = filter($row['url'], "nohtml");
			$ip = filter($row['ip'], "nohtml");
			$shorted = (mb_strlen(strip_tags(stripslashes($row['comment']))) !== mb_strlen(mb_word_wrap(strip_tags(stripslashes($row['comment'])), 200, null, false))) ? true:false;
			$comment = smilies_parse(mb_word_wrap(strip_tags(stripslashes($row['comment'])), 200));
			$ip_info = "http://whatismyipaddress.com/ip/$ip";			
			$username_link = ($username == $ip) ? $ip_info:LinkToGT(sprintf($users_system->profile_url, '', $username));
			$post_link = (isset($nuke_configs['links_function'][$module]) && $nuke_configs['links_function'][$module] != '' && function_exists($nuke_configs['links_function'][$module])) ? $nuke_configs['links_function'][$module]($post_id):"";
			
			if(is_array($post_link))
				$post_link = $post_link[0];
				
			$current_comment_page = ($nuke_comments_configs['item_per_page'] > 0) ? ceil($position/$nuke_comments_configs['item_per_page']):0;
			$comment_link = $post_link;
			if($current_comment_page != 0 && $current_comment_page != 1)
			{
				$comment_link = trim($comment_link,"/");
				if($nuke_configs['gtset'] == 1)
					$comment_link .= "/comment-page-$current_comment_page/";
				else
					$comment_link .= "?page=$current_comment_page";
			}
			$url_arr = ($url != '') ? explode("/", $url):array();
			$clean_url = is_array($url_arr) && !empty($url_arr) ? $url_arr[2]:'';
			
			if($nuke_configs['have_forum'] == 1 && $group_id != 0)
			{
				$forum_groups_cacheData = get_cache_file_contents('nuke_forum_groups');
				if(isset($forum_groups_cacheData[$group_id]))
				{
					$user_colour = "#".str_replace("#","",$forum_groups_cacheData[$group_id]['group_colour']);
					$group_name = $forum_groups_cacheData[$group_id]['group_name'];
				}
			}
			else
			{
				$user_colour = "#000000";
				$group_name = "GUESTS";
			}
			
			$user_level = ($group_name == 'ADMINISTRATORS') ? "<span style=\"font-weight:bold;color:".$user_colour."\">مدير کل سايت : </span>":(($username != '') ? "کاربر سايت : ":"مهمان سايت : ");
			
			$content .= "
				<li class=\"list-group-item\">
				<span class=\"user\" style=\"margin-bottom:8px;\"><i class=\"glyphicon glyphicon-user\"></i> <a href=\"".$url."\" target=\"_blank\">$user_level<span style=\"font-weight:bold;color:".$user_colour."\">".$name."</span></a></span>
				<p class=\"clear\" align=\"justify\"><i class=\"fa fa-comment\" aria-hidden=\"true\"></i> ".$comment." ";
				if($shorted)
				{
					$content .= "<a href=\"".LinkToGT($comment_link."#comment-$cid")."\" target=\"_blank\">"._MORE."</a>";
				}
				$content .= "</p>
				در : <a href=\"".LinkToGT($post_link)."\" target=\"_blank\">$post_title </a>
				</li>
				";
		}
	}
	$content .="</ul>";

?>