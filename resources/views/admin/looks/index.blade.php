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
            <li class="active">Looks</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Looks
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех look
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <div class="tabbable">


                        <div class="table-header">
                            Список всех look

                            <div class="ibox-tools">
                                <a href="{{route('admin.looks.create')}}" class="btn btn-success btn-xs">
                                    <i class="fa fa-plus"></i>
                                    Добавить look
                                </a>
                            </div>

                        </div>
                        <div>
                    <div id="dynamic-table_wrapper" class="dataTables_wrapper form-inline no-footer">
                        <!-- PAGE CONTENT BEGINS -->
                        <div class="row">

                            <!-- FILTERS -->
                            <div class="row">
                                <form method="GET" action="{{route('admin.looks.index')}}">
                                    <div class="row">

                                        {{-- On page --}}
                                        <div class="col-xs-2">
                                            <div>
                                                <label>На странице
                                                    <select name="f[perpage]" class="form-control input-sm">
                                                        <option value="10" @if (isset($filters['perpage']) &&  $filters['perpage']== 10) selected="selected" @endif>10</option>
                                                        <option value="25" @if (isset($filters['perpage']) &&  $filters['perpage']== 25) selected="selected" @endif>25</option>
                                                        <option value="50" @if (isset($filters['perpage']) &&  $filters['perpage']== 50) selected="selected" @endif>50</option>
                                                        <option value="100" @if (isset($filters['perpage']) &&  $filters['perpage']== 100) selected="selected" @endif>100</option>
                                                    </select> </label>
                                            </div>
                                        </div>

                                        {{-- Status --}}
                                        <div class="col-xs-2">
                                            <div>
                                                <label>Статус
                                                    <select name="f[status]" class="form-control input-sm">
                                                        <option value="" @if (!isset($filters['status'])) selected="selected" @endif>Все</option>
                                                        <option value="1" @if (isset($filters['status']) &&  $filters['status']==1) selected="selected" @endif>Опубликовано</option>
                                                        <option value="0" @if (isset($filters['status']) &&  $filters['status']==='0') selected="selected" @endif>Черновик</option>

                                                    </select> </label>
                                            </div>
                                        </div>

                                        {{-- Book --}}
                                        <div class="col-xs-2" style="width: 21%">
                                            <div class="dataTables_filter">
                                                <label>Book:
                                                    <select name=f[category_id]" id="form-field-21">
                                                        <option value="">--Не выбран--</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{$category->id}}" @if(isset($filters['category_id']) && $filters['category_id']==$category->id)selected="selected"@endif>{{$category->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </label>
                                            </div>
                                        </div>

                                        {{-- Show soft deleted --}}
                                        <div class="col-xs-2">
                                            <div>
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
                                        {{-- Buttons --}}
                                        <div class="col-xs-3">
                                            <div>
                                                <a class="btn  btn-xs" href="{{route('admin.looks.index')}}?refresh=1">
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
                                        <th>Изображение</th>
                                        <th>Название</th>
                                        <th>Товаров</th>
                                        <th>Book (раздел)</th>
                                        <th>Статус</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody class="ace-thumbnails clearfix">
                                @forelse($looks as $item)
                                    <tr @if($item->trashed())style="background-color: #F6CECE"@endif>
                                        {{-- Image --}}
                                        <td>
                                            @if($item->image)
                                                <a data-rel="colorbox" href="{{$item->uploads->image->url()}}">
                                                    <img src="{{$item->uploads->image->preview->url()}}" width="100" />
                                                </a>
                                            @endif
                                        </td>

                                        {{-- Name --}}
                                        <td>
                                            {{ $item->name }}
                                        </td>

                                        {{-- Products related count --}}
                                        <td>{{ $item->products->count() }}</td>

                                        {{-- Book --}}
                                        <td>@if($item->category) {{$item->category->name}} @endif</td>

                                        {{-- Status --}}
                                        <td class="col-sm-1 center"><i class="ace-icon glyphicon @if($item->status) glyphicon-ok green @else glyphicon-remove red @endif  bigger-120"></i></td>

                                        {{-- Buttons --}}
                                        <td>
                                            <div class="hidden-sm hidden-xs btn-group">
                                                @if($item->trashed())
                                                <form method="POST" action='{{route('admin.looks.restore', $item->id)}}' style="display:inline;">
                                                    <input type="hidden" name="_method" value="PUT">
                                                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                                                    <button class="btn btn-xs btn-purple action-restore" type="button" style="border-width: 1px;">
                                                        <i class="ace-icon fa fa-undo bigger-120"></i>
                                                    </button>
                                                </form>
                                                @else
                                                <a class="btn btn-xs btn-info" href="{{route('admin.looks.edit', $item->id)}}">
                                                    <i class="ace-icon fa fa-pencil bigger-120"></i>
                                                </a>
                                                <form method="POST" action='{{route('admin.looks.destroy', $item->id)}}' style="display:inline;">
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
                                    <p>No Looks found. Please add some first.</p>
                                @endforelse
                                </tbody>
                            </table>

                            <div class="row" style="border-bottom:none;">
                                <div class="col-xs-6">
                                    <div class="dataTables_paginate paging_simple_numbers" id="dynamic-table_paginate">
                                        {!! $looks->render() !!}
                                    </div>
                                </div>
                            </div>


                        </div><!-- /.row -->
                    </div>
                </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->


@stop