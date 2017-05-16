    @foreach($cats as $cat)
    <option value="{{$cat->id}}" @if((old() && old('parent_id')==$cat->id) || (!old() && !empty($category) && $category->parent_id==$cat->id) || (isset($filters['id_category']) && $filters['id_category']==$cat->id))selected="selected"@endif>
    @for($i=0;$i<$index;$i++)
    &nbsp;
    @endfor
    {{$cat->name}}
    </option>
    @if($cat->children->count()))
        @include('admin.categories.dropdown', ['cats' => $cat->children()->where('id', '!=', !empty($category) ? $category->id : -1)->orderBy('sort')->get(), 'index' => ($index+1), 'category' => !empty($category) ? $category : null])
    @endif
    @endforeach
