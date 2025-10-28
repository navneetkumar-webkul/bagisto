<?php

namespace Webkul\ApiResources\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class ShopAuthenticate extends Middleware
{
    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        parent::__construct($auth);
    }

    /**
     * Determine if the user is authenticated as a customer.
     * Ensures only customers can access protected routes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function authenticate($request, array $guards)
    {
        if (!$this->isAuthenticated($request, $guards)) {
            $this->unauthenticated($request, $guards);
        }

        // Verify the authenticated user is a customer
        $user = auth('sanctum')->user();

        if ($user && $user->hasRole('admin')) {
            throw new AuthenticationException('Admins cannot access customer endpoints.');
        }
    }

    /**
     * Check if any of the guards are authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return bool
     */
    protected function isAuthenticated($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // Don't redirect API requests
        return null;
    }
}
