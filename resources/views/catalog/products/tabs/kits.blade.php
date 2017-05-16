@if($product->restKit)
	<!-- Products set-->
	<div class="product-set-title">
		<span>Собери весь комплект</span>
		<span>Заверши свой образ:</span>
	</div>

	<div class="products-carousel product-set-goods" id="js-product-set">
		<button class="btn btn_carousel-control">
			<i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
		</button>
			<div class="products-carousel__wrap">
				<div class="products-carousel__track">
					@foreach($product->restKit as $prod)
						@include('catalog.products.kit_item', ['product' => $prod])
					@endforeach
				</div>
			</div>
		<button class="btn btn_carousel-control">
			<i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
		</button>
	</div>

	<div class="product-set-banner">
	    <div class="product-set-banner__title">
	        <div class="product-set-banner__orange-cycle"><i class="sprite_main sprite_main-product-set-banner-cycle-white"></i>
	        </div><span>Заберем если не подошло, без проблем!</span>
	    </div>
	    <div class="product-set-banner__text">Если не понравился дизайн или покрой - примем покупку назад и оставим бонус!
	    </div>
	</div>
@endif
