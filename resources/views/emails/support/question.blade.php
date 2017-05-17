<html>

<head></head>
<body>
Здравствуйте!<br>
<br>
Пришел вопрос с сайта {{Request::root()}} от пользователя:<br>
Имя:{{$name}}.<br>
E-mail:{{$email}}.<br>
<br>

{!! nl2br(strip_tags($text)) !!}
</body>