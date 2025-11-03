<?php

namespace Webkul\ApiResources\Models\Admin\Attribute;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    shortName: 'AttributeFamily',
    description: 'Product attribute family resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class AttributeFamily extends \Webkul\Attribute\Models\AttributeFamily {}
