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

if (!defined('CONFIG_FUNCTIONS_FILE')) {
	die("You can't access this file directly...");
}

$this_module_name = basename(dirname(__FILE__));

define("_ONLINE_ADD_CREDIT", 1);
define("_OFFLINE_ADD_CREDIT", 2);
define("_ONLINE_PAY_METHOD", 3);
define("_CREDIT_PAY_METHOD", 4);

define("_CREDIT_STATUS_DELETED", -1);
define("_CREDIT_STATUS_NORMAL", 0);
define("_CREDIT_STATUS_OK", 1);
define("_CREDIT_STATUS_PENDING", 2);
define("_CREDIT_STATUS_CANCELED", 3);
define("_CREDIT_STATUS_FAILED", 4);

define("_CREDIT_TRANSACTION_DEPOSTIT", 1);
define("_CREDIT_TRANSACTION_WITHDRAW", 2);
define("_CREDIT_TRANSACTION_TRANSFER_D", 3);
define("_CREDIT_TRANSACTION_TRANSFER_W", 4);
define("_CREDIT_TRANSACTION_SUSPEND", 5);

$default_currency = array(
	"USD" => "_CREDITS_CUR_USD",
	"EUR" => "_CREDITS_CUR_EUR",
	"GBP" => "_CREDITS_CUR_GBP",
	"AED" => "_CREDITS_CUR_AED",
	"KWD" => "_CREDITS_CUR_KWD",
);

function user_credits_blocked($user_id=0)
{
	global $db, $userinfo;
	
	$total_blocked_credit = 0;
	$result = $db->query("SELECT SUM(amount) as total_blocked_credit, (SELECT tid FROM ".TRANSACTIONS_TABLE." WHERE user_id = :user_id AND amount = '0' AND type = :transaction_type AND status = :status ORDER BY tid ASC LIMIT 1) AS suspended_all FROM ".TRANSACTIONS_TABLE." WHERE user_id = :user_id AND type = :transaction_type AND status = :status", array(":user_id" => ((intval($user_id) == 0) ? $userinfo['user_id']:$user_id), ":transaction_type" => _CREDIT_TRANSACTION_SUSPEND, ":status" => _CREDIT_STATUS_OK));
	
	if($result->count() > 0)
	{
		$row = $result->results()[0];
		$total_blocked_credit = array(intval($row['total_blocked_credit']), intval($row['suspended_all']));
	}
	
	return $total_blocked_credit;
}

function user_credits_allowed($user_id=0)
{
	global $db, $userinfo, $users_system;
	
	$user_credit = 0;
		
	if(intval($user_id) != 0)
	{
		$u_result = $db->table($users_system->users_table)
						->WHERE($users_system->user_fields['user_id'], $user_id)
						->select(["user_credit" => $users_system->user_fields['user_credit']]);
						
		if($u_result->count() != 0)
		{
			$u_row = $u_result->results()[0];
			$user_credit = intval($u_row['user_credit']);
		}
	}
	else
		$user_credit = (isset($userinfo['user_credit'])) ? $userinfo['user_credit']:0;
	
	$total_blocked_credit =  user_credits_blocked($user_id);
	
	if($total_blocked_credit[1] > 0)
		return 0;
	
	$credits_diff = $user_credit-$total_blocked_credit[0];
	if($credits_diff > 0)
		return $credits_diff;
	else
		return 0;
}

function user_credit_reward($request_fields, $new_credit, $user_id=0)
{
	global $db, $userinfo, $users_system;	
					
	$result = $db->table(TRANSACTIONS_TABLE)
		->insert($request_fields);
	user_credit_update($user_id, $new_credit);
}

function user_credit_update($user_id, $new_credit)
{
	global $db, $nuke_configs, $users_system;
	
	$db->table($users_system->users_table)
		->where($users_system->user_fields['user_id'], ((intval($user_id) == 0) ? $userinfo['user_id']:intval($user_id)))
		->update([$users_system->user_fields['user_credit'] => $new_credit]);
}

