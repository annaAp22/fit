<div id="cart-modal" class="modal-box" style="display: inline-block;">
    <form action="{{ route('ajax.cart.update.quantity', ['id' => $product->id]) }}" method="POST" class="js-form-ajax">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $product->id }}">
        <input type="hidden" name="size" value="{{ $size }}">

        <div class="cart-modal_top">
            <div class="form-modal_title">ТОВАР ДОБАВЛЕН В КОРЗИНУ!</div>
            <figure class="cart-modal_top_img align-table-wrap">
                <div><img src="{{ $product->uploads->img->modal->url() }}"></div>
            </figure>
            <div class="cart-modal_top_name">
                <a href="{{ route('product', $product->sysname) }}">{{ $product->name }}</a>
                <div>Артикул: <span>{{ $product->sku }}</span></div>
                @if($size)
                    <div><strong>Размер: <span>{{ $size }}</span></strong></div>
                @endif
            </div>
            <div class="cart-modal_top_counter">
                <div class="quantity">
                    <div class="quantity__handle quantity__handle_minus icon-fade js-quantity" data-num="-1" data-submit><i class="sprite_main sprite_main-icon-arrow-gray-left normal"></i><i class="sprite_main sprite_main-icon-arrow-green-left_active active"></i>
                    </div><input class="quantity__input js-quantity-input" data-submit name="quantity" value="{{ $cnt }}" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/>
                    <div class="quantity__handle quantity__handle_plus icon-fade js-quantity" data-num="1" data-submit><i class="sprite_main sprite_main-icon-arrow-gray-left normal"></i><i class="sprite_main sprite_main-icon-arrow-green-left_active active"></i>
                    </div>
                </div>
            </div>
            <div class="cart-modal_top_price">
                <span class="price">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
            </div>
        </div>
        <div class="cart-modal_bottom">
            <button data-fancybox-close class="btn btn_show-all">Продолжить покупки</button>
            <a href="{{ route('cart') }}" class="btn btn_green">ПЕРЕЙТИ К ОФОРМЛЕНИЮ</a>
        </div>
    </form>
    <button data-fancybox-close  class="modal-close">&#10006;</button>
</div>

