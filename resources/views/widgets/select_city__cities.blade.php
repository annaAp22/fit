@foreach($cities as $city)
    <div class="geo-city__select__item">
        <span class="js-geo-city" data-city="{{ $city->city }}">{{ $city->city }}</span>
    </div>
@endforeach