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
            <div class="caption">Гарантия качества</div>
            <div class="text">Мы имеем достаточный опыт в производстве,чтобы заявить о ни с чем не сравнимом качестве пошива одежды.</div>
        <div class="caption">Функциональность</div>
        <div class="text">“Вечная одежда”!Не стягивается,не скатывается,не теряет эластичности!Держит те формы,которые ни одна другая одежда не удержит.</div>
        <div class="caption">Доступная роскошь</div>
        <div class="text">Profit легкодоступен абсолютно для всех слоев народонаселения!При высокой маржинальности!</div>
        <div class="caption">Московский  производственный комплекс</div>
        <div class="text">В заключении добавлю, серпантинная волна представляет собой серийный ревер. Гармоническое микророндо, в первом приближении, сложно. Фаза заканчивает дискретный open-air.</div>
    </div>
</div>
<!-- Кому выгодно с нами сотрудничать? -->
<div class="cooperation__who-benefits">
    <div class="cooperation__caption">Кому выгодно с нами сотрудничать? </div>
    <div class="container">
        <div class="items between">
                <div class="item in-row-3">
                    <div class="img">
                        <div class="icon sprite_cooperation sprite_cooperation-tie"></div>
                    </div>
                    <div class="caption">Владельцам фитнес клубов</div>
                    <div class="text">Отличная доп.продажа для любого клуба- яркая одежда для тренировок. Ваши клиенты будут очень довольны!</div>
                </div>
            <div class="item in-row-3">
                <div class="img">
                    <div class="icon sprite_cooperation sprite_cooperation-fitness"></div>
                </div>
                <div class="caption">Фитнес
                    тренерам</div>
                <div class="text">У Вас возможность поднять мотивацию к тренировкам для своих учеников и при этом заработать. Мы предлагаем партнерскую программу для таких случаев.</div>
            </div>
            <div class="item in-row-3">
                <div class="img">
                    <div class="icon sprite_cooperation sprite_cooperation-sportpit"></div>
                </div>
                <div class="caption">Магазина<br/> спортивного питания</div>
                <div class="text">Если в вашем магазине до сих пор нет одежды для фитнеса, значит Вы
                    теряете клиентов! Мы поможем это исправить.</div>
            </div>
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
                <div class="item">
                    <div class="img">
                        <div class="icon sprite_cooperation sprite_cooperation-pros_industry"></div>
                    </div>
                    <div class="caption">Собственное производство</div>
                </div>
                <div class="item">
                    <div class="img">
                        <div class="icon sprite_cooperation sprite_cooperation-pros_fabric"></div>
                    </div>
                    <div class="caption">Лучшие спортивные ткани<br/>из Италии, США и Кореи</div>
                </div>
                <div class="item">
                    <div class="img">
                        <div class="icon sprite_cooperation sprite_cooperation-pros_quality"></div>
                    </div>
                    <div class="caption">100% контроль качества</div>
                </div>
                <div class="item">
                    <div class="img">
                        <div class="icon sprite_cooperation sprite_cooperation-pros_promote"></div>
                    </div>
                    <div class="caption">Помощь в продивжении вашего магазина через наш сайт или наши соц.сети</div>
                </div>
                <div class="item">
                    <div class="img">
                        <div class="icon sprite_cooperation sprite_cooperation-pros_rocket"></div>
                    </div>
                    <div class="caption">Отправка любой удобной транспортной компанией в течение 1-2 дней.</div>
                </div>
                <div class="item">
                    <div class="img">
                        <div class="icon sprite_cooperation sprite_cooperation-pros_equipment"></div>
                    </div>
                    <div class="caption">Самое современное японское оборудование</div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--как начать-->
<div class="cooperation__who-benefits">
    <div class="cooperation__caption">Как начать сотрудничать с нами?</div>
    <div class="container">
        <div class="items">
            <div class="item in-row-4">
                <div class="img">
                    <div class="icon sprite_cooperation sprite_cooperation-request"></div>
                </div>
                <div class="caption">Оставить заявку</div>
                <div class="text">Оставьте Вашу заявку на сайте.Менеджер свяжется с Вами в ближайшее время.</div>
            </div>
            <div class="item in-row-4">
                <div class="img">
                    <div class="icon sprite_cooperation sprite_cooperation-order"></div>
                </div>
                <div class="caption">Заказ</div>
                <div class="text">Личные менеджер подскажет самые горячие позиции и новинки.</div>
            </div>
            <div class="item in-row-4">
                <div class="img">
                    <div class="icon sprite_cooperation sprite_cooperation-plane"></div>
                </div>
                <div class="caption">Доставка</div>
                <div class="text">Доставка любой удобной транспортной компанией на следующий день после оплаты.</div>
            </div>
            <div class="item in-row-4">
                <div class="img">
                    <div class="icon sprite_cooperation sprite_cooperation-complete"></div>
                </div>
                <div class="caption">Готово</div>
                <div class="text">Вы официальный представитель бренда Profit.</div>
            </div>

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