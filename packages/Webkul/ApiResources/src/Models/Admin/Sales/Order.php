<?php

namespace Webkul\ApiResources\Models\Admin\Sales;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\ApiResources\Models\Admin\Core\Channel;

#[ApiResource(
    description: 'Order  resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Order extends \Webkul\Sales\Models\Order
{
    /**
     * The attributes that should be appended to the model's array form.
     */
    protected $appends = ['items', 'invoices', 'shipments', 'addresses'];

    /**
     * Get items for API serialization
     */
    public function getItemsAttribute()
    {
        if (! $this->relationLoaded('items')) {
            $this->load('items');
        }

        return $this->relations['items'] ?? [];
    }

    /**
     * Get invoices for API serialization
     */
    public function getInvoicesAttribute()
    {
        if (! $this->relationLoaded('invoices')) {
            $this->load('invoices');
        }

        return $this->relations['invoices'] ?? [];
    }

    /**
     * Get shipments for API serialization
     */
    public function getShipmentsAttribute()
    {
        if (! $this->relationLoaded('shipments')) {
            $this->load('shipments');
        }

        return $this->relations['shipments'] ?? [];
    }

    /**
     * Get addresses for API serialization
     */
    public function getAddressesAttribute()
    {
        if (! $this->relationLoaded('addresses')) {
            $this->load('addresses');
        }

        return $this->relations['addresses'] ?? [];
    }

    /**
     * Get the associated cart that was used to create this order.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order items record associated with the order.
     */
    #[ApiProperty(readableLink: true)]
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)
            ->whereNull('parent_id');
    }

    /**
     * Get the comments record associated with the order.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(OrderComment::class);
    }

    /**
     * Get the order items record associated with the order.
     */
    public function all_items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the order record associated with the item.
     */
    public function downloadable_link_purchased()
    {
        return $this->hasMany(\Webkul\ApiResources\Models\Admin\Sales\DownloadableLinkPurchased::class);
    }

    /**
     * Get the order shipments record associated with the order.
     */
    #[ApiProperty(readableLink: true)]
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    /**
     * Get the order invoices record associated with the order.
     */
    #[ApiProperty(readableLink: true)]
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the order refunds record associated with the order.
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    /**
     * Get the order transactions record associated with the order.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(OrderTransaction::class);
    }

    /**
     * Get the addresses for the order.
     */
    #[ApiProperty(readableLink: true)]
    public function addresses(): HasMany
    {
        return $this->hasMany(OrderAddress::class);
    }

    /**
     * Get the payment for the order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(OrderPayment::class);
    }

    /**
     * Get the channel record associated with the order.
     * Note: Excluded from API serialization to avoid referencing unregistered model class.
     */
    #[ApiProperty(readable: false, writable: false)]
    public function channel(): MorphTo
    {
        return $this->morphTo();
    }

    public function customer(): MorphTo
    {
        return $this->morphTo();
    }
}
