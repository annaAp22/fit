    <ul class="mobile-sidebar__catalog hidden-lg-up">
        <li class="mobile-sidebar__catalog-item js-toggle-active">
            <a href="#">Информация</a>
            <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
            <div class="mobile-sidebar__level-2">
                <div class="mobile-sidebar__title">
                    <div class="mobile-sidebar__catalog-subitem">
                        <a href="#">Информация</a>
                        <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                    </div>
                </div>
                <div class="catalog-dropdown__categories container">
                    <div class="catalog-dropdown__column">
                        <ul class="ul ul_green-hover">
                            @include('blocks.info-additional')
                            @foreach($info as $page)
                                <li><a href="{{ route('page', ['sysname' => $page->sysname]) }}">{{ $page->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </li>
    </ul>