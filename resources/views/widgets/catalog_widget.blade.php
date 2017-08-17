@php
    $men = $categories->where('name', 'Для мужчин')->first();
    $women = $categories->where('name', 'Для женщин')->first();
    $training = $categories->where('name', 'Тип тренировок')->first();
@endphp

<!-- Dropdown catalog-->
<div class="catalog-dropdown">
    <div class="container">
        <!-- Women categories-->
        <div class="catalog-dropdown__item catalog-dropdown__item_women js-women js-women-desktop js-catalog">
            <!-- Categories-->
            <div class="catalog-dropdown__categories container js-dropdown-women">
                @include('widgets.catalog_categories', ['category' => $women, 'unique_offer' => true])
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
        
        <!-- Men categories!-->
        <div class="catalog-dropdown__item catalog-dropdown__item_men js-men js-men-desktop js-catalog">
            <!-- Categories-->
            <div class="catalog-dropdown__categories container js-dropdown-men">
                @include('widgets.catalog_categories', ['category' => $men, 'unique_offer' => true])
            </div>

            <!-- Look banner-->
            <div class="look-dropdown">
                <div class="look-dropdown__text-gray">
                    <div class="look-dropdown__header-top">
                        <img src="/img/catalog-dropdown_men__look-1-min.png"/>
                        <span>СОЗДАЙ СЕБЯ САМ</span>
                    </div>
                    <div class="look-dropdown__body-white">
                        <span>Завершенный спорт-образ: спорт майки, шорты, штаны, рашгарды, спорт лосины </span>
                        <a class="btn btn_orange" href="{{ route('catalog', ['sysname' => $men->sysname]) }}">Подобрать</a>
                    </div>
                </div>
                <a class="look-dropdown__image" href="{{ route('catalog', ['sysname' => $men->sysname]) }}">
                    <img src="/img/catalog-dropdown_men__look-min.jpg"/>
                </a>
            </div>
        </div>

        <!-- Training categories!-->
        <div class="catalog-dropdown__item catalog-dropdown__item_training js-training js-training-desktop js-catalog">
            <!-- Categories-->
            <div class="catalog-dropdown__categories container js-dropdown-training">
                @include('widgets.catalog_categories', ['category' => $training, 'chunk' => 8])
            </div>

            <!-- Look banner-->
            {{--<div class="look-dropdown">--}}
                {{--<div class="look-dropdown__text">--}}
                    {{--<div class="look-dropdown__header">--}}
                        {{--<img src="/img/catalog-dropdown_women__look-1-min.jpg"/>--}}
                        {{--<span>Твой яркий образ</span>--}}
                    {{--</div>--}}
                    {{--<div class="look-dropdown__body">--}}
                        {{--<span>Яркие майки и широкие кофты для удобных тренировок и яркого образа</span>--}}
                        {{--<a class="btn btn_orange" href="{{ route('catalog', ['sysname' => $women->sysname]) }}">Подобрать</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<a class="look-dropdown__image" href="{{ route('catalog', ['sysname' => $women->sysname]) }}">--}}
                    {{--<img src="/img/catalog-dropdown_women__look-min.jpg"/>--}}
                {{--</a>--}}
            {{--</div>--}}
        </div>
    </div>
</div>
