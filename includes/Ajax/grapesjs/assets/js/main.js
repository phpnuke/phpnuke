const { default: mylang } = await import(langPath);
var editor = grapesjs.init({
	avoidInlineStyle: 1,
	height: '100%',
	container: '#gjs',
	fromElement: 1,
	showOffsets: 1,
	allowScripts: 1,
	assetManager: {
		upload: false,
		embedAsBase64: 1,
		assets: grapes_images
	},
	// Default configurations
	storageManager: {
		id: 'gjs-', // Prefix identifier that will be used on parameters
		type: 'remote', //type: 'local', type: 'remote',Type of the storage
		autosave: true, // Store data automatically
		autoload: true, // Autoload stored data on init
		urlStore: g_admin_file+'.php?op=grapesjs&mode=store&post_id='+g_post_id+'&post_type='+g_post_type+'',
		urlLoad: g_admin_file+'.php?op=grapesjs&mode=load&post_id='+g_post_id+'&post_type='+g_post_type+'',
		params: {'csrf_token': g_pn_csrf_token },
		contentTypeJson: true,
		storeComponents: true,
		storeStyles: true,
		storeHtml: true,
		storeCss: true,
		stepsBeforeSave: 5 // If autosave enabled, indicates how many changes are necessary before store method is triggered
	},
	selectorManager: {
		componentFirst: true
	},
	styleManager: {
		sectors: []
	},
	plugins: [
		'grapesjs-swiper-slider', 
		'grapesjs-tui-image-editor', 
		'grapesjs-lory-slider', 
		'grapesjs-tabs', 
		'grapesjs-typed',
		'gjs-plugin-ckeditor',
		'grapesjs-script-editor',
		'grapesjs-component-code-editor',
		'gjs-preset-webpage',  
		'grapesjs-custom-code', 
		'grapesjs-touch', 
		'grapesjs-tooltip', 
		'grapesjs-parser-postcss',
		'grapesjs-page-break', 
		//'grapesjs-uikit', 
		//'grapesjs-bootstrap-elements', 
		//'gjs-navbar', 
		//'gjs-component-countdown', 
		//'grapesjs-style-bg',
	],
	pluginsOpts: {
		'grapesjs-swiper-slider': {},
		'grapesjs-tui-image-editor': {
			height: '600px',
			script: [
				// 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.6.7/fabric.min.js',
				'https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js', 'https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.js', 'https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.js'
			],
			style: ['https://uicdn.toast.com/tui-color-picker/v2.2.7/tui-color-picker.min.css', 'https://uicdn.toast.com/tui-image-editor/v3.15.2/tui-image-editor.min.css', ],
		},
		'grapesjs-lory-slider': {
			sliderBlock: {
				category: 'Extra'
			}
		},
		'grapesjs-tabs': {
			tabsBlock: {
				category: 'Extra'
			}
		},
		'grapesjs-typed': {
			block: {
				category: 'Extra',
				content: {
					type: 'typed',
					'type-speed': 40,
					strings: ['Text row one', 'Text row two', 'Text row three', ],
				}
			}
		},
		'gjs-plugin-ckeditor': {
			position: 'center',
			options: {
				startupFocus: true,
				extraAllowedContent: '*(*);*{*}', // Allows any class and any inline style
				allowedContent: true, // Disable auto-formatting, class removing, etc.
				enterMode: CKEDITOR.ENTER_BR,
				extraPlugins: 'sharedspace,justify,colorbutton,panelbutton,font',
				toolbar: [{
						name: 'styles',
						items: ['Font', 'FontSize']
					},
					['Bold', 'Italic', 'Underline', 'Strike'], {
						name: 'paragraph',
						items: ['NumberedList', 'BulletedList']
					},
					/* {
							  name: 'headings',
							  items: ['H1', 'H2', 'H3', 'H4', 'H5', 'H6']
						  },*/
					{
						name: 'links',
						items: ['Link', 'Unlink']
					}, {
						name: 'colors',
						items: ['TextColor', 'BGColor']
					}
				]
			}
		},
		'grapesjs-script-editor': {
			toolbarIcon: '<i class="fa fa-puzzle-piece"></i>'
		},
		'grapesjs-component-code-editor': {
			panelId: 'views-container'
		},
		'gjs-preset-webpage':{
			modalImportTitle: 'Import Template',
			modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
			modalImportContent: function (editor) {
				return editor.getHtml() + '<style>' + editor.getCss() + '</style>'
			},
			filestackOpts: null, //{ key: 'AYmqZc2e8RLGLE7TGkX3Hz' },
			aviaryOpts: false,
			blocksBasicOpts: {
				flexGrid: 1
			},
			customStyleManager: [{
				name: 'General',
				properties: [{
					extend: 'float',
					type: 'radio',
					default: 'none',
					options: [{
						value: 'none',
						className: 'fa fa-times'
					}, {
						value: 'left',
						className: 'fa fa-align-left'
					}, {
						value: 'right',
						className: 'fa fa-align-right'
					}],
				}, 'display', {
					extend: 'position',
					type: 'select'
				}, 'top', 'right', 'left', 'bottom', ],
			}, {
				name: 'Dimension',
				open: false,
				properties: ['width', {
					id: 'flex-width',
					type: 'integer',
					name: 'Width',
					units: ['px', '%'],
					property: 'flex-basis',
					toRequire: 1,
				}, 'height', 'max-width', 'min-height', 'margin', 'padding'],
			}, {
				name: 'Typography',
				open: false,
				properties: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', {
					extend: 'text-align',
					options: [{
						id: 'left',
						label: 'Left',
						className: 'fa fa-align-left'
					}, {
						id: 'center',
						label: 'Center',
						className: 'fa fa-align-center'
					}, {
						id: 'right',
						label: 'Right',
						className: 'fa fa-align-right'
					}, {
						id: 'justify',
						label: 'Justify',
						className: 'fa fa-align-justify'
					}],
				}, {
					property: 'text-decoration',
					type: 'radio',
					default: 'none',
					options: [{
						id: 'none',
						label: 'None',
						className: 'fa fa-times'
					}, {
						id: 'underline',
						label: 'underline',
						className: 'fa fa-underline'
					}, {
						id: 'line-through',
						label: 'Line-through',
						className: 'fa fa-strikethrough'
					}],
				}, 'text-shadow'],
			}, {
				name: 'Decorations',
				open: false,
				properties: ['opacity', 'border-radius', 'border', 'box-shadow', 'background', // { id: 'background-bg', property: 'background', type: 'bg' }
				],
			}, {
				name: 'Extra',
				open: false,
				buildProps: ['transition', 'perspective', 'transform'],
			}, {
				name: 'Flex',
				open: false,
				properties: [{
					name: 'Flex Container',
					property: 'display',
					type: 'select',
					defaults: 'block',
					list: [{
						value: 'block',
						name: 'Disable'
					}, {
						value: 'flex',
						name: 'Enable'
					}],
				}, {
					name: 'Flex Parent',
					property: 'label-parent-flex',
					type: 'integer',
				}, {
					name: 'Direction',
					property: 'flex-direction',
					type: 'radio',
					defaults: 'row',
					list: [{
						value: 'row',
						name: 'Row',
						className: 'icons-flex icon-dir-row',
						title: 'Row',
					}, {
						value: 'row-reverse',
						name: 'Row reverse',
						className: 'icons-flex icon-dir-row-rev',
						title: 'Row reverse',
					}, {
						value: 'column',
						name: 'Column',
						title: 'Column',
						className: 'icons-flex icon-dir-col',
					}, {
						value: 'column-reverse',
						name: 'Column reverse',
						title: 'Column reverse',
						className: 'icons-flex icon-dir-col-rev',
					}],
				}, {
					name: 'Justify',
					property: 'justify-content',
					type: 'radio',
					defaults: 'flex-start',
					list: [{
						value: 'flex-start',
						className: 'icons-flex icon-just-start',
						title: 'Start',
					}, {
						value: 'flex-end',
						title: 'End',
						className: 'icons-flex icon-just-end',
					}, {
						value: 'space-between',
						title: 'Space between',
						className: 'icons-flex icon-just-sp-bet',
					}, {
						value: 'space-around',
						title: 'Space around',
						className: 'icons-flex icon-just-sp-ar',
					}, {
						value: 'center',
						title: 'Center',
						className: 'icons-flex icon-just-sp-cent',
					}],
				}, {
					name: 'Align',
					property: 'align-items',
					type: 'radio',
					defaults: 'center',
					list: [{
						value: 'flex-start',
						title: 'Start',
						className: 'icons-flex icon-al-start',
					}, {
						value: 'flex-end',
						title: 'End',
						className: 'icons-flex icon-al-end',
					}, {
						value: 'stretch',
						title: 'Stretch',
						className: 'icons-flex icon-al-str',
					}, {
						value: 'center',
						title: 'Center',
						className: 'icons-flex icon-al-center',
					}],
				}, {
					name: 'Flex Children',
					property: 'label-parent-flex',
					type: 'integer',
				}, {
					name: 'Order',
					property: 'order',
					type: 'integer',
					defaults: 0,
					min: 0
				}, {
					name: 'Flex',
					property: 'flex',
					type: 'composite',
					properties: [{
						name: 'Grow',
						property: 'flex-grow',
						type: 'integer',
						defaults: 0,
						min: 0
					}, {
						name: 'Shrink',
						property: 'flex-shrink',
						type: 'integer',
						defaults: 0,
						min: 0
					}, {
						name: 'Basis',
						property: 'flex-basis',
						type: 'integer',
						units: ['px', '%', ''],
						unit: '',
						defaults: 'auto',
					}],
				}, {
					name: 'Align',
					property: 'align-self',
					type: 'radio',
					defaults: 'auto',
					list: [{
						value: 'auto',
						name: 'Auto',
					}, {
						value: 'flex-start',
						title: 'Start',
						className: 'icons-flex icon-al-start',
					}, {
						value: 'flex-end',
						title: 'End',
						className: 'icons-flex icon-al-end',
					}, {
						value: 'stretch',
						title: 'Stretch',
						className: 'icons-flex icon-al-str',
					}, {
						value: 'center',
						title: 'Center',
						className: 'icons-flex icon-al-center',
					}],
				}]
			}],
		},
		'grapesjs-custom-code': {
			blockLabel: 'Custom code',
			category: 'Extra',
			droppable: false,
			modalTitle: 'Insert your code',
			buttonLabel: 'Save'
		},
		/*'grapesjs-bootstrap-elements': {
			blocks: {},
			blockCategories: {},
			labels: {},
			gridDevicesPanel: true,
			formPredefinedActions: [{
				name: 'Contact',
				value: '/contact'
			}, {
				name: 'landing',
				value: '/landing'
			}]
		},*/
		//'gjs-navbar': {},
		//'grapesjs-uikit': {},
	},
	canvas: {
		styles: canvas_styles,
		scripts: canvas_scripts,
	},
	i18n : {
		locale: 'en',
		localeFallback: 'en',
		messages: {mylang}
	}
});

