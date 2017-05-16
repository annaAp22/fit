@extends('layouts.main')

@section('breadcrumbs')
    @if($category == 'root')
        {!!  Breadcrumbs::render('catalogRoot') !!}
    @else
        {!!  Breadcrumbs::render('catalog', $category) !!}
    @endif
@endsection

@section('content')
    <div class="wrap-block-title">
        <div class="block-title_text">
            {{ $category == 'root' ? 'Каталог' : $category->name }}:
        </div>
        <div class="block-title_count">
            @php
                $count = $category == 'root' ? $products_count : $category->productsCount;
            @endphp
            <span class="mod-bold mod-col-or">{{ $count }}</span> {{ App\Helpers\inflectByCount($count, ['one' => 'товар', 'many' => 'товара', 'others' => 'товаров']) }}
        </div>
    </div>

    <div class="subcategory">
        @php
            $catList = $category == 'root' ? $categories : $category->children;
        @endphp
        @foreach($catList as $subcategory)
            <a href="{{ route('catalog', $subcategory->sysname) }}" class="subcategory_item">
                <span class="subcategory_item_count">{{ $subcategory->productsCount }}</span>
                @if($subcategory->uploads)
                    <figure class="subcategory_item_thumb align-table-wrap">
                        <div><img src="{{ $subcategory->uploads->img->preview->path() }}"></div>
                    </figure>
                @endif
                <span class="subcategory_item_title">{{ $subcategory->name }}</span>
            </a>
        @endforeach
    </div>

    @if(!$category == 'root')
        <div class="seotext wrap-typography">
            {!! $category->text !!}
        </div>
    @endif
@endsection
