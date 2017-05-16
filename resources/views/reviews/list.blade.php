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
            <button class="btn-orange pull-right add-review-button" data-product-id="0">ОСТАВИТЬ СВОЙ ОТЗЫВ</button>
        </div>

        <div class="reviews">
            @foreach($reviews as $review)
                <div class="product-review">
                    <div class="product-review_data">
                        <div>{{ $review->name }}</div>
                        <time>{{ \App\Helpers\russianShortDate($review->created_at) }}</time>
                    </div>
                    <div class="product-review_text">
                        <p class="mod-bold">Текст отзыва</p>
                        <p>{{ $review->message }}</p>
                    </div>
                </div>
            @endforeach
        </div>


        <div class="page-navigation">
            @include('vendor.pagination.default', ['paginator' => $reviews])

            <button class="btn-orange pull-right add-review-button" data-product-id="0">ОСТАВИТЬ СВОЙ ОТЗЫВ</button>
        </div>

        <section class="content-full-width">
            @widget('SubscribeWidget')
        </section>
    </main>

@endsection
