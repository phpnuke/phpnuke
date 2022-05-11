/* global quickreply */
/**
 * This file is made for prosilver-based styles.
 * Other styles may require some changes, copy this file
 * to the directory of your style and modify it accordingly.
 *
 * Note: you can also modify quickreply.editor object for your style.
 */
;(function($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	'use strict';

	// Style-specific functions and features of QuickReply.
	quickreply.style = {};

	/**
	 * Sets the correct ID for the quick reply textarea.
	 */
	quickreply.style.setTextareaId = function() {
		quickreply.$.messageBox.find("textarea").first().attr('id', 'message');
	};

	/**
	 * Initializes Ajax preview - creates preview container.
	 */
	quickreply.style.initPreview = function() {
		quickreply.$.mainForm.before('<div id="preview" class="post profile_hidden bg2" style="display: none;"><i id="preview_close" class="fa fa-times"></i><div class="inner"><div class="postbody"><h3></h3><div class="content"></div></div></div></div>');
	};

	/**
	 * Marks the posts within the specified elements read.
	 *
	 * @param {jQuery} elements
	 */
	quickreply.style.markRead = function(elements) {
		elements.find('.unreadpost').removeClass('unreadpost').find('.icon.icon-red').removeClass('icon-red').addClass('icon-lightgray');
	};

	/**
	 * Opens quick reply form if it is collapsed.
	 */
	quickreply.style.showQuickReplyForm = function() {
		/*****************************/
		/* Quick Reply Toggle Plugin */
		/*****************************/
		if ($("#reprap:not(.ouvert)").length) {
			$('#reprap input[type=submit]').click();
		}
	};

	/**
	 * Sets and hides additional form elements.
	 * Used in fixed form mode.
	 */
	quickreply.style.setAdditionalElements = function() {
		quickreply.$.messageBox.siblings(':visible')
			.not('.submit-buttons, #qr_action_box, #qr_text_action_box')
			.not('#qr_captcha_container, #smiley-box, #register-and-translit')
			.not('script, [type=hidden]')
			.addClass('additional-element').hide();
	};

	/**
	 * Returns jQuery object with form editor elements.
	 *
	 * @param {boolean} selectStandard Whether we need to select submit buttons and CAPTCHA container.
	 * @returns {jQuery}
	 */
	quickreply.style.formEditorElements = function(selectStandard) {
		var $qrForm = quickreply.$.mainForm,
			formatButtons = (quickreply.plugins.abbc3) ? '#abbc3_buttons' : '#format-buttons',
			elementsArray = [
				'#attach-panel',
				formatButtons,
				'#register-and-translit',
				'.quickreply-title',
				'.additional-element'
			],
			$elements = $qrForm.find(elementsArray.join(', '));
		return (selectStandard) ? $elements.add($qrForm.find('.submit-buttons, #qr_captcha_container')) : $elements;
	};

	/**
	 * Gets the jQuery object for pagination elements.
	 *
	 * @returns {jQuery}
	 */
	quickreply.style.getPagination = function() {
		return $('.action-bar .pagination');
	};

	/**
	 * Applies the new pagination data.
	 * Used in Ajax response handling function
	 * when temporary container with the new data is available.
	 */
	quickreply.style.handlePagination = function() {
		var $replyPagination = $('#qr_pagination');
		quickreply.style.getPagination().html($replyPagination.html());
		$replyPagination.remove();
	};

	/**
	 * Restores the subject of the first post.
	 * Used after inserting new posts if soft scroll is disabled.
	 *
	 * @param {jQuery} elements      jQuery object for posts.
	 * @param {jQuery} tempContainer jQuery object for the temporary container.
	 */
	quickreply.style.restoreFirstSubject = function(elements, tempContainer) {
		if (quickreply.settings.hideSubject && !elements.find('.post').length) {
			tempContainer.find('.post .postbody div h3').first().addClass('first').css('display', '');
		}
	};

	/**
	 * Adds Ajax functionality for the pagination.
	 */
	quickreply.style.bindPagination = function() {
		if (quickreply.settings.saveReply) {
			$('.action-bar .pagination a:not(.dropdown-trigger, .mark[href="#unread"])').click(function(event) {
				event.preventDefault();
				quickreply.ajaxReload.loadPage($(this).attr('href'));
			});
		}

		$('.action-bar .pagination a.mark[href="#unread"]').click(function(event) {
			event.preventDefault();
			var $unreadPosts = $('.unreadpost');
			quickreply.functions.softScroll(($unreadPosts.length) ? $unreadPosts.first() : quickreply.$.qrPosts);
		});

		$('.action-bar .pagination .page-jump-form :button').click(function() {
			var $input = $(this).siblings('input.inputbox');
			quickreply.functions.pageJump($input);
		});

		$('.action-bar .pagination .page-jump-form input.inputbox').on('keypress', function(event) {
			if (event.which === 13 || event.keyCode === 13) {
				event.preventDefault();
				quickreply.functions.pageJump($(this));
			}
		});

		$('.action-bar .pagination .dropdown-trigger').click(function() {
			var $dropdownContainer = $(this).parent();
			// Wait a little bit to make sure the dropdown has activated
			setTimeout(function() {
				if ($dropdownContainer.hasClass('dropdown-visible')) {
					$dropdownContainer.find('input.inputbox').focus();
				}
			}, 100);
		});
	};

	/**
	 * Adds initial Ajax functionality for the pagination.
	 */
	quickreply.style.initPagination = function() {
		$(window).on('load', function() {
			$('.action-bar .pagination').find('.page-jump-form :button').unbind('click');
			$('.action-bar .pagination').find('.page-jump-form input.inputbox').off('keypress');
			quickreply.style.bindPagination();
		});
	};

	/**
	 * Hides the subject box from the form.
	 */
	quickreply.style.hideSubjectBox = function() {
		$("#subject").closest("dl").remove();
	};

	/**
	 * Save message for the full reply form.
	 */
	quickreply.style.setPostReplyHandler = function() {
		$('.action-bar .button').has('.fa-reply, .fa-lock').click(function(e) {
			e.preventDefault();
			quickreply.form.prepareForStandardSubmission();
			quickreply.$.mainForm.find('#qr_full_editor').off('click').click();
		});
	};

	/**
	 * Gets the ID of the post for the specified element.
	 *
	 * @param {jQuery} element Inner jQuery element
	 * @returns {string}
	 */
	quickreply.style.getPostId = function(element) {
		return element.parents('.post').attr('id').replace('p', '');
	};

	/**
	 * Gets quote buttons for the specified elements.
	 *
	 * @param {jQuery} elements jQuery elements, e.g. posts
	 * @param {string} [type]   Selection specification:
	 *                          by default non-resposive quote buttons of all posts are returned
	 *                          'all' for including buttons in responsive menu
	 *                          'last' for getting all quote buttons of the last post (including responsive ones)
	 * @returns {jQuery}
	 */
	quickreply.style.getQuoteButtons = function(elements, type) {
		var container = (type === 'last') ? elements.find('.post-container:last-child') : elements,
			buttons = container.find('.post-buttons .fa-quote-left').parent('a');
		return (!type) ? buttons.not('.responsive-menu a:has(.fa-quote-left)') : buttons;
	};

	/**
	 * Attaches onclick event to quote buttons in responsive menu.
	 *
	 * @param {jQuery}   elements Container with quote buttons
	 * @param {function} fn       Event handler function
	 */
	quickreply.style.responsiveQuotesOnClick = function(elements, fn) {
		elements.find('.post-buttons .responsive-menu').on('click', 'a:has(.fa-quote-left)', fn);
	};

	/**
	 * Sets up non-responsive quote buttons.
	 *
	 * @param {jQuery} elements jQuery container
	 */
	quickreply.style.setSkipResponsiveForQuoteButtons = function(elements) {
		quickreply.style.getQuoteButtons(elements).closest('li').attr('data-skip-responsive', 'true');
	};

	/**
	 * Whether the last page is currently being displayed.
	 *
	 * @returns {boolean}
	 */
	quickreply.style.isLastPage = function() {
		var paginationContainer = $('.action-bar.bar-top .pagination ul');
		return (paginationContainer.find('li').last().hasClass('active') || !paginationContainer.length);
	};

	/**
	 * Styles the quote button for quick quote only.
	 *
	 * @param {jQuery} elements
	 */
	quickreply.style.setQuickQuoteButton = function(elements) {
		elements.addClass('qr-quickquote')
			.attr('title', quickreply.language.QUICKQUOTE_TITLE)
			.children('span').text(quickreply.language.QUICKQUOTE_TEXT);
	};

	/**
	 * Styles the quote button like a standard one.
	 *
	 * @param {jQuery} elements
	 */
	quickreply.style.removeQuickQuoteButton = function(elements) {
		elements.removeClass('qr-quickquote')
			.attr('title', quickreply.language.REPLY_WITH_QUOTE)
			.children('span').text(quickreply.language.BUTTON_QUOTE);
	};

	/**
	 * Gets PM link anchor element for the specified profile.
	 *
	 * @param {jQuery} $nickname Profile nickname element
	 */
	quickreply.style.getPMLink = function($nickname) {
		return $nickname.parents('.post').find('.contact-icon.pm-icon').parent('a');
	};

	/**
	 * Generates an HTML element for a dropdown element.
	 *
	 * @param {Array}  contentRows    Array with content for the rows in a list
	 * @param {int}    pageX          X coordinate of the cursor
	 * @param {int}    pageY          Y coordinate of the cursor
	 * @param {string} [appendClass]  Optional dropdown class
	 * @returns {jQuery}
	 */
	quickreply.style.createDropdown = function(contentRows, pageX, pageY, appendClass) {
		if (!contentRows) {
			return $('');
		}
		var dropdownStyle = 'top: ' + (pageY + 8) + 'px; ', pointerStyle = '';
		if (pageX > 184) {
			dropdownStyle += 'margin-right: 0; left: auto; right: ' + ($('body').width() - pageX - 20) + 'px;';
			pointerStyle = 'left: auto; right: 10px;';
		} else {
			dropdownStyle += 'left: ' + (pageX - 20) + 'px;';
		}
		appendClass = (appendClass) ? ' ' + appendClass : '';

		return $('<div class="dropdown qr_dropdown' + appendClass + '" style="' + dropdownStyle + '"><div class="pointer" style="' + pointerStyle + '"><div class="pointer-inner"></div></div><ul class="dropdown-contents dropdown-nonscroll"><li>' + contentRows.join('</li><li>') + '</li></ul></div>').appendTo('body');
	};

	/**
	 * Generates an HTML element for a QuickQuote dropdown element.
	 *
	 * @param {int} pageX X coordinate of the cursor
	 * @param {int} pageY Y coordinate of the cursor
	 * @returns {jQuery}
	 */
	quickreply.style.quickQuoteDropdown = function(pageX, pageY) {
		var listElements = [
			quickreply.functions.makeLink({
				href: "#qr_postform",
				text: quickreply.language.INSERT_TEXT
			})
		];

		return quickreply.style.createDropdown(listElements, pageX, pageY, 'qr_quickquote');
	};

	/**
	 * Generates an HTML element for a QuickNick dropdown element.
	 *
	 * @param {int}    pageX          X coordinate of the cursor
	 * @param {int}    pageY          Y coordinate of the cursor
	 * @param {string} viewProfileURL URL to the profile page
	 * @param {jQuery} qrPMLink       jQuery element for a "Send PM" link
	 * @returns {jQuery}
	 */
	quickreply.style.quickNickDropdown = function(pageX, pageY, viewProfileURL, qrPMLink) {
		var listElements = [
			quickreply.functions.makeLink({
				href: "#qr_postform",
				className: "qr_quicknick",
				title: quickreply.language.QUICKNICK_TITLE,
				text: quickreply.language.QUICKNICK
			})
		];

		if (quickreply.settings.quickNickPM && qrPMLink.length) {
			listElements.push(
				quickreply.functions.makeLink({
					href: qrPMLink.attr('href'),
					className: "qr_reply_in_pm",
					title: quickreply.language.REPLY_IN_PM,
					text: quickreply.language.REPLY_IN_PM
				})
			);
		}

		if (viewProfileURL) {
			listElements.push(
				quickreply.functions.makeLink({
					href: viewProfileURL,
					className: "qr_profile",
					title: quickreply.language.PROFILE,
					text: quickreply.language.PROFILE
				})
			);
		}

		return quickreply.style.createDropdown(listElements, pageX, pageY);
	};

	/**
	 * Calculates page bottom value for quick reply form.
	 * Fixed footer elements affect this value.
	 */
	quickreply.style.getPageBottomValue = function() {
		return 0;
	};
})(jQuery, window, document);
