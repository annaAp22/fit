@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('photos') !!}
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

                       <h1>Фотографии клиентов</h1>
                        
                    </div>

                    <div class="related-articles related-articles_content">
                        <div class="articles articles_news related-articles__articles js-container-photos">
                            @include('photos.list')
                        </div>
                    </div>

                    {{-- Pagination --}}
                    @if($photos->currentPage() < $photos->lastPage())
                        <div class="page-navigation js-pagination-photos">
                            <button class="btn btn_more js-action-link"
                                    data-url="{{route('ajax.photos')}}"
                                    data-page="{{$photos->currentPage() + 1}}">
                                <span class="text">Показать больше</span>
                                <span class="count js-items-count">({{ $photos->total() - ($photos->currentPage() * $photos->perPage()) }})</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                            </button>
                            <button class="btn btn_show-all js-action-link"
                                    data-url="{{route('ajax.photos')}}"
                                    data-page="1">
                                <span>Показать все фотографии</span>
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
