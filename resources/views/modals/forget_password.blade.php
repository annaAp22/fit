<div id="forget-password-form" class="form-type-1 modal-box quick-order-cart" style="display: inline-block;">
    <form class="js-form-ajax" action="{{ route('password.email') }}" method="POST">
        <div class="form-modal">
            {{ csrf_field() }}
            <div class="form-modal_title-2">Восстановление пароля </div>
            <div class="form-modal_line">
                <div class="field-caption-1">Укажите Ваш адрес электронной почты, и мы вышлем Ваш пароль в течение нескольких минут:</div>
                <div class="field-wrapper-1">
                    <div class="icon sprite_main-green-letter sprite_main"></div>
                    <input type="text" name="email" placeholder="Электронный адрес" class="js-required-fields"/>
                </div>
            </div>
            <div class="form-modal_line">
                <button class="btn btn_green">Отправить пароль&nbsp;
                    <i class="sprite_main sprite_main-icon-arrow-small-right-dark-green"></i>
                </button>
            </div>
        </div>
    </form>
    <button data-fancybox-close  class="modal-close">&#10006;</button>
    @if(View::exists('users.fill_fields'))
        @include('users.fill_fields')
    @endif
</div>