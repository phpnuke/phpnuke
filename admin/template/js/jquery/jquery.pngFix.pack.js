/**
 * --------------------------------------------------------------------
 * jQuery-Plugin "pngFix"
 * Version: 1.1, 11.09.2007
 * by Andreas Eberhard, andreas.eberhard@gmail.com
 *                      http://jquery.andreaseberhard.de/
 *
 * Copyright (c) 2007 Andreas Eberhard
 * Licensed under GPL (http://www.opensource.org/licenses/gpl-license.php)
 */
(function(m) {
    jQuery.fn.pngFix = function(c) {
        c = jQuery.extend({
            blankgif: 'blank.gif'
        }, c);
		var browser = navigator.userAgent.toLowerCase();
		browser = browser.split('/');
        var e = (navigator.appName == "Microsoft Internet Explorer" && parseInt(navigator.appVersion) == 4 && navigator.appVersion.indexOf("MSIE 5.5") != -1);
        var f = (navigator.appName == "Microsoft Internet Explorer" && parseInt(navigator.appVersion) == 4 && navigator.appVersion.indexOf("MSIE 6.0") != -1);
        if (browser[0] == 'msie' && (e || f)) {
            jQuery(this).find("img[src$=.png]").each(function() {
                jQuery(this).attr('width', jQuery(this).width());
                jQuery(this).attr('height', jQuery(this).height());
                var a = '';
                var b = '';
                var g = (jQuery(this).attr('id')) ? 'id="' + jQuery(this).attr('id') + '" ' : '';
                var h = (jQuery(this).attr('class')) ? 'class="' + jQuery(this).attr('class') + '" ' : '';
                var i = (jQuery(this).attr('title')) ? 'title="' + jQuery(this).attr('title') + '" ' : '';
                var j = (jQuery(this).attr('alt')) ? 'alt="' + jQuery(this).attr('alt') + '" ' : '';
                var k = (jQuery(this).attr('align')) ? 'float:' + jQuery(this).attr('align') + ';' : '';
                var d = (jQuery(this).parent().attr('href')) ? 'cursor:hand;' : '';
                if (this.style.border) {
                    a += 'border:' + this.style.border + ';';
                    this.style.border = ''
                }
                if (this.style.padding) {
                    a += 'padding:' + this.style.padding + ';';
                    this.style.padding = ''
                }
                if (this.style.margin) {
                    a += 'margin:' + this.style.margin + ';';
                    this.style.margin = ''
                }
                var l = (this.style.cssText);
                b += '<span ' + g + h + i + j;
                b += 'style="position:relative;white-space:pre-line;display:inline-block;background:transparent;' + k + d;
                b += 'width:' + jQuery(this).width() + 'px;height:' + jQuery(this).height() + 'px;';
                b += 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + jQuery(this).attr('src') + '\', sizingMethod=\'scale\');';
                b += l + '"></span>';
                if (a != '') {
                    b = '<span style="position:relative;display:inline-block;' + a + d + 'width:' + jQuery(this).width() + 'px;height:' + jQuery(this).height() + 'px;">' + b + '</span>'
                }
                jQuery(this).hide();
                jQuery(this).after(b)
            });
            jQuery(this).find("*").each(function() {
                var a = jQuery(this).css('background-image');
                if (a.indexOf(".png") != -1) {
                    var b = a.split('url("')[1].split('")')[0];
                    jQuery(this).css('background-image', 'none');
                    jQuery(this).get(0).runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + b + "',sizingMethod='scale')"
                }
            });
            jQuery(this).find("input[src$=.png]").each(function() {
                var a = jQuery(this).attr('src');
                jQuery(this).get(0).runtimeStyle.filter = 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + a + '\', sizingMethod=\'scale\');';
                jQuery(this).attr('src', c.blankgif)
            })
        }
        return jQuery
    }
})(jQuery);