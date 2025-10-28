<?php

namespace Webkul\ApiResources\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GraphQLAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // For GET requests (GraphiQL playground), skip authentication entirely
        if ($request->isMethod('GET')) {
            return $next($request);
        }

        // For POST requests, allow them to proceed - the GraphQL resolver will handle auth if needed
        // or let unauthenticated users execute queries that don't require auth
        return $next($request);
    }
}
