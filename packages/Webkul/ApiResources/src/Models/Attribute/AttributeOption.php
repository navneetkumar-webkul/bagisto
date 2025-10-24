<?php

namespace Webkul\ApiResources\Models\Attribute;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
class AttributeOption extends \Webkul\Attribute\Models\AttributeOption
{
    #[ApiProperty(readableLink: true)]
    public function getTranslations()
    {
        return $this->translations;
    }

}
