<html>

<head></head>
<body>
Здравствуйте!<br>
<br>
Спасибо за заказ на сайте <a href="{{Request::root()}}"><b>fit2u</b></a><br>
Номер Вашего заказа <b>{{$order->id}}</b><br>
В ближайшее время наши специалисты свяжутся с вами.<br>
@if(isset($phone))
Если возникнут вопросы, можете связаться с нами по телефону {!! $phone !!}<br>
@endif
<br>
Товары, которые Вы заказали:<br>
<table border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th></th>
        <th>название</th>
        <th>размер</th>
        <th>количество</th>
        <th>стоимость</th>
    </tr>
    @foreach($order->products as $product)
        <tr>
            <td><img src="{{Request::root().$product->uploads->img->preview->url()}}" alt=""></td>
            <td><a href="{{route('product', ['sysname' => $product->sysname])}}">{{$product->name}}</a></td>
            <td align="center">{{$order->getSizeByProduct($product)}}</td>
            <td align="center">{{$product->pivot->cnt}}</td>
            <td>{{$order->getPriceByProduct($product)}} р.</td>
        </tr>
    @endforeach
</table>
<br>
@if($order->delivery_id)
    Способ доставки: <b>{{$order->delivery->name}}</b><br>
    Стоимость доставки: <b>{{$order->delivery->price}} р.</b><br>
    <p>Общая стоимость(с учетом доставки): <b>{{$order->price()}} р.</b></p>
@else
    <p>Общая стоимость: <b>{{$order->price()}} р.</b></p>
@endif
</body>