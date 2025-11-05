<?php

namespace Webkul\ApiResources\Models\Admin\Marketing;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Campaign resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Campaign extends \Webkul\Marketing\Models\Campaign {}
