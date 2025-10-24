<?php

namespace Webkul\ApiResources\Http\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class Authenticate extends Middleware
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
     * Determine if the user is authenticated.
     * Override to throw AuthenticationException directly instead of calling redirectTo().
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function authenticate($request, array $guards)
    {
        if (! $this->isAuthenticated($request, $guards)) {
            $this->unauthenticated($request, $guards);
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
     * Handle an unauthenticated user by throwing an exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthorized',
            $guards
        );
    }
}
