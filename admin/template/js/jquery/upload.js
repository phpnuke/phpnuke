$(function(){

    var ul = $('#upload ul');
	var image_types = ['image/jpg','image/JPG','image/jpeg','image/JPEG','image/jpe','image/JPE','image/png','image/PNG','image/gif','image/GIF','image/bmp','image/BMP'];
	
    $('#drop a.selectfile').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
			$("#drop").removeClass('dragover');
			
            var tpl = $('<li class="working"><div class="preview-image"></div><div class="image-progress"><div class="progress"><div class="bar"></div ><div class="percent">0%</div ></div><div class="fileinfo"></div><div class="message"></div></div><span></span></li>');

            // Append the file name and file size
            tpl.find('.fileinfo').html('<tag class="filename">'+data.files[0].name+'</tag>')
                         .append(' &nbsp;&nbsp; <i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);
			if($.inArray(data.files[0].type, image_types) != -1){
				readURL(data, tpl);
			}
			else
			{
				var ext = data.files[0].name.split(".").pop();
				tpl.find('.preview-image').html('<div class="ext_'+ext+'"></div>');
			}
			
            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            //var jqXHR = data.submit();
			 // Automatically upload the file once it is added to the queue
			var jqXHR = data.submit();
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the progress bar and trigger a change

			var percentVal = progress + '%';
			data.context.find('.bar').width(percentVal)
			data.context.find('.percent').html(percentVal);
		
            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');
        },

        done:function(e, data){
            // Something has gone wrong!
			json = typeof data.result === 'object' ? data.result : JSON.parse(data.result);
			var status = json['status'];
			var message = json['message'];
			var file_name = json['file_name'];
			var file_url = json['file_url'];
			var downloadlink = json['downloadlink'];

			if(status == 'error'){
				data.context.find('.message').html(message);
				data.context.addClass('error');
			}
			if(status == 'success'){
				data.context.find('.message').html(message);
				data.context.find('.message').append(downloadlink);
			}
			
			if(file_name != ''){
				data.context.find('.filename').html(file_name);
				data.context.find('.preview-image').attr('data-url',file_url);
			}
			
			var url = location.href;
			var CKEditorFuncNum = url.match(/CKEditorFuncNum=([0-9]+)/) ? url.match(/CKEditorFuncNum=([0-9]+)/)[1] : null;
			var CKEditorHtml5tag = url.match(/CKEditorHtml5tag=([video|audio]+)/) ? url.match(/CKEditorHtml5tag=([video|audio]+)/)[1] : 'image';
			
			var medias_all = document.getElementById("upload").querySelectorAll('div.preview-image');
			var nr_medias_all = medias_all.length;

			if(nr_medias_all > 0) {
				for(var i=0; i<nr_medias_all; i++)
				{
					medias_all[i].addEventListener('click', function(e)
					{
						if(CKEditorFuncNum !== null) window.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, $(this).data('url'));
						window.close();
					},
					false);
				}
			}

			setTimeout(function(){
				//data.context.fadeOut('slow');
			},3000);
        }

    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

	$("#drop").on( "dragover", function() {
	  $("#drop").addClass('dragover');
	});

	$("#drop").on( "dragleave", function() {
	  $("#drop").removeClass('dragover');
	});
	
    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }	
	
    // Helper function that formats the file sizes
    function readURL(input, tpl) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                tpl.find('.preview-image').html('<img src="'+e.target.result+'" width="60" height="60" />');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

});