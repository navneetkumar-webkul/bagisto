<?php

namespace Webkul\ApiResources\Models\Customer;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Compare Item  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CompareItem extends \Webkul\Customer\Models\CompareItem
{
}
