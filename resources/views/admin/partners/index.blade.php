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
            <li class="active">Партнеры</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Партнеры
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех партнеров
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">

                <div class="table-header">
                    Список всех партнеров
                    <div class="ibox-tools">
                        <a href="{{route('admin.partners.create')}}" class="btn btn-success btn-xs">
                            <i class="fa fa-plus"></i>
                            Добавить партнера
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
                                    <th>Имя</th>
                                    <th>Код</th>
                                    <th>E-mail</th>
                                    <th>Телефон</th>
                                    <th>Заработано</th>
                                    <th>Списано</th>
                                    <th>На баллансе</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($partners as $unit)
                                    <tr>
                                        <td>
                                            <a href="{{route('admin.partners.edit', $unit->id)}}">{{$unit->user->name}}</a>
                                        </td>
                                        <td>{{$unit->code}}</td>
                                        <td>{{$unit->user->email}}</td>
                                        <td>{{$unit->user->phone}}</td>
                                        <td>{{$unit->make_money}}</td>
                                        <td>{{$unit->spent_money}}</td>
                                        <td>{{$unit->remain_money}}</td>
                                        <td>
                                            <div class="hidden-sm hidden-xs btn-group">
                                                <a class="btn btn-xs btn-info" href="{{route('admin.partners.edit', $unit->id)}}">
                                                    <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                </a>
                                                <form method="POST" action='{{route('admin.partners.destroy', $unit->id)}}' style="display:inline;">
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
                                    <p>Нет пользователей</p>
                                @endforelse
                                </tbody>
                            </table>

                            <div class="row" style="border-bottom:none;">
                                <div class="col-xs-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
                                        {!! $partners->render() !!}
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