editor.I18n.setLocale('mylang');
/*editor.I18n.addMessages({
	mylang: {
		styleManager: {
			properties: {
				'background-repeat': 'Repeat',
				'background-position': 'Position',
				'background-attachment': 'Attachment',
				'background-size': 'Size',
			}
		},
	}
});*/
var pn = editor.Panels;
var modal = editor.Modal;
var cmdm = editor.Commands;
const am = editor.AssetManager;
const comps = editor.DomComponents;

cmdm.add('canvas-clear', function () {
	if (confirm('Are you sure to clean the canvas?')) {
		comps.clear();
		setTimeout(function () {
			localStorage.clear()
		}, 0)
	}
});
cmdm.add('set-device-desktop', {
	run: function (ed) {
		ed.setDevice('Desktop')
	},
	stop: function () {},
});
cmdm.add('set-device-tablet', {
	run: function (ed) {
		ed.setDevice('Tablet')
	},
	stop: function () {},
});
cmdm.add('set-device-mobile', {
	run: function (ed) {
		ed.setDevice('Mobile portrait')
	},
	stop: function () {},
});

const panelViews = pn.addPanel({
	id: 'views'
});
panelViews.get('buttons').add([{
	attributes: {
		title: 'Open Code'
	},
	className: 'fa fa-file-code-o',
	command: 'open-code',
	togglable: false, //do not close when button is clicked again
	id: 'open-code'
}]);
/*
editor.BlockManager.add('testBlock', {
	label: 'Block',
	attributes: {
		class: 'gjs-fonts gjs-f-b1'
	},
	content: '<div style="padding-top:50px; padding-bottom:50px; text-align:center">Test block</div>'
});
*/
// Add info command
var mdlClass = 'gjs-mdl-dialog-sm';
var infoContainer = document.getElementById('info-panel');
cmdm.add('open-info', function () {
	var mdlDialog = document.querySelector('.gjs-mdl-dialog');
	mdlDialog.className += ' ' + mdlClass;
	infoContainer.style.display = 'block';
	modal.setTitle('About this demo');
	modal.setContent(infoContainer);
	modal.open();
	modal.getModel().once('change:open', function () {
		mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
	})
});
pn.addButton('options', {
	id: 'open-info',
	className: 'fa fa-question-circle',
	command: function () {
		editor.runCommand('open-info')
	},
	attributes: {
		'title': 'About',
		'data-tooltip-pos': 'bottom',
	},
});

