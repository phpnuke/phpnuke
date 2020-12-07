<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if ( !defined('MODULE_FILE') ) {
	die("Illegal Module File Access");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));

define('INDEX_FILE', is_index_file($module_name));// to define INDEX_FILE status

if(!is_user())
{
	redirect_to(LinkToGT("index.php?modname=Users"));
}

global $db, $nuke_configs;

$pn_credits_config = (isset($pn_credits_config) && !empty($pn_credits_config)) ? $pn_credits_config:((isset($nuke_configs['pn_credits']) && $nuke_configs['pn_credits'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['pn_credits'])):array());

function credits_list($sort = 'DESC', $order_by = '')
{
	global $db, $nuke_configs, $module_name, $pn_credits_config, $userinfo, $page, $search_data, $pn_Cookies, $users_system;
	
	$contents = '';
	$userinfo = $users_system->getuserinfo(true);
	$entries_per_page = 20;
	$current_page = (empty($page)) ? 1 : $page;
	$rows_data = _credits_list($sort, $order_by, $entries_per_page, $current_page, false);

	if(isset($search_data) && !empty($search_data))
	{
		$pn_Cookies->set("credits_search_data", phpnuke_serialize($search_data));
		extract($search_data);
	}
	
	$page_title = _CREDITS_LIST;	

	$sort = ($sort != '' && in_array($sort, array("ASC","DESC"))) ? $sort:"DESC";
	$sort_reverse = ($sort == 'ASC') ? "DESC":"ASC";	
	$sort_icon = ($sort == 'ASC') ? "up":"down";	
	$order_by = ($order_by != '') ? $order_by:'tid';
	$link_to_more = (isset($rows_data['link_to_more']) && $rows_data['link_to_more'] != '') ? $rows_data['link_to_more']:"";
	
	$link_to = "index.php?modname=Credits&op=credits_list".$link_to_more;

	if(isset($rows_data['rows']) && !empty($rows_data['rows']))
	{
		$rows = $rows_data['rows'];
		$total_rows = (isset($rows_data['total_rows']) && $rows_data['total_rows'] != 0) ? $rows_data['total_rows']:0;
		$pagination = '';
		if($total_rows > $entries_per_page)
		{
			$pagination .= "<div id=\"pagination\" class=\"text-center pagination\">";
			$pagination .= clean_pagination($total_rows, $entries_per_page, $current_page, $link_to);
			$pagination .= "</div>";
		}
		
		if(file_exists("themes/".$nuke_configs['ThemeSel']."/credits_list.php"))
			include("themes/".$nuke_configs['ThemeSel']."/credits_list.php");
		elseif(function_exists("credits_list_html"))
			$contents .= credits_list_html($order_data);
		else
			include("modules/$module_name/includes/credits_list.php");
	}
	else
	{
		$contents .=OpenTable();
		$contents .= "<p class=\"text-center\">"._CREDIT_NO_TRANSACTION."</p>";
		$contents .=CloseTable();
	}
	
	$boxes_contents = show_modules_boxes($module_name, "list", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);

	$default_css[] = "<link rel=\"stylesheet\" href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/select2.css\">";
	$default_css[] = "<link href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/jquery-ui.min.css\" rel=\"stylesheet\" type=\"text/css\">";
		
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/select2.min.js\" /></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc.js\" type=\"text/javascript\"></script>";
	$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/datepicker/js/calendar.js\" type=\"text/javascript\"></script>";
	
	if($nuke_configs['multilingual'] == 1)
	{
		$default_css[] = "<link href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/jquery-ui.min.rtl.css\" rel=\"stylesheet\" type=\"text/css\">";
		if($nuke_configs['datetype'] == 1)
			$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-fa.js\" type=\"text/javascript\"></script>";
		elseif($nuke_configs['datetype'] == 2)
			$defer_js[] = "<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-ar.js\" type=\"text/javascript\"></script>";
	}
	
	$custom_theme_setup = array(
		"default_css" => $default_css,
		"defer_js" => $defer_js
	);
	$custom_theme_setup_replace = false;
	
	$meta_tags = array(
		"title" => _CREDITS_ADMIN." - "._CREDITS_LIST,
		"description" => _CREDITS_ADMIN." - "._CREDITS_LIST,
		"extra_meta_tags" => array()
	);
	
	include("header.php");
	$html_output .= $boxes_contents;
	include("footer.php");
}

