@foreach($articles as $item)
    @if( isset($tag) )
        <a href="{{ route('tag.article', ['sysname' => $item->sysname, 'tag_sysname' => $tag->sysname]) }}" class="recipe-item">
    @else
        <a href="{{ route('article', ['sysname' => $item->sysname]) }}" class="recipe-item">
    @endif
        <div class="img-wrapper">
            <div class="img" style="background-image:url( '{{ $item->uploads->img->small->url() }}' )"></div>
        </div>
        <div class="caption">{{$item->name}}</div>
        <div class="text">{{$item->descr}}</div>
    </a>
@endforeach
