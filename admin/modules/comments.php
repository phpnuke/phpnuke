<?PHP

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2017 by MashhadTeam                                    */
/* http://www.phpnuke.ir                                                */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('ADMIN_FILE')) {
	die ("Access Denied");
}

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

if (check_admin_permission($filename))
{
	function get_main_parents($cids)
	{
		global $db, $nuke_configs;
		
		$main_parents = array();
		$when_query = array();
		
		$cids = (is_array($cids)) ? implode(",", $cids):$cids;
		
		$result = $db->query("SELECT main_parent FROM ".COMMENTS_TABLE." WHERE cid IN ($cids)");
		$fetched_rows = intval($result->count());
		if($fetched_rows > 0)
		{
			$rows = $result->results();
			foreach($rows as $row)
				$main_parents[] = $row['main_parent'];
		}
		return $main_parents;		
	}

	function update_comments_replay_time($main_parents)
	{
		global $nuke_configs, $db;
		
		$when_query = array();

		$main_parents = (is_array($main_parents)) ? implode(",", $main_parents):$main_parents;
		
		$result = $db->query("SELECT c.cid, c.`date`, (SELECT `date` FROM ".COMMENTS_TABLE." WHERE main_parent = c.cid AND status = '1' ORDER BY `date` DESC LIMIT 1) as last_replay_time FROM ".COMMENTS_TABLE." AS c WHERE c.cid IN ($main_parents)");

		$fetched_rows = intval($result->count());
		if($fetched_rows > 0)
		{
			$rows = $result->results();
			
			foreach($rows as $row)
			{
				$cid = $row['cid'];
				$date = $row['date'];
				$last_replay_time = (intval($row['last_replay_time']) > 0) ? $row['last_replay_time']:$date;
					
				$when_query[$row['cid']] = "WHEN cid = '$cid' THEN '$last_replay_time'";
			}
			
			if(!empty($when_query))
			{
				$cids = array_keys($when_query);
				$cids = implode(", ", $cids);
				$when_query = implode("\n", $when_query);
				$db->query("UPDATE ".COMMENTS_TABLE." SET last_replay_time = CASE 
					$when_query
				END
				WHERE cid IN($cids)");
			}
		}
	}

	function comments($status=1, $reported=0, $module_name='', $post_id=0, $post_title='')
	{
		global $db, $admin_file, $nuke_configs, $users_system, $hooks;
		$pagetitle = _COMMENTS_ADMIN.(($status == 1) ? " "._APPROVED:" "._PENDING).(($reported == 1) ? " "._REPORTED:"").(($module_name != '') ? " - "._MODULE." $module_name":"").(($post_title != '') ? " - ".sprintf(_POST_COMMENTS_VIEW, $post_title):"");
		
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("comments" => $pagetitle);});
		$contents = '';
		$contents .= GraphicAdmin();
		$nuke_configs['comments'] = ($nuke_configs['comments'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['comments'])):array();
		
		$all_modules_comments = array();
		$all_modules_comments = $hooks->apply_filters('modules_have_comments', $all_modules_comments);
		foreach($all_modules_comments as $modules_comment_key => $modules_comment_value)
		{
			$sel = ($module_name == $modules_comment_key) ? "selected":"";
			$all_modules_comments_link[] = "<option value=\"".$admin_file.".php?op=comments&module_name=$modules_comment_key&status=$status".(($reported != 0) ? "&reported=$reported":"").(($post_id != 0) ? "&post_id=$post_id&post_title=$post_title":"")."\" $sel>$modules_comment_value</option>";
		}
		$contents .= "<div align=\"center\" style=\"margin:20px 0;\">[ <a href=\"".$admin_file.".php?op=comments\">"._APPROVED_COMMENTS."</a> | <a href=\"".$admin_file.".php?op=comments&status=0\">"._PENDING_COMMENTS."</a> | <a href=\"".$admin_file.".php?op=comments&reported=1\">"._REPORTED_COMMENTS."</a> | <a href=\"".$admin_file.".php?op=settings\">"._SETTINGS."</a> ]<br /><br />"._VIEW_COMMENTS_IN_MODULE." <select onchange=\"top.location.href=this.options[this.selectedIndex].value\" class=\"styledselect-select\"><option value=\"".$admin_file.".php?op=comments\">"._ALL."</options>\n".implode("\n", $all_modules_comments_link)."</select></div>";
		
		$contents .= OpenAdminTable();
		$contents .= "
			<form action=\"".$admin_file.".php\" method=\"post\">
			<table align=\"center\" class=\"product-table\" width=\"100%\" id=\"nuke-comments\">
			<thead>
			<tr>
				<th class=\"table-header-repeat line-left\">"._COMMENT."	</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:180px;\">"._POST."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\">"._IN_REPLY."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:80px;\">"._MODULE."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:180px;\">"._DATE."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:100px;\">"._POSTER."</th>
				<th class=\"table-header-repeat no-padding\" style=\"width:160px\"><input data-label=\""._OPERATION."\" type=\"checkbox\" class=\"styled select-all\" data-element=\"#nuke-comments\"></th>
			</tr>
			</thead>
			<tbody>";
			
			$where = array();
			$where_values = array();
			$select2 = "";
			$reported = intval($reported);
			$post_id = intval($post_id);
			$module_name = filter($module_name, "nohtml");
			
			$total_rows = 0;
			$position = 0;
			$entries_per_page = 20;
			$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
			$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
			$link_to = "".$admin_file.".php?op=comments".(($status !== '') ? "&status=$status":"").(($reported != 0) ? "&reported=$reported":"").(($module_name != '') ? "&module_name=$module_name":"").(($post_id != 0) ? "&post_id=$post_id&post_title=$post_title":"");
			
			$status = intval($status);
			$where[] = "c.status =:status";
			$where_values[':status'] = $status;
			if($reported != 0)
			{
				$where[] = "c.reported =:reported";
				$where_values[':reported'] = $reported;
			}
			if($module_name != '')
			{
				$where[] = "c.module =:module_name";
				$where_values[':module_name'] = $module_name;
			}
			if($post_id != 0)
			{
				$where[] = "c.post_id =:post_id";
				$where_values[':post_id'] = $post_id;
			}
			
			$where_values[':start_at'] = intval($start_at);
			$where_values[':entries_per_page'] = intval($entries_per_page);
			
			$where = array_filter($where);
			$where = implode(" AND ", $where);
			$where_values = array_filter($where_values, function($v){
				return $v !== false && !is_null($v) && ($v != '' || $v == '0');
			});
			
			if(!isset($post_id) OR $post_id == 0)
			{
				$select2 = ", (SELECT COUNT(c3.cid) FROM ".COMMENTS_TABLE." AS c3 WHERE ".str_replace("c.","c3.", $where)." AND c3.post_id = c.post_id) as total_post_comments";
			}
			
			$result = $db->query("
				SELECT c.*, 
				(SELECT COUNT(c2.cid) FROM ".COMMENTS_TABLE." AS c2 WHERE ".str_replace("c.","c2.", $where).") as total_rows, 
				pc.name as reply_to, 
				(SELECT COUNT(c4.cid) FROM ".COMMENTS_TABLE." AS c4 WHERE ".str_replace("c.","c4.", $where)." AND c4.cid ".(($nuke_configs['comments']['order_by'] == 1) ? ">":"<")."= IF(c.main_parent =0, c.cid,c.main_parent) AND c4.pid ='0') as position
				$select2 
				FROM ".COMMENTS_TABLE." AS c 
				LEFT JOIN ".COMMENTS_TABLE." AS pc ON pc.cid = c.pid 
				WHERE $where 
				ORDER BY c.cid DESC LIMIT :start_at, :entries_per_page
			", $where_values);

			if($db->count() > 0)
			{
				foreach($result as $row)
				{
					if($total_rows == 0)
						$total_rows = intval($row['total_rows']);
					$position = intval($row['position']);
					$cid = intval($row['cid']);
					$pid = intval($row['pid']);
					$total_post_comments = (isset($row['total_post_comments'])) ? " <span style=\"background:#ccc;border-radius:5px;padding:2px 5px;display:inline-block;\">".intval($row['total_post_comments'])."</span>":0;
					$module = filter($row['module'], "nohtml");
					$post_title = filter($row['post_title'], "nohtml");
					$post_id = intval($row['post_id']);
					$date = nuketimes($row['date'], true, true, true);
					$username = filter($row['username'], "nohtml");
					$name = filter($row['name'], "nohtml");
					$email = filter($row['email'], "nohtml");
					$url = filter($row['url'], "nohtml");
					$ip = filter($row['ip'], "nohtml");
					$comment = stripslashes($row['comment']);
					$ip_info = "http://whatismyipaddress.com/ip/$ip";			
					$username_link = ($username == $ip) ? $ip_info:LinkToGT(sprintf($users_system->profile_url, '', $username));
					
					$post_link = '';
					$post_link = $hooks->apply_filters("get_post_link", $post_link, $module, $post_id);
						
					$current_comment_page = ($nuke_configs['comments']['item_per_page'] > 0) ? ceil($position/$nuke_configs['comments']['item_per_page']):0;
					$comment_link = $post_link;
					if($current_comment_page != 0 && $current_comment_page != 1)
					{
						$comment_link = trim($comment_link,"/");
						if($nuke_configs['gtset'] == 1)
							$comment_link .= "/comment-page-$current_comment_page/";
						else
							$comment_link .= "?page=$current_comment_page";
					}
						
					$reply_to = ($row['reply_to'] != "") ? "<a href=\"$post_link#comment-$pid\" target=\"_blank\">".stripslashes($row['reply_to'])."</a>":"";

					$module_link_to = "".$admin_file.".php?op=comments&module_name=$module".((isset($status)) ? "&status=$status":"").((isset($reported)) ? "&reported=$reported":"");
					$url_arr = ($url != '') ? explode("/", $url):array();
					$clean_url = is_array($url_arr) && !empty($url_arr) ? $url_arr[2]:'';
					$new_status = ($status != 0) ? 0:1;
					$new_status_desc = ($status != 0) ? _DISAPPROVE:_APPROVE;
					$new_status_icon = ($status != 0) ? 13:5;
					
					$contents .= "<tr>
						<td><a href=\"$comment_link#comment-$cid\" target=\"_blank\">$comment</a></td>
						<td><a href=\"$post_link\" target=\"_blank\">$post_title </a>".((isset($row['total_post_comments']) && intval($row['total_post_comments']) != 0) ? " <a href=\"$module_link_to&post_id=$post_id&post_title=$post_title\">$total_post_comments<a/>":"")."</td>
						<td align=\"center\">$reply_to</td>
						<td align=\"center\"><a href=\"$module_link_to\">$module</a></td>
						<td align=\"center\">$date</td>
						<td align=\"center\">
							<a href=\"$username_link\" target=\"_blank\">$name</a><br />
							<a href=\"$url\" target=\"_blank\">$clean_url</a><br />
							<a href=\"mailto:$email\" target=\"_blank\">$email</a>					
						</td>
						<td align=\"center\">
							<a href=\"#\" title=\""._EDIT."\" class=\"table-icon icon-1 info-tooltip editindialog\" data-op=\"comments_edit\" data-cid=\"$cid\"></a>
							<a href=\"".$admin_file.".php?op=comments_delete&cid=$cid&status=$status&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._DELETE_THIS_SURE."');\"></a>
							<a href=\"".$admin_file.".php?op=comments_status&cid=$cid&new_status=$new_status&status=$status&csrf_token="._PN_CSRF_TOKEN."\" title=\"$new_status_desc\" class=\"table-icon icon-$new_status_icon info-tooltip\"></a>
							<a href=\"#\" title=\""._REPLY."\" class=\"table-icon icon-10 info-tooltip replyindialog\" data-cid=\"$cid\" data-op=\"comments_reply\"></a>
							 <input type=\"checkbox\" class=\"styled\" name=\"cids[]\" value=\"$cid\" />
						</td>
						</tr>";
				}
			}
			$contents .= "
			</tbody>
		</table>
		
		<input type=\"hidden\" name=\"op\" value=\"comments_edit\">
		<input type=\"hidden\" name=\"comment_fields[status]\" value=\"$status\">
		<div class=\"text-center\">
			<select name=\"mode\">
				<option value=\"delete\" onclick=\"return confirm('"._DELETE_THIS_SURE."');\">"._DELETE."</option>
				<option value=\"approve\">"._APPROVE."</option>
				<option value=\"disapprove\">"._DISAPPROVE."</option>
			</select>
			<input type=\"submit\" value='"._OPERATION."'>
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</div></form><br /><br />
		<div id=\"pagination\" class=\"pagination\">";
		$contents .= admin_pagination($total_rows, $entries_per_page, $current_page, $link_to);
		$contents .= "</div>
		<div id=\"comments-dialog\"></div>
		<script>
			$(\".replyindialog, .editindialog\").click(function(e)
			{
				e.preventDefault();
				var cid = $(this).data('cid');
				var op = $(this).data('op');
				$.ajax({
					type : 'post',
					url : '".$admin_file.".php',
					data : {'op' : op, 'cid' : cid, csrf_token : pn_csrf_token},
					success : function(responseText){
						$(\"#comments-dialog\").html(responseText);

						$(\"#comments-dialog\").dialog({
							title: '"._COMMENT_VIEW."',
							resizable: false,
							height: 500,
							width: 800,
							modal: true,
							closeOnEscape: true,
							close: function(event, ui)
							{
								$(this).dialog('destroy');
								$(\"#comments-dialog\").html('');
							}
						});
					}
				});
			});
		</script>";

		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function comments_edit($cid, $update='', $comment_fields='', $show_header=0, $mode='', $cids = array())
	{
		global $db, $admin_file, $nuke_configs;
		
		if(isset($cid) && intval($cid) != 0)
			$cids = array($cid);
		
		if($mode != '' && isset($cids) && !empty($cids))
		{
			if($mode == 'delete')
				comments_delete(0, $cids, '', $comment_fields['status']);
			if($mode == 'approve' || $mode == 'disapprove')
				comments_status(0, $cids, (($mode == 'approve') ? 1:0), '', $comment_fields['status']);
				
			redirect_to("".$admin_file.".php?op=comments");
			die();
		}	
		
		if(isset($update) && $update != "" && isset($comment_fields) && is_array($comment_fields) && !empty($comment_fields))
		{
			$db->table(COMMENTS_TABLE)
				->where("cid", $cid)
				->update([
					'name' => $comment_fields['name'],
					'email' => $comment_fields['email'],
					'url' => $comment_fields['url'],
					'comment' => $comment_fields['comment']
				]);

			redirect_to("".$admin_file.".php?op=comments");
			die();
		}
		$nuke_configs['comments'] = ($nuke_configs['comments'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['comments'])):"";
		
		$row = $db->table(COMMENTS_TABLE)
			->where("cid", $cid)
			->select(['name','email', 'url', 'comment'])
			->first();
					
		$commenter_name = $row['name'];
		$commenter_email = $row['email'];
		$commenter_url = $row['url'];
		$comment = stripslashes($row['comment']);
		$content="
			<form action=\"".$admin_file.".php\" method=\"post\">
			<table align=\"center\" border=\"0\" width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<th style=\"width:150px;\">"._NAME."</th>
					<td><input name=\"comment_fields[name]\" value=\"$commenter_name\" class=\"inp-form\" size=\"40\" dir=\"rtl\" type=\"text\"></td>
				</tr>
				<tr>
					<th>"._EMAIL."</th>
					<td><input name=\"comment_fields[email]\" value=\"$commenter_email\" class=\"inp-form-ltr\" size=\"40\" type=\"text\"></td>
				</tr>
				<tr>
					<th>"._URL."</th>
					<td>
						<input name=\"comment_fields[url]\" size=\"60\" type=\"text\" class=\"inp-form-ltr\" value=\"$commenter_url\">
					</td>
				</tr>
				<tr>
					<th>"._COMMENT."</th>
					<td>";
						if($nuke_configs['comments']['editor'] == 0)
						{
							$content .= wysiwyg_textarea("comment_fields[comment]", $comment, 'basic', 30, 5, $width = '500px', $height = '100px');
						}
						else
							$content.="<textarea class=\"form-textarea\" name='comment_fields[comment]' cols='70' rows='15'>".$comment."</textarea>";
					$content.="</td>
				</tr>
				<tr>
					<td colspan=\"2\" align=\"center\">
					<input type=\"submit\" value=\""._SEND."\" name=\"update\" class=\"form-submit\">
					</td>
				</tr>
			</table><input type=\"hidden\" name=\"op\" value=\"comments_edit\"><input type=\"hidden\" name=\"cid\" value=\"$cid\"><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> </form>";
			if($show_header == 1)
			{
				$contents = '';
				include("header.php");
				$contents .= GraphicAdmin();
				$contents .= OpenAdminTable();
				$contents .= $content;
				$contents .= CloseAdminTable();
				$html_output .= $contents;
				include("footer.php");
				die();
			}
			$content .= jquery_codes_load();
			die($content);
	}

	function comments_delete($cid = 0, $cids = array(), $comment_username='', $status = 1)
	{
		global $db, $admin_file, $nuke_configs, $nuke_configs_comments_table, $hooks;
		
		$where = '';
		
		if(isset($cids) && !empty($cids))
			$deleted_cids = $cids;
		elseif(isset($cid) && intval($cid) != 0)
		{
			csrfProtector::authorisePost(true);
			$deleted_cids = array($cid);
		}
		
		if($comment_username != '')
		{
			$comment_username = filter($comment_username, "nohtml");
			$where = "username = '$comment_username'";
		}
		
		if(!empty($deleted_cids))
		{
			$result = $db->table(COMMENTS_TABLE)
						->in('cid', $deleted_cids)
						->select(['module','post_id']);
						
			$module_table_data = array('','');
			$module_table_data = $hooks->apply_filters("modules_comments_table_data", $module_table_data);
			
			if(intval($result->count()) > 0 && isset($module_table_data) && !empty($module_table_data))
			{
				$rows = $result->results();
				foreach($rows as $row)
				{
					$module = $row['module'];
					$post_ids[] = $row['post_id'];
					
					if(isset($module_table_data[$module]))
					{
						$module_table_data = $module_table_data[$module];
					}
				}
				
				if($module_table_data[0] != '')
				{
					
					$duplicated_post_ids = array_count_values($post_ids);
					
					foreach($duplicated_post_ids as $post_id => $counts)
					{
						$when_query[] = "WHEN ".$module_table_data[0]." = $post_id THEN comments-$counts";
					}
					
					$db->query("UPDATE ".$module_table_data[1]." SET comments = CASE ".implode("\n", $when_query)." END WHERE ".$module_table_data[0]." IN(".implode(",", $post_ids).")");
				}
			}
			
			$deleted_cids = implode(",", $deleted_cids);
			$main_parents = get_main_parents($deleted_cids);
			
			$where = "(cid IN ($deleted_cids) OR pid IN($deleted_cids))";
			$db->query("DELETE FROM ".COMMENTS_TABLE." WHERE ".$where."");

			if(!empty($main_parents))
				update_comments_replay_time($main_parents);
		}	
		
		phpnuke_auto_increment(COMMENTS_TABLE);
		
		phpnuke_db_error();
		
		redirect_to("".$admin_file.".php?op=comments&status=$status");
		die();
	}

	function comments_status($cid = 0, $cids = array(), $new_status=0, $comment_username='', $status = 1)
	{
		global $db, $admin_file, $nuke_configs;

		$new_status = intval($new_status);
		$where = array();
		if($comment_username != '')
		{
			$comment_username = filter($comment_username, "nohtml");
			$where[] = "username = '$comment_username'";
		}
		
		$cid = intval($cid);
		
		if(isset($cids) && !empty($cids))
			$status_cids = $cids;
		elseif(isset($cid) && intval($cid) != 0)
		{
			csrfProtector::authorisePost(true);
			$status_cids = array($cid);
		}
		
		if(!empty($status_cids))
		{			
			$status_cids = implode(",", $status_cids);
			$where[] = "cid IN ($status_cids)";

			$where = implode(" AND ", $where);
			$db->query("UPDATE ".COMMENTS_TABLE." SET status = '$new_status' WHERE $where");

			$main_parents = get_main_parents($status_cids);
			if(!empty($main_parents))
				update_comments_replay_time($main_parents);
		}
		
		phpnuke_db_error();
		redirect_to("".$admin_file.".php?op=comments&status=$status");
		die();
	}

	function comments_reply($cid, $reply_save, $reply)
	{
		global $db, $admin_file, $nuke_configs, $userinfo, $aid, $visitor_ip, $nuke_configs_comments_table;
		$cid = intval($cid);
		
		$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
		
		$row = $db->table(COMMENTS_TABLE)
					->where("cid", $cid)
					->first(["comment", "main_parent", "module", "post_id", "post_title"]);
					
		if(isset($reply_save) && $reply_save != "" && isset($reply) && $reply != '')
		{
			$commenter_username	= isset($userinfo['username']) ? $userinfo['username']:$aid;
			$commenter_name		= isset($userinfo['name']) ? $userinfo['name']:$nuke_authors_cacheData[$aid]['realname'];
			$commenter_email	= isset($userinfo['user_email']) ? $userinfo['user_email']:$nuke_authors_cacheData[$aid]['email'];
			$commenter_url		= isset($userinfo['user_website']) ? $userinfo['user_website']:$nuke_authors_cacheData[$aid]['url'];
		
			$module = $row['module'];
			$post_id = $row['post_id'];
		
			$db->table(COMMENTS_TABLE)
				->insert([
					'pid' => $cid,
					'main_parent' => (($row['main_parent'] == 0) ? $cid:$row['main_parent']),
					'module' => $row['module'],
					'post_id' => $row['post_id'],
					'post_title' => $row['post_title'],
					'date' => _NOWTIME,
					'last_replay_time' => _NOWTIME,
					'name' => $commenter_name,
					'username' => $commenter_username,
					'email' => $commenter_email,
					'url' => $commenter_url,
					'ip' => $visitor_ip,
					'comment' => $reply,
					'status' => 1
				]);
			
			if(isset($nuke_configs_comments_table[$module]))
			{
				$module_table_data = $nuke_configs_comments_table[$module];
			}
					
			if($module_table_data[0] != '')
				$db->query("UPDATE ".$module_table_data[1]." SET comments = comments+1 WHERE ".$module_table_data[0]." = '$post_id'");
			if($row['main_parent'] != 0)
				$db->query("UPDATE ".COMMENTS_TABLE." SET last_replay_time='"._NOWTIME."' WHERE cid = '".$row['main_parent']."'");
			
			header("location: ".$admin_file.".php?op=comments");
			die();
		}		

		$nuke_configs['comments'] = ($nuke_configs['comments'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['comments'])):"";
		$comment = $row['comment'];
		$content="
			<form action=\"".$admin_file.".php\" method=\"post\">
			<table align=\"center\" border=\"0\" width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<td align=\""._TEXTALIGN1."\"><div class=\"form-textarea\" style=\"width:100%;\">$comment</div></td>
				</tr>
				<tr>
					<td>";
						if($nuke_configs['comments']['editor'] == 0)
						{
							$content .= wysiwyg_textarea("reply", '', 'basic', 30, 5, $width = '100%', $height = '100px');
						}
						else
							$content.="<textarea class=\"form-textarea\" name='reply' cols='70' rows='15'></textarea>";
					$content.="</td>
				</tr>
				<tr>
					<td align=\"center\">
					<input type=\"submit\" value=\""._SEND."\" name=\"reply_save\" class=\"form-submit\">
					</td>
				</tr>
			</table>
			<input type=\"hidden\" name=\"op\" value=\"comments_reply\">
			<input type=\"hidden\" name=\"cid\" value=\"$cid\">
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>";
			$content .= jquery_codes_load();
			die($content);
	}

	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$module_name = (isset($module_name)) ? filter($module_name, "nohtml"):'';
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'';
	$update = (isset($update)) ? filter($update, "nohtml"):'';
	$reply_save = (isset($reply_save)) ? filter($reply_save, "nohtml"):'';
	$post_title = (isset($post_title)) ? filter($post_title, "nohtml"):'';
	$reply = (isset($reply)) ? stripslashes($reply):0;
	$new_status = (isset($new_status)) ? intval($new_status):0;
	$cids = (isset($cids)) ? $cids:array();
	$comment_fields = (isset($comment_fields)) ? $comment_fields:array();
	$comment_username = (isset($comment_username)) ? filter($comment_username, "nohtml"):'';
	$status = (isset($status)) ? intval($status):1;
	$reported = (isset($reported)) ? intval($reported):0;
	$show_header = (isset($show_header)) ? intval($show_header):0;
	$post_id = (isset($post_id)) ? intval($post_id):0;
	$cid = (isset($cid)) ? intval($cid):0;
	
	switch ($op) {
		case "comments":
			comments($status, $reported, $module_name, $post_id, $post_title);
		break;
		case "comments_edit":
			comments_edit($cid, $update, $comment_fields, $show_header, $mode, $cids);
		break;
		case "comments_delete":
			comments_delete($cid, $cids, $comment_username, $status);
		break;
		case "comments_status":
			comments_status($cid, $cids, $new_status, $comment_username, $status);
		break;
		case "comments_reply":
			comments_reply($cid, $reply_save, $reply);
		break;
	}
}
else
{
	header("location: ".$admin_file.".php");
}
?>