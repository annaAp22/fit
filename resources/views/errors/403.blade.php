@extends('layouts.main')
@section('content')
<div class="wrapper main-wrapper two-side">
    <aside class="ls">
        @widget('CatalogWidget')
    </aside>
    <div class="rs wrapper-2">
        <div class="main-content-5">
            <div class="to-prev-page">
                <div class="btn m-24 child v-c ef tr-1">
                    <div class="sprite-icons n-back-3"></div>&nbsp;&nbsp;&nbsp;
                    <span class=""><a style="text-decoration: none;" href="{{URL::previous()}}">Назад</a></span>
                </div>
            </div>
            <div class="bread-crumb child v-c">
                <div class="sprite-icons n-leaf"></div>
                <a href="{{route('index')}}">Главная</a>
                <a>404 Ошибка</a>
            </div>
            <section class="page-404">
                <div class="allocate child v-b">
                    <h1 class="total-c-10">Ошибка 403</h1>&#10;
                </div>
                <div class="block-1">
                    <img src="/img/404.jpg" alt="" width="100%"/>
                </div>
                <div class="error-win">
                    <div class="c">Ошибка</div>
                    <div class="d">Похоже, запрашиваемая страница вам не доступна.<br/> Не беда. Попробуйте воспользоваться поиском или начните просмотр каталога </div>
                    <a class="btn ef tr-1" href="{{route('index')}}">На главную</a>
                </div>
            </section>
        </div>
    </div>
</div>
@stop