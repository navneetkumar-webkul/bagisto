<?php

namespace Webkul\ApiResources\Models\Admin\CartRule;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart Rule Coupon Usage  resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CartRuleCouponUsage extends \Webkul\CartRule\Models\CartRuleCouponUsage {}
