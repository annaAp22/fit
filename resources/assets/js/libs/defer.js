window.initDefer = function(options) {
    window.defer = options;

    var requiredOptions = ['url', 'buttonSelector', 'countSelector'];

    for(var index in requiredOptions) {
        if(typeof(window.defer[requiredOptions[index]]) === 'undefined') {
            console.error('Function `initDefer` requires `' + requiredOptions[index] + '` option.');
            return;
        }
    }

    if(typeof(window.defer.activeClass) === 'undefined')
        window.defer.activeClass = 'active';

    $(document).on('click', window.defer.buttonSelector, function() {
        var productId = $(this).data('id');

        if($(this).hasClass(window.defer.activeClass))
            $(this).removeClass(window.defer.activeClass);
        else $(this).addClass(window.defer.activeClass);

        $.get(window.injectParams(window.defer.url, {id: productId}), function(data) {
            $(window.defer.countSelector).text(data);
        });
    });
};
