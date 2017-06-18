@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('news') !!}
@endsection

@section('content')
    <main>
        <div class="container">
            <aside class="sidebar">
                @widget('TagsWidget')
                @widget('BannerLeftWidget')
            </aside>
            <section class="content">
                <div class="container-in">
                    <div class="header-listing">

                       <h1>Свежие новости магазина</h1>

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
                        <div class="articles articles_news related-articles__articles js-container-news">
                            @include('news.list')
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if($news->currentPage() < $news->lastPage())
                        <div class="page-navigation js-pagination-news">
                            <button class="btn btn_more js-action-link"
                                    data-url="{{route('ajax.news')}}"
                                    data-page="{{$news->currentPage() + 1}}">
                                <span class="text">Показать больше</span>
                                <span class="count js-items-count">({{ $news->total() - ($news->currentPage() * $news->perPage()) }})</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                            <button class="btn btn_show-all js-action-link"
                                    data-url="{{route('ajax.news')}}"
                                    data-page="1">
                                <span>Показать все новости</span>
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
