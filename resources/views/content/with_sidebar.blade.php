@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('page', $page) !!}
@endsection

@section('content')
    <main class="container">
        <div class="container">
            <aside class="sidebar">
                @widget('TagsWidget')
                @widget('BannerLeftWidget')
            </aside>
            <section class="content">
                    @yield('page_name')
                    {!! $page->content !!}
                    @yield('other_content')
            </section>

            <section class="content-full-width">
                @widget('SubscribeWidget')
            </section>
        </div>
    </main>
@endsection