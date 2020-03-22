var media_settings = {};
(function($) {
    $.fn.medias = function(options)
	{
        var settings = {
            medias_id : 'medias',
            admin_file : 'admin',
            default_folder : '',
            image_types : ['jpg','JPG','jpeg','JPEG','jpe','JPE','png','PNG','gif','GIF','bmp','BMP'],
            width : 250,
            ckeditor : 'false'
        };
                
        if(options) {
            $.extend(settings, options);
        };
		
		$.extend(media_settings, settings);
		 
		if(media_settings.default_folder == '')
		{
			return;
		}
		
		var self = this;
		$(self).html( '<ul class="filetree start"><li class="folder collapsed"><a href="#" rel="'+media_settings.default_folder+'">'+media_settings.default_folder+'</a></li></ul>' );
		
		//getfilelist( $(self) , media_settings.default_folder );
		getfile( media_settings.default_folder, 1 );
		
		var url = location.href;
		var CKEditorFuncNum = url.match(/CKEditorFuncNum=([0-9]+)/) ? url.match(/CKEditorFuncNum=([0-9]+)/)[1] : null;
		$( self ).on('click', 'li a', function()
		{
			var entry = $(this).parent();
					
			var this_dir = escape( $(this).attr('rel') );
				
			if( entry.hasClass('collapsed') )
			{
				entry.find('ul').remove();
				//$.each(entry.parent().find('li'), function(){
					$('.filetree > li').removeClass('expanded').addClass('collapsed');
				//});
				getfilelist( entry, this_dir);
				entry.removeClass('collapsed').addClass('expanded');
				getfile( this_dir, 1 );
				$('#uploadto').attr('href', media_settings.admin_file+'.php?op=media_upload&upload_dir='+this_dir+((media_settings.ckeditor == 'true') ? '&ckeditor='+media_settings.ckeditor+'':'')+((CKEditorFuncNum !== null) ? '&CKEditorFuncNum='+CKEditorFuncNum+'':''));
				$('#upload_path').html(this_dir);
			}
			else
			{
				//getfile( this_dir );
				entry.find('ul').slideUp({ duration: 500, easing: null });
				entry.removeClass('expanded').addClass('collapsed');
			}
			return false;
		});
    };
	
    $.fn.medias_pagination = function()
	{
		$( this ).on('click', '.media_browser', function() {
			var page = $(this).attr('data-page');
			var this_dir = $(this).attr('data-dir');
			$("#"+media_settings.medias_id).find('.product-table tbody').html('');
			getfile( this_dir, page );
		});
    };
})(jQuery);

	
function getfilelist( cont, root )
{
	$( cont ).addClass( 'wait' );
		
	$.post( media_settings.admin_file+'.php', { 'op' : 'media_get_menu_files', dir: root, 'csrf_token' : pn_csrf_token }, function( data )
	{
		$( cont ).find( '.start' ).html( '' );
		$( cont ).removeClass( 'wait' ).append( data );
		if( media_settings.default_folder == root ) 
			$( cont ).find('ul:hidden').show();
		else 
			$( cont ).find('ul:hidden').slideDown({ duration: 500, easing: null });
		
	});
}

