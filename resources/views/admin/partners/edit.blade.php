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
            <li class="active">Редактирование партнера</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Партнеры
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Редактирование
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" action="{{route('admin.partners.update', $partner->id)}}" method="POST">
                    <input type="hidden" name="_method" value="PUT">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                    <input type="hidden" name="manager_id" value="{{Auth::user()->id}}">
                   <div class="form-group">
                       <label class="col-sm-3 control-label no-padding-right" for="form-field-20"> Пользователь </label>
                       <div class="col-sm-9">
                           <input type="text" id="form-field-0" name="name" readonly value="{{$partner->user->name}}" class="col-sm-5">
                       </div>
                   </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> На баллансе </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-0" name="remain_money" readonly value="{{$partner->remain_money}}" class="col-sm-5">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> Списать средства </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-0" name="withdraw_money" value="0" class="col-sm-5">
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
                           <a class="btn btn-info" href="{{route('admin.partners.index')}}">
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
