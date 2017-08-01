<?php
    echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        <title>Одежда для фитнеса, танцев и бодибилдинга</title>
        <description>Одежда для фитнеса, танцев и бодибилдинга</description>
        <link>{{ route('index') }}</link>
            @foreach($offers as $offer)
                <item>
                    <g:id>{{ $offer->offer_id }}</g:id>
                    <g:title>{{ $offer->name }}</g:title>
                    <g:item_group_id>{{ $offer->id }}</g:item_group_id>
                    <g:availability>{{ $offer->stock ? 'in stock' : 'out of stock' }}</g:availability>
                    <g:description>{{ $offer->descr }}</g:description>
                    <g:link>{{ route('product', ['sysname' => $offer->sysname]) }}</g:link>
                    <g:image_link>{{ url($offer->uploads->img->detail->url()) }}</g:image_link>
                    <g:condition>new</g:condition>
                    @if($offer->originalPrice)
                        <g:price>{{ $offer->originalPrice }} RUB</g:price>
                        <g:sale_price>{{ $offer->price }} RUB</g:sale_price>
                    @else
                        <g:price>{{ $offer->price }} RUB</g:price>
                    @endif
                    <g:shipping>
                        <g:country>RU</g:country>
                        <g:price>300.00 RUB</g:price>
                    </g:shipping>
                    @if($offer->brand)
                        <g:brand>{{ $offer->brand->name }}</g:brand>
                    @endif
                    @isset($offer->size)
                        <g:size>{{ $offer->size }}</g:size>
                    @endisset
                    @if($color = $offer->attributes->where('name', 'Цвет')->first())
                        <g:color>{{ $color->pivot->value }}</g:color>
                    @endif
                    <g:age_group>Adult</g:age_group>
                    @if($gender = $offer->attributes->where('name', 'Пол')->first())
                        <g:gender>{{ $gender->pivot->value == 'Женский' ? 'Female' : 'male' }}</g:gender>
                    @endif
                    @if($offer->categories->count())
                        @if($offer->categories->where('name', 'Сумки')->first() )
                            <g:google_product_category>6551</g:google_product_category>
                        @else
                            <g:google_product_category>5322</g:google_product_category>
                        @endif
                    @endif
                </item>
            @endforeach
    </channel>
</rss>