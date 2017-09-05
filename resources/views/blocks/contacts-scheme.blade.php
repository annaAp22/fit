@if(isset($page->photos) && $page->photos->count())
    <div class="contacts__gallery">
        <div class="container-in">
            @php $scheme = 'id=scheme'; @endphp
            @foreach($page->photos->where('name', 'scheme') as $photo)
                <a href="{{ $photo->uploads->img->url() }}" data-fancybox="group-0" {{ $scheme }}>
                    <img class="page-text__image" src="{{ $photo->uploads->img->preview->url() }}" alt="">
                </a>
                @php $scheme = ''; @endphp
            @endforeach
        </div>
    </div>
@endif
