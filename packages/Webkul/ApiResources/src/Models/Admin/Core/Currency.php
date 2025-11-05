<?php

namespace Webkul\ApiResources\Models\Admin\Core;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Currency resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Currency extends \Webkul\Core\Models\Currency {}
