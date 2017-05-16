@if ($paginator->hasPages())
    <div class="page-navigation">
         {{--Previous Page Link--}}
        @if ($paginator->currentPage() == 1)
            <span class="page-navigation_prev"></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-navigation_prev" rel="prev"></a>
        @endif

        {{--Pagination Elements--}}
        @for($p = 1; $p <= $paginator->lastPage(); $p++)
            @if($p == $paginator->currentPage())
                <span class="page-navigation_num">{{ $p }}</span>
            @else
                <a href="{{ $paginator->url($p) }}" class="page-navigation_num">{{ $p }}</a>
            @endif
        @endfor

        {{--Next Page Link--}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-navigation_next" rel="next"></a>
        @else
            <span class="page-navigation_next"></span>
        @endif
    </div>
@endif
