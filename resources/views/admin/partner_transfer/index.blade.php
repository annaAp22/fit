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
            <li class="active">{{trans('admin.accruals_history')}}</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                {{trans('admin.accruals_history')}}
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    {{trans('admin.accruals_list')}}
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">

                <div class="table-header">
                    {{trans('admin.accruals_list')}}
                    <div class="ibox-tools">
                    </div>
                </div>
                <div>
                    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <table id="simple-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Партнер</th>
                                    <th>Оператор</th>
                                    <th>Статус</th>
                                    <th>Сумма</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($list as $item)
                                    <tr>
                                        <td>
                                            <a href="{{route('admin.partners.show', $item->partner->id)}}">{{$item->partner->user->name}}</a>
                                        </td>
                                        <td>
                                            @if(isset($item->operator))
                                                <a href="{{route('admin.users.show', $item->operator->id)}}">{{$item->operator->name}}</a>
                                            @else
                                                {{trans('admin.System')}}
                                            @endif
                                        </td>
                                        <th>{{trans('admin.'.$item->status)}}</th>
                                        <th>{{$item->money}} р.</th>
                                    </tr>
                                @empty
                                    <p>Нет пользователей</p>
                                @endforelse
                                </tbody>
                            </table>

                            <div class="row" style="border-bottom:none;">
                                <div class="col-xs-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
                                        {!! $list->render() !!}
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