function credit_get_gateways_list($html=false)
{
	global $module_name, $pn_credits_config;
	$create_options = array();
	
	$gateways_list = get_dir_list("modules/$module_name/includes/gateways", 'files', true);
	$hav_gateway = false;
	foreach($gateways_list as $gateway)
	{
		unset($gateway_class);
		include("modules/$module_name/includes/gateways/$gateway");
		$func_name = str_replace(".php","",$gateway)."_gateway";
		if(class_exists("$func_name"))
		{
			$gateway_class = new $func_name();
			
			if(isset($pn_credits_config['gateways'][$gateway_class->gateway_name]) && $pn_credits_config['gateways'][$gateway_class->gateway_name]['status'] == 1 && $html)
			{
				$hav_gateway = true;
				$create_options[$gateway_class->gateway_name] = array("title" => $gateway_class->gateway_title, "icon" => $gateway_class->gateway_icon);
			}
			else
				$create_options[] = $gateway_class->gateway_name;
		}
	}
	
	if($html && $hav_gateway)
	{
		$html = '';
		
		foreach($create_options as $gateway_name => $gateway_data)
		{
			$html .= "<option value=\"$gateway_name\">".$gateway_data['title']."</option>";
		}
		
		$create_options = $html;
	}
	else
		$create_options = '';
	return $create_options;
}

function credits_get_type_desc($type)
{
	$type_desc = '';
	switch($type)
	{
		case""._CREDIT_TRANSACTION_DEPOSTIT."":
			$type_desc = _CREDITS_DEPOSIT;
		break;
		case""._CREDIT_TRANSACTION_WITHDRAW."":
			$type_desc = _CREDITS_WITHDRAW;		
		break;
		case""._CREDIT_TRANSACTION_TRANSFER_D."":
			$type_desc = _CREDITS_TRANSFER_D;
		break;
		case""._CREDIT_TRANSACTION_TRANSFER_W."":
			$type_desc = _CREDITS_TRANSFER_W;
		break;
		case""._CREDIT_TRANSACTION_SUSPEND."":
			$type_desc = _CREDITS_SUSPEND;
		break;
	}
	
	return $type_desc;
}

function credits_get_type_color($type)
{
	$type_color = '000000';
	
	switch($type)
	{
		case""._CREDIT_TRANSACTION_DEPOSTIT."":
		case""._CREDIT_TRANSACTION_TRANSFER_D."":
			$type_color = "green";
		break;
		case""._CREDIT_TRANSACTION_WITHDRAW."":
		case""._CREDIT_TRANSACTION_TRANSFER_W."":
			$type_color = "red";
		break;
		case""._CREDIT_TRANSACTION_SUSPEND."":
			$type_color = "orange";
		break;
	}
	
	return $type_color;
}

function credits_get_type_icon($type)
{
	$type_icon = '+';
	
	switch($type)
	{
		case""._CREDIT_TRANSACTION_DEPOSTIT."":
		case""._CREDIT_TRANSACTION_TRANSFER_D."":
			$type_icon = "+";
		break;
		case""._CREDIT_TRANSACTION_WITHDRAW."":
		case""._CREDIT_TRANSACTION_TRANSFER_W."":
			$type_icon = "-";
		break;
		case""._CREDIT_TRANSACTION_SUSPEND."":
			$type_icon = "";
		break;
	}
	
	return $type_icon;
}

