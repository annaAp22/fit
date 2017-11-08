@extends('layouts.main')
@section('breadcrumbs')
    {!!  Breadcrumbs::render('cooperation') !!}
@stop
@section('content')
<!-- верхняя часть страницы с формой и негритянкой -->
<div class="container">
    <div class="header-listing">
        <h1>Оптовикам</h1>
    </div>
    <div class="cooperation__header">
        <form name="cooperation_form_1" action="{{route('ajax.cooperation')}}" class="cooperation-form js-form-ajax" method="post">
            <div class="caption">
                <span class="square"><span>Пр</span></span>одавать Profit вместе – путь<br>к успеху вашей компании!
            </div>
            <div class="text">
                Оставьте заявку и сотрудничайте напрямую с брендом Profit.
            </div>
            <input type="hidden" name="type" value="">
            <input type="text" name="name" placeholder="Ваше имя" class="js-required-fields">
            <input type="text" name="phone" placeholder="Ваш номер телефона" class="js-required-fields">
            <input type="text" name="email" placeholder="E-mail" class="js-required-fields">
            <button class="btn btn_orange-border" type="submit">Узнать условия</button>
        </form>
        <div class="right">
            <div class="cooperation__phone">+7(916)707-95-80</div>
            <div class="cooperation__work-time">Работаем по будням с 9:00 до 18:00</div>
        </div>
    </div>
</div>
<!-- какая-то краткая информация -->
<div class="container">
    <div class="cooperation__info">
        <div class="item">
            <div class="icon-wrapper">
                <div class="icon sprite_cooperation sprite_cooperation-wagon"></div>
            </div>
            <div class="text">Весь товар в наличие на складе</div>
        </div>
        <div class="item">
            <div class="icon-wrapper">
                <div class="icon sprite_cooperation sprite_cooperation-money_flower"></div>
            </div>
            <div class="text">Розничная наценка <b>66,7%</b></div>
        </div>
        <div class="item">
            <div class="icon-wrapper">
                <div class="icon sprite_cooperation sprite_cooperation-rocket"></div>
            </div>
            <div class="text">Товар будет у Вас уже через 2 дня</div>
        </div>
    </div>
</div>
<!-- что-то похожее на преимущества -->
<div class="container cooperation__unknown-block-1">
    <div class="img"></div>
    <div class="items">
        @for($i =0; $i < 4; $i++)
            <div class="caption">Гарантия качества</div>
            <div class="text">Мы имеем достаточный опыт в производстве,чтобы заявить о ни с чем не сравнимом качестве пошива одежды.</div>
        @endfor
    </div>
</div>
<!-- Кому выгодно с нами сотрудничать? -->
<div class="cooperation__who-benefits">
    <div class="cooperation__caption">Кому выгодно с нами сотрудничать? </div>
    <div class="container">
        <div class="items between">
            @for($i =0; $i < 3; $i++)
                <div class="item in-row-3">
                    <div class="img">
                        <div class="icon sprite_cooperation sprite_cooperation-tie"></div>
                    </div>
                    <div class="caption">Владельцам фитнес клубов</div>
                    <div class="text">Отличная доп.продажа для любого клуба- яркая одежда для тренировок. Ваши клиенты будут очень довольны!</div>
                </div>
            @endfor
        </div>
    </div>
</div><!-- преимущества -->
<div class="cooperation__pros container">
    <div class="cooperation__pros-img">
        <img  src="/img/cooperaion_women.png" alt="">
    </div>
    <div class="right-wrapper">
        <div class="wrapper">
            <div class="cooperation__pros-caption">НАШИ ПРЕИМУЩЕСТВА</div>
            <div class="items container-in">
                @for($i =0; $i < 6; $i++)
                    <div class="item">
                        <div class="img">
                            <div class="icon sprite_cooperation sprite_cooperation-pros_equipment"></div>
                        </div>
                        <div class="caption">Собственное производство</div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>
<!--как начать-->
<div class="cooperation__who-benefits">
    <div class="cooperation__caption">Как начать сотрудничать с нами?</div>
    <div class="container">
        <div class="items">
            @for($i =0; $i < 4; $i++)
                <div class="item in-row-4">
                    <div class="img">
                        <div class="icon sprite_cooperation sprite_cooperation-request"></div>
                    </div>
                    <div class="caption">Оставить заявку</div>
                    <div class="text">Оставьте Вашу заявку на сайте.Менеджер свяжется с Вами в ближайшее время.</div>
                </div>
            @endfor
        </div>
    </div>
</div>
<!-- мы работаем по всему миру -->
<div class="container">
    <div class="cooperation__world">
        <div class="cooperation__caption">Мы работаем по всему миру</div>
        <img src="/img/world_map.jpg" alt="">
    </div>
</div>
<!-- нижняя форма сотрудничества -->
<div class="cooperation__footer">
    <form name="cooperation_form_2" action="{{route('ajax.cooperation')}}" class="cooperation-form-wide js-form-ajax" method="post">
        <div class="caption">
            Оставьте заявку
        </div>
        <div class="text">и начните сотрудничать с брендом Profit</div>
        <div class="wrapper">
            <input type="hidden" name="type" value="">
            <input type="text" name="name" placeholder="Ваше имя" class="js-required-fields">
            <input type="text" name="phone" placeholder="Ваш номер телефона" class="js-required-fields">
            <input type="text" name="email" placeholder="E-mail" class="js-required-fields">
        </div>
        <button class="btn btn_sky-border-900">Узнать условия</button>
    </form>
</div>
@include('scripts.form_protection')
@endsection