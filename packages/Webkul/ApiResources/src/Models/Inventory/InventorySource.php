<?php

namespace Webkul\ApiResources\Models\Inventory;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    routePrefix: '/api/v1/admin',
)]
class InventorySource extends \Webkul\Inventory\Models\InventorySource
{
}
