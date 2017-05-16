@php
    $deferred = session()->get('products.defer');
    $bookmarked = $deferred ? array_key_exists($product->id, $deferred) : false;
@endphp

<div class="product">
    <a class="product__image" href="{{ route('product', $product->sysname) }}">
        <!-- Image-->
        <img src="{{ $product->uploads->img->listing->url() }}"/>

        @include('catalog.products.labels')
    </a>

    <!-- Add to wishlist-->
    <div class="product__wishlist wishlist js-hover-notice js-toggle-active js-action-link" data-action="{{ route('ajax.product.defer', ['id' => $product->id]) }}">
        <div class="icon-fade">
            <i class="sprite_main sprite_main-product__wishlist normal"></i>
            <i class="sprite_main sprite_main-product__wishlist_active active"></i>
        </div>
        <i class="sprite_main sprite_main-product__wishlist_done done"></i>

        <!-- Popup-->
        <div class="popup-notice popup-notice_wishlist">
            <div class="popup-notice__triangle">▼</div>
            <i class="sprite_main sprite_main-icon__popup_info"></i>
            <div class="popup-notice__text">Добавить товар в закладки</div>
            <div class="popup-notice__text done">Товар добавлен в закладки!</div>
        </div>
    </div>

    <!-- Description-->
    <form class="product__description js-form-ajax" action="{{ route('ajax.cart.add', ['id' => $product->id, 'cnt' => 1]) }}" method="post">
        {{ csrf_field() }}
        <a class="product__name" href="{{ route('product', $product->sysname) }}">{{ $product->name }}</a>
        <div class="product__price">
            @if($product->originalPrice)
                <i class="sprite_main sprite_main-product__old-price old-price"><span>{{ number_format($product->originalPrice, 0, '.', ' ') }} ₽</span></i>
            @endif
            <span class="current">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
        </div>
        <div class="product__hidden">

            @include('catalog.products.rating')

            @if($sizeAttr = $product->attributes->where('name', 'Размеры')->first())
                @php $sizes = json_decode($sizeAttr->pivot->value); @endphp
                @include('catalog.products.sizes', ['class' => ' product__size'])
            @endif

            <!-- Button buy-->
            @if(isset($sizes) && $sizes)
                <button class="btn btn_green product__buy js-add-to-cart{{ session()->has('products.cart.'. $product->id) ? ' active' : '' }}">
                        <span class="put"><i class="sprite_main sprite_main-product__basket"></i>
                            <span>В корзину</span>
                        </span>
                        <a class="done" href="{{ route('cart') }}">
                            <i class="sprite_main sprite_main-product__basket_done"></i>
                            <span>Добавлено</span>
                        </a>
                </button>
            @endif
        </div>
    </form>
</div>
