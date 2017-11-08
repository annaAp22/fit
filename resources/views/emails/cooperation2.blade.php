<html>

<head></head>
<body>
Здравствуйте!<br>
<br>
На сайте {{Request::root()}} оставили заявку на сотрудничество:<br>
Имя:{{$order->name}}<br>
e-mail:{{$order->email}}<br>
Номер телефона:{{$order->phone}}
</body>