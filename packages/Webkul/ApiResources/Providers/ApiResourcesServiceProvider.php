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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        Route::group(['prefix' => 'api/v1/admin', 'middleware' => ['api']], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        });
    }
}
