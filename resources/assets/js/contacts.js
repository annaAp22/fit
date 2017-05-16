$(function() {
    $('#contacts-form').on('submit', function(e) {
        e.preventDefault();

        var customerName = $(this).find('input[name=name]').val(),
            $_t = $(this);

        $.post($(this).attr('action'), $(this).serialize(), function(data) {
            if(data.result != 'ok') {
                window.thankYouCustomer(data.message);
                return;
            }

            window.thankYouCustomer('Ваше письмо отправлено! В ближайшее время мы ответим Вам.', customerName);
            $_t[0].reset();
        });
    });
});