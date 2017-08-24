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
                <a href="{{route('admin.products.index')}}">Товары</a>
            </li>
            <li class="active">Сортировка товара</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Товары
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Сортировка
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <div class="tabbable">
                    <ul class="nav nav-tabs" id="myTab">
                        <li>
                            <a href="{{route('admin.products.index')}}">
                                Товары
                            </a>
                        </li>
                        <li class="active">
                            <a  href="{{route('admin.products.sort.unique-offers')}}">
                                Сортировка
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="field-img-now" class="tab-pane fade active in">
                            <div class="table-header">
                                Сортировка товаров по типу предложения
                            </div>
                            <div>
                                <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                                    <!-- PAGE CONTENT BEGINS -->
                                    <div class="row">
                                        <div class="row">
                                            <form method="GET" action="{{route('admin.products.sort.unique-offers')}}">
                                                <div class="col-lg-3">
                                                    <div class="">
                                                        <label>Тип предложения:
                                                            <select name="type"  autocomplete="off">
                                                                @foreach($filter['types'] as $key => $val)
                                                                    <option value="{{$key}}" @if($key == $filter['type']) selected="selected" @endif>{{$val}}</option>
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="dataTables_filter">
                                                        <label>Пол:
                                                            <select name="sex" id="form-field-2" autocomplete="off">
                                                                @foreach($filter['sex_types'] as $key => $val)
                                                                    <option value="{{$key}}" @if($key == $filter['sex']) selected="selected" @endif>{{$val}}</option>
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <div class="dataTables_filter">
                                                        <a class="btn  btn-xs" href="{{route('admin.products.sort.unique-offers')}}">
                                                            Сбросить
                                                            <i class="ace-icon glyphicon glyphicon-refresh  align-top bigger-125 icon-on-right"></i>
                                                        </a>
                                                        <button class="btn btn-info btn-xs" type="submit">
                                                            Фильтровать
                                                            <i class="ace-icon glyphicon glyphicon-search  align-top bigger-125 icon-on-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <form class="multisort" action="{{route('admin.products.sort.unique-offers')}}" method="POST">
                                            <input name="_token" type="hidden" value="{{csrf_token()}}">
                                            <input type="hidden" name="type" value="{{$filter['type']}}">
                                            <input type="hidden" name="sex" value="{{$filter['sex']}}">
                                            <div class="dd sortable "  style="max-width: 985px; margin-left:10px;">
                                                <ol class="dd-list">
                                                    @forelse($products as $product)
                                                        <li class="dd-item" data-id="{{$product->id}}">
                                                            <div class="dd-handle">
                                                                @if($product->img)
                                                                    <img src="{{$product->uploads->img->preview->url()}}" width="40px" />
                                                                @endif
                                                                {{$product->name}}
                                                            </div>
                                                        </li>
                                                    @empty
                                                        <p>Нет товаров</p>
                                                    @endforelse
                                                </ol>
                                            </div>

                                            <div class="clearfix form-actions">
                                                <button class="btn btn-success" type="submit">
                                                    <i class="ace-icon fa fa-check"></i>
                                                    Сохранить
                                                </button>&nbsp; &nbsp; &nbsp;
                                            </div>
                                        </form>

                                    </div><!-- /.row -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@stop
