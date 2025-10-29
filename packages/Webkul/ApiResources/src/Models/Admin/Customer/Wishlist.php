<?php

namespace Webkul\ApiResources\Models\Admin\Customer;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    description: 'Wishlist  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Wishlist extends \Webkul\Customer\Models\Wishlist
{
}
