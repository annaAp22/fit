<!-- Stars rating-->
<div class="product-rating">
    <span class="product-rating__count sprite sprite_main sprite sprite_main-product__reviews-count">{{ $product->comments->count() }}</span>
    @for($i=1;$i<=5;$i++)
        <div class="icon-fade product-rating__star">
            @if($i <= $product->averageRating)
                <i class="sprite_main sprite_main-product__star_active"></i>
            @else
                <i class="sprite_main sprite_main-product__star"></i>
            @endif
        </div>
    @endfor
</div>