/* global quickreply, grecaptcha, insert_text, split_lines */
/* jshint -W040 */
;(function($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	'use strict';

	/*****************************/
	/* Not Change Subject Plugin */
	/*****************************/
	if (quickreply.settings.unchangedSubject) {
		if (quickreply.settings.hideSubjectBox) {
			$(document).ready(function() {
				quickreply.style.hideSubjectBox();
			});
		} else {
			$(document).ready(function() {
				quickreply.$.mainForm.find('input[name="subject"][type="text"]')
					.attr('disabled', 'disabled').css('color', 'grey');
			});
		}
	}

	/*********************/
	/* Ctrl+Enter Plugin */
	/*********************/
	if (quickreply.settings.ctrlEnterNotice) {
		quickreply.$.mainForm.find('input[name="post"]').attr('title', quickreply.language.CTRL_ENTER);
	}

	/********************/
	/* Helper functions */
	/********************/
	function QrHelper() {
		var self = this,
			$dropdown = false;

		/**
		 * Gets cursor coordinates.
		 *
		 * @param {event} evt jQuery Event object
		 * @returns {object}
		 */
		self._getCoordinates = function(evt) {
			if (evt.type === 'touchstart') {
				evt.pageX = evt.originalEvent.touches[0].pageX;
				evt.pageY = evt.originalEvent.touches[0].pageY;
			}

			return {
				x: evt.pageX || evt.clientX + document.documentElement.scrollLeft, // FF || IE
				y: evt.pageY || evt.clientY + document.documentElement.scrollTop
			};
		};

		/**
		 * Sets new dropdown and adds body event handler.
		 *
		 * @param {jQuery} $newDropdown jQuery object for new dropdown
		 */
		self._setDropdown = function($newDropdown) {
			$dropdown = $newDropdown;

			// Hide active dropdowns when click event happens outside
			$(document.body).on('mousedown.quickreply.dropdown', function(e) {
				var $parents = $(e.target).parents();
				if (!$parents.is($dropdown)) {
					self._removeDropdown();
				}
			});
		};

		/**
		 * Removes the dropdown.
		 */
		self._removeDropdown = function() {
			if ($dropdown) {
				$dropdown.remove();
				$(document.body).off('mousedown.quickreply.dropdown');
				$dropdown = false;
			}
		};
	}

	/**************************************/
	/* Quick Quote and Full Quote Plugins */
	/**************************************/
	function QrQuote() {
		QrHelper.apply(this, arguments);

		this._postID = 0;
		this._selection = '';

		var self = this,
			clientPC = navigator.userAgent.toLowerCase(), // Get client info
			isIE = ((clientPC.indexOf('msie') !== -1) && (clientPC.indexOf('opera') === -1));

		/**
		 * Get text selection - not only the post content :(
		 * IE9 must use the document.selection method but has the *.getSelection so we just force no IE
		 */
		self._getSelection = function() {
			self._selection = '';

			if (window.getSelection && !isIE && !window.opera) {
				self._selection = window.getSelection().toString();
			} else if (document.getSelection && !isIE) {
				self._selection = document.getSelection();
			} else if (document.selection) {
				self._selection = document.selection.createRange().text;
			}
		};

		/**
		 * Inserts a quote from the specified post to quick reply textarea.
		 */
		self._insertQuote = function() {
			var postAuthor = $('#qr_author_p' + self._postID),
				username = postAuthor.text(),
				userID = postAuthor.attr('data-url').replace(/(.)*[?&]u=/, ''),
				postTime = $('#qr_time' +  self._postID).text();

			if (self._selection) {
				quickreply.form.show();

				if (quickreply.settings.allowBBCode) {
					insert_text('[quote=' + username + ' post_id=' + self._postID + ' time=' + postTime + ' user_id=' + userID + ']' + self._selection + '[/quote]\r');
				} else {
					insert_text(username + ' ' + quickreply.language.WROTE + ':' + '\n');

					var lines = split_lines(self._selection);

					for (var i = 0; i < lines.length; i++) {
						insert_text('> ' + lines[i] + '\n');
					}
				}
			}
		};
	}

	/**********************/
	/* Quick Quote Plugin */
	/**********************/
	function QuickQuote() {
		QrQuote.apply(this, arguments);

		var self = this,
			quickQuoteCancelEvent = false;

		/**
		 * Inserts quick quote to quick reply textarea.
		 *
		 * @returns {boolean}
		 */
		function insertQuickQuote() {
			self._insertQuote();
			self._removeDropdown();
			return false;
		}

		/**
		 * Generates new QuickQuote dropdown.
		 *
		 * @param {event}  evt     jQuery Event object
		 * @param {jQuery} element jQuery container
		 */
		function addQuickQuote(evt, element) {
			var $target = $(evt.target), $element = element || $(this);

			if ($target.is('a') && $target.parents('.codebox').length) {
				return;
			}

			var coordinates = self._getCoordinates(evt);
			// Which mouse button is pressed?
			var key = evt.button || evt.which || null; // IE || FF || Unknown

			self._postID = quickreply.style.getPostId($element);

			setTimeout(function() { // Timeout prevents popup when clicking on selected text
				self._getSelection();

				if (self._selection && key <= 1) { // If text selected && right mouse button not pressed
					self._removeDropdown();
					self._setDropdown(quickreply.style.quickQuoteDropdown(coordinates.x, coordinates.y)
						.mousedown(insertQuickQuote));
				}
			}, 0);
		}

		/**
		 * Adds new QuickQuote dropdown each time when the selection is changed.
		 *
		 * @param {event} evt jQuery Event object
		 */
		function handleQuickQuote(evt) {
			addQuickQuote(evt, $(this));

			if (!quickQuoteCancelEvent) {
				quickreply.$.qrPosts.on('mousemove', '.content', addQuickQuote);

				$(document.body).one('mouseup', function() {
					quickreply.$.qrPosts.off('mousemove', '.content', addQuickQuote);
					quickQuoteCancelEvent = false;
				});

				quickQuoteCancelEvent = true;
			}
		}

		/**
		 * Initializes QuickQuote functionality.
		 */
		self.init = function() {
			if ('ontouchstart' in window) {
				quickreply.$.qrPosts.on('mousedown touchstart', '.content', handleQuickQuote);
			}

			quickreply.$.qrPosts.on('mouseup', '.content', addQuickQuote);
		};
	}

	quickreply.plugins.quickQuote = new QuickQuote();

	if (quickreply.settings.quickQuote) {
		quickreply.plugins.quickQuote.init();
	}

	/*********************/
	/* Full Quote Plugin */
	/*********************/
	function FullQuote() {
		QrQuote.apply(this, arguments);

		var self = this;

		/**
		 * Gets full decoded post content to the selection.
		 */
		function getPostContent() {
			self._selection = '';

			var messageName = 'decoded_p' + self._postID, divarea = false;

			if (document.all) {
				divarea = document.all[messageName];
			} else {
				divarea = document.getElementById(messageName);
			}

			if (divarea.innerHTML) {
				self._selection = divarea.innerHTML.replace(/<br>/ig, '\n');
				self._selection = self._selection.replace(/<br\/>/ig, '\n');
				self._selection = self._selection.replace(/&lt\;/ig, '<');
				self._selection = self._selection.replace(/&gt\;/ig, '>');
				self._selection = self._selection.replace(/&amp\;/ig, '&');
				self._selection = self._selection.replace(/&nbsp\;/ig, ' ');
			} else if (document.all) {
				self._selection = divarea.innerText;
			} else if (divarea.textContent) {
				self._selection = divarea.textContent;
			} else if (divarea.firstChild.nodeValue) {
				self._selection = divarea.firstChild.nodeValue;
			}

			self._selection = self._selection.replace(/(\[attachment.*?\]|\[\/attachment\])/g, '');
		}

		/**
		 * Handles full quote insertion.
		 *
		 * @param {event}  e       jQuery Event object
		 * @param {jQuery} element jQuery container
		 */
		function addFullQuote(e, element) {
			self._postID = quickreply.style.getPostId(element);
			self._selection = '';

			if (quickreply.settings.quickQuoteButton) {
				self._getSelection();
			}

			if (quickreply.settings.fullQuote && !element.hasClass('qr-quickquote')) {
				if (self._selection === '' || typeof self._selection === 'undefined' || self._selection === null) {
					getPostContent();
				}
			}

			if (self._selection !== '') {
				e.preventDefault();
				self._insertQuote();
			} else if (!quickreply.settings.fullQuoteAllowed || element.hasClass('qr-quickquote')) {
				e.preventDefault();
				quickreply.functions.alert(quickreply.language.ERROR, quickreply.language.NO_FULL_QUOTE);
			}
		}

		/**
		 * Handles standard quote buttons.
		 *
		 * @param {event}  e        jQuery Event object
		 * @param {jQuery} elements jQuery container
		 */
		function setQuoteButtons(e, elements) {
			if (quickreply.settings.quickQuoteButton) {
				var quoteButtons = null, lastQuoteButton = null;

				if (!quickreply.settings.fullQuote || !quickreply.settings.fullQuoteAllowed) {
					// Style all quote buttons
					quoteButtons = quickreply.style.getQuoteButtons(elements, 'all');

					quickreply.style.setQuickQuoteButton(quoteButtons);
				} else if (!quickreply.settings.lastQuote && quickreply.style.isLastPage()) {
					// Style only last quote button
					quoteButtons = quickreply.style.getQuoteButtons(quickreply.$.qrPosts, 'all');
					lastQuoteButton = quickreply.style.getQuoteButtons(elements, 'last');

					quickreply.style.removeQuickQuoteButton(quoteButtons);
					quickreply.style.setQuickQuoteButton(lastQuoteButton);
				}
			}

			quickreply.style.getQuoteButtons(elements).click(function(e) {
				addFullQuote(e, $(this));
			});
		}

		/**
		 * Handles responsive quote buttons.
		 *
		 * @param {event} e jQuery Event object
		 */
		function handleResponsiveClick(e) {
			addFullQuote(e, $(this));

			var $container = $(this).parents('.dropdown-container'),
				$trigger = $container.find('.dropdown-trigger:first'),
				data;

			if (!$trigger.length) {
				data = $container.attr('data-dropdown-trigger');
				$trigger = data ? $container.children(data) : $container.children('a:first');
			}

			$trigger.click();
		}

		/**
		 * Initializes FullQuote functionality.
		 */
		self.init = function() {
			$(window).on('load', function(e) {
				setQuoteButtons(e, quickreply.$.qrPosts);
				quickreply.style.responsiveQuotesOnClick(quickreply.$.qrPosts, handleResponsiveClick);
			});

			quickreply.$.qrPosts.on('qr_completed', function(e, elements) {
				setQuoteButtons(e, elements);
				quickreply.style.responsiveQuotesOnClick(elements, handleResponsiveClick);
			}).on('qr_loaded', function(e, elements) {
				quickreply.style.setSkipResponsiveForQuoteButtons(elements);
			});

			quickreply.style.setSkipResponsiveForQuoteButtons(quickreply.$.qrPosts);
		};
	}

	quickreply.plugins.fullQuote = new FullQuote();

	if (quickreply.settings.fullQuote || quickreply.settings.quickQuoteButton) {
		quickreply.plugins.fullQuote.init();
	}

	/*********************/
	/* Quick Nick Plugin */
	/*********************/
	function QuickNick() {
		QrHelper.apply(this, arguments);

		var self = this,
			comma = (quickreply.settings.enableComma) ? ', ' : '\r\n',
			nicknameSelector = quickreply.editor.profileLinkSelector + ', ' + quickreply.editor.profileNoLinkSelector;

		/**
		 * Whether QuickNick dropdown is enabled.
		 *
		 * @returns {boolean}
		 */
		function quickNickIsDropdown() {
			return !!(quickreply.settings.quickNick && (
				quickreply.settings.quickNickString || !quickreply.settings.quickNickUserType
			));
		}

		/**
		 * Whether QuickNick string is enabled.
		 *
		 * @returns {boolean}
		 */
		function quickNickIsString() {
			return !!(quickreply.settings.quickNickString || (
				quickreply.settings.quickNick && quickreply.settings.quickNickUserType
			));
		}

		/**
		 * Generates new QuickNick dropdown for the specified profile link.
		 *
		 * @param {event}  evt       jQuery Event object
		 * @param {jQuery} $nickname jQuery element for the user profile link
		 */
		function addDropdown(evt, $nickname) {
			// Get cursor coordinates
			if (!evt) {
				evt = window.event;
			}
			evt.preventDefault();
			var coordinates = self._getCoordinates(evt);

			// Get nick and id
			var viewprofileURL = $nickname.attr('href');
			var pmLink = quickreply.style.getPMLink($nickname);

			var quickNickDropdown = quickreply.style.quickNickDropdown(
				coordinates.x, coordinates.y, viewprofileURL, pmLink
			);
			self._setDropdown(quickNickDropdown);

			$('a.qr_quicknick', quickNickDropdown).mousedown(function() {
				self.insert($nickname);
				self._removeDropdown();
				return false;
			});
		}

		/**
		 * Applies new title to QuickNick dropdown triggers.
		 *
		 * @param {event}  e        jQuery Event object
		 * @param {jQuery} elements jQuery container
		 */
		function quickNickHandlePosts(e, elements) {
			elements.find(nicknameSelector).each(function() {
				$(this).attr('title', quickreply.language.QUICKNICK);
			});
			elements.find(quickreply.editor.profileNoLinkSelector).addClass('qr_quicknick_trigger');
		}

		/**
		 * Inserts the nickname of the specified user to quick reply textarea.
		 *
		 * @param {jQuery} $nickname jQuery element for the user profile link
		 */
		self.insert = function($nickname) {
			var nickname = $nickname.text(),
				color = ($nickname.hasClass('username-coloured')) ? $nickname.css('color') : false,
				qrColor = (quickreply.settings.colouredNick && color) ?
					'=' + quickreply.functions.getHexColor(color) : '';

			quickreply.form.show();

			if (!quickreply.settings.allowBBCode) {
				insert_text(nickname + comma, false);
			} else if (!quickreply.settings.quickNickRef) {
				insert_text('[b]' + nickname + '[/b]' + comma, false);
			} else {
				insert_text('[ref' + qrColor + ']' + nickname + '[/ref]' + comma, false);
			}
		};

		/**
		 * Initializes QuickNick functionality.
		 */
		self.init = function() {
			if (quickNickIsDropdown()) {
				$(document).ready(function(e) {
					quickNickHandlePosts(e, quickreply.$.qrPosts);
				});

				quickreply.$.qrPosts.on('click', nicknameSelector, function(e) {
					addDropdown(e, $(this));
				}).on('qr_loaded', quickNickHandlePosts);
			}

			if (quickNickIsString()) {
				quickreply.$.qrPosts.on('click', '.qr_quicknick', function(e) {
					e.preventDefault();
					var $nickname = $(this).parent().find(nicknameSelector);
					self.insert($nickname);
				});
			}
		};
	}

	quickreply.plugins.quickNick = new QuickNick();

	if (quickreply.settings.quickNick || quickreply.settings.quickNickString) {
		quickreply.plugins.quickNick.init();

		/**********************/
		/* Live Search Plugin */
		/**********************/
		if (quickreply.plugins.liveSearch) {
			$(document).ready(function() {
				$('#topics_live_search').parent()
					.before('<li><a id="qr_quicknick_live_search" href="#qr_postform" title="' +
						quickreply.language.QUICKNICK_TITLE + '">' + quickreply.language.QUICKNICK +
						'</a></li><li class="separator"></li>');
			});

			$('#leavesearch').on('click', '#qr_quicknick_live_search', function() {
				var comma = (quickreply.settings.enableComma) ? ', ' : '',
					nickname = $('#user_live_search').val();

				quickreply.form.show();
				insert_text('[ref]' + nickname + '[/ref]' + comma, false);
			});
		}
	}

	/*******************/
	/* ABBC 3.1 Plugin */
	/*******************/
	if (quickreply.plugins.abbc3) {
		var qrABBC3BBvideo = function(e, elements) {
			var bbvideo = elements.find('.bbvideo');
			if (bbvideo.length > 0) {
				bbvideo.bbvideo();
			}
		};

		/* Ajax Submit */
		quickreply.$.qrPosts.on('qr_completed', qrABBC3BBvideo);
		quickreply.$.mainForm.on('ajax_submit_preview', qrABBC3BBvideo);
	}

	/*********************/
	/* reCAPTCHA2 Plugin */
	/*********************/
	if (quickreply.plugins.reCAPTCHA2) {
		quickreply.$.mainForm.on('qr_captcha_refreshed', function() {
			var recaptcha2Wrapper = $('.g-recaptcha');
			recaptcha2Wrapper.html('');
			grecaptcha.render(document.getElementsByClassName('g-recaptcha')[0], {
				'sitekey': recaptcha2Wrapper.attr('data-sitekey')
			});
		});
	}
})(jQuery, window, document);
