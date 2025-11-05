<?php

namespace Webkul\ApiResources\Models\Admin\Customer;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Customer Address  resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CustomerAddress extends \Webkul\Customer\Models\CustomerAddress {}
