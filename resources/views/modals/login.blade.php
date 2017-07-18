<div id="login-form" class="form-type-1 modal-box quick-order-cart" style="display: inline-block;">
    <form class="js-form-ajax" action="{{ route('ajax.callback') }}" method="POST">
        <div class="form-modal">
            {{ csrf_field() }}
            <input type="hidden" name="is_multiple" value="1">

            <div class="form-modal_title-2"><span class="green-caption">Вход</span>, личный кабинет</div>
            <div class="form-modal_line">
                <div class="field-caption-1">Ваша электронная почта:</div>
                <div class="field-wrapper-1">
                    <div class="icon sprite_main-green-letter sprite_main"></div>
                    <input type="text" name="email" placeholder="Электронный адрес" class="js-required-fields"/>
                </div>
            </div>
            <div class="form-modal_line">
                <div class="field-caption-1">Пароль:</div>
                <div class="field-wrapper-1">
                    <div class="icon sprite_main-green-lock sprite_main"></div>
                    <input type="password" name="password" class="js-required-fields"/>
                </div>
            </div>
            <div class="form-modal_line">
                <label class="radio radio_box">
                    <input class="js-required-fields hidden-input-2" name="remember" value="1" type="checkbox" checked>
                    <span class="fake-input-2"><span></span></span>
                    <span class="label-2">Запомнить</span>
                </label>
            </div>
            <div class="form-modal_line">
                <button class="btn btn_green">ВОЙТИ&nbsp;
                    <i class="sprite_main sprite_main-icon-arrow-small-right-dark-green"></i>
                </button>
            </div>
            <a href="" class="some-link">Забыли пароль?</a>
        </div>
    </form>
    <button data-fancybox-close  class="modal-close">&#10006;</button>
</div>