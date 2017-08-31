<div class="look-book-slider js-look-book">
    <div class="look-book-slider__wrap">
        <div class="look-book-slider__track">
            @foreach($looks as $key => $look)
                <div class="look-book-slider__item {{ $key == 0 ? "active" : "" }}">
                    <form class="look-book__buy-package-form js-form-ajax" action="{{ route('ajax.cart.multiple.add') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="size_choose" value="1">
                        @foreach($look->products as $product)
                            <input type="hidden" name="product_ids[]" value="{{$product->id}}">
                        @endforeach
                        <button class="btn btn_green btn-look-book_buy">
                            <i class="sprite_main sprite_main-product__basket"></i>
                            <span>Купить весь комплект</span>
                        </button>
                    </form>


                    {{-- Dots --}}
                    @foreach($look->products as $ind => $product)
                        @php
                        $top = 100*$product->position->top/$look->uploads->image->normal->height;
                        $left = 100*$product->position->left/$look->uploads->image->normal->width;
                        @endphp
                        <div class="moving-dot js-moving-dot" id="js-moving-dot-{{$key.$ind}}" style="top:{{ $top }}%;left:{{ $left }}%">
                            <span class="moving-dot__plus {{ $top > 60 ? 'bottom' : 'top' }} {{ $left > 50 ? "left" : "right" }} js-toggle-active-target" data-target="#js-moving-dot-{{$key.$ind}}" data-reset=".js-moving-dot" data-toggle="0">+</span>

                            {{-- Product --}}
                            <form class="look-book-product {{ $top > 60 ? 'bottom' : 'top' }} {{ $left > 50 ? "left" : "right" }} js-form-ajax" action="{{ route('ajax.cart.add', ['id' => $product->id, 'cnt' => 1]) }}" method="post">
                                {{ csrf_field() }}
                                {{-- Name --}}
                                <a class="product__name" href="{{ route('product', $product->sysname) }}">{!! $product->getWrapTagInName() !!}</a>

                                {{-- Sizes --}}
                                @if(count($product->sizes))
                                    @include('catalog.products.sizes', ['class' => ' product__size'])
                                @else
                                    <div class="product__size-hidden">
                                        <input type="hidden" name="size" value="0">
                                    </div>
                                @endif

                                {{-- Buy button --}}
                                <button class="btn btn_green product__buy js-add-to-cart{{ session()->has('products.cart.'. $product->id) ? ' active' : '' }}">
                                            <span class="put"><i class="sprite_main sprite_main-product__basket"></i>
                                                <span>В корзину</span>
                                            </span>
                                    <a class="done" href="{{ route('cart') }}">
                                        <i class="sprite_main sprite_main-product__basket_done"></i>
                                        <span>Добавлено</span>
                                    </a>
                                </button>
                            </form>

                        </div>
                    @endforeach
                    <img class="look-book-slider__banner look-book-slider__banner_md-up" src="{{ $look->uploads->image->normal->url() }}" alt="" role="presentation"/>
                </div>
            @endforeach
        </div>
    </div>
    <button class="btn btn_carousel-control left" disabled="disabled">
        <i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
    </button>
    <button class="btn btn_carousel-control right" disabled="disabled">
        <i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
    </button>
</div>