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
            <li class="active">Баннеры</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Фотографии
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех фотографий
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <div class="tabbable">


                        <div class="table-header">
                            Список всех фотографий

                            <div class="ibox-tools">
                                <a href="{{route('admin.photos.create')}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-plus"></i>
                                    Добавить фотографию
                                </a>
                            </div>

                        </div>
                        <div>
                    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">

                            <!-- FILTERS -->
                            <div class="row">
                                <form method="GET" action="{{route('admin.photos.index')}}">
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <div>
                                                <label>На странице
                                                    <select name="f[perpage]" class="form-control input-sm">
                                                        <option value="10" @if (isset($filters['perpage']) &&  $filters['perpage']== 10) selected="selected" @endif>10</option>
                                                        <option value="25" @if (isset($filters['perpage']) &&  $filters['perpage']== 25) selected="selected" @endif>25</option>
                                                        <option value="50" @if (isset($filters['perpage']) &&  $filters['perpage']== 50) selected="selected" @endif>50</option>
                                                        <option value="100" @if (isset($filters['perpage']) &&  $filters['perpage']== 100) selected="selected" @endif>100</option>
                                                    </select> </label>
                                            </div>
                                        </div>

                                        <div class="col-xs-3">
                                            <div>
                                                <label>Статус
                                                    <select name="f[status]" class="form-control input-sm">
                                                        <option value="" @if (!isset($filters['status'])) selected="selected" @endif>Все</option>
                                                        <option value="1" @if (isset($filters['status']) &&  $filters['status']==1) selected="selected" @endif>Опубликовано</option>
                                                        <option value="0" @if (isset($filters['status']) &&  $filters['status']==='0') selected="selected" @endif>Черновик</option>

                                                    </select> </label>
                                            </div>
                                        </div>

                                        <div class="col-xs-3">
                                            <div>
                                                <a class="btn  btn-xs" href="{{route('admin.photos.index')}}?refresh=1">
                                                    Сбросить
                                                    <i class="ace-icon glyphicon glyphicon-refresh  align-top bigger-125 icon-on-right"></i>
                                                </a>

                                                <button class="btn btn-info btn-xs" type="submit">
                                                    Фильтровать
                                                    <i class="ace-icon glyphicon glyphicon-search  align-top bigger-125 icon-on-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <table id="simple-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Фото</th>
                                    <th>Подпись</th>
                                    <th>Сортировка</th>
                                    <th>Статус</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody class="ace-thumbnails clearfix">
                                @forelse($photos as $item)
                                    <tr>
                                        <td>
                                            @if($item->img)
                                                <a data-rel="colorbox" href="{{$item->uploads->img->url()}}">
                                                    <img src="{{$item->uploads->img->preview->url()}}" width="100" />
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $item->caption }}</td>
                                        <td>{{$item->sort}}</td>
                                        <td class="col-sm-1 center"><i class="ace-icon glyphicon @if($item->status) glyphicon-ok green @else glyphicon-remove red @endif  bigger-120"></i></td>
                                        <td>
                                            <div class="hidden-sm hidden-xs btn-group">
                                                <a class="btn btn-xs btn-info" href="{{route('admin.photos.edit', $item->id)}}">
                                                    <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                </a>
                                                <form method="POST" action='{{route('admin.photos.destroy', $item->id)}}' style="display:inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                                                    <button class="btn btn-xs btn-danger action-delete" type="button" style="border-width: 1px;">
                                                        <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <p>Нет фотографий</p>
                                @endforelse
                                </tbody>
                            </table>

                            <div class="row" style="border-bottom:none;">
                                <div class="col-xs-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
                                        {!! $photos->render() !!}
                                    </div>
                                </div>
                            </div>


                        </div><!-- /.row -->
                    </div>
                </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->


@stop