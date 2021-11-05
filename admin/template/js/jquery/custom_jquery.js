function custom_jquery(nuke_options)
{
	var options = {
		reload : false,
		nuke_date : 1,
		nuke_lang : 'farsi'
	};

	if(nuke_options) {
		$.extend(options, nuke_options);
	};
		
	// 1 - START DROPDOWN SLIDER SCRIPTS ------------------------------------------------------------------------
	if(!options.reload)
	{
		$(".showhide-account").click(function ()
		{
			$(".account-content").slideToggle("fast");
			$(this).toggleClass("active");
			return false;
		});

		$(".action-slider").click(function ()
		{
			var pagenumber_id = $(this).attr('rel');
			$("#actions-box-slider"+pagenumber_id).slideToggle("fast");
			$(this).toggleClass("activated");
			return false;
		});
		
		$("body").click(function ()
		{
			$(".actions-box-slider").slideUp("fast");
		});

		//  END ----------------------------- 1

		// 2 - START LOGIN PAGE SHOW HIDE BETWEEN LOGIN AND FORGOT PASSWORD BOXES--------------------------------------
		$(".forgot-pwd").click(function ()
		{
			$("#loginbox").hide();
			$("#forgotbox").show();
			return false;
		});

		$(".back-login").click(function ()
		{
			$("#loginbox").show();
			$("#forgotbox").hide();
			return false;
		});
		// END ----------------------------- 2

		// 3 - MESSAGE BOX FADING SCRIPTS ---------------------------------------------------------------------
		$(".message-close").click(function ()
		{
			$(this).parent().fadeOut("slow");
		});
		// END ----------------------------- 3

		// 4 - TABLE ROW BACKGROUND COLOR CHANGES ON ROLLOVER -----------------------------------------------------------------------
		$('.product-table tr').hover(function ()
		{
			if($(this).parent().parent().hasClass('no-hover')) return;
			$(this).addClass('activity-blue');
		},
		function ()
		{
			$(this).removeClass('activity-blue');
		});
		// END -----------------------------  4
		
		$(".admin_menu_toogle").on('click', function()
		{
			$('.admin_menu_icons').slideToggle();
		});
		
		$(document).bind("click", function (e)
		{
			if(e.target.id == "showhide-account")
				return;
			$(".account-content").slideUp();
		});
	
	}
	
	$("body .styledselect-select").each(function()
	{
		if($(this).outerWidth() == 0)
		{
			$(this).css({'width':200})
		}
	});
		
	$("body .styledselect-select").select2({
		dir: ((options.nuke_date == 3) ? 'ltr':'rtl')
	});
	
	$.ui.dialog.prototype._allowInteraction = function (e)
	{
		return !!$(e.target).closest('.ui-dialog, .ui-datepicker, .select2-dropdown').length;
	};
	
	//$('.styledselect').selectbox({ inputClass: 'selectbox_styled' });
	
	$('body input.file_1').filestyle({ 
		image: 'admin/template/images/forms/choose_file.gif',
		imageheight : 30,
		imagewidth : 78,
		width : 300
	});
	$('body input.file_2').filestyle({ 
		image: 'admin/template/images/forms/upload_file.gif',
		imageheight : 30,
		imagewidth : 78,
		width : 140
	});
	
	$('body a.info-tooltip ').mytooltip({
		track: true,
		delay: 0,
		fixPNG: true, 
		showURL: false,
		showBody: ' - ',
		top: -35,
		left: 5
	});
	
	
	$('body .tag-input').select2({
		tags: true
	});
	
	var inputs = $('body input.styled:not(.disabled)').each(function()
	{
		$(this).prettyCheckable({labelPosition:'left'});
	});

		
	$('body .select-all, body .prettycheckbox').on('click', function()
	{
		if($(this).find('.select-all').length > 0 || $(this).attr('class') == 'select-all')
		{
			var input_type = $(this).find('input').attr('type');
		
			var input_checked = this.checked;
			
			if(input_type == 'checkbox')
			{
				$(this).attr('data-element', $(this).find('input').attr('data-element'));
				input_checked = $(this).find('.select-all').is(':checked');
			}

			var el = (typeof $(this).attr('data-element') != 'undefined') ? $(this).attr('data-element'):'body';

			if(input_checked)
			{
				$(el+' input[type="checkbox"]').prettyCheckable('check');
			}
			else
			{
				$(el+' input[type="checkbox"]').prettyCheckable('uncheck');
			}	
		}			
	});
	
	tb_init('body a.thickbox, area.thickbox, input.thickbox');//pass where to apply thickbox
	imgLoader = new Image();// preload image
	imgLoader.src = tb_pathToImage;
	
	/*
	$(document).bind("click", function (e)
	{
		if (e.target.id != $(".action-slider").attr("class")) $(".actions-box-slider").slideUp();
	});*/
	
	if($(".calendar").length > 0)
	{
		$(".calendar").each(function()
		{
			var id = $(this).attr('id');
			var data_date = $(this).attr('data-date');
			if (typeof data_date !== typeof undefined && data_date !== false)
			{
				var lang = (data_date == 1) ? "fa":((data_date == 2) ? "ar":"");
				var cal_class = (data_date < 3) ? " ui-datepicker-rtl":"";
			}
			else
			{
				var lang = ((options.nuke_date == 3) ? '':((options.nuke_date == 1) ? 'fa':((options.nuke_date == 2) ? 'ar':'')));
				var cal_class = (options.nuke_date == 3) ? "":" ui-datepicker-rtl";
			}
			
			$(this).datepicker({
				dateFormat: 'yy/mm/dd',
				changeMonth: true,
				changeYear: true,
				showOtherMonths: true,
				regional: lang
			}).datepicker('widget').wrap('<div class="ll-skin-latoja'+cal_class+'"/>');
		});
	}
	
	if($('.color-picker').length > 0)
	{
		$('.color-picker').each( function()
		{
			$(this).minicolors({
				control: $(this).attr('data-control') || 'hue',
				defaultValue: $(this).attr('data-defaultValue') || '',
				format: $(this).attr('data-format') || 'hex',
				keywords: $(this).attr('data-keywords') || '',
				inline: $(this).attr('data-inline') === 'true',
				letterCase: $(this).attr('data-letterCase') || 'lowercase',
				opacity: $(this).attr('data-opacity'),
				input_size: $(this).attr('size') || 7,
				position: $(this).attr('data-position') || 'bottom left',
				swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
				change: function(hex, opacity)
				{
					var log;
					try {
						log = hex ? hex : 'transparent';
						if( opacity ) log += ', ' + opacity;
						console.log(log);
					} catch(e) {}
				},
				theme: 'default'
			});

		});
	}
	
	$(".simple-code-editor").on('keydown', function(e)
	{ 
		var keyCode = e.keyCode || e.which; 
		if (keyCode == 9)
		{ 
			e.preventDefault(); 
			var code_editor_id = $(this).attr('id');
			var caretPos = document.getElementById(code_editor_id).selectionStart;
			var caretPos2 = document.getElementById(code_editor_id).selectionEnd;
			if(caretPos2 > caretPos)
				$(this).insertAtCursor("");
			$(this).setCursorPosition(caretPos);
			$(this).insertAtCursor("\t");
			/*var textAreaTxt = $(this).val();
			var txtToAdd = "\t";
			$(this).val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );*/

		} 
	});	
}

