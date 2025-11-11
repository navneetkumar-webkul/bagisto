<?php

namespace Webkul\ApiResources\Dto;

use ApiPlatform\Metadata\ApiProperty;

/**
 * DTO for creating a Product via GraphQL
 */
class CreateProductInput
{
    #[ApiProperty(description: 'Product SKU update')]
    public ?string $sku = null;

    #[ApiProperty(description: 'Product type (simple, configurable, etc.)')]
    public ?string $type = 'simple';

    #[ApiProperty(description: 'Attribute family ID')]
    public ?int $attributeFamily = null;

    #[ApiProperty(description: 'Parent product ID')]
    public ?string $parent = null;

    #[ApiProperty(description: 'Super attributes for configurable products')]
    public ?array $superAttributes = null;

    #[ApiProperty(description: 'Channel code')]
    public ?string $channel = null;

    #[ApiProperty(description: 'Locale code')]
    public ?string $locale = null;

    #[ApiProperty(description: 'Product name')]
    public ?string $name = null;

    #[ApiProperty(description: 'Product description')]
    public ?string $description = null;

    #[ApiProperty(description: 'Product short description')]
    public ?string $shortDescription = null;

    #[ApiProperty(description: 'Product URL key')]
    public ?string $urlKey = null;

    #[ApiProperty(description: 'Product status')]
    public ?int $status = 1;

    #[ApiProperty(description: 'Product visibility')]
    public ?int $visibleIndividually = 1;

    #[ApiProperty(description: 'Product price')]
    public ?float $price = null;

    #[ApiProperty(description: 'All product attributes')]
    public ?array $allAttributes = null;
}
