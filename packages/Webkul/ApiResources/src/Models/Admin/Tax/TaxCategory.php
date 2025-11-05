<?php

namespace Webkul\ApiResources\Models\Admin\Tax;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Tax Category  resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class TaxCategory extends \Webkul\Tax\Models\TaxCategory {}
