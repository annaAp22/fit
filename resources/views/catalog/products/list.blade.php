@php
    $i = 0;
    $perPage = $global_settings['perpage']->value;
    if(isset($products) and isset($products->perPage) and $products->perPage() > 0) {
        $scrollNum =  (int)(($products->perPage()-1) / $perPage) * $perPage;
    }else {
        $scrollNum = 0;
    }
@endphp
@foreach($products as $product)
    @php $scrollTarget = ($i === $scrollNum); $i++; @endphp
    @include('catalog.products.list_item')
@endforeach

