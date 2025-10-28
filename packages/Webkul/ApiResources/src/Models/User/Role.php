<?php

namespace Webkul\ApiResources\Models\User;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Role resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Role extends \Webkul\User\Models\Role
{
}
