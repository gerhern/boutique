<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Observers\ProductImageObserver;
use App\Policies\products\ProductPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Product::class, ProductPolicy::class);
        ProductImage::observe(ProductImageObserver::class);
    }
}
