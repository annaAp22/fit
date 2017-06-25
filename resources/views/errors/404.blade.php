@extends('layouts.main')
@section('content')
    <div class="error-404 container">
        <div class="error-404__wrap">
            <h1 class="h1 error-404__title">Страница не найдена</h1>
            <div class="ps">
                <div class="sprite_main sprite_main-close-2"></div>
                Ошибка 404. Запрашиваемая страница не найдена. Воспользуйтесь поиском по сайту или начните с каталога товаров
            </div>
            <div class="error-404__text">
                <div class="img">
                    <img src="/img/404-min.jpg" alt="">
                </div>
                <a href="{{ route('index') }}" class="btn btn_yellow-2 arrowed">
                    <span>На главную</span>
                    <span class="sprite_main sprite_main-orange-arrow">
                        <span class="sprite_main sprite_main-orange-dark-arrow"></span>
                    </span>
                </a>
            </div>
        </div>
    </div>
@stop
