<?php

namespace Webkul\ApiResources\Models\Admin\Attribute;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Attribute Group resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class AttributeGroup extends \Webkul\Attribute\Models\AttributeGroup {}
