<?php

namespace Webkul\ApiResources\Models\Admin\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Product Downloadable Link Translation resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class ProductDownloadableLinkTranslation extends \Webkul\Product\Models\ProductDownloadableLinkTranslation {}
