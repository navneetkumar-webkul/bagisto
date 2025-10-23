<?php

namespace Webkul\ApiResources\Models\Product;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\ApiResources\Models\Attribute\Attribute;
use Webkul\ApiResources\Models\Attribute\AttributeFamily;
use Webkul\ApiResources\Models\CatalogRule\CatalogRuleProductPrice;
use Webkul\ApiResources\Models\Category\Category;
use Webkul\ApiResources\Models\Core\Channel;
use Webkul\ApiResources\Models\Inventory\InventorySource;
use Webkul\BookingProduct\Models\BookingProductProxy;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Product\Models\ProductBundleOptionProxy;
use Webkul\Product\Models\ProductDownloadableLinkProxy;
use Webkul\Product\Models\ProductDownloadableSampleProxy;

#[ApiResource]
class Product extends \Webkul\Product\Models\Product
{
    public function product_flats(): HasMany
    {
        return $this->hasMany(ProductFlat::class, 'product_id');
    }

    public function attribute_family(): BelongsTo
    {
        return $this->belongsTo(AttributeFamily::class);
    }

    #[ApiProperty(readableLink: true)]
    public function getSuperAttributes()
    {
        return $this->super_attributes;
    }

    public function super_attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_super_attributes');
    }

    /**
     * Get the product attribute values that owns the product.
     */
    public function attribute_values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Get the product customer group prices that owns the product.
     */
    public function customer_group_prices(): HasMany
    {
        return $this->hasMany(ProductCustomerGroupPrice::class);
    }

    /**
     * Get the product customer group prices that owns the product.
     */
    public function catalog_rule_prices(): HasMany
    {
        return $this->hasMany(CatalogRuleProductPrice::class);
    }

    /**
     * Get the price indices that owns the product.
     */
    public function price_indices(): HasMany
    {
        return $this->hasMany(ProductPriceIndex::class);
    }

    /**
     * Get the inventory indices that owns the product.
     */
    public function inventory_indices(): HasMany
    {
        return $this->hasMany(ProductInventoryIndex::class);
    }

    /**
     * The categories that belong to the product.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    /**
     * The images that belong to the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id')
            ->orderBy('position');
    }

    /**
     * The videos that belong to the product.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(ProductVideo::class, 'product_id')
            ->orderBy('position');
    }

    /**
     * Get the product reviews that owns the product.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * The inventory sources that belong to the product.
     */
    public function inventory_sources(): BelongsToMany
    {
        return $this->belongsToMany(InventorySource::class, 'product_inventories')
            ->withPivot('id', 'qty');
    }

    /**
     * Get inventory source quantity.
     *
     * @return bool
     */
    public function inventory_source_qty($inventorySourceId)
    {
        return $this->inventories()
            ->where('inventory_source_id', $inventorySourceId)
            ->sum('qty');
    }

    /**
     * The inventories that belong to the product.
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(ProductInventory::class, 'product_id');
    }

    /**
     * The ordered inventories that belong to the product.
     */
    public function ordered_inventories(): HasMany
    {
        return $this->hasMany(ProductOrderedInventory::class, 'product_id');
    }

    /**
     * Get the customizable options.
     */
    public function customizable_options(): HasMany
    {
        return $this->hasMany(ProductCustomizableOption::class)
            ->orderBy('sort_order');
    }

    /**
     * Get the grouped products that owns the product.
     */
    public function grouped_products(): HasMany
    {
        return $this->hasMany(ProductGroupedProduct::class);
    }

    /**
     * Get the grouped products that owns the product.
     */
    public function booking_products(): HasMany
    {
        return $this->hasMany(BookingProductProxy::modelClass());
    }

    /**
     * The images that belong to the product.
     */
    public function downloadable_samples(): HasMany
    {
        return $this->hasMany(ProductDownloadableSampleProxy::modelClass());
    }

    /**
     * The images that belong to the product.
     */
    public function downloadable_links(): HasMany
    {
        return $this->hasMany(ProductDownloadableLinkProxy::modelClass());
    }

    /**
     * Get the bundle options that owns the product.
     */
    public function bundle_options(): HasMany
    {
        return $this->hasMany(ProductBundleOption::class);
    }

    /**
     * The cross sells that belong to the product.
     */
    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(Channel::class, 'product_channels', 'product_id', 'channel_id');
    }
}
