<!DOCTYPE html>
<html lang="ru">
<head>
    {!! Meta::render() !!}
    <meta name="viewport" content="width=device-width,initial-scale=1">

    @include('blocks.favicons')
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
</head>
<body>
    <div class="main-wrap">
        @include('blocks.header.index')

        <div class="main-section">
            <div class="inner-wrap group">
                <main class="main-content">
                    @section('breadcrumbs')
                    @show

                    @yield('content')
                </main>
            </div>
        </div>

        @include('blocks.footer.index')
        @include('blocks.modals')

        {{-- UNUSED --}}
        {{--        @include('blocks.buttons')--}}
        {{--        @include('blocks.header.fixed')--}}
    </div>
    <div class="hidden" id="loader">
        <div class="loader-bg"></div>
        <div class="loader"></div>
    </div>
    <script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>
