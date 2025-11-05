<?php

namespace Webkul\ApiResources\Models\Admin\Tax;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Tax Rate  resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class TaxRate extends \Webkul\Tax\Models\TaxRate {}
