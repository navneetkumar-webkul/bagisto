<?php

namespace Webkul\ApiResources\Models\Admin\Core;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Core Config resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CoreConfig extends \Webkul\Core\Models\CoreConfig {}
