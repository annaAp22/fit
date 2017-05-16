<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Review::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'message' => $faker->realText,
        'status' => rand(0, 1),
    ];
});

$factory->define(App\Models\News::class, function(Faker\Generator $faker) {
    return [
        'sysname' => 'some-furl-'.rand(0, 9999),

        'name' => $faker->sentence,
        'body' => $faker->text,


        'title' => $faker->sentence,
        'keywords' => $faker->words(10, true),
        'description' => $faker->sentences(2, true),

        'status' => rand(0, 1),
    ];
});

$factory->define(App\Models\Category::class, function(Faker\Generator $faker) {
    return [
        'sysname' => 'some-furl-'.rand(0, 9999),

        'name' => $faker->words(2, true),
        'text' => $faker->text,


        'title' => $faker->sentence,
        'keywords' => $faker->words(10, true),
        'description' => $faker->sentences(2, true),

        'hit' => rand(0, 1),
        'new' => rand(0, 1),
        'act' => rand(0, 1),

        'status' => rand(0, 1),
    ];
});

$factory->define(App\Models\Tag::class, function(Faker\Generator $faker) {
    return [
        'sysname' => 'some-furl-'.rand(0, 9999),

        'name' => $faker->sentence,
        'text' => $faker->text,

        'title' => $faker->sentence,
        'keywords' => $faker->words(10, true),
        'description' => $faker->sentences(2, true),

        'views' => rand(0, 1000),
        'status' => rand(0, 1),
    ];
});

$factory->define(App\Models\Brand::class, function(Faker\Generator $faker) {
    return [
        'sysname' => 'some-furl-'.rand(0, 9999),

        'name' => $faker->sentence,
        'text' => $faker->text,


        'title' => $faker->sentence,
        'keywords' => $faker->words(10, true),
        'description' => $faker->sentences(2, true),

        'status' => rand(0, 1),
    ];
});

$factory->define(App\Models\Product::class, function(Faker\Generator $faker) {
    return [
        'sysname' => 'some-furl-'.rand(0, 9999),
//        'category_id' => '',
        'brand_id' => '',

        'name' => $faker->sentence,
        'descr' => $faker->sentence,
        'text' => $faker->text,

        'sku' => uniqid('sku-'),
        'price' => rand(500, 1000000),
        'discount' => rand(0, 50),

        'video_url' => 'https://youtube.com/somevideo',

        'title' => $faker->sentence,
        'keywords' => $faker->words(10, true),
        'description' => $faker->sentences(2, true),

        'new' => rand(0, 1),
        'act' => rand(0, 1),
        'hit' => rand(0, 1),
        'stock' => rand(0, 1),
        'status' => rand(0, 1),
    ];
});

$factory->define(App\Models\Article::class, function(Faker\Generator $faker) {
    return [
        'sysname' => 'some-furl-'.rand(0, 9999),

        'date' => date("Y-m-d H:m:s"),

        'name' => $faker->sentence,
        'descr' => $faker->sentence,
        'text' => $faker->text,


        'title' => $faker->sentence,
        'keywords' => $faker->words(10, true),
        'description' => $faker->sentences(2, true),

        'status' => rand(0, 1),
    ];
});

$factory->define(App\Models\Payment::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'descr' => $faker->sentence,
        'add' => rand(0, 1),
        'status' => rand(0, 1),
    ];
});

$factory->define(App\Models\Delivery::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'descr' => $faker->sentence,
        'price' => rand(300, 500),
        'status' => rand(0, 1),
    ];
});

$factory->define(App\Models\Order::class, function(Faker\Generator $faker) {
    return [
        'delivery_id' => '',
        'payment_id' => '',
        'customer_id' => '',

        'payment_add' => '',

        'datetime' => date('Y-m-d H:m:s'),
        'name' => $faker->name,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'amount' => rand(5000, 10000),
        'status' => array_rand(App\Models\Order::$statuses),
    ];
});

$factory->define(App\Models\Kit::class, function(Faker\Generator $faker) {
    return [ 'name' => $faker->bothify('Kit kit.###') ];
});
