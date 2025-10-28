<?php

namespace Webkul\ApiResources\Models\Marketing;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Campaign resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Campaign extends \Webkul\Marketing\Models\Campaign
{
}
