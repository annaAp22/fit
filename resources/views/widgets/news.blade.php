@if($news->count())
<div class="container">
    <!-- Related articles-->
    <div class="related-articles main-news">
        <div class="related-header related-articles__header">
            <div class="related-header__title">Свежие<span>\ новости</span>
            </div><a class="related-header__listing-link" href="{{ route('news') }}">Читать все новости<i class="sprite_main sprite_main-icon__arrow_black_to_right"></i></a>
        </div>
        <div class="articles related-articles__articles">
            @include('news.list')
        </div>
    </div>
</div>
@endif