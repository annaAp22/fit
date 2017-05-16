$(function() {
    $('.search-next-page-btn').on('click', function(e) {
        e.preventDefault();

        var query = $(this).data('query'),
            nextPage = $(this).data('next-page')
            token = $('.main-header_search').find('input[type=hidden]').val(),
            _t = this;

        if(nextPage) {
            $.post('/search.html', {text: query, '_token': token, page: nextPage}, function(data) {

                $('.catalog-items').append($(data.html));
                $(_t).data('next-page', data.nextPage);

                if(data.nextPage == false) $(_t).hide();

            });
        }
    });
});