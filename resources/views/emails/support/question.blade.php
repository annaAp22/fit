<html>

<head></head>
<body>
Здравствуйте!<br>
<br>
Пришел вопрос с сайта {{Request::root()}} <br>
от пользователя: <br>
@if(isset($name))
    Имя:{{$name}}.<br>
@endif
@if(isset($email))
    E-mail:{{$email}}.<br>
@endif
@if(isset($phone))
    Телефон:{{$phone}}.<br>
@endif
<br>
Вопрос: <br>
{!! nl2br(strip_tags($text)) !!}
</body>