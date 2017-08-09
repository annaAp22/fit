@foreach($comments as $comment)
    <div class="product-review review">
        <div class="product-review__info">
            <i class="sprite_main sprite_main-product_review-person-green"></i>
            <div>
                <div class="product-review__name">{{ $comment->name }}</div>
                <div class="product-review__date">{{ $comment->created_at->format('Y.m.d') }}</div>
                <div class="product-rating">
                    @for($i = 0; $i < $comment->rating; $i++)
                        <div class="icon-fade product-rating__star active"><i class="sprite_main sprite_main-product__star normal"></i><i class="sprite_main sprite_main-product__star_active active"></i>
                        </div>
                    @endfor
                    @for(;$i < 5; $i++)
                        <div class="icon-fade product-rating__star"><i class="sprite_main sprite_main-product__star normal"></i><i class="sprite_main sprite_main-product__star_active active"></i>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
        <div class="product-review__text">{{ $comment->text }}</div>
        @if($comment->answer)
            <div class="product-review__text product-review__text_answer">
                <div class="product-review__answer-title">Ответ магазина:</div>
                {{ $comment->answer }}
            </div>
        @endif
    </div>
@endforeach