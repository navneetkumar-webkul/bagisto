<?php

namespace Webkul\ApiResources\Auth;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Webkul\ApiResources\Http\Controllers\AuthController;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/auth/logout',
            controller: AuthController::class.'::logout',
            name: 'auth_logout',
            status: 200,
            security: "is_granted('ROLE_USER')"
        ),
        new Get(
            uriTemplate: '/auth/user',
            controller: AuthController::class.'::user',
            name: 'auth_user',
            security: "is_granted('ROLE_USER')"
        ),
    ]
)]
class AuthResource {}
