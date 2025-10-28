<?php

namespace Webkul\ApiResources\Models\Checkout;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Cart extends \Webkul\Checkout\Models\Cart
{
}
