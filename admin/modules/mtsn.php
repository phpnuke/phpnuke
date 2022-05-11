<?PHP

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2017 by MashhadTeam                                    */
/* http://www.phpnuke.ir                                                */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
/*********************************************************/
/* MTSN - MashhadTeam Secure Nuke  [Security Is First]   */
/*********************************************************/

if (!defined('ADMIN_FILE'))
{
  die ("Access Denied");
}

global $db, $admin_file;

$filename =  basename(__FILE__);
$filename = substr($filename,0,-4);

if (check_admin_permission($filename))
{
	function mtsn_admin()
	{
		global $db, $admin_file, $hooks, $nuke_configs;
		
		$hooks->add_filter("set_page_title", function(){return array("mtsn_admin" => _MTSNADMIN);});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><b>MTSN V ".$nuke_configs['mtsn_version']."</b></div>
		<br /><p align='center'>
		[ <a href='".$admin_file.".php?op=mtsn_admin'>"._MTSNADMIN."</a> | <a href='".$admin_file.".php?op=ip_ban_page'>"._IP_PART."</a> ]
		</p><br /><br />";
		$contents .= CloseAdminTable();
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><b>"._SETTINGS."</b></div>
		<form style='margin:0;' method='post' action='".$admin_file.".php'>
		<table cellpadding='2' cellspacing='2' width=\"100%\" class=\"product-table no-border id-form\">";

		$check1 = ($nuke_configs['mtsn_text_file']=="1") ? " checked":"";
		$check2 = ($nuke_configs['mtsn_text_file']=="0") ? " checked":"";

		$contents .= "<tr>
		<th style=\"width:200px;\">"._SAVE_IN_TEXT_FILE."</th>
		<td><input type=\"radio\" name=\"config_fields[mtsn_text_file]\" class=\"styled\" data-label=\""._YES."\" value=\"1\" $check1 /> &nbsp;&nbsp; <input type=\"radio\" name=\"config_fields[mtsn_text_file]\" class=\"styled\" data-label=\""._NO."\" value=\"0\" $check2 /></td></tr>";

		$check1 = ($nuke_configs['mtsn_show_alarm']=="1") ? " checked":"";
		$check2 = ($nuke_configs['mtsn_show_alarm']=="0") ? " checked":"";

		$contents .= "<tr><th>"._SHOW_ATACK_WARNING."</th><td><input type=\"radio\" name=\"config_fields[mtsn_show_alarm]\" class=\"styled\" data-label=\""._YES."\" value=\"1\" $check1 /> &nbsp;&nbsp; <input type=\"radio\" name=\"config_fields[mtsn_show_alarm]\" class=\"styled\" data-label=\""._NO."\" value=\"0\" $check2 /></td></tr>";

		$check1 = ($nuke_configs['mtsn_status']=="1") ? " checked":"";
		$check2 = ($nuke_configs['mtsn_status']=="0") ? " checked":"";

		$contents .= "<tr><th>"._STATUS."</th><td><input type=\"radio\" name=\"config_fields[mtsn_status]\" class=\"styled\" data-label=\""._ACTIVE."\" value=\"1\" $check1 /> &nbsp;&nbsp; <input type=\"radio\" name=\"config_fields[mtsn_status]\" class=\"styled\" data-label=\""._INACTIVE."\" value=\"0\" $check2 /></td></tr>";

		$check1 = ($nuke_configs['mtsn_block_ip']=="1") ? " checked":"";
		$check2 = ($nuke_configs['mtsn_block_ip']=="0") ? " checked":"";

		$contents .= "<tr><th>"._AUTOMATIC_IPBAN."</th><td><input type=\"radio\" name=\"config_fields[mtsn_block_ip]\" class=\"styled\" data-label=\""._ACTIVE."\" value=\"1\" $check1 /> &nbsp;&nbsp; <input type=\"radio\" name=\"config_fields[mtsn_block_ip]\" class=\"styled\" data-label=\""._INACTIVE."\" value=\"0\" $check2 /></td></tr>
		
		<tr><th>"._UNBAN_AFTER." </th><td><input type=\"text\" class=\"inp-form\" dir='ltr' name='config_fields[mtsn_block_ip_expire]' value='".$nuke_configs['mtsn_block_ip_expire']."' size='30'> "._SECOND."".bubble_show(_NUMBER_IS_SECOND)."</td></tr>";

		$check1 = ($nuke_configs['mtsn_send_mail']=="1") ? " checked":"";
		$check2 = ($nuke_configs['mtsn_send_mail']=="0") ? " checked":"";

		$contents .= "<tr><th>"._EMAIL_SENDING."</th><td><input type=\"radio\" name=\"config_fields[mtsn_send_mail]\" class=\"styled\" data-label=\""._YES."\" value=\"1\" $check1 /> &nbsp;&nbsp; <input type=\"radio\" name=\"config_fields[mtsn_send_mail]\" class=\"styled\" data-label=\""._NO."\" value=\"0\" $check2 /></td></tr>
		
		<tr><th>"._ADMINEMAIL."</th><td><input type=\"text\" class=\"inp-form\" dir='ltr' name='config_fields[mtsn_admin_mail]' value='".$nuke_configs['mtsn_admin_mail']."' size='30'></td></tr>
		<tr><th colspan=\"2\" style=\"text-align:center;\">"._PROTECTION_AGAINST_ATTACKSSETTINGS."</th></tr>";

		$check1 = ($nuke_configs['mtsn_string_filter']=="1") ? " checked":"";
		$check2 = ($nuke_configs['mtsn_string_filter']=="0") ? " checked":"";

		$contents .= "<tr>
		<th>"._STRINGS_ATTACKS."</th>
		<td><input type=\"radio\" name=\"config_fields[mtsn_string_filter]\" class=\"styled\" data-label=\""._YES."\" value=\"1\" $check1 /> &nbsp;&nbsp; <input type=\"radio\" name=\"config_fields[mtsn_string_filter]\" class=\"styled\" data-label=\""._NO."\" value=\"0\" $check2 /></td>
		</tr>";

		$check1 = ($nuke_configs['mtsn_html_filter']=="1") ? " checked":"";
		$check2 = ($nuke_configs['mtsn_html_filter']=="0") ? " checked":"";

		$contents .=  "
		<tr>
		<th>"._FILTERING_UNAUTHORIZED_CODES."</th>
		<td><input type=\"radio\" name=\"config_fields[mtsn_html_filter]\" class=\"styled\" data-label=\""._YES."\" value=\"1\" $check1 /> &nbsp;&nbsp; <input type=\"radio\" name=\"config_fields[mtsn_html_filter]\" class=\"styled\" data-label=\""._NO."\" value=\"0\" $check2 /></td>
		</tr>";

		$check1 = ($nuke_configs['mtsn_injection_filter']=="1") ? " checked":"";
		$check2 = ($nuke_configs['mtsn_injection_filter']=="0") ? " checked":"";

		$contents .= "
		<tr>
		<th>"._ATTACKS_TO_DATABASE."</th>
		<td><input type=\"radio\" name=\"config_fields[mtsn_injection_filter]\" class=\"styled\" data-label=\""._YES."\" value=\"1\" $check1 /> &nbsp;&nbsp; <input type=\"radio\" name=\"config_fields[mtsn_injection_filter]\" class=\"styled\" data-label=\""._NO."\" value=\"0\" $check2 /></td>
		</tr>";
		
		$check1 = ($nuke_configs['mtsn_ddos_filter']=="1") ? " checked":"";
		$check2 = ($nuke_configs['mtsn_ddos_filter']=="0") ? " checked":"";

		$contents .= "<tr>
		<th>"._ATTACKS_TO_DDOS."</th>
		<td><input type=\"radio\" name=\"config_fields[mtsn_ddos_filter]\" class=\"styled\" data-label=\""._YES."\" value=\"1\" $check1 /> &nbsp;&nbsp; <input type=\"radio\" name=\"config_fields[mtsn_ddos_filter]\" class=\"styled\" data-label=\""._NO."\" value=\"0\" $check2 />".bubble_show(_ATTACKS_TO_DDOS_DESC)."</td>
		</tr>
		<tr><th colspan=\"2\" style=\"text-align:center;\">"._OTHER_SETTINGS."</th></tr>
		";
		$mtsn_gfx_chk = ($nuke_configs['mtsn_gfx_chk'] != '') ? ((is_array($nuke_configs['mtsn_gfx_chk'])) ? $nuke_configs['mtsn_gfx_chk']:explode(",", $nuke_configs['mtsn_gfx_chk'])):array();
		$check1 = (in_array('admin_login', $mtsn_gfx_chk)) ? "checked":"";
		$check2 = (in_array('user_login', $mtsn_gfx_chk)) ? "checked":"";
		$check3 = (in_array('comments', $mtsn_gfx_chk)) ? "checked":"";
		$check4 = (in_array('send_post', $mtsn_gfx_chk)) ? "checked":"";
		$check5 = (in_array('feedback', $mtsn_gfx_chk)) ? "checked":"";
		$check6 = (in_array('user_sign_up', $mtsn_gfx_chk)) ? "checked":"";
		
		$contents .="
		<tr>
			<th style=\"width:250px\">"._SEQCODE_SETTINGS."</th><td>
				<table width=\"100%\">
					<tr>
						<td>
							<input type=\"checkbox\" class=\"styled\" name=\"config_fields[mtsn_gfx_chk][]\" value=\"admin_login\" data-label=\""._ADMIN_LOGIN."\" $check1 /> &nbsp;&nbsp;
						</td>
						<td>
							<input type=\"checkbox\" class=\"styled\" name=\"config_fields[mtsn_gfx_chk][]\" value=\"user_login\" data-label=\""._ADMIN_LOGIN."\" $check2 /> &nbsp;&nbsp;
						</td>
						<td>
							<input type=\"checkbox\" class=\"styled\" name=\"config_fields[mtsn_gfx_chk][]\" value=\"comments\" data-label=\""._COMMENT_SEND."\" $check3 /> &nbsp;&nbsp;
						</td>
						<td>
							<input type=\"checkbox\" class=\"styled\" name=\"config_fields[mtsn_gfx_chk][]\" value=\"send_post\" data-label=\""._SEND_POST."\" $check4 /> &nbsp;&nbsp;
						</td>
						<td>
							<input type=\"checkbox\" class=\"styled\" name=\"config_fields[mtsn_gfx_chk][]\" value=\"feedback\" data-label=\""._SEND_FEEDBACK."\" $check5 /> &nbsp;&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<input type=\"checkbox\" class=\"styled\" name=\"config_fields[mtsn_gfx_chk][]\" value=\"user_sign_up\" data-label=\""._SIGNUP_COMPLATE."\" $check6 /> &nbsp;&nbsp;
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th>"._SECCODE_TYPE."</th>
			<td>
				<select name='config_fields[seccode_type]' class=\"styledselect-select\">";
					$sel0 = ($nuke_configs['seccode_type'] == 1) ? "selected":"";
					$sel1 = ($nuke_configs['seccode_type'] == 2) ? "selected":"";
					$contents .="<option value='1' $sel0>" . _CHARS_SECCODE . "</option>
					<option value='2' $sel1>" . _GOOGLE_RECAPTCHA . "</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>"._GOOGLE_RECAPTCHA_API_SITEKEY."</th>
			<td>
				<input type=\"text\" class=\"inp-form-ltr\" name=\"config_fields[google_recaptcha_sitekey]\" value=\"".$nuke_configs['google_recaptcha_sitekey']."\" />
				".bubble_show(_GOOGLE_RECAPTCHA_HELP)."
			</td>
		</tr>
		<tr>
			<th>"._GOOGLE_RECAPTCHA_API_SECRETKEY."</th>
			<td>
				<input type=\"text\" class=\"inp-form-ltr\" name=\"config_fields[google_recaptcha_secretkey]\" value=\"".$nuke_configs['google_recaptcha_secretkey']."\" />
			</td>
		</tr>
		<tr>
			<th>"._SEQCODE_CHARS."</th>
			<td>
				<input type=\"text\" class=\"inp-form\" name=\"config_fields[mtsn_captcha_charset]\" value=\"".$nuke_configs['mtsn_captcha_charset']."\" />
			".bubble_show(_LEAVE_EMPTY_TO_DEFAULT)."
			</td>
		</tr>
		<tr>
			<th>"._CENSORMODE."</th>
			<td>
				<select name='config_fields[mtsn_CensorMode]' class=\"styledselect-select\">";
					$sel0 = ($nuke_configs['mtsn_CensorMode'] == 0) ? "selected":"";
					$sel1 = ($nuke_configs['mtsn_CensorMode'] == 1) ? "selected":"";
					$sel2 = ($nuke_configs['mtsn_CensorMode'] == 2) ? "selected":"";
					$sel3 = ($nuke_configs['mtsn_CensorMode'] == 3) ? "selected":"";
					$contents .="<option value='0' $sel0>" . _NOFILTERING . "</option>
					<option value='1' $sel1>" . _EXACTMATCH . "</option>
					<option value='2' $sel2>" . _MATCHBEG . "</option>
					<option value='3' $sel3>" . _MATCHANY . "</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>"._DISALLOWED_WORD_AND_CHARS."</th>
			<td>
				<textarea class=\"form-textarea\" name='config_fields[mtsn_CensorWords]'>".$nuke_configs['mtsn_CensorWords']."</textarea>
			".bubble_show(_DISALLOWED_WORD_AND_CHARS_DESC)."
			</td>
		</tr>
		<tr>
			<th>"._IGNORE_SOME_WORDS_IN_SEARCH."</th>
			<td>
				<select name=\"config_fields[mtsn_search_skipwords][]\" class=\"styledselect-select tag-input\" multiple=\"multiple\" style=\"width:100%\">";
				$mtsn_search_skipwords = ($nuke_configs['mtsn_search_skipwords'] != '') ? explode(",", $nuke_configs['mtsn_search_skipwords']):array();
				$mtsn_search_skipwords = array_filter($mtsn_search_skipwords);
				if(!empty($mtsn_search_skipwords))
				{
					foreach($mtsn_search_skipwords as $skipwords)
					{
						$contents .= "<option value=\"$skipwords\" selected>$skipwords</option>";
					}					
				}					
				$contents .= "</select>
			".bubble_show(_IGNORE_SOME_WORDS_DESC)."
			</td>
		</tr>
		<tr><th>"._CENSORREPLACE."</th><td>
			<input type=\"text\" name='config_fields[mtsn_CensorReplace]' class=\"inp-form\" value=\"".$nuke_configs['mtsn_CensorReplace']."\">
		</td></tr>
		<tr><th>"._LOGIN_ATTEMPTS."</th><td>
			<input type=\"text\" name='config_fields[mtsn_login_attempts]' class=\"inp-form\" value=\"".$nuke_configs['mtsn_login_attempts']."\">
			".bubble_show(_LOGIN_ATTEMPTS_DESC)."
		</td></tr>
		<tr><th>"._LOGIN_ATTTEMPTS_EXPIRE."</th><td>
			<input type=\"text\" name='config_fields[mtsn_login_attempts_time]' class=\"inp-form\" value=\"".$nuke_configs['mtsn_login_attempts_time']."\">
			".bubble_show(_LOGIN_ATTTEMPTS_EXPIRE_DESC)."
		</td></tr>
		<tr><th>"._REFERRERS_EXPIRE."</th><td>
			<input type=\"text\" name='config_fields[mtsn_requests_mintime]' class=\"inp-form\" value=\"".$nuke_configs['mtsn_requests_mintime']."\">
			".bubble_show(_REFERRERS_EXPIRE_DESC)."
		</td></tr>
		<tr><th>"._ANTIFLOOD_ALLOWED_PAGENAMUBERS."</th><td>
			<input type=\"text\" name='config_fields[mtsn_requests_pages]' class=\"inp-form\" value=\"".$nuke_configs['mtsn_requests_pages']."\">
			".bubble_show(_ANTIFLOOD_ALLOWED_PAGENAMUBERS_DESC)."
		</td></tr>
		<tr><th>"._MAX_LOGIN_TIME."</th><td>
			<input type=\"text\" name='config_fields[session_timeout]' class=\"inp-form\" value=\"".$nuke_configs['session_timeout']."\">
			".bubble_show(_MAX_LOGIN_TIME_DESC)."
		</td></tr>
		</table>
		<input type='submit' name='submit' class=\"form-submit\" value='"._SEND."'>
		<input name='op' value='save_configs' type='hidden'>
		<input name='return_op' value='mtsn_admin' type='hidden'>
		<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
		<input name='log_message' value='"._MTSN_LOG."' type='hidden'>
		</form>";
		$contents .= CloseAdminTable();
		$contents .= "<br>";
		$contents = $hooks->apply_filters("mtsn_admin", $contents);

		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\">"._ATACKS_ARCHIVE."</div><br>
		<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"product-table\">
		<tr>
		<th class=\"table-header-repeat line-left\" style=\"text-align:center;\"><a>"._HOST."</a></th>
		<th class=\"table-header-repeat line-left\" style=\"text-align:center;\"><a>"._IP_ADDRESS."</a></th>
		<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:180px;\"><a>"._DATE."</a></th>
		<th class=\"table-header-repeat line-left\" style=\"text-align:center;\"><a>"._ATTACK_TYPE."</a></th>
		<th class=\"table-header-repeat line-left\" style=\"text-align:center;width:200px;\"><a>"._ADD_TO_BANED_LIST."</a></th>

		</tr>";
		$entries_per_page = 20;
		$total_rows = 0;
		$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
		$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
		$link_to = "".$admin_file.".php?op=mtsn_admin";
		$result = $db->query("SELECT *, (SELECT COUNT(id) FROM ".MTSN_TABLE.") AS total_rows FROM ".MTSN_TABLE." ORDER BY id DESC LIMIT ?, ?", [$start_at, $entries_per_page]);
		
		if($db->count() > 0)
		{
			foreach($result as $row)
			{
				$id			= intval($row['id']);
				$total_rows	= intval($row['total_rows']);
				$title		= stripslashes($row['server']);
				$ip			= stripslashes($row['ip']);
				$time		= stripslashes($row['time']);
				$method		= htmlentities($row['method']);
				$method2	= ($method);
				$date		= nuketimes($time);
				$time		= date("h:i:s",$time);
				$method		= substr($method,0,20);
				
				$contents .= "<tr>
					<td align=\"center\">$title</td>
					<td align=\"center\"><a href = 'http://www.whois.sc/$ip'>$ip</a></td>
					<td align=\"center\">$date&nbsp;&nbsp;&nbsp;$time</td>
					<td dir='ltr' align=\"center\">$method</td>
					<td align=\"center\"><a href='".$admin_file.".php?op=ip_ban_page&ipaddress=$ip'title=\""._IP_BANNING."\" class=\"table-icon icon-1 info-tooltip\"></td>
				</tr>";
			}
		}
		if($total_rows > $entries_per_page)
		{
			$contents .= "
			<tr>
			<td valign=\"top\" align=\"center\" colspan=\"5\" style=\"border:0\">
			<div id=\"pagination\" class=\"pagination\">";
			$contents .= admin_pagination($total_rows, $entries_per_page, $current_page, $link_to);
			$contents .= "</div></td></tr>";
		}
		$contents .= "</table>";
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	/*function set_config($config_fields)
	{
	  global $db, $admin_file;
		if(isset($config_fields))
		{
			foreach($config_fields as $key => $val){
				if($key == "mtsn_gfx_chk" || $key == "mtsn_search_skipwords")
				{
					$val = ($val != "") ? implode(",", $val):"";
				}
				$query_set[] = "$key = '$val'";
			}
			$query_set = implode(", ", $query_set);
			
			die("UPDATE ".CONFIG_TABLE." SET $query_set");
		}
		cache_system('nuke_config');
		add_log("ویرایش اطلاعات بخش امنیتی", 1);
		Header("Location: ".$admin_file.".php?op=mtsn_admin");
	}*/

	function ip_ban_page($id=0,$ipsearch=array(), $ipsearch2='', $ipaddress='')
	{
		global $db, $admin_file, $hooks, $nuke_configs;

		$result = $db->table(MTSN_IPBAN_TABLE)
						->order_by(['id' => 'ASC'])
						->select();
						
		$nuke_mtsn_ipban_cacheData = array();
		if($db->count() > 0)
		{
			foreach($result as $row)
				$nuke_mtsn_ipban_cacheData[$row['id']] = $row;
		}
		
		$id = intval($id);
		
		$reason = '';
		$status = 0;
		$expire = 0;
		
		$pagetitle = _MTSNADMIN;

		if($id != 0)
		{
			$ipaddress = (isset($nuke_mtsn_ipban_cacheData[$id])) ? $nuke_mtsn_ipban_cacheData[$id]['ipaddress']:'';
			$reason = (isset($nuke_mtsn_ipban_cacheData[$id])) ? $nuke_mtsn_ipban_cacheData[$id]['reason']:'';
			$status = (isset($nuke_mtsn_ipban_cacheData[$id])) ? $nuke_mtsn_ipban_cacheData[$id]['status']:0;
			$expire = (isset($nuke_mtsn_ipban_cacheData[$id])) ? $nuke_mtsn_ipban_cacheData[$id]['expire']:0;
			$expire = ($expire != 0) ? nuketimes($expire, false, false, false, 1):0;
			$pagetitle .= " - "._EDIT_IP." : <span dir=\"ltr\">$ipaddress</span>";
		}
		$hooks->add_filter("set_page_title", function() use($pagetitle){return array("ip_ban_page" => $pagetitle);});
		
		$status_sel1 = ($status == 1) ? "selected":"";
		$status_sel2 = ($status == 0) ? "selected":"";
		
		if(isset($ipsearch2) && $ipsearch2 != "")
		{
			$ipsearch = base64_decode($ipsearch2);
		}
		$contents = '';
		$contents .= GraphicAdmin();
		
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><b>MTSN V ".$nuke_configs['mtsn_version']."</b></div>
		<br /><p align='center'>
		[ <a href='".$admin_file.".php?op=mtsn_admin'>"._MTSNADMIN."</a> | <a href='".$admin_file.".php?op=ip_ban_page'>"._IP_PART."</a> ]
		</p><br /><br />";
		$contents .= CloseAdminTable();
		
		$contents .= OpenAdminTable();
		$contents .= "<div class=\"text-center\"><font class=\"title\"><b>"._IP_ADDRESS_BAN."</b></font></div>";
		$contents .= CloseAdminTable();
		
		$contents .= OpenAdminTable();
		$contents .= "<div align=\"center\">
		<form action='".$admin_file.".php' method='post'>
		<table width=\"100%\" class=\"product-table no-border id-form\">
			<tr>
				<th>IP</th>
				<td>
					<input type='text' class=\"inp-form-ltr\" name='ipaddress' value='$ipaddress' size='50'>
					&nbsp; <span id=\"banlist_help\" style=\"cursor:pointer;color:#ff0000;\">راهنما</span>
				</td>
			</tr>
			<tr>
				<th>"._REASON."</th>
				<td>
					<input class=\"inp-form\" type='text' name='reason' value='$reason' size='50' maxlength='255'>
				</td>
			</tr>
			<tr>
				<th>"._STATUS."</th>
				<td>
					<select name=\"status\" class=\"styledselect-select\">
						<option value=\"1\" $status_sel1>"._ALLOWED."</option>
						<option value=\"0\" $status_sel2>"._DISALLOWED."</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>"._EXPIRE_DATE."</th>
				<td>
					<input class=\"inp-form-ltr calendar\" type='text' name='expire' value='$expire' size='50' maxlength='255'>
					".bubble_show(_ZERO_MEANS_UNLIMITED)."
				</td>
			</tr>
			<tr>
				<td colspan=\"2\">
					<input type='submit' value='"._SEND."' class=\"form-submit\">
				</td>
			</tr>
		</table>
		<div id=\"help\" style=\"display:none;\">
			<pre style=\"direction:ltr;text-align:left;font-size:15px;font-family:Arial;line-height:21px;\">#   legal range formats are:
			#
			#       IPv4 -
			#       255.255.255.255                 Single address
			#       255.255.255.255/16              CIDR Mask
			#       255.255.255.255/255.255.0.0     address w/mask
			#       255.255.*.*                     wildcards
			#       255.255.255.0-255.255.255.255   low to high address
			#
			#       IPv6 -
			#       2001:0db8:85a3:0042:1000:8a2e:0370:7334     single address
			#       2001:0db8:85a3:0042:1000:8a2e:0370:7334/64  CIDR Mask
			#       address w/mask
			#       2001:0db8:85a3:0042:1000:8a2e:0370:7334/ffff:ffff:ffff:ffff::
			#       low to high address
			#       2001:0db8:85a3:0042:1000:8a2e:0370:7000-2001:0db8:85a3:0042:1000:8a2e:0370:7fff</pre>
		</div>
		<script>
		$(\"#banlist_help\").on('click', function(){
			$(\"#help\").dialog({
				title: '"._IPRANGE_FULL_QUIED."',
				resizable: false,
				minHeight: 500,
				width: 800,
				modal: true,
				closeOnEscape: true,
				close: function(event, ui)
				{
					$(this).dialog('destroy');
				}
			});
		});
		</script>
		<input type='hidden' name='op' value='addnewip'><input type='hidden' name='edited_id' value='$id'><input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> </form></div>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/calendar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-ar.js\" type=\"text/javascript\"></script>
		<script src=\"includes/Ajax/jquery/datepicker/js/jquery.ui.datepicker-cc-fa.js\" type=\"text/javascript\"></script>";
		$contents .= CloseAdminTable();
		if (!empty($nuke_mtsn_ipban_cacheData))
		{
			$ipsearch_parts = (isset($ipsearch) && !empty($ipsearch)) ? $ipsearch:'';
			$contents .= "<br>";
			$contents .= OpenAdminTable();
			$contents .= "
			<div class=\"text-center\"><font class=\"title\"><b>"._IPBANNED."</b></font><br><br></div>
			<div align=\"center\"><table border=\"0\" width=\"100%\" class=\"product-table\" id=\"baned-ips\">
			<tr>
				<td colspan=\"5\">
					<form action=\"".$admin_file.".php?op=ip_ban_page\" method=\"post\">
						<b>"._IP_SEARCH." :</b> 
						<span class=\"banned_ips\">
							<input type='text' class=\"inp-form\" name='ipsearch' value='$ipsearch_parts' />
						</span>
						&nbsp; &nbsp;
						<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
						<input type='submit' value='"._SEND."' class=\"form-submit\">
					</form>
				</td>
			</tr>
			</table>
			<form action=\"".$admin_file.".php?op=ip_ban_page\" method=\"post\">
			<table border=\"0\" width=\"100%\" class=\"product-table\" id=\"baned-ips\">
			<tr>
				<th class=\"table-header-repeat line-left\">"._IP."</th>
				<th class=\"table-header-repeat line-left\" style=\"width:150px\">"._IP_BANNER."</th>
				<th class=\"table-header-repeat line-left\" style=\"width:80px\">"._STATUS."</th>
				<th class=\"table-header-repeat line-left\" style=\"width:300px\">"._REASON."</th>
				<th class=\"table-header-repeat line-left\" style=\"width:180px\">تاریخ</th>
				<th class=\"table-header-repeat no-padding\" style=\"width:120px\"><input type=\"checkbox\" data-label=\""._OPERATION."\" class=\"styled select-all\" data-element=\"#baned-ips\"></th>
			</tr>";
			$nuke_mtsn_ipban_cacheData = array_reverse($nuke_mtsn_ipban_cacheData, true);
			
			$total_rows = sizeof($nuke_mtsn_ipban_cacheData);
			$entries_per_page = 20;

			$current_page = (empty($_GET['page'])) ? 1 : $_GET['page'];
			$start_at  = ($current_page * $entries_per_page) - $entries_per_page;
			$link_to = "".$admin_file.".php?op=ip_ban_page".((isset($ipsearch) && !empty($ipsearch)) ? "&ipsearch2=".base64_encode($ipsearch)."":"");

			$nuke_mtsn_ipban_cacheData = array_slice($nuke_mtsn_ipban_cacheData,$start_at,$entries_per_page, true);
			
			foreach($nuke_mtsn_ipban_cacheData as $id => $nuke_banned_ip_info){
				if(isset($ipsearch) && !empty($ipsearch))
					if(!stristr($nuke_banned_ip_info['ipaddress'], $ipsearch))
						continue;
						
				$nuke_banned_ip_info['reason'] = filter($nuke_banned_ip_info['reason'], "nohtml");
				$nuke_banned_ip_info['blocker'] = filter($nuke_banned_ip_info['blocker'], "nohtml");
				$nuke_banned_ip_info['status'] = ($nuke_banned_ip_info['status'] == 0) ? _DISALLOWED:_ALLOWED;
				$contents .= "<tr>
					<td align='center' dir=\"ltr\">&nbsp;".$nuke_banned_ip_info['ipaddress']."</td>
					<td align='center' nowrap>&nbsp;".$nuke_banned_ip_info['blocker']."&nbsp;</td>
					<td align='center' nowrap>&nbsp;".$nuke_banned_ip_info['status']."&nbsp;</td>
					<td align='center'>&nbsp;".$nuke_banned_ip_info['reason']."&nbsp;</td>
					<td align='center' nowrap>&nbsp;"._START." : ".nuketimes($nuke_banned_ip_info['time'], false, false, false, 1)."<br />"._END." : ".nuketimes($nuke_banned_ip_info['expire'], false, false, false, 1)."&nbsp;</td>
					<td align='center'>
						<a href=\"".$admin_file.".php?op=ip_ban_page&id=$id\" class=\"table-icon icon-1 info-tooltip\" title=\""._EDIT."\"></a>
						<a href=\"".$admin_file.".php?op=deleteip&id=$id&csrf_token="._PN_CSRF_TOKEN."\" class=\"table-icon icon-5 info-tooltip\" title=\""._UNBAN."\" onclick=\"return confirm('"._UNBAN_CONFIRM."');\"></a>
						<input type=\"checkbox\" class=\"styled\" name=\"baned_ip_list[]\" value=\"$id\" />
					</td>
				</tr>";
			}
			$contents .= "
			<tr>
				<td colspan=\"6\" align=\"center\">
					<input type=\"submit\" value=\""._DELETE_SELECTED."\" onclick=\"return confirm('"._UNBANS_CONFIRM."');\" />
				</td>
			</tr>";
			if($total_rows > $entries_per_page){
				$contents .="<tr>
					<td valign=\"top\" align=\"center\" colspan=\"6\" style=\"border:0\">
						<div id=\"pagination\" class=\"pagination\">";
						$contents .= admin_pagination($total_rows, $entries_per_page, $current_page, $link_to);
						$contents .="</div>
					</td>
				</tr>";
			}
			$contents .="
			<input type=\"hidden\" name=\"op\" value=\"deleteip\" />
			<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
			</form></table></div>";
			$contents .= CloseAdminTable();
		}
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	function deleteip($id, $baned_ip_list)
	{
		csrfProtector::authorisePost(true);
		global $db, $admin_file, $hooks;
		$ips = '';
		
		$hooks->do_action("deleteip_before", $id, $baned_ip_list);
		
		if(is_array($baned_ip_list) && !empty($baned_ip_list))
		{
			$db->table(MTSN_IPBAN_TABLE)
				->in('id', $baned_ip_list)
				->delete();
				
			$ips = implode(", ", $baned_ip_list);
		}
		else
		{
			$id = intval($id);
			$db->table(MTSN_IPBAN_TABLE)
				->where('id', $id)
				->delete();
			$ips = $id;
		}
		$hooks->do_action("deleteip_after", $id, $baned_ip_list, $ips);
		cache_system("nuke_mtsn_ipban");
		add_log(sprintf(_DELETE_BANED_IP, $ips), 1);
		Header("Location: ".$admin_file.".php?op=ip_ban_page");
	}
	
	function clearallip()
	{
		global $db, $admin_file, $hooks;
		$db->query("TRUNCATE TABLE  ".MTSN_IPBAN_TABLE."");
		$hooks->do_action("clearallip_after", $id, $baned_ip_list, $ips);
		cache_system("nuke_mtsn_ipban");
		add_log(_DELETE_ALLBANED_IP, 1);
		Header("Location: ".$admin_file.".php?op=ippage");
		include("footer.php");
	}

	function addnewip($ipaddress, $reason, $status, $expire, $edited_id)
	{
		global $db, $aid, $hooks, $admin_file, $visitor_ip;
		
		$hooks->add_filter("set_page_title", function(){return array("addnewip" => _ADD_IP);});
		$contents = '';
		$contents .= GraphicAdmin();
		$contents .= OpenAdminTable();
		$blocker = $aid;
		
		$ipaddress_check = adv_filter($ipaddress, array(),array('required','valid_ip'));
		
		$validation = $ipaddress_check[0];
		$ipaddress = $ipaddress_check[1];
		if($validation == 'error')
			$contents .= "<div class=\"text-center\">$ipaddress"._GOBACK."</div>";
		elseif($ipaddress == '')
			$contents .= "<div class=\"text-center\">"._IPMUSTHAVEVALUE."<br />"._GOBACK."</div>";
		elseif ($ipaddress == $visitor_ip)
		{
			$contents .= "<div class=\"text-center\">Is Your IP!</div>";
			$contents .= "<br><div class=\"text-center\"><a href = '$admin_file.php?op=ip_ban_page'>"._GOBACK_TEXT."</a></div>";
		}
		else
		{
			$hooks->do_action("addnewip_before", $ipaddress, $reason, $status, $expire, $edited_id);
			$expire = ($expire != 0) ? to_mktime($expire):0;
			if($edited_id > 0)
			{
				$db->table(MTSN_IPBAN_TABLE)
					->where('id', $edited_id)
					->update([
						'ipaddress' => $ipaddress,
						'reason' => $reason,
						'expire' => $expire,
						'status' => $status,
					]);
				
				add_log(sprintf(_EDIT_BANEDIP, $ipaddress), 1);
			}
			else
			{
				$time=_NOWTIME;
				$db->table(MTSN_IPBAN_TABLE)
					->insert([
						'blocker' => $blocker,
						'ipaddress' => $ipaddress,
						'reason' => $reason,
						'time' => $time,
						'expire' => $expire,
						'status' => $status,
					]);
				add_log(sprintf(_ADD_BANEDIP, $ipaddress), 1);
			}
			
			$hooks->do_action("addnewip_after", $ipaddress, $reason, $status, $expire, $edited_id);			
			
			cache_system("nuke_mtsn_ipban");
			header("Location: ".$admin_file.".php?op=ip_ban_page");
		}
		$contents .= CloseAdminTable();
		
		include("header.php");
		$html_output .= $contents;
		include("footer.php");
	}

	$id = (isset($id)) ? intval($id):0;
	$edited_id = (isset($edited_id)) ? intval($edited_id):0;
	$status = (isset($status)) ? intval($status):0;
	$expire = (isset($expire)) ? $expire:0;
	$reason = (isset($reason)) ? filter($reason, "nohtml"):'';
	$ipsearch2 = (isset($ipsearch2)) ? filter($ipsearch2, "nohtml"):'';
	$config_fields = (isset($config_fields)) ? $config_fields:array();
	$ipsearch = (isset($ipsearch)) ? filter($ipsearch, "nohtml"):'';
	$ipaddress = (isset($ipaddress)) ? filter($ipaddress, "nohtml"):'';
	$baned_ip_list = (isset($baned_ip_list)) ? $baned_ip_list:array();
	
	switch($op)
	{
		default:
			mtsn_admin();
		break;
		case "set_config":
			set_config($config_fields);
		break;
		case "ip_ban_page":
			ip_ban_page($id, $ipsearch, $ipsearch2);
		break;
		case "addnewip":
			addnewip($ipaddress, $reason, $status, $expire, $edited_id);
		break;
		case "clearallip":
			clearallip();
		break;
        case "deleteip":
			deleteip($id, $baned_ip_list);
		break;
	}
}
else
{
	header("location: ".$admin_file.".php");
}
?>