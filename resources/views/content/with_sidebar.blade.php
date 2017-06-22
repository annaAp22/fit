@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('page', $page) !!}
@endsection

@section('content')
    <main class="container">
        <aside class="sidebar">
            {{--@widget('TagsWidget')--}}
            @widget('BannerLeftWidget')
        </aside>
        <section class="content">
            {!! $page->content !!}
        </section>

        <section class="content-full-width">
            @widget('SubscribeWidget')
        </section>
    </main>
@endsection