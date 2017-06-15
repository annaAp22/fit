<html>

<head></head>
<body>
Здравствуйте!<br>
<br>
Спасибо за заказ на сайте <a href="{{Request::root()}}"><b>fit2u</b>></a><br>
В ближайшее время наши специалисты свяжутся с вами.<br><br>
Товары, которые вас заинтересовали:<br>
<table border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th></th>
        <th>название</th>
        <th>цена</th>
    </tr>
    @foreach($order->products as $product)
        <tr>
            <td><img src="{{Request::root().$product->uploads->img->preview->url()}}" alt=""></td>
            <td><a href="{{route('product', ['sysname' => $product->sysname])}}">{{$product->name}}</a></td>
            <td>{{$product->price - $product->discount}} р.</td>
        </tr>
    @endforeach
</table>


</body>