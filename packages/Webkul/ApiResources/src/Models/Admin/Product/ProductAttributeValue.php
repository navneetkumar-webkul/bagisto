<?php

namespace Webkul\ApiResources\Models\Admin\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Product Attribute Value resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class ProductAttributeValue extends \Webkul\Product\Models\ProductAttributeValue
{
}
