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
    {{-- cooperations --}}
    <a class="nav-pages__item" href="{{ route('cooperation') }}"><span>Опт</span></a>
    {{-- Info pages - pages with type = info --}}
    <div class="nav-pages__item js-toggle-active" data-reset=".js-catalog">
        <span>Информация</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>

        <ul class="nav-pages__dropdown">
            {{-- MD down visible --}}
            <li><button class="btn btn_more"><i class="sprite_main sprite_main-icon__arrow_to_top"></i><span>Вернуться назад</span></button></li>
            <li class="mobile-sidebar__title">Информация</li>
            @include('blocks.info-additional')
            {{-- Static pages --}}
            @foreach($info as $page)
                <li><a href="{{ route('page', ['sysname' => $page->sysname]) }}">{{ $page->name }}</a></li>
            @endforeach

        </ul>
    </div>
</div>