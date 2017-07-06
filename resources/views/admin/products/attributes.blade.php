@php
    $attribute_ids = old('attributes') ?:
        !isset($product) ? null :
        ($product->attributes->count() ?
            $product->attributes->pluck('pivot.value', 'id')->all() :
            null);
@endphp
<div class="form-group">
    <label class="col-sm-3 control-label no-padding-right"> Атрибуты </label>
    <div class="col-sm-9">
        <div class="dynamic-input">
            @php $i = 0; @endphp
            @if($attribute_ids)
                @foreach($attribute_ids as $attr_id => $attr_value)
                    @include('admin.attributes.dynamic', ['attributes' => $attributes, 'first' => $i == 0, 'id' => $attr_id, 'value' => $attr_value])
                    @php $i++; @endphp
                @endforeach
            @endif
            @include('admin.attributes.dynamic', ['attributes' => $attributes, 'first' => $i == 0, 'id' => null, 'value' => null])
        </div>
    </div>
</div>