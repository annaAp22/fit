@if ($breadcrumbs)
    <div class="breadcrumbs">
        <div class="container">
            <i class="sprite_main sprite_main-breadcrumbs__home"></i>
            @foreach ($breadcrumbs as $breadcrumb)
                @if (!$breadcrumb->last)
                    <a class="breadcrumbs__item" href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
                @else
                    <div class="breadcrumbs__item breadcrumbs__item_current">
                        {{ $breadcrumb->title }}
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
