<?php
/**
*
* This file is part of the PHP-NUKE Software package.
*
* @copyright (c) PHP-NUKE <https://www.phpnuke.ir>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

if (!defined('BLOCK_FILE'))
{
    Header("Location: ../index.php");
    die();
}

global $db, $nuke_configs, $block_global_contents, $users_system, $custom_theme_setup;

$custom_theme_setup = array_merge_recursive($custom_theme_setup, array(
	"defer_js" => array(
		"<script type=\"text/javascript\" src=\"".$nuke_configs['nukecdnurl']."includes/Ajax/jquery/form-validator/jquery.form-validator.min.js\"></script>"
	)
));

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