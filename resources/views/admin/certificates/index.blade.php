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
            <li class="active">Сертификаты</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Сертификаты
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список сертификатов
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">

                <div class="table-header">
                    Список всех сертификатов
                    <div class="ibox-tools">
                        <a href="{{route('admin.certificates.create')}}" class="btn btn-success btn-xs">
                            <i class="fa fa-plus"></i>
                            Добавить
                        </a>
                    </div>
                </div>

                <div>
                    <ul class="ace-thumbnails clearfix">
                        @foreach($certificates as $photo)
                        <li>
                            <a href="{{$photo->uploads->img->url()}}" data-rel="colorbox">
                                <img src="{{$photo->uploads->img->preview->url()}}" />
                            </a>
                            <div class="tools tools-bottom">

                                <form method="POST" action='{{route('admin.certificates.destroy', $photo->id)}}' style="display:inline;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                                    <a href="#" class="action-delete" style="font-size:18px;">
                                        <i class="ace-icon fa fa-times red"></i>
                                    </a>
                                </form>
                                <a href="{{route('admin.image.crop', [
                                        'img' => $photo->uploads->img->url(),
                                        'preview' => $photo->uploads->img->preview->url(),
                                        'width' => $photo->uploads->img->preview->width,
                                        'height' => $photo->uploads->img->preview->height
                                    ])}}" title="Изменить">
                                    <i class="ace-icon glyphicon glyphicon-camera"></i>
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                </div>

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->


@stop