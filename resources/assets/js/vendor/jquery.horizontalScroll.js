/**
 * @author Vladimir Bukvinov
 * @version 0.0.0.1
 */
/**
 * Функция вешается на контейнер, который содержит один блок.
 * Блок будет прокручиваться внутри контейнера с помощью кнопок управления.
 * Кнопки управления будут показаны , если скролл не виден(опционально)
 * */
(function ($, undefined) {
    var defaults = {
        'buttonsWrapper':'.js-buttons-wrapper',//обертка содержащая кнопки, располагается внутри контейнера, может быть скрыта(опционално)
        'leftButton':'.js-left-btn',//кнопка которая сдвигает скролл влево
        'rightButton':'.js-right-btn',//кнопка, которая сдвигает скролл вправо
        'scrollStep': 70,
        'autoHide':false,//скрывать обертку для кнопок скролирования, если скролл отсутствует
        'autoHideMargin':100//нижняя часть контейнера, при видимости которой кнопки должны исчезать
    }
    $.fn.horizontalScroll = function (options) {
        if (this.length === 0) {
            return this;
        }
        //показать/скрыть кнопки прокрутки
        function showHideButtonsWrapper() {
            var $this = $(window);
            if($block.outerWidth() > $wrapper.outerWidth()) {
                if($this.scrollTop() + $this.height() > $wrapper.offset().top + $wrapper.height() - settings.autoHideMargin) {
                    buttonsWrapper.hidden = true;
                } else {
                    buttonsWrapper.hidden = false;
                }
            }else {
                buttonsWrapper.hidden = true;
            }
        }
        var settings = $.extend(true, {}, defaults, options);
        var wrapper = this.get(0);
        var $wrapper = this.eq(0);
        var $block = $wrapper.children(':first');
        var buttonsWrapper;
        var leftBtn, rightBtn;
        buttonsWrapper = wrapper.querySelector(settings.buttonsWrapper);
        leftBtn = buttonsWrapper.querySelector(settings.leftButton);
        rightBtn = buttonsWrapper.querySelector(settings.rightButton);
        $(leftBtn).click(function(e) {
            $wrapper.scrollLeft($wrapper.scrollLeft() - settings.scrollStep);
        })
        $(rightBtn).click(function(e) {
            $wrapper.scrollLeft($wrapper.scrollLeft() + settings.scrollStep);
        })
        if(settings.autoHide) {
            showHideButtonsWrapper();
            //при изменении размеров окна, прячем прячем/показываем кнопки
            $(window).resize(function(e) {
                showHideButtonsWrapper();
            });
            //при скролле окна, прячем прячем/показываем кнопки
            $(window).scroll(function(e) {
                showHideButtonsWrapper();
            });
        }
        return this;
    }
})(jQuery);