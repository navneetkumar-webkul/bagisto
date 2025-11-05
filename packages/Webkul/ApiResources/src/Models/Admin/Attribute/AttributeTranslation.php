<?php

namespace Webkul\ApiResources\Models\Admin\Attribute;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Attribute Translation  resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class AttributeTranslation extends \Webkul\Attribute\Models\AttributeTranslation {}
