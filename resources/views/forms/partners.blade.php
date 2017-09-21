@if(!isset($user) || !$user->partner)
<form name="registration-form" action="{{route('ajax.register')}}" class="questions-and-offers js-form-ajax" method="POST">
    <input name="last_name" value="registration" type="hidden">
    <input type="hidden" name="partner" value='1'/>
    <fieldset class="fieldset">
        <legend>Стать партнером</legend>
        <div class="left-side">
            <div class="field-caption-1">Ваше имя</div>
            <div class="field-wrapper-1">
                <div class="icon sprite_main-form-input-person-green sprite_main"></div>
                <input name="name" placeholder="Ф.И.О" class="js-required-fields" type="text">
            </div>            <div class="field-caption-1">Ваша электронная почта:</div>
            <div class="field-wrapper-1">
                <div class="icon sprite_main-green-letter sprite_main"></div>
                <input type="text" name="email" placeholder="template@gmail.com" class="js-required-fields"/>
            </div>
            <div class="field-caption-1">Ваш телефон:</div>
            <div class="field-wrapper-1">
                <div class="icon sprite_main-green-phone sprite_main"></div>
                <input type="text" name="phone" placeholder="+7 964 561 23 45" class="js-phone js-required-fields"/>
            </div>
        </div>
        <div class="right-side">
            <div class="field-caption-1">У меня будет такой пароль:</div>
            <div class="field-wrapper-1">
                <div class="icon sprite_main-green-lock sprite_main"></div>
                <input name="password" class="js-required-fields" type="password">
            </div>
            <div class="field-caption-1">Введите пароль ещё раз:</div>
            <div class="field-wrapper-1">
                <div class="icon sprite_main-green-lock sprite_main"></div>
                <input name="password_confirmation" class="js-required-fields" type="password">
            </div>
            <div class="field-wrapper-2">
                <button type="submit" class="btn btn_green">Отправить</button>
            </div>
        </div>
        <script type="text/javascript">
            $('.js-phone').mask("+7 000 000 00 00", {placeholder: "+7 ___ ___ __ __"});
        </script>
        <div class="clearfix"></div>
    </fieldset>
</form>
@include('scripts.registration_protection')
@endif