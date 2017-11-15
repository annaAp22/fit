$(function() {
    window.loader = $('#loader');
    $(document).ajaxSend(function(event, XMLHttpRequest, ajaxOptions) {
        var other_ajax_url = [
            'https://cartprotector.com/',
        ];
        if($.inArray(ajaxOptions.url, other_ajax_url) != -1) {
            console.log('loader blocked for ' + ajaxOptions.url);
        } else {
            if (window.loader.hasClass('hidden')) $(window.loader).removeClass('hidden');
        }
    });
    $(document).ajaxStart(function() {

    });

    $(document).ajaxStop(function() {
        if (!window.loader.hasClass('hidden')) $(window.loader).addClass('hidden');
    });
});