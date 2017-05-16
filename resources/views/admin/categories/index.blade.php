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
            <li class="active">Каталог</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Категории товаров
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех категорий
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <div class="tabbable">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active">
                            <a data-toggle="tab" href="{{route('admin.categories.index')}}" aria-expanded="true">
                                Каталог
                            </a>
                        </li>
                        <li>
                            <a  href="{{route('admin.categories.sort')}}">
                                Сортировка
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <div class="table-header">
                            Список всех категорий

                            <div class="ibox-tools">
                                <a href="{{route('admin.categories.create')}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-plus"></i>
                                    Добавить категорию
                                </a>
                            </div>

                        </div>
                        <div>
                    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">

                            <!-- FILTERS -->
                            <div class="row">
                                <form method="GET" action="{{route('admin.categories.index')}}">
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <div class="dataTables_length">
                                                <label>На страниц
                                                    <select name="f[perpage]" class="form-control input-sm">
                                                        <option value="10" @if (isset($filters['perpage']) &&  $filters['perpage']== 10) selected="selected" @endif>10</option>
                                                        <option value="25" @if (isset($filters['perpage']) &&  $filters['perpage']== 25) selected="selected" @endif>25</option>
                                                        <option value="50" @if (isset($filters['perpage']) &&  $filters['perpage']== 50) selected="selected" @endif>50</option>
                                                        <option value="100" @if (isset($filters['perpage']) &&  $filters['perpage']== 100) selected="selected" @endif>100</option>
                                                    </select> </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="dataTables_filter">
                                                <label>Название:
                                                    <input type="text" name="f[name]" value="{{$filters['name'] or ''}}" class="form-control input-sm">
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xs-3">
                                            <div class="dataTables_filter">
                                                <label>Категория:
                                                    <select name="f[id_category]" id="form-field-20">
                                                        <option value="">--Не выбрана--</option>
                                                        @foreach($categories_filter as $category)
                                                            <option value="{{$category->id}}" @if(isset($filters['id_category']) && $filters['id_category']==$category->id)selected="selected"@endif>{{$category->name}}</option>
                                                            @if($category->children->count()))
                                                            @include('admin.categories.dropdown', ['cats' => $category->children, 'index' => 1])
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="dataTables_filter">
                                                <label>ЧПУ:
                                                    <input type="text" name="f[sysname]" value="{{$filters['sysname'] or ''}}" class="form-control input-sm">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="dataTables_length">
                                                <label>Статус
                                                    <select name="f[status]" class="form-control input-sm">
                                                        <option value="" @if (!isset($filters['status'])) selected="selected" @endif>Все</option>
                                                        <option value="1" @if (isset($filters['status']) &&  $filters['status']==1) selected="selected" @endif>Опубликовано</option>
                                                        <option value="0" @if (isset($filters['status']) &&  $filters['status']==='0') selected="selected" @endif>Черновик</option>

                                                    </select> </label>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="dataTables_length">
                                                <div class="checkbox">
                                                    <label class="block">
                                                        <input name="f[deleted]" value="1" type="checkbox" @if (isset($filters['deleted'])) checked="checked" @endif class="ace input-lg">
                                                        <span class="lbl bigger-120"> С удаленными</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-3">
                                            <div class="dataTables_filter">
                                                <a class="btn  btn-xs" href="{{route('admin.categories.index')}}?refresh=1">
                                                    Сбросить
                                                    <i class="ace-icon glyphicon glyphicon-refresh  align-top bigger-125 icon-on-right"></i>
                                                </a>

                                                <button class="btn btn-info btn-xs" type="submit">
                                                    Фильтровать
                                                    <i class="ace-icon glyphicon glyphicon-search  align-top bigger-125 icon-on-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <table id="simple-table" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Иконка</th>
                                    <th>Изображение</th>
                                    <th>Ярлыки</th>
                                    <th>Категория</th>
                                    <th>Товаров</th>
                                    <th>ЧПУ</th>
                                    <th>Статус</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody class="ace-thumbnails clearfix">
                                @forelse($categories as $item)
                                    <tr @if($item->trashed())style="background-color: #F6CECE"@endif>
                                        <td>
                                            <a href="{{route('admin.categories.edit', $item->id)}}">{{$item->name}}</a>
                                        </td>
                                        <td class="col-sm-1 center">
                                            @if($item->icon && $item->uploads)
                                                <img src="{{$item->uploads->icon->original->url()}}" />
                                            @endif
                                        </td>
                                        <td class="col-sm-1 center">
                                            @if($item->img && $item->uploads)
                                                <a data-rel="colorbox" href="{{$item->uploads->img->url()}}">
                                                    <img src="{{$item->uploads->img->preview->url()}}" height="90px" />
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->new) <span class="label label-info">Новинка</span>  </br> @endif
                                            @if($item->act) <span class="label label-warning"><i class="ace-icon fa fa-exclamation-triangle bigger-120"></i>Акция</span>  </br> @endif
                                            @if($item->hit) <span class="label label-danger ">Хит</span> @endif
                                        </td>
                                        <td>@if($item->parent){{$item->parent->name}}@endif</td>
                                        <td>@if($item->products->count())
                                                <span class="badge badge-warning bigger-120">{{$item->products->count()}}</span>
                                                <a class="btn btn-minier btn-info" href="{{route('admin.products.index')}}?f[id_category]={{$item->id}}" title="Товары в категории">
                                                    <i class="ace-icon fa fa-pencil"></i>
                                                </a>
                                                <a class="btn btn-minier btn-pink" href="{{route('admin.products.category.sort', $item->id)}}" title="Сортировка в категории">
                                                    <i class="ace-icon glyphicon glyphicon-list-alt"></i>
                                                </a>
                                            @endif
                                            <a class="btn btn-minier" href="{{route('admin.categories.products', $item->id)}}" title="Привязка товаров">
                                                <i class="ace-icon fa fa-link"></i>
                                            </a>
                                        </td>
                                        <td>{{$item->sysname}}</td>
                                        <td class="col-sm-1 center"><i class="ace-icon glyphicon @if($item->status) glyphicon-ok green @else glyphicon-remove red @endif  bigger-120"></i></td>
                                        <td>
                                            <div class="hidden-sm hidden-xs btn-group">
                                                @if($item->trashed())
                                                <form method="POST" action='{{route('admin.categories.restore', $item->id)}}' style="display:inline;">
                                                    <input type="hidden" name="_method" value="PUT">
                                                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                                                    <button class="btn btn-xs btn-purple action-restore" type="button" style="border-width: 1px;">
                                                        <i class="ace-icon fa fa-undo bigger-120"></i>
                                                    </button>
                                                </form>
                                                @else
                                                <a class="btn btn-xs btn-info" href="{{route('admin.categories.edit', $item->id)}}">
                                                    <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                </a>
                                                <form method="POST" action='{{route('admin.categories.destroy', $item->id)}}' style="display:inline;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                                                    <button class="btn btn-xs btn-danger action-delete" type="button" style="border-width: 1px;">
                                                        <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <p>Нет категорий</p>
                                @endforelse
                                </tbody>
                            </table>

                            <div class="row" style="border-bottom:none;">
                                <div class="col-xs-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
                                        {!! $categories->render() !!}
                                    </div>
                                </div>
                            </div>


                        </div><!-- /.row -->
                    </div>
                </div>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->


@stop