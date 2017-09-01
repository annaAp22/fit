@if($looks->count())
    <div class="look-book">
        <div class="look-book__title">
            <span>Подобрать</span> <span>\</span> <span>Look</span>
        </div>
        <div class="look-book__text">
            Лучшие модели работают для того, чтобы Вы смогли подобрать именно то, что нужно Вам, чтобы завершить свой неповторимый образ. Просто кликните по интересующей
            Вас вещи и получите информацию для покупки
        </div>
        @include('looks.carousel')
    </div>

@endif