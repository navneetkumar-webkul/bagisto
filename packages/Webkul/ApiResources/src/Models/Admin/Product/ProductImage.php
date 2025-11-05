<?php

namespace Webkul\ApiResources\Models\Admin\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    routePrefix: '/api/admin',
)]
class ProductImage extends \Webkul\Product\Models\ProductImage {}
