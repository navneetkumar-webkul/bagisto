<?php

namespace Webkul\ApiResources\Models\Admin\Marketing;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Template resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Template extends \Webkul\Marketing\Models\Template {}
