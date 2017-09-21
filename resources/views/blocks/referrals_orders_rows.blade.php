<tr>
    <th>
        <div class="name">Имя</div>
    </th>
    <th>Дата оформления</th>
    <th class="count-col">Товаров</th>
    <th>Цена с доставкой</th>
    <th>Статус</th>
    <th></th>
</tr>
@for($i = 0, $j = $lastOrderCount; $i < count($orders); $i++, $j++, $odd = $odd ^ 1)
    @php
        $order = $orders[$i];
        $products = $order->products;
    @endphp
    <tr class="{{$odd?'odd':'even'}}" data-id="{{$j}}">
        <td>{{$order->name}}</td>
        <td>{{$order->created_at->format('d.m.Y')}}</td>
        <td class="count-col">{{$products->sum('pivot.cnt')}}</td>
        <td class="price">{{$order->totalWithDelivery}} ₽</td>
        <td class="status">{{$order->statusName()}}</td>
        <td class="more-col">
            <div class="more js-toggle-active js-open-order">
                <span class="text close">Подробнеe</span>
                <span class="text open">Скрыть</span>
                <span class="close">
                            <span class="sprite_main sprite_main-icon__arrow_green_down"></span>
                        </span>
                <span class="open">
                            <span class="sprite_main sprite_main-icon__arrow_green_up"></span>
                        </span>
            </div>
        </td>
    </tr>
    <tr class="orders-table_goods-line">
        <td colspan="6"></td>
    </tr>
    @foreach($products as $product)
        <tr class="orders-table_good" data-id="{{$j}}">
            <td colspan="2" class="name-col">
                            <span class="name">
                                <a href="{{route('product', $product->sysname)}}">{{$product->name}}</a>
                            </span>
            </td>
            <td class="count-col">{{$product->pivot->cnt}}</td>
            <td class="price">{{$product->discount_price}} ₽</td>
            <td></td>
        </tr>
    @endforeach
    <tr class="orders-table_goods-line"></tr>
@endfor
