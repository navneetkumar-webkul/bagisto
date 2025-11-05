<?php

namespace Webkul\ApiResources\Models\Admin\Sales;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Invoice Item  resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class InvoiceItem extends \Webkul\Sales\Models\InvoiceItem {}
