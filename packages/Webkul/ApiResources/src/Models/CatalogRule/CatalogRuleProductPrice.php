<?php

namespace Webkul\ApiResources\Models\CatalogRule;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Catalog Rule Product Price resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CatalogRuleProductPrice extends \Webkul\CatalogRule\Models\CatalogRuleProductPrice
{
}
