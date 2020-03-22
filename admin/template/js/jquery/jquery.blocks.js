function validate_box_id(box_id)
{
	if(box_id == 'all' ||box_id == '' || box_id.length == 0)
	{
		alert(blocks_language.enter_boxid)
		return false;
	}
	
	var duplicated_box_id = false;
	
	$(".blocks-column").each(function(){
		if(box_id == $(this).data('box_id'))
		{
			duplicated_box_id = true;
		}
	});

	if(duplicated_box_id)
	{
		alert();
		return false;
	}
	
	if(!(/^[a-zA-Z0-9\_]+$/.test(box_id)))
	{
		alert(blocks_language.boxid_in_english);
		return false;
	}
	
	return true;
}

$(document).ready(function(){
	
	$(document).on({
		mouseover: function () {
			$(this).find('.blocks-tools').fadeIn('fast');
		},
		mouseenter: function () {
			$(this).find('.blocks-tools').fadeIn('fast');
		},
		mouseleave: function () {
			$(this).find('.blocks-tools').fadeOut('fast');
		}
	}, ".blocks-sortable-item-inbox");
	
	$("body").on('click','.blocks-tools a', function(){
		var bid = $(this).closest('li').data('bid');
		var block_name = $(this).closest('li').find('.block-title').text();
		
		var box_id = $(this).closest('div').data('box_id');

		operation =(''+$(this).attr('class')+'').match(/(remove|edit|active|preview|infblock)+/g);

		var that_box = this;
		var lis = $(that_box).closest('li');

		if(operation == 'preview')
		{
			echo_message('', true, false);
			$.ajax({
				type : 'post',
				url : ''+admin_file+'.php',
				data : {'op' : 'preview_block', 'bid' : bid, 'box_id' : box_id, 'csrf_token' : pn_csrf_token},
				success : function(responseText){
					$("#blocks-dialog").html(responseText);

					$("#blocks-dialog").dialog({
						title: blocks_language.block_preview+' '+block_name+'',
						resizable: false,
						minHeight: 400,
						width: 600,
						modal: true,
						closeOnEscape: true,
						close: function(event, ui)
						{
							$(this).dialog('destroy');
							$("#blocks-dialog").html('');
							echo_message('',false,true);
						}
					});
				}
			});
		}
		
		if(operation == 'infblock')
		{
			echo_message('', true, false);
			$.ajax({
				type : 'post',
				url : ''+admin_file+'.php',
				data : {'op' : 'info_block', 'bid' : bid, 'box_id' : box_id, 'csrf_token' : pn_csrf_token},
				success : function(responseText){
					$("#blocks-dialog").html(responseText);

					$("#blocks-dialog").dialog({
						title: blocks_language.block_info+' '+block_name+'',
						resizable: false,
						minHeight: 400,
						width: 600,
						modal: true,
						closeOnEscape: true,
						close: function(event, ui)
						{
							$(this).dialog('destroy');
							$("#blocks-dialog").html('');
							echo_message('',false,true);
						}
					});
				}
			});
		}
	
		if(operation == 'remove')
		{
			var remove_confirm = confirm(blocks_language.block_delete_confirm);
			if(remove_confirm == true){
				echo_message('', true, false);
				$.ajax({
					type : 'post',
					url : ''+admin_file+'.php',
					data : {'op' : 'remove_block', 'bid' : bid, 'box_id' : box_id, 'csrf_token' : pn_csrf_token},
					success : function(responseText){
						responseText = typeof responseText === 'object' ? responseText : JSON.parse(responseText);
						if(responseText.status == 'success')
						{
							$(lis).removeClass('blocks-sortable-item-inbox').removeClass('in-expire').removeClass('future').removeClass('deactivated');
							$(lis).find('.blocks-tools').fadeOut(1);
							$(lis).remove();
							//$(lis).insertBefore('.blocks-column-full .blocks-sortable-list li:last-child');
							echo_message(responseText, false, false);
						}
						else
							echo_message(responseText, false, false);
					}
				});
			}
		}
		
		if(operation == 'active')
		{
			echo_message('', true, false);
			$.ajax({
				type : 'post',
				url : ''+admin_file+'.php',
				data : {'op' : 'change_block_status', 'bid' : bid, 'box_id' : box_id, 'csrf_token' : pn_csrf_token},
				success : function(responseText){
					responseText = typeof responseText === 'object' ? responseText : JSON.parse(responseText);
					if(responseText.status == 'success')
					{
						if(responseText.new_status == 1)
						{
							$(lis).removeClass('deactivated');
							$(lis).removeClass('future');
							$(that_box).addClass('icon-13').removeClass('icon-5');
							that_box.tooltipText = blocks_language.block_deactivate;
						}
						else
						{
							$(lis).addClass('deactivated');
							$(that_box).addClass('icon-5').removeClass('icon-13');
							that_box.tooltipText = blocks_language.block_activate;
						}
						echo_message(responseText, false, false);
					}
					else
						echo_message(responseText, false, false);
				}
			});
		}
		
		if(operation == 'edit')
		{
			echo_message('', true, false);
			$.ajax({
				type : 'post',
				url : ''+admin_file+'.php',
				data : {'op' : 'BlocksEdit', 'bid' : bid, 'box_id' : box_id, 'csrf_token' : pn_csrf_token},
				success : function(responseText){
					$("#blocks-dialog").html(responseText);

					$("#blocks-dialog").dialog({
						title: blocks_language.block_Edit+' '+block_name+'',
						resizable: false,
						minHeight: 500,
						width: 800,
						modal: true,
						closeOnEscape: true,
						close: function(event, ui)
						{
							$(this).dialog('destroy');
							$("#blocks-dialog").html('');
							echo_message('',false,true);
						}
					});
				}
			});
		}
		
		return false;
	});
	
	$("body").on('click','.toggle-box', function(){
		$(this).closest('.blocks-column, .blocks-column-full').find('.blocks-sortable-list').slideToggle(100);
		if($(this).hasClass('icon-11'))
		{
			$(this).removeClass('icon-11');
			$(this).addClass('icon-12');
		}
		else
		{
			$(this).removeClass('icon-12');
			$(this).addClass('icon-11');
		}					
		return false;
	});
	
	$("body").on('click','.remove-box', function(){
		var remove_confirm = confirm(blocks_language.box_delete_confirm);
		if(remove_confirm == true)
		{
			var remove_box_id = $(this).closest('.blocks-column, .blocks-column-full').data('box_id');
			var that_box = this;
			if(remove_box_id != 'left' && remove_box_id != 'right' && remove_box_id != 'topcenter' && remove_box_id != 'bottomcenter')
			{
				echo_message('', true, false);
				$.ajax({
					type : 'post',
					url : ''+admin_file+'.php',
					data : {'op' : 'updateweight', 'mode' : 'remove', 'box_id': remove_box_id, 'csrf_token' : pn_csrf_token},
					success: function(theResponse){
						theResponse = typeof theResponse === 'object' ? theResponse : JSON.parse(theResponse);
						if(theResponse.status == 'success')
						{
							$(that_box).parent().fadeOut(1);
							
							$(that_box).closest('.blocks-column, .blocks-column-full').find('.blocks-sortable-item').each(function(){
								$(this).removeClass('blocks-sortable-item-inbox').removeClass('in-expire').removeClass('future').removeClass('deactivated');
							});
							
							var lis = $(that_box).closest('.blocks-column, .blocks-column-full').find('.blocks-sortable-list').html();

							$(that_box).closest('.blocks-column, .blocks-column-full').remove();						
							
							$(lis).insertBefore('.blocks-column-full .blocks-sortable-list li:last-child'); 
							sortable_handle();
						}
						echo_message(theResponse, false, false);
					}
				});
			}
			else
				alert(blocks_language.box_cannot_deleted);
		}
		return false;
	});				
		
	$("body").on('click','#edit_box_id_submit', function(){
		var edit_dialog_box_id = $(this).data('box_id');
		var new_box_title = $("#"+edit_dialog_box_id+"_edit_title").val();
		var new_box_theme_location = $("#"+edit_dialog_box_id+"_edit_theme_location").val();
		var new_box_theme_priority = $("#"+edit_dialog_box_id+"_edit_theme_priority").val();
		var new_box_status = $("#"+edit_dialog_box_id+"_status").is(':checked');
		if(new_box_title.replace(' ', '_') != edit_dialog_box_id)
			if(!validate_box_id(new_box_title.replace(' ', '_'))) return false;
		
		echo_message('', true, false);
		$.ajax({
			type : 'post',
			url : ''+admin_file+'.php',
			data : {'op' : 'updateweight', 'mode' : 'edit' , 'box_id': edit_dialog_box_id, 'new_box_title': new_box_title.replace(' ', '_'), 'new_box_status': new_box_status, 'new_box_theme_location': new_box_theme_location, 'new_box_theme_priority': new_box_theme_priority, 'csrf_token' : pn_csrf_token},
			success: function(theResponse){
				theResponse = typeof theResponse === 'object' ? theResponse : JSON.parse(theResponse);
				if(theResponse.status == 'success')
				{
					$("#"+edit_dialog_box_id+"_title").html(blocks_language.box_name+' <b>'+new_box_title+'</b>');
					$("#"+edit_dialog_box_id+"_title").closest('.blocks-column').data('box_id', new_box_title);
					$("#"+edit_dialog_box_id+"_title").closest('.blocks-column').data('theme_location', new_box_theme_location);
					$("#"+edit_dialog_box_id+"_title").closest('.blocks-column').data('theme_priority', new_box_theme_priority);
					$("#"+edit_dialog_box_id+"_title").attr('id', new_box_title.replace(' ', '_')+"_title");
					if(new_box_status)
					{
						$("#"+new_box_title.replace(' ', '_')+"_title").closest('.blocks-column').find('.blocks-sortable-item').each(function(){
							$(this).addClass('deactivated');
						});
					}
				}
				echo_message(theResponse, false, false);
			}
		});
		$("#blocks-dialog").dialog('close');
	});
	
	$("body").on('click','.edit-box', function(){
	
		var edit_box_id = $(this).closest('.blocks-column').data('box_id');
		var edit_theme_location = $(this).closest('.blocks-column').data('theme-location');
		var edit_theme_priority = $(this).closest('.blocks-column').data('theme-priority');
		theme_widgets = theme_widgets;
		widget_options = '<option value="" selected>'+blocks_language.anyone+'</option>';
		$.each(theme_widgets, function(key, value)
		{
			var sel = (edit_theme_location == key) ? "selected":"";
			widget_options += '<option value="'+key+'" '+sel+'>'+value+'</option>';
		});
		
		var dialog_html = '<style>.ui-dialog {overflow: visible;}</style><br />';
		dialog_html += ''+blocks_language.box_name+' : <input type="text" class="inp-form" value="'+edit_box_id.replace('_', ' ')+'" id="'+edit_box_id+'_edit_title"> ';
		dialog_html += '<input type="button" class="form-submit" data-box_id="'+edit_box_id+'" id="edit_box_id_submit" />';
		dialog_html += '<br /><br />';
		dialog_html += ''+blocks_language.position+': <select class="styledselect-select-dialog" id="'+edit_box_id+'_edit_theme_location">'+widget_options+'</select>';
		dialog_html += '<br /><br />';
		dialog_html += ''+blocks_language.priority+' : <input type="text" class="inp-form" value="'+edit_theme_priority+'" id="'+edit_box_id+'_edit_theme_priority"> ';
		dialog_html += '<br /><br />';
		dialog_html += '<input data-label="'+blocks_language.boxdeactivate+'" type="checkbox" class="styled" id="'+edit_box_id+'_status" value="1" />';
		$("#blocks-dialog").html(dialog_html);
		
		$( ".styledselect-select-dialog" ).selectmenu({
			width: 300
		});
		
		$('#blocks-dialog input.styled:not(.disabled)').each(function() {
			$(this).prettyCheckable({labelPosition:((''+nuke_language+'' == 'farsi' || ''+nuke_language+'' == 'arabic') ? 'left':'right')});
		});
		
		$("#blocks-dialog").dialog({
			title: blocks_language.edit_boxname+' '+edit_box_id+'',
			resizable: false,
			minHeight: 200,
			width: 500,
			modal: true,
			closeOnEscape: true,
			close: function(event, ui)
			{
				$(this).dialog('destroy');
				$("#blocks-dialog").html('');
				echo_message('',false,true);
			}
		});
		
		return false;
	});
	
	$("#add_block_box").on('click', function(){
		var box_id = $("#new-box-name").val().replace(' ','_');
		
		var column_nums = $("#blocks-containment .blocks-column").length;
		
		if(!validate_box_id(box_id)) return false;
		
		var class_name = 'blocks-column fl';
		
		if(column_nums%3 == 0)
			class_name += ' first clearer';

		var new_column = '<div class="'+class_name+'" data-box_id="'+box_id+'" data-theme-location="">';
		new_column += '	<div class="blocks-box-header">';
		new_column += '		<span id="'+box_id+'_title">'+blocks_language.box_name+' <b>'+box_id.replace('_',' ')+'</b></span>';
		new_column += '		<span class="blocks-box-tools">';
		new_column += '			<a class="remove-box table-icon icon-2 info-tooltip" href="#" title="'+blocks_language.delete_box+'"></a>';
		new_column += '			<a class="edit-box table-icon icon-6 info-tooltip" href="#" title="'+blocks_language.edit_box+'"></a>';
		new_column += '			<a class="toggle-box table-icon icon-11 info-tooltip" href="#" title="'+blocks_language.showhide_box+'"></a>';
		new_column += '		</span>';
		new_column += '	</div>';
		new_column += '	<ul class="blocks-sortable-list" style="display:block;"><li></li></ul>';
		new_column += '</div>';
		
		$("#blocks-more-columns").append(new_column);
		$("#blocks-more-columns .blocks-column:last").css({'opacity':'0'}).animate({opacity: 1}, 1000 );					
		sortable_handle();
	});
	
	$("#add-html-block, #add-rss-block").on('click', function(){
		var block_type = $(this).attr('id');
		block_type = block_type.split('-');
		
		var all_box_ids = [];
		
		$('#blocks-boxes .blocks-sortable-list').each(function(){
			var box_ids = $(this).parent().data('box_id');
			if(box_ids != 'undefined' && box_ids != '' && box_ids != 'all')
			{
				all_box_ids.push(box_ids);
			}
		});
		
		$.ajax({
			type : 'post',
			url : ''+admin_file+'.php',
			data : {'op' : 'BlocksEdit', block_type : block_type[1], all_box_ids : all_box_ids, 'csrf_token' : pn_csrf_token},
			success : function(responseText){
				$("#blocks-dialog").html(responseText);

				$("#blocks-dialog").dialog({
					title: blocks_language.add_block,
					resizable: false,
					minHeight: 500,
					width: 800,
					modal: true,
					closeOnEscape: true,
					close: function(event, ui)
					{
						$(this).dialog('destroy');
						$("#blocks-dialog").html('');
						echo_message('',false,true);
					}
				});
			}
		});
		return false;
	});

	var box_id;
	var block_id;
	var inbox_update = false;
	function sortable_handle()
	{
		$('.blocks-sortable-list').sortable({
			connectWith: '.blocks-sortable-list',
			placeholder: 'blocks-placeholder',
			containment: '#blocks-containment',
			opacity: 0.75,
			start : function(){
				box_id = 'all';
			},
			stop : function(event, ui)
			{
				if(box_id == 'all')
					echo_message('', false, true);
			},
			beforeStop : function(event, ui)
			{
				echo_message('', true, false);
			},
			receive: function(event, ui)
			{
				var sourceList = (typeof ui.sender.parent().data('box_id') !== typeof undefined) ? ui.sender.parent().data('box_id'):'all';
				var targetList = $(this).parent().data('box_id');
				var weights = $(this).sortable('toArray');
				var bids_weight = [];
				var box_id = sourceList.replace(' ','_')+','+targetList.replace(' ','_');
				
				block_id = ui.item.attr('id');
				if(weights.length > 0)
				{
					$.each( weights, function( key, value )
					{
						if(value != '')
						{
							arr_block_id = value.split('-');
							bids_weight.push(arr_block_id[2]);
						}
					});
				}
				
				arr_block_id = null;
				
				$.ajax({
					type : 'post',
					url : ''+admin_file+'.php',
					data : {
						'op' : 'updateweight', 
						'mode': 'outbox', 
						'box_id': box_id, 
						'block_id': block_id, 
						'bids_weight': bids_weight,
						'csrf_token' : pn_csrf_token
					},
					success: function(theResponse)
					{
						theResponse = typeof theResponse === 'object' ? theResponse : JSON.parse(theResponse);
						
						arr_block_id = block_id.split('-');
						new_block_id = 'block-'+targetList+'-'+arr_block_id[2];
							$('#' + block_id + (($('#' + block_id + '.cloned-el').length > 0) ? '.cloned-el':'')).attr('id', new_block_id).css({'width':$('.ui-sortable-handle').width()});
							
						if(!$('#' + new_block_id).hasClass('blocks-sortable-item-inbox'))
						{
							$('#'+new_block_id).addClass('blocks-sortable-item-inbox');
						}
						echo_message(theResponse, false, false);
					}
				});
				inbox_update = false;
			},
			update: function(e,ui)
			{
				weights = $(this).sortable('toArray');
				box_id = $(this).parent().data('box_id');
				block_id = ui.item.attr('id');
				arr_block_id = block_id.split('-');
				bids_weight = [];
				
				if(!ui.sender)
				{
					if(weights.length > 0)
					{
						$.each( weights, function( key, value )
						{
							if(value != '')
							{
								arr_weights_id = value.split('-');
								if(arr_block_id[2] == arr_weights_id[2] &&arr_block_id[1] == box_id )
									inbox_update = true;
								
								bids_weight.push(arr_weights_id[2]);
							}
						});
					}
				}
				
				if(inbox_update)
				{
					$.ajax({
						type : 'post',
						url : ''+admin_file+'.php',
						data : {
							'op' : 'updateweight', 
							'mode': 'inbox', 
							'box_id': box_id.replace(' ','_'), 
							'bids_weight': bids_weight,
							'csrf_token' : pn_csrf_token
						},
						success: function(theResponse)
						{
							theResponse = typeof theResponse === 'object' ? theResponse : JSON.parse(theResponse);
							
							echo_message(theResponse, false, false);
						}
					});
					inbox_update = false;
				}
			},
		});
		$( ".blocks-draggable-item" ).draggable({
			connectToSortable: ".blocks-sortable-list",
			helper: function() {
				var helper = $(this).clone().addClass('cloned-el');
				helper.css({
					'width': $('.ui-sortable-handle').length != 0 ? $('.ui-sortable-handle').width():($('.blocks-column').length != 0 ? ($('.blocks-column').width()-30):$(this).width()), 
				});
				return helper;

			},
			revert: "invalid"
		});
		$( "ul, li" ).disableSelection();
	}
	sortable_handle();

});