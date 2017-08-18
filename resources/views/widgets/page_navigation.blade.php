<div class="nav-pages js-pages">
    {{-- Delivery --}}
    <a class="nav-pages__item" href="{{ route('delivery') }}"><span>Доставка и оплата</span></a>
    {{-- Articles --}}
    {{--<a class="nav-pages__item" href="{{ route('articles') }}"><span>Статьи</span></a>--}}
    {{-- Reviews --}}
    {{--<a class="nav-pages__item" href="{{ route('reviews') }}"><span>Отзывы</span></a>--}}

    @if($help->count())
        {{-- Help pages --}}
        <div class="nav-pages__item js-toggle-active" data-reset=".js-catalog"><span>Помощь</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
            <ul class="nav-pages__dropdown">
                {{-- MD down visible --}}
                <li><button class="btn btn_more"><i class="sprite_main sprite_main-icon__arrow_to_top"></i><span>Вернуться назад</span></button></li>
                <li class="mobile-sidebar__title">Помощь</li>

                @foreach($help as $page)
                    <li><a href="{{ route('page', ['sysname' => $page->sysname]) }}">{{ $page->name }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Contacts --}}
    <a class="nav-pages__item" href="{{ route('contacts') }}"><span>Контакты</span></a>

    {{-- Info pages --}}
    <div class="nav-pages__item js-toggle-active" data-reset=".js-catalog">
        <span>Информация</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>

        <ul class="nav-pages__dropdown">
            {{-- MD down visible --}}
            <li><button class="btn btn_more"><i class="sprite_main sprite_main-icon__arrow_to_top"></i><span>Вернуться назад</span></button></li>
            <li class="mobile-sidebar__title">Информация</li>

            {{-- Non static pages --}}
            {{--<li><a href="#">Отзывы клиентов</a></li>
            <li><a href="#">Наши клиенты</a></li>
            <li><a href="#">Рецепты ПП</a></li>
            <li><a href="#">Новости</a></li>
            <li><a href="#">База знаний</a></li>
            <li><a href="#">Наши представители</a></li>
            <li><a href="#">Магазин в Москве</a></li>
            <li><a href="#">Фото клиентов</a></li>
            <li><a href="#">Подарочный сертификат</a></li>
            <li><a href="#">Карта сайта</a></li>
            <li><a href="#">Спонсор</a></li>--}}
            @include('blocks.info-additional')
            {{-- Static pages --}}
            @foreach($info as $page)
                <li><a href="{{ route('page', ['sysname' => $page->sysname]) }}">{{ $page->name }}</a></li>
            @endforeach

        </ul>
    </div>
</div>