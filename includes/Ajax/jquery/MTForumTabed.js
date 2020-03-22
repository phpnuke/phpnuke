/*#######################################################################
# PHP-Nuke Block: MashhadTeam Center Forum Block v.2 tabbed 		   #
# Made for PHP-Nuke 8.3                                                #
#                                                                      #
# Made by Mohsen Shareh [mohsenweb]                                    #
# Modified by iman64 http://www.phpnuke.ir                             #
# phpnukiha@yahoo.com                                				   #
#######################################################################*/

var busy = 0;
var serach_form = 0;
function ChangeTabedForumPage(e, forum_mode, F_p, search_query){
	if(F_p == '') F_p = 1;

	if(busy == 0){
		$("#MTFloader").html("<img src='"+phpnuke_cdnurl+"images/ajax-loader.gif' width=\"16\" height=\"16\" alt=\"loading \" title=\"loading ...\" />");
		$("#forum_tabs-"+forum_mode).fadeTo("slow", 0.33);
		switch(e){
			case "First":
				F_p = 1;
			break;
			case "Next":
				F_p++;
			break;
			case "Prev":
				if(F_p>1)
				{
					F_p--;
				}
			break;
		}
		var cssObj1 = {
		  'opacity' : 0
		}
		if(forum_mode == 6){
			search_query = $("#MTFSerach_input").val();
			serach_form = '<p align="center"><form style="text-align:center;" onsubmit="ChangeTabedForumPage(\'First\', 6, 1, \''+this.value+'\');return false;"><input type="text" style="width:300px;padding:5px;font-family:tahoma;" id="MTFSerach_input" value="'+search_query+'" /><input type="submit" value="جستجو" style="width:50px;padding:5px;font-family:tahoma;" /><input type="hidden" name="csrf_token" value="'+pn_csrf_token+'" /> </form></p>';
		}else{
			serach_form = "";
			search_query = "";
		}
		$.ajax({
			type: "POST",
			url: phpnuke_url+"ajax.php",
			data: {'mtforumtabed': 'true' , 'p': F_p, 'forum_mode': forum_mode, 'search_query': search_query, 'csrf_token' : pn_csrf_token}, success: function (responseText) {
				$("#forum_tabs-"+forum_mode).css(cssObj1).animate({ opacity: 1 },{queue: false, duration: 'slow'}).html(serach_form+'<div class="MTForumBlock">'+responseText+'</div>');
				$("#MTFloader").html('');
				$("#forum_tabs-"+forum_mode).fadeTo("slow", 1);

				$("#MTFNext_button").attr('href','javascript:ChangeTabedForumPage(\'Prev\', '+forum_mode+', '+F_p+', \''+search_query+'\')');
				$("#MTFPrev_button").attr('href','javascript:ChangeTabedForumPage(\'Next\', '+forum_mode+', '+F_p+', \''+search_query+'\')');
			}
		});
	}
}