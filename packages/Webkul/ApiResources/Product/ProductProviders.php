<?php

declare(strict_types=1);

namespace Webkul\ApiResources\Product;

use ApiPlatform\Metadata\Operation;
use Illuminate\Http\Request;
use Webkul\Product\Models\Product as ProductModel;
use Webkul\Product\Models\ProductFlat as ProductFlatModel;

final class ProductProviders
{
    /**
     * Provide a collection of Product models.
     * Called by ApiPlatform's CallableProvider when operation provider is set to this callable.
     *
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     *
     * @return \ApiPlatform\State\Pagination\PaginatorInterface|\Illuminate\Database\Eloquent\Collection|array|null
     */
    public static function provideProductCollection(Operation $operation, array $uriVariables = [], array $context = [])
    {
        $page = $context['filters']['page'] ?? 1;
        $itemsPerPage = $context['filters']['itemsPerPage'] ?? 30;

        $paginator = ProductModel::query()
            ->with([
                'attribute_family',
                'attribute_values' => function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->whereNull('channel')
                                ->whereNull('locale');
                    });
                }
            ])
            ->select([
                'id', 'sku', 'type', 'attribute_family_id', 'parent_id',
                'additional', 'created_at', 'updated_at'
            ])
            ->paginate(
                perPage: $itemsPerPage,
                page: $page
            );

        return new \Webkul\ApiResources\Support\EloquentPaginator($paginator);
    }

    /**
     * Provide a single Product item by identifier.
     */
    public static function provideProductItem(Operation $operation, array $uriVariables = [], array $context = [])
    {
        $id = $uriVariables['id'] ?? ($context['id'] ?? null);
        if (null === $id) {
            return null;
        }

        $product = ProductModel::query()
            ->with(['attribute_values' => function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereNull('channel')
                            ->whereNull('locale');
                });
            }])
            ->find($id);

        return $product ? \Webkul\ApiResources\Support\ProductDataMapper::mapToResource($product, ProductResource::class) : null;
    }

    /**
     * Persist a Product item (create or update).
     */
    public static function persistProduct($data, Operation $operation, array $uriVariables = [], array $context = [])
    {

        $id = $uriVariables['id'] ?? null;

        if ($id) {
            $product = ProductModel::findOrFail($id);
            $product->update(self::prepareProductData($data));
        } else {
            $product = ProductModel::create(self::prepareProductData($data));
        }

        // Handle attribute values
        foreach (self::prepareAttributeData($data) as $attributeId => $value) {
            $product->attribute_values()->updateOrCreate(
                ['attribute_id' => $attributeId],
                ['text_value' => $value]
            );
        }

        return \Webkul\ApiResources\Support\ProductDataMapper::mapToResource($product, ProductResource::class);
    }

    /**
     * Remove a Product item.
     */
    public static function removeProduct($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $id = $uriVariables['id'] ?? null;
        if ($id) {
            $product = ProductModel::findOrFail($id);
            $product->delete();
        }
        return null;
    }

    /**
     * Prepare product data for persistence.
     */
    private static function prepareProductData($data): array
    {
        return [
            'type' => $data->type ?? 'simple',
            'sku' => $data->sku,
            'attribute_family_id' => $data->attributeFamilyId,
            // Add other base fields as needed
        ];
    }

    /**
     * Prepare attribute data for persistence.
     */
    private static function prepareAttributeData($data): array
    {
        $attributes = [];

        // Map resource properties to attribute IDs
        $mapping = [
            'name' => 2,
            'urlKey' => 3,
            'taxCategoryId' => 4,
            // Add other attribute mappings as needed
        ];

        foreach ($mapping as $property => $attributeId) {
            if (isset($data->$property)) {
                $attributes[$attributeId] = $data->$property;
            }
        }

        return $attributes;
    }

    /**
     * Provide a collection of ProductFlat models.
     */
    public static function provideProductFlatCollection(Operation $operation, array $uriVariables = [], array $context = [])
    {
        $page = $context['filters']['page'] ?? 1;
        $itemsPerPage = $context['filters']['itemsPerPage'] ?? 30;

        $paginator = ProductFlatModel::query()->paginate(
            perPage: $itemsPerPage,
            page: $page
        );

        return new \Webkul\ApiResources\Support\EloquentPaginator($paginator);
    }

    /**
     * Provide a single ProductFlat item by identifier.
     */
    public static function provideProductFlatItem(Operation $operation, array $uriVariables = [], array $context = [])
    {
        $id = $uriVariables['id'] ?? ($context['id'] ?? null);
        if (null === $id) {
            return null;
        }

        return ProductFlatModel::query()->find($id);
    }
}
