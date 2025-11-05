<?php

namespace Webkul\ApiResources\Models\Admin\Sitemap;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Sitemap  resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Sitemap extends \Webkul\Sitemap\Models\Sitemap {}
