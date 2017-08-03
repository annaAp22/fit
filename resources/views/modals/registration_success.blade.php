<div id="registration-success-form" class="form-type-1 modal-box quick-order-cart" style="display: inline-block;">
    <form class="js-form-ajax" action="{{ route('ajax.callback') }}" method="POST">
        <div class="form-modal">
            {{ csrf_field() }}
            <div class="form-modal_title-2">Ваш кабинет успешно создан!</div>
            <div class="form-modal_line">
                <div class="field-caption-1">Теперь Вы можете следить за статусом своих заказов, первым узнавать о скидках, новинках и прочее. </div>
            </div>
            {{--<div class="form-modal_line">--}}
                {{--<button data-fancybox-close class="btn btn_yellow-3">Перейти к покупкам&nbsp;--}}
                    {{--<i class="sprite_main sprite_main-orange-arrow-2"></i>--}}
                {{--</button>--}}
            {{--</div>--}}
            @section('list-marker-1')
                <div class="sprite_main sprite_main-green-checked"></div>
            @endsection
            <div class="total-list-1-header">Создав личный кабинет Вы получайте ряд преимуществ в магазине Fit2U</div>
            <ul class="total-list-1">
                <li>
                    @yield('list-marker-1')
                    <span>Получи доступ к специальным ценам и выгодным предложениям</span></li>
                <li>
                    @yield('list-marker-1')
                    <span>Управляй своим профилем и настраивай подписку</span></li>
                <li>
                    @yield('list-marker-1')
                    <span>Владей историей своих заказов и оформляй их быстрее</span></li>
            </ul>
        </div>
    </form>
    <button data-fancybox-close  class="modal-close" onclick="location.reload()">&#10006;</button>
</div>