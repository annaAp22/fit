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
            <li class="active">Сортировка</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Категории товаров
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Сортировка категорий
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">

                <div class="tabbable">
                    <ul class="nav nav-tabs" id="myTab">
                        <li>
                            <a href="{{route('admin.categories.index')}}" >
                                Каталог
                            </a>
                        </li>
                        <li class="active">
                            <a data-toggle="tab" href="{{route('admin.categories.sort')}}" aria-expanded="true">
                                Сортировка
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="field-img-now" class="tab-pane fade active in">
                            <div class="table-header">
                                Сортировка категорий
                            </div>
                            <div>
                                <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                                    <!-- PAGE CONTENT BEGINS -->
                                    <div class="row">
                                        <form class="multisort" action="{{route('admin.categories.sort.save')}}" method="POST">
                                            <input name="_token" type="hidden" value="{{csrf_token()}}">
                                            <div class="dd nestable" style="max-width: 985px; margin-left:10px;">
                                                <ol class="dd-list">
                                                    @forelse($categories as $cat)
                                                        <li class="dd-item" data-id="{{$cat->id}}">
                                                            <div class="dd-handle">
                                                                @if($cat->uploads)
                                                                    <img src="{{$cat->uploads->icon->url()}}" width="65" />
                                                                @endif
                                                                {{$cat->name}}
                                                            </div>
                                                            @if($cat->children->count())
                                                            @include('admin.categories.sort_inner', ['cats' => $cat->children()->orderBy('sort')->get()])
                                                            @endif
                                                        </li>
                                                    @empty
                                                        <p>Нет категорий</p>
                                                    @endforelse
                                                </ol>
                                            </div>

                                            <div class="clearfix form-actions">
                                                <button class="btn btn-success" type="submit">
                                                    <i class="ace-icon fa fa-check"></i>
                                                    Сохранить сортировку
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
    </div><!-- /.page-content -->


@stop