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

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/graphql.php',
            'graphql'
        );
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
    }
}
