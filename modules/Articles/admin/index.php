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

if (!defined('ADMIN_FILE')) {
	die ("Access Denied");
}

if (check_admin_permission($module_name, false, true))
{
	/*********************************************************/
	/* articles Functions                                  */
	/*********************************************************/
	function articles_menu()
	{
		global $db, $admin_file, $nuke_configs, $all_post_types;
		$post_types = array();
		$contents = "
		<p align=\"center\" style=\"padding:20px; 0;\">[ 
			<a href=\"".$admin_file.".php?op=articles\">"._SHOW_ALL."</a> | 
			<a href=\"".$admin_file.".php?op=articles&status=publish\">"._PUBLISHED."</a> | 
			<a href=\"".$admin_file.".php?op=articles&status=future\">"._PUBLISH_IN_FUTURE."</a> | 
			<a href=\"".$admin_file.".php?op=articles&status=draft\">"._DRAFT."</a> | 
			<a href=\"".$admin_file.".php?op=articles&status=pending\">"._PENDING_POST."</a> | 
			<a href=\"".$admin_file.".php?op=categories&module_name=Articles\">"._ARTICLES_CATEGORIES."</a>
		]</p>
		<p align=\"center\">[ "._NEW_ARTICLE." :  ";
			foreach($all_post_types as $post_type_key => $post_type_desc)
				$post_types[] ="<a href=\"".$admin_file.".php?op=article_admin&post_type=$post_type_key\">$post_type_desc</a>";
			
			$post_types = implode(" | ", $post_types);
		$contents .= "$post_types
		]</p>";
		return $contents;
	}
	
	function articles($status='', $post_type = 'Articles', $search_query = '', $order_by = '', $sort='DESC')
	{
		global $db, $hooks, $admin_file, $nuke_configs, $all_post_types;
		
		$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
		
		$contents = '';
		$articles_mid = 0;
		foreach($nuke_modules_cacheData as $mid => $module_data)
			if($module_data['title'] == 'Articles')
			{
				$articles_mid = $mid;
				break;
			}
		
		switch($status)
		{
			case"publish":
				$post_status = " "._PUBLISHED."";
			break;
			case"future":
				$post_status = " "._PUBLISH_IN_FUTURE."";
			break;
			case"draft":
				$post_status = " "._DRAFT."";
			break;
			case"pending":
				$post_status = " "._PENDING_POST."";
			break;
			default:
				$post_status = "";
			break;
		}
		
		$pagetitle = _ARTICLES_ADMIN."".(($post_status != '') ? $post_status:"");
		
		$link_to_more = "&post_type=$post_type";
		$publish_now = "";
		$where = array();
		$params = array();
		
		if($status != '')
		{
			$link_to_more .= "&status=$status";
			$params[":status"] = $status;
			$where[] = "s.status = :status";
		}
		
		$module = (isset($post_type) && $post_type != '') ? $post_type:"Articles";
		
		if(isset($post_type) && $post_type != '')
		{
			$params[":post_type"] = $post_type;
			$where[] = "s.post_type = :post_type";
			
			$pagetitle .= (isset($all_post_types[$post_type])) ? " - ".$all_post_types[$post_type]:"";
		}
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("articles" => $pagetitle);});
		
		if(isset($search_query) && $search_query != '')
		{
			$params[":search_query"] = "%".rawurldecode($search_query)."%";
			$where[] = "s.title LIKE :search_query";
			$link_to_more .= "&search_query=".rawurlencode($search_query)."";
		}
		
		$where = array_filter($where);
		$where = (!empty($where)) ? "WHERE ".implode(" AND ", $where):'';

		$sort = ($sort != '' && in_array($sort, array("ASC","DESC"))) ? $sort:"DESC";
		$order_by = ($order_by != '') ? $order_by:'sid';
		$sort_reverse = ($sort == 'ASC') ? "DESC":"ASC";	
		
		$entries_per_page = 20;
		$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
		$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
		$link_to = "".$admin_file.".php?op=articles".$link_to_more;

		$total_rows = 0;
		$result = $db->query("
			SELECT s.*,
			(SELECT COUNT(s2.sid) FROM ".POSTS_TABLE." AS s2 ".str_replace("s.","s2.", $where).") as total_rows, 
			(SELECT sc.catname FROM ".CATEGORIES_TABLE." AS sc WHERE sc.module = s.post_type AND s.cat_link = sc.catid) as cat_name
			FROM ".POSTS_TABLE." AS s 
			$where 
			ORDER BY s.$order_by $sort LIMIT $start_at, $entries_per_page
		", $params);
		
		$table_fields = array(
			array(
				"width" => "70px",
				"op" => "articles&post_type=$post_type",
				"id" => "sid",
				"text" => _ID,
			),
			array(
				"width" => "auto",
				"op" => "articles&post_type=$post_type",
				"id" => "title",
				"text" => _TITLE,
			),
			array(
				"width" => "120px",
				"op" => "articles&post_type=$post_type",
				"id" => "time",
				"text" => _PUBLISH_DATE,
			),
			array(
				"width" => "70px",
				"op" => "articles&post_type=$post_type",
				"id" => "comments",
				"text" => _COMMENTS,
			),
			array(
				"width" => "120px",
				"text" => _AUTHOR,
			),
			array(
				"width" => "120px",
				"text" => _CATEGORY,
			),
			array(
				"width" => "120px",
				"text" => _IN_MAIN_PAGE,
			),
			array(
				"width" => "160px",
				"text" => _OPERATION,
			),
		);
		
		$contents .= GraphicAdmin();
		$contents .= articles_menu();
		$contents .= OpenAdminTable();
		$contents .= "
			<table align=\"center\" class=\"product-table no-border no-hover\" width=\"100%\">
				<tr>
					<form action=\"".$admin_file.".php\" method=\"post\">
						<td width=\"50%\">
							"._SEARCH_BY_TITLE."
							<input class=\"inp-form\" type=\"text\" name=\"search_query\" size=\"30\" value=\"$search_query\" />
							<input type=\"hidden\" value=\"articles\" name=\"op\" />
							<input type=\"hidden\" name=\"order_by\" value=\"$order_by\" />
							<input type=\"hidden\" name=\"status\" value=\"$status\" />
							<input type=\"hidden\" name=\"sort\" value=\"$sort\" />
							<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
							<input type=\"submit\" class=\"form-search\" value=\""._SEARCH."\" /></p>
						</td>
					</form>
					<form action=\"".$admin_file.".php\" method=\"get\">
						<td width=\"50%\">
							"._VIEW_POST_TYPE."
							<input type=\"hidden\" value=\"articles\" name=\"op\" />
							<select name=\"post_type\" class=\"styledselect-select\">
								<option value=\"\">"._ALL."</option>";
								foreach($all_post_types as $post_type_key => $post_type_desc)
								{
									$sel = ($post_type == $post_type_key) ? "selected":"";
									$contents .="<option value=\"$post_type_key\" $sel>".$post_type_desc."</option>";
								}
							$contents .="</select>
							<input type=\"hidden\" name=\"order_by\" value=\"$order_by\" />
							<input type=\"hidden\" name=\"status\" value=\"$status\" />
							<input type=\"hidden\" name=\"sort\" value=\"$sort\" />
							<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
							<input type=\"submit\" class=\"form-search\" value=\""._VIEW."\" /></p>
						</td>
					</form>
				</tr>
			</table>
			<table align=\"center\" class=\"product-table\" width=\"100%\">
			<thead>
			<tr>
				".admin_tables_sortable($table_fields, $sort, $link_to_more, $order_by)."
			</tr>
			</thead>
			<tbody>";
			if(!empty($result))
			{
				foreach($result as $row)
				{
					$total_rows = intval($row['total_rows']);
					$sid = intval($row['sid']);
					$cat_link = intval($row['cat_link']);
					$comments = intval($row['comments']);
					$ihome = (intval($row['ihome'] == 1)) ? _YES:_NO;
					$title = filter($row['title'], "nohtml");
					$post_type = filter($row['post_type'], "nohtml");
					$datetime = nuketimes($row['time'], false, false, false, 1);
					$auther_id = filter($row['aid'], "nohtml");
					$cat_name = filter($row['cat_name'], "nohtml");
					$this_status = filter($row['status'], "nohtml");
					$article_link = LinkToGT(articleslink($sid, $title, filter($row['post_url'], "nohtml"), $row['time'], intval($row['cat_link']), $post_type));
					
					switch($this_status)
					{
						case"future":
							$this_post_status = " ("._PUBLISH_IN_FUTURE.")";
						break;
						case"draft":
							$this_post_status = " ("._DRAFT.")";
						break;
						case"pending":
							$this_post_status = " ("._PENDING_ARTICLES.")";
						break;
						default:
							$this_post_status = "";
						break;
					}
					
					$title_link = ($this_status == 'pending' | $this_status == 'draft') ? "".$title."":"<a href=\"$article_link\" target=\"_blank\">$title</a>";
					
					$publish_now = (in_array($this_status , array("future","draft"))) ? "<a href=\"".$admin_file.".php?op=article_admin&mode=publish_now&sid=$sid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._PUBLISH."\" class=\"table-icon icon-5 info-tooltip\"></a>":"";
			
					$contents .= "<tr>
						<td>$sid</td>
						<td>".$title_link."$this_post_status</td>
						<td align=\"center\">$datetime</td>
						<td align=\"center\">$comments</td>
						<td align=\"center\">$auther_id</td>
						<td align=\"center\">$cat_name</td>
						<td align=\"center\">$ihome</td>
						<td align=\"center\">
							<a href=\"".$admin_file.".php?op=article_admin&mode=edit&sid=$sid\" title=\""._EDIT."\" class=\"table-icon icon-1 info-tooltip\"></a>
							<a href=\"".$admin_file.".php?op=article_admin&mode=delete&sid=$sid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._ARTICLE_DELETE_SURE."');\"></a>
							<a href=\"#\" title=\""._CHANGE_AUTHOR."\" class=\"table-icon icon-6 info-tooltip change_admin\" data-sid=\"$sid\" data-op=\"article_change_admin\"></a>
							".$publish_now."
							<a href=\"".$admin_file.".php?op=module_edit_boxess&mid=$articles_mid&module_part=".(($post_type != 'Articles') ? strtolower($post_type)."_":"")."more&special_page=post_".$sid."\" title=\""._BLOCKS_LAYOUT."\" class=\"table-icon icon-15 info-tooltip\"></a>
						</td>
						</tr>";
				}
			}
			$contents .= "
			</tbody>
		</table>";
		
		if($total_rows > 0)
		{
			$contents .= "<div id=\"pagination\" class=\"pagination\">";
			$contents .= admin_pagination($total_rows, $entries_per_page, $current_page, $link_to);
			$contents .= "</div>";
		}
		else
			$contents .= "<p align=\"center\">"._NO_ARTICLE_FOUND."</p>";
		
		$contents .= "<div id=\"comments-dialog\"></div>
		<script>
			$(\".change_admin\").click(function(e)
			{
				e.preventDefault();
				var sid = $(this).data('sid');
				var op = $(this).data('op');
				var dialog_title = $(this).attr('title');
				$.post(\"".$admin_file.".php\",
				{
					op: op,
					sid: sid,				
					status: '$status',	
					csrf_token : pn_csrf_token
				},
				function(responseText, status){
					$(\"#comments-dialog\").html(responseText);
					$(\"#comments-dialog\").dialog({
						title: dialog_title,
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
				});
			});
		</script>";

		$contents .= CloseAdminTable();
		phpnuke_db_error();
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	function article_admin($sid=0, $mode="edit", $submit='', $article_fields=array(), $article_image_upload=array(), $micro_data=array(), $go_to_pings=1, $post_type = 'Articles')
	{
		global $db, $aid, $visitor_ip, $admin_file, $nuke_configs, $nuke_articles_configs_cacheData, $PnValidator, $module_name, $PingOptimizer, $all_post_types, $hooks, $cache;
		
		$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
		$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
		$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
		
		$sid = intval($sid);
		$row = array();
	
		$mode = (!in_array($mode, array("edit", "delete", "publish_now", "grapesjs", "auto_save"))) ? "edit":$mode;
		$article_status = "publish";
		$article_aid = '';
		$radminsuper = false;
		$radminarticle = false;
		
		$all_positions_data = (isset($nuke_configs['positions'])) ? phpnuke_unserialize(stripslashes($nuke_configs['positions'])):array();
	
		$nuke_modules_cacheData_by_title = phpnuke_array_change_key($nuke_modules_cacheData, "mid", "title");

		$radminsuper = (intval($nuke_authors_cacheData[$aid]['radminsuper']) == 1) ? true:false;;
		
		$articles_admins = ($nuke_modules_cacheData_by_title['Articles']['admins'] != '') ? explode(",", $nuke_modules_cacheData_by_title['Articles']['admins']):array();
		$radminarticle = (in_array($aid, $articles_admins)) ? true:false;

		// determine radminsuper & article_aid & article_status & counter
		if($sid != 0 && in_array($mode, array('delete', 'edit', 'auto_save', 'publish_now', 'grapesjs')))
		{			
			$result2 = $db->table(POSTS_TABLE)
							->where("sid", $sid)
							->first([
								"aid as article_aid", 
								"status as article_status", 
								"title as old_title", 
							]);
			if($result2->count() > 0)
			{
				$article_aid = filter($result2['article_aid'], "nohtml");
				$article_status = filter($result2['article_status'], "nohtml");
				$old_title = filter($result2['old_title'], "nohtml");
			}
			
			if($article_aid == '' && $article_status == 'pending')
				$article_aid = $aid;
				
			$row = $db->table(POSTS_TABLE)
							->where("sid", $sid)
							->first();
		}
		
		if($sid == 0)
		{
			$db->table(POSTS_TABLE)
				->insert(["sid" => null, 'status' => 'draft', 'time' => _NOWTIME]);
			$sid = $db->lastInsertId();
			$row['sid'] = $sid;
			$row['aid'] = $article_aid;
			$row['status'] = 'draft';
			$row['time'] = _NOWTIME;
			
			$db->table(AUTHORS_TABLE)
				->where("aid", $article_aid)
				->update([
					'counter' => ["+", 1]
				]);
			
			$del_result = $db->table(POSTS_TABLE)
				->where('status', 'draft')
				->where('title', '')
				->where('time', '<=', (_NOWTIME-100))
				->select(['sid','aid']);
			if($del_result->count() > 0)
			{
				$del_rows = $del_result->results();
				foreach($del_rows as $del_row)
				{
					$del_sid = $del_row['sid'];
					$del_aid = $del_row['aid'];
					
					$db->table(AUTHORS_TABLE)
						->where("aid", $del_aid)
						->update([
							'counter' => ["-", 1]
						]);
					$db->table(POSTS_TABLE)
						->where('sid', $del_sid)
						->delete();
				}
			}
		}
		
		if(isset($article_fields['post_type']) && $article_fields['post_type'] != '')
			$post_type = $article_fields['post_type'];
		elseif(isset($row['post_type']) && $row['post_type'] != '')
			$post_type = $row['post_type'];
		
		if($article_aid == '')
			$article_aid = $aid;
		
		// check admin permission
		if($mode == "edit" || $mode == "delete" || $mode == "auto_save" || $mode == "publish_now")
		{
			if ((!$radminarticle OR $article_aid != $row['aid']) AND !$radminsuper)
			{
				if($mode != 'auto_save')
				{
					include("header.php");
					$html_output .= title(_ARTICLES_ADMIN, _NOTAUTHORIZED1."<br><br>"._NOTAUTHORIZED2);
					include("footer.php");
				}
				else
				{
					die(_NOTAUTHORIZED1." "._NOTAUTHORIZED2);
				}
			}
		}
		
		// delete article
		if($mode == "delete")
		{
			$hooks->do_action("delete_post_before", $sid);	
			if (($radminarticle AND $article_aid == $aid) OR $radminsuper)
			{
				csrfProtector::authorisePost(true);
				
				if(file_exists("files/".$row['post_type']."/$sid.jpg"))
					unlink("files/".$row['post_type']."/$sid.jpg");
				
				if(file_exists("files/".$row['post_type']."/thumbs/$sid.jpg"))
				unlink("files/".$row['post_type']."/thumbs/$sid.jpg");
				
				$db->table(POSTS_TABLE)
					->where("sid", $sid)
					->delete();
				
				$db->table(COMMENTS_TABLE)
					->where("post_id", $sid)
					->where("module", $row['post_type'])
					->delete();
				
				$db->table(SCORES_TABLE)
					->where("db_table", $row['post_type'])
					->where("post_id", $sid)
					->delete();
				
				$db->table(SCORES_TABLE)
					->where("post_id", $sid)
					->update([
						'post_id' => 0
					]);
				
				$db->table(AUTHORS_TABLE)
					->where("aid", $article_aid)
					->update([
						'counter' => ["-", 1]
					]);
				$hooks->do_action("delete_post_after", $sid);	
				cache_system("nuke_authors");
				add_log(sprintf(_ARTICLE_DELETE_LOG, $old_title), 1);
				$PingOptimizer->phpnuke_FuturePingDelete($row['post_type'], $sid);
				redirect_to("".$admin_file.".php?op=articles&post_type=".$row['post_type']."");
				die();
			}
		}
		
		// publish future or draft article now
		if($mode == "publish_now")
		{
			csrfProtector::authorisePost(true);
			$published_title = (isset($article_fields['title']) && $article_fields['title']!= '') ? $article_fields['title']:$row['title'];
			$post_url = trim(sanitize(str2url($published_title)), "-");
			$post_url = get_unique_post_slug(POSTS_TABLE, "sid", $sid, "post_url", $post_url, 'publish', false, " AND post_type = '".$row['post_type']."'");
			
			if($row['time'] == '' || $row['time'] > _NOWTIME)
				$time = _NOWTIME;
			else
				$time = $row['time'];
				
			$db->table(POSTS_TABLE)
				->where("sid", $sid)
				->update([
					'status' => 'publish',
					'post_url' => $post_url,
					'time' => $time,
				]);
			$hooks->do_action("publish_post", $sid);	
			header("location: ".$admin_file.".php?op=article_admin&mode=edit&sid=$sid");
			die();			
		}
				
		$preview_contents = '';
		
		if(isset($article_fields['status']) && $article_fields['status'] == 'preview')
		{
			$row = $article_fields;
			$row['time'] = _NOWTIME;
			foreach($row as $key => $field)
			{
				if($key == "set_publish_date")
				{
					$datetime[0] = ($article_fields['publish_date'] != '') ? "".$article_fields['publish_date']."":"";
					$datetime[1] = ((isset($article_fields['publish_time'])) ? implode(":",$article_fields['publish_time']):"0:0").":0";
					if($datetime[0] != '')
					{
						$datetime = implode(" ", $datetime);
						$row['time'] = to_mktime($datetime);
					}
				}				
					
				if(is_array($field))
				{
					if(!is_list_array($field) || is_multi_array($field))
					{
						$row[$key] = addslashes(phpnuke_serialize($field));
					}
					else
						$row[$key] = implode(",", $field);
				}
			}
			
			$row = $hooks->apply_filters("preview_post_row", $row);
			unset($row['publish_date']);
			unset($row['publish_time']);
			
			if(isset($micro_data) && !empty($micro_data))
				$row['micro_data'] = addslashes(phpnuke_serialize($micro_data));
				
			$preview_article_image = "";
			if($row['post_image'] != '')
				$preview_article_image = "<img src=\"".LinkToGT("index.php?timthumb=true&src=".$row['post_image']."&q=90&w=150")."\" />";
				
			$preview_contents .="<div class=\"form-textarea\"><p align=\"center\"><b>"._PREVIEW."</b></p><br /><br />";
			$preview_contents .="<div>$preview_article_image</div>";
			$preview_contents .="<div><b><span style=\"color:#".$row['title_color'].";\">".$row['title']."</span></b></div>";
			$preview_contents .="<div>".$row['hometext']."</div>";
			$preview_contents .="<div>".$row['bodytext']."</div>";
			$preview_contents .="<div>".((isset($row['tags'])) ? $row['tags']:"")."</div>";
			$preview_contents .="</div>";
			$preview_contents = $hooks->apply_filters("preview_post", $preview_contents);
		}

		// submit edited data
		if(isset($submit) && isset($article_fields) && !empty($article_fields) && isset($article_fields['status']) && $article_fields['status'] != 'preview')
		{
			$items	= array();
			
			if($mode != 'auto_save')
			{
				$PnValidator->add_validator("in_languages", function($field, $input, $param = NULL)
				{
					$param = explode("-", $param);
					return in_array($input[$field], $param);
				}); 
				
				$validation_rules = $hooks->apply_filters("post_validation_rules", array(
					'title'			=> 'required',
					'hometext'		=> 'required',
					'post_type'		=> 'required',
					'permission'	=> 'is_array',
					'cat'			=> 'is_array',
				));
				
				$filter_rules = $hooks->apply_filters("post_filter_rules", array(
					'title'			=> 'sanitize_string|filter',
					'post_url'		=> 'sanitize_title|str2url',
					'title_lead'	=> 'sanitize_string|filter',
					'title_color'	=> 'sanitize_string|filter',
					'alanguage'		=> 'sanitize_string|filter',
					'post_type'		=> 'sanitize_string|filter',
					'hometext'		=> 'addslashes',
					'bodytext'		=> 'addslashes',
					'cat_link'		=> 'sanitize_numbers',
					'ihome'			=> 'sanitize_numbers',
					'allow_comment'	=> 'sanitize_numbers',
					'haspoll'		=> 'sanitize_numbers',
					'pollID'		=> 'sanitize_numbers',
					'position'		=> 'sanitize_numbers',
					'post_image'	=> 'filter',
				));
				
				$PnValidator->validation_rules($validation_rules); 
				
				// Get or set the filtering rules
				$PnValidator->filter_rules($filter_rules); 

				$article_fields = $PnValidator->sanitize($article_fields, array('title','title_lead','title_color','alanguage','post_type'), true, true);
				$validated_data = $PnValidator->run($article_fields);

				// validate submitted data
				if($validated_data !== FALSE)
				{
					$article_fields = $validated_data;
				}
				else
				{
					$hooks->add_filter("set_page_title", function(){return array("articles_admins" => _ADD_NEW_ARTICLE);});
					include("header.php");
					$html_output .= title(_ERROR_IN_OP, $PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br /><br />'), articles_menu());
					include("footer.php");
				}
			}
			
			if(!isset($article_fields['meta_fields']))
				$article_fields['meta_fields'] = array();
			else
				insert_update_meta_fields($article_fields['meta_fields'], $sid, $article_fields['post_type']);
			
			if($mode != 'auto_save' && isset($article_fields['set_publish_date']) && $article_fields['set_publish_date'] == 1)
			{
				$datetime[0] = (isset($article_fields['publish_date'])) ? $article_fields['publish_date']:"";
				$datetime[1] = ((isset($article_fields['publish_time'])) ? implode(":",$article_fields['publish_time']):"0:0").":0";
				if($datetime[0] != '')
					$datetime = implode(" ", $datetime);

				$items['time'] = to_mktime($datetime);

				if($items['time'] > _NOWTIME)
					$items['status'] = "future";	
			}
			else
				$items['time'] = $row['time'];	
			
			unset($article_fields['publish_date']);
			unset($article_fields['publish_time']);
			unset($article_fields['set_publish_date']);
			
			foreach($article_fields as $key => $value)
			{
				// check if post_url is empty and set value for it
				if($key == 'post_url' && $mode != 'auto_save')
				{
					if($value == '')
					{
						$value = $article_fields['title'];
					}
					
					$value = trim(sanitize(str2url($value)), "-");
					
					$value = get_unique_post_slug(POSTS_TABLE, "sid", $sid, "post_url", $value, $article_fields['status'], false, " AND post_type = '".$article_fields['post_type']."'");
					
				}
				
				if($key == "status" && isset($items['status']))
				{
					continue;
				}

				if($key == "tags")
				{
					$items['tags'] = (is_array($article_fields['tags'])) ? implode(",", $article_fields['tags']):str_replace(array(":",'-'),",", $article_fields['tags']);
					continue;
				}
				
				if($key == "time" && $mode != 'auto_save')
				{
					$items['time'] = str_replace("'", "", $value);
					continue;
				}
				
				if(is_array($value) && !empty($value))
				{
					if(!is_list_array($value) || is_multi_array($value))
						$value = addslashes(phpnuke_serialize($value));
					else
						$value = implode(",", $value);
				}
				
				$items[$key] = $value;
			}
			
			$items = $hooks->apply_filters("post_fields_parse", $items, $sid, $article_fields);
			
			unset($article_fields['meta_fields']);
			unset($items['meta_fields']);
			
			if(!isset($items['tags']))
			{
				$article_fields['tags'] = '';
				$items['tags'] = '';
			}
			if($mode != "auto_save")
			{
				$micro_data_type = $micro_data['_pnmm_type'];
				$micro_data2 = (isset($micro_data[$micro_data_type])) ? $micro_data[$micro_data_type]:"";
				unset($micro_data);
				$micro_data[$micro_data_type] = $micro_data2;
			}
			$items['micro_data'] = addslashes(phpnuke_serialize($micro_data));
			$items['micro_data'] = $hooks->apply_filters("post_micro_data_parse", $items['micro_data'], $items, $article_fields);

			// save to db
			if($mode == "edit" || $mode == "auto_save")
			{
				$hooks->do_action("post_before_update", $sid, $items, $article_fields);
				
				$post_url = LinkToGT(articleslink($sid, $items['title'], $items['post_url'], $items['time'], $items['cat_link'], $items['post_type']));
				
				if($go_to_pings == 1)
				{
					$ping_data = array(
						"poster" => $items['aid'], 
						"poster_ip" => $items['ip'], 
						"title" => $items['title'], 
						"time" => $items['time'], 
						"status" => $items['status'], 
						"post_url" => $post_url
					);
					
					$PingOptimizer->set_defaults($items['post_type'], $sid, $ping_data);
					
					$PingOptimizer->phpnuke_Ping();
				}
				
				if($mode == 'auto_save')
					unset($items['status']);
				
				$db->table(POSTS_TABLE)
					->where("sid", $sid)
					->update($items);
				
				if($mode == 'edit')
				{
					$hooks->do_action("post_after_update", $sid, $items, $article_fields);
					add_log(sprintf((($row['title'] != '') ? _EDIT_ARTICLE_LOG:_ADD_ARTICLE_LOG), "<a href=\"$post_url\" target=\"_blank\">".$items['title']."</a>"), 1);
				}
				elseif($mode == 'auto_save')
				{
					$hooks->do_action("post_after_auto_save", $sid, $items, $article_fields);
				}
			}
			
			// add new tags
			$row['tags'] = $hooks->apply_filters("post_tags_parse", $row['tags'], $sid, $row, $article_fields);
			$old_tags = ((isset($row['tags']) && $row['tags'] != '' && !is_array($row['tags'])) ? explode(",", $row['tags']):array());
			update_tags($old_tags, $items['tags']);
			
			position_update($items['post_type']);
			
			if($mode == 'auto_save')
			{
				die('auto save ok');
			}
			
			// upload image
			if(isset($article_image_upload) && $article_image_upload['name'] != '')
			{
				upload_image($sid, $article_image_upload, $post_type);
			}
			
			phpnuke_db_error();
			
			$hooks->do_action("post_save_finish", $sid, $items, $article_fields);
			
			redirect_to("".$admin_file.".php?op=article_admin&mode=edit&sid=$sid");
			die();
		}
		
		if(in_array($post_type, array("Downloads","Pages","Gallery","Faqs","Statics")) && !isset($nuke_categories_cacheData[$post_type]))
		$nuke_categories_cacheData[$post_type] = $nuke_categories_cacheData['Articles'];
		
		$nuke_categories_cacheData[$post_type] = $hooks->apply_filters("post_get_categories_cacheData", $nuke_categories_cacheData[$post_type], $nuke_categories_cacheData, $post_type);
		
		$categories = new categories_list($nuke_categories_cacheData[$post_type]);
		$categories->categories_list();
		
		$languageslists = get_dir_list('language', 'files');
		
		$article_fields = array(
			"sid"			=> ((intval($sid) != 0) ?				$sid:0),
			"status"		=> ((isset($row['status'])) ?			$row['status']:"publish"),
			"post_type"		=> ((isset($row['post_type'])) ?		$row['post_type']:$post_type),
			"aid"			=> ((isset($row['aid'])) ?				$row['aid']:$aid),
			"title"			=> ((isset($row['title'])) ?			$row['title']:""),
			"title_lead"	=> ((isset($row['title_lead'])) ?		$row['title_lead']:""),
			"title_color"	=> ((isset($row['title_color'])) ?		$row['title_color']:""),
			"time"			=> ((isset($row['time'])) ?				$row['time']:_NOWTIME),
			"hometext"		=> ((isset($row['hometext'])) ?			stripslashes($row['hometext']):''),
			"bodytext"		=> ((isset($row['bodytext'])) ?			stripslashes($row['bodytext']):''),
			"post_url"		=> ((isset($row['post_url'])) ?			$row['post_url']:''),
			"cat"			=> ((isset($row['cat'])) ?				explode(",", $row['cat']):array()),
			"tags"			=> ((isset($row['tags'])) ?				explode(",", $row['tags']):array()),
			"ihome"			=> ((isset($row['ihome'])) ?			$row['ihome']:1),
			"alanguage"		=> ((isset($row['alanguage'])) ?		$row['alanguage']:''),
			"allow_comment"	=> ((isset($row['allow_comment'])) ?	$row['allow_comment']:1),
			"haspoll"		=> ((isset($row['haspoll'])) ?			$row['haspoll']:0),
			"pollID"		=> ((isset($row['pollID'])) ?			$row['pollID']:0),
			"position"		=> ((isset($row['position'])) ?			$row['position']:1),
			"post_pass"		=> ((isset($row['post_pass'])) ?		$row['post_pass']:''),
			"post_image"	=> ((isset($row['post_image'])) ?		$row['post_image']:''),
			"cat_link"		=> ((isset($row['cat_link'])) ?			$row['cat_link']:0),
			"permissions"	=> ((isset($row['permissions'])) ?		explode(",", $row['permissions']):array()),
			"micro_data"	=> ((isset($row['micro_data'])) ?		phpnuke_unserialize(stripslashes($row['micro_data'])):array()),
			"download"		=> ((isset($row['download'])) ?			phpnuke_unserialize(stripslashes($row['download'])):array()),
		);
		
		$article_fields = $hooks->apply_filters("post_set_article_fields", $article_fields, $mode);
		
		$article_fields['meta_fields'] = $hooks->apply_filters("post_set_meta_fields", array());
		
		$meta_result = $db->table(POSTS_META_TABLE)
				->where('post_id', $sid)
				->where('meta_part', 'articles')
				->select();
		if($meta_result->count() > 0)
		{
			$meta_rows = $meta_result->results();
			foreach($meta_rows as $meta_row)
			{
				$mid = intval($meta_row['mid']);
				$meta_key = filter($meta_row['meta_key'], "nohtml");
				$meta_value = $meta_row['meta_value'];
				$article_fields['meta_fields'][$meta_key] = (is_serialized($meta_value)) ? phpnuke_unserialize($meta_value):(($meta_value != '') ? $meta_value:"");;
			}
		}
		
		if($mode == 'grapesjs')
		{
			if(isset($article_fields['meta_fields']['grapesjs']) && !$cache->isCached("grapesjs_".$article_fields['post_type']."_".$sid.""))
				$cache->store("grapesjs_".$article_fields['post_type']."_".$sid."", $article_fields['meta_fields']['grapesjs']);
			
			$bodytext = $row['bodytext'];
			if(isset($article_fields['meta_fields']['grapesjs']))
			{
				$grapesjs = objectToArray(json_decode($article_fields['meta_fields']['grapesjs']));
				$bodytext = "<style>".$grapesjs['gjs-css']."</style>".$grapesjs['gjs-html']."";
			}
			
			die(grapesjs($sid, $bodytext, $article_fields['post_type']));
		}
		
		$ihome_checked1 = ($article_fields['ihome'] == 1) ? "checked":"";
		$ihome_checked2 = ($article_fields['ihome'] == 0) ? "checked":"";
		
		$allow_comment_checked1 = ($article_fields['allow_comment'] == 1) ? "checked":"";
		$allow_comment_checked2 = ($article_fields['allow_comment'] == 0) ? "checked":"";
		
		$post_image = "";
		$article_image_local = "";
		$article_image_local_checked = "checked";
		$article_image_remote = " style=\"display:none;\"";
		$article_image_remote_checked = "";
		
		$hooks->do_action("post_form_before", $article_fields);
		
		if($article_fields['status'] == 'preview')
		{
			if($article_fields['post_image'] != '')
			{
				$article_image_local = " style=\"display:none;\"";
				$article_image_remote = "";
				
				$article_image_local_checked = "";
				$article_image_remote_checked = "checked";
				
				$post_image = "<img src=\"".LinkToGT("index.php?timthumb=true&src=".$article_fields['post_image']."&q=90&w=150")."\" />";
				
			}
			elseif(file_exists("files/".$article_fields['post_type']."/".$article_fields['sid'].".jpg"))
				$post_image = "<div id=\"post_image_show\"><img src=\"".LinkToGT("index.php?timthumb=true&src=files/".$article_fields['post_type']."/".$article_fields['sid'].".jpg&q=90&h=60")."\" /> <a href=\"#\" id=\"delete_image\" data-sid=\"".$article_fields['sid']."\" data-post-type=\"".$article_fields['post_type']."\">"._DELETE."</a></div>";
			
			$post_image = $hooks->apply_filters("post_form_image_parse", $post_image, $article_fields);
		}
		
		$datetime = ($article_fields['time'] != '') ? $article_fields['time']:_NOWTIME;
		$publish_datetime['date'] = nuketimes($datetime, false, false, false, 1);
		$publish_datetime['time'] = date("H:i", $datetime);
		$set_publish_date = ($datetime > _NOWTIME) ? "checked":"";

		$post_link = ($article_fields['title'] != '') ? LinkToGT(articleslink($sid, $article_fields['title'], $article_fields['post_url'], $article_fields['time'], $article_fields['cat_link'], $article_fields['post_type'])):"";
		$short_link =($article_fields['title'] != '') ?  LinkToGT(articleslink($sid, $article_fields['title'], $article_fields['post_url'], $article_fields['time'], $article_fields['cat_link'], $article_fields['post_type'], "short")):"";
				
		include("".INCLUDE_PATH."/micro_data.php");
		
		$pagetitle = _ADD_NEW_ARTICLE." - ".$all_post_types[$post_type];
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("articles_admins" => $pagetitle);});
		
		$grapesjs_btn = "<a class=\"grapesjs-btn\" href=\"".$admin_file.".php?op=article_admin&mode=grapesjs&sid=$sid\" target=\"grapesjs\" style=\"float:right;border:1px solid #ccc;border-radius:3px;padding:10px 6px;margin-left:2px;\"><i class=\"fa fa-edit\"></i> "._EDIT." "._IN." grapesjs</a>";
		
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= articles_menu();
		$contents .= OpenAdminTable();
		$contents .= $preview_contents;
		$contents = $hooks->apply_filters("post_form_scripts", $contents);
		
		$post_fields = array();
		
		$contents .="
		<script src=\"".INCLUDE_PATH."/Ajax/jquery/micro_data_toggle.js\"></script>
		<!-- MiniColors -->
		<script src=\"".INCLUDE_PATH."/Ajax/jquery/jquery.minicolors.min.js\"></script>
		<link rel=\"stylesheet\" href=\"".INCLUDE_PATH."/Ajax/jquery/jquery.minicolors.css\">
		<!-- MiniColors -->
		<script type=\"text/javascript\" src=\"".INCLUDE_PATH."/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>
		<script type=\"text/javascript\" src=\"".INCLUDE_PATH."/Ajax/jquery/clipboard.min.js\"></script>
		<script type=\"text/javascript\">
			new ClipboardJS('.copytoClipboard');
		</script>
		
		<form action=\"".$admin_file.".php\" method=\"post\" enctype=\"multipart/form-data\" id=\"article_form\">
			<table width=\"100%\" class=\"id-form product-table no-border\">";
				$contents .="<tr>
					<td colspan=\"2\">".sprintf(_ADD_NEW_POST_IN_FORMAT, $all_post_types[$post_type])."</td>
				</tr>";
				
				if($post_link != '')
				{
				$contents .="<tr>
					<th style=\"width:200px\">"._POST_LINK."</th>
					<td class=\"dleft aright\">
						<input type=\"text\" class=\"inp-form-ltr\" id=\"copytoClipboard\" style=\"float:left;width:40%;\" value=\"$post_link\" /><span style=\"float:left;cursor:pointer;border:1px solid #ccc;border-radius:3px;padding:10px 6px;margin-left:5px;\" class=\"copytoClipboard\" data-clipboard-target=\"#copytoClipboard\"><i class=\"fa fa-copy fa-2\"></i> "._COPY."</span>
						
						<input type=\"text\" class=\"inp-form-ltr\" id=\"short_copytoClipboard\" style=\"float:left;width:200px;margin-left:10px;\" value=\"$short_link\" />
						<span style=\"float:left;cursor:pointer;border:1px solid #ccc;border-radius:3px;padding:10px 6px;margin-left:5px;\" class=\"copytoClipboard\" data-clipboard-target=\"#short_copytoClipboard\"><i class=\"fa fa-copy fa-2\"></i> "._COPY."</span>
						
						<a href=\"$post_link\" target=\"_blank\" style=\"float:right;border:1px solid #ccc;border-radius:3px;padding:10px 6px;margin-left:2px;\"><i class=\"fa fa-eye\"></i> "._VIEW."</a>
						$grapesjs_btn
					</td>
				</tr>";
				}
				else
				{
				$contents .="<tr>
					<th style=\"width:200px\"></th>
					<td class=\"dleft aright\">$grapesjs_btn</td>
				</tr>";
				}
				
				$post_fields['title'] = "<tr>
					<th style=\"width:200px\">"._TITLE."</th>
					<td><input type=\"text\" size=\"40\" name=\"article_fields[title]\" id=\"title_field\" value=\"".$article_fields['title']."\" class=\"inp-form\" minlength=\"3\" data-validation=\"required\" data-validation-error-msg=\""._TITLE_IS_REQUIRED."\" /></td>
				</tr>";
				$post_fields['post_url'] = "<tr>
					<th>"._PERMALINK."</th>
					<td>
						<input type=\"text\" size=\"40\" name=\"article_fields[post_url]\" id=\"post_url\" value=\"".$article_fields['post_url']."\" class=\"inp-form\" />
					</td>					
				</tr>";
				$post_fields['title_lead'] = "<tr>
					<th>"._ARTICLE_LEAD."</th>
					<td><input type=\"text\" size=\"40\" name=\"article_fields[title_lead]\" value=\"".$article_fields['title_lead']."\" class=\"inp-form\" /></td>					
				</tr>";
				$post_fields['title_color'] = "<tr>
					<th>"._TITLE_COLOR."</th>
					<td><input type=\"text\" name=\"article_fields[title_color]\" size=\"37\" data-letterCase=\"uppercase\" value=\"".$article_fields['title_color']."\" class=\"color-picker inp-form\" id=\"swatches-opacity\" class=\"demo\" data-opacity=\"1\" data-swatches=\"#fff|#000|#f00|#0f0|#00f|#ff0\" value=\"#000\" /></td>					
				</tr>";
				$post_fields['cat_link'] = "<tr>
					<th>"._MAIN_CAT."</th>
					<td>
					<select name=\"article_fields[cat_link]\" class=\"styledselect-select\" data-validation=\"required\">";
					asort($categories->result);
					foreach($categories->result as $cid => $catname)
					{
						$sel = ($cid == $article_fields['cat_link']) ? "selected":"";
						$post_fields['cat_link'] .= "<option value=\"$cid\" $sel>".str_replace("-"," ", $catname)."</option>";
					}					
					$post_fields['cat_link'] .= "</select>
					</td>					
				</tr>";
				$post_fields['cat'] = "<tr>
					<th>"._RELATED_CATS."</th>
					<td>
					<select name=\"article_fields[cat][]\" class=\"styledselect-select\" multiple=\"multiple\" style=\"width:100%\">";
					$article_fields['cat'] = array_filter($article_fields['cat']);
					foreach($categories->result as $cid => $catname)
					{
						$sel = (in_array($cid, $article_fields['cat'])) ? "selected":"";
						$post_fields['cat'] .= "<option value=\"$cid\" $sel>".str_replace("-"," ", $catname)."</option>";
					}					
					$post_fields['cat'] .= "</select>
					</td>					
				</tr>";
				$post_fields['ihome'] = "<tr>
					<th>"._SHOW_IN_MAIN_PAGE."</th>
					<td><input type=\"radio\" name=\"article_fields[ihome]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $ihome_checked1 /><input type=\"radio\" name=\"article_fields[ihome]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $ihome_checked2 /></td>					
				</tr>";
				$post_fields['allow_comment'] = "<tr>
					<th>"._ALLOW_COMMENT."</th>
					<td><input type=\"radio\" name=\"article_fields[allow_comment]\" value=\"1\" class=\"styled\" data-label=\""._ACTIVE."\" $allow_comment_checked1 /><input type=\"radio\" name=\"article_fields[allow_comment]\" value=\"0\" class=\"styled\" data-label=\""._INACTIVE."\" $allow_comment_checked2 /></td>					
				</tr>";
				if($nuke_configs['multilingual'] == 1)
				{
					$post_fields['alanguage'] = "
					<tr>
						<th>"._LANGUAGE."</th>
						<td>
							<select name=\"article_fields[alanguage]\" class=\"styledselect-select\">
								<option value=\"\">"._ALL."</option>";
							foreach($languageslists as $languageslist)
							{
								if($languageslist != "")
								{
									if($languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
									$languageslist = str_replace(".php", "", $languageslist);
									$sel = ($languageslist == $article_fields['alanguage']) ? "selected":"";
									$post_fields['alanguage'] .= "<option value=\"$languageslist\" $sel>".ucfirst($languageslist)."</option>";
								}
							}
							$post_fields['alanguage'] .= "
							</select>
						</td>
					</tr>";
				}
				else
					$post_fields['alanguage'] = "<input type=\"hidden\" name=\"article_fields[alanguage]\" value=\"\" />";
				
				if(isset($all_positions_data[$post_type]) && !empty($all_positions_data[$post_type]))
				{
				$post_fields['position'] = "<tr>
					<th>"._POSITION."</th>
					<td>
						<select name=\"article_fields[position]\" class=\"styledselect-select\">";
						foreach($all_positions_data[$post_type] as $position_id => $position_data)
						{
							$sel = ($position_id == $article_fields['position']) ? "selected":"";
							$post_fields['position'] .= "<option value=\"$position_id\" $sel>".$position_data['position_title']."</option>\n";
						}
						$post_fields['position'] .= "</select>
					</td>					
				</tr>";
				}
				$post_fields['post_pass'] = "<tr>
					<th>"._PASSWORD."</th>
					<td><input type=\"text\" size=\"40\" name=\"article_fields[post_pass]\" value=\"".$article_fields['post_pass']."\" class=\"inp-form\" /></td>					
				</tr>";
				$post_fields['post_image'] = "<tr>
					<th>"._POST_IMAGE."</th>
					<td>
						$post_image<br />
						<input type=\"radio\" value=\"1\" class=\"styled\" name=\"post_image\" data-label=\""._DIRECT_IMAGE_LINK."\" $article_image_remote_checked /> 
						<input type=\"radio\" value=\"2\" class=\"styled\" name=\"post_image\" data-label=\""._IMAGE_UPLOAD."\" $article_image_local_checked /><br />
						
						<div id=\"image_from_remote\"$article_image_remote>
							<input type=\"text\" class=\"inp-form\" name=\"article_fields[post_image]\" value=\"".$article_fields['post_image']."\" size=\"40\" value=\"\" />
						</div>
						<div id=\"article_image_local\"$article_image_local>
							<input type=\"file\" class=\"file_1\" name=\"article_image_upload\" /> 
						</div>
					</td>					
				</tr>";
				$post_fields['hometext'] = "<tr>
					<th>"._HOMETEXT."</th>
					<td>
						".wysiwyg_textarea("article_fields[hometext]", $article_fields['hometext'], "PHPNukeAdmin", "50", "12")."
					</td>					
				</tr>";
				$post_fields['bodytext'] = "<tr>
					<th>"._BODYTEXT."</th>
					<td>
						".wysiwyg_textarea("article_fields[bodytext]", $article_fields['bodytext'], "PHPNukeAdmin", "50", "12")."
					</td>					
				</tr>";
				$post_fields['download'] = "<tr>
					<th>"._ARTICLE_FILES."</th>
					<td>".get_post_download_files($article_fields['download'], _ARTICLE_FILES, "article_fields[download]")."</td>				
				</tr>";
				$post_fields['permissions'] = "<tr>
					<th>"._SHOWN_FOR."</th>
					<td>";
						$permissions = get_groups_permissions();
						foreach($permissions as $key => $premission_name)
						{
							$checked = (($mode == 'new' && $key == 0) || in_array($key, $article_fields['permissions'])) ? "checked":'';
							
							$post_fields['permissions'] .= "<input data-label=\"$premission_name\" type=\"checkbox\" class=\"styled\" id=\"edit-block-permission_$key\" value=\"$key\" name=\"article_fields[permissions][]\" $checked />&nbsp; ";
						}
					$post_fields['permissions'] .= "</td>					
				</tr>";
				$post_fields['tags'] = "<tr>
					<th>"._KEYWORDS."</th>
					<td>
						<select class=\"styledselect-select tag-input\" name=\"article_fields[tags][]\" multiple=\"multiple\" style=\"width:100%\">";
						$article_fields['tags'] = array_filter($article_fields['tags']);
						if(isset($article_fields['tags']) && !empty($article_fields['tags']))
						{
							if(!is_array($article_fields['tags']))
								$article_fields['tags'] = explode(",", $article_fields['tags']);
							foreach($article_fields['tags'] as $tag)
								$post_fields['tags'] .= "<option value=\"$tag\" selected>$tag</option>\n";
						}
						$post_fields['tags'] .= "</select>
					</td>					
				</tr>";
				$post_fields['publish_date'] = "<tr>
					<th>"._PUBLISH_DATE."</th>
					<td>
						<script src=\"".INCLUDE_PATH."/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc.js\" type=\"text/javascript\"></script>
						<script src=\"".INCLUDE_PATH."/Ajax/jquery/datepicker/js/calendar.js\" type=\"text/javascript\"></script>
						<script src=\"".INCLUDE_PATH."/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-ar.js\" type=\"text/javascript\"></script>
						<script src=\"".INCLUDE_PATH."/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-fa.js\" type=\"text/javascript\"></script>	
						<input type=\"text\" name=\"article_fields[publish_date]\" class=\"inp-form-ltr calendar\" value=\"".$publish_datetime['date']."\">
						&nbsp; "._HOUR." <select name=\"article_fields[publish_time][hour]\" class=\"styledselect-select\" style=\"width:70px;\">";
						
						$publish_datetime['time'] = explode(":", $publish_datetime['time']);
						$publish_hour = $publish_datetime['time'][0];
						$publish_min = $publish_datetime['time'][1];
						for($h=0;$h < 24; $h++)
						{
							$hour = correct_date_number($h);
							$selected = ($publish_hour == $hour) ? "selected":"";
							$post_fields['publish_date'] .= "<option value=\"$hour\" $selected>$hour</option>\n";
						}
						$post_fields['publish_date'] .= "</select>
						&nbsp; <select name=\"article_fields[publish_time][min]\" class=\"styledselect-select\" style=\"width:70px;\">";
						for($m=0;$m < 60; $m++)
						{
							$min = ($m < 10) ? "0".$m:$m;
							$selected = ($publish_min == $min) ? "selected":"";
							$post_fields['publish_date'] .= "<option value=\"$min\" $selected>$min</option>\n";
						}
						$post_fields['publish_date'] .= "</select>
						".bubble_show("<div style=\"margin-top:-7px;\"><input id=\"set_publish_date\" type='checkbox' class='styled' name='article_fields[set_publish_date]' value='1' data-label=\""._SET_PUBLISH_DATE."\" $set_publish_date style=\"top:10px;\"></div>")."
					</td>
				</tr>";
				$post_fields['micro_data'] = "<tr>
					<th>"._POST_METADATA."</th>
					<td>
						<table width=\"100%\">
							".show_micro_data_inputs($article_fields['micro_data'])."
						</table>
					</td>					
				</tr>";
				
				$check1 = ($article_fields['status'] == 'draft') ? "checked":"";
				$check2 = ($article_fields['status'] == 'publish') ? "checked":"";
				$check3 = ($article_fields['status'] == 'preview') ? "checked":"";
				$post_fields['status'] = "<tr>
					<th>"._PUBLISH_AS."</th>
					<td id=\"publish_btns\">
						<input type=\"radio\" name=\"article_fields[status]\" value=\"draft\" class=\"styled\" data-label=\""._DRAFT."\" $check1 /> &nbsp; 
						<input type=\"radio\" name=\"article_fields[status]\" value=\"publish\" class=\"styled\" data-label=\""._IMMEDIATE_PUBLISH."\" $check2 /> &nbsp; 
						<input type=\"radio\" name=\"article_fields[status]\" value=\"preview\" class=\"styled\" data-label=\""._PREVIEW."\" $check3 /> &nbsp; 
					</td>					
				</tr>";
				$post_fields['go_to_pings'] = "<tr>
					<th>"._INFORM_TO_PING_SERICES."</th>
					<td><input type=\"radio\" name=\"go_to_pings\" value=\"1\" class=\"styled\" data-label=\""._YES."\" checked /><input type=\"radio\" name=\"go_to_pings\" value=\"0\" class=\"styled\" data-label=\""._NO."\" /></td>					
				</tr>";
				
				$post_fields['meta_fields'] =  $hooks->apply_filters("bulid_meta_fields", '', $post_type, $article_fields);

				$post_fields['submit'] = "
				<tr>
					<td colspan=\"2\">
						
						<input type=\"submit\" name=\"submit\" value=\"submit\" class=\"form-submit\" />
					</td>					
				</tr>";
				$post_fields['hiddens'] = "
				<tr>
					<td colspan=\"2\">
						<input type=\"hidden\" name=\"op\" value=\"article_admin\" />
						<input type=\"hidden\" name=\"article_fields[aid]\" value=\"$article_aid\" />
						<input type=\"hidden\" name=\"article_fields[ip]\" value=\"$visitor_ip\" />
						<input type=\"hidden\" name=\"sid\" value=\"$sid\" />
						<input type=\"hidden\" name=\"mode\" value=\"$mode\" />
						<input type=\"hidden\" name=\"article_fields[post_type]\" id=\"post_type\" value=\"".$article_fields['post_type']."\" />
						<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
					</td>					
				</tr>";
			
				$post_fields = $hooks->apply_filters("post_fields_inputs", $post_fields, $post_type, $article_fields);
				$contents .= implode("\n\t\t\t", $post_fields);
			
			$contents = $hooks->apply_filters("post_after_inputs", $contents, $post_type, $article_fields);
			$contents .= "</table>";
		$contents .= "</form>
		<script>
			$(\"#set_publish_date\").on(\"change\",function(){
				if($(this).prop(\"checked\"))
					alert('"._SET_PUBLISH_DATE_ALERT."');
			});
			$(document).ready(function(){
				
				$.validate({
					form : '#article_form',
					modules : 'security',
				  });
				
				$('input[name=post_image]').on('change', function(){
					if($(this).val() == 1)
					{
						$(\"#article_image_local\").hide();
						$(\"#image_from_remote\").show();
					}
					else
					{
						$(\"#image_from_remote\").hide();
						$(\"#article_image_local\").show();
					}
				});
				
				$(\"#delete_image\").on('click', function(){
					var confirm = confirm('"._DELETE_THIS_SURE."');
					if(confirm)
					{
						$.post(\"".$admin_file.".php\",
						{
							sid: $(this).data('sid'),
							post_type: $(this).data('post-type'),
							op: 'delete_image',
							csrf_token : pn_csrf_token
						},
						function(data){
							$(\"#post_image_show\").html('');
						});
					}
					return false;
				});
				$(\"#title_field\").on('blur', function(){
					if($(\"#post_url\").val() == '' || $(\"#post_url\").val() != '".$article_fields['post_url']."')
					{
						var title = $(\"#title_field\");
						$.post(\"ajax.php\",
						{
							op: 'get_unique_post_slug',
							table: '".POSTS_TABLE."',
							id_name: 'sid',
							id_value: '$sid',
							field: 'post_url',
							slug: title.val(),
							post_status: '".$article_fields['status']."',				
							where: ' AND post_type = \'$post_type\'',
							csrf_token : pn_csrf_token
						},
						function(data, status){
							$(\"#post_url\").val(data);
						});
					}
				});
				
				$.fn.serializeObject = function() {
					var data = {};

					function buildInputObject(arr, val) {
						if (arr.length < 1) {
							return val;  
						}
						var objkey = arr[0];
						if (objkey.slice(-1) == \"]\") {
							objkey = objkey.slice(0,-1);
						}  
						var result = {};
						if (arr.length == 1){
							result[objkey] = val;
						} else {
							arr.shift();
							var nestedVal = buildInputObject(arr,val);
							result[objkey] = nestedVal;
						}
						return result;
					}

					function gatherMultipleValues( that ) {
						var final_array = [];
						$.each(that.serializeArray(), function( key, field ) {
							// Copy normal fields to final array without changes
							if( field.name.indexOf('[]') < 0 ){
								final_array.push( field );
								return true; // That's it, jump to next iteration
							}

							// Remove \"[]\" from the field name
							var field_name = field.name.split('[]')[0];

							// Add the field value in its array of values
							var has_value = false;
							$.each( final_array, function( final_key, final_field ){
								if( final_field.name === field_name ) {
									has_value = true;
									final_array[ final_key ][ 'value' ].push( field.value );
								}
							});
							// If it doesn't exist yet, create the field's array of values
							if( ! has_value ) {
								final_array.push( { 'name': field_name, 'value': [ field.value ] } );
							}
						});
						return final_array;
					}

					// Manage fields allowing multiple values first (they contain \"[]\" in their name)
					var final_array = gatherMultipleValues( this );

					// Then, create the object
					$.each(final_array, function() {
						var val = this.value;
						var c = this.name.split('[');
						var a = buildInputObject(c, val);
						$.extend(true, data, a);
					});

					return data;
				};
				var auto_save_timeOut;
				function auto_save(){
					let article_form_data = $('#article_form').serializeObject();
					article_form_data['mode'] = 'auto_save';
					article_form_data['article_fields']['hometext'] = CKEDITOR.instances.editor_articlefieldshometext.getData();
					article_form_data['article_fields']['bodytext'] = CKEDITOR.instances.editor_articlefieldsbodytext.getData();
					article_form_data['article_fields']['post_type'] = '$post_type';
					
					$.post(
						'".$admin_file.".php',
						{
							'op':'article_admin', 
							'submit' : 'submit', 
							'mode' : 'auto_save', 
							'article_fields' : article_form_data['article_fields'], 
							'sid': '$sid', 
							'post_type': '$post_type',
							'csrf_token': pn_csrf_token
						},
						function (response) {
							if(response != '')
								console.log('post auto saved');
						}
					);
					auto_save_timeOut = setTimeout(auto_save, 30000);
				}
				
				auto_save();
				
				$(\"#publish_btns input\").on('click', function(){
					clearTimeout(auto_save_timeOut);
				});
			});	
		</script>";
		$contents .= CloseAdminTable();
		
		$contents = $hooks->apply_filters("post_form_after", $contents, $article_fields);
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function article_change_admin($sid, $status = '', $new_aid = '', $submit = '')
	{
		global $db, $admin_file, $nuke_configs, $hooks;

		$nuke_authors_cacheData = get_cache_file_contents('nuke_authors', true);
		
		$sid = intval($sid);
		
		$row = $db->table(POSTS_TABLE)
			->where('sid', $sid)
			->first(['aid']);
		
		$aid = filter($row['aid'], "nohtml");
		
		$hooks->do_action("article_change_admin_before", $sid, $new_aid);
		
		if(isset($submit) && $submit != '' && isset($new_aid) && $new_aid != '')
		{
			$db->query("UPDATE ".AUTHORS_TABLE." SET counter = case 
			WHEN aid = :aid THEN counter-1
			WHEN aid = :new_aid THEN counter+1
			END
			WHERE aid IN(:aid,:new_aid)", array(":aid" => $aid, "new_aid" => $new_aid));
			
		
			$row = $db->table(POSTS_TABLE)
				->where('sid', $sid)
				->update([
					'aid' => $new_aid
				]);
			
			cache_system("nuke_authors");
			$hooks->do_action("article_change_admin_after", $sid, $new_aid);
			header("location: ".$admin_file.".php?op=articles".(($status != '') ? "&status=$status":""));
		}
		
		$content="
			<form action=\"".$admin_file.".php\" method=\"post\">
			<table align=\"center\" border=\"0\" width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<td align=\""._TEXTALIGN1."\" colspan=\"2\">"._CURRENT_AUTHOR." : $aid</td>
				</tr>
				<tr>
					<th>"._NEW_AUTHOR."</td>
					<td>
						<select name=\"new_aid\" class=\"styledselect-select\">";
						foreach($nuke_authors_cacheData as $admin_id => $author_data)
						{
							if($aid == $admin_id) continue;
							$content .="<option value=\"$admin_id\">".ucfirst($admin_id)."</option>";
						}							
						$content .="</select>
					</td>
				</tr>
				<tr>
					<td align=\"center\">
					<input type=\"submit\" value=\""._SEND."\" name=\"submit\" class=\"form-submit\">
					</td>
				</tr>
			</table>
			<input type=\"hidden\" name=\"op\" value=\"article_change_admin\">
			<input type=\"hidden\" name=\"sid\" value=\"$sid\">
			<input type=\"hidden\" name=\"status\" value=\"$status\">
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> ";
			$content = $hooks->apply_filters("article_change_admin_form", $content);
			$content .="</form>";
			$content .= jquery_codes_load('',true);
			die($content);
	}
	
	function delete_image($sid, $post_type = 'Articles')
	{
		global $nuke_configs, $hooks;
		csrfProtector::authorisePost(true);
		$hooks->do_action("post_delete_image_before", $sid, $post_type);
		if(file_exists("files/$post_type/$sid.jpg"))
		{
			unlink("files/$post_type/$sid.jpg");
			if(file_exists("files/$post_type/thumbs/$sid.jpg"))
				unlink("files/$post_type/thumbs/$sid.jpg");
		}
		$hooks->do_action("post_delete_image_after", $sid, $post_type);
		return true;
	}
	
	function positions($post_type='Articles')
	{
		global $db, $nuke_configs, $admin_file, $all_post_types, $hooks;
		$contents = '';
		$pagetitle = _POSITIONS_ADMIN.((isset($all_post_types[$post_type])) ? " - ".$all_post_types[$post_type]:"");
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("positions" => $pagetitle);});
		
		$contents .= GraphicAdmin();
		
		foreach($all_post_types as $post_type_key => $post_type_val)
		{
			$sel = ($post_type_key == $post_type) ? "selected":"";
			$all_post_types_options[] = "<option value=\"".$admin_file.".php?op=positions&post_type=$post_type_key\" $sel>$post_type_val</option>";
		}
		$contents .= "<div align=\"center\" style=\"margin:20px 0;\">[ <a href=\"#\" title=\""._ADD_POSITION."\" class=\"editindialog\" data-op=\"positions_admin\" data-position-id=\"0\" data-mode=\"add\" data-post-type=\"$post_type\">"._ADD_POSITION."</a> ]<br /><br />"._SHOW_MODULES_CAT." <select onchange=\"top.location.href=this.options[this.selectedIndex].value\" class=\"styledselect-select\">".implode("\n", $all_post_types_options)."</select></div>";
		
		$contents .= OpenAdminTable();
		$contents .= "
			<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>
			<table align=\"center\" class=\"product-table\" width=\"100%\">
			<thead>
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:50px;\">ID</th>
				<th class=\"table-header-repeat line-left\">"._TITLE."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\">"._POST_TYPE."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:110px;\">"._OPERATION."</th>
			</tr>
			</thead>
			<tbody>";
			$all_positions_data = (isset($nuke_configs['positions'])) ? phpnuke_unserialize(stripslashes($nuke_configs['positions'])):array();
			
			if($all_positions_data != '' & !empty($all_positions_data) && isset($all_positions_data[$post_type]) && !empty($all_positions_data[$post_type]))
			{
				foreach($all_positions_data[$post_type] as $row)
				{
					$position_id = intval($row['id']);
					$position_title = filter($row['position_title'], "nohtml");
					
					$contents .= "<tr>
						<td align=\"center\">$position_id</td>
							<td>$position_title</td>
							<td align=\"center\">$post_type</td>
							<td align=\"center\">
								<a href=\"#\" title=\""._EDIT."\" class=\"table-icon icon-1 info-tooltip editindialog\" data-op=\"positions_admin\" data-position-id=\"$position_id\" data-mode=\"edit\" data-post-type=\"$post_type\"></a>
								<a href=\"".$admin_file.".php?op=positions_admin&mode=delete&position_id=$position_id&post_type=$post_type&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" onclick=\"return confirm('"._DELETE_THIS_SURE."');\" class=\"table-icon icon-2 info-tooltip\"></a>
							</td>
							</tr>";
				}
			}
			$contents .= "
			</tbody>
		</table>
		<div id=\"positions-dialog\"></div>
		<script>
			$(\"#positions_form\").validate();
			$(\".editindialog\").click(function(e)
			{
				e.preventDefault();
				var position_id = $(this).data('position-id');
				var post_type = $(this).data('post-type');
				var mode = $(this).data('mode');
				var op = $(this).data('op');
				$.ajax({
					type : 'post',
					url : '".$admin_file.".php',
					data : {'op' : op, 'position_id' : position_id, 'post_type' : post_type, 'mode' : mode, csrf_token : pn_csrf_token},
					success : function(responseText){
						$(\"#positions-dialog\").html(responseText);

						$(\"#positions-dialog\").dialog({
							title: '"._POSITIONS_EDIT."',
							resizable: false,
							height: 350,
							width: 800,
							modal: true,
							closeOnEscape: true,
							close: function(event, ui)
							{
								$(this).dialog('destroy');
								$(\"#positions-dialog\").html('');
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
	
	function positions_admin($position_id, $post_type='Articles', $article_fields = array(), $mode='add', $submit='')
	{
		global $db, $admin_file, $nuke_configs, $hooks;

		$position_id = intval($position_id);
		
		$all_positions_data = (isset($nuke_configs['positions'])) ? phpnuke_unserialize(stripslashes($nuke_configs['positions'])):array();
		
		if(!in_array($mode, array('add','edit','delete')))
			die("bad request");
		
		if($mode == 'edit' && isset($all_positions_data[$post_type][$position_id]))
		{
			$position_title = $all_positions_data[$post_type][$position_id]['position_title'];
			$replace_position = $all_positions_data[$post_type][$position_id]['replace_position'];
			$max = $all_positions_data[$post_type][$position_id]['max'];
		}
		else
		{
			$position_title = '';
			$replace_position = 0;
			$max = 10;
		}
		
		if($mode == 'add')
		{
			if(isset($all_positions_data[$post_type]) && !empty($all_positions_data[$post_type]))
			{
				$last_position = end($all_positions_data[$post_type]);
				$position_id = $last_position['id']+1;
			}
			else
				$position_id = 1;
		}
		
		if($mode == 'delete')
		{
			$hooks->do_action("post_position_delete_before", $position_id, $post_type);
			if(isset($all_positions_data[$post_type][$position_id]))
			{
				$position_title = $all_positions_data[$post_type][$position_id]['position_title'];
				unset($all_positions_data[$post_type][$position_id]);
			
				update_configs('positions', $all_positions_data);
				
				cache_system("nuke_configs");
				add_log(sprintf(_POSITIONS_ADD_EDIT_LOG, _DELETE, $position_title), 1);
				$hooks->do_action("post_position_delete_after", $position_id, $post_type);
				redirect_to("".$admin_file.".php?op=positions&post_type=$post_type");
				die();
			}
		}
		
		if(isset($submit) && isset($article_fields) && !empty($article_fields))
		{
			$all_positions_data[$post_type] = (isset($all_positions_data[$post_type])) ? $all_positions_data[$post_type]:array();
			$all_positions_data[$post_type][$position_id] = array(
				'id' => $position_id,
				'position_title' => $article_fields['position_title'],
				'replace_position' => ((isset($article_fields['replace_position']) && $article_fields['replace_position'] != 0) ? $article_fields['replace_position']:$position_id),
				'max' => $article_fields['max'],
			);
			
			
			$hooks->do_action("post_position_update_before", $all_positions_data, $post_type);
			update_configs('positions', $all_positions_data);
			
			cache_system("nuke_configs");
			add_log(sprintf(_POSITIONS_ADD_EDIT_LOG, (($mode == 'edit') ? _EDIT:_ADD), $article_fields['position_title']), 1);
			redirect_to("".$admin_file.".php?op=positions&post_type=$post_type");
			die();
		}
		
		$content="
			<form action=\"".$admin_file.".php\" method=\"post\" id=\"positions_form\">
			<table align=\"center\" border=\"0\" width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<th style=\"width:150px;\">ID</th>
					<td>$position_id</td>
				</tr>
				<tr>
					<th style=\"width:150px;\">"._TITLE."</th>
					<td><input name=\"article_fields[position_title]\" value=\"$position_title\" class=\"inp-form\" size=\"40\" type=\"text\" required /></td>
				</tr>";
				if(isset($all_positions_data[$post_type]) && !empty($all_positions_data[$post_type]) && $mode == 'edit')
				{
						$content .="
				<tr>
					<th>"._REPLACE_POSITION."</th>
					<td>
						<select name=\"article_fields[replace_position]\" class=\"styledselect-select\" style=\"width:100%\">";
						foreach($all_positions_data[$post_type] as $position__id => $position_data)
						{
							$sel = ($position__id == $replace_position) ? "selected":"";
							$content .= "<option value=\"$position__id\" $sel>".$position_data['position_title']."</option>";
						}
						$content .= "</select>
					</td>
				</tr>";
				}
				$content .="
				<tr>
					<th>"._POSITION_MAX."</th>
					<td>
						<input name=\"article_fields[max]\" size=\"60\" type=\"text\" class=\"inp-form\" value=\"$max\">
						".bubble_show(_POSITION_MAX_DESC)."
					</td>
				</tr>
				<tr>
					<td colspan=\"2\" align=\"center\">
					<input type=\"submit\" value=\""._UPDATE."\" name=\"submit\" class=\"form-submit\">
					</td>
				</tr>
			</table>
			<input type=\"hidden\" name=\"mode\" value=\"$mode\" />
			<input type=\"hidden\" name=\"op\" value=\"positions_admin\">
			<input type=\"hidden\" name=\"position_id\" value=\"$position_id\">
			<input type=\"hidden\" name=\"post_type\" value=\"$post_type\">
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />";
			$content = $hooks->apply_filters("post_position_inputs", $content);
			$content .="
			</form>";
			$content .=  jquery_codes_load('',true);
			die($content);
	}
	
	function position_update($post_type = 'Articles')
	{
		global $db, $nuke_configs, $hooks;
		$all_positions_data = (isset($nuke_configs['positions'])) ? phpnuke_unserialize(stripslashes($nuke_configs['positions'])):array();
		
		if(isset($all_positions_data[$post_type]) && !empty($all_positions_data[$post_type]))
		{
			foreach($all_positions_data[$post_type] as $pos_id => $pos_data)
			{
				if($pos_data['replace_position'] != $pos_id && $pos_data['max'] > 0)
				{
					$result = $db->query("SELECT sid, time FROM ".POSTS_TABLE." WHERE post_type = '$post_type' AND position = '$pos_id' ORDER BY time ASC");
					$numrows = intval($result->count());
					if($numrows > $pos_data['max'])
					{
						$diff = $numrows-$pos_data['max'];
						$sids = array();
						$rows = $result->results();
						
						for($i=0;$i<$diff;$i++)
							$sids[] = $rows[$i]['sid'];
						
						if(!empty($sids))
						{
							$sids = implode(",", $sids);
							$result = $db->query("UPDATE ".POSTS_TABLE." SET position = '".$pos_data['replace_position']."' WHERE post_type = '$post_type' AND position = '$pos_id' AND sid IN ($sids)");
						}
					}
				}
			}
			$hooks->do_action("post_positions_update", $all_positions_data, $post_type);
		}
	}
	
	global $pn_prefix;
	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$status = (isset($status)) ? filter($status, "nohtml"):'';
	$post_type = (isset($post_type)) ? filter($post_type, "nohtml"):'Articles';
	$new_aid = (isset($new_aid)) ? filter($new_aid, "nohtml"):'';
	$order_by = (isset($order_by)) ? filter($order_by, "nohtml"):'';
	$sort = (isset($sort)) ? filter($sort, "nohtml"):'';
	$search_query = (isset($search_query)) ? filter($search_query, "nohtml"):'';
	$submit = filter(request_var('submit', '', '_POST'), "nohtml");
	$article_image_upload = request_var('article_image_upload', array(), '_FILES');
	$go_to_pings = (isset($go_to_pings)) ? intval($go_to_pings):1;
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'edit';
	$article_fields = request_var('article_fields', array(), '_POST');
	$micro_data = request_var('micro_data', array(), '_POST');
	$sid = (isset($sid)) ? intval($sid):0;
	$position_id = (isset($position_id)) ? intval($position_id):0;
	$other_vars = array();
	$other_vars = $hooks->apply_filters("post_vars_parse", $other_vars);
	
	$hooks->do_action("admin_post_operations", $post_type);
	
	switch($op)
	{
		default:
		case"articles":
			articles($status, $post_type, $search_query, $order_by, $sort);
		break;
		
		case"article_change_admin":
			article_change_admin($sid, $status, $new_aid, $submit);
		break;
		
		case"article_admin":
			article_admin($sid, $mode, $submit, $article_fields, $article_image_upload, $micro_data, $go_to_pings, $post_type);
		break;
		
		case"delete_image":
			delete_image($sid, $post_type);
		break;
		
		case"positions_admin":
			positions_admin($position_id, $post_type, $article_fields, $mode, $submit);
		break;
		
		case"positions":
			positions($post_type);
		break;
	}
	
} else {
	include("header.php");
	GraphicAdmin();
	OpenAdminTable();
	echo "<div class=\"text-center\"><b>"._ERROR."</b><br><br>You do not have administration permission for module \"$module_name\"</div>";
	CloseAdminTable();
	include("footer.php");
}

?>