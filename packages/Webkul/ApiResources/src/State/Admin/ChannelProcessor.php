<?php

namespace Webkul\ApiResources\State\Admin;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Webkul\ApiResources\Http\Requests\Admin\CreateChannelRequest;
use Webkul\Core\Repositories\ChannelRepository;

class ChannelProcessor implements ProcessorInterface
{
    public function __construct(
        private ChannelRepository $channelRepository
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): mixed {
        try {
            // Extract input data from request
            $inputData = $context['request']->all();

            // Validate the request data
            $this->validateRequest($context['request'], $inputData);

            // Normalize the relationship fields to extract IDs
            $inputData = $this->normalizeRelationships($inputData);

            // Dispatch before event
            Event::dispatch('core.channel.create.before');

            // Delegate all business logic to the repository
            $channel = $this->channelRepository->create($inputData);

            // Dispatch after event
            Event::dispatch('core.channel.create.after', $channel);

            // Return a JSON response with 201 Created status
            return new JsonResponse([
                'id'                => $channel->id,
                'code'              => $channel->code,
                'root_category_id'  => $channel->root_category_id,
                'default_locale_id' => $channel->default_locale_id,
                'base_currency_id'  => $channel->base_currency_id,
                'hostname'          => $channel->hostname,
                'theme'             => $channel->theme,
                'is_maintenance_on' => $channel->is_maintenance_on,
                'created_at'        => $channel->created_at?->toIso8601String(),
            ], 201);
        } catch (ValidationException $e) {
            // Return validation errors with 422 Unprocessable Entity status
            return new JsonResponse([
                '@context'          => '/api/v1/contexts/ConstraintViolationList',
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
        } catch (\Exception $e) {
            \Log::error('ChannelProcessor: Failed to create channel', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Validate the request data using FormRequest
     */
    private function validateRequest($request, array $data): void
    {
        $factory = app('validator');

        $validator = $factory->make(
            $data,
            (new CreateChannelRequest)->rules(),
            (new CreateChannelRequest)->messages()
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Normalize relationship fields to extract IDs
     */
    private function normalizeRelationships(array $data): array
    {
        $channel = new \Webkul\ApiResources\Models\Admin\Core\Channel;
        $reflection = new \ReflectionClass(\Webkul\ApiResources\Models\Admin\Core\Channel::class);
        $extractIdsMethod = $reflection->getMethod('extractIds');
        $extractIdsMethod->setAccessible(true);

        // Handle array relationships
        $data['locales'] = $extractIdsMethod->invoke($channel, $data['locales'] ?? []);
        $data['currencies'] = $extractIdsMethod->invoke($channel, $data['currencies'] ?? []);
        $data['inventory_sources'] = $extractIdsMethod->invoke($channel, $data['inventory_sources'] ?? []);

        // Handle single ID fields
        if (isset($data['default_locale_id'])) {
            $data['default_locale_id'] = $this->extractSingleId($data['default_locale_id']);
        }
        if (isset($data['base_currency_id'])) {
            $data['base_currency_id'] = $this->extractSingleId($data['base_currency_id']);
        }

        return $data;
    }

    /**
     * Extract a single ID from various formats
     */
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
}
