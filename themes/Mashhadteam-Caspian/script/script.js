$(document).ready(function () {
	if ($(".selectpicker").length > 0) {
		$(".selectpicker").select2({
			dir: ((nuke_date == 3) ? "ltr" : "rtl")
		})
	}
	if ($('.btn-input-file').length > 0) {
		$('.btn-input-file').each(function () {
			var that = $(this);
			that.find('.btn-choose').on('click', function () {
				that.find('#uploadfile').click()
			});
			that.find('.btn-reset').on('click', function () {
				that.find('.uploadfile').val(null);
				that.find('.input-sub-file').val('')
			});
			that.find('.uploadfile').change(function () {
				that.find('.input-sub-file').val($(this).val())
			})
		})
	}
	$('a[class*=_tooltip]').tooltip({
		container:'body',
	});
	
	$('a[class*=_popover]').popover({
		container:'body',
		trigger: "hover"
	});
	$('.colour-switcher a').click(function () {
		var c = $(this).attr('href').replace('#', '');
		var cacheBuster = 4 * Math.floor(Math.random() * 6);
		$('.colour-switcher a').removeClass('active');
		$('.colour-switcher a.' + c).addClass('active');
		$('#colour-scheme').attr('href', phpnuke_url + 'themes/' + phpnuke_theme + '/style/colour-' + c + '.css')
	});
	$(".rating-load").jRating({
		bigStarsPath: phpnuke_url + 'themes/' + phpnuke_theme + '/images/stars.png',
		smallStarsPath: phpnuke_url + 'themes/' + phpnuke_theme + '/images/small.png',
		likesPath: phpnuke_url + 'themes/' + phpnuke_theme + '/images/like_dislike.png',
		pn_icon_Path: phpnuke_url + 'themes/' + phpnuke_theme + '/images/pn_icon.png',
		bigstarWidth: 23,
		bigstarHeight: 20,
		smallstarWidth: 12,
		smallstarHeight: 10,
		likesWidth: 20,
		likesHeight: 20,
		pnWidth: 24,
		pnHeight: 20,
		phpPath: 'index.php',
		type: 'big',
		step: !1,
		isDisabled: !1,
		showRateInfo: !0,
		canRateAgain: !1,
		sendRequest: !0,
		length: 5,
		decimalLength: 0,
		rateMax: 20,
		rateInfosX: -45,
		rateInfosY: 5,
		likesboxwidth: 80,
		likesboxheight: 20,
		pnboxwidth: 100,
		pnboxheight: 20,
		nbRates: 1 /** templates **/ ,
		like_theme: '<div class="like-dislike">\n\t<div class="like-box" id="like-box-{IDBOX}"><div class="like-count">\n\t<span id="like-count-{IDBOX}" style="font-weight:bold;font-size:12px;">{LIKE-COUNT}</span>\n</div>\n<div class="like">&nbsp;</div></div>\n<span class="like-loading" id="like-loading-{IDBOX}"><img src="{AJAX-LOADER-IMG-PATH}" /></span>\n<div class="dislike-box" id="dislike-box-{IDBOX}"><div class="dislike-count">\n\t<span id="dislike-count-{IDBOX}" style="font-weight:bold;font-size:12px;">{DISLIKE-COUNT}</span>\n</div>\n<div class="dislike">&nbsp;</div></div>\n</div>\n',
		pnrate_theme: '<div class="pnrate">\n\t<div class="positive-rate">&nbsp;</div>\n<div class="pnrate-count" id="pnrate-count-{IDBOX}" style="font-weight:bold;font-size:12px;color:{OLD_PNRATE_COLOR};">{OLD_PNRATE}</div>\n<span class="like-loading" id="like-loading-{IDBOX}"><img src="{AJAX-LOADER-IMG-PATH}" /></span>\n<div class="negative-rate">&nbsp;</div>\n</div>\n',
		onSuccess: function () {
			jSuccess(theme_languages.success_voted, {
				HorizontalPosition: 'center',
				VerticalPosition: 'top'
			})
		},
		onError: function () {
			alert(theme_languages.try_again)
		}
	});
	if ($(".calendar").length > 0) {
		$(".calendar").each(function () {
			var id = $(this).attr('id');
			var data_date = $(this).attr('data-date');
			if (typeof data_date !== typeof undefined && data_date !== !1) {
				var lang = (data_date == 1) ? "fa" : ((data_date == 2) ? "ar" : "");
				var cal_class = (data_date < 3) ? " ui-datepicker-rtl" : ""
			} else {
				var lang = ((nuke_date == 3) ? '' : ((nuke_date == 1) ? 'fa' : ((nuke_date == 2) ? 'ar' : '')));
				var cal_class = (nuke_date == 3) ? "" : " ui-datepicker-rtl"
			}
			$(this).datepicker({
				dateFormat: 'yy/mm/dd',
				changeMonth: !0,
				changeYear: !0,
				showOtherMonths: !0,
				regional: lang
			}).datepicker('widget').wrap('<div class="ll-skin-latoja' + cal_class + '"/>')
		})
	}
	setInterval(function () {
		var currentTime = new Date();
		var hours = currentTime.getHours();
		var minutes = currentTime.getMinutes();
		var seconds = currentTime.getSeconds();
		hours = (hours < 10 ? "0" : "") + hours;
		minutes = (minutes < 10 ? "0" : "") + minutes;
		seconds = (seconds < 10 ? "0" : "") + seconds;
		var currentTimeString = hours + ":" + minutes + ":" + seconds;
		$(".nukeclock").html(currentTimeString)
	}, 1000); /*$('a[href*="#"]:not([href="#"])').click(function(){if (location.pathname.replace(/^\//,'')==this.pathname.replace(/^\//,'')&&location.hostname==this.hostname){var target = $(this.hash);target=target.length?target:$('[name='+this.hash.slice(1)+']');if(target.length){$('html, body').animate({scrollTop:target.offset().top},100);return false;}}});*/
	$(".reply-comment").on('click', function (e) {
		e.preventDefault();
		$("#reply_pid").val($(this).data('cid'));
		main_parent = ($(this).data('main-parent') == 0) ? $(this).data('cid') : $(this).data('main-parent');
		$("#reply_main_parent").val(main_parent);
		$("#reply_to_html").html($(this).data('replylang') + ' ' + $(this).data('name') + ' : ' + $(this).data('message'));
		$([document.documentElement, document.body]).animate({
			scrollTop: ($("#reply_to_html").offset().top - 50)
		}, 500);
	});
});

