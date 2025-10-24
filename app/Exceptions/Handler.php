<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends \Illuminate\Foundation\Exceptions\Handler
{
    public function render($request, Throwable $e)
    {
        // For API routes, return JSON responses
        if ($request->expectsJson() || $request->is('api/*')) {
            if ($e instanceof ValidationException) {
                return response()->json([
                    'errors' => $e->errors(),
                ], 422);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => 'Authentication token required',
                ], 401);
            }

            if ($e instanceof RouteNotFoundException && str_contains($e->getMessage(), 'Route [login]')) {
                // Handle the "Route [login] not defined" error for API requests
                return response()->json([
                    'message' => 'Unauthorized',
                    'error' => 'Authentication token required',
                ], 401);
            }
        }

        return parent::render($request, $e);
    }
}
