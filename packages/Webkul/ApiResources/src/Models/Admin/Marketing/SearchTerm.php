<?php

namespace Webkul\ApiResources\Models\Admin\Marketing;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Search Term resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class SearchTerm extends \Webkul\Marketing\Models\SearchTerm
{
}