function credit_edit($order_data = array())
{
	global $db, $nuke_configs, $pn_credits_config, $module_name, $userinfo, $users_system, $pn_Cookies, $PnValidator, $default_currency;

	$form_title = _CREDITS_ACCOUNT_CHARGE;
	
	$userinfo = $users_system->getuserinfo(true);

	$contents = '';
	
	/*$order_data = array(
		"amount" => 20,
		"ex_rate" => "GBP",
		"title" => "تست برداشت از حساب اعتباری",
		"desc" => "شرح مختصر برداشت",
		"part" => 'vip',
		"part_desc" => 'فروش اکانت VPN',
		"id" => $order_id,
		"link" => LinkToGT("index.php?modname=feedback"),
		"reward" => 30
	);*/
	
	if(isset($order_data) && !empty($order_data))
	{
		$amount = (isset($order_data['ex_rate']) && $order_data['ex_rate']!= '') ? credits_currency_cal($order_data['amount'], $order_data['ex_rate']):$order_data['amount'];
		$amount_in_ex_rate = (isset($order_data['ex_rate']) && $order_data['ex_rate']!= '') ? "( ".$order_data['amount']." ".constant($default_currency[$order_data['ex_rate']])." )":"";
		
		// Get or set the filtering rules
		$PnValidator->validation_rules(array(
			'link'			=> 'valid_url',
		)); 
		
		// Get or set the filtering rules
		$PnValidator->filter_rules(array(
			'amount'		=> 'sanitize_numbers',
			'id'			=> 'sanitize_numbers',
			'reward'		=> 'sanitize_numbers',
			'title'			=> 'sanitize_string',
			'desc'			=> 'sanitize_string',
			'part'			=> 'sanitize_string'
		)); 

		$order_data = $PnValidator->sanitize($order_data, array(), true, true);
		$validated_data = $PnValidator->run($order_data);
		if($validated_data !== FALSE)
		{
			$order_data = $validated_data;
		}
		else
		{
			die_error($PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br />'));
		}
	
		$result = $db->table(TRANSACTIONS_TABLE)
					->where('order_id', $order_data['id'])
					->where('order_part', $order_data['part'])
					->select(['status']);
		if($result->count() > 0)
		{
			$row = $result->results()[0];
			$status = $row['status'];
			if($status != _CREDIT_STATUS_NORMAL)
			{
				die_error(""._CREDITS_TRANSACTION_DONE."<br /><a href=\"".$order_data['link']."\">".$order_data['title']."</a>");
			}
		}
		$form_title = ""._CREDITS_PAY_ORDER." <a href=\"".$order_data['link']."\">".$order_data['title']."</a>";
	}
	
	$user_credits_allowed = user_credits_allowed();
	
	if(file_exists("themes/".$nuke_configs['ThemeSel']."/credits_form.php"))
		include("themes/".$nuke_configs['ThemeSel']."/credits_form.php");
	elseif(function_exists("credits_form"))
		$contents .= credits_form($order_data);
	else
		include("modules/$module_name/includes/credits_form.php");
	
	$boxes_contents = show_modules_boxes($module_name, "form", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
		
	$custom_theme_setup = array(
		"default_css" => ((isset($default_css) && !empty($default_css)) ? $default_css:""),
		"default_js" => ((isset($default_js) && !empty($default_js)) ? $default_js:""),
		"defer_js" => ((isset($defer_js) && !empty($defer_js)) ? $defer_js:"")
	);
	$custom_theme_setup_replace = false;

	$meta_tags = array(
		"title" => $form_title,
		"description" => ""._CREDITS_ADMIN." - ".$form_title,
		"extra_meta_tags" => array()
	);
	
	include("header.php");
	$html_output .= $boxes_contents;
	include("footer.php");
}

function credit_create_form($order_data, $credit_method, $credit_gateway, $offline_credit, $online_credit, $offline_credit_file)
{
	global $db, $nuke_configs, $module_name, $userinfo, $pn_credits_config, $users_system, $default_currency;
	
	$order_data = (isset($order_data) && $order_data != '' && !empty($order_data)) ? objectToArray(json_decode(str_replace("'",'"', $order_data))):"";
	
	$credit_method = intval($credit_method);
	
	$transaction_type = ($credit_method == 3) ? _CREDIT_TRANSACTION_WITHDRAW:_CREDIT_TRANSACTION_DEPOSTIT;
	
	$errors = array();
	
	$amount = (isset($offline_credit['amount']) && $offline_credit['amount'] != '') ? $offline_credit['amount']:((isset($online_credit['amount']) && $online_credit['amount'] != '') ? $online_credit['amount']:0);
	
	$title = (isset($offline_credit['title']) && $offline_credit['title'] != '') ? $offline_credit['title']:((isset($online_credit['title']) && $online_credit['title'] != '') ? $online_credit['title']:'');
	
	$description = (isset($offline_credit['desc']) && $offline_credit['desc'] != '') ? $offline_credit['desc']:((isset($online_credit['desc']) && $online_credit['desc'] != '') ? $online_credit['desc']:'');
	
	$order_id = 0;
	$order_link = '';
	$order_part = '';
	if($order_data != '')
	{
		$amount = (isset($order_data['ex_rate']) && $order_data['ex_rate']!= '') ? credits_currency_cal($order_data['amount'], $order_data['ex_rate']):$order_data['amount'];
		$order_id = $order_data['id'];
		$order_link = $order_data['link'];
		$order_part = $order_data['part'];
	}
	
	if($amount != 0)
	{
		$amount = explode(" ", $amount);
		$amount = intval(str_replace(",", "", $amount[0]));
		if($amount > $pn_credits_config['max_amount'])
		{
			$errors[] = _CREDITS_MORE_THAN_AMOUNT;
		}
		if($amount < $pn_credits_config['min_amount'])
		{
			$errors[] = _CREDITS_LESS_THAN_AMOUNT;
		}
	}
	
	if(empty($errors))
	{
		$request_fields = array(
			"user_id" => $userinfo['user_id'],
			"factor_number" => _NOWTIME,
			"create_time" => _NOWTIME,
			"update_time" => '',
			"status" => _CREDIT_STATUS_NORMAL,
			"type" => $transaction_type,
			"gateway" => ($credit_method != 1) ? '':$credit_gateway,
			"data" => '',
			"amount" => $amount,
			"title" => $title,
			"description" => $description,
			"order_part" => $order_part,
			"order_id" => $order_id,
			"order_link" => $order_link,
			"order_data" => phpnuke_serialize($order_data),
		);
		
		$result = $db->table(TRANSACTIONS_TABLE)
			->insert($request_fields);

		$tid = $db->lastInsertId();
		
		$request_fields['name'] = $userinfo['realname'];
		
		$request_fields['phone'] = (isset($userinfo['user_phone']) && $userinfo['user_phone'] != '') ? $userinfo['user_phone']:"";
		
		$request_fields['mail'] = $userinfo['user_email'];
		
		if($credit_method == 1)
		{
			$db->table(TRANSACTIONS_TABLE)
				->where('tid', $tid)
				->update([
					'gateway' => $credit_gateway,
				]);
				
			include("modules/$module_name/includes/gateways/$credit_gateway.php");
			$class_name = $credit_gateway."_gateway";
			$class_obj = new $class_name();
			
			$result = $class_obj->create_form($tid, $request_fields);
			
			if($result === false)
				$errors[] = _CREDITS_GATEWAY_CON_ERROR;
			
			if(isset($result['error_code']) && intval($result['error_code']) > 0)
				$errors[] = $result['error_message'];
		}
		elseif($credit_method == 2)
		{
			$payment_data = array();
			$payment_data['gateway'] = 'offline';
			$payment_data['datetime'] = to_mktime($offline_credit['date']);
			$payment_data['number'] = $offline_credit['number'];
			$imgContent = '';
			if(isset($offline_credit_file["tmp_name"]))
			{
				$check = getimagesize($offline_credit_file["tmp_name"]);
				if($check !== false)
				{
					$image = $offline_credit_file['tmp_name'];
					$imgContent = addslashes(file_get_contents($image));
				}else
					$errors[] = _CREDITS_UPLOAD_FISH;
			}
			
			$db->table(TRANSACTIONS_TABLE)
				->where('tid', $tid)
				->update([
					'update_time' => _NOWTIME,
					'gateway' => 'offline',
					'status' => _CREDIT_STATUS_PENDING,
					'data' => phpnuke_serialize($payment_data),
					'fish_image' => $imgContent,
				]);
			
			if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && $userinfo['user_phone'] != '' && isset($pn_credits_config['notify']['sms']) && $pn_credits_config['notify']['sms'] == 1)
			{
				$message = sprintf(_CREDIT_OFFLINE_FORM_PENDING, $tid, number_format($amount, 0), nuketimes(_NOWTIME, true, true, true, 1));
				pn_sms('send', $userinfo['user_phone'], $message);
			}
			
			if(isset($pn_credits_config['notify']['email']) && $pn_credits_config['notify']['email'] == 1)
			{				
				$message = array(
					"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "offline_pending.txt"),
					"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
					"username" => $userinfo['username'], 
					"tid" => $tid, 
					"amount" => number_format($amount, 0), 
					"create_time" => _NOWTIME
				);
				
				phpnuke_mail($userinfo['user_email'], sprintf(_CREDITS_EMAIL_OFFLINE_PENDING, $nuke_configs['sitename']), $message);
			}
			
			if($order_data != '')
				redirect_to($order_link);
			else
				redirect_to(LinkToGT("index.php?modname=$module_name&op=credits_list"));
		}
		elseif($credit_method == 3)
		{
			$user_credit = $userinfo['user_credit'];
			$user_credits_allowed = user_credits_allowed();
			if($user_credits_allowed > 0)
			{
				$result = $db->table(TRANSACTIONS_TABLE)
					->where('tid', $tid)
					->update(["status" => _CREDIT_STATUS_OK, "update_time" => _NOWTIME]);
				$user_credit = $user_credit-$amount;
				
				user_credit_update($userinfo['user_id'], $user_credit);
				
				if($order_data['reward'] != 0)
				{
					$request_fields = array(
						"user_id" => $userinfo['user_id'],
						"factor_number" => _NOWTIME,
						"create_time" => _NOWTIME,
						"update_time" => _NOWTIME,
						"status" => _CREDIT_STATUS_OK,
						"type" => _CREDIT_TRANSACTION_DEPOSTIT,
						"gateway" => '',
						"data" => '',
						"amount" => $order_data['reward'],
						"title" => _CREDITS_REWARD,
						"description" => sprintf(_CREDITS_REWARD_FOR, $description),
						"order_part" => $order_part,
						"order_id" => $order_id,
						"order_link" => $order_link,
					);
					
					user_credit_reward($request_fields, ($user_credit+$order_data['reward']));
				}
				$userinfo = $users_system->getuserinfo(true);
			
				if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && $userinfo['user_phone'] != '' && isset($pn_credits_config['notify']['sms']) && $pn_credits_config['notify']['sms'] == 1)
				{
					$sms_text = sprintf(_CREDITS_PAY_BY_CREDIT_SMS, $tid, $amount, number_format($user_credit, 0), nuketimes(_NOWTIME, true, true, true, 1));
					pn_sms('send', $userinfo['user_phone'], $sms_text);
				}
			
				if(isset($pn_credits_config['notify']['email']) && $pn_credits_config['notify']['email'] == 1)
				{				
					$message = array(
						"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "order_ok.txt"),
						"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
						"username" => $userinfo['username'], 
						"tid" => $tid, 
						"order_id" => $order_id, 
						"order_part" => $order_part, 
						"order_link" => LinkToGT($order_link), 
						"amount" => number_format($amount, 0), 
						"update_time" => _NOWTIME
					);
					
					phpnuke_mail($userinfo['user_email'], sprintf(_CREDITS_EMAIL_ORDER_OK, $nuke_configs['sitename']), $message);
				}
			}
			else
				$errors[] = _CREDITS_REMAIN_LESS_THAN_AMOUNT;
			
			redirect_to($order_link);
		}
	}

	if(!empty($errors))
	{
		$contents = "";
		foreach($errors as $error)
		{
			$contents .="<div class=\"text-jsutify\">$error</div>";
		}
		
		die_error($contents);
	}
	die();
}

