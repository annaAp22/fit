@php
    $men = $categories->where('name', 'Для мужчин')->first();
    $women = $categories->where('name', 'Для женщин')->first();
@endphp

<!-- Dropdown catalog-->
<div class="catalog-dropdown">
    <div class="container">
        <!-- Women categories-->
        <div class="catalog-dropdown__item catalog-dropdown__item_women js-women js-women-desktop js-catalog">
            <!-- Categories-->
            <div class="catalog-dropdown__categories container js-dropdown-women">
                @include('widgets.catalog_categories', ['category' => $women])
            </div>

            <!-- Look banner-->
            <div class="look-dropdown">
                <div class="look-dropdown__text">
                    <div class="look-dropdown__header">
                        <img src="/img/catalog-dropdown_women__look-1-min.jpg"/>
                        <span>Твой яркий образ</span>
                    </div>
                    <div class="look-dropdown__body">
                        <span>Яркие майки и широкие кофты для удобных тренировок и яркого образа</span>
                        <a class="btn btn_orange" href="{{ route('catalog', ['sysname' => $women->sysname]) }}">Подобрать</a>
                    </div>
                </div>
                <a class="look-dropdown__image" href="{{ route('catalog', ['sysname' => $women->sysname]) }}">
                    <img src="/img/catalog-dropdown_women__look-min.jpg"/>
                </a>
            </div>
        </div>
        
        <!-- Men categories-->
        <div class="catalog-dropdown__item catalog-dropdown__item_men js-men js-men-desktop js-catalog">
            <!-- Categories-->
            <div class="catalog-dropdown__categories container js-dropdown-men">
                @include('widgets.catalog_categories', ['category' => $men])
            </div>

            <!-- Look banner-->
            <div class="look-dropdown">
                <div class="look-dropdown__text">
                    <div class="look-dropdown__header">
                        <img src="/img/catalog-dropdown_women__look-1-min.jpg"/>
                        <span>Твой яркий образ</span>
                    </div>
                    <div class="look-dropdown__body">
                        <span>Яркие майки и широкие кофты для удобных тренировок и яркого образа</span>
                        <a class="btn btn_orange" href="{{ route('catalog', ['sysname' => $men->sysname]) }}">Подобрать</a>
                    </div>
                </div>
                <a class="look-dropdown__image" href="{{ route('catalog', ['sysname' => $men->sysname]) }}">
                    <img src="/img/catalog-dropdown_women__look-min.jpg"/>
                </a>
            </div>
        </div>
    </div>
</div>
