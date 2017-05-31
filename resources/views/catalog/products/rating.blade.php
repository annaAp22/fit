<!-- Stars rating-->
@php $comments = $product->comments->first(); @endphp
<div class="product-rating">
    <span class="product-rating__count sprite sprite_main sprite sprite_main-product__reviews-count">{{ ($comments) ? $comments->count : 0 }}</span>
    @for($i=1;$i<=5;$i++)
        <div class="icon-fade product-rating__star">
            @if($comments && $i <= $comments->avg)
                <i class="sprite_main sprite_main-product__star_active"></i>
            @else
                <i class="sprite_main sprite_main-product__star"></i>
            @endif
        </div>
    @endfor
</div>