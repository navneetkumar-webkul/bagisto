<?php

namespace Webkul\ApiResources\Models\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    routePrefix: '/api/v1/admin',
    )]
class ProductVideo extends \Webkul\Product\Models\ProductVideo
{
}
