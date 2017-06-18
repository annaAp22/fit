<div id="quick-order" class="modal-box quick-order-cart" style="display: inline-block;">
    <form class="js-form-ajax" action="{{ route('ajax.order.fast') }}" method="POST">
        <div class="form-modal">
            {{ csrf_field() }}
            <input type="hidden" name="is_multiple" value="1">

            <div class="form-modal_title">БЫСТРЫЙ ЗАКАЗ</div>
            <div class="form-modal_line">
                <label>Ваше имя: <span class="mod-col-or">*</span></label>
                <input class="js-required-fields input input_text" type="text" name="name" placeholder="Фамилия, имя, отчество *" >
            </div>
            <div class="form-modal_line">
                <label>Ваш email: <span class="mod-col-or">*</span></label>
                <input class="js-required-fields input input_text" type="text" name="email" placeholder="sample@example.com" >
            </div>
            <div class="form-modal_line">
                <label>Ваш телефон: <span class="mod-col-or">*</span></label>
                <input class="phone-input js-required-fields js-phone input input_text" type="text" name="phone" placeholder="+7 (xxx) xxx xx xx * " >
                <script type="text/javascript">
                    $('.js-phone').mask("+7 000 000 00 000", {placeholder: "+7 ___ ___ __ __"});
                </script>
            </div>
            <div class="form-modal_line">
                <button class="btn btn_green">ОТПРАВИТЬ</button>
            </div>
        </div>
    </form>
    <button data-fancybox-close  class="modal-close">&#10006;</button>
</div>