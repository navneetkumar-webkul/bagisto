<?php

namespace Webkul\ApiResources\Http\Middleware;

use Closure;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful as Middleware;

class CustomEnsureFrontendRequestsAreStateful extends Middleware
{
    /**
     * Handle the incoming request.
     */
    public function handle($request, $next)
    {
        // Skip stateful CSRF checks for API routes
        if ($request->is('api/*', 'api/v1/*')) {
            return $next($request);
        }

        // For non-API routes, use normal Sanctum behavior
        return parent::handle($request, $next);
    }
}
