<?php

namespace Webkul\ApiResources\Models\Sales;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Refund Item  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class RefundItem extends \Webkul\Sales\Models\RefundItem
{
}
