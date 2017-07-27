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
            <li class="active">Добавление товара</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Товары
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
                <form class="form-horizontal" role="form" action="{{route('admin.products.store')}}" method="POST" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-20"> Категория </label>
                        <div class="col-sm-9">
                            <select multiple="" name="categories[]" class="chosen-select form-control tag-input-style" id="form-field-20" data-placeholder="Выберите категории...">
                                <option value="">--Не выбрана--</option>
                                @foreach($categories as $category)
                                <option value="{{$category->id}}" @if(old() && old('categories') && in_array($category->id, old('categories')))selected="selected"@endif>{{$category->name}}</option>
                                @if($category->children->count()))
                                    @include('admin.products.dropdown', ['cats' => $category->children, 'index' => 1])
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-21"> Бренд </label>
                        <div class="col-sm-9">
                            <select name="brand_id" id="form-field-21">
                                <option value="">--Не выбран--</option>
                                @foreach($brands as $brand)
                                <option value="{{$brand->id}}" @if(old() && old('brand_id')==$brand->id)selected="selected"@endif>{{$brand->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> Название </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-0" name="name" placeholder="Название" value="{{ old('name') }}" class="col-sm-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ЧПУ (URL) </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-1" name="sysname" placeholder="sysname" value="{{ old('sysname') }}" class="col-sm-5">
                            <span class="help-button" data-rel="popover" data-trigger="hover" data-placement="left" data-content="Если оставить пустым, будет автоматически сгенерированно из Названия" title="">?</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-23"> Описание </label>
                        <div class="col-sm-9">
                            <textarea name="descr" class="form-control limited" id="form-field-23" maxlength="200" class="col-sm-12">{{old('descr')}}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Изображение </label>
                        <div class="col-sm-9">
                            <input name="img" type="file" value="{{ old('img') }}" class="img-drop" accept="image/*" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-14"> Дополнительные изображения </label>
                        <div class="col-sm-9">
                            <div class="dynamic-input">
                                <div class="input-group dynamic-input-item col-sm-5" style="margin-bottom:5px;">
                                    <div class="col-sm-10">
                                        <input type="file" name="photos[]" class="file-input-img col-sm-12"  accept="image/*" />
                                    </div>
                                    <div class="col-sm-2">
                                        <a href="" class="input-group-addon plus">
                                            <i class="glyphicon glyphicon-plus bigger-110"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group calculate">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-10"> Цена </label>
                        <div class="col-sm-9" style="padding-left: 12px;">
                            <div class="col-sm-2 input-group" style="float: left;">
                                <input name="price" value="{{old('price')}}" placeholder="Цена" type="text" id="form-field-10" class="input-number form-control" title="Цена на сайте">
                                <span class="input-group-addon">
                                    <i class="fa fa-rub bigger-110"></i>
                                </span>
                            </div>
                            <div class="col-sm-2 input-group" style="float: left; margin-left: 20px;">
                                <input name="discount" value="{{old('discount')}}" placeholder="Скидка" type="text" class="form-control input-number">
                                <span class="input-group-addon">%</span>
                            </div>
                            <div class="col-sm-3 input-group" style="float: left; margin-left: 20px;">
                                <input name="price_old" value="{{old('price_old')}}" placeholder="Цена в МойСклад" type="text" class="input-number form-control" title="Цена в МойСклад">
                                <span class="input-group-addon">
                                    <i class="fa fa-rub bigger-110"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-11"> Артикул </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-11" name="sku" placeholder="Название" value="{{ old('sku') }}" class="col-sm-2">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> Ярлыки </label>
                        <div class="col-sm-9">
                            <label class="block">
                                <input name="new" value="1" type="checkbox" @if (old('new')) checked="checked" @endif class="ace input-lg">
                                <span class="lbl bigger-120"> Новинка</span>
                            </label>
                            <label class="block">
                                <input name="act" value="1" type="checkbox" @if (old('act')) checked="checked" @endif class="ace input-lg">
                                <span class="lbl bigger-120"> Акция</span>
                            </label>
                            <label class="block">
                                <input name="hit" value="1" type="checkbox" @if (old('hit')) checked="checked" @endif class="ace input-lg">
                                <span class="lbl bigger-120"> Хит</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> Выгрузка в сторонние сервисы </label>
                        <div class="col-sm-9">
                            <label>
                                <input name="ya_market" type="checkbox" class="ace input-lg" value="1" @if (old() && old('ya_market') == 1) checked="checked" @endif>
                                <span class="lbl"> Yandex Market</span>
                            </label>&nbsp;
                            <label>
                                <input name="merchant" type="checkbox" class="ace input-lg" value="1" @if (old() && old('merchant') == 1) checked="checked" @endif>
                                <span class="lbl"> Google Merchant Center</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right"> Наличие </label>
                        <div class="col-sm-9">
                            <label class="block">
                                <input name="stock" type="radio" class="ace" value="1" @if (old('stock') == 1 || !old()) checked="checked" @endif>
                                <span class="lbl"> В наличии</span>
                            </label>&nbsp;
                            <label class="block">
                                <input name="stock" type="radio" class="ace" value="0" @if (old() && old('stock') == 0) checked="checked" @endif>
                                <span class="lbl"> Под заказ</span>
                            </label>
                        </div>
                    </div>
                    @if($attributes->count())
                        @include('admin.products.attributes', ['attributes' => $attributes, 'product' => null])
                    @endif

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-12"> Теги </label>
                        <div class="col-sm-9">

                            <select multiple="" name="tags[]" class="chosen-select form-control tag-input-style" id="form-field-12" data-placeholder="Выберите тэг...">
                                @foreach($tags as $tag)
                                <option value="{{$tag->id}}" @if(old() && old('tags') && in_array($tag->id, old('tags')))selected="selected"@endif>{{$tag->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-12"> Комплекты (Old)</label>
                        <div class="col-sm-9">

                            <select multiple="" name="kits[]" class="chosen-select form-control tag-input-style" id="form-field-12" data-placeholder="Выберите комплект...">
                                @foreach($kits as $kit)
                                    <option value="{{$kit->id}}" @if(old() && old('kits') && in_array($kit->id, old('kits')))selected="selected"@endif>{{$kit->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-77"> Товары в комплекте (New)</label>
                        <div class="col-sm-9">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h4 class="smaller">
                                        Товары
                                        <small>Выберите из списка</small>
                                    </h4>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main">
                                        @for($i = 0; $i<10; $i++)
                                            <select name="related[{{$i}}]" class="chosen-select chosen-autocomplite form-control" id="form-field-7{{$i}}" data-url="{{route('admin.products.search')}}" data-placeholder="Начните ввод...">
                                                <option value="">  </option>
                                                @if($related && $related->has($i))
                                                    <option value="{{$related->get($i)->id}}" selected>{{$related->get($i)->name}}</option>
                                                @endif
                                            </select>
                                            <br><br>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Текст </label>
                        <div class="col-sm-9">
                            <textarea class="ck-editor" id="editor2" name="text">{{ old('text') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="video-url-field"> Видео (URL) </label>
                        <div class="col-sm-9">
                            <input type="text" id="video-url-field" name="video_url" placeholder="Ссылка на видео" value="{{ old('video_url') }}" class="col-sm-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Title </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-6" name="title" placeholder="Title" value="{{ old('title') }}" class="col-sm-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-7"> Description </label>
                        <div class="col-sm-9">
                            <textarea id="form-field-7" name="description" placeholder="Description" class="col-sm-12">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-8"> Keywords </label>
                        <div class="col-sm-9">
                            <textarea id="form-field-8" name="keywords" placeholder="Keywords" class="col-sm-12">{{ old('keywords') }}</textarea>
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
                            <a class="btn btn-info" href="{{route('admin.categories.index')}}">
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
