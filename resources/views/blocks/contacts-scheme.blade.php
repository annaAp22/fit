@if(isset($page->photos) && $page->photos->count())
    <div class="contacts__gallery" id="#scheme">
        <div class="container-in">
            @foreach($page->photos->where('name', 'scheme') as $photo)
                <a href="{{ $photo->uploads->img->url() }}" data-fancybox="group-0">
                    <img class="page-text__image" src="{{ $photo->uploads->img->preview->url() }}" alt="">
                </a>
            @endforeach
        </div>
    </div>
@endif
