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
	
	public function MTForumTabed($p=1, $forum_mode=1, $search_query="")
	{
		global $db, $nuke_configs, $pn_dbcharset, $users_system, $PnValidator;
		
		if($nuke_configs['have_forum'] != 1)
			return _NO_FORUM_SUPPORTED;
		
		$forum_mode = intval($forum_mode);
		
		if($forum_mode == 6 && (!isset($search_query) || $search_query == ''))
		{
			$content = "<p align=\"center\">لطفاً عبارتي براي جستجو وارد کنيد</p>";
			return $content;
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
				$content = "<p align=\"center\">لطفاً عبارتي براي جستجو وارد کنيد</p>";
				return $content;
				die();
			}		
			$search_query = $search_data['search_query'];
		}
		
		$content = '';
		$Last_New			= (isset($nuke_configs['forum_last_number']) && intval($nuke_configs['forum_last_number']) > 0) ? $nuke_configs['forum_last_number']:20;
		$from 				= $Last_New * ($p-1);
		$db->query("SET NAMES '".$users_system->collation."'");
		
		$get_last_topic_res = $db->query("SELECT
		t.topic_id, t.forum_id, t.topic_title, t.topic_views, t.topic_poster, t.topic_last_poster_id, t.topic_last_post_id, t.topic_last_post_time, t.topic_type,
		(SELECT COUNT(p.post_id) FROM ".$users_system->posts_table." AS p WHERE p.topic_id = t.topic_id) AS topic_replies,
		u.username as first_poster_name, uu.username as last_poster_name,
		g.group_colour as first_poster_colour, gg.group_colour as last_poster_colour
		FROM ".$users_system->topics_table." AS t
		LEFT JOIN ".$users_system->users_table." AS u ON u.user_id = t.topic_poster 
		LEFT JOIN ".$users_system->users_table." AS uu ON uu.user_id = t.topic_last_poster_id 
		LEFT JOIN ".$users_system->groups_table." AS g ON g.group_id = u.group_id 
		LEFT JOIN ".$users_system->groups_table." AS gg ON gg.group_id = uu.group_id 
		".(($forum_mode == 4) ? "WHERE topic_type = '1'":"")."
		".(($forum_mode == 5) ? "WHERE topic_type = '2' OR topic_type = '3'":"")."
		".(($forum_mode == 6) ? "WHERE topic_title LIKE '%$search_query%'":"")." 
		ORDER BY ".(($forum_mode == 2) ? "topic_views DESC":(($forum_mode == 3) ? "topic_replies DESC":"topic_last_post_id DESC"))." LIMIT $from,$Last_New");
		
		
		$content .= '
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
				foreach($get_last_topic_res as $get_last_topic)
				{
					$topic_title 			= filter($get_last_topic['topic_title'], "nohtml");
					$topic_last_post_id 	= intval($get_last_topic['topic_last_post_id']);
					$topic_type 			= intval($get_last_topic['topic_type']);
					$forum_id 				= intval($get_last_topic['forum_id']);
					$topic_id 				= intval($get_last_topic['topic_id']);
					$post_time 				= nuketimes($get_last_topic['topic_last_post_time'], false, false, false, 1);
					$topic_replies 			= intval($get_last_topic['topic_replies']);
					$topic_views 			= intval($get_last_topic['topic_views']);
					$first_poster_name 		= filter($get_last_topic['first_poster_name'], "nohtml");
					$first_poster_colour	= filter($get_last_topic['first_poster_colour'], "nohtml");
					$last_poster_name 		= filter($get_last_topic['last_poster_name'], "nohtml");
					$last_poster_colour		= filter($get_last_topic['last_poster_colour'], "nohtml");
					$topicurl 				= ($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1) ? $users_system->forum_url.str_replace(array("{F}","{T}","{P}"),array($forum_id, $topic_id, $topic_last_post_id), $nuke_configs['forum_seo_post_link']) : "".$users_system->forum_url."viewtopic.php?p=$topic_last_post_id#p$topic_last_post_id";
					$topicurl 			= filter($topicurl, "nohtml");
					
					$topic_type_colour = ($forum_mode == 1 && $topic_type == 1) ? " style=\"color:#ff0000 !important;\"":(($forum_mode == 1 && ($topic_type == 2 || $topic_type == 3)) ? " style=\"color:#00cc99 !important;\"":"");
				
					$content .= '
					<tr>
						<td class="text-'._TEXTALIGN1.'">
							<a href="'.$topicurl.'"'.$topic_type_colour.'><img border="0" src="'.$nuke_configs['nukeurl'].'themes/'.$nuke_configs['ThemeSel'].'/images/MTForumBlock/FBarrow.gif" />&nbsp;'.$topic_title.'</a>
						</td>
						<td class="hidden-xs" style="color:#'.$first_poster_colour.';">'.$first_poster_name.'</td>
						<td class="hidden-xs">'.$topic_replies.'</td>
						<td class="hidden-xs">'.$topic_views.'</td>
						<td class="hidden-xs"><font style="color:#'.$last_poster_colour.';">'.$last_poster_name.'</font></td>
					</tr>';
				}
			}	
		
			$content .= '
			</tbody>
		</table>';

		$db->query("SET NAMES '$pn_dbcharset'");
		return $content;
	}
}

global $mtforumtabed;
if(isset($mtforumtabed) && $mtforumtabed != '')
{
	$mtforum_tabed = new MTForumTabed();
	$forum_mode = (isset($forum_mode)) ? $forum_mode:1;
	$search_query = (isset($search_query)) ? $search_query:'';
	die($mtforum_tabed->MTForumTabed($p, $forum_mode, $search_query));
}
?>