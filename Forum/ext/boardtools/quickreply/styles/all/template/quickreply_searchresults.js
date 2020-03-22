; (function ($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	// Hide Subject Plugin
	$('#page-body').find('.not_first_post').each(function() {
		$(this).next('div.search').find('div.postbody h3:first').css('display', 'none');
	});
})(jQuery, window, document);
