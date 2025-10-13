<?php

declare(strict_types=1);

namespace Webkul\ApiResources\Support;

use ApiPlatform\State\Pagination\PaginatorInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Adapts a Bagisto product model to the API Resource format.
 */
class ProductDataMapper
{
    /**
     * Maps the product model data to the API resource.
     *
     * @param \Webkul\Product\Models\Product $product
     */
    public static function mapToResource($product, $resourceClass)
    {
        $resource = new $resourceClass();

        // Map base properties
        $resource->id = (int) $product->id;
        $resource->sku = $product->sku ?: null;
        $resource->type = $product->type ?: null;
        $resource->attribute_family_id = $product->attribute_family_id ? (string) $product->attribute_family_id : null;

        // Map attribute values
        foreach ($product->attribute_values as $attributeValue) {

            if (!$attributeValue) continue;


            switch ($attributeValue->attribute_id) {
                case 1: // SKU
                    if ($attributeValue->text_value) {
                        $resource->sku = $attributeValue->text_value;
                    }
                    break;
                default:

                    break;
            }
        }

        return $resource;
    }
}
