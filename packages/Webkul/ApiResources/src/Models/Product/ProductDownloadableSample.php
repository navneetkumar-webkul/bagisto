<?php

namespace Webkul\ApiResources\Models\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Product Downloadable Sample resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class ProductDownloadableSample extends \Webkul\Product\Models\ProductDownloadableSample
{
}
