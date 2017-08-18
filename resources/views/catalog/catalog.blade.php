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
                $filters['paginator'] = $products;
                $filters['category'] = isset($category) ? $category : null;
                $filters['brand'] = isset($brand) ? $brand : null;
                $filters['tag'] = isset($tag) ? $tag : null;
                if(isset($category)) {
                $filters['filters'] = $category->filters;
                }
                if(isset($tag)) {
                $filters['filters'] = $tag->filters;
                }
                $filters['sort'] = isset($filters['sort'])?$filters['sort']:'sort';
                @endphp

                @if(isset($filters['productsCount']) and $filters['productsCount'] > 0)
                    @include('catalog.filters', $filters)
                @endif

                {{-- Catalog navigation --}}
                @if(isset($category) && isset($parent_zero_id))
                    @widget('ListingCatalog', ['current' => $category, 'parent_id' => $parent_zero_id])
                @else
                    @widget('ListingCatalog')
                @endif
                @if(isset($category))
                    @widget('TagsWidget', ['category_id' => $category->id, 'category' => $category])
                @elseif(isset($tag))
                    @widget('TagsWidget', ['tag_id' => $tag->id])
                @endif
                @if(isset($category))
                    @widget('BannerLeftWidget', ['sex' => $category->getRootCategorySysname()])
                @else
                    @widget('BannerLeftWidget')
                @endif
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
                            <i class="sprite_main sprite_main-icon__goods_count">{{ $products->totalCount }}</i>
                        </div>
                    </div>

                    {{-- Category Offer Look banner --}}
                    @if( isset($category->offers) && $category->offers->count() )
                        @php $offer = $category->offers->first(); @endphp
                        <a class="banner-look" href="{{ $offer->url ?: '' }}" target="_blank">
                            <img class="banner-look__image banner-look__image_xl" src="{{ $offer->uploads->image->lg->url() }}" alt="" role="presentation"/>
                            {{--<img class="banner-look__image banner-look__image_md" src="{{ $offer->uploads->image->lg->url() }}" alt="" role="presentation"/>--}}
                        </a>
                    @endif
                            <!-- Show filters button md down-->
                    <button class="btn btn_filter js-toggle-sidebar" data-target=".js-filter-visible">Фильтры подбора товаров</button>
                    <!-- Sorting and view-->
                    <div class="goods-sorting"><i class="sprite_main sprite_main-listing__filter"></i><span>Сортировать товары:</span>
                        @php
                        $sortNames = array(
                        'sort' => 'По умолчанию',
                        'expensive' => 'Сначала дороже',
                        'cheaper' => 'Сначала дешевле',
                        'hit' => 'По популярности',
                        'act' => 'По акциям',
                        'new' => 'По новинкам',
                        )
                        @endphp
                        <div class="sorting-select js-toggle-active js-select"><span class="js-selected">{{$sortNames[$filters['sort']]}}</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            <div class="sorting-select__dropdown">
                                @foreach($sortNames as $key => $value)
                                    <div class="sorting-select__option js-option js-sort" data-sort="{{$key}}">{{$value}}</div>
                                @endforeach
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
                    @if($products->currentPage() < $products->totalPages)
                        <div class="page-navigation">
                            <button class="btn btn_more js-pagination" data-all="false">
                                <span class="text">Показать больше</span>
                                <span class="count js-goods-count">(<span>{{ $products->totalCount - ($products->currentPage() * $products->perPage()) }}</span>)</span>
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
