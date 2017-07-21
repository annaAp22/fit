<div id="registration-form" class="form-type-1 modal-box quick-order-cart" style="display: inline-block;">
    <form name="registration-form" class="js-form-ajax" action="{{ route('ajax.register') }}" method="POST">
        <div class="form-modal">
            {{ csrf_field() }}
            <input type="hidden" name="last_name" value="registration">
            <div class="form-modal_title-2"><span class="green-caption">Создать</span>, личный кабинет</div>
            <div class="form-modal_line">
                <div class="field-caption-1">Привет! Меня зовут:</div>
                <div class="field-wrapper-1">
                    <div class="icon sprite_main-form-input-person-green sprite_main"></div>
                    <input type="text" name="name" placeholder="Ф.И.О" class="js-required-fields"/>
                </div>
            </div>
            <div class="form-modal_line">
                <div class="field-caption-1">Со мной можно связать по телефону:</div>
                <div class="field-wrapper-1">
                    <div class="icon sprite_main-green-phone sprite_main"></div>
                    <input type="text" name="phone" placeholder="+7 964 561 23 45" class="js-phone js-required-fields"/>
                </div>
            </div>
            <script type="text/javascript">
                $('#registration-form .js-phone').mask("+7 000 000 00 00", {placeholder: "+7 ___ ___ __ __"});
            </script>
            <div class="form-modal_line">
                <div class="field-caption-1">Если телефон не доступен, пишите на почту:</div>
                <div class="field-wrapper-1">
                    <div class="icon sprite_main-green-letter sprite_main"></div>
                    <input type="text" name="email" placeholder="Электронный адрес" class="js-required-fields"/>
                </div>
            </div>
            <div class="form-modal_line">
                <div class="field-caption-1">У меня будет такой пароль:</div>
                <div class="field-wrapper-1">
                    <div class="icon sprite_main-green-lock sprite_main"></div>
                    <input type="password" name="password" class="js-required-fields"/>
                </div>
            </div>
            <div class="form-modal_line">
                <div class="field-caption-1">Введите пароль ещё раз:</div>
                <div class="field-wrapper-1">
                    <div class="icon sprite_main-green-lock sprite_main"></div>
                    <input type="password" name="password_confirmation" class="js-required-fields"/>
                </div>
            </div>
            <div class="form-modal_line">
                <button class="btn btn_yellow-3">Создать свой кабинет&nbsp;
                    <i class="sprite_main sprite_main-orange-arrow-2"></i>
                </button>
            </div>
        </div>
    </form>
    <button data-fancybox-close class="modal-close">&#10006;</button>
    <script type="text/javascript">
        var reg_form = document.forms['registration-form'];
        reg_form.onsubmit = function(e) {
            reg_form['last_name'].value = 444 + 222;
        };
    </script>
    @if(View::exists('users.fill_fields'))
        @include('users.fill_fields')
    @endif
</div>