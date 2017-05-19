<!-- Product reviews-->
<div class="product-reviews-and-comments">
    <div class="product-reviews-and-comments__tabs">
        <div class="product-reviews-and-comments__tab active js-toggle-active-target" data-target=".js-reviews" data-switch="2">
            Отзывы -<span> {{ $comments->total() }}</span>
        </div>
        <div class="product-reviews-and-comments__separator"></div>
        <div class="product-reviews-and-comments__tab js-toggle-active-target js-vk-comments-widget" data-target=".js-reviews" data-switch="2">
            Комментарии ВК
        </div>
    </div>

    <!-- Reviews-->
    <div id="product-reviews" class="product-reviews container-in active js-reviews" data-count="{{$comments->count()}}">
        <!-- Reviews items-->
        @if($comments->count())
            @include('catalog.products.comments')
        @else
            <!-- Empty reviews-->
            <div class="reviews-empty"><i class="sprite_main sprite_main-empty-reviews-arrow-gray"></i>
                <div class="reviews-empty__title">Отзывов пока что нет
                </div>
                <div class="reviews-empty__text">Будьте первым, кто поделится своим мнением о товаре. Ваш отзыв внесет ценный вклад в развитие сервиса
                </div>
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
        @endif
        <!-- Reviews navigation-->
        @if($comments->lastPage() > $comments->currentPage())
            <div id="product-reviews-navigation" class="product-reviews-navigation">
                <button class="btn btn_more js-get" data-action="{{route('ajax.product.comments')}}?product_id={{$product->id}}" data-page="{{$comments->currentPage() + 1}}"><span class="text">Показать еще</span><span class="count">({{min($comments->total() - ($comments->currentPage() * $comments->perPage()), $comments->perPage())}})</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                </button>
                <button class="btn btn_show-all js-get" data-action="{{route('ajax.product.comments')}}?product_id={{$product->id}}&per_page=all"><span>Показать все</span><i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                </button>
            </div>
        @endif
    </div>
    <!-- VK comments-->
    <div class="product-vk-comments js-reviews" id="js-vk_comments">
    </div>
</div>

<form class="product-review-form js-form-ajax" method="post" action="{{ route('ajax.product.comment', ['id' => $product->id ]) }}">
    <div class="product-review-form__title">Оставить свой отзыв
    </div>
    <!-- Form body-->
    <div class="product-review-form__body js-comment-success">

        <!-- Form fields-->
        <div class="product-review-form__label">Представьтесь
        </div><input class="input input_text" type="text" name="name" placeholder="Ваше имя"/>
        <div class="product-review-form__label product-review-form__label_mt">Оцените товар по 5-ти бальной шкале
        </div>
        <div class="rating-inputs">
            <label class="radio radio_square">
                <input type="radio" name="rating" value="1"/><span class="fake-input"><span></span></span><span class="label">1</span>
            </label>
            <label class="radio radio_square">
                <input type="radio" name="rating" value="2"/><span class="fake-input"><span></span></span><span class="label">2</span>
            </label>
            <label class="radio radio_square">
                <input type="radio" name="rating" value="3"/><span class="fake-input"><span></span></span><span class="label">3</span>
            </label>
            <label class="radio radio_square">
                <input type="radio" name="rating" value="4"/><span class="fake-input"><span></span></span><span class="label">4</span>
            </label>
            <label class="radio radio_square">
                <input type="radio" name="rating" value="5"/><span class="fake-input"><span></span></span><span class="label">5</span>
            </label>
        </div>
        <div class="product-review-form__label product-review-form__label_mt">Текст Вашего отзыва</div>
        <textarea class="textarea textarea_text" name="text" placeholder="Несколько слов о товаре"></textarea>
        <button class="btn btn_green-border-900">ОТПРАВИТЬ</button>
    </div>
</form>
