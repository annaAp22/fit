$(function() {
    window.initCart({
        addMethod: 'post',
        addUrl: '/#id/#quantity',
        addSelectors: '.js-add-to-cart-btn',

        removeMethod: 'delete',
        removeUrl: '/#id',
        removeSelectors: '.remove-from-cart-btn',

        quantitySelectors: [
            '.main-header_cart_count',
            '.cart-wrap_steps_count > span > span.mod-col-or'
        ],
        quantityNameSelectors: '.cart-wrap_steps_count > span > span.count-name',
        totalSelectors: [
            '.main-header_cart_total',
            '.main-header_cart_detail_bottom span.mod-col-or',
            '.cart-wrap .cart-wrap_bottom span.mod-col-or'
        ],

        infoblockSelector: '.main-header_cart',
        infoblockListSelector: '.main-header_cart_detail',
        infoblockListItemSelector: '.main-header_cart_detail_item',
        infoblockListItemTemplate: '<div class="#class" data-id="#id">' +
                                       '<figure><img src="#img"></figure>' +
                                       '<div><a href="#link">#title</a></div>' +
                                       '<span>#price #currency</span>' +
                                   '</div>',
        detailsSelector: '.cart-wrap',
        detailsListSelector: '.cart-wrap_table tbody',
        detailsListItemSelector: 'tr[data-id]',
        detailsListItemTemplate: '<tr data-id="#id">' +
                                    '<td class="col1">' +
                                        '<figure class="align-table-wrap">' +
                                            '<div><img src="#img"></div>' +
                                        '</figure>' +
                                    '</td>' +
                                    '<td class="col2">' +
                                        '<a class="cart-wrap_table_name" href="#link">#title</a>' +
                                        '<div class="product_item_available">' +
                                            '<span class="icon-in-stock">На заказ</span>' +
                                        '</div>' +
                                        '<div class="product_item_sku">Артикул: <span>#sku</span></div>' +
                                    '</td>' +
                                    '<td class="col3">' +
                                        '<span class="mod-bold">#price</span> #currency' +
                                    '</td>' +
                                    '<td class="col4">' +
                                        '<div class="js-counter product_counter_obj">' +
                                            '<button class="product_counter_obj_minus">-</button>' +
                                            '<input type="text" value="#quantity"/>' +
                                            '<button class="product_counter_obj_plus">+</button>' +
                                        '</div>' +
                                    '</td>' +
                                    '<td class="col5">' +
                                        '<span class="mod-bold">#item_total</span> #currency' +
                                    '</td>' +
                                    '<td class="col6">' +
                                        '<button class="delete remove-from-cart-btn" data-id="#id"></button>' +
                                    '</td>' +
                                '</tr>',
        detailsInStock: '<span class="icon-in-stock">В наличии</span>',
        detailsOutOfStock: '<span class="icon-no-avaible">На заказ</span>',
        detailsSubtotalSelector: 'td.col5 > span',
        detailsQuantityInputSelector: 'td.col4 input[type="text"]',

        animationCallback: function() {
            console.log('animated');
        },
        updateCallback: function(event, element, data) {
            var quantity = $(element).data('quantity'),
                id = $(element).data('id'),
                img = $(element).data('img'),
                title = $(element).data('title'),
                price = $(element).data('price'),
                link = $(element).data('link'),
                sku = $(element).data('sku'),
                $modal = $('#cart-modal'),
                $img = $modal.find('.cart-modal_top figure img'),
                $title = $modal.find('.cart-modal_top_name a'),
                $quantity = $modal.find('.cart-modal_top_counter input'),
                $price = $modal.find('.cart-modal_top_price span.price'),
                $sku = $modal.find('.cart-modal_top_name span');

            if(event == 'add') {
                console.log('Cart element added');
                $img.attr('src', img);
                $title.text(title);
                $title.attr('href', link);
                $quantity.val(quantity);
                $price.text(price);
                $sku.text(sku);
                $quantity.attr('name', 'goods[' + id + ']');
                $.fancybox.open({ src: '#cart-modal', type: 'inline' });
            } else {
                console.log('Cart element removed');
            }
        }
    });
});
