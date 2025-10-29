<?php

namespace Webkul\ApiResources\Models\Admin\CatalogRule;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Catalog Rule Product resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class CatalogRuleProduct extends \Webkul\CatalogRule\Models\CatalogRuleProduct
{
}
