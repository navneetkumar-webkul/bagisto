<?php

namespace Webkul\ApiResources\Models\Product;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Product Downloadable Link Translation resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class ProductDownloadableLinkTranslation extends \Webkul\Product\Models\ProductDownloadableLinkTranslation
{
}