function openwindow(url) {
	var openwindow_config = 'toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=400,height=200';
	window.open(url, "Copyright", "" + openwindow_config + "")
}

function costum_like(el, idBox, rate, db_table, db_table_var, c_votetype) {
	var oldlikesrate = $(el).attr('data-likes');
	$.post("index.php", {
		idBox: idBox,
		rate: rate,
		oldlikesrate: oldlikesrate,
		db_table: db_table,
		db_table_var: db_table_var,
		c_votetype: c_votetype,
		jaction: 'liking',
		csrf_token: pn_csrf_token
	}, function (data) {
		if (!data.error) {
			alert(data.message);
			response = data.server.split(",");
			$("#meta-heart-" + idBox + " span").html(response[0]);
			$(el).attr('data-likes', response[0] + "," + response[1]);
		} else {
			/*do anything*/ }
	}, 'json');
}
$(window).on('load', function () {
	$('body a[data-toggle="modal"]').each(function () {
		var modal_mode = $(this).data('mode');
		var modal_href = $(this).attr('href');
		if (modal_mode !== undefined) $(this).attr('href', modal_href + '?' + modal_mode + '=true');
	});
});
$('#sitemodal').on('hidden.bs.modal', function (e) {
	$(e.target).removeData("bs.modal").find(".modal-content").empty();
});
new ClipboardJS('.copytoClipboard');