<?php

namespace Webkul\ApiResources\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as AuthenticateMiddleware;
use Illuminate\Http\Request;

/**
 * Middleware that dispatches authentication based on API prefix.
 * - /api/admin/* -> enforces 'sanctum' guard
 * - /api/shop/*  -> currently left public (no guard)
 *
 * Adjust guards or prefixes as needed for your project.
 */
class ApiPrefixAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/admin*') || $request->is('api/admin')) {
            $authMiddleware = app(AuthenticateMiddleware::class);

            return $authMiddleware->handle($request, $next, 'sanctum');
        }

        if ($request->is('api/shop*') || $request->is('api/shop')) {

            return $next($request);
        }

        return $next($request);
    }
}
