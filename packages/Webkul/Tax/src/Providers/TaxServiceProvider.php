<?php

namespace Webkul\Tax\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxRate;

class TaxServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(\Webkul\Tax\Contracts\TaxCategory::class, TaxCategory::class);
        $this->app->bind(\Webkul\Tax\Contracts\TaxRate::class, TaxRate::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
