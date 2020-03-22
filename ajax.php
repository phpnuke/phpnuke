<?php
/************************************************************************/
/* PHP-NUKE: Advanced Content Management System                         */
/* ============================================                         */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

@require_once("mainfile.php");
global $p, $users_system;

switch($op)
{
	case"get_unique_post_slug":
		if(is_admin())
			get_unique_post_slug($table, $id_name, $id_value, $field, $slug, $post_status, true);
	break;
	case"send_to_report_friend":

		$code_accepted = false;
		global $report_friend_form_security_code, $report_friend_form_security_code_id;
		if(extension_loaded("gd") && in_array("send_post", $nuke_configs['mtsn_gfx_chk']))
			$code_accepted = code_check($report_friend_form_security_code, $report_friend_form_security_code_id);
		else
			$code_accepted = true;

		if($code_accepted)
		{
			$mode = isset($mode) ? $mode:"friend";
			$report_friend_form_name = isset($report_friend_form_name) ? $report_friend_form_name:"";
			$report_friend_form_email = isset($report_friend_form_email) ? $report_friend_form_email:"";
			$report_friend_form_message = isset($report_friend_form_message) ? $report_friend_form_message:"";
			$report_friend_form_subject = isset($report_friend_form_subject) ? $report_friend_form_subject:"";
			$report_friend_form_message = isset($report_friend_form_message) ? $report_friend_form_message:"";
			$report_friend_form_post_title = isset($report_friend_form_post_title) ? $report_friend_form_post_title:"";
			$report_friend_form_post_link = isset($report_friend_form_post_link) ? $report_friend_form_post_link:"";
			$report_friend_form_post_id = isset($report_friend_form_post_id) ? $report_friend_form_post_id:"";
			$report_friend_form_module_name = isset($report_friend_form_module_name) ? $report_friend_form_module_name:"";		
			report_friend_form(true, $mode, $report_friend_form_post_id, $report_friend_form_post_title, $report_friend_form_module_name, $report_friend_form_subject, $report_friend_form_message, $report_friend_form_post_link, $report_friend_form_name, $report_friend_form_email);
		}
		else
		{
			$results = array(
				'status' => 'danger',
				'message' => 'کد امنيتي صحيح نمي باشد',
			);
			die(json_encode($results));
		}
	break;
	case"LastForumTopics":
			die($users_system->MTForumBlock($p));
	break;
}
	
?>