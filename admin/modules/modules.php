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

global $db, $admin_file;

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

if (check_admin_permission($filename))
{
	function modules()
	{
		global  $db, $hooks, $nuke_configs, $admin_file;
		$result = $db->table(MODULES_TABLE)
						->order_by(['mid' => 'ASC'])
						->select();
		
		$nuke_modules_cacheData = array();
		if($db->count() > 0)
		{
			foreach($result as $row)
			{
				$mid = $row['mid'];
				unset($row['mid']);
				$nuke_modules_cacheData[$mid] = $row;
			}
		}
		
		$hooks->add_filter("set_page_title", function(){return array("modules" => _MODULES_ADMIN);});
		$contents = '';
		$contents .= GraphicAdmin();
		
		$moduleslist = get_modules_list();		
		
		$modules_changed = false;
		
		//add new modules in database
		$query_sets = array();
		foreach($moduleslist as $module_name){
			$is_new = true;
			foreach($nuke_modules_cacheData as $mid => $module_info)
			{
				if($module_info['title'] == $module_name)
				{
					$is_new = false;
					break;
				}
			}
			if($is_new)
			{
				$query_sets[] = array($module_name, '', '0', '', '', '0');
				
			}
		}
		
		if(is_array($query_sets) && !empty($query_sets))
		{
			$result = $db->table(MODULES_TABLE)
							->multiinsert(
								['title', 'lang_titles', 'active', 'mod_permissions', 'admins', 'all_blocks'],
								$query_sets
							);
			if($result)
				$modules_changed = true;
		}
		
		unset($query_sets);
		
		//remove deleted modules from database
		$query_sets_mids = array();
		foreach($nuke_modules_cacheData as $mid => $module_info)
		{
			$must_removed = true;
			foreach($moduleslist as $module_name){
				if($module_name == $module_info['title'])
				{
					$must_removed = false;
					break;
				}
			}
			
			if($must_removed)
			{
				$query_sets_mids[] = $mid;
			}			
		}
		
		if(is_array($query_sets_mids) && !empty($query_sets_mids))
		{
			$result = $db->table(MODULES_TABLE)
							->in('mid', $query_sets_mids)
							->delete();
			if($result)
				$modules_changed = true;
		}
		unset($query_sets_mids);
		
		if($modules_changed)
		{
			cache_system('nuke_modules');
			$nuke_modules_cacheData = get_cache_file_contents('nuke_modules');
		}
		
		$contents .= OpenAdminTable();

		$contents .= "<div class=\"text-center\"><font class='option'>"._MODULES."</font><br><br>";
		$contents .= "<font class='content'>"._MODULESACTIVATION."</font><br><br>";
		$contents .= ""._MODULEHOMENOTE."<br><br><br><br></div>";
		
		$contents .="
		<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"product-table\">
		<tr>
			<th class=\"table-header-repeat line-left\">"._TITLE."</th>
			<th class=\"table-header-repeat line-left\">"._CUSTOMTITLE."</th>
			<th class=\"table-header-repeat line-left\">"._STATUS."</th>
			<th class=\"table-header-repeat\">"._OPERATION."</th>
		</tr>
		<tr><td colspan=\"6\" align='center'><b>"._ACTIVE_MODULES."</b></td></tr>";
		$i = 0;
		foreach($nuke_modules_cacheData as $mid => $module_info)
		{
			if($module_info['active'] == 0 OR $module_info['in_menu'] == 0)
				continue;
			
			$mtitle = filter($module_info['title'], "nohtml");
			$lang_titles = ($module_info['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($module_info['lang_titles'])):"";
			if($lang_titles != "" && $nuke_configs['multilingual'] == 1)
			{
				$lang_title = filter($lang_titles[$nuke_configs['currentlang']], "nohtml");
			}
			else
			{
				$lang_title = $mtitle;
			}
			
			$active = intval($module_info['active']);
			$mod_permissions = ($module_info['mod_permissions'] != "") ? explode(",", $module_info['mod_permissions']):'';
			$in_menu = intval($module_info['in_menu']);

			if ($active == 1) {
				$active = "<i>"._ACTIVE."</i>";
				$change = _DEACTIVATE;
				$act = 0;
			} else {
				$active = "<i>"._INACTIVE."</i>";
				$change = _ACTIVATE;
				$act = 1;
			}
			
			if ($module_info['main_module'] == 1) {
				$mtitle = "<b>$mtitle</b>";
				$lang_title = "<b>$lang_title</b>";
				$active = "<b>("._INHOME.")</b>";
				$puthome = "<i>"._PUTINHOME."</i>";
				$change_status = "<i>$change</i>";
			} else {
				$puthome = "<a href='".$admin_file.".php?op=home_module&mid=$mid&csrf_token="._PN_CSRF_TOKEN."' onclick=\"retirn confirm('"._SURETOCHANGEMOD."');\">"._PUTINHOME."</a>";
				$change_status = "<a href='".$admin_file.".php?op=module_status&mid=$mid&active=$act&csrf_token="._PN_CSRF_TOKEN."'>$change</a>";
			}
			
			if($i%2 == 0){
				$rowclass = " class=\"alternate-row\"";
			}else{
				$rowclass="";
			}
			
			$contents .= "<tr$rowclass>
			<td align='center'>&nbsp;$mtitle</td>
			<td align='center'>$lang_title</td>
			<td align='center'>$active</td>
			<td align='center'>[ <a href='".$admin_file.".php?op=module_edit&mid=$mid'>"._EDIT."</a> | $change_status | $puthome ]</td>
			</tr>";
			$i++;
		}
		
		$contents .= "<tr>
		<td align='center' colspan=\"6\"><font class='title'><b>"._INVISIBLEMODULES."</b></font></td></tr>";
		$i=0;
		foreach($nuke_modules_cacheData as $mid => $module_info)
		{
			if($module_info['active'] == 0 OR $module_info['in_menu'] == 1)
				continue;
			
			$mtitle = filter($module_info['title'], "nohtml");
			$lang_titles = ($module_info['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($module_info['lang_titles'])):"";
			if($lang_titles != "" && $nuke_configs['multilingual'] == 1)
			{
				$lang_title = filter($lang_titles[$nuke_configs['currentlang']], "nohtml");
			}
			else
			{
				$lang_title = $mtitle;
			}
			
			$active = intval($module_info['active']);
			$in_menu = intval($module_info['in_menu']);

			if ($active == 1) {
				$active = "<i>"._ACTIVE."</i>";
				$change = _DEACTIVATE;
				$act = 0;
			} else {
				$active = "<i>"._INACTIVE."</i>";
				$change = _ACTIVATE;
				$act = 1;
			}
			
			if ($module_info['main_module'] == 1) {
				$mtitle = "<b>$mtitle</b>";
				$lang_title = "<b>$lang_title</b>";
				$active = "<b>("._INHOME.")</b>";
				$puthome = "<i>"._PUTINHOME."</i>";
				$change_status = "<i>$change</i>";
			} else {
				$puthome = "<a href='".$admin_file.".php?op=home_module&mid=$mid&csrf_token="._PN_CSRF_TOKEN."' onclick=\"retirn confirm('"._SURETOCHANGEMOD."');\">"._PUTINHOME."</a>";
				$change_status = "<a href='".$admin_file.".php?op=module_status&mid=$mid&active=$act&csrf_token="._PN_CSRF_TOKEN."'>$change</a>";
			}
			
			if($i%2 == 0){
				$rowclass = " class=\"alternate-row\"";
			}else{
				$rowclass="";
			}
			
			$contents .= "<tr$rowclass>
			<td align='center'>&nbsp;$mtitle</td>
			<td align='center'>$lang_title</td>
			<td align='center'>$active</td>
			<td align='center'>[ <a href='".$admin_file.".php?op=module_edit&mid=$mid'>"._EDIT."</a> | $change_status | $puthome ]</td>
			</tr>";
			$i++;
		}

		$contents .= "<tr>
		<td align='center' colspan=\"6\"><font class='title'><b>"._INACTIVEMODULES."</b></font></td></tr>";
			
		$i=0;
		foreach($nuke_modules_cacheData as $mid => $module_info)
		{
			if($module_info['active'] == 1)
				continue;
			
			$mtitle = filter($module_info['title'], "nohtml");
			$lang_titles = ($module_info['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($module_info['lang_titles'])):"";
			if($lang_titles != "" && $nuke_configs['multilingual'] == 1)
			{
				$lang_title = filter($lang_titles[$nuke_configs['currentlang']], "nohtml");
			}
			else
			{
				$lang_title = $mtitle;
			}
			
			$active = intval($module_info['active']);
			$in_menu = intval($module_info['in_menu']);

			if ($active == 1) {
				$active = "<i>"._ACTIVE."</i>";
				$change = _DEACTIVATE;
				$act = 0;
			} else {
				$active = "<i>"._INACTIVE."</i>";
				$change = _ACTIVATE;
				$act = 1;
			}
			
			if ($module_info['main_module'] == 1) {
				$mtitle = "<b>$mtitle</b>";
				$lang_title = "<b>$lang_title</b>";
				$active = "<b>("._INHOME.")</b>";
				$puthome = "<i>"._PUTINHOME."</i>";
				$change_status = "<i>$change</i>";
			} else {
				$puthome = "<a href='".$admin_file.".php?op=home_module&mid=$mid&csrf_token="._PN_CSRF_TOKEN."' onclick=\"retirn confirm('"._SURETOCHANGEMOD."');\">"._PUTINHOME."</a>";
				$change_status = "<a href='".$admin_file.".php?op=module_status&mid=$mid&active=$act&csrf_token="._PN_CSRF_TOKEN."'>$change</a>";
			}
			
			if($i%2 == 0){
				$rowclass = " class=\"alternate-row\"";
			}else{
				$rowclass="";
			}
			
			$contents .= "<tr$rowclass>
			<td align='center'>&nbsp;$mtitle</td>
			<td align='center'>$lang_title</td>
			<td align='center'>$active</td>
			<td align='center'>[ <a href='".$admin_file.".php?op=module_edit&mid=$mid'>"._EDIT."</a> | $change_status | $puthome ]</td>
			</tr>";
			$i++;
		}
		$contents .= "</table>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function home_module($mid)
	{
		csrfProtector::authorisePost(true);
		global  $admin_file, $db, $nuke_configs, $hooks;
		$mid = intval($mid);
		
		$db->table(MODULES_TABLE)
			->where('mid', '!=', $mid)
			->update([
				'main_module' => 0
			]);
			
		$db->table(MODULES_TABLE)
			->where('mid', $mid)
			->update([
				'active' => 1,
				'main_module' => 1,
				'mod_permissions' => '',
			]);
		
		$hooks->do_action("home_module", $mid);
		cache_system('nuke_modules');
		@Header("Location: ".$admin_file.".php?op=modules");
	}

	function module_status($mid, $active)
	{
		csrfProtector::authorisePost(true);
		global  $admin_file, $db, $nuke_configs, $hooks;
		$mid = intval($mid);
		
		$result = $db->table(MODULES_TABLE)
			->where('mid', $mid)
			->first(['title', 'active']);
		
		$active = intval($active);
		
		$result = $db->table(MODULES_TABLE)
			->where('mid', $mid)
			->update(['active' => $active]);
		
		$hooks->do_action("module_status", $mid, $active);
		cache_system('nuke_modules');
		add_log(sprintf(_ACTIVATEMODULELOG, (($active == 1) ? _ACTIVATE:_DEACTIVATE), $result['title']), 1);
		@Header("Location: ".$admin_file.".php?op=module_edit&mid=$mid");
	}

	function module_edit($mid, $submit='', $module_data=array())
	{
		global  $hooks, $admin_file, $db, $nuke_configs;
		$mid = intval($mid);

		$modules_row = $db->table(MODULES_TABLE)
			->where('mid', $mid)
			->first();
		
		$contents = '';
		if(isset($submit) && $submit != '' && is_array($module_data) && !empty($module_data))
		{
			$module_data = $hooks->apply_filters("module_edit_save", $module_data, $mid);
			$mod_permissions = (is_array($module_data['mod_permissions']) && !empty($module_data['mod_permissions'])) ? implode(",", $module_data['mod_permissions']):"";
			$in_menu = intval($module_data['in_menu']);
			$all_blocks = intval($module_data['all_blocks']);		
			$activemodule = intval($module_data['activemodule']);		
			
			if($nuke_configs['multilingual'] == 1){
				$lang_titles = (!empty($module_data['lang_titles'])) ? phpnuke_serialize($module_data['lang_titles']):"";
			}else{
				$lang_titles = filter($module_data['lang_titles'], "nohtml", 1);
			}
			
			$db->table(MODULES_TABLE)
				->where('mid', $mid)
				->update([
					'lang_titles' => $lang_titles,
					'mod_permissions' => $mod_permissions,
					'in_menu' => $in_menu,
					'all_blocks' => $all_blocks,
					'active' => $activemodule,
				]);
			$hooks->apply_filters("module_edit_after", $module_data, $mid);
			cache_system('nuke_modules');
			add_log(sprintf(_EDITMODULETATA, $modules_row['title']), 1);
			header("Location: ".$admin_file.".php?op=module_edit&mid=$mid");
			die();
		}

		$pagetitle = _MODULES_ADMIN." - ".sprintf(_EDITMODULETATA, $modules_row['title']);
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("module_edit" => $pagetitle);});
			
		if ($modules_row['main_module'] == 1) { $a = " - "._INHOME.""; } else { $a = ""; }
		
		$insel1 = ($modules_row['in_menu'] == 1) ? "selected":"";
		$insel2 = ($modules_row['in_menu'] == 0) ? "selected":"";
		
		$lang_titles = ($modules_row['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($modules_row['lang_titles'])):array();
		$mod_permissions = ($modules_row['mod_permissions'] != "") ? explode(",", $modules_row['mod_permissions']):array();

		$nuke_modules_boxes_parts = array();
		$module_parts_list = array();
		
		$nuke_modules_boxes_parts = $hooks->apply_filters("modules_boxes_parts", $nuke_modules_boxes_parts);
		
		if(isset($nuke_modules_boxes_parts[$modules_row['title']]) && is_array($nuke_modules_boxes_parts[$modules_row['title']]) && !empty($nuke_modules_boxes_parts[$modules_row['title']]))
		{
			foreach($nuke_modules_boxes_parts[$modules_row['title']] as $module_part => $module_part_title)
			{
				$module_part_title = defined($module_part_title) ? constant($module_part_title):$module_part_title;
				$module_parts_list[] = "<a href=\"".$admin_file.".php?op=module_edit_boxess&mid=$mid&module_part=$module_part\">$module_part_title</a>";
			}
		}
				
		$module_parts_list = "[ ".implode(" | ", $module_parts_list)." ]";
		
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		
		$contents .= "<div align=\"center\"><form action='".$admin_file.".php' method='post'>
		<table align='center' border='0' cellpadding='2' width=\"100%\" cellspacing='2' class=\"id-form product-table no-border\">
		<tr><th align='center' colspan='2' class='option' style=\"text-align:center\"><b>"._MODULES_EDIT."</b><br>(".$modules_row['title']."$a)</th></tr>";
		//////////////////////
		if($nuke_configs['multilingual'] != 1)
		{
			$contents .= "<tr><th style=\"width:210px;\">"._TITLE.":</th><td><input class=\"inp-form\" type=\"text\" name=\"module_data[lang_titles]\" size=\"30\" maxlength=\"60\" value=\"".$modules_row['lang_titles']."\"></td></tr>";
		}
		else
		{			
			$languageslists = get_dir_list('language', 'files');
			foreach($languageslists as $languageslist)
			{
				if($languageslist != "")
				{
					if($languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
					$languageslist = str_replace(".php", "", $languageslist);
					$contents .= "<tr><th>"._TITLE_INLANG." : ".ucfirst($languageslist)."</th><td> ";
					$contents .= "<input class=\"inp-form\" type=\"text\" name=\"module_data[lang_titles][$languageslist]\" size=\"30\" maxlength=\"60\" style=\"margin-bottom:5px;\" value=\"".(isset($lang_titles[$languageslist]) ? $lang_titles[$languageslist]:"")."\">";
				}
			}
			$contents .= "</td></tr>";
		}
		$contents .= "<tr><th style=\"width:250px;\">"._SHOWINMENU."</th><td><select name='module_data[in_menu]' class=\"styledselect-select\">
		<option value='1' $insel1> "._YES."</option><option value='0' $insel2> "._NO."</option>
		</select></td></tr>
		<tr><th>"._SHOWN_FOR."</th><td>";
		
		if ($modules_row['main_module'] == 1)
		{
			$contents .= "<b>"._MAINMODULEALARM."</b>
			<input name=\"module_data[mod_permissions][]\" type=\"hidden\" value=\"0\" />";
		}
		else
		{
			$permissions = get_groups_permissions();
			foreach($permissions as $key => $premission_name)
			{
				$checked = (in_array($key, $mod_permissions)) ? "checked":'';
				
				$contents .= "<input name=\"module_data[mod_permissions][]\" data-label=\"$premission_name\" type=\"checkbox\" class=\"styled\" id=\"module-permission_$key\" value=\"$key\" $checked />&nbsp; ";
			}
		}
		$contents .= "
		</td></tr>";
		$contents .= "<tr>
			<td><b>"._BLOCKS_LAYOUT."</b></td>
			<td>
				"._BLOCKS_LAYOUT_DESC."<br /><br />
				$module_parts_list
			</td>
		</tr>";
		if ($modules_row['main_module'] == 1)
		{
			$contents .= "<input type='hidden' name='module_data[all_blocks]' value='1'>";
			$contents .= "<input type='hidden' name='module_data[activemodule]' value='1'>";
		}
		else
		{
		$contents .= "<tr><th>"._SHOWMAINPAGEBLOCKS."</th><td>";
		$sel1 = ($modules_row['all_blocks'] == 0) ? "checked":"";
		$sel2 = ($modules_row['all_blocks'] == 1) ? "checked":"";

		$contents .= "<input data-label=\""._YES."\" type=\"radio\" class=\"styled\" name=\"module_data[all_blocks]\" value=\"1\" $sel2 />
		<input data-label=\""._NO."\" type=\"radio\" class=\"styled\" name=\"module_data[all_blocks]\" value=\"0\" $sel1 />
		</td></tr>
		<tr><th>"._ACTIVATE."</th><td>";

		$sel1 = ($modules_row['active'] == 0) ? "checked":"";
		$sel2 = ($modules_row['active'] == 1) ? "checked":"";
		
		$contents .= "<input data-label=\""._YES."\" type=\"radio\" class=\"styled\" name=\"module_data[activemodule]\" value=\"1\" $sel2 />
		<input data-label=\""._NO."\" type=\"radio\" class=\"styled\" name=\"module_data[activemodule]\" value=\"0\" $sel1 />
		</td></tr>";
		}
		$contents .= "<tr><td align='center' colspan='2'><input type='submit' name='submit' value='submit' class=\"form-submit\"></td></tr>
		</table>
		<input type='hidden' name='mid' value='$mid'>
		<input type='hidden' name='op' value='module_edit'>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form></div>";
		$contents = $hooks->apply_filters("module_edit_form", $contents);
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	function module_edit_boxess($mid, $module_part='', $submit='', $module_boxes='', $special_page='')
	{
		global  $hooks, $admin_file, $db, $nuke_configs;
		$mid = intval($mid);	
		
		$modules_row = $db->table(MODULES_TABLE)
			->where('mid', $mid)
			->first();
		
		$all_module_boxes = $modules_row['module_boxes'];
		
		if($special_page != '')
		{
			$special_page_arr = explode("_", $special_page);
			$mresult = $db->table(POSTS_META_TABLE)
							->where('meta_part', 'module_boxes')
							->where('post_id', $special_page_arr[1])
							->where('meta_key', $special_page_arr[0])
							->select();
			if($mresult->count() > 0)
			{
				$row = $mresult->results()[0];
				$all_module_boxes = $row['meta_value'];
			}
		}
		
		$all_module_boxes = ($all_module_boxes != '') ? phpnuke_unserialize(stripslashes($all_module_boxes)):array();
		$module_part = (isset($module_part) && $module_part != '') ? $module_part:"index";

		if(isset($submit) && $submit != '' && isset($module_boxes) && $module_boxes != '')
		{
			$module_part = str_replace(".php","", $module_part);
			$all_module_boxes[$module_part] = $module_boxes;
			
			$module_boxes = $hooks->apply_filters("module_edit_boxess", $module_boxes, $mid, $module_part, $special_page);
			if($special_page != '')
			{
				$special_page_arr = explode("_", $special_page);
				$mresult = $db->table(POSTS_META_TABLE)
								->where('meta_part', 'module_boxes')
								->where('post_id', $special_page_arr[1])
								->where('meta_key', $special_page_arr[0])
								->select();

				if($mresult->count() > 0)
				{
					$db->table(POSTS_META_TABLE)
						->where('meta_part', 'module_boxes')
						->where('post_id', $special_page_arr[1])
						->where('meta_key', $special_page_arr[0])
						->update([
							'meta_value' => phpnuke_serialize($all_module_boxes),
						]);
				}
				else
				{
					$db->table(POSTS_META_TABLE)
						->insert([
							'meta_part' => 'module_boxes',
							'post_id' => $special_page_arr[1],
							'meta_key' => $special_page_arr[0],
							'meta_value' => phpnuke_serialize($all_module_boxes),
						]);
				}
			}
			else
			{
				$db->table(MODULES_TABLE)
					->where('mid', $mid)
					->update([
						'module_boxes' => phpnuke_serialize($all_module_boxes),
					]);
			}
			
			$hooks->do_action("module_edit_boxess_after", $module_boxes, $mid, $module_part, $special_page);
			
			cache_system('nuke_modules');
			add_log(sprintf(_EDITMODULEBOXESLAYOUTS, $modules_row['title']), 1);
			Header("Location: ".$admin_file.".php?op=module_edit&mid=$mid");
			die();
		}		
			
		$nuke_blocks_cacheData = get_cache_file_contents('nuke_blocks');
		$nuke_modules_boxes_cacheData = get_cache_file_contents('nuke_modules_boxes');
		
		$pagetitle = _MODULES_ADMIN." - ".sprintf(_EDITMODULEBOXESLAYOUTS, $modules_row['title']);
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("module_edit_boxess" => $pagetitle);});

		$module_boxes_def = (isset($all_module_boxes[$module_part])) ? $all_module_boxes[$module_part]:'';
		
		$all_module_boxes = (isset($all_module_boxes[$module_part])) ? explode("|", $all_module_boxes[$module_part]):array();
		$all_boxes = array();
		foreach($all_module_boxes as $module_boxes)
		{
			$module_boxes = explode(",", $module_boxes);
			foreach($module_boxes as $box)
			{
				$all_boxes[] = $box;
			}
		}
		
		$all_boxes = $hooks->apply_filters("module_edit_all_boxess", $all_boxes, $module_boxes, $mid, $module_part, $special_page);
		
		$all_boxes = array_filter($all_boxes);
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		
		$contents .= "<div align=\"center\"><form action='".$admin_file.".php' method='post'>
		<table align='center' border='0' cellpadding='2' width=\"100%\" cellspacing='2' class=\"id-form product-table no-border\">
		<tr><th align='center' class='option' style=\"text-align:center\"><b>"._CHANGEMODEDIT."</b><br>(".$modules_row['title']." - "._PART." $module_part)<br /><br /><a href=\"".$admin_file.".php?op=module_edit&mid=$mid\">"._BACKTOMODULEEDITPAGE."</a></th></tr>";
		//////////////////////

		$contents .= "<tr><td>
		<div id=\"blocks-boxes\">
		<div id=\"blocks-containment\">
			<div class=\"blocks-column-full\">
				<div class=\"blocks-box-header\">
					"._EXISTS_BLOCKS_BOXES."
				</div>
				<ul class=\"blocks-sortable-list\">";
					if(isset($nuke_blocks_cacheData['blocks_boxes']) && !empty($nuke_blocks_cacheData['blocks_boxes']))
					{
						foreach($nuke_blocks_cacheData['blocks_boxes'] as $box_id => $box_id_info)
						{
							if(in_array($box_id, $all_boxes)) continue;
							$contents .= "<li class=\"blocks-sortable-item\" id=\"$box_id\"><span class=\"block-title\">$box_id</span></li>";
						}
					}
					$contents .= "
				</ul>
				</div>
				<div style=\"clear:both;\"></div>
				<div class=\"blocks-column\" style=\"width:100%;\">
					<table class=\"product-table no-hover\" style=\"width: 100%;\" cellpadding=\"10\">
						<tbody>
							<tr>
								<td valign=\"top\" width=\"100%\" colspan=\"3\">
									<ul class=\"blocks-sortable-list right-box\" style=\"min-height:80px;border:1px dashed #000;\">";
									if(isset($all_module_boxes[4]) && !empty($all_module_boxes[4]))
									{
										$top_full_module_boxes = explode(",", $all_module_boxes[4]);
										foreach($top_full_module_boxes as $top_full_boxes)
										{
											$contents .= "<li class=\"blocks-sortable-item\" id=\"$top_full_boxes\"><span class=\"block-title\">$top_full_boxes</span></li>";
										}
									}
									$contents .= "</ul>
								</td>
							</tr>
							<tr>
								<td valign=\"top\" width=\"33%\">
									<ul class=\"blocks-sortable-list right-box\" style=\"min-height:80px;border:1px dashed #000;\">";
									if(isset($all_module_boxes[0]) && !empty($all_module_boxes[0]))
									{
										$right_module_boxes = explode(",", $all_module_boxes[0]);
										foreach($right_module_boxes as $right_boxes)
										{
											$contents .= "<li class=\"blocks-sortable-item\" id=\"$right_boxes\"><span class=\"block-title\">$right_boxes</span></li>";
										}
									}
									$contents .= "</ul>
								</td>
								<td valign=\"top\" width=\"33%\">
									<ul class=\"blocks-sortable-list center-box\" style=\"min-height:80px;border:1px dashed #000;\">";
									if(isset($all_module_boxes[2]) && !empty($all_module_boxes[2]))
									{
										$top_module_boxes = explode(",", $all_module_boxes[2]);
										foreach($top_module_boxes as $top_boxes)
										{
											$contents .= "<li class=\"blocks-sortable-item\" id=\"$top_boxes\"><span class=\"block-title\">$top_boxes</span></li>";
										}
									}
									$contents .= "<li class=\"blocks-sortable-item ui-state-disabled\" id=\"main-module_content\"><span class=\"block-title\">"._MAIN_MODULE_CONTENTS."</span>
									</li>";
									if(isset($all_module_boxes[3]) && !empty($all_module_boxes[3]))
									{
										$bottom_module_boxes = explode(",", $all_module_boxes[3]);
										foreach($bottom_module_boxes as $bottom_boxes)
										{
											$contents .= "<li class=\"blocks-sortable-item\" id=\"$bottom_boxes\"><span class=\"block-title\">$bottom_boxes</span></li>";
										}
									}
									$contents .= "</ul>
								</td>
								<td valign=\"top\" width=\"33%\">
									<ul class=\"blocks-sortable-list left-box\" style=\"min-height:80px;border:1px dashed #000;\">";
									if(isset($all_module_boxes[1]) && !empty($all_module_boxes[1]))
									{
										$left_module_boxes = explode(",", $all_module_boxes[1]);
										foreach($left_module_boxes as $left_boxes)
										{
											$contents .= "<li class=\"blocks-sortable-item\" id=\"$left_boxes\"><span class=\"block-title\">$left_boxes</span></li>";
										}
									}
									$contents .= "</ul>
								</td>
							</tr>
							<tr>
								<td valign=\"top\" width=\"100%\" colspan=\"3\">
									<ul class=\"blocks-sortable-list right-box\" style=\"min-height:80px;border:1px dashed #000;\">";
									if(isset($all_module_boxes[5]) && !empty($all_module_boxes[5]))
									{
										$bottom_full_module_boxes = explode(",", $all_module_boxes[5]);
										foreach($bottom_full_module_boxes as $bottom_full_boxes)
										{
											$contents .= "<li class=\"blocks-sortable-item\" id=\"$bottom_full_boxes\"><span class=\"block-title\">$bottom_full_boxes</span></li>";
										}
									}
									$contents .= "</ul>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		</td></tr>
		<tr><td align='center'><input type='submit' name='submit' value='submit' class=\"form-submit\"></td></tr>
		</table>
		<input type='hidden' name='module_boxes' id='module_boxes' value='$module_boxes_def'>
		<input type='hidden' name='mid' value='$mid'>
		<input type='hidden' name='module_part' value='$module_part'>
		<input type='hidden' name='special_page' value='$special_page'>
		<input type='hidden' name='op' value='module_edit_boxess'>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form></div>
		<script>
		
			// Example 2.3: Save items automaticly
			var box_id;
			function sortable_handle(){
				$('#blocks-boxes .blocks-sortable-list').sortable({
					connectWith: '#blocks-boxes .blocks-sortable-list',
					placeholder: 'blocks-placeholder',
					containment: '#blocks-containment',
					opacity: 0.75,
					cancel: '.ui-state-disabled',						
					stop: function(){
						var module_boxes = [];
						var columns = [];
						var top_boxes = [];
						var bottom_boxes = [];
						var is_in_top = true;

						$('#blocks-boxes ul.blocks-sortable-list').each(function(){
							columns.push($(this).sortable('toArray'));
						});
						
						var top_full_boxes = columns[1];
						var right_boxes = columns[2];
						var middle_boxes = columns[3];
						var left_boxes = columns[4];
						var bottom_full_boxes = columns[5];
						$.each(middle_boxes, function(index, value){
							if(value != 'main-module_content' && is_in_top)
							{
								top_boxes.push(value);
							}
							else if(value == 'main-module_content' && is_in_top)
							{
								is_in_top = false;
							}
							else
							{
								bottom_boxes.push(value);
							}
						});
						
						module_boxes.push(right_boxes.join(','));
						module_boxes.push(left_boxes.join(','));
						module_boxes.push(top_boxes.join(','));
						module_boxes.push(bottom_boxes.join(','));
						module_boxes.push(top_full_boxes.join(','));
						module_boxes.push(bottom_full_boxes.join(','));
						$(\"#module_boxes\").val(module_boxes.join('|'));
					}
				});
			}
			sortable_handle();

		</script>";
		
		$contents = $hooks->apply_filters("module_edit_boxess_form", $contents, $all_boxes, $module_boxes, $mid, $module_part, $special_page);
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	$mid = (isset($mid)) ? intval($mid):0;
	$in_menu = (isset($in_menu)) ? intval($in_menu):0;
	$mod_permissions = (isset($mod_permissions)) ? $mod_permissions:array();
	$lang_titles = (isset($lang_titles)) ? $lang_titles:array();
	$module_data = (isset($module_data)) ? $module_data:array();
	$all_blocks = (isset($all_blocks)) ? intval($all_blocks):0;
	$activemodule = (isset($activemodule)) ? intval($activemodule):0;
	$module_boxes = (isset($module_boxes)) ? $module_boxes:'';
	$module_part = (isset($module_part)) ? filter($module_part, "nohtml"):'';
	$special_page = (isset($special_page)) ? filter($special_page, "nohtml"):'';
	$submit = (isset($submit)) ? filter($submit, "nohtml"):'';
	$op = (isset($op)) ? filter($op, "nohtml"):'';
	
	switch ($op)
	{

		default:
			modules();
		break;
		
		case "home_module":
			home_module($mid);
		break;
		
		case "module_status":
			module_status($mid, $active);
		break;
		
		case "module_edit":
			module_edit($mid, $submit, $module_data);
		break;
		
		case "module_edit_boxess":
			module_edit_boxess($mid, $module_part, $submit, $module_boxes, $special_page);
		break;
	}
}
else
{
	header("location: ".$admin_file.".php");
}

?>