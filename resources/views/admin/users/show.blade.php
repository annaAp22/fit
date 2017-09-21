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
                <a href="{{route('admin.users.index')}}">Пользователи</a>
            </li>
            <li class="active">Просмотр пользователя</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Пользователи
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
                    <input type="hidden" name="_method" value="PUT">
                   <div class="form-group">
                       <label class="col-sm-3 control-label no-padding-right" for="form-field-20"> Роль </label>
                       <div class="col-sm-9">
                           <input type="text" id="form-field-0" name="group" readonly value="{{$user->group->name}}" class="col-sm-12">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> Имя </label>
                       <div class="col-sm-9">
                           <input type="text" id="form-field-0" name="name" placeholder="Имя" value="{{ $user->name }}" class="col-sm-12">
                       </div>
                   </div>

                   <div class="form-group">
                       <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> E-mail </label>
                       <div class="col-sm-9">
                           <input type="email" id="form-field-1" name="email" placeholder="E-mail" value="{{ $user->email }}" class="col-sm-12">
                       </div>
                   </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Телефон </label>
                        <div class="col-sm-9">
                            <input type="email" id="form-field-1" name="phone" placeholder="E-mail" value="{{ $user->phone }}" class="col-sm-12">
                        </div>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@stop
