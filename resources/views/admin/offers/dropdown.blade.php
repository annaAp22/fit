@foreach($cats as $cat)
    <option value="{{$cat->id}}" @if((old() && old('categories') && in_array($cat->id, old('categories'))) || (!old() && !empty($offer) && $offer->categories->count() && $offer->categories->find($cat->id)) || (isset($filters['id_category']) && $filters['id_category']==$cat->id))selected="selected"@endif>
        @for($i=0;$i<$index;$i++) &nbsp; @endfor
        {{$cat->name}}
    </option>
    @if($cat->children->count())
        @include('admin.offers.dropdown', ['cats' => $cat->children, 'index' => ($index+1), 'offer' => !empty($offer) ? $offer : null])
    @endif
@endforeach