/*! Carousel.JS - v0.2.0 - 2017-04-28
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
                xl : {items: 5}, // or just xl : 5
                lg : {items: 4},
                md : {items: 3},
                sm : {items: 2},
                xs : {items: 2}
            }, // carousel responsive items visible
            pagination: false
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
                self.track.css( {
                    'width' : "",
                    'height': t + "%",
                    'margin-top': -m,
                    'margin-bottom': -m
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
                self.track.css( {
                    'height' : "",
                    'width': t + "%",
                    'margin-left': -m,
                    'margin-right': -m
                });
            }
            self.items.attr('draggable', false);
            self.items.find('*').attr('draggable', false);
        };

        // Next slide
        this.next = function(){
            if(self.position > (self.count - self._options.items) * (-1) ) {
                self.position--;
                self.delta = self.move(-1 * (self.dimension));
            }
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
        this.responsive = function() {

            this.responsiveItems();

            // Update css on media breakpoint
            $('header').on("webkitTransitionEnd transitionend oTransitionEnd", function (event) {

                if (event.originalEvent.propertyName == "min-height") {

                    self.responsiveItems();
                    self.bindTouch();
                    self.updateCss();
                }
            });
        };

        this.responsiveItems = function() {
            var media = window.getComputedStyle(
                document.querySelector('header'), '::after'
            ).getPropertyValue('content').replace(/"/g, '').replace(/'/g, "");
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
            self.touch = new Hammer(self.track[0]);

            if(self._options.vertical) {
                // Pan (drag)
                self.touch.get('pan').set({ direction: Hammer.DIRECTION_VERTICAL });
                self.touch.on("panup pandown panend panstart", function(ev) {
                    self.pan(ev);
                });
            }
            else {
                self.touch.get('pan').set({ direction: Hammer.DIRECTION_HORIZONTAL });
                self.touch.on("panleft panright panend panstart", function(ev) {
                    self.pan(ev);
                });
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
            $(".carousel-pagination__page").removeClass('active');
            $(self.pages[-1 * self.position]).addClass('active');
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

            if(self.count > self._options.items) {

                // Bind control buttons events
                $(self.buttons[0]).click(self.prev);
                $(self.buttons[1]).click(self.next);

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
            }
            else {
                // Disable buttons if no overflow items
                self.buttons.attr('disabled', true);
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