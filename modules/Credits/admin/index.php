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
	define("MODULE_FILE", true);

	$pn_credits_config = (isset($pn_credits_config) && !empty($pn_credits_config)) ? $pn_credits_config:((isset($nuke_configs['pn_credits']) && $nuke_configs['pn_credits'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['pn_credits'])):array());

	function credits_menu()
	{
		global $db, $hooks, $admin_file, $nuke_configs, $page;
		$contents = "
		<p align=\"center\" style=\"padding:20px; 0;\">[ 
			<a href=\"".$admin_file.".php?op=credits\">"._CREDITS_ADMIN."</a> | 
			<a href=\"".$admin_file.".php?op=credits_list\">"._CREDITS_LIST."</a> | 
			<a href=\"".$admin_file.".php?op=settings#credits_settings\">"._CREDITS_SETTINGS."</a>
		]</p>";
		$contents .= "
		<p align=\"center\">[ 
			<a href=\"".$admin_file.".php?op=credits_users_op\" class=\"mode_operation\" data-form_type=\"1\" data-mode=\"credits_form\">"._CREDITS_CHARGE."</a> | 
			<a href=\"".$admin_file.".php?op=credits_users_op\" class=\"mode_operation\" data-form_type=\"2\" data-mode=\"credits_form\">"._CREDITS_DEDUCTION."</a> | 
			<a href=\"".$admin_file.".php?op=credits_users_op\" class=\"mode_operation\" data-form_type=\"3\" data-mode=\"credits_form\">"._CREDITS_TRANSFER."</a> | 
			<a href=\"".$admin_file.".php?op=credits_users_op\" class=\"mode_operation\" data-form_type=\"4\" data-mode=\"credits_form\">"._CREDITS_SUSPEND."</a> | 
			<a href=\"".$admin_file.".php?op=credits_users_op&mode=suspend_list\">"._CREDITS_SUSPEND_LIST."</a>
		]</p><br />";
		
		$contents .= "<div id=\"credits-dialog\"></div>
		<script>
			$(\".mode_operation\").click(function(e)
			{
				e.preventDefault();
				var form_type = $(this).data('form_type');
				var credit_mode = $(this).data('mode');
				
				$.post(\"".$admin_file.".php?op=credits_users_op\",
				{
					form_type: form_type,
					mode: credit_mode,
					csrf_token : pn_csrf_token
				},
				function(responseText){
					$(\"#credits-dialog\").html(responseText);
					$(\"#credits-dialog\").dialog({
						resizable: false,
						height: 500,
						width: 800,
						modal: true,
						closeOnEscape: true,
						close: function(event, ui)
						{
							$(this).dialog('destroy');
							$(\"#credits-dialog\").html('');
						}
					});
				});
			});
		</script>";
		
		$contents = $hooks->apply_filters("credits_admin_menu", $contents);
		return $contents;
	}
	
	function credits($order_by = '', $sort='DESC')
	{
		global $db, $hooks, $module_name, $admin_file, $nuke_configs, $users_system;
			
		$contents = '';
				
		$hooks->add_filter("set_page_title", function() {return array("credits" => _CREDITS_ADMIN);});
		
		$contents .= GraphicAdmin();
		$contents .= credits_menu();
		
		$contents .= OpenAdminTable();
		
		$last_30_days = _NOWTIME-(30*86400);
		
		$result = $db->table(TRANSACTIONS_TABLE)
						->where('update_time',">=",$last_30_days)
						->where('status',_CREDIT_STATUS_OK)
						->where('type', "!=", _CREDIT_TRANSACTION_SUSPEND)
						->select(array(
							"type",
							"amount",
							"update_time"
						));
		$dataProvider_contents = array();
		
		if($result->count() > 0)
		{
			$rows = $result->results();
			if(!empty($rows))
			{
				$credits_data = array();
				foreach($rows as $row)
				{
					$type = intval($row['type']);
					$amount = intval($row['amount']);
					$update_time = $row['update_time'];
					
					$datetime = mktime(0,0,0,date("m", $update_time),date("d", $update_time),date("y", $update_time));
					
					$credits_data[$datetime][$type][] = $amount;
				}
				
				foreach($credits_data as $datetime => $credit_data)
				{
					$withdraw_number = ((isset($credit_data[_CREDIT_TRANSACTION_WITHDRAW])) ? sizeof($credit_data[_CREDIT_TRANSACTION_WITHDRAW]):0)+((isset($credit_data[_CREDIT_TRANSACTION_TRANSFER_W])) ? sizeof($credit_data[_CREDIT_TRANSACTION_TRANSFER_W]):0);
					$deposit_number = ((isset($credit_data[_CREDIT_TRANSACTION_DEPOSTIT])) ? sizeof($credit_data[_CREDIT_TRANSACTION_DEPOSTIT]):0)+((isset($credit_data[_CREDIT_TRANSACTION_TRANSFER_D])) ? sizeof($credit_data[_CREDIT_TRANSACTION_TRANSFER_D]):0);
					$total_transactions_number = $withdraw_number+$deposit_number;
					$sum_deposit = 0;
					$sum_withdraw = 0;
					$datetime = nuketimes($datetime, false, false, false, 1);
					if(isset($credit_data[_CREDIT_TRANSACTION_DEPOSTIT]))
					{
						foreach($credit_data[_CREDIT_TRANSACTION_DEPOSTIT] as $deposit_count)
							$sum_deposit = $sum_deposit+$deposit_count;
					}
					if(isset($credit_data[_CREDIT_TRANSACTION_TRANSFER_D]))
					{
						foreach($credit_data[_CREDIT_TRANSACTION_TRANSFER_D] as $deposit_count)
							$sum_deposit = $sum_deposit+$deposit_count;
					}
					if(isset($credit_data[_CREDIT_TRANSACTION_WITHDRAW]))
					{
						foreach($credit_data[_CREDIT_TRANSACTION_WITHDRAW] as $withdraw_count)
							$sum_withdraw = $sum_withdraw+$withdraw_count;
					}
					if(isset($credit_data[_CREDIT_TRANSACTION_TRANSFER_W]))
					{
						foreach($credit_data[_CREDIT_TRANSACTION_TRANSFER_W] as $withdraw_count)
							$sum_withdraw = $sum_withdraw+$withdraw_count;
					}
					
					$dataProvider_contents[] = array(
						"column-1" => $sum_deposit,
						"column-2" => $sum_withdraw,
						"column-3" => $total_transactions_number,
						"date" => $datetime
					);
				}
			}
		}
		
		$result = $db->query("SELECT SUM(t.amount) as total_deposits, COUNT(t.tid) as total_transactions, u.".$users_system->user_fields['username']." as username, u.".$users_system->user_fields['user_avatar']." as user_avatar, u.".$users_system->user_fields['user_avatar_type']." as user_avatar_type FROM ".TRANSACTIONS_TABLE." as t LEFT JOIN ".$users_system->users_table." as u ON u.".$users_system->user_fields['user_id']." = t.user_id WHERE t.type = ? AND t.update_time >= ? GROUP BY t.user_id ORDER BY total_deposits DESC", array(_CREDIT_TRANSACTION_DEPOSTIT, $last_30_days));
		$user_dataproviders = array();
		if($result->count() > 0)
		{
			$rows = $result->results();
			
			foreach($rows as $key => $row)
			{
				if($key == 10)
					break;
				$user_dataproviders[] = array(
					"name" => $row['username'],
					"points" => $row['total_deposits'],
					"transactions" => intval($row['total_transactions']),
					"avatar" => $users_system->get_avatar_url($row, 40, 40)
				);
			}
		}
		
		$chart_data['deposits'] = _CREDITS_DEPOSIT;
		$chart_data['withdraw'] = _CREDITS_WITHDRAW;
		$chart_data['total_transactions'] = _CREDITS_TOTAL_TRANSACTIONS;
		$chart_data['amount'] = _CREDITS_AMOUNT;
		$chart_data['statistics'] = _CREDITS_STATISTICS;
		$chart_data['chart_users'] = _CREDIT_CHART_USERS;
		$chart_data['chart_users_title'] = _CREDIT_CHART_USERS_TITLE;
		$chart_data['dataProvider_contents'] = $dataProvider_contents;
		$chart_data['user_dataproviders'] = $user_dataproviders;
		
		$chart_data = json_encode($chart_data);
		
		$contents .= "
			<script type=\"text/javascript\">			
				var chart_data = JSON.parse('$chart_data');
			</script>
			<script src=\"includes/amcharts/amcharts.js\"></script>
			<script src=\"includes/amcharts/serial.js\"></script>
			<script src=\"includes/amcharts/themes/light.js\"></script>
			<script src=\"includes/amcharts/plugins/responsive/responsive.min.js\"></script>
			<script src=\"modules/$module_name/includes/admin_chart.js\"></script>
			<style>
			#chartdiv {
				width	: 100%;
				height	: 500px;
			}									
			</style>
			<div id=\"chartdiv\" style=\"width: 100%; height: 500px; background-color: #FFFFFF;\" dir=\"ltr\" ></div>
			<table width=\"100%\" class=\"product-table no-hover no-border\">
				<tr>
					<td width=\"50%\"></td>
					<td width=\"50%\"><div id=\"chartdiv2\" style=\"width: 100%; height: 300px; background-color: #FFFFFF;\" dir=\"ltr\" ></div></td>
				</tr>
			</table>
			";
		
		$contents .= CloseAdminTable();
		$contents = $hooks->apply_filters("credits_admin_main", $contents);
		phpnuke_db_error();
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}
	
	function credits_list($status = '', $sort = 'DESC', $order_by = '')
	{
		global $db, $hooks, $admin_file, $nuke_configs, $module_name, $pn_credits_config, $pn_Cookies, $page, $search_data;
		
		$contents = '';
		$status = ($status != '') ? intval($status):'';
		
		$entries_per_page = 20;
		$current_page = (empty($page)) ? 1 : $page;
		$rows_data = _credits_list($sort, $order_by, $entries_per_page, $current_page, true);

		if(isset($search_data) && !empty($search_data))
		{
			$pn_Cookies->set("credits_search_data", phpnuke_serialize($search_data));
			extract($search_data);
		}

		$sort = ($sort != '' && in_array($sort, array("ASC","DESC"))) ? $sort:"DESC";
		$sort_reverse = ($sort == 'ASC') ? "DESC":"ASC";	
		$sort_icon = ($sort == 'ASC') ? "up":"down";	
		$order_by = ($order_by != '') ? $order_by:'tid';
		$link_to_more = (isset($rows_data['link_to_more']) && $rows_data['link_to_more'] != '') ? $rows_data['link_to_more']:"";
		
		$link_to = "".$admin_file.".php?op=credits_list".$link_to_more;
		
		$sel0 = ($status == '') ? "selected":"";
		$sel1 = ($status === 0) ? "selected":"";
		$sel2 = ($status == 1) ? "selected":"";
		$sel3 = ($status == 2) ? "selected":"";
		$sel4 = ($status == 3) ? "selected":"";
		$sel5 = ($status == 4) ? "selected":"";
		$sel6 = ($status == (-1)) ? "selected":"";

		$search_query = (isset($search_data) && !empty($search_data)) ? $search_query:"";
		$transaction_from_date = (isset($search_data) && !empty($search_data)) ? $transaction_from_date:"";
		$transaction_to_date = (isset($search_data) && !empty($search_data)) ? $transaction_to_date:"";

		$search_type_sel0 = (isset($search_data) && !empty($search_data) && $search_type == 0) ? "selected":"";
		$search_type_sel1 = (isset($search_data) && !empty($search_data) && $search_type == 1) ? "selected":"";
		$search_type_sel2 = (isset($search_data) && !empty($search_data) && $search_type == 2) ? "selected":"";
		$search_type_sel3 = (isset($search_data) && !empty($search_data) && $search_type == 3) ? "selected":"";
		$search_type_sel4 = (isset($search_data) && !empty($search_data) && $search_type == 4) ? "selected":"";
		$search_type_sel5 = (isset($search_data) && !empty($search_data) && $search_type == 5) ? "selected":"";

		$search_status0 = (isset($search_data) && !empty($search_data) && $search_status == 0) ? "selected":"";
		$search_status1 = (isset($search_data) && !empty($search_data) && $search_status == 1) ? "selected":"";
		$search_status2 = (isset($search_data) && !empty($search_data) && $search_status == 2) ? "selected":"";
		$search_status3 = (isset($search_data) && !empty($search_data) && $search_status == 3) ? "selected":"";
		$search_status4 = (isset($search_data) && !empty($search_data) && $search_status == 4) ? "selected":"";

		$transaction_type_sel0 = (isset($search_data) && !empty($search_data) && $transaction_type == 0) ? "selected":"";
		$transaction_type_sel1 = (isset($search_data) && !empty($search_data) && $transaction_type == 1) ? "selected":"";
		$transaction_type_sel2 = (isset($search_data) && !empty($search_data) && $transaction_type == 2) ? "selected":"";
		$transaction_type_sel3 = (isset($search_data) && !empty($search_data) && $transaction_type == 3) ? "selected":"";
		$transaction_type_sel4 = (isset($search_data) && !empty($search_data) && $transaction_type == 4) ? "selected":"";
		$transaction_type_sel5 = (isset($search_data) && !empty($search_data) && $transaction_type == 5) ? "selected":"";
		
		$status_desc = '';
		$status_all = array(
			""._CREDIT_STATUS_DELETED.""	=> _CREDITS_DELETED,
			""._CREDIT_STATUS_FAILED.""		=> _CREDITS_FAILED,
			""._CREDIT_STATUS_NORMAL.""		=> _CREDITS_NORMAL,
			""._CREDIT_STATUS_OK.""			=> _CREDITS_OK,
			""._CREDIT_STATUS_PENDING.""	=> _CREDITS_PENDING,
			""._CREDIT_STATUS_CANCELED.""	=> _CREDITS_CANCELED,
		);
		
		$status_desc = ($status != '') ? " ( ".$status_all[$status]." )":"";
		
		$pagetitle = _CREDITS_LIST.$status_desc;
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("credits_list" => $pagetitle);});
			
		$contents .= GraphicAdmin();
		$contents .= credits_menu();

		$contents .= OpenAdminTable();
		$contents .= "
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/calendar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-ar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-fa.js\" type=\"text/javascript\"></script>	
		<table align=\"center\" class=\"product-table no-border no-hover\" width=\"100%\">
			<tr>
				<td valign=\"top\">
					<form action=\"".$admin_file.".php?op=credits_list\" method=\"post\">
						<input type=\"text\" class=\"inp-form\" name=\"search_data[search_query]\" placeholder=\""._SEARCH."\"value=\"$search_query\" /> &nbsp; 
						<span id=\"more-search-options\" style=\"cursor:pointer\">"._CREDITS_SHOW_MORE_SEARCH_OPTIONS."</span>
						<div id=\"credit-search-box\" style=\"display:none\">
							<table class=\"product-table id-form no-border\">
								<tr>
									<th>"._SEARCH_IN."</th>
									<td>
										<select name=\"search_data[search_type]\" class=\"styledselect-select\">
											<option value=\"0\" $search_type_sel0>"._CREDITS_SEARCH_IN_ALL."</option>
											<option value=\"1\" $search_type_sel1>"._CREDITS_SEARCH_IN_TRANSACTION_ID."</option>
											<option value=\"2\" $search_type_sel2>"._CREDITS_SEARCH_IN_ORDER_ID."</option>
											<option value=\"3\" $search_type_sel3>"._CREDITS_SEARCH_IN_TITLE."</option>
											<option value=\"4\" $search_type_sel4>"._CREDITS_SEARCH_IN_DESC."</option>
											<option value=\"5\" $search_type_sel5>"._CREDITS_SEARCH_IN_GATEWAY."</option>
										</select>
									</td>
								</tr>
								<tr>
									<th>"._STATUS."</th>
									<td>
										<select name=\"search_data[search_status]\" class=\"styledselect-select\">
											<option value=\"0\" $search_status0>"._CREDITS_SEARCH_IN_ALL."</option>
											<option value=\"1\" $search_status1>"._CREDITS_NORMAL."</option>
											<option value=\"2\" $search_status2>"._CREDITS_PENDING."</option>
											<option value=\"3\" $search_status3>"._CREDITS_CANCELED."</option>
											<option value=\"3\" $search_status4>"._CREDITS_FAILED."</option>
										</select>
									</td>
								</tr>
								<tr>
									<th>"._CREDIT_DETAILS_TYPE."</th>
									<td>
										<select name=\"search_data[transaction_type]\" class=\"styledselect-select\">
											<option value=\"0\" $transaction_type_sel0>"._CREDITS_SEARCH_IN_ALL."</option>
											<option value=\"1\" $transaction_type_sel1>"._CREDITS_DEPOSIT."</option>
											<option value=\"2\" $transaction_type_sel2>"._CREDITS_WITHDRAW."</option>
											<option value=\"3\" $transaction_type_sel3>"._CREDITS_TRANSFER_D."</option>
											<option value=\"4\" $transaction_type_sel4>"._CREDITS_TRANSFER_W."</option>
											<option value=\"5\" $transaction_type_sel5>"._CREDITS_SUSPEND."</option>
										</select>
									</td>
								</tr>
								<tr>
									<th>"._CREDIT_DETAILS_DATETIME."</th>
									<td>
										<input type=\"text\" class=\"inp-form-ltr calendar\" placeholder=\""._FROM."\" name=\"search_data[transaction_from_date]\" value=\"$transaction_from_date\" /><br /><br />
										<input type=\"text\" class=\"inp-form-ltr calendar\" placeholder=\""._TO."\" name=\"search_data[transaction_to_date]\" value=\"$transaction_to_date\" />
									</td>
								</tr>
							</table>
						</div>
						<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
						&nbsp; <input type=\"submit\" class=\"form-submit\" />&nbsp; 
						<a href=\"".LinkToGT("index.php?modname=Credits&op=delete_all_filters&in_admin=1")."\" class=\"btn btn-info del-filters\">"._CREDITS_DELETE_ALL_FILTERS."</a>
					</form>
				</td>
				<td valign=\"top\">
					"._CREDITS_SORT_LIST_BY."
					<select id=\"change_status\" class=\"select2 styledselect-select\">
						<option value=\"\" $sel0>"._ALL."</option>
						<option value=\"0\" $sel1>"._CREDITS_NORMAL."</option>
						<option value=\"1\" $sel2>"._CREDITS_OK."</option>
						<option value=\"2\" $sel3>"._CREDITS_PENDING."</option>
						<option value=\"3\" $sel4>"._CREDITS_CANCELED."</option>
						<option value=\"4\" $sel5>"._CREDITS_FAILED."</option>
						<option value=\"-1\" $sel6>"._CREDITS_DELETED."</option>
					</select>
				</td>
			</tr>
		</table>
		<table align=\"center\" class=\"product-table\" width=\"100%\">
			<thead>
				<tr>
					<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:70px;\"><a href=\"".$admin_file.".php?op=credits_list&order_by=tid&sort=".$sort_reverse."\"".(($order_by == 'tid') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._CODE."</a></th>
					<th class=\"table-header-repeat line-left\"><a href=\"".$admin_file.".php?op=credits_list&order_by=type&sort=".$sort_reverse."\"".(($order_by == 'type') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._CREDIT_DETAILS_TYPE."</a></th>
					<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\"><a href=\"".$admin_file.".php?op=credits_list&order_by=amount&sort=".$sort_reverse."\"".(($order_by == 'amount') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._CREDIT_DETAILS_AMOUNT."</a></th>
					<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:170px;\"><a href=\"".$admin_file.".php?op=credits_list&order_by=create_time&sort=".$sort_reverse."\"".(($order_by == 'create_time') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._CREATIONDATE."</a></th>
					<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:170px;\"><a href=\"".$admin_file.".php?op=credits_list&order_by=status&sort=".$sort_reverse."\"".(($order_by == 'status') ? " class=\"arrow_".strtolower($sort)."\"":"").">"._STATUS."</a></th>
					<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:140px;\">"._OPERATION."</th>
				</tr>
			</thead>
			<tbody>";
		$total_rows = (isset($rows_data['total_rows']) && $rows_data['total_rows'] != 0) ? $rows_data['total_rows']:0;
		if(isset($rows_data['rows']) && !empty($rows_data['rows']))
		{
			$rows = $rows_data['rows'];
			if(!empty($rows))
			{
				foreach($rows as $row)
				{
					$tid = $row['tid'];
					$amount = number_format($row['amount'], 0);
					$create_time = nuketimes($row['create_time'], true, true, false, 1);
					$status = $row['status'];
					$type = $row['type'];
					$title = $row['title'];
					$description = $row['description'];
					$order_id = $row['order_id'];
					$order_link = $row['order_link'];
					$order_data = ($row['order_data'] != '') ? phpnuke_unserialize($row['order_data']):"";
					
					$amount = "<span style=\"color:".credits_get_type_color($type).";\">$amount".credits_get_type_icon($type)."</span>";

					$status_desc = ($status != '') ? " ( ".$status_all[$status]." )":"";
					
					$type_desc = credits_get_type_desc($type);
					
					if($order_id != 0 && $order_link != '' & !empty($order_data))
					{
						$order_desc = "<br />".$order_data['part_desc']." - "._CODE." : <a href=\"$order_link\" target=\"_blank\">$order_id</a><br />$description";
					}
					else
					{
						$order_desc = "<br />$description";
					}
					
					$operations = array();
					$operations[] = "<a href=\"".$admin_file.".php?op=credit_view&tid=$tid\" data-mode=\"view\" data-tid=\"$tid\" title=\""._VIEW."\" class=\"table-icon icon-7 info-tooltip operation\"></a>";
					$operations[] = "<a href=\"".$admin_file.".php?op=credits_admin&mode=delete&tid=$tid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._DELETE."\" class=\"table-icon icon-2 info-tooltip prompt_need\"></a>";
					if($status == _CREDIT_STATUS_PENDING)
					{
						$operations[] = "<a href=\"".$admin_file.".php?op=credits_admin&mode=failed&tid=$tid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._FAIL."\" class=\"table-icon icon-13 info-tooltip prompt_need\"></a>";
						$operations[] = "<a href=\"".$admin_file.".php?op=credits_admin&mode=approve&tid=$tid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._APPROVE."\" class=\"table-icon icon-5 info-tooltip\"></a>";
					}
					$contents .= "
					<tr>
						<td align=\"center\">$tid</td>
						<td>$type_desc - ".$title."".$order_desc."</td>
						<td align=\"center\">$amount</td>
						<td align=\"center\">$create_time</td>
						<td align=\"center\">$status_desc</td>
						<td align=\"center\">
							".implode("\n", $operations)."
						</td>
					</tr>";
				}
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
		{
			$contents .= "<p align=\"center\">"._CREDIT_NO_TRANSACTION."</p>";
		}

		
		$contents .= "<div id=\"credits-dialog\"></div>
		<script>
			$(\"#change_status\").on('change', function()
			{
				top.location.href='".$admin_file.".php?op=credits_list'+(($(this).val() != '') ? '&status='+$(this).val():'');
			});
			$('.prompt_need').on('click', function(e)
			{
				e.preventDefault();
				var delete_href= $(this).attr('href');				
				var reason = prompt('"._CREDIT_DELETE_THIS_TRANSACTION."');
				if(reason == '')
				{
					alert('"._CREDIT_ENTER_REASON."');
					return false;
				}
				delete_href += '&reason='+reason;
				if(reason !== null)
					document.location.href=delete_href;
			});
			
			$(\"#more-search-options\").on('click', function(){
				$(\"#credit-search-box\").dialog({
					title: '"._CREDITS_SHOW_MORE_SEARCH_OPTIONS."',
					resizable: false,
					height: 350,
					width: 600,
					modal: true,
					closeOnEscape: true,
					close: function(event, ui)
					{
						$(this).dialog('destroy');
					}
				});
			});
			$(\".operation\").click(function(e)
			{
				e.preventDefault();
				var credit_tid = $(this).data('tid');
				var credit_mode = $(this).data('mode');
				
				$.post(\"".$admin_file.".php?op=credits_admin\",
				{
					tid: credit_tid,
					mode: credit_mode,
					csrf_token : pn_csrf_token
				},
				function(responseText){
					$(\"#credits-dialog\").html(responseText);
					$(\"#credits-dialog\").dialog({
						resizable: false,
						height: 500,
						width: 700,
						modal: true,
						closeOnEscape: true,
						close: function(event, ui)
						{
							$(this).dialog('destroy');
							$(\"#credits-dialog\").html('');
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
		
	function credits_users_op($tid = 0, $mode = '', $sort = 'DESC', $order_by = '', $form_type)
	{
		global $db, $admin_file, $nuke_configs, $module_name, $pn_credits_config, $userinfo, $page, $search_data, $hooks;
		
		$contents = '';		
		
		if($mode == "credits_form")
		{
			$contents .="
			<form action=\"".$admin_file.".php?op=credits_admin\" method=\"post\">
			<table class=\"id-form product-table no-border\">
				<tr>
					<th>"._TITLE."</th>
					<td><input type=\"text\" name=\"credits_fields[title]\" class=\"inp-form-ltr\" /></td>
				</tr>
				<tr>
					<th>"._DESCRIPTIONS."</th>
					<td><input type=\"text\" name=\"credits_fields[description]\" class=\"inp-form-ltr\" /></td>
				</tr>";
			switch($form_type)
			{
				case"1":
				case"2":
				case"4":
					$contents .="<tr>
						<th>"._USERNAME."</th>
						<td><input type=\"text\" name=\"credits_fields[username]\" class=\"inp-form-ltr\" /></td>
					</tr>
					<tr>
						<th>".(($form_type == 1) ? _CREDITS_CHARGE:(($form_type == 2) ? _CREDITS_DEDUCTION:_CREDITS_SUSPEND_AMOUNT))."</th>
						<td>
							<input type=\"text\" name=\"credits_fields[credit]\" class=\"inp-form-ltr\" />
							".(($form_type == 1) ? "<input type=\"hidden\" name=\"mode\" value=\"credits_charge\" />":(($form_type == 2) ? "<input type=\"hidden\" name=\"mode\" value=\"credits_deduction\" />":"<input type=\"hidden\" name=\"mode\" value=\"credits_suspend\" />".bubble_show(_CREDITS_SUSPEND_DESC).""))."
						</td>
					</tr>";
				break;
				case"3":
					$contents .="<tr>
						<th>"._CREDITS_FROM_USERNAME."</th>
						<td><input type=\"text\" name=\"credits_fields[username]\" class=\"inp-form-ltr\" /></td>
					</tr>
					<tr>
						<th>"._CREDITS_TO_USERNAME."</th>
						<td><input type=\"text\" name=\"credits_fields[target_username]\" class=\"inp-form-ltr\" /></td>
					</tr>
					<tr>
						<th>"._CREDITS_AMOUNT."</th>
						<td><input type=\"text\" name=\"credits_fields[credit]\" class=\"inp-form-ltr\" /></td>
					</tr>
					<tr>
						<th>"._CREDITS_TRANSFER_ALL."</th>
						<td><input type=\"checkbox\" name=\"credits_fields[credit_all]\" value=\"1\" class=\"styled\" data-label=\""._CREDITS_TR_ALL_1."\" /></td>
					</tr>
					<tr>
						<th>"._CREDITS_TRANSFER_TYPE."</th>
						<td>
							"._CREDITS_TRANSFER_TYPE_DESC."
							<input type=\"radio\" name=\"credits_fields[credit_transfer_type]\" value=\"1\" class=\"styled\" data-label=\""._CREDITS_TR_TY_1."\" checked /><br />
							<input type=\"radio\" name=\"credits_fields[credit_transfer_type]\" value=\"2\" class=\"styled\" data-label=\""._CREDITS_TR_TY_2."\" /><br />
							<input type=\"radio\" name=\"credits_fields[credit_transfer_type]\" value=\"3\" class=\"styled\" data-label=\""._CREDITS_TR_TY_3."\" /><br />
							<input type=\"radio\" name=\"credits_fields[credit_transfer_type]\" value=\"4\" class=\"styled\" data-label=\""._CREDITS_TR_TY_4."\" /><br />
							<input type=\"hidden\" name=\"mode\" value=\"credits_transfer\" />
						</td>
					</tr>";
				break;
			}
			$contents .="
				<tr>
					<td colspan=\"2\"><input type=\"submit\" name=\"submit\" class=\"form-submit\" value=\"submit\" /></td>
				</tr>
			</table><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> </form>";
			$contents .=  jquery_codes_load();
			$contents = $hooks->apply_filters("credits_admin_users_op", $contents);
			die($contents);
		}

		if($mode == 'suspend_list')
		{
			$pagetitle = _CREDITS_LIST." - "._CREDITS_SUSPEND_LIST;
			$hooks->add_filter("set_page_title", function() use($pagetitle){return array("suspend_list" => $pagetitle);});
				
			$contents .= GraphicAdmin();
			$contents .= credits_menu();
					
			$contents .= OpenAdminTable();
			$entries_per_page = 20;
			$current_page = (empty($page)) ? 1 : $page;

			$sort = ($sort != '' && in_array($sort, array("ASC","DESC"))) ? $sort:"DESC";

			$order_by = ($order_by != '') ? $order_by:'tid';
			$link_to_more = (isset($rows_data['link_to_more']) && $rows_data['link_to_more'] != '') ? $rows_data['link_to_more']:"";
			
			$link_to = "".$admin_file.".php?op=credits_users_op".$link_to_more;
		
			$start_at  = ($current_page * $entries_per_page) - $entries_per_page;

			$total_rows = 0;	
			$result = $db->query("
			SELECT *, 
			(SELECT COUNT(tid) FROM ".TRANSACTIONS_TABLE." WHERE type = :transaction_type and status = :status) as total_rows 
			FROM ".TRANSACTIONS_TABLE."
			 WHERE type = :transaction_type and status = :status
			ORDER BY $order_by $sort LIMIT :start_at,:entries_per_page", array(":transaction_type" => _CREDIT_TRANSACTION_SUSPEND, ":status" => _CREDIT_STATUS_OK, ":start_at" => $start_at, ":entries_per_page" => $entries_per_page));

			$table_fields = array(
				array(
					"width" => "70px",
					"op" => "credits_users_op&mode=suspend_list",
					"id" => "tid",
					"text" => _CODE,
				),
				array(
					"width" => "auto",
					"op" => "credits_users_op&mode=suspend_list",
					"id" => "type",
					"text" => _CREDIT_SUSPEND_REASON,
				),
				array(
					"width" => "120px",
					"op" => "credits_users_op&mode=suspend_list",
					"id" => "amount",
					"text" => _CREDIT_DETAILS_AMOUNT,
				),
				array(
					"width" => "170px",
					"op" => "credits_users_op&mode=suspend_list",
					"id" => "create_time",
					"text" => _CREATIONDATE,
				),
			);		
			
			$contents .= "
			<table align=\"center\" class=\"product-table\" width=\"100%\">
				<thead>
					<tr>
						".admin_tables_sortable($table_fields, $sort, $link_to_more, $order_by)."
						<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:120px;\">"._OPERATION."</th>
					</tr>
				</thead>
				<tbody>";
			if($result->count() > 0)
			{
				$rows = $result->results();
				foreach($rows as $row)
				{
					$tid = $row['tid'];
					$amount = number_format($row['amount'], 0);
					$suspend_time = nuketimes($row['create_time'], true, true, false, 1);
					$title = $row['title'];
					$description = $row['description'];
					
					$amount = "<span style=\"color:red;\">".(($amount == 0) ? ""._CREDITS_SUSPEND_ALL."":$amount)."</span>";
					
					$contents .= "
					<tr>
						<td align=\"center\">$tid</td>
						<td>".$title."<br /><small>".$description."</small></</td>
						<td align=\"center\">$amount</td>
						<td align=\"center\">$suspend_time</td>
						<td align=\"center\">
							<a href=\"".$admin_file.".php?op=credits_admin&mode=credits_unsuspend&tid=$tid&csrf_token="._PN_CSRF_TOKEN."\" title=\""._CREDITS_UNSUSPEND."\" class=\"table-icon icon-2 info-tooltip\" onclick=\"return confirm('"._CREDITS_UNSUSPEND_SURE."');\"></a>
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
			{
				$contents .= "<p align=\"center\">"._CREDIT_NO_TRANSACTION."</p>";
			}
			$contents .= CloseAdminTable();
			phpnuke_db_error();
			include("header.php");
			$html_output .= $contents;
			include("footer.php");

		}	
	}
	
	function credits_admin($tid=0, $mode="view", $submit, $credits_fields=array())
	{
		global $db, $hooks, $aid, $ya_config, $admin_file, $nuke_configs, $PnValidator, $module_name, $userinfo, $pn_credits_config, $reason, $users_system;
		
		$tid = intval($tid);
		$all_modes = array("view", "delete", "failed", "approve", "credits_transfer", "credits_charge", "credits_deduction", "credits_suspend", "credits_unsuspend");
		$mode = (!in_array($mode, $all_modes)) ? "view":$mode;
		$nowtime = nuketimes(_NOWTIME, true, true, true, 1);
		
		$username = (isset($credits_fields['username']) && $credits_fields['username'] != '') ? $credits_fields['username']:"";
		$target_username = (isset($credits_fields['target_username']) && $credits_fields['target_username'] != '') ? $credits_fields['target_username']:"";
		$credits_amount = (isset($credits_fields['credit']) && $credits_fields['credit'] != '') ? $credits_fields['credit']:0;
		$user_id = 0;
		$target_user_id = 0;
		
		$user_credit = '';
		$user_phone = '';
		$user_email = '';
		$target_user_credit = '';
		$target_user_phone = '';
		$target_user_email = '';
		
		if($username != '')
		{
			$u_result = $db->table($users_system->users_table)
							->where($users_system->user_fields['username'], $username)
							->select([
								'user_id' => $users_system->user_fields['user_id'], 
								'user_email' => $users_system->user_fields['user_email'], 
								'user_credit' => $users_system->user_fields['user_credit']
							]);
							
			if($u_result->count() != 0)
			{
				$u_row = $u_result->results()[0];
				$user_id = intval($u_row['user_id']);
				$user_email = $u_row['user_email'];
				$user_credit = $u_row['user_credit'];
				$user_phone = $users_system->get_user_phone($u_row['user_id']);
			}
			unset($u_result);
			unset($u_row);
		}
			
		if($target_username != '')
		{
			$u_result = $db->table($users_system->users_table)
							->WHERE($users_system->user_fields['username'], $target_username)
							->select([
								'user_id' => $users_system->user_fields['user_id'], 
								'user_email' => $users_system->user_fields['user_email'], 
								'user_credit' => $users_system->user_fields['user_credit']
							]);
							
			if($u_result->count() != 0)
			{
				$u_row = $u_result->results()[0];
				$target_user_id = intval($u_row['user_id']);
				$target_user_email = $u_row['user_email'];
				$target_user_credit = $u_row['user_credit'];
				$target_user_phone = $users_system->get_user_phone($u_row['user_id']);
			}
			unset($u_result);
			unset($u_row);
		}
		
		if(isset($submit) && $submit != '')
		{
			$payment_data = array();
			$payment_data['gateway'] = 'offline';
			$payment_data['datetime'] = _NOWTIME;
			$payment_data['number'] = '';
				
			$results_msg = '';
			
			if($user_id == 0)
			{
				$results_msg = _INCORRECT_USERNAME;
			}
			else
			{
			
				$request_fields = array(
					"aid" => $aid,
					"factor_number" => _NOWTIME,
					"create_time" => _NOWTIME,
					"update_time" => _NOWTIME,
					"status" => _CREDIT_STATUS_OK,
					"gateway" => 'offline',
					"data" => phpnuke_serialize($payment_data),
					"title" => (isset($credits_fields['title']) && $credits_fields['title'] != '') ? $credits_fields['title']:"",
					"description" => (isset($credits_fields['description']) && $credits_fields['description'] != '') ? $credits_fields['description']:"",
					"order_part" => '',
					"order_id" => '',
					"order_link" => '',
					"order_data" => '',
				);
				
				if($mode == "credits_transfer" && $user_id != 0 && $target_user_id != 0 && $credits_amount != 0)
				{
					$transfer_amount1 = $transfer_amount2 = 0;
					
					$user_credits_allowed = user_credits_allowed($user_id);
					
					if($user_credits_allowed > 0)
					{
						$transfer_amount1 = $transfer_amount2 = $credits_amount;
						
						if(isset($credits_fields['credit_all']) && $credits_fields['credit_all'] == 1)
						{
							$transfer_amount1 = $transfer_amount2 = $user_credits_allowed;
							$user_credit = $user_credit-$transfer_amount1;
							$target_user_credit = $target_user_credit+$transfer_amount2;
						}
						elseif($user_credits_allowed < $credits_amount)
						{
							switch(intval($credits_fields['credit_transfer_type']))
							{
								case"1":
									$transfer_amount1 = $transfer_amount2 = $user_credits_allowed;
									$user_credit = $user_credit-$transfer_amount1;
									$target_user_credit = $target_user_credit+$transfer_amount2;
								break;
								case"2":
									$transfer_amount1 = $credits_amount;
									$transfer_amount2 = $user_credits_allowed;
									$user_credit = $user_credit-$credits_amount;
									$target_user_credit = $target_user_credit+$user_credits_allowed;
								break;
								case"3":
									$transfer_amount1 = $transfer_amount2 = $credits_amount;
									$user_credit = $user_credit-$transfer_amount1;
									$target_user_credit = $target_user_credit+$transfer_amount2;
								break;
								case"4":
									$transfer_amount1 = $transfer_amount2 = 0;//do nothing
								break;
							}
						}
						
						if($transfer_amount1 != 0 && $transfer_amount2 != 0)
						{
							//for user 1
							$request_fields['user_id'] = $user_id;
							$request_fields['type'] = _CREDIT_TRANSACTION_TRANSFER_W;
							$request_fields['amount'] = $transfer_amount1;
							
							$result = $db->table(TRANSACTIONS_TABLE)
							->insert($request_fields);
							
							user_credit_update($user_id, ["-",$transfer_amount1]);
							
							//for user 2
							$request_fields['user_id'] = $target_user_id;
							$request_fields['type'] = _CREDIT_TRANSACTION_TRANSFER_D;
							$request_fields['amount'] = $transfer_amount2;
							
							$result = $db->table(TRANSACTIONS_TABLE)
							->insert($request_fields);
							
							user_credit_update($target_user_id, ["+",$transfer_amount2]);
							
							$results_msg = sprintf(_CREDITS_TRANSFER_MSG, $username, number_format($transfer_amount1, 0), $target_username, number_format($transfer_amount2, 0));
							
							$email_texts[] = array($username, _CREDITS_DEDUCTION, number_format($transfer_amount1, 0), $user_credit, $user_email);
							$sms_text[] = array($user_phone, sprintf(_CREDITS_TRANSFER_SMS, $username, $target_username, number_format($transfer_amount1, 0), $nowtime));
							
							$email_texts[] = array($target_username, _CREDITS_CHARGE, number_format($transfer_amount2, 0), $target_user_credit, $target_user_email);
							$sms_text[] = array($target_user_phone, sprintf(_CREDITS_TRANSFER_SMS, $username, $target_username, number_format($transfer_amount2, 0), $nowtime));
						}
					}			
				}
				
				if($mode == "credits_charge" && $user_id != 0 && $credits_amount != 0)
				{
					$request_fields['user_id'] = $user_id;
					$request_fields['type'] = 1;
					$request_fields['amount'] = $credits_amount;
					
					$result = $db->table(TRANSACTIONS_TABLE)
					->insert($request_fields);
					
					user_credit_update($user_id, ["+",$credits_amount]);
					$user_credit = $user_credit+$credits_amount;
					$email_texts[] = array($username, _CREDITS_CHARGE, number_format($credits_amount, 0), $user_credit, $user_email);
							
					$results_msg = sprintf(_CREDITS_CHARGE_MSG, $username, number_format($credits_amount, 0));
					$sms_text[] = array($user_phone, sprintf(_CREDITS_CHAREG_SMS, $username, number_format($credits_amount, 0), $nowtime));
				}
				
				if($mode == "credits_deduction" && $user_id != 0 && $credits_amount != 0)
				{
					$request_fields['user_id'] = $user_id;
					$request_fields['type'] = 2;
					$request_fields['amount'] = $credits_amount;
					
					$result = $db->table(TRANSACTIONS_TABLE)
					->insert($request_fields);
					
					user_credit_update($user_id, ["-",$credits_amount]);
					$user_credit = $user_credit-$credits_amount;
					$email_texts[] = array($username, _CREDITS_DEDUCTION, number_format($credits_amount, 0), $user_credit, $user_email);
					
					$results_msg = sprintf(_CREDITS_DEDUCTION_MSG, $username, number_format($credits_amount, 0));
					$sms_text[] = array($user_phone, sprintf(_CREDITS_DEDUCTION_SMS, $username, number_format($credits_amount, 0), $nowtime));
				}
				
				if($mode == "credits_suspend" && $user_id != 0)
				{
					$request_fields['user_id'] = $user_id;
					$request_fields['type'] = _CREDIT_TRANSACTION_SUSPEND;
					$request_fields['amount'] = $credits_amount;
					
					$result = $db->table(TRANSACTIONS_TABLE)
					->insert($request_fields);
					
					$email_texts[] = array($username, _CREDITS_SUSPEND, (($credits_amount == 0) ? "All":number_format($credits_amount, 0)), $user_credit, $user_email);
					
					$results_msg = ($credits_amount == 0) ? sprintf(_CREDITS_SUSPEND_ALL_MSG, $username):sprintf(_CREDITS_SUSPEND_MSG, $username, number_format($credits_amount, 0));
					$sms_text[] = array($user_phone, (($credits_amount == 0) ? sprintf(_CREDITS_SUSPEND_ALL_SMS, $username, $nowtime):sprintf(_CREDITS_SUSPEND_SMS, $username, number_format($credits_amount,0), $nowtime)));
				}
			}
			
			if(!empty($results_msg))
			{
				if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && $user_phone != '' && isset($pn_credits_config['notify']['sms']) && $pn_credits_config['notify']['sms'] == 1)
				{
					foreach($sms_text as $sms_msg)
					{
						if($sms_msg[0] != '')
							pn_sms('send', $sms_msg[0], $sms_msg[1]);
					}
				}

				if(isset($pn_credits_config['notify']['email']) && $pn_credits_config['notify']['email'] == 1)
				{	
					foreach($email_texts as $email_text)
					{
						$message = array(
							"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "admin_transaction.txt"),
							"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
							"username" => $email_text[0], 
							"description" => $email_text[1], 
							"amount" => $email_text[2], 
							"current_credit" => number_format($email_text[3], 0),
							"update_time" => $nowtime
						);
						
						phpnuke_mail($email_text[4], sprintf(_CREDITS_EMAIL_ADMIN_TRANSACTION, $nuke_configs['sitename']), $message);
					}
				}

				add_log($results_msg, 1);
				phpnuke_db_error();
			}
			
			$contents = GraphicAdmin();
			$contents .= credits_menu();
	
			$contents .= OpenAdminTable();
			$contents .= "<p align=\"center\">".$results_msg."</p>";
			$contents .= CloseAdminTable();
			
			include("header.php");
			$html_output .= $contents;
			include("footer.php");
		}
		
		$result = $db->query("SELECT t.*, u.".$users_system->user_fields['username']." as username, u.".$users_system->user_fields['user_email']." as user_email, u.".$users_system->user_fields['user_credit']." as user_credit FROM ".TRANSACTIONS_TABLE." as t LEFT JOIN ".$users_system->users_table." AS u ON u.".$users_system->user_fields['user_id']." = t.user_id WHERE t.tid = '$tid'");
		$row = ($db->count() > 0) ? $result->results()[0]:array();
		$row['user_phone'] = (!empty($row)) ? $users_system->get_user_phone($row['user_id']):"";
		
		if($mode == 'view')
		{
			$contents = _credit_view($tid, true);
			die($contents);
		}

		// delete transaction
		if($mode == "delete" || $mode == "failed")
		{
			csrfProtector::authorisePost(true);
			if(isset($reason) && $reason != '')
			{
				$row['data'] = phpnuke_unserialize(stripslashes($row['data']));
				$row['data'][(($mode == "delete") ? 'delete_reason':'failed_reason')] = $reason;
				$row['data'] = phpnuke_serialize($row['data']);
			}
			
			$db->table(TRANSACTIONS_TABLE)
				->where("tid", $tid)
				->update(["status" => (($mode == "delete") ? _CREDIT_STATUS_DELETED:_CREDIT_STATUS_FAILED), "data" => $row['data']]);
			add_log(sprintf((($mode == "delete") ? _CREDIT_DELETE_LOG:_CREDIT_FAILED_LOG), $tid), 1);
			
			if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && $row['user_phone'] != '' && isset($pn_credits_config['notify']['sms']) && $pn_credits_config['notify']['sms'] == 1)
			{
				$sms_text = sprintf((($mode == "delete") ? _CREDIT_OFFLINE_FORM_DELETED:_CREDIT_OFFLINE_FORM_FAILED), $tid, $reason, $nowtime);
				pn_sms('send', $row['user_phone'], $sms_text);
			}
			
			$hooks->do_action("credits_admin_after_delete", $tid, $mode, $credits_fields);
			
			redirect_to("".$admin_file.".php?op=credits_list");
			die();
		}
				
		// approve transaction
		if($mode == "approve")
		{
			csrfProtector::authorisePost(true);
			$user_credit = $row['user_credit'];
			$user_phone = $row['user_phone'];
			$username = $row['username'];
			$user_email = $row['user_email'];
			$new_user_credit = $user_credit+$row['amount'];
			
			user_credit_update($row['user_id'], $new_user_credit);

			$update_data = array(
				"status" => _CREDIT_STATUS_OK,
				"update_time" => _NOWTIME
			);
			
			$db->table(TRANSACTIONS_TABLE)
				->where('tid', $tid)
				->update($update_data);
				
			add_log(sprintf(_CREDIT_APPROVE_LOG, $tid), 1);
			if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && $user_phone != '' && isset($pn_credits_config['notify']['sms']) && $pn_credits_config['notify']['sms'] == 1)
			{
				$sms_text = sprintf(_CREDIT_OFFLINE_FORM_APPROVED, $tid, $nowtime);
				pn_sms('send', $user_phone, $sms_text);
			}

			if(isset($pn_credits_config['notify']['email']) && $pn_credits_config['notify']['email'] == 1)
			{	
				$message = array(
					"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "offline_approved.txt"),
					"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
					"username" => $username, 
					"tid" => $tid, 
					"amount" => number_format($row['amount'], 0), 
					"current_credit" => number_format($new_user_credit, 0),
					"update_time" => _NOWTIME
				);
				
				phpnuke_mail($user_email, sprintf(_CREDITS_EMAIL_ADMIN_APPROVE, $nuke_configs['sitename']), $message);
			}
			$hooks->do_action("credits_admin_after_approve", $tid, $mode, $credits_fields);
			redirect_to("".$admin_file.".php?op=credits_list");
			die();
		}
		
		// unsuspend transaction
		if($mode == "credits_unsuspend")
		{
			csrfProtector::authorisePost(true);
			$tr_type = intval($row['type']);
			$tr_status = intval($row['status']);
			if($tr_type == _CREDIT_TRANSACTION_SUSPEND && $tr_status == _CREDIT_STATUS_OK)
			$db->table(TRANSACTIONS_TABLE)
				->where("tid", $tid)
				->update(["status" => _CREDIT_STATUS_CANCELED]);
				
			add_log(sprintf(_CREDIT_UNSUSPEND_LOG, $tid), 1);
			if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && $user_phone != '' && isset($pn_credits_config['notify']['sms']) && $pn_credits_config['notify']['sms'] == 1)
			{
				$sms_text = sprintf(_CREDIT_UNSUSPEND_SMS, $tid, $username, $nowtime);
				pn_sms('send', $user_phone, $sms_text);
			}
			$hooks->do_action("credits_admin_after_unsuspend", $tid, $mode, $credits_fields);
			redirect_to("".$admin_file.".php?op=credits_users_op&mode=suspend_list");
		}
	}

	global $pn_prefix;
	$op = (isset($op)) ? filter($op, "nohtml"):'';
	$order_by = (isset($order_by)) ? filter($order_by, "nohtml"):'';
	$sort = (isset($sort)) ? filter($sort, "nohtml"):'';
	$submit = filter(request_var('submit', '', '_POST'), "nohtml");
	$mode = (isset($mode)) ? filter($mode, "nohtml"):'new';
	$form_type = (isset($form_type)) ? intval($form_type):1;
	$credits_fields = request_var('credits_fields', array(), '_POST');
	$tid = (isset($tid)) ? intval($tid):0;
	$status = (isset($status)) ? $status:'';
	
	switch($op)
	{
		default:
		case"credits":
			credits();
		break;
		
		case"credits_list":
			credits_list($status, $sort, $order_by);
		break;
		
		case"credits_users_op":
			credits_users_op($tid, $mode, $sort, $order_by, $form_type);
		break;
		
		case"credits_admin":
			credits_admin($tid, $mode, $submit, $credits_fields);
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