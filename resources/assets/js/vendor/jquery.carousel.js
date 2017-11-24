/*! Carousel.JS - v0.3.0 - 2017-05-23
 *
 * Copyright (c) 2017 Alexander Kamyshnyi;
 * Licensed under the MIT license */
(function ( $ ) {

    var Carousel = function($el, options) {
        this._defaults = {
            items : 5, // number of items visible
            vertical : false, // vertical or horizontal carousel
            margin : 12, // item margin left and right value in px
            responsive : {
                1492 : {items: 5}, // or just xl : 5
                1203 : {items: 4},
                840 : {items: 3},
                576 : {items: 2},
                320 : {items: 2}
            }, // carousel responsive items visible
            pagination: false,
            auto: false,
            speed: 5000,
            loop: false,
            afterLoad: false
        };
        this._options = $.extend({}, this._defaults, options);

        this.options = function(options) {
            return (options) ?
                $.extend(true, self._options, options) :
                self._options;
        };

        // Index of current slide
        this.position = 0;

        // DeltaX or deltaY
        this.delta = 0;

        // Update carousel css
        this.updateCss = function() {
            var m = self._options.margin, d, t, c;
            d = (self._options.vertical) ? self.wrapper.height() : self.wrapper.width();
            // Item size height margins, px
            self.dimension = ((d + m*2) / self._options.items);
            // Count
            c = (self.count > self._options.items) ? self.count : self._options.items;
            // Track size, px
            self.trackDimension = t = (self.dimension + self._options.margin * 2) * c;
            // Item size, %
            self.dimension = ( self.dimension / t ) * 100;
            // Track size, %
            t = ( t / d ) * 100;

            if(self._options.vertical) {
                // Set items css
                self.items.css({
                    'margin-top': m + "px",
                    'margin-bottom': m + "px",
                    'height': "calc(" + self.dimension + "% - " + m*2 + "px)",
                    'width' : ""
                });
                // Set track height
                var tr = 'translateY';
                self.track.css( {
                    'width' : "",
                    'height': t + "%",
                    'margin-top': -m,
                    'margin-bottom': -m,
                    '-webkit-transform': tr + '(0)',
                    '-ms-transform': tr + '(0)',
                    'transform': tr + '(0)'
                });
            }
            else {
                // Set items css
                self.items.css({
                    'margin-left': m + "px",
                    'margin-right': m + "px",
                    'width': "calc(" + self.dimension + "% - " + m*2 + "px)",
                    'height' : ""
                });
                // Set track css
                var tr = 'translateX';
                self.track.css( {
                    'height' : "",
                    'width': t + "%",
                    'margin-left': -m,
                    'margin-right': -m,
                    '-webkit-transform': tr + '(0)',
                    '-ms-transform': tr + '(0)',
                    'transform': tr + '(0)'
                });
            }
            self.items.attr('draggable', false);
            self.items.attr('ondragstart', 'return false;');
            self.items.find('*').attr('draggable', false);
            self.position = 0;
            self.delta = 0;
        };

        // Next slide
        this.next = function(){
            if(self.position > (self.count - self._options.items) * (-1) ) {
                self.position--;
                self.delta = self.move(-1 * (self.dimension));
            }else if(self._options.loop) {
                self.position = 0;
                self.delta = 0;
                self.move();
            }
        };
        // Middle slide
        this.middle = function(){
            self.position = Math.ceil(self.count / 2);
            self.delta = self.move((-self.position + 1) * (self.dimension));
            console.log(self.position);
            console.log(self.delta);
            console.log(self.dimension);
        };

        // Previous slide
        this.prev = function(){
            if(self.position < 0) {
                self.position++;
                self.delta = self.move(self.dimension);
            }
        };

        // Move slides
        this.move = function(offset) {
            if(typeof offset === 'undefined')
                offset = 0;
            var p = self.delta + offset,
                t = (self._options.vertical) ? 'translateY' : 'translateX';
                self.track.css({
                    '-webkit-transform': t + '(' + p + '%)',
                    '-ms-transform': t + '(' + p + '%)',
                    'transform': t + '(' + p + '%)'
                });
            if(self._options.pagination) {
                self.pageActive();
            }


            self.items.removeClass('active');
            self.items.eq(-1*self.position).addClass('active');

            return p;
        };

        // Touch dragging
        this.pan = function(ev) {
            // Offset, px
            var offset = (self._options.vertical) ? ev.deltaY : ev.deltaX;
            // Offset, %
            offset = (offset / self.trackDimension) * 100;

            if(ev.type == "panstart") {
                self.track.addClass('js-dragged');
            }
            if(['panup','pandown','panleft','panright'].indexOf(ev.type) !== -1) {
                self.move(offset);
            }
            if(ev.type == "panend") {

                // Set timeout for preventing link click event
                setTimeout(function(){
                    self.track.removeClass('js-dragged');

                    // Align slide position
                    var p = (self.delta + offset) / self.dimension;
                    self.position = (p < 0) ? Math.round(Math.max( (self._options.items - self.count), p)) : 0;
                    self.delta = 0;
                    self.delta = self.move(self.position * self.dimension);

                },100);

            }
        };

        // Responsive
        this.findLayout = function() {
            // Detect layout by window width
            var w = window.innerWidth,
                breakpoints = Object.keys(self._options.responsive).sort(function(a, b) {
                    return b - a;
                }),
                layout = breakpoints[0];
            breakpoints.some(function(el) {
                if(w >= el) {
                    layout = el;
                    return true;
                }
            });
            return layout;
        };

        this.responsive = function() {

            this.layout = this.findLayout();

            this.responsiveItems();

            $( window ).resize(function() {
                var layout = self.findLayout();
                if(self.layout != layout) {
                    self.layout = layout;
                    self.responsiveItems();
                    self.bindTouch();
                    self.checkButtons();
                    self.updateCss();
                    self.pageActive();

                    self.items.removeClass('active');
                    self.items.eq(-1*self.position).addClass('active');
                }

            });
        };

        this.responsiveItems = function() {
            var media = self.layout;

            // change items visible count
            if(typeof media !== 'undefined' && typeof self._options.responsive[media] !== 'undefined') {

                if(typeof self._options.responsive[media] === 'object') {
                    if(typeof self._options.responsive[media]['items'] !== 'undefined') {
                        self._options.items = self._options.responsive[media]['items'];
                    }

                    // Reinit carousel with new options
                    if(typeof self._options.responsive[media]['options'] !== 'undefined') {
                        self.options(self._options.responsive[media]['options']);
                        self.updateCss();
                    }
                }
                else {
                    self._options.items = self._options.responsive[media];
                }
            }
        };

        // Bind touch events
        this.bindTouch = function() {
            if(typeof self.touch !== 'undefined') {
                self.touch.destroy();
            }

            var items = self.items;
            if(self._options.responsive && typeof self._options.responsive[self.layout] !== 'undefined') {
                if(typeof self._options.responsive[self.layout].items !== 'undefined') {
                    items = self._options.responsive[self.layout].items;
                }
                else {
                    items = self._options.responsive[self.layout];
                }
            }

            if(self.count > items) {
                self.touch = new Hammer(self.track[0]);

                if (self._options.vertical) {
                    // Pan (drag)
                    self.touch.get('pan').set({direction: Hammer.DIRECTION_VERTICAL});
                    self.touch.on("panup pandown panend panstart", function (ev) {
                        self.pan(ev);
                    });
                }
                else {
                    self.touch.get('pan').set({direction: Hammer.DIRECTION_HORIZONTAL});
                    self.touch.on("panleft panright panend panstart", function (ev) {
                        self.pan(ev);
                    });
                }
            }
        };

        // Add pagination
        this.paginate = function() {
            var pag = document.createElement('div'), pages = [];
            pag.className = "carousel-pagination";
            for( var i=0;i<self.count;i++ ) {
                pages[i] = document.createElement('div');
                if( i > 0 ) {
                    pages[i].className = "carousel-pagination__page";
                }
                else {
                    pages[i].className = "carousel-pagination__page active";
                }
            }
            pages.forEach(function(page) {
                pag.appendChild(page);
                page.addEventListener('click', function() {
                    self.movePage(page);
                });
            });
            $el.append(pag);
            self.pages = pages;
        };

        // Move to certain page
        this.movePage = function(page) {
            var $page = $(page);
            self.position = -1 * $page.index();
            self.delta = self.position * self.dimension;
            self.move();
        };

        // Activate page icon
        this.pageActive = function() {
            if(self.pages) {
                self.pages.forEach(function(page) {
                    page.className = "carousel-pagination__page";
                });
                $(self.pages[-1 * self.position]).addClass('active');
            }
        };

        // Disable buttons if count < items
        this.checkButtons = function() {
            var items = self.items;
            if(self._options.responsive && typeof self._options.responsive[self.layout] !== 'undefined') {
                if(typeof self._options.responsive[self.layout].items !== 'undefined') {
                    items = self._options.responsive[self.layout].items;
                }
                else {
                    items = self._options.responsive[self.layout];
                }
            }
            // UnBind control buttons events
            $(self.buttons[0]).unbind('click');
            $(self.buttons[1]).unbind('click');

            if(self.count <= items) {
                // Disable buttons if no overflow items
                self.buttons.attr('disabled', true);
            }
            else {
                self.buttons.attr('disabled', false);
                // Bind control buttons events
                $(self.buttons[0]).click(self.prev);
                $(self.buttons[1]).click(self.next);
            }
        };

        // Set auto slide interval
        this.autoSlide = function() {
            self.timer = setInterval(function(e) {
                    self.next();
            }, self._options.speed);
        };

        // Pause auto slide on hover
        this.pauseOnHover = function() {
            self.wrapper.on('mouseover', function(e) {
                $(this).addClass('hover');
                clearInterval(self.timer);
            });
            self.wrapper.on('mouseout', function(e) {
                $(this).removeClass('hover');
                self.autoSlide();
            });
        };

        // Init carousel
        var self = this;
        self.wrapper = $el.find("> div");
        self.track = self.wrapper.find("> div");
        self.items = self.track.find("> *");
        self.buttons = $el.find("> button");



        if(self.items) {
            self.count = self.items.length;

            if( /*!self._options.vertical &&*/ self._options.responsive) {
                self.responsive();
            }

            self.updateCss();

            // Disable buttons if count < items
            self.checkButtons();

            // Bind touch events
            self.bindTouch();

            // Prevent link click event on drag end
            $('body').on('click', '.js-dragged a', function(e){
                e.preventDefault();
                return false;
            });

            // Add pagination
            if(self._options.pagination) {
                self.paginate();
            }

            // Auto slide with time interval
            if(self._options.auto) {
                self.autoSlide();
                // Pause auto slide on hover
                self.pauseOnHover();
            }

            if(self._options.afterLoad) {
                self._options.afterLoad(self);
            }

        }

    };

    $.fn.carousel = function(methodOrOptions) {
        var method = (typeof methodOrOptions === 'string') ? methodOrOptions : undefined;

        if (method) {
            var carousels = [];

            function getCarousel() {
                var $el = $(this);
                var carousel = $el.data('carousel');

                carousels.push(carousel);
            }

            this.each(getCarousel);

            var args = (arguments.length > 1) ? Array.prototype.slice.call(arguments, 1) : undefined;
            var results = [];

            function applyMethod(index) {
                var carousel = carousels[index];

                if (!carousel) {
                    console.warn('$.carousel not instantiated yet');
                    console.info(this);
                    results.push(undefined);
                    return;
                }

                if (typeof carousel[method] === 'function') {
                    var result = carousel[method].apply(carousel, args);
                    results.push(result);
                } else {
                    console.warn('Method \'' + method + '\' not defined in $.carousel');
                }
            }

            this.each(applyMethod);

            return (results.length > 1) ? results : results[0];
        }
        else {
            var options = (typeof methodOrOptions === 'object') ? methodOrOptions : undefined;

            function init() {
                var $el = $(this);
                var carousel = new Carousel($el, options);

                $el.data('carousel', carousel);
            }

            return this.each(init);

        }
    };

}( jQuery ));