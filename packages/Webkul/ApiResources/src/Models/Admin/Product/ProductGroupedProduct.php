<?php

namespace Webkul\ApiResources\Models\Admin\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    routePrefix: '/api/admin',
)]
class ProductGroupedProduct extends \Webkul\Product\Models\ProductGroupedProduct {}
