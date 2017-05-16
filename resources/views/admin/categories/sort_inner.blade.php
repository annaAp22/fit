<ol class="dd-list">
    @foreach($cats as $cat)
    <li class="dd-item" data-id="{{$cat->id}}">
        <div class="dd-handle">
            @if($cat->uploads)
                <img src="{{$cat->uploads->icon->url()}}" width="65" />
            @endif
            {{$cat->name}}
        </div>
        @if($cat->children->count())
            @include('admin.categories.sort_inner', ['cats' => $cat->children()->orderBy('sort')->get()])
        @endif
    </li>
    @endforeach
</ol>