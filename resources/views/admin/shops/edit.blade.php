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
            <li class="active">Редактирование магазина</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="page-header">
            <h1>
                Магазин
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Редактирование
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" action="{{route('admin.shops.update', $shop->id)}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">Название</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" placeholder="Название" value="{{ old('title', $shop->title) }}" class="col-sm-5" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> Закрепить за городом</label>
                        <div class="col-sm-9">
                            <div class="col-sm-5 no-padding">
                                <select name="city_id" class="chosen-select">
                                    <option value="">  </option>
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}" @if($city->id == $shop->city_id)selected @endif>{{$city->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">адрес</label>
                        <div class="col-sm-9">
                            <input type="text" name="address" placeholder="Название" value="{{ old('address', $shop->address) }}" class="col-sm-5">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">Телефон</label>
                        <div class="col-sm-9">
                            <input type="text" name="phone" placeholder="Телефон" value="{{ old('phone', $shop->phone) }}" class="col-sm-5">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">Email</label>
                        <div class="col-sm-9">
                            <input type="text" name="email" placeholder="shop@mail.ru" value="{{ old('email', $shop->email) }}" class="col-sm-5">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">Ссылка на магазин</label>
                        <div class="col-sm-9">
                            <input type="text" name="link" placeholder="Название" value="{{ old('link', $shop->link) }}" class="col-sm-5">
                            <span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Если ссылок несколько, то перечисляем их через запятую." title="">?</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">Долгота</label>
                        <div class="col-sm-9">
                            <input type="text" name="long" placeholder="0.0" value="{{ old('long', $shop->long) }}" class="col-sm-5" required/>
                            <span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Используется для координат." title="">?</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">Широта</label>
                        <div class="col-sm-9">
                            <input type="text" name="lat" placeholder="0.0" value="{{ old('long', $shop->lat) }}" class="col-sm-5" required/>
                            <span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Используется для координат." title="">?</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Активность </label>
                        <div class="col-sm-9">
                            <label>
                                <input type="hidden" name="status" value="0">
                                <input name="status" @if (old('status') || empty(old()) && $shop->status) checked="checked" @endif  value="1" class="ace ace-switch ace-switch-4 btn-empty" type="checkbox">
                                <span class="lbl"></span>
                            </label>
                        </div>
                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-info" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Сохранить
                            </button>

                            &nbsp; &nbsp; &nbsp;
                            <button class="btn" type="reset">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Обновить
                            </button>
                        </div>
                    </div>

                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@stop