(function($) {
    $.fn.add_field = function(options)
	{
        var add_field_settings = {
            maxField : 20,
            addButton : $('.add_button'),
            remove_button : '.remove_button',
            fieldHTML : '',
            x : 1,
        };
                
        if(options) {
            $.extend(add_field_settings, options);
        };
				
		var self = this;
		$(add_field_settings.addButton).click(function(e){
			e.preventDefault();
			if(add_field_settings.x < add_field_settings.maxField)
			{
				$(self).append(add_field_settings.fieldHTML.replace(/\{X\}/g,add_field_settings.x)+"\n");
				add_field_settings.x++; //Increment field counter
				if($(this).find('.styledselect-select'))
				{
					$(this).parent().parent().find('.styledselect-select').select2({
						dir: ((options.nuke_date == 3) ? 'ltr':'rtl')
					});
				}
				custom_jquery();
			}
		});
		$(self).on('click', add_field_settings.remove_button, function(e){
			e.preventDefault();
			$(this).parent('div').remove();
			//add_field_settings.x--;
		});
    };
})(jQuery);


function loadFromCookie(name)
{
	if ( $.cookie(name) != null )
	{
		return $.cookie(name);
	}
	else 
	{
		return null;
	}	
}

function admin_overlay_loading(mode)
{
	if ( mode == 'load' )
	{
		$("body").append('<div id="admin_overlay_loading"><div class="loading">لطفاً منتظر بمانيد</div></div>');
	}
	else 
	{
		$("#admin_overlay_loading").remove();
	}	
}

function echo_message(response, loading, destroy, timeout)
{
	var add_class = 'in_loading';
	timeout = (typeof timeout == 'undefined') ? 3000 : timeout;
	var message_html = '<div class="loading">plaease wait ...</div>';
	
	if(destroy)
		$("#update_result").attr('class','').slideToggle(100);
		
	if($("#update_result").css('display') == 'none' || $("#update_result").hasClass('in_loading'))
	{
		if(!loading && response.status != '' && !destroy)
		{
			add_class = response.status;
			message_html = '<img src="images/icon_'+((response.status == 'success') ? 'true':'false')+'.png" width="30px" height="30px" align="absmiddle" /> '+response.message;
			$("#update_result").removeClass('in_loading');
			$("#update_result").css({'display':'none'});
			$("#update_result").addClass(add_class).html(message_html).slideToggle(100);
		}
		else
		{
			if(!destroy)
				$("#update_result").attr('class', add_class).html(message_html).css({'display':'block'});
		}
		
		if(!loading && response.status != '')
		{
			setTimeout(function(){
				$("#update_result").removeClass(add_class).slideToggle(100);
			},timeout);
		}
	}
}