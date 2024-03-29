<?php

$contents = '';

if(isset($pn_credits_config['credits_direct_msg']) && $pn_credits_config['credits_direct_msg'] != '')
{
	$contents .= OpenTable($form_title);
	$contents .= $pn_credits_config['credits_direct_msg'];
	$contents .= CloseTable();
}

$contents .= OpenTable(_CREDITS_ADMIN.' - '.$form_title);

$gateways_list = (isset($pn_credits_config['gateways']) && !empty($pn_credits_config['gateways'])) ? credit_get_gateways_list(true):"";

$check1 = ($gateways_list != '') ? "checked":"";
$check2 = ($check1 != 'checked') ? "checked":"";

$contents .= "
<form action=\"".LinkToGT("index.php?modname=Credits")."\" id=\"credit_form\" method=\"post\" class=\"form-horizontal\" style=\"padding:10px;\" enctype=\"multipart/form-data\">
	<div class=\"form-group\">
		<label class=\"col-sm-2 control-label\">"._CREDITS_PAY_METHOD."</label>
		<div class=\"col-sm-10\">";
			if($gateways_list != '')
			{
				$contents .= "
				<div class=\"radio-inline\">
					<label>
						<input type=\"radio\" name=\"credit_method\" id=\"online_credit\" value=\"1\" style=\"margin-top:2px;\" $check1 />
						"._CREDITS_PAY_ONLINE."
					</label>
				</div>";
			}
			$contents .= "
			<div class=\"radio-inline\">
				<label>
					<input type=\"radio\" name=\"credit_method\" id=\"offline_credit\" value=\"2\" style=\"margin-top:2px;\" $check2 />
					"._CREDITS_PAY_OFFLINE."
				</label>
			</div>";
			
			if(isset($order_data) && !empty($order_data))
			{
				if($user_credits_allowed-$order_data['amount'] >= 0)
				{
					$contents .= "
						<div class=\"radio-inline\">
							<label>
								<input type=\"radio\" name=\"credit_method\" id=\"user_credit_remain\" value=\"3\" style=\"margin-top:2px;\" />
								"._CREDITS_PAY_BY_CREDIT."
							</label>
						</div>";
				}
			}
		
			$contents .= "
		</div>
	</div>";
	if(isset($order_data) && !empty($order_data))
	{
		foreach($order_data as $order_key => $order_value)
		{
			$contents .= "<input type=\"hidden\" name=\"order_data[$order_key]\" value=\"$order_value\" />";
		}
		$contents .= "
		<input type=\"hidden\" name=\"order_data_json\" value=\"".str_replace('"', "'", json_encode($order_data))."\" />
		<div class=\"form-group\">
			<label class=\"col-sm-2 control-label\">"._CREDITS_AMOUNT."</label>
			<div class=\"col-sm-10 form-control-static\">
				$amount_in_ex_rate ".number_format($amount,0)." "._RIAL."
			</div>
		</div>
		<div class=\"form-group\">
			<label class=\"col-sm-2 control-label\">"._CREDITS_ACCOUNT_REMAIN."</label>
			<div class=\"col-sm-10 form-control-static\">
				".number_format($userinfo['user_credit'],0)." "._RIAL."
			</div>
		</div>
		<div class=\"form-group\">
			<label class=\"col-sm-2 control-label\">"._CREDITS_ORDER_TITLE."</label>
			<div class=\"col-sm-10 form-control-static\">
				<a href=\"".$order_data['link']."\">".$order_data['title']."</a>
			</div>
		</div>
		<div class=\"form-group\">
			<label class=\"col-sm-2 control-label\">"._CREDITS_ORDER_ID."</label>
			<div class=\"col-sm-10 form-control-static\">
				".$order_data['id']."
			</div>
		</div>";
	}
	$contents .= "
	<div class=\"credit_form\">
		<div id=\"online_form\" style=\"display:".(($gateways_list == '') ? 'none':'block').";\">";
			if($gateways_list != '')
			{
			$contents .= "<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">"._CREDIT_DETAILS_GATEWAY."</label>
				<div class=\"col-sm-10\">
					<select class=\"selectpicker\" name=\"credit_gateway\" id=\"credit_gateway\" data-validation=\"required\">
						".$gateways_list."
					</select>
				</div>
			</div>";
			}
			if(!isset($order_data) || empty($order_data))
			{
			$contents .= "<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">"._CREDITS_AMOUNT."</label>
				<div class=\"col-sm-10\">
					<input type=\"text\" IsCurrencyElseNumber=\"online-faShow\" id=\"online_creditamount\" name=\"online_credit[amount]\" class=\"form-control\" placeholder=\""._CREDITS_AMOUNT_IN_RIAL."\" data-validation=\"required number\" data-validation-ignore=\",\" /> <span id=\"online-faShow\"></span>
				</div>
			</div>";
			}
			$contents .= "
			<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">"._TITLE."</label>
				<div class=\"col-sm-10\">
					<input class=\"form-control\" id=\"online_credit_title\" name=\"online_credit[title]\" type=\"text\" placeholder=\""._OPTIONAL."\" value=\"".((isset($order_data['title'])) ? $order_data['title']:"")."\">
				</div>
			</div>
			<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">"._DESCRIPTIONS."</label>
				<div class=\"col-sm-10\">
					<textarea class=\"form-control\" id=\"online_credit_desc\" name=\"online_credit[desc]\" rows=\"7\" placeholder=\""._DESCRIPTIONS."\">".((isset($order_data['desc'])) ? $order_data['desc']:"")."</textarea>
				</div>
			</div>
		</div>
		
		<div id=\"offline_form\" style=\"display:".(($gateways_list == '') ? 'block':'none').";\">";
			if(!isset($order_data) || empty($order_data))
			{
			$contents .= "<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">"._CREDITS_AMOUNT."</label>
				<div class=\"col-sm-10\">
					<input type=\"text\" IsCurrencyElseNumber=\"offline-faShow\" id=\"offline_credit_amount\" name=\"offline_credit[amount]\" class=\"form-control\" placeholder=\""._CREDITS_AMOUNT_IN_RIAL."\" data-validation=\"required number\" data-validation-ignore=\",\" /> <span id=\"offline-faShow\"></span>
				</div>
			</div>";
			}
			else
			{
			$contents .= "
			<div class=\"form-group\">
				<input name=\"offline_credit[amount]\" type=\"hidden\" value=\"".((isset($order_data['amount'])) ? $order_data['amount']:"")."\">
			</div>";
			}
			$contents .= "
			<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">"._CREDITS_RECEIPT_DATE."</label>
				<div class=\"col-sm-10\">
					<input type=\"text\" class=\"form-control calendar\" id=\"offline_credit_date\" name=\"offline_credit[date]\" placeholder=\"yyyy/mm/dd\" data-validation=\"required\">
				</div>
			</div>
			<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">"._CREDITS_RECEIPT_NUMBER."</label>
				<div class=\"col-sm-10\">
					<input class=\"form-control\" id=\"offline_credit_number\" name=\"offline_credit[number]\" type=\"text\" data-validation=\"required\">
				</div>
			</div>
			<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">"._PICTURE."</label>
				<div class=\"col-sm-10\">
					<div class=\"input-group\">
						<span class=\"input-group-btn\">
							<span class=\"btn btn-primary\" onclick=\"$(this).parent().find('input[type=file]').click();\">"._BROWSE."</span>
							<input name=\"offline_credit_file\" onchange=\"$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());\" style=\"display: none;\" type=\"file\" data-validation=\"required\">
						</span>
						<span class=\"form-control\" style=\"width:150px;z-index:0\"></span>
					</div>
					<br />"._CREDITS_RECEIPT_PICTURE_DESC."<br />
				</div>
			</div>
			<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">"._TITLE."</label>
				<div class=\"col-sm-10\">
					<input class=\"form-control\" id=\"offline_credit_title\" name=\"offline_credit[title]\" type=\"text\" placeholder=\""._OPTIONAL."\" value=\"".((isset($order_data['title'])) ? $order_data['title']:"")."\">
				</div>
			</div>
			<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">"._DESCRIPTIONS."</label>
				<div class=\"col-sm-10\">
					<textarea class=\"form-control\" id=\"offline_credit_desc\" name=\"offline_credit[desc]\" rows=\"7\" placeholder=\""._OPTIONAL."\">".((isset($order_data['desc'])) ? $order_data['desc']:"")."</textarea>
				</div>
			</div>
		</div>
		<div class=\"form-group\">
			<label class=\"col-sm-2\"></label>
			<div class=\"col-sm-10\">
				<input type=\"hidden\" name=\"op\" value=\"credit_create_form\" />
				<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
				<input type=\"submit\" value=\""._SEND."\" name=\"submit\" />
			</div>
		</div>
	</div>
</form>";
$contents .= CloseTable();

?>