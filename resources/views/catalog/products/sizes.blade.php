<!-- Sizes-->
<div class="size-filter{{ isset($class) ? $class : '' }} square-filter js-square-check-filter js-square-check-single">
    <!-- Popup sizes-->
    <div class="popup-notice popup-notice_size js-popup-size">
        <div class="popup-notice__triangle">▼
        </div><i class="sprite_main sprite_main-icon__popup_info"></i>
        <div class="popup-notice__text">Выберите Ваш размер!
        </div>
    </div>
    @foreach($sizes as $size)
        @if(isset($openSizes) && !in_array($size, $openSizes))
            <div class="size-filter__size js-square missing" title="Этого размера нет в наличии, но Вы можете оформить предзаказ"><span>{{ $size }}</span>
                <input type="hidden" name="size" value="{{ $size }}" disabled>
            </div>
        @else
            <div class="size-filter__size js-square"><span>{{ $size }}</span>
                <input type="hidden" name="size" value="{{ $size }}" disabled>
            </div>
        @endif
    @endforeach
</div>