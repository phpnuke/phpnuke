/* global quickreply, form_name, text_name */
/**
 * Функция перевода транслита в нормальный русский язык добавлена 01/01/2002 Андреем Мазлиным
 * Updated in 2015 and 2017.
 */
function translit() {
	'use strict';

	var buf = '', selection = null, txtarea = document.forms[form_name].elements[text_name], i, start = 0, end = 0;

	if (document.selection) {
		selection = document.selection.createRange();
		buf = selection.text;
	} else if (typeof (txtarea.selectionStart) === "number") {
		start = txtarea.selectionStart;
		end = txtarea.selectionEnd;
		if (start !== end) {
			buf = txtarea.value.substr(start, end - start);
		} else {
			buf = txtarea.value;
		}
	} else {
		buf = txtarea.value;
	}

	for (i = 0; i < quickreply.translit.langFromCaps.length; i++) {
		buf = buf.replace(new RegExp(quickreply.translit.langFromCaps[i], 'g'), quickreply.translit.langToCaps[i]);
	}

	for (i = 0; i < quickreply.translit.langFrom.length; i++) {
		buf = buf.replace(new RegExp(quickreply.translit.langFrom[i], 'g'), quickreply.translit.langTo[i]);
	}

	if (selection) {
		selection.text = buf;
	} else {
		if (start !== end) {
			txtarea.value = txtarea.value.substr(0, start) + buf + txtarea.value.substr(end);
		} else {
			txtarea.value = buf;
		}
	}
	txtarea.focus();
}
