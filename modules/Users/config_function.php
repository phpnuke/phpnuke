<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('CONFIG_FUNCTIONS_FILE')) {
	die("You can't access this file directly...");
}

$this_module_name = basename(dirname(__FILE__));

define("ANONYMOUS", 1);
define("USER_STATUS_INACTIVE", 0);
define("USER_STATUS_ACTIVE", 1);
define("USER_STATUS_REQUIRE_ADMIN", 2);
define("USER_STATUS_EMAIL_ACTIVATE", 3);
define("USER_STATUS_DELETED", -1);
define('AVATAR_UPLOAD', 'upload');
define('AVATAR_REMOTE', 'remote');
define('AVATAR_GRAVATAR', 'gravatar');
define('CHMOD_ALL', 7);
define('CHMOD_READ', 4);
define('CHMOD_WRITE', 2);
define('CHMOD_EXECUTE', 1);

$cache_systems['nuke_groups'] = array(
	'name'			=> "_GROUPS",
	"main_id"		=> 'group_id',
	'table'			=> GROUPS_TABLE,
	'where'			=> "group_id != '1'",
	'order'			=> 'ASC',
	'fetch_type'	=> \PDO::FETCH_ASSOC,
	'first_code'	=> '',
	'loop_code'		=> '',
	'end_code'		=> '',
	'auto_load'		=> true
);

