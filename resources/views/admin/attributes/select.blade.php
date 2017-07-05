<select name="" class="col-sm-9 select-attribute" style="height: 34px"  placeholder="Атрибут">
    <option value="-1">--Не выбран--</option>
    @foreach($attributes as $key => $attribute)
        @php
            if(isset($attribute->hidden)) {
                $visibility = 'hidden';
            }else{
                $visibility = '';
            }
        @endphp

        <option value="{{$attribute->id}}"
                @if($attribute->id == $selected_id)selected @endif
                data-type="{{$attribute->getOriginal('type')}}"
                data-unit="{{$attribute->unit}}"
                data-list="{{$attribute->list}}" {{$visibility}}>{{$attribute->name}}</option>
    @endforeach
</select>
