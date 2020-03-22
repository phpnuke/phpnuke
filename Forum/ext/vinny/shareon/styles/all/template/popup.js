/* Source: http://www.codebelt.com/jquery/open-new-browser-window-with-jquery-custom-size/ */

$(document).ready(function(){
	$('.js-newWindow').click(function (event) {
		event.preventDefault();

		var $this = $(this);
 
		var url = $this.attr("href");
		var windowName = "popUp";
		var windowSize = $this.data("popup");
 
		window.open(url, windowName, windowSize);
	});
});
