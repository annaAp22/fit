<header>
    <div class="line line_lg"></div>
    <div class="line"></div>
    <div class="container">
        <div class="header">
            <!-- Schedule -->
            <div class="header__time">с {{ $global_settings['schedule']->value['start_workday'] }} до {{ $global_settings['schedule']->value['end_workday'] }} без выходных</div>

            <!-- Hamburger -->
            <div class="header__hamburger">
                <div class="hamburger js-toggle-sidebar" data-target=".js-nav-visible">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <!-- Site logo -->
            <a class="header__logo" href="/">
                <img src="/img/header__logo-min.png" alt="fit2u"/>
            </a>

            {{-- City choose --}}
            {{--<div class="header__city">
                <i class="sprite_main sprite_main-header__city_point"></i>
                <span>г. Екатеринбург</span>
                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
            </div>--}}

            <!-- Search Start -->
            <form class="header__search" method="POST" action="{{ route('search') }}">
                {{ csrf_field() }}
                <button class="icon-fade">
                    <i class="sprite_main sprite_main-header__search_active normal"></i>
                    <i class="sprite_main sprite_main-header__search active"></i>
                </button>
                <input type="search" name="text" placeholder="Поиск по товарам..."/>
            </form>
            <!-- Search End -->

            {{-- Phones --}}
            <div class="header__phones">
                <div>
                    <i class="sprite_main sprite_main-header__phones_whatsapp"></i>&nbsp;
                    <i class="sprite_main sprite_main-header__phones_viber"></i>
                </div>
                <div class="header__item">{!! $global_settings['phone_number']->value['free'] !!}<br/><span>Бесплатно по России</span>
                </div>
                <div class="header__item">{!! $global_settings['phone_number']->value['msk'] !!}<br/><span>с {{ $global_settings['schedule']->value['start_workday'] }} до {{ $global_settings['schedule']->value['end_workday'] }} без выходных</span>
                </div>
            </div>


            <div class="header__basket">
                {{-- WishList--}}
                <a href="{{ route('bookmarks') }}" class="js-hover-notice">

                    @if($countD = count($defer))
                    <div class="count count_wishlist">
                        <span class="js-wishlist-quantity">{{ $countD }}</span>
                    </div>
                    @endif

                    {{-- Icon--}}
                    <span class="icon-fade header-wishlist">
                        <i class="sprite_main sprite_main-header__basket_wishlist normal"></i>
                        <i class="sprite_main sprite_main-header__basket_wishlist_active active"></i>
                    </span>

                    {{-- Popup --}}
                    <span class="popup-notice popup-notice_wishlist-header">
                        <span class="popup-notice__triangle">▼</span>
                        <span class="popup-notice__text">Отложенное</span>
                    </span>
                </a>

                {{-- Seen products --}}
                <a href="{{ route('seen') }}" class="js-hover-notice">

                    @if($countS = count($seen))
                    <div class="count count_seen">
                        <span class="js-seen-quantity">{{ $countS }}</span>
                    </div>
                    @endif

                    {{-- Icon--}}
                    <span class="icon-fade seen">
                        <i class="sprite_main sprite_main-header__basket_seen normal"></i>
                        <i class="sprite_main sprite_main-header__basket_seen_active active"></i>
                    </span>

                    {{-- Poupup--}}
                    <span class="popup-notice popup-notice_wishlist-header">
                        <span class="popup-notice__triangle">▼</span>
                        <span class="popup-notice__text">Просмотренное</span>
                    </span>
                </a>

                {{-- Basket--}}
                @widget('HeaderBasket')

            </div>
            <div class="header__navigation">
                <div class="nav-catalog">
                    <div class="nav-catalog__item js-toggle-active-target js-women js-catalog" data-target=".js-women" data-reset=".js-men"><span>ДЛЯ ЖЕНЩИН</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                    </div>
                    <div class="nav-catalog__item js-toggle-active-target js-men js-catalog" data-target=".js-men" data-reset=".js-women"><span>ДЛЯ МУЖЧИН</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                    </div>
                </div>

                {{-- Pages navigation --}}
                @widget('PageNavigation')

            </div>
            <!-- Site login-->
            {{--<div class="header__enter" href="#">--}}
                {{--<i class="sprite_main sprite_main-header__enter"></i>--}}
                {{--<span>Войти / Вступить</span>--}}
                {{--<div class="dropdown">--}}
                    {{--<a href="">Войти в систему</a>--}}
                    {{--<a href="">Создать кабинет</a>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>

    @widget('CatalogWidget')
</header>