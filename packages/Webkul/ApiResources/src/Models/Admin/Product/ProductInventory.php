<?php

namespace Webkul\ApiResources\Models\Admin\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    routePrefix: '/api/admin',
)]
class ProductInventory extends \Webkul\Product\Models\ProductInventory {}
