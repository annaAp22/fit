@php
$clothes = [
    'Топики',
    'Комбинезоны',
    'Брюки',
    'Лосины и легинсы',
    'Шорты',
    'Майки и кофты',
    'Купальники и боди',
    'Юбки и платья',
];

$accessories = [
    'Пояса и бандаж',
    'Повязки спортивные',
    'Напульсники и рукава',
    'Гетры',
    'Сумки',
    'Перчатки',
];
@endphp

@extends('catalog.main_category')

@section('categories')
    <div class="cat_woman container-in">

        {{-- Area 1 --}}
        <div class="cat_woman__area_1">
            <div class="container-in">

                @foreach($clothes as $key => $name)
                    @if($key < 3)
                        @php $cat = $categories[0]->children->where('name', $name)->first(); @endphp
                        @if($cat)
                        <a class="cat_woman__item cat_woman__item_0{{ $key }}" href="{{ route('catalog', ['sysname' => $cat->sysname]) }}">
                            <div class="cat_woman__item__text">
                                <div class="cat_woman__item-title"><div>{{ $cat->name }}</div></div>
                                <div class="cat_woman__item-preview">{{ $cat->text_preview }}</div>
                                <button class="btn btn_white btn_white_hover-black main-benefits-banner__link">Смотреть
                                    <i class="sprite_main sprite_main-button-arrow-right-black"></i>
                                    <i class="sprite_main sprite_main-button-arrow-right-white"></i>
                                </button>
                            </div>
                            <div class="cat_woman__item__img">
                                <div>
                                    <img src="{{ $cat->uploads->img->url() }}" alt="{{ $cat->name }}">
                                </div>
                            </div>
                        </a>
                        @endif
                    @endif
                @endforeach

            </div>
        </div>

        @foreach($clothes as $key => $name)
            @if($key > 2)
                @php $cat = $categories[0]->children->where('name', $name)->first(); @endphp
                @if($cat)
                    <a class="cat_woman__item cat_woman__item_0{{ $key }}" href="{{ route('catalog', ['sysname' => $cat->sysname]) }}">
                        <div class="cat_woman__item__text">
                            <div class="cat_woman__item-title"><div>{{ $cat->name }}</div></div>
                            <div class="cat_woman__item-preview">{{ $cat->text_preview }}</div>
                            <button class="btn btn_white btn_white_hover-black main-benefits-banner__link">Смотреть
                                <i class="sprite_main sprite_main-button-arrow-right-black"></i>
                                <i class="sprite_main sprite_main-button-arrow-right-white"></i>
                            </button>
                        </div>
                        <div class="cat_woman__item__img">
                            <div>
                                <img src="{{ $cat->uploads->img->url() }}" alt="{{ $cat->name }}">
                            </div>
                        </div>
                    </a>
                @endif
            @endif
        @endforeach
    </div>

    @if( isset($categories[1]) )


    {{-- Header --}}
    <div class="cat_woman__header page-text__title_700 page-text__title_h1">{{ $categories[1]->name }}</div>

    <div class="cat_woman container-in">
        @foreach($accessories as $key => $name)
                @php $cat = $categories[1]->children->where('name', $name)->first(); @endphp
                @if($cat)
                    <a class="cat_woman__item cat_woman__item_1{{ $key }}" href="{{ route('catalog', ['sysname' => $cat->sysname]) }}">
                        <div class="cat_woman__item__text">
                            <div class="cat_woman__item-title"><div>{{ $cat->name }}</div></div>
                            <div class="cat_woman__item-preview">{{ $cat->text_preview }}</div>
                            <button class="btn btn_white btn_white_hover-black main-benefits-banner__link">Смотреть
                                <i class="sprite_main sprite_main-button-arrow-right-black"></i>
                                <i class="sprite_main sprite_main-button-arrow-right-white"></i>
                            </button>
                        </div>
                        <div class="cat_woman__item__img">
                            <div>
                                <img src="{{ $cat->uploads->img->url() }}" alt="{{ $cat->name }}">
                            </div>
                        </div>
                    </a>
                @endif
        @endforeach
    </div>
    @endif
@stop