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

	function reports($module_name='', $post_id=0, $post_title='', $mode = '', $rids, $search_query = '', $order_by = '', $sort='DESC')
	{
		global $db, $admin_file, $nuke_configs, $users_system, $hooks;
		
		if($mode == "delete")
		{
			csrfProtector::authorisePost(true);
			$deleted_rids = array();
			
			if(is_array($rids) && !empty($rids))
				$deleted_rids = $rids;
			elseif(intval($rids) != 0)
				$deleted_rids = array($rids);
			
			if(!empty($deleted_rids))
			{				
				$result = $db->table(REPORTS_TABLE)
							->where("module", 'comments')
							->select(['rid', 'post_id']);

				if($result->count() > 0)
				{
					$rows = $result->results();
					foreach($rows as $row)
					{
						$post_id = $row['post_id'];
						$rids_arr[$row['rid']] = $post_id;
						if(in_array($row['rid'], $deleted_rids))
						{
							$post_ids[$row['post_id']][] = $row['rid'];
						}
					}
					
					foreach($post_ids as $post_id => $rids_val)
					{
						$counted_array = array_count_values($rids_arr);
						if($counted_array[$post_id] == count($rids_val))
						{
							$db->table(COMMENTS_TABLE)
								->where("cid", $post_id)
								->update(['reported' => 0]);
						}
					}
				}
				
				$db->table(REPORTS_TABLE)
					->in("rid", $deleted_rids)
					->delete();
			}
			header("location: ".$admin_file.".php?op=reports");
			die($mode);
		}
		
		$pagetitle = ""._REPORTS."".(($module_name != '') ? " - "._MODULE." $module_name":"").(($post_title != '') ? " - "._VIEW_REPORTS_OF." « $post_title »":"");
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("reports" => $pagetitle);});
		
		$contents = '';
		$contents .= GraphicAdmin();
		
		$all_modules_reports = array();
		$all_modules_reports = $hooks->apply_filters('modules_have_reports', $all_modules_reports);
		
		$all_modules_reports_link[] = "<option value=\"".$admin_file.".php?op=reports&module_name=comments".(($post_id != 0) ? "&post_id=$post_id":"")."\" ".(($module_name == 'comments') ? "selected":"").">"._COMMENTS."</option>";
		
		foreach($all_modules_reports as $modules_report)
		{
			$sel = ($module_name == $modules_report) ? "selected":"";
			$all_modules_reports_link[] = "<option value=\"".$admin_file.".php?op=reports&module_name=$modules_report".(($post_id != 0) ? "&post_id=$post_id":"")."\" $sel>$modules_report</option>";
		}
		$contents .= "<div align=\"center\" style=\"margin:20px 0;\">"._VIEW_REPORTS_IN_MODULE." <select onchange=\"top.location.href=this.options[this.selectedIndex].value\" class=\"styledselect-select\"><option value=\"".$admin_file.".php?op=reports\">"._ALL."</options>\n".implode("\n", $all_modules_reports_link)."</select></div>";
		
		$link_to_more = '';
		if(isset($search_query) && $search_query != '')
		{
			$where[] = "subject LIKE '%".rawurldecode($search_query)."%' OR message LIKE '%".rawurldecode($search_query)."%' OR post_title LIKE '%".rawurldecode($search_query)."%'";
			$link_to_more = "&search_query=".rawurlencode($search_query)."";
		}
		
		$entries_per_page = 20;
		$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
		$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
		$link_to = "".$admin_file.".php?op=reports".(($module_name != '') ? "&module_name=$module_name":"").(($post_id != 0) ? "&post_id=$post_id&post_title=$post_title":"").$link_to_more;
		
		$where = array();
		$where_values = array();
		$select2 = "";
	
		if($module_name != '')
		{
			$where[] = "r.module =:module_name";
			$where_values[':module_name'] = $module_name;
		}
		if($post_id != 0)
		{
			$where[] = "r.post_id =:post_id";
			$where_values[':post_id'] = $post_id;
		}
		
		$where_values = (!empty($where_values)) ? $where_values:array();
		
		$where = array_filter($where);
		$where_values = array_filter($where_values);

		$where = (!empty($where)) ? "WHERE ".implode(" AND ", $where):'';

		$sort = ($sort != '') ? $sort:"DESC";
		$order_by = ($order_by != '' && in_array($order_by, array("rid", "post_title", "subject", "module", "time", "user_id"))) ? filter($order_by, "nohtml"):'rid';
		$sort_reverse = ($sort == 'ASC') ? "DESC":"ASC";	
		
		if(!isset($post_id) OR $post_id == 0)
		{
			$select2 = ", (SELECT COUNT(r3.rid) FROM ".REPORTS_TABLE." AS r3".(($where != "") ? " ".str_replace("r.","r3.", $where)." AND r3.post_id = r.post_id":" WHERE r3.post_id = r.post_id").") as total_post_reports";
		}
		
		$total_rows = 0;
		$position = 0;
		$result = $db->query("
			SELECT r.*, 
			u.".$users_system->user_fields['username']." as username,
			(SELECT COUNT(r2.rid) FROM ".REPORTS_TABLE." AS r2".(($where != "") ? " ".str_replace("r.","r2.", $where):"").") as total_rows
			$select2 
			FROM ".REPORTS_TABLE." AS r 
			LEFT JOIN ".$users_system->users_table." AS u ON u.".$users_system->user_fields['user_id']." = r.user_id 
			".(($where != "") ? " $where":"")."
			ORDER BY r.$order_by $sort LIMIT :start_at,:entries_per_page", array_merge($where_values, [':start_at' => (int) $start_at, ':entries_per_page' => (int) $entries_per_page]));

		$contents .= OpenAdminTable();
		$contents .= "
			<table align=\"center\" class=\"product-table no-border no-hover\" width=\"100%\">
				<tr>
					<form action=\"".$admin_file.".php\" method=\"post\">
						<td>
							<input class=\"inp-form\" type=\"text\" name=\"search_query\" size=\"30\" value=\"$search_query\" />
							<input type=\"hidden\" value=\"reports\" name=\"op\" />
							<input type=\"hidden\" name=\"order_by\" value=\"$order_by\" />
							<input type=\"hidden\" name=\"sort\" value=\"$sort\" />
							<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
							<input type=\"submit\" class=\"form-search\" value=\""._SEARCH."\" />
							".bubble_show(_SEARCH_IN_REPORTS)."
						</td>
					</form>
				</tr>
			</table>
			<form action=\"".$admin_file.".php\" method=\"post\">
			<table align=\"center\" class=\"product-table\" width=\"100%\" id=\"reports\">
			<thead>
			<tr>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=reports&order_by=rid&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'rid') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._ID."</a></th>
				<th class=\"table-header-repeat line-left\"><a href=\"".$admin_file.".php?op=reports&order_by=post_title&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'post_title') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._POSTTITLE."</a></th>
				<th class=\"table-header-repeat line-left\"><a href=\"".$admin_file.".php?op=reports&order_by=subject&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'subject') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._REPORTTITLE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=reports&order_by=module&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'module') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._MODULE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a href=\"".$admin_file.".php?op=reports&order_by=time&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'time') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._DATE."</a></th>
				<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a href=\"".$admin_file.".php?op=reports&order_by=user_id&sort=".$sort_reverse."".$link_to_more."\"".(($order_by == 'user_id') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._AUTHOR."</a></th>
				<th class=\"table-header-repeat line-left no-padding\" style=\"text-align:center;width:120px;\"><input data-label=\""._OPERATION."\" type=\"checkbox\" class=\"styled select-all\" data-element=\"#reports\"></th>
			</tr>
			</thead>
			<tbody>";
			
			if($db->count() > 0)
			{
				foreach($result as $row)
				{
					$total_rows = intval($row['total_rows']);
					$rid = intval($row['rid']);
					$total_post_reports = (isset($row['total_post_reports'])) ? " <span style=\"background:#ccc;border-radius:5px;padding:2px 5px;display:inline-block;\">".intval($row['total_post_reports'])."</span>":0;
					$subject = stripslashes($row['subject']);
					$message = str_replace(array("\n","\r","\t"),"", strip_tags($row['message']));
					$module = filter($row['module'], "nohtml");
					$post_title = filter($row['post_title'], "nohtml");
					$post_id = intval($row['post_id']);
					$time = nuketimes($row['time'], true, true, true, 1);
					$username = filter($row['username'], "nohtml");
					$ip = filter($row['ip'], "nohtml");
					$ip_info = "http://whatismyipaddress.com/ip/$ip";	
					$post_link = $row['post_link'];
					$username_link = ($username == $ip) ? $ip_info:LinkToGT(sprintf($users_system->profile_url, '', $username));
					if($post_link == '')
						$post_link = $hooks->apply_filters("get_post_link", $post_link, $module, $post_id);
					
					$module_link_to = "".$admin_file.".php?op=reports&module_name=$module";
					
					$contents .= "<tr>
						<td>$rid</td>
						<td>".(($post_link != '') ? "<a href=\"$post_link\" target=\"_blank\">$post_title </a>":"$post_title ")."".((isset($row['total_post_reports']) && intval($row['total_post_reports']) != 0) ? " <a href=\"$module_link_to&post_id=$post_id&post_title=$post_title\">$total_post_reports<a/>":"")."</td>
						<td>$subject</td>
						<td align=\"center\"><a href=\"$module_link_to\">$module</a></td>
						<td align=\"center\">$time</td>
						<td align=\"center\">
							<a href=\"$username_link\" target=\"_blank\">$username</a>
							<br />
							<a href=\"$ip_info\" target=\"_blank\" rel=\"nofollow\">$ip</a>
						</td>
						<td align=\"center\">
							<a href=\"".$admin_file.".php?op=reports&mode=delete&rids=$rid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._REPORT_DELETE_CONFIRM."');\"></a>
							<a href=\"#\" title=\""._VIEW."\" class=\"table-icon icon-7 info-tooltip showindialog\" data-message=\"$message\"></a>
							<input type=\"checkbox\" class=\"styled\" name=\"rids[]\" value=\"$rid\" />
						</td>
						</tr>";
				}
			}
			$contents .= "
				<tr>
					<td colspan=\"7\" align=\"center\"><input type=\"submit\" value=\""._DELETE_SELECTED."\" onclick=\"return confirm('"._REPORTS_DELETE_CONFIRM."');\" /></td>
				</tr>
			</tbody>
		</table>
		<input type=\"hidden\" value=\"delete\" name=\"mode\" />
		<input type=\"hidden\" value=\"reports\" name=\"op\" />
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		</form>
		<div id=\"pagination\" class=\"pagination\">";
		$contents .= admin_pagination($total_rows, $entries_per_page, $current_page, $link_to);
		$contents .= "</div>
		<div id=\"reports-dialog\"></div>
		<script>
			$(\".showindialog\").click(function(e)
			{
				e.preventDefault();
				var message = $(this).data('message');
				$(\"#reports-dialog\").html(message);

				$(\"#reports-dialog\").dialog({
					title: '"._VIEW_REPORT."',
					resizable: false,
					height: 300,
					width: 400,
					modal: true,
					closeOnEscape: true,
					close: function(event, ui)
					{
						$(this).dialog('destroy');
						$(\"#reports-dialog\").html('');
					}
				});
			});
		</script>";

		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'';
	$module_name = (isset($module_name)) ? filter($module_name, "nohtml"):'';
	$post_title = (isset($post_title)) ? filter($post_title, "nohtml"):'';
	$post_id = (isset($post_id)) ? intval($post_id):0;
	$rids = (isset($rids)) ? $rids:'';
	$order_by = (isset($order_by)) ? filter($order_by, "nohtml"):'';
	$sort = (isset($sort)) ? filter($sort, "nohtml"):'';
	$search_query = (isset($search_query)) ? filter($search_query, "nohtml"):'';
	
	switch ($op) {
		case "reports":
			reports($module_name, $post_id, $post_title, $mode, $rids, $search_query, $order_by, $sort);
		break;
	}
}
else
{
	header("location: ".$admin_file.".php");
}
?>