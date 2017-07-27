@if($ordersRemained > 0)
    <form class="page-navigation js-form-ajax" action="{{route('ajax.orders-history')}}">
        {{ csrf_field() }}
        <input type="hidden" name="odd" value="{{$odd}}">
        <input type="hidden" name="page" value="{{$page?$page + 1:2}}">
        <button class="btn btn_more" type="submit">
            <span class="text">Показать больше</span>
            <span class="count">(<span class="js-orders-remaining">{{$ordersRemained}}</span>)</span>
            <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
        </button>
        <input type="hidden" name="perPage">
        <button class="btn btn_show-all" name="showAll" type="submit" value="all" onclick="this.form.perPage.value = this.value">
            <span>Показать все заказы</span>
            <i class="sprite_main sprite_main-icon__arrow_green_down"></i>
        </button>
    </form>
@endif