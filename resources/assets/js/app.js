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


    // Toggle target active class on mouse over
    $(".js-toggle-active-target_over").mouseenter(function(){
        if( !$(this).hasClass('active'))
            toggleActiveTarget($(this));
    });

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
            xl: {items: 5, options : {margin: 0, vertical: true}},
            lg: {items: 5, options : {margin: 0, vertical: true}},
            md: {items: 5, options : {margin: 0, vertical: true}},
            sm: {items: 5, options : {margin: 0, vertical: false}},
            xs: {items: 4, options : {margin: 0, vertical: false}}
        }
    });

    //Product set carousel
    $("#js-product-set").carousel();

    // Product seen carousel
    $(".js-product-carousel").carousel();

    // Load VK comments widget
    $(".js-vk-comments-widget").on('click.vk',function() {
        $(this).off('click.vk');
        $.getScript("//vk.com/js/api/openapi.js?145", function() {
            VK.init({apiId: API_ID, onlyWidgets: true});
            VK.Widgets.Comments("js-vk_comments", {limit: 5, attach: false});
        });
    });
    //good review
    $('.product-review-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.post(form.attr('action'), form.serialize(), function(data) {
            if(data.result === 'ok') {
                var title = form.find('.form-success__title');
                var body = form.find('.product-review-form__body');
                body.children().css('display', 'none');
                title.text('Спасибо, '+ form.get(0)['name'].value +'!');
                body.find('.form-success').css('display', 'block');
            } else {

            }
        });
        return false;
    });
    function recalcProductReviews(currentCount, totalCount) {
        if(!totalCount)
            totalCount = $('#product-reviews').data('count');
        if(!currentCount)
            currentCount = $('.product-review').length;
        var moreCount = totalCount - currentCount;
        if(moreCount > 20)
            moreCount = 20;
        if(moreCount > 0) {
            var s = '(' + (moreCount) + ')';
            $('#product-reviews .btn_more .count').text(s);
        }else {
            $('.product-reviews-navigation:first').hide();
        }

    }
});
function appendComments(data) {
    var navigation = $('#product-reviews-navigation');
    var btn_more = $('#product-reviews .btn_more:first');
    var per_page = 5;
    if(data['clear']) {
        $('.product-review').remove();
    }
    if(data['count'] > 0) {
        btn_more.data('page', data['next_page']);
        if(data['count'] > per_page) {
            data['count'] = per_page;
        }
        btn_more.find('.count').text('(' + data['count'] + ')');
    }else {
        navigation.hide();
    }
    navigation.before(data['html']);
}

function appendGoods(data) {
    var $goods_block = $('#js-goods');
    var $navigation = $('.page-navigation');
    var $btn_more = $navigation.find('.btn_more');
    if(data['nextPage']) {
        $btn_more.data('page', data['nextPage']);
        $btn_more.find('.count').text('(' + data['count'] + ')');
    } else {
        $navigation.remove();
    }
    if(data['clear']) {
        $goods_block.text('');
    }
    $goods_block.append(data['html']);
}

