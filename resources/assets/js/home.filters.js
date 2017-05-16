$(function() {
    var $homeProducts = $('.home-products'),
        initial = $homeProducts.clone().html(),
        carouselOptions = {
            items: $homeProducts.data('slide-count'),
            nav: $homeProducts.data('arrows'),
            dots: $homeProducts.data('dots'),
            navText: ['', ''],
            autoplay: true,
            autoplayTimeout: 8000,
            loop: true,
            autoplayHoverPause: true
        };

    $homeProducts.trigger('destroy.owl.carousel');
    $homeProducts.removeClass('owl-loaded');
    $(initial).appendTo($('.tmp'));

    $homeProducts.owlCarousel(carouselOptions);

    $('button.home-filter').on('click', function(e) {
        e.preventDefault();

        $('button.home-filter').removeClass('active');
        $(this).addClass('active');

        $homeProducts.trigger('destroy.owl.carousel');
        $homeProducts.removeClass('owl-loaded');

        var type = $(this).data('filter'),
            $tmp = $('.tmp');

        $homeProducts.html('');
        switch(type) {
            case 'hit':
                $homeProducts.append($tmp.find('article.product_item.mod-hit').clone());
                break;
            case 'new':
                $homeProducts.append($tmp.find('article.product_item.mod-new').clone());
                break;
            case 'sale':
                $homeProducts.append($tmp.find('article.product_item.mod-act').clone());
                break;
        }

        $homeProducts.owlCarousel(carouselOptions);
    });
});