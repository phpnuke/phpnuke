<?php

class mellat_gateway{
	
	var $gateway_name			= "mellat";
	var $gateway_title			= "درگاه پرداخت الکترونیک ملت";
	var $gateway_icon			= "";
	var $gwebservice			= "https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl";
	var $namespace				= "http://interfaces.core.sw.bps.com/";
	var $response					= array();
	
    private $errorMessages = array(
        0 => 'تراکنش با موفقیت انجام شد',
        11 => 'شماره کارت نا معتبر است',
        12 => 'موجودی کافی نیست',
        13 => 'رمز نادرست است',
        14 => 'تعداد دفعات وارد کردن رمز بیش از حد مجاز است',
        15 => 'کارت نا معتبر است',
        16 => 'دفعات برداشت وجه بیش از حد مجاز است',
        17 => 'کاربر از انجام تراکنش منصرف شده',
        18 => 'تاریخ انقضای کارت گذشته',
        19 => 'مبلغ برداشت وجه بیش از حد مجاز است',
        111 => 'صادر کننده کارت نا معتبر است',
        112 => 'خطای سوییچ صادر کننده کارت',
        113 => 'پاسخی از صادر کننده کارت دریافت نشد',
        114 => 'دارنده کارت مجاز به انجام این تراکنش نیست',
        21 => 'پذیرنده نا معتبر است',
        23 => 'خطای امنیتی رخ داده است',
        24 => 'اطلاعات کاربری پذیرنده نا معتبر است',
        25 => 'مبلغ نا معتبر است',
        31 => 'پاسخ نا معتبر است',
        32 => 'فرمت اطلاعات وارد شده صحیح نمی باشد',
        33 => 'حساب نا معتبر است',
        34 => 'خطای سیستمی',
        35 => 'تاریخ نا معتبر است',
        41 => 'شماره درخواست تکراری است',
        42 => 'تراکنش Sale یافت نشد',
        43 => 'قبلا درخواست Verify داده شده است',
        44 => 'درخواست Verify یافت نشد',
        45 => 'تراکنش Settle شده است',
        46 => 'تراکنش Settle نشده است',
        47 => 'تراکنش Settle یافت نشد',
        48 => 'تراکنش Reverse شده است',
        49 => 'تراکنش ReFund یافت نشد',
        412 => 'شناسه قبض نادرست است',
        413 => 'شناسه پرداخت نادرست است',
        414 => 'سازمان صادر کننده قبض نا معتبر است',
        415 => 'زمان جلسه کاری به پایان رسیده',
        416 => 'خطا در ثبت اطلاعات',
        417 => 'شناسه پرداخت کننده نا معتبر است',
        418 => 'اشکال در تعریف اطلاعات مشتری',
        419 => 'تعداد دفعات ورود اطلاعات از حد مجاز گذشته اس',
        421 => 'IP نا معتبر است.',
        51 => 'تراکنش تکراری است',
        54 => 'تراکنش مرجع موجود نیست',
        55 => 'تراکنش نا معتبر است',
        61 => 'خطا در واریز',
    );
	
	function __construct(){
		global $module_name;

		define("_CREDITS_DATA_GATEWAY", "درگاه");
		define("_CREDITS_DATA_SALEORDERID", "شماره درخواست خريد");
		define("_CREDITS_DATA_SALEREFERENCEID", "كد مرجع تراكنش خريد");
	
		if(file_exists("modules/$module_name/includes/mellat.gif"))
			$this->gateway_icon = "modules/$module_name/includes/mellat.gif";
		
		return true;
	}
	
