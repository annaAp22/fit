/**
 * @author Vladimir Bukvinov
 * @version 0.0.0.1
 */
/**
 * Показывает кнопки управления для горизонтального скрола не умещающегося контента.
 * Функция применяется к первому елемменту, подходящему под селектор
 * */
var defaults = {
    'wrapper': '.js-horizontal-scroll',//контейнер с горизонтальным скроллом
    'buttonsWrapper':null,//обертка содержащая кнопки, если не указана, значит кнопки лежат в контейнере
    'leftButton':'js-left-btn',//кнопка которая сдвигает скролл влево
    'rightButton':'js-right-btn',//кнопка, которая сдвигает скролл вправо
    'scrollStep': 70
}
(function ($, undefined) {
    $.fn.horizontalScroll = function (options) {
        var settings = $.extend(true, {}, defaults, options);
        var wrapper = document.querySelector(settings.wrapper);
        var $wrapper = $(wrapper);
        var buttonsWrapper;
        var leftBtn, rightBtn;
        if(settings.buttonWrapper) {
            buttonsWrapper = document.querySelector(settings.buttonWrapper);
        } else {
            buttonsWrapper = wrapper;
        }
        leftBtn = buttonsWrapper.querySelector(settings.leftButton);
        rightBtn = buttonsWrapper.querySelector(settings.rightButton);
        $(leftBtn).click(function(e) {
            $wrapper.scrollLeft($wrapper.scrollLeft() - settings.scrollStep);
        })
        $(rightBtn).click(function(e) {
            $wrapper.scrollLeft($wrapper.scrollLeft() + settings.scrollStep);
        })
        return this;
    }
})(jQuery);