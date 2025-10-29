<?php

namespace Webkul\ApiResources\Models\Admin\Inventory;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    routePrefix: '/api/v1/admin',
)]
class InventorySource extends \Webkul\Inventory\Models\InventorySource
{
}
