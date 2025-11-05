<?php

namespace Webkul\ApiResources\Models\Admin\Checkout;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart Payment resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CartPayment extends \Webkul\Checkout\Models\CartPayment {}
