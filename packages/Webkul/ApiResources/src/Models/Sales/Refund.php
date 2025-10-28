<?php

namespace Webkul\ApiResources\Models\Sales;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Refund  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Refund extends \Webkul\Sales\Models\Refund
{
}
