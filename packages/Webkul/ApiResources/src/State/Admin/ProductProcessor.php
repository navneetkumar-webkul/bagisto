<?php

namespace Webkul\ApiResources\State\Admin;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Webkul\ApiResources\Http\Requests\Admin\ProductFormRequest;
use Webkul\ApiResources\Models\Admin\Product\Product;
use Webkul\Product\Repositories\ProductRepository;

/**
 * @implements ProcessorInterface<Product, Product|void>
 */
final class ProductProcessor implements ProcessorInterface
{
    /**
     * @return JsonResponse
     *
     * @throws ValidationException
     * @throws \Exception
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): mixed {

        try {
            if ($operation instanceof Post) {
                return $this->handleCreate($context);
            }

            if ($operation instanceof Put) {
                return $this->handleUpdate($context, $uriVariables);
            }

            if ($operation instanceof Delete) {
                return $this->handleDelete($uriVariables);
            }

            throw new \RuntimeException('Unsupported operation type: '.get_class($operation));
        } catch (ValidationException $e) {
            return $this->handleValidationException($e);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    private function handleCreate(array $context): JsonResponse
    {
        $inputData = $context['request']->all();

        $imagesData = null;
        if (isset($inputData['images']) && is_array($inputData['images'])) {
            $imagesData = $inputData['images'];

            unset($inputData['images']);
        }

        $this->validateRequest($context['request'], $inputData);

        $inputData = $this->normalizeRelationships($inputData);

        if ($imagesData !== null) {
            $inputData['images'] = $this->processImages($imagesData);
        }

        Event::dispatch('catalog.product.create.before');

        $product = $this->getRepository()->create($inputData);

        Event::dispatch('catalog.product.create.after', $product);

        return new JsonResponse([
            'data'    => $product,
            'message' => trans('api-resources.rest-api.admin.catalog.products.create-success'),
        ], 201);
    }

    private function handleUpdate(array $context, array $uriVariables): JsonResponse
    {
        $productId = $uriVariables['id'] ?? null;

        if (! $productId) {
            throw new \InvalidArgumentException('Product ID is required for update operation');
        }

        $inputData = $context['request']->all();

        $imagesData = null;
        if (isset($inputData['images']) && is_array($inputData['images'])) {
            $imagesData = $inputData['images'];

            unset($inputData['images']);
        }

        $this->updateValidateRequest($context['request'], $inputData);

        $inputData = $this->normalizeRelationships($inputData);

        if ($imagesData !== null) {
            $inputData['images'] = $this->processImages($imagesData);
        }

        Event::dispatch('catalog.product.update.before', $productId);

        $product = $this->getRepository()->update($inputData, $productId);

        Event::dispatch('catalog.product.update.after', $product);

        return new JsonResponse([
            'data'    => $product,
            'message' => trans('api-resources.rest-api.admin.catalog.products.update-success'),
        ], 200);
    }

    private function handleDelete(array $uriVariables): JsonResponse
    {
        $productId = $uriVariables['id'] ?? null;

        if (! $productId) {
            throw new \InvalidArgumentException('Product ID is required for delete operation');
        }

        Event::dispatch('catalog.product.delete.before', $productId);

        $this->getRepository()->delete($productId);

        Event::dispatch('catalog.product.delete.after', $productId);

        return new JsonResponse([
            'message' => trans('api-resources.rest-api.admin.catalog.products.delete-success'),
        ], 200);
    }

    /**
     * @throws ValidationException
     */
    private function validateRequest(\Illuminate\Http\Request $request, array $data): void
    {
        $factory = app('validator');

        $validator = $factory->make(
            $data,
            (new ProductFormRequest)->rules(),
            (new ProductFormRequest)->messages()
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private function updateValidateRequest(\Illuminate\Http\Request $request, array $data): void
    {
        // TODO : Implement updateValidateRequest() method.
    }

    /**
     * Summary of normalizeRelationships
     */
    private function normalizeRelationships(array $data): array
    {
        if (isset($data['attribute_family_id'])) {
            $data['attribute_family_id'] = $this->extractSingleId($data['attribute_family_id']);
        }

        if (isset($data['super_attributes']) && is_array($data['super_attributes'])) {
            $data['super_attributes'] = array_map(function ($item) {
                return is_array($item) ? array_map(fn ($id) => $this->extractSingleId($id), $item) : $this->extractSingleId($item);
            }, $data['super_attributes']);
        }

        if (isset($data['images']) && is_array($data['images'])) {
            $data['images'] = $this->processImages($data['images']);
        }

        return $data;
    }

    /**
     * Process images from API payload (base64 or file references)
     */
    private function processImages(array $imagesData): array
    {
        $processedImages = [];

        if (isset($imagesData['files']) && is_array($imagesData['files'])) {
            foreach ($imagesData['files'] as $index => $imageData) {
                $position = $imagesData['position'][$index] ?? ($index + 1);

                if (is_string($imageData) && strpos($imageData, 'data:image') === 0) {
                    $processedImages[] = [
                        'file'     => $imageData,
                        'position' => $position,
                    ];
                }
            }
        }

        return $processedImages;
    }

    private function extractSingleId(mixed $value): ?int
    {
        if (! $value) {
            return null;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        if (is_string($value)) {
            $normalized = trim($value, '/');
            $parts = explode('/', $normalized);

            if (! empty($parts)) {
                $id = (int) end($parts);

                return $id > 0 ? $id : null;
            }
        }

        return null;
    }

    private function handleValidationException(ValidationException $e): JsonResponse
    {
        return new JsonResponse([
            '@context'          => '/api/contexts/ConstraintViolationList',
            '@type'             => 'ConstraintViolationList',
            'hydra:title'       => 'An error occurred',
            'hydra:description' => 'Validation failed.',
            'violations'        => collect($e->errors())->map(function ($messages, $field) {
                return [
                    'propertyPath' => $field,
                    'message'      => is_array($messages) ? implode(', ', $messages) : $messages,
                ];
            })->values(),
        ], 422);
    }

    /**
     * @throws \Exception
     */
    private function handleException(\Exception $e): JsonResponse
    {
        Log::error('ProductProcessor: Failed to process product operation', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
        ]);

        throw $e;
    }

    private function getRepository(): ProductRepository
    {
        return app(ProductRepository::class);
    }
}
