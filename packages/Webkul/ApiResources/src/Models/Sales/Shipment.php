<?php

namespace Webkul\ApiResources\Models\Sales;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Sales\Models\ShipmentItemProxy;

#[ApiResource(
    description: 'Shipment  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Shipment extends \Webkul\Sales\Models\Shipment
{
    /**
     * Get shipment items.
     */
    #[ApiProperty(readableLink: true)]
    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItemProxy::modelClass());
    }
}
