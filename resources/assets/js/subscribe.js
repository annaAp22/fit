$(function() {
    $('form.subscribe').submit(function(e) {
        e.preventDefault();

        var $email = $(this).find('input[name=email]'),
            $form = $(this),
            $headline = $(this).find('.h1'),
            $message = $(this).find('.mod-fs-90'),
            $inputs = $(this).find('.form-line');

        if(!$email.val() || $email.val()=='') {
            $email.css({"border-color": "red"});
            return false;
        }

        var $data = {
            'email': $email.val(),
            'act': $(this).find('input[name=act]').length ? 1 :0
        };

        $.post($(this).attr('action'), $data, function(response){
            if(response.message) {
                $headline.text('Ошибка!');
                $message.text(response.message);
            } else {
                $headline.text('Спасибо!');
                $message.text('Теперь мы будем оповещать Вас о новых акциях, скидках и новинках магазина.')
                $inputs.addClass('hidden');
            }
        });
    });
});
