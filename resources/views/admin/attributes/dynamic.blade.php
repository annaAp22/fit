<div class="dynamic-input-item dynamic-attributes" style="margin-bottom:5px;">
    {{-- Attribute select --}}
    <div class="input-group col-sm-4" style="float:left;">
        <a href="" class="input-group-addon @if($first)plus @else minus @endif">
            <i class="glyphicon @if($first)glyphicon-plus @else glyphicon-minus @endif bigger-110"></i>
        </a>
        @include('admin.attributes.select', ['attributes' => $attributes, 'selected_id' => $id])
    </div>

    {{-- String --}}
    <div class="input-group col-sm-3 field field-value input-string" style="float:left; display:none;">
        <input class="col-sm-9" type="text" data-dynamic name="attributes[{{$id ?: -1}}]" value="{{ $value ?: '' }}" placeholder="Значение">
    </div>

    {{-- Integer --}}
    <div class="input-group col-sm-3 field field-value input-integer" style="float:left; display:none;">
        <a class="input-group-addon">шт.</a>
        <input class="col-sm-9" type="text" data-dynamic name="attributes[{{$id ?: -1}}]" value="{{ $value ?: '' }}" placeholder="Значение">
    </div>

    {{-- List --}}
    <div class="input-group col-sm-3 field field-values input-list" style="float:left; display:none;">
        <select name="attributes[{{$id ?: -1}}]" data-dynamic class="col-sm-9" style="height: 34px" data-val="{{ $value ?: ''}}" value="{{ $value ?: '' }}">
            <option value=""></option>
        </select>
    </div>

    {{-- Color --}}
    <div class="input-group col-sm-3 field field-values input-color" style="float:left; display:none;">
        <input  name="attributes[{{$id ?: -1}}]" data-dynamic type="text" value="{{ $value ?: '' }}" />
    </div>

    {{-- Checklist --}}
    <div class="input-group col-sm-6 field field-values input-checklist" style="float:left; display:none;">
        <input  name="attributes[{{$id ?: -1}}]" data-dynamic type="hidden" value="{{ $value ?: '' }}" data-val="{{ $value ?: '' }}" />
    </div>

    <div style="clear:both"></div>
</div>
