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
            <li class="active">Города</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Города
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех городов
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">

                <div class="table-header">
                    Список всех городов
                    @can('add', new App\Models\Page())
                    <div class="ibox-tools">
                        <a href="{{route('admin.cities.create')}}" class="btn btn-success btn-xs">
                            <i class="fa fa-plus"></i>
                            Добавить город
                        </a>
                    </div>
                    @endcan
                </div>
                <div>
                    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <table id="simple-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>ЧПУ</th>
                                    <th>Долгота</th>
                                    <th>Ширина</th>
                                    <th>Статус</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>
                                            <a href="{{route('admin.cities.edit', $item->id)}}">{{$item->title}}</a>
                                        </td>
                                        <td>{{$item->sysname}}</td>
                                        <td>{{$item->long}}</td>
                                        <td>{{$item->lat}}</td>
                                        <td class="col-sm-1 center"><i class="ace-icon glyphicon @if($item->status) glyphicon-ok green @else glyphicon-remove red @endif  bigger-120"></i></td>
                                        <td>
                                            <div class="hidden-sm hidden-xs btn-group">

                                                <a class="btn btn-xs btn-info" href="{{route('admin.cities.edit', $item->id)}}">
                                                    <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                </a>
                                                @can('delete', new App\Models\Page())
                                                <form method="POST" action='{{route('admin.cities.destroy', $item->id)}}' style="display:inline;">
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
                                    <p>Нет элементов</p>
                                @endforelse
                                </tbody>
                            </table>
                        </div><!-- /.row -->
                    </div>
                </div>

            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->


@stop