<?php

namespace Webkul\ApiResources\Models\Admin\Checkout;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart Item resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CartItem extends \Webkul\Checkout\Models\CartItem {}
