@if(isset($page->photos) && $page->photos->count())
    <div class="contacts__gallery">
        <div class="container-in">
            @foreach($page->photos->filter(function ($item) {
            return ($item['name'] != 'scheme' && $item['name'] != 'map');
            }) as $photo)
                <a href="{{ $photo->uploads->img->url() }}" data-fancybox="group-1">
                    <img class="page-text__image" src="{{ $photo->uploads->img->preview->url() }}" alt="">
                </a>
            @endforeach
        </div>
    </div>
@endif
