<!--
    -->
@extends('content.with_sidebar')
@section('page_name')
    <h1>{{$page->name}}</h1>
@endsection
@section('other_content')
    <div class="recipe-item_full">
        <div class="img-wrapper">
            <img class="img" src="{{$page->uploads->img->small->url()}}" alt="">
            <!-- Share-->
            <div class="share article-detailed__share">
                <span>Поделиться:</span>
                <a class="share__link" href="http://www.facebook.com/sharer.php?u={{ route('articles.record', $page->sysname) }}" target="_blank">
                    <i class="sprite_main sprite_main-product_social-facebook"></i>
                </a>
                <div class="share__separator">|</div>
                <!-- Additional params: &title=, &description=, &image=-->
                <a class="share__link" href="http://vk.com/share.php?url={{ route('articles.record', $page->sysname) }}&title={{ $page->name }}&description={{ $page->description }}&image={{ $page->uploads->img->url() }}" target="_blank">
                    <i class="sprite_main sprite_main-product_social-vk"></i>
                </a>
                <div class="share__separator">|</div>
                <!-- Additional params: &text=, &via=<Twitter_account_link>-->
                <a class="share__link" href="http://twitter.com/share?url={{ route('articles.record', $page->sysname )}}&text={{ $page->name}}" target="_blank">
                    <i class="sprite_main sprite_main-product_social-twitter"></i>
                </a>
                <div class="share__separator">|</div>
                <!-- Additional params: &title=, &description=, &imageUrl=-->
                <a class="share__link" href="https://connect.ok.ru/offer?url={{ route('articles.record', $page->sysname )}}&title={{ $page->name}}&description={{ $page->description }}&imageUrl={{ $page->uploads->img->url() }}" target="_blank">
                    <i class="sprite_main sprite_main-product_social-odnoklasniki"></i>
                </a>
            </div>
        </div>
        <div class="wrapper">
            <div class="caption">Ингредиенты</div>
            <div class="recipe-item_text">
                {!! $page->text !!}
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <h3>Ещё рецепты:</h3>
    <div id="recipes">
        <div class="recipe-items js-container-article">
            @include('articles.list')
        </div>
    </div>
@endsection