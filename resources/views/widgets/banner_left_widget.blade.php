<div class="sidebar-banners">
    @foreach($banners as $banner)
        <a class="sidebar-banners__banner" href="{{ url($banner->url) }}" {{ $banner->blank ? 'target="_blank"' : '' }}>
            <img src="{{ $banner->uploads->img->url() }}" alt=""/>
        </a>
    @endforeach
</div>