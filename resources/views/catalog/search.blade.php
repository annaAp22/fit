@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('search', $text) !!}
@endsection

@section('content')
    <main>

        <div class="container">
            <aside class="sidebar">

                {{-- Catalog navigation --}}
                @widget('ListingCatalog')

                @widget('TagsWidget')
                @widget('BannerLeftWidget')
            </aside>

            <section class="content">
                <div class="container-in">

                    <div class="header-listing">
                        <h1>Результаты поиска</h1>
                        <div class="goods-count">
                            <span>Товаров найдено</span>
                            <i class="sprite_main sprite_main-icon__goods_count">{{ $products->total() }}</i>
                        </div>
                    </div>

                    <!-- Goods listing-->
                    <div class="goods-listing js-view" id="js-goods">
                        @if($products->count())
                            @include('catalog.products.list')
                        @else
                            <p class="col-24">Поиск не принес результатов. Попробуйте поискать что-нибудь другое.</p>
                        @endif
                    </div>

                    {{-- Pagination --}}
                    @if($products->currentPage() < $products->lastPage())
                        <div class="page-navigation">
                            <button class="btn btn_more js-get" data-text="{{$text}}" data-action="{{route('search')}}" data-page="{{$products->currentPage() + 1}}">
                                <span class="text">Показать больше</span>
                                <span class="count js-goods-count">({{ $products->total() - ($products->currentPage() * $products->perPage()) }})</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                            <button class="btn btn_show-all js-get" data-per_page="all"  data-text="{{$text}}" data-action="{{route('search')}}">
                                <span>Показать все товары</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </section>

        </div>
    </main>
@endsection