function _credit_view($tid, $in_admin = false)
{
	global $db, $nuke_configs, $module_name, $pn_credits_config, $userinfo, $search_data, $users_system;
	
	$contents = '';
	
	$tid = intval($tid);
	$result = $db->query("SELECT t.*, u.".$users_system->user_fields['username']." as username, u.".$users_system->user_fields['realname']." as realname, u2.".$users_system->user_fields['username']." as rel_username, u2.".$users_system->user_fields['realname']." as rel_user_realname FROM ".TRANSACTIONS_TABLE." as t LEFT JOIN ".$users_system->users_table." AS u ON u.".$users_system->user_fields['user_id']." = t.user_id LEFT JOIN ".$users_system->users_table." AS u2 ON u2.".$users_system->user_fields['user_id']." = t.rel_user_id WHERE t.tid = ?", array($tid));

	if($result->sql_numrows() > 0)
	{
		$row = $result->results()[0];
		
		$order_data = (isset($row['order_data']) && $row['order_data'] != '' && !empty($row['order_data'])) ? objectToArray(json_decode(str_replace("'",'"', $row['order_data']))):"";
		
		$details = array();
	
		$details['create_time'] = nuketimes($row['create_time'], true, true, false, 3);
		$details['update_time'] = nuketimes($row['update_time'], true, true, false, 3);
		$details['factor_number'] = $row['factor_number'];
		$details['title'] = $row['title'];
		$details['description'] = $row['description'];
		
		switch($row['status'])
		{
			case _CREDIT_STATUS_NORMAL:
				$details['status'] = "<span style=\"color:red;\"><i class=\"glyphicon glyphicon-remove\"></i> "._CREDITS_NORMAL."</span>";
			break;
			case _CREDIT_STATUS_OK:
				$details['status'] = "<span style=\"color:green;\"><i class=\"glyphicon glyphicon-ok\"></i> "._CREDITS_OK."</span>";
			break;
			case _CREDIT_STATUS_PENDING:
				$details['status'] = "<span style=\"color:pink;\"><i class=\"glyphicon glyphicon-time\"></i> "._CREDITS_PENDING."</span>";
			break;
			case _CREDIT_STATUS_CANCELED:
				$details['status'] = "<span style=\"color:orange;\"><i class=\"glyphicon glyphicon-remove\"></i> "._CREDITS_CANCELED."</span>";
			break;
			case _CREDIT_STATUS_FAILED:
				$details['status'] = "<span style=\"color:red;\"><i class=\"glyphicon glyphicon-remove\"></i> "._CREDITS_FAILED."</span>";
			break;
		}
		
		$user_name = (isset($row['realname']) && $row['realname'] != '') ? $row['realname']." ( ".$row['username']." )":$row['username'];
				
		$details['transaction_by'] = ($row['aid'] != '') ? sprintf(_CREDITS_BY_ADMIN, $row['aid']):$user_name;
		
		$details['type'] = credits_get_type_desc($row['type'])." ".(($row['type'] == _CREDIT_TRANSACTION_DEPOSTIT || $row['type'] == _CREDIT_TRANSACTION_TRANSFER_D) ? (($row['gateway'] != "offline") ? _CREDITS_GATEWAY_ONLINE:_CREDITS_GATEWAY_OFFLINE):"");

		$rel_user_name = (isset($row['rel_user_realname']) && $row['rel_user_realname'] != '') ? $row['rel_user_realname']." ( ".$row['rel_username']." )":$row['rel_username'];
		
		if($row['type'] == _CREDIT_TRANSACTION_TRANSFER_D)
			$details['rel_from_user_realname'] = $rel_user_name;
		elseif($row['type'] == _CREDIT_TRANSACTION_TRANSFER_W)
			$details['rel_to_user_realname'] = $rel_user_name;
			
		$details['amount'] = number_format($row['amount'], 0)." "._RIAL."";
		
		if($row['type'] == 1 && isset($row['data']) && $row['data'] != '')
		{
			$row['data'] = phpnuke_unserialize($row['data']);
			$row['data'] = objectToArray($row['data']);
			
			if(isset($row['data']['gateway']) && $row['data']['gateway'] == "offline")
			{
				$details['offline_time'] = nuketimes($row['data']['datetime'], false, false, false, 1);
				$details['offline_number'] = $row['data']['number'];
				$details['fish_image_url'] = $row['fish_image'];
				if(isset($row['data']['delete_reason']))
					$details['status'] .= " <span style=\"color:red;\">(".$row['data']['delete_reason'].")</span>";
				if(isset($row['data']['failed_reason']))
					$details['status'] .= " <span style=\"color:red;\">(".$row['data']['failed_reason'].")</span>";
				
				unset($row['gateway']);
			}
			else
			{
				eval('$details[\'gateway\'] = $row[\'data\'][\'gateway_title\'];');
				$details['payment_tranc_id'] = (isset($row['data']['tranc_id']) && $row['data']['tranc_id'] != '') ? $row['data']['tranc_id']:"";
				if(!empty($row['data']))
				{
					$details['other_online_data'] = "<table width=\"100%\">";
					foreach($row['data'] as $row_data_key => $row_data_val)
					{
						$details['other_online_data'] .= "<tr><td>$row_data_key</td><td>$row_data_val</td></tr>";
					}
					$details['other_online_data'] .= "</table>";
				}
			}
		}
		
		if((isset($row['gateway']) && $row['gateway'] == '') || $row['type'] != _CREDIT_TRANSACTION_DEPOSTIT)
			unset($row['gateway']);
		
		if($row['order_part'] != '')
			$details['order_part'] = $row['order_part'];
			
		if($row['order_id'] != '' && intval($row['order_id']) != 0)
			$details['order_id'] = $row['order_id'];
		if($row['order_link'] != '')
			$details['order_link'] = $row['order_link'];
		
		
		if($in_admin)
		{
			$contents .="<table width=\"100%\" class=\"product-table no-border hover id-form\">";
			foreach($details as $item => $value)
			{
				if($value == '' && (strtoupper($item) == "FISH_IMAGE_URL" || strtoupper($item) == "OFFLINE_NUMBER"))
					continue;
				$details_title = constant("_CREDIT_DETAILS_".strtoupper($item));
				$value = (strtoupper($item) == "FISH_IMAGE_URL" ) ? "<img src=\"data:image/jpeg;base64,".base64_encode(stripslashes($value))."\" style=\"max-width:250px;height:auto;\" />":$value;
				$contents .="
				<tr>
					<th style=\"width:150px;\">$details_title</th>
					<td>$value</td>
				</tr>";
			}
			$contents .="</table>";
		}
		else
		{
			if(file_exists("themes/".$nuke_configs['ThemeSel']."/credits_details.php"))
				include("themes/".$nuke_configs['ThemeSel']."/credits_details.php");
			elseif(function_exists("credits_details"))
				$contents .= credits_details($details);
			else
			{
				$contents .="<!-- Modal -->
				<div class=\"modal-header\">
					<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
					<h4 class=\"modal-title\">"._CREDITS_VIEW_DETAILS." $tid</h4>
				</div>
				<div class=\"modal-body\">";
				
				foreach($details as $item => $value)
				{
					if($value == '' && (strtoupper($item) == "FISH_IMAGE_URL" || strtoupper($item) == "OFFLINE_NUMBER"))
						continue;
						
					if($value == 0 && strtoupper($item) == "AMOUNT")
						continue;
						
					$details_title = constant("_CREDIT_DETAILS_".strtoupper($item));
					$value = (strtoupper($item) == "FISH_IMAGE_URL") ? "<img src=\"data:image/jpeg;base64,".base64_encode(stripslashes($value))."\" style=\"max-width:250px;height:auto;\" />":$value;
					$contents .="<div class=\"clear form-group\">
							<label for=\"credit-details-$item\" class=\"control-label col-sm-3 col-md-4\">$details_title</label>
							<div id=\"credit-details-$item\" class=\"col-sm-9 col-md-8\">$value</div>
						</div>";
				}
				$contents .= "
				</div>
				<div class=\"clear modal-footer\">
					<div class=\"errorPlacement\"></div>
					<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
				</div>";
			}
		}
		if($in_admin)
			$contents .=  jquery_codes_load();
	}
	
	return $contents;
}

