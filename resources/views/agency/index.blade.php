@extends('layouts.main')
@section('content')
    <main class="container">
        <div class="container">
            @include('blocks.aside')
            <section class="content">
                <div class="agencies js-agencies">
                    <h1>Купить фитнес одежду Profit в другом городе</h1>
                    <p>Ищете одежду для фитнеса Profit в вашем городе? Смотрите список наших представителей и выбирайте ближайшее.</p>
                    <div class="subtitle">Наша одежда в твоем городе:</div>
                    <!-- Map -->
                    <div id="agencies-map" class="agencies-map" data-long="77.0720816" data-lat="55.748517"  data-zoom="3"></div>
                    <div class="agencies__cities">
                        @foreach($cities as $item)
                            <a href="{{route('agencies.details', ['sysname' => $item->sysname])}}">{{$item->title}}</a>
                            @foreach($item->shops as $shop)
                                <input class="js-lat" type="hidden" value="{{$shop->lat}}">
                                <input class="js-long" type="hidden" value="{{$shop->long}}">
                                <input class="js-address" type="hidden" value="{{$shop->address}}">
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </section>
            <section class="content-full-width">
                @widget('SubscribeWidget')
            </section>
        </div>
    </main>
@endsection