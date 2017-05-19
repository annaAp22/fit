@foreach($comments as $comment)
    <div class="product-review review">
        <div class="product-review__info">
            <i class="sprite_main sprite_main-product_review-person-green"></i>
            <div>
                <div class="product-review__name">{{ $comment->name }}</div>
                <div class="product-review__date">{{ $comment->created_at }}</div>
                <div class="product-rating">
                    <div class="icon-fade product-rating__star active"><i class="sprite_main sprite_main-product__star normal"></i><i class="sprite_main sprite_main-product__star_active active"></i>
                    </div>
                    <div class="icon-fade product-rating__star active"><i class="sprite_main sprite_main-product__star normal"></i><i class="sprite_main sprite_main-product__star_active active"></i>
                    </div>
                    <div class="icon-fade product-rating__star active"><i class="sprite_main sprite_main-product__star normal"></i><i class="sprite_main sprite_main-product__star_active active"></i>
                    </div>
                    <div class="icon-fade product-rating__star active"><i class="sprite_main sprite_main-product__star normal"></i><i class="sprite_main sprite_main-product__star_active active"></i>
                    </div>
                    <div class="icon-fade product-rating__star"><i class="sprite_main sprite_main-product__star normal"></i><i class="sprite_main sprite_main-product__star_active active"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="product-review__text">{{ $comment->text }}</div>
    </div>
@endforeach