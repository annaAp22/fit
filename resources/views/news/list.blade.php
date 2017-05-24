@isset($news)
    @foreach($news as $article)
        <div class="article-preview article-preview_news articles__article">
            <a class="article-preview__image" href="{{ route('news.record', ['sysname' => $article->sysname]) }}"><img src="{{ $article->uploads->img->preview->url() }}"/></a>
            <div class="article-preview__title">{{ $article->name }}</div>
            <div class="article-preview__preview-text">{{ App\Helpers\russianDate($article->date) }}</div>
            <a class="btn btn_read-full" href="{{ route('news.record', $article->sysname) }}">Смотреть</a>
        </div>
    @endforeach
@endisset