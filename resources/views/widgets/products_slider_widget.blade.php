@if(isset($products))
    <div class="products-carousel-2 product-set-goods-large js-product-slider">
        <button class="btn btn_carousel-control">
            <i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
        </button>
        <div class="products-carousel__wrap">
            <div class="products-carousel__track">
                @foreach($products as $prod)
                    @include('catalog.products.kit_item', ['product' => $prod])
                @endforeach
                {{--@include('catalog.products.list')--}}
            </div>
        </div>
        <button class="btn btn_carousel-control">
            <i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
        </button>
    </div>
@endif
