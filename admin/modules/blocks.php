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

if (check_admin_permission($filename)) {

	/*********************************************************/
	/* Blocks Functions                                      */
	/*********************************************************/

	function add_remove_blocks()
	{
		global $db, $nuke_configs;
		
		$nuke_blocks_cacheData = get_cache_file_contents('nuke_blocks');
		
		$exept_files = array("", ".", "..", ".htaccess","index.html");		
		$default_blocks = get_dir_list("blocks", 'files', false, $exept_files);
		$custom_blocks = get_dir_list("themes/".$nuke_configs['ThemeSel']."/blocks", 'files', false, $exept_files);
		$blockslist = array();
		
		if(!empty($default_blocks))
			$blockslist = array_merge($blockslist, $default_blocks);
		
		if(!empty($custom_blocks))
			$blockslist = array_merge($blockslist, $custom_blocks);
			
		$blockslist = array_filter($blockslist);
		sort($blockslist);

		$blocks_changed = false;
		
		//add new blocks in database
		$query_sets = array();
		
		foreach($blockslist as $block_name)
		{
			$is_new = true;
			if(isset($nuke_blocks_cacheData['blocks']) && !empty($nuke_blocks_cacheData['blocks']))
			{
				foreach($nuke_blocks_cacheData['blocks'] as $bid => $block_data)
				{
					if($block_data['blockfile'] == $block_name)
					{
						$is_new = false;
						break;
					}
				}
			}
			if($is_new)
			{
				$bl = str_replace("block-","",$block_name);
				$bl = str_replace(".php","",$bl);
				$bl = str_replace("_"," ",$bl);
				$query_sets[] = array($bl, $block_name);
			}
		}
		
		if(is_array($query_sets) && !empty($query_sets))
		{
			$insert_db = $db->table(BLOCKS_TABLE)
							->multiinsert(
								['title', 'blockfile'],
								$query_sets
							);
			if($insert_db)
			{
				$blocks_changed = true;
				cache_system('nuke_blocks');
				$nuke_blocks_cacheData = get_cache_file_contents('nuke_blocks');
			}
		}
		
		unset($query_sets);
		$query_sets_bids = array();
		//remove deleted blocks from database
		if(isset($nuke_blocks_cacheData['blocks']) && !empty($nuke_blocks_cacheData['blocks']))
		{
			foreach($nuke_blocks_cacheData['blocks'] as $bid => $block_data)
			{
				if($block_data['blockfile'] == '') continue;
				if(!in_array($block_data['blockfile'], $blockslist))
				{
					$query_sets_bids[] = $bid;
				}
			}
		}
		if(is_array($query_sets_bids) && !empty($query_sets_bids))
		{
			$delete_db = $db->table(BLOCKS_TABLE)
							->in('bid', $query_sets_bids)
							->delete();
			if($delete_db)
			{
				phpnuke_auto_increment(BLOCKS_TABLE);
				$blocks_changed = true;
				cache_system('nuke_blocks');
			}
		}
		
		unset($query_sets_bids);
		
		$nuke_blocks_cacheData = get_cache_file_contents('nuke_blocks');

		return $nuke_blocks_cacheData;
	}
	
	$nuke_blocks_cacheData = add_remove_blocks();
	$nuke_headlines_cacheData = get_cache_file_contents('nuke_headlines');
	
	function updateweight($box_id, $block_id, $bids_weight, $mode, $new_box_title, $new_box_theme_location, $new_box_theme_priority, $new_box_status)
	{
		global $db, $nuke_configs, $nuke_blocks_cacheData, $block_data_cookie;
						
		if($mode == 'remove')
		{
			$delete_db = $db->table(BLOCKS_BOXES_TABLE)
							->where('box_id', $box_id)
							->delete();
			if($delete_db)
			{
				$response = json_encode(array(
					"status" => "success",
					"message" => _BOX_DELETE_OK
				));
			}
			else
			{
				$response = json_encode(array(
					"status" => "error",
					"message" => ""._ERROR_IN_OP." : ".$db->getErrors('last')['message'].""
				));
			}
		}
		elseif($mode == 'edit')
		{
			$query_set = array(
				'box_id' => $new_box_title,
				'box_theme_location' => $new_box_theme_location,
				'box_theme_priority' => $new_box_theme_priority
			);
			
			if($new_box_status == 'true')
				$query_set['box_status'] = 0;
			
			$update_db = $db->table(BLOCKS_BOXES_TABLE)
							->where('box_id', $box_id)
							->update($query_set);
			if($update_db)
			{
				$response = json_encode(array(
					"status" => "success",
					"message" => _BOX_EDIT_OK
				));
			}
			else
			{
				$response = json_encode(array(
					"status" => "error",
					"message" => ""._ERROR_IN_OP." : ".$db->getErrors('last')['message'].""
				));
			}
		}
		elseif($mode == 'outbox')
		{
			$block_id = explode("-", $block_id);
			$bid = $block_id[2];
			$box_ids = explode(",", $box_id);
			$source_box_id = $box_ids[0];
			$target_box_id = $box_ids[1];
			
			$result = $db->table(BLOCKS_BOXES_TABLE)
								->where('box_id', $target_box_id)
								->select();
			if($db->count() > 0)
			{
				foreach($result as $row)
				{
					$boxes_data[$row['box_id']] = $row;
				}
			}
			else
			{
				$db->table(BLOCKS_BOXES_TABLE)
					->insert([
						'box_id' => $target_box_id,
						'box_status' => 1
					]);
					
				$boxes_data[$target_box_id] = array(
					"box_id" => $target_box_id,
					"box_blocks" => '',
					"box_blocks_data" => '',
					"box_status" => 1,
					"box_theme_location" => '',
					"box_theme_priority" => '',
				);
			}
			
			if($source_box_id != 'all')
			{
				$result = $db->table(BLOCKS_BOXES_TABLE)
								->where('box_id', $source_box_id)
								->select();
				if($db->count() > 0)
				{
					foreach($result as $row)
					{
						$boxes_data[$row['box_id']] = $row;
					}
				}
			}
			
			$source_box_block_data = array();
			if($source_box_id != 'all')
			{
				$source_box_blocks = ($boxes_data[$source_box_id]['box_blocks'] != '') ? explode(",", $boxes_data[$source_box_id]['box_blocks']):array();
				$source_box_blocks_data = ($boxes_data[$source_box_id]['box_blocks_data'] != '') ? phpnuke_unserialize(stripslashes($boxes_data[$source_box_id]['box_blocks_data'])):array();
				
				// remove block from source box
				$source_box_blocks = array_diff($source_box_blocks, array($bid));
				$source_box_block_data = $source_box_blocks_data[$bid];
				unset($source_box_blocks_data[$bid]);
			}
			
			$target_box_blocks_data = (isset($boxes_data[$target_box_id]['box_blocks_data']) && $boxes_data[$target_box_id]['box_blocks_data'] != '') ? phpnuke_unserialize(stripslashes($boxes_data[$target_box_id]['box_blocks_data'])):array();
			
			//add block to target box
			$target_box_blocks_data[$bid] = ($source_box_id != 'all' && !empty($source_box_block_data)) ? $source_box_block_data:array(
				"title" => $nuke_blocks_cacheData['blocks'][$bid]['title'],
				"lang_titles" => '',
				"blanguage" => '',
				"weight" => 0,
				"active" => 1,
				"time" => _NOWTIME,
				"permissions" => 0,
				"publish" => 0,
				"expire" => 0,
				"action" => '',
				"theme_block" => ''
			);
			
			$target_box_blocks = array();
			$new_target_box_blocks_data = array();
			foreach($bids_weight as $bid_key => $bid_val)
			{
				$target_box_blocks[] = $bid_val;
				$new_weight = $bid_key+1;
				$new_target_box_blocks_data[$bid_val] = $target_box_blocks_data[$bid_val];
				$new_target_box_blocks_data[$bid_val]['weight'] = $new_weight;
			}		
			
			$result1 = true;
			if($source_box_id != 'all')
			{
				$source_box_blocks = implode(",",$source_box_blocks);
				$source_box_blocks_data = (!empty($source_box_blocks_data)) ? addslashes(phpnuke_serialize($source_box_blocks_data)):'';
				
				$result1 = $db->table(BLOCKS_BOXES_TABLE)
					->where('box_id', $source_box_id)
					->update([
						'box_blocks' => $source_box_blocks,
						'box_blocks_data' => $source_box_blocks_data,
					]);
			}
			
			$target_box_blocks = implode(",",$target_box_blocks);
			$target_box_blocks_data = (!empty($new_target_box_blocks_data)) ? addslashes(phpnuke_serialize($new_target_box_blocks_data)):'';
			
			$result2 = $db->table(BLOCKS_BOXES_TABLE)
				->where('box_id', $target_box_id)
				->update([
					'box_blocks' => $target_box_blocks,
					'box_blocks_data' => $target_box_blocks_data,
				]);
			
			if($result1 && $result2)
			{
				$response = json_encode(array(
					"status" => "success",
					"message" => _CHANGES_OK
				));
			}
			else
			{
				$response = json_encode(array(
					"status" => "error",
					"message" => ""._ERROR_IN_OP." : ".$db->getErrors('last')['message'].""
				));
			}
		}
		elseif($mode == 'inbox')
		{
		
			$row = $db->table(BLOCKS_BOXES_TABLE)
				->where('box_id', $box_id)
				->first();
							
			$box_blocks_data = (isset($row['box_blocks_data']) && $row['box_blocks_data'] != '') ? phpnuke_unserialize(stripslashes($row['box_blocks_data'])):array();
						
			$box_blocks = $bids_weight;
			$new_box_blocks_data = array();
			foreach($bids_weight as $bid_key => $bid_val)
			{
				$new_weight = $bid_key+1;
				$new_box_blocks_data[$bid_val] = $box_blocks_data[$bid_val];
				$new_box_blocks_data[$bid_val]['weight'] = $new_weight;
			}			
			
			$box_blocks = implode(",",$box_blocks);
			$box_blocks_data = (!empty($new_box_blocks_data)) ? addslashes(phpnuke_serialize($new_box_blocks_data)):'';
			
			$result = $db->table(BLOCKS_BOXES_TABLE)
				->where('box_id', $box_id)
				->update([
					'box_blocks' => $box_blocks,
					'box_blocks_data' => $box_blocks_data,
				]);
			
			if($result)
			{
				$response = json_encode(array(
					"status" => "success",
					"message" => _CHANGES_OK
				));
			}
			else
			{
				$response = json_encode(array(
					"status" => "error",
					"message" => ""._ERROR_IN_OP." : ".$db->getErrors('last')['message'].""
				));
			}
		}
		cache_system("nuke_blocks");
		add_log(sprintf(_CHANGE_BOX_WEIGHT, $box_id), 1);
		
		die($response);
	}

	function BlocksAdmin()
	{
		global $db, $hooks, $admin_file, $nuke_configs, $nuke_blocks_cacheData, $nuke_headlines_cacheData, $theme_setup;

		$hooks->add_filter("set_page_title", function(){return array("BlocksAdmin" => _BLOCKSADMIN);});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$contents .=  "<div class=\"text-center\"><font class=\"title\"><b>"._BLOCKSADMIN."</b></font></div>";
		$contents .= CloseAdminTable();
		
		$theme_widgets = (isset($theme_setup['theme_widgets'])) ? $theme_setup['theme_widgets']:array();
		$json_theme_widgets = json_encode($theme_widgets);
		
		$not_installed_blocks = $installed_blocks = $active_blocks = $in_expire_blocks = $inactive_blocks = $future_blocks = 0;

		$all_installed_blocks = array();
		
		foreach($nuke_blocks_cacheData['blocks_boxes'] as $box_id => $box_data)
		{
			if(isset($box_data['blocks']))
			{
				foreach($box_data['blocks'] as $bid => $block_data)
				{
					$all_installed_blocks = array_merge($all_installed_blocks,array($bid));
					
					if($block_data['active'] == 1 && $block_data['expire'] == 0 && $block_data['publish'] == 0)
						$active_blocks++;
						
					if($block_data['active'] == 1 && $block_data['expire'] != 0)
						$in_expire_blocks++;
						
					if($block_data['active'] == 0 && $block_data['publish'] == 0 && $block_data['expire'] == 0)
						$inactive_blocks++;
						
					if($block_data['active'] == 0 && $block_data['publish'] != 0)
						$future_blocks++;
				}
			}
		}
		$all_blocks = array_keys($nuke_blocks_cacheData['blocks']);
		
		$all_installed_blocks = array_unique($all_installed_blocks);
		
		foreach($all_blocks as $bid)
		{
			if(!in_array($bid, $all_installed_blocks))
				$not_installed_blocks++;
		}
				
		$contents .=  "<br />";
		$contents .= OpenAdminTable();
		$contents .=  "<br />
		<div id=\"blocks-boxes\">
			<div id=\"blocks-containment\">
				<div class=\"active-blocks\">
					<b>"._ACT_INACT_BLOCKS."</b>
					<br /><br />
					<table class=\"id-form\" width=\"100%\">
						<tr>
							<td>
								"._ADD_BOX." &nbsp; 
								<input type=\"text\" class=\"inp-form\" id=\"new-box-name\" placeholder=\""._ENTER_BOXID."\" /> 
								<input type=\"button\" id=\"add_block_box\" value=\"\" class=\"form-submit\" /> 
								".bubble_show(_BOXID_IN_ENGLISH)."
							</td>
							<td width=\"340\">
								<a href=\"#\" id=\"add-html-block\">".sprintf(_ADD_SP_BLOCK, "Html")."</a>
								<a href=\"#\" id=\"add-rss-block\">".sprintf(_ADD_SP_BLOCK, "Rss")."</a>
							</td>
						</tr>
						<tr>
							<td colspan=\"2\" id=\"blocks-help\">
								<br />
								<br />
								<hr />
								<br />
								<b>"._GUIDE."</b>
								<ul>
									<li><span class=\"color-box\" style=\"background:#ccc;\"></span><span class=\"help-text\">"._NOT_INSTALLED." ($not_installed_blocks "._BLOCK.")</span></li>
									<li><span class=\"color-box\" style=\"background:#fff;\"></span><span class=\"help-text\">"._INSTALLED_AND_ACTIVE." ($active_blocks "._BLOCK.")</span></li>
									<li><span class=\"color-box\" style=\"background:#fceded;\"></span><span class=\"help-text\"> "._INSTALLED_AND_INACTIVE." ($inactive_blocks "._BLOCK.")</span></li>
									<li><span class=\"color-box\" style=\"background:#fcf6d8;\"></span><span class=\"help-text\">"._ACTIVE_FUTURE_EXPIRE." ($in_expire_blocks "._BLOCK.")</span></li>
									<li><span class=\"color-box\" style=\"background:#e2f8fc;\"></span><span class=\"help-text\">"._INACTIVE_FUTURE_PUBLISH." ($future_blocks "._BLOCK.")</span></li>
								</ul>								
							</td>
						</tr>
					</table>
				</div>
				<div class=\"blocks-column-full\" data-box_id=\"all\">
					<div class=\"blocks-box-header\">
						"._EXISTS_BLOCKS."
						<span class=\"blocks-box-tools\">
							<a class=\"toggle-box table-icon icon-11 info-tooltip\" href=\"#\" title=\""._SHOW_HIDE."\"></a>
						</span>
					</div>
					<ul class=\"blocks-draggable-list\">";
					
						foreach($nuke_blocks_cacheData['blocks'] as $bid => $block_data)
						{
							if($block_data['blockfile'] == NULL)
								continue;
								
							$title = filter($block_data['title'], "nohtml");
							
							$contents .=  "<li class=\"blocks-draggable-item blocks-sortable-item\" id=\"block-all-$bid\" data-bid=\"$bid\">
								<span class=\"block-title\">$title</span>
								<span class=\"blocks-tools\">
									<a class=\"infblock-block table-icon icon-15 info-tooltip\" href=\"#\" title=\""._INFO."\"></a>
									<a class=\"preview-block table-icon icon-7 info-tooltip\" href=\"#\" title=\""._SHOW."\"></a>
									<a class=\"remove-block table-icon icon-2 info-tooltip\" href=\"#\" title=\""._DELETE."\"></a>
									<a class=\"edit-block table-icon icon-6 info-tooltip\" href=\"#\" title=\""._EDIT."\"></a>
									<a class=\"active-block table-icon icon-13 info-tooltip\" href=\"#\" title=\""._DEACTIVATE."\"></a>
								</span>
							</li>\n";
						}
					$contents .= "
						<li class=\"clearer\"></li>
					</ul>
				</div>
				
				<div class=\"clear\"></div>";
				$i = 0;
				$contents .= "<div id=\"update_result\"></div>";
				
				$primary_boxes = array("right","left","topcenter","bottomcenter","comments");
				foreach($primary_boxes as $primary_box)
				if(!isset($nuke_blocks_cacheData['blocks_boxes'][$primary_box]))
					$nuke_blocks_cacheData['blocks_boxes'][$primary_box] = array();
					
				foreach($nuke_blocks_cacheData['blocks_boxes'] as $box_id => $box_data)
				{
					if($box_id == "")
						continue;
					$box_data['box_theme_location'] = (isset($box_data['box_theme_location'])) ? $box_data['box_theme_location']:"";
					$box_data['box_theme_priority'] = (isset($box_data['box_theme_priority'])) ? $box_data['box_theme_priority']:"";
					
					if($i%3 == 0)
						$class_name = 'blocks-column '.((_DIRECTION == "rtl") ? "fl":"fr").' first clearer';
					else
						$class_name = 'blocks-column '.((_DIRECTION == "rtl") ? "fl":"fr").'';
										
					$contents .= "<div class=\"$class_name\" data-box_id=\"$box_id\" data-theme-location=\"".$box_data['box_theme_location']."\" data-theme-priority=\"".$box_data['box_theme_priority']."\">
						<div class=\"blocks-box-header\">
							<span id=\"".$box_id."_title\">"._BOXNAME." : <b>".str_replace("_"," ", $box_id)."</b></span>
							<span class=\"blocks-box-tools\">
								<a class=\"remove-box table-icon icon-2 info-tooltip\" href=\"#\" title=\""._DELETE."\"></a>
								<a class=\"edit-box table-icon icon-6 info-tooltip\" href=\"#\" title=\""._EDIT."\"></a>
								<a class=\"toggle-box table-icon icon-11 info-tooltip\" href=\"#\" title=\""._SHOW_HIDE."\"></a>
							</span>
						</div>
						<ul class=\"blocks-sortable-list\">
							";
							if(isset($box_data['blocks']) && !empty($box_data['blocks']))
							{
								foreach($box_data['blocks'] as $bid => $block_data)
								{									
									$title = filter($block_data['title'], "nohtml");
									$lang_titles = ($block_data['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($block_data['lang_titles'])):"";
									if($lang_titles != "" && $nuke_configs['multilingual'] == 1){
										$title = filter($lang_titles[$nuke_configs['currentlang']], "nohtml");
									}
									
									$active_class = ($block_data['active'] == 1) ? "":" deactivated";
									
									$active_class .= ($block_data['expire'] != 0) ? " in-expire":"";
									
									$active_class .= ($block_data['publish'] != 0) ? " future":"";
									
									$active_icon_class = ($block_data['active'] == 1) ? "13":"5";
									
									$active_title = ($block_data['active'] == 1) ? _DEACTIVATE:_ACTIVATE;
									
									$contents .= "<li class=\"blocks-sortable-item blocks-sortable-item-inbox$active_class\" id=\"block-$box_id-$bid\" data-bid=\"$bid\">
										<span class=\"block-title\">$title</span>
										<span class=\"blocks-tools\">
											<a class=\"infblock-block table-icon icon-15 info-tooltip\" href=\"#\" title=\""._INFO."\"></a>
											<a class=\"preview-block table-icon icon-7 info-tooltip\" href=\"#\" title=\""._SHOW."\"></a>
											<a class=\"remove-block table-icon icon-2 info-tooltip\" href=\"#\" title=\""._DELETE."\"></a>
											<a class=\"edit-block table-icon icon-6 info-tooltip\" href=\"#\" title=\""._EDIT."\"></a>
											<a class=\"active-block table-icon icon-$active_icon_class info-tooltip\" href=\"#\" title=\"$active_title\"></a>
										</span>
									</li>";
								}
							}
						$contents .= "</ul>

					</div>";
					$i++;
				}
				$contents .= "
				<div id=\"blocks-more-columns\"></div>
				<div class=\"clear\"></div>
			</div>
		</div>
		<div id=\"blocks-dialog\"></div>
		<script type=\"text/javascript\">
			var admin_file = '".$admin_file."';
			var nuke_language = '".$nuke_configs['language']."';
			var theme_widgets = JSON.parse('$json_theme_widgets');
			var blocks_language = {
				enter_boxid : '"._ENTER_BOXID."',
				enter_new_boxid : '"._ENTER_NEW_BOXID."',
				boxid_in_english : '"._BOXID_IN_ENGLISH_ALERT."',
				block_preview : '"._BLOCK_PREVIEW."',
				block_info : '"._BLOCK_INFO."',
				block_delete_confirm : '"._BLOCK_DELETE_CONFIRM."',
				block_deactivate : '"._DEACTIVATE."',
				block_edit : '"._BLOCK_EDIT."',
				box_delete_confirm : '"._BOX_DELETE_CONFIRM."',
				box_cannot_deleted : '"._BOX_CANNOTDELETEED."',
				box_name : '"._BOXNAME."',
				anyone : '"._ANYONE."',
				position : '"._BOX_POSITION."',
				priority : '"._BOX_ERIORITY."',
				boxdeactivate : '"._BOX_DEACTIVATE."',
				edit_boxname : '"._EDIT_BOXNAME."',
				delete_box : '"._DELETE."',
				edit_box : '"._EDIT."',
				showhide_box : '"._SHOW_HIDE."',
				add_block : '"._ADD_BLOCK."',
			};
			var pn_csrf_token = '"._PN_CSRF_TOKEN."';
		</script>
				
		<script src=\"admin/template/js/jquery/jquery.blocks.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/jquery.base64.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/calendar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-ar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-fa.js\" type=\"text/javascript\"></script>	
	
		<script type=\"text/javascript\">
			/*var fixHelper = function(e, ui) {
				ui.children().each(function() {
					$(this).width($(this).width());
				});
				return ui;
			};
			$(document).ready(function(){ 
				$(function() {
					$(\"#contentLeft table tbody\").sortable({ opacity: 0.6, cursor: 'move', update: function() {
						var order = 'csrf_token="._PN_CSRF_TOKEN."\"action=updateRecordsListings&' + $(this).sortable(\"serialize\");
						$.post(\"".$admin_file.".php?op=updateweight\", order, function(theResponse){
							$(\"#contentRight\").html(theResponse);
						}); 															 
					},helper : fixHelper
					}).disableSelection();
				});
			});	*/
		</script>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	function preview_block($box_id, $bid)
	{
		global $db, $admin_file;
		$contents = '';
		$contents .= blocks($box_id, array($bid));
		die($contents);
	}
	
	function remove_block($box_id, $bid)
	{
		global $db, $admin_file, $nuke_blocks_cacheData, $nuke_configs;
		
		if($box_id != "all")
		{
			$row = $db->table(BLOCKS_BOXES_TABLE)
							->where('box_id', $box_id)
							->first();
			
			$box_blocks = (isset($row['box_blocks']) && $row['box_blocks'] != '') ? explode(",", $row['box_blocks']):array();
			$box_blocks_data = (isset($row['box_blocks_data']) && $row['box_blocks_data'] != '') ? phpnuke_unserialize(stripslashes($row['box_blocks_data'])):array();
			
			if($nuke_blocks_cacheData['blocks'][$bid]['content'] != '' || $nuke_blocks_cacheData['blocks'][$bid]['url'] != '')
			{
				$db->table(BLOCKS_TABLE)
					->where('bid', $bid)
					->delete();
				phpnuke_auto_increment(BLOCKS_TABLE);
			}
			
			if(in_array($bid, $box_blocks))
				$box_blocks = array_diff($box_blocks, array($bid));
			
			$box_blocks = implode(",", $box_blocks);
			
			if(isset($box_blocks_data[$bid]))
				unset($box_blocks_data[$bid]);
				
			$box_blocks_data = addslashes(phpnuke_serialize($box_blocks_data));
			
			$result = $db->table(BLOCKS_BOXES_TABLE)
				->where('box_id', $box_id)
				->update([
					'box_blocks' => $box_blocks,
					'box_blocks_data' => $box_blocks_data,
				]);
			
			if($result)
			{
				cache_system("nuke_blocks");
				
				$response = json_encode(array(
					"status" => "success",
					"message" => _BLOCK_DELETE_OK
				));
			}
			else
			{
				$response = json_encode(array(
					"status" => "error",
					"message" => ""._ERROR_IN_OP." ".$db->getErrors('last')['message'].""
				));
			}
			$block_title = $nuke_blocks_cacheData['blocks'][$bid]['title'];
			add_log(sprintf(_BLOCK_DELETE_LOG, $block_title, $box_id), 1);
		}
		else
		{
			$response = json_encode(array(
				"status" => "success",
				"message" => _OP_OK
			));
		}
		die($response);
	}
	
	function change_block_status($box_id, $bid)
	{
		global $db, $admin_file, $nuke_blocks_cacheData, $nuke_configs;
		$row = $db->table(BLOCKS_BOXES_TABLE)
						->where('box_id', $box_id)
						->first();
		
		$box_blocks_data = (isset($row['box_blocks_data']) && $row['box_blocks_data'] != '') ? phpnuke_unserialize(stripslashes($row['box_blocks_data'])):array();
		
		$old_status = $box_blocks_data[$bid]['active'];
		$new_status = ($old_status == 0) ? 1:0;
		$box_blocks_data[$bid]['active'] = $new_status;
			
		if($new_status == 1)
			$box_blocks_data[$bid]['publish'] = 0;
			
		$box_blocks_data = addslashes(phpnuke_serialize($box_blocks_data));
		
		$result = $db->table(BLOCKS_BOXES_TABLE)
			->where('box_id', $box_id)
			->update([
				'box_blocks_data' => $box_blocks_data,
			]);
			
		if($result)
		{
			cache_system("nuke_blocks");
			
			$response = json_encode(array(
				"status" => "success",
				"message" => sprintf(_BLOCK_ACT_DEACT_MSG, (($new_status == 1) ? _ACTIVE:_INACTIVE)),
				"new_status" => $new_status
			));
		}
		else
		{
			$response = json_encode(array(
				"status" => "error",
				"message" => sprintf(_BLOCK_ACT_DEACT_ERROR, (($new_status == 1) ? _ACTIVE:_INACTIVE)).$db->getErrors('last')['message'].""
			));
		}
		$block_title = $nuke_blocks_cacheData['blocks'][$bid]['title'];
		add_log(sprintf(_BLOCK_ACT_DEACT_LOG, (($new_status == 1) ? _ACTIVE:_INACTIVE), $block_title, $box_id), 1);
		die($response);
	}

	function info_block($box_id, $bid)
	{
		global $db, $admin_file, $nuke_blocks_cacheData, $nuke_headlines_cacheData, $nuke_configs;
		$contents = '';

		$bid = intval($bid);
		$row = $nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid];
		$title = filter($row['title'], "nohtml");
		$blockcontent = $row['content'];
		$url = filter($row['url'], "nohtml");
		$weight = intval($row['weight']);
		$active = intval($row['active']);
		$refresh = intval($row['refresh']);
		$blanguage = $row['blanguage'];
		$blockfile = filter($row['blockfile'], "nohtml");
		$expire = intval($row['expire']);
		$publish = intval($row['publish']);
		$action = $row['action'];
		$theme_block = $row['theme_block'];
		$block_permissions = ($row['permissions'] != 0 && $row['permissions'] != '') ? explode(",", $row['permissions']):(($row['permissions'] == 0) ? array(0):array());
		$lang_titles = ($row['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($row['lang_titles'])):"";
		
		$type  = '';
		if ($url != "")
		{
			$type = _RSSCONTENT;
		}
		elseif($url == "" && $blockcontent != "")
		{
			$type = _HTMLCONTENT;
		}
		elseif($blockfile != "")
		{
			$type = _BLOCKFILE;
		}
		
		$title .= (($nuke_configs['multilingual'] == 1 && isset($lang_titles[$nuke_configs['currentlang']])) ? " <u>".$lang_titles[$nuke_configs['currentlang']]."</u>":'');
			
		$languageslists = get_dir_list('language', 'files');
		$contents .= "<div class=\"text-center\"><font class=\"option\"><b>"._BLOCK_INFO.": $title $type</b></font></div><br /><br />";
		$contents .= "
		<table width=\"100%\" class=\"product-table no-border\">";
		
			$contents .= "<tr>
				<th>"._BOXNAME."</th>
				<td>".str_replace("_", " ", $box_id)."</td>
			</tr>";
			if($nuke_configs['multilingual'] != 1)
			{
				$contents .= "
				<tr>
					<th style=\"width:160px;\">"._TITLE.":</th>
					<td>$title</td>
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
						$contents .= "
						<tr>
							<th style=\"width:160px;\">"._TITLE_INLANG." : ".ucfirst($languageslist)."</th>
							<td id=\"edit-block-lang_titles\">
								".(isset($lang_titles[$languageslist]) ? $lang_titles[$languageslist]:"")."
							</td>
						</tr>";
					}
				}
			}
			
			$contents .= "
			<tr>
				<th style=\"width:250px;\">وضعیت</th>
				<td>".(($active == 1) ? _ACTIVE:_INACTIVE)."</td>
			</tr>";
			if($publish != 0){
			$contents .= "<tr>
				<th>"._ACTIVATE_FUTURE_TIME."</th>
				<td>";
					$publish_hour = ($publish != 0) ? date("H", $publish):0;
					$publish_min = ($publish != 0) ? date("i", $publish):0;
					$publish_date = nuketimes($publish, false, false, false, 3);
					$contents .= "$publish_date ".((intval($publish_hour) != 0 && intval($publish_min) != 0) ? ""._HOUR." $publish_hour:$publish_min":"")."</td>
			</tr>";
			}
			if($expire != 0){
			$contents .= "<tr>
				<th>"._EXPIRATION."</th>
				<td>";
					
					$expire_hour = date("H", $expire);
					$expire_min = date("i", $expire);
					$expire_date = nuketimes($expire, false, false, false, 3);
					$contents .= "$expire_date ".((intval($expire_hour) != 0 && intval($expire_min) != 0) ? ""._HOUR." $expire_hour:$expire_min":"")."</td>
			</tr>";
			}			
			$contents .= "<tr>
				<th>"._SHOWN_FOR."</th>
				<td id=\"edit-block-permissions\">";
					$all_permissions = get_groups_permissions();
					$permissions = array();
					foreach($all_permissions as $key => $premission_name)
					{
						if(in_array($key, $block_permissions))
							$permissions[] = $premission_name;
					}
					$permissions = implode(", ", $permissions);
				$contents .= "$permissions
				</td>
			</tr>
		</table>";		
		$contents .=  jquery_codes_load();
		die($contents);
	}

	function BlocksEdit($box_id, $bid, $block_type, $all_box_ids)
	{
		global $db, $admin_file, $nuke_blocks_cacheData, $nuke_headlines_cacheData, $new_boxes, $nuke_configs;
		$contents = '';
		$new_block = (isset($block_type) && $block_type != '') ? true:false;
		$block_type = (isset($block_type) && $block_type != '') ? $block_type:'';
		$contents .= $new_boxes."<br /><br />";
		if($new_block && sizeof($nuke_blocks_cacheData['blocks_boxes']) < 1)
		{
			die("<p align=\"center\">"._NO_BOX_ESISTS_ERROR."</p>");
		}
		
		if(!$new_block)
		{
			$bid = intval($bid);
			$row = $nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid];
		}
		
		$block_type = ($block_type == '') ? (($row['url'] == '' && $row['content'] != '') ? "html":(($row['url'] != '') ? "rss":"")):$block_type;
		
		$title = ($new_block) ? "":filter($row['title'], "nohtml");
		$blockcontent = ($new_block && ($block_type == 'html' || $block_type == 'rss')) ? "":$row['content'];
		$url = ($new_block) ? "":filter($row['url'], "nohtml");
		$weight = ($new_block) ? "":intval($row['weight']);
		$active = ($new_block) ? 1:intval($row['active']);
		$refresh = ($new_block) ? "":intval($row['refresh']);
		$blanguage = ($new_block) ? "":$row['blanguage'];
		$blockfile = ($new_block) ? "":filter($row['blockfile'], "nohtml");
		$expire = ($new_block) ? 0:intval($row['expire']);
		$publish = ($new_block) ? 0:intval($row['publish']);
		$action = ($new_block) ? "d":$row['action'];
		$theme_block = ($new_block) ? "":$row['theme_block'];
		$block_permissions = ($new_block) ? array(0):(($row['permissions'] !== 0 && $row['permissions'] != '') ? explode(",", $row['permissions']):(($row['permissions'] == 0) ? array(0):array()));
		$lang_titles = ($new_block) ? "":(($row['lang_titles'] != "") ? phpnuke_unserialize(stripslashes($row['lang_titles'])):"");

		$type = '';
		if ($url != "" || $block_type == 'rss')
		{
			$type = _RSSCONTENT;
		}
		elseif(($url == "" && $blockcontent != "") || $block_type == 'html')
		{
			$type = _HTMLCONTENT;
		}
		elseif($blockfile != "")
		{
			$type = _BLOCKFILE;
		}		
		
		$title .= (($nuke_configs['multilingual'] == 1 && !$new_block && isset($lang_titles[$nuke_configs['currentlang']])) ? " <u>".$lang_titles[$nuke_configs['currentlang']]."</u>":'');
			
		$languageslists = get_dir_list('language', 'files');
		$contents .=  "<div class=\"text-center\"><font class=\"option\"><b>".($new_block ? _ADD:_EDIT)." "._BLOCK.": ".($new_block ? $block_type:"$title")." $type</b></font></div><br /><br />";
		$contents .= "
		<table width=\"100%\" class=\"id-form product-table no-border\">";
		
			if($new_block)
			{
				$contents .= "<tr>
					<th>"._SELECT_BOX." :</th>
					<td>
						<select class=\"styledselect-select\" id=\"edit-block-box\">";
							foreach($all_box_ids as $this_box_id)
							{								
								$contents .=  "<option value=\"$this_box_id\">".str_replace("_"," ", ucfirst($this_box_id))."</option>";
							}
							$contents .= "
						</select>
					</td>
				</tr>";
			}
			
			if($nuke_configs['multilingual'] != 1)
			{
				$contents .= "
				<tr>
					<th style=\"width:160px;\">"._TITLE.":</th>
					<td>
						<input class=\"inp-form\" type=\"text\" name=\"title\" id=\"edit-block-title\" size=\"30\" maxlength=\"60\" value=\"$title\">
					</td>
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
						$contents .= "
						<tr>
							<th style=\"width:160px;\">"._TITLE_INLANG." : ".ucfirst($languageslist)."</th>
							<td id=\"edit-block-lang_titles\">
								<input class=\"inp-form\" type=\"text\" data-language=\"$languageslist\" size=\"30\" maxlength=\"60\" value=\"".(isset($lang_titles[$languageslist]) ? $lang_titles[$languageslist]:"")."\">
							</td>
						</tr>";
					}
				}
				$contents .= "<input type=\"hidden\" name=\"title\" id=\"edit-block-title\" value=\"\">";
			}
		
			if($block_type == 'html')
			{
				$contents .=  "<tr>
					<th>"._BLOCK_CONTENTS.":</th>
					<td id=\"edit-block-contents\">
						".wysiwyg_textarea("blockcontent", stripslashes($blockcontent), "PHPNukeAdmin", "50", "12")."
					</td>
				</tr>";
			}
		
			if($block_type == 'rss')
			{
				$contents .= "<tr>
					<th>"._RSSFILE.":</th>
					<td>
						<select class=\"styledselect-select\" id=\"edit-block-rss\">
							<option value=\"0\" selected>"._CUSTOM."</option>";
								foreach($nuke_headlines_cacheData as $hid => $nuke_headlines_info)
								{
									$hid = intval($hid);
									$htitle = filter($nuke_headlines_info['sitename'], "nohtml");
									$sel = ($hid == $hid && !$new_block) ? "selected":"";
									$contents .= "<option value=\"$hid\" $sel>$htitle</option>";
								}
								$contents .= "
						</select>
						<br />
						<input type=\"text\" name=\"url\" size=\"30\" maxlength=\"200\" class=\"inp-form-ltr\" id=\"edit-block-url\" value=\"$url\" style=\"margin-top:10px;\" />
						<br />
						[ <a href=\"".$admin_file.".php?op=HeadlinesAdmin\">"._RSS_EDIT."</a> ]<br />
						<font class=\"tiny\">
						"._SETUPHEADLINES."
					</td>
				</tr>";
			}
			
			if(is_dir("themes/".$nuke_configs['ThemeSel']."/blocks/themes/"))
			{

				$contents .= "<tr>
				<th>"._DEF_BLOCK_THEME.":</th>
				<td>
					<select class=\"styledselect-select\" id=\"edit-block-theme\">
						<option value=\"\">"._DEF_THEME."</option>";	
						$langdir = dir("themes/".$nuke_configs['ThemeSel']."/blocks/themes/");
						while($func=$langdir->read()) {
							if(substr($func, 0, 6) == "block-") {
								$block_list[] = $func;
							}
						}
						closedir($langdir->handle);
						if(isset($block_list) && !empty($block_list))
						{
							sort($block_list);
							foreach($block_list as $block_template)
							{
								$selected = ($theme_block == $block_template && !$new_block) ? "selected":"";
								$contents .= "<option value=\"$block_template\" $selected>".ucfirst($block_template)."</option>";
							}
						}
					$contents .= "</select>
				</td>
				</tr>";
			}
			
			if ($nuke_configs['multilingual'] == 1)
			{
				$contents .= "
				<tr>
					<th>"._LANGUAGE.":</th>
					<td>
						<select id=\"edit-block-language\" class=\"styledselect-select\">";
							foreach($languageslists as $languageslist)
							{
								if($languageslist != "")
								{
									if($languageslist == 'index.html' || $languageslist == '.htaccess' || $languageslist == 'alphabets.php') continue;
									$languageslist = str_replace(".php", "", $languageslist);
									$selected = ($languageslist == $blanguage && !$new_block) ? "selected":"";
									$contents .= "<option value=\"".$languageslist."\" $selected>".ucfirst($languageslist)."</option>";
								}
							}
							$selected_all = ($blanguage == "") ? "selected":"";
							$contents .= "<option value=\"\" $selected_all>"._ALL."</option>
						</select>
					</td>
				</tr>";
			}
			else
			{
				$contents .= "<input type=\"hidden\" id=\"edit-block-language\" value=\"\">";
			}
			
			$contents .= "
			<tr>
				<th>"._ACTIVATE."</th>
				<td>";
					$sel1 = ($active == 1) ? "checked":"";
					$sel2 = ($active == 0) ? "checked":"";
					$contents .= "<input data-label=\""._YES."\" type=\"radio\" class=\"styled\" name=\"active\" id=\"edit-block-activeated\" value=\"1\" $sel1>&nbsp;&nbsp;&nbsp;
					<input data-label=\""._NO."\" type=\"radio\" class=\"styled\" name=\"active\" id=\"edit-block-deactiveated\" value=\"0\" $sel2>&nbsp;&nbsp;&nbsp;
					
				</td>
			</tr>
			<tr>
				<th>"._ACTIVATE_FUTURE_TIME."</th>
				<td>";
					
					$publish_date = ($publish != 0) ? nuketimes($publish, false, false, false, 1):0;
					$publish_chk = ($publish != 0) ? "checked":"";
					$publish_disabled = ($publish == 0) ? "disabled":"";
					$contents .= "
					<input type=\"text\" id=\"edit-block-publish\" class=\"inp-form-ltr calendar\" value=\"$publish_date\" $publish_disabled>
					&nbsp; ساعت <select id=\"edit-block-publish-hour\" class=\"styledselect-select\" style=\"width:50px\" $publish_disabled>";
					$publish_hour = ($publish != 0) ? date("H", $publish):0;
					$publish_min = ($publish != 0) ? date("i", $publish):0;
					for($h=0;$h < 24; $h++)
					{
						$hour = correct_date_number($h);
						$selected = ($publish_hour == $hour) ? "selected":"";
						$contents .= "<option value=\"$hour\" $selected>$hour</option>";
					}
					$contents .= "</select>
					&nbsp; <select id=\"edit-block-publish-min\" class=\"styledselect-select\" style=\"width:50px\" $publish_disabled>";
					for($m=0;$m < 60; $m++)
					{
						$min = correct_date_number($m);
						$selected = ($publish_min == $min) ? "selected":"";
						$contents .= "<option value=\"$min\" $selected>$min</option>";
					}
					$contents .= "</select>
					".bubble_show("<div style=\"margin-top:-7px;\"><span style=\"float:right;margin-top:7px\">"._PUBLISH_AT_FUTURE."</span> <input data-label=\""._YES."\" type=\"checkbox\" class=\"styled\" name=\"active\" id=\"edit-block-publish-check\" value=\"1\" $publish_chk /></div>")."
				</td>
			</tr>
			<tr>
				<th>"._EXPIRATION."</th>
				<td>";
					
					$expire_date = ($expire != 0) ? nuketimes($expire, false, false, false, 1):0;
					$contents .= "<input type=\"text\" id=\"edit-block-expire\" class=\"inp-form-ltr calendar\" value=\"$expire_date\">
					&nbsp; ساعت <select id=\"edit-block-expire-hour\" class=\"styledselect-select\" style=\"width:50px\">";
					$expire_hour = ($expire != 0) ? date("H", $expire):0;
					$expire_min = ($expire != 0) ? date("i", $expire):0;
					for($h=0;$h < 24; $h++)
					{
						$hour = correct_date_number($h);
						$selected = ($expire_hour == $hour) ? "selected":"";
						$contents .= "<option value=\"$hour\" $selected>$hour</option>";
					}
					$contents .= "</select>
					&nbsp; <select id=\"edit-block-expire-min\" class=\"styledselect-select\" style=\"width:50px\">";
					for($m=0;$m < 60; $m++)
					{
						$min = correct_date_number($m);
						$selected = ($expire_min == $min) ? "selected":"";
						$contents .= "<option value=\"$min\" $selected>$min</option>";
					}
					$contents .= "</select>
					".bubble_show(_ZERO_MEANS_UNLIMITED)."
				</td>
			</tr>
			<tr>
				<th>"._AFTEREXPIRATION."</th>
				<td>
					<select id=\"edit-block-expire-action\" class=\"styledselect-select\">";
						$selected1 = ($action == "d") ? "selected":"";
						$selected2 = ($action == "r") ? "selected":"";
						$contents .= "
						<option name=\"action\" value=\"d\" $selected1>"._DEACTIVATE."</option>
						<option name=\"action\" value=\"r\" $selected2>"._DELETE."</option>
					</select>
				</td>
			</tr>";
			if($block_type == 'rss')
			{
			$contents .= "
			<tr>
				<th>"._REFRESHTIME."</th>
				<td>
					<input type=\"text\" id=\"edit-block-refresh\" class=\"inp-form-ltr\" value=\"$refresh\">
					".bubble_show(_ONLYHEADLINES)."
				</td>
			</tr>";			
			}
			
			$contents .= "<tr>
				<th>"._SHOWN_FOR."</th>
				<td id=\"edit-block-permissions\">";
					$permissions = get_groups_permissions();
					foreach($permissions as $key => $premission_name)
					{
						$checked = (in_array($key, $block_permissions)) ? "checked":'';
						
						$contents .= "<input data-label=\"$premission_name\" type=\"checkbox\" class=\"styled\" id=\"edit-block-permission_$key\" value=\"$key\" $checked />&nbsp; ";
					}
				$contents .= "</td>
			</tr>
			<tr>
				<td colspan=\"2\"><input type=\"button\" class=\"form-submit\" value=\""._SAVEBLOCK."\" id=\"edit-form-submit\"> <div id=\"edit-loading\" class=\"loading\" style=\"display:none;\"></div></form><div class=\"clear\"></td>
			</tr>
		
		</table>";
		$jquery_plugin = "

		var currentlang = '".$nuke_configs['currentlang']."';
		
		$(\"#edit-block-rss\").on('change', function(){
			if($(this).val() == 0)
				$(\"#edit-block-url\").fadeIn(100);
			else
				$(\"#edit-block-url\").fadeOut(100);
		});
		
		$(\"#edit-block-publish-check\").on('change', function(){
			if($(this).is(':checked'))
			{
				alert('"._BLOCK_FUTURE_PUBLISH."');
				$(\"#edit-block-publish\").attr('disabled',false);
				$(\"#edit-block-publish-hour\").attr('disabled',false);
				$(\"#edit-block-publish-min\").attr('disabled',false);
			}
			else
			{
				$(\"#edit-block-publish\").val(0).attr('disabled','disabled');
				$(\"#edit-block-publish-hour\").attr('disabled','disabled');
				$(\"#edit-block-publish-min\").attr('disabled','disabled');
			}
		});
		
		$(\"#edit-form-submit\").click(function(){
			$(\"#edit-loading\").html('"._PLEASE_WAIT."').css({'display':'inline-block', 'padding':'0 40px', 'width':'180px', 'background-position':'"._TEXTALIGN1."'});
			
			var edited_lang_titles = {};
			$(\"#edit-block-lang_titles input\").each(function(){
				if($(this).val() != '')
					edited_lang_titles[$(this).data('language')] = $(this).val();
			});

			var edited_permissions = [];
			$(\"#edit-block-permissions input\").each(function(){
				if($(this).is(':checked'))
				{
					edited_permissions.push($(this).val());
				}
			});
			eval('var currentlang_title = edited_lang_titles.'+currentlang+';');

			if((Object.keys(edited_lang_titles).length == 0) && $(\"#edit-block-title\").val() == '')
			{
				alert('"._ENTER_BLOCK_NAME."');
				$(\"#edit-loading\").css({'display':'none'});
				return false;
			}
			
			if((Object.keys(edited_lang_titles).length > 0) && typeof(currentlang_title) === typeof undefined)
			{
				alert('"._ENTER_BLOCK_NAME_INDEFLANG." : '+currentlang+'');
				$(\"#edit-loading\").css({'display':'none'});
				return false;
			}
			edited_permissions = edited_permissions.join(',');
			$.base64.utf8encode = true;
			
			var block_fields = {
				'edited_title': ''+$(\"#edit-block-title\").val()+'',
				'edited_lang_titles': edited_lang_titles,
				'edited_contents': ''+(('$block_type' == 'html') ? $.base64('encode', CKEDITOR.instances['editor_blockcontent'].getData()):'')+'',
				'edited_url': ($(\"#edit-block-url\").length > 0 ? ''+$(\"#edit-block-url\").val()+'':''),
				'edited_rss': ($(\"#edit-block-rss\").length > 0 ? ''+$(\"#edit-block-rss\").val()+'':''),
				'edited_theme': ''+$(\"#edit-block-theme\").val()+'',
				'edited_language': ''+$(\"#edit-block-language\").val()+'',
				'edited_active': (($(\"#edit-block-activeated\").is(':checked')) ? 1:0),
				'edited_publish': ''+$(\"#edit-block-publish\").val()+'',
				'edited_publish_hour': ''+$(\"#edit-block-publish-hour\").val()+'',
				'edited_publish_min': ''+$(\"#edit-block-publish-min\").val()+'',
				'edited_expire': ''+$(\"#edit-block-expire\").val()+'',
				'edited_expire_hour': ''+$(\"#edit-block-expire-hour\").val()+'',
				'edited_expire_min': ''+$(\"#edit-block-expire-min\").val()+'',
				'edited_expire_action': ''+$(\"#edit-block-expire-action\").val()+'',
				'edited_block_refresh': ($(\"#edit-block-refresh\").length > 0 ? ''+$(\"#edit-block-refresh\").val()+'':''),
				'edited_permissions': edited_permissions
			};
			
			var new_block = (parseInt($new_block)) ? true:false;
			var box_id = ((new_block) ? $(\"#edit-block-box\").val():'$box_id');
			
			echo_message('', true, false);
			$.ajax({
				type : 'post',
				url : '".$admin_file.".php',
				data : {'op' : 'BlocksSave', 'block_fields' : block_fields, box_id : box_id, bid : '$bid', new_block : new_block, csrf_token : pn_csrf_token},
				success: function(theResponse){
					theResponse = typeof theResponse === 'object' ? theResponse : JSON.parse(theResponse);
					
					if(new_block)
					{
						var active_class = (block_fields.edited_active == 1) ? '':' deactivated';
						
						active_class += (block_fields.edited_expire != 0) ? ' in-expire':'';
						
						active_class += (block_fields.edited_publish != 0) ? ' future':'';
						
						var active_icon_class = (block_fields.edited_active == 1) ? '13':'5';
						
						var active_title = (block_fields.edited_active == 1) ? '"._DEACTIVATE."':'"._ACTIVATE."';
								
						var new_block_content = '			<li class=\"blocks-sortable-item blocks-sortable-item-inbox'+active_class+'\" id=\"block-'+box_id+'-'+theResponse.new_bid+'\" data-bid=\"'+theResponse.new_bid+'\">';
						new_block_content += '					<span class=\"block-title\">'+theResponse.new_title+'</span>';
						new_block_content += '					<span class=\"blocks-tools\">';
						new_block_content += '						<a class=\"infblock-block table-icon icon-15 info-tooltip\" href=\"#\" title=\""._INFO."\"></a>';
						new_block_content += '						<a class=\"preview-block table-icon icon-7 info-tooltip\" href=\"#\" title=\""._SHOW."\"></a>';
						new_block_content += '						<a class=\"remove-block table-icon icon-2 info-tooltip\" href=\"#\" title=\""._DELETE."\"></a>';
						new_block_content += '						<a class=\"edit-block table-icon icon-6 info-tooltip\" href=\"#\" title=\""._EDIT."\"></a>';
						new_block_content += '						<a class=\"active-block table-icon icon-'+active_icon_class+' info-tooltip\" href=\"#\" title=\"'+active_title+'\"></a>';
						new_block_content += '					</span>';
						new_block_content += '				</li>';
						$(\"#blocks-boxes .blocks-column\").each(function()
						{
							if($(this).data('box_id') == $(\"#edit-block-box\").val())
								$(this).find('ul.blocks-sortable-list').append(new_block_content);
						});
					}
					else
					{
						var lis = $(\"#block-\"+box_id+\"-$bid\");
						
						$(lis).find('.block-title').html(theResponse.new_title);
						
						if($(\"#edit-block-activeated\").is(':checked'))
						{
							$(lis).removeClass('deactivated');
							$(lis).find('.active-block').addClass('icon-13').removeClass('icon-5');
							lis.find('.active-block').tooltipText = '"._DEACTIVATE."';
						}
						else
						{
							$(lis).addClass('deactivated');
							$(lis).find('.active-block').addClass('icon-5').removeClass('icon-13');
							lis.find('.active-block').tooltipText = '"._ACTIVATE."';
						}
						
						if($(\"#edit-block-expire\").val() != 0)
						{
							$(lis).addClass('in-expire');
						}
						else
						{
							$(lis).removeClass('in-expire');
						}
						
						if($(\"#edit-block-publish\").val() != 0)
						{
							$(lis).addClass('future');
						}
						else
						{
							$(lis).removeClass('future');
						}
					}
					$(\"#blocks-dialog\").dialog('close');
					echo_message(theResponse, false, false);
				}
			});
			
		});";
		
		$contents .= jquery_codes_load($jquery_plugin);
		die($contents);
	}

	function BlocksSave($block_fields, $box_id, $bid, $new_block)
	{
		global $db, $admin_file, $nuke_blocks_cacheData, $nuke_headlines_cacheData, $nuke_configs;

		$new_block = filter_var($new_block, FILTER_VALIDATE_BOOLEAN);
		
		$block_query_set = array();
		$box_block_query_set = array();
		
		if ($block_fields['edited_title'] == "" || $block_fields['edited_title'] == "undefined")
		{
			if(isset($nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['blockfile']) && $nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['blockfile'] != '')
			{
				$block_query_set['title'] = $box_block_query_set['title'] = @str_replace(array("block-", ".php", "_"),array("",""," "),$nuke_blocks_cacheData['blocks_boxes'][$box_id]['blocks'][$bid]['blockfile']);
			}
			else
			{
				$block_query_set['title'] = $box_block_query_set['title'] = '';
			}
		}
		
		if($nuke_configs['multilingual'] == 1)
		{
			$box_block_query_set['lang_titles'] = phpnuke_serialize($block_fields['edited_lang_titles']);
		}else{
			$box_block_query_set['lang_titles'] = "";
		}
		
		if ($block_fields['edited_rss'] != '' && $block_fields['edited_rss'] != "undefined")
		{
			$block_fields['edited_url'] = $nuke_headlines_cacheData[$block_fields['edited_rss']]['headlinesurl'];
		}
		
		$block_fields['edited_contents'] = (isset($block_fields['edited_contents']) && $block_fields['edited_contents'] != '') ? base64_decode($block_fields['edited_contents']):"";
		
		if ($block_fields['edited_url'] != '' && $block_fields['edited_url'] != "undefined")
		{
			if (!@preg_match("#http:\/\/#", $block_fields['edited_url'])) {
				$block_fields['edited_url'] = "http://".$block_fields['edited_url'];
			}
			
			$block_query_set['url'] = $block_fields['edited_url'];
			
			$items = get_rss_contents($block_fields['edited_url']);
			if(is_array($items) && !empty($items)){
				$block_fields['edited_contents'] = "<ul class=\"rss_block_content\" id=\"rss_block_".$bid.">";
				foreach($items as $item){
					$block_fields['edited_contents'] .= "<li><a href=\"".$item['link']."\" target=\"new\">".$item['title']."</a></li>\n";
				}
				$block_fields['edited_contents'] .= "</ul>";
			}
		}
		elseif ($block_fields['edited_contents'] == '' || $block_fields['edited_contents'] == "undefined")
		{
			$block_fields['edited_contents'] = '';
		}
		
		$block_query_set['content'] = addslashes($block_fields['edited_contents']);
		
		$box_block_query_set['theme_block'] = $block_fields['edited_theme'];
		
		$box_block_query_set['blanguage'] = $block_fields['edited_language'];
		
		$box_block_query_set['active'] = $block_fields['edited_active'];
		
		if($block_fields['edited_expire'] != 0 && $block_fields['edited_expire'] != 'undefined')
		{
			$block_fields['edited_expire'] = to_mktime($block_fields['edited_expire'], $block_fields['edited_expire_hour'].":".$block_fields['edited_expire_min'].":0");
			$box_block_query_set['expire'] = $block_fields['edited_expire'];
			$box_block_query_set['action'] = $block_fields['edited_expire_action'];
		}
		
		if($block_fields['edited_publish'] != 0 && $block_fields['edited_publish'] != 'undefined')
		{
			$block_fields['edited_publish'] = to_mktime($block_fields['edited_publish'], $block_fields['edited_publish_hour'].":".$block_fields['edited_publish_min'].":0");
			$box_block_query_set['active'] = 0;
			$box_block_query_set['publish'] = $block_fields['edited_publish'];
		}
		
		if($block_fields['edited_block_refresh'] != '' && $block_fields['edited_block_refresh'] != 'undefined')
		{
			$block_query_set['refresh'] = $block_fields['edited_block_refresh'];
		}
		if($block_fields['edited_permissions'] != '' && $block_fields['edited_permissions'] != 'undefined')
		{
			$box_block_query_set['permissions'] = $block_fields['edited_permissions'];
		}
		
		$box_block_query_set['time'] = _NOWTIME;
		
		if($new_block && !empty($block_query_set))
		{
			$result1 = $db->table(BLOCKS_TABLE)
				->insert($block_query_set);
			
			$bid = intval($db->lastInsertId());
		}
		else
		{
			$result1 = $db->table(BLOCKS_TABLE)
				->where('bid', $bid)
				->update($block_query_set);
		}
		
		$row = $db->table(BLOCKS_BOXES_TABLE)
						->where('box_id', $box_id)
						->first();
		
		$box_blocks = (isset($row['box_blocks']) && $row['box_blocks'] != '') ? explode(",", $row['box_blocks']):array();
		$box_blocks_data = (isset($row['box_blocks_data']) && $row['box_blocks_data'] != '') ? phpnuke_unserialize(stripslashes($row['box_blocks_data'])):array();
					
		if(!in_array($bid, $box_blocks))
			$box_blocks[] = $bid;
		
		$box_blocks_data[$bid] = array(
			"title"				=> (isset($box_block_query_set['title'])) ? $box_block_query_set['title']:'',
			"lang_titles"		=> (isset($box_block_query_set['lang_titles'])) ? $box_block_query_set['lang_titles']:'',
			"blanguage"			=> (isset($box_block_query_set['blanguage'])) ? $box_block_query_set['blanguage']:'',
			"weight"			=> (sizeof($box_blocks_data)+1),
			"active"			=> (isset($box_block_query_set['active'])) ? $box_block_query_set['active']:1,
			"time"				=> (isset($box_block_query_set['time'])) ? $box_block_query_set['time']:_NOWTIME,
			"permissions"		=> (isset($box_block_query_set['permissions'])) ? $box_block_query_set['permissions']:0,
			"publish"			=> (isset($box_block_query_set['publish'])) ? $box_block_query_set['publish']:0,
			"expire"			=> (isset($box_block_query_set['expire'])) ? $box_block_query_set['expire']:0,
			"action"			=> (isset($box_block_query_set['action'])) ? $box_block_query_set['action']:'',
			"theme_block"		=> (isset($box_block_query_set['theme_block'])) ? $box_block_query_set['theme_block']:''
		);
		
		$box_blocks = implode(",",$box_blocks);
		$box_blocks_data = addslashes(phpnuke_serialize($box_blocks_data));
		
		$result2 = $db->table(BLOCKS_BOXES_TABLE)
			->where('box_id', $box_id)
			->update([
				'box_blocks' => $box_blocks,
				'box_blocks_data' => $box_blocks_data,
			]);
					
		if($result1 && $result2)
		{
			cache_system("nuke_blocks");
			
			$lang_titles = (isset($box_block_query_set['lang_titles'])) ? phpnuke_unserialize(stripslashes($box_block_query_set['lang_titles'])):'';
			$title = (isset($box_block_query_set['lang_titles'])) ? $box_block_query_set['title']:$block_query_set['title'];
			
			$response = json_encode(array(
				"status" => "success",
				"message" => (($new_block) ? _BLOCK_ADD_MSG:_BLOCK_EDIT_MSG),
				"new_bid" => $bid,
				"new_title" => (($nuke_configs['multilingual'] == 1 && $lang_titles != '') ? $lang_titles[$nuke_configs['currentlang']]:$title),
				"new_expire" => ((isset($box_block_query_set['expire']) && $box_block_query_set['expire'] != 0) ? nuketimes($block_fields['edited_expire'], true, true, true, 1):"")
			));
		}
		else
		{
			$response = json_encode(array(
				"status" => "error",
				"message" => ""._ERROR_IN_OP." : ".$db->getErrors('last')['message'].""
			));
		}
		
		$nuke_blocks_cacheData = get_cache_file_contents('nuke_blocks');
		
		$block_title = $nuke_blocks_cacheData['blocks'][$bid]['blockfile'];
		add_log(sprintf(_BLOCK_EDIT_LOG, $block_title, $box_id), 1);
		
		die($response);
	}

	function HeadlinesAdmin()
	{
		global $db, $hooks, $admin_file, $nuke_headlines_cacheData, $nuke_configs;
		
		$hooks->add_filter("set_page_title", function(){return array("HeadlinesAdmin" => _HEADLINESADMIN);});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><font class=\"title\"><b>"._HEADLINESADMIN."</b></font></div>";
		$contents .= CloseAdminTable();
		$contents .= "<br />";
		$contents .= OpenAdminTable();
		$contents .= "<form action=\"".$admin_file.".php\" method=\"post\">"
		."<table border=\"1\" width=\"100%\" align=\"center\" class=\"product-table\"><tr>"
		."<th class=\"table-header-repeat line-left\">"._SITENAME."</th>"
		."<th class=\"table-header-repeat line-left\">"._URL."</th>"
		."<th class=\"table-header-repeat line-left\" style=\"width:100px;\">"._OPERATION."</th><tr>";
		foreach ($nuke_headlines_cacheData as $hid => $nuke_headlines_info) {
			$sitename = filter($nuke_headlines_info['sitename'], "nohtml");
			$headlinesurl = filter($nuke_headlines_info['headlinesurl'], "nohtml");
			$contents .= "<td align=\"center\">$sitename</td>"
			."<td align=\"center\"><a href=\"$headlinesurl\" target=\"new\">$headlinesurl</a></td>"
			."<td align=\"center\"><a href=\"".$admin_file.".php?op=HeadlinesEdit&amp;hid=$hid\" class=\"table-icon icon-1 info-tooltip\" title=\""._EDIT."\"></a> <a class=\"table-icon icon-2 info-tooltip\" href=\"".$admin_file.".php?op=HeadlinesDel&amp;hid=$hid&ok=1&csrf_token="._PN_CSRF_TOKEN."\" onclick=\"return confirm('"._CONFIRM_HEADLINE_DELETE."');\" title=\""._DELETE."\"></a></td><tr>";
		}
		$contents .= "<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> </form></td></tr></table>";
		$contents .= CloseAdminTable();
		$contents .= "<br />";
		$contents .= OpenAdminTable();
		$contents .= "<font class=\"option\"><b>"._ADDHEADLINE."</b></font><br /><br />"
		."<font class=\"content\">"
		."<form action=\"".$admin_file.".php\" method=\"post\">"
		."<table border=\"0\" width=\"100%\" class=\"id-form\">"
		."<tr><th style=\"width:200px;\">"._SITENAME.":</th><td><input type=\"text\" class=\"inp-form\" name=\"xsitename\" size=\"31\" maxlength=\"30\"></td></tr>"
		."<tr><th>"._RSSFILE.":</th><td><input type=\"text\" class=\"inp-form\" name=\"headlinesurl\" size=\"50\" maxlength=\"200\"></td></tr>"
		."<tr><td><input class=\"form-submit\" type=\"submit\" value=\""._ADD."\"></td></tr></table>"
		."<input type=\"hidden\" name=\"op\" value=\"HeadlinesAdd\"><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> "
		."</form>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function HeadlinesAdd($xsitename, $headlinesurl)
	{
		global $db, $admin_file, $nuke_configs;
		$xsitename = filter($xsitename, "nohtml", 1);
		$headlinesurl = filter($headlinesurl, "nohtml", 1);
		$xsitename = @str_replace(" ", "", $xsitename);
		$db->table(HEADLINES_TABLE)
			->insert([
				'sitename' => $xsitename,
				'headlinesurl' => $headlinesurl,
			]);
		cache_system('nuke_headlines');
		
		add_log(sprintf(_ADDHEADLINE_OK, $xsitename), 1);
		Header("Location: ".$admin_file.".php?op=HeadlinesAdmin");
	}

	function HeadlinesEdit($hid)
	{
		global $db, $hooks, $admin_file, $nuke_headlines_cacheData, $nuke_configs;
		
		$xsitename = filter($nuke_headlines_cacheData[$hid]['sitename'], "nohtml");
		$headlinesurl = filter($nuke_headlines_cacheData[$hid]['headlinesurl'], "nohtml");
		
		$hooks->add_filter("set_page_title", function() use($xsitename){return array("HeadlinesEdit" => sprintf(_EDITHEADLINE_OK, $xsitename));});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$contents .= "<br /><br /><div class=\"text-center\"><font class=\"title\"><b>"._HEADLINESADMIN."</b></font></div>";
		$contents .= CloseAdminTable();
		$contents .= "<br /><br />";
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><font class=\"option\"><b>"._EDITHEADLINE."</b></font></div>
	<form action=\"".$admin_file.".php\" method=\"post\">
	<input type=\"hidden\" name=\"hid\" value=\"$hid\">
	<table border=\"0\" width=\"100%\" class=\"id-form\">
	<tr><th>"._SITENAME.":</th><td><input class=\"inp-form\" type=\"text\" name=\"xsitename\" size=\"31\" maxlength=\"30\" value=\"$xsitename\"></td></tr>
	<tr><td>"._RSSFILE.":</td><td><input class=\"inp-form\" type=\"text\" name=\"headlinesurl\" size=\"50\" maxlength=\"200\" value=\"$headlinesurl\"></td></tr><tr><td><input class=\"form-submit\" type=\"submit\" value=\""._SAVECHANGES."\"></td></tr></table>
	<input type=\"hidden\" name=\"op\" value=\"HeadlinesSave\">
	<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
	</form>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output = $contents;
		include("footer.php");
	}

	function HeadlinesSave($hid, $xsitename, $headlinesurl)
	{
		global $db, $admin_file;
		$hid = intval($hid);
		$xsitename = filter($xsitename, "nohtml", 1);
		$headlinesurl = filter($headlinesurl, "nohtml", 1);
		$xsitename = @str_replace(" ", "", $xsitename);
		$db->table(HEADLINES_TABLE)
			->where('hid', $hid)
			->update([
				'sitename' => $xsitename,
				'headlinesurl' => $headlinesurl,
			]);
		
		cache_system('nuke_headlines');
		add_log(sprintf(_EDITHEADLINE_OK, $xsitename), 1);
		Header("Location: ".$admin_file.".php?op=HeadlinesAdmin");
	}

	function HeadlinesDel($hid)
	{
		csrfProtector::authorisePost(true);
		global $db, $admin_file;
		$hid = intval($hid);
		$db->table(HEADLINES_TABLE)
			->where('hid', $hid)
			->delete();
		phpnuke_auto_increment(HEADLINES_TABLE);
		cache_system('nuke_headlines');
		add_log(sprintf(_DELETEHEADLINE_OK, $xsitename), 1);
		header("Location: ".$admin_file.".php?op=HeadlinesAdmin");
	}
	
	$bid = (isset($bid)) ? intval($bid):0;
	$hid = (isset($hid)) ? intval($hid):0;
	$box_id = (isset($box_id)) ? filter($box_id, "nohtml"):'all';
	$xsitename = (isset($xsitename)) ? filter($xsitename, "nohtml"):'';
	$headlinesurl = (isset($headlinesurl)) ? filter($headlinesurl, "nohtml"):'';
	$block_type = (isset($block_type)) ? filter($block_type, "nohtml"):'';
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'';
	$new_box_title = (isset($new_box_title)) ? filter($new_box_title, "nohtml"):'';
	$new_box_theme_location = (isset($new_box_theme_location)) ? filter($new_box_theme_location, "nohtml"):'';
	$new_box_theme_priority = (isset($new_box_theme_priority)) ? filter($new_box_theme_priority, "nohtml"):'';
	$new_box_status = (isset($new_box_status)) ? filter($new_box_status, "nohtml"):'';
	$new_block = (isset($new_block)) ? $new_block:false;
	$all_box_ids = (isset($all_box_ids)) ? $all_box_ids:array();
	$block_fields = (isset($block_fields)) ? $block_fields:array();
	$bids_weight = (isset($bids_weight)) ? $bids_weight:array();
	$block_id = (isset($block_id)) ? $block_id:0;
	
	switch($op) {
		case "BlocksAdmin":
		BlocksAdmin();
		break;
		
		case "BlocksEdit":
		BlocksEdit($box_id, $bid, $block_type, $all_box_ids);
		break;

		case "BlocksSave":
		BlocksSave($block_fields, $box_id, $bid, $new_block);
		break;

		case "HeadlinesDel":
		HeadlinesDel($hid);
		break;

		case "HeadlinesAdd":
		HeadlinesAdd($xsitename, $headlinesurl);
		break;

		case "HeadlinesSave":
		HeadlinesSave($hid, $xsitename, $headlinesurl);
		break;

		case "HeadlinesAdmin":
		HeadlinesAdmin();
		break;

		case "HeadlinesEdit":
		HeadlinesEdit($hid);
		break;
		
		case "updateweight":
		updateweight($box_id, $block_id, $bids_weight, $mode, $new_box_title, $new_box_theme_location, $new_box_theme_priority, $new_box_status);
		break;

		case "remove_block":
		remove_block($box_id, $bid);
		break;

		case "info_block":
		info_block($box_id, $bid);
		break;

		case "change_block_status":
		change_block_status($box_id, $bid);
		break;

		case "preview_block":
		preview_block($box_id, $bid);
		break;
	}

}
else
{
	header("location: ".$admin_file.".php");
}

?>