function _credits_list($sort = 'DESC', $order_by = '', $entries_per_page = 20, $page = 1, $in_admin = false)
{
	global $db, $nuke_configs, $module_name, $pn_credits_config, $userinfo, $search_data, $status, $pn_Cookies, $users_system;
	
	$link_to_more = "";
	$where = array();
	$params = array();
	
	if($order_by != '')
		$link_to_more .= "&order_by=$order_by";
	if($sort != '')
		$link_to_more .= "&sort=$sort";
	
	$search_data_cookie	= '';
	$search_data_cookie = $pn_Cookies->get("credits_search_data");
	if($search_data_cookie != '')
	{
		$search_data = phpnuke_unserialize($search_data_cookie);
	}
	
	if(isset($search_data) && !empty($search_data))
	{
		extract($search_data);
		
		$search_type = intval($search_type);
		$search_status = intval($search_status);
		$transaction_type = intval($transaction_type);
		
		$search_query = adv_filter($search_query, array('sanitize_string'), array('required'));
		if($search_query[0] != 'error')
		{
			if(isset($search_query[1]) && $search_query[1] != '')
			{
				$params[":search_query"] = "%".rawurldecode($search_query[1])."%";
				$params[":l_search_query"] = "".intval($search_query[1])."";
				//$link_to_more .= "&search_data[search_query]=".rawurlencode($search_query[1])."";
			}
		}

		if(isset($transaction_from_date) && $transaction_from_date != '')
		{
			$transaction_from_date = to_mktime($transaction_from_date);
			
			//$link_to_more .= "&search_data[transaction_from_date]=$transaction_from_date";
			$params[":transaction_from_date"] = $transaction_from_date;
			$where[] = "t.create_time >= :transaction_from_date OR t.update_time >= :transaction_from_date";
		}
		
		if(isset($transaction_to_date) && $transaction_to_date != '')
		{
			$transaction_to_date = to_mktime($transaction_to_date);
			
			//$link_to_more .= "&search_data[transaction_to_date]=$transaction_to_date";
			$params[":transaction_to_date"] = $transaction_to_date;
			$where[] = "t.create_time <= :transaction_to_date OR t.update_time <= :transaction_from_date";
		}

		if($search_type != 0)
		{
			$search_where = array();
			//$link_to_more .= "&search_data[search_type]=$search_type";
			$params[":search_type"] = $search_type;
			if($search_type == 1 && intval($search_query[1]) != 0)
				$search_where[] = "t.tid = :l_search_query";
			if($search_type == 2 && intval($search_query[1]) != 0)
				$search_where[] = "t.order_id = :l_search_query OR t.factor_number = :l_search_query";
			if($search_type == 3)
				$search_where[] = "t.title LIKE :search_query";
			if($search_type == 4)
				$search_where[] = "t.description LIKE :search_query";
			if($search_type == 5)
				$search_where[] = "t.gateway LIKE :search_query";

			
			if(($search_type == 1 || $search_type == 2) && intval($search_query[1]) == 0)
			{
				unset($search_where);
				unset($params[":search_type"]);
				$where[] = "t.tid = 0";
			}
			
			if(!empty($search_where))
				$where[] = "(".implode(" OR ", $search_where).")";
		}
		elseif($search_query[0] != 'error')
			$where[] = "(t.title LIKE :search_query OR t.description LIKE :search_query OR t.order_id = :l_search_query OR t.factor_number = :l_search_query OR t.gateway = :search_query)";
		
		if($search_status != _CREDIT_STATUS_NORMAL)
		{
			//$link_to_more .= "&search_data[search_status]=$search_status";
			$params[":search_status"] = $search_status;
			$where[] = "t.status = :search_status";
		}
		if($transaction_type != 0)
		{
			//$link_to_more .= "&search_data[transaction_type]=$transaction_type";
			$params[":transaction_type"] = $transaction_type;
			$where[] = "t.type = :transaction_type";
		}
			
	}

	if(isset($status) && $status !== '')
	{
		$params[":status"] = intval($status);
		$where[] = "t.status = :status";
	}
	else
	{
		$params[":status"] = _CREDIT_STATUS_DELETED;
		$where[] = "t.status != :status";	
	}
	
	if(!$in_admin)
	{
		$params[":user_id"] = intval($userinfo['user_id']);
		$where[] = "t.user_id = :user_id";
	}
	
	if($in_admin && isset($search_data['username']) && $search_data['username'] != '')
	{
		$u_result = $db->table($users_system->users_table)
						->where($users_system->user_fields['username'], $search_data['username'])
						->select(["user_id" => $users_system->user_fields['user_id']]);
		if($u_result->count() > 0)
		{
			$rows = $u_result->results();
			$user_id = $rows[0]['user_id'];
			$params[":user_id"] = intval($user_id);
			$where[] = "t.user_id = :user_id";
		}
	}
	
	$where = array_filter($where);
	$where = (!empty($where)) ? "WHERE ".implode(" AND ", $where):'';
	$sort = ($sort != '' && in_array($sort, array("ASC","DESC"))) ? $sort:"DESC";
	$order_by = ($order_by != '') ? $order_by:'tid';
	
	$start_at  = ($page * $entries_per_page) - $entries_per_page;

	$total_rows = 0;	
	$result = $db->query("
	SELECT t.*, 
	(SELECT COUNT(tid) FROM ".TRANSACTIONS_TABLE." ".str_replace("t.","", $where).") as total_rows 
	FROM ".TRANSACTIONS_TABLE." AS t 
	$where 
	ORDER BY t.$order_by $sort LIMIT $start_at, $entries_per_page", $params);

	$rows = array();
	if($result->count() > 0)
	{
		$rows = $result->results();
		$total_rows = $rows[0]['total_rows'];
	}
	
	return array("rows" => $rows, "total_rows" => $total_rows, "link_to_more" => $link_to_more);
}

function credits_settings()
{
	global $nuke_configs, $db, $admin_file, $pn_credits_config, $default_currency;
	
	$contents = '';
	$pn_credits_config = (isset($pn_credits_config) && !empty($pn_credits_config)) ? $pn_credits_config:((isset($nuke_configs['pn_credits']) && $nuke_configs['pn_credits'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['pn_credits'])):array());

	$contents = '';
	$contents .= jquery_codes_load('', true);//reload jquery function is need in jquery yi tabs

	$contents .="
	<form action='".$admin_file.".php' method='post'>
	<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">

	<tr><th>"._CREDITS_MIN_AMOUNT."</th><td>
		<input type=\"text\" name='config_fields[pn_credits][min_amount]' class=\"inp-form-ltr\" size=\"40\" value=\"".$pn_credits_config['min_amount']."\">
	</td></tr>
	<tr><th>"._CREDITS_MAX_AMOUNT."</th><td>
		<input type=\"text\" name='config_fields[pn_credits][max_amount]' class=\"inp-form-ltr\" size=\"40\" value=\"".$pn_credits_config['max_amount']."\">
	</td></tr>
	<tr><th>"._NOTIFY_METHODS."</th><td>";
	$checked1 = (isset($pn_credits_config['notify']['sms']) && $pn_credits_config['notify']['sms'] == 1) ? "checked":"";
	$checked2 = (isset($pn_credits_config['notify']['email']) && $pn_credits_config['notify']['email'] == 1) ? "checked":"";
	$contents .= "<input type='checkbox' class='styled' name='config_fields[pn_credits][notify][sms]' value='1' data-label=\"" . _SMS . "\" $checked1> &nbsp; &nbsp;";
	$contents .= "<input type='checkbox' class='styled' name='config_fields[pn_credits][notify][email]' value='1' data-label=\"" . _EMAIL . "\" $checked2>
	</td></tr>";
	
	$contents .= "<tr><th>"._CREDITS_DIRECT_MESSAGE."</th><td>
		".wysiwyg_textarea("config_fields[pn_credits][credits_direct_msg]", $pn_credits_config['credits_direct_msg'], "PHPNukeAdmin", "50", "12")."
	</td></tr>
	
	<tr><th>"._CREDITS_LIST_MESSAGE."</th><td>
		".wysiwyg_textarea("config_fields[pn_credits][credits_list_msg]", $pn_credits_config['credits_list_msg'], "PHPNukeAdmin", "50", "12")."
	</td></tr>
	<tr><td colspan=\"2\"><hr /></td></tr>
	<tr><th colspan=\"2\" style=\"text-align:center\">"._CREDITS_CURRENCY_SETTINGS." <span class=\"add_field_icon add_field_button\" title=\""._ADD_NEW_FIELD."\"></span></th></tr><tr><th></th><td><div class=\"input_fields_wrap\">";
	
	$all_currencies = $pn_credits_config['currencies'];
	
	if(!isset($all_currencies) || empty($all_currencies))
	{
		$all_currencies = array(
			array(
				"code" => "USD",
				"name" => _CREDITS_CUR_USD,
				"rial_ex_rate" => 0
			),
			array(
				"code" => "EUR",
				"name" => _CREDITS_CUR_EUR,
				"rial_ex_rate" => 0
			),
			array(
				"code" => "AED",
				"name" => _CREDITS_CUR_AED,
				"rial_ex_rate" => 0
			),
			array(
				"code" => "GBP",
				"name" => _CREDITS_CUR_GBP,
				"rial_ex_rate" => 0
			),
			array(
				"code" => "KWD",
				"name" => _CREDITS_CUR_KWD,
				"rial_ex_rate" => 0
			),
		);
	}
	
	foreach($all_currencies as $key => $currency_data)
	{
		$remove = (isset($default_currency[$currency_data['code']])) ? "":"&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a></div>";
		$contents .="<div style=\"margin:3px 0;\">
			"._CREDITS_CUR_CODE." <input type=\"text\" name=\"config_fields[pn_credits][currencies][$key][code]\" class=\"inp-form-ltr\" size=\"5\" value=\"".$currency_data['code']."\"> &nbsp; 
			"._CREDITS_CUR_NAME." <input type=\"text\" name=\"config_fields[pn_credits][currencies][$key][name]\" class=\"inp-form\" size=\"20\" value=\"".$currency_data['name']."\"> &nbsp; 
			"._CREDITS_CUR_EXRATE." <input type=\"text\" name=\"config_fields[pn_credits][currencies][$key][rial_ex_rate]\" class=\"inp-form-ltr\" size=\"10\" value=\"".$currency_data['rial_ex_rate']."\"> "._RIAL."".$remove."</div>";
	}
	$x = $key+1;
	
	$contents .= "</div>
	
	</td></tr>
	<tr><td colspan=\"2\"><hr /></td></tr>
	
	<tr><th colspan=\"2\" style=\"text-align:center\">"._CREDITS_GATEWAYS_SETTINGS."</th></tr>";
	
	$create_options = array();
	
	$gateways_list = get_dir_list("modules/Credits/includes/gateways", 'files', true);
	foreach($gateways_list as $gateway)
	{
		unset($gateway_class);
		include("modules/Credits/includes/gateways/$gateway");
		$func_name = str_replace(".php","",$gateway)."_gateway";
		if(class_exists("$func_name"))
		{
			$gateway_class = new $func_name();
			$contents .= $gateway_class->set_configs($pn_credits_config);
		}
	}
		
	$contents.="<tr><td colspan=\"2\"><input type='hidden' name='op' value='save_configs'>
	<input type='hidden' name='return_op' value='settings#credits_settings'>
	<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
	<input class=\"form-submit\" type='submit' name='submit' value='" . _SAVECHANGES . "'></td></tr></table></form>
	<script>
		$(document).ready(function(){
			$(\".input_fields_wrap\").add_field({ 
				addButton: $(\".add_field_button\"),
				remove_button: '.remove_field',
				fieldHTML: '<div style=\"margin:3px 0;\">			"._CREDITS_CUR_CODE." <input type=\"text\" name=\"config_fields[pn_credits][currencies][{X}][code]\" class=\"inp-form-ltr\" size=\"5\" value=\"\"> &nbsp; 			"._CREDITS_CUR_NAME." <input type=\"text\" name=\"config_fields[pn_credits][currencies][{X}][name]\" class=\"inp-form\" size=\"20\" value=\"\"> &nbsp; 			"._CREDITS_CUR_EXRATE." <input type=\"text\" name=\"config_fields[pn_credits][currencies][{X}][rial_ex_rate]\" class=\"inp-form-ltr\" size=\"10\" value=\"\"> "._RIAL."&nbsp; &nbsp; <a href=\"#\" class=\"remove_field\">"._REMOVE."</a></div>',
				x: $x,
			});
		});
	</script>";
	die($contents);		
}

function credits_currency_cal($amount, $in_currency='')
{
	global $pn_credits_config;
	
	if($in_currency == '')
		return $amount;
	
	$all_currencies = $pn_credits_config['currencies'];
	foreach($all_currencies as $currency_data)
	{
		if($currency_data['code'] == $in_currency)
		{
			$amount = $amount*$currency_data['rial_ex_rate'];
		}
	}
	
	return $amount;
}

$other_admin_configs['credits_settings'] = array("title" => _CREDITS_SETTINGS, "function" => "credits_settings", "God" => false);

$nuke_modules_boxes_parts[$this_module_name] = array(
	"list" => "_CREDITS_LIST",
	"form" => "_CREDITS_FORM",
);

?>