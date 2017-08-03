@extends('layouts.main')
@section('content')
    <main class="container">
        <div class="container">
            <aside class="sidebar">
                @widget('BannerLeftWidget')
            </aside>
            <section class="content">
                <div class="agencies js-agencies">
                    <h1>Купить фитнес одежду Profit в городе {{$city->title}}</h1>
                    @foreach($city->shops as $item)
                        <input class="js-lat" type="hidden" value="{{$item->lat}}">
                        <input class="js-long" type="hidden" value="{{$item->long}}">
                        <div class="page-text__title_700 page-text__title_h2">{{$item->title}}</div>
                        <p class="js-address">Адрес: {{$item->address}}</p>
                        <p>Телефоны: {{$item->phone}}</p>
                        <p>Email: <a href="mailto:{{$item->email}}">{{$item->email}}</a></p>
                        <p>Ссылки на магазин:</p>
                        @foreach($item->links() as $link)
                            <a href="{{$link}}">{{$link}}</a>
                        @endforeach
                    @endforeach
                    <!-- Map -->
                    @if(count($city->shops))
                        <div id="agencies-map" class="agencies-map" data-zoom="{{$city->zoom}}"></div>
                    @endif
                    <div class="agencies__cities">
                    </div>
                </div>
            </section>
            <section class="content-full-width">
                @widget('SubscribeWidget')
            </section>
        </div>
    </main>
@endsection