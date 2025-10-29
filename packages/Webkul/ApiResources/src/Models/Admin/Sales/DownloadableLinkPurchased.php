<?php

namespace Webkul\ApiResources\Models\Admin\Sales;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Downloadable Link Purchased  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class DownloadableLinkPurchased extends \Webkul\Sales\Models\DownloadableLinkPurchased
{
}
