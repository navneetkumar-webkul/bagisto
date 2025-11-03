<?php

namespace Webkul\ApiResources\Models\Admin\CartRule;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart Rule Coupon  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CartRuleCoupon extends \Webkul\CartRule\Models\CartRuleCoupon {}
