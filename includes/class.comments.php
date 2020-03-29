<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
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

class phpnuke_comments
{
	private $module_name = '';
	private $post_title = '';
	public $Req_URIs = '';
	public $Req_URIs_2 = '';
	private $db_table = '';
	private $output = '';
	private $forum_groups_cacheData = array();
	private $sendtoconfirm = 0;
	private $db_id = 0;
	private $post_id = 0;
	private $total_rows = 0;
	private $comments_rows = array();
	private $scores_data = array();
	private $all_parent_comments = array();
	private $all_post_comments = array();

    public function __construct($comments_data)
    {
		global $nuke_configs, $comment_op, $comment_form_fields;
        $this->module_name		= filter($comments_data['module_name'], "nohtml");
        $this->post_title		= filter($comments_data['post_title'], "nohtml");
        $this->post_id			= intval($comments_data['post_id']);
        $this->allow_comments	= intval($comments_data['allow_comments']);
        $this->db_table			= filter($comments_data['db_table'], "nohtml");
        $this->db_id			= filter($comments_data['db_id'], "nohtml");
		
		$this->Req_URIs			= $nuke_configs['REQUESTURL'];
		$this->Req_URIs_2		= LinkToGT($this->Req_URIs.((isset($_GET['page']) && intval($_GET['page']) != 0) ? "comment-page-".intval($_GET['page'])."/":""));
		$this->comments_configs = phpnuke_unserialize(stripslashes($nuke_configs['comments']));
		if($nuke_configs['have_forum'] == 1)
		{
			$this->forum_groups_cacheData = get_cache_file_contents('nuke_forum_groups');
		}
		
		if(isset($comment_op) && $comment_op == "post_comments")
			$this->output .= $this->post_comments($comment_form_fields);
    }

    public function __destruct()
    {
        //Close
    }

