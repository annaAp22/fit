@extends('layouts.main')
{{--@section('breadcrumbs')--}}
    {{--{!!  Breadcrumbs::render('index') !!}--}}
{{--@stop--}}

@section('content')
<main role="main">
  <!-- Main slider-->
    @if($banners->count())
        <div class="main-slider js-single-banner">
            <div class="main-slider__wrap">
                <div class="main-slider__track">
                    @foreach($banners as $banner)
                        <a class="main-slider__item" @if($banner->url)href="{{ $banner->url }}"@endif>
                            <img class="main-slider__banner main-slider__banner_md-up" src="{{ $banner->uploads->img->lg->url() }}" alt="" role="presentation"/>
                            <img class="main-slider__banner main-slider__banner_sm" src="{{ $banner->uploads->img->sm->url() }}" alt="" role="presentation"/>
                        </a>
                    @endforeach
                </div>
            </div>
            <button class="btn btn_main-slider-control left"><i class="sprite_main sprite_main-slider-arrow-circle-white-left"></i>
            </button>
            <button class="btn btn_main-slider-control right"><i class="sprite_main sprite_main-slider-arrow-circle-white-left"></i>
            </button>
        </div>
    @endif

  <!-- Made from fiber-->
  <div class="colored-bg colored-bg_f3f3f3">
    <div class="container">
      <!-- Main categories-->
      <div class="main-benefits container-in">
        <!-- Sale category -->
        <a class="main-benefits-banner-green" href="{{ route('actions', ['sysname' => 'woman']) }}">
          <span class="main-benefits-banner-green__title">Скидки</span>
          <span class="main-benefits-banner-green__text"><span>до</span>
            <span>60 %</span></span>
          <span class="main-benefits-banner-green__link"><span>Смотреть</span><i class="sprite_main sprite_main-button-arrow-right-white"></i></span>
        </a>
        <!-- Women category -->
        @php
          $women = $categories->where('name', 'Для женщин')->first();
          $men = $categories->where('name', 'Для мужчин')->first();
        @endphp
        <a class="main-benefits-banner main-benefits-banner_light-gray" href="{{ route('catalog', ['sysname' => $women->sysname]) }}">
          <img class="main-benefits-banner__image" src="{{ $women->uploads->img_main->original->url() }}" alt="" role="presentation"/>
          <span class="main-benefits-banner__caption"><span class="main-benefits-banner__title">Для неё</span>
            <span class="main-benefits-banner__text">Яркая, стильная и удобная спортивная одежда</span>
            <button class="btn btn_white main-benefits-banner__link">Смотреть<i class="sprite_main sprite_main-button-arrow-right-black"></i></button>
          </span>
        </a>
        <!-- Men category -->
        <a class="main-benefits-banner main-benefits-banner_dark-gray" href="{{ route('catalog', ['sysname' => $men->sysname]) }}">
          <img class="main-benefits-banner__image" src="{{ $men->uploads->img_main->original->url() }}" alt="" role="presentation"/>
          <span class="main-benefits-banner__caption">
            <span class="main-benefits-banner__title">Для него</span>
            <span class="main-benefits-banner__text">Износостойкая, брендовая спортивная одежда</span>
            <button class="btn btn_white main-benefits-banner__link">Смотреть<i class="sprite_main sprite_main-button-arrow-right-black"></i></button>
          </span>
        </a>
      </div>
      @include('blocks.best-clothes')
    </div>
  </div>
  <!-- Video and Quality-->
  <div class="container">
    <!-- Video and Quality banner-->
    {{--<div class="video-and-quality container-in">--}}
      {{--<!-- YouTube video-->--}}
        {{--<a data-fancybox class="youtube-video" href="//www.youtube.com/embed/LVDh_iL8YdM?autoplay=1">--}}
            {{--<span class="youtube-video__play"></span>--}}
            {{--<img class="youtube-video__image" src="/img/main-video-min.jpg" alt="" role="presentation"/>--}}
            {{--<span class="youtube-video__title youtube-video__title_top-left-white">Всё, что нужно занать о нашей спортивной одежде</span>--}}
        {{--</a>--}}

      {{--<!-- Quality--><a class="main-quality" href="{{ route('article', ['sysname' => 'iz_chego_shem']) }}"><img class="main-quality__image" src="/img/main-made-super-quality-min.jpg" alt="" role="presentation"/><span class="main-quality__caption"><span class="main-quality__title">Супер Качество</span><span class="main-quality__text">Ткань имеет свойства растягиваться в обоих направлениях. Выводит пот через ткань наружу, оставляя кожу сухой и теплой. Устойчив к зацепкам и закатыванию.</span>--}}
      {{--<button class="btn btn_white">Подробнее<i class="sprite_main sprite_main-button-arrow-right-black"></i>--}}
      {{--</button></span></a>--}}
    {{--</div>--}}
    <!-- Style and Design-->
    {{--<div class="style-and-design container-in">--}}
      {{--<div class="style-and-design__col"><img class="style-and-design__image" src="/img/main-made-style-min.jpg" alt="" role="presentation"/>--}}
      {{--</div>--}}
      {{--<div class="style-and-design__col">--}}
        {{--<div class="style-and-design__border">--}}
          {{--<div class="style-and-design__title"><i class="sprite_main sprite_main-main-style-leaf-gray"></i><span>Стиль и европейский дизайн</span><i class="sprite_main sprite_main-main-style-leaf-gray"></i>--}}
          {{--</div>--}}
          {{--<div class="style-and-design__text">Лучшие дизайнеры спортивной одежды работали над тем, чтобы Вы выглядели стильно и современно. Наша спортивная одежда изготавливается в соответствии с самыми свежими трендами спортивной моды--}}
          {{--</div><a class="btn btn_deep-yellow" href="{{ route('catalog', ['sysname' => $women->sysname]) }}">Перейти в каталог<i class="sprite_main sprite_main-button-arrow-right-black"></i></a>--}}
        {{--</div>--}}
      {{--</div>--}}
    {{--</div>--}}
  </div>
  <!-- Main find size-->
  <div class="colored-bg colored-bg_f3f3f3 colored-bg_mt">
    <div class="container">
      <!-- Main benefits-->
      <div class="main-find-size container-in">
        <div class="main-find-size__col">
          <div class="main-find-size__title"><i class="sprite_main sprite_main-main-size-ruller"></i>
            <div><span>Подбор размера по вашим сантиметрам</span><span>Поможем подобрать Вам одежду. Посоветуем лучший вариант. Размер будет точно Вам впору!</span></div>
          </div>
          <div class="main-find-size-white-block">
            <div class="main-find-size-white-block__image-wrapper">
              <img class="" src="/img/main-size-girl-opened-mouth-min.jpg" alt="" role="presentation"/>
            </div>

            <div class="main-find-size-white-block__caption">
              <div class="main-find-size-white-block__title">Всё равно не подошла одежда?
              </div>
              <div class="main-find-size-white-block__text">Не растраивайтесь! Смело возвращайте товар назад. Примем без проблем
              </div>
              <a class="btn btn_green-border-900 js-action-link" href="#" data-url="{{route('ajax.modal')}}" data-modal="callback">Подобрать одежду</a>
            </div>
          </div>
        </div>
        <div class="main-find-size__col"><img class="main-find-size__image" src="/img/main-size-girl-without-head-min.jpg" alt="" role="presentation"/>
        </div>
      </div>
    </div>
  </div>

  @widget('news')

  @widget('recipesWidget')
</main>
@endsection
