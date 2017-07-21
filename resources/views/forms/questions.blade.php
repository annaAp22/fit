<form action="{{route('ajax.questions')}}" class="questions-and-offers js-form-ajax" method="POST">
    <fieldset class="fieldset">
        <legend>Вопросы и предложения</legend>
        <div class="left-side">
            <div class="field-caption-1">Ваша электронная почта:</div>
            <div class="field-wrapper-1">
                <div class="icon sprite_main-green-letter sprite_main"></div>
                <input type="text" name="email" placeholder="Coolgirl_yamakasy@gmail.com" class="js-required-fields"/>
            </div>
        </div>
        <div class="right-side">
            <div class="field-caption-1">Ваш телефон:</div>
            <div class="field-wrapper-1">
                <div class="icon sprite_main-green-phone sprite_main"></div>
                <input type="text" name="phone" placeholder="+7 964 561 23 45" class="js-phone js-required-fields"/>
            </div>
        </div>
        <script type="text/javascript">
            $('.js-phone').mask("+7 000 000 00 00", {placeholder: "+7 ___ ___ __ __"});
        </script>
        <div class="clearfix"></div>
        <div class="field-caption-1">Ваш вопрос:</div>
        <div class="field-wrapper-1">
            <div class="icon-top sprite_main-green-pen sprite_main"></div>
            <textarea name="text" placeholder="Напишите свой вопрос" class="js-required-fields"></textarea>
        </div>
        <button type="submit" class="btn btn_green">Отправить</button>
    </fieldset>
</form>