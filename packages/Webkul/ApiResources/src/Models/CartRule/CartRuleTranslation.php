<?php

namespace Webkul\ApiResources\Models\CartRule;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Cart Rule Translation  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CartRuleTranslation extends \Webkul\CartRule\Models\CartRuleTranslation
{
}
