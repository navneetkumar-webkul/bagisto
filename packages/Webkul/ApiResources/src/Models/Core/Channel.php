<?php

namespace Webkul\ApiResources\Models\Core;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\ApiResources\Models\Category\Category;
use Webkul\ApiResources\Models\Inventory\InventorySource;

#[ApiResource(
    description: 'Channel resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')"
)]
class Channel extends \Webkul\Core\Models\Channel
{
    #[ApiProperty(readableLink: true)]
    public function getLocales()
    {
        return $this->locales;
    }

    #[ApiProperty(writable: true)]
    public function setLocales($locales)
    {
        if (is_array($locales)) {
            $localeIds = array_map(function($locale) {
                if (is_string($locale)) {
                    return (int) basename($locale);
                }
                return is_object($locale) ? $locale->id : $locale;
            }, $locales);

            $this->locales()->sync($localeIds);
        }
    }

    #[ApiProperty(readableLink: true)]
    public function getCurrencies()
    {
        return $this->currencies;
    }

    #[ApiProperty(readableLink: true)]
    public function getTranslations()
    {
        return $this->translations;
    }

    #[ApiProperty(readableLink: true)]
    public function getRoot_category()
    {
        return $this->root_category;
    }

    public function locales(): BelongsToMany
    {
        return $this->belongsToMany(Locale::class, 'channel_locales');
    }

    // public function default_locale(): BelongsTo
    // {
    //     return $this->belongsTo(Locale::class);
    // }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'channel_currencies');
    }

    public function inventory_sources(): BelongsToMany
    {
        return $this->belongsToMany(InventorySource::class, 'channel_inventory_sources');
    }

    public function base_currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function root_category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'root_category_id');
    }
}
