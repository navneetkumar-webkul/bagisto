<?php

namespace Webkul\ApiResources\State\Admin;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Illuminate\Support\Facades\Event;
use Webkul\ApiResources\Models\Admin\Product\Product;

/**
 * @implements ProcessorInterface<Product, Product|void>
 */
final class ProductProcessor implements ProcessorInterface
{
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    )
    {
        Event::dispatch('catalog.product.create.before');

        $productRepository = app(\Webkul\Product\Repositories\ProductRepository::class);

        $product = $productRepository->create($context['request']->only([
            'type',
            'attribute_family_id',
            'sku',
            'super_attributes',
            'family',
        ]));

        Event::dispatch('catalog.product.create.after', $product);

        return response([
            'data'    => $product,
            'message' => trans('api-resources.rest-api.admin.catalog.products.create-success'),
        ]);
    }
}
