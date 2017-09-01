$(function(){
    ///^[a-z0-9_-]+@[a-z0-9-]+\.[a-z]{2,6}$/i;
    var email_pattern =/.+@.+\..+/i;
    var $body = $('body');

    // Toggle active class
    $body.on("click", ".js-toggle-active", function(e){
        e.stopPropagation();
        var $this = $(this),
            reset = $this.data('reset');
        if($this.hasClass(".js-prevent-default"))
            e.preventDefault();
        // Remove active class
        if(typeof reset !== 'undefined') {
            $(reset).not($this).removeClass("active")
        }

        $this.toggleClass('active');
    });
    //Prevent default for hovered elements
    $body.on("click", ".js-prevent", function(e){
        e.preventDefault();
        return false;
    });
    //переключение вкладок и страниц соответствующих вкладкам
    $body.on("click", ".js-tab-active", function(e) {
        e.stopPropagation();
        var $this = $(this);
        var $parent = $this.closest('.js-tabulator');
        var $pages = $parent.find('.js-tab-page');
        var $tabs = $parent.find('.js-tab-active');
        var $page = $pages.eq($this.index());
        var data = $page.data();
        $tabs.removeClass('active');
        $this.addClass('active');
        $pages.removeClass('active');
        $page.addClass('active');
        //если содержимое вкладки уже было создано, то больше не создаем
        if(typeof data.complete != 'undefined' && data.complete == '1') {
            return;
        }
        //выполняем привязанное действие к странице, соответствующей вкладке
        if(typeof data.action != 'undefined') {
            var fn = window[data.action];
            var parent = $page.get(0);
            fn(parent);
        }
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
            var text = $(this).text();
            select.find(".js-selected").text(text);
            var $parent = select.closest('.js-select');
            var $hidden_input = $parent.find('.js-value');
            if($hidden_input.length) {
                var val = $(this).data('val');
                if(!val){
                    val = text;
                }
                $hidden_input.val(val);
                $hidden_input.trigger('change');
            }
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
            reset = $this.data('reset'),
            toggle = $this.data('toggle');

        // toggle switch if needed
        if(typeof switchN !== 'undefined') {
            $("[data-switch="+switchN+"]").toggleClass("active");
        }
        // remove active from all elements
        else if(typeof reset !== 'undefined') {
            $(reset).not($(target)).removeClass("active")
        }
        else if(typeof  toggle === 'unefined' || toggle == 1) {
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
            training = $('.js-dropdown-training'),
            navigation = $('.js-pages'),
            filter = $('.sidebar-filter'),
            geo = $('.js-geo-level-2');
        var screenSize = checkWindowWidth();
        navigation.detach();
        women.detach();
        men.detach();
        training.detach();
        filter.detach();
        geo.detach();

        if ( screenSize ) {
            //desktop screen
            navigation.insertAfter('.nav-catalog');
            women.prependTo('.js-women-desktop');
            men.prependTo('.js-men-desktop');
            training.prependTo('.js-training-desktop');
            //filter.prependTo('.sidebar');
            filter.insertAfter('.sidebar-catalog');
            geo.appendTo('.js-geo-desktop');

        } else {
            //mobile screen
            women.appendTo('.js-women-mobile');
            men.appendTo('.js-men-mobile');
            training.appendTo('.js-training-mobile');
            navigation.insertAfter('.js-pages-mobile');
            filter.appendTo('#sidebar-filters');
            geo.appendTo('.js-geo-mobile');
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
        auto:true,
        loop:true,
        responsive: {
            1492: 1,
            1203: 1,
            840: 1,
            576: 1,
            320: 1
        }
    });

    // Look Book banners slider
    var lookBook = $(".js-look-book");
    if( typeof lookBook !== 'undefined' ) {
        lookBook.carousel({
            margin: 20,
            pagination: true,
            auto: false,
            loop: true,
            responsive: {
                1492: 1,
                1203: 1,
                840: 1,
                576: 1,
                320: 1
            },
            afterLoad: function(instance) {
                if(instance.items.length > 2) {
                    instance.next();
                }
                return false;
            }
        });
    }


    productsSliderInit();

    // Load VK comments widget
    $(".js-vk-comments-widget").on('click.vk',function() {
        $(this).off('click.vk');
        $.getScript("//vk.com/js/api/openapi.js?145", function() {
            VK.init({apiId: 4411901, onlyWidgets: true});
            VK.Widgets.Comments("js-vk_comments", {limit: 5, attach: false});
        });
    });

    var vkReviews = $("#js-vk_reviews");
    if(vkReviews.length) {
        $.getScript("//vk.com/js/api/openapi.js?145", function() {
            VK.init({apiId: 4411901, onlyWidgets: true});
            VK.Widgets.Comments("js-vk_reviews", {limit: 10, attach: false});
        });
    }

    // Checkout steps
    $body.on('click', '.js-step-next', function(e) {
        e.preventDefault();
        nextStep($(this));
    });

    $body.on('click', '.js-link', function(e) {
        if($(this).hasClass('disabled')) {
            e.preventDefault();
        }
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
                if(typeof data.status !== 'undefined' && data.status == 'passwords.reset') {
                    document.forms['home'].submit();
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
            if(el.name == 'email') {
                if(el.value.search(email_pattern) == 0) {
                    $(el).removeClass('error');
                    checked++;
                } else if(errors){
                    $(el).addClass('error');
                }
            } else
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
        }, 'json');
    });

    // Mask phone
    $('.js-phone').mask("+7 000 000 00 00", {placeholder: "+7 ___ ___ __ __"});

    // Check required fields
    $body.on('input change click', '.js-required-fields', function(){
        var form = $(this).closest('form');
        formFieldsValidate(form);
    });
    function formFieldsValidate(form) {
        var fields = form.find('.js-required-fields');
        if( formValid(fields) ) {
            $('.js-step-next').attr('disabled', false);
            form.find('.js-link').removeClass('disabled');
        }
        else{
            $('.js-step-next').attr('disabled', true);
            form.find('.js-link').addClass('disabled');
        }
    }

    // TODO: Поместить в функцию, где нужно проверить форму. Вызывать метод проверки, а не событие нажатия.
    // Ну кто блин так делает? Зачем при каждой закгрузке любой страницы вызывать это событие?
    // Да ещё и не саму проверку, а событие нажатия. А если на этом поле ещё другая функция на нажатие повешена?
    // Как теперь понять, где это испоьлзуется? P.S. Поменял временно на change
    //
    //тут же проверяем на валидность поля, так, как они могут быть заполнены автоматически
    $('form').find('.js-required-fields:first').change();


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
    $(document).on('yaCounter32222358inited', function () {
        console.log('счетчик yaCounter32222358 можно использовать');
    });
    $(document).on('yaCounter32222361inited', function () {
        console.log('счетчик yaCounter32222361 можно использовать');
    });

    $(document).on('yaCounter12254515inited', function () {
        console.log('счетчик yaCounter12254515 можно использовать');
    });
    //yandex targets
    $body.on('click', '#quick-buy-btn', function(e) {
        if(typeof yaCounter12254515 !== 'undefined') {
            yaCounter12254515.reachGoal('1CLICK_BTN');
        }else {
            console.log('yaCounter32222358 not defined');
        }
        return true;
    });
    $body.on('click', '#quick-order-finish', function(e) {
        if(typeof yaCounter12254515 !== 'undefined') {
            yaCounter12254515.reachGoal('1CLICK_FINISH');
        }else {
            console.log('yaCounter12254515 not defined');
        }
        return true;
    });
    $body.on('click', '#order-finish-btn', function(e) {
        if(typeof yaCounter12254515 !== 'undefined') {
            yaCounter12254515.reachGoal('ORDER_FINISH');
        }else {
            console.log('yaCounter12254515 not defined');
        }
        return true;
    });
    $('.js-horizontal-scroll').horizontalScroll({
        'autoHide':true,
        'autoHideMargin':30
    });
    //показываем/скрываем товары под заказом, в таблице заказов
    $body.on('click', '.js-open-order', function(e) {
       var grandpa = $(this).parent().parent();
        grandpa.toggleClass('active')
        var container = grandpa.parent();
        var id = grandpa.data('id');
        if(id != null) {
            container.children('[data-id="'+id+'"]').toggleClass('active');
        }
    });

    // Change user geo city
    $body.on('click', '.js-geo-city', function(e) {
        var city = $(this).data('city'),
            expires = 3600 * 24 * 30;
        setCookie('city', city, {expires: expires, path: '/'});
        location.reload();
    });

    // Open user geo city modal from product card
    $body.on('click', '.js-product-delivery__city', function() {
        scrollToEl2($('header'));
        $('.js-geo-city-widget').addClass('active');
    });

    $('.js-geo-city-search').autocomplete({
        serviceUrl: '/ajax/geo_cities/autocomplete',
        minChars: 2,
        onSelect: function (suggestion) {
            //alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
            var city = suggestion.value,
                expires = 3600 * 24 * 30;
            setCookie('city', city, {expires: expires, path: '/'});
            location.reload();
        }
    });


    //event for filter button showing while mosemove in filter section
    $('#js-filters').hover(
        function () {
            $( "#js-filters" ).mousemove(function( event ) {
                var filtherH = $( "#js-filters" ).height();
                var colorFH = $( ".color-filter" ).height();
                if(colorFH == null){
                    colorFH = 0;
                }
                var parentOffset = $(this).offset();
                var relY = event.pageY - 22 - parentOffset.top;
                //alert('relY'+relY);
                var filterMH = filtherH - (colorFH + 125);
                $('#append_btn').css('top', relY );
                if(relY <= 100){
                    $('#append_btn').css('display', 'none' );

                }else if(relY >= filterMH){


                    $('#append_btn').css('display', 'none' );
                }else{
                    $('#append_btn').css('display', 'block' );
                }
            });
            $('#js-filters').css('position', 'relative');
            $(this).append('<button id="append_btn" class="btn btn_yellow btn_w100p js-close-filters" style="width: 202px; position: absolute; top: 0;left: 100%; z-index: 10;" name="apply">Применить</button>');
        },function () {
            $( this ).find( "#append_btn" ).remove();
        }
    );

    $('.js-youtube-video').fancybox();
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
        $(".js-add-to-cart-btn").hide();
        $(".js-added-to-cart-btn").addClass('active');
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
        var options = {
            src : data.modal,
            type : 'inline',
            smallBtn: false
        };
        $.fancybox.open([options],
            {
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

        $('form').find('.js-required-fields:first').change();
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

//обновление данных на странице
function elementsRender(data) {
    if (!data) {
        return false
    }
    var obj;
    var s;
    var i;
    //перейти на страницу
    obj = data['redirect'];
    if(typeof obj !== 'undefined' && obj) {
        location.href = obj;
        return true;
    }
    //обновить страницу
    obj = data['reload'];
    if(typeof obj !== 'undefined' && obj) {
        location.reload();
    }
    $.fancybox.close();
    //обновляем текст в блоке
    obj = data['text'];
    if (typeof obj !== 'undefined') {
        for(s in obj) {
            $(s).html(obj[s]);
        }
    }
    obj = data['append'];
    if (typeof obj !== 'undefined') {
        for(s in obj) {
            $(s).append(obj[s]);
        }
    }

    obj = data['fields'];
    if (typeof obj !== 'undefined') {
        for(s in obj) {
            $(s).value(obj[s]);
        }
    }
    obj = data['hide'];
    if (typeof obj !== 'undefined') {
        for(i=0; i < obj.length; i++) {
            $(obj[i]).attr('hidden', true);
        }
    }
    obj = data['show'];
    if (typeof obj !== 'undefined') {
        for(i=0; i < obj.length; i++) {
            $(obj[i]).removeAttr('hidden');
        }
    }
    return true
}

function replaceGeoCities(data) {
    if(typeof data.cities !== 'undefined') {
        $(".js-geo-city__body").html(data.cities);
    }
}
function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}
function deleteCookie(name) {
    setCookie(name, "", {
        expires: -1
    })
}
/*
* Add windget in parent
* @param parent - parent DOM element for widget, must contain data params:
* name - widget name,
* url - widget controller url.
* Other params contain widget options
* **/
function widget(parent) {
    var data = $(parent).data();
    var url = data.url;
    var params = {
        'name':data.name,
        'params':data.options
    }
    var callback;
    if(data.callback) {
        callback = window[data.callback];
    }
    $.post(url, params, function(data) {
        if(data) {
            var $parent = $(parent);
            $parent.empty();
            $parent.append(data);
            if(callback) {
                callback();
            }
        }
    }).done(function(e) {
        $(parent).data('complete', 1);
    });
}
//массив инициализированных слайдеров продуктов
var productsSlider = [];
//Создается еще один слайдер продуктов
function productsSliderInit() {
    var $sliders = $('.js-product-slider');
    var find;
    var slider;

    for(var i = 0; i < $sliders.length; i++) {
        find = false;
        //если на элементе уже установлен слайдер, то пропускаем его
        for(var j = 0; j < productsSlider.length; j++) {
            if(productsSlider[j] == $sliders[i]) {
                find = true;
                break;
            }
        }
        if(!find) {
            slider = $sliders[i];
            productsSlider.push(slider);
            $(slider).carousel({
                responsive: {
                    1492 : {items: 6},
                    1203 : {items: 5},
                    840 : {items: 5},
                    576 : {items: 3},
                    300 : {items: 2},
                    0 : {items: 1}
                }
            });
        }
    }
}

function carouselInit(selector, options) {
    if( typeof selector === 'undefined' )
        return false;
    if( typeof options === 'undefined' )
        options = {};
    $(selector).carousel(options);
}
