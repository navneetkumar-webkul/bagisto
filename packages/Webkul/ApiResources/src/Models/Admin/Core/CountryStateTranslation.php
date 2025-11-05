<?php

namespace Webkul\ApiResources\Models\Admin\Core;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ApiResource(
    routePrefix: '/api/admin',
    operations: [
        new GetCollection,
        new Get,
    ]
)]
class CountryStateTranslation extends \Webkul\Core\Models\CountryStateTranslation {}
