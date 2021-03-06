jQuery(document).ready(function () {
	"use strict";
	var options = {};
	options.ui = {
		container: "#pwd-container",
		showVerdictsInsideProgressBar: true,
		viewports: {
			progress: ".pwstrength_viewport_progress"
		}
	};
	options.common = {
		debug: true,
		onLoad: function () {
			$('#messages').text('Start typing password');
		}
	};
	$(':password').pwstrength(options);
});
var form_step = 1;

$( document ).ready( function () {
	$("body").on('click', '#resend_reset_password_code', function(event){
		event.preventDefault();
		$.post(reset_password_url,
		{
			mode: 'form',			
			resend: true,			
		},
		function(data, status){
			data = JSON.parse(data);
			if(data.status == 'danger')
			{
				form_step = 1;
				var message = '<div class="alert alert-'+data.status+'">'+data.message+'</div>';
				$('#post-message').fadeIn('1000').html(message);
				$('#reset-password-form-submit').hide(function(){
					setTimeout("$('#reset-password-form-submit').show('1000');$('#post-message').fadeOut('1000');", 3000);
				});
			}
			else
			{
				var message = '<div class="alert alert-success">کد اعتبار سنجي مجددا ارسال شد</div>';
				$('#post-message').fadeIn('1000').html(message);
				form_step = 2;
				setTimeout(function(){
					$('#modal-body').fadeIn('1000').html(data.message);
				}, 3000);
				
			}
		});
	});
	$.validate({
		form : '#reset_password_form',
		modules : 'security',
		onSuccess: function($form)
		{
			if(form_step == 1)
			{
				$.post(reset_password_url,
				{
					mode: 'form',
					reset_password_username: $('#reset_password_username').val(),
					reset_password_user_email: $('#reset_password_user_email').val(),
					security_code: $('#security_code_RESET_PASSWORD_FORM').val(),			
					security_code_id: '_RESET_PASSWORD_FORM',			
				},
				function(data, status){
					data = JSON.parse(data);
					if(data.status == 'danger')
					{
						var message = '<div class="alert alert-'+data.status+'">'+data.message+'</div>';
						$('#post-message').fadeIn('1000').html(message);
						$('#reset-password-form-submit').hide(function(){
							setTimeout("$('#reset-password-form-submit').show('1000');$('#post-message').fadeOut('1000');", 3000);
						});
					}
					else
					{
						form_step = 2;
						$('#modal-body').fadeIn('1000').html(data.message);
					}
				});
			}
			else if(form_step == 2)
			{
				$.post(reset_password_url,
				{
					mode: 'reset',
					credit_code: $('#credit_code').val(),			
				},
				function(data, status){
					data = JSON.parse(data);
					if(data.status == 'danger')
					{
						var message = '<div class="alert alert-'+data.status+'">'+data.message+'</div>';
						$('#post-message').fadeIn('1000').html(message);
						$('#reset-password-form-submit').hide(function(){
							setTimeout("$('#reset-password-form-submit').show('1000');$('#post-message').fadeOut('1000');", 3000);
						});
					}
					else
					{
						form_step = 3;
						$('#modal-body').fadeIn('1000').html(data.message);
					}
				});
			}
			else if(form_step == 3)
			{	
				$.post(reset_password_url,
				{
					mode: 'reset_password_confirm',
					new_user_password: $( "#reset_password_form" ).serializeArray(),
				},
				function(data, status){
					data = JSON.parse(data);
					var message = '<div class="alert alert-'+data.status+'">'+data.message+'</div>';
					var elem = (data.status == 'success') ? $('#modal-body'):$('#post-message');
					elem.fadeIn('1000').html(message);
					if(data.status == "success")
					{
						$('#reset-password-form-submit').hide(function(){
							setTimeout("$('.modal-footer').hide('1000');$('#post-message').fadeOut('1000');", 3000);
							top.location.href=phpnuke_url;
						});
					}
					else
					{
						var message = '<div class="alert alert-'+data.status+'">'+data.message+'</div>';
						$('#post-message').fadeIn('1000').html(message);
						$('#reset-password-form-submit').hide(function(){
							setTimeout("$('#reset-password-form-submit').show('1000');$('#post-message').fadeOut('1000');", 3000);
						});
					}
				});
				
			}
			return false;
		}
	});	

	$.validate({
		form : '#user_configs_form',
		modules : 'security',
		onSuccess: function($form)
		{
			if(($('#new_user_password').val() != '' || $('#new_user_password_cn').val() != '') && $('#user_password').val() == '')
			{
				alert('رمز عبور فعلی و رمز عبور جدید را کامل طبق خواسته سیستم وارد نمایید');
				return false;
			}
		}
	});
	
	$("#user_avatar_type").on('change', function(){
		switch($(this).val())
		{
			case"upload":
				$(".remote_avater").fadeOut(1);
				$(".gravatar_avater").fadeOut(1);
				$(".upload_avater").fadeIn(200);
			break;
			
			case"remote":
				$(".gravatar_avater").fadeOut(1);
				$(".upload_avater").fadeOut(1).find('.uploadfile').val(null);
				$(".remote_avater").fadeIn(200);
			break;
			
			case"gravatar":
				$(".remote_avater").fadeOut(1);
				$(".upload_avater").fadeOut(1).find('.uploadfile').val(null);
				$(".gravatar_avater").fadeIn(200);
			break;
		}
	} );

	$("#remove_avatar").on('click', function(){
		$("#avatar_preview").fadeOut();
		$("#remove_avatar_input").val(1);
	});
} );