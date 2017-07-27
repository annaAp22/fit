@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('reviews') !!}
@endsection

@section('content')
    <main class="container">
        <div class="wrap-block-title">
            <h1 class="catalog-title">
                Отзывы наших клиентов:
            </h1>
        </div>
        {{--<div class="reviews">
            @foreach($reviews as $review)
                @include('reviews.review', ['review' => $review])
            @endforeach
        </div>
        <div class="page-navigation">
            @include('vendor.pagination.default', ['paginator' => $reviews])
            <button class="btn_orange pull-right add-review-button" data-product-id="0">ОСТАВИТЬ СВОЙ ОТЗЫВ</button>
        </div>--}}

        <div id="js-vk_reviews"></div>

        <section class="content-full-width">
            @widget('SubscribeWidget')
        </section>
    </main>

@endsection
