<?php

namespace Webkul\ApiResources\Models\Admin\Attribute;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Attribute Option resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class AttributeOption extends \Webkul\Attribute\Models\AttributeOption
{
    #[ApiProperty(readableLink: true)]
    public function getTranslations()
    {
        return $this->translations;
    }

}
