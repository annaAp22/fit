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
            <li class="active">Редактирование страницы</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Содержание страниц
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
                <form class="form-horizontal" role="form" action="{{route('admin.pages.update', $page->id)}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> Название </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-0" name="name" placeholder="Название" value="{{ old('name', $page->name) }}" class="col-sm-5">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Системное (URL) </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" name="sysname" placeholder="sysname" value="{{ old('sysname', $page->sysname) }}" class="col-sm-5" @cannot('sysname', new App\Models\Page()) readonly @endcan>
                            <span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Используется в качестве части адреса страницы." title="">?</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Содержание </label>
                        <div class="col-sm-9">
                            <textarea class="ck-editor" id="editor2" name="content">{{ old('content', $page->content) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Title </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-6" name="title" placeholder="Title" value="{{ old('title', $page->title) }}" class="col-sm-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-7"> Description </label>
                        <div class="col-sm-9">
                            <textarea id="form-field-7" name="description" placeholder="Description" class="col-sm-12">{{ old('description', $page->description) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-8"> Keywords </label>
                        <div class="col-sm-9">
                            <textarea id="form-field-8" name="keywords" placeholder="Keywords" class="col-sm-12">{{ old('keywords', $page->keywords) }}</textarea>
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
                                @forelse($page->vars as $key => $var)
                                <div class="input-group dynamic-input-item" style="margin-bottom:5px;">
                                    <input type="hidden" name="var_ids[]" value="{{$var->id}}">
                                    <input class="col-sm-3" class="form-control" type="text" name="vars[]" value="{{$var->var}}" placeholder="Имя">
                                    <input class="col-sm-9"class="form-control" type="text" name="values[]" value="{{$var->value}}" placeholder="Значение">
                                    <a href="" class="input-group-addon @if($key == 0)plus @else minus @endif">
                                        <i class="glyphicon @if($key == 0)glyphicon-plus @else glyphicon-minus @endif bigger-110"></i>
                                    </a>
                                </div>
                                @empty
                                <div class="input-group dynamic-input-item" style="margin-bottom:5px;">
                                    <input class="col-sm-3" class="form-control" type="text" name="vars[]" placeholder="Имя">
                                    <input class="col-sm-9"class="form-control" type="text" name="values[]" placeholder="Значение">
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
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Фотографии </label>
                        <div class="col-sm-9">
                            <div class="tabbable">
                                <ul class="nav nav-tabs" id="myTab">
                                    @if($page->photos())
                                        <li class="active">
                                            <a data-toggle="tab" href="#field-photos-now" aria-expanded="false">
                                                Текущие
                                            </a>
                                        </li>
                                    @endif
                                    <li @if(!$page->photos()) class="active" @endif>
                                        <a data-toggle="tab" href="#field-photos-new" aria-expanded="true">
                                            Добавить
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    @if($page->photos())
                                        <div id="field-photos-now" class="tab-pane fade active in">
                                            <ul class="ace-thumbnails photo-container clearfix">
                                                @foreach($page->photos as $photo)
                                                    <li class="photo-container-item">
                                                        <a href="{{$photo->uploads->img->original->url()}}" data-rel="colorbox" title="{{$photo->name}}">
                                                            <img src="{{$photo->uploads->img->preview->url()}}" />
                                                            <div class="tags">
                                                                <span class="label-holder label-delete" style="display: none;">
                                                                    <span class="label label-danger arrowed">На удаление</span>
                                                                </span>
                                                            </div>
                                                        </a>
                                                        <div class="tools tools-top">
                                                            <a href="#" class="photo-action-cancel" style="font-size:18px;display: none;" title="Отменить удаление">
                                                                <i class="ace-icon fa fa-link"></i>
                                                            </a>
                                                            <a href="#" class="photo-action-delete" style="font-size:18px;" title="На удаление">
                                                                <i class="ace-icon fa fa-times red"></i>
                                                            </a>
                                                        </div>
                                                        <input type="hidden" name="p_ids[]" value="{{$photo->id}}">
                                                        <input type="hidden" class="input-delete" name="p_delete[]" value="0">
                                                        <input type="text" name="p_names[]" placeholder="Описание" value="{{$photo->name}}" class="col-sm-12">
                                                    </li>
                                                @endforeach
                                            </ul>


                                        </div>
                                    @endif

                                    <div id="field-photos-new" class="tab-pane fade @if(!$page->photos()) active in @endif" style="height: 100px">
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
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Активность </label>
                        <div class="col-sm-9">
                            <label>
                                <input type="hidden" name="status" value="0">
                                <input name="status" @if ((old() && old('status')) || (empty(old()) && $page->status) ) checked="checked" @endif   value="1" class="ace ace-switch ace-switch-4 btn-empty" type="checkbox">
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
                                Отменить
                            </button>
                        </div>
                    </div>

                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@stop
