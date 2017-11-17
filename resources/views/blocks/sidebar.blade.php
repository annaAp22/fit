<div class="mobile-sidebar js-nav-visible" id="sidebar-navigation">
  <div class="mobile-sidebar__title">
      <div class="name">Каталог</div>
  </div>
  <ul class="mobile-sidebar__catalog">
    <li class="mobile-sidebar__catalog-item js-toggle-active js-level1-women">
        <a href="#">Для женщин</a>
        <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
      <div class="mobile-sidebar__level-2 js-women-mobile">
        <div class="mobile-sidebar__title">
          <div class="mobile-sidebar__catalog-subitem">
              <a href="#">Для женщин</a>
              <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
          </div>
        </div>
      </div>
    </li>
    <li class="mobile-sidebar__catalog-item js-toggle-active js-level1-men"><a href="#">Для мужчин</a><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
      <div class="mobile-sidebar__level-2 js-men-mobile">
        <div class="mobile-sidebar__title">
            <div class="mobile-sidebar__catalog-subitem">
                <a href="#">Для мужчин</a>
                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
            </div>
        </div>
      </div>
    </li>
    <li class="mobile-sidebar__catalog-item js-toggle-active js-level1-training"><a href="#">Тип тренировок</a><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
      <div class="mobile-sidebar__level-2 js-training-mobile">
        <div class="mobile-sidebar__title">
            <div class="mobile-sidebar__catalog-subitem">
                <a href="#">Тип тренировок</a>
                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
            </div>
        </div>
      </div>
    </li>
  </ul>
  <div class="mobile-sidebar__title js-pages-mobile">
      <div class="name">Навигация</div>
  </div>
  {{-- City choose --}}
  <div class="mobile-sidebar__title">
      <div class="name">Ваш город</div>
  </div>
    <div class="sidebar-geo js-toggle-active">
        <div class="sidebar-geo__city">
            <i class="sprite_main sprite_main-header__city_point"></i>
            <span>{{ $user_city }}</span>
            <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
        </div>

        <div class="mobile-sidebar__level-2 sidebar-geo__level-2 js-geo-mobile">
            <div class="mobile-sidebar__title">
                <div class="mobile-sidebar__catalog-subitem">
                    <a href="#">Город</a>
                    <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                </div>
            </div>

            <form class="form-search geo-city__search js-prevent" method="POST">
                {{ csrf_field() }}
                <button class="icon-fade" type="submit">
                    <i class="sprite_main sprite_main-header__search_active normal"></i>
                    <i class="sprite_main sprite_main-header__search active"></i>
                </button>
                <input class="js-geo-city-search geo-city-search" type="search" name="text" placeholder="Найти город..."/>
            </form>

        </div>
    </div>
</div>
<div class="mobile-sidebar js-filter-visible" id="sidebar-filters">
</div>