	function create_form($tid, $form_data){
		global $db, $module_name, $nuke_configs, $pn_credits_config;
		
		$redirect = $nuke_configs['nukeurl']."index.php?modname=$module_name&op=credit_response&tid=$tid&credit_gateway=".$this->gateway_name."&csrf_token="._PN_CSRF_TOKEN."";
		
		include_once(INCLUDE_PATH."/nusoap.php");
		$client = new nusoap_client($this->gwebservice);
		
		$parameters = array(
			'terminalId' => $pn_credits_config['gateways'][$this->gateway_name]['terminalId'],
			'userName' => "".$pn_credits_config['gateways'][$this->gateway_name]['userName']."",
			'userPassword' => "".$pn_credits_config['gateways'][$this->gateway_name]['userPassword']."",
			'orderId' => "".$form_data['factor_number']."",
			'amount' => $form_data['amount'],
			'localDate' => "".date("Y").date("m").date("d")."",
			'localTime' => "".date("H").date("i").date("s")."",
			'additionalData' => "".(isset($form_data['descriptions']) ? $form_data['descriptions']:"")."",
			'callBackUrl' => "$redirect",
			'payerId' => "0"
		);		
		
		$result = $client->call('bpPayRequest', $parameters, $this->namespace);

		$result_arr = explode(",", $result);

		if(isset($result_arr[0]) && $result_arr[0] == "0" && isset($result_arr[1]))
		{
			die("<html><head><meta charset='utf-8' /></head><body>
			<div style=\"text-align: center; padding: 20px; direction: rtl; background-color: #faf4db; font-family: 'BNazanin', Tahoma\">
				"._PLEASE_WAIT."
			</div>
			<form name='bankForm' action='https://bpm.shaparak.ir/pgwchannel/startpay.mellat' method='POST'>
				<input type='hidden'  name='RefId' value='".$result_arr[1]."'>
			</form>
			<script language='javascript'>document.bankForm.submit();</script>
			</body></html>");
		}
		
		if(isset($result_arr[0]))
		{
			$this->response['error_code'] = $result_arr[0];
			$this->response['error_message'] = (isset($this->errorMessages[$result_arr[0]])) ? $this->errorMessages[$result_arr[0]]:sprintf(_CREDITS_NO_ERROR_MESSAGED, $result_arr[0]);
		}
		
		return $this->response;
	}

	function response($tid, $factor_number){
		global $RefId, $ResCode, $SaleOrderId, $SaleReferenceId, $CardHolderInfo, $module_name, $nuke_configs, $pn_credits_config;

		$result = (-1);
		
		if(!isset($RefId) && !isset($ResCode) && !isset($SaleOrderId) && !isset($SaleReferenceId))
        {

			$this->response['error_code'] = 1000;
			$this->response['error_message'] = _CREDITS_BAD_PARAMS;
            return $this->response;
        }
		$parameters = array(
			'terminalId' => $pn_credits_config['gateways'][$this->gateway_name]['terminalId'],
			'userName' => "".$pn_credits_config['gateways'][$this->gateway_name]['userName']."",
			'userPassword' => "".$pn_credits_config['gateways'][$this->gateway_name]['userPassword']."",
			'orderId' => $factor_number,
			'saleOrderId' => $SaleOrderId,
			'saleReferenceId' => $SaleReferenceId
		);
		
		if($ResCode == 0)
		{
			try{
				include_once(INCLUDE_PATH."/nusoap.php");
				$client = new nusoap_client($this->gwebservice);
				$error = $client->getError();
				if($error)
				{
					$this->response['error_code'] = 1001;
					$this->response['error_message'] = _CREDITS_ERROR_IN_APPROVE_TRANSACTION;
				}
			}catch(Exception $e){
				$this->response['error_code'] = 1002;
				$this->response['error_message'] = _CREDITS_ERROR_IN_APPROVE_TRANSACTION;
			}
			
			if(empty($this->response))
			{
				// Call the SOAP method			
				$result = $client->call('bpVerifyRequest', $parameters, $this->namespace);
				$error = $client->getError();
				if($error)
				{
					$this->response['error_code'] = 1003;
					$this->response['error_message'] = _CREDITS_ERROR_IN_APPROVE_TRANSACTION;
				}
				
				unset($result);
				$result = $client->call('bpSettleRequest',$parameters,$this->namespace);
				$error = $client->getError();
				if($error){
					$this->response['error_code'] = 1004;
					$this->response['error_message'] = _CREDITS_ERROR_IN_TRANSACTION_SETTEL;
				}
				if($result != 0 && $result != 45){
					$this->response['error_code'] = 1004;
					$this->response['error_message'] = _CREDITS_ERROR_IN_TRANSACTION_SETTEL;
				}
			}
		}
		else
		{
			$this->response['error_code'] = $ResCode;
			$this->response['error_message'] = $this->errorMessages[$ResCode];
		}
		
		$this->response['gateway']			= $this->gateway_name;
		$this->response['gateway_title']	= $this->gateway_title;
		$this->response['RefId']			= $RefId;
		$this->response['ResCode']			= $ResCode;
		$this->response['saleOrderId']		= $SaleOrderId;
		$this->response['SaleReferenceId']	= $SaleReferenceId;
		$this->response['CardHolderInfo']	= $CardHolderInfo;
		$this->response['factor_number']	= $factor_number;
		
		$this->response['result']			= ($ResCode == 0 && $result == 0) ? true:false;
		
		return $this->response;
	}
	
	function parse_gateway_infos($data)
	{
		global $nuke_configs;
		
		$data = phpnuke_unserialize($data);
		
		foreach($data as $item => $value)
		{
			$item_title = constant("_CREDITS_DATA_".strtoupper($item));
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
					<td align=\"center\">"._CREDITS_TERMINAL_ID."</td>
					<td align=\"center\"><input type=\"text\" style=\"direction:ltr;\" size=\"30\" name=\"config_fields[pn_credits][gateways][".$this->gateway_name."][terminalId]\" value=\"".((isset($gateways_configs['gateways'][$this->gateway_name]['terminalId'])) ? $gateways_configs['gateways'][$this->gateway_name]['terminalId']:"")."\" class=\"inp-form\" /></td>
				</tr>
				<tr>
					<td align=\"center\">"._USERNAME."</td>
					<td align=\"center\"><input type=\"text\" style=\"direction:ltr;\" size=\"30\" name=\"config_fields[pn_credits][gateways][".$this->gateway_name."][userName]\" value=\"".((isset($gateways_configs['gateways'][$this->gateway_name]['userName'])) ? $gateways_configs['gateways'][$this->gateway_name]['userName']:"")."\" class=\"inp-form\" /></td>
				</tr>
				<tr>
					<td align=\"center\">"._PASSWORD."</td>
					<td align=\"center\"><input type=\"text\" style=\"direction:ltr;\" size=\"30\" name=\"config_fields[pn_credits][gateways][".$this->gateway_name."][userPassword]\" value=\"".((isset($gateways_configs['gateways'][$this->gateway_name]['userPassword'])) ? $gateways_configs['gateways'][$this->gateway_name]['userPassword']:"")."\" class=\"inp-form\" /></td>
				</tr>";
		return $configs;
	}
}


?>