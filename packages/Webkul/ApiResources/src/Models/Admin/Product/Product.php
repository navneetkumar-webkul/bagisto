<?php

namespace Webkul\ApiResources\Models\Admin\Product;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\QueryCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\Mutation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\ApiResources\Models\Admin\Attribute\Attribute;
use Webkul\ApiResources\Models\Admin\Attribute\AttributeFamily;
use Webkul\ApiResources\Models\Admin\CatalogRule\CatalogRuleProductPrice;
use Webkul\ApiResources\Models\Admin\Category\Category;
use Webkul\ApiResources\Models\Admin\Core\Channel;
use Webkul\ApiResources\Models\Admin\Inventory\InventorySource;
use Webkul\ApiResources\State\Admin\ProductProcessor;
use Webkul\ApiResources\Http\Requests\Admin\ProductFormRequest;
use Webkul\BookingProduct\Models\BookingProductProxy;
use Webkul\Product\Models\ProductDownloadableLinkProxy;
use Webkul\Product\Models\ProductDownloadableSampleProxy;

#[ApiResource(
    description: 'Product resource',
    routePrefix: '/api/admin',
    security: "is_granted('ROLE_ADMIN')",
    paginationEnabled: true,
    paginationItemsPerPage: 5,
    paginationClientItemsPerPage: true,
    operations: [
        new Get(
            security: "is_granted('VIEW_PRODUCT')"
        ),
        new GetCollection(
            security: "is_granted('VIEW_PRODUCT')"
        ),
        new Post(
            security: "is_granted('CREATE_PRODUCT')",
            processor: ProductProcessor::class
            
        ),
        new Patch(
            security: "is_granted('EDIT_PRODUCT')"
        ),
        new Delete(
            security: "is_granted('DELETE_PRODUCT')"
        ),
    ],
    graphQlOperations: [
        new Query(
            name: 'item_query',
            security: "is_granted('VIEW_PRODUCT')"
        ),
        new QueryCollection(
            name: 'collection_query',
            security: "is_granted('VIEW_PRODUCT')"
        ),
        new Mutation(
            name: 'create',
            security: "is_granted('CREATE_PRODUCT')",
            processor: ProductProcessor::class
        ),
        new Mutation(
            name: 'update',
            security: "is_granted('EDIT_PRODUCT')"
        ),
        new Mutation(
            name: 'delete',
            security: "is_granted('DELETE_PRODUCT')"
        ),
    ]
)]
class Product extends \Webkul\Product\Models\Product
{
    protected $appends = ['all_attributes'];

    public function getAllAttributesAttribute()
    {
        $attributes = [];

        if (! isset($this->id) || ! $this->attribute_family) {
            return $attributes;
        }

        $familyAttributes = $this->checkInLoadedFamilyAttributes();

        $requestedLocales = $this->getRequestedLocales();

        $requestedChannels = $this->getRequestedChannels();

        foreach ($familyAttributes as $attribute) {

            if ($attribute->value_per_locale) {

                if (count($requestedLocales) === 1 && $requestedLocales[0] === core()->getDefaultLocaleCodeFromDefaultChannel()) {
                    $attributes[$attribute->code] = $this->getAttributeValueForLocale($attribute, $requestedLocales[0]);
                } else {
                    foreach ($requestedLocales as $locale) {

                        if ($attribute->value_per_channel) {

                            foreach ($requestedChannels as $channel) {
                                $value = $this->getAttributeValueForLocaleAndChannel($attribute, $locale, $channel);
                                $attributes[$attribute->code][$channel][$locale] = $value;
                            }

                        } else {
                            $value = $this->getAttributeValueForLocale($attribute, $locale);

                            $attributes[$attribute->code][$locale] = $value;
                        }
                    }
                }

            } else {

                $attributes[$attribute->code] = $this->getCustomAttributeValue($attribute);

            }
        }

        if ($this->isGraphQLRequest()) {
            return json_encode($attributes);
        }

        return $attributes;
    }

    /**
     * Check if the current request is a GraphQL request
     */
    protected function isGraphQLRequest(): bool
    {
        return request()->is('graphql') ||
               request()->is('api/graphql') ||
               request()->header('Content-Type') === 'application/graphql' ||
               request()->has('query');
    }

    /**
     * Get requested locales from query parameter
     */
    protected function getRequestedLocales(): array
    {
        $localesParam = request()->query('locales');

        if ($localesParam) {
            $locales = array_map('trim', explode(',', $localesParam));

            $availableLocales = core()->getAllLocales()->pluck('code')->toArray();

            $locales = array_intersect($locales, $availableLocales);

            return ! empty($locales) ? $locales : [core()->getDefaultLocaleCodeFromDefaultChannel()];
        }

        return [core()->getDefaultLocaleCodeFromDefaultChannel()];
    }

    /**
     * Get requested channels from query parameter
     */
    protected function getRequestedChannels(): array
    {
        $channelsParam = request()->query('channels');

        if ($channelsParam) {

            $channels = array_map('trim', explode(',', $channelsParam));

            $availableChannels = core()->getAllChannels()->pluck('code')->toArray();

            $channels = array_intersect($channels, $availableChannels);

            return ! empty($channels) ? $channels : [core()->getDefaultChannelCode()];
        }

        return [core()->getDefaultChannelCode()];
    }

    protected function getAttributeValueForLocale($attribute, $locale)
    {
        $attributeValue = $this->attribute_values
            ->where('locale', $locale)
            ->where('attribute_id', $attribute->id)
            ->first();

        return $attributeValue[$attribute->column_name] ?? $attribute->default_value;
    }

    protected function getAttributeValueForLocaleAndChannel($attribute, $locale, $channel)
    {
        $attributeValue = $this->attribute_values
            ->where('channel', $channel)
            ->where('locale', $locale)
            ->where('attribute_id', $attribute->id)
            ->first();

        return $attributeValue[$attribute->column_name] ?? $attribute->default_value;
    }

    public function product_flats(): HasMany
    {
        return $this->hasMany(ProductFlat::class, 'product_id');
    }

    public function attribute_family(): BelongsTo
    {
        return $this->belongsTo(AttributeFamily::class);
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