function getfile( this_dir, page)
{
	var table_rows = '';
	var img_src = '';
	$.ajax({
		type: "POST",
		url: media_settings.admin_file+'.php',
		data: {'op': 'media_get_files' , 'directory': this_dir , 'page': page, 'ckeditor' : media_settings.ckeditor, 'csrf_token' : pn_csrf_token}, success: function (responseText) {
			if(responseText == 'null')
			{
				$("#"+media_settings.medias_id).find('.product-table tbody').html('');
			}
			
			medias_data = typeof responseText === 'object' ? responseText : JSON.parse(responseText);
			var i = 1;
			var counter = 1;
			var thumb_width_height = (media_settings.ckeditor == 'true') ? 100:70;
			$.each(medias_data.files, function(index, value){
			
				if($.inArray(value.type, media_settings.image_types) != -1)
				{
					img_src = '<img src="'+this_dir+'/'+value.urlname+'" width="'+thumb_width_height+'" class="'+value.mime_type+'" height="'+thumb_width_height+'" rel="'+this_dir+'/'+value.urlname+'" style="border:1px solid #ccc;padding:1px;" title="'+value.urlname+'" />';
				}
				else
				{
					img_src = '<span class="ext_'+value.type+' '+value.mime_type+'" rel="'+this_dir+'/'+value.urlname+'" style="display:block;width:70px;height:70px;border:1px solid #ccc;padding:1px;background-position:center;" title="'+value.urlname+'"></span>';
				}
				
				if(media_settings.ckeditor == 'true')
				{
					if(counter == 1)
						table_rows += '<tr>\n\t';
					table_rows += '<td width="16.66%" align="center">'+img_src+'</td>\n\t';
					if(counter == 6)
					{
						table_rows += '</tr>\n';
						counter = 0;
					}
					counter++;
				}
				else
				//
				{
					table_rows += '<tr>\n\t';
					table_rows += '<td align="center">'+i+'</td>\n\t';
					table_rows += '<td>'+img_src+'</td>\n\t';
					table_rows += '<td align="center"><a href="'+this_dir+'/'+value.urlname+'">'+value.name+'</a></td>\n\t';
					table_rows += '<td align="center">'+value.size+'</td>\n\t';
					table_rows += '<td align="center">'+value.time+'</td>\n\t';
					table_rows += '<td><a href="'+media_settings.admin_file+'.php?op=get_media_metadata&width=1000&file_name='+this_dir+'/'+value.urlname+'" class="table-icon icon-7 info-tooltip thickbox" title="'+media_languages.view_more_info+'"></a><a href="#" class="table-icon icon-2 info-tooltip" onclick="delete_file(\''+this_dir+'/'+value.urlname+'\',\''+page+'\', 0); return false;" title="حذف"></a> <input type="checkbox" class="file_checks styled" name="file_check[]" value="'+this_dir+'/'+value.urlname+'" /></td>\n</tr>\n';
					i++;
				}
			});
			
			if(media_settings.ckeditor == '' || media_settings.ckeditor == 'false')
			{
				table_rows += '<tr>\n\t';
				table_rows += '<td align="center" colspan="6"><input type="button" onclick="delete_file(\'\',\''+page+'\',1); return false;" value="حذف انتخاب شده ها" /></td>\n';
				table_rows += '\n</tr>\n';
			}
			
			var pagination_row = '<tr><td colspan="6"><div class="pagination">'+medias_data.pagination+'</div></td></tr>';
			
			$("#"+media_settings.medias_id).find('.product-table tbody').html(/*pagination_row.replace('id="actions-box-slider"','id="actions-box-slider2"').replace('rel=""','rel="2"')+*/table_rows);
			$("#"+media_settings.medias_id).find('.product-table tfoot').html(pagination_row);
			
			if(media_settings.ckeditor == 'true')
			{
				var url = location.href;
				var CKEditorFuncNum = url.match(/CKEditorFuncNum=([0-9]+)/) ? url.match(/CKEditorFuncNum=([0-9]+)/)[1] : null;
				var CKEditorHtml5tag = url.match(/CKEditorHtml5tag=([video|audio]+)/) ? url.match(/CKEditorHtml5tag=([video|audio]+)/)[1] : 'image';

				var medias_all = document.getElementById(media_settings.medias_id).querySelectorAll('.'+CKEditorHtml5tag);
				var nr_medias_all = medias_all.length;

				if(nr_medias_all > 0) {
					for(var i=0; i<nr_medias_all; i++)
					{
						medias_all[i].addEventListener('click', function(e)
						{
							if(CKEditorFuncNum !== null) window.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, e.target.getAttribute('rel'));
							window.close();
						},
						false);
					}
				}
			}
		}
	});
}

function delete_file( filename, page, is_multi)
{
	var r = confirm(media_languages.are_you_sure);
	if(r == false)
		return false;
	
	var filenames  = [];
	if(is_multi)
	{
		
		var files_checked_counter = 0;
		$('.file_checks').each(function(){
			if(this.checked){
				filenames[files_checked_counter] = $(this).val();
				files_checked_counter++;
			}
		});
	}
	else
		filenames[0] = filename;
	
	if((filenames[0] != '' && is_multi == 1) || is_multi == 0)
	{
		$("#media_messages").html('');
		var color = '';
		
		$.ajax({
			type: "POST",
			url: media_settings.admin_file+'.php',
			data: {'op': 'delete_media' , 'file_name': filenames, 'csrf_token' : pn_csrf_token}, success: function (responseText) {
				medias_data = typeof responseText === 'object' ? responseText : JSON.parse(responseText);
				$.each(medias_data.status, function(index, value)
				{
					color = (value == 'success') ? '#1a5e04':'#ff0000';
					
					$("#media_messages").append('<span style="color:'+color+';font-weight:bold">'+medias_data.message[index]+'</span><br /><br />');
				});
					
				$("#media_messages").dialog({
					resizable: true,
					minHeight: 100,
					width: 800,
					modal: true,
					closeOnEscape: true
				});
				
				if(medias_data.emptypege == 1)
				{
					page = page-1;
				}
				
				if(page <= 0)
				{
					page = 1;
				}
				
				getfile( medias_data.this_dir, page);
			}
		});
	}
}