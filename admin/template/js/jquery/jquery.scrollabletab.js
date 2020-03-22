
(function($, undefined) {
    if (!$.xui) {
        $.xui = {};
    }
    var tabs = $.extend({}, $.ui.tabs.prototype),
        _super = {
            _create: tabs._create,
            _destroy: tabs._destroy,
            _update: tabs._update
        };
    $.xui.tabs = $.extend(tabs, {
        options: $.extend({}, tabs.options, {
            scrollable: false,
            changeOnScroll: false,
            closable: false,
            resizable: false,
            resizeHandles: "e,s,se"
        }),
        _create: function() {
            var self = this,
                o = self.options;
            _super._create.apply(self);
            if (o.scrollable) {
                self.element.addClass("ui-tabs-scrollable");
                var scrollContainer = $('<div class="ui-tabs-scroll-container"></div>').prependTo(this.element);
                self.header = $('<div class="ui-tabs-nav-scrollable ui-widget-header ui-corner-all"></div>').prependTo(scrollContainer);
                var nav = self.element.find(".ui-tabs-nav:first").removeClass("ui-widget-header ui-corner-all").appendTo(this.header);
                var arrowsNav = $('<ol class="ui-helper-reset ui-helper-clearfix ui-tabs-nav-arrows"></ol>').prependTo(self.element);
                var navPrev = $('<li class="ui-tabs-arrow-previous ui-state-default ui-corner-bl ui-corner-tl" title="Previous"><a href="#"><span class="ui-icon ui-icon-carat-1-w">Previous tab</span></a></li>').prependTo(arrowsNav).hide(),
                    navNext = $('<li class="ui-tabs-arrow-next ui-state-default ui-corner-tr ui-corner-br" title="Next"><a href="#"><span class="ui-icon ui-icon-carat-1-e">Next tab</span></a></li>').appendTo(arrowsNav).hide();
                var html_direction = ($("html").attr('dir') == "rtl") ? "marginRight":"marginLeft";
                
                var scrollTo = function(to, delay) {
                    var navWidth = 0,
                        arrowWidth = navPrev.outerWidth(),
                        marginRight = -(parseInt(nav.css(html_direction), 10)),
                        hwidth = self.header.width(),
                        newMargin = 0;
                    
                    nav.find("li").each(function() {
                        navWidth += $(this).outerWidth(true);
                    });
                    
                    if (to instanceof $.Event) {
                        
                    } else {
                        newMargin = marginRight+to;
                        if (newMargin > (navWidth-hwidth)) {
                            newMargin = (navWidth-hwidth);
                        } else if (newMargin < 0) {
                            newMargin = 0;
                        }
						
                        nav.stop(true).animate({
                            marginRight: -(newMargin)
                        }, delay, function(){
                            $(window).trigger("resize.tabs");
                        });
                    }
                }
                
                
                var holdTimer = false;
                navPrev.add(navNext).bind({
                    "click": function(e) {
                        var isNext = this === navNext[0];
                        e.preventDefault();
                        if (o.changeOnScroll) {
                            self.select(self.options.selected + (isNext ? 1 : -1));
                        } else {
                            if (!holdTimer)
                                scrollTo(isNext ? 150 : -150, 250);
                        }
                    },
                    "mousedown": function(e){
                        if (!o.changeOnScroll) {
                            var isNext = this === navNext[0],
                                duration = 10, pos = 15, timer;
                            if (holdTimer)
                                clearTimeout(holdTimer);
                            holdTimer = setTimeout(timer = function(){
                                scrollTo(isNext ? pos : -(pos), duration);
                                holdTimer = setTimeout(arguments.callee, duration);
                            }, 150);
                        }
                    },
                    "mouseup mouseout": function(e){
                        if (!o.changeOnScroll) {
                            clearTimeout(holdTimer);
                            holdTimer = false;
                            nav.stop();
                        }
                    }
                });

                $(window).bind("resize.tabs", function(e) {
                    var navWidth = 0;
                    var arrowWidth = navPrev.outerWidth();
                    nav.find("li").each(function() {
                        navWidth += $(this).outerWidth(true);
                    });
                    
                    var marginRight = -(parseInt(nav.css(html_direction), 10)),
                        hwidth = self.header.width();
                    
                    if (navWidth > (hwidth+marginRight)) {
                        self.header.addClass("ui-tabs-arrow-r");
                        navNext.show("fade");
                        if (marginRight > 0) {
                            self.header.addClass("ui-tabs-arrow-l");
                            navPrev.show("fade");
                        } else {
                            self.header.removeClass("ui-tabs-arrow-l");
                            navPrev.hide("fade");
                        }
                    } else {
                        self.header.removeClass("ui-tabs-arrows ui-tabs-arrow-l");
                        navNext.hide("fade");
                        if (marginRight > 0) {
                            self.header.addClass("ui-tabs-arrow-l");
                            navPrev.show("fade");
                        } else {
                            self.header.removeClass("ui-tabs-arrow-l");
                            navPrev.hide("fade");
                        }
                    }
                }).trigger("resize.tabs");
                
                arrowsNav.find("li").bind({
                    "mouseenter focus": function(e) {
                        $(this).addClass("ui-state-hover");
                    },
                    "mouseleave blur": function(e) {
                        $(this).removeClass("ui-state-hover");
                    }
                });

                this.anchors.bind("click.tabs", function(){
                    var li = $(this).parent(),
                        arrowWidth = navPrev.outerWidth(),
                        width = li.outerWidth(true),
                        hwidth = self.header.width(),
                        pos = li.position().left,
                        marginLeft = -(parseInt(nav.stop(true,true).css("marginLeft"),10)),
                        newMargin = -1;

                    if (li.index() === 0) {
                        newMargin = 0;
                    } else if ((pos+width) >= (hwidth+marginLeft)) {
                        newMargin = pos-hwidth+width;
                        if ((li.index()+1) < nav.find("li").length) {
                            newMargin += arrowWidth;
                        }
                    } else if (pos < marginLeft) {
                        newMargin = pos-arrowWidth;
                    }
                    
                    if (newMargin > -1) {
                        nav.animate({
                            marginLeft: -(newMargin)
                        }, 250, function(){
                            $(window).trigger("resize.tabs");
                        });
                    }
                });
            }
            return self;
        },
        _update: function(){
            console.log(arguments);
            _super._update.apply(this);
        }
    });
    $.widget("xui.tabs", $.xui.tabs);
})(jQuery);