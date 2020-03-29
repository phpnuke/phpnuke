/* global quickreply */
;(function($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	'use strict';

	/**
	 * Require special attention after style modifications:
	 * .post-body.panel-info - unread posts selector
	 */

	// Modify editor variables and add new ones.
	$.extend(quickreply.editor, {
		attachPanel: '#attach-tab',
		postSelector: 'div.post-body',
		postTitleSelector: '.panel-heading h3:first',
		totalPostsContainer: '.total-posts-container',
		paginationContainer: 'nav .pagination'
	});

	// Style-specific functions and features of QuickReply.
	quickreply.style = {};

	/**
	 * Sets the correct ID for the quick reply textarea.
	 */
	quickreply.style.setTextareaId = function() {
		$("#message").closest(".form-group").remove();
		$("#qr_message_temp").attr("id", "message");
	};

	/**
	 * Initializes Ajax preview - creates preview container.
	 */
	quickreply.style.initPreview = function() {
		quickreply.$.mainForm.before('<div id="preview" class="post-body panel panel-info" style="display: none;"><div class="panel-heading"><h3></h3><i id="preview_close" class="fa fa-times"></i></div><div class="panel-body"><div class="content"></div></div></div>');
	};

	/**
	 * Marks the posts within the specified elements read.
	 *
	 * @param {jQuery} elements
	 */
	quickreply.style.markRead = function(elements) {
		elements.find('.post-body.panel-info').removeClass('panel-info');
	};

	/**
	 * Opens quick reply form if it is collapsed.
	 */
	quickreply.style.showQuickReplyForm = function() {
		var $this = quickreply.$.mainForm.find('.panel-heading span.clickable');
		if ($this.hasClass('panel-collapsed')) {
			$this.parents('.panel-collapsible').find('.panel-body, .panel-footer').show();
			$this.removeClass('panel-collapsed');
			$this.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
		}
	};

	/**
	 * Sets and hides additional form elements.
	 * Used in fixed form mode.
	 */
	quickreply.style.setAdditionalElements = function() {
		var $messageBox = quickreply.$.messageBox,
			$formGroups = $messageBox.closest('.form-group').siblings(),
			$additionalTabs = $messageBox.closest('fieldset').siblings().not(quickreply.editor.attachPanel);
		$formGroups.add($additionalTabs).not('#abbc3_buttons').not('script, [type=hidden]')
			.addClass('additional-element').hide();
	};

	/**
	 * Returns jQuery object with form editor elements.
	 *
	 * @param {boolean} selectSubmitButtons Whether we need to select submit buttons.
	 * @returns {jQuery}
	 */
	quickreply.style.formEditorElements = function(selectSubmitButtons) {
		var $qrForm = quickreply.$.mainForm,
			$elements = $qrForm.find('#attach-tab, #format-buttons, #abbc3_buttons, .additional-element');
		return (selectSubmitButtons) ? $elements.add($qrForm.find('.submit-buttons')) : $elements;
	};

	/**
	 * Gets the jQuery object for pagination elements.
	 *
	 * @returns {jQuery} For ComBoot we return several elements
	 */
	quickreply.style.getPagination = function() {
		var qe = quickreply.editor;
		return $(qe.totalPostsContainer + ', ' + qe.paginationContainer);
	};

	/**
	 * Applies the new pagination data.
	 * Used in Ajax response handling function
	 * when temporary container with the new data is available.
	 */
	quickreply.style.handlePagination = function() {
		var replyPagination = $('#qr_pagination'), postsCounter = $('#qr_total_posts');
		$(quickreply.editor.paginationContainer).html(replyPagination.html());
		$(quickreply.editor.totalPostsContainer).html(postsCounter.html());
		replyPagination.remove();
		postsCounter.remove();
	};

	/**
	 * Restores the subject of the first post.
	 * Used after inserting new posts if soft scroll is disabled.
	 *
	 * @param {jQuery} elements      jQuery object for posts.
	 * @param {jQuery} tempContainer jQuery object for the temporary container.
	 */
	quickreply.style.restoreFirstSubject = function(elements, tempContainer) {
		if (quickreply.settings.hideSubject && !elements.find('.post-body').length) {
			tempContainer.find(quickreply.editor.postTitleSelector).first().addClass('first').css('display', '')
				.parents(quickreply.editor.postSelector).removeClass('hidden_subject');
		}
	};

	/**
	 * Adds Ajax functionality for the pagination.
	 */
	quickreply.style.bindPagination = function() {
		if (quickreply.settings.saveReply) {
			$(quickreply.editor.totalPostsContainer).children('a:not([href="#unread"])')
				.add('nav .pagination a:not([href="#"])').click(function(event) {
				event.preventDefault();
				quickreply.ajaxReload.loadPage($(this).attr('href'));
			});
		}

		$(quickreply.editor.totalPostsContainer).children('a[href="#unread"]').click(function(event) {
			event.preventDefault();
			var $unreadPosts = $('.post-body.panel-info');
			quickreply.functions.softScroll(($unreadPosts.length) ? $unreadPosts.first() : quickreply.$.qrPosts);
		});

		$('.total-posts-container .page-jump-form :button').click(function() {
			var $input = $(this).parent().siblings('input.form-control');
			quickreply.functions.pageJump($input);
			$(quickreply.editor.totalPostsContainer).removeClass('open');
		});

		$('.total-posts-container .page-jump-form input.form-control').on('keypress', function(event) {
			if (event.which === 13 || event.keyCode === 13) {
				event.preventDefault();
				quickreply.functions.pageJump($(this));
				$(quickreply.editor.totalPostsContainer).removeClass('open');
			}
		});

		$('.total-posts-container .dropdown-toggle').click(function() {
			var $dropdownContainer = $(this).parent();
			// Wait a little bit to make sure the dropdown has activated
			setTimeout(function() {
				if ($dropdownContainer.hasClass('open')) {
					$dropdownContainer.find('input.form-control').focus();
				}
			}, 100);
		});
	};

	/**
	 * Adds initial Ajax functionality for the pagination.
	 */
	quickreply.style.initPagination = function() {
		$(window).on('load', function() {
			$('.total-posts-container .page-jump-form :button').unbind('click');
			$('.total-posts-container .page-jump-form input.form-control').off('keypress');
			quickreply.style.bindPagination();
		});
	};

	/**
	 * Hides the subject box from the form.
	 */
	quickreply.style.hideSubjectBox = function() {
		quickreply.$.mainForm.find('input[name="subject"]').closest(".form-group").remove();
	};

	/**
	 * Save message for the full reply form.
	 */
	quickreply.style.setPostReplyHandler = function() {
		$('.row:has(.pagination)').find('.fa-lock, .fa-pencil-square-o').closest('a').click(function(e) {
			e.preventDefault();
			quickreply.form.prepareForStandardSubmission();
			quickreply.$.mainForm.find('[name="preview"]').off('click').click();
		});
	};

	/**
	 * Gets the ID of the post for the specified element.
	 *
	 * @param {jQuery} element Inner jQuery element
	 * @returns {string}
	 */
	quickreply.style.getPostId = function(element) {
		return element.parents('.post-body').parent().attr('id').replace('p', '');
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
			buttons = container.find('.topic-buttons .fa-quote-left').parent('a');
		return (!type) ? buttons.not('.btn-toolbar-mobile button:has(.fa-quote-left)') : buttons;
	};

	/**
	 * Attaches onclick event to quote buttons in responsive menu.
	 *
	 * @param {jQuery}   elements Container with quote buttons
	 * @param {function} fn       Event handler function
	 */
	quickreply.style.responsiveQuotesOnClick = function(elements, fn) {
		elements.find('.btn-toolbar-mobile').on('click', 'button:has(.fa-quote-left)', fn);
	};

	/**
	 * Sets up non-responsive quote buttons.
	 *
	 * @param {jQuery} elements jQuery container
	 */
	quickreply.style.setSkipResponsiveForQuoteButtons = function(elements) {
		$('.btn-toolbar-mobile').prepend('<button type="button" class="btn btn-default btn-xs" title="' +
			quickreply.language.BUTTON_QUOTE + '"><i class="fa fa-quote-left"></i></button>')
			.find('.dropdown-toggle').one('click', function() {
			$(this).closest('.btn-toolbar-mobile').find('li').has('.fa-quote-left').remove();
		});
	};

	/**
	 * Whether the last page is currently being displayed.
	 *
	 * @returns {boolean}
	 */
	quickreply.style.isLastPage = function() {
		return $(quickreply.editor.paginationContainer).find('li').last().hasClass('active') ||
			typeof $(quickreply.editor.paginationContainer).html() === "undefined";
	};

	/**
	 * Styles the quote button for quick quote only.
	 *
	 * @param {jQuery} elements
	 */
	quickreply.style.setQuickQuoteButton = function(elements) {
		elements.addClass('qr-quickquote')
			.attr('title', quickreply.language.QUICKQUOTE_TITLE).tooltip('destroy').tooltip({container: 'body'})
			.children('span').text(quickreply.language.QUICKQUOTE_TEXT);
	};

	/**
	 * Styles the quote button like a standard one.
	 *
	 * @param {jQuery} elements
	 */
	quickreply.style.removeQuickQuoteButton = function(elements) {
		elements.removeClass('qr-quickquote')
			.attr('title', quickreply.language.REPLY_WITH_QUOTE).tooltip('destroy').tooltip({container: 'body'})
			.children('span').text(quickreply.language.BUTTON_QUOTE);
	};

	/**
	 * Gets PM link anchor element for the specified profile.
	 *
	 * @param {jQuery} $nickname Profile nickname element
	 */
	quickreply.style.getPMLink = function($nickname) {
		return $nickname.parents('.post-body').find('.contact-icon.pm-icon').parent('a');
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
			dropdownStyle += 'margin-right: 0; left: auto; right: ' + ($('body').width() - pageX - 21) + 'px;';
			pointerStyle = 'left: auto; right: 10px;';
		} else {
			dropdownStyle += 'left: ' + (pageX - 21) + 'px;';
			pointerStyle = 'left: 21px; right: auto;'; // Handle "margin-left: -11px;"
		}
		appendClass = (appendClass) ? ' ' + appendClass : '';
		return $('<div class="popover bottom qr_dropdown' + appendClass + '" style="' + dropdownStyle + '"><div class="arrow" style="' + pointerStyle + '"></div><ul class="popover-content dropdown-menu"><li>' + contentRows.join('</li><li>') + '</li></ul></div>').appendTo('body');
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
		return $('#footer-nav').height();
	};

	// Style-specific functions.
	function qrSetPosition() {
		var $qrFooterElements = $('#qr_show_fixed_form');
		if (!quickreply.form.is('hidden')) {
			$qrFooterElements = $qrFooterElements.add(quickreply.$.mainForm);
		}
		$qrFooterElements.css('bottom', $('#footer-nav').height());

		if (quickreply.form.is('fullscreen')) {
			quickreply.$.mainForm.css('padding-top', 0).css('height', '').css('top', $('#header-nav').height());
		}
	}

	$(window).on('load resize', qrSetPosition);
	$(quickreply.editor.mainForm).on('fullscreen-before', function() {
		quickreply.$.mainForm.css('padding-top', $('#header-nav').height());
	}).on('fullscreen', qrSetPosition).on('fullscreen-exit', function() {
		quickreply.$.mainForm.css('top', 'auto');
	});

	// Special handling for events.
	$("#qr_posts").on("qr_loaded", function(e, elements) {
		// Enable tooltip on all buttons
		elements.find(".btn[data-toggle!='dropdown']:not([disabled]):not(.disabled)").attr({
			'data-toggle': 'tooltip',
			'data-container': 'body'
		});
		elements.find('[data-toggle="tooltip"]').tooltip({container: 'body'});

		// Create mobile post toolbar
		elements.find(quickreply.editor.postSelector).each(function() {
			var $post = $(this),
				$btnGroups = $post.find('.post-content .btn-toolbar .btn-group'),
				$btnGroupsAmount = $btnGroups.length;

			$btnGroups.each(function(index) {
				var $this = $(this),
					$postbody = $this.closest('.post-body');
				if (!$this.is(':empty')) {
					$this.children('a').each(function() {
						var $link = $(this),
							$icon = $link.children('i').attr('class'),
							$title = $link.data('original-title'),
							$href = $link.attr('href'),
							$item = '<li><a href="' + $href + '"><i class="' + $icon + '" aria-hidden="true"></i> ' + $title + '</a></li>';
						$postbody.find('.btn-toolbar-mobile ul').append($item);
					});
					if (index !== $btnGroupsAmount - 1) {
						$postbody.find('.btn-toolbar-mobile ul').append('<li role="separator" class="divider"></li>');
					}
					$postbody.find('.btn-toolbar-mobile').removeClass('hidden').addClass('visible-xs-block');
				}
			});
		});

		// Re-initialize to-top animation.
		elements.find('.to-top').click(function() {
			$('#back-to-top').tooltip('hide');
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});

		// Set data-lightbox
		elements.find('.content img').each(function() {
			var $this = $(this);
			$this.attr('data-lightbox', $this.attr('src'));
		});

		var $primaryColor = $('.btn-primary').css('background-color'),
			$footerColor = $('.panel-footer').css('background-color');

		// more beautiful quote
		elements.find('blockquote').css('border-left', '3px solid ' + $primaryColor)
			.css('background-color', $footerColor);

		// Smoth scroll #links
		elements.find('a[href*=#]:not([href=#]):not([data-toggle=tab]):not([data-type=char-select])').click(function() {
			if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
				var target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
				if (target.length) {
					$('html,body').animate({
						scrollTop: target.offset().top
					}, 1000);
					return false;
				}
			}
		});
	});

	// Fix mobile post toolbar
	$('.btn-toolbar-mobile').find('.dropdown-toggle').one('click', function() {
		$(this).closest('.btn-toolbar-mobile').find('li').last().filter('.divider').remove();
	});

	if (quickreply.settings.formType === 0) {
		// Hide quick reply form after posting a reply.
		$(quickreply.editor.mainForm).on("ajax_submit_success", function() {
			var $this = quickreply.$.mainForm.find('.panel-heading span.clickable');
			if (!$this.hasClass('panel-collapsed')) {
				$this.parents('.panel-collapsible').find('.panel-body, .panel-footer').slideUp();
				$this.addClass('panel-collapsed');
				$this.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
			}
		});
	}
})(jQuery, window, document);
