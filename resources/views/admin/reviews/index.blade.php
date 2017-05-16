@extends('admin.layout')

@section('header')
    <div class="page-header clearfix">
        <h1>
            <i class="glyphicon glyphicon-align-justify"></i> Reviews
            <a class="btn btn-success pull-right" href="{{ route('admin.reviews.create') }}"><i class="glyphicon glyphicon-plus"></i> Create</a>
        </h1>

    </div>
@endsection

@section('main')
    <div class="page-content">

        <div class="page-header">
            <h1>
                Отзывы о магазине
                <small>
                    <i class="ace-icon fa fa-angle-double-right"></i>
                    Список всех отзывов
                </small>
            </h1>
        </div><!-- /.page-header -->

        @include('admin.message')

        <div class="row">
            <div class="col-md-12">
                <div class="table-header">
                    Список всех отзывов

                    <div class="ibox-tools">
                        <a href="{{route('admin.reviews.create')}}" class="btn btn-success btn-xs">
                            <i class="fa fa-plus"></i>
                            Добавить отзыв
                        </a>
                    </div>

                </div>
                @if($reviews->count())
                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>Имя</th>
                            <th>E-Mail</th>
                            <th>Содержание</th>
                            <th>Статус</th>
                            <th>Дата создания</th>
                            <th class="text-right"></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>{{$review->name}}</td>
                                <td>{{$review->email}}</td>
                                <td>{{$review->message}}</td>
                                <td><i class="ace-icon glyphicon {{$review->status == 1 ? 'glyphicon-ok green' : 'glyphicon-remove red'}}"></i> </td>
                                <td>{{$review->created_at}}</td>
                                <td class="text-right">
                                    <a class="btn btn-xs btn-warning" href="{{ route('admin.reviews.edit', $review->id) }}"><i class="glyphicon glyphicon-edit"></i> </a>
                                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="display: inline;" onsubmit="if(confirm('Delete? Are you sure?')) { return true } else {return false };">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i> </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {!! $reviews->render() !!}
                @else
                    <p>Нет отзывов о магазине</p>
                @endif

            </div>
        </div>
    </div>

@endsection