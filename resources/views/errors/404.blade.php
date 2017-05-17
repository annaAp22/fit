@extends('layouts.main')
@section('content')
    <div class="error-404 container">
        <div class="error-404__wrap">
            <h1 class="h1 error-404__title">Страница не найдена</h1>
            <div class="error-404__text">
                <div class="error-404__404">404</div>
                <a href="{{ route('index') }}" class="btn btn_green">ВЕРНУТЬСЯ НА ГЛАВНУЮ</a>
                <p class="mod-padd-top-13">Не отчаивайтесь. Просто воспользуйтесь поиском по сайту или начните с каталога товаров.</p>

            </div>
        </div>
    </div>
@stop
