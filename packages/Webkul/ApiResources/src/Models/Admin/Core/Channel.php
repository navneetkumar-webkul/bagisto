<?php

namespace Webkul\ApiResources\Models\Admin\Core;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\ApiResources\Models\Admin\Category\Category;
use Webkul\ApiResources\Models\Admin\Inventory\InventorySource;
use Webkul\ApiResources\State\Admin\ChannelProcessor;

#[ApiResource(
    description: 'Channel resource',
    routePrefix: '/api/v1/admin',
    security: "is_granted('ROLE_ADMIN')",
    operations: [
        new Post(
            processor: ChannelProcessor::class,
            deserialize: false,
            name: 'create',
            openapi: new Model\Operation(
                summary: 'Store the channel',
                description: 'Channel creation endpoint',
                tags: ['Channel'],
                parameters: [],
                requestBody: new Model\RequestBody(
                    description: 'Channel creation payload',
                    required: true,
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                'type'       => 'object',
                                'properties' => [
                                    'code' => [
                                        'type'    => 'string',
                                        'example' => 'ncr',
                                    ],
                                    'name' => [
                                        'type'    => 'string',
                                        'example' => 'NCR Region',
                                    ],
                                    'description' => [
                                        'type'     => 'string',
                                        'nullable' => true,
                                        'example'  => null,
                                    ],
                                    'root_category_id' => [
                                        'type'    => 'integer',
                                        'example' => 1,
                                    ],
                                    'hostname' => [
                                        'type'    => 'string',
                                        'example' => 'example.com',
                                    ],
                                    'locales' => [
                                        'type'  => 'array',
                                        'items' => [
                                            'type'   => 'string',
                                            'format' => 'iri-reference',
                                        ],
                                        'example'     => ['/api/v1/admin/locales/1'],
                                        'description' => 'Array of IRI references to locales',
                                    ],
                                    'default_locale_id' => [
                                        'type'        => 'string',
                                        'format'      => 'iri-reference',
                                        'example'     => '/api/v1/admin/locales/1',
                                        'description' => 'IRI reference to default locale',
                                    ],
                                    'currencies' => [
                                        'type'  => 'array',
                                        'items' => [
                                            'type'   => 'string',
                                            'format' => 'iri-reference',
                                        ],
                                        'example'     => ['/api/v1/admin/currencies/1'],
                                        'description' => 'Array of IRI references to currencies',
                                    ],
                                    'base_currency_id' => [
                                        'type'        => 'string',
                                        'format'      => 'iri-reference',
                                        'example'     => '/api/v1/admin/currencies/1',
                                        'description' => 'IRI reference to base currency',
                                    ],
                                    'inventory_sources' => [
                                        'type'  => 'array',
                                        'items' => [
                                            'type'   => 'string',
                                            'format' => 'iri-reference',
                                        ],
                                        'example'     => ['/api/v1/admin/inventory_sources/1'],
                                        'description' => 'Array of IRI references to inventory sources',
                                    ],
                                    'theme' => [
                                        'type'    => 'string',
                                        'example' => 'default',
                                    ],
                                    'is_maintenance_on' => [
                                        'type'    => 'boolean',
                                        'example' => false,
                                    ],
                                    'maintenance_mode_text' => [
                                        'type'    => 'string',
                                        'example' => 'This site is under maintenance mode now, visit again after some time.',
                                    ],
                                    'allowed_ips' => [
                                        'type'    => 'string',
                                        'example' => '144.127.233.247,206.176.12.230,165.173.215.218',
                                    ],
                                    'seo_title' => [
                                        'type'    => 'string',
                                        'example' => 'NCR Region Store',
                                    ],
                                    'seo_description' => [
                                        'type'    => 'string',
                                        'example' => 'NCR Region Description',
                                    ],
                                    'seo_keywords' => [
                                        'type'    => 'string',
                                        'example' => 'NCR Region Keywords',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
            ),
        ),
    ]
)]
class Channel extends \Webkul\Core\Models\Channel
{
    #[ApiProperty(readableLink: true, writable: true)]
    public function getLocales()
    {
        return $this->locales;
    }

    #[ApiProperty(writable: true)]
    public function setLocales($locales)
    {
        if (is_array($locales)) {
            $localeIds = $this->extractIds($locales);
            $this->locales()->sync($localeIds);
        }
    }

    #[ApiProperty(readableLink: true, writable: true)]
    public function getCurrencies()
    {
        return $this->currencies;
    }

    #[ApiProperty(writable: true)]
    public function setCurrencies($currencies)
    {
        if (is_array($currencies)) {
            $currencyIds = $this->extractIds($currencies);
            $this->currencies()->sync($currencyIds);
        }
    }

    #[ApiProperty(readableLink: true, writable: true)]
    public function getInventory_sources()
    {
        return $this->inventory_sources;
    }

    #[ApiProperty(writable: true)]
    public function setInventory_sources($inventory_sources)
    {
        if (is_array($inventory_sources)) {
            $sourceIds = $this->extractIds($inventory_sources);
            $this->inventory_sources()->sync($sourceIds);
        }
    }

    #[ApiProperty(writable: true)]
    public function setDefault_locale_id($locale_id)
    {
        if ($locale_id) {
            $ids = $this->extractIds([$locale_id]);
            if (! empty($ids)) {
                $this->default_locale_id = reset($ids);
            }
        }
    }

    #[ApiProperty(writable: true)]
    public function setBase_currency_id($currency_id)
    {
        if ($currency_id) {
            $ids = $this->extractIds([$currency_id]);
            if (! empty($ids)) {
                $this->base_currency_id = reset($ids);
            }
        }
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

    /**
     * Extract numeric IDs from various formats (nested arrays, IRIs, objects, numbers)
     * Handles: nested arrays, IRIs (with/without leading /), objects with id property, numeric values
     *
     * @param  mixed  $data  The data to extract IDs from
     * @return array Array of numeric IDs
     */
    private function extractIds($data): array
    {
        $ids = [];

        if (! is_array($data)) {
            return $ids;
        }

        foreach ($data as $item) {
            // Recursively handle nested arrays
            if (is_array($item)) {
                $ids = array_merge($ids, $this->extractIds($item));

                continue;
            }

            // Handle objects with id property
            if (is_object($item) && isset($item->id)) {
                $ids[] = (int) $item->id;

                continue;
            }

            // Handle IRI strings like "api/v1/locales/1" or "/api/v1/locales/1"
            if (is_string($item)) {
                $normalized = trim($item, '/');
                $parts = explode('/', $normalized);
                if (! empty($parts)) {
                    $id = (int) end($parts);
                    if ($id > 0) {
                        $ids[] = $id;
                    }
                }

                continue;
            }

            // Handle numeric values
            if (is_numeric($item)) {
                $ids[] = (int) $item;
            }
        }

        return array_filter(array_unique($ids)); // Remove duplicates
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
