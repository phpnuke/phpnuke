/* global quickreply */
; (function ($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	/**
	 * Hides subjects of the posts within the specified elements (if needed).
	 *
	 * @param {jQuery} elements
	 */
	quickreply.special.functions.qr_hide_subject = function (elements) {
		if (quickreply.special.hideSubject) {
			elements.find('div.post').each(function () {
				var qr_post_subject = $(this).find('.postbody div h3:first').not('.first');
				if (qr_post_subject.length) {
					qr_post_subject.css('display', 'none');
					$(this).addClass('hidden_subject');
				}
			});
		}
	};
	quickreply.special.functions.qr_hide_subject($('#qr_posts'));
})(jQuery, window, document);
