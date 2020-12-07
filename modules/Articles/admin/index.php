<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

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
		global $db, $admin_file, $nuke_configs;
		$contents = "
		<p align=\"center\" style=\"padding:20px; 0;\">[ 
			<a href=\"".$admin_file.".php?op=articles\">"._SHOW_ALL."</a> | 
			<a href=\"".$admin_file.".php?op=articles&status=publish\">"._PUBLISHED."</a> | 
			<a href=\"".$admin_file.".php?op=articles&status=future\">"._PUBLISH_IN_FUTURE."</a> | 
			<a href=\"".$admin_file.".php?op=articles&status=draft\">"._DRAFT."</a> | 
			<a href=\"".$admin_file.".php?op=articles&status=pending\">"._PENDING_POST."</a> | 
			<a href=\"".$admin_file.".php?op=categories&module_name=Articles\">"._ARTICLES_CATEGORIES."</a>
		]</p>
		<p align=\"center\">[ "._NEW_ARTICLE." :  
			<a href=\"".$admin_file.".php?op=article_admin&post_type=Articles\">"._POST_TYPE_ARTICLES."</a> | 
			<a href=\"".$admin_file.".php?op=article_admin&post_type=Downloads\">"._POST_TYPE_DOWNLOADS."</a> | 
			<a href=\"".$admin_file.".php?op=article_admin&post_type=Pages\">"._POST_TYPE_PAGESCONTENTS."</a> | 
			<a href=\"".$admin_file.".php?op=article_admin&post_type=Gallery\">"._POST_TYPE_GALLERY."</a> | 
			<a href=\"".$admin_file.".php?op=article_admin&post_type=Faqs\">"._POST_TYPE_FAQS."</a> | 
			<a href=\"".$admin_file.".php?op=article_admin&post_type=Statics\">"._POST_TYPE_STATICS."</a>
		]</p>";
		return $contents;
	}
	
	function articles($status='', $post_type = 'Articles', $search_query = '', $order_by = '', $sort='DESC')
	{
		global $db, $pagetitle, $admin_file, $nuke_configs, $nuke_authors_cacheData;
			
		$contents = '';
		
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
			if(in_array($status , array("future","draft")))
				$publish_now = "<a href=\"".$admin_file.".php?op=article_admin&mode=publish_now&sid={SID}&csrf_token="._PN_CSRF_TOKEN."\" title=\""._PUBLISH."\" class=\"table-icon icon-5 info-tooltip\"></a>";
		}
		
		$all_post_types = array(
			"Articles" => _POST_TYPE_ARTICLES,
			"Statics" => _POST_TYPE_STATICS,
			"Downloads" => _POST_TYPE_DOWNLOADS,
			"Gallery" => _POST_TYPE_GALLERY,
			"Pages" => _POST_TYPE_PAGESCONTENTS,
			"Faqs" => _POST_TYPE_FAQS,
		);
		
		$module = (isset($post_type) && $post_type != '') ? $post_type:"Articles";
		
		if(isset($post_type) && $post_type != '')
		{
			$params[":post_type"] = $post_type;
			$where[] = "s.post_type = :post_type";
			
			$pagetitle .= (isset($all_post_types[$post_type])) ? " - ".$all_post_types[$post_type]:"";
		}
		
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
		
		$contents .= GraphicAdmin();
		$contents .= articles_menu();
		
		$sel1 = (isset($post_type) && ($post_type == 'Articles' || $post_type == '')) ? "selected":"";
		$sel2 = (isset($post_type) && ($post_type == 'Statics')) ? "selected":"";
		$sel3 = (isset($post_type) && ($post_type == 'Downloads')) ? "selected":"";
		$sel4 = (isset($post_type) && ($post_type == 'Gallery')) ? "selected":"";
		$sel5 = (isset($post_type) && ($post_type == 'Pages')) ? "selected":"";
		$sel6 = (isset($post_type) && ($post_type == 'Faqs')) ? "selected":"";
		
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
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=articles&post_type=$post_type&order_by=sid&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'sid') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._ID."</a></th>
				<th class=\"table-header-repeat line-left\"><a href=\"".$admin_file.".php?op=articles&post_type=$post_type&order_by=title&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'title') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._TITLE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a href=\"".$admin_file.".php?op=articles&post_type=$post_type&order_by=time&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'time') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._PUBLISH_DATE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=articles&post_type=$post_type&order_by=comments&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'comments') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._COMMENTS."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\">"._AUTHOR."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\">"._CATEGORY."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\">"._IN_MAIN_PAGE."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:140px;\">"._OPERATION."</th>
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
							".str_replace("{SID}",$sid, $publish_now)."
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
	
	function article_admin($sid=0, $mode="new", $submit, $article_fields=array(), $article_image_upload=array(), $micro_data=array(), $go_to_pings=1, $post_type = 'Articles')
	{
		global $db, $aid, $visitor_ip, $pagetitle, $admin_file, $nuke_configs, $nuke_authors_cacheData, $nuke_modules_cacheData, $nuke_articles_configs_cacheData, $PnValidator, $module_name, $PingOptimizer, $nuke_meta_keys_parts;
		
		$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
		
		$all_post_types = array(
			"Articles" => _POST_TYPE_ARTICLES,
			"Statics" => _POST_TYPE_STATICS,
			"Downloads" => _POST_TYPE_DOWNLOADS,
			"Gallery" => _POST_TYPE_GALLERY,
			"Pages" => _POST_TYPE_PAGESCONTENTS,
			"Faqs" => _POST_TYPE_FAQS,
		);
		
		$sid = intval($sid);
		$row = array();
	
		$mode = (!in_array($mode, array("new", "edit", "delete", "publish_now"))) ? "new":$mode;
		$article_status = "publish";
		$article_aid = '';
		
		// determine radminsuper & article_aid & article_status & counter
		if($mode == "delete" || $mode == 'edit' || $mode == 'publish_now')
		{
			$nuke_modules_cacheData_by_title = phpnuke_array_change_key($nuke_modules_cacheData, "mid", "title");
	
			$radminsuper = intval($nuke_authors_cacheData[$aid]['radminsuper']);
			
			$articles_admins = ($nuke_modules_cacheData_by_title[$module_name]['admins'] != '') ? explode(",", $nuke_modules_cacheData_by_title[$module_name]['admins']):array();
			$radminarticle = (in_array($aid, $articles_admins)) ? true:false;
			
			$result2 = $db->table(POSTS_TABLE)
							->where("sid", $sid)
							->first([
								"aid as article_aid", 
								"status as article_status", 
								"title as old_title", 
							]);

			$article_aid = filter($result2['article_aid'], "nohtml");
			$article_status = filter($result2['article_status'], "nohtml");
			$old_title = filter($result2['old_title'], "nohtml");
			if($article_aid == '' && $article_status == 'pending')
				$article_aid = $aid;
				
			$row = $db->table(POSTS_TABLE)
							->where("sid", $sid)
							->first();
		}
		
		if(isset($article_fields['post_type']) && $article_fields['post_type'] != '')
			$post_type = $article_fields['post_type'];
		elseif(isset($row['post_type']) && $row['post_type'] != '')
			$post_type = $row['post_type'];
		elseif(!isset($post_type) || $post_type == '')
			$post_type = 'Articles';
			
		$module = (isset($post_type) && $post_type != '') ? $post_type:"Articles";
		
		if($article_aid == '')
			$article_aid = $aid;
		
		// delete article
		if($mode == "delete")
		{
			if (($radminarticle AND $article_aid == $aid) OR ($radminsuper == 1))
			{
				csrfProtector::authorisePost(true);
				$module_folder = $row['post_type'];
				if(file_exists("files/$module_folder/$sid.jpg"))
					unlink("files/$module_folder/$sid.jpg");
				
				if(file_exists("files/$module_folder/thumbs/$sid.jpg"))
				unlink("files/$module_folder/thumbs/$sid.jpg");
				
				$db->table(POSTS_TABLE)
					->where("sid", $sid)
					->delete();
				
				$db->table(COMMENTS_TABLE)
					->where("post_id", $sid)
					->where("module", $module_folder)
					->delete();
				
				$db->table(SCORES_TABLE)
					->where("db_table", $module_folder)
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
						'counter' => ["+", 1]
					]);
					
				cache_system("nuke_authors");
				add_log(sprintf(_ARTICLE_DELETE_LOG, $old_title), 1);
				$PingOptimizer->phpnuke_FuturePingDelete($module_folder, $sid);
				Header("Location: ".$admin_file.".php?op=articles&post_type=$module_folder");
			}
			else
			{
				include("header.php");
				$html_output .= GraphicAdmin();
				$html_output .= OpenAdminTable();
				$html_output .= "<div class=\"text-center\"><font class=\"title\"><b>"._ARTICLES_ADMIN."</b></font></div>";
				$html_output .= CloseAdminTable();
				$html_output .= OpenAdminTable();
				$html_output .= "<div class=\"text-center\"><b>"._NOTAUTHORIZED1."</b><br><br>"._NOTAUTHORIZED2."<br><br>"._GOBACK."</div>";
				$html_output .= CloseAdminTable();
				include("footer.php");
			}
		}
		
		// check edit or publish permission
		if($mode == "edit" || $mode == "publish_now")
		{
			if ((!$radminarticle OR $article_aid != $row['aid']) AND ($radminsuper != 1))
			{
				include("header.php");
				$html_output .= GraphicAdmin();
				$html_output .= OpenAdminTable();
				$html_output .= "<div class=\"text-center\"><font class=\"title\"><b>"._ARTICLES_ADMIN."</b></font></div>";
				$html_output .= CloseAdminTable();
				$html_output .= OpenAdminTable();
				$html_output .= "<div class=\"text-center\"><b>"._NOTAUTHORIZED1."</b><br><br>"._NOTAUTHORIZED2."<br><br>"._GOBACK."</div>";
				$html_output .= CloseAdminTable();
				include("footer.php");
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
			
			header("location: ".$admin_file.".php?op=article_admin&mode=edit&sid=$sid");
			die();			
		}		
		
		$languageslists = get_dir_list('language', 'files');
		
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
			unset($row['publish_date']);
			unset($row['publish_time']);
			
			if(isset($micro_data) && !empty($micro_data))
				$row['micro_data'] = addslashes(phpnuke_serialize($micro_data));
				
			$preview_article_image = "";
			if($row['post_image'] != '')
				$preview_article_image = "<img src=\"".$nuke_configs['nukeurl']."index.php?timthumb=true&src=".$row['post_image']."&q=90&w=150\" />";
				
			$preview_contents .="<div class=\"form-textarea\"><p align=\"center\"><b>"._PREVIEW."</b></p><br /><br />";
			$preview_contents .="<div>$preview_article_image</div>";
			$preview_contents .="<div><b><span style=\"color:#".$row['title_color'].";\">".$row['title']."</span></b></div>";
			$preview_contents .="<div>".$row['hometext']."</div>";
			$preview_contents .="<div>".$row['bodytext']."</div>";
			$preview_contents .="<div>".((isset($row['tags'])) ? $row['tags']:"")."</div>";
			$preview_contents .="</div>";
		}

		// submit edited data
		if(isset($submit) && isset($article_fields) && !empty($article_fields) && (!isset($article_fields['status']) || $article_fields['status'] != 'preview'))
		{
			$items	= array();
			
			$PnValidator->add_validator("in_languages", function($field, $input, $param = NULL)
			{
				$param = explode("-", $param);
				return in_array($input[$field], $param);
			}); 
			
			$PnValidator->validation_rules(array(
				'title'			=> 'required',
				'hometext'		=> 'required',
				'post_type'		=> 'required',
				'permission'	=> 'is_array',
				'cat'			=> 'is_array',
			)); 
			// Get or set the filtering rules
			$PnValidator->filter_rules(array(
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

			$article_fields = $PnValidator->sanitize($article_fields, array('title','title_lead','title_color','alanguage','post_type'), true, true);
			$validated_data = $PnValidator->run($article_fields);

			// validate submitted data
			if($validated_data !== FALSE)
			{
				$article_fields = $validated_data;
			}
			else
			{
				include("header.php");
				$pagetitle = _ADD_NEW_ARTICLE;
				$html_output .= GraphicAdmin();
				$html_output .= articles_menu();
				$html_output .= OpenAdminTable();
				$html_output .= '<p align=\"center\">'._ERROR_IN_OP.' :<br /><Br />'.$PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br /><br />')._GOBACK."</p>";
				$html_output .= CloseAdminTable();
				include("footer.php");
			}

			if(!isset($article_fields['meta_fields']))
				$article_fields['meta_fields'] = array();
			insert_update_meta_fields($article_fields['meta_fields'], $sid);
			unset($article_fields['meta_fields']);
			
			if(isset($article_fields['set_publish_date']) && $article_fields['set_publish_date'] == 1)
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
				$items['time'] = ($mode == "edit") ? $row['time']:_NOWTIME;	
			
			unset($article_fields['publish_date']);
			unset($article_fields['publish_time']);
			unset($article_fields['set_publish_date']);
			
			foreach($article_fields as $key => $value)
			{
				// check if post_url is empty and set value for it
				if($key == 'post_url' && $value == '')
				{
					$value = trim(sanitize(str2url($article_fields['title'])), "-");
					$value = get_unique_post_slug(POSTS_TABLE, "sid", $sid, "post_url", $value, $article_fields['status'], false, " AND post_type = '".$article_fields['post_type']."'");
				}
				
				if($key == "status" && isset($items['status']))
				{
					continue;
				}

				if($key == "tags")
				{
					$items['tags'] = (is_array($article_fields['tags'])) ? implode(",", $article_fields['tags']):str_replace(":",",", $article_fields['tags']);
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
			
			if(isset($nuke_meta_keys_parts[$module]) && !empty($nuke_meta_keys_parts[$module]))
			{
				foreach($nuke_meta_keys_parts[$module] as $meta_key => $article_fields_meta_field)
				{
					if(isset($article_fields[$meta_key]) && $article_fields[$meta_key] !== null && isset($article_fields_meta_field['php_after']) && $article_fields_meta_field['php_after'] != '')
					{
						eval($article_fields_meta_field['php_after']);
					}
				}
			}

			if(!isset($items['tags']))
			{
				$article_fields['tags'] = '';
				$items['tags'] = '';
			}
			
			$micro_data_type = $micro_data['_pnmm_type'];
			$micro_data2 = (isset($micro_data[$micro_data_type])) ? $micro_data[$micro_data_type]:"";
			unset($micro_data);
			$micro_data[$micro_data_type] = $micro_data2;
			
			$items['micro_data'] = addslashes(phpnuke_serialize($micro_data));

			// save to db
			if($mode == "new")
			{
				$article_fields['publish_time'] = str_replace("'", "", $items['time']);

				$insert_result = $db->table(POSTS_TABLE)
									->insert($items);

				$sid = intval($db->lastInsertId());
				
				if($insert_result && $mode != 'draft' && $mode != 'pending')
				
					$db->table(AUTHORS_TABLE)
						->where("aid", $items['aid'])
						->update([
							'counter' => ["+", 1]
						]);
				
				$post_url = LinkToGT(articleslink($sid, $article_fields['title'], $article_fields['post_url'], $article_fields['publish_time'], $article_fields['cat_link'], $article_fields['post_type']));
				
				add_log(sprintf(_ADD_ARTICLE_LOG, "<a href=\"$post_url\" target=\"_blank\">".$article_fields['title']."</a>"), 1);
				
				if(($article_fields['status'] == 'publish' || $article_fields['status'] == 'future') && $go_to_pings == 1)
				{
					$ping_data = array(
						"poster" => $article_fields['aid'], 
						"poster_ip" => $article_fields['ip'], 
						"title" => $article_fields['title'], 
						"time" => $article_fields['publish_time'], 
						"status" => $article_fields['status'], 
						"post_url" => $post_url
					);
					
					$PingOptimizer->set_defaults($article_fields['post_type'], $sid, $ping_data);
					
					$PingOptimizer->phpnuke_Ping();
				}
			}
			
			if($mode == "edit")
			{
				$db->table(POSTS_TABLE)
					->where("sid", $sid)
					->update($items);
									
				add_log(sprintf(_EDIT_ARTICLE_LOG, $article_fields['title']), 1);
			}
			
			// add new tags
					
			$old_tags = ((isset($row['tags']) && $row['tags'] != '') ? explode(",", $row['tags']):array());
			update_tags($old_tags, $article_fields['tags']);
			
			// upload image
			if(isset($article_image_upload) && $article_image_upload['name'] != '')
			{
				upload_image($sid, $article_image_upload, $module);
			}
			
			phpnuke_db_error();
			header("location: ".$admin_file.".php?op=article_admin&mode=edit&sid=$sid");
			die();
		}
		
		if(in_array($module, array("Downloads","Pages","Gallery","Faqs","Statics")) && !isset($nuke_categories_cacheData[$module]))
		$nuke_categories_cacheData[$module] = $nuke_categories_cacheData['Articles'];
		
		$categories = new categories_list($nuke_categories_cacheData[$module]);
		$categories->categories_list();
		
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
		
		$article_fields['meta_fields'] = array();
		if(isset($nuke_meta_keys_parts[$module]) && !empty($nuke_meta_keys_parts[$module]))
		{
			$article_fields_meta_fields = array_keys($nuke_meta_keys_parts[$module]);
			foreach($article_fields_meta_fields as $article_fields_meta_field)
			{
				$article_fields['meta_fields'][$article_fields_meta_field] = '';
			}
		}
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
				$meta_value = stripslashes($meta_row['meta_value']);
				$article_fields['meta_fields'][$meta_key] = (is_serialized($meta_value)) ? phpnuke_unserialize($meta_value):(($meta_value != '') ? $meta_value:"");;
			}
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
			
		if($mode != 'new' || $article_fields['status'] == 'preview')
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
		}
		
		$datetime = ($article_fields['time'] != '') ? $article_fields['time']:_NOWTIME;
		$publish_datetime['date'] = nuketimes($datetime, false, false, false, 1);
		$publish_datetime['time'] = date("H:i", $datetime);
		$set_publish_date = ($datetime > _NOWTIME) ? "checked":"";

		$post_link = LinkToGT(articleslink($sid, $article_fields['title'], $article_fields['post_url'], $article_fields['time'], $article_fields['cat_link'], $article_fields['post_type']));
		$short_link = LinkToGT(articleslink($sid, $article_fields['title'], $article_fields['post_url'], $article_fields['time'], $article_fields['cat_link'], $article_fields['post_type'], "short"));
				
		include("".INCLUDE_PATH."/micro_data.php");
		
		$pagetitle = _ADD_NEW_ARTICLE." - ".$all_post_types[$post_type];
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= articles_menu();
		$contents .= OpenAdminTable();
		$contents .= $preview_contents;
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
				/*if($mode == 'edit' && $article_fields['post_type'] == 'Statics')
				{
				$contents .="<tr>
					<td colspan=\"2\">"._STATIC_PAGE_NOT_AUTO."</td>
				</tr>";
				}*/
				if($mode == 'new')
				{
				$contents .="<tr>
					<td colspan=\"2\">".sprintf(_ADD_NEW_POST_IN_FORMAT, $all_post_types[$post_type])."</td>
				</tr>";
				}
				
				if($mode == 'edit')
				{
				$contents .="<tr>
					<th style=\"width:200px\">"._POST_LINK."</th>
					<td class=\"dleft aright\">
						<input type=\"text\" class=\"inp-form-ltr\" id=\"copytoClipboard\" style=\"float:left;width:40%;\" value=\"$post_link\" /><span style=\"float:left;cursor:pointer;border:1px solid #ccc;border-radius:3px;padding:10px 6px;margin-left:5px;\" class=\"copytoClipboard\" data-clipboard-target=\"#copytoClipboard\"><i class=\"fa fa-copy fa-2\"></i> "._COPY."</span>
						
						<input type=\"text\" class=\"inp-form-ltr\" id=\"short_copytoClipboard\" style=\"float:left;width:200px;margin-left:10px;\" value=\"$short_link\" />
						<span style=\"float:left;cursor:pointer;border:1px solid #ccc;border-radius:3px;padding:10px 6px;margin-left:5px;\" class=\"copytoClipboard\" data-clipboard-target=\"#short_copytoClipboard\"><i class=\"fa fa-copy fa-2\"></i> "._COPY."</span>
						
						<a href=\"$post_link\" target=\"_blank\" style=\"float:right;border:1px solid #ccc;border-radius:3px;padding:10px 6px;margin-left:2px;\"><i class=\"fa fa-eye\"></i> "._VIEW."</a>
					</td>
				</tr>";
				}
				$contents .="<tr><td><input type=\"hidden\" name=\"article_fields[post_type]\" id=\"post_type\" value=\"".$article_fields['post_type']."\" /></td></tr>";
				$contents .="<tr>
					<th style=\"width:200px\">"._TITLE."</th>
					<td><input type=\"text\" size=\"40\" name=\"article_fields[title]\" id=\"title_field\" value=\"".$article_fields['title']."\" class=\"inp-form\" minlength=\"3\" data-validation=\"required\" data-validation-error-msg=\""._TITLE_IS_REQUIRED."\" /></td>
				</tr>
				<tr>
					<th>"._PERMALINK."</th>
					<td>
						<input type=\"text\" size=\"40\" name=\"article_fields[post_url]\" id=\"post_url\" value=\"".$article_fields['post_url']."\" class=\"inp-form\" />
					</td>					
				</tr>
				<tr>
					<th>"._ARTICLE_LEAD."</th>
					<td><input type=\"text\" size=\"40\" name=\"article_fields[title_lead]\" value=\"".$article_fields['title_lead']."\" class=\"inp-form\" /></td>					
				</tr>
				<tr>
					<th>"._TITLE_COLOR."</th>
					<td><input type=\"text\" name=\"article_fields[title_color]\" size=\"37\" data-letterCase=\"uppercase\" value=\"".$article_fields['title_color']."\" class=\"color-picker inp-form\" id=\"swatches-opacity\" class=\"demo\" data-opacity=\"1\" data-swatches=\"#fff|#000|#f00|#0f0|#00f|#ff0\" value=\"#000\" /></td>					
				</tr>
				<tr>
					<th>"._MAIN_CAT."</th>
					<td>
					<select name=\"article_fields[cat_link]\" class=\"styledselect-select\" data-validation=\"required\">";
					asort($categories->result);
					foreach($categories->result as $cid => $catname)
					{
						$sel = ($cid == $article_fields['cat_link']) ? "selected":"";
						$contents .= "<option value=\"$cid\" $sel>".str_replace("-"," ", $catname)."</option>";
					}					
					$contents .= "</select>
					</td>					
				</tr>
				<tr>
					<th>"._RELATED_CATS."</th>
					<td>
					<select name=\"article_fields[cat][]\" class=\"styledselect-select\" multiple=\"multiple\" style=\"width:100%\">";
					$article_fields['cat'] = array_filter($article_fields['cat']);
					foreach($categories->result as $cid => $catname)
					{
						$sel = (in_array($cid, $article_fields['cat'])) ? "selected":"";
						$contents .= "<option value=\"$cid\" $sel>".str_replace("-"," ", $catname)."</option>";
					}					
					$contents .= "</select>
					</td>					
				</tr>
				<tr>
					<th>"._SHOW_IN_MAIN_PAGE."</th>
					<td><input type=\"radio\" name=\"article_fields[ihome]\" value=\"1\" class=\"styled\" data-label=\""._YES."\" $ihome_checked1 /><input type=\"radio\" name=\"article_fields[ihome]\" value=\"0\" class=\"styled\" data-label=\""._NO."\" $ihome_checked2 /></td>					
				</tr>
				<tr>
					<th>"._ALLOW_COMMENT."</th>
					<td><input type=\"radio\" name=\"article_fields[allow_comment]\" value=\"1\" class=\"styled\" data-label=\""._ACTIVE."\" $allow_comment_checked1 /><input type=\"radio\" name=\"article_fields[allow_comment]\" value=\"0\" class=\"styled\" data-label=\""._INACTIVE."\" $allow_comment_checked2 /></td>					
				</tr>";
				if($nuke_configs['multilingual'] == 1)
				{
					$contents .= "
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
									$contents .= "<option value=\"$languageslist\" $sel>".ucfirst($languageslist)."</option>";
								}
							}
							$contents .= "
							</select>
						</td>
					</tr>";
				}
				else
					$contents .= "<input type=\"hidden\" name=\"article_fields[alanguage]\" value=\"\" />";
				
				$contents .= "<tr>
					<th>"._POSITION."</th>
					<td>
						<select name=\"article_fields[position]\" class=\"styledselect-select\">";
						for($i =1; $i <=100; $i++)
						{
							$position_name = (isset($nuke_articles_configs_cacheData['position_names'][$i]) && $nuke_articles_configs_cacheData['position_names'][$i] != '') ? $nuke_articles_configs_cacheData['position_names'][$i]:""._POSITION_NUMBER." $i";
							$sel = ($i == $article_fields['position']) ? "selected":"";
							$contents .= "<option value=\"$i\" $sel>$position_name</option>\n";
						}
						$contents .= "</select>
					</td>					
				</tr>
				<tr>
					<th>"._PASSWORD."</th>
					<td><input type=\"text\" size=\"40\" name=\"article_fields[post_pass]\" value=\"".$article_fields['post_pass']."\" class=\"inp-form\" /></td>					
				</tr>
				<tr>
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
				</tr>
				<tr>
					<th>"._HOMETEXT."</th>
					<td>
						".wysiwyg_textarea("article_fields[hometext]", $article_fields['hometext'], "PHPNukeAdmin", "50", "12")."
					</td>					
				</tr>
				<tr>
					<th>"._BODYTEXT."</th>
					<td>
						".wysiwyg_textarea("article_fields[bodytext]", $article_fields['bodytext'], "PHPNukeAdmin", "50", "12")."
					</td>					
				</tr>
				<tr>
					<th>"._ARTICLE_FILES."</th>
					<td>".get_post_download_files($article_fields['download'], _ARTICLE_FILES, "article_fields[download]")."</td>				
				</tr>
				<tr>
					<th>"._SHOWN_FOR."</th>
					<td>";
						$permissions = get_groups_permissions();
						foreach($permissions as $key => $premission_name)
						{
							$checked = (($mode == 'new' && $key == 0) || in_array($key, $article_fields['permissions'])) ? "checked":'';
							
							$contents .= "<input data-label=\"$premission_name\" type=\"checkbox\" class=\"styled\" id=\"edit-block-permission_$key\" value=\"$key\" name=\"article_fields[permissions][]\" $checked />&nbsp; ";
						}
					$contents .= "</td>					
				</tr>
				<tr>
					<th>"._KEYWORDS."</th>
					<td>
						<select class=\"styledselect-select tag-input\" name=\"article_fields[tags][]\" multiple=\"multiple\" style=\"width:100%\">";
						$article_fields['tags'] = array_filter($article_fields['tags']);
						if(isset($article_fields['tags']) && !empty($article_fields['tags']))
						{
							if(!is_array($article_fields['tags']))
								$article_fields['tags'] = explode(",", $article_fields['tags']);
							foreach($article_fields['tags'] as $tag)
								$contents .= "<option value=\"$tag\" selected>$tag</option>\n";
						}
						$contents .= "</select>
					</td>					
				</tr>
				<tr>
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
							$contents .= "<option value=\"$hour\" $selected>$hour</option>\n";
						}
						$contents .= "</select>
						&nbsp; <select name=\"article_fields[publish_time][min]\" class=\"styledselect-select\" style=\"width:70px;\">";
						for($m=0;$m < 60; $m++)
						{
							$min = ($m < 10) ? "0".$m:$m;
							$selected = ($publish_min == $min) ? "selected":"";
							$contents .= "<option value=\"$min\" $selected>$min</option>\n";
						}
						$contents .= "</select>
						".bubble_show("<div style=\"margin-top:-7px;\"><input id=\"set_publish_date\" type='checkbox' class='styled' name='article_fields[set_publish_date]' value='1' data-label=\""._SET_PUBLISH_DATE."\" $set_publish_date style=\"top:10px;\"></div>")."
					</td>
				</tr>
				<tr>
					<th>"._POST_METADATA."</th>
					<td>
						<table width=\"100%\">
							".show_micro_data_inputs($article_fields['micro_data'])."
						</table>
					</td>					
				</tr>";
				if($mode == 'new' || $article_status == 'pending')
				{
				$contents .= "<tr>
					<th>"._PUBLISH_AS."</th>
					<td>
						<input type=\"radio\" name=\"article_fields[status]\" value=\"draft\" class=\"styled\" data-label=\""._DRAFT."\" /> &nbsp; 
						<input type=\"radio\" name=\"article_fields[status]\" value=\"publish\" class=\"styled\" data-label=\""._IMMEDIATE_PUBLISH."\" checked /> &nbsp; 
						<input type=\"radio\" name=\"article_fields[status]\" value=\"preview\" class=\"styled\" data-label=\""._PREVIEW."\" /> &nbsp; 
					</td>					
				</tr>
				<tr>
					<th>"._INFORM_TO_PING_SERICES."</th>
					<td><input type=\"radio\" name=\"go_to_pings\" value=\"1\" class=\"styled\" data-label=\""._YES."\" checked /><input type=\"radio\" name=\"go_to_pings\" value=\"0\" class=\"styled\" data-label=\""._NO."\" /></td>					
				</tr>";
				}
				$contents .= bulid_meta_fields($module, $article_fields['meta_fields']);
				$contents .= "
				<tr>
					<td colspan=\"2\">
						<input type=\"submit\" name=\"submit\" value=\"submit\" class=\"form-submit\" />
					</td>					
				</tr>";
			$contents .= "</table>
			<input type=\"hidden\" name=\"op\" value=\"article_admin\" />
			<input type=\"hidden\" name=\"article_fields[aid]\" value=\"$article_aid\" />
			<input type=\"hidden\" name=\"article_fields[ip]\" value=\"$visitor_ip\" />
			<input type=\"hidden\" name=\"sid\" value=\"$sid\" />
			<input type=\"hidden\" name=\"mode\" value=\"$mode\" />
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>
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
			});
		</script>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function article_change_admin($sid, $status = '', $new_aid = '', $submit = '')
	{
		global $db, $admin_file, $nuke_configs, $nuke_authors_cacheData;

		
		$sid = intval($sid);
		
		$row = $db->table(POSTS_TABLE)
			->where('sid', $sid)
			->first(['aid']);
		
		$aid = filter($row['aid'], "nohtml");
		
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
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form>";
			$content .= jquery_codes_load('',true);
			die($content);
	}
	
	function delete_image($sid, $post_type = 'Articles')
	{
		global $nuke_configs;
		csrfProtector::authorisePost(true);
		if(file_exists("files/$post_type/$sid.jpg"))
		{
			unlink("files/$post_type/$sid.jpg");
			if(file_exists("files/$post_type/thumbs/$sid.jpg"))
				unlink("files/$post_type/thumbs/$sid.jpg");
		}
		return true;
	}
	
	global $pn_prefix;
	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$status = (isset($status)) ? filter($status, "nohtml"):'';
	$post_type = (isset($post_type)) ? filter($post_type, "nohtml"):'Articles';
	$new_aid = (isset($new_aid)) ? filter($new_aid, "nohtml"):'';
	$order_by = (isset($order_by)) ? filter($order_by, "nohtml"):'';
	$sort = (isset($sort)) ? filter($sort, "nohtml"):'';
	$search_query = (isset($search_query)) ? filter($search_query, "nohtml"):'';
	$submit = (isset($submit)) ? filter($submit, "nohtml"):'';
	$article_image_upload = (isset($article_image_upload)) ? $article_image_upload:array();
	$go_to_pings = (isset($go_to_pings)) ? intval($go_to_pings):1;
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'new';
	$article_fields = (isset($article_fields)) ? $article_fields:array();
	$micro_data = (isset($micro_data)) ? $micro_data:array();
	$sid = (isset($sid)) ? intval($sid):0;
	
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