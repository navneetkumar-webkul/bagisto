<?php

namespace Webkul\ApiResources\Models\Admin\Checkout;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Cart extends \Webkul\Checkout\Models\Cart {}
