$(function() {
    $('.fast-buy-button').on('click', function(e) {
        e.preventDefault();

        var $modal = $('#quick-order'),
            $title = $modal.find('.quick-order_col_data a'),
            $img = $modal.find('.quick-order_col_thumb img'),
            $quantity = $modal.find('.quick-order_col_data input'),
            $price = $modal.find('.quick-order_col_price span.price'),
            $id = $modal.find('input[name=id]'),
            id = $(this).data('id'),
            quantity = $(this).data('quantity'),
            img = $(this).data('img'),
            title = $(this).data('title'),
            price = $(this).data('price'),
            link = $(this).data('link');

        $id.val(id);
        $title.text(title);
        $title.attr('href', link);
        $price.text(price);
        $img.attr('src', img);
        $quantity.val(quantity);

        $.fancybox.open({ src: '#quick-order', type: 'inline' });
    });

    $('#quick-order form').on('submit', function(e) {
        e.preventDefault();

        var customerName = $(this).find('input[name=name]').val(),
            _form = $(this);

        $.post($(this).attr('action'), $(this).serialize(), function(data) {

            if(typeof(data.message) !== 'undefined') {
                window.thankYouCustomer(data.message);
                return;
            }

            _form[0].reset();
            $.fancybox.getInstance().close();
            window.thankYouCustomer('В ближайшее время Вам перезвонит наш персонал. Пожалуйста, не отключайте телефон.', customerName);
        });
    });
});