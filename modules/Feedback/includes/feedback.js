function phpnukeMap(){var a=document.getElementById(feedback_data.mapid),b={center:new google.maps.LatLng(feedback_data.lat,feedback_data.lng),mapTypeId:google.maps.MapTypeId.ROADMAP,zoom:feedback_data.zoom,panControl:feedback_data.panControl,zoomControl:feedback_data.zoomControl,zoomControlOptions:{style:google.maps.ZoomControlStyle.DEFAULT},mapTypeControl:feedback_data.mapTypeControl,scaleControl:feedback_data.scaleControl,streetViewControl:feedback_data.streetViewControl,overviewMapControl:feedback_data.overviewMapControl,rotateControl:feedback_data.rotateControl},c=new google.maps.Map(a,b),d=new google.maps.Marker({position:b.center,icon:feedback_data.marker_icon,animation:google.maps.Animation.BOUNCE});d.setMap(c),google.maps.event.addListener(d,"click",function(){var a=new google.maps.InfoWindow({content:feedback_data.infowindow});a.open(c,d)})} $(document).ready(function(){$("#feedback_submit").click(function(a){$.validate({form:"#feedback_form",modules:"security",onSuccess:function($form){$("#feedback_response").html('<span class="ajax-loader"></span>');var b={};b.sender_name=$("#feedback_name").val(),b.sender_email=$("#feedback_email").val(),b.subject=$("#feedback_subject").val(),b.message=$("#feedback_message").val(), b.g_recaptcha_response = $("#g-recaptcha-response").val() ,b.security_code=$("#security_code_feedback").val(),b.feedback_dept=$("#feedback_dept").val(),b.custom={},$(feedback_data.custom_data).each(function(a,c){b.custom[c]=$("#feedback_"+c).val()}),$.post(phpnuke_url+"index.php?modname=Feedback",{submit:"ok",feedback_fields:b,csrf_token:pn_csrf_token},function(b){b="object"===typeof b?b:JSON.parse(b),$("#feedback_response").html('<div class="alert alert-'+b.status+' fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+b.message+"</div>")});return false;}});});$("#reply_feedback_submit").click(function(a){var b=$("#feedback_reply_message").val(),c=$(this).data("fid"),d=$(this).data("adminfile");$("#reply_feedback_submit").hide(),$.post(d+".php",{op:"reply_feedback_pm",fid:c,submit:"ok",feedback_reply_message:b,csrf_token:pn_csrf_token},function(b){b="object"===typeof b?b:JSON.parse(b),"error"==b.status?alert(b.message):$(".chat-history").append('<li style="list-style:none;"><div class="message-data"><span class="message-data-name">'+b.sender_name+'</span><span class="message-data-time">'+b.time+'</span><span class="clearfix"></span></div><div class="message my-message align-'+b.direction+'">'+b.message+"</div></li>"),a.preventDefault(),$("#reply_feedback_submit").show()})})});