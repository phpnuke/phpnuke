<?php

class zarinpal_gateway{
	
	var $gateway_name			= "zarinpal";
	var $gateway_title			= "درگاه پرداخت zarinpal";
	var $gateway_icon			= "";
	var $request_address		= "https://api.zarinpal.com/pg/v4/payment/request.json";
	var $verify_address			= "https://api.zarinpal.com/pg/v4/payment/verify.json";
	var $response					= array();
	
	private $request_response = array(
		"-9" => "خطای اعتبار سنجی",
		"-10" => "ای پی و يا مرچنت كد پذيرنده صحيح نيست",
		"-11" => "مرچنت کد فعال نیست لطفا با تیم پشتیبانی ما تماس بگیرید",
		"-12" => "تلاش بیش از حد در یک بازه زمانی کوتاه.",
		"-15" => "ترمینال شما به حالت تعلیق در آمده با تیم پشتیبانی تماس بگیرید",
		"-16" => "سطح تاييد پذيرنده پايين تر از سطح نقره اي است.",
		"100" => "عملیات موفق",
		"-30" => "اجازه دسترسی به تسویه اشتراکی شناور ندارید",
		"-31" => "حساب بانکی تسویه را به پنل اضافه کنید مقادیر وارد شده واسه تسهیم درست نیست",
		"-32" => "Wages is not valid, Total wages(floating) has been overload max amount. 	",
		"-33" => "درصد های وارد شده درست نیست",
		"-34" => "مبلغ از کل تراکنش بیشتر است",
		"-35" => "تعداد افراد دریافت کننده تسهیم بیش از حد مجاز است",
		"-40" => "Invalid extra params, expire_in is not valid.",
		"-50" => "مبلغ پرداخت شده با مقدار مبلغ در وریفای متفاوت است",
		"-51" => "پرداخت ناموفق",
		"-52" => "خطای غیر منتظره با پشتیبانی تماس بگیرید",
		"-53" => "اتوریتی برای این مرچنت کد نیست",
		"-54" => "اتوریتی نامعتبر است",
		"101" => "تراکنش وریفای شده",
		"-1" => "اطلاعات ارسال شده ناقص است",
		"-2" => "IP و يا مرچنت كد پذيرنده صحيح نيست.",
		"-3" => "با توجه به محدوديت هاي شاپرك امكان پرداخت با رقم درخواست شده ميسر نمي باشد.",
		"-4" => "سطح تاييد پذيرنده پايين تر از سطح نقره اي است.",
		"-11" => "درخواست مورد نظر يافت نشد.",
		"-12" => "امكان ويرايش درخواست ميسر نمي باشد.",
		"-21" => "هيچ نوع عمليات مالي براي اين تراكنش يافت نشد.",
		"-22" => "تراكنش نا موفق ميباشد.",
		"-33" => "رقم تراكنش با رقم پرداخت شده مطابقت ندارد.",
		"-34" => "سقف تقسيم تراكنش از لحاظ تعداد يا رقم عبور نموده است",
		"-40" => "اجازه دسترسي به متد مربوطه وجود ندارد.",
		"-41" => "اطلاعات ارسال شده مربوط به AdditionalData غيرمعتبر ميباشد.",
		"-42" => "مدت زمان معتبر طول عمر شناسه پرداخت بايد بين 30 دقيه تا 45 روز مي باشد.",
		"-54" => "درخواست مورد نظر آرشيو شده است.",
		"100" => "عمليات با موفقيت انجام گرديده است.",
		"101" => "عمليات پرداخت موفق بوده و قبلا PaymentVerification تراكنش انجام شده است",
		
		
		
	);
	
	function __construct(){
		global $module_name;
	
		if(file_exists("modules/$module_name/includes/zarinpal.gif"))
			$this->gateway_icon = "modules/$module_name/includes/zarinpal.gif";
		
		return true;
	}
	
	function get_error($err)
	{
		$this->response['error_code'] = $err;
		$this->response['error_message'] = (isset($this->request_response[$err])) ? $this->request_response[$err]:"خطا";
	}

