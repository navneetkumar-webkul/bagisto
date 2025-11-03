<?php

namespace Webkul\ApiResources\Models\Admin\Marketing;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    shortName: 'UrlRewrite',
    description: 'URL Rewrite resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class URLRewrite extends \Webkul\Marketing\Models\URLRewrite {}
