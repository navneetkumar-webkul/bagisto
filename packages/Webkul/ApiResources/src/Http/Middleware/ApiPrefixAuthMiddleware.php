<?php

namespace Webkul\ApiResources\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as AuthenticateMiddleware;
use Illuminate\Http\Request;

/**
 * Middleware that dispatches authentication based on API prefix.
 * - /api/v1/admin/* -> enforces 'sanctum' guard
 * - /api/v1/shop/*  -> currently left public (no guard)
 *
 * Adjust guards or prefixes as needed for your project.
 */
class ApiPrefixAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/v1/admin*') || $request->is('api/v1/admin')) {
            $authMiddleware = app(AuthenticateMiddleware::class);

            return $authMiddleware->handle($request, $next, 'sanctum');
        }

        if ($request->is('api/v1/shop*') || $request->is('api/v1/shop')) {

            return $next($request);
        }

        return $next($request);
    }
}
