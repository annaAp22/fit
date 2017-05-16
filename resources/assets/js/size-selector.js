$(function() {
    $('.product_item_size-selector a').click(function(e) {
        e.preventDefault();

        $(this).parent().find('a').removeClass('active');

        $(this).addClass('active');
        $(this)
            .parents('.product_item,.product-object_data')
            .find('.product_item_add-to-cart')
            .data('size', $(this).text());
    });
});