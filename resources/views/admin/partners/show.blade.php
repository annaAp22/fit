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
                <a href="{{route('admin.partners.index')}}">Партнеры</a>
            </li>
            <li class="active">Просмотр партнера</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Партнеры
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Просмотр
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="form-horizontal">
                   <div class="form-group">
                       <label class="col-sm-3 control-label no-padding-right" for="form-field-20"> Имя </label>
                       <div class="col-sm-5">
                           <input type="text" id="form-field-0" name="name" readonly value="{{ $partner->user->name }}" class="col-sm-12">
                       </div>
                   </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-20"> E-mail </label>
                        <div class="col-sm-5">
                            <input type="text" id="form-field-0" name="email" readonly value="{{ $partner->user->email }}" class="col-sm-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-20"> Телефон </label>
                        <div class="col-sm-5">
                            <input type="text" id="form-field-0" name="phone" readonly value="{{ $partner->user->phone }}" class="col-sm-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-20"> Код </label>
                        <div class="col-sm-5">
                            <input type="text" id="form-field-0" name="code" readonly value="{{ $partner->code }}" class="col-sm-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-20"> Всего заработано </label>
                        <div class="col-sm-5">
                            <input type="text" id="form-field-0" name="make_money" readonly value="{{ $partner->make_money }}" class="col-sm-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-20"> Списано со счета </label>
                        <div class="col-sm-5">
                            <input type="text" id="form-field-0" name="remain_money" readonly value="{{ $partner->spent_money }}" class="col-sm-12">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-20"> На баллансе </label>
                        <div class="col-sm-5">
                            <input type="text" id="form-field-0" name="spent_money" readonly value="{{ $partner->remain_money }}" class="col-sm-12">
                        </div>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@stop