function credit_response($tid, $credit_gateway='')
{
	global $db, $nuke_configs, $module_name, $userinfo, $users_system;
	
	$result = $db->table(TRANSACTIONS_TABLE)
				->where('tid', $tid)
				->select();
	if($result->count() > 0)
	{
		$row = $result->results()[0];
		
		if($row['gateway'] == $credit_gateway)
		{	
			$status = $row['status'];
			
			if($status != _CREDIT_STATUS_NORMAL)
			{
				die_error(""._CREDITS_TRANSACTION_DONE."<br /><a href=\"".$row['order_link']."\">".$row['title']."</a>");
			}
				
			$order_data = (isset($row['order_data']) && $row['order_data'] != '' && !empty($row['order_data'])) ? phpnuke_unserialize($row['order_data']):"";

			unset($gateway_class);
			include("modules/$module_name/includes/gateways/".$row['gateway'].".php");
			$func_name = $row['gateway']."_gateway";
			
			if(class_exists("$func_name"))
			{
				$gateway_class = new $func_name();
				$response = $gateway_class->response($tid, $row['factor_number']);
				
				if($response['result'] === true)
				{
					$user_credit = $userinfo['user_credit'];
					$new_user_credit = $user_credit+$row['amount'];
					
					user_credit_update($userinfo['user_id'], $new_user_credit);
					$userinfo = $users_system->getuserinfo(true);
					$update_data = array(
						"status" => _CREDIT_STATUS_OK,
						"update_time" => _NOWTIME,
						"data" => phpnuke_serialize($response)
					);
					
					$db->table(TRANSACTIONS_TABLE)
						->where('tid', $tid)
						->update($update_data);

					if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && $userinfo['user_phone'] != '' && isset($pn_credits_config['notify']['sms']) && $pn_credits_config['notify']['sms'] == 1)
					{
						$sms_text = ($order_data != '') ? sprintf(_CREDITS_PAY_ONLINE_ORDER_SMS, $tid, $row['amount'], $row['order_id'], nuketimes(_NOWTIME, true, true, true, 1)):sprintf(_CREDITS_PAY_ONLINE_NOORDER_SMS, $tid, number_format($row['amount'], 0), nuketimes(_NOWTIME, true, true, true, 1));
						pn_sms('send', $userinfo['user_phone'], $sms_text);
					}
					
					if(isset($pn_credits_config['notify']['email']) && $pn_credits_config['notify']['email'] == 1)
					{				
						$message = array(
							"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "online_ok.txt"),
							"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
							"username" => $userinfo['username'], 
							"tid" => $tid, 
							"type" => _CREDITS_ACCOUNT_CHARGE, 
							"current_credit" => number_format($new_user_credit, 0), 
							"amount" => number_format($amount, 0), 
							"update_time" => _NOWTIME
						);
						
						phpnuke_mail($userinfo['user_email'], sprintf(_CREDITS_EMAIL_ONLINE_OK, $nuke_configs['sitename']), $message);
					}
					
					if($order_data != '')
					{
						$user_credits_allowed = user_credits_allowed();
						if($user_credits_allowed > 0)
						{
							$user_credit = $new_user_credit-$row['amount'];
							
							user_credit_update($userinfo['user_id'], $user_credit);
							
							$request_fields = array(
								"user_id" => $userinfo['user_id'],
								"factor_number" => $row['factor_number'],
								"create_time" => _NOWTIME,
								"update_time" => _NOWTIME,
								"status" => _CREDIT_STATUS_OK,
								"type" => _CREDIT_TRANSACTION_WITHDRAW,
								"gateway" => '',
								"data" => '',
								"amount" => $row['amount'],
								"title" => $row['title'],
								"description" => $row['description'],
								"order_part" => (isset($row['order_part'])) ? $row['order_part']:"",
								"order_id" => (isset($row['order_id'])) ? $row['order_id']:"",
								"order_link" => (isset($row['order_link'])) ? $row['order_link']:"",
								"order_data" => phpnuke_serialize($order_data),
							);
							
							$result = $db->table(TRANSACTIONS_TABLE)
								->insert($request_fields);

							$tid = $db->lastInsertId();

							if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && $userinfo['user_phone'] != '' && isset($pn_credits_config['notify']['sms']) && $pn_credits_config['notify']['sms'] == 1)
							{
								$sms_text = sprintf(_CREDITS_PAY_ONLINE_ORDER_SMS, $tid, $row['amount'], $request_fields['order_id'], nuketimes(_NOWTIME, true, true, true, 1));
								pn_sms('send', $userinfo['user_phone'], $sms_text);
							}
						
							if(isset($pn_credits_config['notify']['email']) && $pn_credits_config['notify']['email'] == 1)
							{				
								$message = array(
									"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "order_ok.txt"),
									"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
									"username" => $userinfo['username'], 
									"tid" => $tid, 
									"order_id" => $request_fields['order_id'], 
									"order_part" => $request_fields['order_part'], 
									"order_link" => LinkToGT($request_fields['order_link']), 
									"amount" => number_format($row['amount'], 0), 
									"update_time" => _NOWTIME
								);
								
								phpnuke_mail($userinfo['user_email'], sprintf(_CREDITS_EMAIL_ORDER_OK, $nuke_configs['sitename']), $message);
							}
							
							if(isset($order_data['reward']) && $order_data['reward'] != 0)
							{
								$request_fields = array(
									"user_id" => $userinfo['user_id'],
									"factor_number" => _NOWTIME,
									"create_time" => _NOWTIME,
									"update_time" => _NOWTIME,
									"status" => _CREDIT_STATUS_OK,
									"type" => _CREDIT_TRANSACTION_DEPOSTIT,
									"gateway" => '',
									"data" => '',
									"amount" => $order_data['reward'],
									"title" => _CREDITS_REWARD,
									"description" => sprintf(_CREDITS_REWARD_FOR, $row['description']),
									"order_part" => $row['order_part'],
									"order_id" => $row['order_id'],
									"order_link" => $row['order_link'],
									"order_data" => phpnuke_serialize($order_data),
								);
								
								$last_user_credit = ($user_credit+$order_data['reward']);
								
								user_credit_reward($request_fields, ($user_credit+$last_user_credit));
								
								if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && $userinfo['user_phone'] != '' && isset($pn_credits_config['notify']['sms']) && $pn_credits_config['notify']['sms'] == 1)
								{
									$sms_text = sprintf(_CREDITS_REWARD_SMS, $tid, number_format($order_data['reward'], 0), $row['order_id'], nuketimes(_NOWTIME, true, true, true, 1));
									pn_sms('send', $userinfo['user_phone'], $sms_text);
								}
					
								if(isset($pn_credits_config['notify']['email']) && $pn_credits_config['notify']['email'] == 1)
								{				
									$message = array(
										"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "online_ok.txt"),
										"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
										"username" => $userinfo['username'], 
										"tid" => $tid, 
										"type" => sprintf(_CREDITS_REWARD_FOR, $row['description']), 
										"current_credit" => number_format($last_user_credit, 0), 
										"amount" => number_format($order_data['reward'], 0), 
										"update_time" => _NOWTIME
									);
									
									phpnuke_mail($userinfo['user_email'], sprintf(_CREDITS_EMAIL_REWARD_OK, $nuke_configs['sitename']), $message);
								}
							}
							$userinfo = $users_system->getuserinfo(true);
						}
						
						redirect_to($row['order_link']);
						die();
					}
					
					redirect_to(LinkToGT("index.php?modname=$module_name&op=credits_list"));
					die();
				}
				else
					die_error($response['error_message']);
			}
			else
				die_error(_CREDITS_BAD_GATEWAY);
		}
		else
			die_error(_CREDITS_BAD_GATEWAY);
	}
	else
		die_error(_CREDITS_NO_RELATED_ORDER);

	die();
}

