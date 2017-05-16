// Открывает модальное окно с сообщением для покупателя
// window.thankYouCustomer('Error'); - выведет сообщение об ошибке
// window.thankYouCustomer('A message', 'Bob') - поблагодарит Bob`а за лид
window.thankYouCustomer = function(message, name) {
    var $modal = $('#thanks'),
        $title = $modal.find('.form-modal_title'),
        $message = $modal.find('p');

    if(typeof(name) === 'undefined') {
        $title.text('Произошла ошибка!');
        $message.text(message);
    } else {
        $title.text('Спасибо, ' + name + '!');
        $message.text(message);
    }

    $.fancybox.open({ src: '#thanks', type: 'inline' });
};
