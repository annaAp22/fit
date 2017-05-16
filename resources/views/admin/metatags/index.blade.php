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
            <li class="active">Мета-тэги</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Метатэги
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех мета-тегов
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">

                <div class="table-header">
                    Список всех мета-тегов
                @can('add', new App\Models\Metatag())
                    <div class="ibox-tools">
                        <a href="{{route('admin.metatags.create')}}" class="btn btn-success btn-xs">
                            <i class="fa fa-plus"></i>
                            Добавить мета-теги
                        </a>
                    </div>
                @endcan
                </div>
                <div>
                    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">

                            <!-- FILTERS -->
                            <div class="row">
                                <form method="GET" action="{{route('admin.metatags.index')}}">
                                    <div class="col-xs-3">
                                        <div class="dataTables_length">
                                            <label>На страниц
                                                <select name="f[perpage]" class="form-control input-sm">
                                                    <option value="10" @if (isset($filters['perpage']) &&  $filters['perpage']== 10) selected="selected" @endif>10</option>
                                                    <option value="25" @if (isset($filters['perpage']) &&  $filters['perpage']== 25) selected="selected" @endif>25</option>
                                                    <option value="50" @if (isset($filters['perpage']) &&  $filters['perpage']== 50) selected="selected" @endif>50</option>
                                                    <option value="100" @if (isset($filters['perpage']) &&  $filters['perpage']== 100) selected="selected" @endif>100</option>
                                                </select> </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="dataTables_filter">
                                            <label>URL:
                                                <input type="text" name="f[url]" value="{{$filters['url'] or ''}}" class="form-control input-sm">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="dataTables_filter">
                                            <label>Название:
                                                <input type="text" name="f[name]" value="{{$filters['name'] or ''}}" class="form-control input-sm">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="dataTables_filter">
                                            <a class="btn  btn-xs" href="{{route('admin.metatags.index')}}?refresh=1">
                                                Сбросить
                                                <i class="ace-icon glyphicon glyphicon-refresh  align-top bigger-125 icon-on-right"></i>
                                            </a>

                                            <button class="btn btn-info btn-xs" type="submit">
                                                Фильтровать
                                                <i class="ace-icon glyphicon glyphicon-search  align-top bigger-125 icon-on-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <table id="simple-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>URL</th>
                                    <th class="col-sm-1">Title</th>
                                    <th class="col-sm-1">Description</th>
                                    <th class="col-sm-1">Keywords</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($metatags as $metatag)
                                    <tr>
                                        <td>
                                            <a href="{{route('admin.metatags.edit', $metatag->id)}}">{{$metatag->name}}</a>
                                        </td>
                                        <td>{{$metatag->url}}</td>
                                        <td class="col-sm-1 center"><i class="ace-icon glyphicon @if($metatag->title) glyphicon-ok green @else glyphicon-remove red @endif  bigger-120"></i></td>
                                        <td class="col-sm-1 center"><i class="ace-icon glyphicon @if($metatag->description) glyphicon-ok green @else glyphicon-remove red @endif bigger-120"></i></td>
                                        <td class="col-sm-1 center"><i class="ace-icon glyphicon @if($metatag->keywords) glyphicon-ok green @else glyphicon-remove red @endif bigger-120"></i></td>

                                        <td>
                                            <div class="hidden-sm hidden-xs btn-group">

                                                <a class="btn btn-xs btn-info" href="{{route('admin.metatags.edit', $metatag->id)}}">
                                                    <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                </a>
                                            @can('delete', new App\Models\Metatag())
                                                <form method="POST" action='{{route('admin.metatags.destroy', $metatag->id)}}' style="display:inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                                                    <button class="btn btn-xs btn-danger action-delete" type="button" style="border-width: 1px;">
                                                        <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <p>Нет метатегов</p>
                                @endforelse
                                </tbody>
                            </table>

                            <div class="row" style="border-bottom:none;">
                                <div class="col-xs-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
                                        {!! $metatags->render() !!}
                                    </div>
                                </div>
                            </div>


                        </div><!-- /.row -->
                    </div>
                </div>

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->


@stop