<?php
echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
?>

<yml_catalog date="{{ date('Y-m-d H:i') }}">
    <shop>
        <name>{{$global_settings['shop_name']->value}}</name>
        <company>{{$global_settings['company_name']->value}}</company>
        <categories>
        @foreach($categories as $category)
            <category id="{{ $category->id }}" @if($category->parent_id)parentId="{{ $category->parent_id }}"@endif>{{ $category->name }}</category>
        @endforeach
        </categories>
        <offers>
            @foreach($offers as $offer)
                <offer id="{{ $offer->offer_id }}" productId="{{ $offer->id }}" quantity="{{$offer->quantity}}">
                    @foreach($offer->categories as $category)
                    <categoryId>{{$category->id}}</categoryId>
                    @endforeach
                    <url>{{ route('product', ['sysname' => $offer->sysname]) }}</url>
                    <price>{{ $offer->price }}</price>
                    @if($offer->originalPrice)
                        <oldprice>{{ $offer->originalPrice }}</oldprice>
                    @endif
                    <picture>{{ url($offer->uploads->img->detail->url()) }}</picture>
                    <xmlId>{{ $offer->xmlId }}</xmlId>
                    <name>{{ $offer->name }}</name>
                    <productName>{{ $offer->name }}</productName>
                    @if($offer->brand)
                        <vendor>{{ $offer->brand->name }}</vendor>
                    @endif
                    <param name="Артикул" code="article">{{$offer->sku}}</param>
                    @isset($offer->size)
                    <param name="Размер" code="size" unit="RU">{{ $offer->size }}</param>
                    @endisset
                    @foreach($offer->attributes->where('name', '!=', 'Размеры') as $param)
                        <param name="{{ $param->name }}">{{ $param->pivot->value }}</param>
                    @endforeach
                </offer>
            @endforeach
        </offers>
    </shop>
</yml_catalog>