@foreach($articles as $item)
    <a href="{{route('articles.record', [$item->sysname])}}" class="recipe-item">
        <div class="img-wrapper">
            <div class="img" style="background-image:url({{$item->uploads->img->small->url()}})"></div>
        </div>
        <div class="caption">{{$item->name}}</div>
        <div class="text">{{$item->descr}}</div>
    </a>
@endforeach
