<?php

namespace Webkul\ApiResources\Models\Category;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;

#[ApiResource]
class Category extends \Webkul\Category\Models\Category
{
    /**
     * Get the category children.
     */
    #[ApiProperty(readableLink: true)]
    public function getChildren()
    {
        return $this->children;
    }
}
