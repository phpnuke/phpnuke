/* global phpbb, addquote:true, quickreply */
;(function($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	'use strict';

	/*****************************/
	/* Not Change Subject Plugin */
	/*****************************/
	if (quickreply.posting.settings.unchangedSubject) {
		if (quickreply.posting.settings.hideSubjectBox) {
			$("#subject").closest("dl").hide();
		} else {
			$('#subject').prop("readonly", true);
		}
	}

	/*********************/
	/* Full Quote Plugin */
	/*********************/
	var postsContainer = $('#topicreview'), quickQuoteButtons = false, alertTimeout = null;

	/**
	 * Gets user selection.
	 *
	 * @returns string
	 */
	function getSelection() {
		var theSelection = '',
			clientPC = navigator.userAgent.toLowerCase(), // Get client info
			isIE = ((clientPC.indexOf('msie') !== -1) && (clientPC.indexOf('opera') === -1));

		// Get text selection - not only the post content :(
		// IE9 must use the document.selection method but has the *.getSelection so we just force no IE
		if (window.getSelection && !isIE && !window.opera) {
			theSelection = window.getSelection().toString();
		} else if (document.getSelection && !isIE) {
			theSelection = document.getSelection();
		} else if (document.selection) {
			theSelection = document.selection.createRange().text;
		}

		return theSelection;
	}

	/**
	 * Prevents full quotes for the specified quote buttons.
	 *
	 * @param {jQuery} element jQuery element for quote buttons
	 */
	function preventFullQuote(element) {
		element.each(function() {
			var $this = $(this), addQuoteFunction = addquote, processClick = false;

			$this.addClass('qr-quickquote').mousedown(function() {
				if (!getSelection()) {
					processClick = true;
					addquote = function() {
					};
				}
			}).click(function(e) {
				if (!processClick) {
					// User selected some text - process quote as usual.
					return;
				}

				e.preventDefault();
				e.stopPropagation();

				// Cancel animation.
				$('html,body').stop();

				// Remove previous timeouts.
				if (alertTimeout !== null) {
					clearTimeout(alertTimeout);
				}

				var alert = phpbb.alert(quickreply.posting.language.ERROR, quickreply.posting.language.NO_FULL_QUOTE);
				alertTimeout = setTimeout(function() {
					$('#darkenwrapper').fadeOut(phpbb.alertTime, function() {
						alert.hide();
					});
				}, 5000);

				processClick = false;
				addquote = addQuoteFunction;
			});
		});
	}

	if (!quickreply.posting.settings.fullQuoteAllowed) {
		quickQuoteButtons = postsContainer.find('.post-buttons .fa-quote-left').parent('a');
	} else if (!quickreply.posting.settings.lastQuote) {
		quickQuoteButtons = postsContainer.find('.post:first-of-type .post-buttons .fa-quote-left').parent('a');
	}

	if (quickQuoteButtons && quickQuoteButtons.length) {
		$(document).ready(function() {
			preventFullQuote(quickQuoteButtons);
		});
	}
})(jQuery, window, document);
