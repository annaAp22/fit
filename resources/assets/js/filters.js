$(function() {
    var $filters = $('#js-filters'),
        $sorters = $('.js-sort'),
        $page = $filters.find('input[name=page]'),
        $pageCount = $filters.find('input[name="pageCount"]'),
        $productsCount = $('.js-goods-count span'),
        $items = $('#js-goods'),
        $paginator = $('.js-pagination'),
        dontTouchThis = [
            'page',
            '_token',
            'category_id',
            'brand_id',
            'tag_id',
            'price_from',
            'price_to'
        ],
        dontTouchSelector = '',
        resetPagination = function(submit) {
            if(typeof submit === 'undefined')
                submit = false;
            //console.log('reset pagination');
            $page.val(1);
            $pageCount.val(1);
            if(submit) {
                $filters.trigger('submit');
            }
        },
        // Reset filters values
        resetFilters = function() {
            $filters.find('input[type=checkbox]', 'input[type=radio]').attr('checked', false);
            $filters.find('input[name^=attribute]').attr('disabled', true);
            $filters.find('input[name=sort]').val('sort');
            $('.js-square').removeClass('active');
            if(rangeSlider) {
                rangeSlider.noUiSlider.set([rRange[0], rRange[1]]);
            }
            resetPagination(true);
        };

    for(var index in dontTouchThis)
        dontTouchSelector += '[name!=' + dontTouchThis[index] + ']';

    if($page.val() == '') $paginator.hide();

    // Price range slider init
    var rangeSlider = document.getElementById('js-range-slider');
    if( rangeSlider ) {
        var $this = $(rangeSlider);
        rStart = $this.data('start'),
            rRange = $this.data('range');

        noUiSlider.create(rangeSlider, {
            start: [ rStart[0], rStart[1] ],
            connect: true,
            range: {
                'min': rRange[0],
                'max': rRange[1]
            }
        });

        var priceMin = document.getElementById('js-price-min'),
            priceMax = document.getElementById('js-price-max');

        rangeSlider.noUiSlider.on('update', function( values, handle ) {

            var value = values[handle];

            if ( handle ) {
                priceMax.value = Math.round(value);
            } else {
                priceMin.value = Math.round(value);
            }
        });

        rangeSlider.noUiSlider.on('set', function(){
            resetPagination();
        });

        priceMin.addEventListener('change', function(){
            rangeSlider.noUiSlider.set([this.value, null]);
        });
        priceMax.addEventListener('change', function(){
            rangeSlider.noUiSlider.set([null, this.value]);
        });
    }

    $filters.find('input[name^=attribute],select[name^=attribute]').on('change', function(e) {
        e.preventDefault();
        resetPagination();
    });
    $filters.find('.js-square').on('click', function(e) {
        resetPagination();
    });

    // $filters.on('submit', function(e) {
    //     e.preventDefault();
    //     var formData = $(this).serialize();
    //     console.log(formData);
    //     $.post($(this).attr('action'), formData, function(data) {
    //         if(data['reload'] == 1) {
    //             location.reload();
    //             return true;
    //         }
    //         if(data.clear) {
    //             $page.val(2);
    //             $items.html($(data.items));
    //         } else {
    //             if($page.val() == 'all') {
    //                 $items.html($(data.items));
    //             }
    //             else {
    //                 $items.append($(data.items));
    //             }
    //         }
    //
    //         if(data.next_page === null) {
    //             $paginator.hide();
    //         }
    //         else {
    //             $paginator.show();
    //             $page.val(data.next_page);
    //         }
    //         $productsCount.html(data.count);
    //     });
    // });
    $sorters.on('click', function(e) {
        e.preventDefault();
        var sort = $(this).data('sort');
        $filters.find("input[name=sort]").val(sort);

        if($(this).hasClass('active')) {
            $(this).removeClass('active');
            //$filters.find('input[type=hidden]' + dontTouchSelector).val('');
        } else {
            $sorters.removeClass('active');
            $(this).addClass('active');

            //$filters.find('input[type=hidden]' + dontTouchSelector).val('');
            //$filters.find('input[type=hidden][name=' + $(this).attr('name') + ']').val($(this).val());
        }

        resetPagination(true);
    });

    $paginator.on('click', function(e) {
        e.preventDefault();
        var showAll = $(this).data('all');
        if(typeof showAll !== 'undefined' && showAll) {
            $page.val('all');
        }else {
            $page.val(1);
        }
        $pageCount.val(parseInt($pageCount.val())+1);
        $filters.trigger('submit');
    });

    $(".js-filters-reset").on('click', function(e) {
        e.preventDefault();
        resetFilters();
        $('.js-toggle-sidebar.active').trigger('click');
        return false;
    });
    $(".js-close-filters").on('click', function(){
        $('.js-toggle-sidebar.active').trigger('click');
    });
});