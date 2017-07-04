$(function() {
    var $filters = $('#js-filters'),
        $sorters = $('.js-sort'),
        $page = $filters.find('input[name=page]'),
        $pageCount = $filters.find('input[name="pageCount"]'),
        $productsCount = $('.js-goods-count span'),
        $items = $('#js-goods'),
        $paginator = $('.js-pagination'),
        dontTouchThis = [
            'page',
            '_token',
            'category_id',
            'brand_id',
            'tag_id',
            'price_from',
            'price_to'
        ],
        dontTouchSelector = '',
        resetPagination = function(submit) {
            if(typeof submit === 'undefined')
                submit = false;
            //console.log('reset pagination');
            $page.val(1);
            $pageCount.val(1);
            if(submit) {
                $filters.trigger('submit');
            }
        },
        // Reset filters values
        resetFilters = function() {
            $filters.find('input[type=checkbox]', 'input[type=radio]').attr('checked', false);
            $filters.find('input[name^=attribute]').attr('disabled', true);
            $filters.find('input[name=sort]').val('sort');
            $('.js-square').removeClass('active');
            rangeSlider.noUiSlider.set([rRange[0], rRange[1]]);

            resetPagination(true);
        };

    for(var index in dontTouchThis)
        dontTouchSelector += '[name!=' + dontTouchThis[index] + ']';

    if($page.val() == '') $paginator.hide();

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

        rangeSlider.noUiSlider.on('set', function(){
            resetPagination();
        });

        priceMin.addEventListener('change', function(){
            rangeSlider.noUiSlider.set([this.value, null]);
        });
        priceMax.addEventListener('change', function(){
            rangeSlider.noUiSlider.set([null, this.value]);
        });
    }

    $filters.find('input[name^=attribute],select[name^=attribute]').on('change', function(e) {
        e.preventDefault();
        resetPagination();
    });
    $filters.find('.js-square').on('click', function(e) {
        resetPagination();
    });

    // $filters.on('submit', function(e) {
    //     e.preventDefault();
    //     var formData = $(this).serialize();
    //     console.log(formData);
    //     $.post($(this).attr('action'), formData, function(data) {
    //         if(data['reload'] == 1) {
    //             location.reload();
    //             return true;
    //         }
    //         if(data.clear) {
    //             $page.val(2);
    //             $items.html($(data.items));
    //         } else {
    //             if($page.val() == 'all') {
    //                 $items.html($(data.items));
    //             }
    //             else {
    //                 $items.append($(data.items));
    //             }
    //         }
    //
    //         if(data.next_page === null) {
    //             $paginator.hide();
    //         }
    //         else {
    //             $paginator.show();
    //             $page.val(data.next_page);
    //         }
    //         $productsCount.html(data.count);
    //     });
    // });
    $sorters.on('click', function(e) {
        e.preventDefault();
        var sort = $(this).data('sort');
        $filters.find("input[name=sort]").val(sort);

        if($(this).hasClass('active')) {
            $(this).removeClass('active');
            //$filters.find('input[type=hidden]' + dontTouchSelector).val('');
        } else {
            $sorters.removeClass('active');
            $(this).addClass('active');

            //$filters.find('input[type=hidden]' + dontTouchSelector).val('');
            //$filters.find('input[type=hidden][name=' + $(this).attr('name') + ']').val($(this).val());
        }

        resetPagination(true);
    });

    $paginator.on('click', function(e) {
        e.preventDefault();
        var showAll = $(this).data('all');
        if(typeof showAll !== 'undefined' && showAll) {
            $page.val('all');
            $pageCount.val(1);
        }else {
            $page.val(1);
            $pageCount.val(parseInt($pageCount.val())+1);
        }
        console.log('get next page');
        $filters.trigger('submit');
    });

    $(".js-filters-reset").on('click', function(e) {
        e.preventDefault();
        resetFilters();
        $('.js-toggle-sidebar.active').trigger('click');
        return false;
    });
    $(".js-close-filters").on('click', function(){
        $('.js-toggle-sidebar.active').trigger('click');
    });
});
var mapDiv = document.getElementById('map');
var map2Div = document.getElementById('agencies-map');
var mapLoad = function(e) {
    mapDiv.removeEventListener('click', mapLoad);
    $.getScript( "https://maps.googleapis.com/maps/api/js?key=AIzaSyBIc5obn1ArfkEzXhkgZiMyyHPRQmjNx5M", function() {
        init();
    });
};
mapDiv.addEventListener('click', mapLoad);
if(map2Div)
    mapLoad();

