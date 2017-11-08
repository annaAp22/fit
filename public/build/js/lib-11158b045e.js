$(function() {
    window.loader = $('#loader');

    $(document).ajaxStart(function() {
        if (window.loader.hasClass('hidden')) $(window.loader).removeClass('hidden');
    });

    $(document).ajaxStop(function() {
        if (!window.loader.hasClass('hidden')) $(window.loader).addClass('hidden');
    });
});
//# sourceMappingURL=lib.js.map
