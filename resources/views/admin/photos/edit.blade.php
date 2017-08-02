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
                <a href="{{route('admin.photos.index')}}">Фотографии</a>
            </li>
            <li class="active">Добавление фотографии</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Фотографии
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
                <form class="form-horizontal" role="form" action="{{route('admin.photos.update', $photo->id)}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Фотография </label>
                        <div class="col-sm-9">
                            <div class="tabbable">
                                <ul class="nav nav-tabs" id="myTab">
                                    @if($photo->img)
                                        <li class="active">
                                            <a data-toggle="tab" href="#field-img-now" aria-expanded="false">
                                                Текущее
                                            </a>
                                        </li>
                                    @endif
                                    <li @if(!$photo->img) class="active" @endif>
                                        <a data-toggle="tab" href="#field-img-new" aria-expanded="true">
                                            Новое
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    @if($photo->img)
                                        <div id="field-img-now" class="tab-pane fade active in">
                                            <ul class="ace-thumbnails clearfix">
                                                <li>
                                                    <a href="{{$photo->uploads->img->url()}}"  data-rel="colorbox" class="cboxElement">
                                                        <img  src="{{$photo->uploads->img->detailed->url()}}">
                                                    </a>
                                                    <div class="tools">
                                                        <a href="{{route('admin.image.crop', [
                                                            'img' => $photo->uploads->img->url(),
                                                            'preview' => $photo->uploads->img->detailed->url(),
                                                            'width' => $photo->uploads->img->detailed->width,
                                                            'height' => $photo->uploads->img->detailed->height
                                                        ])}}" title="Изменить">
                                                            <i class="ace-icon glyphicon glyphicon-camera"></i>
                                                        </a>
                                                    </div>
                                                </li>
                                            </ul>


                                        </div>
                                    @endif
                                    <div id="field-img-new" class="tab-pane fade @if(!$photo->img) active in @endif">
                                        <input name="img" type="file" class="img-drop" accept="image/*" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Подпись </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" name="caption" placeholder="Подпись" value="{{ old('caption', $photo->caption) }}" class="col-sm-5">
                            <span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="" title="">?</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> Сортировка </label>
                        <div class="col-sm-9" style="padding-left: 12px;">
                            <div class="col-sm-2 input-group" style="float: left;">
                                <input name="sort" value="{{old('sort', $photo->sort)}}" placeholder="500" type="text" id="form-field-10" class="input-number form-control" title="Меньше номер - выше фотография">
                                <span class="input-group-addon">
                                    <i class="fa fa-sort bigger-110"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Активность </label>
                        <div class="col-sm-9">
                            <label>
                                <input type="hidden" name="status" value="0">
                                <input name="status" @if ((old() && old('status')) || (empty(old()) && $photo->status) ) checked="checked" @endif  value="1" class="ace ace-switch ace-switch-4 btn-empty" type="checkbox">
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
                            <a class="btn btn-info" href="{{route('admin.photos.index')}}">
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