	function create_form($tid, $form_data)
	{
		global $db, $module_name, $pn_Sessions, $nuke_configs, $pn_credits_config;
		
		$redirect = $nuke_configs['nukeurl']."index.php?modname=$module_name&op=credit_response&tid=$tid&amount=".$form_data['amount']."&credit_gateway=".$this->gateway_name."&csrf_token="._PN_CSRF_TOKEN."&order_id=".$form_data['factor_number']."";

		$meta_data = array();
		if(isset($form_data['mail']) && $form_data['mail'] != '')
			$meta_data['email'] = $form_data['mail'];
		if(isset($form_data['phone']) && $form_data['phone'] != '')
			$meta_data['mobile'] = $form_data['phone'];

		$data = array("merchant_id" => $pn_credits_config['gateways'][$this->gateway_name]['merchant_id'],
			"amount" => $form_data['amount'],
			"callback_url" => $redirect,
			"description" => (isset($form_data['description']) && $form_data['description'] != '') ? $form_data['description']:"pay factor number: ".$form_data['factor_number'],
			"metadata" => $meta_data,
		);
		
		$jsonData = json_encode($data);
		$ch = curl_init($this->request_address);
		curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($jsonData)
		));
		
		$result = curl_exec($ch);
		$err = curl_error($ch);
		$result = json_decode($result, true, JSON_PRETTY_PRINT);
		
		curl_close($ch);

		$this->response['error_code'] = 0;
		$this->response['error_message'] = "خطای نامشخص";
		
		if ($err) {
			$this->get_error($err);
		} else {
			if (empty($result['errors'])) {
				if ($result['data']['code'] == 100) {
					$pn_Sessions->set("zarinpal_".$form_data['factor_number']."", $result->id);
					header('Location: https://www.zarinpal.com/pg/StartPay/' . $result['data']["authority"]);
				}
			} else {
				$this->response['error_code'] = $result['errors']['code'];
				$this->response['error_message'] = (isset($this->request_response[$result['errors']['code']])) ? $this->request_response[$result['errors']['code']]:$result['errors']['message'];
			}
		}
		
		return $this->response;
	}

	function response($tid, $factor_number)
	{
		global $Authority, $amount, $pn_Sessions, $module_name, $nuke_configs, $pn_credits_config;

		$site_id = $pn_Sessions->get("zarinpal_".$factor_number."", false);
		$this->response['result']			= false;
		$this->response['error_code'] = 0;
		$this->response['error_message'] = "خطای نامشخص";
		
		$data = array("merchant_id" => $pn_credits_config['gateways'][$this->gateway_name]['merchant_id'], "authority" => $Authority, "amount" => $amount);
		
		$jsonData = json_encode($data);
		$ch = curl_init($this->verify_address);
		curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($jsonData)
		));

		$result = curl_exec($ch);
		$err = curl_error($ch);
		curl_close($ch);
		$result = json_decode($result, true);
		if ($err) {
			$this->get_error($err);
			$this->response['result']			= false;
		} else {
			if ($result['data']['code'] == 100) {
			
				$this->response['gateway']			= $this->gateway_name;
				$this->response['gateway_title']	= $this->gateway_title;
				$this->response['status']			= "موفق";
				$this->response['ref_id']			= $result['data']['ref_id'];
				$this->response['id']				= $site_id;
				$this->response['payment']			= $result['data'];
				$this->response['result']			= true;
			} else {
				$this->response['error_code'] = $result['errors']['code'];
				$this->response['error_message'] = (isset($this->request_response[$result['errors']['code']])) ? $this->request_response[$result['errors']['code']]:$result['errors']['message'];
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
			if($item == 'id' && !is_admin()) continue;
			$item_title = (defined("_CREDITS_DATA_".strtoupper($item)) ? constant("_CREDITS_DATA_".strtoupper($item)):$item);
			$this->response = array($item_title, $value);
		}
		
		return $this->response;
	}

	function set_configs($gateways_configs){
	
		$checke1 = (isset($gateways_configs['gateways'][$this->gateway_name]['status']) && $gateways_configs['gateways'][$this->gateway_name]['status'] == 1) ? "checked":"";
		$checke2 = (!isset($gateways_configs['gateways'][$this->gateway_name]['status']) || (isset($gateways_configs['gateways'][$this->gateway_name]['status']) && $gateways_configs['gateways'][$this->gateway_name]['status'] == 0)) ? "checked":"";
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
					<td align=\"center\"><input type=\"text\" style=\"direction:ltr;\" size=\"30\" name=\"config_fields[pn_credits][gateways][".$this->gateway_name."][merchant_id]\" value=\"".((isset($gateways_configs['gateways'][$this->gateway_name]['merchant_id'])) ? $gateways_configs['gateways'][$this->gateway_name]['merchant_id']:"")."\" class=\"inp-form\" /></td>
				</tr>";
		return $configs;
	}
}

?>