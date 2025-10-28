<?php

namespace Webkul\ApiResources\Models\Checkout;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart Payment resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CartPayment extends \Webkul\Checkout\Models\CartPayment
{
}
