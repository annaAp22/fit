@php
$clothes = [
    'Майки и футболки',
    'Шорты мужские',
    'Штаны мужские',
    'Лосины мужские',
    'Рашгарды',
];
@endphp

@extends('catalog.main_category')

@section('categories')
    
    <div class="cat_man container-in">
        @foreach($clothes as $key => $name)
            @php $cat = $categories[0]->children->where('name', $name)->first(); @endphp
            @if($cat)
                <a class="cat_man__item cat_man__item_0{{ $key }}" href="{{ route('catalog', ['sysname' => $cat->sysname]) }}">
                    <div class="cat_man__item__img hover-zoom-dark">
                        <img src="{{ $cat->uploads->img->url() }}" alt="{{ $cat->name }}">
                    </div>
                    <div class="cat_man__item__text">
                        <div class="cat_man__item-title">
                            <div>{{ $cat->name }}</div>
                            <button class="btn btn_white btn_white_hover-black main-benefits-banner__link">Смотреть
                                <i class="sprite_main sprite_main-button-arrow-right-black"></i>
                                <i class="sprite_main sprite_main-button-arrow-right-white"></i>
                            </button>
                        </div>
                        <div class="cat_man__item-preview">{{ $cat->text_preview }}</div>
                    </div>
                </a>
            @endif
        @endforeach
    </div>

@stop