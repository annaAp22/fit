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
            <li class="active">Заказы</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Заказы
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех заказов
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <div class="tabbable">


                        <div class="table-header">
                            Список всех заказов
                        </div>
                        <div>
                    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">

                            <!-- FILTERS -->
                            <div class="row">
                                <form method="GET" action="{{route('admin.orders.index')}}">
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <div>
                                                <label>На страниц
                                                    <select name="f[perpage]" class="form-control input-sm">
                                                        <option value="10" @if (isset($filters['perpage']) &&  $filters['perpage']== 10) selected="selected" @endif>10</option>
                                                        <option value="25" @if (isset($filters['perpage']) &&  $filters['perpage']== 25) selected="selected" @endif>25</option>
                                                        <option value="50" @if (isset($filters['perpage']) &&  $filters['perpage']== 50) selected="selected" @endif>50</option>
                                                        <option value="100" @if (isset($filters['perpage']) &&  $filters['perpage']== 100) selected="selected" @endif>100</option>
                                                    </select> </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div>
                                                <label>Имя:
                                                    <input type="text" name="f[name]" value="{{$filters['name'] or ''}}" class="form-control input-sm">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-4">
                                            <div>
                                                <label>Дата:
                                                    <div class="input-daterange input-group">
                                                        <input type="text" class="input-sm form-control" name="f[date_from]" value="{{$filters['date_from'] or ''}}">
                                                        <span class="input-group-addon">
                                                    <i class="fa fa-exchange"></i>
                                                </span>
                                                        <input type="text" class="input-sm form-control"  name="f[date_to]" value="{{$filters['date_to'] or ''}}">
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div>
                                                <label>Тип
                                                <select name="f[status]" class="form-control input-sm">
                                                    <option value="" @if (!isset($filters['status'])) selected="selected" @endif>Все</option>
                                                    @foreach(\App\Models\Order::$statuses as $status => $name)
                                                    <option value="{{$status}}" @if (isset($filters['status']) &&  $filters['status']==$status) selected="selected" @endif>{{$name}}</option>
                                                    @endforeach
                                                </select> </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="dataTables_length">
                                                <div class="checkbox">
                                                    <label class="block">
                                                        <input name="f[deleted]" value="1" type="checkbox" @if (isset($filters['deleted'])) checked="checked" @endif class="ace input-lg">
                                                        <span class="lbl bigger-120"> С удаленными</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-3" style="float: right">
                                            <div>
                                                <a class="btn  btn-xs" href="{{route('admin.orders.index')}}?refresh=1">
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
                                    <th>№</th>
                                    <th>ФИО</th>
                                    <th>Дата</th>
                                    <th>Сумма</th>
                                    <th>Статус</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody class="ace-thumbnails clearfix">
                                @forelse($orders as $item)
                                    <tr @if($item->trashed())style="background-color: #F6CECE"@endif>
                                        <td>{{ $item->id }}</td>
                                        <td>
                                            <a href="{{route('admin.orders.edit', $item->id)}}">{{$item->name}}</a>
                                        </td>
                                        <td>
                                            {{$item->dateLocale()}}
                                        </td>
                                        <td>{{$item->amount}} руб.</td>
                                        <td class="col-sm-1 center">
                                            {{$item->statusName()}}
                                        </td>
                                        <td>
                                            <div class="hidden-sm hidden-xs btn-group">
                                                @if($item->trashed())
                                                <form method="POST" action='{{route('admin.orders.restore', $item->id)}}' style="display:inline;">
                                                    <input type="hidden" name="_method" value="PUT">
                                                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                                                    <button class="btn btn-xs btn-purple action-restore" type="button" style="border-width: 1px;">
                                                        <i class="ace-icon fa fa-undo bigger-120"></i>
                                                    </button>
                                                </form>
                                                @else
                                                <a class="btn btn-xs btn-info" href="{{route('admin.orders.edit', $item->id)}}">
                                                    <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                </a>
                                                <form method="POST" action='{{route('admin.orders.destroy', $item->id)}}' style="display:inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                                                    <button class="btn btn-xs btn-danger action-delete" type="button" style="border-width: 1px;">
                                                        <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <p>Нет заказов</p>
                                @endforelse
                                </tbody>
                            </table>

                            <div class="row" style="border-bottom:none;">
                                <div class="col-xs-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
                                        {!! $orders->render() !!}
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