<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* Part: blocks				                                            */
/* Part Name: block-ads		                                            */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2006 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('BLOCK_FILE'))
{
    Header("Location: ../index.php");
    die();
}

function invitation_block_assets($theme_setup)
{
	global $nuke_configs;
	$theme_setup = array_merge_recursive($theme_setup, array(
		"defer_js" => array(
			"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>"
		)
	));
	return $theme_setup;
}

global $db, $nuke_configs, $users_system, $hooks;

$hooks->add_filter("site_theme_headers", "invitation_bloc_assets", 10);

$content = "
<div id=\"invitation_message\"></div>
<form action=\"".LinkToGT("index.php?modname=Users")."\" method=\"post\" id=\"invitation_form\">
<div class=\"form-group input-group text-center\">
	<input type=\"text\" class=\"form-control\" placeholder=\""._EMAIL."\" value=\"\" name=\"invited_email\" id=\"invited_email\">
	<span class=\"input-group-btn\">
		<button class=\"btn btn-default\" type=\"submit\" name=\"submit\" id=\"invitation-submit\" value=\""._SEND."\">
			<span class=\"glyphicon glyphicon-search\"></span>
		</button>
	</span>
</div>
<input type=\"hidden\" name=\"csrf_token\" value=\""._PN_CSRF_TOKEN."\" /> 
</form>
<script>
	$(document).ready(function(){
		$.validate({
			'form':'#invitation_form',
			'module':'security',
			onSuccess:function($"."form)
			{
				$('#invitation-submit').attr('disabled', true);
				var invited_email = $('#invited_email').val();
				$.post('".LinkToGT("index.php?modname=Users")."',
				{
					'op': 'send_invitation_code',			
					'invited_email': invited_email,	
					'csrf_token' : phpnuke_csrf_token
				},
				function(data, status){
					data = JSON.parse(data);
					var message = '<div class=\"alert alert-'+((data.valid) ? 'success':'danger')+'\">'+data.message+'</div>';					
					$('#invitation_message').fadeIn('1000').html(message);
					setTimeout(\"$('#invitation-submit').show('1000');$('#invitation_message').fadeOut('1000');\", 3000);
					
				$('#invitation-submit').attr('disabled', false);
				});
				return false;
			}
		});
	});
</script>";

?>