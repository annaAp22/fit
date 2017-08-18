<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\Article::class        => \App\Policies\ArticlePolicy::class,
        \App\Models\Banner::class         => \App\Policies\BannerPolicy::class,
        \App\Models\Photo::class         => \App\Policies\PhotoPolicy::class,
        \App\Models\Brand::class          => \App\Policies\BrandPolicy::class,
        \App\Models\Category::class       => \App\Policies\CategoryPolicy::class,
        \App\Models\City::class           => \App\Policies\CityPolicy::class,
        \App\Models\Shop::class           => \App\Policies\ShopPolicy::class,
        \App\Models\Certificate::class    => \App\Policies\CertificatePolicy::class,
        \App\Models\Page::class           => \App\Policies\PagePolicy::class,
        \App\Models\Kit::class            => \App\Policies\KitPolicy::class,
        \App\Models\Metatag::class        => \App\Policies\MetatagPolicy::class,
        \App\Models\News::class           => \App\Policies\NewsPolicy::class,
        \App\Models\Order::class          => \App\Policies\OrderPolicy::class,
        \App\Models\Product::class        => \App\Policies\ProductPolicy::class,
        \App\Models\ProductComment::class => \App\Policies\ProductCommentPolicy::class,
        \App\Models\Review::class         => \App\Policies\ReviewPolicy::class,
        \App\Models\Setting::class        => \App\Policies\SettingPolicy::class,
        \App\Models\Tag::class            => \App\Policies\TagPolicy::class,
        \App\User::class                  => \App\Policies\UserPolicy::class,
        \App\Models\Look::class           => \App\Policies\LookPolicy::class,
        \App\Models\LookCategory::class   => \App\Policies\LookCategoryPolicy::class,
        \App\Models\Offer::class          => \App\Policies\OfferPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('register-user', function ($user) {
            return $user->group()->first()->name == 'Admin';
        });
    }
}
