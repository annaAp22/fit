// Инициализация корзины
window.initCart = function(options) {
    window.cart = options;

    // Установка опций по умолчанию
    setDefault = function(optName, defaultValue) {
        window.cart[optName] = typeof(window.cart[optName]) === 'undefined' ? defaultValue : window.cart[optName];
    };

    // Упаковка в массив
    toArray = function(arg) {
        if(!$.isArray(arg)) arg = [ arg ];
        return arg;
    };

    // Проверить есть ли позиция в заданном списке по ID
    isInList = function(listSelector, listItemSelector, id) {
        return $(listSelector).find(listItemSelector + '[data-id=' + id + ']').length > 0;
    };

    // Проверить пуст ли заданный список
    isEmptyList = function(listSelector, listItemSelector) {
        return $(listSelector).find(listItemSelector).length <= 0;
    };

    // Добавить позицию в заданный список по шаблону
    // убирает класс "empty" с главного блока
    prependList = function(blockSelector, listSelector, listItemSelector, template, params) {
        if(!isInList(listSelector, listItemSelector, params.id)) {
            var html = window.injectParams(template, params);
            $(listSelector).prepend(html);
            $(blockSelector).removeClass(window.cart.emptyClass);
        }
    };

    // Убрать позицию из заданного списка по ID
    // добавляет класс "empty" на главный блок, если список пуст
    removeItemFromList = function(blockSelector, listSelector, listItemSelector, id) {
        $(listSelector).find(listItemSelector).each(function(i, el) {
            if($(el).data('id') == id) $(el).remove();
        });

        if(isEmptyList(listSelector, listItemSelector))
            $(blockSelector).addClass(window.cart.emptyClass);
    };

    // AJAX: Добавить товар в корзину
    add = function(id, qnt, extra_params, callback) {
        qnt = qnt || 1;
        var method = typeof(window.cart.addMethod) === 'undefined' ? 'get' : window.cart.addMethod;
        $[method](
            window.injectParams(window.cart.addUrl, {id: id, quantity: qnt }),
            extra_params,
            callback
        );
    };

    // AJAX: Убрать товар из корзины
    remove = function(id, callback) {
        var method = typeof(window.cart.removeMethod) === 'undefined' ? 'get' : window.cart.removeMethod;
        $.ajax({
            type: method,
            url: window.injectParams(window.cart.removeUrl, { id: id }),
            success: callback
        });
    };

    // Обновление информационных элементов
    updateTotals = function(count, amount, count_name) {
        for(var index in window.cart.quantitySelectors)
            $(window.cart.quantitySelectors[index]).text(count);

        for(var index in window.cart.totalSelectors)
            if($(window.cart.totalSelectors[index]).data('without-currency') !== 'undefined')
                $(window.cart.totalSelectors[index]).text(amount);
            else
                $(window.cart.totalSelectors[index]).text(amount + ' ' + window.cart.currency);

        for(var index in window.cart.quantityNameSelectors)
            $(window.cart.quantityNameSelectors[index]).text(count_name);
    };

    // Добавить позицию в виджет корзины
    insertInfoblockListItem = function(params) {
        prependList(
            window.cart.infoblockSelector,
            window.cart.infoblockListSelector,
            window.cart.infoblockListItemSelector,
            window.cart.infoblockListItemTemplate,
            params);
    };

    // Добавить позицию на странице корзины
    insertDetailsListItem = function(params) {
        prependList(
            window.cart.detailsSelector,
            window.cart.detailsListSelector,
            window.cart.detailsListItemSelector,
            window.cart.detailsListItemTemplate,
            params);
    };

    // Убрать позицию из виджета корзины
    removeInfoblockListItem = function(id) {
        removeItemFromList(
            window.cart.infoblockSelector,
            window.cart.infoblockListSelector,
            window.cart.infoblockListItemSelector,
            id);
    };

    // Убрать позицию со страницы корзины
    removeDetailsListItem = function(id) {
        removeItemFromList(
            window.cart.detailsSelector,
            window.cart.detailsListSelector,
            window.cart.detailsListItemSelector,
            id);
    };

    // Callback для добавляющих элементов
    addFunction = function() {
        var requirements = $(this).data('requires'),
            id           = $(this).data('id'),
            quantity     = $(this).data('quantity'),
            noId         = typeof(id) === 'undefined',
            noQuantity   = typeof(quantity) === 'undefined',
            _t           = this;

        if(noId) console.error('Define product id as `data-id` attribute on `Add to cart` button.');
        if(noQuantity) console.error('Define quantity as `data-quantity` attribute on `Add to cart` button.')

        if(noId || noQuantity) return;

        var extra_params = {};
        for(var i in requirements) {
            var requirement = $(this).data(requirements[i]);
            if(typeof(requirement) === 'undefined') {
                var message = $(this).data('requires-' + requirements[i] + '-message');
                window.thankYouCustomer(message);
                return;
            }

            extra_params[requirements[i]] = requirement;
        }

        if(typeof(window.cart.animationCallback) !== 'undefined') window.cart.animationCallback();

        add(id, quantity, extra_params, function(data) {
            updateTotals(data.count, data.amount, data.count_name);
            insertInfoblockListItem({
                currency: window.cart.currency,
                id: $(_t).data('id'),
                class: window.cart.infoblockListItemSelector.substr(1),
                img: $(_t).data('img'),
                title: $(_t).data('title'),
                price: $(_t).data('price'),
                link: $(_t).data('link')
            });
            insertDetailsListItem({
                currency: window.cart.currency,
                id: $(_t).data('id'),
                title: $(_t).data('title'),
                img: $(_t).data('img'),
                link: $(_t).data('link'),
                stock: ($(_t).data('stock') > 0) ? window.cart.detailsInStock : window.cart.detailsOutOfStock,
                quantity: $(_t).data('quantity'),
                price: $(_t).data('price'),
                item_total: parseInt($(_t).data('price')) * parseInt($(_t).data('quantity')),
            });
            window.cart.updateCallback('add', _t, data);
        });
    };

    // Callback для убирающих элементов
    removeFunction = function() {
        var id         = $(this).data('id'),
            quantity   = $(this).data('quantity'),
            _t         = this;

        if(typeof(id) === 'undefined') {
            console.error('Define product id as `data-id` attribute on `Add to cart` button.');
            return;
        }

        remove(id, function(data) {
            updateTotals(data.count, data.amount, data.count_name);
            removeInfoblockListItem($(_t).data('id'));
            removeDetailsListItem($(_t).data('id'));
            window.cart.updateCallback('remove', _t, data);
        });
    };

    // Инициализация
    // Опции по умполчанию
    setDefault('emptyClass', 'empty-cart');
    setDefault('currency', 'р.');
    setDefault('addMethod', 'get');
    setDefault('removeMethod', 'get');

    // Элементы, которые должны быть преобразованы в массив
    var needToBeAnArray = [
        'addSelectors',
        'removeSelectors',
        'quantitySelectors',
        'quantityNameSelectors',
        'totalSelectors'
    ];
    for(var index in needToBeAnArray)
        window.cart[needToBeAnArray[index]] = toArray(window.cart[needToBeAnArray[index]]);

    // Элементы, добавляющие товар в корзину
    for(var index in window.cart.addSelectors)
        $(document).on('click', window.cart.addSelectors[index], addFunction);

    // Элементы, убирающие товар из корзины
    for(var index in window.cart.removeSelectors)
        $(document).on('click', window.cart.removeSelectors[index], removeFunction);

    $(window.cart.detailsSelector).on('change', window.cart.detailsQuantityInputSelector, function() {
        var currentRow = $(this).parents(window.cart.detailsListItemSelector)[0],
            newQuantity = parseInt($(this).val()),
            price = parseInt($(this).data('price')),
            subTotal = newQuantity * price;

        // console.log($(currentRow).find(window.cart.detailsSubtotalSelector).text(subTotal));
        // .find(window.cart.detailsSubtotalSelector)
    });
};

