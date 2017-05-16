@extends('admin.layout')
@section('main')
    <div class="error-container">
        <div class="well">
            <h1 class="grey lighter smaller">
                <span class="blue bigger-125">
                    <i class="ace-icon fa fa-sitemap"></i>
                    404
                </span>
                Страница не найдена!
            </h1>

            <hr>
            <h3 class="lighter smaller">Мы искали везде, но не смогли найти то что вы ищете!</h3>

            <div>
                <div class="space"></div>
                <h4 class="smaller">Для решения проблемы:</h4>

                <ul class="list-unstyled spaced inline bigger-110 margin-15">
                    <li>
                        <i class="ace-icon fa fa-hand-o-right blue"></i>
                        Изменить URL или выбрать другой раздел
                    </li>
                    <li>
                        <i class="ace-icon fa fa-hand-o-right blue"></i>
                        Обратиться к администратору
                    </li>

                </ul>
            </div>

            <hr>
            <div class="space"></div>

            <div class="center">
                <a href="javascript:history.back()" class="btn btn-grey">
                    <i class="ace-icon fa fa-arrow-left"></i>
                    Назад
                </a>

                <a href="{{route('admin.main')}}" class="btn btn-primary">
                    <i class="ace-icon fa fa-tachometer"></i>
                    На главную
                </a>
            </div>
        </div>
    </div>
@stop