<div class="catalog-dropdown__column-fake">
    <div class="catalog-dropdown__title-fake"> </div>
    <ul class="ul ul_green-hover">
        @foreach($items as $subcat)
            <li data-text="{{ $subcat->name }}">
                <a href="{{ route('catalog', $subcat->sysname) }}">{{ $subcat->name }}</a>
            </li>
        @endforeach
    </ul>
</div>
