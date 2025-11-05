<?php

namespace Webkul\ApiResources\Models\Admin\Core;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Locale resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Locale extends \Webkul\Core\Models\Locale {}
