@extends('layouts.main')

@section('breadcrumbs')
    @php
        $root = null;
        if(isset($category)) $root = $category;
        if(isset($brand)) $root = $brand;
        if(isset($tag)) $root = $tag;
    @endphp
    @if(isset($page))
        {!!  Breadcrumbs::render('new_hit_act', $page) !!}
    @else
	    {!!  Breadcrumbs::render('catalog', $root) !!}
    @endif
@endsection

@section('content')
    <main>
        <div class="container">
            <aside class="sidebar">

                @php
                    $filtersParams = [
                        'paginator' => $products,
                        'category'  => isset($category) ? $category : null,
                        'brand'     => isset($brand) ? $brand : null,
                        'tag'       => isset($tag) ? $tag : null,
                        'minPrice'  => $products->min_price ?: 0,
                        'maxPrice'  => $products->max_price ?: 0,
                    ];
                    if(isset($category))
                        $filtersParams['filters'] = $category->filters;
                    if(isset($tag))
                        $filtersParams['filters'] = $tag->filters;

                @endphp

                @if($products->total() > 1)
                    @include('catalog.filters', $filtersParams)
                @endif

                {{-- Catalog navigation --}}
                @if(isset($category))
                    @widget('ListingCatalog', ['current' => $category, 'parent_id' => $parent_zero_id])
                @else
                    @widget('ListingCatalog')
                @endif

                @widget('TagsWidget')
                @widget('BannerLeftWidget')
            </aside>
            <section class="content">
                <div class="container-in">
                    <div class="header-listing">
                        @if(isset($category))
                            <h1>{{ $category->name }}</h1>
                        @elseif(isset($tag))
                            <h1>{{ $tag->name }}</h1>
                        @elseif(isset($page))
                            <h1>{{ $page->name }}</h1>
                        @endif
                        <div class="goods-count">
                            <span>Товаров в категории</span>
                            <i class="sprite_main sprite_main-icon__goods_count">{{ $products->total() }}</i>
                        </div>
                    </div>

                    <!-- Look banner-->
                {{-- <a class="banner-look" href="#">
                     <img class="banner-look__image banner-look__image_xl" src="/img/listing_look_banner-min.jpg" alt="" role="presentation"/><img class="banner-look__image banner-look__image_md" src="/img/listing_look_banner-md-min.jpg" alt="" role="presentation"/>
                 </a>--}}
                    <!-- Show filters button md down-->
                    <button class="btn btn_filter js-toggle-sidebar" data-target=".js-filter-visible">Фильтры подбора товаров</button>
                    <!-- Sorting and view-->
                    <div class="goods-sorting"><i class="sprite_main sprite_main-listing__filter"></i><span>Сортировать товары:</span>
                        <div class="sorting-select js-toggle-active js-select"><span class="js-selected">По умолчанию</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            <div class="sorting-select__dropdown">
                                <div class="sorting-select__option js-option js-sort" data-sort="sort">По умолчанию</div>
                                <div class="sorting-select__option js-option js-sort" data-sort="expensive">Сначала дороже</div>
                                <div class="sorting-select__option js-option js-sort" data-sort="cheaper">Сначала дешевле</div>
                                <div class="sorting-select__option js-option js-sort" data-sort="hit">По популярности</div>
                                <div class="sorting-select__option js-option js-sort" data-sort="act">По акциям</div>
                                <div class="sorting-select__option js-option js-sort" data-sort="new">По новинкам</div>
                            </div>
                        </div>
                    </div>

                    <!-- Change goods view-->
                    <div class="goods-view"><span>Отображать товары:</span>
                        <div class="icon-fade active js-toggle-active-target" data-target=".js-view" data-switch="s1"><i class="sprite_main sprite_main-listing__switch_tile normal"></i><i class="sprite_main sprite_main-listing__switch_tile_active active"></i>
                        </div>
                        <div class="icon-fade js-toggle-active-target" data-target=".js-view" data-switch="s1"><i class="sprite_main sprite_main-listing__switch_tile-wide normal"></i><i class="sprite_main sprite_main-listing__switch_tile-wide_active active"></i>
                        </div>
                    </div>

                    <!-- Look md down-->
                    <a class="btn btn_look" href="#">Подобрать<strong> Look</strong></a>

                    <!-- Goods listing-->
                    <div class="goods-listing js-view" id="js-goods">
                        @if($products->count())
                            @include('catalog.products.list')
                        @else
                            Нет товаров
                        @endif
                    </div>

                    {{-- Pagination --}}
                    @if($products->currentPage() < $products->lastPage())
                        <div class="page-navigation">
                            <button class="btn btn_more js-pagination" data-all="false">
                                <span class="text">Показать больше</span>
                                <span class="count js-goods-count">({{ $products->total() - ($products->currentPage() * $products->perPage()) }})</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                            <button class="btn btn_show-all js-pagination" data-all="true">
                                <span>Показать все товары</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                        </div>
                    @endif

                </div>
            </section>
            <section class="content-full-width">
                <!-- Page text-->
                <article class="page-text">
                    @if(isset($category))
                        {!! $category->text !!}
                    @elseif(isset($tag))
                        {!! $tag->text !!}
                    @endif
                </article>

                <!-- Related articles-->
                <div class="related-articles">
                    
                </div>

                @if(isset($category))
                    @widget('ArticlesWidget', ['category_id' => $category->id])
                @elseif(isset($tag))
                    @widget('ArticlesWidget', ['tag_id' => $tag->id])
                @endif

                @widget('SubscribeWidget')
            </section>
        </div>
    </main>
@endsection
