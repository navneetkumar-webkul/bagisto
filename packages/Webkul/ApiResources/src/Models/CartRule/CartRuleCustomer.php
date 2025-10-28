<?php

namespace Webkul\ApiResources\Models\CartRule;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart Rule Customer  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CartRuleCustomer extends \Webkul\CartRule\Models\CartRuleCustomer
{
}
