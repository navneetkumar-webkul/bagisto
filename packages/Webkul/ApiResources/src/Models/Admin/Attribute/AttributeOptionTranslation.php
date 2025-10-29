<?php

namespace Webkul\ApiResources\Models\Admin\Attribute;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Attribute Option Translation  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class AttributeOptionTranslation extends \Webkul\Attribute\Models\AttributeOptionTranslation
{

}
