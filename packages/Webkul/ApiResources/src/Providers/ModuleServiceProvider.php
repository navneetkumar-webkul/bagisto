<?php

namespace Webkul\ApiResources\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Illuminate\Support\Facades\Route;

use Webkul\ApiResources\Http\Middleware\ApiPrefixAuthMiddleware;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * The name of this module
     */
    protected $name = 'ApiResources';

    public function boot(): void
    {
        if ($this->app->bound('router')) {
            $this->app['router']->aliasMiddleware('api.prefix.auth', ApiPrefixAuthMiddleware::class);
        }

        $this->loadRoutesFrom(__DIR__.'/../../routes/docs.php');
    }
}
