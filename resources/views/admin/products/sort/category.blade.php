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
                <div class="table-header" style="max-width: 985px; margin-left:10px;">
                    Сортировка товаров в категории {{$category->name}}
                </div>
                <div>
                    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">
                            <form class="multisort" action="{{route('admin.products.category.sort.save', $category->id)}}" method="POST">
                                <input name="_token" type="hidden" value="{{csrf_token()}}">
                                <div class="dd sortable "  style="max-width: 985px; margin-left:10px;">
                                    <ol class="dd-list">
                                        @forelse($products as $product)
                                            <li class="dd-item" data-id="{{$product->id}}">
                                                <div class="dd-handle">
                                                    @if($product->img)
                                                        <img src="{{$product->uploads->img->preview->url()}}" width="40px" />
                                                    @endif
                                                    {{$product->name}}

                                                    <div class="pull-right">
                                                        <div class="col-sm-4 input-group">
                                                            <input name="prices[{{$product->id}}]" value="{{$product->price}}" placeholder="Цена" type="text" class="input-number form-control input-sm">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-rub bigger-110"></i>
                                                            </span>
                                                        </div>
                                                        <div class="checkbox" style="margin-left: 20px;">
                                                            <label class="block">
                                                                <input name="stocks[{{$product->id}}]" value="1" type="checkbox" class="ace input-lg" @if($product->stock) checked @endif>
                                                                <span class="lbl bigger-120"> В наличии</span>
                                                            </label>
                                                        </div>
                                                    </div>
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
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@stop
