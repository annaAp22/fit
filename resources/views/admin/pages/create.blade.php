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
                <a href="{{route('admin.pages.index')}}">Содержание страниц</a>
            </li>
            <li class="active">Добавление страницы</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Содержание страниц
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Добавление
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" action="{{route('admin.pages.store')}}" method="POST" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> Название </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-0" name="name" placeholder="Название" value="{{ old('name') }}" class="col-sm-5">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Системное (URL) </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" name="sysname" placeholder="sysname" value="{{ old('sysname') }}" class="col-sm-5">
                            <span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Используется в качестве части адреса страницы." title="">?</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Содежрание </label>
                        <div class="col-sm-9">
                            <textarea class="ck-editor" id="editor2" name="content">{{ old('content') }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Title </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-6" name="title" placeholder="Title" value="{{ old('title') }}" class="col-sm-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-7"> Description </label>
                        <div class="col-sm-9">
                            <textarea id="form-field-7" name="description" placeholder="Description" class="col-sm-12">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-8"> Keywords </label>
                        <div class="col-sm-9">
                            <textarea id="form-field-8" name="keywords" placeholder="Keywords" class="col-sm-12">{{ old('keywords') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Дополнительные данные </label>
                        <div class="col-sm-9">
                            <div class="dynamic-input">
                                @if (old('vars'))
                                    @foreach(old('vars') as $key => $var)
                                        <div class="input-group dynamic-input-item" style="margin-bottom:5px;">
                                            @if (old('var_ids')[$key])
                                                <input type="hidden" name="var_ids[]" value="{{old('var_ids')[$key]}}">
                                            @endif
                                            <input class="col-sm-3" class="form-control" type="text" name="vars[]" value="{{$var}}" placeholder="Имя">
                                            <input class="col-sm-9"class="form-control" type="text" name="values[]" value="{{old('values')[$key]}}" placeholder="Значение">
                                            <a href="" class="input-group-addon @if($key == 0)plus @else minus @endif">
                                                <i class="glyphicon @if($key == 0)glyphicon-plus @else glyphicon-minus @endif bigger-110"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group dynamic-input-item" style="margin-bottom:5px;">
                                        <input class="col-sm-3" class="form-control" type="text" name="vars[]" placeholder="Имя">
                                        <input class="col-sm-9"class="form-control" type="text" name="values[]" placeholder="Значение">
                                        <a href="" class="input-group-addon plus">
                                            <i class="glyphicon glyphicon-plus bigger-110"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Фотографии </label>
                        <div class="col-sm-9">
                            <div class="dynamic-input">
                                <div class="input-group dynamic-input-item col-sm-12" style="margin-bottom:5px;">
                                    <div class="col-sm-5">
                                        <input type="file" name="photos[]" class="file-input-img col-sm-12"  accept="image/*" />
                                    </div>
                                    <div class="col-sm-7">
                                        <input class="col-sm-10" style="padding-top: 2px;" type="text" name="names[]" placeholder="Описание">
                                        <a href="" class="input-group-addon plus">
                                            <i class="glyphicon glyphicon-plus bigger-110"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Активность </label>
                        <div class="col-sm-9">
                            <label>
                                <input type="hidden" name="status" value="0">
                                <input name="status" checked="checked" value="1" class="ace ace-switch ace-switch-4 btn-empty" type="checkbox">
                                <span class="lbl"></span>
                            </label>
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
                                Обновить
                            </button>
                        </div>
                    </div>

                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@stop
