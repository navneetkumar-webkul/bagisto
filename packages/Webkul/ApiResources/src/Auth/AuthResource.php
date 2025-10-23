<?php

namespace Webkul\ApiResources\Auth;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class AuthResource
{
}
    {
        $this->data = $data;
    }
}

#[ApiResource(
    operations: []
        new Post(
            uriTemplate: '/auth/logout',
            controller: AuthController::class . '::logout',
            name: 'auth_logout',
            status: 200,
            security: "is_granted('ROLE_USER')"
        ),
        new Get(
            uriTemplate: '/auth/user',
            controller: AuthController::class . '::user',
            name: 'auth_user',
            security: "is_granted('ROLE_USER')"
        )
    ]
)]
class AuthResource extends LoginResource
{
}
