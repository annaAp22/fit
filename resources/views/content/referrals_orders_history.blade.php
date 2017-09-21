@extends('layouts.main')
@section('content')
<main class="container">
    @include('blocks.aside')
    <section class="content">
        <!-- Header -->
        <div class="header-room">
            <h1>Мой кабинет</h1>
            <div class="icon sprite_main sprite_main-header__enter"></div>
            <div class="name">{{$user->name}}</div>
            <div class="id-wrapper">
                <span class="text">Мой id:</span>
                <span class="id">{{$user->id}}</span>
                <div class="corner"></div>
            </div>
        </div>
        <div class="orders-table_caption">ИСТОРИЯ ЗАКАЗОВ ВАШИХ РЕФЕРАЛОВ</div>
        <div class="orders-table_wrapper js-horizontal-scroll">
            <table class="orders-table js-orders-table">
                @include('blocks.referrals_orders_rows')
            </table>
            <div class="horizontal_scroller hidden-md-up js-buttons-wrapper">
                <div class="left js-left-btn">
                    <span class="sprite_main sprite_main-icon-arrow-gray-left"></span>
                </div>
                <div class="right js-right-btn">
                    <span class="sprite_main sprite_main-icon-arrow-gray-left"></span>
                </div>
            </div>
        </div>
        <div class="js-page-navigation">
            @include('blocks.orders_pages')
        </div>
        @if(!count($orders))
            <div class="no-orders">На данный момент нет заказов</div>
        @endif
    </section>

    <section class="content-full-width">
        @widget('SubscribeWidget')
    </section>
</main>
@endsection