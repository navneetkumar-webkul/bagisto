<?php

namespace Webkul\ApiResources\Metadata\Property;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use Webkul\ApiResources\Models\Admin\Product\Product;

final class ProductPropertyMetadataFactory implements PropertyMetadataFactoryInterface
{
    private const PRODUCT_PROPERTIES = [
        'sku'                 => 'string',
        'type'                => 'string',
        'attributeFamily'     => 'int',
        'parent'              => 'string',
        'superAttributes'     => 'array',
        'channel'             => 'string',
        'locale'              => 'string',
        'name'                => 'string',
        'description'         => 'string',
        'shortDescription'    => 'string',
        'urlKey'              => 'string',
        'status'              => 'int',
        'visibleIndividually' => 'int',
        'price'               => 'float',
    ];

    public function __construct(private readonly PropertyMetadataFactoryInterface $decorated) {}

    public function create(string $resourceClass, string $property, array $options = []): ApiProperty
    {
        $propertyMetadata = $this->decorated->create($resourceClass, $property, $options);

        if ($resourceClass === Product::class && isset(self::PRODUCT_PROPERTIES[$property])) {
            // Create a new ApiProperty with readable and writable flags set
            return new ApiProperty(
                readable: true,
                writable: true,
                openapiContext: $propertyMetadata->getOpenapiContext(),
                description: $propertyMetadata->getDescription(),
            );
        }

        return $propertyMetadata;
    }
}
