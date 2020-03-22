/*!
 * Nestable jQuery Plugin - Copyright (c) 2012 David Bushell - http://dbushell.com/
 * Dual-licensed under the BSD or MIT licenses
 */
;(function($, window, document, undefined)
{
	var hasTouch = 'ontouchstart' in document;

	/**
	 * Detect CSS pointer-events property
	 * events are normally disabled on the dragging element to avoid conflicts
	 * https://github.com/ausi/Feature-detection-technique-for-pointer-events/blob/master/modernizr-pointerevents.js
	 */
	var hasPointerEvents = (function()
	{
		var el	= document.createElement('div'),
			docEl = document.documentElement;
		if (!('pointerEvents' in el.style)) {
			return false;
		}
		el.style.pointerEvents = 'auto';
		el.style.pointerEvents = 'x';
		docEl.appendChild(el);
		var supports = window.getComputedStyle && window.getComputedStyle(el, '').pointerEvents === 'auto';
		docEl.removeChild(el);
		return !!supports;
	})();
	var eStart  = hasTouch ? 'touchstart'  : 'mousedown',
		eMove   = hasTouch ? 'touchmove'   : 'mousemove',
		eEnd	= hasTouch ? 'touchend'	: 'mouseup';
		eEnd	= hasTouch ? 'touchend'	: 'mouseup',
		eCancel = hasTouch ? 'touchcancel' : 'mouseup';
		
	var defaults = {
			listNodeName	: 'ol',
			itemNodeName	: 'li',
			itemUniqueId	: 'id',
			rootClass	   : 'dd',
			listClass	   : 'dd-list',
			itemClass	   : 'dd-item',
			itemOptions	   : 'dd-options',
			itemOptionsLi	   : 'dd-itemOption',
			dragClass	   : 'dd-dragel',
			handleClass	 : 'dd-handle',
			collapsedClass  : 'dd-collapsed',
			placeClass	  : 'dd-placeholder',
			noDragClass	 : 'dd-nodrag',
			emptyClass	  : 'dd-empty',
			removeClassName	  : 'removeitem',
			itemOptionsHtml   : '',
			expandBtnHTML   : '<button data-action="expand" type="button">Expand</button>',
			collapseBtnHTML : '<button data-action="collapse" type="button">Collapse</button>',
			editBtnHTML   : '<span data-action="edit" class="operations"></span>',
			removeBtnHTML : '<span data-action="remove" class="operations"></span>',
			group		   : 0,
			maxDepth		: 5,
			threshold	   : 20,
			allowDecrease   : true,
			allowIncrease   : true,
			scroll			  : false,
			scrollSensitivity   : 1,
			scrollSpeed		 : 5,
			scrollTriggers	  : {
				top: 40,
				left: 40,
				right: -40,
				bottom: -40
			}
		};

	function Plugin(element, options)
	{
		this.w  = $(document);
		this.el = $(element);
		this.rtl = this.el.css('direction') == "rtl";
		//console.log('this.rtl',this.rtl);
		this.options = $.extend({}, defaults, options);
		this.init();
	}

	Plugin.prototype = {

		init: function()
		{
			var list = this;

			list.reset();

			list.el.data('nestable-group', this.options.group);

			list.placeEl = $('<div class="' + list.options.placeClass + '"/>');

			$.each(this.el.find(list.options.itemNodeName), function(k, el) {
				list.addButton($(el));
				list.setParent($(el));
			});

			list.el.on('click', 'button, .operations, .editoptions', function(e) {
				if (list.dragEl) {
					return;
				}
				var target = $(e.currentTarget),
					action = target.data('action'),
					item   = target.parent(list.options.itemNodeName);

				if (action === 'collapse') {
					list.collapseItem(item);
				}
				if (action === 'expand') {
					list.expandItem(item);
				}
				if (action === 'edit') {
					list.editItem(item);
				}
				if (action === 'save') {
					item = target.closest('li');
					list.saveItem(item);
				}
				if (action === 'remove') {
					list.removeItem(item);
				}
			});

			var onStartEvent = function(e)
			{
				if (e.isPropagationStopped()) {
					return
				}
				var handle = $(e.target);
				if (!handle.hasClass(list.options.handleClass)) {
					if (handle.closest('.' + list.options.noDragClass).length) {
						return;
					}
					handle = handle.closest('.' + list.options.handleClass);
				}

				if (!handle.length || list.dragEl) {
					return;
				}

				list.isTouch = /^touch/.test(e.type);
				if (list.isTouch && e.touches.length !== 1) {
					return;
				}

				e.preventDefault();
				list.dragStart(e.touches ? e.touches[0] : e);
			};

			var onMoveEvent = function(e)
			{
				if (list.dragEl) {
					e.preventDefault();
					list.dragMove(e.touches ? e.touches[0] : e);
				}
			};

			var onEndEvent = function(e)
			{
				if (list.dragEl) {
					e.preventDefault();
					list.dragStop(e.touches ? e.touches[0] : e);
				}
			};

			if (hasTouch) {
				list.el[0].addEventListener('touchstart', onStartEvent, false);
				window.addEventListener('touchmove', onMoveEvent, false);
				window.addEventListener('touchend', onEndEvent, false);
				window.addEventListener('touchcancel', onEndEvent, false);
			}

			list.el.on('mousedown', onStartEvent);
			list.w.on('mousemove', onMoveEvent);
			list.w.on('mouseup', onEndEvent);

		},
	
		add: function(el)
		{
			var list = this,
				opt = list.options,
				new_el = $('<'+opt.itemNodeName+'>');
				
			if(!el.id || !el.text)
			{
				return;
			}

			new_el.addClass(opt.itemClass).append($('<div>').addClass(opt.handleClass).text(el.text));
			new_el.append($('<div>').addClass(opt.itemOptions));
			
			new_el.attr('data-'+opt.itemUniqueId, el.id);
			list.addButton(new_el);
			for (var key in el)
			{
				if (el.hasOwnProperty(key) && key != 'id' && key != 'text')
				{
					new_el.attr('data-'+key, el[key]);
				}
			}
			
			if(list.el.find(opt.itemNodeName).length == 0)
				list.el.find(opt.listNodeName).html(new_el);
			else
				list.el.find(opt.itemNodeName).last().after(new_el);
		},
		
		serialize: function()
		{
			var data,
				depth = 0,
				list  = this,
				step  = function(level, depth)
				{
					var array = [ ],
						items = level.children(list.options.itemNodeName);
					items.each(function()
					{
						var li   = $(this),
							item = $.extend({}, li.data()),
							sub  = li.children(list.options.listNodeName);
							
						if (sub.length) {
							item.children = step(sub, depth + 1);
						}
						array.push(item);
					});
					return array;
				};
			data = step(list.el.find(list.options.listNodeName).first(), depth);
			return data;
		},

		serialise: function()
		{
			return this.serialize();
		},

		reset: function()
		{
			this.mouse = {
				offsetX   : 0,
				offsetY   : 0,
				startX	: 0,
				startY	: 0,
				lastX	 : 0,
				lastY	 : 0,
				nowX	  : 0,
				nowY	  : 0,
				distX	 : 0,
				distY	 : 0,
				dirAx	 : 0,
				dirX	  : 0,
				dirY	  : 0,
				lastDirX  : 0,
				lastDirY  : 0,
				distAxX   : 0,
				distAxY   : 0
			};
			this.isTouch	= false;
			this.moving	 = false;
			this.dragEl	 = null;
			this.dragRootEl = null;
			this.dragDepth  = 0;
			this.dragLevel  = 0;
			this.hasNewRoot = false;
			this.pointEl	= null;
		},

		removeItem: function(li)
		{
			var cssObj = {
				opacity: 0,
				'background-color': '#ff7575'
			}
			var list  = this;

			var sub_lists = li.find(this.options.itemNodeName);
			
			if (sub_lists.length) {
				li.find(this.options.itemNodeName).data('remove', 'true');
			}
			
			li.animate(cssObj, 200 ,function() {
				$(this).data('remove', 'true').addClass(list.options.removeClassName).hide();
				list.el.trigger('change', [li]);
			});
			li.trigger('change');
		},

		editItem: function(li)
		{
			var list = this,
				opt = list.options;
			var itemData = li.data();
			
			$("."+opt.itemOptions).each(function(){
				if($(this).parent().data(opt.itemUniqueId) != li.data(opt.itemUniqueId))
				{
					$(this).css({'display':'none'}).empty();
				}
			});			
			
			if(li.find("."+opt.itemOptions).first().css('display') != 'block')
			{
				li.find("."+opt.itemOptions).first().html(opt.itemOptionsHtml).toggle('blind', 300);
				
				li.find(".styledselect-select-dialog").selectmenu();
				
				$.each(itemData, function(key, value)
				{
					if(key == 'status')
					{
						li.find(".styledselect-select-dialog option").each(function()
						{
							if($(this).val() == value)
							{
								$(this).attr("selected",'selected');
								li.find(".styledselect-select-dialog").selectmenu( "destroy" ).selectmenu();
							}
						});
					}
					if(key != opt.itemUniqueId && key != 'status')
					{
						$("#itemOption-"+key).val(value);
					}
				});
			}
			else
				li.find("."+opt.itemOptions).first().toggle('blind', 300, function()
				{
					li.find("."+opt.itemOptions).first().empty();
				});
			this.el.trigger('change', [li]);
			li.trigger('change');
		},

		saveItem: function(li)
		{
			var list = this,
				opt = list.options;
			var itemData = li.data();
						
			li.find('.'+opt.itemOptionsLi).each(function(){
				var item_value = $(this).val();
				
				var item_option_id = $(this).attr('id');
				item_option_id = item_option_id.replace('itemOption-','');
				li.attr("data-"+item_option_id,item_value);
				li.data(item_option_id,item_value);
			});			
			
			var sub_lists = li.find(this.options.itemNodeName);
			
			if (sub_lists.length && $('#itemOption-status').val() == 0) {
				li.find(this.options.itemNodeName).data('status', 0);
			}
			
			li.find("."+opt.itemOptions).first().toggle('blind', 300, function()
			{
				li.find("."+opt.itemOptions).first().empty();
			});
			
			this.el.trigger('change', [li]);
			li.trigger('change');
		},

		expandItem: function(li)
		{
			li.removeClass(this.options.collapsedClass);
			li.children('[data-action="expand"]').hide();
			li.children('[data-action="collapse"]').show();
			li.children(this.options.listNodeName).show();
			this.el.trigger('expand', [li]);
			li.trigger('expand');
		},

		collapseItem: function(li)
		{
			var lists = li.children(this.options.listNodeName);
			if (lists.length) {
				li.addClass(this.options.collapsedClass);
				li.children('[data-action="collapse"]').hide();
				li.children('[data-action="expand"]').show();
				li.children(this.options.listNodeName).hide();
			}
			this.el.trigger('collapse', [li]);
			li.trigger('collapse');
		},

		expandAll: function()
		{
			var list = this;
			list.el.find(list.options.itemNodeName).each(function() {
				list.expandItem($(this));
			});
		},

		collapseAll: function()
		{
			var list = this;
			list.el.find(list.options.itemNodeName).each(function() {
				list.collapseItem($(this));
			});
		},
		
		isParent: function(li)
		{
			return (li.find('button[data-action]').length != 0);
		},
		
		addButton: function(li)
		{
			li.prepend($(this.options.editBtnHTML));
			li.prepend($(this.options.removeBtnHTML));
		},

		setParent: function(li)
		{
			if (!this.isParent(li)) {
				if (li.children(this.options.listNodeName).length) {
					li.prepend($(this.options.expandBtnHTML));
					li.prepend($(this.options.collapseBtnHTML));
				}
				li.children('[data-action="expand"]').hide();
			}
		},

		unsetParent: function(li)
		{
			li.removeClass(this.options.collapsedClass);
			li.children('button[data-action]').remove();
			li.children(this.options.listNodeName).remove();
		},

		dragStart: function(e)
		{
			var mouse	= this.mouse,
				target   = $(e.target),
				// dragItem = target.closest(this.options.itemNodeName);
				dragItem = target.closest('.' + this.options.handleClass).closest(this.options.itemNodeName);

				this.handle = target.closest('.' + this.options.handleClass);
				mouse.handleOffsetX = e.pageX - this.handle.offset().left;
				mouse.handleOffsetY = e.pageY - this.handle.offset().top;
				this.target_width = this.handle.width(); // for rtl
			
			this.placeEl.css('height', dragItem.height());

			mouse.offsetX = e.offsetX !== undefined ? e.offsetX : e.pageX - target.offset().left;
			mouse.offsetY = e.offsetY !== undefined ? e.offsetY : e.pageY - target.offset().top;
			mouse.startX = mouse.lastX = e.pageX;
			mouse.startY = mouse.lastY = e.pageY;

			this.dragLevel = dragItem.parents(this.options.listNodeName).length;
			this.dragRootEl = this.el;

			this.dragEl = $(document.createElement(this.options.listNodeName)).addClass(this.options.listClass + ' ' + this.options.dragClass);
			this.dragEl.css('width', dragItem.width());

			dragItem.after(this.placeEl);
			dragItem[0].parentNode.removeChild(dragItem[0]);
			dragItem.appendTo(this.dragEl);

			var rtlFix = 0;
			if( this.rtl )
				rtlFix = this.dragEl.width() - this.target_width;
			
			// console.log( 'rtl:', this.rtl );
			// console.log( 'e.pageX: ', e.pageX, 'e.pageY: ', e.pageY, 'mouse.offsetX: ', mouse.offsetX, 'mouse.offsetY: ', mouse.offsetY );
			// console.log( 'left:', e.pageX - mouse.offsetX + rtlFix, 'top:', e.pageY - mouse.offsetY , 'rtlFix:', + rtlFix, 'this.dragEl.W:', this.dragEl.width(), 'target.w', target_width );
			// console.log('target:', target);
			// console.log('target.w',target_width );
			
			
			$(document.body).append(this.dragEl);
			this.dragEl.css({
				//'left' : e.pageX - mouse.handleOffsetX - rtlFix,
				//'top'  : e.pageY - mouse.handleOffsetY
				'left' : e.pageX - mouse.handleOffsetX - 1,
				'top'  : e.pageY - mouse.handleOffsetY -1
			});
			// total depth of dragging item
			var i, depth,
				items = this.dragEl.find(this.options.itemNodeName);
			for (i = 0; i < items.length; i++) {
				depth = $(items[i]).parents(this.options.listNodeName).length;
				if (depth > this.dragDepth) {
					this.dragDepth = depth;
				}
			}
		},

		dragStop: function(e)
		{
			var el = this.dragEl.children(this.options.itemNodeName).first();
			el[0].parentNode.removeChild(el[0]);
			this.placeEl.replaceWith(el);

			this.dragEl.remove();
			this.el.trigger('change');
			if (this.hasNewRoot) {
				this.dragRootEl.trigger('change');
			}
			this.reset();
		},

		dragMove: function(e)
		{
			var list, parent, prev, next, depth,
				opt   = this.options,
				mouse = this.mouse;

			var rtlFix = 0;
			if( this.rtl )
				rtlFix = this.dragEl.width() - this.target_width;
				
			this.dragEl.css({
				//'left' : e.pageX - mouse.handleOffsetX - rtlFix,
				//'top'  : e.pageY - mouse.handleOffsetY
				'left' : e.pageX - mouse.handleOffsetX - 1,
				'top'  : e.pageY - mouse.handleOffsetY - 1
			});

			// mouse position last events
			mouse.lastX = mouse.nowX;
			mouse.lastY = mouse.nowY;
			// mouse position this events
			mouse.nowX  = e.pageX;
			mouse.nowY  = e.pageY;
			// distance mouse moved between events
			mouse.distX = mouse.nowX - mouse.lastX;
			mouse.distY = mouse.nowY - mouse.lastY;
			// direction mouse was moving
			mouse.lastDirX = mouse.dirX;
			mouse.lastDirY = mouse.dirY;
			// direction mouse is now moving (on both axis)
			mouse.dirX = mouse.distX === 0 ? 0 : mouse.distX > 0 ? 1 : -1;
			mouse.dirY = mouse.distY === 0 ? 0 : mouse.distY > 0 ? 1 : -1;
			// axis mouse is now moving on
			var newAx   = Math.abs(mouse.distX) > Math.abs(mouse.distY) ? 1 : 0;

			// do nothing on first move
			if (!mouse.moving) {
				mouse.dirAx  = newAx;
				mouse.moving = true;
				return;
			}

			//Do scrolling
			if (opt.scroll) {
				var scrolled = false;
				var scrollParent = this.el.scrollParent()[0];
				if(scrollParent != document && scrollParent.tagName != 'HTML') {
					if((opt.scrollTriggers.bottom + scrollParent.offsetHeight) - e.pageY < opt.scrollSensitivity)
						scrollParent.scrollTop = scrolled = scrollParent.scrollTop + opt.scrollSpeed;
					else if(e.pageY - opt.scrollTriggers.top < opt.scrollSensitivity)
						scrollParent.scrollTop = scrolled = scrollParent.scrollTop - opt.scrollSpeed;
					if((opt.scrollTriggers.right + scrollParent.offsetWidth) - e.pageX < opt.scrollSensitivity)
						scrollParent.scrollLeft = scrolled = scrollParent.scrollLeft + opt.scrollSpeed;
					else if(e.pageX - opt.scrollTriggers.left < opt.scrollSensitivity)
						scrollParent.scrollLeft = scrolled = scrollParent.scrollLeft - opt.scrollSpeed;
				} else {
					if(e.pageY - $(document).scrollTop() < opt.scrollSensitivity)
						scrolled = $(document).scrollTop($(document).scrollTop() - opt.scrollSpeed);
					else if($(window).height() - (e.pageY - $(document).scrollTop()) < opt.scrollSensitivity)
						scrolled = $(document).scrollTop($(document).scrollTop() + opt.scrollSpeed);

					if(e.pageX - $(document).scrollLeft() < opt.scrollSensitivity)
						scrolled = $(document).scrollLeft($(document).scrollLeft() - opt.scrollSpeed);
					else if($(window).width() - (e.pageX - $(document).scrollLeft()) < opt.scrollSensitivity)
						scrolled = $(document).scrollLeft($(document).scrollLeft() + opt.scrollSpeed);
				}
			}

			if (this.scrollTimer)
				clearTimeout(this.scrollTimer);
			if (opt.scroll && scrolled) {
				this.scrollTimer = setTimeout(function() {
					$(window).trigger(e);
				}, 10);
			}
			
			// calc distance moved on this axis (and direction)
			if (mouse.dirAx !== newAx) {
				mouse.distAxX = 0;
				mouse.distAxY = 0;
			} else {
				mouse.distAxX += Math.abs(mouse.distX);
				if (mouse.dirX !== 0 && mouse.dirX !== mouse.lastDirX) {
					mouse.distAxX = 0;
				}
				mouse.distAxY += Math.abs(mouse.distY);
				if (mouse.dirY !== 0 && mouse.dirY !== mouse.lastDirY) {
					mouse.distAxY = 0;
				}
			}
			mouse.dirAx = newAx;

			/**
			 * move horizontal
			 */
			if (mouse.dirAx && mouse.distAxX >= opt.threshold) {
				// reset move distance on x-axis for new phase
				mouse.distAxX = 0;
				prev = this.placeEl.prev(opt.itemNodeName);
				// increase horizontal level if previous sibling exists and is not collapsed
				// ! rtl
				var distX_direction = true;
				//console.log('this.rtl',this.rtl);
				if( this.rtl && mouse.distX > 0 ){
					distX_direction = false;
				} 
				if( !this.rtl && mouse.distX < 0 ){
					distX_direction = false;
				}

				if (opt.allowIncrease && distX_direction && prev.length && !prev.hasClass(opt.collapsedClass)) {
					//console.log('moving right');
					// cannot increase level when item above is collapsed
					list = prev.find(opt.listNodeName).last();
					// check if depth limit has reached
					depth = this.placeEl.parents(opt.listNodeName).length;
					if (depth + this.dragDepth <= opt.maxDepth) {
						// create new sub-level if one doesn't exist
						if (!list.length) {
							list = $('<' + opt.listNodeName + '/>').addClass(opt.listClass);
							list.append(this.placeEl);
							prev.append(list);
							this.setParent(prev);
						} else {
							// else append to next level up
							list = prev.children(opt.listNodeName).last();
							list.append(this.placeEl);
						}
					}
				}
				// decrease horizontal level
				if (opt.allowDecrease && !distX_direction ) {
					//console.log('moving left');
					// we can't decrease a level if an item preceeds the current one
					next = this.placeEl.next(opt.itemNodeName);
					if (!next.length) {
						parent = this.placeEl.parent();
						this.placeEl.closest(opt.itemNodeName).after(this.placeEl);
						if (!parent.children().length) {
							this.unsetParent(parent.parent());
						}
					}
				}
			}

			var isEmpty = false;

			// find list item under cursor
			if (!hasPointerEvents) {
				this.dragEl[0].style.visibility = 'hidden';
			}
			
			this.pointEl = $(document.elementFromPoint(e.pageX - document.documentElement.scrollLeft, e.pageY - (window.pageYOffset || document.documentElement.scrollTop))); 
			if (!hasPointerEvents) {
				this.dragEl[0].style.visibility = 'visible';
			}
			if (this.pointEl.hasClass(opt.handleClass)) {
				this.pointEl = this.pointEl.closest(opt.itemNodeName);
			}
			if (this.pointEl.hasClass(opt.emptyClass)) {
				isEmpty = true;
			}
			else if (!this.pointEl.length || !this.pointEl.hasClass(opt.itemClass)) {
				return;
			}

			// find parent list of item under cursor
			var pointElRoot = this.pointEl.closest('.' + opt.rootClass),
				isNewRoot   = this.dragRootEl.data('nestable-id') !== pointElRoot.data('nestable-id');

			/**
			 * move vertical
			 */
			if (!mouse.dirAx || isNewRoot || isEmpty) {
			
				// get previously hovered element based on direction of mouse movement
				if (mouse.dirY == 1) {
					prev = this.pointEl.prev(opt.itemNodeName);
				} else if (mouse.dirY == -1) {
					prev = this.pointEl.next(opt.itemNodeName);
				}
				
				// check if groups match if dragging over new root
				if (isNewRoot && opt.group !== pointElRoot.data('nestable-group')) {
					return;
				}
				// check depth limit
				var currentLevel = this.pointEl.parents(opt.listNodeName).length;
				var currentDepth = this.placeEl.parents(opt.listNodeName).length;
				depth = this.dragDepth - 1 + this.pointEl.parents(opt.listNodeName).length;
				if (depth > opt.maxDepth) {
					return;
				}
				
				if (!opt.allowIncrease && this.dragLevel < currentLevel) {
					return;
				}
				
				if (!opt.allowDecrease && this.dragLevel > currentLevel && (this.dragLevel - currentLevel != 1)) {
					return;
				}
				if ((!opt.allowDecrease || !opt.allowIncrease) && (this.dragLevel - currentLevel == 1)) {
				
					// set parent for currently hovered element
					list = this.pointEl.find(opt.listNodeName).last();
					if (!list.length) {
						list = $(document.createElement(opt.listNodeName)).addClass(opt.listClass);
						list.append(this.placeEl);
						this.pointEl.append(list);
						this.setParent(this.pointEl);
					}
					
					// unset parent for previously hovered element
					if (prev != undefined && this.isParent(prev) && prev.find(opt.listNodeName).children().length == 0) {
						this.unsetParent(prev);
					}
					
					return;
				}
							
				var before = e.pageY < (this.pointEl.offset().top + this.pointEl.height() / 2);
				parent = this.placeEl.parent();
				// if empty create new list to replace empty placeholder
				if (isEmpty) {
					list = $(document.createElement(opt.listNodeName)).addClass(opt.listClass);
					list.append(this.placeEl);
					this.pointEl.replaceWith(list);
				}
				else if (before) {
					this.pointEl.before(this.placeEl);
				}
				else {
					this.pointEl.after(this.placeEl);
				}
				if (!parent.children().length) {
					this.unsetParent(parent.parent());
				}
				if (!this.dragRootEl.find(opt.itemNodeName).length && !this.dragRootEl.find('.' + opt.emptyClass).length) {
					//console.log('nest add empty placeholder');
					this.dragRootEl.append('<div class="' + opt.emptyClass + '"/>');
				}
				// parent root list has changed
				if (isNewRoot) {
					//this.dragRootEl = pointElRoot;
					this.hasNewRoot = this.el[0] !== this.dragRootEl[0];
				}
			}
		}

	};

	$.fn.nestable = function(params, val)
	{
		var lists  = this,
			retval = this;

		lists.each(function(iel)
		{
			var plugin = $(this).data("nestable");

			if (!plugin) {
				$(this).data("nestable", new Plugin(this, params));
				$(this).data("nestable-id", new Date().getTime());
				// $(this).data("nestable-id", new Date().getTime());
				$(this).data("nestable-id", iel);
			} else {
				if (typeof params === 'string' && typeof plugin[params] === 'function') {
					if (typeof val === 'object') {
						retval = plugin[params](val);
					}else{
						retval = plugin[params]();
					}
				}
			}
		});

		return retval || lists;
	};

})(window.jQuery || window.Zepto, window, document);