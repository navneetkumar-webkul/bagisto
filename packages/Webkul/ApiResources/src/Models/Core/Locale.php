<?php

namespace Webkul\ApiResources\Models\Core;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Locale resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Locale extends \Webkul\Core\Models\Locale
{

}
