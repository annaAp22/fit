<form class="form-horizontal" role="form" action="{{ $kit->exists ? route('admin.kits.update', $kit->id) : route('admin.kits.store') }} " method="POST">
	@if($kit->exists) <input type="hidden" name="_method" value="PUT"> @endif
    <input name="_token" type="hidden" value="{{csrf_token()}}">

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-0"> Название </label>
        <div class="col-sm-9">
            <input type="text" id="form-field-0" name="name" placeholder="Название" value="{{ old('name', $kit->name) }}" class="col-sm-12">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-77">Товары в комплекте</label>
        <div class="col-sm-9">
            <dynamic-products :initial-products="{{ json_encode(old('products', $kit->products)) }}"></dynamic-products>
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
            <a class="btn btn-info" href="{{route('admin.kits.index')}}">
                <i class="ace-icon glyphicon glyphicon-backward bigger-110"></i>
                Назад
            </a>
        </div>
    </div>
</form>
