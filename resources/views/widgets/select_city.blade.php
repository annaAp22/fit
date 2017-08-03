<div class="geo-city js-geo-city-widget">
    <div class="geo-city__top">
        <div class="geo-city__title">Выбор Вашего города:</div>

        <form class="form-search geo-city__search js-prevent" method="POST">
            {{ csrf_field() }}
            <button class="icon-fade" type="submit">
                <i class="sprite_main sprite_main-header__search_active normal"></i>
                <i class="sprite_main sprite_main-header__search active"></i>
            </button>
            <input class="js-geo-city-search" type="search" name="text" placeholder="Найти город..."/>
        </form>

        <div class="modal-close geo-city__close js-toggle-active-target" data-target=".js-geo-city-widget">✖</div>

    </div>

    <div class="geo-city__bottom js-geo-desktop">

        <div class="description-scroll geo-city__select js-geo-level-2">
            <div class="geo-city__select__title">Регион:</div>
            <div class="description-scroll__body geo-city__select__body">
                @if( $active = $regions->where('region', $config['region'])->first())
                    <div class="geo-city__select__item">
                        <span class="active js-toggle-active js-geo-region js-action-link" data-reset=".js-geo-region" data-url="{{ route('ajax.geo_cities') }}" data-region="{{ $active->region }}">{{ $active->region }}</span>
                    </div>
                @endif
                @foreach($regions as $region)
                    @if( !$active or $active->region != $region->region )
                        <div class="geo-city__select__item">
                            <span class="js-toggle-active js-geo-region js-action-link" data-reset=".js-geo-region" data-url="{{ route('ajax.geo_cities') }}" data-region="{{ $region->region }}">{{ $region->region }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="description-scroll geo-city__select js-geo-level-2">
            <div class="geo-city__select__title">Город:</div>
            <div class="description-scroll__body geo-city__select__body js-geo-city__body">
                @if( $active = $cities->where('city', $config['city'])->first())
                    <div class="geo-city__select__item">
                        <span class="active">{{ $active->city }}</span>
                    </div>
                @endif
                @foreach($cities as $city)
                    @if( !$active or $active->city != $city->city )
                        <div class="geo-city__select__item">
                            <span class="js-geo-city" data-city="{{ $city->city }}">{{ $city->city }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
</div>