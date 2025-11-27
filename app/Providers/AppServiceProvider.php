<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Item;
use App\Models\Category;
use App\Models\Banner;
use App\Observers\ItemObserver;
use App\Observers\CategoryObserver;
use App\Observers\BannerObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Schema::defaultStringLength(191);

        // Register model observers to invalidate home cache on data changes
        Item::observe(ItemObserver::class);
        Category::observe(CategoryObserver::class);
        Banner::observe(BannerObserver::class);
    }
}

