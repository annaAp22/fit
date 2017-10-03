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
            <li class="active">Мой склад</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Cинхронизация.
            </h1>
        </div><!-- /.page-header -->
        @include('admin.message')
        <div class="form-group">
            <div>Автоматическая синхронизация товаров выполняется раз в сутки!</div>
            <div>Если были добавлены новые товары, можно выполнить их синхронизацию на этой странице, затем синхронизировать атрибуты(цены, наличие, размеры).</div>
            <div>Помните, слишком часто это делать не нужно, это нагружает сервер.</div>
            <br>
            @if(isset($cron_counter['import_products']))
                <div>Последний раз синхронизация товаров выполнялась <ins>{{$cron_counter['import_products']->updated_at}}</ins> </div>
            @endif
            @if(isset($cron_counter['import_attributes']))
                <div>Последний раз синхронизация аттрибутов выполнялась <ins>{{$cron_counter['import_attributes']->updated_at}}</ins> </div>
            @endif
            <br>
            <div>Желаете синхронизировать что-либо?</div>
        </div>

    </div><!-- /.page-content -->

    <div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">
            <form method="get" action="{{route('admin.moysklad.sync_products')}}" style="display:inline;">
                <input name="_token" type="hidden" value="{{csrf_token()}}">
                <button class="btn btn-info action-sync" type="button">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Синхронизация товаров
                </button>
            </form>
            <form method="get" action="{{route('admin.moysklad.sync_attributes')}}" style="display:inline;">
                <input name="_token" type="hidden" value="{{csrf_token()}}">
                <button class="btn btn-info action-sync" type="button">
                    <i class="ace-icon fa fa-check bigger-110"></i>
                    Синхронизация атрибутов
                </button>
            </form>
        </div>
    </div>
@stop