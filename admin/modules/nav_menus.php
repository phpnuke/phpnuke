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

	class Walker_nav_menus_admin extends Walker
	{

		public function start_lvl(&$output, $depth = 0, $args = array())
		{
			$indent = str_repeat("\t", $depth);
			$output .= "\n$indent\t<".$args->list_type." class=\"dd-list\">\n";
		}
			
		public function end_lvl(&$output, $depth = 0, $args = array())
		{
			$indent = str_repeat("\t", $depth);
			$output .= "$indent\t</".$args->list_type.">\n";
		}
		
		public function start_el(&$output, $element, $depth = 0, $args = array(), $id = 0)
		{
			$indent = ($depth) ? str_repeat("\t", $depth) : '';

			$classes = empty($element->classes) ? array() : (array) $element->classes;
			$classes[] = 'menu-item-' . $element->nid;
			
			$class_names = join(' ', array_filter($classes));
			$class_names = $class_names ? ' class="dd-item ' . filter($class_names, "nohtml") . '"' : '';

			$id				= $element->nid;
			$title			= $element->title;
			$url			= $element->url;
			$type			= $element->type;
			$part_id		= $element->part_id;
			$module			= $element->module;
			$status			= $element->status;
			
			$data_id		= isset($id)		? ' data-'.	$this->_uniqueId.'="' . intval($id) . '"' : '';
			$data_title		= isset($title)		? ' data-title="' . $title . '"' : '';
			$data_url		= isset($url)		? ' data-url="' . $url . '"' : '';
			$data_type		= isset($type)		? ' data-type="' . $type . '"' : '';
			$data_part_id	= isset($part_id)	? ' data-part_id="' . $part_id . '"' : '';
			$data_module	= isset($module)	? ' data-module="' . $module . '"' : '';
			$data_status	= isset($status)	? ' data-status="' . $status . '"' : '';
			
			$output .= $indent . '<li' . $data_id . $data_title . $data_url . $data_type . $data_part_id . $data_module. $data_status;
			
			if(isset($element->attributes) && !empty($element->attributes))
			{
				foreach ($element->attributes as $attribute => $value)
				{
					$value = filter($value, "nohtml");
					$output .= ' data-' . $attribute . '="' . $value . '"';
				}
			}
			
			$output .=  $class_names.'>';

			$item_output = $args->before . $title . $args->after;
			
			$output .= $item_output;
		}
		
		public function end_el(&$output, $element, $depth = 0, $args = array())
		{
			$output .= "</li>\n";
		}
	}

	function nav_menus()
	{
		global $db, $hooks, $nuke_nav_menus_cacheData, $admin_file, $nuke_configs;
		
		$contents = '';
		$contents .= GraphicAdmin();
		
		$hooks->add_filter("set_page_title", function(){return array("nav_menus" => _NAVS_ADMIN);});
		
		$contents .= "<div align=\"center\" style=\"margin:20px 0;\">[ <a href=\"".$admin_file.".php?op=nav_menus_admin\">"._ADD_NEW_NAV."</a> ]</div>";
		
		$contents .= OpenAdminTable();
		$contents .= "
			<table align=\"center\" class=\"product-table\" width=\"100%\">
			<thead>
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;\">ID</th>
				<th class=\"table-header-repeat line-left\">"._TITLE."</th>
				<th class=\"table-header-repeat line-left\">"._POSITION_ON_THEME."</th>
				<th class=\"table-header-repeat line-left\">"._STATUS."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:110px;\">"._OPERATION."</th>
			</tr>
			</thead>
			<tbody>";
			
			$past_navs = _NOWTIME-86400;
			
			$db->table(NAV_MENUS_TABLE)
				->where("status", 2)
				->where("date", "<", $past_navs)
				->delete();
				
			$result = $db->table(NAV_MENUS_TABLE)
							->where("status", "!=", 2)
							->order_by(["nav_id" => "DESC"])
							->select();
			if($db->count() > 0)
			{
				foreach($result as $row)
				{
					$nav_id = intval($row['nav_id']);
					$nav_status = (intval($row['status']) == 0) ? _INACTIVE:_ACTIVE;
					$nav_title = filter($row['nav_title'], "nohtml");
					$nav_location = filter($row['nav_location'], "nohtml");
									
					$contents .= "<tr>
						<td align=\"center\">$nav_id</td>
						<td>$nav_title</td>
						<td align=\"center\">$nav_location</td>
						<td align=\"center\">$nav_status</td>
						<td align=\"center\">
							<a href=\"".$admin_file.".php?op=nav_menus_admin&mode=edit&nav_id=$nav_id\" title=\""._EDIT."\" class=\"table-icon icon-1 info-tooltip\"></a>
							<a href=\"".$admin_file.".php?op=nav_menus_admin&mode=delete&nav_id=$nav_id&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._NAV_DELETE_SURE."');\"></a>
						</td>
						</tr>";
				}
			}
			$contents .= "
			</tbody>
		</table>";

		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	function nav_menus_admin($nav_id=0, array $nav_fields = array(), array $nav_menu_fields = array(), $mode='add_nav')
	{
		global $db, $admin_file, $nuke_configs, $hooks, $theme_setup;
		
		$nav_id = intval($nav_id);
		$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
		$theme_nav_menus = (isset($theme_setup['theme_nav_menus'])) ? $theme_setup['theme_nav_menus']:array();
		
		// add menu to a nav
		if($mode == "add_nav_menu" && !empty($nav_menu_fields))
		{
			$hooks->do_action("add_nav_menu_before", $nav_id, $nav_fields, $nav_menu_fields, $mode);
			$result = $db->table(NAV_MENUS_DATA_TABLE)
							->order_by(["nid" => "DESC"])
							->limit(0, 1)
							->select()
							->first();	

			$last_nid = intval($result['nid']);
			$new_increment_nid = $last_nid+1;
			
			if($nav_menu_fields['type'] == 'custom')
			{
				$insert = $db->table(NAV_MENUS_DATA_TABLE)
							->insert([
								"nid" => $new_increment_nid,
								"nav_id" => $nav_id,
								"title" => $nav_menu_fields['title'],
								"url" => $nav_menu_fields['url'],
								"type" => $nav_menu_fields['type'],
							]);

				$new_increment_nid++;
			}
			elseif($nav_menu_fields['type'] == 'categories')
			{
				$nav_categories = $nav_menu_fields['categories'];
				$insert_values = array();
				if(!empty($nav_categories))
				{
					foreach($nav_categories as $cat_id => $cat_text)
					{
						$cat_link = sanitize(filter(implode("/", array_reverse(get_parent_names($cat_id, $nuke_categories_cacheData[$nav_menu_fields['module']], "parent_id", "catname_url"))), "nohtml"), array("/"));
						$insert_values[] = array($new_increment_nid, $nav_id, $cat_text, $nav_menu_fields['type'], $nav_menu_fields['module'], $cat_id, LinkToGT("index.php?modname=".$nav_menu_fields['module']."&category=$cat_link"));
						$new_increment_nid++;
					}
				}
				
				$db->table(NAV_MENUS_DATA_TABLE)->setSchema("auto_increment = $new_increment_nid")->alter();
							
				if($insert_values != '')
					$insert = $db->table(NAV_MENUS_DATA_TABLE)
									->multiinsert(["nid", "nav_id", "title", "type", "module", "part_id", "url"],$insert_values);
				else
					$insert = false;
			}

			if($insert)
			{
				$result = $db->table(NAV_MENUS_DATA_TABLE)
								->where("nav_id", $nav_id)
								->where("nid", ">", $last_nid)
								->where("nid", "<", $new_increment_nid)
								->order_by(["nid" => "DESC"])
								->select();
				if($db->count() > 0)
				{
					foreach($result as $row)
					{
						$new_nid = intval($row['nid']);
						$pid = intval($row['pid']);
						$title = filter($row['title'], "nohtml"); 
						$url = filter($row['url'],"nohtml");
						$type = filter($row['type'],"nohtml");
						$module = filter($row['module'],"nohtml");
						$part_id = filter($row['part_id'],"nohtml");
						$attributes = ($row['attributes'] != '') ? phpnuke_unserialize(stripslashes($row['attributes'])):array();
						
						$message[$new_nid] = array(
							'nid' => $new_nid,
							'pid' => $pid,
							'title' => $title,
							'url' => $url,
							'type' => $type,
							'status' => 0,
							'module' => $module,
							'part_id' => $part_id,
							"attributes" => $attributes
						);
					}
				}
				$response = json_encode(
				array(
					"status" => "success",
					"message" => $message
				));
				cache_system("nuke_nav_menus");
			}
			else
				$response = json_encode(
				array(
					"status" => "error",
					"message" => ""._ERROR_IN_OP." : ".$db->getErrors('last')['message'].""
				));
			
			$hooks->do_action("add_nav_menu_after", $nav_id, $nav_fields, $nav_menu_fields, $mode);
			die($response);
		}
		// add menu to a nav
		
		// save a nav
		if($mode == "save_nav" && !empty($nav_fields))
		{
			$hooks->do_action("save_nav_before", $nav_id, $nav_fields, $nav_menu_fields, $mode);
			$update_query = array();
			
			$update_fields = array('status', 'pid', 'title', 'url', 'weight', 'attributes');
			
			if(!is_array($nav_fields))
				$nav_fields = json_decode($nav_fields);
				
			if(isset($nav_fields['final_items']))
			{
				$nav_fields['final_items'] = (is_array($nav_fields['final_items'])) ? $nav_fields['final_items']:objectToArray(json_decode($nav_fields['final_items']));
				$db->sql_query("UPDATE ".NAV_MENUS_TABLE." SET nav_title = '".$nav_fields['general_title']."', lang_nav_title = '".addslashes(phpnuke_serialize($nav_fields['lang_titles']))."', nav_location = '".$nav_fields['nav_location']."', status = '".$nav_fields['nav_status']."', date = '"._NOWTIME."' WHERE nav_id = '$nav_id'");
				
				$unnested_nav_fields = array_flatten($nav_fields['final_items'], 0, 'nid', 'pid', 'children', array('remove','title','url','target','xfn','href','classes', 'styles', 'status'), array());
				
				if(!empty($unnested_nav_fields))
				{
					$must_removed_items = array();
					$weight = 1;
					foreach($unnested_nav_fields as $nid => $nav_data)
					{
						$unnested_nav_fields[$nid]['weight'] = $weight;
						$weight++;
						foreach($nav_data as $nav_data_key => $nav_data_val)
						{
							if(in_array($nav_data_key, array("target","xfn","classes","styles")))
							{
								$unnested_nav_fields[$nid]['attributes'][$nav_data_key] = $nav_data_val;
								unset($unnested_nav_fields[$nid][$nav_data_key]);
							}
							if($nav_data_key == "remove")
								$must_removed_items[] = $nid;
						}
					}
					
					$update_query = "UPDATE ".NAV_MENUS_DATA_TABLE." SET ";
					$c = 0;
					$update_query_items = '';
					foreach($update_fields as $update_field_key => $update_field)
					{
						$update_query_field = (($c == 0) ? "":", \n").((is_array($update_field)) ? $update_field_key:$update_field)." = (CASE ";
						$update_query_value = '';
						foreach($unnested_nav_fields as $nid => $nav_data)
						{
							$update_query_value .= "\nWHEN nid = '$nid' THEN '".((isset($nav_data[$update_field])) ? ((is_array($nav_data[$update_field])) ? phpnuke_serialize($nav_data[$update_field]):$nav_data[$update_field]):"")."' ";
						}
						
						if($update_query_value != '')
						{
							$c++;
							$update_query_items .= $update_query_field.$update_query_value."END)";
						}
					}
					
					if(!empty($must_removed_items))
					{
						$must_removed_items = implode(",", $must_removed_items);
						$db->sql_query("DELETE FROM ".NAV_MENUS_DATA_TABLE." WHERE nid IN ($must_removed_items)");
						$response = json_encode(
								array(
									"status" => "success",
									"message" => _OP_OK
								));
					}
					
					if($update_query_items != '')
					{
						$update_query .= $update_query_items."\n WHERE nid IN (".implode(",", array_keys($unnested_nav_fields)).")";

						if($db->sql_query($update_query))
						{
							$response = json_encode(
								array(
									"status" => "success",
									"message" => _OP_OK
								));
						}
						elseif($response == '')
						{
							$response = json_encode(
								array(
									"status" => "error",
									"message" => ""._ERROR_IN_OP." : ".end($db->errors['message']).""
								));
						}
					}
					elseif($response == '')
					{
						$response = json_encode(
						array(
							"status" => "error",
							"message" => _INCOMPLETE_SENT_DATA
						));
					}
				}
				else
				{
					$response = json_encode(
					array(
						"status" => "error",
						"message" => _INCOMPLETE_SENT_DATA
					));
				}
				cache_system("nuke_nav_menus");
				add_log(sprintf(_EDIT_NAV_LOG, $nav_fields['general_title']), 1);
			}
			else
			{
				$response = json_encode(
					array(
						"status" => "error",
						"message" => _EMPTY_NAV
					));
			}
			$hooks->do_action("save_nav_after", $nav_id, $nav_fields, $nav_menu_fields, $mode);
			die($response);
		}
		// save a nav
		
		// remove a nav
		if($mode == "delete")
		{
			csrfProtector::authorisePost(true);
			$hooks->do_action("delete_nav_before", $nav_id, $nav_fields, $nav_menu_fields, $mode);
			$result1 = $db->sql_query("DELETE FROM ".NAV_MENUS_DATA_TABLE." WHERE nav_id = '$nav_id'");
			$result1 = $db->sql_query("DELETE FROM ".NAV_MENUS_TABLE." WHERE nav_id = '$nav_id'");
			$hooks->do_action("delete_nav_after", $nav_id, $nav_fields, $nav_menu_fields, $mode);
			cache_system("nuke_nav_menus");
			header("location: ".$admin_file.".php?op=nav_menus");
		}
		// remove a nav
		
		$max_depth = 11;
		$nav_date = 0;
		$new_nav = false;
		
		$nuke_categories_cacheData = get_cache_file_contents('nuke_categories');
		
		$nav_title = '';
		$lang_nav_title = array();
		$nav_location = '';
		
		if($nav_id == 0)
		{
			$nav_id = $db->table(NAV_MENUS_TABLE)
				->insert([
					"date"		=> _NOWTIME,
					"status"	=> 2
				]);
			
			$nav_title = '';
			$lang_nav_title = array();
			$nav_location = '';
			$nav_status = 2;
			$new_nav = true;
		}
		
		$result = $db->query("SELECT n.*, nv.nav_title, nv.nav_location, nv.lang_nav_title, nv.status as nav_status FROM ".NAV_MENUS_DATA_TABLE." AS n LEFT JOIN ".NAV_MENUS_TABLE." AS nv ON n.nav_id = nv.nav_id WHERE n.nav_id = ? ORDER BY n.weight ASC, n.nid ASC", array($nav_id));
		
		if(intval($result->count()) == 0)
			$result = $db->query("SELECT *, status as nav_status FROM ".NAV_MENUS_TABLE." WHERE nav_id = ?", array($nav_id));
		
		if($db->count() > 0)
		{
			foreach($result as $row)
			{
				if($nav_title == '' && $nav_location == '')
				{
					$nav_status = intval($row['nav_status']);
					$nav_title = filter($row['nav_title'],"nohtml");
					$lang_nav_title = ($lang_nav_title != '') ? phpnuke_unserialize(stripslashes($row['lang_nav_title'])):array();
					$nav_location = filter($row['nav_location'],"nohtml");
				}
				
				if(isset($row['nid']))
				{
					$nid = intval($row['nid']);
					$pid = intval($row['pid']);
					
					$title = filter($row['title'],"nohtml");
					$url = filter($row['url'],"nohtml");
					$type = filter($row['type'],"nohtml");
					$module = filter($row['module'],"nohtml");
					$part_id = intval($row['part_id']);
					$status_id = intval($row['status']);
					$attributes = ($row['attributes'] != '') ? phpnuke_unserialize(stripslashes($row['attributes'])):array();
					$nav_menus[] = (object) array('nid' => $nid, 'pid' => $pid, 'title' => $title, 'url' => $url, 'type' => $type, 'module' => $module, 'part_id' => $part_id, 'status' => $status_id, "attributes" => (object) $attributes);
				}
			}
		}
		
		if(!empty($nav_menus))
		{
			//convert array menus to string
			$menus_output = '';
			$args = (object) array(
				'list_type'			=> "ol",
				'depth'				=> $max_depth,
				'before'			=> '<div class="dd-handle menu-actions">',
				'after'				=> '</div><div class="dd-options"></div>', 
			);
			
			$walker = new Walker_nav_menus_admin;
			$args = array($nav_menus, 'nid', 'pid', $max_depth, $args);
			$menus_output .= call_user_func_array(array($walker, "walk"), $args);
			//convert array menus to string
		}
		
		//convert array categories to string
		$categories_output = '';
		$args = (object) array(
			'list_type'			=> "ul",
			'depth'				=> $max_depth,
			'hide_content'		=> true,
			'before'			=> '<input type="checkbox" class="styled" data-label="%2$s" id="checkbox-%1$d" value="%1$d" /> ',
			'after'				=> '',
		);
		
		$walker = new Walker_nav_categories;
		foreach($nuke_categories_cacheData as $module => $nuke_categories_cacheData_val)
		{
			foreach ($nuke_categories_cacheData_val as $key => &$nuke_categories_cacheData_value) {
				$nuke_categories_cacheData_value['cattext'] = category_lang_text($nuke_categories_cacheData_value['cattext']);
				$nuke_categories_cacheData_value = (object)$nuke_categories_cacheData_value;
			}
			$argsnew = array($nuke_categories_cacheData_val, 'catid', 'parent_id', $max_depth, $args);

			$categories_output .= "
									<div class=\"beefup nav_select\" id=\"nav_cats_select-$module\">
										<h3 class=\"beefup__head\">
											"._CATEGORIES." $module
										</h3>

										<div class=\"beefup__body\">
											<ul class=\"categories_select checktree\" data-module=\"$module\">
												".call_user_func_array(array($walker, "walk"), $argsnew)."
											</ul>
											<input data-label=\""._CHECKALL."\" type=\"checkbox\" class=\"styled select-all\" data-element=\"#nav_cats_select-$module\">
											<div class=\"single-field\">
												<button class=\"add_menu\" data-action=\"categories\">"._ADD."</button>
											</div>
										</div>
									</div>";
		}
		//convert array categories to string

		$itemoptionshtml = '<table class="product-table no-border id-form editoptions"><tr><th style=\"width:150px;\">'._TITLE.'</th><td><input type="text" class="inp-form dd-itemOption" size="25" id="itemOption-title" /></td></tr><tr><th>'._ADDRESS.'</th><td><input type="text" class="inp-form-ltr dd-itemOption" placeholder="http://" size="25" id="itemOption-url" /></td></tr><tr><th>'._TARGET.'</th><td><input type="text" class="inp-form-ltr dd-itemOption" size="25" placeholder="'._FOREXAMPLE.' _target" id="itemOption-target" /></td></tr><tr><th> '._XHTML_FRIENDS_NETWORK.'</th><td><input type="text" class="inp-form dd-itemOption" size="25" placeholder="'._FOREXAMPLE.' friends" id="itemOption-xfn" /></td></tr><tr><th>'._CUSTOMM_CLASSES.'</th><td><input type="text" class="inp-form-ltr dd-itemOption" size="25" id="itemOption-classes" /></td></tr><tr><th>'._CUSTOM_STYLES.'</th><td><input type="text" class="inp-form-ltr dd-itemOption" size="25" id="itemOption-styles" /></td></tr><tr><th>'._STATUS.'</th><td><select class="styledselect-select-dialog dd-itemOption" id="itemOption-status"><option value="1">'._ACTIVE.'</option><option value="0">'._INACTIVE.'</option></select></td></tr></table><button type="button" data-action="save">'._SAVE.'</button>';
		
		$languageslists = get_dir_list('language', 'files');
		$contents = "";
		
		$contents .= GraphicAdmin();
		
		$pagetitle = ($nav_id == 0) ? _ADD_NEW_NAV:sprintf(_EDIT_NAV, $nav_title);
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("nav_menus_admin" => $pagetitle);});
		
		$contents .= "<div align=\"center\" style=\"margin:20px 0;\">[ <a href=\"".$admin_file.".php?op=nav_menus\">"._NAVS_ADMIN."</a> ]</div>";
		
		$contents .= OpenAdminTable();
		
		$contents .="
		<script src=\"admin/template/js/jquery/jquery.nestable.js\"></script>
		<script src=\"includes/Ajax/jquery/jquery.beefup.min.js\"></script>
		<script src=\"includes/Ajax/jquery/jquery-checktree.js\"></script>
		<link rel=\"stylesheet\" href=\"admin/template/css/jquery.nestable.css\" type=\"text/css\" media=\"all\" />
		<link rel=\"stylesheet\" href=\"includes/Ajax/jquery/jquery.beefup.css\" type=\"text/css\" media=\"all\" />
		<link rel=\"stylesheet\" href=\"includes/Ajax/jquery/jquery-checktree.css\" type=\"text/css\" media=\"all\" />
		<table width=\"100%\" class=\"product-table no-border\">
			<tr>
				<th align=\""._TEXTALIGN1."\" style=\"width:200px;\">"._GENERAL_NAV_NAME."</th>
				<td align=\""._TEXTALIGN1."\"><input type=\"text\" id=\"nav_title\" value=\"$nav_title\" class=\"inp-form\" /></td>
			</tr>";
			if($nuke_configs['multilingual'] == 1)
			{
				foreach($languageslists as $languageslist)
				{
					if($languageslist != "")
					{
						if($languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
						$languageslist = str_replace(".php", "", $languageslist);
						$contents .="<tr>
							<th align=\""._TEXTALIGN1."\">"._TITLE_INLANG." ".ucfirst($languageslist)."</th>
							<td align=\""._TEXTALIGN1."\"><input type=\"text\" class=\"nav_titles inp-form\" data-language=\"$languageslist\" value=\"".(isset($lang_nav_title[$languageslist]) ? $lang_nav_title[$languageslist]:"")."\" /></td>
						</tr>";
					}
				}
			}
			$contents .="<tr>
				<th align=\""._TEXTALIGN1."\">"._POSITION_ON_THEME."</th>
				<td align=\""._TEXTALIGN1."\"><select id=\"nav_location\" class=\"styledselect-select\">";
				foreach($theme_nav_menus as $theme_nav_id => $theme_nav_name)
				{
					$sel = ($theme_nav_id == $nav_location) ? "selected":"";
					$contents .="<option value=\"$theme_nav_id\" $sel>$theme_nav_name</option>";
				}
				$contents .="</select>
				</td>
			</tr>
			<tr>
				<th align=\""._TEXTALIGN1."\">"._STATUS."</th>
				<td align=\""._TEXTALIGN1."\">";
				$check1 = ($nav_status == 1) ? "checked":"";
				$check2 = ($nav_status == 0) ? "checked":"";
				$contents .="<input type=\"radio\" value=\"1\" name=\"nav_status\" class=\"styled nav_status\" data-label=\""._ACTIVE."\" $check1 />
				<input type=\"radio\" value=\"0\" name=\"nav_status\" class=\"styled nav_status\" data-label=\""._INACTIVE."\" $check2 />
				</td>
			</tr>
		</table>
		<div id=\"update_result\"></div>
		<table width=\"100%\" class=\"id-form\">
			<tr>
				<td width=\"350px\" valign=\"top\" style=\"border:1px solid #ccc;\">
					<table align=\"center\" class=\"product-table no-hover no-border\" width=\"100%\">
						<thead>
						<tr>
							<th class=\"table-header-repeat line-left\" style=\"text-align:center;\">"._SELECT."</th>
						</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									$categories_output
									<div class=\"beefup nav_select\" id=\"nav_select-2\">
										<h3 class=\"beefup__head\">
											"._CUSTOM_URLS."
										</h3>

										<div class=\"beefup__body\">
											<p>
												<div class=\"single-field\">
													<label for=\"custom_menu_url\">"._ADDRESS.":</label>
													<input name=\"custom_menu_url\" id=\"custom_menu_url\" type=\"text\" class=\"inp-form-ltr\" value=\"http://\"></input>
												</div>
												<div class=\"single-field\">
													<label for=\"custom_menu_title\">"._TEXT.":</label>
													<input name=\"custom_menu_title\" id=\"custom_menu_title\" type=\"text\" class=\"inp-form\"></input>
												</div>
												<div class=\"single-field\">
													<button class=\"add_menu\" data-action=\"custom\">"._ADD."</button>
												</div>
											</p>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td valign=\"top\" style=\"border:1px solid #ccc;\">
					<table align=\"center\" class=\"product-table no-hover no-border\" width=\"100%\">
						<thead>
						<tr>
							<th class=\"table-header-repeat line-left\" style=\"text-align:center;\">"._STRAUCTURE."
								<menu id=\"nestable-menu\">
									<button type=\"button\" class=\"form-submit\" data-action=\"expand-all\">"._EXPAND_ALL."</button>
									<button type=\"button\" class=\" form-submit\" data-action=\"collapse-all\">"._CLOSE_ALL."</button>
									<button type=\"button\" class=\" form-submit\" data-action=\"save-nav\">"._SAVECHANGES."</button>
								</menu>
							</th>
						</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class=\"dd\" id=\"nestable\">
										<ol class=\"dd-list\">
											".(($nav_id != 0 && !empty($nav_menus) && $menus_output != '') ? $menus_output:"")."
										</ol>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<textarea id=\"nestable-output\"></textarea>
		<script>
			var admin_file = '$admin_file';
			var max_depth = $max_depth;
			var nav_id = $nav_id;
			var item_options_html = '$itemoptionshtml';
		</script>
		<script src=\"admin/template/js/jquery/jquery.nav_menus.js\"></script>";

		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'';
	$nav_fields = (isset($nav_fields)) ? $nav_fields:array();
	$nav_menu_fields = (isset($nav_menu_fields)) ? $nav_menu_fields:array();
	$nav_id = (isset($nav_id)) ? intval($nav_id):0;
	$nid = (isset($nid)) ? intval($nid):0;
	
	switch ($op) {
		case "nav_menus":
			nav_menus();
		break;
		case "nav_menus_admin":
			nav_menus_admin($nav_id, $nav_fields, $nav_menu_fields, $mode);
		break;
	}
}
else
{
	header("location: ".$admin_file.".php");
}
?>