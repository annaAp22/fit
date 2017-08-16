window.initAutocomplete = function() {
    var $id = $(this).attr('id');
    $id = $id.replace(/\-/gi, "_");
    var selector = '#'+$id+'_chosen .chosen-search input';
    var $url = $(this).data('url');
    var MySelect = $(this);

    $(selector).autocomplete({
        source: function( request, response ) {
            $search_param = $(selector).val();
            var data = {
                search_param: $search_param
            };
            if($search_param.length > 2) { //отправлять поисковой запрос к базе, если введено более 2 символов
                $.post($url, data, function onAjaxSuccess(data) {
                    if((data.length!='0')) {
                        $('ul.chosen-results').find('li').each(function () {
                            $(this).remove();//отчищаем выпадающий список перед новым поиском
                        });
                        MySelect.find('option').each(function () {
                            $(this).remove(); //отчищаем поля перед новым поисков
                        });
                    }
                    jQuery.each(data, function(){
                        MySelect.append('<option value="' + this.id + '">' + this.name + ' </option>');
                    });
                    MySelect.trigger("chosen:updated");
                    $(selector).val($search_param);
                    anSelected = MySelect.val();
                });
            }
        }
    });
};

jQuery(function($) {
    $body = $('body');
    $('[data-rel=tooltip]').tooltip({container:'body'});
    $('[data-rel=popover]').popover({container:'body'});

    $(".action-delete").on(ace.click_event, function() {
        var $this = $(this);
        bootbox.confirm("Вы уверены что хотите удалить объект?", function(result) {
            if(result) {
                $this.closest('form').submit();
                return true;
            }
        });
    });

    $(".action-restore").on(ace.click_event, function() {
        var $this = $(this);
        bootbox.confirm("Вы уверены что хотите востановить объект?", function(result) {
            if(result) {
                $this.closest('form').submit();
                return true;
            }
        });
    });

    $('.chosen-select').chosen({allow_single_deselect:true});

    if($('.chosen-autocomplite').length)
        $('.chosen-autocomplite').each(window.initAutocomplete);

    $('textarea.limited').inputlimiter({
        remText: '%n символ(ов) осталось...',
        limitText: 'максимально: %n.'
    });

    $(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl+V, Command+V
            (e.keyCode === 86 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $('.spinbox-input').ace_spinner({min:0,max:100,step:1, on_sides: true, icon_up:'ace-icon fa fa-plus bigger-110', icon_down:'ace-icon fa fa-minus bigger-110', btn_up_class:'btn-success' , btn_down_class:'btn-danger'});

    if($('.calculate').length) {
        $('.calculate input[name="price"]').change(function(){
            var price = $('.calculate input[name="price"]').val();
            var price_old = $('.calculate input[name="price_old"]').val();
            if(price && price_old) {
                var discount = Math.ceil( ((price_old - price)/price_old) * 100 );
                if(discount < 0) {
                    discount = 0;
                }
                $('.calculate input[name="discount"]').val(discount)
            }

        });

        $('.calculate input[name="price_old"]').change(function(){
            var discountEl = $('.calculate input[name="discount"]'),
                discount = discountEl.val(),
                priceEl = $('.calculate input[name="price"]'),
                price = priceEl.val(),
                price_old = $(this).val();
            if(discount) {
                price = Math.ceil(  price_old * ( 1 - (discount / 100) ) );
                priceEl.val(price);
            }
            else if(price) {
                discount = Math.ceil( ((price_old - price)/price_old) * 100 );
                if(discount < 0) {
                    discount = 0;
                }
                discountEl.val(discount)
            }
        });

        $('.calculate input[name="discount"]').change(function(){
            var discount = $(this).val();
            var price_old = $('.calculate input[name="price_old"]').val();
            if(price_old) {
                var price = Math.ceil(  price_old * ( 1 - (discount / 100) ) );
                $('.calculate input[name="price"]').val(price)
            }
        });
    }

    if($('#photo-crop').length) {

        function preview(img, selection) {
            if (!selection.width || !selection.height)
                return;

            $('#x1').val(selection.x1);
            $('#y1').val(selection.y1);
            $('#x2').val(selection.x2);
            $('#y2').val(selection.y2);
        }

        var ias = $('#photo-crop').imgAreaSelect({
            movable:true,
            fadeSpeed: 200, onSelectChange: preview,
            instance: true, show:true, x1:0, y1:0, x2: $('#photo-crop').data('width'), y2:$('#photo-crop').data('height')
        });

        ias.setOptions({aspectRatio: $('#photo-crop').data('width') + ':' + $('#photo-crop').data('height')});
        ias.setOptions({minWidth: $('#photo-crop').data('width')});
        ias.setOptions({minHeight:$('#photo-crop').data('height')});
        ias.update();
    }


    CKEDITOR.replaceAll('ck-editor');

    $('.file-input-img').ace_file_input({
        no_file:'Не выбрано ...',
        btn_choose:'Выберите',
        btn_change:'Изменить',
        droppable:false,
        onchange:null,
        thumbnail:false, //| true | large
        whitelist:'gif|png|jpg|jpeg'
    });


    $('.img-drop').ace_file_input({
        style:'well',
        btn_choose:'Выберите или перенесите изображение',
        btn_change:null,
        no_icon:'ace-icon fa fa-cloud-upload',
        droppable:true,
        thumbnail:'fit'//large | fit
        ,
        preview_error : function(filename, error_code) {
        }
    }).on('change', function(){
    });
    var colorbox_params = {
        rel: 'colorbox',
        reposition:true,
        scalePhotos:true,
        scrolling:false,
        previous:'<i class="ace-icon fa fa-arrow-left"></i>',
        next:'<i class="ace-icon fa fa-arrow-right"></i>',
        close:'&times;',
        current:'{current} of {total}',
        maxWidth:'100%',
        maxHeight:'100%',
        onOpen:function(){
            $overflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';
        },
        onClosed:function(){
            document.body.style.overflow = $overflow;
        },
        onComplete:function(){
            $.colorbox.resize();
        }
    };

    $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);

    $(".youtube-popup").colorbox({iframe:true, innerWidth:640, innerHeight:390});

    $('.dynamic-input-item .plus').click(function(e){
        e.preventDefault();
        var block = $(this).closest('.dynamic-input-item').clone();
        block.find('.ace-file-container').remove();
        block.find('.plus').removeClass('plus').addClass('minus').end().
              find('i').removeClass('glyphicon-plus').addClass('glyphicon-minus').end().
              find(':input').val('').end().
              find('.field').hide();
        block.find('.input-color .palette-color-picker-button').remove();
        $(this).closest('.dynamic-input').append(block);

        $('.file-input-img').ace_file_input({
            no_file:'Не выбрано ...',
            btn_choose:'Выберите',
            btn_change:'Изменить',
            droppable:false,
            onchange:null,
            thumbnail:false, //| true | large
            whitelist:'gif|png|jpg|jpeg'
        });

        $('.input-mask').each(function(){
            $(this).mask($(this).data('mask'));
        });
    });

    $(document).on("click",".dynamic-input-item .minus",function(e) {
        e.preventDefault();
        $(this).closest('.dynamic-input-item').remove();
    });

    $(document).on("change",".select-get-sub",function(e) {
        var $select = $(this);
        $.ajax({
            url: $select.data('url'),
            type:'POST',
            data: 'id='+$select.val()+'&val='+$select.data('val'),
            success:function(data){
                $('#'+$select.data('content-id')).html(data);
            }
        });
    });

    if ($('.select-get-sub').length){
        $('.select-get-sub').trigger('change');
    }

    $('.photo-container .photo-action-delete').click(function(e){
        e.preventDefault();
        var el = $(this).closest('.photo-container-item');
        el.find('.photo-action-cancel').show().end().
        find('.photo-action-delete').hide().end().
        find('.label-delete').show().end().
        find('.input-delete').val(1);
    });

    $('.photo-container .photo-action-cancel').click(function(e){
        e.preventDefault();
        var el = $(this).closest('.photo-container-item');
        el.find('.photo-action-cancel').hide().end().
        find('.photo-action-delete').show().end().
        find('.label-delete').hide().end().
        find('.input-delete').val(0);
    });

    $.mask.definitions['~']='[+-]';
    $('.phone-mask').mask('+7(999) 999-99-99');

    $('.input-mask').each(function(){
        $(this).mask($(this).data('mask'));
    });

    $(".select2").css('width','200px').select2({allowClear:true})
        .on('change', function(){
            $(this).closest('form').validate().element($(this));
    });

    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

    $('.date-timepicker').datetimepicker({autoclose:true, format: 'DD.MM.YYYY H:mm'}).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });



    $('.input-daterange').datepicker({autoclose:true, format: 'dd.mm.yyyy'});

    $('.nestable').nestable();
    $('.sortable .dd-list').sortable();

    $('.check-vision').change(function(){
        $('.vision-group').hide();
        $('.'+$(this).val()+'-group').show();
    });

    $('.spinner').ace_spinner({value:0,min:0,max:20,step:1, btn_up_class:'btn-info' , btn_down_class:'btn-info'})
        .closest('.ace-spinner')
        .on('changed.fu.spinbox', function(){
            //alert($('#spinner1').val())
        });

    var multisortInner = function($form, $item, $id) {
        $item.find('> .dd-list > .dd-item').each(function(){
            $form.append('<input type="hidden" name="ids['+$id+'][]" value="'+$(this).data('id')+'">');
            multisortInner($form, $(this), $(this).data('id'));
        });
    }

    $('.multisort').submit(function(e){
       var $form = $(this);
       $form.find('.dd > .dd-list > .dd-item').each(function(){
          $form.append('<input type="hidden" name="ids[0][]" value="'+$(this).data('id')+'">');
          multisortInner($form, $(this), $(this).data('id'));
       });
       return true;
    });

    if($('.cart').length) {
        var cart_delivery = function() {
            var $cost = parseInt($('.cart').find(':input[name=delivery_id] option:selected').data('price'));
            var $amount = parseInt($('.cart .goods .amount').text());
            $('.cart .goods .delivery').text($cost);
            $('.cart .goods .total').text($cost + $amount);
        }

        $('.cart :input[name=delivery_id]').change(function(){
            cart_delivery();
        });

        cart_delivery();
    }

    $('.show-hidden-option').change(function(){
        if($(this).data('class')) {
            $('.' + $(this).data('class')).hide();
        }
        if($(this).find('option:selected').data('id')) {
            $('#' + $(this).find('option:selected').data('id')).show();
        }
    });

    $(document).on("change", '.dynamic-attributes .select-attribute', function(e) {
        var $block = $(this).closest('.dynamic-attributes');
        var $el = $(this).find('option:selected');

        var $values = $block.find('[data-dynamic]'),
            id = $(this).val(),
            type = $el.data('type'),
            value_input_name = 'attributes[' + id + ']';

        $block.find('.field').hide();
        $values.attr('name', '');

        $field = $block.find('.input-' + type);
        $input = $field.find('select,input');
        $input.attr('name', value_input_name);

        // Refresh color picker
        var cp_data = $input.data('paletteColorPickerPlugin');
        if(cp_data) cp_data.destroy();

        // Refresh checkbox list
        $block.find('.input-checklist label input').attr('name', '');

        if (type) {
            switch (type) {
                case 'string':
                    break;

                case 'integer':
                    $field.find('.input-group-addon').text($el.data('unit'));
                    break;

                case 'list':
                    var options = $el.data('list');

                    $input.empty();
                    $input.append($('<option/>').text('-- Не выбрано --'));

                    for (var n in options) {
                        var tag_options = { value: options[n] };

                        if($input.data('val') == options[n])
                            tag_options['selected'] = 'selected';

                        $input.append($('<option/>', tag_options).text(options[n]));
                    }
                    break;

                case 'color':
                    $input.paletteColorPicker({
                        colors: ["#5B0F00","#660000","#783F04","#7F6000","#274E13","#0C343D","#1C4587","#073763","#20124D","#4C1130",
                            "#5B0F00","#660000","#783F04","#7F6000","#274E13","#0C343D","#1C4587","#073763","#20124D","#4C1130",
                            "#85200C","#990000","#B45F06","#BF9000","#38761D","#134F5C","#1155CC","#0B5394","#351C75","#741B47",
                            "#A61C00","#CC0000","#E69138","#F1C232","#6AA84F","#45818E","#3C78D8","#3D85C6","#674EA7","#A64D79",
                            "#CC4125","#E06666","#F6B26B","#FFD966","#93C47D","#76A5AF","#6D9EEB","#6FA8DC","#8E7CC3","#C27BA0",
                            "#DD7E6B","#EA9999","#F9CB9C","#FFE599","#B6D7A8","#A2C4C9","#A4C2F4","#9FC5E8","#B4A7D6","#D5A6BD",
                            "#E6B8AF","#F4CCCC","#FCE5CD","#FFF2CC","#D9EAD3","#D0E0E3","#C9DAF8","#CFE2F3","#D9D2E9","#EAD1DC",
                            "#980000","#FF0000","#FF9900","#FFFF00","#00FF00","#00FFFF","#4A86E8","#0000FF","#9900FF","#FF00FF",
                            "#000000","#222222","#444444","#666666","#888888","#AAAAAA","#CCCCCC","#DDDDDD","#EEEEEE","#FFFFFF"],
                        clear_btn: 'last',
                        timeout: 1000
                    });
                    break;

                case 'checklist':
                    var options = $el.data('list');

                    $input.attr('name', '');
                    $field.find('label').remove();

                    for(var n in options) {
                        var tag_options = {
                            type: 'checkbox',
                            name: value_input_name+'[]',
                            value: options[n],
                        };

                        if($.inArray(options[n].toString(), $input.data('val')) !== -1)
                            tag_options['checked'] = 'checked';

                        $label = $('<label/>', { class: 'sizebox' })
                            .text(options[n])
                            .prepend($('<input/>', tag_options));
                        $field.append($label);
                    }
                    break;
            }
            $field.show();
        }
    });

    if($('.dynamic-attributes .select-attribute').length) {
        $('.dynamic-attributes .select-attribute').each(function(){
            if($(this).val()) $(this).trigger('change');
        });
    }

    $('.nestable-off').change(function() {
        //if($(this).is(':checked')) {
            $('.goods-nestable').toggleClass('nestable');
            $('.nestable').data('uk-nestable', "{maxDepth:0, group:'widgets'}");
        //}
    })

    $(document).on('click', '.js-dynamic-product-add', function(e) {
        e.preventDefault();
        var $box = $(this).parents('.js-dynamic-product'),
            $container = $box.find('.js-dynamic-product-container'),
            $input = $box.find('.js-dynamic-product-input:first').clone();

        $input.find('select').attr('id', 'product-' + Math.floor(Math.random() * 100));
        $input.find('select').css('display', 'block');
        $input.find('*[id$=_chosen]').remove();
        $container.append($input);

        $input.find('.chosen-select').chosen({allow_single_deselect:true});
        $input.find('.chosen-autocomplite').each(window.initAutocomplete);
    });

    $(document).on('click', '.js-dynamic-product-remove', function(e) {
        e.preventDefault();
        $(this).parents('.js-dynamic-product-input').remove();
    });
    // Do some action by ajax
    $body.on('click', '.js-save-check', function(e) {
        var $this = $(this),
            url = $this.data('url'),
            postData = {
                'id': $this.data('id'),
                'checker':this.name
            }
        if($this.prop('checked')) {
            postData.value = 1;
        }
        $.post(url, postData, function(data) {
            // Exception
            if(typeof data.error !== 'undefined'){
                console.log(data.message);
            }
            // Do some action
            if(typeof data.action !== 'undefined'){
                var fn = window[data.action];
                if(typeof fn === 'function') {
                    fn(data);
                }
            }
        }, 'json');
    });
});
//показывает уведомление о сохранении
function saveComplete() {
    $('#saveComplete').animate({'opacity':'1'}, 100, function() {
      $(this).animate({'opacity':'0'}, 5000);
    })
}
