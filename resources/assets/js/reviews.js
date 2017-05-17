$(function() {
    var $modal = $('#add-review'),
        $form = $modal.find('form'),
        $rating = $form.find('.js-rating-input'),
        route = window.routes.product_comment_path;

    $('.add-review-button').on('click', function(e) {
        e.preventDefault();
        $rating.show();

        var productId = $(this).data('product-id');
        if(typeof(productId) === 'undefined') productId = 0;
        if(productId === 0) $rating.hide();

        $form.attr('action', window.injectParams(route, {id: productId}));
        $.fancybox.open({ src: '#add-review', type: 'inline' });
    });

    $form.on('submit', function(e) {
        e.preventDefault();
        var customerName = $form.find('input[name=name]').val();

        $.post($(this).attr('action'), $(this).serialize(), function(data) {
            if(data.result === 'ok') {
                $form[0].reset();
                $.fancybox.getInstance().close();
                window.thankYouCustomer('Ваш отзыв отправлен! В ближайшее время мы ответим Вам.', customerName);
            } else
                window.thankYouCustomer(data.message);

        });
    });
});