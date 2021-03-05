<?php

class idpay_gateway{
	
	var $gateway_name			= "idpay";
	var $gateway_title			= "درگاه پرداخت idpay";
	var $gateway_icon			= "";
	var $request_address		= "https://api.idpay.ir/v1.1/payment";
	var $verify_address			= "https://api.idpay.ir/v1.1/payment/verify";
	var $inquiry_address		= "https://api.idpay.ir/v1.1/payment/inquiry";
	var $response				= array();
	var $pn_reserve_configs		= array();
	
	private $request_response = array(
		"200" => array("","تراکنش با موفقیت ایجاد شد"),
		"400" => array("52","استعلام نتیجه ای نداشت."),
		"403" => array(
			"11" => "کاربر مسدود شده است.",
			"12" => "API Key یافت نشد.",
			"13" => "درخواست شما از {ip} ارسال شده است. این IP با IP های ثبت شده در وب سرویس همخوانی ندارد.",
			"14" => "وب سرویس تایید نشده است.",
			"21" => "حساب بانکی متصل به وب سرویس تایید نشده است.",
		),
		"406" => array(
			"31" => "کد تراکنش id نباید خالی باشد.",
			"32" => "شماره سفارش order_id نباید خالی باشد.",
			"33" => "مبلغ amount نباید خالی باشد.",
			"34" => "مبلغ amount باید بیشتر از {min-amount} ریال باشد.",
			"35" => "مبلغ amount باید کمتر از {max-amount} ریال باشد.",
			"36" => "مبلغ amount بیشتر از حد مجاز است.",
			"37" => "آدرس بازگشت callback نباید خالی باشد.",
			"38" => "درخواست شما از آدرس {domain} ارسال شده است. دامنه آدرس بازگشت callback با آدرس ثبت شده در وب سرویس همخوانی ندارد.",
		),
		"405" => array(
			"51" => "تراکنش ایجاد نشد.",
			"53" => "تایید پرداخت امکان پذیر نیست.",
			"54" => "مدت زمان تایید پرداخت سپری شده است.",
		)
	);
	
	private $transaction_result = array(
		"1" => "پرداخت انجام نشده است",
		"2" => "پرداخت ناموفق بوده است",
		"3" => "خطا رخ داده است",
		"4" => "بلوکه شده",
		"5" => "برگشت به پرداخت کننده",
		"6" => "برگشت خورده سیستمی",
		"7" => "انصراف از پرداخت",
		"8" => "به درگاه پرداخت منتقل شد",
		"10" => "در انتظار تایید پرداخت",
		"100" => "پرداخت تایید شده است",
		"101" => "پرداخت قبلا تایید شده است",
		"200" => "به دریافت کننده واریز شد",
	);
	
	function __construct(){
		global $module_name, $nuke_configs;
	
		if(file_exists("modules/$module_name/includes/idpay.gif"))
			$this->gateway_icon = "modules/$module_name/includes/idpay.gif";
		
		$this->pn_reserve_configs = (isset($nuke_configs['reserve_configs']) && $nuke_configs['reserve_configs'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['reserve_configs'])):array();

		return true;
	}
	
	function get_error($result, $http_status)
	{
		$this->response['error_code'] = $result->error_code;
		$this->response['error_message'] = (isset($this->request_response[$http_status][$result->error_code])) ? $this->request_response[$http_status][$result->error_code]:"خطا";
	}

