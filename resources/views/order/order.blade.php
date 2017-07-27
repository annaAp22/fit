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
            <form name="checkout-form" class="checkout-form" action="{{ route('order.details') }}" method="post">
                <div class="form-header">
                    <!-- Step 1-->
                    <div class="form-header__title js-step js-step js-step_1 active"><i class="sprite_main sprite_main-form-header-smile-green"></i><span>Давайте знакомиться!</span>
                    </div>
                    <div class="form-header__notice form-required js-step js-step js-step_1">- эти поля нужно заполнять обязательно
                    </div>
                    <!-- Step 2-->
                    <div class="form-header__title js-step js-step js-step_2"><i class="sprite_main sprite_main-checkout-truck-green"></i><span>Как мы доставим Ваш товар?</span>
                    </div>
                    <!-- Step 3-->
                    <div class="form-header__title js-step js-step js-step_3" id="js-order-success"><i class="sprite_main sprite_main-checkout-flag-green"></i><span>Готово!</span>
                    </div>
                </div>
                <div class="form-body">
                    <!-- Step 1-->
                    <div class="container-in js-step js-step_1 active">
                        <div class="form-input form-body__input">
                            <div class="form-label form-required">Привет! Меня зовут:
                            </div>
                            <input class="input input_text form-input__input js-required-fields" type="text" name="name" placeholder="Ф.И.О." value="{{isset($user->name)?$user->name:''}}"/>
                            <i class="form-input__icon sprite sprite_main sprite sprite_main-form-input-person-green"></i>
                        </div>
                        <div class="form-input form-body__input">
                            <div class="form-label form-required">Со мной можно связаться по телефону:
                            </div>
                            <input class="input input_text form-input__input js-phone js-required-fields" type="text" name="phone" placeholder="+7 ___ ___ __ __" value="{{isset($user->phone)?$user->phone:''}}"/>
                            <i class="form-input__icon sprite sprite_main sprite sprite_main-form-input-phone-green"></i>
                        </div>
                        <div class="form-input form-body__input">
                            <div class="form-label form-required">Email:
                            </div>
                            <input class="input input_text form-input__input js-required-fields" type="text" name="email" placeholder="my_email@gmail.com" value="{{isset($user->email)?$user->email:''}}"/>
                            <i class="form-input__icon sprite sprite_main sprite sprite_main-form-input-letter-green"></i>
                        </div>
                        <div class="form-input form-body__input">
                            <div class="form-label">Я живу по адресу:
                            </div><input class="input input_text form-input__input" type="text" name="address" placeholder="г. Город ул. Улица д. 1 этаж 1 кв. 1" /><i class="form-input__icon sprite sprite_main sprite sprite_main-form-input-point-green"></i>
                        </div>
                        <div class="form-modal_center">
                            <label class="radio radio_box">
                                <input class="js-required-fields" name="rating" value="1" type="checkbox" checked><span class="fake-input"><span></span></span><span class="label">Я соглашаюсь, на <a target="_blank" href="{{route('page', ['sysname' => 'polzovatelskoe-soglashenie'])}}">обработку персональных данных</a></span>
                            </label>
                        </div>

                    </div>
                    <!-- Step 2-->
                    <div class="container-in js-step js-step_2">
                        @isset($deliveries)
                            @foreach($deliveries as $key => $delivery)
                                <label class="checkout-delivery js-delivery">
                                    <input class="checkout-delivery__input" type="radio" name="delivery_id" value="{{ $delivery->id }}" {{ $key ? '' : 'checked="checked"' }}/>
                                    <span class="checkout-delivery__fake-radio">
                                        <i class="sprite_main sprite_main-checkout-checked-green"></i>
                                    </span>
                                    <span class="checkout-delivery__description">
                                        <span class="checkout-delivery__title">{{ $delivery->name }}</span>
                                        <span class="checkout-delivery__text">{{ $delivery->descr }}
                                            <span class="checkout-delivery__price js-price" data-price="{{ $delivery->price }}">{{ $delivery->price }} ₽</span>
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        @endisset
                    </div>
                    <!-- Step 3-->
                    <div class="container-in js-step js-step_3" id="order-success"></div>
                </div>
                <div class="form-footer js-step js-step js-step_2 active">
                    <!-- Step 1-->
                    <div class="container-in js-step js-step_1 active">
                        <button id="order-finish-btn" class="btn btn_green form-footer__btn form-footer__btn_step-1 js-step-next" data-next_step="2" disabled><span>Подтвердить</span><i class="sprite_main sprite_main-icon-arrow-small-right-dark-green"></i>
                        </button>
                        <div class="form-secure"><i class="form-secure__icon sprite sprite_main sprite sprite_main-form-secure-lock-gray"></i>
                            <div>
                                <div class="form-secure__title">Ваши данные защищены
                                </div>
                                <div class="form-secure__text">Магазин Fit2U дает абсолютную гарантию на полную конфеденциальность Вашей личной информации
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Step 2-->
                    <div class="container-in js-step js-step_2">
                        <button class="btn btn_green form-footer__btn form-footer__btn_step-2" id="js-order-submit"><span>Подтвердить</span><i class="sprite_main sprite_main-icon-arrow-small-right-dark-green"></i>
                        </button>
                        <div class="checkout-total">
                            <div class="checkout-total__text">Итоговая стоимость с учетом доставки:
                            </div>
                            <div class="checkout-total__price js-total" data-amount="{{ $cart['amount'] }}">{{ number_format($cart['amount'], 0, '.', ' ') }} ₽
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Full width content-->
    <section class="content-full-width">
        @widget('SubscribeWidget')
    </section>
</main>
@stop