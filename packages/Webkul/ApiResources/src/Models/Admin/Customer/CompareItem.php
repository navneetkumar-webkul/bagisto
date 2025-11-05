<?php

namespace Webkul\ApiResources\Models\Admin\Customer;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Compare Item  resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CompareItem extends \Webkul\Customer\Models\CompareItem {}
