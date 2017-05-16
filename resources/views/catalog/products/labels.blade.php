<!-- Ribbons-->
<span class="ribbons">
    @if($product->hit)
        <span class="ribbons__ribbon ribbons__ribbon_hit">Хит!</span>
    @endif
    @if($product->new)
        <span class="ribbons__ribbon ribbons__ribbon_new">New</span>
    @endif
    @if($product->discount)
        <span class="ribbons__ribbon ribbons__ribbon_sale">-{{ $product->discount }}%</span>
    @endif
</span>