<!DOCTYPE html>
<html lang="ru">
<head>
    {!! Meta::render() !!}
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="shortcut icon" href="/img/favicon/favicon.ico" type="image/x-icon"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">

</head>
<body>
<div class="main-wrap">
    @include('modals.sizes')
</div>
<div class="hidden" id="loader">
    <div class="loader-bg"></div>
    <div class="loader"></div>
</div>
<script src="{{ elixir('js/app.js') }}"></script>
</body>
</html>
