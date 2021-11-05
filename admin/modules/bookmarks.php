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

	function updatebookmarksweight($bookmarks_ids){
		global $db;
		$listingCounter = 1;
		foreach ($bookmarks_ids as $key => $recordIDValue) {
			$recordIDValue = str_replace("bookmark_", "", $recordIDValue);
			$bookmarks_query[] = "WHEN bid = '$recordIDValue' THEN '".$listingCounter."'";
			$bids[] = $recordIDValue;
			$listingCounter++;	
		}
		//$bids = implode(",", $bids);
		$bookmarks_query = implode(" \n", $bookmarks_query);
		
		$result = $db->query("UPDATE ".BOOKMARKSITE_TABLE." SET weight = CASE $bookmarks_query END WHERE bid IN (".implode(",",$bids).")");

		if($result)
		{
			cache_system("nuke_bookmarksite");
			
			$response = json_encode(array(
				"status" => "success",
				"message" => _WEIGHTS_UPDATED
			));
		}
		else
		{
			$response = json_encode(array(
				"status" => "error",
				"message" => ""._ERROR_IN_OP." ".$db->getErrors('last')['message'].""
			));
		}
		add_log(_BOOKMARKS_WEIGHTS_UPDATE, 1);
		die($response);
	}

	function bookmarks() {
		global $db, $hooks, $admin_file;	
		
		$result = $db->table(BOOKMARKSITE_TABLE)
			->order_by(['bid' => 'ASC'])
			->select();
			
		$nuke_bookmarksite_cacheData = array();
		if($db->count() > 0)
		{
			foreach($result as $row){
				$bid = $row['bid'];
				$nuke_bookmarksite_cacheData[$bid] = $row;
			}
		}
		
		$hooks->add_filter("set_page_title", function(){return array("HeadlinesEdit" => _BOOKMARKS_ADMIN);});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$contents .="
		<div align=\"center\"><br /><br /><br /><br />
			<form action=\"".$admin_file.".php\" method=\"post\">
				<table align=\"center\" border=\"0\" width=\"100%\" class=\"id-form product-table no-border\">
					<tr>
						<th style=\"width:120px;\">"._TITLE."</th>
						<td><input name=\"title\" size=\"40\" dir=\"rtl\" type=\"text\" class=\"inp-form\"></td>
					</tr>
					<tr>
						<th>"._ICON_LINK."</th>
						<td><input name=\"iconpath\" size=\"40\" type=\"text\" class=\"inp-form-ltr\"></td>
					</tr>
					<tr>
						<th>"._REFERRAL_LINK."</th>
						<td>
							<input name=\"url\" size=\"60\" type=\"text\" class=\"inp-form-ltr\" placeholder=\"http://bookmark.com/link.php?title={TITLE}&url={URL}\">
							".bubble_show(_REFERRAL_LINK_DESC)."
						</td>
					</tr>
					<tr>
						<th>"._ACTIVE."</th>
						<td><label for=\"active-bookmark\">"._YES."</label><input name=\"active\" checked type=\"radio\" class=\"styled\" value=\"1\" id=\"active-bookmark\"> &nbsp;&nbsp; <label for=\"inactive-bookmark\">"._NO."</label><input name=\"active\" type=\"radio\" class=\"styled\" value=\"0\" id=\"inactive-bookmark\"></td>
					</tr>
					<tr>
						<td colspan=\"2\" align=\"center\">
						<input type=\"submit\" value=\"1\" name=\"save\" class=\"form-submit\">
						</td>
					</tr>
					<tr>
						<td id=\"result\" colspan=\"2\" align=\"center\"></td>
					</tr>
				</table>
				<input type=\"hidden\" name=\"op\" value=\"save_bookmarks\"><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> </form>
			</div>
			<div id=\"update_result\"></div><br />
			<table align=\"center\" class=\"product-table\" width=\"100%\" id=\"bookmarks-table\">
			<thead>
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:100px;\"><a>"._ICON."</a></th>
				<th class=\"table-header-repeat line-left\"><a>عنوان</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a>"._OPERATION."</a></th>
			</tr>
			</thead>
			<tbody>";
			$nuke_bookmarksite_cacheData_by_weight = array_sort_by_values($nuke_bookmarksite_cacheData, 'bid', 'weight', $mode="asc");

			foreach($nuke_bookmarksite_cacheData_by_weight as $weight => $nuke_bookmarksite_info){
				$bid = intval($nuke_bookmarksite_info['bid']);
				$title = filter($nuke_bookmarksite_info['title'], "nohtml");
				$iconpath = filter($nuke_bookmarksite_info['iconpath'], "nohtml");
				$url = filter($nuke_bookmarksite_info['url']);
				$contents .= "<tr id=\"bookmark_$bid\">
					<td align=\"center\"><img src=\"$iconpath\"></td>
					<td>$title</td>
					<td><a href=\"".$admin_file.".php?op=editbookmark&bid=$bid\" title=\""._EDIT."\" class=\"table-icon icon-1 info-tooltip\"></a> <a href=\"".$admin_file.".php?op=save_bookmarks&delete=1&bid=$bid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\"></a></td>
					</tr>";
			}
			$contents .= "
			</tbody>
			</table>
			<script>
				$('#bookmarks-table tbody').sortable({
					opacity: 0.75,
					update: function(){
						echo_message('', true, false);
						var arr = $(this).sortable('toArray');
						$.ajax({
							type : 'post',
							url : '".$admin_file.".php',
							data : {'op' : 'updatebookmarksweight', 'bookmarks_ids': arr, csrf_token : pn_csrf_token},
							success: function(theResponse){
								theResponse = typeof theResponse === 'object' ? theResponse : JSON.parse(theResponse);
								echo_message(theResponse, false, false, 5000);
							}
						});
					}
				});
			</script>";

		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function editbookmark($bid) {
		global $db, $hooks, $admin_file;
		
		$row = $db->table(BOOKMARKSITE_TABLE)
			->where('bid', $bid)
			->first();
		
		$contents = '';
		$title = filter($row['title'], "nohtml");
		$iconpath = filter($row['iconpath'], "nohtml");
		$url = filter($row['url']);
		$active = intval($row['active']);
		$weight = intval($row['weight']);
		$sel1 = ($active == 1) ? "checked":"";
		$sel2 = ($active == 0) ? "checked":"";
		
		$hooks->add_filter("set_page_title", function() use($title){return array("HeadlinesEdit" => sprintf(_BOOKMARK_EDIT, $title));});
		
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();

		$contents .= "
			<div align=\"center\">
			<form action=\"".$admin_file.".php\" method=\"post\">
			<table align=\"center\" border=\"0\" width=\"100%\" class=\"id-form product-table no-border\">
				<tr>
					<th style=\"width:150px;\">"._TITLE."</th>
					<td><input name=\"title\" value=\"$title\" class=\"inp-form\" size=\"40\" dir=\"rtl\" type=\"text\"></td>
				</tr>
				<tr>
					<th>"._ICON_LINK."</th>
					<td><input name=\"iconpath\" value=\"$iconpath\" class=\"inp-form-ltr\" size=\"40\" type=\"text\"></td>
				</tr>
				<tr>
					<th>"._REFERRAL_LINK."</th>
					<td>
						<input name=\"url\" size=\"60\" type=\"text\" class=\"inp-form-ltr\" value=\"$url\" placeholder=\"http://bookmark.com/link.php?title={TITLE}&url={URL}\">
						".bubble_show(_REFERRAL_LINK_DESC)."</td>
				</tr>
				<tr>
					<th>"._ACTIVE."</th>
					<td><label for=\"active-bookmark\">"._YES."</label><input name=\"active\" checked type=\"radio\" class=\"styled\" value=\"1\" id=\"active-bookmark\" $sel1> &nbsp;&nbsp; <label for=\"inactive-bookmark\">"._NO."</label><input name=\"active\" type=\"radio\" class=\"styled\" value=\"0\" id=\"inactive-bookmark\" $sel2></td>
				</tr>
				<tr>
					<td colspan=\"2\" align=\"center\">
					<input type=\"submit\" value=\"1\" name=\"update\" class=\"form-submit\">
					</td>
				</tr>
				<tr>
					<td id=\"result\" colspan=\"2\" align=\"center\"></td>
				</tr>
			</table><input type=\"hidden\" name=\"op\" value=\"save_bookmarks\"><input type=\"hidden\" name=\"bid\" value=\"$bid\"><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> </form></div>
		</table>";

		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function save_bookmarks($title, $iconpath, $url, $active, $save, $update, $delete, $bid){
		global $db, $admin_file;
		
		$op = "insert";
		
		if(isset($update) && $update == 1 && intval($bid) != 0)
			$op = "update";
		
		if(isset($delete) && $delete == 1 && intval($delete) != 0)
			$op = "delete";
		
		
		if($op == 'insert')
		{
			$result1 = $db->query("SELECT MAX(weight) as last_weight FROM ".BOOKMARKSITE_TABLE."");
			$row1 = $result1->results()[0];
			$last_weight = intval($row1['last_weight']);
			$last_weight++;	

			$result = $db->table(BOOKMARKSITE_TABLE)
				->insert([
					'title' => $title,
					'iconpath' => $iconpath,
					'active' => $active,
					'url' => $url,
					'weight' => $last_weight,
				]);
				
			add_log(sprintf(_BOOKMARK_ADD_LOG, $title), 1);
		}
		elseif($op == 'update')
		{
			$result = $db->table(BOOKMARKSITE_TABLE)
				->where('bid', $bid)
				->update([
					'title' => $title,
					'iconpath' => $iconpath,
					'active' => $active,
					'url' => $url,
				]);
			add_log(sprintf(_BOOKMARK_EDIT_LOG, $title), 1);
		}
		elseif($op == 'delete')
		{
			csrfProtector::authorisePost(true);
			$result = $db->table(BOOKMARKSITE_TABLE)
				->where('bid', $bid)
				->delete();
			add_log(sprintf(_BOOKMARK_DELETE_LOG, $title), 1);
		}
		cache_system('nuke_bookmarksite');
		header("location: ".$admin_file.".php?op=bookmarks");
	}

	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$title = (isset($title)) ? filter($title, "nohtml"):'';
	$iconpath = (isset($iconpath)) ? filter($iconpath, "nohtml"):'';
	$url = (isset($url)) ? filter($url, "nohtml"):'';
	$active = (isset($active)) ? intval($active):0;
	$save = (isset($save)) ? intval($save):0;
	$update = (isset($update)) ? intval($update):0;
	$delete = (isset($delete)) ? intval($delete):0;
	$bid = (isset($bid)) ? intval($bid):0;
	
	switch ($op) {
		case "bookmarks":
			bookmarks();
		break;
		case "save_bookmarks":
			save_bookmarks($title, $iconpath, $url, $active, $save, $update, $delete, $bid);
		break;
		case "editbookmark":
			editbookmark($bid);
		break;
		case "updatebookmarksweight":
			updatebookmarksweight($bookmarks_ids);
		break;
	}
}
else
{
	header("location: ".$admin_file.".php");
}
?>