<div class="header__navigation">
    <div class="nav-catalog">
        @if($categories->contains('name', 'Для женщин'))
        <div class="nav-catalog__item js-toggle-active-target js-women js-catalog" data-target=".js-women" data-reset=".js-men, .js-training"><span>ДЛЯ ЖЕНЩИН</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
        </div>
        @endif
        @if($categories->contains('name', 'Для мужчин'))
        <div class="nav-catalog__item js-toggle-active-target js-men js-catalog" data-target=".js-men" data-reset=".js-women, .js-training"><span>ДЛЯ МУЖЧИН</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
        </div>
        @endif
        @if($categories->contains('name', 'Для тренировок'))
        <div class="nav-catalog__item nav-catalog__item_wide js-toggle-active-target js-training js-catalog" data-target=".js-training" data-reset=".js-women, .js-men"><span>ДЛЯ ТРЕНИРОВОК</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
        </div>
        @endif
    </div>
    {{-- Pages navigation --}}
    @widget('PageNavigation')

</div>
