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
                    @if(isset($data['title']))
                        {{$data['title']}}
                    @else
                        Список всех товаров
                    @endif

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
                        @if(!isset($data['hide_sort']))
                        <li>
                            <a  href="{{route('admin.products.sort.unique-offers')}}">
                                Сортировка
                            </a>
                        </li>
                        @endif
                    </ul>
                    <div class="tab-content">
                        <div id="field-img-now" class="tab-pane fade active in">
                            <div class="table-header">
                                @if(isset($data['title']))
                                    {{$data['title']}}
                                @else
                                    Список всех товаров
                                @endif
                                @if(!isset($data['hide_tools']))
                                <div class="ibox-tools">
                                    <a href="{{route('admin.products.create')}}" class="btn btn-success btn-xs">
                                        <i class="fa fa-plus"></i>
                                        Добавить товар
                                    </a>
                                </div>
                                @endif
                            </div>
                            <div>
                                <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                                    <!-- PAGE CONTENT BEGINS -->
                                    <div class="row">

                                        <!-- FILTERS -->
                                        @if(!isset($data['hide_filter']))
                                        <div class="row">
                                            <form method="GET" action="{{route('admin.products-quick_edit')}}">
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="">
                                                            <label>Название:
                                                                <input type="text" name="f[name]" value="{{$filters['name'] or ''}}" class="form-control input-sm">
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-3">
                                                        <div class="">
                                                            <label>ЧПУ:
                                                                <input type="text" name="f[sysname]" value="{{$filters['sysname'] or ''}}" class="form-control input-sm">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <div class="">
                                                            <label>Статус
                                                                <select name="f[status]" class="form-control input-sm">
                                                                    <option value="" @if (!isset($filters['status'])) selected="selected" @endif>Все</option>
                                                                    <option value="1" @if (isset($filters['status']) &&  $filters['status']==1) selected="selected" @endif>Опубликовано</option>
                                                                    <option value="0" @if (isset($filters['status']) &&  $filters['status']==='0') selected="selected" @endif>Черновик</option>

                                                                </select> </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <div class="dataTables_length">
                                                            <div class="checkbox">
                                                                <label class="block">
                                                                    <input name="f[deleted]" value="1" type="checkbox" @if (isset($filters['deleted'])) checked="checked" @endif class="ace input-lg">
                                                                    <span class="lbl"> С удаленными</span>
                                                                </label>
                                                            </div>
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
                                                            <label>Тэг:
                                                                <select name="f[tag]" class="form-control input-sm" style="width:80%">
                                                                    <option value="">--Не выбрана--</option>
                                                                    @foreach($tags as $tag)
                                                                        <option value="{{$tag->id}}" @if (isset($filters['tag']) &&  $filters['tag']== $tag->id) selected="selected" @endif>{{$tag->name}}</option>
                                                                    @endforeach
                                                                </select> </label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <div class="">
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
                                                        <div class="">
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
                                        @endif
                                        <form class="row col-xs-12 js-groups-data">
                                            <diw class="row">
                                                <div class="h4">Групповое редактирование</div>
                                                <div class="h6">Применяется к выбранным элементам на странице</div>
                                            </diw>
                                            <div class="col-xs-4">
                                                <div class="">
                                                    <label>Скидка %:
                                                        <input name="group-discount" value="0" class="form-control input-sm " data-target="#simple-table" type="number">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-xs-2">
                                                <button class="btn btn-info btn-xs js-discount-apply" type="button">Применить к выбранным</button>
                                            </div>
                                            <div class="col-xs-2">
                                                <button class="btn btn-info btn-xs js-discount-apply-all" type="button">Применить ко всем</button>
                                            </div>
                                            <div class="dataTables_filter col-xs-4">
                                                {{--<button class="btn btn-info btn-xs js-discount-apply-space">Сохранить для всех товаров</button>--}}
                                            </div>
                                        </form>
                                        <div class="row">
                                            <table id="simple-table" class="table table-striped table-bordered table-hover" data-url="{{route('ajax.product.quick_save')}}">
                                                <thead>
                                                <tr>
                                                    <th>Выбрать</th>
                                                    <th>Название</th>
                                                    <th>Цена в МойСклад руб.</th>
                                                    <th>Цена руб.</th>
                                                    <th>Скидка %</th>
                                                    <th>Изображение</th>
                                                    <th>ЧПУ</th>
                                                    <th>Ярлыки</th>
                                                    <th>Статус</th>
                                                    <th></th>
                                                </tr>
                                                </thead>

                                                <tbody class="ace-thumbnails clearfix">
                                                @forelse($products as $item)
                                                    <tr @if($item->trashed())style="background-color: #F6CECE"@endif class="calculate js-item js-quick-save-product" data-url="{{route('ajax.product.quick_save')}}">
                                                        <td>
                                                            <label class="block">
                                                                <input name="group" data-id="{{$item->id}}" value="0" autocomplete="off" class="ace input-lg" type="checkbox">
                                                                <span class="lbl"></span>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="id" value="{{$item->id}}"/>
                                                            <a href="{{route('admin.products.edit', $item->id)}}">{{$item->name}}</a>
                                                        </td>

                                                        <td>
                                                            {{$item->price_old}}
                                                            <input name="price_old" type="hidden" value="{{$item->price_old}}" autocomplete="off">
                                                        </td>
                                                        <td>
                                                            <input name="price" type="number" value="{{$item->price}}" autocomplete="off">
                                                        </td>
                                                        <td>
                                                            <input name="discount" type="number" value="{{$item->discount}}" autocomplete="off">
                                                        </td>
                                                        <td class="col-sm-1 center">
                                                            @if($item->uploads)
                                                                <a data-rel="colorbox" href="{{ $item->uploads->img->url() }}">
                                                                    <img src="{{ $item->uploads->img->preview->url() }}" />
                                                                </a>
                                                            @endif
                                                        </td>
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
        <div>
            <div class="quick-panel js-quick-panel">
                <div class="wrapper">
                    <button class="js-quick-save btn btn-info btn-xs" data-target="#simple-table">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div><!-- /.page-content -->
    <div id="saveComplete" class="saved">С о х р а н е н о</div>
    <div id="js-save-error" class="saved error">Ошибка</div>
@stop