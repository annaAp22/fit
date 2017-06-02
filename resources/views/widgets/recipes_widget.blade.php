@if($articles->count())
<div class="container">
    <!-- Main recipes-->
    <div class="main-recipes">
        <div class="related-header related-header_xs main-recipes__header">
            <div class="related-header__title">Вкусные<span>\ рецепты</span>
            </div><a class="related-header__listing-link" href="{{ route('tags', $tag->sysname) }}">Читать все статьи<i class="sprite_main sprite_main-icon__arrow_black_to_right"></i></a>
        </div>
        <div class="recipe-articles main-recipes__articles">
            @foreach( $articles as $article )
                <div class="recipe-article-preview recipe-articles__article">
                    <a class="recipe-article-preview__image" href="{{ route('tag.article', ['tag_sysname' => $tag->sysname, 'sysname' => $article->sysname]) }}">
                        <img src="{{ $article->uploads->img->middle->url() }}"/>
                    </a>
                    <div class="recipe-article-preview__caption">
                        <div class="recipe-article-preview__title">{{ $article->name }}
                        </div>
                        <div class="recipe-article-preview__preview-text">{{ $article->descr }}
                        </div>
                    </div>
                </div>
            @endforeach
            {{--<div class="recipe-article-preview recipe-articles__article"><a class="recipe-article-preview__image" href="#"><img src="/img/main-recipe-2-min.jpg"/></a>
                <div class="recipe-article-preview__caption">
                    <div class="recipe-article-preview__title">Тирамису
                    </div>
                    <div class="recipe-article-preview__preview-text">Идеально подойдёт для завтрака или перекуса!
                    </div>
                </div>
            </div>
            <div class="recipe-article-preview recipe-articles__article"><a class="recipe-article-preview__image" href="#"><img src="/img/main-recipe-3-min.jpg"/></a>
                <div class="recipe-article-preview__caption">
                    <div class="recipe-article-preview__title">Курица в кефире
                    </div>
                    <div class="recipe-article-preview__preview-text">Идеально подойдёт для завтрака или перекуса!
                    </div>
                </div>
            </div>
            <div class="recipe-article-preview recipe-articles__article"><a class="recipe-article-preview__image" href="#"><img src="/img/main-recipe-4-min.jpg"/></a>
                <div class="recipe-article-preview__caption">
                    <div class="recipe-article-preview__title">Диетический десерт
                    </div>
                    <div class="recipe-article-preview__preview-text">Идеально подойдёт для завтрака или перекуса!
                    </div>
                </div>
            </div>
            <div class="recipe-article-preview recipe-articles__article"><a class="recipe-article-preview__image" href="#"><img src="/img/main-recipe-5-min.jpg"/></a>
                <div class="recipe-article-preview__caption">
                    <div class="recipe-article-preview__title">Очень вкусный салат
                    </div>
                    <div class="recipe-article-preview__preview-text">Идеально подойдёт для завтрака или перекуса!
                    </div>
                </div>
            </div>--}}
                <a class="related-header__listing-link sm" href="#">Читать все статьи<i class="sprite_main sprite_main-icon__arrow_black_to_right"></i></a>
        </div>
    </div>
</div>
@endif