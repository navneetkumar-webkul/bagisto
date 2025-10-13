<?php

namespace Webkul\ApiResources\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiV2Middleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/ld+json');

        return $next($request);
    }
}
