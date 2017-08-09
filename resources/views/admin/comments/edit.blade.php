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
                <a href="{{route('admin.comments.index')}}">Комментарии</a>
            </li>
            <li class="active">Редактирование комментария</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Комментарии
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Редактирование
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-xs-12 ace-thumbnails">
                <!-- PAGE CONTENT BEGINS -->
                <form class="form-horizontal" role="form" action="{{route('admin.comments.update', $comment->id)}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Дата</label>
                        <div class="col-sm-9">
                            <div class="input-group col-sm-4">
                                <input class=" form-control date-picker" name="date" value="{{old('date', $comment->datePicker())}}" id="form-field-1" type="text" data-date-format="dd.mm.yyyy" />
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar bigger-110"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> Товар </label>
                        <div class="col-sm-9">
                            <select name="product_id" class="chosen-select chosen-autocomplite form-control" id="form-field-0" data-url="{{route('admin.products.search')}}" data-placeholder="Начните ввод...">
                                <option value="">  </option>
                                @if($product)
                                    <option value="{{$product->id}}" selected>{{$product->name}}</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Имя </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-2" name="name" placeholder="Имя" value="{{ old('name', $comment->name) }}" class="col-sm-12">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> E-mail </label>
                        <div class="col-sm-9">
                            <input type="text" id="form-field-3" name="email" placeholder="E-mail" value="{{ old('email', $comment->email) }}" class="col-sm-12">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-17"> Текст </label>
                        <div class="col-sm-9">
                            <textarea id="form-field-17" name="text" placeholder="Текст" class="col-sm-12">{{ old('text', $comment->text) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-17"> Достоинства </label>
                        <div class="col-sm-9">
                            <textarea id="form-field-17" name="pros" placeholder="Достоинства" class="col-sm-12">{{ old('pros', $comment->pros) }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-17"> Недостатки </label>
                        <div class="col-sm-9">
                            <textarea id="form-field-17" name="cons" placeholder="Недостатки" class="col-sm-12">{{ old('cons', $comment->cons) }}</textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-18"> Ответ магазина </label>
                        <div class="col-sm-9">
                            <textarea id="form-field-18" name="answer" placeholder="Текст ответа" class="col-sm-12">{{ old('answer', $comment->answer) }}</textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Активность </label>
                        <div class="col-sm-9">
                            <label>
                                <input type="hidden" name="status" value="0">
                                <input name="status" @if ((old() && old('status')) || (empty(old()) && $comment->status) ) checked="checked" @endif   value="1" class="ace ace-switch ace-switch-4 btn-empty" type="checkbox">
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
                            <a class="btn btn-info" href="{{route('admin.comments.index')}}">
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
