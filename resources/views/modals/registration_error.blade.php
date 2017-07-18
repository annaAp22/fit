<div id="registration-error-form" class="form-type-1 modal-box quick-order-cart" style="display: inline-block;">
    <div class="form-modal">
        <div class="form-modal_title-2">Не удалось создать кабинет!</div>
        <div class="form-modal_line">
        </div>
        @if(isset($messages))
            @foreach($messages as $item)
                <div class="field-caption-1">{{$item}}</div>
            @endforeach
        @else
            <div class="field-caption-1">Неизвестная ошибка</div>
        @endif
    </div>
    <button data-fancybox-close  class="modal-close">&#10006;</button>
</div>