function credit_view($tid)
{
	$contents = _credit_view($tid);
	die($contents);
}

function delete_all_filters($in_admin)
{
	global $pn_Cookies, $admin_file;
	
	$pn_Cookies->delete("credits_search_data");
	
	$link = ($in_admin == "") ? LinkToGT("index.php?modname=Credits&op=credits_list"):$admin_file.".php?op=credits_list";
	
	redirect_to($link);
}

$tid							= (isset($tid)) ? intval($tid):1;
$submit							= (isset($submit)) ? filter($submit, "nohtml"):"";
$search_query					= (isset($search_query)) ? filter($search_query, "nohtml"):"";
$page							= (isset($page)) ? intval($page):1;
$order_by						= (isset($order_by)) ? filter($order_by, "nohtml"):"";
$sort							= (isset($sort)) ? filter($sort, "nohtml"):"";
$in_admin						= (isset($in_admin)) ? filter($in_admin, "nohtml"):"";
$credit_gateway					= (isset($credit_gateway)) ? filter($credit_gateway, "nohtml"):"";
$offline_credit_file			= (isset($offline_credit_file)) ? $offline_credit_file:array();
$order_data						= (isset($order_data)) ? $order_data:array();
$search_data					= (isset($search_data)) ? $search_data:array();
$op								= (isset($op)) ? filter($op, "nohtml"):"";

switch($op)
{	
	default:
		credit_edit($order_data);
	break;
	
	case"credits_list":
		credits_list($sort, $order_by);
	break;
	
	case"credit_create_form":
		credit_create_form($order_data, $credit_method, $credit_gateway, $offline_credit, $online_credit, $offline_credit_file);
	break;
	
	case"credit_response":
		credit_response($tid, $credit_gateway);
	break;
	
	case"credit_view":
		credit_view($tid);
	break;
	
	case"delete_all_filters":
		delete_all_filters($in_admin);
	break;
}

?>