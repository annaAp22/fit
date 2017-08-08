<div class="checkout-goods">
    <div class="checkout-goods__title">Мои покупки</div>
    @foreach($cart['products'] as $product)
        <div class="checkout-goods__product">
            <div class="checkout-goods__image"><img src="{{ $product->uploads->img->cart->url() }}" alt="{{ $product->name }}"></div>
            <div>
                <div class="checkout-goods__name">{{ $product->name }}
                    <div class="checkout-goods__art"> {{ $product->sku }}
                    </div>
                </div>
                @if($product->size)
                    <div class="checkout-goods__size">Размер:<span>{{ $product->size }}</span></div>
                @endif
                <div class="checkout-goods__price">{{ number_format($product->price, 0, '.', ' ') }} ₽
                </div>
            </div>
        </div>
    @endforeach
    <div class="checkout-goods__text">Итоговая стоимость без учёта доставки:
    </div>
    <div class="checkout-goods__price">{{ number_format($cart['amount'], 0, '.', ' ') }} ₽
    </div>
</div>