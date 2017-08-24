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
            <li class="active">Товары</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Товары
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех товаров
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <div class="tabbable">

                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active">
                            <a data-toggle="tab" href="{{route('admin.products.index')}}" aria-expanded="true">
                                Товары
                            </a>
                        </li>
                        <li>
                            <a  href="{{route('admin.products.sort.unique-offers')}}">
                                Сортировка
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="field-img-now" class="tab-pane fade active in">
                            <div class="table-header">
                                Список всех товаров

                                <div class="ibox-tools">
                                    <a href="{{route('admin.products.create')}}" class="btn btn-success btn-xs">
                                        <i class="fa fa-plus"></i>
                                        Добавить товар
                                    </a>
                                </div>

                            </div>
                            <div>
                                <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                                    <!-- PAGE CONTENT BEGINS -->
                                    <div class="row">

                                        <!-- FILTERS -->
                                        <div class="row">
                                            <form method="GET" action="{{route('admin.products.index')}}">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="dataTables_filter">
                                                            <label>Название:
                                                                <input type="text" name="f[name]" value="{{$filters['name'] or ''}}" class="form-control input-sm">
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="dataTables_filter">
                                                            <label>Категория:
                                                                <select name="f[id_category]" id="form-field-20">
                                                                    <option value="">--Не выбрана--</option>
                                                                    @foreach($categories as $category)
                                                                        <option value="{{$category->id}}" @if(isset($filters['id_category']) && $filters['id_category']==$category->id)selected="selected"@endif>{{$category->name}}</option>
                                                                        @if($category->children->count()))
                                                                        @include('admin.products.dropdown', ['cats' => $category->children, 'index' => 1])
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-4">
                                                        <div class="dataTables_filter">
                                                            <label>Бренд:
                                                                <select name=f[brand_id]" id="form-field-21">
                                                                    <option value="">--Не выбран--</option>
                                                                    @foreach($brands as $brand)
                                                                        <option value="{{$brand->id}}" @if(isset($filters['brand_id']) && $filters['brand_id']==$brand->id)selected="selected"@endif>{{$brand->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="dataTables_filter">
                                                            <label>ЧПУ:
                                                                <input type="text" name="f[sysname]" value="{{$filters['sysname'] or ''}}" class="form-control input-sm">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($attributes->count())
                                                    @foreach($attributes->chunk(3) as $block)
                                                        <div class="row">
                                                            @foreach($block as $attribute)
                                                                <div class="col-xs-4">
                                                                    <div class="dataTables_filter">
                                                                        <label>{{$attribute->name}}:
                                                                            @if($attribute->type == 'list')
                                                                                <select name="f[attributes][{{$attribute->id}}]"  class="form-control input-sm">
                                                                                    <option value="">--Не выбран--</option>
                                                                                    @forelse($attribute->getLists() as $key => $value)
                                                                                        <option value="{{$value}}" @if(isset($filters['attributes'][$attribute->id]) && $filters['attributes'][$attribute->id]==$value)selected="selected"@endif>{{$value}}</option>
                                                                                        @endforeach
                                                                                </select>
                                                                            @else
                                                                                <input type="text" name="f[attributes][{{$attribute->id}}]" value="{{$filters['attributes'][$attribute->id] or ''}}" class="form-control input-sm">
                                                                            @endif
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                @endif
                                                <div class="row">
                                                    <div class="col-xs-2 col-lg-4">
                                                        <div class="dataTables_filter">
                                                            <label>Тэг:
                                                                <select name="f[tag]" class="form-control input-sm" style="width:80%">
                                                                    <option value="">--Не выбрана--</option>
                                                                    @foreach($tags as $tag)
                                                                        <option value="{{$tag->id}}" @if (isset($filters['tag']) &&  $filters['tag']== $tag->id) selected="selected" @endif>{{$tag->name}}</option>
                                                                    @endforeach
                                                                </select> </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="dataTables_filter">
                                                            <label>Статус
                                                                <select name="f[status]" class="form-control input-sm">
                                                                    <option value="" @if (!isset($filters['status'])) selected="selected" @endif>Все</option>
                                                                    <option value="1" @if (isset($filters['status']) &&  $filters['status']==1) selected="selected" @endif>Опубликовано</option>
                                                                    <option value="0" @if (isset($filters['status']) &&  $filters['status']==='0') selected="selected" @endif>Черновик</option>

                                                                </select> </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3 col-lg-4">
                                                        <div class="dataTables_filter">
                                                            <div class="checkbox">
                                                                <label class="block">
                                                                    <input name="f[deleted]" value="1" type="checkbox" @if (isset($filters['deleted'])) checked="checked" @endif class="ace input-lg">
                                                                    <span class="lbl bigger-120"> С удаленными</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="dataTables_filter">
                                                            <label>Сортировка:
                                                                <select name="f[sort]">
                                                                    <option value="">--Не выбрана--</option>
                                                                    @foreach($sorts as $key => $value)
                                                                        <option value="{{$key}}" @if(isset($filters['sort']) && $filters['sort'] == $key) selected="selected" @endif>{{$value}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="dataTables_length">
                                                            <label>На странице
                                                                <select name="f[perpage]" class="form-control input-sm">
                                                                    <option value="10" @if (isset($filters['perpage']) &&  $filters['perpage']== 10) selected="selected" @endif>10</option>
                                                                    <option value="25" @if (isset($filters['perpage']) &&  $filters['perpage']== 25) selected="selected" @endif>25</option>
                                                                    <option value="50" @if (isset($filters['perpage']) &&  $filters['perpage']== 50) selected="selected" @endif>50</option>
                                                                    <option value="100" @if (isset($filters['perpage']) &&  $filters['perpage']== 100) selected="selected" @endif>100</option>
                                                                </select> </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <div class="dataTables_filter">
                                                            <a class="btn  btn-xs" href="{{route('admin.products.index')}}?refresh=1">
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
                                        <div class="row">
                                            <table id="simple-table" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Название</th>
                                                    <th>Цена</th>
                                                    <th>Изображение</th>
                                                    <th>Категории</th>
                                                    <th>Бренд</th>
                                                    <th>ЧПУ</th>
                                                    <th>Ярлыки</th>
                                                    <th>Теги</th>
                                                    <th>Статус</th>
                                                    <th></th>
                                                </tr>
                                                </thead>

                                                <tbody class="ace-thumbnails clearfix">
                                                @forelse($products as $item)
                                                    <tr @if($item->trashed())style="background-color: #F6CECE"@endif>
                                                        <td>
                                                            <a href="{{route('admin.products.edit', $item->id)}}">{{$item->name}}</a>
                                                        </td>
                                                        <td>{{$item->price}} руб.</td>
                                                        <td class="col-sm-1 center">
                                                            @if($item->uploads)
                                                                <a data-rel="colorbox" href="{{ $item->uploads->img->url() }}">
                                                                    <img src="{{ $item->uploads->img->preview->url() }}" />
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td>@if($item->categories->count()) {{ $item->categories->implode('name', ', ')}} @endif</td>
                                                        <td>@if($item->brand) {{$item->brand->name}} @endif</td>
                                                        <td>{{$item->sysname}}</td>
                                                        <td>
                                                            <label class="block">
                                                                <input name="new" data-id="{{$item->id}}" data-url="{{route('ajax.product-check')}}" value="1" type="checkbox" autocomplete="off" @if($item->new) checked="checked" @endif class="ace input-lg js-save-check">
                                                                <span class="lbl"> Новинка</span>
                                                            </label>
                                                            <label class="block">
                                                                <input name="act" data-id="{{$item->id}}" data-url="{{route('ajax.product-check')}}" value="1" type="checkbox" autocomplete="off" @if($item->act) checked="checked" @endif class="ace input-lg js-save-check">
                                                                <span class="lbl"> Акция</span>
                                                            </label>
                                                            <label class="block">
                                                                <input name="hit" data-id="{{$item->id}}" data-url="{{route('ajax.product-check')}}" value="1" type="checkbox" autocomplete="off" @if($item->hit) checked="checked" @endif class="ace input-lg js-save-check">
                                                                <span class="lbl"> Хит</span>
                                                            </label>
                                                            <br>
                                                            {{--@if($item->new) <span class="label label-info">Новинка</span>  </br> @endif--}}
                                                            {{--@if($item->act) <span class="label label-warning"><i class="ace-icon fa fa-exclamation-triangle bigger-120"></i>Акция</span>  </br> @endif--}}
                                                            {{--@if($item->hit) <span class="label label-danger ">Хит</span> </br> @endif--}}
                                                            @if($item->stock)
                                                                <span class="label  label-yellow arrowed-in arrowed-in-right">В наличии</span>
                                                            @else
                                                                <span class="label  label-purple arrowed">Под заказ</span>
                                                            @endif

                                                        </td>
                                                        <td>
                                                            @foreach($item->tags as $tag)
                                                                <span class="label label-info arrowed-in-right arrowed">{{$tag->name}}</span>
                                                            @endforeach
                                                        </td>
                                                        <td class="col-sm-1 center"><i class="ace-icon glyphicon @if($item->status) glyphicon-ok green @else glyphicon-remove red @endif  bigger-120"></i></td>
                                                        <td class="col-sm-1 center">
                                                            <div class="hidden-sm hidden-xs btn-group">
                                                                @if($item->trashed())
                                                                    <form method="POST" action='{{route('admin.products.restore', $item->id)}}' style="display:inline;">
                                                                        <input type="hidden" name="_method" value="PUT">
                                                                        <input name="_token" type="hidden" value="{{csrf_token()}}">
                                                                        <button class="btn btn-xs btn-purple action-restore" type="button" style="border-width: 1px;">
                                                                            <i class="ace-icon fa fa-undo bigger-120"></i>
                                                                        </button>
                                                                    </form>

                                                                    <form action="{{ route('admin.products.remove', ['id' => $item->id]) }}" method="post" style="float: right" title="Безвозвратно удалить">
                                                                        <input name="_token" type="hidden" value="{{csrf_token()}}">
                                                                        <button class="btn btn-xs btn-danger action-delete" type="button" style="border-width: 1px;">
                                                                            <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <a class="btn btn-xs btn-info" href="{{route('admin.products.edit', $item->id)}}">
                                                                        <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                                    </a>
                                                                    <form method="POST" action='{{route('admin.products.destroy', $item->id)}}' style="display:inline;">
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
                                                    <p>Нет товаров</p>
                                                @endforelse
                                                </tbody>
                                            </table>
                                        </div>


                                        <div class="row" style="border-bottom:none;">
                                            <div class="col-xs-6">
                                                <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
                                                    {!! $products->render() !!}
                                                </div>
                                            </div>
                                        </div>


                                    </div><!-- /.row -->
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
    <div id="saveComplete" class="saved">Сохранено</div>
@stop