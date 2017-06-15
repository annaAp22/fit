<html>

<head></head>
<body>
Здравствуйте!<br>
<br>
На сайте {{Request::root()}} произведен быстрый заказ:<br>
Имя:{{$order->name}}<br>
Email:{{$order->email}}<br>
Телефон:{{$order->phone}}<br>
Ссылка на заказ: {{Request::root().'/admin/orders/'.$order->id.'/edit'}}<br>
Товары:
<table border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th></th>
        <th>название</th>
        <th>цена</th>
    </tr>
    @foreach($order->products as $product)
        <tr>
            <td><img src="{{Request::root().$product->uploads->img->preview->url()}}" alt=""></td>
            <td>{{$product->name}}</td>
            <td>{{$product->price - $product->discount}} р.</td>
        </tr>
    @endforeach
</table>


</body>