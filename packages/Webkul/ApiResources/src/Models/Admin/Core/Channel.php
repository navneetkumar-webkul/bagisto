<?php

namespace Webkul\ApiResources\Models\Admin\Core;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\ApiResources\Models\Admin\Category\Category;
use Webkul\ApiResources\Models\Admin\Inventory\InventorySource;
use ApiPlatform\Metadata\Post;
use Webkul\ApiResources\State\Admin\ChannelProcessor;
use ApiPlatform\OpenApi\Model;

#[ApiResource(
    description: 'Channel resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')",
    operations: [
        new Post (
            processor: ChannelProcessor::class,
            openapi: new Model\Operation(
                summary: 'Store the channel',
                description: 'Channel creation endpoint',
                tags: ['Channel'],
                parameters: [],
                requestBody: new Model\RequestBody(
                    description: 'Admin credentials',
                    required: true,
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'code' => [
                                        'type' => 'string',
                                        'example' => 'ncr',
                                    ],
                                    'name' => [
                                        'type' => 'string',
                                        'example' => 'NCR Region',
                                    ],
                                    'description' => [
                                        'type' => 'string',
                                        'nullable' => true,
                                        'example' => null,
                                    ],
                                    'inventory_sources' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string',
                                            'example' => 'api/v1/inventory_sources/1',
                                        ],
                                    ],
                                    'root_category_id' => [
                                        'type' => 'integer',
                                        'example' => 1,
                                    ],
                                    'hostname' => [
                                        'type' => 'string',
                                        'example' => 'example.com',
                                    ],
                                    'locales' => [
                                        'type' => 'array',
                                        'items' => [
                                            'oneOf' => [
                                                ['type' => 'integer'],
                                                ['type' => 'string'],
                                            ],
                                            'example' => ['api/v1/locales/1'],
                                        ],
                                    ],
                                    'default_locale_id' => [
                                        'type' => 'integer',
                                        'example' => 'api/v1/locales/1',
                                    ],
                                    'currencies' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'integer',
                                            'example' => 'api/v1/currencies/1',
                                        ],
                                    ],
                                    'base_currency_id' => [
                                        'type' => 'integer',
                                        'example' => 'api/v1/currencies/1',
                                    ],
                                    'theme' => [
                                        'type' => 'string',
                                        'example' => 'default',
                                    ],
                                    'is_maintenance_on' => [
                                        'type' => 'integer',
                                        'example' => 0,
                                    ],
                                    'maintenance_mode_text' => [
                                        'type' => 'string',
                                        'example' => 'This site is under maintenance mode now, visit again after some time.',
                                    ],
                                    'allowed_ips' => [
                                        'type' => 'string',
                                        'example' => '144.127.233.247,206.176.12.230,165.173.215.218',
                                    ],
                                    'seo_title' => [
                                        'type' => 'string',
                                        'example' => 'NCR Region Store',
                                    ],
                                    'seo_description' => [
                                        'type' => 'string',
                                        'example' => 'NCR Region Description',
                                    ],
                                    'seo_keywords' => [
                                        'type' => 'string',
                                        'example' => 'NCR Region Keywords',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
            ),
        ),
        new Get(),
        new Put(),
        new Delete(),
        new Patch(),
    ]
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