function login_sign_up_theme($mode="header")
{
	global $db, $op, $nuke_configs, $ya_config, $module_name;
	$content = '';
	
	$custom_theme_setup = array(
		"default_css" => array(
			"<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/bootstrap/css/bootstrap.min.css\">",
			"<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."includes/fonts/font-awesome.min.css\">",
			"".((_DIRECTION == 'rtl') ? "<link rel=\"stylesheet\" href=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/bootstrap/css/bootstrap-rtl.css\">":"")."",
			"<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."modules/$module_name/includes/CNBYA.css\">",
		),
		"default_js" => array(
			"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/jquery.min.js\"></script>",
			"<script>var phpnuke_url = '".$nuke_configs['nukeurl']."';var phpnuke_cdnurl = '".$nuke_configs['nukecdnurl']."';var phpnuke_theme = '".$nuke_configs['ThemeSel']."';var module_name = '".$module_name."';var reset_password_url = '".LinkToGT("index.php?modname=$module_name&op=reset_password")."';</script>",
			"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>",
		),
		"defer_js" => array(			
			"<!--[if lt IE 9]> <script src=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/plugins/html5shiv/dist/html5shiv.js\"></script><script src=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/plugins/respond/respond.min.js\"></script> <![endif]-->",
			"<script src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/bootstrap/js/bootstrap.min.js\"></script>",
			"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."themes/".$nuke_configs['ThemeSel']."/script/script.js\"></script>",
		),
		"default_link_rel" => array(
			"<link rel=\"apple-touch-icon-precomposed\" sizes=\"114x114\" href=\"".$nuke_configs['nukeurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/114x114.png\">",
			"<link rel=\"apple-touch-icon-precomposed\" sizes=\"72x72\" href=\"".$nuke_configs['nukeurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/72x72.png\">",
			"<link rel=\"apple-touch-icon-precomposed\" sizes=\"57x57\" href=\"".$nuke_configs['nukeurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/57x57.png\">",
			"<link rel=\"apple-touch-icon-precomposed\" href=\"".$nuke_configs['nukeurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/default.png\">",
			"<link rel=\"shortcut icon\" href=\"".$nuke_configs['nukeurl']."themes/".$nuke_configs['ThemeSel']."/images/icons/favicon.png\">",
		),
		"default_meta" => array()
	);
	if($mode == "header")
	{
		$content = _theme_header($ya_config['meta_tags'], $custom_theme_setup, true);
		$content .=" 
	<body>
		<div class=\"container\">
			<div class=\"row\">
				<div class=\"col-md-6 col-md-offset-3\">
					<div class=\"panel panel-default\">
						<div class=\"panel-body\">
							<div class=\"row\">
								<div class=\"col-lg-12\">";
	}
	else
	{
		$content ="
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id=\"footer text-center\">";
				if($op == 'sign_up')
					$content .="<p class=\"text-center\">طي فرآيند ثبت نام به هيچ عنوان به صفحه قبل باز نگرديد. در غير اين صورت بايد مراحل ثبت نام را از نو آغاز نماييد</p>";
				$content .="<p class=\"text-center\">Copyright © 2019 All Rights are Reserved. Powerd By <a href=\"http://www.PHPNuke.ir/\">PHPNuke.ir</a>
				</p>
			</div>
		</div>
	
		<!-- Modal -->
		<div class=\"modal fade\" id=\"sitemodal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">
			<div class=\"modal-dialog\">
				<div class=\"modal-content\">
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<script type=\"text/javascript\">
			$('#sitemodal').on('hidden.bs.modal', function (e) {
				$(e.target).removeData(\"bs.modal\").find(\".modal-content\").empty();
			})
		</script>
		<!-- /.modal -->";
		$content .= _theme_footer($custom_theme_setup, true);
	}
	return $content;
}

function ya_html_output($ya_config, $contents)
{
	global $module_name, $nuke_configs;
	
	$meta_tags = $ya_config['meta_tags'];
	if($ya_config['login_sign_up_theme'] == 1)
	{
		$content = login_sign_up_theme("header");
		$content .= $contents;
		$content .= login_sign_up_theme("footer");
		die($content);
	}
	else
	{
		$custom_theme_setup = array(
			"default_css" => array(
				"<link rel=\"stylesheet\" type=\"text/css\" href=\"".$nuke_configs['nukecdnurl']."modules/$module_name/includes/CNBYA_in.css\">",
			),
			"default_js" => array(
				"<script>var phpnuke_url = '".$nuke_configs['nukeurl']."';var phpnuke_cdnurl = '".$nuke_configs['nukecdnurl']."';var phpnuke_theme = '".$nuke_configs['ThemeSel']."';var module_name = '".$module_name."';</script>",
				"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>",
			),
			"default_link_rel" => array(),
			"default_meta" => array()
		);
		if(isset($ya_config['custom_theme_setup']) && !empty($ya_config['custom_theme_setup']))
			foreach($ya_config['custom_theme_setup'] as $key => $value)
				$custom_theme_setup[$key] = array_merge($custom_theme_setup[$key], $value);
		
		$custom_theme_setup_replace = false;
		include("header.php");
		unset($meta_tags);
		$html_output .= show_modules_boxes($module_name, "login_sighnup", array("bottom_full", "top_full","left","top_middle","bottom_middle","right"), $contents);
		include("footer.php");
	}
}

function ya_custum_userfields(&$userinfo, $user_id)
{
	global $db, $nuke_Configs;
	
	$user_id = intval($user_id);
	
	if($user_id == 0)
		return;
	
	$result = $db->query("SELECT f.name, f.display, v.value FROM ".USERS_FIELDS_TABLE." as f LEFT JOIN ".USERS_FIELDS_VALUES_TABLE." as v ON v.fid = f.fid WHERE v.uid = ? AND f.act = '1'", [$user_id]);
	
	$results = $result->results();
	if(!empty($results))
	{
		foreach($results as $field => $value)
		{
			$userinfo[$value['name']] = $value['value'];
			$userinfo['custom_fields'][$value['name']] = array($value['display'], $value['value']);
		}
	}
}

function _check_register_fields($field = 'username', $value = '', $retuen_num = false, $mode = 'new', $default_value = '')
{
	global $db, $nuke_configs, $ya_config;

	$user_info = $db->table(USERS_TABLE)
				->where($field, $value)
				->select(['user_id', 'username', 'user_email', 'user_realname']);

	if(intval($user_info->count()) > 0)
		$userinfo = $user_info->results()[0];
	
	$response = array(
	  'valid' => false,
	  'message' => 'Post argument is missing.'
	);
	
	if(filter($mode, 'nohtml') == 'edit' && isset($userinfo[$field]) && $userinfo[$field] == $value)
	{
		$default_value = (isset($default_value) && $default_value != '') ? filter($default_value, "nohtml"):"";
		$response = ($default_value != '' && $default_value == $userinfo[$field]) ? true:false;
		$message = ($response) ? '':(($field == "username") ? _USER_HAS_REGISTERED:(($field == "user_email") ? _USER_EMAIL_HAS_SELECTED:'bad value'));
		$response = array('valid' => $response, 'message' => $message);
		die(json_encode($response));
	}
	
	$result_count = $db->table(USERS_TABLE)
						->where($field, $value)
						->first(['user_id']);
	$result_count = $db->count();

	if(filter_var($retuen_num, FILTER_VALIDATE_BOOLEAN))
	{
		return ($result_count > 0) ? true:false;
		die();
	}
	
	$message = "";
	$result = true;
	
	$bad_mail = ($ya_config['bad_mail'] != '' && !is_array($ya_config['bad_mail'])) ? explode("\n", str_replace("\r","",$ya_config['bad_mail'])):array();
	$bad_username = ($ya_config['bad_username'] != '' && !is_array($ya_config['bad_username'])) ? explode("\n", str_replace("\r","",$ya_config['bad_username'])):array();
	$bad_nick = ($ya_config['bad_nick'] != '' && !is_array($ya_config['bad_nick'])) ? explode("\n", str_replace("\r","",$ya_config['bad_nick'])):array();
	
	if($field == "username")
	{
		if(in_array($value, $bad_username))
		{
			$message = _USER_BAD_NAME;
			$result = false;
		}
		elseif($result_count > 0)
		{
			$message = _USER_HAS_REGISTERED;
			$result = false;
		}
		
	}
	
	if($field == "user_realname")
	{
		if(in_array($value, $bad_nick))
		{
			$message = _USER_BAD_REALNAME;
			$result = false;
		}
	}
	
	if($field == "user_email")
	{
		if(in_array($value, $bad_mail))
		{
			$message = _USER_BAD_EMAIL;
			$result = false;
		}
		elseif($result_count > 0)
		{
			$message = _USER_EMAIL_HAS_SELECTED;
			$result = false;
		}
	}

	$response = array('valid' => $result, 'message' => $message);
	die(json_encode($response));
}

function users_config()
{
	global $nuke_configs, $db, $admin_file, $ya_config;
	
	$contents = '';
	$ya_config = (isset($ya_config) && !empty($ya_config)) ? $ya_config:((isset($nuke_configs['users']) && $nuke_configs['users'] != '') ? phpnuke_unserialize(stripslashes($nuke_configs['users'])):array());

	$ya_config['bad_mail'] = $ya_config['bad_mail'];
	$ya_config['bad_nick'] = $ya_config['bad_nick'];
	$ya_config['bad_username'] = $ya_config['bad_username'];
	
	$contents .="
	<form action='".$admin_file.".php' method='post'>
	<table border=\"0\" align=\"center\" cellpadding=\"3\" class=\"id-form product-table no-border\" width=\"100%\">
	<tr><th colspan=\"2\" style=\"text-align:center\">"._USERS_CONFIGS."</th></tr>
	<tr><th style=\"width:200px;\">"._LOGIN_SIGN_UP_THEME."</th><td>";
	$checked1 = ($ya_config['login_sign_up_theme'] == 1) ? "checked":"";
	$checked2 = ($ya_config['login_sign_up_theme'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][login_sign_up_theme]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][login_sign_up_theme]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th style=\"width:200px;\">"._ALLOW_REGISTER."</th><td>";
	$checked1 = ($ya_config['allowuserreg'] == 1) ? "checked":"";
	$checked2 = ($ya_config['allowuserreg'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][allowuserreg]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][allowuserreg]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th style=\"width:200px;\">"._COPPA_CHECK."</th><td>";
	$checked1 = ($ya_config['coppa'] == 1) ? "checked":"";
	$checked2 = ($ya_config['coppa'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][coppa]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][coppa]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th style=\"width:200px;\">"._TOS_CHECK."</th><td>";
	$checked1 = ($ya_config['tos'] == 1) ? "checked":"";
	$checked2 = ($ya_config['tos'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][tos]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][tos]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th style=\"width:200px;\">"._INVITATION."</th><td>";
	$checked1 = ($ya_config['invitation'] == 1) ? "checked":"";
	$checked2 = ($ya_config['invitation'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][invitation]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][invitation]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th>"._MAX_INVITATION."</th><td>
		<input type=\"text\" name=\"config_fields[users][max_invitation]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['max_invitation']."\">
	</td></tr>
	<tr><th>"._NICK_MAX."</th><td>
		<input type=\"text\" name=\"config_fields[users][nick_max]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['nick_max']."\">
	</td></tr>
	<tr><th>"._NICK_MIN."</th><td>
		<input type=\"text\" name=\"config_fields[users][nick_min]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['nick_min']."\">
	</td></tr>
	<tr><th>"._PASS_MAX."</th><td>
		<input type=\"text\" name=\"config_fields[users][pass_max]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['pass_max']."\">
	</td></tr>
	<tr><th>"._PASS_MIN."</th><td>
		<input type=\"text\" name=\"config_fields[users][pass_min]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['pass_min']."\">
	</td></tr>
	<tr><th>"._DOUBLE_CHECK_EMAIL."</th><td>";
	$checked1 = ($ya_config['doublecheckemail'] == 1) ? "checked":"";
	$checked2 = ($ya_config['doublecheckemail'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][doublecheckemail]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][doublecheckemail]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr>
		<th>"._BAD_MAILS."</th>
		<td>
			<textarea class=\"form-textarea\" name=\"config_fields[users][bad_mail]\" style=\"height: 100px; width: 400px;direction:ltr;padding:10px;\">".$ya_config['bad_mail']."</textarea>
		</td>
	</tr>
	<tr>
		<th>"._BAD_USERNAMES."</th>
		<td>
			<textarea class=\"form-textarea\" name=\"config_fields[users][bad_username]\" style=\"height: 100px; width: 400px;direction:ltr;padding:10px;\">".$ya_config['bad_username']."</textarea>
		</td>
	</tr>
	<tr>
		<th>"._BAD_NICKS."</th>
		<td>
			<textarea class=\"form-textarea\" name=\"config_fields[users][bad_nick]\" style=\"height: 100px; width: 400px;direction:ltr;padding:10px;\">".$ya_config['bad_nick']."</textarea>
		</td>
	</tr>
	<tr><th>"._REQUIRE_ADMIN_CONFIRM."</th><td>";
	$checked1 = ($ya_config['requireadmin'] == 1) ? "checked":"";
	$checked2 = ($ya_config['requireadmin'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][requireadmin]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][requireadmin]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th>"._REQUIRE_EMAIL_ACTIVATION."</th><td>";
	$checked1 = ($ya_config['email_activatation'] == 1) ? "checked":"";
	$checked2 = ($ya_config['email_activatation'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][email_activatation]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][email_activatation]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th>"._SEND_REGISTER_EMAIL."</th><td>";
	$checked1 = ($ya_config['send_email_af_reg'] == 1) ? "checked":"";
	$checked2 = ($ya_config['send_email_af_reg'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][send_email_af_reg]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][send_email_af_reg]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th>"._SEND_REGISTER_EMAIL_TO_ADMIN."</th><td>";
	$checked1 = ($ya_config['sendaddmail'] == 1) ? "checked":"";
	$checked2 = ($ya_config['sendaddmail'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][sendaddmail]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][sendaddmail]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th>"._AVATAR_SALT."</th><td>
		<input type=\"text\" name=\"config_fields[users][avatar_salt]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['avatar_salt']."\">
	</td></tr>
	<tr><th>"._AVATAR_PATH."</th><td>
		<input type=\"text\" name=\"config_fields[users][avatar_path]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['avatar_path']."\">
	</td></tr>
	<tr><th>"._ALLOW_AVATAR."</th><td>";
	$checked1 = ($ya_config['allow_avatar'] == 1) ? "checked":"";
	$checked2 = ($ya_config['allow_avatar'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][allow_avatar]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][allow_avatar]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th>"._ALLOW_AVATAR_UPLOAD."</th><td>";
	$checked1 = ($ya_config['allow_avatar_upload'] == 1) ? "checked":"";
	$checked2 = ($ya_config['allow_avatar_upload'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][allow_avatar_upload]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][allow_avatar_upload]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th>"._ALLOW_AVATAR_REMOTE."</th><td>";
	$checked1 = ($ya_config['allow_avatar_remote'] == 1) ? "checked":"";
	$checked2 = ($ya_config['allow_avatar_remote'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][allow_avatar_remote]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][allow_avatar_remote]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th>"._ALLOW_GRAVATAR."</th><td>";
	$checked1 = ($ya_config['allow_gravatar'] == 1) ? "checked":"";
	$checked2 = ($ya_config['allow_gravatar'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][allow_gravatar]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][allow_gravatar]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th>"._ALLOW_CHANGE_MAIL."</th><td>";
	$checked1 = ($ya_config['allowmailchange'] == 1) ? "checked":"";
	$checked2 = ($ya_config['allowmailchange'] == 0) ? "checked":"";
	$contents .= "<input type='radio' class='styled' name='config_fields[users][allowmailchange]' value='1' data-label=\"" . _YES . "\" $checked1> &nbsp; &nbsp;<input type='radio' class='styled' name='config_fields[users][allowmailchange]' value='0' data-label=\"" . _NO . "\" $checked2>
	</td></tr>
	<tr><th>"._AVATAR_MAX_WIDTH."</th><td>
		<input type=\"text\" name=\"config_fields[users][avatar_max_width]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['avatar_max_width']."\">
	</td></tr>
	<tr><th>"._AVATAR_MAX_HEIGHT."</th><td>
		<input type=\"text\" name=\"config_fields[users][avatar_max_height]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['avatar_max_height']."\">
	</td></tr>
	<tr><th>"._AVATAR_MIN_WIDTH."</th><td>
		<input type=\"text\" name=\"config_fields[users][avatar_min_width]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['avatar_min_width']."\">
	</td></tr>
	<tr><th>"._AVATAR_MIN_HEIGHT."</th><td>
		<input type=\"text\" name=\"config_fields[users][avatar_min_height]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['avatar_min_height']."\">
	</td></tr>
	<tr><th>"._AVATAR_MAX_FILESIZE."</th><td>
		<input type=\"text\" name=\"config_fields[users][avatar_filesize]\" size=\"40\" class=\"inp-form-ltr\" value=\"".$ya_config['avatar_filesize']."\">
	</td></tr>
	<tr><th>"._SITE_MTTOS."</th><td>";
		$contents .= wysiwyg_textarea('config_fields[users][mttos]', $ya_config['mttos'], 'Basic', 10, 3);
	$contents .= "</td></tr>
	<tr><th>"._NOTIFY_METHODS."</th><td>";
	$checked1 = (isset($ya_config['notify']['sms']) && $ya_config['notify']['sms'] == 1) ? "checked":"";
	$checked2 = (isset($ya_config['notify']['email']) && $ya_config['notify']['email'] == 1) ? "checked":"";
	$contents .= "<input type='checkbox' class='styled' name='config_fields[users][notify][sms]' value='1' data-label=\"" . _SMS . "\" $checked1> &nbsp; &nbsp;<input type='checkbox' class='styled' name='config_fields[users][notify][email]' value='1' data-label=\"" . _EMAIL . "\" $checked2>
	</td></tr>
	<tr><td colspan=\"2\">
		<input type=\"submit\" name=\"submit\" class=\"form-submit\" value=\"submit\">
	</td></tr>
	</table>
	<input type='hidden' name='op' value='save_configs'>
	<input type='hidden' name='return_op' value='settings#users_config'>
	<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
	</form>";
	 return $contents;
}

$other_admin_configs['users_config'] = array("title" => _USERS_CONFIGS, "function" => "users_config", "God" => false);

$nuke_modules_boxes_parts[$this_module_name] = array(
	"login_signup" => "_LOGIN_SIGNUP",
	"profile" => "_USER_PROFILE",
	"edit" => "_EDIT",
);

?>