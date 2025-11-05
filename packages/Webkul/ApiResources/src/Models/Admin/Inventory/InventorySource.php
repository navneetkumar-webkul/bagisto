<?php

namespace Webkul\ApiResources\Models\Admin\Inventory;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    routePrefix: '/api/admin',
)]
class InventorySource extends \Webkul\Inventory\Models\InventorySource {}
