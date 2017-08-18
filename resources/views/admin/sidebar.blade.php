<div id="sidebar" class="sidebar responsive">
    <script type="text/javascript">
        try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
    </script>

    <ul class="nav nav-list">
        @can('index', new App\Models\Order())
        <li @if( str_is('admin.orders*', Route::currentRouteName()) ||
                str_is('admin.deliveries*', Route::currentRouteName()) ||
                str_is('admin.payments*', Route::currentRouteName())
                        )
                    class="active open"
                        @endif >
                    <a href="#"  class="dropdown-toggle">
                <i class="menu-icon glyphicon glyphicon-list-alt"></i>
                <span class="menu-text"> Заказы </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                    <li  @if( str_is('admin.orders*', Route::currentRouteName())) class="active" @endif>
                        <a href="{{route('admin.orders.index')}}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            <span class="menu-text"> Заказы </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li  @if( str_is('admin.deliveries*', Route::currentRouteName())) class="active" @endif>
                        <a href="{{route('admin.deliveries.index')}}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            <span class="menu-text"> Способы доставки </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
                    <li  @if( str_is('admin.payments*', Route::currentRouteName())) class="active" @endif>
                        <a href="{{route('admin.payments.index')}}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            <span class="menu-text"> Способы оплаты </span>
                        </a>
                        <b class="arrow"></b>
                    </li>
            </ul>
        </li>
        @endcan
        @can('index', new App\Models\Product())
        <li @if( str_is('admin.products*', Route::currentRouteName()) ||
                 str_is('admin.attributes*', Route::currentRouteName()))
              class="active open"
            @endif >
            <a href="#"  class="dropdown-toggle">
                <i class="menu-icon fa  fa-briefcase"></i>
                <span class="menu-text"> Товары </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li  @if( str_is('admin.attributes*', Route::currentRouteName())) class="active" @endif>
                    <a href="{{route('admin.attributes.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span class="menu-text"> Атрибуты </span>
                    </a>
                    <b class="arrow"></b>
                </li>
                <li  @if( str_is('admin.products*', Route::currentRouteName())) class="active" @endif>
                    <a href="{{route('admin.products.index')}}">
                        <i class="menu-icon fa  fa-caret-right"></i>
                        <span class="menu-text"> Товары </span>
                    </a>
                    <b class="arrow"></b>
                </li>
                <li  @if( str_is('admin.kits*', Route::currentRouteName())) class="active" @endif>
                    <a href="{{route('admin.kits.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span class="menu-text"> Комплекты </span>
                    </a>
                    <b class="arrow"></b>
                </li>


            </ul>
        </li>
        @endcan

        @can('index', new App\Models\Look())
        <li class="{{ str_is('admin.looks*', Route::currentRouteName()) || str_is('admin.look_categories*', Route::currentRouteName()) ? 'active open' : '' }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-eye"></i>
                <span class="menu-text">Look Book</span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li  @if( str_is('admin.looks*', Route::currentRouteName())) class="active" @endif>
                    <a href="{{route('admin.looks.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span class="menu-text"> Looks </span>
                    </a>
                    <b class="arrow"></b>
                </li>

                <li  @if( str_is('admin.look_categories*', Route::currentRouteName())) class="active" @endif>
                    <a href="{{route('admin.look_categories.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span class="menu-text"> Books </span>
                    </a>
                    <b class="arrow"></b>
                </li>
            </ul>
        </li>
        @endcan

        @can( 'index', new App\Models\Offer() )
        <li class="{{ str_is('admin.offers*', Route::currentRouteName()) ? 'active' : '' }}">
            <a href="{{route('admin.offers.index')}}">
                <i class="menu-icon fa fa-handshake-o"></i>
                <span class="menu-text"> Offer категории </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan

        @can('index', new App\Models\ProductComment())
        <li  @if( str_is('admin.comments*', Route::currentRouteName())) class="active" @endif>
            <a href="{{route('admin.comments.index')}}">
                <i class="menu-icon fa fa-comments-o"></i>
                <span class="menu-text"> Комментарии </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan
        @can('index', new App\Models\Review())
            <li  @if( str_is('admin.reviews*', Route::currentRouteName())) class="active" @endif>
                <a href="{{route('admin.reviews.index')}}">
                    <i class="menu-icon fa fa-comments-o"></i>
                    <span class="menu-text"> Отзывы </span>
                </a>
                <b class="arrow"></b>
            </li>
        @endcan
        @can('index', new App\Models\News())
            <li  @if( str_is('admin.news*', Route::currentRouteName())) class="active" @endif>
                <a href="{{route('admin.news.index')}}">
                    <i class="menu-icon fa fa-newspaper-o"></i>
                    <span class="menu-text"> Новости </span>
                </a>
                <b class="arrow"></b>
            </li>
        @endcan
        @can('index', new App\Models\Category())
        <li  @if( str_is('admin.categories*', Route::currentRouteName())) class="active" @endif>
            <a href="{{route('admin.categories.index')}}">
                <i class="menu-icon fa fa-folder-open"></i>
                <span class="menu-text"> Каталог </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan
        @can('index', new App\Models\Brand())
        <li  @if( str_is('admin.brands*', Route::currentRouteName())) class="active" @endif>
            <a href="{{route('admin.brands.index')}}">
                <i class="menu-icon fa fa-bolt"></i>
                <span class="menu-text"> Бренды </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan
        @can('index', new App\Models\Tag())
        <li  @if( str_is('admin.tags*', Route::currentRouteName())) class="active" @endif>
            <a href="{{route('admin.tags.index')}}">
                <i class="menu-icon glyphicon glyphicon-tag"></i>
                <span class="menu-text"> Теги </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan
        @can('index', new App\Models\Article())
        <li  @if( str_is('admin.articles*', Route::currentRouteName())) class="active" @endif>
            <a href="{{route('admin.articles.index')}}">
                <i class="menu-icon fa fa-comment"></i>
                <span class="menu-text"> Статьи </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan
        @can('index', new App\Models\Certificate())
        <li  @if( str_is('admin.certificates*', Route::currentRouteName())) class="active" @endif>
            <a href="{{route('admin.certificates.index')}}">
                <i class="menu-icon glyphicon glyphicon-file"></i>
                <span class="menu-text"> Сертификаты </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan
        @can('index', new App\Models\City())
        <li  @if( str_is('admin.cities*', Route::currentRouteName())) class="active" @endif>
            <a href="{{route('admin.cities.index')}}">
                <i class="menu-icon glyphicon glyphicon-tree-deciduous"></i>
                <span class="menu-text">Города</span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan
        @can('index', new App\Models\Shop())
        <li  @if( str_is('admin.cities*', Route::currentRouteName())) class="active" @endif>
            <a href="{{route('admin.shops.index')}}">
                <i class="menu-icon glyphicon glyphicon-shopping-cart"></i>
                <span class="menu-text">Магазины</span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan
        @can('index', new App\Models\Banner())
        <li  @if( str_is('admin.banners*', Route::currentRouteName())) class="active" @endif>
            <a href="{{route('admin.banners.index')}}">
                <i class="menu-icon glyphicon glyphicon-picture"></i>
                <span class="menu-text"> Баннеры </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan
        @can('index', new App\Models\Photo())
        <li  @if( str_is('admin.photos*', Route::currentRouteName())) class="active" @endif>
            <a href="{{route('admin.photos.index')}}">
                <i class="menu-icon glyphicon glyphicon-picture"></i>
                <span class="menu-text"> Фотографии </span>
            </a>
            <b class="arrow"></b>
        </li>
        @endcan

        @if(Gate::check('index', new App\Models\Page()) || Gate::check('index', new App\Models\Metatag()) || Gate::check('index', new App\Models\Setting()) || Gate::check('index', new App\User()))

        <li @if( str_is('admin.page*', Route::currentRouteName()) ||
                 str_is('admin.metatags*', Route::currentRouteName()) ||
                 str_is('admin.settings*', Route::currentRouteName())
                )
            class="active open"
                @endif >
            <a href="#"  class="dropdown-toggle">
                <i class="menu-icon fa fa-cogs"></i>
                <span class="menu-text"> Служебные разделы </span>
                <b class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @can('index', new App\Models\Page())
                <li  @if( str_is('admin.page*', Route::currentRouteName())) class="active" @endif>
                    <a href="{{route('admin.pages.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span class="menu-text"> Содержание страниц </span>
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('index', new App\Models\Metatag())
                <li  @if( str_is('admin.metatags*', Route::currentRouteName())) class="active" @endif>
                    <a href="{{route('admin.metatags.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span class="menu-text"> Мета-тэги </span>
                    </a>
                    <b class="arrow"></b>
                </li>
                @endif
                @can('index', new App\Models\Setting())
                <li @if( str_is('admin.settings*', Route::currentRouteName())) class="active" @endif>
                    <a href="{{route('admin.settings.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span class="menu-text"> Настройки </span>
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('index', new App\User())
                <li @if( str_is('admin.users*', Route::currentRouteName())) class="active" @endif>
                    <a href="{{route('admin.users.index')}}">
                        <i class="menu-icon fa fa-caret-right"></i>
                        <span class="menu-text"> Пользователи </span>
                    </a>
                    <b class="arrow"></b>
                </li>
                @endcan
                <li @if( str_is('admin.cache-clear', Route::currentRouteName())) class="active" @endif>
                    <a href="{{route('admin.cache-clear')}}">
                        <i class="menu-icon fa fa-refresh"></i>
                        <span class="menu-text" title="сделать это!"> Очистить кэш </span>
                    </a>
                    <b class="arrow"></b>
                </li>
            </ul>
        </li>

        @endif

    </ul><!-- /.nav-list -->

    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>

    <script type="text/javascript">
        try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
    </script>
</div>
