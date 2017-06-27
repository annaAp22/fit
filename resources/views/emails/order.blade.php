<html>

<head>
</head>
<body>
<p>Здравствуйте!</p>
@if(isset($quick_buy) && $quick_buy === 1)
    На сайте <a href="{{Request::root()}}"><b>fit2u</b>></a> произведен быстрый заказ:
@else
    На сайте <a href="{{Request::root()}}"><b>fit2u</b></a> произведен заказ:
@endif
<br>
Имя: <b>{{$order->name}}</b><br>
Email: <b>{{$order->email}}</b><br>
Телефон: <b>{{$order->phone}}</b><br>
<a href="{{Request::root().'/admin/orders/'.$order->id.'/edit'}}"><b>Ссылка на заказ</b></a><br><br>
<p>Товары:</p>
<table border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th></th>
        <th>название</th>
        <th>размер</th>
        <th>цена</th>
    </tr>
    @foreach($order->products as $product)
        <tr>
            <td><img src="{{Request::root().$product->uploads->img->preview->url()}}" alt=""></td>
            <td><a href="{{route('product', ['sysname' => $product->sysname])}}">{{$product->name}}</a></td>
            <td align="center">{{$order->getSizeByProduct($product)}}</td>
            <td>{{$product->price - $product->discount}} р.</td>
        </tr>
    @endforeach
</table>


</body>