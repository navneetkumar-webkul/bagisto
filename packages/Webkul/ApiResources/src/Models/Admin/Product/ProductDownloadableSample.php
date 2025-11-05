<?php

namespace Webkul\ApiResources\Models\Admin\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Product Downloadable Sample resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class ProductDownloadableSample extends \Webkul\Product\Models\ProductDownloadableSample {}
