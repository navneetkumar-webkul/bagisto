<?php

namespace Webkul\ApiResources\Models\Admin\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    routePrefix: '/api/v1/admin',
    )]
class ProductBundleOption extends \Webkul\Product\Models\ProductBundleOption
{
}
