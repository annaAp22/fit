<html>
<head></head>
<body style="font-family: Verdana, Geneva, sans-serif;font-size:16px;line-height:28px;">
<a href="{{$global_settings['site_url']}}">
    <img src="{{$global_settings['site_url']}}/img/header__logo-min.png" alt="fit2u"/>
</a>
<br><br>
Здравствуйте! Для сброса пароля на сайте <a style="color:#000;" href="{{$global_settings['site_url']}}">{{$global_settings['site_url']}}</a>  перейдите по ссылке:
<a style="color:#000;font-size:12px;" href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
</body>
</html>
