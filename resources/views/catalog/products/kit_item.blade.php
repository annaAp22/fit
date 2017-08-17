<div class="product-set-item">

	
	<!-- Order form-->
	<form class="product-set-item__description js-form-ajax" action="{{ route('ajax.cart.add', ['id' => $product->id, 'cnt' => 1]) }}" method="post">
		{{ csrf_field() }}

        <!-- Image-->
        <a class="product-set-item__image" href="{{ route('product', $product->sysname) }}">
            <img src="{{ $product->uploads->img->kit->url() }}"/>
            @if(count($product->sizes))
                @include('catalog.products.sizes', ['class' => ' product-set-item__size'])
            @else
                <input type="hidden" name="size" value="0">
            @endif
        </a>

		<!-- Name-->
		<a class="product-set-item__name" href="{{ route('product', $product->sysname) }}">{{ $product->name }}</a>

		<!-- Price-->
		<div class="product-set-item__price product__price">
			<span class="current">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
            @if($product->originalPrice)
                <i class="old-price"><span>{{ number_format($product->originalPrice, 0, '.', ' ') }} ₽</span></i>
            @endif
		</div>



		<!-- Add-->
        <button class="btn btn_green-border product-set-item__buy js-add-to-cart{{ session()->has('products.cart.'. $product->id) ? ' active' : '' }}">
            <span>Добавить</span>
            <span>В корзине</span>
        </button>
	</form>
</div>