	public function display_comments()
	{
		global $db, $nuke_configs, $users_system, $userinfo, $visitor_ip;
		
		if ($this->allow_comments > 0)
		{
			$votetype = $nuke_configs['votetype'];
			$user_id = (isset($userinfo['user_id']) && isset($userinfo['is_registered']) && $userinfo['is_registered'] == 1) ? $userinfo['user_id']:0;
			$query_params[':user_id'] = $user_id;
			$query_params[':visitor_ip'] = $visitor_ip;
			$vote_where = ($user_id != 0) ? "user_id = :user_id":"rating_ip = :visitor_ip";
				
			if($votetype == 1)
			{
				$votes_query	= "SUM(IF(score > 0, score, -score)) AS score, COUNT(id) AS ratings, 0 as likes, 0 as dislikes";
				$newvotetype = 1;
			}
			else
			{
				$votes_query	= "SUM(IF(score >= 1, 1, 0)) AS likes, SUM(IF(score < 0, 1, 0)) AS dislikes, COUNT(id) AS ratings, 0 as score";
					$newvotetype = "2,3";
			}			

			$where = array();
			$where[] = "c.module = :module";
			$where[] = "c.post_id = :post_id";
			
			$query_params[':module'] = $this->module_name;
			$query_params[':post_id'] = $this->post_id;
			$query_params[':username'] = $userinfo['username'];
			if(!is_admin())
				$where[] = "(c.status = '1' OR c.username = :username OR c.ip = :visitor_ip)";

			$order_limit = "";
			if($this->comments_configs['item_per_page'] > 0)
			{
				$entries_per_page			= $this->comments_configs['item_per_page'];
				$current_page				= (empty($_GET['page'])) ? 1 : intval($_GET['page']);
				$start_at					= ($current_page * $entries_per_page) - $entries_per_page;
				$link_to					= $this->Req_URIs;
				$order_limit = " LIMIT $start_at, ".$this->comments_configs['item_per_page']."";
			}
			
			$total_rows_query = ", COUNT(tc.cid) as total_rows";
			$total_rows_left = "LEFT JOIN ".COMMENTS_TABLE." AS tc ON ".str_replace("c.", "tc.", implode(" AND ", $where))." AND tc.pid = '0'";
			
			$first_where = "c.pid = '0'";
			
			$query_all = "SELECT c.*, 
				IF(c.username IS NULL OR c.username = '', '', (SELECT CONCAT_WS(',', ".$users_system->user_fields['group_id'].", ".$users_system->user_fields['user_avatar'].", ".$users_system->user_fields['user_avatar_type'].", ".$users_system->user_fields['user_email'].") FROM ".$users_system->users_table." WHERE ".$users_system->user_fields['username']." = c.username)) as user_avatar_data,
				EXISTS(SELECT 1 FROM ".COMMENTS_TABLE." WHERE pid= c.cid LIMIT 1) as replies,
				EXISTS(SELECT 1 FROM ".REPORTS_TABLE." WHERE post_id= c.cid AND module='comments' LIMIT 1) as reported
				{TOTAL_ROWS_QUERY}
				FROM ".COMMENTS_TABLE." AS c 
				{TOTAL_ROWS_LEFT}
				WHERE {FIRST_WHERE} AND ".implode(" AND ", $where)." 
				GROUP BY c.cid
				ORDER BY c.cid". (($this->comments_configs['order_by'] == 1) ? " DESC":" ASC")."{ORDER_LIMIT}";
			
			$result = $db->query(str_replace(array('{TOTAL_ROWS_QUERY}','{TOTAL_ROWS_LEFT}','{FIRST_WHERE}','{ORDER_LIMIT}'),array($total_rows_query,$total_rows_left,$first_where,$order_limit), $query_all), $query_params);

			if($db->count() > 0)
			{
				$this->comments_rows = $result->results();
				foreach ($this->comments_rows as $row)
				{
					$this->total_rows = $row['total_rows'];
					$all_cids[] = $row['cid'];
				}

				$sub_comments = array();
				$result2 = $db->query(str_replace(array('{TOTAL_ROWS_QUERY}','{TOTAL_ROWS_LEFT}','{FIRST_WHERE}','{ORDER_LIMIT}'),array('','',"c.main_parent IN (".implode(",", $all_cids).")",''), $query_all), $query_params);
				
				if($result2->count() > 0)
				{
					$sub_comments = $result2->results();
					foreach ($sub_comments as $row)
					{
						$all_cids[] = $row['cid'];
					}
				}
				
				// get comments scores
				$result = $db->query("SELECT 
				s.post_id, $votes_query, EXISTS(SELECT 1 FROM ".SCORES_TABLE." as sc WHERE ($vote_where) AND s.post_id = sc.post_id AND sc.db_table = 'comments' LIMIT 1) AS you_rated FROM ".SCORES_TABLE." as s WHERE s.post_id IN (".implode(",", $all_cids).") AND s.votetype IN ($newvotetype) AND s.db_table = 'comments' GROUP BY s.post_id", $query_params);
				
				if(!empty($result))
				{
					$scores = $result->results();
					foreach($scores as $scores_val)
					{
						$this->scores_data[$scores_val['post_id']] = array(
							'you_rated' => intval($scores_val['you_rated']),
							'likes' => $scores_val['likes'],
							'dislikes' => $scores_val['dislikes'],
							'ratings' => $scores_val['ratings'],
							'score' => $scores_val['score']
						);
					}
				}
				// get posts scores
				
				$this->comments_rows = array_merge($this->comments_rows, $sub_comments);
			}
			
			$this->output .= "
			<div class=\"clear\"></div>
			<a name=\"postcomments\"></a>";
			
			if($this->sendtoconfirm == 1 && $this->comments_configs['confirm_need'] == 1 && !is_admin())
			{
				$this->output .="<p align=\"center\" style=\"color:#FF0000;\">"._SUCCESSFULLYRECORDED."</p>";
				$this->sendtoconfirm = 0;
			}
			
			$this->output .= $this->display_comments_childs();
		
			if($this->comments_configs['item_per_page'] > 0 && $this->total_rows > $entries_per_page){
				$this->output .="
				<div id=\"pagination\" align=\"center\">";
				$this->output .= clean_pagination($this->total_rows, $entries_per_page, $current_page, $link_to, '', 'comment-page-%d/');
				$this->output .="</div><div style=\"clear:both;\"></div>";
			}
			
			if($this->comments_configs['allow'] == 1)
				$this->output .= $this->comment_form();
			
			return $this->output;
		}
	}
	
	public function display_comments_childs($pid=0, $depth=0, $main_parent=0)
	{
		global $nuke_configs, $users_system, $pn_Cookies, $visitor_ip;

		$pid = intval($pid);
		$comments_show = '';
		
		$depth = intval($depth);

		if(!empty($this->comments_rows))
		{
			foreach($this->comments_rows as $post_comment)
			{
				if($post_comment['pid'] != $pid) continue;
			
				$user_avatar_data = ($post_comment['user_avatar_data'] != '') ? explode(",", $post_comment['user_avatar_data']):array();
				$post_comment['user_avatar'] = (isset($user_avatar_data['user_avatar']) && !empty($user_avatar_data['user_avatar'])) ? $user_avatar_data['user_avatar']:'';
				$post_comment['user_avatar_type'] = (isset($user_avatar_data['user_avatar_type']) && !empty($user_avatar_data['user_avatar_type'])) ? $user_avatar_data['user_avatar_type']:'';
				$post_comment['user_email'] = (isset($user_avatar_data['user_email']) && !empty($user_avatar_data['user_email'])) ? $user_avatar_data['user_email']:'';
				
				$post_comment['deact'] = ((is_admin() || $post_comment['ip'] == $visitor_ip) AND $post_comment['status'] == 0) ? "<p align=\"center\" style=\"color:#FF0000;\"><b>"._INACTIVE."</b></p>":"";
				
				$post_comment['avatar'] = ($post_comment['user_avatar'] != '') ? $users_system->get_avatar_url($post_comment):LinkToGT("images/avatar-s.png");
				
				$post_comment['comment'] = smilies_parse(stripslashes($post_comment['comment']));
				
				if($nuke_configs['have_forum'] == 1 && isset($user_avatar_data['group_id']) && isset($this->forum_groups_cacheData[$user_avatar_data['group_id']]))
				{
					$post_comment['user_colour'] = $this->forum_groups_cacheData[$user_avatar_data['group_id']]['group_colour'];
					$post_comment['user_group_name'] = $this->forum_groups_cacheData[$user_avatar_data['group_id']]['group_name'];
				}
				else
				{
					$post_comment['user_colour'] = "#000000";
					$post_comment['user_group_name'] = "GUESTS";
				}
				
				$post_comment['user_colour'] = ($nuke_configs['have_forum'] == 1 && $post_comment['user_colour'] != '') ? "#".str_replace("#","",$post_comment['user_colour']):"#000000";

				$post_comment['date'] = nuketimes($post_comment['date'], false, false, false, 1);
				$post_comment['replies'] = intval($post_comment['replies']);
				$main_parent = ($depth == 1) ? intval($post_comment['main_parent']):$main_parent;

				$post_comment['likes'] = $post_comment['dislikes'] = $post_comment['score'] = $post_comment['ratings'] = 0;
				if(isset($this->scores_data[$post_comment['cid']]) && !empty($this->scores_data[$post_comment['cid']]))
					$post_comment = array_merge($post_comment, $this->scores_data[$post_comment['cid']]);
				
				$disabled_rating = false;

				if ($pn_Cookies->exists('Comments_ratecookie'))
				{
					$rcookie				= base64_decode($pn_Cookies->get('Comments_ratecookie'));
					$rcookie				= addslashes($rcookie);
					$r_cookie				= explode(",", $rcookie);
					if(in_array($post_comment['cid'], $r_cookie))
					{
						$disabled_rating	= true;
					}
				}

				if(isset($post_comment['you_rated']) && $post_comment['you_rated'] > 0)
					$disabled_rating	= true;
		
				$post_comment['rating_box']		= rating_load($post_comment['score'], $post_comment['ratings'], $post_comment['likes'], $post_comment['dislikes'], 'Comments', "cid", $post_comment['cid'], $disabled_rating, $nuke_configs['votetype']);

				$comments_show .= comments_theme($this, $post_comment, $depth, $main_parent);
			}
		}
		return $comments_show;
	}

	private function comment_form()
	{
		global $nuke_configs, $users_system, $userinfo, $last_comment_info;
		
		$is_user = is_user();
		$content = "";
		$content .="
		<div class=\"clear\"></div>
		\n\n<!-- COMMENTS FORM START -->\n
		<a name=\"commenteditor\"></a>
		<form class=\"form-horizontal\" style=\"margin:10px;\" role=\"form\" name = 'comments' action=\"".$this->Req_URIs_2."#commenteditor\" method=\"post\">
			<div class=\"col-sm-2\"></div><div class=\"col-sm-10\" style=\"margin-bottom:8px;\" id=\"reply_to_html\"></div>";
			if(isset($this->comments_configs['inputs']['name_act']) && $this->comments_configs['inputs']['name_act'] == 1 && (!$is_user || (isset($this->comments_configs['inputs']['name_enter']) && $this->comments_configs['inputs']['name_enter'] == 1)))
			{
				$name_req = (isset($this->comments_configs['inputs']['name_req']) && $this->comments_configs['inputs']['name_req'] == 1) ? " ("._REQUIERD.")":"";
				$name_info = (isset($last_comment_info['name']) && $last_comment_info['name'] != '') ? filter($last_comment_info['name'], "nohtml"):((isset($userinfo['realname']) && $userinfo['realname'] != '' && $userinfo['realname'] != 'anonymous') ? $userinfo['realname']:_ANONYMOUS);
			$content .="
			<div class=\"col-sm-2\"></div><div class=\"col-sm-10\" style=\"margin-bottom:8px;\">"._GUEST_ADD_COMMENT."</div>
			<div class=\"form-group\">
				<label for=\"comment_form_fields_name\" class=\"col-sm-2 control-label\">"._NAME." : $name_req</label>
				<div class=\"col-sm-10\">
					<input type=\"text\" class=\"form-control\" id=\"comment_form_fields_name\" name=\"comment_form_fields[name]\" placeholder=\""._ENTER_NAME."\" value=\"$name_info\">
				</div>
			</div>";
			}
			elseif($is_user)
			{
				$content .="
				<div class=\"col-sm-2\"><input type=\"hidden\" name=\"comment_form_fields[name]\" value=\"".$userinfo['username']."\"></div>
				<div class=\"col-sm-10\" style=\"margin-bottom:8px;\">".sprintf(_USER_ADD_COMMENT, "<span style=\"color:#".str_replace("#","",$userinfo['group_colour']).";\">".$userinfo['username']."</span>")."</div>";
			}
			if(isset($this->comments_configs['inputs']['email_act']) && $this->comments_configs['inputs']['email_act'] == 1 && (!$is_user || (isset($this->comments_configs['inputs']['email_enter']) && $this->comments_configs['inputs']['email_enter'] == 1)))
			{
				$email_req = (isset($this->comments_configs['inputs']['email_req']) && $this->comments_configs['inputs']['email_req'] == 1) ? " ("._REQUIERD.")":"";
				$email_info = (isset($last_comment_info['email']) && $last_comment_info['email'] != '') ? filter($last_comment_info['email'], "nohtml"):((isset($userinfo['user_email']) && $userinfo['user_email'] != '') ? $userinfo['user_email']:"");
			$content .="
			<div class=\"form-group\">
				<label for=\"comment_form_fields_email\" class=\"col-sm-2 control-label\">"._EMAIL." : $email_req</label>
				<div class=\"col-sm-10\">
					<input type=\"email\" class=\"form-control\" id=\"comment_form_fields_email\" name=\"comment_form_fields[email]\" placeholder=\""._ENTER_EMAIL."\" value=\"$email_info\">
				</div>
			</div>";
			}
			elseif($is_user)
				$content .="<input type=\"hidden\" name=\"comment_form_fields[email]\" value=\"".$userinfo['user_email']."\">";
				
			if(isset($this->comments_configs['inputs']['url_act']) && $this->comments_configs['inputs']['url_act'] == 1 && (!$is_user || (isset($this->comments_configs['inputs']['url_enter']) && $this->comments_configs['inputs']['url_enter'] == 1)))
			{
				$url_req = (isset($this->comments_configs['inputs']['url_req']) && $this->comments_configs['inputs']['url_req'] == 1) ? " ("._REQUIERD.")":"";
				$url_info = (isset($last_comment_info['url']) && $last_comment_info['url'] != '') ? filter($last_comment_info['url'], "nohtml"):((isset($userinfo['user_website']) && $userinfo['user_website'] != '') ? $userinfo['user_website']:"");
			$content .="
			<div class=\"form-group\">
				<label for=\"comment_form_fields_url\" class=\"col-sm-2 control-label\">"._URL." $url_req</label>
				<div class=\"col-sm-10\">
					<input type=\"text\" class=\"form-control\" id=\"comment_form_fields_url\" name=\"comment_form_fields[url]\" placeholder=\"http://www.domain.com\" value=\"$url_info\">
				</div>
			</div>";
			}
			
			elseif($is_user)
				$content .="<input type=\"hidden\" name=\"comment_form_fields[url]\" value=\"".((isset($userinfo['user_website']) && $userinfo['user_website'] != '') ? $userinfo['user_website']:"")."\">";
			$content .="
			<div class=\"form-group\">
				<label for=\"comment_form_fields_message\" class=\"col-sm-2 control-label\">"._UCOMMENT."</label>
				<div class=\"col-sm-10\">";
				if($this->comments_configs['editor'] == 1)
				{
					$content .= "
					<script language=\"javascript\">
						function Smiles(which)
						{
							var old_val = $(\"#comment_form_fields_textarea\").val();
							$(\"#comment_form_fields_textarea\").val(old_val+' '+which+' ');
						} 
					</script>";
					$content .="
					<textarea class=\"form-control\" rows=\"4\" name=\"comment_form_fields[comment]\" id=\"comment_form_fields_textarea\"></textarea><br><br>";
					
					$content .= smilies_parse('', true);
				}
				elseif($this->comments_configs['editor'] == 2)
				{
					$content .= wysiwyg_textarea('comment_form_fields[comment]', '', 'basic', 0, 0, '90%', '100px');
				}
				$content .="</div>
			</div>";
				if (extension_loaded("gd") AND in_array("comments" ,$nuke_configs['mtsn_gfx_chk']))
				{
	
					$sec_code_options = array(
						"input_attr" => array(
							"class" => "form-control",
							"id" => "comment_form_fields_human",
							"placeholder" => _ENTER_SECCODE
						)
					);	
					$security_code_input = makePass("_comments", $sec_code_options);
					$content .= "
					<div class=\"form-group\">
						<label for=\"comment_form_fields_human\" class=\"col-sm-2 control-label\">"._SECCODE."</label>
						<div class=\"col-sm-10\">
							<div class=\"comment_seccode\">".$security_code_input['image']."<br /><br />".$security_code_input['input']."</div>
						</div>
					</div>";
				}
			$content .="
			<div class=\"form-group\">
				<div class=\"col-sm-10 col-sm-offset-2\">
					<input id=\"submit\" name=\"submit\" type=\"submit\" value=\""._SEND."\" class=\"btn btn-primary\">
				</div>
			</div>
			<div class=\"form-group\">
				<div class=\"col-sm-10 col-sm-offset-2\">
				</div>
			</div>
			<input type=\"hidden\" name=\"comment_op\" value=\"post_comments\">
			<input type=\"hidden\" name=\"comment_form_fields[reply_pid]\" value=\"0\" id=\"reply_pid\">
			<input type=\"hidden\" name=\"comment_form_fields[reply_main_parent]\" value=\"0\" id=\"reply_main_parent\">
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>";
		
		if ((!isset($this->comments_configs['anonymous']) OR intval($this->comments_configs['anonymous']) == 0) AND !is_user())
		{
			$content = "<br>";
			$content .= "<div class=\"text-center\">"._NOANONCOMMENTS."</div>";
		}
		return $content;
	}
		
	private function post_comments($comment_form_fields)
	{
		global $db, $userinfo, $nuke_configs, $security_code, $security_code_id, $visitor_ip, $PnValidator, $users_system;

		$code_accepted = false;
		
		if(extension_loaded("gd") && in_array("comments", $nuke_configs['mtsn_gfx_chk']))
			$code_accepted = code_check($security_code, $security_code_id);
		else
			$code_accepted = true;

		$errors = array();
		
		if ($code_accepted && $this->comments_configs['allow'] == 1)
		{
			if(isset($this->comments_configs['inputs']['name_act']) && $this->comments_configs['inputs']['name_act'] == 1 && isset($this->comments_configs['inputs']['name_req']) && $this->comments_configs['inputs']['name_req'] == 1 && $comment_form_fields['name'] == '' && !isset($userinfo['is_registered']))
				$errors[] = _ENTER_NAME;
			if(isset($this->comments_configs['inputs']['email_act']) && $this->comments_configs['inputs']['email_act'] == 1 && isset($this->comments_configs['inputs']['email_req']) && $this->comments_configs['inputs']['email_req'] == 1 && $comment_form_fields['email'] == '' && !isset($userinfo['is_registered']))
				$errors[] = _ENTER_EMAIL;
			if(isset($this->comments_configs['inputs']['url_act']) && $this->comments_configs['inputs']['url_act'] == 1 && isset($this->comments_configs['inputs']['url_req']) && $this->comments_configs['inputs']['url_req'] == 1 && $comment_form_fields['url'] == '' && !isset($userinfo['is_registered']))
				$errors[] = _ENTER_URL;
			
			if($comment_form_fields['comment'] == '')
				$errors[] = _ENTER_UCOMMENT;

			if($this->comments_configs['limit'] != 0 && strlen($comment_form_fields['comment']) > $this->comments_configs['limit'])
				$comment_form_fields['comment'] = mb_substr($comment_form_fields['comment'], 0, $this->comments_configs['limit']);
				
				
			if($comment_form_fields['name'] == '')
				$comment_form_fields['name']	= (isset($userinfo['realname']) && $comment_form_fields['name'] == '') ? filter($userinfo['realname'], "nohtml"):"Anonymous";
				
			if($comment_form_fields['email'] == '')
			$comment_form_fields['email']	= (isset($userinfo['user_email']) && $comment_form_fields['email'] == '') ? filter($userinfo['user_email'], "nohtml"):"";
			
			if($comment_form_fields['url'] == '')
			$comment_form_fields['url']		= (isset($userinfo['user_website']) && $comment_form_fields['url'] == '') ? filter($userinfo['user_website'], "nohtml"):"";
			
			$status = ($this->comments_configs['confirm_need'] == 1 && !is_admin()) ? 0:1;
			
			$time = _NOWTIME;
			
			$username = (is_user() && isset($userinfo['username'])) ? filter($userinfo['username'], "nohtml"):'';
			
			$PnValidator->validation_rules(array(
				'name'		=> 'max_len,70|min_len,3',
				'url'		=> 'valid_url',
				'email'		=> 'valid_email'
			)); 
		
			// Get or set the filtering rules
			$PnValidator->filter_rules(array(
				'reply_pid'	=> 'trim|sanitize_string',
				'name'		=> 'trim|sanitize_string',
				'email'		=> 'trim|sanitize_email',
				'comment'	=> 'stripslashes',
			)); 

			$comment_form_fields = $PnValidator->sanitize($comment_form_fields, array('name','email'), true, true);
			$validated_data = $PnValidator->run($comment_form_fields);

			if($validated_data !== FALSE)
			{
				$comment_form_fields = $validated_data;			

				if(empty($errors))
				{
					$db->table(COMMENTS_TABLE)
						->insert([
							'pid' => $comment_form_fields['reply_pid'], 
							'main_parent' => $comment_form_fields['reply_main_parent'], 
							'module' => $this->module_name, 
							'post_id' => $this->post_id, 
							'post_title' => $this->post_title, 
							'date' => $time, 
							'name' => $comment_form_fields['name'], 
							'username' => $username, 
							'email' => $comment_form_fields['email'], 
							'url' => $comment_form_fields['url'], 
							'ip' => $visitor_ip, 
							'comment' => $comment_form_fields['comment'], 
							'status' => $status
						]);
						
					$db->query("UPDATE ".$this->db_table." SET comments=comments+1 WHERE ".$this->db_id." = '".$this->post_id."'");
					
					// notifications
						///send comment with sms to admins or members
						if(is_active("Sms") && isset($this->comments_configs['notify']['sms']) && $this->comments_configs['notify']['sms'] == 1)
						{
							require_once('modules/Sms/includes/sms.class.php');
							$comment = @preg_replace("/<[^>]+\>/i", "", strip_tags($comment_form_fields['comment']));
							$sms_text = mb_substr($comment,0,100);
							$sms_obj = new sms_obj();
							$sms_obj->text = $sms_text;
							$sms_obj->Send();
						}
						///send comment with sms to admins or members
						
						///send comment with email to admins or members
						if(isset($this->comments_configs['notify']['email']) && $this->comments_configs['notify']['email'] == 1)
						{
							phpnuke_mail($nuke_configs['adminmail'], _NEW_COMMENT, $comment_form_fields['comment']);
						}
						///send comment with email to admins or members
					// notifications
					
					update_points(5);
					
					if($this->comments_configs['confirm_need'] == 1 && !is_admin())
					{
						$this->sendtoconfirm = 1;
					}
					
					phpnuke_db_error();
				}
				else
				{
					$this->output .= "<div class=\"gump-error-message text-center\">"._ERROR_IN_OP." : <br /><Br />".implode("<br />", $errors)."</div><div class=\"clear\"></div>";
				}
			}
			else
			{
				$this->output .= $PnValidator->get_readable_errors(true,'','','<br />');
			}
		}
		else
		{
			$this->output .= "<div class=\"text-center\"><span style=\"color:#ff0000;\">"._BADSECURITYCODE."</span></div>";
		}
	}
}

?>