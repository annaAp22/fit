@extends('layouts.main')

@section('breadcrumbs')
    @if( isset($tag) )
        {!!  Breadcrumbs::render('articles.tag.article', $tag, $page) !!}
    @else
        {!!  Breadcrumbs::render('article', $page) !!}
    @endif
@endsection

@section('content')
    <main>
        <div class="container">
            @include('blocks.aside')
            <section class="content">
                <div class="container-in">
                    <div class="header-listing">
                        <h1>{{ $page->name }}</h1>

                        <!-- Back to shopping link-->
                        {{--<a class="btn btn_back-link" href="#" onclick="location.href = document.referrer;">--}}
                        {{--<span class="icon-fade">--}}
                            {{--<i class="sprite_main sprite_main-icon-arrow-small-left-gray normal"></i>--}}
                            {{--<i class="sprite_main sprite_main-icon-arrow-small-left-green_active active"></i>--}}
                            {{--<span>Назад к покупкам</span>--}}
                        {{--</span>--}}
                        {{--</a>--}}
                    </div>

                    <div class="page-text article-detailed">
                        <div class="article-detailed__image">
                            <img class="page-text__image" src="{{ $page->uploads->img->preview->url() }}" alt="{{ $page->name }}">

                            <!-- Share-->
                            <div class="share article-detailed__share">
                                <span>Поделиться:</span>
                                <a class="share__link" href="http://www.facebook.com/sharer.php?u={{ route('news', $page->sysname) }}" target="_blank">
                                    <i class="sprite_main sprite_main-product_social-facebook"></i>
                                </a>
                                <div class="share__separator">|</div>
                                <!-- Additional params: &title=, &description=, &image=-->
                                <a class="share__link" href="http://vk.com/share.php?url={{ route('news', $page->sysname) }}&title={{ $page->name }}&description={{ $page->description }}&image={{ $page->uploads->img->url() }}" target="_blank">
                                    <i class="sprite_main sprite_main-product_social-vk"></i>
                                </a>
                                <div class="share__separator">|</div>
                                <!-- Additional params: &text=, &via=<Twitter_account_link>-->
                                <a class="share__link" href="http://twitter.com/share?url={{ route('news', $page->sysname )}}&text={{ $page->name}}" target="_blank">
                                    <i class="sprite_main sprite_main-product_social-twitter"></i>
                                </a>
                                <div class="share__separator">|</div>
                                <!-- Additional params: &title=, &description=, &imageUrl=-->
                                <a class="share__link" href="https://connect.ok.ru/offer?url={{ route('news', $page->sysname )}}&title={{ $page->name}}&description={{ $page->description }}&imageUrl={{ $page->uploads->img->url() }}" target="_blank">
                                    <i class="sprite_main sprite_main-product_social-odnoklasniki"></i>
                                </a>
                            </div>
                        </div>
                        <div class="article-detailed__text">
                            {!! $page->text !!}
                        </div>
                    </div>

                    <div class="related-articles related-articles_content">
                        <h3>Ещё рецепты:</h3>
                        <div class="articles articles__recipes js-container-article">
                            @include('articles.list')
                        </div>
                    </div>

                </div>
            </section>
            <section class="content-full-width">
                @widget('SubscribeWidget')
            </section>
        </div>
    </main>
@endsection