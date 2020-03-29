/* global quickreply */
jQuery(function($) {
	'use strict';
	/* Quick Nick plugin adjustment. */
	function set_qr_profile_buttons(elements) {
		if (!quickreply.settings.quickNick) return;
		elements.find('.profile-context .user-icons').each(function() {
			var $this = $(this), current_post = $this.parents('.post'),
				link = current_post.find('.postprofile a.username-coloured, .postprofile a.username'),
				refer_button = '<a class="icon-refer" href="#qr_postform" title="' + quickreply.language.QUICKNICK_TITLE + '"></a>',
				qr_pm_link = current_post.find('.contact-icon.pm-icon').parent('a'),
				pm_button = (quickreply.settings.quickNickPM && qr_pm_link.length) ? '<a class="icon-pm" href="' + qr_pm_link.attr('href') + '" title="' + quickreply.language.REPLY_IN_PM + '"></a>' : '';
			$this.append(pm_button).find('.icon-profile').after(refer_button);
			$this.find('.icon-refer').click(function() {
				quickreply.plugins.quickNick.insert(link);
				link.click();
				return false;
			});
		});
		elements.find('.postbody').find('a.username-coloured, a.username').click(function(e) {
			e.preventDefault();
			e.stopPropagation();
			$(this).parents('.post').find('a.username-coloured.dropdown-toggle, a.username.dropdown-toggle').click();
		});
	}
	var qr_posts = $('#qr_posts');
	set_qr_profile_buttons(qr_posts);
	qr_posts.on('qr_loaded', function (e, elements) {
		set_qr_profile_buttons(elements);
		elements.find('.postprofile').each(function() {
			var $this = $(this),
				$trigger = $this.find('dt a'),
				$contents = $this.siblings('.profile-context').children('.dropdown'),
				options = {
					direction: 'auto',
					verticalDirection: 'auto'
				},
				data;

			if (!$trigger.length) {
				data = $this.attr('data-dropdown-trigger');
				$trigger = data ? $this.children(data) : $this.children('a:first');
			}

			if (!$contents.length) {
				data = $this.attr('data-dropdown-contents');
				$contents = data ? $this.children(data) : $this.children('div:first');
			}

			if (!$trigger.length || !$contents.length) return;

			if ($this.hasClass('dropdown-up')) options.verticalDirection = 'up';
			if ($this.hasClass('dropdown-down')) options.verticalDirection = 'down';
			if ($this.hasClass('dropdown-left')) options.direction = 'left';
			if ($this.hasClass('dropdown-right')) options.direction = 'right';

			phpbb.registerDropdown($trigger, $contents, options);
		});
	});
});
