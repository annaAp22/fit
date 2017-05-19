@extends('layouts.main')

@section('breadcrumbs')
    {!!  Breadcrumbs::render('bookmarks') !!}
@stop

@section('content')
    <main>
        <div class="container">
            {{-- SideBar --}}
            @include('content.aside')

        </div>
    </main>
@stop