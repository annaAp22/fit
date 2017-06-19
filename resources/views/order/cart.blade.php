@extends('layouts.main')

{{--@php session()->flush(); @endphp--}}

@section('breadcrumbs')
    {!!  Breadcrumbs::render('cart') !!}
@endsection

@section('content')
    <main class="container" role="main">
        <form class="product-cart" action="{{ route('ajax.cart.edit') }}" method="post">
            {{ csrf_field() }}
            <input id="is_fast" type="hidden" name="is_fast" value="0">
            <!-- Page header-->
            <div class="product-cart-header">
                <div class="product-cart-header__title">
                    <!-- H1 title-->
                    <h1 class="product-cart-header__h1">Моя корзина</h1>
                    <!-- Products count-->
                    <div class="product-cart-header__count">
                        <i class="sprite_main sprite_main-icon__goods_count js-cart-quantity">{{ $products->count() }}</i>
                    </div>
                </div>
                <!-- Back to shopping link-->
                {{--<a class="btn btn_back-link" href="#" onclick="location.href = document.referrer;">--}}
                    {{--<span class="icon-fade">--}}
                        {{--<i class="sprite_main sprite_main-icon-arrow-small-left-gray normal"></i>--}}
                        {{--<i class="sprite_main sprite_main-icon-arrow-small-left-green_active active"></i>--}}
                        {{--<span>Назад к покупкам</span>--}}
                    {{--</span>--}}
                {{--</a>--}}
            </div>
            <!-- Products list table-->
            <div class="product-cart-table">
                <div class="product-cart-table__header product-cart-table__row container-in">
                    <div class="product-cart-table__col product-cart-table__col_photo">Фото товара:
                    </div>
                    <div class="product-cart-table__col product-cart-table__col_name">Название товара:
                    </div>
                    <div class="product-cart-table__col product-cart-table__col_quantity">Количество:
                    </div>
                    <div class="product-cart-table__col product-cart-table__col_price">Цена за шт.:
                    </div>
                    <div class="product-cart-table__col product-cart-table__col_price-sum">Цена:
                    </div>
                    <div class="product-cart-table__col product-cart-table__col_remove">Удалить:
                    </div>
                </div>
                <div class="product-cart-table__body">
                    @forelse($products as $product)
                    <div class="product-cart-table__product product-cart-table__row container-in js-product" data-id="{{ $product->id }}-{{ $product->size }}">
                        <div class="product-cart-table__col product-cart-table__col_photo">
                            <a class="product-cart-table__image" href="{{ route('product', $product->sysname) }}"><img src="{{ $product->uploads->img->cart->url() }}" alt="{{ $product->name }}"></a>
                        </div>
                        <div class="product-cart-table__col product-cart-table__col_name">
                            <a class="product-cart-table__name" href="{{ route('product', $product->sysname) }}">{{ $product->name }}<span class="product-cart-table__art"> {{ $product->sku }}</span></a>
                            @if($product->size)
                                <div class="product-cart-table__size">Размер:<span>{{ $product->size }}</span></div>
                            @endif
                        </div>
                        <div class="product-cart-table__separator">
                        </div>
                        <div class="product-cart-table__col product-cart-table__col_quantity">
                            <div class="quantity">
                                <div class="quantity__handle quantity__handle_minus icon-fade js-quantity" data-num="-1"><i class="sprite_main sprite_main-icon-arrow-gray-left normal"></i><i class="sprite_main sprite_main-icon-arrow-green-left_active active"></i>
                                </div><input class="quantity__input js-quantity-input" name="products[{{ $product->id }}][{{ $product->size }}]" value="{{ $product->count }}" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/>
                                <div class="quantity__handle quantity__handle_plus icon-fade js-quantity" data-num="1"><i class="sprite_main sprite_main-icon-arrow-gray-left normal"></i><i class="sprite_main sprite_main-icon-arrow-green-left_active active"></i>
                                </div>
                            </div>
                        </div>
                        <div class="product-cart-table__separator product-cart-table__separator_md-up">
                        </div>
                        <div class="product-cart-table__col product-cart-table__col_price">
                            <div class="product-cart-table__price js-price" data-price="{{ $product->price }}">{{ number_format($product->price, 0, '.', ' ') }} ₽
                            </div>
                        </div>
                        <div class="product-cart-table__separator">
                        </div>
                        <div class="product-cart-table__col product-cart-table__col_price-sum">
                            <div class="product-cart-table__price-sum js-amount" data-amount="{{ $product->amount }}">{{ number_format($product->amount, 0, '.', ' ') }} ₽
                            </div>
                        </div>
                        <div class="product-cart-table__col product-cart-table__col_remove"><a class="product-cart-table__remove icon-fade js-action-link" data-url="{{ route('ajax.cart.remove', ['id' => $product->id, 'size' => $product->size]) }}"><i class="sprite_main sprite_main-icon-x-gray normal"></i><i class="sprite_main sprite_main-icon-x-red_active active"></i></a>
                        </div>
                    </div>
                    @empty
                    <div class="product-cart-table__product product-cart-table__row">
                        Корзина пуста
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="product-cart-footer container-in">
                @if($products->count())
                    <!-- Checkout-->
                    <button class="btn btn_green product-cart-footer__checkout js-cart-submit" data-is_fast="0">Оформить заказ
                    </button>
                    <!-- Quick buy-->
                    {{--<button class="btn btn_yellow product-cart-footer__quick-buy js-cart-submit" data-is_fast="1">Купить в 1 клик</button>--}}
                    <!-- Back to shopping link-->
                {{--<a class="btn btn_back-link product-cart-footer__back-link" href="#" onclick="location.href = document.referrer;"><span class="icon-fade"><i class="sprite_main sprite_main-icon-arrow-small-left-gray normal"></i><i class="sprite_main sprite_main-icon-arrow-small-left-green_active active"></i><span>Назад к покупкам</span></span></a>--}}
                    <div class="product-cart-footer__cart-total">Итоговая стоимость:
                        <div class="product-cart-footer__price-total js-total-amount" data-total="{{ $amount }}">{{ number_format($amount, 0, '.', ' ') }} ₽
                        </div>
                    </div>
                @endif
            </div>
        </form>
        <!-- Full width content-->
        <section class="content-full-width">
            @widget('SubscribeWidget')
        </section>
    </main>
@stop