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
                <a href="{{route('admin.retailcrm_order_statuses.index')}}">Статусы заказов из retailCRM</a>
            </li>
            <li class="active">Добавление статуса</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Статусы
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Добавление
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" action="{{route('admin.retailcrm_order_statuses.store')}}" method="POST" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> Символьные код </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-0" name="sysname" placeholder="Символьные код" value="{{ old('sysname') }}" class="col-sm-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> Название </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-0" name="name" placeholder="Название" value="{{ old('name') }}" class="col-sm-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Статус заказа на сайте </label>
                        <div class="col-sm-9">
                            <select name="status_id" id="form-field-6">
                                <option value="">--Не выбран--</option>
                                @foreach($statuses as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
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
                            <a class="btn btn-info" href="{{route('admin.retailcrm_order_statuses.index')}}">
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
