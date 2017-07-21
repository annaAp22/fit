<h1>Спонсорство</h1>
<div class="sponsor">
    <div class="spec-title-green">Мы сотрудничаем:</div>
    <div class="sponsor__block-1">
        <div class="wrapper">
            <div class="spec-num">1.</div>
            <div class="spec-caption-1">Занимаетесь фитнесом? Бодибилдингом?</div>
            <div class="sponsor__text">Мы сотрудничаем с  НЕпрофессионалами, добившимися значительного прогресса с "нуля" в построении тела, фитнесе, танцах и т.д.</div>
            <button class="btn btn_silver-border-900 js-action-link" data-modal="order" data-url="{{route('ajax.modal')}}">ПОДАТЬ ЗАЯВКУ</button>
        </div>
    </div>
    <div class="sponsor__block-2">
        <div class="wrapper">
            <div class="spec-num">2.</div>
            <div class="spec-caption-1">Вы тренер фитнес клуба?</div>
            <div class="sponsor__text">Рады сотрудничать с тренерами фитнес клубов</div>
            <button class="btn btn_sky-border-900 js-action-link" data-modal="order" data-url="{{route('ajax.modal')}}">ПОДАТЬ ЗАЯВКУ</button>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="sponsor__block-3">
        <div class="wrapper">
            <div class="spec-num">3.</div>
            <div class="spec-caption-1">Школы танцев</div>
            <div class="sponsor__text">Cотрудничаем с  школами танцев и полдэнс</div>
            <button class="btn btn_silver-border-900 js-action-link" data-modal="order" data-url="{{route('ajax.modal')}}">ПОДАТЬ ЗАЯВКУ</button>
        </div>
    </div>
    <div class="sponsor__block-4">
        <div class="spec-title-red">Мы не спонсируем:</div>
        <div>- Конкурсы красоты;</div>
        <div>- Спортивные мероприятия;</div>
        <div>- Профессиональных спортсменов.</div>
    </div>
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
</div>
