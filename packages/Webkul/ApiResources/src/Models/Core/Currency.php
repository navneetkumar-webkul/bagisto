<?php

namespace Webkul\ApiResources\Models\Core;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Currency resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Currency extends \Webkul\Core\Models\Currency
{

}
