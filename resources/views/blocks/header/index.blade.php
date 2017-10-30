<header>
    <div class="line line_lg"></div>
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
            <div class="header__city">
                <span class="js-toggle-active-target" data-target=".js-geo-city-widget">
                    <i class="sprite_main sprite_main-header__city_point"></i>
                    <span>{{ $user_city }}</span>
                    <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                </span>

                {{-- Choose city popup --}}
                @widget('SelectCity')
            </div>

            <!-- Search Start -->
            <form id="search" class="header__search form-search" method="POST" action="{{ route('search') }}">
                {{ csrf_field() }}
                <button class="icon-fade" type="submit">
                    <i class="sprite_main sprite_main-header__search_active normal"></i>
                    <i class="sprite_main sprite_main-header__search active"></i>
                </button>
                <input type="search" name="text" placeholder="Поиск по товарам..."/>
            </form>
            <!-- Search End -->

            {{-- Phones --}}
            <div class="header__phones">
                <div>
                    <i>
                        <a target="_blank" href="https://mssg.me/fit2u" rel="nofollow" class="messenger">
                            <div class="sprite_main sprite_main-social_32_whatsapp"></div>
                            <div class="sprite_main sprite_main-social_32_viber"></div>
                            <div class="sprite_main sprite_main-social_32_vk"></div>
                            <div class="sprite_main sprite_main-social_32_telegram"></div>
                        </a>
                    </i>
                    &nbsp;
                </div>

                <div class="header__item">
                    <span>с {{ $global_settings['schedule']->value['start_workday'] }} до {{ $global_settings['schedule']->value['end_workday'] }}
                        <br/>без выходных</span>
                </div>
                <div class="header__item">{!! $global_settings['phone_number']->value['free'] !!}<br/><span>Бесплатно по России</span>
                </div>
            </div>


            <div class="header__basket">
                {{-- WishList--}}
                <a href="{{ route('bookmarks') }}" class="js-hover-notice">


                    <div class="count count_wishlist @if($countD = count($defer)){{'active'}}@endif">
                        <span class="js-wishlist-quantity">{{ $countD }}</span>
                    </div>


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
            <div class="hr-1">
                <div></div>
            </div>
            @widget('CatalogWidget', ['type' => 'headerNavigation'])
            <!-- Site login-->
            <div class="header__enter js-toggle-active" href="#">
                <i class="sprite_main sprite_main-header__enter"></i>
                <span class="js-user-name">{{Auth::check()?Auth::user()->name:'Войти / Вступить'}}</span>
                <div class="dropdown">
                    <div id="js-not-autorized" @if(Auth::check()) hidden @endif>
                        <a id="js-user-login" class= "js-action-link" data-modal="login" data-url="{{route('ajax.modal')}}">Войти в систему</a>
                        <a id="js-user-register" class="js-action-link" data-modal="registration" data-url="{{route('ajax.modal')}}">Создать кабинет</a>
                    </div>
                    <div id="js-autorized"  @if(!Auth::check()) hidden @endif>
                            <a href="{{route('room')}}">Мои данные</a>
                            <a href="{{route('orders-history')}}">Мои заказы</a>
                            @if(isset($user) && $user->partner)
                            <a href="{{route('orders-history', ['referrals' => 1])}}">Заказы рефералов</a>
                            @endif
                        <a  class="js-action-link" data-url="{{ route('ajax.logout') }}">Выйти</a>
                    </div>
                </div>
            </div>
            <div class="vl"></div>
        </div>
    </div>

    @widget('CatalogWidget')
</header>