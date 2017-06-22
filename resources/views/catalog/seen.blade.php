@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('seen') !!}
@stop

@section('content')
    <main>
        <section class="container">
            {{-- SideBar --}}
            @include('content.aside')

            <section class="content">
                <div class="container-in">
                    <!-- Header -->
                    <div class="header-listing">
                        <h1>Просмотренные товары</h1>
                        <div class="goods-count">
                            <span>Товаров в категории</span>
                            <i class="sprite_main sprite_main-icon__goods_count">{{ $products->count() }}</i>
                        </div>
                    </div>

                    <!-- Goods listing-->
                    <div class="goods-listing js-view" id="js-goods">
                        @if($products->count())
                            @include('catalog.products.list')
                        @else
                            Нет товаров
                        @endif
                    </div>
                </div>
            </section>
            <section class="content-full-width">
                @widget('SubscribeWidget')
            </section>
        </section>
    </main>
@stop