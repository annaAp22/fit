@extends('layouts.main')
@section('content')
<main class="container">
    @include('blocks.aside')
    <section class="content">
            <!-- Header -->
            <div class="header-room">
                <h1>Мой кабинет</h1>
                <div class="icon sprite_main sprite_main-header__enter"></div>
                <div class="name">{{$user->name}}</div>
                <div class="id-wrapper">
                    <span class="text">Мой id:</span>
                    <span class="id">{{$user->id}}</span>
                    <div class="corner"></div>
                </div>
            </div>
            <form id="user-info-form" action="{{route('ajax.user-update')}}" class="form-type-2 js-form-ajax" method="POST">
                <fieldset class="fieldset-2">
                    <div class="caption">ИЗМЕНИТЬ ЛИЧНУЮ ИНФОРМАЦИЮ</div>
                    <div class="left-side">
                        <div class="field-caption-2">Хочу изменить имя и фамилию:</div>
                        <div class="field-wrapper-1">
                            <div class="icon sprite_main-green-letter sprite_main"></div>
                            <input type="text" name="name" placeholder="Coolgirl_yamakasy@gmail.com" class="js-required-fields" value="{{$user->name}}"/>
                        </div>
                        <div class="field-caption-2">Хочу поменять телефон:</div>
                        <div class="field-wrapper-1">
                            <div class="icon sprite_main-green-phone sprite_main"></div>
                            <input type="text" name="phone" placeholder="+7 964 561 23 45" class="js-phone js-required-fields" value="{{$user->phone?:''}}"/>
                        </div>
                        <div class="field-caption-2">Хочу изменить электронную почту:</div>
                        <div class="field-wrapper-1">
                            <div class="icon sprite_main-green-letter sprite_main"></div>
                            <input type="text" name="email" value="{{$user->email}}" class="js-required-fields"/>
                        </div>
                        <div class="grid margin-top-40">
                            <div class="hidden-sm-down btn-wrapper">
                                <div class="form-modal_line">
                                    <button class="btn btn_yellow-3">Сохранить изменения</button>
                                </div>
                                <div class="form-secure-2"><i class="form-secure__icon sprite sprite_main sprite sprite_main-form-secure-lock-gray"></i>
                                    <div>
                                        <div class="form-secure__title">Ваши данные защищены
                                        </div>
                                        <div class="form-secure__text">Магазин Fit2U дает абсолютную гарантию на полную конфеденциальность Вашей личной информации
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right-side">
                        <div class="field-caption-2">Хочу изменить пароль(или использовать текущий):</div>
                        <div class="field-wrapper-1">
                            <div class="icon sprite_main-green-lock sprite_main"></div>
                            <input type="password" name="password" class="js-required-fields" placeholder="Пароль" value=""/>
                        </div>
                        <div class="field-caption-2">Ввести пароль ещё раз:</div>
                        <div class="field-wrapper-1">
                            <div class="icon sprite_main-green-lock sprite_main"></div>
                            <input type="password" name="password_confirmation" class="js-required-fields" placeholder="Пароль"/>
                        </div>
                        <div class="field-caption-2">Мой День рождения:</div>
                        <div class="field-wrapper-1 inline-fields">
                            <div class="sorting-select type-2 day js-toggle-active js-select">
                                <input class="js-value" type="hidden" name="day" value="1"/>
                                <span class="js-selected">{{isset($user->birthday)?$user->birthday->day:1}}</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                                <div class="sorting-select__dropdown scroll">
                                    @for($i = 1; $i < 32; $i++)
                                        <div class="sorting-select__option js-option">{{$i}}</div>
                                    @endfor
                                </div>
                            </div>
                            @php
                                $m = $global_settings['month_arr'];
                            @endphp
                            <div class="sorting-select type-2 month js-toggle-active js-select">
                                <input class="js-value" type="hidden" name="month" value="{{$m_index = isset($user->birthday)?$user->birthday->month:1}}"/>
                                <span class="js-selected">{{trans('times.'.$m[$m_index - 1])}}</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                                <div class="sorting-select__dropdown scroll">
                                    @for($i = 0; $i < count($m); $i++)
                                        <div class="sorting-select__option js-option" data-val="{{$i+1}}">{{trans('times.'.$m[$i])}}</div>
                                    @endfor
                                </div>
                            </div>
                            <div class="sorting-select type-2 year js-toggle-active js-select">
                                @php
                                    $current_year = date('Y');
                                @endphp
                                <input class="js-value" type="hidden" name="year" value="{{$y_index = isset($user->birthday)?$user->birthday->year:$current_year}}"/>
                                <span class="js-selected">{{$y_index}}</span>
                                <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
                                <div class="sorting-select__dropdown  scroll">
                                    @for($i = $current_year; $i >  - 100; $i--)
                                        <div class="sorting-select__option js-option">{{$i}}</div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="form-modal_line margin-top-40">
                            <label class="radio radio_box-2">
                                <div>
                                    <input class="hidden-input" name="subscription" value="1" type="checkbox" checked>
                                    <span class="fake-input">
                                        <span></span>
                                    </span>
                                </div>
                                <span class="label">Я хочу получать новости о новинках, скидках, акциях и рейтинговых товарах</span>
                            </label>
                        </div>
                    </div>
                    <div class="grid margin-top-40">
                        <div class="hidden-md-up">
                            <div class="form-modal_line">
                                <button class="btn btn_yellow-3">Сохранить изменения</button>
                            </div>
                            <div class="form-secure-2"><i class="form-secure__icon sprite sprite_main sprite sprite_main-form-secure-lock-gray"></i>
                                <div>
                                    <div class="form-secure__title">Ваши данные защищены
                                    </div>
                                    <div class="form-secure__text">Магазин Fit2U дает абсолютную гарантию на полную конфеденциальность Вашей личной информации
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </fieldset>
            </form>
    </section>

    <section class="content-full-width">
        @widget('SubscribeWidget')
    </section>
</main>
@endsection