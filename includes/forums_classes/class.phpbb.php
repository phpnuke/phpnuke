<?php

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

if(!defined('ANONYMOUS'))
	define('ANONYMOUS', 1);
define('USER_NORMAL', 0);
define('USER_INACTIVE', 1);
define('USER_IGNORE', 2);
define('USER_FOUNDER', 3);
// referer validation
define('REFERER_VALIDATE_NONE', 0);
define('REFERER_VALIDATE_HOST', 1);
define('REFERER_VALIDATE_PATH', 2);

class users_system{
	
	var $user_fields = array();
	var $cookie_data = array();
	var $page = array();
	var $data = array();
	var $ranks = array();
	var $config = array();
	var $browser = '';
	var $forwarded_for = '';
	var $host = '';
	var $session_id = '';
	var $ip = '';
	var $load = 0;
	var $time_now = 0;
	var $update_session_page = true;

	var $ipv4_regex = '#^(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$#';
	var $ipv6_regex = '#^(?:(?:(?:[\dA-F]{1,4}:){6}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:::(?:[\dA-F]{1,4}:){0,5}(?:[\dA-F]{1,4}(?::[\dA-F]{1,4})?|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:):(?:[\dA-F]{1,4}:){4}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,2}:(?:[\dA-F]{1,4}:){3}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,3}:(?:[\dA-F]{1,4}:){2}(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,4}:(?:[\dA-F]{1,4}:)(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,5}:(?:[\dA-F]{1,4}:[\dA-F]{1,4}|(?:(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d{1,2}|1\d\d|2[0-4]\d|25[0-5])))|(?:(?:[\dA-F]{1,4}:){1,6}:[\dA-F]{1,4})|(?:(?:[\dA-F]{1,4}:){1,7}:)|(?:::))$#i';
		
	protected $strip;

	public function __construct()
	{
		global $db, $nuke_configs, $pn_dbcharset;
		
		$this->strip = false;
		
		// define default user fileds of phpbb system
		$this->user_fields['user_id']				= "user_id";
		$this->user_fields['username']				= "username";
		$this->user_fields['realname']				= $this->user_fields['name'] = "username_clean";
		$this->user_fields['user_password']			= "user_password";
		$this->user_fields['user_birthday']			= "user_birthday";
		$this->user_fields['user_lastvisit']		= "user_lastvisit";
		$this->user_fields['user_avatar']			= "user_avatar";
		$this->user_fields['user_avatar_type']		= "user_avatar_type";
		$this->user_fields['user_avatar_width']		= "user_avatar_width";
		$this->user_fields['user_avatar_height']	= "user_avatar_height";
		$this->user_fields['user_email']			= "user_email";
		$this->user_fields['username_clean']		= "username_clean";
		$this->user_fields['user_website']			= "phpbb_website";
		$this->user_fields['user_group_id']			= "group_id";
		$this->user_fields['user_regdate']			= "user_regdate";
		$this->user_fields['user_gender']			= "user_gender";
		$this->user_fields['user_points']			= "user_points";
		$this->user_fields['user_colour']			= "user_colour";
		$this->user_fields['user_credit']			= "user_credit";
		
		// define default topics fileds of phpbb system
		$this->user_fields['topic_id']				= "topic_id";
		$this->user_fields['forum_id']				= "forum_id";
		$this->user_fields['topic_title']			= "topic_title";
		$this->user_fields['topic_poster']			= "topic_poster";
		$this->user_fields['topic_views']			= "topic_views";
		$this->user_fields['topic_status']			= "topic_status";
		$this->user_fields['topic_last_post_id']	= "topic_last_post_id";
		$this->user_fields['topic_last_poster_id']	= "topic_last_poster_id";
		
		// define default forum_name fileds of phpbb system
		$this->user_fields['forum_name']			= "forum_name";
		$this->user_fields['forum_id']				= "forum_id";
		
		// define default groups fileds of phpbb system
		$this->user_fields['group_id']				= "group_id";
		$this->user_fields['group_type']			= "group_type";
		$this->user_fields['group_name']			= "group_name";
		$this->user_fields['group_colour']			= "group_colour";
		
		// define default posts fileds of phpbb system
		$this->user_fields['post_id']				= "post_id";
		$this->user_fields['poster_id']				= "poster_id";
		$this->user_fields['poster_ip']				= "poster_ip";
		$this->user_fields['post_time']				= "post_time";
		$this->user_fields['post_subject']			= "post_subject";
		$this->user_fields['post_text']				= "post_text";
		$this->user_fields['post_postcount']		= "post_postcount";
		$this->user_fields['post_visibility']		= "post_visibility";
		
		
		$this->user_fields['common_where']			= "user_type != '2' AND user_id > '1'";
		
		$this->forum_db						= $nuke_configs['forum_db'];
		$this->configs_table				= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."config`";
		$this->users_table					= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."users`";
		$this->user_group					= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."user_group`";
		$this->user_notifications_table		= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."user_notifications`";
		$this->profile_fields_data_table	= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."profile_fields_data`";
		$this->bots_table					= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."bots`";
		$this->sessions_table				= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."sessions`";
		$this->sessions_keys_table			= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."sessions_keys`";
		$this->topics_table					= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."topics`";
		$this->posts_table					= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."posts`";
		$this->forums_table					= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."forums`";
		$this->groups_table					= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."groups`";
		$this->ranks_table					= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."ranks`";
		$this->login_attempts_table			= "`".$this->forum_db."`.`".$nuke_configs['forum_prefix']."login_attempts`";
		
		$extra_cache_codes = $this->cache_system();
		
		if(!empty($extra_cache_codes))
			cache_system('',$extra_cache_codes);
			
		$this->config					= get_cache_file_contents('nuke_phpbb3_configs');
		$this->ranks					= get_cache_file_contents('nuke_phpbb3_ranks');
		$this->config['script_path']	= trim($this->config['script_path'],"/")."/";
		$this->collation				= (isset($nuke_configs['forum_collation'])) ? $nuke_configs['forum_collation']:"latin1";
		$this->server_name				= $this->config['server_name'];
		$this->server_protocol			= $this->config['server_protocol'];
		$this->forum_path				= $this->config['script_path'];
		$this->forum_url				= $this->config['server_protocol'].$this->config['server_name']."/".$this->config['script_path'];
		
		$this->profile_url 				= $this->forum_url.(($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1 && isset($nuke_configs['forum_seo_profile_link']) && $nuke_configs['forum_seo_profile_link'] != '') ? str_replace(array("{UID}","{UN}"),array('%1$d','%2$s'), $nuke_configs['forum_seo_profile_link']):'memberlist.php?mode=viewprofile&u=%1$d');
		$this->ucp_url 					= $this->forum_url.(($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1 && isset($nuke_configs['forum_seo_ucp_link']) && $nuke_configs['forum_seo_ucp_link'] != '') ? str_replace(array("{UID}","{UN}"),array('%1$d','%2$s'), $nuke_configs['forum_seo_ucp_link']):'ucp.php');
		$this->register_url				= $this->forum_url.(($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1 && isset($nuke_configs['forum_seo_register_link']) && $nuke_configs['forum_seo_register_link'] != '') ? str_replace(array("{UID}","{UN}"),array('%1$d','%2$s'), $nuke_configs['forum_seo_register_link']):'ucp.php?mode=register');
		$this->passlost_url				= $this->forum_url.(($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1 && isset($nuke_configs['forum_seo_passlost_link']) && $nuke_configs['forum_seo_passlost_link'] != '') ? $nuke_configs['forum_seo_passlost_link']:'ucp.php?mode=sendpassword');
		$this->forum_admin_path			= $this->config['script_path']."adm/index.php";
		
		if(!defined("IN_INSTALL"))
		{
			$this->session_begin();
			$this->update_session_infos();
			
			foreach($this->user_fields as $key => $value)
				if(!isset($this->data[$key]) && isset($this->data[$value]))
					$this->data[$key] = $this->data[$value];
			
			if(!isset($this->data['user_credit']))
			{
				$result = $db->query("DESCRIBE ".$this->users_table."");
				$rows = $result->results();
				$rows = phpnuke_array_change_key($rows, "", "Field");
				$columns = array_keys($rows);
				if(!in_array("user_credit", $columns))
					$db->query("ALTER TABLE ".$this->users_table." ADD `user_credit` bigint(20) UNSIGNED NOT NULL DEFAULT '0'");
				$this->data['user_credit'] = 0;
			}
		}
				
		$db->query("SET NAMES '".$pn_dbcharset."'");
	}
	
	public function MTForumBlock($p=1)
	{
		global $db, $nuke_configs, $pn_dbcharset;
		$content = '';
		$Last_New			= (isset($nuke_configs['forum_last_number']) && intval($nuke_configs['forum_last_number']) > 0) ? $nuke_configs['forum_last_number']:20;
		$from 				= $Last_New * ($p-1);
		$db->query("SET NAMES '".$this->collation."'");
		
		$get_last_topic_res = $db->query("SELECT topic_id, forum_id, topic_title, topic_views, topic_poster, topic_first_poster_name, topic_last_poster_id, topic_last_poster_name, topic_last_poster_colour, topic_last_post_id, topic_last_post_time, topic_posts_approved
		FROM ".$this->topics_table."
		ORDER BY topic_last_post_time DESC LIMIT $from,$Last_New"
		);
		
		$content .= '
		<style>
		#MTForumBlock table th {text-align: center;}
		</style>
		<table class="table table-sm table-striped table-hover table-responsive text-center table-condensed">
			<thead>
				<tr class="bg-info">
					<th style="text-align:center !important">عنوان</th>
					<th style="text-align:center !important" class="hidden-xs">نويسنده</th>
					<th style="text-align:center !important" class="hidden-xs">پاسخ</th>
					<th style="text-align:center !important" class="hidden-xs">بازديد</th>
					<th style="text-align:center !important" class="hidden-xs">آخرين ارسال</th>
				</tr>
			</thead>
			<tbody>';
			if($db->count() > 0)
			{
				foreach($get_last_topic_res as $get_last_topic)
				{
					$topic_title 		= filter($get_last_topic['topic_title'], "nohtml");
					$topic_last_post_id = intval($get_last_topic['topic_last_post_id']);
					$forum_id 			= intval($get_last_topic['forum_id']);
					$topic_id 			= intval($get_last_topic['topic_id']);
					$poster_id 			= intval($get_last_topic['topic_last_poster_id']);
					$post_time 			= nuketimes($get_last_topic['topic_last_post_time'], false, false, false, 1);
					$topic_replies 		= intval($get_last_topic['topic_posts_approved']);
					$topic_views 		= intval($get_last_topic['topic_views']);
					$first_poster 		= filter($get_last_topic['topic_first_poster_name'], "nohtml");
					$username 			= filter($get_last_topic['topic_last_poster_name'], "nohtml");
					$user_id 			= intval($get_last_topic['topic_last_poster_id']);
					$group_colour 		= filter($get_last_topic['topic_last_poster_colour'], "nohtml");
					$group_colour 		= (empty($group_colour)) ? '000000' :  $group_colour;
					$topicurl 			= ($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1) ? $this->forum_url.str_replace(array("{F}","{T}","{P}"),array($forum_id, $topic_id, $topic_last_post_id), $nuke_configs['forum_seo_post_link']) : "".$this->forum_url."viewtopic.php?p=$topic_last_post_id#p$topic_last_post_id";
					$topicurl 			= filter($topicurl, "nohtml");
					
					$content .= '
					<tr>
						<td class="text-'._TEXTALIGN1.'">
							<a href="'.$topicurl.'"><img border="0" src="'.$nuke_configs['nukeurl'].'themes/'.$nuke_configs['ThemeSel'].'/images/MTForumBlock/FBarrow.gif" />&nbsp;'.$topic_title.'</a>
						</td>
						<td class="hidden-xs">'.$first_poster.'</td>
						<td class="hidden-xs">'.$topic_posts_approved.'</td>
						<td class="hidden-xs">'.$topic_views.'</td>
						<td class="hidden-xs"><font style="color:#'.$group_colour.';">'.$username.'</font></td>
					</tr>';
				}
			}
		
		
			$content .= '
			</tbody>
		</table>';

		$db->query("SET NAMES '$pn_dbcharset'");
		return $content;
	}
		
