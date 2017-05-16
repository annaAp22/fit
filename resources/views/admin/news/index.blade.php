@extends('admin.layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> News
            <a class="btn btn-success pull-right" href="{{ route('admin.news.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('main')
    <div class="page-content">

        <div class="page-header">
            <h1>
                Новости
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех новостей
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-md-12">
                <div class="table-header">
                    Список всех новостей

                    <div class="ibox-tools">
                        <a href="{{route('admin.news.create')}}" class="btn btn-success btn-xs">
                            <i class="fa fa-plus"></i>
                            Добавить новость
                        </a>
                    </div>

                </div>
                @if($news->count())
                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>ЧПУ (URL)</th>
                            <th>Title</th>
                            <th>Keywords</th>
                            <th>Description</th>
                            <th>Статус</th>
                            <th>Дата создания</th>
                            <th class="text-right"></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($news as $news)
                            <tr>
                                <td>{{$news->name}}</td>
                                <td>{{$news->sysname}}</td>
                                <td>{{$news->title}}</td>
                                <td>{{$news->keywords}}</td>
                                <td>{{$news->description}}</td>
                                <td><i class="ace-icon glyphicon {{$news->status == 1 ? 'glyphicon-ok green' : 'glyphicon-remove red'}}"></i> </td>
                                <td>{{$news->created_at}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-warning" href="{{ route('admin.news.edit', $news->id) }}"><i class="glyphicon glyphicon-edit"></i> </a>
                                    <form action="{{ route('admin.news.destroy', $news->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Удалить новость? Вы уверены?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
{{--                    {!! $news->render() !!}--}}
                @else
                    <p>Нет новостей</p>
                @endif

            </div>
        </div>
    </div>
@endsection