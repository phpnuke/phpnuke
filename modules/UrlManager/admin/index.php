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
	
	function urlmanager($meid = 0, $submit = '', $mode = 'new', $UrlManager_fields = array())
	{
		global $db, $hooks, $admin_file, $nuke_configs;	

		if($mode == "delete")
		{
			$result = $db->table(POSTS_META_TABLE)
				->where('mid', $meid)
				->delete();
			redirect_to("".$admin_file.".php?op=urlmanager");
			die();
		}
		
		if(isset($submit) && isset($UrlManager_fields) && !empty($UrlManager_fields))
		{
			$old_url = str_replace($nuke_configs['nukeurl'], "", $UrlManager_fields['old_url']);
			$new_url = str_replace($nuke_configs['nukeurl'], "", $UrlManager_fields['new_url']);
		
			$old_url = trim($old_url, "/");
			
			$post_id = 0;
			$errors = array();
			if(intval($new_url) === $new_url)//sid number
			{
				$post_id = $new_url;
				$new_url = '';
			}
			
			$old_url_encoded = rawurlencode_map($old_url);
			$result = $db->query("SELECT mid FROM ".POSTS_META_TABLE." WHERE meta_part = 'old_urls' AND (meta_key = '$old_url' OR meta_key = '$old_url_encoded')");
			
			$row = array();
			if($result->count() > 0)
			{
				$row = $result->results()[0];
			}
			
			$data = array(
				"meta_part" => "old_urls",
				"post_id" => $post_id,
				"meta_key" => $old_url,
				"meta_value" => phpnuke_serialize(["code" => $UrlManager_fields['code'], "url" => $new_url]),
			);
			
			if($meid == 0)//new redirect
			{
				if($result->count() > 0)//old url is exist
				{
					$errors[] = _OLD_URL_EXIST;
				}
				else
				{
					$db->table(POSTS_META_TABLE)
						->insert($data);
				}
				
			}
			else//edit redirect
			{
				if($result->count() > 0)
				{
					if($meid != $row['mid'])
					{
						$errors[] = _OLD_URL_EXIST;
					}
				}
				
				if(empty($errors))
				{
					$db->table(POSTS_META_TABLE)
						->where('mid', $meid)
						->update($data);
				}
			}
		
			if(!empty($errors))
			{
				$hooks->add_filter("set_page_title", function(){return array("urlmanager" => _URLS_MANAGER);});
				include("header.php");
				$html_output .= GraphicAdmin();
				$html_output .= OpenAdminTable();
				$html_output .= "<p align=\"center\">"._ERROR_IN_OP." : ".implode("<br />", $errors)." <br /><br /><a href=\"".$admin_file.".php?op=urlmanager&meid=".$row['mid']."\">"._EDIT."</a></p>";
				$html_output .= CloseAdminTable();
				include("footer.php");
			}
		
			redirect_to("".$admin_file.".php?op=urlmanager");
			die();
		}
		
		$total_rows = 0;
		$entries_per_page = 20;
		$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
		$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
		$link_to = "".$admin_file.".php?op=urlmanager";
		
		$row = array(
			"mid" => 0,
			"meta_key" => '',
			"meta_value" => phpnuke_serialize(['code' => 302, 'url' => '']),
		);
		
		if($meid == 0)
		{
			$result = $db->query("SELECT *, (SELECT COUNT(mid) FROM ".POSTS_META_TABLE." WHERE meta_part = 'old_urls') as total_rows FROM ".POSTS_META_TABLE." WHERE meta_part = 'old_urls' ORDER BY mid DESC LIMIT $start_at, $entries_per_page");
		}
		else
		{
			$result = $db->table(POSTS_META_TABLE)
				->where('mid', $meid)
				->select();
			if($result->count() > 0)
				$row = $result->results()[0];
		}
		
		$row['meta_value'] = phpnuke_unserialize($row['meta_value']);
		$new_url = $row['meta_value']['url'];
		$code = intval($row['meta_value']['code']);
		
		$sel_301 = ($code == 301) ? "selected":"";
		$sel_302 = ($code == 302) ? "selected":"";
		$sel_303 = ($code == 303) ? "selected":"";
		$sel_410 = ($code == 410) ? "selected":"";
		
		$hooks->add_filter("set_page_title", function(){return array("urlmanager" => _URLS_MANAGER);});
		
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$contents .="
		<div align=\"center\"><br /><br /><br /><br />
			<form action=\"".$admin_file.".php\" method=\"post\">
				<table align=\"center\" border=\"0\" width=\"100%\" class=\"id-form product-table no-border\">
					<tr>
						<th>"._OLD_URL."</th>
						<td><input name=\"UrlManager_fields[old_url]\" size=\"150\" dir=\"rtl\" type=\"text\" class=\"inp-form-ltr\" value=\"".$row['meta_key']."\" ></td>
					</tr>
					<tr>
						<th>"._NEW_URL."</th>
						<td><input name=\"UrlManager_fields[new_url]\" size=\"150\" type=\"text\" class=\"inp-form-ltr\" value=\"".$new_url."\" ></td>
					</tr>
					<tr>
						<th>"._REDIRECT_CODE."</th>
						<td>
							<select class=\"styledselect-select\" name=\"UrlManager_fields[code]\">
								<option value=\"301\" $sel_301>301</option>
								<option value=\"302\" $sel_302>302</option>
								<option value=\"303\" $sel_303>303</option>
								<option value=\"410\" $sel_410>410</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan=\"2\" align=\"center\">
						<input type=\"submit\" value=\""._SEND."\" name=\"save\" class=\"form-submit\">
						</td>
					</tr>
					<tr>
						<td id=\"result\" colspan=\"2\" align=\"center\"></td>
					</tr>
				</table>
				<input type=\"hidden\" name=\"op\" value=\"urlmanager\">
				<input type=\"hidden\" name=\"meid\" value=\"".$row['mid']."\">
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" />
			</form>
		</div>";
		if($meid == 0)
		{
			$contents .="<table align=\"center\" class=\"product-table\" width=\"100%\" id=\"bookmarks-table\">
				<thead>
				<tr>
					<th class=\"table-header-repeat line-left\"><a>"._OLD_URL."</a></th>
					<th class=\"table-header-repeat line-left\"><a>"._NEW_URL."</a></th>
					<th class=\"table-header-repeat line-left\"><a>"._REDIRECT_CODE."</a></th>
					<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a>"._OPERATION."</a></th>
				</tr>
				</thead>
				<tbody>";
				if($result->count() > 0)
				{
					$rows = $result->results();
					foreach($rows as $row)
					{
						$row['meta_value'] = phpnuke_unserialize($row['meta_value']);
						$new_url = $row['meta_value']['url'];
						$code = intval($row['meta_value']['code']);
						
						$contents .= "<tr>
						<td><a href=\"".trim($row['meta_key'], "/")."/\">".$row['meta_key']."</a></td>
						<td><a href=\"".trim($new_url, "/")."/\">".$new_url."</a></td>
						<td>$code</td>
						<td><a href=\"".$admin_file.".php?op=urlmanager&meid=".$row['mid']."\" title=\""._EDIT."\" class=\"table-icon icon-1 info-tooltip\"></a> <a href=\"".$admin_file.".php?op=urlmanager&mode=delete&meid=".$row['mid']."&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" onclick=\"return confirm('"._DELETE_LINK."');\" class=\"table-icon icon-2 info-tooltip\"></a></td>
						</tr>";
					}
				}
				$contents .= "
				</tbody>
				</table>";
			
			if(isset($row['total_rows']) && $row['total_rows'] > 0)
			{
				$contents .= "<div id=\"pagination\" class=\"pagination\">";
				$contents .= admin_pagination($row['total_rows'], $entries_per_page, $current_page, $link_to);
				$contents .= "</div>";
			}
			else
				$contents .= "<p align=\"center\">"._NO_ARTICLE_FOUND."</p>";
		}
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
		
	global $pn_prefix;
	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$submit = filter(request_var('submit', '', '_POST'), "nohtml");
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'new';
	$UrlManager_fields = request_var('UrlManager_fields', array(), '_POST');
	$meid = (isset($meid)) ? intval($meid):0;
	
	switch($op)
	{
		default:
		case"mrlmanager":
			urlmanager($meid, $submit, $mode, $UrlManager_fields);
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