<?php

Route::get('modal', 'FrontApiController@modal')->name('modal');

Route::post('/subscribe', [
    'as' => 'subscribe',
    'uses' => 'FrontApiController@subscribe'
]);

Route::post('/letter', [
    'as'   => 'letter',
    'uses' => 'FrontApiController@letter'
]);

Route::post('/callback', [
    'as'   => 'callback',
    'uses' => 'FrontApiController@callback'
]);

Route::post('/products/get', [
    'as' => 'products.get',
    'uses' => 'FrontApiController@getProducts'
]);

Route::group([ 'prefix' => 'product' ], function() {
    Route::get('/defer/{id}', [
        'as'   => 'product.defer',
        'uses' => 'FrontApiController@defer'
    ])->where(['id' => '[0-9]+']);

    Route::post('/comment/{id}', [
        'as'   => 'product.comment',
        'uses' => 'FrontApiController@comment'
    ])->where(['id' => '[0-9]+']);


    Route::get('/comments', 'FrontApiController@comments')->name('product.comments');
});

Route::group(['prefix' => 'cart'], function() {
    Route::post('/cart/add/{id}/{cnt}', [
        'as'   => 'cart.add',
        'uses' => 'FrontApiController@addToCart'
    ])->where(['id' => '[0-9]+', 'cnt' => '[0-9]+']);

    Route::get('/cart/remove/{id}/{size}', [
        'as'   => 'cart.remove',
        'uses' => 'FrontApiController@removeFromCart'
    ])->where('id', '[0-9]+');

    Route::post('/cart/update/{id}', 'FrontApiController@updateCartQuantity')->name('cart.update.quantity')->where('id', '[0-9]+');

    Route::post('/', [
        'as'   => 'cart.edit',
        'uses' => 'FrontApiController@cartEdit'
    ]);
});

Route::group(['prefix' => 'order'], function() {
    Route::post('/fast', [
        'as'   => 'order.fast',
        'uses' => 'FrontApiController@fast'
    ]);
});
