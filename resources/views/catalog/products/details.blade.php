@extends('layouts.main')

{{--@php session()->flush(); @endphp--}}
@section('breadcrumbs')
    {!!  Breadcrumbs::render('product', $product) !!}
@endsection

@section('content')
    <main class="container container_block" role="main">
        <section class="container-in product-detailed">
            <!-- H1 title md down-->
            <h1 class="product-detailed__title product-detailed__title_md-down">
                {{ $product->name }}
            </h1>

            <!-- Article md down-->
            <div class="product-detailed__subtitle product-detailed__art product-detailed__art_md-down">
                Артикул: {{ $product->sku }}
            </div>

            <!-- Image gallery-->
            <div class="product-gallery product-detailed__gallery" id="js-product-gallery">
                <div class="product-gallery__navigation" id="js-product-gallery-nav">
                    <button class="btn btn_carousel-control">
                        <i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
                    </button>
                    <div class="product-gallery__wrap">
                        <div class="product-gallery__track">
                            <div class="product-gallery__thumb active js-gallery-thumb">
                                <img src="{{ $product->uploads->img->modal->url() }}" alt="{{ $product->name }}" role="presentation"/>
                            </div>
                            @if($product->video_url)
                                <div class="product-gallery__thumb product-gallery__thumb_video js-gallery-thumb"
                                     style="background-image: url('https://img.youtube.com/vi/{{ $product->video_code }}/1.jpg')">
                                    <div class="product-gallery__thumb_video__play"></div>
                                </div>
                            @endif
                            @foreach($product->photos as $i => $photo)
                                <div class="product-gallery__thumb js-gallery-thumb">
                                    <img src="{{ $photo->uploads->img->modal->url() }}" alt="{{ $product->name }}" role="presentation"/>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button class="btn btn_carousel-control down">
                        <i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
                    </button>
                </div>
                <div class="product-gallery__image-wrap">
                    <a class="product-gallery__image-link active js-gallery-big" data-fancybox="group" href="{{ $product->uploads->img->url() }}">
                        <img class="product-gallery__image" src="{{ $product->uploads->img->detail->url() }}" alt="{{ $product->name }}" role="presentation"/>
                    </a>

                    @if($product->video_url)
                        <a class="product-gallery__image-link js-gallery-big js-gallery-thumb" data-fancybox="group" href="{{ $product->video_url }}">
                            <img class="product-gallery__video" src="https://img.youtube.com/vi/{{ $product->video_code }}/maxresdefault.jpg" alt="">
                            <div class="product-gallery__thumb_video__play"></div>
                        </a>
                    @endif

                    @foreach($product->photos as $i => $photo)
                        <a class="product-gallery__image-link js-gallery-big" data-fancybox="group" href="{{ $photo->uploads->img->url() }}">
                            <img class="product-gallery__image" src="{{ $photo->uploads->img->detail->url() }}" alt="{{ $product->name }}" role="presentation"/>
                        </a>
                    @endforeach

                    @include('catalog.products.labels')

                    <!-- Add to wishlist-->
                    <span class="wishlist js-hover-notice js-toggle-active js-action-link{{ in_array($product->id, $defer) ? ' active' : '' }}"
                          data-url="{{ route('ajax.product.defer', ['id' => $product->id]) }}">
                        <span class="icon-fade">
                            <i class="sprite_main sprite_main-product__wishlist normal"></i>
                            <i class="sprite_main sprite_main-product__wishlist_active active"></i>
                        </span>
                        <i class="sprite_main sprite_main-product__wishlist_done done"></i>
                        <!-- Popup-->
                        <span class="popup-notice popup-notice_wishlist">
                            <span class="popup-notice__triangle">▼</span>
                            <i class="sprite_main sprite_main-icon__popup_info"></i>
                            <span class="popup-notice__text">Добавить товар в закладки</span>
                            <span class="popup-notice__text done">Товар добавлен в закладки!</span>
                        </span>
                    </span>
                </div>
            </div>

            <div class="product-detailed__details">
                <div class="container-in">
                    <!-- H1 title-->
                    <h1 class="product-detailed__title product-detailed__title_lg-up">
                        {{ $product->name }}
                    </h1>
                    <form class="product-detailed__column js-form-ajax" action="{{ route('ajax.cart.add', ['id' => $product->id, 'cnt' => 1]) }}" method="post">
                        {{ csrf_field() }}

                        <!-- Article-->
                        <div class="product-detailed__subtitle product-detailed__art product-detailed__art_lg-up">
                            Артикул: {{ $product->sku }}
                        </div>

                        <!-- Price-->
                        <div class="product-detailed__price product__price">
                            @if($product->originalPrice)
                                <i class="sprite_main sprite_main-product__old-price_detailed old-price">
                                    <span>{{ number_format($product->originalPrice, 0, '.', ' ') }} ₽</span>
                                </i>
                            @endif
                            <span class="current">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                        </div>

                        @include('catalog.products.rating')
                        @if(count($product->sizes))
                            <!-- Size-->
                            <div class="product-detailed__subtitle product-detailed__subtitle_size">Выберите свой размер:</div>
                            @include('catalog.products.sizes', ['class' => ' product-detailed__size'])
                        @else
                                <span>&nbsp;</span>
                                <input type="hidden" name="size" value="0">
                        @endif

                            {{-- How to choose size modal --}}
                            @if($product->getSex() == 'Мужской')
                                <a class="btn btn_more product-detailed__btn product-detailed__btn product-detailed__btn_size js-action-link" data-url="{{route('ajax.modal')}}" data-modal="sizes_men">
                                    <i class="sprite_main sprite_main-icon__popup_info"></i>
                                    <span>Как подобрать размер?</span>
                                </a>
                            @else
                                <a class="btn btn_more product-detailed__btn product-detailed__btn product-detailed__btn_size js-action-link" data-url="{{route('ajax.modal')}}" data-modal="sizes_women">
                                    <i class="sprite_main sprite_main-icon__popup_info"></i>
                                    <span>Как подобрать размер?</span>
                                </a>
                            @endif

                        <!-- Buy-->
                        <button class="btn btn_green product__buy product-detailed__btn product-detailed__btn product-detailed__btn_buy js-add-to-cart{{ session()->has('products.cart.'. $product->id) ? ' active' : '' }}"  onclick="document.getElementById('is_fast').value = 0;">
                            <span class="put">
                                <i class="sprite_main sprite_main-product__basket"></i>
                                <span>В корзину</span>
                            </span>
                            <a class="done" href="{{ route('cart') }}">
                                <i class="sprite_main sprite_main-product__basket_done"></i>
                                <span>Добавлено</span>
                            </a>
                        </button>

                        <!-- Quick buy-->
                        <button id="quick-buy-btn" name="is_fast" value="1" class="btn btn_orange-border product-detailed__btn product-detailed__btn product-detailed__btn_quick js-add-to-cart" onclick="document.getElementById('is_fast').value = 1;">Купить в 1 клик</button>
                        <input id="is_fast" type="hidden" name="is_fast" value="0">

                        <!-- Share-->
                        <div class="share product-detailed__share">
                            <span>Поделиться:</span>
                            <a class="share__link" href="http://www.facebook.com/sharer.php?u={{ route('product', $product->sysname) }}" target="_blank">
                                <i class="sprite_main sprite_main-product_social-facebook"></i>
                            </a>
                            <div class="share__separator">|</div>
                            <!-- Additional params: &title=, &description=, &image=-->
                            <a class="share__link" href="http://vk.com/share.php?url={{ route('product', $product->sysname) }}&title={{ $product->name }}&description={{ $product->description }}&image={{ $product->uploads->img->url() }}" target="_blank">
                                <i class="sprite_main sprite_main-product_social-vk"></i>
                            </a>
                            <div class="share__separator">|</div>
                            <!-- Additional params: &text=, &via=<Twitter_account_link>-->
                            <a class="share__link" href="http://twitter.com/share?url={{ route('product', $product->sysname )}}&text={{ $product->name}}" target="_blank">
                                <i class="sprite_main sprite_main-product_social-twitter"></i>
                            </a>
                            <div class="share__separator">|</div>
                            <!-- Additional params: &title=, &description=, &imageUrl=-->
                            <a class="share__link" href="https://connect.ok.ru/offer?url={{ route('product', $product->sysname )}}&title={{ $product->name}}&description={{ $product->description }}&imageUrl={{ $product->uploads->img->url() }}" target="_blank">
                                <i class="sprite_main sprite_main-product_social-odnoklasniki"></i>
                            </a>
                        </div>
                    </form>


                    <div class="product-detailed__column">
                        <!-- Description-->
                        <div class="product-detailed__subtitle product-detailed__subtitle_des">Характеристики:</div>
                        <div class="description-scroll product-detailed__description">
                            <div class="description-scroll__body">
                                <!-- Color-->
                                @php
                                    $main_color = $product->attributes->where('name', 'Основной цвет')->first();
                                    $sub_color = $product->attributes->where('name', 'Цвет вставок')->first();
                                    $country_of_origin = $product->attributes->where('name', 'Страна производства')->first();
                                @endphp
                                <div class="description-scroll__color-wrap">
                                    @if($main_color)
                                        <span class="description-scroll__param-title description-scroll__param-title_strong">
                                            <span>Цвет основы:</span>
                                            <span class="description-scroll__color" style="background-color:{{$main_color->pivot->value}};" title="{{ $main_color->color }}"></span>
                                        </span>
                                    @endif
                                    @if($sub_color)
                                        <span class="description-scroll__param-title description-scroll__param-title_strong">
                                            <span>Цвет вставок:</span>
                                            <span class="description-scroll__color" style="background-color:{{$sub_color->pivot->value}};" title="{{ $sub_color->color }}"></span>
                                        </span>
                                    @endif
                                </div>
                                <!-- Brand-->
                                @isset($product->brand)
                                    <div class="description-scroll__param-title description-scroll__param-title_strong description-scroll__mt">
                                        <span>Торговая марка:</span>
                                        <span class="description-scroll__param-value">{{ $product->brand->name }}</span>
                                    </div>
                                @endisset
                                <!-- Country of origin-->
                                @if($country_of_origin)
                                <div class="description-scroll__param-title description-scroll__mt"><strong>Страна производства:</strong><span class="description-scroll__param-value">{{$country_of_origin->pivot->value}}</span>
                                </div>
                                @endif
                            <!-- Sex-->
                                @if(isset($product->sex))
                                    <div class="description-scroll__param-title description-scroll__mt"><strong>Пол:</strong><span class="description-scroll__param-value">{{$product->sex}}</span>
                                    </div>
                                @endif
                            <!-- Material-->
                                @if(isset($product->material))
                                    <div class="description-scroll__param-title description-scroll__mt"><strong>Состав ткани:</strong><span class="description-scroll__param-value">{{$product->material}}</span>
                                    </div>
                                    <a href="https://fit2u.ru/page/iz_chego_shem">Подробнее о тканях</a>
                                @endif
                                <!-- Other description-->
                                {{--<div class="description-scroll__param-value description-scroll__mt">--}}
                                    {{--{!! $product->text !!}--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        <!-- Warranty-->
                        <div class="product-warranty product-detailed__warranty">
                            <div class="product-warranty__item">
                                <i class="sprite_main sprite_main-product_warranty-thumb-up"></i>
                                <div class="product-warranty__text">
                                    <div class="product-warranty__title">Гарантия качества на 1000 тренировок
                                    </div>
                                    <div class="product-warranty__note">Дорогая итальянская ткань, прочные немецкие нитки
                                    </div>
                                </div>
                            </div>
                            <div class="product-warranty__item">
                                <i class="sprite_main sprite_main-product_ruller"></i>
                                <div class="product-warranty__text">
                                    <div class="product-warranty__title">Подбор размера по Вашим сантиметрам
                                    </div>
                                    <div class="product-warranty__note">Размер будет точно Вам в пору!
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delivery-->
                        <div class="product-delivery product-detailed__delivery js-toggle-active">
                            <div class="product-delivery__wrap"><i class="sprite_main sprite_main-product_delivery-truck"></i>
                                <div class="product-delivery__title">Доставка:
                                </div>
                                <div class="product-delivery__note">{{ $user_city == 'Москва' ? 'Уже завтра!' : 'Почтой' }}
                                </div><i class="sprite_main sprite_main-icon_arrow_gray_up"></i>
                            </div>
                            <div class="product-delivery__hidden">
                                <div class="product-delivery__city js-product-delivery__city"><span>Ваш город:</span><span class="">{{ $user_city }}<i class="sprite_main sprite_main-icon__arrow_green_up"></i></span>
                                </div>
                                @if( $user_city == 'Москва' )
                                    <div class="product-delivery__cost"><span>Стоимость:</span><span>Курьером: от 300 руб.<br/>При заказе от 6 000 руб<br/>Бесплатно</span></div>
                                @else
                                    <div class="product-delivery__cost"><span>Стоимость:</span><span> от 300 руб.</span></div>
                                @endif
                                <div class="product-delivery__link"><a href="{{ route('delivery') }}">Подробнее о доставке по России</a>
                                </div>
                                <div class="product-delivery__store"><i class="sprite_main sprite_main-header__city_point"></i><a href="{{ route('contacts') }}">Магазин в Москве</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @include('catalog.products.tabs.kits')

            @include('catalog.products.tabs.reviews')

        </section>

        <section class="content-full-width">

            {{--<!-- Seo text-->
            <div class="page-text">
                <div class="page-text__title page-text__title_h2 page-text__title_700">Плавные линии в сочетании с уверенными и яркими формами
                </div>
                <p class="page-text__text">
                    Каждый веб-разработчик знает, что такое текст-«рыба». Текст этот, несмотря на название, не имеет никакого отношения к обитателям водоемов. Используется он веб-дизайнерами для вставки на интернет-страницы и демонстрации внешнего вида контента, просмотра шрифтов, абзацев, отступов и т.д. Так как цель применения такого текста исключительно демонстрационная, то и смысловую нагрузку ему нести совсем необязательно. Более того, нечитабельность текста сыграет на руку при оценке качества восприятия макета.
                </p>
                <div class="page-text__title page-text__title_h2 page-text__title_700">Подчеркнет Ваше превосходство
                </div>
                <p class="page-text__text">Каждый веб-разработчик знает, что такое текст-«рыба». Текст этот, несмотря на название, не имеет никакого отношения к обитателям водоемов. Используется он веб-дизайнерами для вставки на интернет-страницы и демонстрации внешнего вида контента, просмотра шрифтов, абзацев, отступов и т.д. Так как цель применения такого текста исключительно демонстрационная, то и смысловую нагрузку ему нести совсем необязательно. Более того, нечитабельность текста сыграет на руку при оценке качества восприятия макета.
                </p><a class="youtube-video" href="#"><span class="youtube-video__play"></span><img class="youtube-video__image" src="/img/product-video2-min.jpg" alt="" role="presentation"/><span class="youtube-video__title youtube-video__title_top-left-white">Всё, что нужно занать о нашей спортивной одежде</span></a>
            </div>--}}

            @widget('InstagramWidget')

            @widget('ViewProductsWidget', ['product_id' => $product->id])
            
            @include('looks.product_detailed')

            @widget('SimilarProductsWidget', ['product' => $product])
            @widget('SubscribeWidget')
        </section>
    </main>
@endsection
