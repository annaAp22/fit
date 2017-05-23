<div class="related-articles">
    <div class="related-header related-articles__header">
        <div class="related-header__title">Полезно<span>\ знать</span>
        </div><a class="related-header__listing-link" href="{{ route('articles') }}">Читать все статьи<i class="sprite_main sprite_main-icon__arrow_black_to_right"></i></a>
    </div>
    <div class="articles articles_related related-articles__articles">
        @foreach($articles as $article)
            <div class="article-preview article-preview_related articles__article">
                <a class="article-preview__image" href="{{ route('article', $article->sysname) }}"><img src="{{ $article->uploads->img->preview->url() }}"/></a>
                <div class="article-preview__title">{{ $article->name }}</div>
                <div class="article-preview__preview-text">{{ $article->descr }}</div>
                <a class="btn btn_read-full" href="{{ route('article', $article->sysname) }}">Читать</a>
            </div>
        @endforeach
    </div>
</div>
