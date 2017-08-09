<div class="catalog-dropdown__column">
    <div class="catalog-dropdown__title">{{ $cat_name }}</div>
    <ul class="ul ul_green-hover">
        @foreach($items as $subcat)
            <li data-text="{{ $subcat->name }}">
                <a href="{{ route('catalog', $subcat->sysname) }}">{{ $subcat->name }}</a>
            </li>
        @endforeach
    </ul>
</div>
