<?php

namespace Webkul\ApiResources\Models\Admin\Cms;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'CMS Page resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Page extends \Webkul\CMS\Models\Page {}
