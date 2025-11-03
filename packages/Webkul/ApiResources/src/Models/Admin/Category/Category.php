<?php

namespace Webkul\ApiResources\Models\Admin\Category;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;

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
