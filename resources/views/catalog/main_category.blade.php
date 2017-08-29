@extends('layouts.main')

@section('breadcrumbs')
    {!! Breadcrumbs::render('catalog', $category) !!}
@stop

@section('content')
    <main>
        <div class="container">

            <section class="content-full-width main-category">
                {{-- Header --}}
                <div class="container-in">
                    <div class="header-listing">
                        <h1>{{ $category->name }}</h1>
                    </div>
                </div>


                @yield('categories')

                @widget('InstagramWidget')

                @widget('SubscribeWidget')
            </section>
        </div>
    </main>
@stop