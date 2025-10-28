<?php

namespace Webkul\ApiResources\Models\Category;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;

#[ApiResource(
    description: 'Category  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
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
