@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('look_book') !!}
@stop

@section('content')
    <main>
        <div class="container">
           {{-- <aside class="sidebar">
                @widget('BannerLeftWidget')
            </aside>
            <section class="content">--}}

                <div class="container-in">
                    <div class="header-listing">
                        <h1>Подобрать Look</h1>
                    </div>
                    @foreach($books as $book)
                        @if($book->looks->count())
                            <div class="look-book">
                                <div class="look-book__title">
                                    <span>{{ strstr($book->name, ' ', true) }}</span> <span>\</span> <span>{{ strstr($book->name, ' ') }}</span>
                                </div>
                                @include('looks.carousel', ['looks' => $book->looks])
                            </div>
                        @endif
                    @endforeach
                </div>

            {{--</section>--}}
        </div>
    </main>
@stop