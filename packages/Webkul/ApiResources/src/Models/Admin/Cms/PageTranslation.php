<?php

namespace Webkul\ApiResources\Models\Admin\Cms;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'CMS Page Translation resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class PageTranslation extends \Webkul\CMS\Models\PageTranslation
{
}
