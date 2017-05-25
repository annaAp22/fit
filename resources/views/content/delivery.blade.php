@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('page', $page) !!}
@endsection

@section('content')
    <main class="container">
        <div class="container">
            <aside class="sidebar">
                @widget('TagsWidget')
                @widget('BannerLeftWidget')
            </aside>
            <section class="content">
                <div class="container-in delivery">

                    <!-- Header -->
                    <div class="header-listing">
                        <h1>Доставка и оплата <span>Москва (до МКАД!!!)</span></h1>
                        <!-- Back to shopping link-->
                        <a class="btn btn_back-link" href="#" onclick="location.href = document.referrer;">
                            <span class="icon-fade">
                                <i class="sprite_main sprite_main-icon-arrow-small-left-gray normal"></i>
                                <i class="sprite_main sprite_main-icon-arrow-small-left-green_active active"></i>
                                <span>Назад к покупкам</span>
                            </span>
                        </a>
                    </div>

                    <div class="delivery__image">
                        <img src="{{ asset('assets/uploads/delivery-courier-min.jpg') }}" alt="">
                    </div>
                    <div class="delivery__courier">
                        <div class="page-text__title_h2 page-text__title_700">Курьер по Москве 300 руб.</div>
                        <div class="delivery__text">
                            Оплата курьера является отдельной услугой и не зависит от вашего заказа.
                            Наши курьеры подождут до 15 минут и Вы сможете примерить заказанные вещи.
                            <br><br>
                            <strong>Способы оплаты:</strong>  наличными курьеру.
                            <br>
                            Другие города России и зарубежья.
                        </div>
                        <div class="delivery__yellow-border">
                            <strong>Доставка бесплатно</strong> <span>по Мск от 6000 руб.</span>
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