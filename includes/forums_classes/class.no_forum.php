<?php

if(!defined('NUKE_FILE'))
{
	die ("You can't access this file directly...");
}

if(!defined('ANONYMOUS'))
	define('ANONYMOUS', 1);
define('USER_NORMAL', 1);
define('USER_INACTIVED', 0);
define('USER_DELETED', -1);

class users_system{
	
	var $user_fields = array();
	var $page = array();
	var $data = array();

	public function __construct()
	{
		global $db, $nuke_configs, $pn_dbcharset;
		
		// define default user fileds of phpnuke system
		$this->user_fields['user_id']				= "user_id";
		$this->user_fields['username']				= "username";
		$this->user_fields['realname']				= $this->user_fields['name'] = "user_realname";
		$this->user_fields['user_password']			= "user_password";
		$this->user_fields['user_birthday']			= "user_birthday";
		$this->user_fields['user_lastvisit']		= "user_lastvisit";
		$this->user_fields['user_avatar']			= "user_avatar";
		$this->user_fields['user_avatar_type']		= "user_avatar_type";
		$this->user_fields['user_avatar_width']		= "user_avatar_width";
		$this->user_fields['user_avatar_height']	= "user_avatar_height";
		$this->user_fields['user_email']			= "user_email";
		$this->user_fields['username_clean']		= "username";
		$this->user_fields['user_website']			= "user_website";
		$this->user_fields['user_regdate']			= "user_regdate";
		$this->user_fields['user_active']			= "user_status";
		$this->user_fields['user_gender']			= "user_gender";
		$this->user_fields['user_points']			= "user_points";
		$this->user_fields['group_id']				= "group_id";
		$this->user_fields['group_colour']			= "group_colour";
		$this->user_fields['user_credit']			= "user_credit";
		
		$this->user_fields['common_where']			= "user_status = '1'";
		$this->users_table							= USERS_TABLE;
		$this->groups_table							= GROUPS_TABLE;
		$this->profile_url 							= LinkToGT('index.php?modname=Users&op=userinfo&username=%2$s');
		$this->register_url 						= LinkToGT('index.php?modname=Users&op=register');
		$this->passlost_url 						= LinkToGT('index.php?modname=Users&op=reset_password');
		$this->collation							= $pn_dbcharset;
		
		$extra_cache_codes = $this->cache_system();
		
		if(!empty($extra_cache_codes))
			cache_system('',$extra_cache_codes);
		
		$this->getuserinfo();
		$this->online();
	}
	
	public function MTForumBlock($p=1)
	{
		return _NO_FORUM_SUPPORTED;
	}
		
