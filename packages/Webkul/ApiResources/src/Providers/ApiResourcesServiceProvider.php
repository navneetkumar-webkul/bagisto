<?php

namespace Webkul\ApiResources\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ApiResourcesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app['router']->aliasMiddleware('apiv2', \Webkul\ApiResources\Http\Middleware\ApiV2Middleware::class);

        // Override the default Authenticate middleware for API routes
        $this->app['router']->aliasMiddleware('api.auth', \Webkul\ApiResources\Http\Middleware\Authenticate::class);

        // Register Shop (Customer) authentication middleware
        $this->app['router']->aliasMiddleware('api.shop.auth', \Webkul\ApiResources\Http\Middleware\ShopAuthenticate::class);

        // Register GraphQL authentication middleware
        $this->app['router']->aliasMiddleware('graphql.auth', \Webkul\ApiResources\Http\Middleware\GraphQLAuthMiddleware::class);

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/graphql.php',
            'graphql'
        );

        $this->app->bind(\Webkul\ApiResources\State\AuthProcessor::class, function ($app) {
            return new \Webkul\ApiResources\State\AuthProcessor();
        });

        $this->app->bind(\Webkul\ApiResources\State\Admin\ChannelProcessor::class, function ($app) {
            return new \Webkul\ApiResources\State\Admin\ChannelProcessor();
        });

        $this->app->tag([
            \Webkul\ApiResources\State\AuthProcessor::class,
            \Webkul\ApiResources\State\Admin\ChannelProcessor::class,
        ], \ApiPlatform\State\ProcessorInterface::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerApiRoutes();

        $this->publishes([
            __DIR__ . '/../Config/graphql.php' => config_path('graphql.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../Config/api-platform.php' => config_path('api-platform.php'),
        ], 'config');
    }

    /**
     * Register API routes
     */
    protected function registerApiRoutes(): void
    {
        // Admin authentication routes
        Route::group(['prefix' => 'api/v1/admin', 'middleware' => ['api']], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/admin_auth.php');
        });

        // Shop (Customer) authentication routes
        Route::group(['prefix' => 'api/v1/shop/auth', 'middleware' => ['api']], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/shop_auth.php');
        });
    }
}
