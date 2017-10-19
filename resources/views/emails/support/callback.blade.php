<html>

<head></head>
<body>
Здравствуйте!<br>
<br>
На сайте {{Request::root()}} запросили обратный звонок:<br>
Имя:{{$callback->name}}<br>
Телефон:{{$callback->phone}}
</body>