	function create_form($tid, $form_data)
	{
		global $db, $module_name, $pn_Sessions, $nuke_configs, $pn_credits_config;
		
		$redirect = $nuke_configs['nukeurl']."index.php?modname=$module_name&op=credit_response&tid=$tid&credit_gateway=".$this->gateway_name."&csrf_token="._PN_CSRF_TOKEN."";
		
		$employer_id = $db->table(RESERVES_TABLE)
						->where('order_id', $form_data['order_id'])
						->first()['employer'];
						
		$employer_idpay = (isset($this->pn_reserve_configs['employers'][$employer_id]['idpay']) && $this->pn_reserve_configs['employers'][$employer_id]['idpay'] != '') ? $this->pn_reserve_configs['employers'][$employer_id]['idpay']:$pn_credits_config['gateways'][$this->gateway_name]['apikey'];
		
		$params = array(
		  'order_id' => $form_data['order_id'],
		  'amount' => $form_data['amount'],
		  'name' => $form_data['name'],
		  'phone' => $form_data['phone'],
		  'mail' => $form_data['mail'],
		  'desc' => $form_data['description'],
		  'callback' => $redirect,
		  'reseller' => null,
		);	
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->request_address);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  'Content-Type: application/json',
		  'X-API-KEY: '.$employer_idpay.'',
		));

		$this->response['error_code'] = 0;
		$this->response['error_message'] = "خطای نامشخص";
		$result      = curl_exec( $ch );
		$result      = json_decode( $result );
		$http_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close($ch);
		
		if ( $http_status != 201 || empty( $result ) || empty( $result->id ) || empty( $result->link ) )
		{
			$this->get_error($result, $http_status);
		}
		else
		{
			$pn_Sessions->set("idpay_".$form_data['factor_number']."", $result->id);
			die("<html><head><meta charset='utf-8' /></head><body>
			<div style=\"text-align: center; padding: 20px; direction: rtl; background-color: #faf4db; font-family: Tahoma\">
				"._PLEASE_WAIT."
			</div>
			<meta http-equiv=\"refresh\" content=\"0;url=".$result->link."\" />
			</body></html>");
			
		}
		
		return $this->response;
	}

	function response($tid, $factor_number)
	{
		global $db, $status, $track_id, $id, $order_id, $amount, $card_no, $hashed_card_no, $date, $pn_Sessions, $module_name, $nuke_configs, $pn_credits_config;
		
		$employer_id = $db->table(RESERVES_TABLE)
						->where('order_id', $order_id)
						->first()['employer'];
		$employer_idpay = (isset($this->pn_reserve_configs['employers'][$employer_id]['idpay']) && $this->pn_reserve_configs['employers'][$employer_id]['idpay'] != '') ? $this->pn_reserve_configs['employers'][$employer_id]['idpay']:$pn_credits_config['gateways'][$this->gateway_name]['apikey'];

		$site_id = $pn_Sessions->get("idpay_".$order_id."", false);
		$this->response['result']			= false;
		$this->response['error_code'] = 0;
		$this->response['error_message'] = "خطای نامشخص";
		
		if(isset($site_id) && $site_id != '')
		{
			$params = array(
			  'id' => $site_id,
			  'order_id' => $order_id,
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->verify_address);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			  'Content-Type: application/json',
			  'X-API-KEY: '.$employer_idpay.'',
			));

			$result      = curl_exec( $ch );
			$result      = json_decode( $result );
			$http_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			curl_close($ch);
			
			if ( $http_status != 200)
			{
				$this->get_error($result, $http_status);
				$this->response['result']			= false;
			}
			else
			{
				$this->response['gateway']			= $this->gateway_name;
				$this->response['gateway_title']	= $this->gateway_title;
				$this->response['status']			= ((isset($this->transaction_result[$result->status])) ? $this->transaction_result[$result->status]:"نامشخص");
				$this->response['track_id']			= $result->track_id;
				$this->response['order_id']			= $result->order_id;
				$this->response['id']				= $result->id;
				$this->response['date']				= $result->date;
				$this->response['payment']			= array(
					"track_id" => $result->payment->track_id,
					"amount" => $result->payment->amount,
					"card_no" => $result->payment->card_no,
					"hashed_card_no" => $result->payment->hashed_card_no,
					"date" => $result->payment->date,
				);
				$this->response['verify']			= array(
					"date" => $result->verify->date,
				);
				$this->response['result']			= true;
			}
		}
		
		return $this->response;
	}
	
	function payment_inquiry($order_data)
	{
		global $pn_Sessions, $module_name, $nuke_configs, $pn_credits_config;

		$this->response['result']			= false;
		$this->response['error_code'] = 0;
		$this->response['error_message'] = "خطای نامشخص";
		
		if(isset($order_id['id']) && $order_id['id'] != '')
		{
			$params = array(
			  'id' => $order_id['id'],
			  'order_id' => $order_id['order_id'],
			);
		
			$employer_id = $db->table(RESERVES_TABLE)
							->where('order_id', $order_id['order_id'])
							->first()['employer'];
			$employer_idpay = (isset($this->pn_reserve_configs['employers'][$employer_id]['idpay']) && $this->pn_reserve_configs['employers'][$employer_id]['idpay'] != '') ? $this->pn_reserve_configs['employers'][$employer_id]['idpay']:$pn_credits_config['gateways'][$this->gateway_name]['apikey'];

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->inquiry_address);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			  'Content-Type: application/json',
			  'X-API-KEY: '.$employer_idpay.'',
			));

			$result      = curl_exec( $ch );
			$result      = json_decode( $result );
			$http_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			curl_close($ch);
			
			if ( $http_status != 200)
			{
				$this->get_error($result, $http_status);
				$this->response['result']			= false;
			}
			else
			{
				$this->response['gateway']			= $this->gateway_name;
				$this->response['gateway_title']	= $this->gateway_title;
				$this->response['wage']				= array(
					"by" => $result->wage->by,
					"type" => $result->wage->type,
					"amount" => $result->wage->amount,
				);
				$this->response['status']			= ((isset($this->transaction_result[$result->status])) ? $this->transaction_result[$result->status]:"نامشخص");
				$this->response['payer']			= array(
					"name" => $result->payer->name,
					"phone" => $result->payer->phone,
					"mail" => $result->payer->mail,
					"desc" => $result->payer->desc,
				);
				$this->response['settlement']			= array(
					"track_id" => $result->settlement->track_id,
					"amount" => $result->settlement->amount,
					"date" => $result->settlement->date,
				);
				$this->response['result']			= true;
			}
		}
		
		return $this->response;
	}
	
	function parse_gateway_infos($data)
	{
		global $nuke_configs;
		
		$data = phpnuke_unserialize($data);
		
		foreach($data as $item => $value)
		{
			if(in_array($item, array("payment","verify"))) continue;
			if($item == 'id' && !is_admin()) continue;
			$item_title = (defined("_CREDITS_DATA_".strtoupper($item)) ? constant("_CREDITS_DATA_".strtoupper($item)):$item);
			$this->response = array($item_title, $value);
		}
		
		return $this->response;
	}

	function set_configs($gateways_configs){
	
		$checke1 = (isset($gateways_configs['gateways'][$this->gateway_name]['status']) && $gateways_configs['gateways'][$this->gateway_name]['status'] == 1) ? "checked":"";
		$checke2 = (isset($gateways_configs['gateways'][$this->gateway_name]['status']) && $gateways_configs['gateways'][$this->gateway_name]['status'] == 0) ? "checked":"";
		$configs = "
				<tr>
					<td align=\"center\" colspan=\"2\"><b>".$this->gateway_title."</b></td>
				</tr>
				<tr>
					<td align=\"center\">"._ACTIVE."</td>
					<td align=\"center\">
						<input type=\"radio\" class=\"styled\" name=\"config_fields[pn_credits][gateways][".$this->gateway_name."][status]\" value=\"1\" data-label=\""._YES."\" $checke1 /> &nbsp; 
						<input type=\"radio\" class=\"styled\" name=\"config_fields[pn_credits][gateways][".$this->gateway_name."][status]\" value=\"0\" data-label=\""._NO."\" $checke2 />
					</td>
				</tr>
				<tr>
					<td align=\"center\">کد API</td>
					<td align=\"center\"><input type=\"text\" style=\"direction:ltr;\" size=\"30\" name=\"config_fields[pn_credits][gateways][".$this->gateway_name."][apikey]\" value=\"".((isset($gateways_configs['gateways'][$this->gateway_name]['apikey'])) ? $gateways_configs['gateways'][$this->gateway_name]['apikey']:"")."\" class=\"inp-form\" /></td>
				</tr>";
		return $configs;
	}
}


?>