// Simple warn notifier
var origWarn = console.warn;
toastr.options = {
	closeButton: true,
	preventDuplicates: true,
	showDuration: 250,
	hideDuration: 150
};
console.warn = function (msg) {
	if (msg.indexOf('[undefined]') == -1) {
		toastr.warning(msg);
	}
	origWarn(msg);
};

// Add and beautify tooltips
[
	['sw-visibility', 'Show Borders'],
	['preview', 'Preview'],
	['fullscreen', 'Fullscreen'],
	['export-template', 'Export'],
	['undo', 'Undo'],
	['redo', 'Redo'],
	['gjs-open-import-webpage', 'Import'],
	['canvas-clear', 'Clear canvas']
].forEach(function (item) {
	pn.getButton('options', item[0]).set('attributes', {
		title: item[1],
		'data-tooltip-pos': 'bottom'
	});
});
[
	['open-sm', 'Style Manager'],
	['open-layers', 'Layers'],
	['open-blocks', 'Blocks']
].forEach(function (item) {
	pn.getButton('views', item[0]).set('attributes', {
		title: item[1],
		'data-tooltip-pos': 'bottom'
	});
});
var titles = document.querySelectorAll('*[title]');
for (var i = 0; i < titles.length; i++) {
	var el = titles[i];
	var title = el.getAttribute('title');
	title = title ? title.trim() : '';
	if (!title) break;
	el.setAttribute('data-tooltip', title);
	el.setAttribute('title', '');
}
// Show borders by default
pn.getButton('options', 'sw-visibility').set('active', 1);
// Store and load events
editor.on('storage:load', function (e) {
	console.log('Loaded ', e)
});
editor.on('storage:store', function (e) {
	console.log('Stored ', e)
});

