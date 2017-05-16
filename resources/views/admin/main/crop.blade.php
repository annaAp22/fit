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
            <li class="active">Редактирование изоображения</li>
        </ul><!-- /.breadcrumb -->


    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
                Изображения
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
                <form class="form-horizontal" role="form" action="{{route('admin.image.crop.save')}}" method="POST" enctype="multipart/form-data">
                    <input name="_token" type="hidden" value="{{csrf_token()}}">

                    <input name="img" type="hidden" value="{{$input['img']}}">
                    <input name="preview" type="hidden" value="{{$input['preview']}}">
                    <input name="width" type="hidden" value="{{$input['width']}}">
                    <input name="height" type="hidden" value="{{$input['height']}}">
                    @if(!empty($input['previews']))
                        @foreach($input['previews'] as $key =>  $preview)
                        <input name="previews[]" type="hidden" value="{{$preview}}">
                        <input name="widths[]" type="hidden" value="{{$input['widths'][$key]}}">
                        <input name="heights[]" type="hidden" value="{{$input['heights'][$key]}}">
                        @endforeach
                    @endif

                    <input type="hidden" name="x1" id="x1" value="-">
                    <input type="hidden" name="y1" id="y1" value="-">
                    <input type="hidden" name="x2" id="x2" value="-">
                    <input type="hidden" name="y2" id="y2" value="-">


                    <div class="form-group">
                        <div class="col-sm-12">
                            <img id="photo-crop" src="{{$input['img']}}" data-width="{{$input['width']}}" data-height="{{$input['height']}}">
                            <img id="photo" src="{{$input['preview']}}?{{time()}}">
                        </div>

                    </div>

                    <div class="clearfix form-actions">
                        <div class="col-md-offset-3 col-md-9">
                            <button class="btn btn-success" type="submit">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                Сохранить
                            </button>
                        </div>
                    </div>
                </form>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
@stop