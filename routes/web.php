<?php
/*
 * Страницы с sidebar'ом
 */
//Artisan::call('view:clear');
//Cache::flush();
//логируем путь, чтоб видеть где появилась ошибка
if(env('LOG_URL')) {
    \Illuminate\Support\Facades\Log::info(\Illuminate\Support\Facades\URL::current());
}
include('redirects.php');
//конец редиректов
Route::group([
    'middleware' => ['location', 'settings']
], function() {
    Auth::routes();
    // Страницы
    Route::get('/', ['as' => 'index', 'uses' => 'MainController@index']);
    Route::get('/delivery', ['as' => 'delivery', 'uses' => 'MainController@delivery']);
    Route::get('/contacts', ['as' => 'contacts', 'uses' => 'MainController@contacts']);
    Route::get('/articles', 'MainController@articles')->name('articles');
    Route::get('/articles/{sysname}', ['as' => 'article', 'uses' => 'MainController@article']);
    Route::get('articles/{tag_sysname}/{sysname}', 'MainController@tagArticle')->name('tag.article');
    // All other pages
    Route::get('/agencies', 'CityController@index')->name('agencies');
    Route::get('/agencies/{sysname}', 'CityController@details')->name('agencies.details');
    Route::get('/page/{sysname}', 'MainController@page')->name('page')->where(['sysname' => '[a-zA-Z0-9_-]+']);
    Route::any('/search', ['as' => 'search', 'uses' => 'CatalogController@search']);

    // Каталог
    Route::get('/catalog.html', ['as' => 'catalog.root', 'uses' => 'CatalogController@catalogRoot'])->where(['sysname' => '[a-zA-Z0-9_-]+']);
    Route::get('/brand/{sysname}.html', ['as' => 'brands', 'uses' => 'CatalogController@brands'])->where(['sysname' => '[a-zA-Z0-9_-]+']);
    Route::any('/tag/{sysname}', ['as' => 'tags', 'uses' => 'CatalogController@tags'])->where(['sysname' => '[a-zA-Z0-9_-]+']);
    Route::get('/seen', ['as' => 'seen', 'uses' => 'CatalogController@seen']);
    Route::get('/bookmarks', ['as' => 'bookmarks', 'uses' => 'CatalogController@bookmarks']);

    // Товары
    Route::get('/product/{sysname}', ['as' => 'product', 'uses' => 'CatalogController@product'])->where(['sysname' => '[a-zA-Z0-9_-]+']);
    Route::any('/actions/{sysname?}', ['as' => 'actions', 'uses' => 'CatalogController@actions']); // Sale category
    Route::any('/new/{sysname?}', ['as' => 'new', 'uses' => 'CatalogController@newproducts']);
    Route::any('/hits/{sysname?}', ['as' => 'hits', 'uses' => 'CatalogController@hits']);

    // Отзывы
    Route::get('/reviews', ['as' => 'reviews', 'uses' => 'MainController@reviews']);

    // Новости
    Route::get('/news', ['as' => 'news', 'uses' => 'MainController@news']);
    Route::get('/news/{sysname}', ['as' => 'news.record', 'uses' => 'MainController@newsSingle']);
    //личный кабинет
    Route::get('/room', 'RoomController@index')->name('room');
    Route::get('/room/orders', 'RoomController@orders')->name('orders-history');
    //});

    Route::get('/cart', ['as' => 'cart', 'uses' => 'OrderController@cart']);
    Route::get('/order.html', ['as' => 'order', 'uses' => 'OrderController@order']);
    Route::post('/order/details', ['as'   => 'order.details', 'uses' => 'OrderController@details']);
//    Route::get('/order/confirm', ['as' => 'order.confirm', 'uses' => 'OrderController@confirm']);

    // Customers Photos
    Route::get('/photos', ['as' => 'photos', 'uses' => 'PhotoController@index']);

    // Look Book
    Route::get('/look-book', 'LookController@index')->name('look_book');

    // Woman adn man main category
    Route::get('woman', 'CatalogController@main_category')->name('main_woman');
    Route::get('man', 'CatalogController@main_category')->name('main_man');

    // Каталог (совместимость со старым ЧПУ)
    Route::any('{sysname}', ['as' => 'catalog', 'uses' => 'CatalogController@catalog'])->where(['sysname' => '[a-zA-Z0-9_-]+']);
    //test page
    //Route::get('/test.html', 'TestController@index')->name('test');
    //Route::get('/one-more-test.html', 'TestController@oneMoreTest')->name('one-more-test');

    // Product comments
//    Route::post('/comment', ['as' => 'comment', 'uses' => 'FrontApiController@comment']);
//    Route::get('/comments', ['as' => 'comments', 'uses' => 'FrontApiController@comments']);


    //run onece
    //Route::get('/run-once/add-ms-sizes.html', 'RunOnceController@addMsSizesAttribute')->name('add-ms-sizes');
    Route::get('/run-once/remove-sizes-type.html', 'RunOnceController@removeSizesType')->name('remove-sizes-type');
    Route::get('/run-once/remove-sex-sizes.html', 'RunOnceController@removeSizesSex')->name('remove-sex-sizes');

    // Yandex Market
    Route::get('/export/yandex-market', 'ExportController@yandexMarket')->name('yandex_market');
    //other exports
    Route::get('/export/icml.xml', 'ExportController@icml')->name('icml');
    Route::get('/export/retail-rocket', 'ExportController@retailRocket')->name('retail_rocket');
    // Google Merchant Center
    Route::get('/export/google-merchant', 'ExportController@googleMerchant')->name('google_merchant');
    // CommerceML
    Route::any('/export/commerceml/{token}', 'ExportController@commerceMLExchange')->name('commerceML');

    // Import goods from moysklad
    Route::get('/moysklad/import/products', 'MoySkladController@importProducts')->name('import.products');
    Route::get('/moysklad/get/rests', 'MoySkladController@updatePriceAndStock')->name('get.rests');
    Route::get('/moysklad/get/agents', 'MoySkladController@updateAgents')->name('get.agents');
    Route::get('/moysklad/export/orders', 'MoySkladController@exportOrders')->name('post.orders');
    Route::get('/moysklad/get/order/{id}', 'MoySkladController@getOrder')->name('get.order');

    //RetailCRM
    Route::get('/retailCRM/product/{id}', 'RetailCRMController@product')->name('retailcrm-get-product');
    Route::get('/retailCRM/order/{id?}', 'RetailCRMController@orders')->name('retailcrm-get-order');
    //Route::model('', 'User');


});

//Route::get('/offer.html', ['as' => 'offer', function(){
//    return 'Страница публичной офферты';
//}]);

/*
 * Личный кабинет
 */
Route::group(['middleware' => 'auth'], function() {
    Route::get('/me',                  [ 'as' => 'customer.dashboard', 'uses' => 'CustomerAccountController@me']);
    Route::get('/my_order/{order_id}', [ 'as' => 'customer.order',     'uses' => 'CustomerAccountController@order']);
});
