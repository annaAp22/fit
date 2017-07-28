@for($i = 0, $j = $lastOrderCount; $i < count($orders); $i++, $j++, $odd = $odd ^ 1)
    @php
        $order = $orders[$i];
        $products = $order->products;
    @endphp
    <tr class="{{$odd?'odd':'even'}}" data-id="{{$j}}">
        <td>{{$order->id}}</td>
        <td>{{$order->created_at->format('d.m.Y')}}</td>
        <td class="count-col">{{$products->sum('pivot.cnt')}}</td>
        <td class="price">{{$products->sum('pivot.price') + $order->delivery_price}} ₽</td>
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
            <td class="price">{{$product->pivot->price}} ₽</td>
            <td class="status">
                @if($product->status == 1)
                    <form class="js-form-ajax" action="{{ route('ajax.cart.add', ['id' => $product->id, 'cnt' => $product->pivot->cnt]) }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="size" value="{{$product->size}}">
                        <button class="btn btn_green-border-small">ПОВТОРИТЬ ЗАКАЗ</button>
                    </form>
                @else
                    Нет на складе
                @endif
            </td>
            <td></td>
        </tr>
    @endforeach
    <tr class="orders-table_goods-line"></tr>
@endfor
