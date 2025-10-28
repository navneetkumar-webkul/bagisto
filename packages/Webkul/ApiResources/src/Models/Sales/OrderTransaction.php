<?php

namespace Webkul\ApiResources\Models\Sales;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Order Transaction resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class OrderTransaction extends \Webkul\Sales\Models\OrderTransaction
{
}
