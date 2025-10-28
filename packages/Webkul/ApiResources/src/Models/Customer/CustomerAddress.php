<?php

namespace Webkul\ApiResources\Models\Customer;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Customer Address  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CustomerAddress extends \Webkul\Customer\Models\CustomerAddress
{
}
