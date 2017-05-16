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
                <a href="{{route('admin.settings.index')}}">Настройки</a>
            </li>
            <li class="active">Редактирование настройки</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Настройки
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Редактирование
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" action="{{route('admin.settings.update', $setting->id)}}" method="POST">
                    <input type="hidden" name="_method" value="PUT">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Название </label>
                        <div class="col-sm-9">
                            <input type="text" @cannot('name', new App\Models\Setting()) readonly @endcan id="form-field-2" name="var" placeholder="Название" value="{{ old('route', $setting->var) }}" class="col-sm-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Тип </label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label>
                                    <input name="type" type="radio" class="ace check-vision" value="string" data-name="string-group" @if ((old() && old('type')=='string') || (empty(old()) && $setting->type=='string') ) checked="checked" @endif>
                                    <span class="lbl"> Строка</span>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input name="type" type="radio" class="ace check-vision" value="array" data-name="array-group" @if ((old() && old('type')=='array') || (empty(old()) && $setting->type=='array') ) checked="checked" @endif >
                                    <span class="lbl"> Массив</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group vision-group string-group" @if ((old() && old('type')=='array') || (empty(old()) && $setting->type=='array') )style="display:none;" @endif>
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Значение</label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-3" name="value" placeholder="Значение" value="{{ old('value', $setting->value) }}" class="col-sm-12">
                        </div>
                    </div>

                    <div class="form-group vision-group array-group" @if ((old() && old('type')=='string') || (empty(old()) && $setting->type=='string') )style="display:none;" @endif>
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Значения</label>
                        <div class="col-sm-9">
                            <div class="dynamic-input">
                                @if (old('values'))
                                    @foreach(old('values') as $key => $value)
                                        <div class="input-group dynamic-input-item" style="margin-bottom:5px;">
                                            <input class="col-sm-3" class="form-control" type="text" name="keys[]" value="{{old('keys')[$key]}}" placeholder="Ключ">
                                            <input class="col-sm-9"class="form-control" type="text" name="values[]" value="{{$value}}" placeholder="Значение">
                                            <a href="" class="input-group-addon @if($key == 0)plus @else minus @endif">
                                                <i class="glyphicon @if($key == 0)glyphicon-plus @else glyphicon-minus @endif bigger-110"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @elseif($setting->values)
                                    @foreach($setting->values as $key => $value)
                                        <div class="input-group dynamic-input-item" style="margin-bottom:5px;">
                                            <input class="col-sm-3" class="form-control" type="text" name="keys[]" value="{{$key}}" placeholder="Имя">
                                            <input class="col-sm-9"class="form-control" type="text" name="values[]" value="{{$value}}" placeholder="Значение">
                                            <a href="" class="input-group-addon @if($key == 0)plus @else minus @endif">
                                                <i class="glyphicon @if($key == 0)glyphicon-plus @else glyphicon-minus @endif bigger-110"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group dynamic-input-item" style="margin-bottom:5px;">
                                        <input class="col-sm-3" class="form-control" type="text" name="keys[]" placeholder="Ключ">
                                        <input class="col-sm-9"class="form-control" type="text" name="values[]" placeholder="Значение">
                                        <a href="" class="input-group-addon plus">
                                            <i class="glyphicon glyphicon-plus bigger-110"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Сохранить
                            </button>

                            &nbsp; &nbsp; &nbsp;
                            <button class="btn" type="reset">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Отменить
                            </button>
                        </div>
                    </div>

                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@stop
