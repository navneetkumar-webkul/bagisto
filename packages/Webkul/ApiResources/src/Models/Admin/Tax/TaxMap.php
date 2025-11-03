<?php

namespace Webkul\ApiResources\Models\Admin\Tax;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Tax Map  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class TaxMap extends \Webkul\Tax\Models\TaxMap {}
