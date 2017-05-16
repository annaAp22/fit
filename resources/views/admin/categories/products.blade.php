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
                <a href="{{route('admin.categories.index')}}">Каталог</a>
            </li>

            <li>
                <a href="{{route('admin.categories.edit', $category->id)}}">{{ $category->name }}</a>
            </li>

            <li class="active">Привязка товаров</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Категории товаров
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    {{ $category->name }} / привязка товаров
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" action="{{route('admin.categories.products.sync', $category->id)}}" method="POST" enctype="multipart/form-data">
                	{{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-77">Товары категории &laquo;{{$category->name}}&raquo;</label>
                        <div class="col-sm-9">
                            <dynamic-products :initial-products="{{ json_encode($category->products) }}"></dynamic-products>
                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-success" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Сохранить
                            </button>
                            &nbsp; &nbsp; &nbsp;
                            <a class="btn btn-info" href="{{route('admin.categories.index')}}">
                                <i class="ace-icon glyphicon glyphicon-backward bigger-110"></i>
                                Назад
                            </a>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