	public function user_statistics()
	{
		
		global $db, $nuke_configs, $HijriCalendar, $userinfo, $users_system, $currentpage, $pn_dbcharset, $cache;

		$showpms = 1; //1 to Show Private Messages data - 0 is off
		$showmost = 1; //1 to Show Mostonline data - 0 is off
		$useavatars = 1; //1 to Show Avatars - 0 is off
		$use_ranks = 1; //1 to Show Ranks - 0 is off
		$showonlyadmin = 1; //1 to show all users where the online users are - 0 only admin sees
		$showpoints = 1; //1 to Show Points data - 0 is off
		$show_great_user = 1; //1 to Show IP of viewers - 0 is off
		$showips = 1; //1 to Show IP of viewers - 0 is off
		//Lastuser Name
		$viewers_ip = array();
		$greet_guest = "";
		$greet_user = "";
		$your_avatar = "";
		$your_new_privmsg = 0;
		$your_unread_privmsg = 0;
		$login_form = array();
		$your_rank_data = array();
		$your_points = 0;
		
		$online_users = array(
			'online_users'			=> array(),
			'guests_ips'			=> array(),
			'total_online'			=> 0,
			'members_online'		=> 0,
			'visible_online'		=> 0,
			'hidden_online'			=> 0,
			'guests_online'			=> 0,
		);		
		
		$time = (_NOWTIME - (intval($this->config['load_online_time']) * 60));

		$db->query("SET NAMES '".$this->collation."'");
		//Registered users online
		$result = $db->query("SELECT DISTINCT 
		s.session_id, s.session_user_id, s.session_ip, s.session_viewonline, s.session_page,
		(SELECT COUNT(DISTINCT session_ip) FROM ".$this->sessions_table." WHERE session_user_id = '".ANONYMOUS."' AND session_time >= " . ($time - ((int) ($time % 60))).") AS num_guests,
		u.username, u.user_type, u.group_id, u.user_posts
		FROM ".$this->sessions_table." AS s 
		LEFT JOIN ".$this->users_table." AS u ON u.user_id = s.session_user_id
		WHERE s.session_time >= " . ($time - ((int) ($time % 60)))."
		GROUP BY s.session_id
		ORDER by u.user_type DESC, u.user_id ASC
		");
		$forum_groups_cacheData = get_cache_file_contents('nuke_forum_groups');
		//Assemble the online registered users
		$your_info = array();
		$rank_title = '';
		$i = 1;
		if($db->count() > 0)
		{
			foreach ($result as $row)
			{
				$online_users['guests_online'] = intval($row['num_guests']);
				
				$user_type = intval($row['user_type']);
				//if($user_type == 2) continue;
				
				$session_id = intval($row['session_id']);
				$session_user_id = intval($row['session_user_id']);
				$session_viewonline = intval($row['session_viewonline']);
				$session_ip = $row['session_ip'];
				$session_page = $row['session_page'];
				
				$username = filter($row['username'], "nohtml");
				$user_posts = intval($row['user_posts']);
				
				$group_colour = (isset($row['group_id']) && $row['group_id'] != '' && isset($forum_groups_cacheData[$row['group_id']])) ? str_replace("#", "", $forum_groups_cacheData[$row['group_id']]['group_colour']):"000000";
				
				if($this->session_id == $session_id)
					$your_info = $row;
					
				$viewer_ip = '';
				$filtered_ip = '';
				
				if($showips == 1)
				{
					$viewer_ip_bank = explode('.', $session_ip);
					for($j=2;$j < count($viewer_ip_bank);$j++)
						$viewer_ip_bank[$j] = "***";
						
					$filtered_ip = implode(".",$viewer_ip_bank);
					
					if(is_admin())
						$viewer_ip = "<a href=\"https://whatismyipaddress.com/ip/".$session_ip."\" target=\"_blank\" style=\"color:#".$group_colour." !important;\">".$session_ip."</a>";
					else
						$viewer_ip = $filtered_ip;
				}
				if(filter_var( $session_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ))
				{
					$viewer_ip_bank = explode(':', $session_ip);
					for($j=4;$j < count($viewer_ip_bank);$j++)
						unset($viewer_ip_bank[$j]);
						
					$filtered_ip = implode(":",$viewer_ip_bank);
					
					if(is_admin())
						$viewer_ip = "<a href=\"https://whatismyipaddress.com/ip/".$session_ip."\" target=\"_blank\" style=\"color:#".$group_colour." !important;\">".$filtered_ip."</a>";
					else
						$viewer_ip = $filtered_ip;
				}
				
				// Skip multiple sessions for one user
				if($session_user_id != ANONYMOUS && $user_type != 2)
				{
					if (!isset($online_users['online_users'][$session_user_id]))
					{
						$czi = correct_date_number($i);
						
						if (((is_admin()) && ($showonlyadmin == 0)) || $showonlyadmin == 1 && $session_page != '')
							$where = "<a href=\"".$this->forum_url."$session_page\">$czi</a>";
						else
							$where = "$czi";
						
						$online_users['online_users'][$session_user_id] = array(
							"where" => $where,
							"profile" => sprintf($this->profile_url, $session_user_id, $username),
							"username" => $username,
							"group_colour" => $group_colour,
							"user_posts" => $user_posts,
							"ip" => $session_ip,
							"short_ip" => $filtered_ip,
							"viewer_ip" => $viewer_ip,
							"hidden" => ((!$session_viewonline) ? true:false),
						);
					
						if ($session_viewonline)
							$online_users['visible_online']++;
						else
							$online_users['hidden_online']++;
							
						$online_users['members_online']++;
					
						$i++;
					}
				}
				elseif($viewer_ip != '')
				{

					if (!isset($online_users['guests_ips'][$session_ip]))
					{
						$online_users['guests_ips'][$session_ip] = $viewer_ip;
					}
				}
			}
		}
		$online_users['total_online'] = $online_users['guests_online'] + $online_users['visible_online'] + $online_users['hidden_online'];
		
		$your_profile_url = '';
		$logout_url = $this->forum_url.(($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1 && isset($nuke_configs['forum_seo_logout_link']) && $nuke_configs['forum_seo_logout_link'] != '') ? $nuke_configs['forum_seo_logout_link']:'ucp.php?mode=logout&sid='.$this->session_id);
		
		if (is_user())
		{
			if($show_great_user)
			{
				//Greet User
				$username = $this->data['username'];
				$now = gmdate ('H');
				if ($now < 12)
					$greet_user =  ""._GOODMORNINGUSER." $username";
				else if ($now < 18)
					$greet_user =  ""._GOODAFTERNOONUSER." $username";
				else if ($now >= 18 )
					$greet_user =  ""._GOODEVENINGUSER." $username";
			}
			
			if ($useavatars == 1)
				$your_avatar = $this->get_avatar_url($this->data, $this->data['user_avatar_width'], $this->data['user_avatar_height']);
				
			if ($use_ranks == 1)
				$your_rank_data = $this->phpbb_get_user_rank($this->data, $this->data['user_posts']);

			if ($showpoints == 1 && isset($this->data['user_points']))
				$your_points = number_format($this->data['user_points']);

			if ($showpms == 1)
			{
				$pm_url = $this->forum_url.(($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1 && isset($nuke_configs['forum_seo_pm_link']) && $nuke_configs['forum_seo_pm_link'] != '') ? $nuke_configs['forum_seo_pm_link']:'ucp.php?i=pm&folder=inbox');
		
				$your_new_privmsg = "<a title=\""._CHECKPMS."\" href=\"$pm_url\">".$this->data['user_new_privmsg']."</a>";
				$your_unread_privmsg = "<a title=\""._UNREADPM."\" href=\"$pm_url\">".$this->data['user_unread_privmsg']."</a>";
			}
			//$your_profile_url = sprintf($this->profile_url, $this->data['user_id'], $this->data['username']);
			$your_profile_url = $this->ucp_url;
		}
		else
		{
			//Greet Guest
			$now = gmdate ('H');
			if ($now < 12)
				$greet_guest =  ""._GOODMORNINGGUEST."";
			else if ($now < 18)
				$greet_guest =  ""._GOODAFTERNOONGUEST."";
			else if ($now >= 18 )
				$greet_guest =  ""._GOODEVENINGGUEST."";
			
			$login_form = $this->get_login_form_data();
		}
		
		$statistics_data = ($cache->isCached("statistics_data")) ? $cache->retrieve("statistics_data"):array();
			
		$statistics_data['update_time'] = isset($statistics_data['update_time']) ? $statistics_data['update_time']:0;
		
		if((_NOWTIME-$statistics_data['update_time']) >= ((isset($nuke_configs['statistics_refresh']) && $nuke_configs['statistics_refresh'] != '') ? $nuke_configs['statistics_refresh']:3600))
		{
			$today_year = date("Y");
			$today_month = date("m");
			$today_day = date("d");
			
			$nowday = mktime(0,0,0,$today_month,$today_day,$today_year);
			$yesterday = $nowday-86400;
			
			$yesterday_year = date("Y", $yesterday);
			$yesterday_month = date("m", $yesterday);
			$yesterday_day = date("d", $yesterday);
			
			$result = $db->query("SELECT DISTINCT
				var, count, 
				(SELECT COUNT(user_id) FROM ".$this->users_table." WHERE user_regdate > ? AND user_type != '2') as today_register,
				(SELECT COUNT(user_id) FROM ".$this->users_table." WHERE (user_regdate > ? AND user_regdate < ? AND user_type != '2')) as yesterday_register,
				(SELECT user_id FROM ".$this->users_table." WHERE user_type != '2' ORDER BY user_id DESC LIMIT 0,1) as last_user_id,
				(SELECT username FROM ".$this->users_table." WHERE user_type != '2' ORDER BY user_id DESC LIMIT 0,1) as last_username,
				(SELECT g.group_colour FROM ".$this->users_table." as u LEFT JOIN ".$this->groups_table." AS g ON g.group_id = u.group_id WHERE u.user_type != '2' ORDER BY u.user_id DESC LIMIT 0,1) as last_user_colour,						
				(SELECT COUNT(user_id) FROM ".$this->users_table." WHERE user_id > '1' AND user_type != '2') as total_users,
				(SELECT hits FROM ".STATISTICS_TABLE." WHERE year=? AND month=? AND day=? ORDER BY id DESC LIMIT 1) as today_visits,
				(SELECT hits FROM ".STATISTICS_TABLE." WHERE year=? AND month=? AND day=? ORDER BY id DESC LIMIT 1) as yesterday_visits,
				(SELECT count FROM ".STATISTICS_COUNTER_TABLE." WHERE type='total') as total_visits
				From ".STATISTICS_COUNTER_TABLE." WHERE type = 'mosts'
			", array($nowday, $yesterday, $nowday, $today_year, $today_month, $today_day, $yesterday_year, $yesterday_month, $yesterday_day));
			$total_visits = 0;

			if($db->count() > 0)
			{
				foreach($result as $row)
				{
					if($total_visits == 0)
					{
						$today_register			= intval($row['today_register']);
						$yesterday_register		= intval($row['yesterday_register']);
						$last_user_id			= intval($row['last_user_id']);
						$last_username			= filter($row['last_username'], "nohtml");
						$last_user_colour		= filter($row['last_user_colour'], "nohtml");
						$last_user_profile_url	= sprintf($this->profile_url, $last_user_id, $last_username);
						$total_users			= intval($row['total_users']);
						$today_visits			= intval($row['today_visits']);
						$yesterday_visits		= intval($row['yesterday_visits']);
						$total_visits			= intval($row['total_visits']);
					}
					$var = $row['var'];
					if($var == "total")
						$total_mostonline	= $row['count'];
					if($var == "members")
						$total_members		= $row['count'];
					if($var == "guests")
						$total_guests		= $row['count'];
				}
				$statistics_data = array(
					"today_register" => $today_register,
					"yesterday_register" =>	$yesterday_register,
					"last_user_id" => $last_user_id,
					"last_username" => $last_username,
					"last_user_colour" => $last_user_colour,
					"last_user_profile_url" => $last_user_profile_url,
					"total_users" => $total_users,
					"today_visits" => $today_visits,
					"yesterday_visits" => $yesterday_visits,
					"total_visits" => $total_visits,
					"total_mostonline" => $total_mostonline,
					"total_members" => $total_members,
					"total_guests" => $total_guests,
					"update_time" => _NOWTIME
				);				
				$cache->store("statistics_data", $statistics_data);
			}
		}
		else
		{
			foreach($statistics_data as $key => $value)
				$$key = $value;
		}
		
		//Break Mostonline Total?
		if ($total_mostonline < $online_users['total_online'])
		{
			$db->query("UPDATE ".STATISTICS_COUNTER_TABLE." SET count = CASE
					WHEN var = 'total' THEN '".$online_users['total_online']."'
					WHEN var = 'members' THEN '".$online_users['members_online']."'
					WHEN var = 'guests' THEN '".$online_users['guests_online']."'
					WHEN var = 'date' THEN '"._NOWTIME."'
				END
				WHERE type = 'mosts' AND var IN ('total', 'members', 'guests', 'date')
			");
		}
		
		$statistics = array(
			"wellcom_user"			=> $greet_user,
			"user_avatar_image"		=> $your_avatar,
			"user_rank"				=> $your_rank_data,
			"wellcom_guest"			=> $greet_guest,
			"login_form"			=> $login_form,
			"user_points"			=> $your_points,
			"user_new_privmsg"		=> $your_new_privmsg,
			"user_unread_privmsg"	=> $your_unread_privmsg,
			"user_profile_url"		=> $your_profile_url,
			
			"user_logout_url"		=> $logout_url,
			
			"online_members"		=> $online_users['online_users'],
			"online_gusts"			=> $online_users['guests_ips'],
			"hidden_online"			=> $online_users['hidden_online'],
			"online_guests_num"		=> $online_users['guests_online'],
			"total_onlines"			=> $online_users['total_online'],
			
			"total_mostonline"		=> $total_mostonline,
			"total_members"			=> $total_members ,
			"total_gusts"			=> $total_guests,
			
			"today_register"		=> $today_register,
			"yesterday_register"	=> $yesterday_register,
			
			"last_user_id"			=> $last_user_id,
			"last_user_colour"		=> $last_user_colour,
			"last_username"			=> $last_username,
			"last_user_profile_url"	=> $last_user_profile_url,
			
			"total_users"			=> $total_users,
			"today_visits"			=> $today_visits,
			"yesterday_visits"		=> $yesterday_visits,
			"total_visits"			=> $total_visits,
		);
		
		$db->query("SET NAMES '".$pn_dbcharset."'");
		phpnuke_db_error();
		return $statistics;
	}
	
	/*public function add_user($user_data, $extra_data)
	{
		global $db, $nuke_configs, $userinfo, $users_system, $currentpage, $pn_dbcharset;
		
		$result1 = $db->query("SELECT group_id FROM ".$this->groups_table." WHERE group_name = 'REGISTERED'");
		$rows = $result1->results();
		$group_id = (isset($rows[0])) ? $rows[0]['group_id']:2;
		
		$insert_query = array(
			'user_type' => 0, 
			'group_id' => $group_id, 
			'user_regdate' => $user_data['user_regdate'], 
			'username' => $user_data['username'], 
			'username_clean' => $user_data['username'], 
			'user_password' => $user_data['user_pass'], 
			'user_email' => $user_data['user_email'], 
			'user_lang' => $user_data['user_lang'],
		);
		
		if(isset($extra_data) && !empty($extra_data))
		{
			$insert_query = array_merge($insert_query,$extra_data);
		}
		
		$db->table($this->users_table)
			->insert($insert_query);
			
		if(!$db->error)
		{
			$user_id = $db->lastInsertId();
			$db->table($this->user_group)
				->insert([
					"group_id" => $group_id,
					"user_id" => $user_id,
					"user_pending" => 0
				]);
			
			$db->table($this->user_notifications_table)
				->insert([
					"item_type" => 'notification.type.post',
					"item_id" => 0,
					"user_id" => $user_id,
					"method" => 'notification.method.email',
					"notify" => 1,
				]);
			
			$db->table($this->user_notifications_table)
				->insert([
					"item_type" => 'notification.type.topic',
					"item_id" => 0,
					"user_id" => $user_id,
					"method" => 'notification.method.email',
					"notify" => 1,
				]);
			
			$db->table($this->profile_fields_data_table)
				->insert([
					"user_id" => $user_id,
					"pf_phpbb_website" => $user_data['user_website']
				]);
				
			$db->table($this->configs_table)
				->multiinsert(["config_name","config_value","is_dynamic"], array(
					array('newest_user_colour', '', 1),
					array('newest_user_id', '50', 1),
					array('newest_username', 'mahmood', 1),
					array('num_users', '4', 1)
				));
			return true;
		}
		return false;
	}*/
	
	public function clean_path($path)
	{
		$exploded = explode('/', $path);
		$filtered = array();
		foreach ($exploded as $part)
		{
			if ($part === '.' && !empty($filtered))
			{
				continue;
			}

			if ($part === '..' && !empty($filtered) && $filtered[sizeof($filtered) - 1] !== '.' && $filtered[sizeof($filtered) - 1] !== '..')
			{
				array_pop($filtered);
			}
			else
			{
				$filtered[] = $part;
			}
		}
		$path = implode('/', $filtered);
		return $path;
	}
	
	public function phpbb_get_user_rank($user_data = array(), $user_posts = false)
	{
		global $nuke_config;

		$user_rank_data = array(
			'title'		=> null,
			'img'		=> null,
			'img_src'	=> null,
		);

		/**
		* Preparing a user's rank before displaying
		*
		* @event core.modify_user_rank
		* @var	array	user_data		Array with user's data
		* @var	int		user_posts		User_posts to change
		* @since 3.1.0-RC4
		*/

		if (empty($this->ranks))
		{
			$this->ranks = cache_system("nuke_phpbb3_ranks");
		}

		if (!empty($user_data['user_rank']))
		{

			$user_rank_data['title'] = (isset($this->ranks['special'][$user_data['user_rank']]['rank_title'])) ? $this->ranks['special'][$user_data['user_rank']]['rank_title'] : '';

			$user_rank_data['img_src'] = (!empty($this->ranks['special'][$user_data['user_rank']]['rank_image'])) ? $this->forum_path . $this->config['ranks_path'] . '/' . $this->ranks['special'][$user_data['user_rank']]['rank_image'] : '';

			$user_rank_data['img'] = (!empty($this->ranks['special'][$user_data['user_rank']]['rank_image'])) ? '<img src="' . $user_rank_data['img_src'] . '" alt="' . $this->ranks['special'][$user_data['user_rank']]['rank_title'] . '" title="' . $this->ranks['special'][$user_data['user_rank']]['rank_title'] . '" />' : '';
		}
		else if ($user_posts !== false)
		{
			if (!empty($this->ranks['normal']))
			{
				foreach ($this->ranks['normal'] as $rank)
				{
					if ($user_posts >= $rank['rank_min'])
					{
						$user_rank_data['title'] = $rank['rank_title'];
						$user_rank_data['img_src'] = (!empty($rank['rank_image'])) ? $this->forum_path . $this->config['ranks_path'] . '/' . $rank['rank_image'] : '';
						$user_rank_data['img'] = (!empty($rank['rank_image'])) ? '<img src="' . $user_rank_data['img_src'] . '" alt="' . $rank['rank_title'] . '" title="' . $rank['rank_title'] . '" />' : '';
						break;
					}
				}
			}
		}

		return $user_rank_data;
	}
	
	public function get_avatar_url($row, $width=100, $height=100, $ignore_config=false)
	{
		$avatar = strtolower(trim($row['user_avatar']));
		$avatar_type = $row['user_avatar_type'];
		
		if($avatar_type == 'avatar.driver.upload')
			$avatar_url = $this->forum_url."download/file.php?avatar=$avatar";
		elseif($avatar_type == 'avatar.driver.local')
			$avatar_url = $this->forum_url.$this->config['avatar_gallery_path']."/$avatar";
		elseif($avatar_type == 'avatar.driver.remote')
			$avatar_url = $avatar;
		elseif($avatar_type == 'avatar.driver.gravatar')
		{
			$avatar_url = _GRAVATAR_URL;
			$avatar_url .=  md5($row['user_email']);

			if ($width || $height)
				$avatar_url .= '?s=' . max($width, $height);
		}
		else
			$avatar_url = (file_exists("images/avatar.png")) ? LinkToGT("images/avatar.png"):LinkToGT("images/blank.gif");


		return $avatar_url;
	}
	
	public function get_login_form_data($html=true, $options = array())
	{
		global $currentpage, $nuke_configs;
		$form_tokens = $this->add_form_key('login', '_LOGIN');
		if($html)
		{
			$login_url = $this->forum_url.(($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1 && isset($nuke_configs['forum_seo_login_link']) && $nuke_configs['forum_seo_login_link'] != '') ? $nuke_configs['forum_seo_login_link']:'ucp.php?mode=login');
			$register_url = $this->forum_url.(($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1 && isset($nuke_configs['forum_seo_register_link']) && $nuke_configs['forum_seo_register_link'] != '') ? $nuke_configs['forum_seo_register_link']:'ucp.php?mode=register');
			$passlost_url = $this->forum_url.(($nuke_configs['gtset'] == 1 && $nuke_configs['forum_GTlink_active'] == 1 && isset($nuke_configs['forum_seo_passlost_link']) && $nuke_configs['forum_seo_passlost_link'] != '') ? $nuke_configs['forum_seo_passlost_link']:'ucp.php?mode=sendpassword');
				
			$login_form = "
			<p>
				<span style=\"font-size:8px;\" class=\"glyphicon glyphicon-record\"></span> <a href=\"$register_url\">"._PLEASE_SIGNUP."</a><br>
				<span style=\"font-size:8px;\" class=\"glyphicon glyphicon-record\"></span> <a href=\"$passlost_url\">"._RESET_PASSWORD."</a>
			</p>
			<form class=\"form-signin\" action=\"$login_url\" method=\"post\" data-focus=\"username\">
				<div class=\"form-group\">
					<input type=\"text\" class=\"form-control\" id=\"username\" name=\"username\" placeholder=\""._USERNAME."\" required />
				</div>
				<div class=\"form-group\">
					<input type=\"password\" class=\"form-control\" id=\"password\" name=\"password\" required>
				</div>
				<div class=\"checkbox\">
					<label><input type=\"checkbox\" value=\"1\" name=\"autologin\"> "._REMEMBER_ME."</label>
				</div>
				<button type=\"submit\" name=\"login\" class=\"btn btn-default\">"._LOGIN."</button>
				<input type=\"hidden\" name=\"creation_time\" value=\"".$form_tokens['creation_time']."\" />
				<input type=\"hidden\" name=\"form_token\" value=\"".$form_tokens['form_token']."\" />
				<input type=\"hidden\" name=\"redirect\" value=\"".rtrim((rtrim(LinkToGT($currentpage), "/")), "/")."/"."\" />
				<input type=\"hidden\" name=\"sid\" value=\"".$this->session_id."\" />
			</form>
			<div class=\"clearfix\"></div>";
		}
		else
		{
		$login_form = array(
				"action"		=> $login_url,
				"sid"			=> $this->session_id,
				"inputs"		=> array(
					"text"			=> array("username" => ""),
					"password"		=> array("password" => ""),
					"hidden"		=> array("redirect" => rtrim((rtrim(LinkToGT($currentpage), "/")), "/")."/"),
					"hidden"		=> array("sid" => $this->session_id, "creation_time" => $form_tokens['creation_time'], "form_token" => $form_tokens['form_token']),
					"checkbox"		=> array("autologin" => 1)
				),
				"html" 			=> array(),
				"terms"			=> $this->forum_url."ucp.php?mode=terms",
				"privacy"		=> $this->forum_url."ucp.php?mode=privacy",
				"register"		=> $register_url,
				"sendpassword"	=> $passlost_url,
			);
		}
		return $login_form;
	}
	
	public function cache_system()
	{
		global $db, $pn_dbcharset;
		
		$this->collation = (isset($this->collation) && $this->collation != '') ? $this->collation:"latin1";
		$extra_code = array(
			"general" => '',
			"phpcodes" => array( 
				"nuke_phpbb3_configs" => //nuke_phpbb_configs
					'
					$db->query("set names \''.$this->collation.'\'");
					$result = $db->table("'.$this->configs_table.'")->select();
					if($db->count() > 0)
					{
						foreach($result as $nuke_phpbb3_configs_row)
						{
							$extra_codes_rows[$nuke_phpbb3_configs_row["config_name"]] = $nuke_phpbb3_configs_row["config_value"];
						}
					}
				',
				"nuke_phpbb3_ranks" => //nuke_phpbb_ranks
					'
					$result = $db->table("'.$this->ranks_table.'")->order_by(["rank_min" => "DESC"])->select();
					if($db->count() > 0)
					{
						foreach($result as $nuke_phpbb3_ranks_row)
						{
							if ($nuke_phpbb3_ranks_row["rank_special"])
							{
								unset($nuke_phpbb3_ranks_row["rank_min"]);
								$extra_codes_rows["special"][$nuke_phpbb3_ranks_row["rank_id"]] = $nuke_phpbb3_ranks_row;
							}
							else
								$extra_codes_rows["normal"][$nuke_phpbb3_ranks_row["rank_id"]] = $nuke_phpbb3_ranks_row;
						}
					}
				',
				"nuke_forum_groups" => //nuke_phpbb_groups
					'
					$result = $db->table("'.$this->groups_table.'")->group_by("group_id")->select();
					if($db->count() > 0)
					{
						foreach($result as $nuke_forum_groups_row)
						{
							$extra_codes_rows[$nuke_forum_groups_row["group_id"]] = $nuke_forum_groups_row;
						}
					}
					$db->query("set names \''.$pn_dbcharset.'\'");
				'
			)
		);
		
		return $extra_code;
	}
	
	function get_profile_fields($user_id)
	{
		global $db;
		$user_id  = intval($user_id);
		if($user_id != 0)
		{
			$sql = 'SELECT *
				FROM ' . $this->profile_fields_data_table . "
				WHERE user_id = $user_id ORDER BY user_id ASC LIMIT 0,1";
			$result = $db->query($sql);
			if(intval($db->count()) > 0)
			{
				$row = $result->results()[0];
				foreach($row as $key => $val)
				{
					$key2 = str_replace("pf_", "", $key);
					$row[$key2] = $val;
					if($key != $key2)
						unset($row[$key]);
				}
				
				$this->data = array_merge($this->data, $row);
			}
		}
	}
	
    protected function preparePathInfo()
    {
        $baseUrl = $this->prepareBaseUrl();

        if (null === ($requestUri = $this->prepareRequestUri())) {
            return '/';
        }

        // Remove the query string from REQUEST_URI
        if ($pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        $pathInfo = substr($requestUri, strlen($baseUrl));
        if (null !== $baseUrl && (false === $pathInfo || '' === $pathInfo)) {
            // If substr() returns false then PATH_INFO is set to an empty string
            return '/';
        } elseif (null === $baseUrl) {
            return $requestUri;
        }

        return (string) $pathInfo;
    }
	
    protected function prepareBasePath()
    {
        $filename = basename($this->server('SCRIPT_FILENAME'));
        $baseUrl = $this->prepareBaseUrl();
        if (empty($baseUrl)) {
            return '';
        }

        if (basename($baseUrl) === $filename) {
            $basePath = dirname($baseUrl);
        } else {
            $basePath = $baseUrl;
        }

        if ('\\' === DIRECTORY_SEPARATOR) {
            $basePath = str_replace('\\', '/', $basePath);
        }

        return rtrim($basePath, '/');
    }
	
    private function getUrlencodedPrefix($string, $prefix)
    {
        if (0 !== strpos(rawurldecode($string), $prefix)) {
            return false;
        }

        $len = strlen($prefix);

        if (preg_match(sprintf('#^(%%[[:xdigit:]]{2}|.){%d}#', $len), $string, $match)) {
            return $match[0];
        }

        return false;
    }
	
    protected function prepareRequestUri()
    {
        $requestUri = '';

        if (array_key_exists('X_ORIGINAL_URL', $_SERVER)) {
            // IIS with Microsoft Rewrite Module
            $requestUri = $_SERVER['X_ORIGINAL_URL'];
            unset($_SERVER['X_ORIGINAL_URL']);
            unset($_SERVER['HTTP_X_ORIGINAL_URL']);
            unset($_SERVER['UNENCODED_URL']);
            unset($_SERVER['IIS_WasUrlRewritten']);
        } elseif (array_key_exists('X_REWRITE_URL', $_SERVER)) {
            // IIS with ISAPI_Rewrite
            $requestUri = $_SERVER['X_REWRITE_URL'];
            unset($_SERVER['X_REWRITE_URL']);
        } elseif (array_key_exists('IIS_WasUrlRewritten', $_SERVER) && $_SERVER['IIS_WasUrlRewritten'] == '1' && $_SERVER['UNENCODED_URL'] != '') {
            // IIS7 with URL Rewrite: make sure we get the unencoded URL (double slash problem)
            $requestUri = $_SERVER['UNENCODED_URL'];
            unset($_SERVER['UNENCODED_URL']);
            unset($_SERVER['IIS_WasUrlRewritten']);
        } elseif (array_key_exists('REQUEST_URI', $_SERVER)) {
            $requestUri = $_SERVER['REQUEST_URI'];
        } elseif (array_key_exists('ORIG_PATH_INFO', $_SERVER)) {
            // IIS 5.0, PHP as CGI
            $requestUri = $_SERVER['ORIG_PATH_INFO'];
            if ('' != $_SERVER['QUERY_STRING']) {
                $requestUri .= '?'.$_SERVER['QUERY_STRING'];
            }
            unset($_SERVER['ORIG_PATH_INFO']);
        }

        // normalize the request URI to ease creating sub-requests from this request
        $_SERVER['REQUEST_URI'] = $requestUri;

        return $requestUri;
    }
    
	protected function prepareBaseUrl()
    {
        $filename = basename($this->server('SCRIPT_FILENAME'));

        if (basename($this->server('SCRIPT_NAME')) === $filename) {
            $baseUrl = $this->server('SCRIPT_NAME');
        } elseif (basename($this->server('PHP_SELF')) === $filename) {
            $baseUrl = $this->server('PHP_SELF');
        } elseif (basename($this->server('ORIG_SCRIPT_NAME')) === $filename) {
            $baseUrl = $this->server('ORIG_SCRIPT_NAME'); // 1and1 shared hosting compatibility
        } else {
            // Backtrack up the script_filename to find the portion matching
            // php_self
            $path = $this->server('PHP_SELF', '');
            $file = $this->server('SCRIPT_FILENAME', '');
            $segs = explode('/', trim($file, '/'));
            $segs = array_reverse($segs);
            $index = 0;
            $last = count($segs);
            $baseUrl = '';
            do {
                $seg = $segs[$index];
                $baseUrl = '/'.$seg.$baseUrl;
                ++$index;
            } while ($last > $index && (false !== $pos = strpos($path, $baseUrl)) && 0 != $pos);
        }

        // Does the baseUrl have anything in common with the request_uri?
        $requestUri = $this->prepareRequestUri();

        if ($baseUrl && false !== $prefix = $this->getUrlencodedPrefix($requestUri, $baseUrl)) {
            // full $baseUrl matches
            return $prefix;
        }

        if ($baseUrl && false !== $prefix = $this->getUrlencodedPrefix($requestUri, rtrim(dirname($baseUrl), '/'.DIRECTORY_SEPARATOR).'/')) {
            // directory portion of $baseUrl matches
            return rtrim($prefix, '/'.DIRECTORY_SEPARATOR);
        }

        $truncatedRequestUri = $requestUri;
        if (false !== $pos = strpos($requestUri, '?')) {
            $truncatedRequestUri = substr($requestUri, 0, $pos);
        }

        $basename = basename($baseUrl);
        if (empty($basename) || !strpos(rawurldecode($truncatedRequestUri), $basename)) {
            // no match whatsoever; set it blank
            return '';
        }

        // If using mod_rewrite or ISAPI_Rewrite strip the script filename
        // out of baseUrl. $pos !== 0 makes sure it is not matching a value
        // from PATH_INFO or QUERY_STRING
        if (strlen($requestUri) >= strlen($baseUrl) && (false !== $pos = strpos($requestUri, $baseUrl)) && $pos !== 0) {
            $baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
        }

        return rtrim($baseUrl, '/'.DIRECTORY_SEPARATOR);
    }

	public function extract_current_page($root_path)
	{
		$page_array = array();

		// First of all, get the request uri...
		$script_name = $_SERVER['SCRIPT_NAME'];
		$args = explode('&', $_SERVER['QUERY_STRING']);

		// If we are unable to get the script name we use REQUEST_URI as a failover and note it within the page array for easier support...
		if (!$script_name)
		{
			$script_name = htmlspecialchars_decode($this->server('REQUEST_URI'));
			$script_name = (($pos = strpos($script_name, '?')) !== false) ? substr($script_name, 0, $pos) : $script_name;
			$page_array['failover'] = 1;
		}

		// Replace backslashes and doubled slashes (could happen on some proxy setups)
		$script_name = str_replace(array('\\', '//'), '/', $script_name);

		// Now, remove the sid and let us get a clean query string...
		$use_args = array();

		// Since some browser do not encode correctly we need to do this with some "special" characters...
		// " -> %22, ' => %27, < -> %3C, > -> %3E
		$find = array('"', "'", '<', '>', '&quot;', '&lt;', '&gt;');
		$replace = array('%22', '%27', '%3C', '%3E', '%22', '%3C', '%3E');

		foreach ($args as $key => $argument)
		{
			if (strpos($argument, 'sid=') === 0)
			{
				continue;
			}

			$use_args[] = str_replace($find, $replace, $argument);
		}
		unset($args);

		// The following examples given are for an request uri of {path to the phpbb directory}/adm/index.php?i=10&b=2

		// The current query string
		$query_string = trim(implode('&', $use_args));

		// basenamed page name (for example: index.php)
		$page_name = (substr($script_name, -1, 1) == '/') ? '' : basename($script_name);
		$page_name = urlencode(htmlspecialchars($page_name));

		$symfony_request_path = $this->clean_path($this->preparePathInfo());
		if ($symfony_request_path !== '/')
		{
			$page_name .= str_replace('%2F', '/', urlencode($symfony_request_path));
		}

		// current directory within the phpBB root (for example: adm)
		$root_dirs = explode('/', str_replace('\\', '/', PHPNUKE_ROOT_PATH));
		$page_dirs = explode('/', str_replace('\\', '/', PHPNUKE_ROOT_PATH));
		$intersection = array_intersect_assoc($root_dirs, $page_dirs);

		$root_dirs = array_diff_assoc($root_dirs, $intersection);
		$page_dirs = array_diff_assoc($page_dirs, $intersection);

		$page_dir = str_repeat('../', sizeof($root_dirs)) . implode('/', $page_dirs);

		if ($page_dir && substr($page_dir, -1, 1) == '/')
		{
			$page_dir = substr($page_dir, 0, -1);
		}

		// Current page from phpBB root (for example: adm/index.php?i=10&b=2)
		$page = (($page_dir) ? $page_dir . '/' : '') . $page_name;
		if ($query_string)
		{
			$page .= '?' . $query_string;
		}

		// The script path from the webroot to the current directory (for example: /phpBB3/adm/) : always prefixed with / and ends in /
		$script_path = $this->prepareBasePath();

		// The script path from the webroot to the phpBB root (for example: /phpBB3/)
		$script_dirs = explode('/', $script_path);
		array_splice($script_dirs, -sizeof($page_dirs));
		$root_script_path = implode('/', $script_dirs) . (sizeof($root_dirs) ? '/' . implode('/', $root_dirs) : '');

		// We are on the base level (phpBB root == webroot), lets adjust the variables a bit...
		if (!$root_script_path)
		{
			$root_script_path = ($page_dir) ? str_replace($page_dir, '', $script_path) : $script_path;
		}

		$script_path .= (substr($script_path, -1, 1) == '/') ? '' : '/';
		$root_script_path .= (substr($root_script_path, -1, 1) == '/') ? '' : '/';

		$page_array += array(
			'page_name'			=> $page_name,
			'page_dir'			=> $page_dir,

			'query_string'		=> $query_string,
			'script_path'		=> str_replace(' ', '%20', htmlspecialchars($script_path)),
			'root_script_path'	=> str_replace(' ', '%20', htmlspecialchars($root_script_path)),

			'page'				=> $page,
		);

		return $page_array;
	}
	
	public function autologin()
	{
		global $db;
		
		if (!isset($_SERVER['PHP_AUTH_USER']))
		{
			return array();
		}

		$php_auth_user = htmlspecialchars_decode($this->server('PHP_AUTH_USER'));
		$php_auth_pw = htmlspecialchars_decode($this->server('PHP_AUTH_PW'));

		if (!empty($php_auth_user) && !empty($php_auth_pw))
		{
			set_var($php_auth_user, $php_auth_user, 'string', true);
			set_var($php_auth_pw, $php_auth_pw, 'string', true);

			$result = $db->table($this->users_table)
						->where("username","=",$php_auth_user)
						->first();

			if(!empty($result))
			{
				return ($result['user_type'] == USER_INACTIVE || $result['user_type'] == USER_IGNORE) ? array() : $result;
			}
		}

		return array();
	}
	
	function unique_id($extra = 'c')
	{
		global $db;
		static $dss_seeded = false;

		$val = $this->config['rand_seed'] . microtime();
		$val = md5($val);
		$this->config['rand_seed'] = md5($this->config['rand_seed'] . $val . $extra);

		if ($dss_seeded !== true && ($this->config['rand_seed_last_update'] < _NOWTIME - rand(1,10)))
		{
			$db->query("UPDATE ".$this->configs_table." SET config_value = CASE 
				WHEN config_name = 'rand_seed_last_update' THEN '". _NOWTIME ."'
				WHEN config_name = 'rand_seed' THEN '". $this->config['rand_seed'] ."'
			END
			WHERE config_name IN('rand_seed_last_update','rand_seed')");
			$dss_seeded = true;
		}

		return substr($val, 4, 16);
	}
	
	function extract_current_hostname()
	{
		// Get hostname
		$host = htmlspecialchars_decode($this->header('Host', $this->server('SERVER_NAME')));

		// Should be a string and lowered
		$host = (string) strtolower($host);

		// If host is equal the cookie domain or the server name (if config is set), then we assume it is valid
		if ((isset($this->config['cookie_domain']) && $host === $this->config['cookie_domain']) || (isset($this->config['server_name']) && $host === $this->config['server_name']))
		{
			return $host;
		}

		// Is the host actually a IP? If so, we use the IP... (IPv4)
		if (long2ip(ip2long($host)) === $host)
		{
			return $host;
		}

		// Now return the hostname (this also removes any port definition). The http:// is prepended to construct a valid URL, hosts never have a scheme assigned
		$host = @parse_url('http://' . $host);
		$host = (!empty($host['host'])) ? $host['host'] : '';

		// Remove any portions not removed by parse_url (#)
		$host = str_replace('#', '', $host);

		// If, by any means, the host is now empty, we will use a "best approach" way to guess one
		if (empty($host))
		{
			if (!empty($this->config['server_name']))
			{
				$host = $this->config['server_name'];
			}
			else if (!empty($this->config['cookie_domain']))
			{
				$host = (strpos($this->config['cookie_domain'], '.') === 0) ? substr($this->config['cookie_domain'], 1) : $this->config['cookie_domain'];
			}
			else
			{
				// Set to OS hostname or localhost
				$host = (function_exists('php_uname')) ? php_uname('n') : 'localhost';
			}
		}

		// It may be still no valid host, but for sure only a hostname (we may further expand on the cookie domain... if set)
		return $host;
	}

	function short_ipv6($ip, $length)
	{
		if ($length < 1)
		{
			return '';
		}

		// extend IPv6 addresses
		$blocks = substr_count($ip, ':') + 1;
		if ($blocks < 9)
		{
			$ip = str_replace('::', ':' . str_repeat('0000:', 9 - $blocks), $ip);
		}
		if ($ip[0] == ':')
		{
			$ip = '0000' . $ip;
		}
		if ($length < 4)
		{
			$ip = implode(':', array_slice(explode(':', $ip), 0, 1 + $length));
		}

		return $ip;
	}

	public function set_var(&$result, $var, $type, $multibyte = false, $trim = true)
	{
		settype($var, $type);
		$result = $var;

		if ($type == 'string')
		{
			$result = str_replace(array("\r\n", "\r", "\0"), array("\n", "\n", ''), $result);

			if ($trim)
			{
				$result = trim($result);
			}

			$result = htmlspecialchars($result, ENT_COMPAT, 'UTF-8');

			if (!empty($result))
			{
				// Make sure multibyte characters are wellformed
				if ($multibyte)
				{
					if (!preg_match('/^./u', $result))
					{
						$result = '';
					}
				}
				else
				{
					// no multibyte, allow only ASCII (0-127)
					$result = preg_replace('/[\x80-\xFF]/', '?', $result);
				}
			}

			$result = ($this->strip) ? stripslashes($result) : $result;
		}
	}
	
	public function recursive_set_var(&$var, $default, $multibyte, $trim = true)
	{
		if (is_array($var) !== is_array($default))
		{
			$var = (is_array($default)) ? array() : $default;
			return;
		}

		if (!is_array($default))
		{
			$type = gettype($default);
			$this->set_var($var, $var, $type, $multibyte, $trim);
		}
		else
		{
			// make sure there is at least one key/value pair to use get the
			// types from
			if (empty($default))
			{
				$var = array();
				return;
			}

			list($default_key, $default_value) = each($default);
			$value_type = gettype($default_value);
			$key_type = gettype($default_key);

			$_var = $var;
			$var = array();

			foreach ($_var as $k => $v)
			{
				$this->set_var($k, $k, $key_type, $multibyte);

				$this->recursive_set_var($v, $default_value, $multibyte, $trim);
				$var[$k] = $v;
			}
		}
	}
	
	public function variable($var_name, $default, $multibyte = false, $super_global = '_REQUEST')
	{
		return $this->_variable($var_name, $default, $multibyte, $super_global, true);
	}
	
	protected function _variable($var_name, $default, $multibyte = false, $super_global = '_REQUEST', $trim = true)
	{
		$path = false;

		// deep direct access to multi dimensional arrays
		if (is_array($var_name))
		{
			$path = $var_name;
			// make sure at least the variable name is specified
			if (empty($path))
			{
				return (is_array($default)) ? array() : $default;
			}
			// the variable name is the first element on the path
			$var_name = array_shift($path);
		}
		
		if (!isset($super_global[$var_name]))
		{
			return (is_array($default)) ? array() : $default;
		}
		$var = $super_global[$var_name];

		if ($path)
		{
			// walk through the array structure and find the element we are looking for
			foreach ($path as $key)
			{
				if (is_array($var) && isset($var[$key]))
				{
					$var = $var[$key];
				}
				else
				{
					return (is_array($default)) ? array() : $default;
				}
			}
		}

		$this->recursive_set_var($var, $default, $multibyte, $trim);

		return $var;
	}

	public function server($var_name, $default = '')
	{
		$multibyte = true;

		if (isset($_SERVER[$var_name]))
		{
			return $this->variable($var_name, $default, $multibyte, $_SERVER);
		}
		return '';
	}

	public function header($header_name, $default = '')
	{
		$var_name = 'HTTP_' . str_replace('-', '_', strtoupper($header_name));
		return $this->server($var_name, $default);
	}

	public function validate_session($user)
	{
		// Check if PHP_AUTH_USER is set and handle this case
		if (isset($_SERVER['PHP_AUTH_USER']))
		{
			$php_auth_user = $this->server('PHP_AUTH_USER');

			return ($php_auth_user === $user['username']) ? true : false;
		}

		// PHP_AUTH_USER is not set. A valid session is now determined by the user type (anonymous/bot or not)
		if ($user['user_type'] == USER_IGNORE)
		{
			return true;
		}

		return;
	}
	
	function set_login_key($user_id = false, $key = false, $user_ip = false)
	{
		global $db;

		$user_id = ($user_id === false) ? $this->data['user_id'] : $user_id;
		$user_ip = ($user_ip === false) ? $this->ip : $user_ip;
		$key = ($key === false) ? (($this->cookie_data['k']) ? $this->cookie_data['k'] : false) : $key;

		$key_id = $this->unique_id(hexdec(substr($this->session_id, 0, 8)));

		$sql_ary = array(
			'key_id'		=> "'".(string) md5($key_id)."'",
			'last_ip'		=> "'".(string) $this->ip."'",
			'last_login'	=> "'".(int) _NOWTIME."'"
		);

		if (!$key)
		{
			$sql_ary += array(
				'user_id'	=> "'".(int) $user_id."'"
			);
		}

		//foreach($sql_ary as $sql_ary_key => $sql_ary_val)
		//	$items_query = "$sql_ary_key = $sql_ary_val";
		
		if ($key)
		{
			$db->table($this->sessions_keys_table)
				->where('user_id', (int) $user_id)
				->where('key_id', md5($key))
				->update($sql_ary);
		}
		else
		{
			$db->table($this->sessions_keys_table)
				->insert($sql_ary);
		}

		$this->cookie_data['k'] = $key_id;

		return false;
	}
	
	function session_begin($update_session_page = true)
	{
		global $SID, $_SID, $db, $nuke_configs, $visitor_ip;

		$db->query("SET NAMES '".$this->collation."'");
		// Give us some basic information
		$this->time_now				= _NOWTIME;
		$this->cookie_data			= array('u' => 0, 'k' => '');
		$this->update_session_page	= $update_session_page;
		$this->browser				= $this->header('User-Agent');
		$this->referer				= $this->header('Referer');
		$this->forwarded_for		= $this->header('X-Forwarded-For');

		$this->host					= $this->extract_current_hostname();
		$this->page					= $this->extract_current_page($nuke_configs['forum_path']);

		// if the forwarded for header shall be checked we have to validate its contents
		if ($this->config['forwarded_for_check'])
		{
			$this->forwarded_for = preg_replace('# {2,}#', ' ', str_replace(',', ' ', $this->forwarded_for));

			// split the list of IPs
			$ips = explode(' ', $this->forwarded_for);
			foreach ($ips as $ip)
			{
				// check IPv4 first, the IPv6 is hopefully only going to be used very seldomly
				if (!empty($ip) && !preg_match(get_preg_expression('ipv4'), $ip) && !preg_match(get_preg_expression('ipv6'), $ip))
				{
					// contains invalid data, don't use the forwarded for header
					$this->forwarded_for = '';
					break;
				}
			}
		}
		else
		{
			$this->forwarded_for = '';
		}

		if (isset($_COOKIE[$this->config['cookie_name'] . '_sid']) || isset($_COOKIE[$this->config['cookie_name'] . '_u']))
		{
			$this->cookie_data['u'] = intval($_COOKIE[$this->config['cookie_name'] . '_u']);
			$this->cookie_data['k'] = filter($_COOKIE[$this->config['cookie_name'] . '_k']);
			$this->session_id 		= filter($_COOKIE[$this->config['cookie_name'] . '_sid']);

			$SID = '?sid=' . $this->session_id;
			$_SID = $this->session_id;

			if (empty($this->session_id) && isset($_COOKIE['sid']))
			{
				$this->session_id = $_SID = filter($_COOKIE['sid']);
				$SID = '?sid=' . $this->session_id;
				$this->cookie_data = array('u' => 0, 'k' => '');
			}
		}
		elseif(isset($_COOKIE['sid']))
		{
			$this->session_id = $_SID = filter($_COOKIE['sid']);
			$SID = '?sid=' . $this->session_id;
		}
		else
		{
			$this->session_id = $_SID = '';
			$SID = '?sid=' . $this->session_id;
		}
		
		// Why no forwarded_for et al? Well, too easily spoofed. With the results of my recent requests
		// it's pretty clear that in the majority of cases you'll at least be left with a proxy/cache ip.
		$this->ip = htmlspecialchars_decode($visitor_ip);
		$this->ip = preg_replace('# {2,}#', ' ', str_replace(',', ' ', $this->ip));

		// split the list of IPs
		$ips = explode(' ', trim($this->ip));

		// Default IP if REMOTE_ADDR is invalid
		$this->ip = '127.0.0.1';

		foreach ($ips as $ip)
		{
			if (preg_match($this->ipv4_regex, $ip))
			{
				$this->ip = $ip;
			}
			else if (preg_match($this->ipv6_regex, $ip))
			{
				// Quick check for IPv4-mapped address in IPv6
				if (stripos($ip, '::ffff:') === 0)
				{
					$ipv4 = substr($ip, 7);

					if (preg_match($this->ipv4_regex, $ipv4))
					{
						$ip = $ipv4;
					}
				}

				$this->ip = $ip;
			}
			else
			{
				// We want to use the last valid address in the chain
				// Leave foreach loop when address is invalid
				break;
			}
		}
		
		// if session id is set
		if (!empty($this->session_id))
		{
			/*$sql = 'SELECT u.*, s.*, g.*
				FROM ' . $this->sessions_table . ' s, ' . $this->users_table . ' u, ' . $this->groups_table . ' g
				WHERE s.session_id = ?
					AND u.user_id = s.session_user_id
					AND g.group_id = u.group_id';*/
			$sql = "SELECT u.*, s.*, g.* FROM ".$this->sessions_table." AS s LEFT JOIN ".$this->users_table." AS u ON u.user_id = s.session_user_id LEFT JOIN ".$this->groups_table." AS g ON g.group_id = u.group_id WHERE s.session_id = ?";
			$result = $db->query($sql, array($this->session_id));
			$this->data = isset($result->results()[0]) ? $result->results()[0]:array();
			$this->get_profile_fields($this->data['user_id']);
			// Did the session exist in the DB?
			if (isset($this->data['user_id']))
			{
				if (strpos($this->ip, ':') !== false && strpos($this->data['session_ip'], ':') !== false)
				{
					$s_ip = $this->short_ipv6($this->data['session_ip'], $this->config['ip_check']);
					$u_ip = $this->short_ipv6($this->ip, $this->config['ip_check']);
				}
				else
				{
					$s_ip = implode('.', array_slice(explode('.', $this->data['session_ip']), 0, $this->config['ip_check']));
					$u_ip = implode('.', array_slice(explode('.', $this->ip), 0, $this->config['ip_check']));
				}

				$s_browser = ($this->config['browser_check']) ? trim(strtolower(substr($this->data['session_browser'], 0, 149))) : '';
				$u_browser = ($this->config['browser_check']) ? trim(strtolower(substr($this->browser, 0, 149))) : '';

				$s_forwarded_for = ($this->config['forwarded_for_check']) ? substr($this->data['session_forwarded_for'], 0, 254) : '';
				$u_forwarded_for = ($this->config['forwarded_for_check']) ? substr($this->forwarded_for, 0, 254) : '';

				// referer checks
				$check_referer_path = (@$this->config['referer_validation'] == REFERER_VALIDATE_PATH);

				if ($u_ip === $s_ip && $s_browser === $u_browser && $s_forwarded_for === $u_forwarded_for)
				{
					$session_expired = false;

					$ret = $this->validate_session($this->data);

					if ($ret !== null && !$ret)
					{
						$session_expired = true;
					}

					if (!$session_expired)
					{
						// Check the session length timeframe if autologin is not enabled.
						// Else check the autologin length... and also removing those having autologin enabled but no longer allowed board-wide.
						if (!$this->data['session_autologin'])
						{
							if ($this->data['session_time'] < $this->time_now - ($this->config['session_length'] + 60))
							{
								$session_expired = true;
							}
						}
						else if (!$this->config['allow_autologin'] || ($this->config['max_autologin_time'] && $this->data['session_time'] < $this->time_now - (86400 * (int) $this->config['max_autologin_time']) + 60))
						{
							$session_expired = true;
						}
					}

					if (!$session_expired)
					{
						$this->data['is_registered'] = ($this->data['user_id'] != ANONYMOUS && ($this->data['user_type'] == USER_NORMAL || $this->data['user_type'] == USER_FOUNDER)) ? true : false;
						$this->data['is_bot'] = (!$this->data['is_registered'] && $this->data['user_id'] != ANONYMOUS) ? true : false;
						$this->data['user_lang'] = basename($this->data['user_lang']);

						return true;
					}
				}
			}
		}
		
		// If we reach here then no (valid) session exists. So we'll create a new one
		return $this->session_create();
	}

	function session_create($user_id = false, $set_admin = false, $persist_login = false, $viewonline = true)
	{
		global $SID, $_SID, $db, $nuke_configs, $REQUESTURL, $pn_Cookies;

		$db->query("SET NAMES '".$this->collation."'");
		$this->data = array();
		
		$session_last_gc = (isset($nuke_configs['session_last_gc']) && $nuke_configs['session_last_gc'] > $this->config['session_last_gc']) ? $nuke_configs['session_last_gc']:$this->config['session_last_gc'];
		
		if ($this->time_now > $session_last_gc + $this->config['session_gc'])
		{
			$this->session_gc();
		}
		
		if (!$this->config['allow_autologin'])
		{
			$this->cookie_data['k'] = $persist_login = false;
		}
		
		/**
		* Here we do a bot check, oh er saucy! No, not that kind of bot
		* check. We loop through the list of bots defined by the admin and
		* see if we have any useragent and/or IP matches. If we do, this is a
		* bot, act accordingly
		*/
		$bot = false;
		if(file_exists($nuke_configs['forum_path']."/cache/data_bots.php"))
		{
			$bots_chache = phpnuke_get_url_contents($nuke_configs['forum_path']."/cache/data_bots.php", true, false, true);
			$bots_chache = explode("\n", str_replace("\r","",$bots_chache));
			$bots_chache = end($bots_chache);
			$active_bots = phpnuke_unserialize($bots_chache);
					

			foreach ($active_bots as $row)
			{
				if ($row['bot_agent'] && preg_match('#' . str_replace('\*', '.*?', preg_quote($row['bot_agent'], '#')) . '#i', $this->browser))
				{
					$bot = $row['user_id'];
				}

				// If ip is supplied, we will make sure the ip is matching too...
				if ($row['bot_ip'] && ($bot || !$row['bot_agent']))
				{
					// Set bot to false, then we only have to set it to true if it is matching
					$bot = false;

					foreach (explode(',', $row['bot_ip']) as $bot_ip)
					{
						$bot_ip = trim($bot_ip);

						if (!$bot_ip)
						{
							continue;
						}

						if (strpos($this->ip, $bot_ip) === 0)
						{
							$bot = (int) $row['user_id'];
							break;
						}
					}
				}

				if ($bot)
				{
					break;
				}
			}
		}

		$this->data = $this->autologin();

		if ($user_id !== false && sizeof($this->data) && $this->data['user_id'] != $user_id)
		{
			$this->data = array();
		}

		if (sizeof($this->data))
		{
			$this->cookie_data['k'] = '';
			$this->cookie_data['u'] = $this->data['user_id'];
		}

		// If we're presented with an autologin key we'll join against it.
		// Else if we've been passed a user_id we'll grab data based on that
		if (isset($this->cookie_data['k']) && $this->cookie_data['k'] && $this->cookie_data['u'] && !sizeof($this->data))
		{
			$sql = 'SELECT u.*, g.*
				FROM ' . $this->users_table . ' u, ' . $this->sessions_keys_table . ' k, ' . $this->groups_table . ' g
				WHERE u.user_id = ?
					AND u.user_type IN (' . USER_NORMAL . ', ' . USER_FOUNDER . ")
					AND k.user_id = u.user_id
					AND g.group_id = u.group_id
					AND k.key_id = ?";
			$result = $db->query($sql, array((int) $this->cookie_data['u'], md5($this->cookie_data['k'])));
			$user_data = isset($result->results()[0]) ? $result->results()[0]:array();
			
			if ($user_id === false || (isset($user_data['user_id']) && $user_id == $user_data['user_id']))
			{
				$this->data = $user_data;
				$this->get_profile_fields($this->data['user_id']);
				$bot = false;
			}
		}

		if ($user_id !== false && !sizeof($this->data))
		{
			$this->cookie_data['k'] = '';
			$this->cookie_data['u'] = $user_id;

			$sql = 'SELECT u.*, g.*
				FROM ' . $this->users_table . ' u, ' . $this->groups_table . ' g
				WHERE u.user_id = ?
					AND u.user_type IN (' . USER_NORMAL . ', ' . USER_FOUNDER . ')
					AND g.group_id = u.group_id';
			$result = $db->query($sql, array((int) $this->cookie_data['u']));
			$this->data = isset($result->results()[0]) ? $result->results()[0]:array();
			$this->get_profile_fields($this->data['user_id']);
			$bot = false;
		}

		// If no data was returned one or more of the following occurred:
		// Key didn't match one in the DB
		// User does not exist
		// User is inactive
		// User is bot
		if (!sizeof($this->data) || !is_array($this->data))
		{
			$this->cookie_data['k'] = '';
			$this->cookie_data['u'] = ($bot) ? $bot : ANONYMOUS;

			if (!$bot)
			{
				$sql = 'SELECT *
					FROM ' . $this->users_table . '
					WHERE user_id = ?';
				$result = $db->query($sql, array((int) $this->cookie_data['u']));
			}
			else
			{
				// We give bots always the same session if it is not yet expired.
				$sql = 'SELECT u.*, s.*, g.*
					FROM ' . $this->users_table . ' AS u
					LEFT JOIN ' . $this->sessions_table . ' AS s ON (s.session_user_id = u.user_id)
					LEFT JOIN ' . $this->groups_table . ' AS g ON (g.group_id = u.group_id)
					WHERE u.user_id = ?';
				$result = $db->query($sql, array((int) $bot));
			}

			$this->data = (isset($result->results()[0])) ? $result->results()[0]:array();
			$this->get_profile_fields($this->data['user_id']);
		}

		if ($this->data['user_id'] != ANONYMOUS && !$bot)
		{
			$this->data['session_last_visit'] = (isset($this->data['session_time']) && $this->data['session_time']) ? $this->data['session_time'] : (($this->data['user_lastvisit']) ? $this->data['user_lastvisit'] : _NOWTIME);
		}
		else
		{
			$this->data['session_last_visit'] = $this->time_now;
		}

		// Force user id to be integer...
		$this->data['user_id'] = (int) $this->data['user_id'];

		$this->data['is_registered'] = (!$bot && $this->data['user_id'] != ANONYMOUS && ($this->data['user_type'] == USER_NORMAL || $this->data['user_type'] == USER_FOUNDER)) ? true : false;
		$this->data['is_bot'] = ($bot) ? true : false;

		// If our friend is a bot, we re-assign a previously assigned session
		if ($this->data['is_bot'] && $bot == $this->data['user_id'] && $this->data['session_id'])
		{
			// Only assign the current session if the ip, browser and forwarded_for match...
			if (strpos($this->ip, ':') !== false && strpos($this->data['session_ip'], ':') !== false)
			{
				$s_ip = $this->short_ipv6($this->data['session_ip'], $this->config['ip_check']);
				$u_ip = $this->short_ipv6($this->ip, $this->config['ip_check']);
			}
			else
			{
				$s_ip = implode('.', array_slice(explode('.', $this->data['session_ip']), 0, $this->config['ip_check']));
				$u_ip = implode('.', array_slice(explode('.', $this->ip), 0, $this->config['ip_check']));
			}

			$s_browser = ($this->config['browser_check']) ? trim(strtolower(substr($this->data['session_browser'], 0, 149))) : '';
			$u_browser = ($this->config['browser_check']) ? trim(strtolower(substr($this->browser, 0, 149))) : '';

			$s_forwarded_for = ($this->config['forwarded_for_check']) ? substr($this->data['session_forwarded_for'], 0, 254) : '';
			$u_forwarded_for = ($this->config['forwarded_for_check']) ? substr($this->forwarded_for, 0, 254) : '';

			if ($u_ip === $s_ip && $s_browser === $u_browser && $s_forwarded_for === $u_forwarded_for)
			{
				$this->session_id = $this->data['session_id'];

				// Only update session DB a minute or so after last update or if page changes
				if ($this->time_now - $this->data['session_time'] > 60 || ($this->update_session_page && $this->data['session_page'] != $this->page['page']))
				{
					// Update the last visit time
					$db->table($this->users_table)
						->where('user_id', (int) $this->data['user_id'])
						->update([
							'user_lastvisit' => (int) $this->data['session_time']
						]);
				}

				$SID = '?sid=';
				$_SID = '';
				return true;
			}
			else
			{
				// If the ip and browser does not match make sure we only have one bot assigned to one session
				$db->table($this->sessions_table)
					->where('session_user_id', $this->data['user_id'])
					->delete();
			}
		}

		$session_autologin = (($this->cookie_data['k'] || $persist_login) && $this->data['is_registered']) ? true : false;
		$set_admin = ($set_admin && $this->data['is_registered']) ? true : false;

		// Create or update the session
		$sql_ary = array(
			'session_user_id'		=> (int) $this->data['user_id'],
			'session_start'			=> (int) $this->time_now,
			'session_last_visit'	=> (int) $this->data['session_last_visit'],
			'session_time'			=> (int) $this->time_now,
			'session_browser'		=> (string) trim(substr($this->browser, 0, 149)),
			'session_forwarded_for'	=> (string) $this->forwarded_for,
			'session_ip'			=> (string) $this->ip,
			'session_autologin'		=> (($session_autologin) ? 1 : 0),
			'session_admin'			=> (($set_admin) ? 1 : 0),
			'session_viewonline'	=> (($viewonline) ? 1 : 0),
		);

		if ($this->update_session_page)
		{
			//$sql_ary['session_page'] = (string) substr($this->page['page'], 0, 199);
			$sql_ary['session_page'] = $REQUESTURL;
		}

		$db->table($this->sessions_table)
			->where('session_id', $this->session_id)
			->where('session_user_id', ANONYMOUS)
			->delete();

		// Since we re-create the session id here, the inserted row must be unique. Therefore, we display potential errors.
		// Commented out because it will not allow forums to update correctly
//		$db->sql_return_on_error(false);

		// Something quite important: session_page always holds the *last* page visited, except for the *first* visit.
		// We are not able to simply have an empty session_page btw, therefore we need to tell phpBB how to detect this special case.
		// If the session id is empty, we have a completely new one and will set an "identifier" here. This identifier is able to be checked later.
		if (empty($this->data['session_id']))
		{
			// This is a temporary variable, only set for the very first visit
			$this->data['session_created'] = true;
		}

		$this->session_id = $this->data['session_id'] = md5($this->unique_id());

		$sql_ary['session_id'] = (string) $this->session_id;
		//$sql_ary['session_page'] = (string) substr($this->page['page'], 0, 199);
		$sql_ary['session_page'] = ($REQUESTURL != '' && $REQUESTURL !== null) ? $REQUESTURL:"index.php";

		$db->table($this->sessions_table)
			->insert($sql_ary);

		// Regenerate autologin/persistent login key
		if ($session_autologin)
		{
			$this->set_login_key();
		}

		// refresh data
		$SID = '?sid=' . $this->session_id;
		$_SID = $this->session_id;
		$this->data = array_merge($this->data, $sql_ary);
		$this->get_profile_fields($this->data['user_id']);

		if (!$bot)
		{
			$cookie_expire = (($this->config['max_autologin_time']) ? 86400 * (int) $this->config['max_autologin_time'] : (365*24*3600));

			$pn_Cookies->cookie_prefix_set = false;
			$pn_Cookies->set($this->config['cookie_name'].'_u', $this->cookie_data['u'], $cookie_expire);
			$pn_Cookies->set($this->config['cookie_name'].'_k', $this->cookie_data['k'], $cookie_expire);
			$pn_Cookies->set($this->config['cookie_name'].'_sid', $this->session_id, $cookie_expire);

			$pn_Cookies->cookie_prefix_set = true;
			
			$result = $db->table($this->sessions_table)
				->where("session_user_id", (int) $this->data['user_id'])
				->where("session_time", ">=", (int) ($this->time_now - (max($this->config['session_length'], $this->config['form_token_lifetime']))))
				->first(array("COUNT(session_id) AS sessions"));

			if($db->count() > 0)
			{
				if ((int) $result['sessions'] <= 1 || empty($this->data['user_form_salt']))
				{
					$this->data['user_form_salt'] = $this->unique_id();
					// Update the form key
					
					$result = $db->table($this->users_table)
						->where("user_id", (int) $this->data['user_id'])
						->update([
							"user_form_salt" => $this->data['user_form_salt']
						]);
				}
			}
		}
		else
		{
			$this->data['session_time'] = $this->data['session_last_visit'] = $this->time_now;

			// Update the last visit time
				
			$db->table($this->users_table)
				->where("user_id", (int) $this->data['user_id'])
				->update([
					"user_lastvisit" => (int) $this->data['session_time']
				]);

			$SID = '?sid=';
			$_SID = '';
		}
		
		return true;
	}

	function session_gc()
	{
		global $db, $config, $phpbb_container, $phpbb_dispatcher, $pn_dbcharset;

		$batch_size = 10;

		if (!$this->time_now)
		{
			$this->time_now = _NOWTIME;
		}

		// Firstly, delete guest sessions
		
		$db->table($this->sessions_table)
			->where("session_user_id", ANONYMOUS)
			->where("session_time", (int) ($this->time_now - $this->config['session_length']))
			->delete();

		// Get expired sessions, only most recent for each user
		$result = $db->table($this->sessions_table)
			->where("session_time", ($this->time_now - $this->config['session_length']))
			->group_by("session_user_id, session_page")
			->limit($batch_size)
			->select(["session_user_id", "session_page", "MAX(session_time) AS recent_time"]);
			
		$del_user_id = array();
		$del_sessions = 0;
		if($db->count() > 0)
		{
			foreach ($result as $row)
			{
				$sql_query['user_lastvisit'][(int) $row['session_user_id']] = (int) $row['recent_time'];
				$sql_query['user_lastpage'][(int) $row['session_user_id']] = $row['session_page'];
				$del_user_id[] = (int) $row['session_user_id'];
				$del_sessions++;
			}
		}
		
		if(isset($sql_query['user_lastvisit']) && !empty($sql_query['user_lastvisit']))
		{
			$sql = 'UPDATE ' . $this->users_table . ' SET user_lastvisit = CASE';
				foreach($sql_query['user_lastvisit'] as $session_user_id => $recent_time)
					$sql .=" WHEN user_id = '$session_user_id' THEN '$recent_time'\n";
			$sql .= "
			END
			SET user_lastpage = CASE";
				foreach($sql_query['user_lastpage'] as $session_user_id => $session_page)
					$sql .=" WHEN user_id = '$session_user_id' THEN '$session_page'";
			$sql .="
			END
			WHERE user_id IN (".array_keys($sql_query['user_lastvisit']).")";
			$db->query($sql);
		}
		
		if (sizeof($del_user_id))
		{
			// Delete expired sessions
			$db->table($this->sessions_table)
				->whereFunction("FIND_IN_SET (session_user_id, ".implode(",", $del_user_id).")")
				->where("session_time", "<", ($this->time_now - $this->config['session_length']))
				->delete();
		}

		if ($del_sessions < $batch_size)
		{
			$db->query("SET NAMES '$pn_dbcharset'");
			update_configs('session_last_gc', $this->time_now);
			
			// Less than 10 users, update gc timer ... else we want gc
			// called again to delete other sessions

			if ($this->config['max_autologin_time'])
			{
				$db->table($this->sessions_keys_table)
					->where("last_login", "<", (_NOWTIME - (86400 * (int) $this->config['max_autologin_time'])))
					->delete();
			}

			$db->table($this->login_attempts_table)
				->where("attempt_time", "<", (_NOWTIME - (int) $this->config['ip_login_limit_time']))
				->delete();
		}
		return;
	}
	
	public function update_session($session_data, $session_id = null)
	{
		global $db;
		$session_id = ($session_id) ? $session_id : $this->session_id;
		if(count($session_data) > 0)
		{
			$db->table($this->sessions_table)
				->where("session_id", $session_id)
				->update($session_data);
		}
	}

	public function update_session_infos()
	{
		global $db, $REQUESTURL;

		// No need to update if it's a new session. Informations are already inserted by session_create()
		if (isset($this->data['session_created']) && $this->data['session_created'])
		{
			return;
		}

		// Only update session DB a minute or so after last update or if page changes
		//if ($this->time_now - $this->data['session_time'] > 60 || ($this->update_session_page && $this->data['session_page'] != $this->page['page']))
		if ($this->time_now - $this->data['session_time'] > 60)
		{
			$sql_ary = array('session_time' => $this->time_now);

			// Do not update the session page for ajax requests, so the view online still works as intended
			//$sql_ary['session_page'] = substr($this->page['page'], 0, 199);
			$sql_ary['session_page'] = ($this->update_session_page && !is_ajax() && $REQUESTURL != '' && $REQUESTURL !== null) ? $REQUESTURL:"index.php";

			$this->update_session($sql_ary);

			$this->data = array_merge($this->data, $sql_ary);
			$this->get_profile_fields($this->data['user_id']);
		}
	}
	
	function add_form_key($form_name)
	{
		global $pn_Sessions;
		
		$now = _NOWTIME;
		$token_sid = ($this->data['user_id'] == ANONYMOUS && !empty($this->config['form_token_sid_guests'])) ? $this->session_id : '';
		$token = sha1($now . $this->data['user_form_salt'] . $form_name . $token_sid);

		$s_fields = array(
			'creation_time' => $now,
			'form_token'	=> $token,
		);
		
		return $s_fields;
	}

	function get_user_phone($user_id)
	{
		global $db, $nuke_configs;
		$user_phone = 0;
		$user_id  = intval($user_id);
		if($user_id != 0)
		{
			$sql = 'SELECT *
				FROM ' . $this->profile_fields_data_table . "
				WHERE user_id = $user_id ORDER BY user_id ASC LIMIT 0,1";
			$result = $db->query($sql);
			if(intval($db->count()) > 0)
			{
				$row = $result->results()[0];
				foreach($row as $key => $val)
					if(stristr($key, "phone") || stristr($key, "mobile"))
						$user_phone = $val;
			}
		}
		
		return $user_phone;
	}
	
	function getuserinfo($rebuild = false)
	{
		global $nuke_configs, $db, $pn_dbcharset;

		if($rebuild)
		{
			$this->session_begin();
			$this->update_session_infos();
			$db->query("SET NAMES '".$pn_dbcharset."'");
		}
		
		return $this->data;
	}
}

?>