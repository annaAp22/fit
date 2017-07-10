@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('search', $text) !!}
@endsection

@section('content')
    <main>

        <div class="container">

            {{-- SideBar --}}
            @include('content.aside')

            <section class="content">
                <div class="container-in">

                    <div class="header-listing">
                        <h1>Результаты поиска</h1>
                        <div class="goods-count">
                            <span>Товаров найдено</span>
                            <i class="sprite_main sprite_main-icon__goods_count">{{ isset($products)?$products->total():0 }}</i>
                        </div>
                    </div>

                    <!-- Goods listing-->
                    <div class="goods-listing js-container-search">
                        @if(isset($products) && $products->count())
                            @include('catalog.products.list')
                        @else
                            <p class="col-24">Поиск не принес результатов. Попробуйте поискать что-нибудь другое.</p>
                        @endif
                    </div>

                    {{-- Pagination --}}
                    @if(isset($products) && $products->currentPage() < $products->lastPage())
                        <div class="page-navigation js-pagination-search">
                            <button class="btn btn_more js-action-link"
                                    data-text="{{$text}}"
                                    data-url="{{route('search')}}"
                                    data-page="{{$products->currentPage() + 1}}">
                                <span class="text">Показать больше</span>
                                <span class="count js-items-count">({{ $products->total() - ($products->currentPage() * $products->perPage()) }})</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                            <button class="btn btn_show-all js-action-link"
                                    data-text="{{$text}}"
                                    data-url="{{route('search')}}"
                                    data-page="1">
                                <span>Показать все товары</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </section>
            <section class="content-full-width">
                @widget('SubscribeWidget')
            </section>

        </div>
    </main>
@endsection