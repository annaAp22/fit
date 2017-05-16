var mapDiv = document.getElementById('map');
var mapLoad = function(e) {
    mapDiv.removeEventListener('click', mapLoad);
    $.getScript( "https://maps.googleapis.com/maps/api/js?key=AIzaSyBIc5obn1ArfkEzXhkgZiMyyHPRQmjNx5M", function() {
        init();
    });
};
mapDiv.addEventListener('click', mapLoad);


// When the window has finished loading create our google map below
//google.maps.event.addDomListener(window, 'load', init);

function init() {
    // Basic options for a simple Google Map
    // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
    var mapOptions = {
        // How zoomed in you want the map to start at (always required)
        zoom: 15,

        // The latitude and longitude to center the map (always required)
        center: new google.maps.LatLng(55.711354, 37.654629), /* Moscow*/

        // Do not change zoom on mouse scroll
        scrollwheel: false,

        // How you would like to style the map.
        // This is where you would paste any style found on Snazzy Maps.
        styles: [
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e9e9e9"
                    },
                    {
                        "lightness": 17
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    },
                    {
                        "lightness": 20
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 17
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 29
                    },
                    {
                        "weight": 0.2
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 18
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 16
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    },
                    {
                        "lightness": 21
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#dedede"
                    },
                    {
                        "lightness": 21
                    }
                ]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "visibility": "on"
                    },
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 16
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "saturation": 36
                    },
                    {
                        "color": "#333333"
                    },
                    {
                        "lightness": 40
                    }
                ]
            },
            {
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f2f2f2"
                    },
                    {
                        "lightness": 19
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#fefefe"
                    },
                    {
                        "lightness": 20
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#fefefe"
                    },
                    {
                        "lightness": 17
                    },
                    {
                        "weight": 1.2
                    }
                ]
            }
        ]
    };

    // Get the HTML DOM element that will contain your map
    // We are using a div with id="map" seen below in the <body>
    var mapElement = document.getElementById('map');

    // Create the Google Map using our element and options defined above
    var map = new google.maps.Map(mapElement, mapOptions);

    // Let's also add a marker while we're at it
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(55.710074, 37.654759),
        map: map,
        title: 'Магазин',
        icon: "/img/map_point-min.png"
    });

    // center map on window resize
    google.maps.event.addDomListener(window, "resize", function() {
        var center = map.getCenter();
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
    });
}
$(function(){
    // Toggle active class
    $(".js-toggle-active").click(function(e){
        var $this = $(this),
            reset = $this.data('reset');
        if($this.hasClass(".js-prevent-default"))
            e.preventDefault();
        // Remove active class
        if(typeof reset !== 'undefined') {
            $(reset).removeClass("active")
        }

        $this.toggleClass('active');
    });

    //Prevent default for hovered elements
    $(".js-prevent").click(function(e){
        e.preventDefault();
        return false;
    });

    // Price range slider init
    var rangeSlider = document.getElementById('js-range-slider');
    if( rangeSlider ) {
        var $this = $(rangeSlider);
            rStart = $this.data('start'),
            rRange = $this.data('range');

        noUiSlider.create(rangeSlider, {
            start: [ rStart[0], rStart[1] ],
            connect: true,
            range: {
                'min': rRange[0],
                'max': rRange[1]
            }
        });

        var priceMin = document.getElementById('js-price-min'),
            priceMax = document.getElementById('js-price-max');

        rangeSlider.noUiSlider.on('update', function( values, handle ) {

            var value = values[handle];

            if ( handle ) {
                priceMax.value = Math.round(value);
            } else {
                priceMin.value = Math.round(value);
            }
        });

        priceMin.addEventListener('change', function(){
            rangeSlider.noUiSlider.set([this.value, null]);
        });
        priceMax.addEventListener('change', function(){
            rangeSlider.noUiSlider.set([null, this.value]);
        });
    }

    // Square check filter (size, color)
    var squareCheckFilter = $('.js-square-check-filter');
    if( squareCheckFilter ) {
        $(squareCheckFilter).each(function() {
            var filter = $(this);



            filter.find(".js-square").click(function(){
                var square = $(this),
                    parent = square.parent(),
                    single = false;

                // if single checked needed then reset all checkboxes
                if(parent.hasClass("js-square-check-single")) {
                    single = true;
                }

                if( square.hasClass("active") ) {
                    if(single) {
                        removeActiveSquares(parent);
                    }
                    else {
                        square.removeClass("active");
                        square.find("input").attr("disabled", true);
                    }
                }
                else {
                    if(single) {
                        removeActiveSquares(parent);
                    }
                    square.addClass("active");
                    square.find("input").attr("disabled", null);
                }
            });
        });
    }

    // Remove active class from all squares
    function removeActiveSquares(parent) {
        parent.find(".active").each(function(){
            $(this).removeClass("active");
            $(this).find("input").attr("disabled", true);
        });
    }

    // Show more info in some container
    $(".js-show-more").click(function(e) {
        e.preventDefault();
        var $this = $(this);
        $this.toggleClass("active");
        $this.parent().toggleClass("active");
    });

    // Select behaviour emulation
    $(".js-select").each(function(){
        var select = $(this);
        select.find(".js-option").click(function(){
            select.find(".js-selected").text($(this).text());
        });
    });

    // Toggle target active class on click
    $(".js-toggle-active-target").click(function(e){
        e.preventDefault();
        toggleActiveTarget($(this));
    });

    // Toggle target active class on mouse over
    $(".js-toggle-active-target_over").mouseenter(function(){
        if( !$(this).hasClass('active'))
            toggleActiveTarget($(this));
    });

    function toggleActiveTarget($this) {
        var target = $this.data('target'),
            switchN = $this.data('switch'),
            reset = $this.data('reset');

        // toggle switch if needed
        if(typeof switchN !== 'undefined') {
            $("[data-switch="+switchN+"]").toggleClass("active");
        }
        // remove active from all elements
        else if(typeof reset !== 'undefined') {
            $(reset).removeClass("active")
        }
        else {
            $this.toggleClass("active");
        }

        $(target).toggleClass("active");
    }

    // Show notice popup on element hover
    $(".js-hover-notice").hover(function() {
        $(this).find(".popup-notice").toggleClass('active');
    });

    // Show notice popup on element click (not used yet)
    $(".js-click-notice").click(function() {
        var target = $(this).data("target"),
            popup;
        if(typeof target !== "undefined") {
            popup = $(this).closest(".popup-notice_" + target);
        }
        else {
            popup = $(this).find(".popup-notice");
        }
        popup.addClass('active');
        setTimeout(function(){
            popup.removeClass('active');
        }, 6000);
    });

    // Add to cart
    $(".js-add-to-cart").click(function(e) {
        e.preventDefault();
        var form = $(this).closest('form'),
            data = form.serializeFormJSON();
        if(typeof data.size === "undefined") {
            var size = form.find(".js-popup-size");
            size.addClass('active');
            setTimeout(function(){
                size.removeClass('active');
            }, 3000);
        }
        else {
            // form.submit();
            $(this).addClass('active');
        }
    });

    // Form serialize as json
    $.fn.serializeFormJSON = function () {

        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };



    // Desktop / mobile elements detach / attach
    $('header').on("webkitTransitionEnd transitionend oTransitionEnd", function (event) {
        if (event.originalEvent.propertyName == "min-height") {
            moveElements();
        }
    });
    moveElements();
    function moveElements(){
        var women = $('.js-dropdown-women'),
            men = $('.js-dropdown-men'),
            navigation = $('.js-pages'),
            filter = $('.sidebar-filter');
        var screenSize = checkWindowWidth();

        navigation.detach();
        women.detach();
        men.detach();
        filter.detach();

        if ( screenSize ) {
            //desktop screen
            navigation.insertAfter('.nav-catalog');
            women.prependTo('.js-women-desktop');
            men.prependTo('.js-men-desktop');
            filter.prependTo('.sidebar');

        } else {
            //mobile screen
            women.appendTo('.js-women-mobile');
            men.appendTo('.js-men-mobile');
            navigation.insertAfter('.js-pages-mobile');
            filter.appendTo('#sidebar-filters');
        }
    }
    function checkWindowWidth() {
        var mq = window.getComputedStyle(document.querySelector('header'), '::before').getPropertyValue('content').replace(/"/g, '').replace(/'/g, "");
        return (mq != 'mobile');
    }
    var sidebarOpened = false;
    // Toggle active on filter or navigation open
    $(".js-toggle-sidebar").click(function(){
        var $this = $(this),
            target = $this.data('target'),
            active = $this.hasClass('active');
        if(sidebarOpened) {
            closeSidebar();
            if(!active) {
                setTimeout(function() {
                    openSidebar($this, target);
                }, 500);
            }
        }
        else {
            openSidebar($this, target);
        }
    });
    function closeSidebar() {
        $('.js-sidebar-open').removeClass('active');
        $('.js-nav-visible').removeClass('active');
        $('.js-filter-visible').removeClass('active');
        $('.js-toggle-sidebar').removeClass('active');
        sidebarOpened = false;
    }
    function openSidebar($this, target) {
        setTimeout(function() {
            $this.addClass('active');
        }, 300);
        $(target).addClass('active');
        $('.js-sidebar-open').addClass('active');
        sidebarOpened = true;
    }

    // Product detailed image carousel
    $("#js-product-gallery-nav").carousel({
        vertical : true,
        margin: 0,
        responsive: {
            xl: {items: 5, options: {margin: 0, vertical: true}},
            lg: {items: 5, options: {margin: 0, vertical: true}},
            md: {items: 5, options: {margin: 0, vertical: true}},
            sm: {items: 5, options: {margin: 0, vertical: false}},
            xs: {items: 3, options: {margin: 0, vertical: false}}
        }
    });

    //Product set carousel
    $("#js-product-set").carousel();

    // Product seen carousel
    $(".js-product-carousel").carousel();

    // Main page banners slider
    $("#js-main-banner").carousel({
        margin: 0,
        pagination: true,
        responsive: {
            xl: 1,
            lg: 1,
            md: 1,
            sm: 1,
            xs: 1
        }
    });

    // Load VK comments widget
    $(".js-vk-comments-widget").on('click.vk',function() {
        $(this).off('click.vk');
        $.getScript("//vk.com/js/api/openapi.js?145", function() {
            VK.init({apiId: API_ID, onlyWidgets: true});
            VK.Widgets.Comments("js-vk_comments", {limit: 5, attach: false});
        });
    });


    // Mask phone
    $('.phone').mask("+7 000 000 00 000", {placeholder: "+7 ___ ___ __ __"});

    var $body = $('body');

    // Check required steps
    $body.on('keyup', '.js-required-fields', function(e) {
        var checked = 0;
        $('.js-required-fields').each(function(index, el) {
            if(el.name == 'phone') {
                if( el.value.length > 14 ) {
                    checked++;
                }
            }
            else {
                if(el.value && el.value.length > 1) {
                    checked++;
                }
            }
        });

        if(checked == 2) {
            $('.js-step-next').attr('disabled', false);
        }
        else{
            $('.js-step-next').attr('disabled', true);
        }
    });

    // Checkout steps
    $body.on('click', '.js-step-next', function(e) {
        e.preventDefault();
        nextStep($(this));
    });
    // Next step, accepts element or number
    function nextStep($el) {
        var step = $.isNumeric($el) ? $el : $el.data('next_step');
        if(typeof step !== 'undefined') {
            $(".js-step").removeClass('active');
            $(".js-step_" + step).addClass('active');
        }
    }

    // Order submit click
    $body.on('click', '#js-order-submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form'),
            data = form.serializeFormJSON();

        // Do ajax order request

        // Show next step
        nextStep(3);
    });
});




//# sourceMappingURL=app.js.map
