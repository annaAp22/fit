@extends('layouts.main')

@section('content')
    <main class="container">
        @include('blocks.aside')
        <section class="content">
            <h1>Сброс пароля</h1>
            <form name="reset-password-form" class="js-form-ajax form-type-1" action="{{ url('/password/reset') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-modal_line">
                    <div class="field-caption-1">Email:</div>
                    <div class="field-wrapper-1">
                        <div class="icon sprite_main-green-letter sprite_main"></div>
                        <input type="text" name="email" placeholder="Электронный адрес" class="js-required-fields" value="{{$email}}"/>
                    </div>
                </div>
                <div class="form-modal_line">
                    <div class="field-caption-1">Новый пароль:</div>
                    <div class="field-wrapper-1">
                        <div class="icon sprite_main-green-lock sprite_main"></div>
                        <input type="password" name="password" class="js-required-fields"/>
                    </div>
                </div>
                <div class="form-modal_line">
                    <div class="field-caption-1">Введите пароль ещё раз:</div>
                    <div class="field-wrapper-1">
                        <div class="icon sprite_main-green-lock sprite_main"></div>
                        <input type="password" name="password_confirmation" class="js-required-fields"/>
                    </div>
                </div>
                <div class="form-modal_line">
                    <button class="btn btn_yellow-3">Сохранить изменения
                        <i class="sprite_main sprite_main-orange-arrow-2"></i>
                    </button>
                </div>
            </form>
            <form name="home" action="/"></form>
        </section>
    </main>
@endsection
