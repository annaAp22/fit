@extends('admin.layout')
@section('css')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/css/bootstrap-datepicker.css" rel="stylesheet">
@endsection
@section('header')
    <div class="page-header">
        <h1><i class="glyphicon glyphicon-edit"></i> News / Edit #{{$news->id}}</h1>
    </div>
@endsection

@section('main')
    <div class="page-content">
        <div class="page-header">
            <h1>
                Новости
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Редактирование
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12 ace-thumbnails">

                <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group @if($errors->has('name')) has-error @endif">
                        <label for="name-field" class="col-sm-3 control-label no-padding-right">Заголовок</label>
                        <div class="col-sm-9">
                            <input type="text" id="name-field" name="name" class="form-control" value="{{ is_null(old("name")) ? $news->name : old("name") }}"/>
                        </div>
                       @if($errors->has("name"))
                            <span class="help-block">{{ $errors->first("name") }}</span>
                       @endif
                    </div>
                    <div class="form-group @if($errors->has('sysname')) has-error @endif">
                        <label class="col-sm-3 control-label no-padding-right" for="sysname-field">ЧПУ (URL)</label>
                        <div class="col-sm-9">
                            <input type="text" id="sysname-field" name="sysname" class="form-control" value="{{ is_null(old("sysname")) ? $news->sysname : old("sysname") }}"/>
                        </div>
                       @if($errors->has("sysname"))
                            <span class="help-block">{{ $errors->first("sysname") }}</span>
                       @endif
                    </div>

                    <div class="form-group @if($errors->has('body')) has-error @endif">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Дата</label>
                        <div class="col-sm-9">
                            <div class="input-group col-sm-4">
                                <input class=" form-control date-picker" name="date" value="{{old('date', $news->date)}}" id="form-field-1" type="text" data-date-format="yyyy-mm-dd" />
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar bigger-110"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group @if($errors->has('body')) has-error @endif">
                       <label class="col-sm-3 control-label no-padding-right" for="body-field">Текст</label>
                        <div class="col-sm-9">
                            <textarea class="form-control ck-editor" id="body-field" rows="3" name="body">{{ is_null(old("body")) ? $news->body : old("body") }}</textarea>
                        </div>
                        @if($errors->has("body"))
                            <span class="help-block">{{ $errors->first("body") }}</span>
                        @endif
                    </div>

                    <div class="form-group @if($errors->has('img')) has-error @endif">
                        <label class="col-sm-3 control-label no-padding-right" for="img"> Изображение </label>
                        <div class="col-sm-9">
                            <div class="tabbable">
                                <ul class="nav nav-tabs" id="myTab">
                                    @if($news->img)
                                        <li class="active">
                                            <a data-toggle="tab" href="#field-img-now" aria-expanded="false">
                                                Текущее
                                            </a>
                                        </li>
                                    @endif
                                    <li @if(!$news->img) class="active" @endif>
                                        <a data-toggle="tab" href="#field-img-new" aria-expanded="true">
                                            Новое
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    @if($news->img)
                                        <div id="field-img-now" class="tab-pane fade active in">
                                            <ul class="ace-thumbnails clearfix">
                                                <li>
                                                    <a href="{{$news->uploads->img->url()}}"  data-rel="colorbox" class="cboxElement">
                                                        <img  src="{{$news->uploads->img->preview->url()}}">
                                                    </a>
                                                    <div class="tools">
                                                        <a href="{{route('admin.image.crop', [
                                                            'img' => $news->uploads->img->url(),
                                                            'preview' => $news->uploads->img->preview->url(),
                                                            'width' => $news->uploads->img->preview->width,
                                                            'height' => $news->uploads->img->preview->height 
                                                        ])}}" title="Изменить">
                                                            <i class="ace-icon glyphicon glyphicon-camera"></i>
                                                        </a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                    <div id="field-img-new" class="tab-pane fade @if(!$news->img) active in @endif">
                                        <input name="img" type="file" value="{{ old('img', $news->img) }}" class="img-drop" accept="image/*" />
                                        @if($errors->has("img"))
                                            <span class="help-block">{{ $errors->first("img") }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group @if($errors->has('title')) has-error @endif">
                        <label class="col-sm-3 control-label no-padding-right" for="title-field">Title</label>
                        <div class="col-sm-9">
                            <input type="text" id="title-field" name="title" class="form-control" value="{{ old("title", $news->title) }}"/>
                        </div>
                        @if($errors->has("title"))
                            <span class="help-block">{{ $errors->first("title") }}</span>
                        @endif
                    </div>
                    <div class="form-group @if($errors->has('keywords')) has-error @endif">
                        <label class="col-sm-3 control-label no-padding-right" for="keywords-field">Keywords</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="keywords-field" rows="3" name="keywords">{{ old("keywords", $news->keywords) }}</textarea>
                        </div>
                        @if($errors->has("keywords"))
                            <span class="help-block">{{ $errors->first("keywords") }}</span>
                        @endif
                    </div>
                    <div class="form-group @if($errors->has('description')) has-error @endif">
                        <label class="col-sm-3 control-label no-padding-right" for="description-field">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="description-field" rows="3" name="description">{{ old("description", $news->description) }}</textarea>
                        </div>
                        @if($errors->has("description"))
                            <span class="help-block">{{ $errors->first("description") }}</span>
                        @endif
                    </div>
                    <div class="form-group @if($errors->has('status')) has-error @endif">
                        <label class="col-sm-3 control-label no-padding-right" for="status-field">Статус</label>
                        <div class="col-sm-9">
                            <select name="status" id="status-field" value="{{ old('status', $news->status) }}">
                                <option value="0" @if($news->status == 0) selected @endif>Черновик</option>
                                <option value="1" @if($news->status == 1) selected @endif>Опубликован</option>
                            </select>
                        </div>
                        @if($errors->has("status"))
                            <span class="help-block">{{ $errors->first("status") }}</span>
                        @endif
                    </div>
                    <div class="well well-sm">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a class="btn btn-link pull-right" href="{{ route('admin.news.index') }}"><i class="glyphicon glyphicon-backward"></i>  Назад</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <script>
    $('.date-picker').datepicker({
    });
  </script>
@endsection
