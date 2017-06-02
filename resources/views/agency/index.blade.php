@extends('layouts.main')


@section('content')
    <main class="container">
        <div class="container">
            <aside class="sidebar">
                @widget('TagsWidget')
                @widget('BannerLeftWidget')
            </aside>
            <section class="content">
                <div class="agencies">
                    <h1>Универсальный подарок - сертификат на яркую одежду</h1>
                    <p>Ищете одежду для фитнеса Profit в вашем городе? Смотрите список наших представителей и выбирайте ближайшее.</p>
                    <div class="subtitle">Наша одежда в твоем городе:</div>
                    <!-- Map -->
                    <div id="agencies-map" class="agencies-map"></div>
                    <div class="agencies__cities">
                        <a href="#">Артём, Приморский край</a>
                        <a href="#">Брянск</a>
                        <a href="#">Беларусь, Минск</a>
                        <a href="#">Благовещенск</a>
                        <a href="#">Владивосток</a>
                        <a href="#">Дедовск, МО</a>
                        <a href="#">Екатеринбург</a>
                        <a href="#">Италия, Милан</a>
                        <a href="#">Казахстан, Атырау</a>
                        <a href="#">Кулебаки, Нижегородской области</a>
                        <a href="#">Казахстан, Петропавловск</a>
                        <a href="#">Киргизия, Бишкек</a>
                        <a href="#">Новосибирск</a>
                        <a href="#">Москва</a>
                        <a href="#">Омск</a>
                        <a href="#">Ростов-на-Дону</a>
                        <a href="#">Санкт-Петербург</a>
                        <a href="#">Смоленск</a>
                        <a href="#">Томск</a>
                        <a href="#">Тверь</a>
                        <a href="#">Тольятти</a>
                        <a href="#">Тюмень</a>
                        <a href="#">Чита</a>
                        <a href="#">Хабаровск</a>
                        <a href="#">Южно-Сахалинск</a>
                    </div>
                </div>
            </section>
            <section class="content-full-width">
                @widget('SubscribeWidget')
            </section>
        </div>
    </main>
@endsection