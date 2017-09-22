@if(Auth::check() && (Route::currentRouteName() == 'room' || Route::currentRouteName() == 'orders-history'))
<div class="sidebar_navigation">
    <div class="title">Навигация</div>
    <div class="item @if(Route::currentRouteName() == 'room'){{'active'}}@endif">
        <span class="icon sprite_main sprite_main-icon-arrow-green-left_active"></span>
        <a href="{{route('room')}}">МОИ ДАННЫE</a>
    </div>
    <div class="item @if(isset($active) && $active == 'orders_history'){{'active'}}@endif">
        <span class="icon sprite_main sprite_main-icon-arrow-green-left_active"></span>
        <a href="{{route('orders-history')}}">Мои заказы</a>
        @if(false)
        <div class="cloud">
            <span class="id">{{$orders_count?:0}}</span>
            <div class="corner"></div>
        </div>
        @endif
    </div>
    @if(Auth::user()->partner)
    <div class="item @if(isset($active) && $active == 'referrals_orders_history'){{'active'}}@endif">
        <span class="icon sprite_main sprite_main-icon-arrow-green-left_active"></span>
        <a href="{{route('orders-history', ['referrals' => 1])}}">Заказы реферралов</a>
        @if(false)
            <div class="cloud">
                <span class="id">{{$orders_count?:0}}</span>
                <div class="corner"></div>
            </div>
        @endif
    </div>
    @endif
</div>
@endif