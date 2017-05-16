    @foreach($cats as $cat)
    <option value="{{$cat->id}}" @if((old() && old('categories') && in_array($cat->id, old('categories'))) || (!old() && !empty($article) && $article->categories->count() && $article->categories->find($cat->id)) || (isset($filters['id_category']) && $filters['id_category']==$cat->id))selected="selected"@endif>
    @for($i=0;$i<$index;$i++)
    &nbsp;
    @endfor
    {{$cat->name}}
    </option>
    @if($cat->children->count()))
        @include('admin.articles.dropdown', ['cats' => $cat->children()->orderBy('sort')->get(), 'index' => ($index+1), 'article' => !empty($article) ? $article : null])
    @endif
    @endforeach
