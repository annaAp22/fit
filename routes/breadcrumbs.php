<?php

Breadcrumbs::register('index', function($breadcrumbs) {
    $breadcrumbs->push('Главная', route('index'));
});

Breadcrumbs::register('page', function($breadcrumbs, $page) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push($page->name, route('page', ['sysname' => $page->sysname]));
});

Breadcrumbs::register('search', function($breadcrumbs, $query) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Поиск', route('index'));
    $breadcrumbs->push($query);
});

Breadcrumbs::register('reviews', function($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Отзывы', route('reviews'));
});

Breadcrumbs::register('news', function($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Новости', route('news'));
});

Breadcrumbs::register('news.record', function($breadcrumbs, $newsRecord) {
    $breadcrumbs->parent('news');
    $breadcrumbs->push($newsRecord->name, route('news.record', $newsRecord->sysname));
});

Breadcrumbs::register('articles', function($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Статьи', route('articles'));
});

Breadcrumbs::register('article', function($breadcrumbs, $article) {
    $breadcrumbs->parent('articles');
    $breadcrumbs->push($article->name, route('article', $article->sysname));
});

Breadcrumbs::register('catalogRoot', function($breadcrumbs) {
    $breadcrumbs->parent('index');
//    $breadcrumbs->push('Каталог', route('catalog.root'));
});

Breadcrumbs::register('catalog', function($breadcrumbs, $root) {
    $breadcrumbs->parent('catalogRoot');

    $addParents = function($breadcrumbs, $root) use (&$addParents) {
        if($parent = $root->parent) $addParents($breadcrumbs, $parent);
        return $breadcrumbs->push($root->name, route('catalog', $root->sysname));
    };

    if($root instanceof App\Models\Category) {
        $addParents($breadcrumbs, $root);
        return;
    }

    if($root instanceof App\Models\Brand) {
        $breadcrumbs->push($root->name, route('brands', $root->sysname));
        return;
    }

    if($root instanceof App\Models\Tag) {
        $breadcrumbs->push($root->name, route('tags', $root->sysname));
        return;
    }
});

// New, hit, action products page
Breadcrumbs::register('new_hit_act', function($breadcrumbs, $page) {
    if(isset($page->category))
        $breadcrumbs->parent('catalog', $page->category);
    else
        $breadcrumbs->parent('index');

    $breadcrumbs->push($page->name, route($page->route, ['sysname' => $page->sysname]));
});

Breadcrumbs::register('product', function($breadcrumbs, $product) {
    $category = $product->categories->count() ? $product->categories->first() : null;

    if(!$category) $breadcrumbs->parent('catalogRoot');
    else $breadcrumbs->parent('catalog', $category);

    $breadcrumbs->push($product->name, route('product', $product));
});

Breadcrumbs::register('seen', function($breadcrumbs) {
    $breadcrumbs->parent('catalogRoot');
    $breadcrumbs->push('Просмотренные товары', route('seen'));
});

Breadcrumbs::register('bookmarks', function($breadcrumbs) {
    $breadcrumbs->parent('catalogRoot');
    $breadcrumbs->push('Отложенные товары', route('bookmarks'));
});

Breadcrumbs::register('cart', function($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Корзина', route('cart'));
});

Breadcrumbs::register('delivery', function($breadcrumbs) {
    $breadcrumbs->parent('cart');
    $breadcrumbs->push('Оформление заказа', route('order'));
});

Breadcrumbs::register('confirmation', function($breadcrumbs) {
    $breadcrumbs->parent('cart');
    $breadcrumbs->push('Подтверждение заказа заказа', route('order.confirm'));
});

Breadcrumbs::register('customer.dashboard', function($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Личный кабинет', route('customer.dashboard'));
});

Breadcrumbs::register('customer.order', function($breadcrumbs, $order) {
    $breadcrumbs->parent('customer.dashboard');
    $breadcrumbs->push('Заказ №'.$order->id, route('customer.order', $order->id));
});

Breadcrumbs::register('articles.tag', function($breadcrumbs, $tag) {
    $breadcrumbs->parent('articles');
    $breadcrumbs->push($tag->name, route('tags', ['sysname' => $tag->sysname]));
});
Breadcrumbs::register('articles.tag.article', function($breadcrumbs, $tag, $article) {
    $breadcrumbs->parent('articles.tag', $tag);
    $breadcrumbs->push($article->name, route('tag.article', ['tag_sysname' => $tag->sysname, 'sysname' => $article->sysname]));
});

Breadcrumbs::register('photos', function($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Фотографии клиентов', route('photos'));
});

Breadcrumbs::register('look_book', function($breadcrumbs) {
    $breadcrumbs->parent('index');
    $breadcrumbs->push('Look Book', route('look_book'));
});