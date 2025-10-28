<?php

namespace Webkul\ApiResources\Models\Core;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Core Config resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CoreConfig extends \Webkul\Core\Models\CoreConfig
{
}
