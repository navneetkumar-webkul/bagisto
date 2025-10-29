<?php

namespace Webkul\ApiResources\Models\Admin\Customer;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Customer Group  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CustomerGroup extends \Webkul\Customer\Models\CustomerGroup
{
}
