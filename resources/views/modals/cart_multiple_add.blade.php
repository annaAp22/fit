<div id="cart-modal" class="modal-box cart-multiple-add" style="display: inline-block;">
    <form action="{{ route('ajax.cart.multiple.add') }}" method="POST" class="js-form-ajax" data-submit-on-close="true">
        {{ csrf_field() }}
        <input type="hidden" name="size_choose" value="0">
        <div class="cart-modal_top">
            <div class="form-modal_title">ТОВАРЫ ДОБАВЛЕНЫ В КОРЗИНУ!</div>
        </div>

            @foreach($products as $product)
                <div class="cart-modal_top">
                    <input type="hidden" name="product_ids[]" value="{{$product->id}}">
                    <figure class="cart-modal_top_img align-table-wrap">
                        <div><img src="{{ $product->uploads->img->modal->url() }}"></div>
                    </figure>
                    <div class="cart-modal_top_name">
                        <a href="{{ route('product', $product->sysname) }}">{{ $product->name }}</a>
                        <div>Артикул: <span>{{ $product->sku }}</span></div>
                    </div>
                    <div class="cart-modal_top_counter">
                        @if($product->sizes)
                            <label class="cart-multiple-add__size-label" for="size">Размер: </label>
                            <select name="sizes[{{$product->id}}]" id="size">
                                    @foreach($product->sizes as $size)
                                        <option value="{{ $size }}">{{ $size }}</option>
                                    @endforeach
                            </select>
                        @else
                            <input type="hidden" name="sizes[{{$product->id}}]" value="0">
                        @endif
                    </div>
                    {{--<div class="cart-modal_top_counter">
                        <div class="quantity">
                            <div class="quantity__handle quantity__handle_minus icon-fade js-quantity" data-num="-1" data-submit><i class="sprite_main sprite_main-icon-arrow-gray-left normal"></i><i class="sprite_main sprite_main-icon-arrow-green-left_active active"></i>
                            </div><input class="quantity__input js-quantity-input" data-submit name="quantity" value="1" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57'/>
                            <div class="quantity__handle quantity__handle_plus icon-fade js-quantity" data-num="1" data-submit><i class="sprite_main sprite_main-icon-arrow-gray-left normal"></i><i class="sprite_main sprite_main-icon-arrow-green-left_active active"></i>
                            </div>
                        </div>
                    </div>--}}
                    <div class="cart-modal_top_price">
                        <span class="price">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                    </div>
                </div>
            @endforeach
        <div class="cart-modal_bottom">
            <input type="hidden" name="checkout" value="1" disabled id="checkout">
            <a data-fancybox-close href="{{ route('cart') }}" class="btn btn_green" onclick="document.getElementById('checkout').disabled = false">ПЕРЕЙТИ К ОФОРМЛЕНИЮ</a>
            <button data-fancybox-close class="btn btn_show-all">Продолжить покупки</button>
        </div>
    </form>
    <button data-fancybox-close  class="modal-close">&#10006;</button>
</div>
