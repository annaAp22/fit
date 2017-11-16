@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('bookmarks') !!}
@stop

@section('content')
    <main>
        <div class="container">
            {{-- SideBar --}}
            @include('content.aside')

            <section class="content">
                <div class="container-in">
                    <!-- Header -->
                    <div class="header-listing">
                        <h1>Отложенные товары</h1>
                        <div class="goods-count">
                            <span>Товаров</span>
                            <i class="sprite_main sprite_main-icon__goods_count">{{ $products->count() }}</i>
                        </div>
                    </div>
                {{-- баннер --}}
                @include('catalog.banner')
                    <!-- Goods listing-->
                    <div class="goods-listing js-view" id="js-goods">
                        @if($products->count())
                            @include('catalog.products.list')
                        @else
                            <div class="good-exists">
                                Нет товаров
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </main>
@stop