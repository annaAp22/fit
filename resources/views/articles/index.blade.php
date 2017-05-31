@extends('content.with_sidebar')
@section('page_name')
    <h1>{{$page->name}}</h1>
@endsection
@section('other_content')
    <div id="recipes">
        <div class="recipe-items js-container-article">
            @include('articles.list')
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
@endsection