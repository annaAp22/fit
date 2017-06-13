@if($category->children->count())
	@foreach($category->children as $cat)
		<div class="catalog-dropdown__column">
			<div class="catalog-dropdown__title">{{ $cat->name }}</div>
			<ul class="ul ul_green-hover">
				@if($cat->children->count())
					@foreach($cat->children as $subcat)
						<li>
							<a href="{{ route('catalog', $subcat->sysname) }}">{{ $subcat->name }}</a>
						</li>
					@endforeach
				@endif
			</ul>
		</div>
	@endforeach
	<div class="catalog-dropdown__column">
		<div class="catalog-dropdown__title">Уникалные предложения</div>
		<ul class="ul ul_green-hover">
			<li><a href="{{ route('new', ['sysname' => $category->sysname]) }}">Новинки</a></li>
			<li><a href="{{ route('actions', ['sysname' => $category->sysname]) }}">Акции</a></li>
			<li><a href="{{ route('hits', ['sysname' => $category->sysname]) }}">Хиты продаж</a></li>

            {{-- Look page link--}}
			{{--<li class="star">
				<a href="#"><i class="sprite_main sprite_main-product__star_active"></i><span>Look</span></a>
			</li>--}}
		</ul>
	</div>
@endif