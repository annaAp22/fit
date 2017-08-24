<?php
    /*
    * Админка
    */
Route::group(['middleware' => ['admin']], function () {
    Route::get('/main', ['as' => 'main', 'uses' => 'MainController@index']);

    Route::resource('articles', 'ArticleController', ['except' => ['show']]);
    Route::put('/articles/restore/{id}', ['as' => 'articles.restore', 'uses' => 'ArticleController@restore'])->where(['id' => '[0-9]+']);

    Route::resource('attributes', 'AttributeController', ['except' => ['show']]);
    Route::put('/attributes/restore/{id}', ['as' => 'attributes.restore', 'uses' => 'AttributeController@restore'])->where(['id' => '[0-9]+']);

    Route::resource('brands', 'BrandController', ['except' => ['show']]);
    Route::put('/brands/restore/{id}', ['as' => 'brands.restore', 'uses' => 'BrandController@restore'])->where(['id' => '[0-9]+']);

    Route::resource('banners', 'BannerController', ['except' => ['show']]);
    Route::put('/banners/restore/{id}', ['as' => 'banners.restore', 'uses' => 'BannerController@restore'])->where(['id' => '[0-9]+']);

    Route::resource('photos', 'PhotoController', ['except' => ['show']]);

    Route::resource('categories', 'CategoryController', ['except' => ['show']]);
    Route::get('/categories/sort', ['as' => 'categories.sort', 'uses' => 'CategoryController@sort']);
    Route::post('/categories/sort/save', ['as' => 'categories.sort.save', 'uses' => 'CategoryController@sortSave']);
    Route::put('/categories/restore/{id}', ['as' => 'categories.restore', 'uses' => 'CategoryController@restore'])->where(['id' => '[0-9]+']);
    Route::get('/categories/{id}/products', ['as' => 'categories.products', 'uses' => 'CategoryController@products']);
    Route::post('/categories/{id}/products', ['as' => 'categories.products.sync', 'uses' => 'CategoryController@productsSync']);

    Route::resource('certificates', 'CertificateController', ['except' => ['show', 'edit', 'update']]);
    Route::resource('cities', 'CityController');
    Route::resource('shops', 'ShopController');
    Route::resource('comments', 'ProductCommentController', ['except' => ['show']]);

    Route::resource('deliveries', 'DeliveryController', ['except' => ['show']]);
    Route::put('/deliveries/restore/{id}', ['as' => 'deliveries.restore', 'uses' => 'DeliveryController@restore'])->where(['id' => '[0-9]+']);

    Route::resource('kits', 'KitController', ['except' => ['show']]);

    Route::resource('metatags', 'MetatagsController', ['except' => ['show']]);
    Route::get('/metatags/{route}/edit_route', ['as' => 'metatags.edit_route', 'uses' => 'MetatagsController@editRoute'])->where(['route' => '[\.a-zA-Z0-9_-]+']);

    Route::resource('news', 'NewsController', ['except' => ['show']]);

    Route::resource('orders', 'OrderController', ['except' => ['show', 'create', 'store']]);
    Route::put('/orders/restore/{id}', ['as' => 'orders.restore', 'uses' => 'OrderController@restore'])->where(['id' => '[0-9]+']);

    Route::resource('pages', 'PageController', ['except' => ['show']]);
    Route::get('/pages/{sysname}/edit_sysname', ['as' => 'pages.edit_sysname', 'uses' => 'PageController@editSysname'])->where(['sysname' => '[a-zA-Z0-9_-]+']);
    Route::put('/pages/{sysname}/content', ['as' => 'pages.update_content', 'uses' => 'PageController@updateContent'])->where(['sysname' => '[a-zA-Z0-9_-]+']);
    Route::resource('payments', 'PaymentController', ['except' => ['show']]);
    Route::put('/payments/restore/{id}', ['as' => 'payments.restore', 'uses' => 'PaymentController@restore'])->where(['id' => '[0-9]+']);

    Route::resource('products', 'ProductController', ['except' => ['show']]);
    Route::any('/products/sort/unique_offers', 'ProductController@uniqueOffersSort')->name('products.sort.unique-offers');
    Route::put('/products/restore/{id}', ['as' => 'products.restore', 'uses' => 'ProductController@restore'])->where(['id' => '[0-9]+']);
    Route::post('/products/search', ['as' => 'products.search', 'uses' => 'ProductController@search']);
    Route::get('/products/category/{id}/sort', ['as' => 'products.category.sort', 'uses' => 'ProductController@sortCategory'])->where(['id' => '[0-9]+']);
    Route::post('/products/category/{id}/sort/save', ['as' => 'products.category.sort.save', 'uses' => 'ProductController@sortCategorySave'])->where(['id' => '[0-9]+']);
    Route::get('/products/tag/{id}/sort', ['as' => 'products.tag.sort', 'uses' => 'ProductController@sortTag'])->where(['id' => '[0-9]+']);
    Route::post('/products/tag/{id}/sort/save', ['as' => 'products.tag.sort.save', 'uses' => 'ProductController@sortTagSave'])->where(['id' => '[0-9]+']);
    Route::post('/products/remove/{id}', 'ProductController@remove')->where(['id' => '[0-9]+'])->name('products.remove');

    Route::resource('reviews', 'ReviewController', ['except' => ['show']]);

    Route::resource('settings', 'SettingController', ['except' => ['show']]);

    Route::resource('tags', 'TagController', ['except' => ['show']]);
    Route::put('/tags/restore/{id}', ['as' => 'tags.restore', 'uses' => 'TagController@restore'])->where(['id' => '[0-9]+']);
    Route::get('/tags/{id}/products', ['as' => 'tags.products', 'uses' => 'TagController@products']);
    Route::post('/tags/{id}/products', ['as' => 'tags.products.sync', 'uses' => 'TagController@productsSync']);

    Route::resource('users', 'UserController', ['except' => ['show']]);
    Route::put('/users/restore/{id}', ['as' => 'users.restore', 'uses' => 'UserController@restore'])->where(['id' => '[0-9]+']);

    Route::get('/image/crop', ['as' => 'image.crop', 'uses' => 'MainController@crop']);
    Route::post('/image/crop', ['as' => 'image.crop.save', 'uses' => 'MainController@cropUpdate']);

    Route::post('/editor/upload', ['as' => 'editor.upload', 'uses' => 'MainController@uploadFileCKeditor']);
    Route::get('/cache-clear', 'MainController@cacheClear')->name('cache-clear');

    Route::resource('looks', 'LookController', ['except' => ['show']]);
    Route::put('/looks/restore/{id}', ['as' => 'looks.restore', 'uses' => 'LookController@restore'])->where(['id' => '[0-9]+']);
    Route::resource('look_categories', 'LookCategoryController', ['except' => ['show']]);
    Route::put('/look_categories/restore/{id}', ['as' => 'look_categories.restore', 'uses' => 'LookCategoryController@restore'])->where(['id' => '[0-9]+']);

    Route::resource('offers', 'OfferController', ['except' => ['show']]);
    Route::put('/offers/restore/{id}', ['as' => 'offers.restore', 'uses' => 'OfferController@restore'])->where(['id' => '[0-9]+']);
    });