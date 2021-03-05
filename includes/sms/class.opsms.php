<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

class pn_opsms
{
	public	$msg;
	public	$flash;
	public  $Ids = array();
	public  $Status = array();
	private $_to = array();
	private $send_url			= "http://p2.opsms.ir/API/SendSms.ashx";
	private $get_credit_url		= "http://p2.opsms.ir/API/GetCredit.ashx";
	private $get_delivery_url	= "http://p2.opsms.ir/API/GetDelivery.ashx?recId=";
	private $send_errors = array(
		'0' => "نام کاربری یا رمز عبور صحیح نمی باشد",
		'1' => "اعتبار شم برای ارسال کافی نیست",
		'2' => "اکانت شما دارای محدودیت ارسال می باشد",
		'3' => "پارامتر نام کاربری تعیین نشده است",
		'4' => "پارامتر رمز عبور تعیین نشده است",
		'5' => "پارامتر فرستنده تعریف نشده است",
		'6' => "پارامتر گیرنده تعریف نشده است",
		'7' => "پارامتر متن پیامک تعریف نشده است",
		'8' => "پارامتر فلش معتبر نمی باشد",
		'9' => "شماره فرستنده معتبر نمی باشد",
		'10' => "پیامک ارسال نشد، سیستم موقتاً قطع می باشد.",
		'11' => "تعداد مخاطب از 80 بیشتر است",
	);
	private $delivery_msg = array(
		'0' => "ارسال شده به مخابرات",
		'1' => "رسیده به گوشی",
		'2' => "نرسیده به گوشی",
		'8' => "رسیده به مخارات",
		'16' => "نرسیده به مخابرات",
		'100' => "نامشخص",
		'101' => "پارامتر recid تعیین نشده است",
		'102' => "مقدار پارامتر recid معتبر نمی باشد",
	);
	private $errors;
	private $_user;            //opsms account username
	private $_pass;            //opsms account password
	private $_from;            //opsms account number
	
	
	function __construct($user,	$pass, $from, $to = array(), $message = '', $flash = 0)
	{
		if($user == '' || $pass == '' || $from == '')
			return false;
		$this->msg = $message;       
		$this->_user = $user;
		$this->_pass = $pass;
		$this->_from = $from;
		$this->_flash = $flash;
		
		if(isset($to) && !empty($to))
		{
			if(!is_array($to))
				$to = explode(",", str_replace(array("\n","\r"," "), "", $to));
				
			$this->_to = $to;
		}
	}
	
	function buildQs($fields)
	{
		$Qs = array();
		foreach($fields as $key => $value)
		{
			$Qs[] = "$key=$value";
		}
		
		$Qs = "?".implode("&", $Qs);
		
		return $Qs;
	}

	public function AddRecipient($to)	//Ad a resipient number to _to array
	{
		if(is_array($to))
			$this->_to = array_merge($this->_to, $to);
		else
			$this->_to[] = $to;
	}
	
	public function GetCredit()			// Return account credit
	{
		$fields= array(
			'username' => $this->_user,
			'password' => $this->_pass
		);
		
		$credit = phpnuke_get_url_contents($this->get_credit_url.$this->buildQs($fields), true);
		return number_format(str_replace(",", "", round($credit)), 0);
	}
	
	public function Send()				//Send msg text to _to numbers and stoe message identifiers in Ids array
	{
		$fields =array(
			'username'	=> $this->_user,
			'password'	=> $this->_pass,
			'from'		=> $this->_from,
			'text'		=> urlencode($this->msg),
			'flash'		=> $this->_flash,
			'To'		=> implode(",", $this->_to)
		);
				
		$ids = phpnuke_get_url_contents($this->send_url.$this->buildQs($fields), true);
		
		$this->Ids = explode(",", $ids);
		
		foreach($this->Ids as $key => $id)
		{
			if(in_array($id, $this->send_errors))
				$this->Ids[$key] = $this->send_errors[$id];
		}
	}
	
	public function GetDelivery()		//Get delivery of Ids array and store in Statue array
	{
		foreach($this->Ids as $Id)
		{
			$fields =array(
				'recId'	=> $Id
			);
			
			$this->Status[] = phpnuke_get_url_contents($this->get_delivery_url.$this->buildQs($fields), true);
		}
		foreach($this->Status as $key => $Status)
		{
			if(in_array($Status, $this->delivery_msg))
				$this->Status[$key] = $this->delivery_msg[$Status];
		}
	}

}

?>