	public function user_statistics()
	{
		
		global $db, $nuke_configs, $block_global_contents, $HijriCalendar, $userinfo, $users_system, $currentpage, $cache;

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
		
		$time = (_NOWTIME - (intval($nuke_configs['session_timeout']) * 60));

		//Registered users online
		$result = $db->query("SELECT DISTINCT 
		s.session_id, s.session_user_id, s.session_ip, s.session_page,
		(SELECT COUNT(DISTINCT session_ip) FROM ".SESSIONS_TABLE." WHERE session_user_id = '".ANONYMOUS."' AND session_time >= " . ($time - ((int) ($time % 60))).") AS num_guests,
		u.username, u.user_allow_viewonline,
		g.group_colour
		FROM ".SESSIONS_TABLE." AS s 
		LEFT JOIN ".$this->users_table." AS u ON u.user_id = s.session_user_id
		LEFT JOIN ".$this->groups_table." AS g ON u.group_id = g.group_id
		WHERE s.session_time >= " . ($time - ((int) ($time % 60)))."
		GROUP BY s.session_id
		ORDER by u.user_id ASC
		");
		
		//Assemble the online registered users
		$your_info = array();
		$i = 1;
		
		if($db->count() > 0)
		{
			foreach ($result as $row)
			{
				$online_users['guests_online'] = intval($row['num_guests']);
				
				$session_id = intval($row['session_id']);
				$session_user_id = intval($row['session_user_id']);
				$user_allow_viewonline = intval($row['user_allow_viewonline']);
				$session_ip = $row['session_ip'];
				$session_page = $row['session_page'];
				$group_colour = $row['group_colour'];
				
				$username = filter($row['username'], "nohtml");
				
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
				if($session_user_id != ANONYMOUS)
				{
					if (!isset($online_users['online_users'][$session_user_id]))
					{
						$czi = correct_date_number($i);
						
						if (((is_admin()) && ($showonlyadmin == 0)) || $showonlyadmin == 1 && $session_page != '')
							$where = "<a href=\"".$nuke_configs['nukeurl']."$session_page\" style=\"color:".$group_colour." !important;\">$czi</a>";
						else
							$where = "<span style=\"color:".$group_colour." !important;\">$czi</span>";
						
						$online_users['online_users'][$session_user_id] = array(
							"where" => $where,
							"profile" => LinkToGT("index.php?modname=Users&op=userinfo&username=$username"),
							"username" => $username,
							"ip" => $session_ip,
							"viewer_ip" => $viewer_ip,
							"short_ip" => $filtered_ip,
							"group_colour" => $group_colour,
							"user_posts" => '',
							"hidden" => ((!$user_allow_viewonline) ? true:false),
						);						
					
						if ($user_allow_viewonline)
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
		$logout_url = LinkToGT("index.php?modname=Users&op=logout");
		
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

			if ($showpoints == 1 && isset($this->data['user_points']))
				$your_points = number_format($this->data['user_points']);

			$your_profile_url = LinkToGT("index.php?modname=Users&op=userinfo&username=".$this->data['username']."");
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
				(SELECT COUNT(user_id) FROM ".$this->users_table." WHERE user_regdate > ?) as today_register,
				(SELECT COUNT(user_id) FROM ".$this->users_table." WHERE user_regdate > ? AND user_regdate < ?) as yesterday_register,
				(SELECT user_id FROM ".$this->users_table." ORDER BY user_id DESC LIMIT 0,1) as last_user_id,
				(SELECT username FROM ".$this->users_table." ORDER BY user_id DESC LIMIT 0,1) as last_username,
				(SELECT g.group_colour FROM ".$this->users_table." as u LEFT JOIN ".$this->groups_table." AS g ON g.group_id = u.group_id ORDER BY u.user_id DESC LIMIT 0,1) as last_user_colour,						
				(SELECT COUNT(user_id) FROM ".$this->users_table." WHERE user_id > '1') as total_users,
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
			"wellcom_guest"			=> $greet_guest,
			"login_form"			=> $login_form,
			"user_points"			=> $your_points,
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
			"last_username"			=> $last_username,
			"last_user_profile_url"	=> $last_user_profile_url,
			
			"total_users"			=> $total_users,
			"today_visits"			=> $today_visits,
			"yesterday_visits"		=> $yesterday_visits,
			"total_visits"			=> $total_visits,
			
			//in no forum is ''
		);
		
		phpnuke_db_error();
		return $statistics;
	}
	
	public function add_user($user_data)
	{
		global $db, $nuke_configs, $userinfo, $users_system, $currentpage, $pn_dbcharset;
					
		$insert_query = array(
			'user_type' => 1, 
			'username' => $user_data['username'], 
			'user_email' => $user_data['user_email'], 
			'user_password' => $user_data['user_password'], 
			'user_regdate' => $user_data['user_regdate'], 
			'user_realname' => $user_data['user_realname'],
			'user_lang' => $user_data['user_lang'],
			'user_website' => $user_data['user_website'],
		);
		
		$result1 = $db->table($this->users_table)
			->insert($insert_query);
		
		if($result1)
		{
			return true;
		}
		return false;
	}
	
	public function get_avatar_url($row, $width=100, $height=100, $ignore_config=false)
	{
		global $nuke_configs, $ya_config;
		
		$ya_config = (isset($ya_config) && !empty($ya_config)) ? $ya_config:((isset($nuke_configs['users']) && $nuke_configs['users'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['users'])):array());

		if (empty($row['user_avatar']) || !$row['user_avatar_type'] || (!$ya_config['allow_avatar'] && !$ignore_config))
		{
			return LinkToGT("images/avatar.png");
		}
		
		$avatar = strtolower(trim($row['user_avatar']));
		$avatar_type = $row['user_avatar_type'];
		
		if($avatar_type == 'upload')
			$avatar_url = LinkToGT("index.php?modname=Users&op=get_user_avatar&avatar=$avatar");
		elseif($avatar_type == 'remote')
			$avatar_url = $avatar;
		elseif($avatar_type == 'gravatar')
		{
			$row['avatar_width'] = ($width) ? $width:(isset($ya_config['avatar_width']) ? $ya_config['avatar_width']:100);
			$row['avatar_height'] = ($height) ? $height:(isset($ya_config['avatar_height']) ? $ya_config['avatar_height']:100);
			$avatar_url = _GRAVATAR_URL;
			$avatar_url .=  md5($row['user_email']);

			if ($row['avatar_width'] || $row['avatar_height'])
				$avatar_url .= '?s=' . max($row['avatar_width'], $row['avatar_height']);
		}
		else
			$avatar_url = (file_exists("images/avatar.png")) ? LinkToGT("images/avatar.png"):LinkToGT("images/blank.gif");


		return $avatar_url;
	}
	
	public function get_login_form_data($html=true, $options = array())
	{
		global $currentpage, $nuke_configs;
		
		if (extension_loaded("gd") AND in_array("user_login" ,$nuke_configs['mtsn_gfx_chk']))
		{
			$sec_code_options = array(
				"input_attr" => array(
					"class" => "form-control input input-lg",
					"placeholder" => _SECCODE
				),
				"img_attr" => array(
					"width" => 90,
					"height" => 29,
					"class" => "code"
				)
			);	
			
			$security_code_input = makepass("_USER_LOGIN", $sec_code_options);
		}
		
		if($html)
		{
			$login_form = "
			<p>
				<span style=\"font-size:8px;\" class=\"glyphicon glyphicon-record\"></span> <a href=\"".LinkToGT("index.php?modname=Users&op=register")."\">"._REGISTER."</a><br>
				<span style=\"font-size:8px;\" class=\"glyphicon glyphicon-record\"></span> <a href=\"".LinkToGT("index.php?modname=Users&op=reset_password")."\">"._RESET_PASSWORD."</a>
			</p>
			<form class=\"form-signin\" action=\"".LinkToGT("index.php?modname=Users&op=login")."\" method=\"post\" data-focus=\"username\">
				<div class=\"form-group\">
					<input type=\"text\" class=\"form-control\" id=\"username\" name=\"username\" placeholder=\""._USERNAME."\" required>
				</div>
				<div class=\"form-group\">
					<input type=\"password\" class=\"form-control\" id=\"password\" name=\"user_password\" required>
				</div>";
				if (extension_loaded("gd") AND in_array("user_login" ,$nuke_configs['mtsn_gfx_chk']))
					$login_form .= "
					<div class=\"form-group\">
						<div class=\"input-group\"> <span class=\"input-group-addon\">".$security_code_input['image']."</span> 
							".$security_code_input['input']."
						</div>
					</div>
					";										
				$login_form .="
				<div class=\"checkbox\">
					<label><input type=\"checkbox\" value=\"1\" name=\"remember_me\"> "._REMEMBER_ME."</label>
				</div>
				<button type=\"submit\" name=\"submit\" value=\""._LOGIN."\" class=\"btn btn-default\">"._LOGIN."</button>
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>
			<div class=\"clearfix\"></div>";
		}
		else
		{
		$login_form = array(
				"action"		=> LinkToGT("index.php?modname=Users&op=login"),
				"inputs"		=> array(
					"text"			=> array("username" => ""),
					"password"		=> array("user_password" => ""),
					"checkbox"		=> array("remember_me" => 1)
				),
				"html" 			=> array(
					"sec_code" => $security_code_input
				),
				"terms"			=> LinkToGT("index.php?modname=Users&op=terms"),
				"privacy"		=> LinkToGT("index.php?modname=Users&op=privacy"),
				"register"		=> LinkToGT("index.php?modname=Users&op=register"),
				"sendpassword"		=> LinkToGT("index.php?modname=Users&op=reset_password"),
			);
		}
		return $login_form;
	}
	
	public function cache_system()
	{
		global $db;
		
		$extra_code = array();
	}

	function add_form_key($form_name)
	{
		global $pn_Cookies;
		
		$now = _NOWTIME;
		$token_sid = ($this->data['user_id'] == ANONYMOUS && !empty($this->config['form_token_sid_guests'])) ? $pn_Cookies->get("sid") : '';
		$token = sha1($now . substr($this->data['user_password'], 0, 15) . $form_name . $token_sid);

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
			$result = $db->table(USERS_TABLE)
						->where('user_id', $user_id)
						->select(['user_phone']);
			if(intval($result->count()) > 0)
			{
				$row = $result->results()[0];
				$user_phone = $row['user_phone'];
			}
		}
		
		return $user_phone;
	}
	
	function online()
	{
		global $user, $visitor_ip, $pn_Cookies, $pn_Sessions, $db, $cache, $nuke_config, $currentpage;
		
		$past = _NOWTIME-(($nuke_config['session_timeout'] != "") ? $nuke_config['session_timeout']:3600);
		$session_browser = $_SERVER['HTTP_USER_AGENT'];
		
		$currentpage = ($currentpage != '' && $currentpage !== null) ? $currentpage:"index.php";
		
		$db->table(SESSIONS_TABLE)
			->where('session_time', '<', $past)
			->delete();
			
		$this->session_id = '';
		
		$user_id = isset($this->data['user_id']) ? intval($this->data['user_id']):1;
		$this->session_id = $session_id = $pn_Cookies->get("sid");
		
		$result = $db->table(SESSIONS_TABLE)
					->where('session_id', $session_id)
					->where('session_ip', $visitor_ip)
					->first();
					
		if($session_id != '' && $db->count() > 0)
		{
			$db->table(SESSIONS_TABLE)
				->where('session_id', $session_id)
				->where('session_ip', $visitor_ip)
				->update([
					'session_user_id' => $user_id,
					'session_time' => _NOWTIME,
					'session_browser' => $session_browser,
					'session_page' => $currentpage,
				]);
		}
		else
		{
			$session_id = md5(md5(microtime()));
			$pn_Cookies->set('sid', $session_id);
			
			$db->table(SESSIONS_TABLE)
				->insert([
					'session_id' => $session_id,
					'session_user_id' => $user_id,
					'session_ip' => $visitor_ip,
					'session_time' => _NOWTIME,
					'session_browser' => $session_browser,
					'session_page' => $currentpage,
				]);
			$db->table(SESSIONS_TABLE)
				->where('session_id', "!=", $session_id)
				->where('session_user_id', $user_id)
				->where('session_ip', $visitor_ip)
				->delete();
				
		}
	}

	function getuserinfo($rebuild = false)
	{
		global $db, $user, $nuke_configs, $pn_Cookies, $pn_Sessions, $pn_Bots;

		if (!$user OR empty($user))
			return NULL;

		if(!$rebuild)
		{
			$userinfo = $pn_Sessions->get('userinfo', false);
			
			if (isset($userinfo) AND is_array($userinfo))
				if (phpnuke_validate_user_cookie($user, 'user', $userinfo))
				{
					$this->data = $userinfo;
					return $userinfo;
				}
		}
		
		$user_id = intval($user[0]);
		
		$result = $db->table($this->users_table)
					->join($this->groups_table, [["".$this->groups_table.".group_id", "=", "".$this->users_table.".group_id"]], "left")
					->where($this->users_table.'.user_id', $user_id)
					->first();
		
		if ($db->count() == 1)
		{
			$this->data = $result->results();
			
			$result2 = $db->query("SELECT f.name, v.value FROM ".USERS_FIELDS_TABLE." as f LEFT JOIN ".USERS_FIELDS_VALUES_TABLE." as v ON v.fid = f.fid WHERE v.uid = ?", [$user_id]);
			
			$results = $result2->results();
			if(!empty($results))
			{
				foreach($results as $field => $value)
					$this->data[$value['name']] = $value['value'];
			}
			
			$this->data['is_registered'] = ($this->data['user_status'] == USER_NORMAL) ? true : false;
			$this->data['is_bot'] = (!$this->data['is_registered'] && $pn_Bots->isCrawler()) ? true : false;
			$this->data['user_lang'] = basename($this->data['user_lang']);
			
			$this->data['user_avatar_width'] = 180;
			$this->data['user_avatar_height'] = 180;
			$this->data['user_avatar_mimetype'] = "jpg";
				
			if($this->data['user_avatar'] != '' && $this->data['user_avatar_type'] != '')
			{
				$your_avatar = $this->get_avatar_url($this->data);
				
				if($your_avatar && file_exists($your_avatar))
				{
					list($width, $height, $type, $attr) = getimagesize($your_avatar);
					
					$this->data['user_avatar_width'] = $width;
					$this->data['user_avatar_height'] = $height;
					$this->data['user_avatar_mimetype'] = $type;
				}
			}
			
			foreach($this->user_fields as $key => $value)
				if(!isset($this->data[$key]) && isset($this->data[$value]))
					$this->data[$key] = $this->data[$value];
			
			$pn_Sessions->set('userinfo', $this->data);
		}
		return $this->data;
	}	
}

?>