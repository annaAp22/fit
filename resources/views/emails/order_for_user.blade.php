<html>

<head></head>
<body style="font-family: Verdana, Geneva, sans-serif;font-size:16px;line-height:28px;">
<a href="{{$siteUrl}}">
    <img src="{{$siteUrl}}/img/header__logo-min.png" alt="fit2u"/>
</a>
<br><br>
Здравствуйте! Благодарим вас за покупку!<br>
Вы оформили заказ в магазине крутой фитнес-одежды на сайте <a style="color:#000;text-decoration:none;" href="{{$siteUrl}}">{{$siteUrl}}</a><br>
<b>Ваш заказ №{{$order->id}}.</b><br>
Сумма заказа с учётом стоимости доставки {{$order->totalWithDelivery}} р.<br>
@if(isset($order->delivery))
    Тип доставки: {{$order->delivery->name}}
@else
    Тип доставки: не указан
@endif
<br><br>
<b>Судя по составу заказа, у вас прекрасный вкус:</b>
<br>
<table cellspacing="0" cellpadding="10" width="100%" style="line-height:16px;border-collapse: collapse;">
    <tr style="color:#a7a7a7;font-size:12px;border-bottom:1px solid #d9d9d9;line-height:24px;white-space:nowrap;">
        <th style="text-align: left;">фото товара:</th>
        <th style="text-align:left;padding-right:8%;width:40%;">название товара:</th>
        <th>цена за шт:</th>
        <th>цена:</th>
    </tr>
    @foreach($order->products as $product)
        <tr style="border-bottom:1px solid #d9d9d9;">
            <td style="padding-left:0;">
                <img src="{{$siteUrl}}/{{$product->uploads->img->cart->url()}}" alt="">
            </td>
            <td style="font-size:14px;text-align:left;">
                <a style="color:#272727;text-decoration:none" href="{{route('product', ['sysname' => $product->sysname])}}">{{$product->name}}</a>
                <span style="color:#ababab"> {{$product->sku}}</span><br>
                Размер: <span style="color:#ababab">{{$order->getSizeByProduct($product)?:'не указан'}}</span>
            </td>
            <td style="font-size:18px;width:24%;" align="center">
                <div style="display:table;width:100%;height:70px;border:1px solid #d6d6d6;border-bottom:0;border-top:0;">
                    <div style="display:table-cell;vertical-align:middle;text-align:center;white-space:nowrap">
                        <b>{{$product->price}} р.</b>
                        @if(isset($product->pivot->cnt) && $product->pivot->cnt > 0)
                            <span style="color:#ababab;font-size:14px; font-weight:400;">x {{$product->pivot->cnt}}</span>
                        @endif
                    </div>
                </div>
            </td>
            <td style="font-size:18px;text-align:center;white-space:nowrap"><b>{{$product->price * $product->pivot->cnt}} р.</b></td>
        </tr>
    @endforeach
</table>
<br><br>
@if(isset($phone))
    Остались вопросы? Мы с удовольствием ответим на них
    <a style="color:#000;text-decoration:none" href="tel:{!! $phone !!}" class="daria-goto-anchor" target="_blank" rel="noopener noreferrer"><b>{!! $phone !!}</b></a>
@endif
</body>