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
                <a href="{{route('admin.orders.index')}}">Заказы</a>
            </li>
            <li class="active">Редактирование заказа</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Заказы
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Редактирование
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row cart">
            <div class="col-sm-8">
                <div class="widget-box">
                    <div class="widget-header widget-header-flat">
                        <h4 class="widget-title smaller">
                            <i class="ace-icon fa fa-quote-left smaller-80"></i>
                            Данные по заказу № {{ $order->id }}
                        </h4>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main">
                            <!-- PAGE CONTENT BEGINS -->
                            <form class="form-horizontal" role="form" action="{{route('admin.orders.update', $order->id)}}" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="_method" value="PUT">
                                <input name="_token" type="hidden" value="{{csrf_token()}}">

                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Дата</label>
                                    <div class="col-sm-9">
                                        <div class="input-group col-sm-4">
                                            <input class=" form-control date-timepicker" name="datetime" value="{{old('datetime', $order->datePicker())}}" id="form-field-1" type="text" data-date-format="DD.MM.YYYY H:mm" />
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar bigger-110"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> ФИО </label>
                                    <div class="col-sm-9">
                                        <input type="text" id="form-field-2" name="name" placeholder="ФИО" value="{{ old('name', $order->name) }}" class="col-sm-12">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-3"> E-mail </label>
                                    <div class="col-sm-9">
                                        <input type="email" id="form-field-3" name="email" placeholder="E-mail" value="{{ old('email', $order->email) }}" class="col-sm-12">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-4"> Телефон </label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="ace-icon fa fa-phone" title="{{ $order->phone }}"></i>
                                            </span>
                                            <input type="text" id="form-field-4" name="phone" placeholder="Телефон" value="{{ old('phone', $order->phone) }}" class="form-control phone-mask col-sm-12">
                                        </div>
                                        <span class="hint">{{ $order->phone }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-5"> Адрес </label>
                                    <div class="col-sm-9">
                                        <input type="text" id="form-field-5" name="address" placeholder="Адрес" value="{{ old('address', $order->address) }}" class="col-sm-12">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-6"> Способ доставки </label>
                                    <div class="col-sm-9">
                                        <select name="delivery_id" id="form-field-6">
                                            <option value="">--Не выбрана--</option>
                                            @foreach($deliveries as $item)
                                            <option value="{{$item->id}}" @if ((old() && old('delivery_id')==$item->id) || (empty(old()) && $order->delivery_id==$item->id)) selected="selected" @endif data-price="{{$item->price}}">{{$item->name}} - {{$item->price}} руб.</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-7"> Способ Оплаты </label>
                                    <div class="col-sm-9">
                                        <select name="payment_id" id="form-field-7">
                                            <option value="">--Не выбрана--</option>
                                            @foreach($payments as $item)
                                            <option value="{{$item->id}}" @if ((old() && old('payment_id')==$item->id) || (empty(old()) && $order->payment_id==$item->id)) selected="selected" @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-8"> Данные для оплаты </label>
                                    <div class="col-sm-9">
                                        <textarea id="form-field-8" name="payment_add" placeholder="Дополнительные данные для оплаты" class="col-sm-12">{{ old('payment_add', $order->payment_add) }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-9"> Статус </label>
                                    <div class="col-sm-9">
                                        <select name="status" class="col-sm-5" id="form-field-0">
                                            <option value="">--Не выбран--</option>
                                            @foreach(\App\Models\Order::$statuses as $status => $name)
                                            <option value="{{$status}}" @if((old() && old('status')==$status) || (!old() && $order->status==$status) ) selected="selected"@endif>{{$name}}</option>
                                            @endforeach
                                        </select>
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
                                        <a class="btn btn-info" href="{{route('admin.orders.index')}}">
                                            <i class="ace-icon glyphicon glyphicon-backward bigger-110"></i>
                                            Назад
                                        </a>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
           <div class="col-sm-4">
                <div class="widget-box">
                    <div class="widget-header widget-header-flat">
                        <h4 class="widget-title smaller">
                            <i class="ace-icon fa fa-quote-left smaller-80"></i>
                            Состав заказа
                        </h4>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main">
                               <table class="table table-striped table-bordered table-hover products">
                                    <thead class="thin-border-bottom">
                                        <tr>
                                            <th><i class="ace-icon fa fa-briefcase"></i>Товар</th>
                                            <th>Доп.</th>
                                            <th>Кол-во</th>
                                            <th><i class="ace-icon fa fa-rub"></i>Цена</th>
                                            <th class="hidden-480"><i class="ace-icon fa fa-rub"></i>Сумма</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($order->products as $product)
                                        <tr class="product">
                                            <td class="" style="display: flex">
                                            @if($product->img && $product->uploads)
                                                <a target="_blank" href="{{ route('product', ['sysname' => $product->sysname]) }}" class="img-wr a-l-c">
                                                    <img src="{{$product->uploads->img->preview->url()}}" width="29px" />
                                                </a>
                                            @endif
                                                <a target="_blank"  href="{{ route('product', ['sysname' => $product->sysname]) }}">{{$product->name}}</a>
                                            </td>
                                            <td>
                                                @php $extras = json_decode($product->pivot->extra_params); @endphp
                                                @if(isset($extras->size)) Размер - {{ $extras->size }} @endif
                                            </td>
                                            <td>{{$product->pivot->cnt}}</td>
                                            <td>{{$product->pivot->price}}</td>
                                            <td class="hidden-480 text-success">{{$product->pivot->cnt * $product->pivot->price}}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" style="text-align: right">
                                                Сумма:
                                            </td>
                                            <td class="text-success amount">{{$order->amount}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="text-align: right">
                                                Доставка:
                                            </td>
                                            <td class="text-success delivery"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="text-align: right">
                                                Итого:
                                            </td>
                                            <td class="text-danger total"></td>
                                        </tr>
                                    </tbody>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.row -->
    </div>
@stop
