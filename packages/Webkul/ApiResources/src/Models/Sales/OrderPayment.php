<?php

namespace Webkul\ApiResources\Models\Sales;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Order Payment  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class OrderPayment extends \Webkul\Sales\Models\OrderPayment
{
}
