<?php

namespace Webkul\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Customer\Facades\Captcha;
use Webkul\Customer\Models\CustomerGroup;

class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(\Webkul\Customer\Contracts\CustomerGroup::class, CustomerGroup::class);
    }

    /**
     * Bootstrap application services.
     *
     * @param  \Illuminate\Routing\Router  $router
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'customer');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'customer');

        $this->app['validator']->extend('captcha', function ($attribute, $value, $parameters) {
            return Captcha::getFacadeRoot()->validateResponse($value);
        });
    }
}
