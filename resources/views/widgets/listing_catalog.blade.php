@if($category)
<div class="sidebar-catalog">
    <div class="sidebar-catalog__title">{{ $category->name }}:
    </div>
    <ul class="sidebar-catalog__level-1">
        @foreach($category->children as $key => $subcategory)
            <li class="list-item js-listing-catalog-{{ $key }}{{ ( ($config['current']->id == $category->id && $loop->first) || $config['current']->parent_id == $subcategory->id || $config['current']->id == $subcategory->id)  ? ' active' : '' }}">
                <a href="{{ route('catalog', ['sysname' => $subcategory->sysname]) }}">
                    <span>{{ $subcategory->name }}</span>
                    <i class="sprite_main sprite_main-icon__arrow_green_down js-toggle-active-target" data-target=".js-listing-catalog-{{ $key }}"></i>
                </a>
                <ul class="list-item__level-2">
                    @foreach($subcategory->children as $child)
                        @if($child->id != $config['current']->id)
                            <li><a href="{{ route('catalog', ['sysname' => $child->sysname]) }}"><span>{{ $child->name }}</span></a></li>
                        @else
                            <li>
                                <i class="sprite_main sprite_main-icon__arrow_yellow_to_right"></i>
                                <a class="active" href="{{ route('catalog', ['sysname' => $child->sysname]) }}">
                                    <span>{{ $child->name }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
@endif