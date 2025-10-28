<?php

namespace Webkul\ApiResources\Models\Checkout;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart Shipping Rate resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CartShippingRate extends \Webkul\Checkout\Models\CartShippingRate
{
}
