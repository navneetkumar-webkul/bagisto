<?php

namespace Webkul\ApiResources\Models\Admin\Marketing;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Search Synonym resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class SearchSynonym extends \Webkul\Marketing\Models\SearchSynonym {}
