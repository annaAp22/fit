@extends('layouts.main')
@section('breadcrumbs')
    {!!  Breadcrumbs::render('cooperation') !!}
@stop
@section('content')
<!-- верхняя часть страницы с формой и негритянкой -->
<div class="container">
    <div class="header-listing">
        <h1>Оптовикам</h1>
    </div>
    <div class="cooperation__header">
        <form name="cooperation_form_1" action="{{route('ajax.cooperation')}}" class="cooperation-form js-form-ajax js-cooperation" method="post">
            <div class="caption">
                <span class="square"><span>Пр</span></span>одавать Profit вместе – путь<br>к успеху вашей компании!
            </div>
            <div class="text">
                Оставьте заявку и сотрудничайте напрямую с брендом Profit.
            </div>
            <input type="hidden" name="type" value="">
            <input type="text" name="name" placeholder="Ваше имя" class="js-required-fields">
            <input type="text" name="phone" placeholder="Ваш номер телефона" class="js-required-fields">
            <input type="text" name="email" placeholder="E-mail" class="js-required-fields">
            <button class="btn btn_orange-border" type="submit">Узнать условия</button>
        </form>
        <div class="right">
            <div class="cooperation__phone">{{$global_settings['cooperation_phone']->value}}</div>
            <div class="cooperation__work-time">{{$global_settings['cooperation_work']->value}}</div>
        </div>
    </div>
</div>
{!! $page->content !!}
<!-- нижняя форма сотрудничества -->
<div class="cooperation__footer">
    <form name="cooperation_form_2" action="{{route('ajax.cooperation')}}" class="cooperation-form-wide js-form-ajax js-cooperation" method="post">
        <div class="caption">
            Оставьте заявку
        </div>
        <div class="text">и начните сотрудничать с брендом Profit</div>
        <div class="wrapper">
            <input type="hidden" name="type" value="">
            <input type="text" name="name" placeholder="Ваше имя" class="js-required-fields">
            <input type="text" name="phone" placeholder="Ваш номер телефона" class="js-required-fields">
            <input type="text" name="email" placeholder="E-mail" class="js-required-fields">
        </div>
        <button class="btn btn_sky-border-900">Узнать условия</button>
    </form>
</div>
@include('scripts.form_protection')
@endsection