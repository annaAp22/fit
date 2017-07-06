<aside class="sidebar">

    {{-- Catalog navigation --}}
    @widget('ListingCatalog')
    @if(in_array(Route::currentRouteName(), ['seen', 'bookmarks']))
        @widget('TagsWidget', ['products' => $products])
    @else
        @widget('TagsWidget')
    @endif
    @widget('BannerLeftWidget')
</aside>