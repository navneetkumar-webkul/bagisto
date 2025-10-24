<?php

namespace Webkul\ApiResources\GraphQL;

use GraphQL\Error\Error;
use Illuminate\Validation\ValidationException;

class ErrorFormatter
{
    public function __invoke(Error $error)
    {
        $previous = $error->getPrevious();

        // Handle Laravel validation exceptions
        if ($previous instanceof ValidationException) {
            return [
                'message' => 'Validation error',
                'extensions' => [
                    'category' => 'validation',
                    'validation' => $previous->errors(),
                ],
            ];
        }

        // Handle all other exceptions
        return [
            'message' => $error->getMessage() ?: 'Unexpected error occurred.',
            'extensions' => [
                'category' => 'internal',
            ],
        ];
    }
}
