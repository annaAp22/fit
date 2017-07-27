<html>

<head>
</head>
<body>
<p>Здравствуйте!</p>
@if(isset($quick_buy) && $quick_buy === 1)
    На сайте <a href="{{Request::root()}}"><b>fit2u</b>></a> произведен быстрый заказ <b>№ {{$order->id}}</b>:
@else
    На сайте <a href="{{Request::root()}}"><b>fit2u</b></a> произведен заказ <b>№ {{$order->id}}</b>:
@endif
<br>
Имя: <b>{{$order->name}}</b><br>
Email: <b>{{$order->email}}</b><br>
Телефон: <b>{{$order->phone}}</b><br>
@if($order->delivery_id)
Способ доставки: <b>{{$order->delivery->name}}</b><br>
Стоимость доставки: <b>{{$order->delivery->price}} р.</b><br>
@endif
<a href="{{Request::root().'/admin/orders/'.$order->id.'/edit'}}"><b>Ссылка на заказ</b></a><br><br>
<p>Товары:</p>
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
            <td>{{ $product->price * $product->pivot->cnt }} р.</td>
        </tr>
    @endforeach
</table>
    <p>Общая стоимость(с учетом доставки): <b>{{$order->totalWithDelivery }} р.</b></p>
</body>