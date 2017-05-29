@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('articles', $articles) !!}
@endsection

@section('content')
    <main class="container">
        <div class="page-single">
            <h1 class="page-single_title">{{$page->name}}</h1>
            {!! $page->content !!}

        </div>
        <div class="recipe-item_full">
            <img class="img" src="/img/main-recipe-2-min.jpg" alt="">
            <div class="wrapper">
                <div class="caption">Ингредиенты</div>
                <div class="text">
                    <p>500 г сливочного сыра маскарпоне,</p>
                    <p>4 яйца,</p>
                    <p>сахарная пудра 5 ст. л.,</p>
                    <p>300 мл холодного крепкого эспрессо,</p>
                    <p>1 стакан сладкого вина Marsala (или коньяк, или ром, или Амаретто - только уже не на стаканы, а
                        несколько ложек)</p>
                    <p>200 г готовых савоярди (или "Дамские пальчики" у нас называются). Можно испечь самостоятельно, но
                        не пытайтесь заменить каким-либо другим печеньем. Горький какао-порошок для посыпания или
                        горький чёрный шоколад.</p>
                </div>
            </div>
        </div>
        <div class="recipe-items">
            @for($i = 0; $i < 20; $i++)
                <a href="" class="recipe-item">
                    <div class="img-wrapper">
                        <img class="img" src="/img/main-recipe-2-min.jpg" alt="">
                    </div>
                    <div class="caption">Тирамису</div>
                    <div class="text">Идеально подойдет для завтрака или перекуса!</div>
                </a>
            @endfor
        </div>
        <section class="content-full-width">
            @widget('SubscribeWidget')
        </section>
    </main>
@endsection