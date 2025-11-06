<?php

namespace Webkul\ApiResources\State\Admin;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DefaultProcessor implements ProcessorInterface
{
    private string $resourceType;

    private string $formRequestClass;

    private string $eventPrefix;

    public function __construct(
        private object $repository,
        ?string $resourceType = null,
        ?string $formRequestClass = null,
        ?string $eventPrefix = null
    ) {
        $this->resourceType = $resourceType ?? $this->resolveResourceType();
        $this->formRequestClass = $formRequestClass ?? $this->resolveFormRequestClass();
        $this->eventPrefix = $eventPrefix ?? $this->resolveEventPrefix();
    }

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

            if ($operation instanceof Patch) {
                return $this->handlePartialUpdate($context, $uriVariables);
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
        $this->validateRequest($context['request'], $inputData);
        $inputData = $this->normalizeRelationships($inputData);

        Event::dispatch("{$this->eventPrefix}.create.before");
        $resource = $this->repository->create($inputData);
        Event::dispatch("{$this->eventPrefix}.create.after", $resource);

        return new JsonResponse([
            'data'    => $resource,
            'message' => trans("api-resources.rest-api.admin.{$this->resourceType}.create-success"),
        ], 201);
    }

    private function handleUpdate(array $context, array $uriVariables): JsonResponse
    {
        $resourceId = $uriVariables['id'] ?? null;

        if (! $resourceId) {
            throw new \InvalidArgumentException('Resource ID is required for update operation');
        }

        $inputData = $context['request']->all();
        $this->validateRequest($context['request'], $inputData);
        $inputData = $this->normalizeRelationships($inputData);

        Event::dispatch("{$this->eventPrefix}.update.before", $resourceId);
        $resource = $this->repository->update($inputData, $resourceId);
        Event::dispatch("{$this->eventPrefix}.update.after", $resource);

        return new JsonResponse([
            'data'    => $resource,
            'message' => trans("api-resources.rest-api.admin.{$this->resourceType}.update-success"),
        ], 200);
    }

    private function handlePartialUpdate(array $context, array $uriVariables): JsonResponse
    {
        $resourceId = $uriVariables['id'] ?? null;

        if (! $resourceId) {
            throw new \InvalidArgumentException('Resource ID is required for partial update operation');
        }

        $inputData = $context['request']->all();
        $this->validatePartialRequest($context['request'], $inputData);
        $inputData = $this->normalizeRelationships($inputData);

        Event::dispatch("{$this->eventPrefix}.update.before", $resourceId);
        $resource = $this->repository->update($inputData, $resourceId);
        Event::dispatch("{$this->eventPrefix}.update.after", $resource);

        return new JsonResponse([
            'data'    => $resource,
            'message' => trans("api-resources.rest-api.admin.{$this->resourceType}.update-success"),
        ], 200);
    }

    private function handleDelete(array $uriVariables): JsonResponse
    {
        $resourceId = $uriVariables['id'] ?? null;

        if (! $resourceId) {
            throw new \InvalidArgumentException('Resource ID is required for delete operation');
        }

        Event::dispatch("{$this->eventPrefix}.delete.before", $resourceId);
        $this->repository->delete($resourceId);
        Event::dispatch("{$this->eventPrefix}.delete.after", $resourceId);

        return new JsonResponse([
            'message' => trans("api-resources.rest-api.admin.{$this->resourceType}.delete-success"),
        ], 200);
    }

    private function validateRequest(\Illuminate\Http\Request $request, array $data): void
    {
        if (! class_exists($this->formRequestClass)) {
            return;
        }

        $factory = app('validator');
        $formRequest = new $this->formRequestClass;

        $validator = $factory->make(
            $data,
            $formRequest->rules(),
            $formRequest->messages()
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private function validatePartialRequest(\Illuminate\Http\Request $request, array $data): void
    {
        if (! class_exists($this->formRequestClass)) {
            return;
        }

        $formRequest = new $this->formRequestClass;
        $baseRules = $formRequest->rules();
        $patchRules = [];

        foreach ($baseRules as $field => $rule) {
            if (isset($data[$field])) {
                $patchRules[$field] = $rule;
            }
        }

        if (empty($patchRules)) {
            return;
        }

        $factory = app('validator');
        $validator = $factory->make($data, $patchRules, $formRequest->messages());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private function normalizeRelationships(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = array_map(fn ($item) => is_array($item) ? $item : $this->extractSingleId($item), $value);
            } elseif (! is_null($value) && (is_numeric($value) || is_string($value))) {
                $data[$key] = $this->extractSingleId($value);
            }
        }

        return $data;
    }

    private function extractSingleId(mixed $value): mixed
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

        return $value;
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

    private function handleException(\Exception $e): JsonResponse
    {
        Log::error("DefaultProcessor: Failed to process {$this->resourceType} operation", [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
        ]);

        throw $e;
    }

    private function resolveResourceType(): string
    {
        $class = get_class($this->repository);

        if (preg_match('/(\w+)Repository/', $class, $matches)) {
            return strtolower($matches[1]);
        }

        return 'resource';
    }

    private function resolveFormRequestClass(): string
    {
        $resourceType = $this->resourceType;
        $formatted = implode('', array_map('ucfirst', explode('_', $resourceType)));

        return "Webkul\\ApiResources\\Http\\Requests\\Admin\\{$formatted}FormRequest";
    }

    private function resolveEventPrefix(): string
    {
        $resourceType = $this->resourceType;

        $mapping = [
            'product'   => 'catalog.product',
            'channel'   => 'core.channel',
            'attribute' => 'catalog.attribute',
            'category'  => 'catalog.category',
        ];

        return $mapping[$resourceType] ?? "{$resourceType}";
    }
}
