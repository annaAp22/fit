<a href="{{ route('cart') }}">
    <div class="count">
        <span class="js-cart-quantity">{{ $config['count'] }}</span>
    </div>
    <span class="icon-fade basket">
        <i class="sprite_main sprite_main-header__basket normal"></i>
        <i class="sprite_main sprite_main-header__basket_active active"></i>
    </span>
    <span class="icon-fade basket-min">
        <i class="sprite_main sprite_main-header__basket-min normal"></i>
        <i class="sprite_main sprite_main-header__basket-min-active active"></i>
    </span>
</a>