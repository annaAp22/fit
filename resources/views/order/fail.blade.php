@extends('layouts.main')

{{--@php session()->flush(); @endphp--}}

@section('breadcrumbs')
    {!!  Breadcrumbs::render('delivery') !!}
@endsection

@section('content')
<main class="container" role="main">
    <div class="checkout">
        <!-- Header-->
        <div class="checkout-header container-in">
            <!-- H1 title-->
            <h1 class="checkout-header__h1">Оформление заказа
            </h1>
            <!-- Checkout steps-->
            <div class="checkout-steps">
                <!-- Step 1-->
                <div class="checkout-steps__step">
                    <div class="checkout-steps__number js-step js-step js-step_1 active">1
                    </div><span>Шаг</span>
                    <div class="checkout-steps__name">Информация о покупателе
                    </div>
                </div>
                <!-- Step 2-->
                <div class="checkout-steps__step">
                    <div class="checkout-steps__number js-step js-step js-step_2">2
                    </div><span>Шаг</span>
                    <div class="checkout-steps__name">Выбор доставки и оплаты
                    </div>
                </div>
                <!-- Step 3-->
                <div class="checkout-steps__step">
                    <div class="checkout-steps__number js-step js-step js-step_3">3
                    </div><span>Шаг</span>
                    <div class="checkout-steps__name">Подтверждение заказа
                    </div>
                </div>
            </div>
            <!-- Back to shopping link-->
            {{--<a class="btn btn_back-link" href="#"><span class="icon-fade"><i class="sprite_main sprite_main-icon-arrow-small-left-gray normal"></i><i class="sprite_main sprite_main-icon-arrow-small-left-green_active active"></i><span>Назад к покупкам</span></span></a>--}}
        </div>
        <!-- Body-->
        <div class="checkout-body container-in">
            @include('order.partials.goods')
            <!--  Order form-->
            <div class="checkout-form" method="post">
                <input name="_token" type="hidden" value="{{csrf_token()}}">
                <div class="form-header">
                    <!-- Step 1-->
                    <div class="form-header__title active"><span> {{$message}}</span>
                    </div>
                </div>
                <button id="order-finish-btn" class="btn btn_green form-footer__btn" ><span>Выбрать другой способ оплаты</span><i class="sprite_main sprite_main-icon-arrow-small-right-dark-green"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Full width content-->
    <section class="content-full-width">
        @widget('SubscribeWidget')
    </section>
</main>
@stop