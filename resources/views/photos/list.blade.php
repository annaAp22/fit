@isset($photos)
    @foreach($photos as $photo)
        <div class="article-preview article-preview_news article-preview_photo articles__article">
            <a class="article-preview__image" href="{{ $photo->uploads->img->original->url() }}" data-fancybox="group" data-caption="{{ $photo->caption }}"><img src="{{ $photo->uploads->img->detailed->url() }}"/></a>
        </div>
    @endforeach
@endisset