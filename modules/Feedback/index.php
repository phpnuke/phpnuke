<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('MODULE_FILE')) {
	die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));

if(!defined("INDEX_FILE"))
	define('INDEX_FILE', is_index_file($module_name));// to define INDEX_FILE status

function feedback($submit = '', $feedback_fields = array())
{
	global $db, $nuke_configs, $userinfo, $module_name, $waiting, $PnValidator, $visitor_ip, $pn_Cookies, $hooks;
	
	$captcha_id = "_feedback";

	$real_name = isset($userinfo['name']) ? $userinfo['name']:"";
	$user_email = isset($userinfo['user_email']) ? $userinfo['user_email']:"";
	
	$feedback_configs = (isset($nuke_configs['feedbacks']) && $nuke_configs['feedbacks'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['feedbacks'])):array();
	
	$feedback_custom_fields = (isset($feedback_configs['custom_fields']) && $feedback_configs['custom_fields'] != '') ? $feedback_configs['custom_fields']:array();

	$depts_html = array();
	if(isset($feedback_configs['depts']) && is_array($feedback_configs['depts']) && !empty($feedback_configs['depts']))
		foreach($feedback_configs['depts'] as $key => $depts_data)
		{
			$depts_data['part'] = isset($depts_data['part']) ? $depts_data['part']:"";
			$depts_html[] = "<option value=\"$key\">".$depts_data['name']." ".$depts_data['part']."</options>";
		}

	$depts_html = implode("\n", $depts_html);
	
	if(isset($submit) && $submit != '' && isset($feedback_fields) && !empty($feedback_fields))
	{
		$code_accepted = false;
		if(isset($_POST['feedback_fields']['g_recaptcha_response']) && $_POST['feedback_fields']['g_recaptcha_response'] != '')
			$_POST['g-recaptcha-response'] = $_POST['feedback_fields']['g_recaptcha_response'];
		
		$feedback_fields['security_code'] = (isset($feedback_fields['security_code'])) ? $feedback_fields['security_code']:"";
		if(extension_loaded("gd") && in_array("feedback", $nuke_configs['mtsn_gfx_chk']))
			$code_accepted = code_check($feedback_fields['security_code'], $captcha_id);
		else
			$code_accepted = true;

		if($code_accepted)
		{
			$feedback_depts = (isset($feedback_configs['depts']) && !empty($feedback_configs['depts'])) ? $feedback_configs['depts']:array();
		
			$responsibility = intval($feedback_fields['feedback_dept']);
			$dname = (!empty($feedback_depts) && isset($feedback_depts[$responsibility]['name'])) ? filter($feedback_depts[$responsibility]['name'], "nohtml"):"";
			$demail = (!empty($feedback_depts) && isset($feedback_depts[$responsibility]['email'])) ? filter($feedback_depts[$responsibility]['email'], "nohtml"):"";
			$dmobile = (!empty($feedback_depts) && isset($feedback_depts[$responsibility]['mobile'])) ? $feedback_depts[$responsibility]['mobile']:"";
			$dresponsibility = (!empty($feedback_depts) && isset($feedback_depts[$responsibility]['responsibility'])) ? filter($feedback_depts[$responsibility]['responsibility'], "nohtml"):"";
			
			$PnValidator->validation_rules(array(
				'sender_name'	=> 'required',
				'sender_email'	=> 'required',
				'message'		=> 'required'
			)); 
			// Get or set the filtering rules
			$PnValidator->filter_rules(array(
				'sender_name'	=> 'sanitize_string',
				'sender_email'	=> 'sanitize_email',
				'message'		=> 'stripslashes',
			)); 

			$feedback_fields = $PnValidator->sanitize($feedback_fields, array('sender_name','sender_email'), true, true);
			$validated_data = $PnValidator->run($feedback_fields);
			
			if($validated_data !== FALSE)
			{
				$feedback_fields = $validated_data;
			}
			else
			{
				$response = json_encode(array(
					"status" => "danger",
					"message" => ""._EEROR_IN_OP." : ".$PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br />').""
				));
				die($response);
			}
			
			$email_to[] = $nuke_configs['adminmail'];
			
			if($responsibility != 0)
				$email_to[] = $demail;
			
			$subject2 = $feedback_fields['subject'];
			
			$subject = "".$nuke_configs['sitename']." - "._CONTACT_US.": $subject2\n";
			$subject = stripslashes(FixQuotes(check_html(removecrlf($subject))));
			
			$sender_name = stripslashes(FixQuotes(check_html(removecrlf($feedback_fields['sender_name']))));
			$sender_email = stripslashes(FixQuotes(check_html(removecrlf($feedback_fields['sender_email']))));
			
			$other_msg = array();
			$new_feedback_custom_fields = array();
			
			if(isset($feedback_fields['custom']) && is_array($feedback_fields['custom']) && !empty($feedback_fields['custom']) && is_array($feedback_custom_fields) && !empty($feedback_custom_fields))
			{
				$validation_rules = '';
				$filter_rules = '';
				
				foreach($feedback_fields['custom'] as $key => $val)
				{
					foreach($feedback_custom_fields as $ckey => $cval)
					{
						if($cval['name'] == $key && $cval['required'] == 1)
						{
							$validation_rules[$key] = "required";
							$sanitize_rule = ($cval['data-rule'] == 'number') ? "sanitize_numbers":"sanitize_string";
							$filter_rules[$key] = "$sanitize_rule";
							break;
						}
					}					
					
					$new_feedback_custom_fields[$key] = $val;
				}

				$PnValidator = new GUMP();
				if($validation_rules != '')
					$PnValidator->validation_rules($validation_rules);
				
				if($filter_rules != '')
					$PnValidator->filter_rules($filter_rules);

				$new_feedback_custom_fields = $PnValidator->sanitize($new_feedback_custom_fields, array_keys($new_feedback_custom_fields), true, true);
				$validated_data = $PnValidator->run($new_feedback_custom_fields);
					
				if($validated_data !== FALSE)
				{
					$new_feedback_custom_fields = $validated_data;
				}
				else
				{
					$response = json_encode(array(
						"status" => "danger",
						"message" => "<p align=\"center\">"._ERROR_IN_OP."<br /><br />".$PnValidator->get_readable_errors(true,'gump-field','gump-error-message','<br />')."</p>"
					));
					die($response);
				}			
				
				foreach($new_feedback_custom_fields as $new_key => $new_val)
				{
					$new_val = stripslashes(FixQuotes(check_html(removecrlf($new_feedback_custom_fields[$new_key]))));

					foreach($feedback_custom_fields as $key => $val)
					{
						if($val['name'] == $new_key)
						{
							$new_feedback_custom_fields[$feedback_custom_fields[$key]['title']] = $new_val;
							$other_msg[] = "".$new_feedback_custom_fields[$feedback_custom_fields[$key]['title']].": $new_val";
							break;
						}
					}
				}
			}
			
			$feedback_fields['message'] = str_replace("\n", "<br />", $feedback_fields['message']);
			
			if(!empty($other_msg))
				$other_msg = implode("\r\n\r\n", $other_msg);
			else
				$other_msg = '';
				
			$message = array(
				"template" => array("path" => "modules/$module_name/includes/email_template/", "file" => "new_pm.txt"),
				"feedback_url" => LinkToGT("index.php?modname=Feedback"), 
				"dname" => $dname." ",
				"dresponsibility" => $dresponsibility." ", 
				"sender_name" => $sender_name, 
				"sender_email" => $sender_email, 
				"message" => $feedback_fields['message'].$other_msg,
			);
			
			$waiting = intval($waiting);
			if($waiting == 0)
			{
				phpnuke_mail($email_to, $subject, $message);

				if($feedback_configs['letreceive'] == 1)
					phpnuke_mail($sender_email, $subject, $message);
				
				$new_feedback_custom_fields = (is_array($new_feedback_custom_fields) && !empty($new_feedback_custom_fields)) ? addslashes(phpnuke_serialize($new_feedback_custom_fields)):"";
				
				$db->table(FEEDBACKS_TABLE)
					->insert([
						'sender_name' => $sender_name,
						'sender_email' => $sender_email,
						'subject' => $subject2,
						'message' => addslashes($feedback_fields['message']),
						'custom_fields' => $new_feedback_custom_fields,
						'responsibility' => $responsibility,
						'added_time' => _NOWTIME,
						'ip' => $visitor_ip,
					]);
				
				// notifications
				///send message with sms to admins or members
				if(isset($nuke_configs['sms']) && $nuke_configs['sms'] == 1 && isset($feedback_configs['notify']['sms']) && $feedback_configs['notify']['sms'] == 1)
				{
					$message = @preg_replace("/<[^>]+\>/i", "", strip_tags($feedback_fields['message']));
					$sms_text = mb_substr($message,0,100);
					pn_sms('send', $dmobile, $sms_text);
				}
				///send message with sms to admins or members
				// notifications
				
				$pn_Cookies->set("waiting","1",$feedback_configs['delay']);
				$response = json_encode(array(
					"status" => "success",
					"message" => _FEEDBACK_SENT
				));
			}else{
				$response = json_encode(array(
					"status" => "danger",
					"message" => sprintf(_FEEDBACK_WAIT, number_format(($feedback_configs['delay']/60), 0))
				));
			}
		}
		else
		{
			$response = json_encode(array(
				"status" => "danger",
				"message" => _BADSECURITYCODE
			));
		}
		die($response);
	}
	
	$contents = '';
	
	if(file_exists("themes/".$nuke_configs['ThemeSel']."/feedback.php"))
		include("themes/".$nuke_configs['ThemeSel']."/feedback.php");
	else
	{
		$map_position = ($feedback_configs['map_position'] != '') ? explode(",", $feedback_configs['map_position']):array('36.28795445718431','59.61575198173523');
		
		$feedback_data['mapid'] = "phpnuke_map";
		$feedback_data['responseid'] = "feedback_response";
		$feedback_data['lat'] = $map_position[0];
		$feedback_data['lng'] = $map_position[1];
		$feedback_data['zoom'] = 6;
		$feedback_data['panControl'] = true;
		$feedback_data['zoomControl'] = true;
		$feedback_data['mapTypeControl'] = true;
		$feedback_data['scaleControl'] = true;
		$feedback_data['streetViewControl'] = true;
		$feedback_data['overviewMapControl'] = true;
		$feedback_data['rotateControl'] = true;
		$feedback_data['marker_icon'] = $nuke_configs['nukecdnurl'].'images/marker.png';
		$feedback_data['infowindow'] = _OUR_POSITION;
		$custom_field_contents = '';
		if(!empty($feedback_custom_fields))
		{
			foreach($feedback_custom_fields as $field_name => $field_data)
			{
				$field_name = $field_data['name'];
				$field_title = $field_data['title'];
				$field_description = (isset($field_data['description']) && $field_data['description'] != '') ? $field_data['description']:'';
				$field_required = (isset($field_data['required']) && $field_data['required'] == 1) ? ' required':'';
				$field_rule = (isset($field_data['required']) && $field_data['required'] == 1 && isset($field_data['data-rule']) && $field_data['data-rule']== 'number') ? ' data-rule-'.$field_data['data-rule'].'="true"':'';
				$field_msg = ($field_data['data-msg'] != '') ? ' data-msg-required="'.$field_data['data-msg'].'"':'';
				$feedback_data['custom_data'][] = $field_name;
				$custom_field_contents .="<div class=\"form-group\">
					<label class=\"control-label col-sm-2\" for=\"feedback_$field_name\">$field_title:</label>
					<div class=\"col-sm-10\">
						<input type=\"text\" class=\"form-control col-xs-8\" id=\"feedback_$field_name\" name=\"feedback_fields[custom][$field_name]\" placeholder=\"$field_description\"".$field_required."".$field_rule."".$field_msg.">
					</div>
				</div>";
			}
		}
		
		$json_feedback_data = json_encode($feedback_data);
		
		$hooks->add_filter("site_theme_headers", function ($theme_setup) use($nuke_configs, $module_name, $feedback_configs, $json_feedback_data)
		{
			$theme_setup = array_merge_recursive($theme_setup, array(
				"defer_js" => array(
					"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>",
					"<script>var feedback_data=JSON.parse('$json_feedback_data');</script>",
					"<script src=\"".$nuke_configs['nukecdnurl']."modules/$module_name/includes/feedback.js\"></script>",
					"".(($feedback_configs['map_active'] == 1) ? "<script src=\"https://maps.googleapis.com/maps/api/js?callback=phpnukeMap&key=".$feedback_configs['google_api']."\"></script>":"").""
				)
			));
			
			return $theme_setup;
		}, 10);
		
		$contents = '';
		$contents .= OpenTable($nuke_configs['sitename'].":&nbsp;"._FEEDBACKTITLE);
		$contents .= "
		<p>";
			$contents .= ($feedback_configs['description'] != '') ? "".$feedback_configs['description']."<br>":"";
			$contents .= ($feedback_configs['phone'] != '') ? "<span class=\"glyphicon glyphicon-phone-alt\"></span> "._LANDLINE_PHONE." : ".$feedback_configs['phone']."<br>":"";
			$contents .= ($feedback_configs['mobile'] != '') ? "<span class=\"glyphicon glyphicon-phone\"></span> "._MOBILE_PHONE." : ".$feedback_configs['mobile']." <br>":"";
			$contents .= ($feedback_configs['address'] != '') ? "<span class=\"glyphicon glyphicon-map-marker\"></span> "._ADDRESS." : ".$feedback_configs['address']." <br>":"";
		$contents .= "</p> 
		<div class=\"distribution\">"._FEEDBACK_HEADER_MESSAGE."</div><br>
		<form role=\"form\" class=\"form-horizontal\" id=\"feedback_form\">
			<div class=\"form-group\">
				<label class=\"control-label col-sm-2\" for=\"feedback_name\">"._YOUR_NAME." :</label>
				<div class=\"col-sm-10\"> 
					<input type=\"text\" class=\"form-control col-xs-8\" id=\"feedback_name\" name=\"feedback_fields[name]\" value=\"$real_name\" placeholder=\""._ENTER_NAME."\" minlength=\"3\" data-validation=\"required\" data-validation-error-msg=\""._ENTER_NAMEFAMILY."\">
				</div>
			</div>
			<div class=\"form-group\">
				<label class=\"control-label col-sm-2\" for=\"feedback_email\">"._EMAIL.":</label>
				<div class=\"col-sm-10\">
					<input type=\"email\" class=\"form-control col-xs-8\" id=\"feedback_email\" name=\"feedback_fields[email]\" value=\"$user_email\" placeholder=\""._ENTER_EMAIL."\" data-validation=\"email required\" data-validation-error-msg=\""._ENTER_EMAIL."\">
				</div>
			</div>
			<div class=\"form-group\">
				<label class=\"control-label col-sm-2\" for=\"feedback_subject\">"._SUBJECT.":</label>
				<div class=\"col-sm-10\">
					<input type=\"text\" class=\"form-control col-xs-8\" id=\"feedback_subject\" name=\"feedback_fields[subject]\" placeholder=\""._ENTER_SUBJECT."\">
				</div>
			</div>
			$custom_field_contents
			<div class=\"form-group\">
				<label class=\"control-label col-sm-2\" for=\"feedback_dept\">"._CONTACT_TO.":</label>
				<div class=\"col-sm-10\">
					<select name=\"feedback_fields[dept]\" class=\"form-control\" id=\"feedback_dept\">
						<option value=\"0\">"._DEFAULT."</option>
						$depts_html
					</select>
				</div>
			</div>
			<div class=\"form-group\"> 
				<div class=\"col-sm-offset-2 col-sm-10\">
					<textarea class=\"form-control\" rows=\"5\" id=\"feedback_message\" name=\"feedback_fields[message]\" placeholder=\""._MESSAGE_TEXT."\" data-validation=\"required\" data-validation-error-msg=\""._ENTER_MESSAGE."\"></textarea>
				</div>
			</div>";
			if(extension_loaded("gd") && in_array("feedback", $nuke_configs['mtsn_gfx_chk']))
			{
				$sec_code_options = array(
					"input_attr" => array(
						"class" => "form-control",
						"dir" => "ltr",
						"data-validation=\"required\" data-validation-error-msg" => _BADSECURITYCODE,
					)
				);
				$security_code_input = makePass($captcha_id, $sec_code_options);
				$contents .= "
				<div class=\"form-group\">
					<label class=\"control-label col-sm-2\" for=\"subject\">"._SECCODE.":</label>
					<div class=\"col-sm-10\">
						".$security_code_input['image']."<br /><br />".$security_code_input['input']."
					</div>
				</div>";
			}
			$contents .= "<div class=\"form-group\"> 
				<div class=\"col-sm-offset-2 col-sm-1\">
					<button type=\"submit\" class=\"btn btn-default\" id=\"feedback_submit\">"._SEND."</button>
				</div>
				<div class=\"col-sm-9\">
					<div id=\"feedback_response\"></div>
				</div>
			</div>
		</form>
		<div id=\"phpnuke_map\" style=\"width:100%;height:300px;\"></div>";
		$contents .= CloseTable();
	}
		
	$meta_tags = array(
		"url" => LinkToGT("index.php?modname=Feedback"),
		"title" => _CONTACT_US,
		"description" => $feedback_configs['meta_description'],
		"extra_meta_tags" => array()
	);
	$meta_tags = $hooks->apply_filters("feedback_header_meta", $meta_tags);
	
	$hooks->add_filter("site_header_meta", function ($all_meta_tags) use($meta_tags)
	{
		return array_merge($all_meta_tags, $meta_tags);
	}, 10);		
	unset($meta_tags);
	
	$hooks->add_filter("site_breadcrumb", function($breadcrumbs, $block_global_contents){
		$breadcrumbs['feedback'] = array(
			"name" => _CONTACT_US,
			"link" => LinkToGT("index.php?modname=Feedback"),
			"itemtype" => "WebPage"
		);
		return $breadcrumbs;
	}, 10);
	
	include("header.php");
	$html_output .= show_modules_boxes($module_name, "index", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
	include("footer.php");
}

$op = (isset($op)) ? filter($op, "nohtml"):'';
$submit = filter(request_var('submit', '', '_POST'), "nohtml");
$feedback_fields = request_var('feedback_fields', array(), '_POST');

switch ($op)
{
	default:
		feedback($submit, $feedback_fields);
	break;
}
?>