<?php

namespace Webkul\ApiResources\Models\Sales;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Invoice Item  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class InvoiceItem extends \Webkul\Sales\Models\InvoiceItem
{
}
