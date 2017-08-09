@if(isset($category) && $category->children->count())
	@foreach($category->children as $cat)
		@if($cat->children->count())
			@if(isset($chunk))
				@php
					$chunks = $cat->children->chunk($chunk);
				@endphp

				@if(count($chunks))
					@include('catalog.dropdown-column', ['items' => $chunks[0], 'cat_name' => $cat->name])
					@for($i =1; $i < count($chunks); $i++)
						@include('catalog.dropdown-column-fake', ['items' => $chunks[$i]])
					@endfor
				@endif
			@else
				@include('catalog.dropdown-column', ['items' => $cat->children, 'cat_name' => $cat->name])
			@endif
		@endif
	@endforeach
	@if(isset($unique_offer))
	<div class="catalog-dropdown__column">
		<div class="catalog-dropdown__title">Уникальные предложения</div>
		<ul class="ul ul_green-hover">
			<li><a href="{{ route('new', ['sysname' => $category->sysname]) }}">Новая коллекция</a></li>
			<li><a href="{{ route('actions', ['sysname' => $category->sysname]) }}">Акции</a></li>
			<li><a href="{{ route('hits', ['sysname' => $category->sysname]) }}">Хиты продаж</a></li>

            {{-- Look page link--}}
			{{--<li class="star">
				<a href="#"><i class="sprite_main sprite_main-product__star_active"></i><span>Look</span></a>
			</li>--}}
		</ul>
	</div>
	@endif
@endif