cmdm.add('dashboard', {
	run: function (em, sender) {
		sender.set('active', true);
		dashboardPage();
	}
});
pn.addButton('options', [{
	id: 'dashboard',
	className: 'fa fa-tachometer',
	command: 'dashboard',
	attributes: {
		title: 'Dashboard',
		'data-tooltip-pos': 'bottom'
	}
}]);

function dashboardPage() {
	let url = g_admin_file+'.php';
	location.replace(url);
}
pn.addButton('options', [{
	id: 'save-page',
	className: 'fa fa-floppy-o',
	command: 'save-page',
	attributes: {
		title: 'Save page',
		'data-tooltip-pos': 'bottom'
	}
}]);
cmdm.add('save-page', {
	run: function (em, sender) {
		sender.set('active', true);
		saveContent();
	}
});

function saveContent() {
	let html = editor.getHtml(); //get html content of document
	let css = editor.getCss(); //get css content of document
	let styles = editor.getStyle(); //get style content of document
	let components = editor.getComponents(); //get component content of document
	let assets = am.getAll(); //get component content of document
	// Get edit field value
	let new_data = {
		'gjs-assets':assets,
		'gjs-components':components,
		'gjs-css':css,
		'gjs-html':html,
		'gjs-styles':styles		
	};
	
	$.ajax({
		url: g_admin_file+'.php',
		type: 'post',
		data: {
			post_id: g_post_id,
			post_type: g_post_type,
			csrf_token: g_pn_csrf_token,
			mode: 'save',
			op: 'grapesjs',
			gjs_data: JSON.stringify(new_data)
		}
	}).done(function (rsp) {
		alert(rsp);
	});
}
pn.addButton('options', [{
	id: 'refresh-page',
	className: 'fa fa-refresh',
	command: 'refresh-page',
	attributes: {
		title: 'Refresh page',
		'data-tooltip-pos': 'bottom'
	}
}]);
cmdm.add('refresh-page', {
	run: function (em, sender) {
		sender.set('active', true);
		refreshContent();
	}
});

function refreshContent() {
	location.reload();
}
pn.addButton('options', [{
	id: 'delete-page',
	className: 'fa fa-remove',
	command: 'delete-page',
	attributes: {
		title: 'Delete page',
		'data-tooltip-pos': 'bottom'
	}
}]);
cmdm.add('delete-page', {
	run: function (em, sender) {
		sender.set('active', true);
		deletePage();
	}
});

function deletePage() {
	if(confirm('Are you sure to delete the page ?'))
	{
		$.ajax({
			url: g_admin_file+'.php',
			type: 'post',
			data: {
				post_id: g_post_id,
				post_type: g_post_type,
				csrf_token: g_pn_csrf_token,
				mode: 'delete',
				op: 'grapesjs'
			}
		}).done(function (rsp) {
			localStorage.clear();
			alert(rsp);
			setTimeout(function () {
				window.close();
			}, 0);
			
		});
	}
}
