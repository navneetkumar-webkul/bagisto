<?php

namespace Webkul\ApiResources\Models\Admin\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Product Downloadable Sample Translation resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class ProductDownloadableSampleTranslation extends \Webkul\Product\Models\ProductDownloadableSampleTranslation {}
