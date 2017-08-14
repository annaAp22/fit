@extends('admin.layout')
@section('main')
    <!-- Breadcrumbs Start -->
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
                <a href="{{route('admin.looks.index')}}">Looks</a>
            </li>
            <li class="active">Добавление look</li>
        </ul><!-- /.breadcrumb -->


    </div>
    <!-- Breadcrumbs End -->

    <div class="page-content">

        <div class="page-header">
            <h1>
                Look
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Добавление
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" action="{{route('admin.looks.store')}}" method="POST" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">

                    <div class="form-group">
                        <label for="form-field-0" class="col-sm-3 control-label no-padding-right"> Book (раздел)</label>
                        <div class="col-sm-9">
                            <select name="category_id" id="form-field-0" class="form-control">
                                <option value="">--Не выбрана--</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" @if(old() && old('category_id')==$category->id)selected="selected"@endif>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Название </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" name="name" placeholder="Название" value="{{ old('name') }}" class="col-sm-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-77"> Товары на изображении</label>
                        <div class="col-sm-9">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h4 class="smaller">
                                        Look
                                        <small>Добавьте новую точку и выберите товар</small>
                                    </h4>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main">
                                        @for($i = 1; $i<= 10; $i++)
                                            <div class="form-group dot-related" id="dot-related_{{$i}}">
                                                <label class="col-sm-1 control-label no-padding-right" for="form-field-5">Товар {{ $i }}: </label>
                                                <div class="col-sm-11">
                                                    <select name="products[{{$i}}]" class="chosen-select chosen-autocomplite form-control" id="form-field-7{{$i}}" data-url="{{route('admin.products.search')}}" data-placeholder="Начните ввод..." disabled>
                                                        <option value="">  </option>
                                                        @if($products && $products->has($i))
                                                            <option value="{{$products->get($i)->id}}" selected>{{$products->get($i)->name}}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        @endfor

                                        <button class="btn btn-success" id="add-moving-dot">Добавить точку</button>&nbsp;&nbsp;&nbsp;
                                        <button class="btn btn-danger" id="remove-moving-dot">Удалить последнюю точку</button>
                                        <div id="moving-wrapper" class="moving-wrapper">
                                            <input id="image" name="image" type="file" value="{{ old('image') }}" class="img-drop" accept="image/*" />
                                        </div>

                                        @include('admin.looks.dots')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Активность </label>
                        <div class="col-sm-9">
                            <label>
                                <input type="hidden" name="status" value="0">
                                <input name="status" @if (old('status') || empty(old())) checked="checked" @endif  value="1" class="ace ace-switch ace-switch-4 btn-empty" type="checkbox">
                                <span class="lbl"></span>
                            </label>
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
                            <a class="btn btn-info" href="{{route('admin.looks.index')}}">
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