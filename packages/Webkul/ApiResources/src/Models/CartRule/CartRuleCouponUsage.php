<?php

namespace Webkul\ApiResources\Models\CartRule;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart Rule Coupon Usage  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CartRuleCouponUsage extends \Webkul\CartRule\Models\CartRuleCouponUsage
{
}
