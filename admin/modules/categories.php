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
	function categories($cat_modulename='Articles', $parent_id=0)
	{
		global $db, $admin_file, $nuke_configs, $hooks;
		
		$nuke_categories_cacheData = get_cache_file_contents('nuke_categories', false, true);
		
		$contents = '';
		$contents .= GraphicAdmin();
		
		$parent_id = intval($parent_id);
		
		$cats_link_deep = '';
		if($parent_id != 0)
		{
			$cat_title = sanitize(filter(implode("/", array_reverse(get_parent_names($parent_id, $nuke_categories_cacheData[$cat_modulename], "parent_id", "catname_url"))), "nohtml"), array("/"));
			
			$attrs = array(
				"title" => "{CAT_TEXT}",
				"id" => "{CATID}"
			);
			$cats_link_deep = implode("/", category_link($cat_modulename, $cat_title, $attrs));
			$cats_link_deep = preg_replace('#<a(.*)href="(.*)"(.*)id="(.*)">(.*)</a>#isU', '<a$1href="'.$admin_file.'.php?op=categories&cat_modulename='.$cat_modulename.'&parent_id=$4"$3>$5</a>', $cats_link_deep);
			
			$cats_link_deep = " - <a href=\"".$admin_file.".php?op=categories&cat_modulename=$cat_modulename\">"._MAIN_CATS."</a>/$cats_link_deep";
		}
		
		$hooks->add_filter("set_page_title", function() use($cat_modulename, $cats_link_deep){return array("categories" => sprintf(_CATS_ADMIN_PART, $cat_modulename).$cats_link_deep);});
		
		$all_modules_categories = array();
		$all_modules_categories = $hooks->apply_filters("modules_categories_link", $all_modules_categories);
		
		$all_modules_categories_content = '';
		
		foreach($all_modules_categories as $modules_category_key => $modules_category_value)
		{
			$sel = ($cat_modulename == $modules_category_key) ? "selected":"";
			$all_modules_categories_link[] = "<option value=\"".$admin_file.".php?op=categories&cat_modulename=$modules_category_key\" $sel>".$modules_category_value[0]."</option>";
		}
		
		$contents .= "<div align=\"center\" style=\"margin:20px 0;\">[ <a href=\"#\" title=\""._ADD_CAT."\" class=\"editindialog\" data-op=\"categories_admin\" data-catid=\"0\" data-cat-modulename=\"$cat_modulename\">"._ADD_CAT."</a> ]<br /><br />"._SHOW_MODULES_CAT." <select onchange=\"top.location.href=this.options[this.selectedIndex].value\" class=\"styledselect-select\">".implode("\n", $all_modules_categories_link)."</select></div>";
		
		$contents .= OpenAdminTable();
		$contents .= "
			<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>
			<table align=\"center\" class=\"product-table\" width=\"100%\">
			<thead>
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:50px;\">ID</th>
				<th class=\"table-header-repeat line-left\">"._TITLE."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\">"._MODULE."</th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:110px;\">"._OPERATION."</th>
			</tr>
			</thead>
			<tbody>";
			
			$total_rows = 0;
			$entries_per_page = 20;
			$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
			$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
			$link_to = "".$admin_file.".php?op=categories&cat_modulename=$cat_modulename";
						
			$result = $db->query("
				SELECT c.*, 
				(SELECT COUNT(c1.catid) FROM ".CATEGORIES_TABLE." AS c1 WHERE c1.parent_id = ? AND c1.module =?) as total_rows, 
				(SELECT COUNT(c2.catid) FROM ".CATEGORIES_TABLE." AS c2 WHERE c2.module =? AND c2.parent_id = c.catid) as sub_cats
				FROM ".CATEGORIES_TABLE." AS c  
				WHERE c.parent_id = ? AND c.module =? AND c.type != 1
				ORDER BY c.catid DESC LIMIT ?, ?
			", [$parent_id, $cat_modulename, $cat_modulename, $parent_id, $cat_modulename, $start_at, $entries_per_page]);

			if($db->count() > 0)
			{
				foreach($result as $row)
				{
					if($total_rows == 0)
						$total_rows = intval($row['total_rows']);
					
					$catid = intval($row['catid']);
					$type = intval($row['type']);
					$parent_id = intval($row['parent_id']);			
					$module = filter($row['module'], "nohtml");
					$catname = filter($row['catname'], "nohtml");
					$catname_url = filter(sanitize(str2url($row['catname'])), "nohtml");
					$catimage = filter($row['catimage'], "nohtml");
					$cattext = filter(category_lang_text($row['cattext']), "nohtml");
					$catdesc = stripslashes($row['catdesc']);
					
					$cat_title = sanitize(filter(implode("/", array_reverse(get_parent_names($catid, $nuke_categories_cacheData[$cat_modulename], "parent_id", "catname_url"))), "nohtml"), array("/"));
					
					$cat_link = category_link($module, $cat_title, $attrs=array(), 3);
					$cat_link = end($cat_link);
					$module_link_to = $admin_file.".php?op=categories&cat_modulename=$module&parent_id=$catid";
					$sub_cats = (intval($row['sub_cats']) > 0) ? " <span style=\"background:#ccc;border-radius:5px;padding:2px 5px;display:inline-block;\">".intval($row['sub_cats'])."</span>":'';	
					
					$contents .= "<tr>
						<td align=\"center\">$catid</td>
						<td>".((intval($row['sub_cats']) > 0) ? "<a href=\"$module_link_to\">$cattext</a>":"$cattext")."$sub_cats</td>
						<td align=\"center\">$module</td>
						<td align=\"center\">
							<a href=\"#\" title=\""._EDIT."\" class=\"table-icon icon-1 info-tooltip editindialog\" data-op=\"categories_admin\" data-catid=\"$catid\" data-cat-modulename=\"$module\"></a>
							".(($type != 1) ? "<a href=\"".$admin_file.".php?op=categories_delete&catid=$catid&cat_modulename=$module&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\"></a>":"")."
							<a href=\"$cat_link\" title=\""._SHOW."\" class=\"table-icon icon-7 info-tooltip\"></a>
						</td>
						</tr>";
				}
			}
			$contents .= "
			</tbody>
		</table>
		<div id=\"pagination\" class=\"pagination\">";
		$contents .= admin_pagination($total_rows, $entries_per_page, $current_page, $link_to);
		$contents .= "</div>
		<div id=\"categories-dialog\"></div>
		<script>
			$(\"#categories_form\").validate();
			$(\".editindialog\").click(function(e)
			{
				e.preventDefault();
				var catid = $(this).data('catid');
				var cat_modulename = $(this).data('cat-modulename');
				var op = $(this).data('op');
				$.ajax({
					type : 'post',
					url : '".$admin_file.".php',
					data : {'op' : op, 'catid' : catid, 'cat_modulename' : cat_modulename, csrf_token : pn_csrf_token},
					success : function(responseText){
						$(\"#categories-dialog\").html(responseText);

						$(\"#categories-dialog\").dialog({
							title: '"._CATS_EDIT."',
							resizable: false,
							height: 350,
							width: 800,
							modal: true,
							closeOnEscape: true,
							close: function(event, ui)
							{
								$(this).dialog('destroy');
								$(\"#categories-dialog\").html('');
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

	function categories_admin($catid=0, $update='', $cat_fields='', $cat_modulename='Articles')
	{
		global $db, $admin_file, $nuke_configs;

		$catid = intval($catid);
		
		$nuke_categories_cacheData = get_cache_file_contents('nuke_categories', false, true);
		$module = ($catid > 0) ? $nuke_categories_cacheData[$cat_modulename][$catid]['module']:$cat_modulename;
		$catname = ($catid > 0) ? $nuke_categories_cacheData[$cat_modulename][$catid]['catname']:"";
		$catimage = ($catid > 0) ? $nuke_categories_cacheData[$cat_modulename][$catid]['catimage']:"";
		$cattext = ($catid > 0) ? category_lang_text($nuke_categories_cacheData[$cat_modulename][$catid]['cattext']):"";
		$catdesc = ($catid > 0) ? category_lang_text($nuke_categories_cacheData[$cat_modulename][$catid]['catdesc']):"";
		$cattext_arr = ($catid > 0) ? phpnuke_unserialize($nuke_categories_cacheData[$cat_modulename][$catid]['cattext']):array();
		$parent_id = ($catid > 0) ? $nuke_categories_cacheData[$cat_modulename][$catid]['parent_id']:"";
		
		if(isset($update) && $update != "" && isset($cat_fields) && is_array($cat_fields) && !empty($cat_fields))
		{
			$cat_fields['cattext'] = (!empty($cat_fields['cattext'])) ? phpnuke_serialize($cat_fields['cattext']):"";
			if($catid > 0)
			{
				if($cat_fields['parent_id'] != $catid)
				{
					
					$db->query("UPDATE ".CATEGORIES_TABLE." SET catname = ?, cattext = ?, catdesc = ?, catimage = ?, parent_id = ? WHERE catid = ?", [$cat_fields['catname'], $cat_fields['cattext'], $cat_fields['catdesc'], $cat_fields['catimage'], $cat_fields['parent_id'], $catid]);
				}
			}
			else
			{
				$query_set = array();
				$result = $db->table(CATEGORIES_TABLE)
								->where("type", 1)
								->where("module", $cat_modulename)
								->select(["catid"]);
				
				if(intval($result->count()) == 0)
				{
					$uncategorized = array();
					$languageslists = get_languages_data('all');
					foreach($languageslists as $language_key => $languageslist)
						if(!empty($languageslist))
							$uncategorized[$language_key] = $languageslist["_".strtoupper($language_key)."_UNCAT"];

					$query_set[] = array($cat_modulename, 1, 'uncategorized', phpnuke_serialize($uncategorized), 'uncategorized', '', '0');
				}
				
				$query_set[] = array($cat_modulename, 0, $cat_fields['catname'], $cat_fields['cattext'], $cat_fields['catdesc'], $cat_fields['catimage'], $cat_fields['parent_id']);

				$db->table(CATEGORIES_TABLE)
					->multiinsert(
						['module', 'type', 'catname', 'cattext', 'catdesc', 'catimage', 'parent_id'],
						$query_set
					);
			}
			
			cache_system("nuke_categories");
			add_log(sprintf(_CATS_ADD_EDIT_LOG, (($catid > 0) ? _EDIT:_ADD), $catname), 1);
			header("location: ".$admin_file.".php?op=categories&cat_modulename=$module");
			die();
		}
		
		if(isset($nuke_categories_cacheData[$module]))
		{
			foreach($nuke_categories_cacheData[$module] as $rcatid => $nuke_categories_data)
			{
				if($nuke_categories_data['module'] != $module)
				{
					$nuke_categories_cacheData[$module][$rcatid] = null;
					unset($nuke_categories_cacheData[$module][$rcatid]);
				}
			}
			$nuke_categories_cacheData[$module] = array_filter($nuke_categories_cacheData[$module]);
			
			$categories = new categories_list($nuke_categories_cacheData[$module]);
			array_children_ids($nuke_categories_cacheData[$module], $categories->exept_categories, $catid, 'parent_id');

			$categories->categories_list();
			asort($categories->result);
		}
		$languageslists = get_dir_list('language', 'files');
		$content="
			<form action=\"".$admin_file.".php\" method=\"post\" id=\"categories_form\">
			<table align=\"center\" border=\"0\" width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<th style=\"width:150px;\">"._TITLE."</th>
					<td><input name=\"cat_fields[catname]\" value=\"$catname\" class=\"inp-form\" size=\"40\" type=\"text\" required /></td>
				</tr>";
			
				if($nuke_configs['multilingual'] != 1)
				{
					$content .= "<tr>
					<th>"._DISPLAY_NAME."</th>
					<td><input name=\"cat_fields[cattext]\" value=\"$cattext\" class=\"inp-form\" size=\"40\" type=\"text\" required ></td>
				</tr>";
				}
				else
				{
					foreach($languageslists as $languageslist)
					{
						if($languageslist != "")
						{
							if($languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
							$languageslist = str_replace(".php", "", $languageslist);
							$content .= "
							<tr>
								<th style=\"width:160px;\">"._TITLE_INLANG." : ".ucfirst($languageslist)."</th>
								<td>
									<input class=\"inp-form\" required name=\"cat_fields[cattext][$languageslist]\" type=\"text\" value=\"".(isset($cattext_arr[$languageslist]) ? $cattext_arr[$languageslist]:"")."\">
								</td>
							</tr>";
						}
					}
				}
				$content .="
				<tr>
					<th>"._CAT_DESC."</th>
					<td>
						<input name=\"cat_fields[catdesc]\" size=\"60\" type=\"text\" class=\"inp-form\" value=\"$catdesc\">
					</td>
				</tr>
				<tr>
					<th>"._CAT_IMG_LINK."</th>
					<td>
						<input name=\"cat_fields[catimage]\" size=\"60\" type=\"text\" class=\"inp-form-ltr\" value=\"$catimage\">
					</td>
				</tr>
				<tr>
					<th>"._SUB_CAT."</th>
					<td>
						<select name=\"cat_fields[parent_id]\" class=\"styledselect-select\" style=\"width:100%\"><option value=\"0\">"._MAIN_CAT."</option>";
						if(isset($nuke_categories_cacheData[$module]))
						{
							foreach($categories->result as $pcatid => $pcatname)
							{
								if($pcatname == 'uncategorized')
									continue;
								if($pcatid == $catid)
									continue;
								$sel = ($pcatid == $parent_id) ? "selected":"";
								$content .= "<option value=\"$pcatid\" $sel>$pcatname</option>";
							}
						}
						$content .= "</select>
					</td>
				</tr>
				<tr>
					<td colspan=\"2\" align=\"center\">
					<input type=\"submit\" value=\""._UPDATE."\" name=\"update\" class=\"form-submit\">
					</td>
				</tr>
			</table><input type=\"hidden\" name=\"op\" value=\"categories_admin\"><input type=\"hidden\" name=\"catid\" value=\"$catid\"><input type=\"hidden\" name=\"cat_modulename\" value=\"$cat_modulename\"><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> </form>";
			$content .=  jquery_codes_load('',true);
			die($content);
	}

	function categories_delete($catid, $cat_modulename='Articles')
	{
		csrfProtector::authorisePost(true);
		global $db, $admin_file, $nuke_configs, $hooks;
		
		$catid = intval($catid);
		
		$nuke_categories_cacheData = get_cache_file_contents('nuke_categories', false, true);
		
		if($nuke_categories_cacheData[$cat_modulename][$catid]['type'] == 1)
		{
			header("location: ".$admin_file.".php?op=categories");
			die();
		}
		
		$module = $nuke_categories_cacheData[$cat_modulename][$catid]['module'];
		
		$result = $db->table(CATEGORIES_TABLE)
						->where("type", 1)
						->where("module", $module)
						->select(["catid"])
						->first();
		
		$uncategorized_catid = intval($result['catid']);
		
		$parent_id = $nuke_categories_cacheData[$cat_modulename][$catid]['parent_id'];
		
		$db->table(CATEGORIES_TABLE)
			->where("parent_id", $catid)
			->update([
				"parent_id" => $parent_id
			]);
		
		$categories_delete_data = array();
		$categories_delete_data = $hooks->apply_filters("categories_delete_data", $categories_delete_data, $module);
		
		$hooks->do_action("categories_delete_data_before", $module);
		
		if(isset($categories_delete_data[$module]))
		{
			$col_cats_query = array();
			if(is_array($categories_delete_data[$module]['col_cats']))
			{
				foreach($categories_delete_data[$module]['col_cats'] as $key => $col_cats)
				{
					$col_cats_query[] = "$col_cats";
					$col_cats_query_where[] = "FIND_IN_SET($catid, $col_cats)";
				}
			}
			
			$result = $db->query("SELECT 
				".$categories_delete_data[$module]['col_id']." as id, 
				".implode(",", $col_cats_query)."
				from ".$categories_delete_data[$module]['table']." 
				WHERE (".implode(" OR ", $col_cats_query_where).")".(($categories_delete_data[$module]['where'] != '') ? " AND ".$categories_delete_data[$module]['where']."":"")." 
				ORDER BY ".$categories_delete_data[$module]['col_id']." ASC
			");
			
			$query_sets = array();
			$query_text = array();
			$query_values = array();
			$ids = array();
			$update = false;
			if($db->count() > 0)
			{
				foreach($result as $row)
				{
					$id = intval($row['id']);
					foreach($col_cats_query as $col_cat)
					{
						$cats = $row[$col_cat];
						$cats = explode(",", $cats);
						if(in_array($catid, $cats))
						{
							$cats = remove_id_in_array($catid, $cats);
							$cats = implode(",",$cats);
							$cats = ($cats == "") ? $uncategorized_catid:$cats;
							$query_sets[$col_cat][] = "WHEN ".$categories_delete_data[$module]['col_id']." = '$id' THEN ?";
							$query_values[] = $cats;
							$update = true;
						}
						$ids[] = "'$id'";
					}
				}
			}
			
			if(!empty($query_sets))
			{
				foreach($query_sets as $query_key => $query_set)
				{
					$query_set = "(CASE ".implode("\n", $query_set)."\nEND)";
					$query_text[] = "$query_key = $query_set";
				}
			}
			
			$query_text = implode(",", $query_text);
			//$ids = implode(",", $ids);
			if($update)
				$db->query("UPDATE ".$categories_delete_data[$module]['table']." SET $query_text WHERE ".$categories_delete_data[$module]['col_id']." IN (?)", array_merge($query_values, $ids));
		}
		$db->table(CATEGORIES_TABLE)
			->where("catid", $catid)
			->delete();

		phpnuke_auto_increment(CATEGORIES_TABLE);
		
		cache_system("nuke_categories");
		if(isset($categories_delete_data[$module]['recache']) && $categories_delete_data[$module]['recache'] != '')
			cache_system($categories_delete_data[$module]['recache']);
			
		phpnuke_db_error();
		add_log(_CAT_DELETE_LOG, 1);
		header("location: ".$admin_file.".php?op=categories");
		die();
	}
	
	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$cat_modulename = (isset($cat_modulename)) ? filter($cat_modulename, "nohtml"):'Articles';
	$update = (isset($update)) ? filter($update, "nohtml"):'';
	$cat_fields = (isset($cat_fields)) ? $cat_fields:array();
	$show_header = (isset($show_header)) ? intval($show_header):0;
	$parent_id = (isset($parent_id)) ? intval($parent_id):0;
	$catid = (isset($catid)) ? intval($catid):0;
	
	switch ($op) {
		case "categories":
			categories($cat_modulename, $parent_id);
		break;
		case "categories_admin":
			categories_admin($catid, $update, $cat_fields, $cat_modulename);
		break;
		case "categories_delete":
			categories_delete($catid, $cat_modulename);
		break;
	}
}
else
{
	header("location: ".$admin_file.".php");
}
?>