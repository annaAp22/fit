<?php
    echo '<?xml version="1.0" encoding="utf-8"?>'. "\n" . '<!DOCTYPE yml_catalog SYSTEM "shops.dtd">';
?>

<yml_catalog date="{{ date('Y-m-d H:i') }}">
    <shop>
        <name>Одежда для фитнеса, танцев и бодибилдинга</name>
        <company>Одежда для фитнеса, танцев и бодибилдинга</company>
        <url>{{ route('index') }}</url>
        <currencies><currency id="RUB" rate="1" /></currencies>
        <categories>
            @foreach($categories as $category)
                <category id="{{ $category->id }}" @if($category->parent_id)parentId="{{ $category->parent_id }}"@endif>{{ $category->name }}</category>
            @endforeach
        </categories>

        <delivery-options>
            <option cost="300" days="1" order-before="18"/>
        </delivery-options>


        <offers>
            @foreach($offers as $offer)
                <offer id="{{ $offer->offer_id }}" group_id="{{ $offer->id }}" type="vendor.model" available="{{ $offer->stock ? 'true' : 'false' }}">
                    <url>{{ route('product', ['sysname' => $offer->sysname]) }}{{--?utm_source=yandex_market&amp;utm_medium=cpc&amp;utm_campaign=topiki&amp;utm_content=sportivniy_topik_s_poddergkoy&amp;utm_term=156220001--}}</url>
                    <price>{{ $offer->price }}</price>
                    @if($offer->originalPrice)
                        <oldprice>{{ $offer->originalPrice }}</oldprice>
                    @endif
                    <currencyId>RUB</currencyId>
                    @if($offer->categories->count())
                        <categoryId>{{ $offer->categories[0]->id }}</categoryId>
                    @endif
                    <picture>{{ url($offer->uploads->img->detail->url()) }}</picture>
                    <typePrefix>{{ $offer->name }}</typePrefix>
                    @if($offer->brand)
                        <vendor>{{ $offer->brand->name }}</vendor>
                    @endif
                    <model>{{ $offer->sku }}</model>
                    {{--<description>{{ $offer->descr }}</description>--}}
                    @isset($offer->size)
                        <param name="Размер" unit="RU">{{ $offer->size }}</param>
                    @endisset
                    @foreach($offer->attributes->where('name', '!=', 'Размеры') as $param)
                        <param name="{{ $param->name }}">{{ $param->pivot->value }}</param>
                    @endforeach
                </offer>
            @endforeach
        </offers>
    </shop>
</yml_catalog>