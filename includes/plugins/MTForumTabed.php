<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

class MTForumTabed extends users_system
{
	var $result='';
	
	public function __construct($p=1, $forum_mode=1, $search_query="")
	{
		global $db, $nuke_configs, $pn_dbcharset, $users_system, $PnValidator;
		
		if($nuke_configs['have_forum'] != 1)
			return _NO_FORUM_SUPPORTED;
		
		$forum_mode = intval($forum_mode);
		
		if($forum_mode == 6 && (!isset($search_query) || $search_query == ''))
		{
			$this->result = "<p align=\"center\">لطفاً عبارتي براي جستجو وارد کنيد</p>";
			return $this->result;
			die();
		}
		
		if(isset($search_query) && $search_query != '')
		{
			$search_data = array(
				"search_query"	=> $search_query,
			);
		
			$PnValidator->validation_rules(array(
				'search_query'	=> 'required|regex,/([^\>])+$/i'
			)); 
			
			// Get or set the filtering rules
			$PnValidator->filter_rules(array(
				'search_query'	=> 'sanitize_string|rawurldecode|htmlspecialchars',
			)); 

			$search_data = $PnValidator->sanitize($search_data, array(), true, true);
			$validated_data = $PnValidator->run($search_data);

			if($validated_data !== FALSE)
			{
				$search_data = $validated_data;
			}
			else
			{
				$this->result = "<p align=\"center\">لطفاً عبارتي براي جستجو وارد کنيد</p>";
				return $this->result;
				die();
			}		
			$search_query = $search_data['search_query'];
		}
		$where = array();
		$this->result = '';
		$Last_New			= (isset($nuke_configs['forum_last_number']) && intval($nuke_configs['forum_last_number']) > 0) ? $nuke_configs['forum_last_number']:20;
		$from 				= $Last_New * ($p-1);
		$db->query("SET NAMES '".$users_system->collation."'");
		
		$where[] = "t.topic_status <> 2";
		switch($forum_mode)
		{
			case"4":
				$where[] = "t.topic_type = '1'";
			break;
			case"5":
				$where[] = "(t.topic_type = '2' OR t.topic_type = '3')";
			break;
			case"6":
				$where[] = "t.topic_title LIKE '%$search_query%'";
			break;
			default:
				//nothing
			break;
		}
		switch($forum_mode)
		{
			case"2":
				$order_by = "topic_views DESC";
			break;
			case"3":
				$order_by = "topic_posts_approved DESC";
			break;
			default:
				$order_by = "topic_last_post_time DESC";
			break;
		}
		
		$get_last_topic_res = $db->query("SELECT
		t.topic_id, t.forum_id, t.topic_title, t.topic_views, t.topic_type, t.topic_poster, t.topic_first_poster_name, t.topic_last_poster_id, t.topic_last_poster_name, t.topic_last_post_id, t.topic_last_post_time, t.topic_posts_approved,
		u.group_id as first_group_id, uu.group_id as last_group_id
		FROM ".$users_system->topics_table." AS t
		LEFT JOIN ".$users_system->users_table." AS u ON u.user_id = t.topic_poster 
		LEFT JOIN ".$users_system->users_table." AS uu ON uu.user_id = t.topic_last_poster_id 
		WHERE ".implode(" AND ", $where)."
		ORDER BY t.$order_by LIMIT $from, $Last_New");		

		$this->result .= '
		<style>
		.MTForumBlock table {font-family:sans;}
		.MTForumBlock table th {text-align: center;}
		.ui-state-active{color:#fff;}
		</style>
		<table class="table table-sm table-striped table-hover table-responsive text-center table-condensed">
			<thead>
				<tr class="bg-info">
					<th style="text-align:center !important">'._TITLE.'</th>
					<th style="text-align:center !important" class="hidden-xs">'._AUTHOR.'</th>
					<th style="text-align:center !important" class="hidden-xs">'._REPLY.'</th>
					<th style="text-align:center !important" class="hidden-xs">'._VISITS.'</th>
					<th style="text-align:center !important" class="hidden-xs">'._LAST_POST.'</th>
				</tr>
			</thead>
			<tbody>';
			if($db->count() > 0)
			{
				$forum_groups_cacheData = get_cache_file_contents('nuke_forum_groups');
				foreach($get_last_topic_res as $get_last_topic)
				{
					$topic_title 			= filter($get_last_topic['topic_title'], "nohtml");
					$topic_last_post_id 	= intval($get_last_topic['topic_last_post_id']);
					$topic_type 			= intval($get_last_topic['topic_type']);
					$forum_id 				= intval($get_last_topic['forum_id']);
					$topic_id 				= intval($get_last_topic['topic_id']);
					$post_time 				= nuketimes($get_last_topic['topic_last_post_time'], false, false, false, 1);
					$topic_posts_approved	= intval($get_last_topic['topic_posts_approved']);
					$topic_views 			= intval($get_last_topic['topic_views']);
					$first_poster_name 		= filter($get_last_topic['topic_first_poster_name'], "nohtml");
					$first_group_id			= filter($get_last_topic['first_group_id'], "nohtml");
					$last_poster_name 		= filter($get_last_topic['topic_last_poster_name'], "nohtml");
					$last_group_id			= filter($get_last_topic['last_group_id'], "nohtml");
					$topicurl 				= ($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1) ? $users_system->forum_url.str_replace(array("{F}","{T}","{P}"),array($forum_id, $topic_id, $topic_last_post_id), $nuke_configs['forum_seo_post_link']) : "".$users_system->forum_url."viewtopic.php?p=$topic_last_post_id#p$topic_last_post_id";
					$topicurl 			= filter($topicurl, "nohtml");
					
					$first_poster_colour = $forum_groups_cacheData[$first_group_id]['group_colour'];
					$last_poster_colour = $forum_groups_cacheData[$last_group_id]['group_colour'];
					
					$topic_type_colour = ($forum_mode == 1 && $topic_type == 1) ? " style=\"color:#ff0000 !important;\"":(($forum_mode == 1 && ($topic_type == 2 || $topic_type == 3)) ? " style=\"color:#00cc99 !important;\"":"");
				
					$this->result .= '
					<tr>
						<td class="text-'._TEXTALIGN1.'">
							<a href="'.$topicurl.'"'.$topic_type_colour.'><img border="0" src="'.$nuke_configs['nukeurl'].'themes/'.$nuke_configs['ThemeSel'].'/images/MTForumBlock/FBarrow.gif" />&nbsp;'.$topic_title.'</a>
						</td>
						<td class="hidden-xs" style="color:#'.$first_poster_colour.';">'.$first_poster_name.'</td>
						<td class="hidden-xs">'.$topic_posts_approved.'</td>
						<td class="hidden-xs">'.$topic_views.'</td>
						<td class="hidden-xs"><font style="color:#'.$last_poster_colour.';">'.$last_poster_name.'</font></td>
					</tr>';
				}
			}	
		
			$this->result .= '
			</tbody>
		</table>';
		$db->query("SET NAMES '$pn_dbcharset'");
	}
}

global $mtforumtabed;
if(isset($mtforumtabed) && $mtforumtabed != '')
{
	$forum_mode = (isset($forum_mode)) ? $forum_mode:1;
	$search_query = (isset($search_query)) ? $search_query:'';
	$mtforum_tabed = new MTForumTabed($p, $forum_mode, $search_query);
	die($mtforum_tabed->result);
}
?>