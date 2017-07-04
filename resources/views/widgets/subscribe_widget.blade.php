<!-- Subscription-->
<div class="subscription">
    <div class="subscription__image"><img src="/img/subscription_girl-min.png"/></div>
    <form class="subscription__form js-form-ajax" action="{{route('ajax.subscribe')}}" method="post" enctype="application/x-www-form-urlencoded">
        {{ csrf_field() }}
        <div class="subscription__header">
            <div class="subscription__percent-icon">%</div>
            <div class="subscription__title">Подпишитесь получите скидки до 60%!</div>
        </div>
        <div class="subscription__text">
            Подпишитесь на нашу рассылку и будьте первым, кто получит самые свежие новости  скидках, новинках и выгодных предложениях!
        </div>
        <div class="subscription__input-group"><input class="subscription__input" type="text" name="email" placeholder="Ваша электронная почта..."/>
            <button onclick="fbq('track', 'Lead'); return true;" class="btn btn_green" type="submit"><span>Получить скидку</span></button>
        </div>
    </form>
</div>
