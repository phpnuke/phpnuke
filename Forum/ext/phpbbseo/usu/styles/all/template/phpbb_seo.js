/**
*
* @package Ultimate phpBB SEO Friendly URL
* @version $$
* @copyright (c) 2006 - 2014 www.phpbb-seo.org
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
phpbb_seo = phpbb_seo || {};
/**
* Jump to page
*/
function pageJump(item) {
	var page = item.val();

	if (page !== null && !isNaN(page) && page == Math.floor(page) && page > 0) {
		var per_page = item.attr('data-per-page'),
			base_url = item.attr('data-base-url'),
			start_name = item.attr('data-start-name'),
			anchor = '',
			anchor_parts = base_url.split('#');

		if ( anchor_parts[1] ) {
			base_url = anchor_parts[0];
			anchor = '#' + anchor_parts[1];
		}

		phpbb_seo.page = (page - 1) * per_page;

		if ( phpbb_seo.page > 0 ) {
			var phpEXtest = false;

			if ( start_name !== 'start' || base_url.indexOf('?') >= 0 || ( phpEXtest = base_url.match("/\." + phpbb_seo.phpEX + "$/i"))) {
				document.location.href = base_url.replace(/&amp;/g, '&') + (phpEXtest ? '?' : '&') + start_name + '=' + phpbb_seo.page + anchor;
			} else {
				var ext = base_url.match(/\.[a-z0-9]+$/i);

				if (ext) {
					// location.ext => location-xx.ext
					document.location.href = base_url.replace(/\.[a-z0-9]+$/i, '') + phpbb_seo.delim_start + phpbb_seo.page + ext + anchor;
				} else {
					// location and location/ to location/pagexx.html
					var slash = base_url.match(/\/$/) ? '' : '/';
					document.location.href = base_url + slash + phpbb_seo.static_pagination + phpbb_seo.page + phpbb_seo.ext_pagination + anchor;
				}
			}
		} else {
			document.location.href = base_url + anchor;
		}
	}
}
/**
*  phpbb_seo.external_hrefs()
*  Fixes href="#something" links with virtual directories
*  Optionally open external or marked with a css class links in a new window
*/
phpbb_seo.external_hrefs = function () {
	var current_domain = document.domain.toLowerCase();

	if (!current_domain || !document.getElementsByTagName) {
		return;
	}

	if (phpbb_seo.external_sub && current_domain.indexOf('.') >= 0) {
		current_domain = current_domain.replace(new RegExp(/^[a-z0-9_-]+\.([a-z0-9_-]+\.([a-z]{2,6}|[a-z]{2,3}\.[a-z]{2,3}))$/i), '$1');
	}

	if (phpbb_seo.ext_classes) {
		var extclass = new RegExp("(^|\s)(" + phpbb_seo.ext_classes + ")(\s|$)");
	}

	if (phpbb_seo.hashfix) {
		var basehref = document.getElementsByTagName('base')[0];

		if (basehref) {
			basehref = basehref.href;
			var hashtest = new RegExp("^(" + basehref + "|)#[a-z0-9_-]+$");
			var current_href = document.location.href.replace(/#[a-z0-9_-]+$/i, "");
		} else {
			phpbb_seo.hashfix = false;
		}
	}

	var hrefels = document.getElementsByTagName("a");
	var hrefelslen = hrefels.length;

	for (var i = 0; i < hrefelslen; i++) {
		var el = hrefels[i];
		var hrefinner = el.innerHTML.toLowerCase();

		if (el.onclick || (el.href == '') || (el.href.indexOf('javascript') >=0 ) || (el.href.indexOf('mailto') >=0 ) || (hrefinner.indexOf('<a') >= 0) ) {
			continue;
		}

		if (phpbb_seo.hashfix && el.hash && hashtest.test(el.href)) {
			el.href = current_href + el.hash;
		}

		if (phpbb_seo.external) {

			if ((el.href.indexOf(current_domain) >= 0) && !(phpbb_seo.ext_classes && extclass.test(el.className))) {
				continue;
			}
			$(el).on('click', function () { window.open(this.href); return false; });
		}
	}
};

if (phpbb_seo.external || phpbb_seo.hashfix) {
	$(document).ready(phpbb_seo.external_hrefs);
}
