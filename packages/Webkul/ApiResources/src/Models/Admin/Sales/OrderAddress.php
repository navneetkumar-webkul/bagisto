<?php

namespace Webkul\ApiResources\Models\Admin\Sales;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Order Address  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class OrderAddress extends \Webkul\Sales\Models\OrderAddress
{
}