// When the window has finished loading create our google map below
//google.maps.event.addDomListener(window, 'load', init);

function init() {
    // Basic options for a simple Google Map
    // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
    var mapOptions = {
        // How zoomed in you want the map to start at (always required)
        zoom: 15,

        // The latitude and longitude to center the map (always required)
        center: new google.maps.LatLng(55.709328, 37.653426), /* Moscow*/

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
    var bigMarker = new google.maps.MarkerImage(
        '/img/map_point-min.png',
        new google.maps.Size(126,132),
        new google.maps.Point(0,0),
        new google.maps.Point(118,91)
    );
    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(55.709328, 37.653426),
        map: map,
        title: 'Магазин',
        icon: bigMarker
    });

    mapElement2 = document.getElementById('agencies-map');
    // Create the Google Map using our element and options defined above
    var $agencies = $('.js-agencies');
    var shops = {
        lat:$agencies.find('.js-lat'),
        long:$agencies.find('.js-long'),
        address:$agencies.find('.js-address'),
    }
    //if shop markers is apsend then create map and place markers
    if(shops.lat.length) {
        var zoom = $(map2Div).data('zoom');
        var lat = $(map2Div).data('lat');
        var long = $(map2Div).data('long');
        if(zoom) {
            mapOptions.zoom = zoom;
        }
        // Let's also add a marker while we're at it
        var marker2;
        var address;
        if(!lat)
            lat = shops.lat.eq(0).val();
        if(!long)
            long = shops.long.eq(0).val();
        mapOptions.center = new google.maps.LatLng(lat, long);
        var map2 = new google.maps.Map(mapElement2, mapOptions);
        var markerImage = new google.maps.MarkerImage(
            '/img/map-point-small-min.png',
            new google.maps.Size(52,51),
            new google.maps.Point(0,0),
            new google.maps.Point(47,42)
        );

        for(var i = 0; i < shops.lat.length; i++) {
            address = shops['address'].eq(i).val();
            if(!address) {
                address = shops['address'].eq(i).text()
            }
            marker2 = new google.maps.Marker({
                position: new google.maps.LatLng(shops.lat.eq(i).val(), shops.long.eq(i).val()),
                map: map2,
                title: address,
                icon: markerImage
            });
        }
    }
    // center map on window resize
    google.maps.event.addDomListener(window, "resize", function() {
        var center = map.getCenter();
        google.maps.event.trigger(map, "resize");
        map.setCenter(center);
    });
}
$(function(){
    var $body = $('body');

    // Toggle active class
    $body.on("click", ".js-toggle-active", function(e){
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
    $body.on("click", ".js-prevent", function(e){
        e.preventDefault();
        return false;
    });

    // Square check filter (size, color)
    var squareCheckFilter = $('.js-square-check-filter');
    if( squareCheckFilter ) {
        $body.on("click", ".js-square", function(e){
            e.preventDefault();
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
    }

    // Remove active class from all squares
    function removeActiveSquares(parent) {
        parent.find(".active").each(function(){
            $(this).removeClass("active");
            $(this).find("input").attr("disabled", true);
        });
    }

    // Show more info in some container
    $body.on("click", ".js-show-more", function(e) {
        e.preventDefault();
        var $this = $(this);
        $this.toggleClass("active");
        $this.parent().toggleClass("active");
    });

    // Select behaviour emulation
    $body.on("click", ".js-select", function(){
        var select = $(this);
        select.find(".js-option").click(function(){
            select.find(".js-selected").text($(this).text());
        });
    });

    // Toggle target active class on click
    $body.on("click", ".js-toggle-active-target", function(e){
        e.preventDefault();
        toggleActiveTarget($(this));
    });

    // Toggle target active class on mouse over
    $body.on("mouseenter", ".js-toggle-active-target_over", function(){
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
    $body.on("mouseover", ".js-hover-notice", function() {
        $(this).find(".popup-notice").addClass('active');
    });
    $body.on("mouseout", ".js-hover-notice", function() {
        $(this).find(".popup-notice").removeClass('active');
    });

    // Show notice popup on element click (not used yet)
    $body.on("click", ".js-click-notice", function() {
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
    $body.on("click", ".js-add-to-cart", function(e) {
        e.preventDefault();
        var $this = $(this);
        //if( !$(this).hasClass('active') ) {
            var form = $this.closest('form'),
                data = form.serializeFormJSON();
            if(typeof data.size === "undefined") {
                var size = form.find(".js-popup-size");
                scrollToEl(size);
                size.addClass('active');
                setTimeout(function(){
                    size.removeClass('active');
                }, 3000);
            }
            else {
                form.submit();
                $this.addClass('active');
            }
        //}
        //else {
        //    window.location = $this.find('a').attr('href');
        //}
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
            //filter.prependTo('.sidebar');
            filter.insertAfter('.sidebar-catalog');
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
        setTimeout(function() {
            $('.js-toggle-sidebar').removeClass('active');
        }, 300);
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
            1492: {items: 5, options: {margin: 0, vertical: true}},
            1203: {items: 5, options: {margin: 0, vertical: true}},
            840: {items: 5, options: {margin: 0, vertical: true}},
            576: {items: 5, options: {margin: 0, vertical: false}},
            320: {items: 3, options: {margin: 0, vertical: false}}
        }
    });

    //Product set carousel
    $("#js-product-set").carousel( {
        responsive: {
            1492 : {items: 5},
            1203 : {items: 4},
            840 : {items: 3},
            576 : {items: 2},
            300 : {items: 2},
            0 : {items: 1}
        }
    });

    // Product seen carousel
    $(".js-product-carousel").carousel();

    // Main page banners slider
    $(".js-single-banner").carousel({
        margin: 0,
        pagination: true,
        responsive: {
            1492: 1,
            1203: 1,
            840: 1,
            576: 1,
            320: 1
        }
    });

    // Load VK comments widget
    $(".js-vk-comments-widget").on('click.vk',function() {
        $(this).off('click.vk');
        $.getScript("//vk.com/js/api/openapi.js?145", function() {
            VK.init({apiId: 4411901, onlyWidgets: true});
            VK.Widgets.Comments("js-vk_comments", {limit: 5, attach: false});
        });
    });

    // Checkout steps
    $body.on('click', '.js-step-next', function(e) {
        e.preventDefault();
        nextStep($(this));
    });


    // Delivery price calc
    $body.on('click', '.js-delivery', function(e) {
        var $this = $(this),
            total = $(".js-total"),
            delivery = parseInt($this.find('.js-price').data('price')),
            newTotal = parseInt(total.data('amount')) + delivery;
        total.text(number_format(newTotal, 0, '.', ' ') + ' ₽');
    });
    //кликаем на первый попавшийся способ доставки, чтоб правильно посчиталась цена
    var $first_delivery = $('.js-delivery:eq(0)');
    if($first_delivery.length) {
        $first_delivery.click();
    }
    // Order submit click
    $body.on('click', '#js-order-submit', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        formSubmit(form);
    });

    // Play youtube video
    $body.on("click", ".js-play-video", function(e) {
        e.preventDefault();
        var $this = $(this),
            parent = $this.parent(),
            iframe = parent.find('iframe');
        parent.addClass("active");
        iframe.attr("src", iframe.data('src'));
    });

    // Ajax form submit
    function formSubmit(form) {
        var url = form.attr('action'),
            postData = form.serialize(),
            requiredFields = form.find('.js-required-fields');
        if( formValid(requiredFields, true) ) {
            $.post(url, postData, function(data){
                // Exception
                if(typeof data.error !== 'undefined'){
                    console.log(data.message);
                }
                // Do some action
                if(typeof data.action !== 'undefined'){
                    var fn = window[data.action];
                    if(typeof fn === 'function') {
                        fn(data);
                    }
                }
            },'json');
        }
    }
    $body.on('submit', ".js-form-ajax", function(e){
        e.preventDefault();
        formSubmit($(this));
    });

    $body.on('click', '.js-cart-submit', function(e) {
        var $this = $(this),
            is_fast = $this.data('is_fast');
        document.getElementById('is_fast').value = is_fast;
        if(is_fast) {
            e.preventDefault();
            formSubmit($this.closest('form'));
        }

    });

    // Form validate
    function formValid(fields, errors) {
        if(typeof fields === 'undefined')
            return false;

        if(typeof errors === 'undefined')
            errors = false;

        var checked = 0;
        fields.each(function(index, el) {
            if(el.name == 'phone') {
                if( el.value.length > 14 ) {
                    checked++;
                    $(el).removeClass('error');
                }
                else {
                    if(errors)
                        $(el).addClass('error');
                }
            }
            else {
                if(el.value && el.value.length > 1) {
                    checked++;
                    $(el).removeClass('error');
                }
                else {
                    if(errors)
                        $(el).addClass('error');
                }
            }
            if(el.type == "checkbox") {
                if(!el.checked) {
                    if(errors)
                        $(el).addClass('error');
                }else {
                    $(el).removeClass('error');
                    checked++;
                }
            }
        });
        return checked == fields.length;
    }

    // Quantity change
    $body.on('click', '.js-quantity', function() {
        var $this = $(this),
            num = parseInt($this.data('num')),
            $input = $this.parent().find('.js-quantity-input'),
            quantity = parseInt($input.val()),
            sum = quantity + num;
        if(  sum > 0 ) {
            $input.val(sum);
        }
        if(typeof $this.data('submit') !== 'undefined') {
            $this.closest('form').submit();
        }
        else {
            updateCartSums($this);
        }
    });
    var timer;
    $body.on('keyup' ,'.js-quantity-input', function() {
        clearTimeout(timer);
        var $this = $(this);
        timer = setTimeout(function(){
            if(typeof $this.data('submit') !== 'undefined') {
                $this.closest('form').submit();
            }
            else {
                updateCartSums($this);
            }
        },1000)
    });
    function updateCartSums($this) {
        var product = $this.closest('.js-product'),
            price = parseInt(product.find('.js-price').data('price')),
            cnt = parseInt(product.find('.js-quantity-input').val()),
            amount = product.find('.js-amount'),
            total = $('.js-total-amount'),
            newAmount = price * cnt,
            dif = newAmount - parseInt(amount.data('amount')),
            newTotal = parseInt(total.data('total') + dif);
        amount.data('amount', newAmount);
        amount.text(number_format(newAmount, 0, '.', ' ') + '₽');
        total.data('total', newTotal);
        total.text(number_format(newTotal, 0, '.', ' ') + '₽');
    }

    // Do some action by ajax
    $body.on('click', '.js-action-link', function(e) {
        e.preventDefault();
        var $this = $(this),
            url = $this.data('url'),
             postData = $this.data();
            delete postData.url;
        $.get(url, postData, function(data) {
            // Exception
            if(typeof data.error !== 'undefined'){
                console.log(data.message);
            }
            // Do some action
            if(typeof data.action !== 'undefined'){
                var fn = window[data.action];
                if(typeof fn === 'function') {
                    fn(data);
                }
            }
        });
    });

    // Mask phone
    $('.js-phone').mask("+7 000 000 00 00", {placeholder: "+7 ___ ___ __ __"});

    // Check required fields
    $body.on('input', '.js-required-fields', function(e) {
        var fields = $('.js-required-fields');
        if( formValid(fields) ) {
            $('.js-step-next').attr('disabled', false);
        }
        else{
            $('.js-step-next').attr('disabled', true);
        }
    });
    $body.on('click', '.js-required-fields', function(e) {
        var fields = $('.js-required-fields');
        if( formValid(fields) ) {
            $('.js-step-next').attr('disabled', false);
        }
        else{
            $('.js-step-next').attr('disabled', true);
        }
    });
    // Product gallery thumbs switch
    $body.on('click', '.js-gallery-thumb', function(e) {
        var $this = $(this),
            images = $('.js-gallery-big');
        $('.js-gallery-thumb').removeClass('active');
        $this.addClass('active');
        images.removeClass('active');
        images.eq($this.index()).addClass('active');
    });

    $.fancybox.defaults.hash = false;
    //прокрутка до последнего просмотренного продукта
    $scrollTarget = $('#scrollTarget');
    if($scrollTarget.length) {
        scrollToEl2($scrollTarget);
    }
    $('#search').submit(function(e) {
        if(!$(this).find('input[name=text]').val()) {
            e.preventDefault();
            return false;
        }
    });
    //при загрузке влючаем disabled у размеров, так как браузер запомнил из без disabled
    $('.js-square-check-single input[name=size]').prop('disabled', true);
    var squareTimeout;
    $('.js-square').each(function() {
        $(this).click(function() {
            clearTimeout(squareTimeout);
            if(!$(this).hasClass('missing')) {
                $('#tooltip').hide();
                return;
            }
            var p = $(this).offset();
            var left = p.left;
            if(left + 200 > $(window).width()) {
                left = 20;
            }
            $('#tooltip')
                .css('top', p.top + $(this).outerHeight() + 'px')
                .css('left', left)
                .show();
            squareTimeout = setTimeout(function() {
                $('#tooltip').hide();
            }, 3000);
        })
    })

});

// scroll to element
function scrollToEl($el) {
    var top = $el.offset().top - 100;
    if($(document).scrollTop() > top) {
        $('html, body').animate({
            scrollTop: top
        }, 300);
    }
}
function scrollToEl2($el) {
    var top = $el.offset().top - 100;
    $('html, body').animate({
        scrollTop: top
    }, 300);
}

// Update cart widget
function updateCart(data){
    if(typeof data.count !== 'undefined') {
        $(".js-cart-quantity").text(data.count);
    }

    if(typeof data.modal !== 'undefined') {
        openModal(data);
    }

    if(typeof data.removed !== 'undefined') {
        var product = $("[data-id=" + data.removed + "]"),
            total = $('.js-total-amount');
        total.data('total', data.amount);
        total.text(number_format(data.amount, 0, '.', ' ') + '₽');
        product.remove();
    }
}

// Next step, accepts element or number
function nextStep($el) {
    var step = $.isNumeric($el) ? $el : $el.data('next_step');
    if(typeof step !== 'undefined') {
        $(".js-step").removeClass('active');
        $(".js-step_" + step).addClass('active');
    }
}

// Show order success page
function orderSuccess(data) {
    // Show next step
    $('#order-success').append(data.html);
    nextStep(3);
    scrollToEl($("#js-order-success"));
}

function openModal(data) {
    if(typeof data.modal !== 'undefined') {
        $.fancybox.close();
        $.fancybox.open([{
                src : data.modal,
                type : 'inline'
            }],
            {
                closeBtn: false,
                beforeClose : function( instance, current, e ) {
                    var form = current.$slide.find('form');
                    if(typeof form.data('submit-on-close') !== 'undefined'){
                        form.submit();
                    }
                    if(typeof data.modalAction !== 'undefined'){
                        if(data.modalAction == 'refresh-on-close')
                            window.location = '/';
                    }
                }
            });
    }
}

function number_format( number, decimals, dec_point, thousands_sep ) {	// Format a number with grouped thousands
    //
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +	 bugfix by: Michael White (http://crestidg.com)

    var i, j, kw, kd, km;

    // input sanitation & defaults
    if( isNaN(decimals = Math.abs(decimals)) ){
        decimals = 2;
    }
    if( dec_point == undefined ){
        dec_point = ",";
    }
    if( thousands_sep == undefined ){
        thousands_sep = ".";
    }

    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

    if( (j = i.length) > 3 ){
        j = j % 3;
    } else{
        j = 0;
    }

    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
    //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


    return km + kw + kd;
}

// Show success on product comment submit
function commentSuccess(data) {
    if(typeof data.html !== 'undefined') {
        $(".js-comment-success").html(data.html);
    }
}

// Update some counter by selector
function updateCounter(data) {
    if(typeof data.selector !== 'undefined' && typeof data.count !== 'undefined') {
        $selector = $(data.selector);
        $selector.text(data.count);
        if(data.count == 0) {
            $selector.parent().removeClass('active');
        }else {
            $selector.parent().addClass('active');
        }
    }
}

// Pagination replace content
function paginationReplace(data) {
    if(typeof data.html !== 'undefined' && typeof data.model !== 'undefined') {
        // Replace container data with new
        $('.js-container-' + data.model).html(data.html);
        // Hide pagination buttons
        $('.js-pagination-' + data.model).addClass('hidden');
    }
}

// Pagination append content
function paginationAppend(data) {
    if(typeof data.html !== 'undefined' && typeof data.model !== 'undefined') {
        var pag = $('.js-pagination-' + data.model),
            button = pag.find('button:first-child');
        // Append items to container
        $('.js-container-' + data.model).append(data.html);
        // Update pagination button data
        if(typeof data.count !== 'undefined' && data.count > 0) {
            button.data('page', data.nextPage);
            button.find('.js-items-count').text('(' + data.count + ')');
        }
        else {
            // Hide pagination buttons if no more items
            pag.addClass('hidden');
        }

    }
}
//# sourceMappingURL=app.js.map
