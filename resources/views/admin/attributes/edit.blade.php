@extends('admin.layout')
@section('main')
    <div class="breadcrumbs" id="breadcrumbs">
        <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
        </script>

        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{route('admin.main')}}">Главная</a>
            </li>

            <li>
                <a href="{{route('admin.attributes.index')}}">Атрибуты</a>
            </li>
            <li class="active">Редактирование атрибута</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Атрибуты
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Редактирование
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12 ace-thumbnails">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" action="{{route('admin.attributes.update', $attribute->id)}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> Название </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-0" name="name" placeholder="Название" value="{{ old('name', $attribute->name) }}" class="col-sm-12">
                        </div>
                    </div>
                    <!-- sysname -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-0">Символьный код</label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-0" name="sysname" placeholder="Символьный код" value="{{$attribute->sysname}}" class="col-sm-12">
                            <span class="text-muted">только латинские цифры и знак подчеркивания</span>
                        </div>
                    </div>
                    <!-- type -->
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"  for="form-field-1"> Тип </label>
                        <div class="col-sm-9">
                            <select name="type" class="col-sm-5 show-hidden-option" id="form-field-1"  data-class="div-type-data">
                                <option value="">--Не выбран--</option>
                                @foreach($types as $type => $name)
                                    <option value="{{$type}}" @if((old() && old('type')==$type) || (!old() && $attribute->type==$name) ) selected="selected"@endif  data-id="div-{{$type}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group div-type-data" id="div-integer" @if((old() && old('type') != 'integer') || (!old() && $attribute->type!='Числовой')) style="display:none" @endif>
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Ед. измерения </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-2" name="unit" placeholder="Ед. измерения" value="{{ old('unit', $attribute->unit) }}" class="col-sm-4">
                        </div>
                    </div>

                    <div class="form-group div-type-data" id="div-list" @if((old() && old('type') != 'list') || (!old() && !in_array($attribute->type, ['Список', 'Список чекбоксов']) )) style="display:none" @endif>
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Значения </label>
                        <div class="col-sm-9">
                            <div class="dynamic-input">
                                @if (old('values'))
                                    @foreach(old('values') as $key => $var)
                                        <div class="input-group dynamic-input-item" style="margin-bottom:5px;">
                                            <input class="col-sm-9 form-control" type="text" name="values[]" value="{{old('values')[$key]}}" placeholder="Значение">
                                            <a href="" class="input-group-addon @if($key == 0)plus @else minus @endif">
                                                <i class="glyphicon @if($key == 0)glyphicon-plus @else glyphicon-minus @endif bigger-110"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    @forelse($attribute->getLists() as $key => $value)
                                    <div class="input-group dynamic-input-item" style="margin-bottom:5px;">
                                        <input class="col-sm-9 form-control" type="text" name="values[]" value="{{$value}}" placeholder="Значение">
                                        <a href="" class="input-group-addon @if($key == 0)plus @else minus @endif">
                                            <i class="glyphicon @if($key == 0)glyphicon-plus @else glyphicon-minus @endif bigger-110"></i>
                                        </a>
                                    </div>
                                    @empty
                                    <div class="input-group dynamic-input-item" style="margin-bottom:5px;">
                                        <input class="col-sm-9 form-control" type="text" name="values[]" placeholder="Значение">
                                        <a href="" class="input-group-addon plus">
                                            <i class="glyphicon glyphicon-plus bigger-110"></i>
                                        </a>
                                    </div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-4"> В Фильтрах </label>
                        <div class="col-sm-9">
                            <label>
                                <input type="hidden" name="is_filter" value="0">
                                <input name="is_filter" @if ((old() && old('is_filter')) || (empty(old()) && $attribute->is_filter) )  checked="checked" @endif  value="1" class="ace ace-switch ace-switch-4 btn-empty" type="checkbox">
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Активность </label>
                        <div class="col-sm-9">
                            <label>
                                <input type="hidden" name="status" value="0">
                                <input name="status" @if ((old() && old('status')) || (empty(old()) && $attribute->status) ) checked="checked" @endif   value="1" class="ace ace-switch ace-switch-4 btn-empty" type="checkbox">
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-success" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Сохранить
                            </button>
                            &nbsp; &nbsp; &nbsp;
                            <button class="btn" type="reset">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Обновить
                            </button>
                            &nbsp; &nbsp; &nbsp;
                            <a class="btn btn-info" href="{{route('admin.attributes.index')}}">
                                <i class="ace-icon glyphicon glyphicon-backward bigger-110"></i>
                                Назад
                            </a>

                        </div>
                    </div>
                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@stop
