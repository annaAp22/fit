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
                        <a class="btn btn_back-link" href="#" onclick="location.href = document.referrer;">
                            <span class="icon-fade">
                                <i class="sprite_main sprite_main-icon-arrow-small-left-gray normal"></i>
                                <i class="sprite_main sprite_main-icon-arrow-small-left-green_active active"></i>
                                <span>Назад к покупкам</span>
                            </span>
                        </a>
                    </div>

                    <div class="related-articles related-articles_content">
                        <div class="articles articles_news related-articles__articles">
                            @foreach($news as $article)
                                <div class="article-preview article-preview_news articles__article">
                                    <a class="article-preview__image" href="{{ route('news.record', ['sysname' => $article->sysname]) }}"><img src="{{ $article->uploads->img->preview->url() }}"/></a>
                                    <div class="article-preview__title">{{ $article->name }}</div>
                                    <div class="article-preview__preview-text">{{ App\Helpers\russianDate($article->date) }}</div>
                                    <a class="btn btn_read-full" href="{{ route('news.record', $article->sysname) }}">Смотреть</a>
                                </div>
                            @endforeach
                        </div>
                    </div>



                    {{-- Pagination --}}
                    {{--@if($products->currentPage() < $products->lastPage())
                        <div class="page-navigation">
                            <button class="btn btn_more js-pagination" data-all="false">
                                <span class="text">Показать больше</span>
                                <span class="count js-goods-count">({{ $products->total() - ($products->currentPage() * $products->perPage()) }})</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                            <button class="btn btn_show-all js-pagination" data-all="true">
                                <span>Показать все товары</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                        </div>
                    @endif--}}

                </div>
            </section>
            <section class="content-full-width">
                @widget('SubscribeWidget')
            </section>
        </div>
    </main>
@endsection
