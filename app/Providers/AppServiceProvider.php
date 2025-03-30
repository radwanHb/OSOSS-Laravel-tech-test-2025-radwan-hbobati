<?php

namespace App\Providers;

use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductService::class, function ($app) {
            return new ProductService( $app->make(ProductRepository::class)); // Injecting the ProductRepository dependency
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
