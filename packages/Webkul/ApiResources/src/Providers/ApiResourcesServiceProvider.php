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
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        Route::group(['prefix' => 'api/v1/admin', 'middleware' => ['api']], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/admin_auth.php');
        });

        $this->publishes([
            __DIR__ . '/../Config/graphql.php' => config_path('graphql.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../Config/api-platform.php' => config_path('api-platform.php'),
        ], 'config');
    }
}
