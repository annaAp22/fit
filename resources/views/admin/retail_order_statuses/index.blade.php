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
            <li class="active">Статусы заказов из retailCRM</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Статусы заказа retailCRM
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех статусов
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <div class="tabbable">
                        <div class="table-header">
                            Список всех статусов

                            <div class="ibox-tools">
                                <a href="{{route('admin.retailcrm_order_statuses.load')}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-plus"></i>
                                    Загрузить статусы из CRM
                                </a>
                                <a href="{{route('admin.retailcrm_order_statuses.create')}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-plus"></i>
                                    Добавить статус
                                </a>
                            </div>

                        </div>
                        <div>
                    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">

                            <table id="simple-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Символьный код</th>
                                    <th>Название</th>
                                    <th>Статус на сайте</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody class="ace-thumbnails clearfix">
                                @forelse($statuses as $item)
                                    <tr>
                                        <td>{{$item->sysname}}</td>
                                        <td>
                                            <a href="{{route('admin.retailcrm_order_statuses.edit', $item->id)}}">{{$item->name}}</a>
                                        </td>
                                        <td>{{$item->status_name}}</td>
                                        <td>
                                            <div class="hidden-sm hidden-xs btn-group">
                                                <a class="btn btn-xs btn-info" href="{{route('admin.retailcrm_order_statuses.edit', $item->id)}}">
                                                    <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                </a>
                                                <form method="POST" action='{{route('admin.retailcrm_order_statuses.destroy', $item->id)}}' style="display:inline;">
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
                                    <p>Нет cтатусов</p>
                                @endforelse
                                </tbody>
                            </table>
                        </div><!-- /.row -->
                    </div>
                </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->


@stop