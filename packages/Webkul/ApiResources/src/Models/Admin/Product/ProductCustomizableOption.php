<?php

namespace Webkul\ApiResources\Models\Admin\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    routePrefix: '/api/admin',
)]
class ProductCustomizableOption extends \Webkul\Product\Models\ProductCustomizableOption {}
