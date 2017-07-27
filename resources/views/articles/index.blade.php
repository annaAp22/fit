@extends('layouts.main')

@section('breadcrumbs')
    @if( isset($tag) )
        {!!  Breadcrumbs::render('articles.tag', $tag) !!}
    @else
        {!!  Breadcrumbs::render('articles') !!}
    @endif
@endsection

@section('content')
    <main>
        <div class="container">
            @include('blocks.aside')
            <section class="content">
                <div class="container-in">
                    <div class="header-listing">

                        <h1>{{ isset($tag) ? $tag->name : 'Статьи' }}</h1>

                        <!-- Back to shopping link-->
                        {{--<a class="btn btn_back-link" href="#" onclick="location.href = document.referrer;">--}}
                            {{--<span class="icon-fade">--}}
                                {{--<i class="sprite_main sprite_main-icon-arrow-small-left-gray normal"></i>--}}
                                {{--<i class="sprite_main sprite_main-icon-arrow-small-left-green_active active"></i>--}}
                                {{--<span>Назад к покупкам</span>--}}
                            {{--</span>--}}
                        {{--</a>--}}
                    </div>

                    <div class="related-articles related-articles_content">
                        <div class="articles articles__recipes js-container-article">
                            @include('articles.list')
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if($articles->currentPage() < $articles->lastPage())
                        <div class="page-navigation js-pagination-article">
                            <button class="btn btn_more js-action-link"
                                    data-url="{{route('ajax.articles')}}"
                                    data-page="{{$articles->currentPage() + 1}}">
                                <span class="text">Показать больше</span>
                                <span class="count js-items-count">({{ $articles->total() - ($articles->currentPage() * $articles->perPage()) }})</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                            <button class="btn btn_show-all js-action-link"
                                    data-url="{{route('ajax.articles')}}"
                                    data-page="1">
                                <span>Показать все рецепты</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                        </div>
                    @endif

                </div>
            </section>
            <section class="content-full-width">
                @widget('SubscribeWidget')
            </section>
        </div>
    </main>
@endsection