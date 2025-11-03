<?php

namespace Webkul\ApiResources\Models\Admin\Core;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;

#[ApiResource(
    routePrefix: '/api/v1/admin',
    operations: [
        new GetCollection,
        new Get,
    ]
)]
class ChannelTranslation extends \Webkul\Core\Models\ChannelTranslation {}
