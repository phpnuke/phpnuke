var F_p=1;var busy=0;var page=1;function ChangeForumPage(e)
{if(busy==0){$("#MTFloader").html("<img src='images/ajax-loader.gif' width=\"16\" height=\"16\" alt=\"loading \" title=\"loading ...\" />");$("#MTForumBlock").fadeTo("slow",0.33);switch(e)
{case "Next":page=F_p+1;F_p++;break;case "Prev":if(F_p>1)
{page=F_p-1;F_p--}
break}
var cssObj1={'opacity':0}
$.ajax({type:"POST",url:phpnuke_url+"ajax.php",data:{'op':'LastForumTopics','p':page, 'csrf_token' : pn_csrf_token},success:function(responseText)
{$("#MTForumBlock").css(cssObj1).animate({opacity:1},{queue:!1,duration:'slow'}).html(responseText);$("#MTFloader").html('');$("#MTForumBlock").fadeTo("slow",1)}})}}