<?php

namespace Webkul\ApiResources\Models\Admin\Sales;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Sales\Models\OrderItemProxy;

#[ApiResource(
    description: 'Order Item  resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class OrderItem extends \Webkul\Sales\Models\OrderItem
{
    /**
     * Get the product associated with the order item.
     * Note: Excluded from API serialization to avoid referencing unregistered polymorphic model.
     */
    #[ApiProperty(readable: false, writable: false)]
    public function product(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get child item (bundled product).
     */
    #[ApiProperty(readableLink: true)]
    public function child(): HasOne
    {
        return $this->hasOne(OrderItemProxy::modelClass(), 'parent_id');
    }

    /**
     * Get all child items.
     */
    #[ApiProperty(readableLink: true)]
    public function children(): HasMany
    {
        return $this->hasMany(OrderItemProxy::modelClass(), 'parent_id');
    }

    /**
     * Get parent item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(OrderItemProxy::modelClass(), 'parent_id');
    }
}
