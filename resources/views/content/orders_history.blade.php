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
        @if(isset($user->partner))
            <div>Персоналный номер, передается покупателю для получения им разовой скидки, при этом процент с выполненнго заказа начисляется на личный счет владельца номера.</div>
            <div class="personal-number">
                {{$user->partner->code}}
            </div>
            <div>
                Всего заработано: <b>{{$user->partner->make_money}}</b> р.<br/>
                Снято со счета: <b>{{$user->partner->spent_money}}</b> р.<br/>
                Осталось на счету: <b>{{$user->partner->remain_money}}</b> р.<br/>
            </div>
            <br/>
        @endif
        <div class="orders-table_caption">ИСТОРИЯ ЗАКАЗОВ</div>
        <div class="orders-table_wrapper js-horizontal-scroll">
            <table class="orders-table js-orders-table">
                <tr>
                    <th>
                        <div class="num">Номер заказа</div>
                    </th>
                    <th>Дата оформления</th>
                    <th class="count-col">Товаров</th>
                    <th>Цена с доставкой</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
                @include('blocks.orders_rows')
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
            <div class="no-orders">У Вас пока нет заказов</div>
        @endif
    </section>

    <section class="content-full-width">
        @widget('SubscribeWidget')
    </section>
</main>
@endsection